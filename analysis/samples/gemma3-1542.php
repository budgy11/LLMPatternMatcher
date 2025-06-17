
<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>

    <h1>Password Reset</h1>

    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="hidden" name="reset_token" value="<?php echo $resetToken; ?>">  <!-- Hidden field to pass token -->
