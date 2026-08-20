<?php

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Download</title>
</head>
<body>
    <h1>Shell</h1>
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