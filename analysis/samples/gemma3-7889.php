
<!DOCTYPE html>
<html>
<head>
  <title>E-commerce Purchase</title>
  <style>
    body {
      font-family: sans-serif;
    }
    .cart {
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 10px;
    }
    .cart table {
      width: 100%;
      border-collapse: collapse;
    }
    .cart th, .cart td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }
  </style>
</head>
<body>

  <h1>E-commerce Purchase</h1>

  <?php if (isset($success_message)) { ?>
