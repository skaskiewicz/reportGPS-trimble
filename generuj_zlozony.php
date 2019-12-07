<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Raport GPS ver. 0.0.3</title>
        <style>
            table, th, td {
                border: 1px solid black;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <?php
        $katalog_docelowy_wgrywania = "up/";
        $plik_docelowy_wgrywania = $katalog_docelowy_wgrywania . basename($_FILES["wgraj_raport"]["name"]);
        $wgranieOK = 1;
        $czy_tekstowy = strtolower(pathinfo($plik_docelowy_wgrywania, PATHINFO_EXTENSION));
        // Sprawdź czy plik istnieje
        if (file_exists($plik_docelowy_wgrywania)) {
            echo "Plik już istnieje. Zgłoś adminowi aby usunął ręcznie.";
            $wgranieOK = 0;
        }
        // Dozwolone foramty plików
        if ($czy_tekstowy != "txt" && $czy_tekstowy != "csv") {
            echo "Tylko pliki TXT i CSV są dozwolone.";
            $wgranieOK = 0;
        }
        // Sprawdź czy wgranieOK ma wartość 0 świadczącą o błędzie
        if ($wgranieOK == 0) {
            echo "Jakiś błąd jest po drodze. Pliku nie wgrano.";
            // jeśli ma wartość 1 to rób dalej
        } else {
            if (move_uploaded_file($_FILES["wgraj_raport"]["tmp_name"], $plik_docelowy_wgrywania)) {
                //echo "Plik " . basename($_FILES["wgraj_raport"]["name"]) . " wgrano.";
                //zmień nazwę z dowolnej na standardową
                rename("$katalog_docelowy_wgrywania" . basename($_FILES["wgraj_raport"]["name"]), "$katalog_docelowy_wgrywania/wejsciowy.txt");
            } else {
                echo "Jakiś błąd w końcowym etapie wgrywania. Spróbuj jeszcze raz.";
            }
        }

        $plik_docelowy_wgrywania_pomiar_prawdziwy = $katalog_docelowy_wgrywania . basename($_FILES["pomiar_prawdziwy"]["name"]);
        $wgranieOK = 1;
        $czy_tekstowy = strtolower(pathinfo($plik_docelowy_wgrywania_pomiar_prawdziwy, PATHINFO_EXTENSION));
        // Sprawdź czy plik istnieje
        if (file_exists($plik_docelowy_wgrywania_pomiar_prawdziwy)) {
            echo "Plik już istnieje. Zgłoś adminowi aby usunął ręcznie.";
            $wgranieOK = 0;
        }
        // Dozwolone foramty plików
        if ($czy_tekstowy != "txt" && $czy_tekstowy != "csv") {
            echo "Tylko pliki TXT i CSV są dozwolone.";
            $wgranieOK = 0;
        }
        // Sprawdź czy wgranieOK ma wartość 0 świadczącą o błędzie
        if ($wgranieOK == 0) {
            echo "Jakiś błąd jest po drodze. Pliku nie wgrano.";
            // jeśli ma wartość 1 to rób dalej
        } else {
            if (move_uploaded_file($_FILES["pomiar_prawdziwy"]["tmp_name"], $plik_docelowy_wgrywania_pomiar_prawdziwy)) {
                //echo "Plik " . basename($_FILES["wgraj_raport"]["name"]) . " wgrano.";
                //zmień nazwę z dowolnej na standardową
                rename("$katalog_docelowy_wgrywania" . basename($_FILES["pomiar_prawdziwy"]["name"]), "$katalog_docelowy_wgrywania/pomiar_prawdziwy.txt");
            } else {
                echo "Jakiś błąd w końcowym etapie wgrywania. Spróbuj jeszcze raz.";
            }
        }
        /*
         * To change this license header, choose License Headers in Project Properties.
         * To change this template file, choose Tools | Templates
         * and open the template in the editor.
         */
        $tablica_pikiet = array(array("0baza", "1nr_pkt", "2rtn-fix", "3data czas", "4tyczka", /* 5 */ 0, /* 6 */ 0, /* 7 */ 0, "8pdop",
                "9sat", "10epoki", "11x", "12y", "13h", "14mp", "15mh", "16kod", "17odleglosc-pkt1-2", "18najblizszy"));
        $tablica_pomiaru = array(array("0baza", "1nr_pkt", "2rtn-fix", "3data czas", "4tyczka", "5dx", "6dy", "7dz", "8pdop",
                "9sat", "10epoki", "11x", "12y", "13h", "14mp", "15mh", "16kod"));
        $czy_jest_osnowa = false;
        $i = 0;
        /* $x = 0;
          $y = 0;
          $h = 0; */
        $data_pomiaru = $_POST['data_pomiaru'];
        //$baza = array($_POST['baza_nazwa'], round($_POST['bazaX'], 2, PHP_ROUND_HALF_EVEN), round($_POST['bazaY'], 2, PHP_ROUND_HALF_EVEN), round($_POST['bazaH'], 3, PHP_ROUND_HALF_EVEN));
        $handle = fopen("up/wejsciowy.txt", "r");
        while (($rekord = fgetcsv($handle, 0, ",")) !== FALSE) {
            $tablica_pikiet [$i][1] = $rekord[0];
            $tablica_pikiet [$i][5] = 0.000;
            $tablica_pikiet [$i][6] = 0.000;
            $tablica_pikiet [$i][7] = 0.000;
            $tablica_pikiet [$i][11] = number_format($rekord[1], 2, '.', '');
            //$x = $x + $tablica_pikiet [$i][11];
            $tablica_pikiet [$i][12] = number_format($rekord[2], 2, '.', '');
            //$y = $y + $tablica_pikiet [$i][12];
            $tablica_pikiet [$i][13] = number_format($rekord[3], 3, '.', '');
            //$h = $h + $tablica_pikiet [$i][13];
            if (preg_match("/osn/i", $rekord[4]) == 1) {
                $czy_jest_osnowa = true;
                $tablica_pikiet [$i][16] = "osn";
                $tablica_pikiet [$i + 1][1] = $rekord[0];
                $tablica_pikiet [$i + 1][11] = number_format($rekord[1], 2, '.', '');
                $tablica_pikiet [$i + 1][12] = number_format($rekord[2], 2, '.', '');
                $znak = random_int(0, 1);
                $przesuniecie = "0.00" . random_int(1, 4);
                if ($znak === 0) {
                    $tablica_pikiet [$i][5] = $przesuniecie * (-1.000);
                    $tablica_pikiet [$i][6] = $przesuniecie * (-1.000);
                    $tablica_pikiet [$i][13] = number_format($rekord[3] - $przesuniecie, 3, '.', '');
                    $tablica_pikiet [$i + 1][5] = $przesuniecie;
                    $tablica_pikiet [$i + 1][6] = $przesuniecie;
                    $tablica_pikiet [$i + 1][13] = number_format($rekord[3] + $przesuniecie, 3, '.', '');
                } else {
                    $tablica_pikiet [$i][5] = $przesuniecie;
                    $tablica_pikiet [$i][6] = $przesuniecie;
                    $tablica_pikiet [$i][13] = number_format($rekord[3] + $przesuniecie, 3, '.', '');
                    $tablica_pikiet [$i + 1][5] = $przesuniecie * (-1.000);
                    $tablica_pikiet [$i + 1][6] = $przesuniecie * (-1.000);
                    $tablica_pikiet [$i + 1][13] = number_format($rekord[3] - $przesuniecie, 3, '.', '');
                }
                $tablica_pikiet [$i + 1][16] = "osn";
                $i++;
            } else {
                $tablica_pikiet [$i][16] = "pikieta";
            }
            $i++;
        }
        /* $handle2 = fopen("down/stacje_asg.csv", "r");
          $tablica_stacji = array(array("nazwa", "x", "y", "h", "prs", "X", "Y", "Z"));
          $ii = 0;
          while (($rekord = fgetcsv($handle2, 0, ",")) !== FALSE) {
          for ($j = 0; $j < 8; $j++) {
          $tablica_stacji[$ii][$j] = $rekord[$j];
          }
          $ii++;
          }
          /* $sredniaX = $x / count($tablica_pikiet);
          $sredniaY = $y / count($tablica_pikiet);
          $sredniaH = $h / count($tablica_pikiet);
          $numer_bazy = 0;
          $szukajX = "/" . $baza[1] . "/";
          $szukajY = "/" . $baza[2] . "/";
          $szukajH = "/" . $baza[3] . "/";
          for ($i = 0; $i < sizeof($tablica_stacji); $i++) {
          if (preg_match($szukajX, $tablica_stacji[$i][1]) == 1 or preg_match($szukajY, $tablica_stacji[$i][2]) == 1 or preg_match($szukajH, $tablica_stacji[$i][3]) == 1) {
          $numer_bazy = $i;
          }
          }
          //$minimum = sqrt(pow(($sredniaX - $baza[0][1]), 2) + pow(($sredniaY - $baza[0][2]), 2));
          /* for ($i = 1; $i < sizeof($baza); $i++) {
          $dlugosc = sqrt(pow(($sredniaX - $baza[$i][1]), 2) + pow(($sredniaY - $baza[$i][2]), 2));
          if ($dlugosc < $minimum) {
          $minimum = $dlugosc;
          $numer_bazy = $i;
          }
          } */
        $handle2 = fopen("up/pomiar_prawdziwy.txt", "r");
        $ii = 0;
        while (($rekord = fgetcsv($handle2, 0, ",")) !== FALSE) {
            for ($j = 0; $j < 17; $j++) {
                $tablica_pomiaru[$ii][$j] = $rekord[$j];
            }
            $ii++;
        }
        for ($i = 0; $i < sizeof($tablica_pikiet); $i++) {
            //$tablica_pikiet[$i][0] = $baza[0];
            $tablica_pikiet[$i][2] = "RTN Fix";
            $tablica_pikiet[$i][3] = "data-czas";
            $tablica_pikiet[$i][4] = "2.000";
            //$tablica_pikiet[$i][5] = round($tablica_pikiet[$i][11] - $tablica_stacji[$numer_bazy][1] + random_int(1, 9) * 0.001, 3);
            //$tablica_pikiet[$i][6] = round($tablica_pikiet[$i][12] - $tablica_stacji[$numer_bazy][2] + random_int(1, 9) * 0.001, 3);
            //$tablica_pikiet[$i][7] = round(($tablica_pikiet[$i][13] - $tablica_stacji[$numer_bazy][3]) * 1000 + random_int(1, 9) * 0.1 + random_int(1, 9) * 0.01 + random_int(1, 9) * 0.001, 3);
            $tablica_pikiet[$i][8] = random_int(1, 4) . "." . random_int(0, 9);
            $tablica_pikiet[$i][9] = random_int(8, 17);
            if ($tablica_pikiet[$i][16] === "osn") {
                $tablica_pikiet[$i][10] = 30;
            } else {
                $tablica_pikiet[$i][10] = 5;
            }
            $tablica_pikiet[$i][14] = "0.0" . random_int(1, 3);
            $tablica_pikiet[$i][15] = "0.0" . random_int(1, 3);
            if ($tablica_pikiet[$i][14] > $tablica_pikiet[$i][15]) {
                $tablica_pikiet[$i][15] = $tablica_pikiet[$i][14];
            }
            if ($i != 0 and $i < sizeof($tablica_pikiet)) {
                $tablica_pikiet[$i][17] = round(sqrt(pow($tablica_pikiet[$i][11] - $tablica_pikiet[$i - 1][11], 2) + pow($tablica_pikiet[$i][12] - $tablica_pikiet[$i - 1][12], 2)) * 0.5) + 3 + random_int(0, 15);
            }
        }
        $tablica_pikiet[0][3] = $data_pomiaru . " 07:" . random_int(3, 5) . random_int(0, 5) . ":" . random_int(0, 5) . random_int(0, 5);
        for ($i = 1; $i < sizeof($tablica_pikiet); $i++) {
            if ($tablica_pikiet[$i - 1][16] === "osn") {
                $dataczas = new DateTime($tablica_pikiet[$i - 1][3]);
                $dataczas->add(new DateInterval('P0Y0M0DT0H0M30S'));
                $dataczas->add(new DateInterval('P0Y0M0DT0H0M' . $tablica_pikiet[$i][17] . 'S'));
                $tablica_pikiet[$i][3] = $dataczas->format('Y-m-d H:i:s');
            } else {
                $dataczas = new DateTime($tablica_pikiet[$i - 1][3]);
                $dataczas->add(new DateInterval('P0Y0M0DT0H0M05S'));
                $dataczas->add(new DateInterval('P0Y0M0DT0H0M' . $tablica_pikiet[$i][17] . 'S'));
                $tablica_pikiet[$i][3] = $dataczas->format('Y-m-d H:i:s');
            }
        }
        $rozmiar_tablicy_prawdziwy = sizeof($tablica_pomiaru);
        for ($i = 0; $i < sizeof($tablica_pikiet); $i++) {
            $mala_numer = 0;
            $odl_mala = sqrt(pow($tablica_pikiet[0][11] - $tablica_pomiaru[0][11], 2) + pow($tablica_pikiet[0][12] - $tablica_pomiaru[0][12], 2));
            for ($j = 1; $j < $rozmiar_tablicy_prawdziwy; $j++) {
                $odl = sqrt(pow($tablica_pikiet[$i][11] - $tablica_pomiaru[$j][11], 2) + pow($tablica_pikiet[$i][12] - $tablica_pomiaru[$j][12], 2));
                if ($odl_mala > $odl) {
                    $odl_mala = $odl;
                    $mala_numer = $j;
                }
            }
            $tablica_pikiet[$i][0] = $tablica_pomiaru[$mala_numer][0];
            $tablica_pikiet[$i][5] = $tablica_pikiet[$i][5] + number_format($tablica_pomiaru[$mala_numer][5] + $tablica_pikiet[$i][11] - $tablica_pomiaru[$mala_numer][11], 3, '.', '');
            $tablica_pikiet[$i][6] = $tablica_pikiet[$i][6] + number_format($tablica_pomiaru[$mala_numer][6] + $tablica_pikiet[$i][12] - $tablica_pomiaru[$mala_numer][12], 3, '.', '');
            $tablica_pikiet[$i][7] = $tablica_pikiet[$i][6] + number_format($tablica_pomiaru[$mala_numer][7] + $tablica_pikiet[$i][13] - $tablica_pomiaru[$mala_numer][13], 3, '.', '');
        }
        if ($czy_jest_osnowa) {
            ?>
            <h2>Tabela punktów uśrednionych:</h2>
            <table>
                <tr>
                <b>
                    <th>Nr pkt</th>
                    <th>x</th>
                    <th>y</th>
                    <th>h</th>
                    <th>x'</th>
                    <th>y'</th>
                    <th>h'</th>
                    <th>x sr</th>
                    <th>y sr</th>
                    <th>h sr</th>
                    <th>mx</th>
                    <th>my</th>
                    <th>mh</th>
                    <th>mp</th>
                </b>
            </tr>
            <?php
            for ($i = 1; $i < sizeof($tablica_pikiet); $i++) {
                if ($tablica_pikiet[$i - 1][1] === $tablica_pikiet[$i][1]) {
                    echo "<tr><td>" . $tablica_pikiet[$i - 1][1] . "</td>";
                    echo "<td>" . $tablica_pikiet[$i - 1][11] . "</td><td>" . $tablica_pikiet[$i - 1][12] . "</td>"
                    . "<td>" . $tablica_pikiet[$i - 1][13] . "</td>";
                    echo "<td>" . $tablica_pikiet[$i][11] . "</td><td>" . $tablica_pikiet[$i][12] . "</td>"
                    . "<td>" . $tablica_pikiet[$i][13] . "</td>";
                    echo "<td>" . number_format(round(($tablica_pikiet[$i - 1][11] + $tablica_pikiet[$i][11]) / 2, 2, PHP_ROUND_HALF_EVEN), 2, '.', '') . "</td>";
                    echo "<td>" . number_format(round(($tablica_pikiet[$i - 1][12] + $tablica_pikiet[$i][12]) / 2, 2, PHP_ROUND_HALF_EVEN), 2, '.', '') . "</td>";
                    echo "<td>" . number_format(round(($tablica_pikiet[$i - 1][13] + $tablica_pikiet[$i][13]) / 2, 3, PHP_ROUND_HALF_EVEN), 3, '.', '') . "</td>";
                    echo "<td>0.0" . random_int(0, 1) . "</td><td>0.0" . random_int(0, 1) . "</td><td>0.00" . random_int(0, 4) . "</td>"
                    . "<td>0.0" . random_int(0, 1) . "</td></tr>";
                }
            }
        }
        ?>
    </table><br/>
    <h2>Tabela wektorów GPS:</h2>
    <table>
        <tr>
        <b>
            <th>Pkt Bazowy</th>
            <th>Nr pkt</th>
            <th>Rozwiązanie</th>
            <th>Data Godzina</th>
            <th>Wys. anteny</th>
            <th>ECEF ∆X</th>
            <th>ECEF ∆Y</th>
            <th>ECEF ∆Z</th>
            <th>PODP</th>
            <th>sat</th>
            <th>e</th>
            <th>X</th>
            <th>Y</th>
            <th>h</th>
            <th>mp</th>
            <th>mh</th>
        </b>
    </tr>
    <?php
    for ($i = 0; $i < sizeof($tablica_pikiet); $i++) {
        echo "<tr>";
        for ($j = 0; $j < 16; $j++) {
            echo "<td>", $tablica_pikiet[$i][$j], "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    fclose($handle);
    fclose($handle2);
    unlink($katalog_docelowy_wgrywania . 'wejsciowy.txt');
    unlink($katalog_docelowy_wgrywania . 'pomiar_prawdziwy.txt');
    ?>
</table>
</body>
</html>