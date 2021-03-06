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

require '../../include/easyscp-lib.php';

check_login(__FILE__);

$cfg = EasySCP_Registry::get('Config');

$reseller_id = $_SESSION['user_id'];

if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
	$order_id = $_GET['order_id'];
} else {
	set_page_message(tr('Wrong order ID!'), 'error');
	user_goto('orders.php');
}

if (isset($cfg->HOSTING_PLANS_LEVEL)
	&& $cfg->HOSTING_PLANS_LEVEL === 'admin') {
	$query = "
		SELECT
			*
		FROM
			`orders`
		WHERE
			`id` = ?
		AND
			`status` = 'update'
	";

	$rs = exec_query($sql, $query, $order_id);
} else {
	$query = "
		SELECT
			*
		FROM
			`orders`
		WHERE
			`id` = ?
		AND
			`user_id` = ?
		AND
			`status` = 'update'
	";

	$rs = exec_query($sql, $query, array($order_id, $reseller_id));
}

if ($rs->recordCount() == 0) {
	set_page_message(tr('Permission deny!'), 'error');
	user_goto('orders.php');
}

$hpid = $rs->fields['plan_id'];
$customer_id = $rs->fields['customer_id'];
$dmn_id = get_user_domain_id($sql, $customer_id);
// let's check the reseller limits
$err_msg = '';

if (isset($cfg->HOSTING_PLANS_LEVEL)
	&& $cfg->HOSTING_PLANS_LEVEL === 'admin') {
	$query = "SELECT `props` FROM `hosting_plans` WHERE `id` = ?";
	$res = exec_query($sql, $query, $hpid);
} else {
	$query = "SELECT `props` FROM `hosting_plans` WHERE `reseller_id` = ? AND `id` = ?";
	$res = exec_query($sql, $query, array($reseller_id, $hpid));
}
$data = $res->fetchRow();
$props = $data['props'];

$_SESSION["ch_hpprops"] = $props;

if (!reseller_limits_check($sql, $err_msg, $reseller_id, $hpid)) {
	set_page_message(tr("Order Canceled: resellers maximum exceeded!"), 'info');
	user_goto('orders.php');
}

if (!empty($err_msg)) {
	set_page_message($err_msg, 'error');
	unset($_SESSION['domain_ip']);
	user_goto('orders.php');
}
unset($_SESSION["ch_hpprops"]);

list($domain_php, $domain_cgi, $sub,
	$als, $mail, $ftp,
	$sql_db, $sql_user,
	$traff, $disk, $backup, $domain_dns) = explode(";", $props);

$domain_php = preg_replace("/\_/", "", $domain_php);
$domain_cgi = preg_replace("/\_/", "", $domain_cgi);
$domain_dns = preg_replace("/\_/", "", $domain_dns);

$ed_error = '';

if (!easyscp_limit_check($sub, -1)) {
	$ed_error = tr('Incorrect subdomains limit!');
}
if (!easyscp_limit_check($als, -1)) {
	$ed_error .= tr('Incorrect aliases limit!');
}
if (!easyscp_limit_check($mail, -1)) {
	$ed_error .= tr('Incorrect mail accounts limit!');
}
if (!easyscp_limit_check($ftp, -1)) {
	$ed_error .= tr('Incorrect FTP accounts limit!');
}
if (!easyscp_limit_check($sql_db, -1)) {
	$ed_error .= tr('Incorrect SQL users limit!');
}
if (!easyscp_limit_check($sql_user, -1)) {
	$ed_error .= tr('Incorrect SQL databases limit!');
}
if (!easyscp_limit_check($traff, null)) {
	$ed_error .= tr('Incorrect traffic limit!');
}
if (!easyscp_limit_check($disk, null)) {
	$ed_error .= tr('Incorrect disk quota limit!');
}

list($usub_current, $usub_max,
	$uals_current, $uals_max,
	$umail_current, $umail_max,
	$uftp_current, $uftp_max,
	$usql_db_current, $usql_db_max,
	$usql_user_current, $usql_user_max,
	$utraff_max, $udisk_max
) = generate_user_props($dmn_id);

list($rdmn_current, $rdmn_max,
	$rsub_current, $rsub_max,
	$rals_current, $rals_max,
	$rmail_current, $rmail_max,
	$rftp_current, $rftp_max,
	$rsql_db_current, $rsql_db_max,
	$rsql_user_current, $rsql_user_max,
	$rtraff_current, $rtraff_max,
	$rdisk_current, $rdisk_max
) = get_reseller_default_props($sql, $reseller_id); //generate_reseller_props($reseller_id);

