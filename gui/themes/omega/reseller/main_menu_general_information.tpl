<div class="main_menu">
	<ul class="icons">
		<li><a href="index.php" title="{$TR_MENU_GENERAL_INFORMATION}"><span class="general icon_link">&nbsp;</span></a></li>
		<li><a href="users.php" title="{$TR_MENU_MANAGE_USERS}"><span class="manage_users icon_link">&nbsp;</span></a></li>
		{if isset($HOSTING_PLANS)}
		<li><a href="hosting_plan.php" title="{$TR_MENU_HOSTING_PLANS}"><span class="hosting_plans icon_link">&nbsp;</span></a></li>
		{/if}
		<li><a href="orders.php" title="{$TR_MENU_ORDERS}"><span class="purchasing icon_link">&nbsp;</span></a></li>
		<li><a href="user_statistics.php" title="{$TR_MENU_DOMAIN_STATISTICS}"><span class="statistics icon_link">&nbsp;</span></a></li>
		{if isset($SUPPORT_SYSTEM)}
		<li><a href="ticket_system.php" title="{$TR_MENU_SUPPORT_SYSTEM}"><span class="support icon_link">&nbsp;</span></a></li>
		{/if}
		{if isset($CUSTOM_BUTTONS)}
			{section name=i loop=$BUTTON_NAME}
			<li><a href="{$BUTTON_LINK[i]}" {$BUTTON_TARGET[i]} title="{$BUTTON_NAME[i]}"><span class="{$BUTTON_ICON[i]} icon_link">&nbsp;</span></a></li>
			{/section}
		{/if}
	</ul>
</div>
