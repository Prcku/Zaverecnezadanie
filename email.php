<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use PHPMailer\PHPMailer\PHPMailer;

$conn = new PDO("mysql:host=localhost;dbname=Zaverecnezadanie", "xsochab", "U4IIQqq1mUB33kN");
$sql = "SELECT * FROM command";
$stmt = $conn->prepare($sql);
$stmt->execute();
$commandLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);


function array2csv(array &$array) {
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


if(isset($_POST['send_mail_button'])) {
    array2csv($commandLogs);
    sendmail();
}

function sendmail() {
    $name = "Zaverecne zadanie";
    $to = "zaverecnezadanie@azet.sk";                 // receiver
    $subject = "Zaverecne zadanie - logy";
    $body = "Body text";
    $from = "zaverecnezadanie@azet.sk";
    $password = "kP8B2yecq33wNV4";

    //$file_to_attach = '/testing/file.csv';          // csv

    // Ignore from here

    require_once "PHPMailer/PHPMailer.php";
    require_once "PHPMailer/SMTP.php";
    require_once "PHPMailer/Exception.php";
    $mail = new PHPMailer();

    // To Here

    //SMTP Settings
    $mail->isSMTP();
    // $mail->SMTPDebug = 3;  Keep It commented this is used for debugging
    $mail->Host = "smtp.azet.sk";
    $mail->SMTPAuth = true;
    $mail->Username = $from;
    $mail->Password = $password;
    $mail->AddAttachment('logy.csv');               // csv
    $mail->Port = 587;  // port
    $mail->SMTPSecure = "tls";  // tls or ssl
    $mail->smtpConnect([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ]);

    //Email Settings
    $mail->isHTML(true);
    $mail->setFrom($from, $name);
    $mail->addAddress($to); // enter email address whom you want to send
    $mail->Subject = ("$subject");
    $mail->Body = $body;
    if ($mail->send()) {
        echo "Email is sent!";
    } else {
        echo "Something is wrong: <br><br>" . $mail->ErrorInfo;
    }
}
?>
