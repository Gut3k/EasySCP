<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>{TR_PURCHASE_PAGE_TITLE}</title>
		<meta name="robots" content="nofollow, noindex" />
		<meta http-equiv="Content-Type" content="text/html; charset={THEME_CHARSET}" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<meta http-equiv="Content-Script-Type" content="text/javascript" />
		<link href="{THEME_COLOR_PATH}/css/easyscp.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="{THEME_SCRIPT_PATH}/easyscp.js"></script>
		<script type="text/javascript">
			<!--
			function validateForm(contact) {
				if ("" == document.forms.client_info.client_name.value) {
					alert("Please enter your full name.");
					return false;
				}
				if ("" == document.forms.client_info.client_email.value) {
					alert("Please enter your email address.");
					return false;
				}
				if ("" == document.forms.client_info.client_domain.value) {
					alert("Please enter your domain name.");
					return false;
				}
			}
			//-->
		</script>
	</head>

	<body onLoad="MM_preloadImages('{THEME_COLOR_PATH}/images/icons/database_a.png','{THEME_COLOR_PATH}/images/icons/hosting_plans_a.png','{THEME_COLOR_PATH}/images/icons/domains_a.png','{THEME_COLOR_PATH}/images/icons/general_a.png','{THEME_COLOR_PATH}/images/icons/manage_users_a.png','{THEME_COLOR_PATH}/images/icons/webtools_a.png','{THEME_COLOR_PATH}/images/icons/statistics_a.png','{THEME_COLOR_PATH}/images/icons/support_a.png')">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:100%">
			<tr>
				<td height="80" align="left" valign="top">&nbsp;</td>
			</tr>
			<tr>
				<!-- BDP: purchase_form -->
				<td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="height:100%">
					<tr>
						<td valign="top">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td height="62" align="left" class="title">{TR_PURCHASE_FROM} {TR_PURCHASE_RESELLER_NAME}</td>
									<td width="27" align="right">&nbsp;</td>
								</tr>
								<tr>
									<td valign="top">
										<form method="post" action="pay.php" name="client_info" onSubmit="return validateForm(client_info);">
											<input type="hidden" name="reseller_id" value="{RESELLER_ID}" />
											<input type="hidden" name="purchase_id" value="{PURCHASE_ID}" />
											<table width="100%" cellpadding="5" cellspacing="5">
												<tr>
													<td>
														{TR_CLIENT_NAME}
													</td>
													<td>
														<input type="text" name="client_name" value="" />
													</td>
												</tr>
												<tr>
													<td>
														{TR_CLIENT_EMAIL}
													</td>
													<td>
														<input type="text" name="client_email" value="" />
													</td>
												</tr>
												<tr>
													<td>
														{TR_CLIENT_DOMAIN}
													</td>
													<td>
														<input type="text" name="client_domain" value="" />
													</td>
												</tr>
												<tr>
													<td>
														<input type="reset" value="{TR_RESET}" />
													</td>
													<td>
														<input type="submit" value="{TR_SUBMIT}" />
													</td>
												</tr>
											</table>
										</form>
									</td>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<a href="index.php?reseller_id={RESELLER_ID}">{TR_GO_BACK}</a>
							</td>
						</tr>
					</table>
					<!-- EDP: purchase_form -->
				</td>
			</tr>
			<tr>
				<td height="71">&nbsp;</td>
			</tr>
		</table>
	</body>
</html>
