<?php
/**
 * EasySCP a Virtual Hosting Control Panel
 * Copyright (C) 2010-2012 by Easy Server Control Panel - http://www.easyscp.net
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @link 		http://www.easyscp.net
 * @author 		EasySCP Team
 */

/**
 * Configure base settings for translations
 */

$cfg = EasySCP_Registry::get('Config');

// Specify location of translation tables
bindtextdomain("EasySCP", $cfg->GUI_ROOT_DIR."/locale");
bind_textdomain_codeset("EasySCP", 'UTF-8');

// Choose domain
textdomain("EasySCP");

/**
 * false: don't set (not even auto),
 * null: set if missing,
 * true: force update from session/default, anything else: set it as a language
 */

/**
 * Translates a given string into the selected language, if exists
 *
 * @param string $msgid string to translate
 * @param mixed $substitution Prevent the returned string from being replaced with html entities
 * @return string Translated or original string
 */
function tr($msgid, $substitution = false) {

	$encoding = 'UTF-8';

	$msgstr = gettext($msgid);

	if ($msgid == 'encoding' && $msgstr == 'encoding') {
		$msgstr = 'UTF-8';
	}

	// Detect comments and strip them if $msgid == $msgstr
	// e.g. tr('_: This is just a comment\nReal message to translate here')
	if ( substr($msgid, 0, 3) == '_: ' &&  $msgid == $msgstr &&
			count($l = explode("\n", $msgid)) > 1) {
		unset($l[0]);
		$msgstr = implode("\n", $l);
	}

	// Replace values
	if (func_num_args() > 1) {
		$argv = func_get_args();
		unset($argv[0]); //msgid

		if (is_bool($argv[1])) {
			unset($argv[1]);
		}

		$msgstr = vsprintf($msgstr, $argv);
	}

	if (!$substitution) {
		$msgstr = replace_html(htmlentities($msgstr, ENT_COMPAT, $encoding));
	}

	return $msgstr;
}

/**
 * Gets the available languages in the system
 *
 * @return Array of available languages
 */
function getLanguages() {
	$languages = array(
		'bg_BG' => 'български език - Bulgarian (Bulgaria)',
		'ca_ES' => 'Català - Catalan (Spain)',
		'cs_CZ' => 'Česky - Czech (Czech Republic)',
		'da_DK' => 'Dansk - Danish (Denmark)',
		'de_DE' => 'Deutsch - German (Germany)',
		'el_GR' => 'Νέα Ελληνικά - Greek (Greece)',
		'en_GB' => 'English - English',
		'es_CO' => 'Español - Spanish (Colombia)',
		'es_ES' => 'Español - Spanish (Spain)',
		'eu_ES' => 'Euskara - Basque (Spain)',
		'fi_FI' => 'Suomi - Finnish (Finland)',
		'fr_FR'	=> 'Français - French (France)',
		'gl_ES' => 'Galego - Galician (Spain)',
		'hu_HU' => 'Magyar - Hungarian (Hungary)',
		'ja_JP' => '日本語 - Japanese (Japan)',
		'nl_NL' => 'Nederlands - Dutch (Netherlands)',
		'pl_PL' => 'Polski - Polish (Poland)',
		'pt_BR' => 'Português - Portuguese (Brazil)',
		'pt_PT' => 'Português - Portuguese (Portugal)',
		'ro_RO' => 'Română - Romanian (Romania)',
		'ru_RU'	=> 'Русский язык - Russian (Russia)',
		'sk_SK' => 'Slovenčina - Slovak (Slovakia)',
		'sv_SE'	=> 'Svenska - Swedish (Sweden)',
		'tr_TR' => 'Türkçe - Turkish (Turkey)'
		// 'uk_UA'	=> 'Українська - Ukrainian (Ukraine)'
	);
	return $languages;
}

/**
 * Creates a list of all current installed languages
 *
 * @param string $lang_selected Defines the selected language
 */
function gen_def_language($lang_selected) {

	$cfg = EasySCP_Registry::get('Config');
	$tpl = EasySCP_TemplateEngine::getInstance();

	$languages = getLanguages();

	foreach ($languages as $lang => $language_name) {
		$tpl->append(
			array(
				'LANG_VALUE'	=> $lang,
				'LANG_SELECTED'	=> ($lang === $lang_selected) ? $cfg->HTML_SELECTED : '',
				'LANG_NAME'		=> tohtml($language_name)
			)
		);
	}
}

/**
 * Replaces special encoded strings back to their original signs
 *
 * @param string $string String to replace chars
 * @return String with replaced chars
 */
function replace_html($string) {
	$pattern = array(
		'#&lt;[ ]*b[ ]*&gt;#i',
		'#&lt;[ ]*/[ ]*b[ ]*&gt;#i',
		'#&lt;[ ]*strong[ ]*&gt;#i',
		'#&lt;[ ]*/[ ]*strong[ ]*&gt;#i',
		'#&lt;[ ]*em[ ]*&gt;#i',
		'#&lt;[ ]*/[ ]*em[ ]*&gt;#i',
		'#&lt;[ ]*i[ ]*&gt;#i',
		'#&lt;[ ]*/[ ]*i[ ]*&gt;#i',
		'#&lt;[ ]*small[ ]*&gt;#i',
		'#&lt;[ ]*/[ ]*small[ ]*&gt;#i',
		'#&lt;[ ]*br[ ]*(/|)[ ]*&gt;#i'
	);

	$replacement = array(
		'<b>',
		'</b>',
		'<strong>',
		'</strong>',
		'<em>',
		'</em>',
		'<i>',
		'</i>',
		'<small>',
		'</small>',
		'<br />'
	);

	$string = preg_replace($pattern, $replacement, $string);

	return $string;
}

/**
 * Update the Users languages
 */
function update_user_language(){

	$cfg = EasySCP_Registry::get('Config');
	$sql = EasySCP_Registry::get('Db');

	$user_id = $_SESSION['user_id'];
	$user_lang = clean_input($_POST['def_language']);

	$query = "
		UPDATE
			`user_gui_props`
		SET
			`lang` = ?
		WHERE
			`user_id` = ?
	;";

	exec_query($sql, $query, array($user_lang, $user_id));

	unset($_SESSION['user_def_lang']);
	$_SESSION['user_def_lang'] = $user_lang;
	$cfg->USER_SELECTED_LANG = $user_lang;
}
?>