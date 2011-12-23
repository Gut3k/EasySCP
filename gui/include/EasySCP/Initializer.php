<?php
/**
 * ispCP ω (OMEGA) a Virtual Hosting Control System
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is "ispCP - ISP Control Panel".
 *
 * The Initial Developer of the Original Code is ispCP Team.
 * Portions created by Initial Developer are Copyright (C) 2006-2011 by
 * isp Control Panel. All Rights Reserved.
 *
 * @category    ispCP
 * @package     EasySCP_Initializer
 * @copyright   2006-2011 by ispCP | http://isp-control.net
 * @author      Laurent Declercq <laurent.declercq@ispcp.net>
 * @version     SVN: $Id: Initializer.php 3873 2011-07-10 07:34:22Z benedikt $
 * @link        http://isp-control.net ispCP Home Site
 * @license     http://www.mozilla.org/MPL/ MPL 1.1
 */

/**
 * ispCP Initializer class
 *
 * The initializer is responsible for processing the EasySCP configuration,
 * such as setting the include_path, initializing logging, database and
 * more.
 *
 * @category    EasySCP
 * @package     EasySCP_Initializer
 * @author      Laurent declercq <laurent.declercq@ispcp.net>
 * @since       1.0.7
 * @version     1.1.3
 */
class EasySCP_Initializer {

	/**
	 * EasySCP_Config_Handler instance used by this class
	 *
	 * @var EasySCP_Config_Handler_File
	 */
	private $_config;

	/**
	 * Initialization status
	 *
	 * @staticvar boolean
	 */
	private static $_initialized = false;

	/**
	 * PHP Version required by EasySCP
	 *
	 * @staticvar String
	 */
	private static $_phpVersion = '5.1.4';

	/**
	 * Runs the initializer
	 *
	 * By default, this will invoke the {@link _processAll}  or
	 * {@link _processCLI} methods, which simply executes all of the
	 * initialization routines for execution context. Alternately, you can
	 * specify explicitly which initialization methods you want:
	 *
	 * <i>Usage example:</i>
	 * <code>
	 *	EasySCP_Initializer::run('_setIncludePath')
	 * </code>
	 *
	 * This is useful if you only want the include_path path initialized,
	 * without incurring the overhead of completely loading the entire
	 * environment.
	 *
	 * @throws EasySCP_Exception
	 * @param string|EasySCP_Config_Handler_File $command Initializer method to be
	 *	executed or an EasySCP_Config_Handler_File object
	 * @param EasySCP_Config_Handler_File $config Optional EasySCP_Config_Handler_File
	 * object
	 * @return EasySCP_Initializer The EasySCP_Initializer instance
	 */
	public static function run($command = '_processAll',
		EasySCP_Config_Handler_File $config = null) {

		if(!self::$_initialized) {

			if($command instanceof EasySCP_Config_Handler_File) {
				$config = $command;
				$command = '_processAll';
			}

			// Overrides _processAll command for CLI interface
			if($command == '_processAll' && PHP_SAPI == 'cli') {
				$command = '_processCLI';
			}

			$initializer = new self(
				is_object($config) ? $config : new EasySCP_Config_Handler_File()
			);

			$initializer->$command();

		} else {
			throw new EasySCP_Exception(
				'Error: EasySCP is already fully initialized!'
			);
		}

		return $initializer;
	}

	/**
	 * Singleton
	 *
	 * Create a new Initializer instance that references the given
	 * {@link EasySCP_Config_Handler_File} instance
	 *
	 * @param EasySCP_Config_Handler_File $config EasySCP_Config_Handler_File object
	 * @return void
	 */
	protected function __construct(EasySCP_Config_Handler $config) {
		// Register config object in registry for further usage
		$this->_config = EasySCP_Registry::set('Config', $config);
	}

	/**
	 * Singleton
	 *
	 * Make clone unavailable
	 */
	protected function __clone() {}

