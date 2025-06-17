
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
  <style>
    .error {
      color: red;
    }
  </style>
</head>
<body>

  <h2>User Registration</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
