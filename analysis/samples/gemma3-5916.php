
<!DOCTYPE html>
<html>
<head>
  <title>Product Search</title>
  <style>
    /* Basic styling for the search bar and results */
    #search-bar {
      width: 300px;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    #results {
      margin-top: 20px;
    }

    .result {
      padding: 10px;
      border: 1px solid #eee;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h1>Product Search</h1>

  <input type="text" id="search-bar" placeholder="Search products...">

  <div id="results">
    <?php
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo '<div class="result">';
          echo 'Product Name: ' . $row["name"] . '<br>';
          echo 'Price: ' . $row["price"] . '<br>';
          echo 'Description: ' . $row["description"] . '<br>';
          echo '</div>';
        }
      } else {
        echo '<p>No products found matching your search.</p>';
      }
    ?>
