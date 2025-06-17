
<!DOCTYPE html>
<html>
<head>
  <title>Search</title>
  <style>
    .search-container {
      width: 300px;
      margin: 0 auto;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
