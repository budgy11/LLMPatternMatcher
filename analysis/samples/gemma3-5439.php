
<!DOCTYPE html>
<html>
<head>
  <title>Search Products</title>
  <style>
    #search-bar {
      width: 300px;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
      margin-bottom: 10px;
    }

    #results {
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <h1>Search Products</h1>

  <input type="text" id="search-bar" placeholder="Enter search term">

  <div id="results">
    <?php
    if ($result->num_rows > 0) {
      echo "<ul>";
      while ($row = $result->fetch_assoc()) {
        echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>";
      }
      echo "</ul>";
    } else {
      echo "No results found.";
    }
    ?>
