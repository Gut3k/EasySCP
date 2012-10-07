#!/bin/sh

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

Auswahl=""
OS=""

clear

echo "1 = CentOS"
echo "2 = Debian"
echo "3 = OpenSuse"
echo "4 = Ubuntu"

while :
do
    read -p "Please select your distribution: " Name

    if [ "$Name" = "1" ] || [ "$Name" = "CentOS" ]; then
        Auswahl="CentOS"
    fi

    if [ "$Name" = "2" ] || [ "$Name" = "Debian" ]; then
        Auswahl="Debian"
    fi

    if [ "$Name" = "3" ] || [ "$Name" = "OpenSuse" ]; then
        Auswahl="OpenSuse"
    fi

    if [ "$Name" = "4" ] || [ "$Name" = "OracleLinux" ]; then
        Auswahl="OracleLinux"
    fi


    if [ "$Name" = "5" ] || [ "$Name" = "Ubuntu" ]; then
        Auswahl="Ubuntu"
    fi

    case "$Auswahl" in
	CentOS)
	    echo "Using CentOS. Please wait."
	    break
	    ;;
	Debian)
	    echo "Using Debian. Please wait."
	    make install > /dev/null
		rm -rf /var/www/ > /dev/null
	    cp -R /tmp/easyscp/* / > /dev/null
		echo -n "Debian" > /etc/easyscp/OS
		chmod 0777 /var/www/setup/theme/templates_c/
		/etc/init.d/apache2 restart

	    while :
	    do
		read -p "Start setup [Y/N]?" Setup
		case "$Setup" in
		    [JjYy])
			#echo "ja"
			cd /var/www/easyscp/engine/setup
			perl easyscp-setup
			break
			;;
		    [Nn])
			#echo "nein"
			break
			;;
		    *)
			echo "Wrong selection"
			;;
		esac
	    done
	    break
	    ;;
	OpenSuse)
	    echo "Using OpenSuse. Please wait."
	    break
	    ;;
	OracleLinux)
	    echo "Using Oracle Linux. Please wait."
	    make -f Makefile.oracle install > /dev/null
	    rm -rf /var/www/ > /dev/null
	    cp -R /tmp/easyscp/* / > /dev/null
	    echo -n "OracleLinux" > /etc/easyscp/OS
	    chmod 0777 /var/www/setup/theme/templates_c/
	    service httpd restart
	    while :
	    do
		read -p "Start setup [Y/N]?" Setup
		case "$Setup" in
		    [Yy])
			#echo "ja"
			cd /var/www/easyscp/engine/setup
			perl easyscp-setup
			break
			;;
		    [Nn])
			#echo "nein"
			break
			;;
		    *)
			echo "Wrong selection"
			;;
		esac
	    done
	    break
	    ;;
	Ubuntu)
	    echo "Using Ubuntu. Please wait."
	    break
	    ;;
	*)
	    echo "Please type a number or the name of your distribution!"
	    #Wenn vorher nichts passte
	    ;;
    esac
done

#exit 0
#make install

#cp -R /tmp/easyscp/* /

exit 0