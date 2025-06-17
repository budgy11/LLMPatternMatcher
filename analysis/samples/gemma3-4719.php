
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
  <style>
    .error {
      background-color: #f44336;
      padding: 10px;
      margin-top: 20px;
    }
    .success {
      background-color: #4caf50;
      padding: 10px;
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <h2>User Registration</h2>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