list($a, $b, $c, $d, $e, $f, $utraff_current, $udisk_current, $i, $h) = generate_user_traffic($dmn_id);

if (empty($ed_error)) {
	calculate_user_dvals($sub, $usub_current, $usub_max, $rsub_current, $rsub_max, $ed_error, tr('Subdomain'));
	calculate_user_dvals($als, $uals_current, $uals_max, $rals_current, $rals_max, $ed_error, tr('Alias'));
	calculate_user_dvals($mail, $umail_current, $umail_max, $rmail_current, $rmail_max, $ed_error, tr('Mail'));
	calculate_user_dvals($ftp, $uftp_current, $uftp_max, $rftp_current, $rftp_max, $ed_error, tr('FTP'));
	calculate_user_dvals($sql_db, $usql_db_current, $usql_db_max, $rsql_db_current, $rsql_db_max, $ed_error, tr('SQL Database'));
	calculate_user_dvals($sql_user, $usql_user_current, $usql_user_max, $rsql_user_current, $rsql_user_max, $ed_error, tr('SQL User'));
	calculate_user_dvals($traff, $utraff_current / 1024 / 1024 , $utraff_max, $rtraff_current, $rtraff_max, $ed_error, tr('Traffic'));
	calculate_user_dvals($disk, $udisk_current / 1024 / 1024, $udisk_max, $rdisk_current, $rdisk_max, $ed_error, tr('Disk'));
}

if (empty($ed_error)) {
	$user_props = "$usub_current;$usub_max;";
	$user_props .= "$uals_current;$uals_max;";
	$user_props .= "$umail_current;$umail_max;";
	$user_props .= "$uftp_current;$uftp_max;";
	$user_props .= "$usql_db_current;$usql_db_max;";
	$user_props .= "$usql_user_current;$usql_user_max;";
	$user_props .= "$utraff_max;";
	$user_props .= "$udisk_max;";
	$user_props .= "$domain_php;";
	$user_props .= "$domain_cgi;";
	$user_props .= "$backup;";
	$user_props .= "$domain_dns";
	update_user_props($dmn_id, $user_props);

	$reseller_props = "$rdmn_current;$rdmn_max;";
	$reseller_props .= "$rsub_current;$rsub_max;";
	$reseller_props .= "$rals_current;$rals_max;";
	$reseller_props .= "$rmail_current;$rmail_max;";
	$reseller_props .= "$rftp_current;$rftp_max;";
	$reseller_props .= "$rsql_db_current;$rsql_db_max;";
	$reseller_props .= "$rsql_user_current;$rsql_user_max;";
	$reseller_props .= "$rtraff_current;$rtraff_max;";
	$reseller_props .= "$rdisk_current;$rdisk_max";

	update_reseller_props($reseller_id, $reseller_props);
	// update the sql quotas, too
	$query = "SELECT `domain_name` FROM `domain` WHERE `domain_id` = ?";
	$rs = exec_query($sql, $query, $dmn_id);
	$temp_dmn_name = $rs->fields['domain_name'];

	$query = "SELECT COUNT(`name`) AS cnt FROM `quotalimits` WHERE `name` = ?";
	$rs = exec_query($sql, $query, $temp_dmn_name);
	if ($rs->fields['cnt'] > 0) {
		// we need to update it
		if ($disk == 0) {
			$dlim = 0;
		} else {
			$dlim = $disk * 1024 * 1024;
		}

		$query = "UPDATE `quotalimits` SET `bytes_in_avail` = ? WHERE `name` = ?";
		$rs = exec_query($sql, $query, array($dlim, $temp_dmn_name));
	}

	$query = "
		UPDATE
			`orders`
		SET
			`status` = ?
		WHERE
			`id` = ?
	";
	exec_query($sql, $query, array('added', $order_id));
	set_page_message(tr('Domain properties updated successfully!'), 'success');
	user_goto('users.php?psi=last');
} else {
	set_page_message($ed_error, 'error');
	user_goto('orders.php');
}

