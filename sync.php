<?php

define('SITE_DIR', dirname(__FILE__));
require_once SITE_DIR .'/openvz-panel-api.php';
require_once SITE_DIR .'/db-api.php';

$user = 'xxx';
$password = 'xxx';
$host = 'xxx.xxx.xxx.xxx';
OpenVZPanelAPI::init($user, $password, $host);

DbAPI::init(SITE_DIR.'/db/openvz-iptables.db');
//exit('O_o');

$nodes = OpenVZPanelAPI::getVirtualServers(1);
foreach($nodes as $node) {
    if(!DbAPI::hasNode($node['identity'])) {
        $dbNode = DbAPI::addNode($node);
        DbAPI::forwardNodePort($node['identity'], '22'.$node['identity'], 22);
        DbAPI::forwardNodePort($node['identity'], '33'.$node['identity'], 3306);
    }
}
