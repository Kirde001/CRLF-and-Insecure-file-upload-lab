<?php
if (isset($_GET['redirect'])) {
    header("Location: " . $_GET['redirect']);
    exit();
} 

if (isset($_GET['cookie_val'])) {
    header("Set-Cookie: CustomCookie=" . $_GET['cookie_val']);
    echo "Cookies set.";
    exit();
}
?>