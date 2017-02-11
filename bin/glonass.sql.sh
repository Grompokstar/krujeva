#!/bin/bash

CONFIG=""
SCRIPTNAME=script

while getopts c:f: o
do
	case $o in
		c) CONFIG=$OPTARG;;
		f) SCRIPTNAME=$OPTARG;;
	esac
done

if [ -z "$CONFIG" ]; then
	echo "-c missing"
	exit
fi

if [ ! -f "/etc/glonass/$CONFIG.conf" ]; then
	echo "/etc/glonass/$CONFIG.conf does not exist"
	exit
fi

. /etc/glonass/$CONFIG.conf

SCRIPT="$PROJECTPATH/files/sql/$SCRIPTNAME.sql"

echo $SCRIPT

if [ ! -f "$SCRIPT" ]; then
	echo "$SCRIPT does not exist"
	exit
fi

export PGPASSWORD=$DBPASSWORD

psql -h $DBHOST -p $DBPORT -U $DBUSER -a $DBNAME < $SCRIPT

echo "done"
