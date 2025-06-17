
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

<h1>Forgot Password</h1>

<?php
// Display any error messages
if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}

if (isset($success)) {
    echo "<p style='color: green;'>$success</p>";
}
?>
