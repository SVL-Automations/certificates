<?php

include("db.php");
date_default_timezone_set('Asia/Kolkata');

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $data = mysqli_query($connection, "SELECT s.*, w.name as workshopname, 
                                    DATE_FORMAT(w.start_date,'%D %M %Y') AS start_date, 
                                    DATE_FORMAT(w.end_date,'%D %M %Y') AS end_date,
                                    w.days,w.type,w.file_name, w.certificate_second_line, c.name as collegename 
                                    FROM student AS s INNER JOIN workshop AS w ON s.workshopid = w.id 
                                    INNER JOIN college AS c ON w.collegeid = c.id 
                                    WHERE s.verification_code = '$code' AND s.status = 2");

    if (mysqli_num_rows($data) > 0) {
        $data = mysqli_fetch_assoc($data);


        header('content-type:image/jpeg');
        $image = imagecreatefromjpeg("templates/".$data['file_name']);
        $iWidth = imagesx($image);
        $iHeight = imagesy($image);

        //Text Name
        $font = "assets/fonts/GoblinOne-Regular.ttf";
        $color = imagecolorallocate($image, 19, 21, 22);
        $text = $data['name'];
        $fontSize = 120;
        $angle = 0;

        $tSize = imagettfbbox($fontSize, $angle, $font, $text);
        $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
        $tHeight = max([$tSize[5], $tSize[7]]) - min([$tSize[1], $tSize[3]]);

        $centerX = ceil(($iWidth - $tWidth) / 2);
        $centerX = $centerX < 0 ? 0 : $centerX;

        imagettftext($image, $fontSize, $angle, $centerX, 2400, $color, $font, $text);

        $font = "assets/fonts/CrimsonText-Regular.ttf";
        $text = ".............................................................................................................................................................................................................................";
        $color = imagecolorallocate($image, 19, 21, 22);
        $fontSize = 50;
        $angle = 0;

        $tSize = imagettfbbox($fontSize, $angle, $font, $text);
        $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
        $tHeight = max([$tSize[5], $tSize[7]]) - min([$tSize[1], $tSize[3]]);

        $centerX = ceil(($iWidth - $tWidth) / 2);
        $centerX = $centerX < 0 ? 0 : $centerX;
        imagettftext($image, $fontSize, $angle, $centerX, 2460, $color, $font, $text);

        // Text Days line
        /*
        $font = "assets/fonts/CrimsonText-Regular.ttf";
        $color = imagecolorallocate($image, 19, 21, 22);
        $text = $data['certificate_second_line'];
        $fontSize = 120;
        $angle = 0;

        $tSize = imagettfbbox($fontSize, $angle, $font, $text);
        $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
        $tHeight = max([$tSize[5], $tSize[7]]) - min([$tSize[1], $tSize[3]]);

        $centerX = ceil(($iWidth - $tWidth) / 2);
        $centerX = $centerX < 0 ? 0 : $centerX;

        imagettftext($image, $fontSize, $angle, $centerX, 2700, $color, $font, $text);

        // Text workshop name

        $font = "assets/fonts/GoblinOne-Regular.ttf";
        $color = imagecolorallocate($image, 231, 120, 23);
        $text = '"'. $data['workshopname']. '"';
        $fontSize = 100;
        $angle = 0;

        $tSize = imagettfbbox($fontSize, $angle, $font, $text);
        $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
        $tHeight = max([$tSize[5], $tSize[7]]) - min([$tSize[1], $tSize[3]]);

        $centerX = ceil(($iWidth - $tWidth) / 2);
        $centerX = $centerX < 0 ? 0 : $centerX;

        imagettftext($image, $fontSize, $angle, $centerX, 2950, $color, $font, $text);

        // Text location
        $font = "assets/fonts/CrimsonText-Bold.ttf";
        $color = imagecolorallocate($image, 0, 147, 221);
        $text = " at ".$data['collegename'];       
        $fontSize = 130;
        $angle = 0;

        $tSize = imagettfbbox($fontSize, $angle, $font, $text);
        $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
        $tHeight = max([$tSize[5], $tSize[7]]) - min([$tSize[1], $tSize[3]]);

        $centerX = ceil(($iWidth - $tWidth) / 2);
        $centerX = $centerX < 0 ? 0 : $centerX;

        imagettftext($image, $fontSize, $angle, $centerX, 3200, $color, $font, $text);

        // Text Dates

        $font = "assets/fonts/CrimsonText-Regular.ttf";
        $color = imagecolorallocate($image, 19, 21, 22);
        $text = "from ".$data['start_date']. " to ".$data['end_date'];
        $fontSize = 120;
        $angle = 0;

        $tSize = imagettfbbox($fontSize, $angle, $font, $text);
        $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
        $tHeight = max([$tSize[5], $tSize[7]]) - min([$tSize[1], $tSize[3]]);

        $centerX = ceil(($iWidth - $tWidth) / 2);
        $centerX = $centerX < 0 ? 0 : $centerX;

        imagettftext($image, $fontSize, $angle, $centerX, 3450, $color, $font, $text);
        */

        // Text verification_code

        $font = "assets/fonts/LibreBarcode39Text-Regular.ttf";
        $color = imagecolorallocate($image, 19, 21, 22);
        $text = $data['verification_code'];
        $fontSize = 150;
        $angle = 0;

        $tSize = imagettfbbox($fontSize, $angle, $font, $text);
        $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
        $tHeight = max([$tSize[5], $tSize[7]]) - min([$tSize[1], $tSize[3]]);

        $centerX = ceil(($iWidth - $tWidth) / 2);
        $centerX = $centerX < 0 ? 0 : $centerX;

        imagettftext($image, $fontSize, $angle, $centerX, 3650, $color, $font, $text);


        // Text verification link

        $font = "assets/fonts/CrimsonText-Regular.ttf";
        $color = imagecolorallocate($image, 231, 0, 23);
        $text = "Verify this certificate at : https://certificates.svlautomations.in";
        $fontSize = 80;
        $angle = 0;

        $tSize = imagettfbbox($fontSize, $angle, $font, $text);
        $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
        $tHeight = max([$tSize[5], $tSize[7]]) - min([$tSize[1], $tSize[3]]);

        $centerX = ceil(($iWidth - $tWidth) / 2);
        $centerX = $centerX < 0 ? 0 : $centerX;

        imagettftext($image, $fontSize, $angle, 400, 4800, $color, $font, $text);


        imagejpeg($image);
        imagedestroy($image);
    } else {
        echo "Certificate not found or Not generated. Please contact 88 5335 4141.";
    }
} else {
    echo "Certificate not found or Not generated. Please contact 88 5335 4141.";
}
