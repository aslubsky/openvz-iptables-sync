openvz-iptables-sync
====================

sync tool for openvz containers and iptables on host machine

## Installing

* clone from github and init
```
mkdir /opt/iptables
cd /opt/iptables
git clone https://github.com/aslubsky/openvz-iptables-sync.git .
chmod 666 ./db
php sync.php
```

* add to CRON
```
* *  * * *  /usr/bin/php /opt/iptables/iptables.php
```

* configure your web server root directory in to /opt/iptables/public
