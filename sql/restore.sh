#!/bin/bash

. ./common.sh

PMC_PWD=$(get_password)

if [ $1 ]; then
	RESTORE_FILE=$1
else
	RESTORE_FILE=$(file_select "Choose a restore point")
fi

if [ -f $RESTORE_FILE ]; then
	if [ $PMC_HAS_PWD == "yes" ]; then
        	mysql -u $PMC_USER -p$PMC_PWD < $RESTORE_FILE
	else
	        mysql -u $PMC_USER < $RESTORE_FILE
	fi
	if [ $? == 0 ]; then
        	info "Database $PMC_DB correctly restored from $RESTORE_FILE!"
	fi
fi
