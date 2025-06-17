
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

    <h2>Register</h2>

    <?php if (isset($username_error) || isset($email_error) || isset($password_error)) { ?>
