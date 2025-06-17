
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

<h1>Purchase Functionality</h1>

<?php
// Display any error or success messages
if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}

if (isset($success)) {
    echo "<p style='color: green;'>$success</p>";
}
?>
