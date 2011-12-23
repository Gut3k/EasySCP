<?php
/**
 * EasySCP a Virtual Hosting Control Panel
 *
 * @copyright 	2006-2010 by ispCP | http://isp-control.net
 * @copyright 	2010-2011 by Easy Server Control Panel - http://www.easyscp.net
 * @version 	SVN: $Id$
 * @link 		http://www.easyscp.net
 * @author 		EasySCP Team
 *
 * @license
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
 * The Original Code is "ispCP ω (OMEGA) a Virtual Hosting Control Panel".
 *
 * The Initial Developer of the Original Code is ispCP Team.
 * Portions created by Initial Developer are Copyright (C) 2006-2011 by
 * isp Control Panel. All Rights Reserved.
 *
 * Portions created by the EasySCP Team are Copyright (C) 2010-2011 by
 * Easy Server Control Panel. All Rights Reserved.
 */

/**
 * Autoloading classes
 *
 * @param string $className Class name to be loaded
 */
function autoload_class($className) {

	$path = str_replace('_', '/', $className);

	if(file_exists(INCLUDEPATH . '/' . $path . '.php')) {
		require_once INCLUDEPATH . '/' . $path . '.php';
	}
}
