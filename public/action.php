<?php

define('SITE_DIR', realpath(dirname(__FILE__).'/..'));
require_once SITE_DIR .'/db-api.php';

DbAPI::init(SITE_DIR.'/openvz-iptables.db');

$params = json_decode(trim(file_get_contents('php://input')), true);

print_r(DbAPI::fetchAll("SELECT * FROM nodes_ports"));exit;

switch($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		$id = (int)$_GET['id'];
		DbAPI::updatePortsFwd($id, (int)$params['port_from'], (int)$params['port_to'], $params['proto']);
		echo json_encode($params);
	break;
	case 'PUT':
		$id = (int)$_GET['id'];
		$res = DbAPI::forwardNodePort((int)$params['node_id'], (int)$params['port_from'], (int)$params['port_to'], $params['proto']);
		echo json_encode($params);
	break;
	case 'DELETE':
		$id = $_POST['id'];
	break;
	default:
	$portsFwd = DbAPI::getPorts();
	$res = array();
	foreach($portsFwd as $portFwd) {
		$res []= $portFwd;
	}
	echo json_encode($res);
}