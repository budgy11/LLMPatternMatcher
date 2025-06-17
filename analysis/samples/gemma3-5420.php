
<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      width: 300px;
      margin: 20px auto;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Important for consistent sizing */
    }
  </style>
</head>
<body>

  <div class="search-container">
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
