# ![Alfacollege](https://www.ao-alfa.nl/img/alfa-logo-v-cmyk.png "Alfa-college") NS API

### Inleiding
De bestanden die je hier vindt zijn ten behoeve van de lessen API's van je opleiding Applicatie Ontwikkelaar.

### NS Api gebruiken in basic PHP

Het gaat hier slechts om een voorbeeld om een indruk te krijgen
van de manier waarop je een echte API kunt gebruiken in basic PHP.
  
Zet wel je eigen inloggegevens in de variabelen van de bestanden:  
* api-test-oop.php
* stations.php
  
<!-- language: php -->
```php
$username = 'je eigen username van de api (mail)';
$password = 'je eigen password voor de api';
```

#### NSApi.php
Dit is slechts een voorbeeld om te laten zien hoe je zelf
de API functionaliteit in een Object kunt plaatsen.
  
```php
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

```

### NS Api gebruiken in Laravel
Voor Laravel is er zeker 1 package die je kunt gebruiken, n.l.:  
  
  [Edofre/laravel-ns-api](https://github.com/edofre/laravel-ns-api)
  
#### Installeren

Stap 1  
```bash
composer require edofre/laravel-ns-api
```
  
Stap 2
```bash
php artisan vendor:publish --tag=config
```

#### Voorbeeld

Haal alle stations binnen:
  
```php
$api = new NsApi();
$stations = $api->getStations();
```
