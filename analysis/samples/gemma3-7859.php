
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

<h1>Add Purchase</h1>

<?php
if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
}

if (isset($success_message)) {
    echo "<p style='color: green;'>$success_message</p>";
}
?>
