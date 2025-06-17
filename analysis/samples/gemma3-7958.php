
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Functionality</title>
  <style>
    body {
      font-family: sans-serif;
    }
    .container {
      width: 80%;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
    }
    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 8px;
      box-sizing: border-box;
      margin-bottom: 10px;
    }
    .success-message {
      color: green;
      margin-bottom: 10px;
    }
    .error-message {
      color: red;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Purchase Functionality</h1>

    <?php if (isset($error_message)) { ?>