	/**
	 * Executes all of the available initialization routines
	 *
	 * @return void
	 */
	 protected function _processAll() {

		// Set display errors
		$this->_setDisplayErrors();

		// Check php version and availability of the Php Standard Library
		$this->_checkPhp();

		// Set additionally EasySCP_Exception_Writer observers
		$this->_setExceptionWriters();

		// Include path
		$this->_setIncludePath();

		// Establish the connection to the database
		$this->_initializeDatabase();

		// Se encoding (Both PHP and database)
		$this->_setEncoding();

		// Set timezone
		$this->_setTimezone();

		// Load all the configuration parameters from the database and merge
		// it to our basis configuration object
		$this->_processConfiguration();

		// Initialize output buffering
		$this->_initializeOutputBuffering();

		// Create or restore the session
		$this->_initializeSession();

		// Initialize internationalization libraries
		// $this->_initializeI18n();

		// Initialize logger
		// $this->_initializeLogger();

		// Not yet fully integrated - (testing in progress)
		// $this->loadPlugins();

      	// Trigger the 'OnAfterInitialize' action hook
		// (will be activated later)
		// EasySCP_Registry::get('Hook')->OnAfterInitialize();

		// Run after initialize callbacks (will be changed later)
		$this->_afterInitialize();

		 self::$_initialized = true;
	}

	/**
	 * Executes all of the available initialization routines for CLI interface
	 *
	 * @return void
	 */
	protected function _processCLI() {

		// Check php version and availability of the Php Standard Library
		$this->_checkPhp();

		// Include path
		$this->_setIncludePath();

		// Establish the connection to the database
		$this->_initializeDatabase();

		// Se encoding (Both PHP and database)
		$this->_setEncoding();

		// Load all the configuration parameters from the database and merge
		// it to our basis configuration object
		$this->_processConfiguration();

		self::$_initialized = true;
	}

	/**
	 * Sets the PHP display_errors parameter
	 *
	 * @return void
	 */
	protected function _setDisplayErrors() {

		if($this->_config->DEBUG) {
			ini_set('display_errors', 1);
		} else {
			ini_set('display_errors', 0);
		}
	}

	/**
	 * Check for PHP version and Standard PHP library availability
	 *
	 * EasySCP uses interfaces and classes that come from the Standard Php library
	 * under PHP version 5.1.4. This methods ensures that the PHP version used
	 * is more recent or equal to the PHP version 5.1.4 and that the SPL is
	 * loaded.
	 *
	 * <b>Note:</b> EasySCP requires PHP 5.1.4 or later because some SPL
	 * interfaces were not stable in earlier versions of PHP.
	 *
	 * @throws EasySCP_Exception
	 * @return void
	 * @todo Check SPL part (EasySCP_Exception_Handler use SPL)
	 */
	protected function _checkPhp() {

		// MAJOR . MINOR . TINY
		$phpVersion = substr(phpversion(), 0, 5);

		if(!version_compare($phpVersion, self::$_phpVersion, '>=')) {
			$errMsg = sprintf(
				'Error: PHP version is %s. Version %s or later is required!',
				$phpVersion,
				self::$_phpVersion
			);

		// We will use SPL interfaces like SplObserver, SplSubject
		// Note: Both ArrayAccess and Iterator interfaces are part of PHP core,
		// so, we can do the checking here without any problem.
		} elseif(version_compare($phpVersion, '5.3.0', '<') && !extension_loaded('SPL')) {
			$errMsg =
				'Error: Standard PHP Library (SPL) was not detected!\n' .
				'See http://php.net/manual/en/book.spl.php for more information!';
		} else {
			return;
		}

		throw new EasySCP_Exception($errMsg);
	}

	/**
	 * Sets additional writers or exception handler
	 *
	 * @return void
	 * @todo Automatic detection of new writers based on the namespace
	 */
	protected function _setExceptionWriters() {

		// Get a reference to the EasySCP_Exception_Handler object
		$exceptionHandler = EasySCP_Registry::get('exceptionHandler');

		$writerObservers = explode(',', $this->_config->GUI_EXCEPTION_WRITERS);
		$writerObservers = array_map('trim', $writerObservers);
		$writerObservers = array_map('strtolower', $writerObservers);

		/*
		if(in_array('file', $writerObservers)) {
			// Writer not Yet Implemented
			$exceptionHandler->attach(
				new EasySCP_Exception_Writer_File(
					'path_to_logfile'
				)
			);
		}
		*/

		if(in_array('mail', $writerObservers)) {
			$admin_email = $this->_config->DEFAULT_ADMIN_ADDRESS;

			if($admin_email != '') {

				$exceptionHandler->attach(
					new EasySCP_Exception_Writer_Mail($admin_email)
				);
			}
		}

		/*
		if(in_array('database', $writerObservers)) {
			$exceptionHandler->attach(
				new EasySCP_Exception_Writer_Db(EasySCP_Registry::get('Pdo'))
			);
		}
		*/
	} // end _setExceptionWriters()

