
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

    <h2>User Registration</h2>

    <?php
    if (isset($error)) {
        echo "<p style='color: red;'>Error: " . $error . "</p>";
    }
    if (isset($success)) {
        echo "<p style='color: green;'> " . $success . "</p>";
    }
    ?>
