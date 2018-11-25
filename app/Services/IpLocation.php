<?php

namespace App\Services;

class IpLocation
{
    const COUNTRY = 'country';
    const COUNTRY_CODE = 'countryCode';
    const CITY = 'city';
    const LAT = 'lat';
    const LON = 'lon';
    const REGION = 'region';
    const REGION_NAME = 'regionName';
    const TIMEZONE = 'timezone';

    protected static $instances = [];

    protected $data = null;
    protected $ip;
    protected $api_url = 'http://ip-api.com/json/';

    /**
     * @param $ip string
     * @return IpLocation
     */
    public static function getInstanceFromIp(string $ip) {
        if (isset(self::$instances[$ip]))
            return self::$instances[$ip];

        self::$instances[$ip] = new IpLocation($ip);
        return self::$instances[$ip];
    }

    private function __construct(string $ip) {
        $this->ip = $ip;
    }

    public function get($key, $default = null) {
        if (is_null($this->data)) {
            $this->requestLocationData();
        }

        if (!empty($this->data) and isset($this->data[$key]))
            return $this->data[$key];

        return $default ?? $this->resolveDefault($key);
    }

    private function requestLocationData() {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->getUrl()
        ));

        $response = json_decode(curl_exec($curl), true);

        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($code === 200 && $response) {
            $this->data = $response;
        } else {
            $this->data = [];
        }
    }

    private function getUrl() {
        return $this->api_url . $this->ip;
    }

    private function resolveDefault($key) {
        switch ($key) {
            case self::COUNTRY_CODE: return env('DEFAULT_COUNTRY', 'US');
            case self::COUNTRY: return 'United States';
            default: return null;
        }


    }
}
