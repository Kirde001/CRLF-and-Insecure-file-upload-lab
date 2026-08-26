<?php
$result = "";
$requested_url = "";

if (isset($_POST['url']) && !empty($_POST['url'])) {
    $requested_url = $_POST['url'];
    
    $context = stream_context_create([
        'http' => [
            'ignore_errors' => true,
            'timeout' => 5
        ]
    ]);
    
    $response = @file_get_contents($requested_url, false, $context);
    
    if ($response === FALSE) {
        $result = "Error: Could not fetch the URL. Check if the service is reachable.";
    } else {
        $result = htmlspecialchars($response);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SSRF Vulnerability Lab</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
            background-color: #fcfcfc;
        }
        h2 { border-bottom: 2px solid #eaeaea; padding-bottom: 10px; }
        .intro { font-size: 1.1em; margin-bottom: 20px; }
        ul { margin-bottom: 30px; }
        li { margin-bottom: 8px; }
        .code-block {
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            font-family: "Courier New", Courier, monospace;
            overflow-x: auto;
            margin: 15px 0;
        }
        .malicious-input {
            background-color: #fdf0f0;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .lab-form {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #cce5ff;
            border-radius: 4px;
            margin-top: 30px;
        }
        input[type="text"] { width: 70%; padding: 8px; margin-right: 10px; border: 1px solid #ccc; }
        button { padding: 8px 15px; cursor: pointer; }
        .result-box {
            background: #272822;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            max-height: 400px;
        }
    </style>
</head>
<body>

    <h2>Server-Side Request Forgery (SSRF)</h2>
    
    <p class="intro">
        SSRF is a security vulnerability that happens when the server processes user-provided URLs or IP addresses without proper validation.
    </p>

    <p>Common exploitation paths:</p>
    <ul>
        <li>Accessing Cloud metadata</li>
        <li>Leaking files on the server (e.g., using <code>file:///</code> protocol)</li>
        <li>Network discovery, port scanning with the SSRF</li>
        <li>Sending packets to specific services on the network</li>
    </ul>

    <p><strong>Example:</strong> A server accepts user input to fetch a URL (PHP implementation).</p>
    
    <div class="code-block">
$url = $_POST['url'];<br>
$response = file_get_contents($url);<br>
echo $response;
    </div>

    <p>An attacker supplies a malicious input:</p>
    
    <div class="code-block malicious-input">
http://169.254.169.254/latest/meta-data/
    </div>
    
    <p>This fetches sensitive information from the AWS EC2 metadata service.</p>

    <div class="lab-form">
        <h4>Try it yourself:</h4>
        <form action="ssrf.php" method="POST">
            <input type="text" name="url" placeholder="http://example.com or file:///etc/passwd" value="<?php echo htmlspecialchars($requested_url); ?>" required>
            <button type="submit">Fetch URL</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
            <h4>Server Response:</h4>
            <pre class="result-box"><?php echo $result; ?></pre>
        <?php endif; ?>
    </div>

</body>
</html>