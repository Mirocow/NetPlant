#!/bin/bash

# autogenerated script of NetPlant
# ExecutionQueue ID: 8

# enable site 'example.com'
ssh root@127.0.0.1 'mkdir -p /home/johnyCage/sites/example.com/\{htdocs\|logs\|tmp\}/' || exit 1
ssh root@127.0.0.1 'chown -R johnyCage:johnyCage /home/johnyCage/sites/example.com' || exit 1
scp -P 22 /tmp/netplant_1_2_1361447111.conf root@127.0.0.1:/etc/nginx/netplantHosts/ || exit 1