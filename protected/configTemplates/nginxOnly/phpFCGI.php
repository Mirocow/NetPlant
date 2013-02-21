[netplant-<?php echo $platform->systemUser;?>]

listen = /var/run/php5-fpm/netplant-<?php echo $platform->systemUser;?>.socket
listen.backlog = -1

; Unix user/group of processes
user = <?php echo $platform->systemUser;?>
group = <?php echo $platform->systemUser;?>

; Choose how the process manager will control the number of child processes.
pm = dynamic
pm.max_children = 75
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
;pm.max_requests = 500

; Pass environment variables
; env[HOSTNAME] = $HOSTNAME
; env[PATH] = /usr/local/bin:/usr/bin:/bin
; env[TMP] = /tmp
; env[TMPDIR] = /tmp
; env[TEMP] = /tmp

; host-specific php ini settings here
; php_admin_value[open_basedir] = /home/<?php echo $platform->systemUser;?>/sites/<?php echo $site->name;?>/htdocs:/tmp:/var/tmp:/home/<?php echo $platform->systemUser;?>/sites/<?php echo $site->name;?>/tmp

php_admin_value[memory_limit] = 256M
php_admin_value[error_log] = /home/<?php echo $platform->systemUser;?>/sites/<?php echo $site->name;?>/logs/php_error.log
php_admin_flag[log_errors] = on