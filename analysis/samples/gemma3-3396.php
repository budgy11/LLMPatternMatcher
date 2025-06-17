
<!DOCTYPE html>
<html>
<head>
  <title>Registration Form</title>
  <style>
    .error {
      color: red;
    }
    .success {
      color: green;
    }
  </style>
</head>
<body>

  <h1>Register</h1>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
