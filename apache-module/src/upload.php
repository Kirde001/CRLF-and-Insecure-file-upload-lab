<?php
$upload_dir = 'uploads/';

if (isset($_FILES['userfile'])) {
    $file_name = basename($_FILES['userfile']['name']);
    $target_path = $upload_dir . $file_name;
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path)) {
        echo "<h3>Файл успешно загружен!</h3>";
        echo "<p>Ссылка: <a href='{$target_path}'>{$target_path}</a></p>";
    } else {
        echo "<h3>Ошибка загрузки. Проверьте права на папку uploads/</h3>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Image Uploader (Vulnerable)</title>
</head>
<body>
    <h2>Upload Profile Picture</h2>
    <form enctype="multipart/form-data" action="upload.php" method="POST">
        <input type="file" name="userfile" required>
        <br><br>
        <input type="submit" value="Upload Image">
    </form>
</body>
</html>