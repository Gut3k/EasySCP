{include file='admin/header.tpl'}
<body>
	<div class="header">
		{include file="$MAIN_MENU"}
		<div class="logo">
			<img src="{$THEME_COLOR_PATH}/images/easyscp_logo.png" alt="EasySCP logo" />
			<img src="{$THEME_COLOR_PATH}/images/easyscp_webhosting.png" alt="EasySCP - Easy Server Control Panel" />
		</div>
	</div>
	<div class="location">
		<ul class="location-menu">
			<li><a href="../index.php?logout" class="logout">{$TR_MENU_LOGOUT}</a></li>
		</ul>
		<ul class="path">
			<li><a href="index.php">{$TR_MENU_OVERVIEW}</a></li>
			<li><a>{$TR_PERSONAL_DATA}</a></li>
		</ul>
	</div>
	<div class="left_menu">{include file="$MENU"}</div>
	<div class="main">
		{if isset($MESSAGE)}
		<div class="{$MSG_TYPE}">{$MESSAGE}</div>
		{/if}
		<h2 class="doc"><span>{$TR_PERSONAL_DATA}</span></h2>
		<form action="personal_change.php" method="post" id="admin_personal_change">
			<table>
				<tr>
					<td><label for="fname">{$TR_FIRST_NAME}</label></td>
					<td><input type="text" name="fname" id="fname" value="{$FIRST_NAME}"/></td>
				</tr>
				<tr>
					<td><label for="lname">{$TR_LAST_NAME}</label></td>
					<td><input type="text" name="lname" id="lname" value="{$LAST_NAME}"/></td>
				</tr>
				<tr>
					<td><label for="gender">{$TR_GENDER}</label></td>
					<td>
						<select name="gender" id="gender">
							<option value="M" {$VL_MALE}>{$TR_MALE}</option>
							<option value="F" {$VL_FEMALE}>{$TR_FEMALE}</option>
							<option value="U" {$VL_UNKNOWN}>{$TR_UNKNOWN}</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="firm">{$TR_COMPANY}</label></td>
					<td><input type="text" name="firm" id="firm" value="{$FIRM}"/></td>
				</tr>
				<tr>
					<td><label for="street1">{$TR_STREET_1}</label></td>
					<td><input type="text" name="street1" id="street1" value="{$STREET_1}" /></td>
				</tr>
				<tr>
					<td><label for="street2">{$TR_STREET_2}</label></td>
					<td><input type="text" name="street2" id="street2" value="{$STREET_2}" /></td>
				</tr>
				<tr>
					<td><label for="zip">{$TR_ZIP_POSTAL_CODE}</label></td>
					<td><input type="text" name="zip" id="zip" value="{$ZIP}" /></td>
				</tr>
				<tr>
					<td><label for="city">{$TR_CITY}</label></td>
					<td><input type="text" name="city" id="city" value="{$CITY}" /></td>
				</tr>
				<tr>
					<td><label for="state">{$TR_STATE}</label></td>
					<td><input type="text" name="state" id="state" value="{$STATE}" /></td>
				</tr>
				<tr>
					<td><label for="country">{$TR_COUNTRY}</label></td>
					<td><input type="text" name="country" id="country" value="{$COUNTRY}" /></td>
				</tr>
				<tr>
					<td><label for="email">{$TR_EMAIL}</label></td>
					<td><input type="text" name="email" id="email" value="{$EMAIL}" /></td>
				</tr>
				<tr>
					<td><label for="phone">{$TR_PHONE}</label></td>
					<td><input type="text" name="phone" id="phone" value="{$PHONE}" /></td>
				</tr>
				<tr>
					<td><label for="fax">{$TR_FAX}</label></td>
					<td><input type="text" name="fax" id="fax" value="{$FAX}" /></td>
				</tr>
			</table>
			<div class="buttons">
				<input type="hidden" name="uaction" value="updt_data" />
				<input type="submit" name="Submit" value="{$TR_UPDATE_DATA}" />
			</div>
		</form>
	</div>
{include file='admin/footer.tpl'}