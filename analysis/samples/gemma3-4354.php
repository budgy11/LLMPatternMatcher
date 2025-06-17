
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h2>User Registration</h2>

<?php
// Display any errors
if (!empty($errors)) {
    echo "<div style='color: red;'>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
    echo "</div>";
}
?>
