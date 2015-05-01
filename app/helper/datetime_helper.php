<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 18/2/2558
 * Time: 10:29
 */




if (!function_exists('datetime_to_sql')) {
    function datetime_to_sql($datetime)
    {
        if (!empty($datetime)) {
            $tmp = explode(' ', $datetime);
            $date = explode('/', $tmp[0]);
            $time = '';
            if (!empty($tmp[1])) {
                $time = $tmp[1];
            }
            $y = ($date[2] > 2400 ? $date[2] - 543 : $date);
            $sql_time = "{$y}-{$date[1]}-{$date[0]} {$time}";
            return $sql_time;
        }
        return false;

    }
}

if (!function_exists('sql_to_date')) {
    function sql_to_date($datetime)
    {
        $d = new DateTime($datetime);
        if($d){
            return $d->format('d/m/') . ($d->format('Y') + 543);
        }
        return false;
    }
}

if (!function_exists('view_date')) {

//put your code here
    function view_date($d, $full = false) {
        $d = trim($d);
        $month = array("ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        $monthFull = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $weekFull = array("วันอาทิตย์ ที่", "วันจันทร์ ที่", "วันอังคาร ที่", "วันพุธ ที่", "วันพฤหัสบดี ที่", "วันศุกร์ ที่", "วันเสาร์ ที่");
        $tmp = explode(" ", $d);
        if (!empty($d)) {
            if (strpos($tmp[0], '/') > 0) {
                $date = explode("/", $tmp[0]);
                $fyear = $date[2];
                $fmonth = $date[1];
                $fday = $date[0];
            } else {

                $date = explode("-", $tmp[0]);
                if (strpos($tmp[0], '-') > 0) {
                    $fyear = $date[0];
                    $fmonth = $date[1];
                    $fday = $date[2];
                }
                if (sizeof($date) == 3) {
                    $fyear = $date[0];
                    $fmonth = $date[1];
                    $fday = $date[2];
                }
            }
            if ($fyear < 2200) {
                $fyear = $fyear + 543;
            }


            switch ($full) {
                case 'mm':
                    $finish_date = (int) $fday . " " . $monthFull[(int) $fmonth - 1] . " " . $fyear;
                    break;
                case 'wf':
                    $w = date("w", mktime(0, 0, 0, $fmonth, $fday, ($fyear > 2200 ? $fyear - 543 : $fyear)));
                    $finish_date = $weekFull[$w] . ' ' . intval($fday) . ' ' . $monthFull[(int) $fmonth - 1] . ' ' . $fyear;
                    break;
                case 'sh':
                    $fyear = substr($fyear, '2', '2');
                    $finish_date = $fday . "/" . $fmonth . "/" . $fyear;
                    $finish_date = ($finish_date == "00/00/543") ? "" : $finish_date;
                    $finish_date = ($finish_date == "0//543") ? "" : $finish_date;
                    $finish_date = ($finish_date == "//543") ? "" : $finish_date;
                    break;
                case 'dt':
                    $finish_date = $fday . "/" . $fmonth . "/" . $fyear;
                    $finish_date = ($finish_date == "00/00/543") ? "" : $finish_date;
                    $finish_date = ($finish_date == "0//543") ? "" : $finish_date;
                    $finish_date = ($finish_date == "//543") ? "" : $finish_date;
                    $time = explode(".", $tmp[1]);
                    $finish_date.= " " . $time[0];
                    break;
                default :
                    $finish_date = $fday . "/" . $fmonth . "/" . $fyear;
                    $finish_date = ($finish_date == "00/00/543") ? "" : $finish_date;
                    $finish_date = ($finish_date == "0//543") ? "" : $finish_date;
                    $finish_date = ($finish_date == "//543") ? "" : $finish_date;
                    break;
            }

            //echo $finish_date;
            return $finish_date;
        }
    }

}