<?php

define('SITE_DIR', dirname(__FILE__));
require_once SITE_DIR .'/db-api.php';

DbAPI::init(SITE_DIR.'/openvz-iptables.db');

if(!DbAPI::needSync()) {
    exit(1);
}

$wanIP = 'xx.xx.xx.xx';
$wanIF = 'eth0';

$str = array();
$portsFwd = DbAPI::getPorts();
$identity = null;
foreach($portsFwd as $portFwd) {
    if($identity != $portFwd['identity']) {
        $str []= "\n".'## VPS '.$portFwd['identity'];
    }
    $str []= sprintf(
        '/sbin/iptables -t nat -A PREROUTING -p %s -d %s --dport %s -i %s -j DNAT --to-destination %s:%s',
        $portFwd['proto'], $wanIP, $portFwd['port_from'], $wanIF, $portFwd['port_to'], $portFwd['ip_address']
    );
    if($identity != $portFwd['identity']) {
//        $str []= "\n";
    }
    $identity = $portFwd['identity'];
}
file_put_contents(SITE_DIR.'/all', implode("\n", $str));

shell_exec(SITE_DIR.'/main');
shell_exec('chmod 755 '.SITE_DIR.'/all');
shell_exec(SITE_DIR.'/all');

exit('OK');