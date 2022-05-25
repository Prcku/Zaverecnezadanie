<?php
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $data = json_decode(file_get_contents('php://input'), true);
    $apache_headers = apache_request_headers();
    $auth = $apache_headers["X-Api-Key"];

    $conn = new PDO("mysql:host=localhost;dbname=Zaverecnezadanie", "xsochab", "U4IIQqq1mUB33kN");
    $sql = "SELECT token FROM Tokens WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$auth]);
    $token = $stmt->fetch();
    if ($token == null) {
        http_response_code(401);
        echo json_encode("401 Unauthorized");
    }
    if ($token != null) {
        $conn = new PDO("mysql:host=localhost;dbname=Zaverecnezadanie", "xsochab", "U4IIQqq1mUB33kN");
        $sql = "SELECT * FROM command";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $commandLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);


        function array2csv(array &$array)
        {
            if (count($array) == 0) {
                return null;
            }
            ob_start();

            // $df = fopen("php://output", 'w');               // "php://output"
            $csvFile = 'logy.csv';
            $df = fopen($csvFile, 'w+');

            if ($df === false) {
                die('Error opening csv file ');
            }

            ob_end_clean();
            fputcsv($df, array_keys(reset($array)));

            foreach ($array as $row) {
                fputcsv($df, $row);
            }
            fclose($df);
            ob_get_clean();
            return $csvFile;
        }
    }
}
