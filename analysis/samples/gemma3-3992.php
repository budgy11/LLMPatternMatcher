
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

    <h1>User Registration</h1>

    <?php
    // Display error messages if any
    if (isset($username_error)) echo "<p style='color: red;'>$username_error</p>";
    if (isset($email_error)) echo "<p style='color: red;'>$email_error</p>";
    if (isset($password_error)) echo "<p style='color: red;'>$password_error</p>";
    ?>
