
<!DOCTYPE html>
<html>
<head>
  <title>Search Results</title>
  <style>
    .search-container {
      margin-bottom: 20px;
    }

    input[type="text"] {
      width: 300px;
      padding: 8px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
