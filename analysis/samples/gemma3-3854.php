
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h1>User Registration</h1>

<?php
// Display any errors
if (isset($errors)) {
    echo "<div style='color: red;'>";
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
    echo "</div>";
}
?>
