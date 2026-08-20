<?php

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>c99shell - v.1.0</title>
</head>
<body>
    <h1>WSO Web Shell (Mock)</h1>
    <p>Safe mode: <b>OFF</b></p>
    <p>OS: Linux w51.server.net 2.6.32 #1 SMP</p>
    
    <form method="POST" action="">
        <input type="text" name="cmd" value="whoami">
        <input type="submit" value="Execute">
    </form>

    <?php
    if (isset($_POST['cmd'])) {
        echo "<pre>root</pre>";
    }
    echo "<!-- eval(base64_decode( -->";
    echo "<!-- preg_replace('/.*/e', ... -->";
    ?>
</body>
</html>