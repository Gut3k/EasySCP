#!/usr/bin/make -f

# EasySCP a Virtual Hosting Control Panel
# Copyright (C) 2010-2012 by Easy Server Control Panel - http://www.easyscp.net
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
#
# @link 		http://www.easyscp.net
# @author 		EasySCP Team

ROOT_POSTFIX_CONF=$(ROOT_CONF)/postfix

export

install:

	$(SYSTEM_MAKE_DIRS) $(SYSTEM_CONF)/postfix
	$(SYSTEM_MAKE_DIRS) $(ROOT_POSTFIX_CONF)
	$(SYSTEM_MAKE_DIRS) $(ROOT_POSTFIX_CONF)/easyscp

	cp ./main.cf $(SYSTEM_CONF)/postfix/main.cf
	cp ./master.cf $(SYSTEM_CONF)/postfix/master.cf
	cp ./smtpd.conf $(SYSTEM_CONF)/postfix/smtpd.conf

uninstall:

	rm -rf $(SYSTEM_CONF)/postfix
	rm -rf $(ROOT_POSTFIX_CONF)/easyscp
