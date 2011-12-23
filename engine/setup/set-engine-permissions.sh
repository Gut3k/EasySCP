#!/bin/sh

# EasySCP a Virtual Hosting Control Panel
# Copyright (C) 2001-2006 by moleSoftware GmbH - http://www.molesoftware.com
# Copyright (C) 2006-2010 by isp Control Panel - http://ispcp.net
# Copyright (C) 2010-2011 by Easy Server Control Panel - http://www.easyscp.net
#
# The contents of this file are subject to the Mozilla Public License
# Version 1.1 (the "License"); you may not use this file except in
# compliance with the License. You may obtain a copy of the License at
# http://www.mozilla.org/MPL/
#
# Software distributed under the License is distributed on an "AS IS"
# basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
# License for the specific language governing rights and limitations
# under the License.
#
# The Original Code is "VHCS - Virtual Hosting Control System".
#
# The Initial Developer of the Original Code is moleSoftware GmbH.
# Portions created by Initial Developer are Copyright (C) 2001-2006
# by moleSoftware GmbH. All Rights Reserved.
#
# Portions created by the ispCP Team are Copyright (C) 2006-2010 by
# isp Control Panel. All Rights Reserved.
#
# Portions created by the EasySCP Team are Copyright (C) 2010-2011 by
# Easy Server Control Panel. All Rights Reserved.
#
# The Easy Server Control Panel Home Page is:
#
#    http://www.easyscp.net
#

SELFDIR=$(dirname "$0")
. $SELFDIR/easyscp-permission-functions.sh

echo -n "	Setting Engine Permissions: ";
if [ $DEBUG -eq 1 ]; then
    echo	"";
fi

# easyscp.conf must be world readable because user "vmail" needs to access it.
if [ -f /usr/local/etc/easyscp/easyscp.conf ]; then
	set_permissions "/usr/local/etc/easyscp/easyscp.conf" \
		$ROOT_USER $ROOT_GROUP 0644
else
	set_permissions "/etc/easyscp/easyscp.conf" $ROOT_USER $ROOT_GROUP 0644
fi

# Only root can run engine scripts
recursive_set_permissions "$ROOT_DIR/engine" $ROOT_USER $ROOT_GROUP 0700 0700

# Engine folder must be world-readable because "vmail" user must be able
# to access its "messenger" subfolder.
set_permissions "$ROOT_DIR/engine" $ROOT_USER $ROOT_GROUP 0755

# Messenger script is run by user "vmail".
recursive_set_permissions "$ROOT_DIR/engine/messenger" \
	$MTA_MAILBOX_UID_NAME $MTA_MAILBOX_GID_NAME 0750 0550
recursive_set_permissions "$LOG_DIR/easyscp-arpl-msgr" \
	$MTA_MAILBOX_UID_NAME $MTA_MAILBOX_GID_NAME 0750 0640

# TODO: Fixing fcgid permisions set before 1.0.5

echo " done";

exit 0
