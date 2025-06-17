
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h1>User Registration</h1>

<?php if (isset($errors)) {
    echo "<div style='color: red;'>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
    echo "</div>";
} ?>
