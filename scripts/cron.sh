#!/bin/bash

# This script processes queue for changing details on servers(ie. site conf, db, ftp)

queuePath="`pwd`/queue/"


if [ ! -e $queuePath ]; then
	mkdir $queuePath || echo "Can't make queue directory $queuePath" && exit 1
fi

function rollbackCommand {
	echo "Error running $1"
	rm $2
	exit 1
}

function executeCommand {
	echo "Executing $1"
	chmod +x $1
	$1 || rollbackCommand $1 $2 # run command
	rm $2 # remove Lock file
	rm $1 # remove source file of queue
}


for i in $( ls $queuePath*.sh );
do
	echo $i

	lockFile="$i.lock"
	if [ ! -f $lockFile ]; then
		touch $lockFile
		executeCommand $i $lockFile &
	fi
done

echo "Done"
