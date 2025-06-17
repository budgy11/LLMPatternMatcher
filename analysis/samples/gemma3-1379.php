
<!-- HTML for reset_password.php (example) -->
<!--
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Password</h1>
    <p>Enter the new password below:</p>
    <form method="post" action="update_password.php">
        <input type="password" name="new_password" required>
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
