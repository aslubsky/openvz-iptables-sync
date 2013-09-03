<?php

define('SITE_DIR', dirname(__FILE__));
require_once SITE_DIR .'/openvz-panel-api.php';
require_once SITE_DIR .'/db-api.php';

$user = 'admin';
$password = '******';
$host = 'xx.xx.xx.xx';
OpenVZPanelAPI::init($user, $password, $host);

DbAPI::init(SITE_DIR.'/openvz-iptables.db');
//exit('O_o');

$nodes = OpenVZPanelAPI::getVirtualServers(1);
foreach($nodes as $node) {
    if(!DbAPI::hasNode($node['identity'])) {
        $dbNode = DbAPI::addNode($node);
        DbAPI::forwardNodePort($node['identity'], 22, '22'.$node['identity']);
        DbAPI::forwardNodePort($node['identity'], 3306, '33'.$node['identity']);
    }
}