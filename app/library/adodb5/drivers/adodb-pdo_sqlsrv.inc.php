<?php

/*
  V5.11 5 May 2010   (c) 2000-2010 John Lim (jlim#natsoft.com). All rights reserved.
  Released under both BSD license and Lesser GPL library license.
  Whenever there is any discrepancy between the two licenses,
  the BSD license will take precedence.
  Set tabs to 8.

 */

class ADODB_pdo_sqlsrv extends ADODB_pdo {

    var $hasTop = 'TOP';
    var $sysDate = 'CONVERT(DATETIME,CONVERT(CHAR,GETDATE(),102),102)';
    var $sysTimeStamp = 'GETDATE()';
    var $dsnType = "sqlsrv";

    function _init($parentDriver) {

        $parentDriver->hasTransactions = false; ## <<< BUG IN PDO mssql driver
        $parentDriver->_bindInputArray = false;
        $parentDriver->hasInsertID = true;
    }

    function ServerInfo() {
        return ADOConnection::ServerInfo();
    }

    function SelectLimit($sql, $nrows = -1, $offset = -1, $inputarr = false, $secs2cache = 0) {
        /* SQL Server 2008
          $ret = ADOConnection::SelectLimit($sql, $nrows, $offset, $inputarr, $secs2cache);
          return $ret;
         */

        $sql = str_replace(';', '', $sql);
        if ($nrows > 0 && $offset >= 0 && strpos($sql, 'ORDER BY') !== false) {
            $sql .= " OFFSET $offset ROWS FETCH NEXT $nrows ROWS ONLY; ";

            if ($secs2cache) {
                $rs = $this->CacheExecute($secs2cache, $sql, $inputarr);
            } else {
                $rs = $this->Execute($sql, $inputarr);
            }
        } else {
            $rs = ADOConnection::SelectLimit($sql, $nrows, $offset, $inputarr, $secs2cache);
        }

        return $rs;
    }

    function SetTransactionMode($transaction_mode) {
        $this->_transmode = $transaction_mode;
        if (empty($transaction_mode)) {
            $this->Execute('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
            return;
        }
        if (!stristr($transaction_mode, 'isolation'))
            $transaction_mode = 'ISOLATION LEVEL ' . $transaction_mode;
        $this->Execute("SET TRANSACTION " . $transaction_mode);
    }

    function MetaTables($ttype = false, $showSchema = false, $mask = false) {
        return false;
    }

    function MetaColumns($table, $normalize = true) {
        return false;
    }

    function Concat() {
        $s = "";
        $arr = func_get_args();

        // Split single record on commas, if possible
        if (sizeof($arr) == 1) {
            foreach ($arr as $arg) {
                $args = explode(',', $arg);
            }
            $arr = $args;
        }

        array_walk($arr, create_function('&$v', '$v = "CAST(" . $v . " AS VARCHAR(255))";'));
        $s = implode('+', $arr);
        if (sizeof($arr) > 0)
            return "$s";

        return '';
    }

    function IfNull($field, $ifNull) {
        return " ISNULL($field, $ifNull) "; // if MS SQL Server
    }

    function SQLDate($col = false, $fmt = false) {
        if (!$col)
            $col = $this->sysTimeStamp;
        $s = '';

        if (!$fmt)
            $fmt = str_replace("'", "", $this->fmtTimeStamp);

        $len = strlen($fmt);
        for ($i = 0; $i < $len; $i++) {
            if ($s)
                $s .= '+';
            $ch = $fmt[$i];
            switch ($ch) {
                case 'Y':
                case 'y':
                    $s .= "datename(yyyy,$col)";
                    break;
                case 'M':
                    $s .= "convert(char(3),$col,0)";
                    break;
                case 'm':
                    $s .= "replace(str(month($col),2),' ','0')";
                    break;
                case 'Q':
                case 'q':
                    $s .= "datename(quarter,$col)";
                    break;
                case 'D':
                case 'd':
                    $s .= "replace(str(day($col),2),' ','0')";
                    break;
                case 'h':
                    $s .= "substring(convert(char(14),$col,0),13,2)";
                    break;

                case 'H':
                    $s .= "replace(str(datepart(hh,$col),2),' ','0')";
                    break;

                case 'i':
                    $s .= "replace(str(datepart(mi,$col),2),' ','0')";
                    break;
                case 's':
                    $s .= "replace(str(datepart(ss,$col),2),' ','0')";
                    break;
                case 'a':
                case 'A':
                    $s .= "substring(convert(char(19),$col,0),18,2)";
                    break;

                default:
                    if ($ch == '\\') {
                        $i++;
                        $ch = substr($fmt, $i, 1);
                    }
                    $s .= $this->qstr($ch);
                    break;
            }
        }
        return $s;
    }

    function ToSQLDate($date_time, $lang_eng = FALSE) {
        $date_time = trim($date_time);
        if (empty($date_time))
            return 'NULL';
        else {
            $temp = explode(' ', $date_time);

            $arrDate = explode('/', $temp[0]);
            if ($lang_eng == FALSE)
                $yy = $arrDate[2] - 543;
            else
                $yy = $arrDate[2];
            $mm = str_pad($arrDate[1], 2, '0', STR_PAD_LEFT);
            $dd = str_pad($arrDate[0], 2, '0', STR_PAD_LEFT);
            $date = $yy . '-' . $mm . '-' . $dd;

            $time = '00:00:00';
            if (sizeof($temp) > 1) {
                $arrTime = explode(':', $temp[1]);
                $time = str_pad($arrTime[0], 2, '0', STR_PAD_LEFT) . ':' . str_pad($arrTime[1], 2, '0', STR_PAD_LEFT) . ':' . str_pad(floor($arrTime[2]), 2, '0', STR_PAD_LEFT);
            }
            return "CONVERT(datetime,'" . $date . " " . $time . "',120)";
        }
    }

    function returnBetweenDate($field, $date_time, $lang_eng = FALSE) {
        $temp = explode(' ', $date_time);

        $arrDate = explode('/', $temp[0]);
        if ($lang_eng == FALSE)
            $yy = $arrDate[2] - 543;
        else
            $yy = $arrDate[2];
        $mm = str_pad($arrDate[1], 2, '0', STR_PAD_LEFT);
        $dd = str_pad($arrDate[0], 2, '0', STR_PAD_LEFT);
        $date = $yy . '-' . $mm . '-' . $dd;


        return "({$field} >= CONVERT(datetime,'" . $date . " 00:00:00',120)) AND ({$field} <= CONVERT(datetime,'" . $date . " 23:59:59',120))";
    }

}
