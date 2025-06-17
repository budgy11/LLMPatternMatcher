
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h1>User Registration</h1>

<?php
if (isset($errors)) {
    echo "<div style='color: red;'>Error: " . $errors . "</div>";
}

if (isset($success)) {
    echo "<div style='color: green;'>" . $success . "</div>";
}
?>
