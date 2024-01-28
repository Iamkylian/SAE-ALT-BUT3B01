<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/header.css" rel="stylesheet">
    <title>Deuxième étage</title>
</head>
<body>

<?php
include("../connect.inc.php");

$query = $conn->prepare("SELECT d.room, Donnes.activity, Donnes.illumination, Donnes.time FROM Device d, Donnes
WHERE d.id = Donnes.idDevice ORDER BY Donnes.time;");

$query->execute();

$result = $query->get_result();

$devices = array();

// Parcourir les résultats et les stocker dans le tableau
while ($row = $result->fetch_assoc()) {
    // Ajouter la ligne complète au tableau
    $devices[] = $row;
}

// Tableau contenant seulement le nom des salles.
$roomNames = array_column($devices, 'room');
?>

<style>

    h2 { 
        color: white;
    }

    #occupation {
        display: flex;
        flex-direction: row;
    }

    .occupation-level {
      width: 10px;
      height: 20px;
      border: 1px solid #000;
      position: relative;
      margin-left: 10px;
    }

    .occupation-fill {
      height: 100%;
      top: 0;
      left: 0;
    }

    .black { background-color: #000; }
    .red { background-color: #FF0000; }
    .yellow {background-color: yellow;}
    .green { background-color: rgb(106, 254, 0); }

    g {
        fill: rgb(183, 232, 247);
        stroke: #edf0f1;
        fill-opacity: 1;
        stroke-width: 1px;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-opacity: 1;
        transition: fill 1.2s, stroke 1s;
    }

    .changeColor path {
        fill-opacity: 1;
        stroke-width: 1px;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-opacity: 1;
        transition: fill 0.5s, stroke 0.5s; 
    }

    .changeColor:hover path {
        fill: rgb(0, 240, 255);
        stroke: rgb(51, 102, 204);
    }

<?php
$roomIDs = [
    'B001', 'B002', 'B003', 'B004', 'B005', 'B006', 'B007', 'B008', 'B009', 'B010'
];

foreach ($roomIDs as $roomId) {
    $style = "fill:" . (in_array(trim($roomId), $roomNames, true) ? 'rgb(106, 254, 0)' : 'rgb(60, 60, 60)') . ';';

    $filteredDevices = array_filter($devices, function ($device) use ($roomId) {
        return $device['room'] == $roomId;
    });

    $lastEntry = end($filteredDevices);

    if ($lastEntry) {
        $activity = $lastEntry['activity'];
        $illumination = $lastEntry['illumination'];

        if ($activity > 100 && $illumination > 30) {
            $style = "fill: red;";
        } elseif (30 < $activity && $activity < 100 && 5 < $illumination && $illumination < 30) {
            $style = "fill: yellow;";
        } else {
            $style = "fill: rgb(76, 204, 0);";
        }

        echo "#$roomId .activity { content: '$activity'; }";
        echo "#$roomId .luminosité { content: '$illumination'; }";
    }

    echo "#$roomId { $style }";
}


?>
</style>

<?php include_once('../Parts/navbar.php') ?>

<div class="container-fluid">


<svg  viewBox="50 250 990 320">
        <g id="rgt" class="changeColor">
            <title>RGT</title>
            <path id="path4163" d="m 608.19842,259.42256 21.57668,0.55555 -6.3626,88.06307 -19.02962,-0.27646 z"/>
        </g>
        <g id="magasin" class="changeColor">
            <title>Magasin</title>
            <path id="path4258" d="m 907.97947,435.3071 9.75036,2.17174 c 4.81623,21.39286 18.55939,22.092 31.43436,24.80373 l 8.17867,20.57223 -28.03037,56.07916 -54.14,-14.42874 z"/>
        </g>
        <g id="cafet" class="changeColor">
            <title>Cafeteria</title>
            <path id="path2880" d="m 463.7455,494.71634 -11.01943,-84.81747 31.57454,-0.86689 -4.71664,-56.09376 -17.54393,1.52864 -8.47191,-88.74264 c 50.0573,-5.53876 102.05063,-6.59846 154.63029,-6.30166 l -6.18129,143.11691 32.03402,0.90898 -4.82149,87.55105 c -53.64422,-2.20157 -108.77424,-1.03376 -165.48416,3.71684 z"/>
        </g>
        <g id="couloir">
            <path id="path6616" d="m 411.61595,500.27503 c 17.09537,-2.29899 34.61744,-3.92095 52.12955,-5.55869 l -11.01943,-84.81747 -17.73407,0.48688 -4.29195,-40.94227 -21.93665,2.24682 -54.7299,13.50085 -78.75144,19.42652 -89.65025,22.11506 -26.12719,6.44509 c -8.45809,-5.25059 -17.13725,-8.24012 -26.267,-6.62089 -10.55131,1.84676 -17.09035,6.64205 -22.29071,12.33431 -4.73297,5.18067 -5.80855,11.22502 -4.35039,18.91298 1.46886,7.74436 5.13874,13.40011 10.61932,18.00039 6.5508,4.50137 14.33769,7.19463 23.38408,8.04551 11.28084,-0.40407 21.30946,-2.35685 26.47089,-10.32924 3.17742,-4.71206 5.26086,-9.42692 6.36431,-14.14428 l 40.4988,-9.94065 89.26075,-21.90961 92.03142,-22.58967 z"/>
            <path id="path7278" d="m 917.72983,437.47884 8.42702,-29.67102 c 5.80199,-6.18992 13.00916,-8.28022 20.72846,-8.8764 11.74767,-0.21551 17.95302,2.82933 22.51552,6.84059 4.85222,4.09563 8.26239,9.65164 11.34744,15.53691 1.97654,15.9159 -2.22333,22.4002 -7.28471,27.56894 l -24.29937,13.40471 c -8.65706,-0.89049 -16.45731,-3.1327 -22.56153,-8.05066 -3.48881,-3.28326 -6.97452,-6.57995 -8.87283,-16.75307 z"/>
            <path id="path7679" d="m 629.22966,490.9995 51.80224,3.06383 11.30254,-97.94636 50.89917,6.49463 4.28383,-32.29671 -91.76939,-9.52467 -2.11528,43.21386 -19.58162,-0.55563 z"/>
        </g>
        <g id="san" class="changeColor">
            <title>SAN.</title>
            <path id="path7675" d="m 408.7634,371.6903 v -12.58135 l 70.82057,-6.17073 4.71664,56.09376 -49.30861,1.35377 -4.29195,-40.94227 z"/>
            <path id="path7677" d="m 604.38288,347.76472 71.8438,1.04375 -0.52908,14.0523 -19.94955,-2.07055 -2.11528,43.21386 -51.61564,-1.46461 z"/>
        </g>
        <a href="salle.php?salle=B001">
            <g id="B001" class="changeColor">
                <title>B_001</title>
                <path id="path4166" d="m 623.4125,348.04118 52.81418,0.76729 -0.52908,14.0523 71.81984,7.45412 16.22241,-98.99891 c -43.79427,-6.14991 -88.57809,-9.57414 -133.96476,-11.33787 z"/>
            </g>
        </a>
        <a href="salle.php?salle=B002">
            <g id="B002" class="changeColor">
                <title>B_002</title>
                <path id="path4576" d="m 681.0319,494.06333 11.30254,-97.94636 c 35.25264,3.30875 71.61858,9.55191 108.37308,16.8192 l -21.52749,92.61469 c -33.37067,-5.10702 -66.15643,-9.07228 -98.14813,-11.48753 z"/>
            </g>
        </a>

        <a href="salle.php?salle=B003">
            <g id="B003" class="changeColor">
                <title>B_003</title>
                <path id="path4913" d="m 743.2288,402.61085 57.47868,10.32532 -21.5275,92.61469 c 30.9278,4.83673 62.8043,10.9873 95.9925,18.95436 l 32.807,-89.19812 9.7504,2.17174 8.427,-29.67102 c 8.6147,-7.95655 19.8282,-12.28494 35.3393,-6.79641 8.7588,3.09929 14.8039,10.67797 19.2521,20.29751 l 10.3094,-3.02319 36.34142,-84.94117 C 941.57278,304.31795 853.65608,283.76458 763.73985,271.31598 l -16.22241,98.99891 z"/>
            </g>
        </a>

        <a href="salle.php?salle=B004">
            <g id="B004" class="changeColor">
                <title>B_004</title>
                <path id="path3599" d="m 140.59992,483.85012 -11.3084,26.89069 24.7832,56.69992 c 27.13522,-12.18017 57.45721,-22.63375 89.16825,-32.33475 l -29.30905,-85.67003 -40.4988,9.94065 c -3.06794,17.02996 -14.60872,24.51685 -32.8352,24.47352 z"/>
            </g>
        </a>

        <a href="salle.php?salle=B005">
            <g id="B005" class="changeColor">
                <title>B_005</title>
                <path id="path3768" d="m 213.93392,449.43595 89.26075,-21.90961 23.88503,86.71268 c -28.34943,5.64032 -56.22396,12.82738 -83.83673,20.86696 z"/>
            </g>
        </a>
        <a href="salle.php?salle=B006">
            <g id="B006" class="changeColor">
                <title>B_006</title>
                <path id="path3860" d="m 303.19467,427.52634 92.03142,-22.58967 16.38986,95.33836 c -29.97523,3.69018 -57.58939,8.64796 -84.53625,13.96399 z"/>
            </g>
        </a>

        <a href="salle.php?salle=B007">
            <g id="B007" class="changeColor">
                <title>B_007</title>
                <path id="path3051" d="m 453.56813,265.72422 8.47191,88.74264 -53.27664,4.64209 v 12.58135 l -54.7299,13.50085 -22.00964,-101.91032 c 39.33772,-7.20538 79.38231,-13.59811 121.54427,-17.55661 z"/>
            </g>
        </a>

        <a href="salle.php?salle=B008">
            <g id="B008" class="changeColor">
                <title>B_008</title>
                <path id="path3144" d="m 243.62336,302.95689 c 28.7877,-7.05037 57.63366,-14.05856 88.4005,-19.67606 l 22.00964,101.91032 -78.75144,19.42652 z"/>
            </g>
        </a>

        <a href="salle.php?salle=B009">
            <g id="B009" class="changeColor">
                <title>B_009</title>
                <path id="path3147" d="m 146.79542,331.11587 c 27.28082,-8.85365 57.22303,-17.99111 96.82794,-28.15898 l 31.6587,101.66078 -89.65025,22.11506 z"/>
            </g>
        </a>

        <a href="salle.php?salle=B010">
            <g id="B010" class="changeColor">
                <title>B_010</title>
                <path id="path3354" d="m 58.614622,362.70239 88.180798,-31.58652 38.83639,95.61686 -26.12719,6.44509 c -11.45798,-7.50656 -22.61479,-9.04978 -33.0913,-4.9618 -15.0115,5.85755 -17.69079,13.23839 -20.5334,20.16686 l -10.957198,-3.78735 z"/>
            </g>
        </a>

    </svg>

    <div id="title">
        <h2>Occupation</h2>
    </div>
        <div id="occupation">
            <div class="occupation-level">
                <div class="occupation-fill black"></div>
            </div>
            <h2>No Data</h2>
            <div class="occupation-level">
                <div class="occupation-fill red"></div>
            </div>
            <h2>Occupée</h2>
            <div class="occupation-level">
                <div class="occupation-fill yellow"></div>
            </div>
            <h2>Possiblement occupée</h2>
            <div class="occupation-level">
                <div class="occupation-fill green"></div>
            </div>
            <h2>Libre</h2>
        </div>

    <?php
    ini_set('display_errors', 1);
    ?>
</body>
</html>