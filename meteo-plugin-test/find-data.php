<?php
define('SHORTINIT', true);
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
class YandexData{
    private $lat;
    private $lon;
    private $options;

    function __construct()
    {
        $this->find_option();
    }
    private function find_option() {
        $this->options = get_option('meteo_test_option');
        if ($this->options['on_auto_geo_ip']){
            $ch = curl_init('http://ip-api.com/json/2a00:1760:8107:bc:8cb2:207a:a7b:fd41?lang=ru');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $res = curl_exec($ch);
            curl_close($ch);
            $res = json_decode($res, true);
            $this->lat = $res['lat'];
            $this->lon = $res['lon'];
        }else{
            $this->lat = $this->options['geo_lat'];
            $this->lon = $this->options['geo_lon'];
        }
    }
    function request(){
        $token = 'X-Yandex-API-Key: '.$this->options['token_yandex'];
        $get = [
            'lat' => $this->lat,
            'lon' => $this->lon,
            'lang' => 'ru_RU',
            'limit' => '1',
            'hours' => 'false',
            'extra' => 'false',
        ];
        $ch = curl_init('https://api.weather.yandex.ru/v2/forecast/?' . http_build_query($get));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER,[$token]);
        $request = curl_exec($ch);
        curl_close($ch);
        $request = json_decode($request);
        $array_info = [];
        $array_info['city'] = $request->geo_object->locality->name;
        $array_info['temp'] = $request->fact->temp;
        $array_info['wind_speed'] = $request->fact->wind_speed;
        $array_wind_dir = [
            'nw' => 'северо-западное',
            'n' => 'северное',
            'ne' => 'северо-восточное',
            'e' => 'восточное',
            'se' => 'юго-восточное',
            's' => 'южное',
            'sw' => 'юго-западное',
            'w' => 'западное',
            'c' => 'штиль',
        ];
        $wind_key = $request->fact->wind_dir;
        $array_info['wind_dir'] = $array_wind_dir[$wind_key];

        return $array_info;
    }
}

