<?php
namespace EThesis\Library;


require_once(__DIR__ . '/../../app/library/adodb5/adodb.inc.php');

class Adodb
{
    var $adodb;

    public function __construct($db_select = 'active')    {
        $this->adodb_connect($db_select);
    }

    public  function adodb_connect($db_select)
    {
        $db_conf = include __DIR__ . "/../../app/config/database.php";

        $cfg = $db_conf[$db_conf[$db_select]];

        $DBI =& ADONewConnection($cfg['adapter']);


        $DBI->debug = $cfg['db_debug'];

        if ($cfg['cache_on'] && is_dir(APPPATH . $cfg['cachedir'])) {
            GLOBAL $ADODB_CACHE_DIR;
            $ADODB_CACHE_DIR = APPPATH . $cfg['cachedir'];
        }
        if ($cfg['pconnect']) {
            // persistent
            $DBI->PConnect($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['dbname']) or die("can't do it: " . $DBI->ErrorMsg());
        } else {
            // normal
            $DBI->Connect($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['dbname']) or die("can't do it: " . $DBI->ErrorMsg());
        }
        if (!empty($cfg['char_set'])) {
            $DBI->Execute('SET character_set_results=' . $cfg['char_set']);
            $DBI->Execute('SET NAMES ' . $cfg['char_set']);
        }
        if (!empty($cfg['dbcollat'])) {
            $DBI->Execute('SET collation_connection=' . $cfg['dbcollat']);
        }
        $DBI->SetFetchMode(ADODB_FETCH_ASSOC);

        $DBI->Execute("SET ANSI_WARNINGS ON ");
        $DBI->Execute("SET ANSI_NULLS ON ");
        $DBI->Execute("SET NOCOUNT ON ");
        $DBI->Execute("SET XACT_ABORT ON ");

        $this->adodb = $DBI;

        //return $DBI;
    }

}


