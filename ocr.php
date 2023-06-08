<?php
require './config.php';
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$username     = USERNAME;
$license_code = LICENCES;
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if ($_FILES['attachment']) {
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    $url = 'http://www.ocrwebservice.com/restservices/processDocument?language=english,japanese,indonesian&pagerange=1-5&gettext=true&outputformat=doc';
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    $filePath = $_FILES["attachment"]["tmp_name"];
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    $fp      = fopen($filePath, 'r');
    $session = curl_init();
    curl_setopt($session, CURLOPT_URL, $url);
    curl_setopt($session, CURLOPT_USERPWD, "$username:$license_code");
    curl_setopt($session, CURLOPT_UPLOAD, true);
    curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($session, CURLOPT_TIMEOUT, 200);
    curl_setopt($session, CURLOPT_HEADER, false);
    // For SSL using
    //curl_setopt($session, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($session, CURLOPT_INFILE, $fp);
    curl_setopt($session, CURLOPT_INFILESIZE, filesize($filePath));
    $result   = curl_exec($session);
    $httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
    curl_close($session);
    fclose($fp);

    if ($httpCode == 401) {
        die('Unauthorized request');
    }

    $data = json_decode($result);

    if ($httpCode != 200) {
        die($data->ErrorMessage);
    }

    //print_r($data);
    echo '<form class="centered-form">';
    echo "<p>" . $data->OCRText[0][0] . "</p>";
    echo '<a href="javascript:history.back()" class="back-button">Quay lại</a>';
    echo '</form>';

} else {
    header('Location: Your_URL');
}
?>

<style>
.back-button {
  display: inline-block;
  padding: 10px 20px;
  background-color: #f2f2f2;
  color: #333;
  text-decoration: none;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.back-button:hover {
  background-color: #e6e6e6;
}

.back-button:focus {
  outline: none;
}

.centered-form {
  border: 1px solid #ccc;
  padding: 20px;
  margin: 0 auto;
  width: 90%;
}
</style>