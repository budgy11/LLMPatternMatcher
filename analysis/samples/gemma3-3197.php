
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
  <style>
    .errors {
      color: red;
    }
    .error {
      margin-bottom: 5px;
    }
  </style>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
