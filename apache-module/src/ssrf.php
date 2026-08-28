<?php
if (isset($_GET['url'])) {
    $url = $_GET['url'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $output = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo 'Ошибка cURL: ' . curl_error($ch);
    } else {
        echo $output;
    }
    
    curl_close($ch);
} else {
    echo "Provide a target URL. For example: ?url=https://0x7f.0x0.0x0.0x1";
}
?>