	/**
	 * Sets the include path
	 *
	 * Sets the PHP include_path. Duplicates entries are removed.
	 *
	 * <b>Note:</b> Will be completed later with other paths (MVC switching).
	 *
	 * @return void
	 */
	protected function _setIncludePath() {

		$ps = PATH_SEPARATOR;

		// Get the current PHP include path string and transform it in array
		$include_path = explode(
			$ps, str_replace('.' . $ps, '', DEFAULT_INCLUDE_PATH)
		);

		// Adds the EasySCP gui/include ABSPATH to the PHP include_path
		array_unshift($include_path, dirname(dirname(__FILE__)));

		// Transform array of path to string and set the new PHP include_path
		set_include_path('.' . $ps .implode($ps, array_unique($include_path)));
	}

	/**
	 * Create/restore the session
	 *
	 * @return void
	 */
	protected function _initializeSession() {

        if (!is_writable($this->_config->GUI_ROOT_DIR . '/phptmp')) {
            throw new EasySCP_Exception('The directory '. $this->_config->GUI_ROOT_DIR . '/phptmp must be writable.');
        }

		session_name('EasySCP');

		if (!isset($_SESSION)) {
			session_start();
		}
	}

	/**
	 * Establishes the connection to the database
	 *
	 * This methods establishes the default connection to the database by using
	 * configuration parameters that come from the basis configuration object
	 * and then, register the {@link EasySCP_Database} instance in the
	 * {@link EasySCP_Registry} for shared access.
	 *
	 * A PDO instance is also registered in the registry for shared access.
	 *
	 * @throws EasySCP_Exception
	 * @return void
	 * @todo Remove global variable
	 */
	protected function _initializeDatabase() {

		try {

			$connection = EasySCP_Database::connect(
				$this->_config->DATABASE_USER,
				decrypt_db_password($this->_config->DATABASE_PASSWORD),
				$this->_config->DATABASE_TYPE,
				$this->_config->DATABASE_HOST,
				$this->_config->DATABASE_NAME
			);

		} catch(PDOException $e) {

			throw new EasySCP_Exception_Database(
				'Error: Unable to establish connection to the database! '.
				'SQL returned: ' . $e->getMessage()
			);
		}

		// Register both Database and PDO instances for shared access
		EasySCP_Registry::set('Db', $connection);
		EasySCP_Registry::set('Pdo', EasySCP_Database::getRawInstance());

		// @todo remove the Global
		$GLOBALS['sql'] =  EasySCP_Registry::get('Db');
	}

	/**
	 * Sets encoding
	 *
	 * This methods set encoding for both communication database and PHP.
	 *
	 * @throws EasySCP_Exception
	 * @return void
	 */
	protected function _setEncoding() {

		// Always send the following header:
		// Content-type: text/html; charset=UTF-8'
		// Note: This header can be overrided by calling the header() function
		ini_set('default_charset', 'UTF-8');

		// Switch optionally to utf8 based communication with the database
		if (isset($this->_config->DATABASE_UTF8) &&
			$this->_config->DATABASE_UTF8 == 'yes') {

			$db = EasySCP_Registry::get('Db');

			if(!$db->execute('SET NAMES `utf8`;')) {
				throw new EasySCP_Exception(
					'Error: Unable to set charset for database communication! ' .
					'SQL returned: ' . $db->errorMsg()
				);
			}
		}
	}

