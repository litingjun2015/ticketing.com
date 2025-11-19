<?php
if (!function_exists('fzly_checkDateFormat')) {
    function fzly_checkDateFormat($date)
    {
        //匹配日期格式
        if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {
            //检测是否为日期
            if (checkdate($parts[2], $parts[3], $parts[1])) {
                return true;
            }
        }
        return false;
    }
}
if (!function_exists('fzly_orderNo')) {

    function fzly_orderNo()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}
if (!function_exists('fzly_getDistanceBuilder')) {
    function fzly_getDistanceBuilder($lat, $lng) {
        return "ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN((". fzly_matchLatLng($lat) . " * PI() / 180 - lat * PI() / 180) / 2), 2) + COS(". fzly_matchLatLng($lat). " * PI() / 180) * COS(lat * PI() / 180) * POW(SIN((". fzly_matchLatLng($lng). " * PI() / 180 - lon * PI() / 180) / 2), 2))) * 1000) AS distance";
    }
}

if (!function_exists('get_distance')){
    function get_distance($from,$to,$km=true,$decimal=2){
        sort($from);
        sort($to);
        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $distance = $EARTH_RADIUS*2*asin(sqrt(pow(sin( ($from[0]*pi()/180-$to[0]*pi()/180)/2),2)+cos($from[0]*pi()/180)*cos($to[0]*pi()/180)* pow(sin( ($from[1]*pi()/180-$to[1]*pi()/180)/2),2)))*1000;
        if($km){
            $distance = $distance / 1000;
        }
        return round($distance, $decimal);
    }
}

if (!function_exists('fzly_matchLatLng')) {
    function fzly_matchLatLng($latlng) {
        $match = "/^\d{1,3}\.\d{1,30}$/";
        return preg_match($match, $latlng) ? $latlng : 0;
    }
}

if (!function_exists('fzly_format_date')) {
    function fzly_format_date($time){
        $t=time()-$time;
        $f=array(
            '31536000'=>'年',
            '2592000'=>'个月',
            '604800'=>'星期',
            '86400'=>'天',
            '3600'=>'小时',
            '60'=>'分钟',
            '1'=>'秒'
        );
        foreach ($f as $k=>$v)    {
            if (0 !=$c=floor($t/(int)$k)) {
                return $c.$v;
            }
        }
        return $f;
    }
}

if (!function_exists('fzly_format_dates')) {
    function fzly_format_dates($time){
        $t=time()-$time;
        $f=array(
            '31536000'=>'年',
            '2592000'=>'个月',
            '604800'=>'星期',
            '86400'=>'天',
            '3600'=>'小时',
            '60'=>'分钟',
            '1'=>'秒'
        );
        foreach ($f as $k=>$v)    {
            if (0 !=$c=floor($t/(int)$k)) {
                return $c.$v.'前';
            }
        }
        return '一秒前';
    }
}

if (!function_exists('fzly_generateRandomString')) {
    function fzly_generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}

if (!function_exists('fzly_getDaysArrayWithWeekday')){
    function fzly_getDaysArrayWithWeekday($year, $month) {
        $weekDays = [
            'Monday'    => '1',
            'Tuesday'   => '2',
            'Wednesday' => '3',
            'Thursday'  => '4',
            'Friday'    => '5',
            'Saturday'  => '6',
            'Sunday'    => '7'
        ];
        $first_day = new \DateTime("$year-$month-01");
        $last_day = new \DateTime("$year-$month-" . $first_day->format('t'));

        $date_range = new DatePeriod($first_day, new DateInterval('P1D'), $last_day->modify('+1 day'));

        $daysArray = array();

        foreach ($date_range as $date) {
            $day = array(
                "date" => $date->format("Y-m-d"),
                "weekday" => $weekDays[$date->format('l')] // 获取星期几
            );
            $daysArray[] = $day;
        }

        return $daysArray;
    }
}

