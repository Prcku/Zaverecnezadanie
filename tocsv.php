<?php
//
//$conn = new PDO("mysql:host=localhost;dbname=Zaverecnezadanie", "xsochab", "U4IIQqq1mUB33kN");
//$sql = "SELECT * FROM command";
//$stmt = $conn->prepare($sql);
//$stmt->execute();
//$commandLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
////array2csv($commandLogs);
//
////var_dump($commandLogs);
////echo array2csv($commandLogs);
//
//function array2csv(array &$array) {
//    if (count($array) == 0) {
//        return null;
//    }
//    ob_start();
//
//    $df = fopen("php://output", 'w');
//
//    if ($df === false) {
//        die('Error opening csv file ');
//    }
//
//    ob_end_clean();
//    fputcsv($df, array_keys(reset($array)));
//
//    foreach ($array as $row) {
//        fputcsv($df, $row);
//    }
//    fclose($df);
//
//
//    ob_get_clean();
//    return df;
//}
//
