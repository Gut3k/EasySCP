{include file='admin/header.tpl'}
<body>
	<script type="text/javascript">
	/* <![CDATA[ */
		$(document).ready(function(){
			// Tooltips - begin
			$('#dmn_exp_help').EasySCPtooltips({ msg:"{$TR_DMN_EXP_HELP}"});
			// Tooltips - end
		});
	/* ]]> */
	</script>
	<div class="header">
		{include file="$MAIN_MENU"}
		<div class="logo">
			<img src="{$THEME_COLOR_PATH}/images/easyscp_logo.png" alt="EasySCP logo" />
			<img src="{$THEME_COLOR_PATH}/images/easyscp_webhosting.png" alt="EasySCP - Easy Server Control Panel" />
		</div>
	</div>
	<div class="location">
		<div class="location-area">
			<h1 class="manage_users">{$TR_MENU_MANAGE_USERS}</h1>
		</div>
		<ul class="location-menu">
			
			<li><a href="../index.php?logout" class="logout">{$TR_MENU_LOGOUT}</a></li>
		</ul>
		<ul class="path">
			<li><a href="manage_users.php">{$TR_MENU_OVERVIEW}</a></li>
			<li><a>{$TR_EDIT_DOMAIN}</a></li>
		</ul>
	</div>
	<div class="left_menu">{include file="$MENU"}</div>
	<div class="main">
		{if isset($MESSAGE)}
		<div class="{$MSG_TYPE}">{$MESSAGE}</div>
		{/if}
		<h2 class="user"><span>{$TR_EDIT_DOMAIN}</span></h2>
		<form action="domain_edit.php" method="post" id="admin_domain_edit">
			<fieldset>
				<legend>{$TR_DOMAIN_PROPERTIES}</legend>
				<table>
					<tr>
						<td>{$TR_DOMAIN_NAME}</td>
						<td>{$VL_DOMAIN_NAME}</td>
					</tr>
					<tr>
						<td>{$TR_DOMAIN_IP}</td>
						<td>
							{$VL_DOMAIN_IP}
							<!--
							<select name="domain_ip">
							<option value="{$IP_VALUE}" {$IP_SELECTED}>{$IP_NUM}&nbsp;({$IP_NAME})</option>
							</select>
							-->
						</td>
					</tr>
					<tr>
						<td>{$TR_DOMAIN_EXPIRE}</td>
						<td>{$VL_DOMAIN_EXPIRE}</td>
					</tr>
					<tr>
						<td>{$TR_DOMAIN_NEW_EXPIRE} <span id="dmn_exp_help" class="icon i_help">&nbsp;</span></td>
						<td>
							<select name="dmn_expire" id="dmn_expire">
								<option value="0">Unchanged</option>
								<option value="-1">- 1 Month</option>
								<option value="1">+ 1 Month</option>
								<option value="2">+ 2 Months</option>
								<option value="3">+ 3 Months</option>
								<option value="6">+ 6 Months</option>
								<option value="12">+ 1 Year</option>
								<option value="24">+ 2 Year</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>{$TR_PHP_SUPP}</td>
						<td>
							<select name="domain_php" id="domain_php">
								<option value="_yes_" {$PHP_YES}>{$TR_YES}</option>
								<option value="_no_" {$PHP_NO}>{$TR_NO}</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>{$TR_CGI_SUPP}</td>
						<td>
							<select name="domain_cgi" id="domain_cgi">
								<option value="_yes_" {$CGI_YES}>{$TR_YES}</option>
								<option value="_no_" {$CGI_NO}>{$TR_NO}</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>{$TR_DNS_SUPP}</td>
						<td>
							<select name="domain_dns" id="domain_dns">
								<option value="_yes_" {$DNS_YES}>{$TR_YES}</option>
								<option value="_no_" {$DNS_NO}>{$TR_NO}</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>{$TR_BACKUP}</td>
						<td>
							<select name="backup" id="backup">
								<option value="_dmn_" {$BACKUP_DOMAIN}>{$TR_BACKUP_DOMAIN}</option>
								<option value="_sql_" {$BACKUP_SQL}>{$TR_BACKUP_SQL}</option>
								<option value="_full_" {$BACKUP_FULL}>{$TR_BACKUP_FULL}</option>
								<option value="_no_" {$BACKUP_NO}>{$TR_BACKUP_NO}</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label for="dom_sub">{$TR_SUBDOMAINS}</label></td>
						<td><input type="text" name="dom_sub" id="dom_sub" value="{$VL_DOM_SUB}"/></td>
					</tr>
					<tr>
						<td><label for="dom_alias">{$TR_ALIAS}</label></td>
						<td><input type="text" name="dom_alias" id="dom_alias" value="{$VL_DOM_ALIAS}"/></td>
					</tr>
					<tr>
						<td><label for="dom_mail_acCount">{$TR_MAIL_ACCOUNT}</label></td>
						<td><input type="text" name="dom_mail_acCount" id="dom_mail_acCount" value="{$VL_DOM_MAIL_ACCOUNT}"/></td>
					</tr>
					<tr>
						<td><label for="dom_ftp_acCounts">{$TR_FTP_ACCOUNTS}</label></td>
						<td><input type="text" name="dom_ftp_acCounts" id="dom_ftp_acCounts" value="{$VL_FTP_ACCOUNTS}"/></td>
					</tr>
					<tr>
						<td><label for="dom_sqldb">{$TR_SQL_DB}</label></td>
						<td><input type="text" name="dom_sqldb" id="dom_sqldb" value="{$VL_SQL_DB}"/></td>
					</tr>
					<tr>
						<td><label for="dom_sql_users">{$TR_SQL_USERS}</label></td>
						<td><input type="text" name="dom_sql_users" id="dom_sql_users" value="{$VL_SQL_USERS}"/></td>
					</tr>
					<tr>
						<td><label for="dom_traffic">{$TR_TRAFFIC}</label></td>
						<td><input type="text" name="dom_traffic" id="dom_traffic" value="{$VL_TRAFFIC}"/></td>
					</tr>
					<tr>
						<td><label for="dom_disk">{$TR_DISK}</label></td>
						<td><input type="text" name="dom_disk" id="dom_disk" value="{$VL_DOM_DISK}"/></td>
					</tr>
					<tr>
						<td>{$TR_USER_NAME}</td>
						<td>{$VL_USER_NAME}</td>
					</tr>
				</table>
			</fieldset>
			<div class="buttons">
				<input type="hidden" name="uaction" value="sub_data" />
				<input type="submit" name="Submit" value="{$TR_UPDATE_DATA}" />
			</div>
		</form>
	</div>
{include file='admin/footer.tpl'}