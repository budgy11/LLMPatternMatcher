
<!DOCTYPE html>
<html>
<head>
  <title>Search</title>
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

    button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
