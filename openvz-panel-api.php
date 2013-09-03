<?php

class OpenVZPanelAPI
{
    private static $_host = 'localhost';
    private static $_context = null;

    public static function init($user, $password, $host = 'localhost', $port = 3000)
    {
        self::$_host = $host.':'.$port;
        self::$_context = stream_context_create(array(
            'http' => array(
                'header' => 'Authorization: Basic ' . base64_encode($user.':'.$password)
            )
        ));
    }

    public static function getVirtualServers($sId)
    {
        $url = sprintf('http://%s/api/hardware_servers/virtual_servers?id=%s', self::$_host, $sId);
        $result = file_get_contents($url, false, self::$_context);
//echo $result;

        $doc = simplexml_load_string($result);

        $res = array();
        foreach ($doc->xpath('//virtual_server') as $node) {
            $res []= array(
                'identity' => (string)$node->identity,
                'ip_address' => (string)$node->ip_address,
                'host_name' => (string)$node->host_name,
                'description' => (string)$node->description,
                'ip_address' => (string)$node->ip_address,
            );
//    echo $node->ip_address."\n";
//    echo $node->identity."\n";
//    echo $node->host_name."\n";
//    echo $node->description."\n";
//    echo "=================\n";
            //echo "server: $node\n";
        }
        return $res;
    }
}
