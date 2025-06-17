
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

  <h1>Registration</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
