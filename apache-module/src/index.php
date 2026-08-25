<?php

$uri = $_SERVER['REQUEST_URI'];
$decoded_uri = urldecode($uri);

$lines = preg_split('/[\r\n]+/', $decoded_uri);

$injected = false;

if (count($lines) > 1) {
    for ($i = 1; $i < count($lines); $i++) {
        $injected_header = trim($lines[$i]);
        if (!empty($injected_header)) {
            $injected_header = str_replace('^', ':', $injected_header);
            header($injected_header, false); 
            $injected = true;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>CRLF</title></head>
<body>
    <h1>HTTP Response Splitting</h1>
    <p>URI: <code><?php echo htmlspecialchars($uri); ?></code></p>
    
    <?php if ($injected): ?>
        <h2 style="color:red;">Injected</h2>
        <p>Check cookies.</p>
        <pre><?php print_r($lines); ?></pre>
    <?php else: ?>
        <h2 style="color:green;">No injection</h2>
        <p>Try again</p>
    <?php endif; ?>
</body>
</html>