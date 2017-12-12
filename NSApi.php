<?php

/**
 * Created by J.J. Strootman
 * Date: 12-09-15
 * Time: 20:20
 */

class NSApi
{
    private $username;
    private $password;
    private $baseUrl = "http://webservices.ns.nl/ns-api-";
    private $info;
    private $data;
    private $curlObject;

    /**
     * @param $username
     * @param $password
     */
    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
        $this->curlObject = curl_init();
    }

    public function __destruct() {
        curl_close($this->curlObject);
    }

    /**
     *
     */
    private function callApi($url, $key) {
        curl_setopt($this->curlObject, CURLOPT_URL, $this->baseUrl . $url);
        curl_setopt($this->curlObject, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curlObject, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($this->curlObject, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        $this->data[$key] = simplexml_load_string(curl_exec($this->curlObject));
        $this->info = curl_getinfo($this->curlObject);
    }

    public function getStations() {
        $this->callApi("stations-v2", 'stations');

        return $this->data['stations']->Station;
    }

    public function getTravelInfo($from, $to, array $extra) {
        $url = "treinplanner?fromStation=$from&toStation=$to";

        if (array_key_exists('via',$extra))
            $url .= "&viaStation={$extra['via']}";

        if (array_key_exists('datetime', $extra))
            $url .= "&dateTime={$extra['datetime']}&departure=" .
                (array_key_exists('departure', $extra) ? ($extra['departure'] ? "true" : "false") : 'true');

        $this->callApi($url, 'reisadvies');

        return $this->data['reisadvies'];
    }

    public function getActueleVertrektijden($station) {
        $url = 'avt?station='.$station;

        $this->callApi($url, 'avt');

        return $this->data['avt'];
    }
}
