<?php

define('SITE_DIR', realpath(dirname(__FILE__).'/..'));
require_once SITE_DIR .'/db-api.php';

DbAPI::init(SITE_DIR.'/openvz-iptables.db');

switch($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		$id = $_POST['id'];
	break;
	case 'PUT':
		$id = $_POST['id'];
	break;
	case 'DELETE':
		$id = $_POST['id'];
	break;
	default:
	$portsFwd = DbAPI::getPorts();
	$res = array();
	foreach($portsFwd as $portFwd) {
		$res []= array(
			'id' => $portFwd['identity'],
			'port_from' => $portFwd['port_from'],
			'port_to' => $portFwd['port_to']
		);
	}
	echo json_encode($res);
}