	/**
	 * Sets timezone
	 *
	 * This method ensures that the timezone is set to avoid any error with PHP
	 * versions equal or later than version 5.3.x
	 *
	 * This method acts by checking the `date.timezone` value, and sets it to
	 * the value from the EasySCP PHP_TIMEZONE parameter if exists and if it not
	 * empty or to 'UTC' otherwise. If the timezone identifier is invalid, an
	 * {@link EasySCP_Exception} exception is raised.
	 *
	 * @throws EasySCP_Exception
	 * @return void
	 */
	protected function _setTimezone() {

		// Timezone is not set in the php.ini file ?
		if(ini_get('date.timezone') == '') {

			$timezone = (isset($this->_config->PHP_TIMEZONE) &&
				$this->_config->PHP_TIMEZONE != '')
					? $this->_config->PHP_TIMEZONE : 'UTC';

			if(!date_default_timezone_set($timezone)) {
				throw new EasySCP_Exception(
					'Error: Invalid timezone identifier set in your ' .
					'easyscp.conf file! Please fix this error and re-run the ' .
					'easyscp-update script to fix the value in all your ' .
					'customers\' php.ini files. The current list of valid ' .
					'identifiers is available at the <a href="http://www.php.net/ ' .
					'manual/en/timezones.php" target="_blank">PHP Homepage</a> .'
				);
			}
		}
	}

	/**
	 * Load configuration parameters from the database
	 *
	 * This function retrieves all the parameters from the database and merge
	 * them with the basis configuration object.
	 *
	 * Parameters that exists in the basis configuration object will be replaced
	 * by them that come from the database. The basis configuration object
	 * contains parameters that come from the easyscp.conf configuration file or
	 * any parameter defined in the {@link environment.php} file.
	 *
	 * @return void
	 */
	protected function _processConfiguration() {

		// We get an EasySCP_Config_Handler_Db object
		$dbConfig = new EasySCP_Config_Handler_Db(EasySCP_Registry::get('Pdo'));

		// Now, we can override our basis configuration object with parameter
		// that come from the database
		$this->_config->replaceWith($dbConfig);

		// Finally, we register the EasySCP_Config_Handler_Db for shared access
		EasySCP_Registry::set('Db_Config', $dbConfig);
	}

	/**
	 * Initialize the PHP output buffering / spGzip filter
	 *
	 * <b>Note:</b> The hight level (like 8, 9) for compression are not
	 * recommended for performances reasons. The obtained gain with these levels
	 * is very small compared to the intermediate level like 6,7.
	 *
	 * @return void
	 */
	protected function _initializeOutputBuffering() {

		if(isset($this->_config->COMPRESS_OUTPUT) && $this->_config->COMPRESS_OUTPUT) {
			// Create a new filter that will be applyed on the buffer output
			$filter = EasySCP_Registry::set(
				'bufferFilter',
				new EasySCP_Filter_Compress_Gzip(
					EasySCP_Filter_Compress_Gzip::FILTER_BUFFER
				)
			);

			// Show compression information in HTML comment ?
			if(!$this->_config->SHOW_COMPRESSION_SIZE) {
				$filter->compressionInformation = false;
			}

			// Start the buffer and attach the filter to him
			ob_start(array($filter, EasySCP_Filter_Compress_Gzip::CALLBACK_NAME));
		}
	}

	/**
	 * Initialize translation libraries
	 *
	 * <b>Note:</b> Not Yet Implemented
	 *
	 * @return void
	 * @todo Ask Jochen for the new i18n library and initilization processing
	 */
	protected function _initializeI18n() {}

	/**
	 * Initialize logger
	 *
	 * <b>Note:</b> Not used at this moment
	 *
	 * @return void
	 */
	protected function _initializeLogger() {}

	/**
	 * Not yet implemented
	 *
	 * Not used at this moment because we have only one theme.
	 *
	 * @return void
	 */
	protected function _initializeLayout() {}

	/**
	 * Load all plugins
	 *
	 * This method loads all the active plugins. Only plugins for the current
	 * execution context are loaded.
	 *
	 * <b>Note:</b> Not used at this moment
	 *
	 * @return void
	 */
	protected function _loadPlugins() {

		// Load all the available plugins for the current execution context
		// EasySCP_Plugin_Helpers::getPlugins();

		// Register an EasySCP_Plugin_ActionsHooks for shared access
		// EasySCP_Registry::set('Hook', EasySCP_Plugin_ActionsHooks::getInstance());
	}

	/**
	 * Fires the afterInitialize callbacks
	 *
	 * @return void
	 */
	protected function _afterInitialize() {

		$callbacks = $this->_config->getAfterInitialize();

		if(!empty($callbacks)) {
			foreach($callbacks as $callback) {
				call_user_func_array(
					$callback['callback'], $callback['parameters']
				);
			}
		}
	}
}
