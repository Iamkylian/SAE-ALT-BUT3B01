<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/header.css" rel="stylesheet">
    <title>Document</title>
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
        transition: fill 0.5s, stroke 0.5s; /* Adjust the duration as needed */
    }

    .changeColor:hover path {
        fill: rgb(0, 240, 255);
        stroke: rgb(51, 102, 204);
    }

<?php
$roomIDs = ['B101', 'B102', 'B103', 'B104', 'B105', 'B106', 'B107', 'B108', 'B109', 'B110', 'B111', 'B112', 'B113', 'B114', 'B115', 'B116a', 'B116b'];

// Afficher le style pour chaque chambre avec activity et temperature
foreach ($roomIDs as $roomId) {
    $style = "fill:" . (in_array(trim($roomId), $roomNames, true) ? 'rgb(106, 254, 0)' : 'rgb(60, 60, 60)') . ';';

    // Trouver la dernière donnée envoyée par le device
    $filteredDevices = array_filter($devices, function ($device) use ($roomId) {
        return $device['room'] == $roomId;
    });

    // Get the last entry
    $lastEntry = end($filteredDevices);

    // Si une entrée est trouvée, afficher salle rouge si occupée (activity > 100 & luminosity > 30), sinon vert ou jaune
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


<svg viewBox="0 0 877.99769 300">
        <g id="san" class="changeColor">
            <title>SAN.</title>
            <path id="path4040" d="m 411.34315,46.153649 c 23.72452,0.38611 47.78547,0.7777 72.02524,1.1722 l -3.79645,46.59951 -66.23478,-0.41214 z"/>
            <path id="path4042" d="m 438.08215,46.588819 0.68176,47.08262"/>
            <path id="path4044" d="m 434.6428,65.371529 15.91087,0.17637"/>
            <path id="path4046" d="m 438.47767,73.903559 10.01972,0.0777 0.10225,-8.45502"/>
            <path id="path4048" d="m 466.35913,47.049019 -1.11422,19.96551"/>
            <path id="path4050" d="m 462.3095,67.014529 h 5.92589"/>
            <path id="path4052" d="m 476.83327,66.795079 4.92512,0.2926"/>
            <path id="path4054" d="m 412.14339,65.159969 3.85613,-0.0825"/>
            <path id="path4056" d="m 421.74015,65.686059 6.85286,-0.03"/>
            <path id="path4058" d="m 425.60586,65.686059 -0.24001,-19.3042"/>
        </g>
        <g id="sousStation">
            <title>Sous-station</title>
            <path id="path4150" d="m 464.73646,183.70759 7.52828,0.0186 3.59238,-50.02195 -8.82476,-0.26083 z"/>
            <path id="path4152" d="m 466.03859,155.20002 8.27356,0.017"/>
            <path id="path4154" d="m 411.62583,133.55901 7.76162,-0.54805 1.06608,36.00524 -8.47319,0.64784 z"/>
        </g>
        <g id="rgt">
            <title>rengement</title>
            <path id="path4156" d="m 370.1941,136.5927 41.43173,-3.03369 0.25583,26.05543 -39.28411,3.46335 z"/>
        </g>
        <g id="couloir">
            <title>Couloir</title>
            <path id="path4160" d="m 422.01693,221.81781 40.99971,-0.4579 4.01572,-87.9165 c 77.8224,5.91479 132.66954,1.68759 301.83145,47.92244 6.91445,16.3983 15.97422,17.42538 25.17783,17.42174 9.24991,-1.42047 18.49114,-1.65072 27.84514,-17.33542 1.88491,-9.16453 3.25357,-18.81819 -4.34172,-28.53588 -4.00288,-5.12143 -9.05938,-9.73686 -22.97551,-7.94556 -7.53772,0.97026 -13.14903,4.73027 -16.34498,10.95114 C 682.7389,125.54281 581.57546,113.63102 478.40519,108.24663 l 1.16675,-14.321271 -66.23478,-0.41214 0.40645,11.662951 c -98.54955,5.76522 -199.48262,16.5636 -313.38954,54.75829 -7.650273,-6.88175 -15.720033,-10.77785 -25.642503,-8.64105 -10.62867,2.28888 -16.73366,8.2785 -21.19727,17.64928 -4.2759,8.97671 -4.98113,16.88222 1.91812,27.56566 5.73238,8.87655 12.3846,13.0484 21.3185,13.21796 10.31193,0.19571 20.02395,-2.69571 27.817673,-11.98129 2.86055,-3.92184 5.02453,-7.93287 6.13121,-12.0793 70.88503,-23.79138 157.0172,-44.61068 308.68765,-52.65476 z"/>
        </g>

        <a href='salle.php?salle=B101'>
            <g id="B101" class="changeColor">
                <title>B_101</title>
                <path id="path5658" d="m 312.66082,113.32755 c 32.18625,-3.83968 65.89768,-6.54403 101.08279,-8.15138 l -3.85569,-93.585721 c -36.4258,1.38388 -72.23244,4.52013 -107.62361,8.83227 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B102'>
            <g id="B102" class="changeColor">
                <title>B_102</title>
                <path id="path5902" d="m 218.32871,128.54053 c 31.3953,-6.30443 62.8293,-11.62948 94.33211,-15.21298 L 302.26431,20.422719 c -36.15762,4.33875 -71.34911,10.6068 -106.0989,17.75691 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B103'>
            <g id="B103" class="changeColor">
                <title>B_103</title>
                <path id="path6032" d="m 102.25706,61.711469 c 30.83507,-8.68128 61.86762,-17.00902 93.90835,-23.53184 l 22.1633,90.360901 c -27.56145,5.62817 -55.18818,12.60391 -82.86466,20.60635 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B104'>
            <g id="B104" class="changeColor">
                <title>B_104</title>
                <path id="path6162" d="m 110.6998,185.66572 38.93726,-11.78931 25.96808,87.77854 c -28.4839,8.32013 -57.27527,18.60577 -86.203713,29.76767 l -21.91,-57.86595 9.25949,-23.83036 c 11.59527,0.11209 19.73724,-3.86389 26.067023,-9.9604 4.09567,-3.94473 6.08882,-8.99424 7.88186,-14.10019 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B105'>
            <g id="B105" class="changeColor">
                <title>B_105</title>
                <path id="path6406" d="m 149.63706,173.87641 c 30.00718,-8.32761 60.78703,-16.23808 95.08735,-22.24798 l 18.89053,88.1643 c -30.18975,6.08433 -59.56805,13.31294 -88.0098,21.86222 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B106'>
            <g id="B106" class="changeColor">
                <title>B_106</title>
                <path id="path6536" d="m 244.72441,151.62843 c 30.60334,-5.17445 61.55499,-9.71797 93.56699,-12.34079 l 12.34755,87.33653 c -33.10794,3.47902 -61.25307,8.06016 -87.02401,13.16856 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B107'>
            <g id="B107" class="changeColor">
                <title>B_107</title>
                <path id="path6704" d="m 560.0311,139.53609 c 31.09281,3.25157 62.03017,7.87022 92.83507,13.65361 l -20.55433,83.68832 c -27.38774,-4.61188 -54.9667,-8.93566 -83.83423,-11.31808 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B108'>
            <g id="B108" class="changeColor">
                <title>B_108</title>
                <path id="path6910" d="m 463.01664,221.35991 1.71982,-37.65232 7.52828,0.0186 3.59238,-50.02195 c 28.09423,0.9607 56.1545,2.84309 84.17398,5.83185 l -11.55349,86.02385 c -27.88071,-2.16645 -55.8284,-4.24822 -85.46097,-4.20003 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B109'>
            <g id="B109" class="changeColor">
                <title>B_109</title>
                <path id="path7040" d="m 478.40519,108.24663 c 23.94467,0.8006 48.29368,2.4467 72.83057,4.48569 l 10.99376,-98.300301 c -25.15533,-1.68229 -50.26828,-3.50216 -75.87947,-3.70448 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B109b'>
            <g id="B109B" class="changeColor">
                <title>B_109b</title>
                <path id="path7248" d="m 409.88792,11.590449 1.45523,34.5632 72.02524,1.1722 2.98166,-36.59831 c -26.05669,-0.5198 -51.43523,-0.0778 -76.46213,0.86291 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B110'>
            <g id="B110" class="changeColor">
                <title>B_110</title>
                <path id="path7340" d="m 562.22952,14.432019 c 24.87916,2.02615 49.77904,4.80547 74.70484,8.52737 l -18.79517,97.648831 c -22.15383,-3.06375 -44.3301,-6.06072 -66.90343,-7.8759 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B111'>
            <g id="B111" class="changeColor">
                <title>B_111</title>
                <path id="path7508" d="m 636.93436,22.959389 c 23.78467,3.4809 47.52549,7.92479 71.23458,13.06565 l -22.00171,96.810951 c -22.5281,-4.50991 -45.05729,-9.01661 -68.02804,-12.22777 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B112'>
            <g id="B112" class="changeColor">
                <title>B112</title>
                <path id="path7993" d="m 716.57973,139.6648 21.94726,-96.505261 c 44.15216,10.74473 87.25011,25.37177 129.80476,41.99957 l -46.44497,96.293061 c 1.38366,-8.52305 3.1863,-17.14394 -3.26187,-27.00513 -4.80749,-7.35209 -11.56614,-9.79818 -19.13921,-9.75184 -12.40764,0.0759 -17.30672,5.22774 -21.26113,11.22667 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B112b'>
            <g id="B112B" class="changeColor">
                <title>B_112b</title>
                <path id="path7676" d="m 708.16894,36.025039 c 8.42179,1.8483 17.79734,3.99431 30.35805,7.1345 l -21.94726,96.505261 -30.4125,-6.82881 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B113'>
            <g id="B113" class="changeColor">
                <title>B_113</title>
                <path id="path8389" d="m 632.31184,236.87802 c 50.26601,8.91608 99.37561,22.17579 147.50106,39.13211 l 22.5564,-52.83704 -8.32766,-24.3855 c -6.15944,0.17634 -12.39466,-0.78649 -17.79224,-5.47083 -5.1901,-4.50428 -6.30797,-8.41398 -7.38559,-11.95091 -38.13395,-11.5108 -76.87,-20.62336 -115.99764,-28.17615 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B115'>
            <g id="B115" class="changeColor">
                <title>B_115</title>
                <path id="path8861" d="m 10.913257,91.932779 c 30.52017,-11.33811 60.96676,-21.38831 91.343803,-30.22131 l 33.20699,87.435411 -35.10998,10.78758 c -7.758963,-8.22241 -17.200533,-10.83196 -28.652613,-7.99645 -8.68038,2.14925 -14.8466,8.17383 -18.6313,18.11815 -1.73545,4.55989 -2.92285,9.33783 -3.70852,14.27814 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B116a'>
            <g id="B116A" class="changeColor">
                <title>B_116a</title>
                <path id="path4158" d="m 338.2914,139.28764 31.90296,-2.69496 3.26812,36.01632 -30.13441,2.30399 z"/>
            </g>
        </a>

        <a href='salle.php?salle=B116b'>
            <g id="B116B" class="changeColor">
                <title>B_116b</title>
                <path id="path952" d="m 350.63895,226.62417 c 23.44274,-2.41503 47.20563,-4.08629 71.37798,-4.80636 l -1.5634,-52.80161 -8.47319,0.64784 -0.0987,-10.0496 -39.28404,3.46335 0.86486,9.53121 -30.13441,2.30399 z"/>
                <path id="path954" d="m 411.98034,169.66404 0.51544,52.49465"/>
                <path id="path956" d="m 373.46248,172.609 38.51786,-2.94496"/>
            </g>
        </a>
    </svg>

    <div id="title">
            <h2>Occupation</h2>
        </div>
        <div id="occupation">
            <div class="occupation-level">
                <div class="occupation-fill black" style="width: 100%;"></div>
            </div>
            <h2>No Data</h2>
            <div class="occupation-level">
                <div class="occupation-fill red" style="width: 100%;"></div>
            </div>
            <h2>Occupée</h2>
            <div class="occupation-level">
                <div class="occupation-fill yellow" style="width: 100%;"></div>
            </div>
            <h2>Possiblement occupée</h2>
            <div class="occupation-level">
                <div class="occupation-fill green" style="width: 100%;"></div>
            </div>
            <h2>Libre</h2>
        </div>

    <?php
    ini_set('display_errors', 1);
    ?>
</body>
</html>