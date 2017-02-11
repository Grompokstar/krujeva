#!/bin/bash

CONFIG=""

while getopts c: o
do
	case $o in
		c) CONFIG=$OPTARG;;
	esac
done

if [ -z "$CONFIG" ]; then
	echo "-c missing"
	exit
fi

if [ ! -f "$CONFIG.conf" ]; then
	echo "$CONFIG.conf does not exist"
	exit
fi

. $CONFIG.conf

read -s -p "'postgres' password: " DBPASSWD

echo

ssh -i $IDENTITY -t $USER@$DBHOST "
	cd /;

	#sudo service $SERVICE restart;

	export PGPASSWORD=$DBPASSWD

	dropdb -h $DBHOST -p $DBPORT -U postgres -e $DBNAME;

	createdb -h $DBHOST -p $DBPORT -U postgres -e $DBNAME &&
	psql -h $DBHOST -p $DBPORT -U postgres -a -c '
		create extension postgis;
		grant select on spatial_ref_sys to $DBUSER;
		create extension intarray;
		create extension btree_gist;
		create extension ltree;
	' $DBNAME
"
