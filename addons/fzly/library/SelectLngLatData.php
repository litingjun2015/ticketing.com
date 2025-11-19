<?php

namespace addons\fzly\library;

/**
 * @name: 经纬度查询类
 * @author: Turbo
 * @Date: 2022-09-20 10:44:36
 */
class SelectLngLatData
{
    protected static $instance;
    /**
     * @var array 配置
     */
    protected $option = [];
    /**
     * @var int 当前时间
     */
    protected $time = 0;
    /**
     * @var string 错误信息
     */
    protected $errorMessage = '';

    public function __construct($lng = 0, $lat = 0)
    {
        $this->time = time();
        $this->option['lng'] = $lng;
        $this->option['lat'] = $lat;
    }

    private function __clone()
    {

    }

    /**
     * 初始化
     * @param  $lng
     * @param  $lat
     * @return SelectLngLatData
     */
    public static function instance($lng = 0, $lat = 0)
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($lng, $lat);
        }

        return self::$instance;
    }

    public function getLongLatInfo()
    {
        if (!empty($this->option['lng'])) {
            $longitude = $this->option['lng'];
        } else {
            return$this->setError('请传输经度');

        }
        if (!empty($this->option['lat'])) {
            $latitude = $this->option['lat'];
        } else {
            return$this->setError('请传输纬度');

        }
        $longitude = number_format(doubleval($longitude), 6);
        $latitude = number_format(doubleval($latitude), 6);
        $config = get_addon_config('fzly');
        if (empty($config['tx_map_key'])) {
            return $this->setError('请配置腾讯地图key');

        }
        $key = $config['tx_map_key']; // 腾讯地图key值
        $url = 'https://apis.map.qq.com/ws/geocoder/v1?key=' . $key . '&location=' . $latitude . ',' . $longitude;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $content = curl_exec($ch);
        curl_close($ch);
        if (!empty($content)) {
            $result = json_decode($content, true);
            if ($result['status'] == 0) {
                return $result['result'];
            } else {
                return$this->setError($result['message']);

            }
        } else {
            return$this->setError('经纬度不合法，请重新尝试');

        }
    }

    /**
     * 设置错误信息
     * @param string $msg 错误信息
     */
    protected function setError($msg)
    {
        $this->errorMessage = $msg;
        return ['code'=>0,'msg'=>$msg];
    }

}