
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
  <style>
    .error-messages {
      border: 1px solid red;
      padding: 10px;
      margin-top: 20px;
    }
    .error-text {
      color: red;
    }
  </style>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
