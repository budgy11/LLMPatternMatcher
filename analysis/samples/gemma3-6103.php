        <input type="submit" value="Search">
    </form>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 5px;
      width: 300px; /* Adjust width as needed */
    }

    input[type="text"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 3px;
      box-sizing: border-box; /* Important for width calculations */
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
  </div>

  <?php
  // Example search logic (replace with your database query)
  if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']); // Sanitize input

    // Replace this with your actual database query
    $searchResults = array(
      "apple" => "A delicious fruit!",
      "banana" => "A yellow tropical fruit.",
      "orange" => "A juicy citrus fruit.",
      "example" => "This is an example result."
    );

    if (isset($searchResults[$searchTerm])) {
      echo "<p>You searched for: " . $searchTerm . "<br>" . $searchResults[$searchTerm] . "</p>";
    } else {
      echo "<p>No results found for: " . $searchTerm . "</p>";
    }
  }
  ?>
