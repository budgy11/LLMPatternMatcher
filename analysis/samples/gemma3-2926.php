

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

    <h2>User Registration</h2>

    <?php
    if (isset($error_message)) {
        echo "<p style='color:red;'>Error: " . $error_message . "</p>";
    }

    if (isset($success_message)) {
        echo "<p style='color:green;'>" . $success_message . "</p>";
    }
    ?>
