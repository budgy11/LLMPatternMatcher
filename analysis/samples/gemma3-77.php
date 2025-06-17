
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>

<h2>User Login</h2>

<?php
// Display any error messages
if (isset($error)) {
    echo "<div style='color: red;'>";
    foreach ($error as $err) {
        echo $err . "<br>";
    }
    echo "</div>";
}
?>
