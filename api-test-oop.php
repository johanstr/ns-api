<?php
/**
 * Created by PhpStorm.
 * User: docent
 * Date: 13-09-15
 * Time: 12:18
 */

@include('NSApi.php');

$api = new NSApi('zet hier je eigen username(mail)', 'zet hier je eigen password');

?>
<!DOCTYPE html>
<html>
    <head>
        <title>NS API (OOP) - Test</title>

        <style>
            table {
                border-collapse: collapse;
                border: 1px solid darkblue;
            }

            table tr td {
                padding: 5px;
                text-align: left;
            }

            table tr.travel-main {
                margin: 5px 0;
                border: 1px solid darkblue;
            }

            table tr.travel-main td {
                background-color: darkblue;
                color: white;
            }

            table tr.travel-sub td:first-child {
                padding-left: 15px;
                min-width: 200px;
                text-align: right;
            }

            table tr.travel-sub td:nth-child(2) {
                min-width: 500px;
            }
        </style>
    </head>

    <body>
        <h1>Test 1 - Stations Informatie (Assen - Groningen)</h1>
        <?php
            $stations = $api->getStations();

            foreach($stations as $station) {

                if($station->Namen->Kort == 'Assen' || $station->Namen->Kort == 'Groningen') {
                    echo '<pre>';
                    print_r($station);
                    echo '</pre><hr />';
                }
            }
        ?>

        <h1>Test 2 - Reisadvies (Assen - Groningen)</h1>
        <?php
            $reisadvies = $api->getTravelInfo('ASN', 'GN', array(
                'datetime' => '2015-09-14T10:00'
            ));

            echo '<table>';
            foreach($reisadvies as $advies) {
                $travelDateStart = new DateTime($advies->ReisDeel->ReisStop[0]->Tijd);
                $travelDateEnd = new DateTime($advies->ReisDeel->ReisStop[1]->Tijd);

                $timeStart = $travelDateStart->format('H:i');
                $timeEnd = $travelDateEnd->format('H:i');
                echo "<tr class='travel-main'>
                        <td colspan='2'>
                            Reistijd: {$advies->GeplandeReisTijd}<br />
                            <i>{$advies->ReisDeel->VervoerType}</i>&nbsp;-&nbsp;
                            <i>Treinnummer: {$advies->ReisDeel->RitNummer}</i>
                        </td>
                      </tr>
                      <tr class='travel-sub'>
                        <td><b>Vertrek:</b></td>
                        <td>
                            Station: {$advies->ReisDeel->ReisStop[0]->Naam}<br />
                            Tijd: $timeStart<br />
                            Spoor: {$advies->ReisDeel->ReisStop[0]->Spoor}
                        </td>
                      </tr>
                      <tr class='travel-sub'>
                        <td><b>Aankomst:</b></td>
                        <td>
                            Station: {$advies->ReisDeel->ReisStop[1]->Naam}<br />
                            Tijd: $timeEnd<br />
                            Spoor: {$advies->ReisDeel->ReisStop[1]->Spoor}
                        </td>
                      </tr>";
            }
            echo '</table>';
        ?>

        <h1>Actuele vertrektijden vanaf Station Groningen Centraal</h1>
        <?php
            $avt = $api->getActueleVertrektijden('GN');
        ?>
            <table>
                <tr>
                    <td></td>
                </tr>
            </table>
        <?php

        ?>
    </body>
</html>
