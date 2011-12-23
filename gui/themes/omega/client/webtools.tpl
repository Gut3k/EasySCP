{include file='client/header.tpl'}
<body>
	<div class="header">
		{include file="$MAIN_MENU"}
		<div class="logo">
			<img src="{$THEME_COLOR_PATH}/images/easyscp_logo.png" alt="EasySCP logo" />
			<img src="{$THEME_COLOR_PATH}/images/easyscp_webhosting.png" alt="EasySCP - Easy Server Control Panel" />
		</div>
	</div>
	<div class="location">
		<div class="location-area">
			<h1 class="webtools">{$TR_MENU_WEBTOOLS}</h1>
		</div>
		<ul class="location-menu">
			{if isset($YOU_ARE_LOGGED_AS)}
			<li><a href="change_user_interface.php?action=go_back" class="backadmin">{$YOU_ARE_LOGGED_AS}</a></li>
			{/if}
			<li><a href="../index.php?logout" class="logout">{$TR_MENU_LOGOUT}</a></li>
		</ul>
		<ul class="path">
			<li><a>{$TR_MENU_OVERVIEW}</a></li>
		</ul>
	</div>
	<div class="left_menu">{include file="$MENU"}</div>
	<div class="main">
		{if isset($MESSAGE)}
		<div class="{$MSG_TYPE}">{$MESSAGE}</div>
		{/if}
		<h2 class="tools"><span>{$TR_MENU_WEBTOOLS}</span></h2>
	   	<a href="protected_areas.php">{$TR_HTACCESS}</a>
		<p>{$TR_HTACCESS_TEXT}</p>
		<a href="protected_user_manage.php">{$TR_HTACCESS_USER}</a>
		<p>{$TR_HTACCESS_USER}</p>
		<a href="error_pages.php">{$TR_ERROR_PAGES}</a>
		<p>{$TR_ERROR_PAGES_TEXT}</p>
		<a href="backup.php">{$TR_BACKUP}</a>
		<p>{$TR_BACKUP_TEXT}</p>
		{if isset($WEBMAIL_PATH)}
		<a href="{$WEBMAIL_PATH}">{$TR_WEBMAIL}</a>
		<p>{$TR_WEBMAIL_TEXT}</p>
		{/if}
		<a href="{$FILEMANAGER_PATH}">{$TR_FILEMANAGER}</a>
		<p>{$TR_FILEMANAGER_TEXT}</p>
		{if isset($AWSTATS_PATH)}
		<a href="{$AWSTATS_PATH}">{$TR_AWSTATS}</a>
		<p>{$TR_AWSTATS_TEXT}</p>
		{/if}
	</div>
{include file='client/footer.tpl'}