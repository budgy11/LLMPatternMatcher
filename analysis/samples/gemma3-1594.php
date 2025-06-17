
<!DOCTYPE html>
<html>
<head>
  <title>Password Reset</title>
</head>
<body>

  <h1>Password Reset</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="hidden" name="reset_token" value="<?php echo isset($_GET['token']) ? $_GET['token'] : ''; ?>">
