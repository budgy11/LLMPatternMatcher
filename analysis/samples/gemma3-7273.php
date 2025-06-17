
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

<h1>Make a Purchase</h1>

<?php
if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
} elseif (isset($success)) {
    echo "<p style='color: green;'>$success</p>";
}
?>
