<?php

class DbAPI
{
    private static $conn;

    public static function init($dbPath)
    {
        $needCreate = !file_exists($dbPath);
        self::$conn = new PDO('sqlite:'.$dbPath);
        if($needCreate) {
            self::_create();
        }
    }

    private static function _create()
    {
        self::exec('CREATE TABLE nodes (identity INTEGER PRIMARY KEY, ip_address TEXT, need_sync NUMERIC)');
        self::exec('CREATE TABLE nodes_ports (identity NUMERIC, port_from NUMERIC, port_to NUMERIC, proto TEXT)');
    }

    public static function hasNode($identity)
    {
        $res = self::fetch(
            sprintf("SELECT COUNT(*) as cnt FROM nodes WHERE identity = %s", (int)$identity)
        );
        return (int)$res['cnt'] > 0;
    }

    public static function needSync()
    {
        $res = self::fetch("SELECT COUNT(*) as cnt FROM nodes WHERE need_sync = 1");
        return (int)$res['cnt'] > 0;
    }

    public static function addNode($nodeArr)
    {
        self::exec(
            sprintf("INSERT INTO nodes (identity, ip_address, need_sync) VALUES(%s, '%s', 1)", (int)$nodeArr['identity'], $nodeArr['ip_address'])
        );
    }

    public static function forwardNodePort($identity, $from, $to, $proto = 'tcp')
    {
        self::exec(
            sprintf("INSERT INTO nodes_ports (identity, port_from, port_to, proto) VALUES(%s, %s, %s, '%s')",
                (int)$identity, $from, $to, $proto)
        );
        self::exec(
            sprintf("UPDATE nodes SET need_sync = 1 WHERE identity = %s", (int)$identity)
        );
    }

    public static function getPorts()
    {
        return self::fetchAll("SELECT np.*,n.ip_address FROM nodes_ports np INNER JOIN nodes n ON n.identity = np.identity ORDER BY np.identity");
    }

    public static function exec($q)
    {
        return self::$conn->exec($q);
    }

    public static function fetchAll($q)
    {
        $sth = self::$conn->prepare($q);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->execute();

        return $sth->fetchAll();
    }

    public static function fetch($q)
    {
        $sth = self::$conn->prepare($q);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->execute();

        return $sth->fetch();
    }
}
