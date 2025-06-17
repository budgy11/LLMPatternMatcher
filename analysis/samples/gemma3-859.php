
<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

    <h2>Login</h2>

    <?php if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>
