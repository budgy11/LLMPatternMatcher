
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Functionality</title>
  <style>
    .success {
      color: green;
      margin-top: 10px;
    }
    .error {
      color: red;
      margin-top: 10px;
    }
  </style>
</head>
<body>

  <h2>Purchase Product</h2>

  <?php if (isset($error_message)) { ?>
