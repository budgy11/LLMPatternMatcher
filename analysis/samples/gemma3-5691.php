
<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
  <style>
    .search-container {
      margin-bottom: 20px;
    }
    input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px;
    }
    .results {
      margin-top: 20px;
    }
    .result-item {
      margin-bottom: 10px;
      border: 1px solid #eee;
      padding: 10px;
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
