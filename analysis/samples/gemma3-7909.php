
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Product</title>
  <style>
    body {
      font-family: sans-serif;
    }
    .container {
      width: 80%;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .error {
      color: red;
    }
    .success {
      color: green;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Purchase Product</h1>

    <?php if (isset($error)) { ?>
