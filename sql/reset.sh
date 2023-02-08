#!/bin/bash

. ./common.sh

RESET_SQL="reset.sql"
PMC_PWD=$(get_password)

if [ $PMC_HAS_PWD == "yes" ]; then
	mysql -u $PMC_USER -p$PMC_PWD $PMC_DB < $RESET_SQL
else
	mysql -u $PMC_USER $PMC_DB < $RESET_SQL
fi
if [ $? == 0 ]; then
        info "Database $PMC_DB correctly reset to a fresh state!"
fi
