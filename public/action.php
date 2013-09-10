<?php

define('SITE_DIR', realpath(dirname(__FILE__).'/..'));
require_once SITE_DIR .'/db-api.php';

DbAPI::init(SITE_DIR.'/db/openvz-iptables.db');

$params = json_decode(trim(file_get_contents('php://input')), true);
/*
print_r($_REQUEST);
print_r($params);
exit;*/
//print_r(DbAPI::fetchAll("SELECT * FROM nodes_ports"));exit;

switch($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		$id = (int)$_GET['id'];
		if($id) {
			DbAPI::updatePortsFwd($id, (int)$params['port_from'], (int)$params['port_to'], $params['proto']);
            $res = $params;
		} else {
			$res = DbAPI::forwardNodePort((int)$params['node_id'], (int)$params['port_from'], (int)$params['port_to'], $params['proto']);
		}
        echo json_encode($res);
    break;
	case 'DELETE':
		$id = (int)$_GET['id'];
        DbAPI::deletePortsFwd($id);
        exit('OK');
	break;
	default:
		$res = array();
		$nodes = DbAPI::getNodes();
		foreach($nodes as $node) {
			$nodeArr = $node;
			$nodeArr['ports'] = DbAPI::getPorts($node['id']);
			$res []= $nodeArr;
		}
		echo json_encode($res);
}