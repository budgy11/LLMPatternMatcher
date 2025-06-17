
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
  <style>
    .error { color: red; }
    .success { color: green; }
  </style>
</head>
<body>

  <h1>Register</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
