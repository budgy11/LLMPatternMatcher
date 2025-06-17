
<!DOCTYPE html>
<html>
<head>
  <title>Search Products</title>
  <style>
    /* Basic styling for the search bar and results */
    .search-container {
      margin: 20px;
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
      border: 1px solid #eee;
      margin-bottom: 10px;
      padding: 10px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search_term); ?>">
