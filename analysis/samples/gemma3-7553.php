
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Functionality</title>
  <style>
    .error-message {
      color: red;
      margin-top: 10px;
    }
    .success-message {
      color: green;
      margin-top: 10px;
    }
  </style>
</head>
<body>

  <h1>Purchase Order</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
