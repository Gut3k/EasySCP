<?php
/**
 * EasySCP a Virtual Hosting Control Panel
 * Copyright (C) 2010-2011 by Easy Server Control Panel - http://www.easyscp.net
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

require '../../include/easyscp-lib.php';

check_login(__FILE__);

$cfg = EasySCP_Registry::get('Config');

$tpl = EasySCP_TemplateEngine::getInstance();
$template = 'client/language.tpl';

if (isset($_POST['uaction']) && $_POST['uaction'] === 'save_lang') {
	$user_id = $_SESSION['user_id'];
	$user_lang = clean_input($_POST['def_language']);
	$query = "
		UPDATE
			`user_gui_props`
		SET
			`lang` = ?
		WHERE
			`user_id` = ?
	";

	exec_query($sql, $query, array($user_lang, $user_id));

	if(!isset($_SESSION['logged_from_id'])) {
		unset($_SESSION['user_def_lang']);
		$_SESSION['user_def_lang'] = $user_lang;
	}

	set_page_message(tr('User language updated successfully!'), 'success');
}

// Makes sure that the language selected is the client's language
if (!isset($_SESSION['logged_from']) && !isset($_SESSION['logged_from_id'])) {
	list($user_def_lang, $user_def_layout) = get_user_gui_props($sql, $_SESSION['user_id']);
} else {
	$user_def_layout = $_SESSION['user_theme'];
	$user_def_lang = $_SESSION['user_def_lang'];
}

gen_def_language($tpl, $sql, $user_def_lang);

// static page messages
gen_logged_from($tpl);

check_permissions($tpl);

$tpl->assign(
	array(
		'TR_PAGE_TITLE'				=> tr('EasySCP - Client/Change Language'),
		'TR_LANGUAGE'				=> tr('Language'),
		'TR_CHOOSE_DEFAULT_LANGUAGE'=> tr('Choose default language'),
		'TR_SAVE'					=> tr('Save')
	)
);

gen_client_mainmenu($tpl, 'client/main_menu_general_information.tpl');
gen_client_menu($tpl, 'client/menu_general_information.tpl');

gen_page_message($tpl);

if ($cfg->DUMP_GUI_DEBUG) {
	dump_gui_debug($tpl);
}

$tpl->display($template);

unset_messages();
?>