function calculate_user_dvals($data, $u, &$umax, &$r, $rmax, &$err, $obj) {
	if ($rmax == -1 && $umax >= 0) {
		if ($u > 0) {
			$err .= tr('The <em>%s</em> service cannot be disabled!', $obj) . tr('There are <em>%s</em> records on the system!', $obj);
			return;
		} else if ($data != -1){
			$err .= tr('The <em>%s</em> have to be disabled!', $obj) . tr('The admin has <em>%s</em> disabled on this system!', $obj);
			return;
		} else {
			$umax = $data;
		}
		return;
	} else if ($rmax == 0 && $umax == -1) {
		if ($data == -1) {
			return;
		} else if ($data == 0) {
			$umax = $data;

			return;
		} else if ($data > 0) {
			$umax = $data;

			$r += $umax;

			return;
		}
	} else if ($rmax == 0 && $umax == 0) {
		if ($data == -1) {
			if ($u > 0) {
				$err .= tr('The <em>%s</em> service cannot be disabled!', $obj) . tr('There are <em>%s</em> records on the system!', $obj);
			} else {
				$umax = $data;
			}

			return;
		} else if ($data == 0) {
			return;
		} else if ($data > 0) {
			if ($u > $data) {
				$err .= tr('The <em>%s</em> service cannot be limited!', $obj) . tr('Specified number is smaller than <em>%s</em> records, present on the system!', $obj);
			} else {
				$umax = $data;

				$r += $umax;
			}

			return;
		}
	} else if ($rmax == 0 && $umax > 0) {
		if ($data == -1) {
			if ($u > 0) {
				$err .= tr('The <em>%s</em> service cannot be disabled!', $obj) . tr('There are <em>%s</em> records on the system!', $obj);
			} else {
				$r -= $umax;

				$umax = $data;
			}

			return;
		} else if ($data == 0) {
			$r -= $umax;

			$umax = $data;

			return;
		} else if ($data > 0) {
			if ($u > $data) {
				$err .= tr('The <em>%s</em> service cannot be limited!', $obj) . tr('Specified number is smaller than <em>%s</em> records, present on the system!', $obj);
			} else {
				if ($umax > $data) {
					$data_dec = $umax - $data;

					$r -= $data_dec;
				} else {
					$data_inc = $data - $umax;

					$r += $data_inc;
				}

				$umax = $data;
			}

			return;
		}
	} else if ($rmax > 0 && $umax == -1) {
		if ($data == -1) {
			return;
		} else if ($data == 0) {
			$err .= tr('The <em>%s</em> service cannot be unlimited!', $obj) . tr('There are reseller limits for the <em>%s</em> service!', $obj);

			return;
		} else if ($data > 0) {
			if ($r + $data > $rmax) {
				$err .= tr('The <em>%s</em> service cannot be limited!', $obj) . tr('You are exceeding reseller limits for the <em>%s</em> service!', $obj);
			} else {
				$r += $data;

				$umax = $data;
			}

			return;
		}
	} else if ($rmax > 0 && $umax == 0) {
		// We Can't Get Here! This clone is present only for
		// sample purposes;
		if ($data == -1) {
		} else if ($data == 0) {
		} else if ($data > 0) {
		}
	} else if ($rmax > 0 && $umax > 0) {
		if ($data == -1) {
			if ($u > 0) {
				$err .= tr('The <em>%s</em> service cannot be disabled!', $obj) . tr('There are <em>%s</em> records on the system!', $obj);
			} else {
				$r -= $umax;

				$umax = $data;
			}

			return;
		} else if ($data == 0) {
			$err .= tr('The <em>%s</em> service cannot be unlimited!', $obj) . tr('There are reseller limits for the <em>%s</em> service!', $obj);

			return;
		} else if ($data > 0) {
			if ($u > $data) {
				$err .= tr('The <em>%s</em> service cannot be limited!', $obj) . tr('Specified number is smaller than <em>%s</em> records, present on the system!', $obj);
			} else {
				if ($umax > $data) {
					$data_dec = $umax - $data;

					$r -= $data_dec;
				} else {
					$data_inc = $data - $umax;

					if ($r + $data_inc > $rmax) {
						$err .= tr('The <em>%s</em> service cannot be limited!', $obj) . tr('You are exceeding reseller limits for the <em>%s</em> service!', $obj);

						return;
					}

					$r += $data_inc;
				}

				$umax = $data;
			}

			return;
		}
	}
} // end of calculate_user_dvals()
