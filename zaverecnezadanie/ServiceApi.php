<?php


header('Content-Type: application/json; charset=utf-8');
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
        if (isset($_GET['r'])) {
            $r = $_GET['r'];
            if(-5 < $r && $r > 5 ){
                http_response_code(400);
                echo json_encode("Bad Request");
            }
            else{
            $fp = fopen('input.m', 'w+');
            $text = "";
            $input = "
            pkg load control
            m1 = 2500; m2 = 320;
            k1 = 80000; k2 = 500000;
            b1 = 350; b2 = 15020;
            A=[0 1 0 0;-(b1*b2)/(m1*m2) 0 ((b1/m1)*((b1/m1)+(b1/m2)+(b2/m2)))-(k1/m1) -(b1/m1);b2/m2 0 -((b1/m1)+(b1/m2)+(b2/m2)) 1;k2/m2 0 -((k1/m1)+(k1/m2)+(k2/m2)) 0];
            B=[0 0;1/m1 (b1*b2)/(m1*m2);0 -(b2/m2);(1/m1)+(1/m2) -(k2/m2)];
            C=[0 0 1 0]; D=[0 0];
            Aa = [[A,[0 0 0 0]'];[C, 0]];
            Ba = [B;[0 0]];
            Ca = [C,0]; Da = D;
            K = [0 2.3e6 5e8 0 8e6];
            sys = ss(Aa-Ba(:,1)*K,Ba,Ca,Da);
            
            t = 0:0.01:5;
            r = ".$r.";
            initX1=0;
            initX1d=0;
            initX2=0;
            initX2d=0;
            [y,t,x]=lsim(sys*[0;1],r*ones(size(t)),t,[initX1;initX1d;initX2;initX2d;0]);
            filename = \"matica.txt\";
            fid = fopen (filename, \"w+\");
            fputs (fid, disp(x(size(x,1),:)));
            fputs (fid, disp([t,x(:,1),x(:,3)]));
            fclose (fid);
            ";
            fwrite($fp, $input);
            $vystup = exec('octave input.m');
            fclose($fp);
            $txt_file = file_get_contents("matica.txt");

            $rows = explode("\n", $txt_file);
            foreach ($rows as &$row) {
                $row = explode("  ", $row);
                unset($row[0]);
                foreach ($row as &$row_row) {
                    $row_row = str_replace(" ", "", $row_row);
                    $row_row = floatval($row_row);
                }

            }
                $name = $_GET['name'];
                $command = utf8_decode($_GET['r']);
                $status = "OK";
                $today = date("Y-m-d H:i:s");
                $stmt = $conn->prepare("INSERT INTO command (date, command, name,	status) VALUES (?, ?, ?, ?)");
                $stmt->execute([$today,$command,$name,$status]);
                echo json_encode($rows);
            }
         }
        elseif(isset($_GET['command'])) {
            $name = $_GET['name'];
            $command = utf8_decode($_GET['command']);
//            $fp = fopen('input.m', 'w+');
            $text = "";
            $fp2 = fopen('input2.m', 'w+');
            $input = "
            pkg load control;
            ".$command."
            ";
            fwrite($fp2,$input);
            $vystup = exec("octave input2.m ",$text);
            $name = $_GET['name'];
            $status = "OK";
            if($text == null){
                http_response_code(400);
                $status = "syntax error";
                echo json_encode("syntax error");
            }
            else{
                echo json_encode($text);
            }
            $today = date("Y-m-d H:i:s");
            $stmt = $conn->prepare("INSERT INTO command (date, command, name,	status) VALUES (?, ?, ?, ?)");
            $stmt->execute([$today,$command,$name,$status]);
        }
    }
}