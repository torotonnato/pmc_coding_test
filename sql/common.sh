#!/bin/bash

export APP=pmc_db
export EXPORT_SUFFIX=.backup.sql
export PMC_USER=pmc
export PMC_HAS_PWD=yes
export PMC_DB=pmc
export PMC_DB_TEST=pmc_test

function get_password {
	if [ $PMC_HAS_PWD == "yes" ]; then
		whiptail --passwordbox "Insert password for user '$PMC_USER'@'localhost'" 8 78 3>&1 1>&2 2>&3
	fi
}

function fatal {
	whiptail --title $APP::ERROR --msgbox "$1" 8 78 3>&1 1>&2 2>&3
	exit $2
}

function info {
	whiptail --title $APP::INFO --msgbox "$1" 8 78 3>&1 1>&2 2>&3
}

function yesno {
	whiptail --defaultno --title $APP --yesno "$1" 8 78 3>&1 1>&2 2>&3
	return $?
}

function file_select {
	shopt -s nullglob
	FILE_LIST=$(for f in *$EXPORT_SUFFIX; do printf $f" \e "; done)
	whiptail --title $APP::Select --menu "$1" 15 78 8 $FILE_LIST 3>&1 1>&2 2>&3
}

which whiptail 1> /dev/null 2> /dev/null
if [ ! $? ]; then
	echo "whiptail not found, install it with sudo apt install whiptail"
	exit 1
fi
