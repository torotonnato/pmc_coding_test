#!/bin/bash

. ./common.sh

PMC_PWD=$(get_password)
if [ $1 ]; then
	BACKUP_FILE=$1;
	if [ -f $BACKUP_FILE ]; then
		fatal "Error: file $1 already exists, aborting" 1
	fi
else
	BACKUP_FILE=$(date +%Y%m%d-%H%M-pmc_coding_test.backup.sql)
	if [ -f $BACKUP_FILE ]; then
		yesno "$BACKUP_FILE already exists. Do you want to overwrite it?"
		if [ $? == 1 ]; then
			echo Bye!
			exit 2
		fi
	fi
fi

if [ $PMC_HAS_PWD == "yes" ]; then
	mysqldump -u $PMC_USER -p$PMC_PWD --databases $PMC_DB > $BACKUP_FILE
else
	mysqldump -u $PMC_USER --databases $PMC_DB > $BACKUP_FILE
fi
if [ $? == 0 ]; then
	info "Database $PMC_DB correctly backed up to $BACKUP_FILE!"
fi
