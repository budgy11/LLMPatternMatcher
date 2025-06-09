
<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling - Customize as needed */
    #search-container {
      margin: 20px;
      width: 300px;
    }

    #search-input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Ensures padding doesn't add to width */
    }

    #search-button {
      background-color: #4CAF50; /* Green */
      color: white;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
      margin-left: 5px;
    }

    #search-button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div id="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <script>
    // JavaScript to handle the search button click
    document.getElementById('search-button').addEventListener('click', function() {
      // Get the search term from the input field
      var searchTerm = document.getElementById('search-input').value;

      // Prevent the form from submitting (if there's a form)
      if (searchTerm) {
        // Do something with the search term.  This is where you'd
        // typically call a function to search your data.
        console.log("Searching for: " + searchTerm);

        // Example:  You might want to display the search term in a div.
        //  This is just for demonstration.
        var searchResultsDiv = document.getElementById("search-results");
        if (searchResultsDiv) {
          searchResultsDiv.innerHTML = "<p>You searched for: " + searchTerm + "</p>";
        }

        // You could also redirect the user to a results page, or AJAX the data.

      } else {
        // The search input is empty, so do nothing.
        alert("Please enter a search term.");
      }
    });
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
<title>Simple Search Bar</title>
<style>
  .search-container {
    margin-bottom: 10px;
  }

  input[type="text"] {
    width: 300px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box; /* Important for consistent sizing */
  }

  .search-results {
    margin-top: 20px;
    border: 1px solid #ccc;
    padding: 10px;
  }
</style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
  </div>

  <div id="searchResults" class="search-results" style="display: none;">
    <h2>Search Results:</h2>
    <ul id="resultsList"></ul>
  </div>


  <script>
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const resultsList = document.getElementById('resultsList');

    searchInput.addEventListener('keyup', function(event) {
      const searchTerm = searchTerm.toLowerCase(); //Convert to lowercase for case-insensitive search

      // Sample data (replace with your actual data source)
      const data = [
        { title: 'Apple iPhone 13', description: 'The latest iPhone with a great camera.' },
        { title: 'Samsung Galaxy S22', description: 'A powerful Android phone.' },
        { title: 'Google Pixel 6', description: 'The best camera phone.' },
        { title: 'MacBook Air M2', description: 'A lightweight and powerful laptop.' },
        { title: 'Microsoft Surface Pro 9', description: 'A versatile 2-in-1 tablet.' }
      ];

      // Filter the data based on the search term
      const filteredData = data.filter(item => {
        return item.title.toLowerCase().includes(searchTerm) || item.description.toLowerCase().includes(searchTerm);
      });

      // Display the search results
      if (filteredData.length > 0) {
        searchResults.style.display = 'block';
        resultsList.innerHTML = ''; // Clear previous results

        filteredData.forEach(item => {
          const listItem = document.createElement('li');
          listItem.textContent = `${item.title} - ${item.description}`;
          resultsList.appendChild(listItem);
        });
      } else {
        searchResults.style.display = 'none'; // Hide if no results
      }
    });
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established as $conn

$searchTerm = $_GET['search']; // Get the search term from the URL

if ($searchTerm) {
    $sql = "SELECT * FROM products WHERE title LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Process the results and display them in HTML
        while($row = $result->fetch_assoc()) {
            // Display the row data (title, description, etc.)
            echo "<div>" . $row['title'] . " - " . $row['description'] . "</div>";
        }
    } else {
        echo "No results found.";
    }
}
?>


<?php

// Assuming you have a database connection established ($conn)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security!)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Escape the search term for use in the SQL query (prevent SQL injection)
$searchTerm = $conn->real_escape_string($searchTerm);


// Build the SQL query
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";  // Replace 'your_table_name' and 'your_column_name'

// Execute the query
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Results</title>
</head>
<body>

  <h1>Search Results</h1>

  <form action="" method="GET">
    <input type="text" name="search" placeholder="Enter search term" value="<?php echo $searchTerm; ?>">
    <button type="submit">Search</button>
  </form>

  <?php
  if ($result->num_rows > 0) {
    echo "<ul>";
    // Output the search results
    while($row = $result->fetch_assoc()) {
      echo "<li>" . $row["your_column_name"] . "</li>"; //Replace 'your_column_name'
    }
    echo "</ul>";
  } else {
    echo "<p>No results found.</p>";
  }
  ?>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search Our Website</h1>

  <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php
    // This section processes the search term and performs a basic search
    if (isset($_GET['search_term'])) {
      $search_term = htmlspecialchars($_GET['search_term']); // Sanitize input

      // Basic example:  Search within a simple array of products
      $products = [
        "Red T-Shirt" => "Description for Red T-Shirt",
        "Blue Jeans" => "Description for Blue Jeans",
        "Leather Jacket" => "Description for Leather Jacket",
        "Black Shoes" => "Description for Black Shoes"
      ];

      $results = [];

      foreach ($products as $product_name => $description) {
        if (stripos($product_name, $search_term) !== false) {  //Case-insensitive search
          $results[$product_name] = $description;
        }
      }

      if (!empty($results)) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        foreach ($results as $product_name => $description) {
          echo "<li><strong>$product_name</strong>: $description</li>";
        }
        echo "</ul>";
      } else {
        echo "<p>No results found for '$search_term'.</p>";
      }
    }
  ?>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db connection)

// Get the search term from the input field
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));

//  Example:  Search in a table called 'products' with a column named 'name'

//Option 1: Simple LIKE operator (case-insensitive)
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Option 2: Case-sensitive search
// $sql = "SELECT * FROM products WHERE name = '" . $searchTerm . "'";


// Execute the query
$result = mysqli_query($db, $sql);

// Check for errors
if ($result) {
  // Display the search results
  echo "<form method='get' action=''>";
  echo "<input type='text' name='search' value='" . $searchTerm . "' placeholder='Search'>";
  echo "<button type='submit'>Search</button>";
  echo "</form>";

  echo "<h2>Search Results:</h2>";

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<p>" . $row['name'] . "</p>"; // Assuming 'name' is the column you want to display
      // You can display other columns from the row here as needed.
    }
  } else {
    echo "<p>No results found.</p>";
  }
} else {
  echo "<p>Error executing query: " . mysqli_error($db) . "</p>";
}

// Close the database connection (important!)
mysqli_close($db);
?>


<?php
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

$db = mysqli_connect($host, $username, $password, $database);

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Your other code here, including the search bar logic.
?>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's assume the connection variable is $conn

// Function to handle the search query
function performSearch($searchTerm, $table_name) {
  // Sanitize the search term to prevent SQL injection
  $searchTerm = $conn->real_escape_string($searchTerm);

  // Construct the SQL query
  $sql = "SELECT * FROM $table_name WHERE ";

  // Add the WHERE clause based on whether the search term is in any column
  $columns = array();
  $column_names = array();  // Store column names for dynamic WHERE clause

  // Dynamically detect columns with searchable fields.
  $result = $conn->query("SHOW COLUMNS FROM $table_name");
  while($row = $result->fetch_assoc()) {
    if (isset($row['Type'])) { // Check if column has a type
      $columns[] = $row['Field'];
      $column_names[] = $row['Field'];
    }
  }

  // Build the WHERE clause
  $whereClause = "";
  $whereParams = [];
  foreach ($columns as $column) {
    $whereClause .= "($column) LIKE '%" . $searchTerm . "%'";
    $whereParams[] = $column;
  }

  if ($whereClause != "") {
    $sql = $sql . $whereClause;
  } else {
    //  If no columns are found, return an empty result set.
    return [];
  }

  // Execute the query
  $result = $conn->query($sql);

  // Process the results
  if ($result->num_rows > 0) {
    $rows = [];
    while ($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
    return $rows;
  } else {
    return []; // No results found
  }
}


// Example usage (assuming you have a form with a search input)
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $_GET['search'];

  // Specify the table name
  $table_name = 'your_table_name'; // Replace with your actual table name

  // Perform the search
  $searchResults = performSearch($searchTerm, $table_name);

  // Display the results
  if (count($searchResults) > 0) {
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    echo "<table border='1'>";
    echo "<thead><tr><th>Column 1</th><th>Column 2</th></tr></thead>"; // Adjust column names based on your table

    foreach ($searchResults as $row) {
      echo "<tr>";
      foreach ($row as $value) {
        echo "<td>" . htmlspecialchars($value) . "</td>"; // Escape for security
      }
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No results found for '" . htmlspecialchars($searchTerm) . "'.</p>";
  }
}

?>

<!-- HTML Form to Submit the Search Query -->
<form method="GET" action="">
  <input type="text" name="search" placeholder="Enter search term">
  <button type="submit">Search</button>
</form>


// Example of using prepared statements for search
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $_GET['search'];

  $sql = "SELECT * FROM your_table_name WHERE ";
  $params = [];

  // Dynamically build the WHERE clause and parameters
  foreach ($columns as $column) {
    $sql .= "($column) LIKE ?";
    $params[] = "%" . $searchTerm . "%";
  }

  $stmt = $conn->prepare($sql);
  $stmt->execute($params);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // ... rest of the code to display the results
}


<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = htmlspecialchars($_POST["searchTerm"]);

  // Sanitize the search term (important for security)
  $searchTerm = trim($searchTerm);
  $searchTerm = strip_tags($searchTerm);


  // Basic validation (optional but recommended)
  if (empty($searchTerm)) {
    $message = "Please enter a search term.";
  } else {
    // Perform your search logic here.  
    // This is just an example, you'll need to adapt it to your data.
    //  Replace this with your actual database query or file search.

    // Example:  Searching an array of products (replace with your data source)
    $products = [
      "apple", "banana", "orange", "grape", "kiwi",
      "computer", "laptop", "tablet", "smartphone"
    ];

    $foundProducts = [];
    foreach ($products as $product) {
      if (stripos($product, $searchTerm) !== false) { // Case-insensitive search
        $foundProducts[] = $product;
      }
    }

    if (empty($foundProducts)) {
      $message = "No results found for '" . $searchTerm . "'.";
    } else {
      $message = "Found " . count($foundProducts) . " results for '" . $searchTerm . "': " . implode(", ", $foundProducts);
    }
  }
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="searchTerm" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php
    if (isset($message)) {
      echo "<p>" . $message . "</p>";
    }
  ?>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form (e.g., using $_GET or $_POST)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection
$searchTerm = $searchTerm; // Basic sanitization - more robust sanitization is recommended

// Build the SQL query
$tableName = 'your_table_name'; // Replace with your table name
$columnName = 'your_column_name'; // Replace with the column you want to search in

$sql = "SELECT * FROM $tableName WHERE $columnName LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = mysqli_query($conn, $sql);

// Check for errors in the query
if (mysqli_error($conn)) {
    echo "Error executing query: " . mysqli_error($conn);
    exit;
}

// Display the search results (adjust this section as needed)
if (mysqli_num_rows($result) > 0) {
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    echo "<table border='1'>";
    echo "<thead><tr><th>Column 1</th><th>Column 2</th><th>Column 3</th></tr></thead>"; // Adjust column names as needed
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>"; // Important: Use htmlspecialchars() for security
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
}


// Example HTML form for the search bar
echo "<form method='GET' action='your_script.php'>  <!-- Replace 'your_script.php' with the actual script filename -->
    <label for='search'>Search:</label>
    <input type='text' id='search' name='search' value='" . htmlspecialchars($searchTerm) . "'>
    <button type='submit'>Search</button>
</form>";
?>


<?php

// Sample data (replace with your actual data source)
$products = [
    'apple' => ['name' => 'Red Apple', 'price' => 1.00],
    'banana' => ['name' => 'Yellow Banana', 'price' => 0.50],
    'orange' => ['name' => 'Navel Orange', 'price' => 0.75],
    'grape' => ['name' => 'Green Grape', 'price' => 1.25],
    'pear' => ['name' => 'Green Pear', 'price' => 0.90],
];

// Search term (initially empty)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Perform the search
$searchResults = [];

if ($searchTerm) {
    foreach ($products as $key => $product) {
        // Case-insensitive search
        if (stripos($product['name'], $searchTerm)) {
            $searchResults[$key] = $product;
        }
    }
}

// Output the search form and results
echo '<form method="get" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">';
echo 'Search: <input type="text" name="search" value="' . htmlspecialchars($searchTerm) . '" /> ';
echo '<input type="submit" value="Search" />';
echo '</form>';

if (empty($searchTerm)) {
    echo '<p>No search term entered.</p>';
} else {
    if (empty($searchResults)) {
        echo '<p>No results found for "' . htmlspecialchars($searchTerm) . '".</p>';
    } else {
        echo '<h2>Search Results for "' . htmlspecialchars($searchTerm) . '"</h2>';
        echo '<ul>';
        foreach ($searchResults as $key => $product) {
            echo '<li>' . htmlspecialchars($product['name']) . ' - $' . htmlspecialchars($product['price']) . '</li>';
        }
        echo '</ul>';
    }
}

?>


<?php

// Simple Search Bar Implementation in PHP

// This example demonstrates a basic search bar on a page.
// You'll need to adapt it to your specific needs, such as database queries,
// security, and styling.

// **HTML Form for the Search Bar**
?>
<form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="search">Search:</label>
    <input type="text" id="search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Search</button>
</form>

<?php

// **PHP Code to Handle the Search**

// Check if the search form has been submitted
if (isset($_GET['search'])) {
    // Get the search term
    $searchTerm = $_GET['search'];

    // Sanitize the search term to prevent XSS attacks. IMPORTANT!
    $searchTerm = htmlspecialchars($searchTerm);

    //  Example: Search in an array of strings.  Replace this with your actual data source.
    $data = array(
        "apple",
        "banana",
        "orange",
        "grapefruit",
        "strawberry"
    );

    // Convert the search term to lowercase for case-insensitive search
    $searchTerm = strtolower($searchTerm);

    // Search the data
    $searchResults = [];
    foreach ($data as $item) {
        if (strpos(strtolower($item), $searchTerm) !== false) {
            $searchResults[] = $item;
        }
    }

    // Display the search results (or a "No results found" message)
    if (empty($searchResults)) {
        echo "<p>No results found for '" . $searchTerm . "'</p>";
    } else {
        echo "<h2>Search Results for '" . $searchTerm . "'</h2>";
        echo "<ul>";
        foreach ($searchResults as $result) {
            echo "<li>" . $result . "</li>";
        }
        echo "</ul>";
    }
}
?>


<?php

// Assuming you have a database connection established (e.g., $db)

// Handle the search form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (important to prevent SQL injection)
  $searchTerm = htmlspecialchars(trim($searchTerm)); 

  // Perform the search
  $searchResults = performSearch($searchTerm); 

  // Display the search results
  echo "<form method='post' action='search.php'>
          <input type='text' name='search_term' value='" . $searchTerm . "' placeholder='Search...'>
          <button type='submit'>Search</button>
        </form>";
  
  if (!empty($searchResults)) {
    echo "<h2>Search Results:</h2>";
    echo "<ul>";
    foreach ($searchResults as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
  }
} else {
  // Display the search form if no form has been submitted
  echo "<form method='post' action='search.php'>
          <input type='text' name='search_term' placeholder='Search...'>
          <button type='submit'>Search</button>
        </form>";
}


// Function to perform the search (replace with your actual database query)
function performSearch($searchTerm) {
  // Replace this with your database query logic.  This is just a placeholder.

  // Example:  Assume you have a 'products' table with a 'name' column
  // and you want to search for products where the name contains the search term.

  //  This is just a sample; adjust it to your database and table structure
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); 

  $query = "SELECT name FROM products WHERE name LIKE '%" . $db->real_escape_string($searchTerm) . "%'";
  $result = $db->query($query);

  $searchResults = array();
  if ($result) {
    while ($row = $result->fetch(PDO::FETCH_COLUMN)) {
      $searchResults[] = $row;
    }
  }

  return $searchResults;
}

?>


<?php

// Sample data (replace with your actual data source)
$products = [
  "apple", "banana", "orange", "grape", "kiwi",
  "red apple", "green apple", "apple pie", "banana bread"
];

// Handle the search form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = trim($_POST["search_term"]);

  if (!empty($searchTerm)) {
    // Perform the search
    $searchResults = [];
    foreach ($products as $product) {
      if (stripos($product, $searchTerm) !== false) { // Case-insensitive search
        $searchResults[] = $product;
      }
    }

    // Display the results
    if (empty($searchResults)) {
      echo "<p>No results found for: " . htmlspecialchars($searchTerm) . "</p>";
    } else {
      echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
      echo "<ul>";
      foreach ($searchResults as $result) {
        echo "<li>" . htmlspecialchars($result) . "</li>";
      }
      echo "</ul>";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Products</title>
</head>
<body>

  <h1>Search Products</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter product name">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// and you have a table named 'products' with a column 'name'

// 1. Handle the Search Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = trim($_POST["search_term"]);

  // Sanitize the search term to prevent SQL injection
  $searchTerm = mysqli_real_escape_string($conn, $searchTerm);

  // Validate the search term (optional, but recommended)
  if (empty($searchTerm)) {
    // Do nothing or display an error message (e.g., "Please enter a search term")
    echo "<p>Please enter a search term.</p>";
  } else {
    // 2.  Construct the SQL Query
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

    // 3. Execute the Query
    $result = mysqli_query($conn, $sql);

    // 4. Display the Results
    if (mysqli_num_rows($result) > 0) {
      echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
      echo "<table border='1'>";
      echo "<tr><th>ID</th><th>Name</th><th>Description</th></tr>"; // Example columns
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>"; // Assuming 'id' is a column in your table
        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["description"]) . "</td>"; // Adjust based on your table structure
        echo "</tr>";
      }
      echo "</table>";
    } else {
      echo "<p>No results found for: " . htmlspecialchars($searchTerm) . "</p>";
    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Product Search</title>
</head>
<body>

  <h1>Product Search</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term...">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Replace with your actual database credentials
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ... (rest of the code from above) ...
?>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
    }
    input[type="text"] {
      padding: 8px;
      width: 300px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
    button {
      padding: 8px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <h1>Search:</h1>
    <input type="text" id="searchInput" placeholder="Enter search term...">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      // **IMPORTANT:  Replace this with your actual search logic**
      // This is a placeholder example.
      var results = performSearch(searchTerm);

      // Display the results (replace this with your desired display method)
      displaySearchResults(results);
    }

    // Placeholder function - Replace with your search implementation
    function performSearch(searchTerm) {
      // Example:  Return some dummy data based on the search term
      var results = [
        {title: "Apple Pie Recipe"},
        {title: "Banana Bread Recipe"},
        {title: "Chocolate Cake Recipe"}
      ];

      // Filter the results based on the search term
      var filteredResults = results.filter(function(item) {
        return item.title.toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1;
      });

      return filteredResults;
    }

    function displaySearchResults(results) {
      // Clear any previous results
      document.getElementById("searchResults").innerHTML = "";

      // Display the results
      if (results.length > 0) {
        var resultsContainer = document.getElementById("searchResults");
        resultsContainer.innerHTML = "<h3>Search Results:</h3><ul>";
        results.forEach(function(item) {
          resultsContainer.innerHTML += "<li>" + item.title + "</li>";
        });
        resultsContainer.innerHTML += "</ul>";
      } else {
        resultsContainer.innerHTML = "<p>No results found.</p>";
      }
    }
  </script>

  <!--  Container for results (optional, but recommended) -->
  <div id="searchResults"></div>

</body>
</html>


<?php
// Assume you have a database connection established

function performSearch($searchTerm) {
  // Example: Search a simple array of products
  $products = [
    ['name' => 'Apple', 'description' => 'Red delicious apple'],
    ['name' => 'Banana', 'description' => 'Yellow banana fruit'],
    ['name' => 'Chocolate Cake', 'description' => 'Rich chocolate dessert']
  ];

  $results = [];
  for ($i = 0; $i < count($products); $i++) {
    $product = $products[$i];
    if (strToLower($product['name']) . ' ' . strToLower($product['description']) . ' ' . strToLower($searchTerm) !== "") {
      $results[] = $product;
    }
  }
  return $results;
}
?>


<!DOCTYPE html>
<html>
<head>
<title>PHP Search Bar</title>
<style>
  .search-container {
    margin: 20px;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 5px;
  }

  input[type="text"] {
    width: 300px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  button {
    padding: 8px 15px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  button:hover {
    background-color: #3e8e41;
  }
</style>
</head>
<body>

<div class="search-container">
  <input type="text" id="searchInput" placeholder="Search...">
  <button onclick="search()">Search</button>
</div>

<script>
  function search() {
    var searchTerm = document.getElementById("searchInput").value;

    //  Basic example - replace this with your actual data retrieval logic
    //  This example just logs the search term.

    console.log("Searching for:", searchTerm);

    // **Important: Replace this with your data retrieval code.**
    // This code is just a placeholder.

    // Example: Retrieve data from an array (for demonstration)
    var data = [
      "Apple", "Banana", "Orange", "Grape", "Kiwi", "Mango"
    ];

    var results = data.filter(function(item) {
      return item.toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1;
    });

    // Display results (replace with your desired display method)
    if (results.length > 0) {
      alert("Found results: " + results.join(", "));
    } else {
      alert("No results found.");
    }
  }
</script>

</body>
</html>


<?php
// Assume you have a database connection established: $conn

if (isset($_SERVER["REQUEST_METHOD"]) && "POST" == $_SERVER["REQUEST_METHOD"]) {
    $searchTerm = $_POST["searchInput"];

    // Prepare SQL query (important for security!)
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    $products = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    // Output (example - replace with your desired display method)
    if (count($products) > 0) {
      echo "<ul>";
      foreach ($products as $product) {
        echo "<li>" . $product['name'] . " - " . $product['price'] . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No products found.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>PHP Search Bar with Database</title>
</head>
<body>

<div class="search-container">
  <input type="text" id="searchInput" placeholder="Search Products...">
  <button onclick="search()">Search</button>
</div>

<script>
  function search() {
    var searchTerm = document.getElementById("searchInput").value;

    // AJAX (Asynchronous JavaScript and XML) -  Example (you'll need to adapt this to your setup)
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "search.php");  //  The URL of your PHP file
    xhr.send(JSON.stringify({searchInput: searchTerm}));

    xhr.onload = function() {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.products) {
          // Display the products (using response.products)
          console.log(response.products);
        } else {
          console.log("No products found");
        }
      } else {
        console.error("Error:", xhr.status);
      }
    }
  }
</script>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    #search-container {
      margin-bottom: 10px;
    }

    #search-input {
      width: 300px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    #search-button {
      padding: 8px 15px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    #search-results {
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <div id="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <div id="search-results">
    <h2>Search Results:</h2>
    <?php
    // Example Data (Replace with your actual data source)
    $data = [
      ["item1", "description1"],
      ["item2", "description2"],
      ["item3", "description3"],
      ["item4", "description4"],
      ["item5", "description5"]
    ];

    // Get the search term from the input field
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

    // Perform the search
    $searchResults = [];
    foreach ($data as $item) {
      if (strpos($item[0], $searchTerm) !== false) { //Case-sensitive search
          $searchResults[] = $item;
      }
    }

    // Display the results
    if (empty($searchResults)) {
      echo "<p>No results found.</p>";
    } else {
      echo "<ul>";
      foreach ($searchResults as $result) {
        echo "<li>" . $result[0] . " - " . $result[1] . "</li>";
      }
      echo "</ul>";
    }
    ?>
  </div>

</body>
</html>


<?php

// Sample data (replace with your actual data source - e.g., database, array)
$data = [
    "apple", "banana", "cherry", "date", "fig", "grape", "kiwi"
];

// Get the search term from the form (if submitted)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important to prevent XSS and SQL injection)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Perform the search
$searchResults = [];
if ($searchTerm) {
    foreach ($data as $item) {
        if (stripos($item, $searchTerm) !== false) { // case-insensitive search
            $searchResults[] = $item;
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Example</title>
</head>
<body>

    <h1>Search Results</h1>

    <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="search" value="<?php echo $searchTerm; ?>">
        <button type="submit">Search</button>
    </form>

    <?php if (empty($searchResults)): ?>
        <p>No results found for: <?php echo htmlspecialchars($searchTerm); ?></p>
    <?php else: ?>
        <ul>
            <?php foreach ($searchResults as $result): ?>
                <li><?php echo htmlspecialchars($result); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    #search-container {
      margin: 20px;
      width: 300px;
    }
    #search-input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Important for consistent sizing */
    }
    #search-button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
    #search-results {
      margin-top: 20px;
      list-style: none;
      padding: 0;
    }
    #search-results li {
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #eee;
    }
  </style>
</head>
<body>

  <div id="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <ul id="search-results">
    <!-- Search results will be added here dynamically -->
  </ul>

  <script>
    // Sample data (replace with your actual data source)
    const data = [
      { id: 1, title: "Product A", description: "This is product A." },
      { id: 2, title: "Product B", description: "This is product B." },
      { id: 3, title: "Product C", description: "This is product C." },
      { id: 4, title: "Another Item", description: "A different item." }
    ];

    const searchInput = document.getElementById("search-input");
    const searchButton = document.getElementById("search-button");
    const searchResults = document.getElementById("search-results");

    searchButton.addEventListener("click", function() {
      const searchTerm = searchInput.value.toLowerCase();  // Convert to lowercase for case-insensitive search
      const results = [];

      for (let i = 0; i < data.length; i++) {
        const item = data[i];
        if (item.title.toLowerCase().includes(searchTerm) ||
            item.description.toLowerCase().includes(searchTerm)) {
          results.push(item);
        }
      }

      // Display the results
      searchResults.innerHTML = ""; // Clear previous results
      if (results.length > 0) {
        results.forEach(item => {
          const listItem = document.createElement("li");
          listItem.textContent = `${item.title} - ${item.description}`;
          searchResults.appendChild(listItem);
        });
      } else {
        searchResults.textContent = "No results found.";
      }
    });

    // Handle Enter key press as search
    searchInput.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            searchButton.click();
        }
    });
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
<title>Search Example</title>
<style>
  /* Basic styling - customize as needed */
  #search-bar {
    width: 300px;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    box-sizing: border-box; /* Ensures padding and border are included in width */
  }

  #search-button {
    padding: 8px 15px;
    background-color: #4CAF50; /* Green */
    color: white;
    border: none;
    cursor: pointer;
  }

  #search-button:hover {
    background-color: #3e8e41;
  }

  #results {
    margin-top: 20px;
  }

</style>
</head>
<body>

<h1>Search Example</h1>

<input type="text" id="search-bar" placeholder="Enter search term">
<button id="search-button">Search</button>

<div id="results">
  <!-- Search results will be displayed here -->
</div>

<script>
  // JavaScript code to handle the search
  document.getElementById('search-button').addEventListener('click', function() {
    var searchTerm = document.getElementById('search-bar').value.toLowerCase(); // Get the search term and convert to lowercase
    var resultsDiv = document.getElementById('results');
    resultsDiv.innerHTML = ''; // Clear previous results

    // *** Replace this with your actual search logic ***
    // This is just an example - you'll need to adapt it to your data source
    var data = [
      { title: "Apple iPhone 14", description: "The latest iPhone with amazing features." },
      { title: "Samsung Galaxy S23", description: "A powerful Android phone." },
      { title: "Google Pixel 7", description: "Google's flagship phone." },
      { title: "Amazon Echo Dot", description: "The popular smart speaker." },
      { title: "Sony WH-1000XM5", description: "Noise cancelling headphones." }
    ];

    if (searchTerm === "") {
      resultsDiv.innerHTML = "<p>Please enter a search term.</p>";
      return;
    }

    // Simple search - search by title or description
    data.forEach(function(item) {
      if (item.title.toLowerCase().includes(searchTerm) || item.description.toLowerCase().includes(searchTerm)) {
        var listItem = document.createElement('div');
        listItem.innerHTML = '<strong>' + item.title + '</strong><br>' + item.description;
        listItem.style.margin = '5px 0'; // Add some spacing
        resultsDiv.appendChild(listItem);
      }
    });

    if (resultsDiv.innerHTML === "") {
      resultsDiv.innerHTML = "<p>No results found for '" + searchTerm + "'.</p>";
    }
  });
</script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and you want to search a table named 'products' with a column named 'name'

// Get the search term from the GET request (if provided)
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection and XSS attacks
$search_term = htmlspecialchars(trim($search_term));

// Validate the search term -  important for security and usability
if (empty($search_term)) {
    $results = []; // Return an empty array if no search term is provided
} else {
    // Prepare the SQL query -  VERY IMPORTANT for security
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

    // Execute the query
    $result = mysqli_query($db, $sql);

    if ($result) {
        $results = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
    } else {
        // Handle query error
        echo "Error: " . mysqli_error($db);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Products</title>
</head>
<body>

    <h1>Search Products</h1>

    <form method="GET" action="">
        <input type="text" name="search" value="<?php echo $search_term; ?>" placeholder="Enter search term">
        <input type="submit" value="Search">
    </form>

    <?php
    // Display the search results
    if (!empty($results)) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        foreach ($results as $product) {
            echo "<li>" . $product['name'] . " - " . $product['description'] . "</li>"; // Assuming 'name' and 'description' columns exist
        }
        echo "</ul>";
    } else {
        echo "<p>No products found matching your search.</p>";
    }
    ?>

</body>
</html>


<?php

// Example usage:  This is a basic example and needs to be adapted to your specific needs.

// 1. Get the search query from the search bar (using GET or POST)
//    Let's assume you're using GET method.  For POST, you would use $_POST['search_term'].

$search_term = isset($_GET['search']) ? $_GET['search'] : ''; // Get the search term from the GET request.  Empty string if no 'search' parameter is present.

// 2. Sanitize the search term (VERY IMPORTANT!)
//   This prevents SQL injection and other vulnerabilities.  This example uses simple trimming and escaping.  For production, use a robust escaping function.
$search_term = trim($search_term);  // Remove leading/trailing whitespace
$search_term = htmlspecialchars($search_term); // Escape HTML characters

// 3. Perform the search (replace this with your actual search logic)
$results = [];  // Array to store search results

if ($search_term) {
    // **IMPORTANT:** Replace this with your actual search logic
    // This is just a placeholder example.

    // Example 1:  Simple string search in a fixed list:
    $data = [
        "apple", "banana", "cherry", "date", "fig", "grape"
    ];

    $results = array_filter($data, function($item) use ($search_term) {
        return stripos($item, $search_term) !== false; // Case-insensitive search
    });
    
    // Example 2:  Search in a database (replace with your database connection and query)
    /*
    $conn = new mysqli("localhost", "username", "password", "database_name");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM your_table WHERE name LIKE '%" . $search_term . "%'"; // IMPORTANT: STILL Sanitize your query!
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    }

    $conn->close();
    */
}

// 4. Display the search bar and the results
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Example</title>
</head>
<body>

    <h1>Search</h1>

    <form method="get" action="">  <!--  method="get" means the search term will be sent in the URL.  method="post" would send it as a POST request. -->
        <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>" placeholder="Enter search term">
        <input type="submit" value="Search">
    </form>

    <?php if (!empty($results)): ?>
        <h2>Search Results:</h2>
        <ul>
            <?php foreach ($results as $result): ?>
                <li><?php echo htmlspecialchars($result); ?></li>  <!-- Escape each result before displaying -->
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No results found for <?php echo htmlspecialchars($search_term); ?></p>
    <?php endif; ?>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      width: 300px;
      margin: 0 auto;
      margin-top: 20px;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Important for consistent sizing */
    }

    button {
      padding: 10px 15px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      cursor: pointer;
      margin-left: 5px;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchBox" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>


  <script>
    function search() {
      // Get the value from the search box
      var searchTerm = document.getElementById("searchBox").value;

      // Check if the search term is empty
      if (searchTerm === "") {
        alert("Please enter a search term.");
        return;
      }

      // **Replace this with your actual search logic**
      // This is just a placeholder example.  You'll likely want to
      // make an AJAX request to your server to perform the search
      // against your data.

      // Example:  Display a simple message
      alert("Searching for: " + searchTerm);

      // Simulate a search - you'd replace this with your data retrieval code.
      // For example, fetch data from a database or an array.

    }
  </script>

</body>
</html>


<?php
// Assuming you have a function called `searchData()` that performs the search
// and returns an array of results.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["search_term"];

  // Replace this with your actual search logic
  $results = searchData($searchTerm);

  echo json_encode($results); // Send the results as JSON
}
?>


<!DOCTYPE html>
<html>
<head>
<title>Simple Search Bar</title>
<style>
  .search-container {
    margin: 20px;
    border: 1px solid #ccc;
    padding: 10px;
  }
  .search-input {
    width: 300px;
    padding: 8px;
    border: 1px solid #ccc;
    box-sizing: border-box; /* Important for consistent sizing */
  }
  .search-button {
    padding: 8px 12px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
  }
  .results {
    margin-top: 20px;
  }
</style>
</head>
<body>

<div class="search-container">
  <input type="text" class="search-input" placeholder="Search..." id="searchInput">
  <button class="search-button" onclick="search()">Search</button>
</div>

<div class="results" id="results">
  <!-- Search results will be displayed here -->
</div>

<script>
  function search() {
    var searchTerm = document.getElementById("searchInput").value;
    var resultsDiv = document.getElementById("results");

    // **Replace this with your actual search logic**
    // This is just a placeholder example.
    if (searchTerm.trim() === "") {
      resultsDiv.innerHTML = ""; // Clear previous results
      return;
    }

    // Example:  Let's assume your data is in an array called 'items'
    var items = [
      "Apple",
      "Banana",
      "Orange",
      "Grape",
      "Strawberry"
    ];

    var results = [];
    for (var i = 0; i < items.length; i++) {
      if (items[i].toLowerCase().indexOf(searchTerm.toLowerCase()) > -1) {
        results.push(items[i]);
      }
    }

    if (results.length > 0) {
      var resultsHTML = "<ul>";
      for (var j = 0; j < results.length; j++) {
        resultsHTML += "<li>" + results[j] + "</li>";
      }
      resultsHTML += "</ul>";
      resultsDiv.innerHTML = resultsHTML;
    } else {
      resultsDiv.innerHTML = "No results found.";
    }
  }
</script>

</body>
</html>


<?php
// Assuming you have a database connection established
// (e.g., $conn = mysqli_connect("localhost", "user", "password", "database");)

function searchDatabase($searchTerm) {
  $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'"; // Example - Adjust the table/column names.
  $result = mysqli_query($conn, $query);

  $products = [];
  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $products[] = $row;
    }
    mysqli_free_result($result); // Free the result set
  } else {
    // Handle database error
    error_log("Database error: " . mysqli_error($conn));
  }

  return $products;
}

// Call the function
$searchResults = searchDatabase($searchTerm);

// Display the results (similar to the previous example, but using $searchResults)
// ...
?>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's assume you have a connection variable called $conn

// Get the search term from the search bar
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (very important to prevent SQL injection)
$search_term = htmlspecialchars(trim($search_term));

// Escape the search term for use in the SQL query
$search_term = $conn->real_escape_string($search_term);  //  Use real_escape_string for mysqli

//  Alternatively,  use prepared statements for even better security:
//  $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
//  $stmt->bind_param("s", $search_term, $search_term);
//  $stmt->execute();



//  Example:  Searching a table named 'products' with columns 'name' and 'description'

$sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%' OR description LIKE '%" . $search_term . "%'";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Results</title>
</head>
<body>

  <h1>Search Results</h1>

  <form method="GET" action="">
    <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search_term); ?>">
    <button type="submit">Search</button>
  </form>

  <?php
    if ($result->num_rows > 0) {
      echo "<ul>";
      // Output each row as a list item
      while($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "<strong>" . htmlspecialchars($row['name']) . "</strong> - " . htmlspecialchars($row['description']) . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found.</p>";
    }
  ?>

</body>
</html>


<?php

// Sample data (replace with your actual data source - database, array, etc.)
$items = [
    "apple",
    "banana",
    "orange",
    "grape",
    "watermelon",
    "strawberry",
    "pineapple",
    "kiwi"
];

// Get the search term from the GET request
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars($searchTerm);

// Perform the search
$searchResults = [];
if ($searchTerm) {
    foreach ($items as $item) {
        // Case-insensitive comparison
        if (stripos($item, $searchTerm) !== false) {
            $searchResults[] = $item;
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
</head>
<body>

    <h1>Search Items</h1>

    <form method="GET">
        <input type="text" name="search" placeholder="Enter search term" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($searchResults)): ?>
        <h2>Search Results:</h2>
        <ul>
            <?php foreach ($searchResults as $result): ?>
                <li><?php echo htmlspecialchars($result); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No results found for <?php echo htmlspecialchars($searchTerm); ?></p>
    <?php endif; ?>

</body>
</html>


<?php
// Connect to your database (replace with your credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search term from the GET request
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Sanitize the search term
$searchTerm = htmlspecialchars($searchTerm);

// Perform the search
$searchResults = [];
if ($searchTerm) {
    // SQL query with LIKE operator for case-insensitive search
    $sql = "SELECT * FROM your_table WHERE item_name LIKE '%" . $searchTerm . "%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $searchResults[] = htmlspecialchars($row['item_name']); // Escape the output
        }
    }
}

$conn->close();

// ... (HTML structure as in the previous example) ...
?>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// $db = new mysqli("localhost", "username", "password", "database_name");
// Check connection
// if ($db->connect_error) {
//  die("Connection failed: " . $db->connect_error);
// }

// Example database table structure (adjust to your actual table)
// CREATE TABLE products (
//   id INT AUTO_INCREMENT PRIMARY KEY,
//   name VARCHAR(255) NOT NULL,
//   description TEXT
// );

//  Search Bar HTML
echo '<input type="text" id="searchInput" placeholder="Search products...">';

// JavaScript to handle the search
echo '<script>';
echo 'document.getElementById("searchInput").addEventListener("keyup", function() {';
echo '  var searchTerm = this.value.toLowerCase();';
echo '  document.body.innerHTML = ""; // Clear previous results';
echo '  // Replace this with your actual search logic';
echo '  // Example: Filter products based on searchTerm';
echo '  // var results = filterProducts(searchTerm);';
echo '  //  If results is not empty, display them here';
echo '  //  Example: Display results in a div with id="searchResults"';
echo '});';
echo '</script>';


//  Example PHP function to filter products (replace with your logic)
function filterProducts($searchTerm) {
  // Replace this with your actual database query
  // This is just a placeholder for demonstration
  $products = [
    ['id' => 1, 'name' => 'Laptop', 'description' => 'High-performance laptop'],
    ['id' => 2, 'name' => 'Mouse', 'description' => 'Wireless mouse'],
    ['id' => 3, 'name' => 'Keyboard', 'description' => 'Ergonomic keyboard'],
    ['id' => 4, 'name' => 'Headphones', 'description' => 'Noise-cancelling headphones'],
  ];

  $results = [];
  foreach ($products as $product) {
    if (str_contains(strtolower($product['name']), $searchTerm) ||
        str_contains(strtolower($product['description']), $searchTerm)) {
      $results[] = $product;
    }
  }

  return $results;
}
?>


<?php

// Assuming you have a form to collect the search query
?>

<!DOCTYPE html>
<html>
<head>
<title>Search Bar Example</title>
</head>
<body>

<h1>Search Example</h1>

<form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <input type="text" name="search_term" placeholder="Enter search term..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
  <input type="submit" value="Search">
</form>

<?php

//  Example of how to handle the search term and process the data
if (isset($_GET['search_term'])) {
  $search_term = htmlspecialchars($_GET['search_term']);

  // *** IMPORTANT SECURITY CONSIDERATIONS ***
  // 1.  Input Validation and Sanitization:  This example shows basic sanitization.  
  //     You absolutely MUST add robust input validation and sanitization. 
  //     This is the most critical part of any search bar to prevent security vulnerabilities
  //     like SQL injection or cross-site scripting (XSS).  Use appropriate functions 
  //     like `filter_var()` or a dedicated sanitization library.

  // 2.  Escape for Different Output Contexts:  `htmlspecialchars()` is great for HTML output,
  //     but you might need to escape the search term differently depending on where you're 
  //     using it (e.g., in a database query).

  //  Example:  Let's assume you're searching through a database table called 'products'
  //          that has a 'name' column.

  //  ***  WARNING:  This is a VERY simplified example and MUST be adapted to your specific database and needs. ***
  //  It's vulnerable to SQL injection if not properly handled.

  //  $query = "SELECT * FROM products WHERE name LIKE '%" . $query . "%'";  //  This is dangerous.
  //  $result = mysqli_query($connection, $query); // Replace $connection with your database connection

  //  If you are using prepared statements (HIGHLY recommended for security):

  //  $query = "SELECT * FROM products WHERE name LIKE ?";
  //  $stmt = mysqli_prepare($connection, $query); // Replace $connection with your database connection
  //  mysqli_stmt_bind_param($stmt, "s", $query); // "s" for string
  //  mysqli_stmt_execute($stmt);
  //  $results = mysqli_stmt_get_result($stmt);
  //  // ... Process the $results ...


  // Example of simply displaying the search term (for demonstration)
  echo "<p>You searched for: " . htmlspecialchars($search_term) . "</p>";

}
?>

</body>
</html>


<?php

// This example demonstrates a basic search bar.
// You'll likely want to integrate this into a more robust application
// with database connections and proper error handling.

// Get the search term from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = isset($_POST["search_term"]) ? trim($_POST["search_term"]) : "";

  // Sanitize the search term - VERY IMPORTANT to prevent XSS
  $searchTerm = htmlspecialchars($searchTerm); 

  // Perform your search logic here.  This is just a placeholder.
  $searchResults = performSearch($searchTerm); 
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term" value="<?php echo htmlspecialchars($searchTerm); ?>">
    <button type="submit">Search</button>
  </form>

  <?php if (!empty($searchTerm)) {
    echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
    if (empty($searchResults)) {
      echo "<p>No results found.</p>";
    } else {
      echo "<ul>";
      foreach ($searchResults as $result) {
        echo "<li>" . htmlspecialchars($result) . "</li>";
      }
      echo "</ul>";
    }
  } ?>

</body>
</html>


function performSearch($searchTerm) {
  $dbHost = "localhost";
  $dbName = "your_database_name";
  $dbUser = "your_username";
  $dbPassword = "your_password";

  $mysqli = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

  if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
  }

  $sql = "SELECT * FROM your_table WHERE title LIKE ? OR description LIKE ?";
  $stmt = $mysqli->prepare($sql);

  if ($stmt->prepare() === false) {
    die("Error preparing statement: " . $mysqli->error);
  }

  $searchTermEscaped = "%" . $mysqli->real_escape_string($searchTerm) . "%"; //Important for LIKE queries

  $stmt->bind_param("ss", $searchTermEscaped); // "ss" means two strings

  $stmt->execute();

  $results = $stmt->get_result();

  $stmt->close();
  $results->close();

  return $results; //Returns a mysqli_result object, which you'll loop through.
}


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px;
      margin: 20px auto;
      text-align: center;
    }

    input[type="text"] {
      width: 90%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div id="searchResults" style="margin-top: 20px;">
    <!-- Search results will appear here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      var resultsDiv = document.getElementById("searchResults");

      // **Important:** Replace this with your actual search logic
      // This is just a placeholder to demonstrate how to display results.
      // You should replace it with your database query or other search method.

      if (searchTerm.trim() === "") {
        resultsDiv.innerHTML = ""; // Clear results if search term is empty
        return;
      }


      // Example search logic (replace with your actual implementation)
      var results = [];
      if (searchTerm.toLowerCase() === "apple") {
          results = ["Apple iPhone", "Apple iPad", "Apple Watch"];
      } else if (searchTerm.toLowerCase() === "banana") {
          results = ["Banana Bunch", "Banana Bread"];
      }
      else {
          results = ["No results found", "Try a different search term"];
      }

      // Clear previous results
      resultsDiv.innerHTML = "";

      // Display the results
      if (results.length > 0) {
          for (var i = 0; i < results.length; i++) {
              var resultItem = document.createElement("p");
              resultItem.textContent = results[i];
              resultsDiv.appendChild(resultItem);
          }
      }
      else{
          var noResultItem = document.createElement("p");
          noResultItem.textContent = "No results found";
          resultsDiv.appendChild(noResultItem);
      }

    }
  </script>

</body>
</html>


<?php

// This is a basic example.  You'll likely want to adapt this to your
// specific database and search needs.

// -------------------------------------------------------------------
// Configuration
// -------------------------------------------------------------------

$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$results_per_page = 10; // Number of results to display per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// -------------------------------------------------------------------
// Database Connection (Replace with your actual connection)
// -------------------------------------------------------------------

// Example using a dummy database (replace with your database details)
// $db_host = "localhost";
// $db_name = "your_database_name";
// $db_user = "your_username";
// $db_pass = "your_password";
// $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }



// -------------------------------------------------------------------
// Search Logic
// -------------------------------------------------------------------

// Sanitize the search term
$search_term = trim($search_term);
$search_term = htmlspecialchars($search_term);  //Important for security - prevents XSS

// SQL query (adjust this based on your database schema)
$query = "SELECT * FROM your_table_name WHERE title LIKE '%" . $search_term . "%'"; // Example:  searching in a 'title' column.  Use proper escaping for your database system.


// Execute the query
$result = $conn->query($query);

// -------------------------------------------------------------------
// Pagination
// -------------------------------------------------------------------
$start = ($page - 1) * $results_per_page;
$end = $start + $results_per_page;


// -------------------------------------------------------------------
// Display Results
// -------------------------------------------------------------------

echo "<form method='get' action=''>";
echo "<input type='text' name='search' value='" . $search_term . "' style='width: 300px;'>";
echo "<input type='submit' value='Search'>";
echo "</form>";

if ($result->num_rows > 0) {
    echo "<h2>Search Results for: " . $search_term . "</h2>";
    echo "<table border='1'>";
    echo "<thead><tr><th>ID</th><th>Title</th><th>Description</th></tr></thead>";  // Adjust columns as needed
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";  // Adjust column names
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>" . $row['description'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No results found for: " . $search_term . "</p>";
}

// Pagination links
if ($page > 1) {
    echo "<br><a href='?search=" . $search_term . "&page=" . ($page - 1) . "'>Previous</a>";
}

for ($i = 1; $i <= max(1, ceil($page / $results_per_page)); $i++) {
    if ($i != $page) {
        echo "<a href='?search=" . $search_term . "&page=" . $i . "'>$i</a> ";
    }
}

if ($page < max(1, ceil($page / $results_per_page))) {
    echo "<a href='?search=" . $search_term . "&page=" . ($page + 1) . "'>Next</a>";
}
// -------------------------------------------------------------------
// Close Connection
// -------------------------------------------------------------------
// $conn->close(); // Important to close the database connection

?>


<?php

// ... (Configuration as before) ...

// SQL query with placeholders
$query = "SELECT * FROM your_table_name WHERE title LIKE :search_term";

// Prepare the statement
$stmt = $conn->prepare($query);

if ($stmt) {
    // Bind the parameter
    $stmt->bind_param("s", $search_term); // "s" indicates a string

    // Execute the statement
    $stmt->execute();

    // Fetch the results
    $result = $stmt->get_result();

    // Process the results (as before)
    // ... (Display Results section) ...

    // Close the statement
    $stmt->close();
} else {
    // Handle the error
    echo "Error preparing statement: " . $conn->error;
}

// ... (Close Connection) ...
?>


<?php

// Assuming you have a database connection established (e.g., $db_connection)

// Get the search term from the form
$search_term = isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : '';

// Perform the search
if ($search_term) {
  //  Example: Search through a database table named 'products'
  //  Replace this with your actual query and database setup.

  $results = [];

  // Example query (adapt to your database schema and needs)
  $sql = "SELECT * FROM products WHERE product_name LIKE '%" . $search_term . "%' OR description LIKE '%" . $search_term . "%'";
  $result = mysqli_query($db_connection, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $results[] = $row;
    }
  }

  // You can add error handling here to check if the query was successful.
  mysqli_free_result($result); // Free up the result set

} else {
  //  No search term entered, display all results or a default page.
  //  You can adjust this part to show all products or a "No search term" page.
  $results = []; // Reset the results array
  //  Example: Display all products
  //  $sql = "SELECT * FROM products";
  //  $result = mysqli_query($db_connection, $sql);
  //  $results = [];
  //  while ($row = mysqli_fetch_assoc($result)) {
  //    $results[] = $row;
  //  }
  //  mysqli_free_result($result);
}


?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search Products</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term" value="<?php echo $search_term; ?>">
    <button type="submit">Search</button>
  </form>

  <?php if (!empty($results)): ?>
    <h2>Search Results:</h2>
    <?php if (empty($results)): ?>
      <p>No results found for "<?php echo htmlspecialchars($search_term); ?>"</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Product Name</th>
            <th>Description</th>
            <th>Other Columns...</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($results as $row): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['product_name']); ?></td>
              <td><?php echo htmlspecialchars($row['description']); ?></td>
              <td>...</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

<?php  // Display all results (commented out - uncomment if needed)
//   if (empty($results)):
//     echo "<p>No results found for '" . htmlspecialchars($search_term) . "'</p>";
//   else:
//      echo "<p>No results found.</p>";
//   endif;
?>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Escape the search term for use in the query (prevents SQL injection)
$escapedSearchTerm = $conn->real_escape_string($searchTerm);


// Perform the search (example using a simple table called 'products')
$sql = "SELECT * FROM products WHERE name LIKE '%" . $escapedSearchTerm . "%'"; //Using LIKE for partial matches
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Products</title>
</head>
<body>

    <h1>Search Products</h1>

    <form method="GET" action="">
        <input type="text" name="search" placeholder="Enter search term" value="<?php echo $searchTerm; ?>">
        <button type="submit">Search</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No products found matching your search.</p>";
    }
    ?>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (still needed for HTML escaping in the output)
$searchTerm = htmlspecialchars($searchTerm);

// Escape the search term for the query - prepared statements handle the escaping
$sql = "SELECT * FROM products WHERE name LIKE :search";
$stmt = $conn->prepare($sql);  //Use prepare()
$stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR); //Bind the parameter
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      margin-bottom: 10px;
    }

    input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px; /* Adjust as needed */
    }

    button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      //  Example:  Search within a hypothetical array of items
      //  Replace this with your actual data retrieval logic
      var items = ["apple", "banana", "cherry", "date", "elderberry"];

      if (searchTerm) {
        var results = items.filter(function(item) {
          return item.toLowerCase().includes(searchTerm.toLowerCase());
        });

        // Display the results (replace this with your desired display method)
        var resultsHTML = "<ul>";
        results.forEach(function(result) {
          resultsHTML += "<li>" + result + "</li>";
        });
        resultsHTML += "</ul>";

        document.getElementById("resultsContainer").innerHTML = resultsHTML; // Display results in a results container
      } else {
        // Clear results if the search box is empty
        document.getElementById("resultsContainer").innerHTML = "";
      }
    }
  </script>

  <!-- Container for displaying search results (optional) -->
  <div id="resultsContainer"></div>


</body>
</html>


<?php

// Simple search bar implementation using PHP

// Check if the search form has been submitted
if (isset($_SERVER["REQUEST_METHOD"]) && "POST" == $_SERVER["REQUEST_METHOD"]) {
  // Get the search term from the form
  $searchTerm = htmlspecialchars(trim($_POST["search"]));

  // Perform the search (replace with your actual search logic)
  $searchResults = performSearch($searchTerm); 

  // Display the search results
  echo "<div class='search-results'>";
  if (empty($searchResults)) {
    echo "No results found for: " . $searchTerm;
  } else {
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    echo "<ul class='search-results-list'>";
    foreach ($searchResults as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  }
  echo "</div>";
}

// Form for submitting the search term
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <label for="search">Search:</label>
  <input type="text" id="search" name="search" placeholder="Enter search term">
  <button type="submit">Search</button>
</form>


function performSearch($searchTerm) {
  $data = [
    "apple",
    "banana",
    "orange",
    "grapefruit",
    "pineapple"
  ];
  $results = [];
  foreach ($data as $item) {
    if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
      $results[] = $item;
    }
  }
  return $results;
}


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
    }
    input[type="text"] {
      padding: 8px;
      width: 300px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .results {
      margin-top: 20px;
    }
    .result-item {
      padding: 10px;
      border: 1px solid #eee;
      margin-bottom: 5px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <h2>Search</h2>
    <input type="text" id="search-input" placeholder="Enter search term">
    <button onclick="performSearch()">Search</button>
  </div>

  <div class="results" id="results-container">
    <!-- Results will be displayed here -->
  </div>


  <script>
    function performSearch() {
      const searchTerm = document.getElementById('search-input').value.toLowerCase();
      const resultsContainer = document.getElementById('results-container');

      // **Important:  Replace this with your actual data source and logic**
      const data = [
        { title: 'Apple iPhone 14', description: 'The latest iPhone with a fantastic camera.' },
        { title: 'Samsung Galaxy S23', description: 'A powerful Android phone.' },
        { title: 'Google Pixel 7', description: 'Google\'s flagship phone.' },
        { title: 'Laptop - Dell XPS 13', description: 'A sleek and powerful laptop.' },
        { title: 'Gaming PC - Corsair', description: 'A high-performance gaming PC.' },
      ];

      // Clear previous results
      resultsContainer.innerHTML = '';

      // Loop through the data and display matching results
      data.forEach(item => {
        const title = item.title.toLowerCase();
        const description = item.description.toLowerCase();

        if (title.includes(searchTerm) || description.includes(searchTerm)) {
          const resultItem = document.createElement('div');
          resultItem.classList.add('result-item');
          resultItem.innerHTML = `
            <h3>${item.title}</h3>
            <p>${item.description}</p>
          `;
          resultsContainer.appendChild(resultItem);
        }
      });
    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established
$searchTerm = $_GET['search']; // Get the search term from the query string

// Database connection details (replace with your details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Construct the SQL query (case-insensitive search)
$sql = "SELECT title, description FROM products WHERE title LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<div class='result-item'>" .
         "<h3>" . $row["title"] . "</h3>" .
         "<p>" . $row["description"] . "</p>" .
         "</div>";
  }
} else {
  echo "<p>No results found.</p>";
}

$conn->close();
?>


<?php

// Assuming you're working in a web environment (e.g., Apache with PHP)

// Initialize an empty array to hold the search results (for demonstration)
$searchResults = [];

// Function to handle the search submission
function handleSearch($searchTerm) {
  // Sanitize the search term to prevent XSS vulnerabilities
  $searchTerm = htmlspecialchars(trim($searchTerm));

  // Basic example: Search in a simple array of products
  $products = [
    "Laptop X1",
    "Tablet Z2",
    "Smartphone Y5",
    "Wireless Mouse",
    "Keyboard K3",
  ];

  // Convert search term to lowercase for case-insensitive search
  $searchTermLower = strtolower($searchTerm);

  $searchResults = []; // Reset results for each search

  foreach ($products as $product) {
    $productLower = strtolower($product);
    if (strpos($productLower, $searchTermLower) !== false) { // strpos returns position or false if not found
      $searchResults[] = $product;
    }
  }
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Handle the search
  handleSearch($searchTerm);
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
</head>
<body>

  <h1>Search Products</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php if (empty($searchResults)) {
    echo "<p>No results found.</p>";
  } else {
    echo "<h2>Search Results:</h2>";
    echo "<ul>";
    foreach ($searchResults as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  } ?>

</body>
</html>


<?php
// ... (previous code) ...

// Assuming you have a database connection established
$db = new mysqli("localhost", "username", "password", "database_name");

if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}

// Function to handle the search
function handleSearch($searchTerm) {
  $searchTerm = htmlspecialchars(trim($searchTerm));
  $searchTermLower = strtolower($searchTerm);

  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTermLower . "%'";  // Use LIKE for partial matches
  $result = $db->query($sql);

  if ($result->num_rows > 0) {
    $searchResults = [];
    while($row = $result->fetch_assoc()) {
      $searchResults[] = $row['name']; // Adjust column name if needed
    }
  } else {
    $searchResults = []; // No results
  }
}

// ... (rest of the code - form, display) ...

// Close the database connection when done
$db->close();
?>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Simple search bar HTML form
echo '<form action="search_results.php" method="GET">';
echo '  <input type="text" name="search_term" placeholder="Search..."';
echo '      value="' . htmlspecialchars($_GET['search_term']) . '" />';
echo '  <button type="submit">Search</button>';
echo '</form>';


// Example of how to search in a database (assuming a table called 'products' with a 'name' column)
// This is just an example; adjust the query and column names to match your database
if (isset($_GET['search_term'])) {
  $searchTerm = htmlspecialchars($_GET['search_term']);

  // Escape the search term to prevent SQL injection
  $searchTermEscaped = $conn->real_escape_string($searchTerm);  // Use real_escape_string for mysqli

  //  A basic search query
  $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTermEscaped . "%'";

  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    echo '<h2>Search Results for: ' . htmlspecialchars($searchTerm) . '</h2>';
    echo '<ul>';
    while ($row = $result->fetch_assoc()) {
      echo '<li>' . htmlspecialchars($row['name']) . ' - ' . htmlspecialchars($row['description']) . '</li>';  // Assuming you have a 'description' column
    }
    echo '</ul>';
  } else {
    echo '<p>No results found for: ' . htmlspecialchars($searchTerm) . '</p>';
  }
}

?>


<?php
// Assuming you're already in a session where the search term is available
session_start();

if (isset($_GET['search_term'])) {
    $searchTerm = htmlspecialchars($_GET['search_term']);

    // Your database query logic (as in the main page)
    // ...

    // Display the search results here
    // ...
}
?>


<?php

// Assuming you have a database connection established (e.g., $db)

// Function to handle the search query
function performSearch($search_term, $table_name, $search_columns) {
  global $db; // Access the database connection

  // Sanitize and escape the search term
  $search_term = $db->real_escape_string($search_term);

  // Build the SQL query
  $sql = "SELECT * FROM " . $table_name . " WHERE ";

  // Build the WHERE clause dynamically based on the search_columns
  $where_clause = "";
  $conditions = [];
  foreach ($search_columns as $column) {
    // Add the condition only if the column is not empty
    if (!empty($column)) {
      $where_clause .= " (" . $column . ") LIKE '%" . $search_term . "%' OR ";
      $conditions[] = "($column) LIKE '%" . $search_term . "%'";
    }
  }

  // Remove the trailing "OR"
  $where_clause = rtrim($where_clause, 'OR');

  // Add the WHERE clause to the SQL query
  $sql .= $where_clause . " LIMIT 10"; // Limit results for better performance - adjust as needed

  // Execute the query
  $result = $db->query($sql);

  // Process the results
  if ($result) {
    echo "<form method='get' action='search_results.php'>
            <input type='text' name='search' placeholder='Search...' value='" . htmlspecialchars($search_term) . "'/>
            <button type='submit'>Search</button>
          </form>";

    if ($result->num_rows > 0) {
      echo "<ul>";
      while ($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['id']) . ": " . htmlspecialchars($row['name']) . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found.</p>";
    }

  } else {
    echo "<p>Error executing query: " . $db->error . "</p>";
  }
}

// Example usage:
// Assuming you have a table named 'products' with columns 'id', 'name', and 'description'
// and you want to search across 'name' and 'description'
$table_name = 'products';
$search_columns = ['name', 'description'];
performSearch("laptop", $table_name, $search_columns);
?>


<?php

// Example PHP code for a simple search bar

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (very important to prevent XSS attacks)
  $searchTerm = htmlspecialchars(trim($searchTerm));

  // Perform your search logic here (e.g., database query, array search, etc.)
  //  For this example, we'll just display the search term
  echo "<p>You searched for: " . $searchTerm . "</p>";

  // Optional:  Redirect to a results page or display results
  // echo "<a href='results.php?search=" . $searchTerm . "'>View Results</a>";

}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
</head>
<body>

  <h1>Simple Search</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px;
      margin: 20px auto;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    button {
      padding: 10px 15px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div id="searchResults">
    <!-- Results will be displayed here -->
  </div>


  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      var searchTermEncoded = encodeURIComponent(searchTerm); //Crucial for URL safety

      // You would typically make an AJAX request here to your server
      // to send the searchTerm to your server for searching.

      // For demonstration purposes, we'll just display a basic message.
      // In a real application, you would replace this with your server-side logic.

      var resultsDiv = document.getElementById("searchResults");
      resultsDiv.innerHTML = "<p>Searching for: " + searchTermEncoded + "</p>";
      //  For a real app, you would replace the above with a JSON response
      //  and do more than just display a message.  Also, you would handle
      //  the response gracefully (e.g., show empty results if no results are found).

      // Example JSON response (replace with your actual data):
      /*
      var response = {
          "results": [
              {"title": "Example 1", "description": "This is an example."},
              {"title": "Example 2", "description": "Another example."},
              {"title": "Example 3", "description": "Yet another example."}
          ]
      };

      //  Parse the JSON and display the results
      //  (You'll need to use JSON.parse() here).

      */
    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established in a separate file or session
// For demonstration purposes, using a simplified simulation:

$searchTerm = $_GET['searchTerm']; // Get the search term from the URL (GET method)

// Simulate a database search (replace with your actual database query)
$results = [
    ['title' => 'Example 1', 'description' => 'This is an example.'],
    ['title' => 'Example 2', 'description' => 'Another example.'],
    ['title' => 'Example 3', 'description' => 'Yet another example.'],
];

// Filter results based on the search term
$filteredResults = [];
foreach ($results as $result) {
    $title = strtolower($result['title']); // Convert to lowercase for case-insensitive search
    $description = strtolower($result['description']);
    if (strpos(strtolower($title), strtolower($searchTerm)) !== false || strpos(strtolower($description), strtolower($searchTerm)) !== false) {
        $filteredResults[] = $result;
    }
}


// Convert the results to JSON
$json_results = json_encode($filteredResults);

// Set the content type to JSON
header('Content-Type: application/json');

// Send the JSON response
echo $json_results;
?>


<?php

// Assuming you have a database connection established (e.g., $db is your connection object)

// Get the search term from the input field
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security and preventing SQL injection)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Escape the search term for use in the database query
$searchTerm = $db->real_escape_string($searchTerm); // Assuming $db is your database connection object


// You can add more filtering/validation here if needed,
// such as allowing only alphanumeric characters or limiting the search
// to specific fields.

// SQL Query (Example - Adjust based on your database schema)
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = $db->query($sql);

// Check if the query was successful
if ($result) {
  // Display the search results
  echo "<form method='get' action='your_script.php'>
         <input type='text' name='search' value='" . $searchTerm . "' placeholder='Search...'>
         <button type='submit'>Search</button>
       </form>";
  echo "<br>";
  echo "<h2>Search Results:</h2>";

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<div>";
      // Display the data from each row
      echo "<p>" . htmlspecialchars($row['your_column_name']) . "</p>";  // Escape the output as well
      echo "</div>";
    }
  } else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
  }

} else {
  // Handle query errors
  echo "<p>Error executing query: " . $db->error . "</p>";
}

?>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// and a table named 'products' with a column named 'name'

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (important for security)
  $searchTerm = htmlspecialchars($searchTerm);  // Escape HTML entities

  // Perform the search
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

  // Example using mysqli
  $conn = new mysqli("your_db_host", "your_db_user", "your_db_password", "your_db_name");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Output the search results
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Description</th></tr>"; // Example headers
    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row["id"] . "</td>";
      echo "<td>" . $row["name"] . "</td>";
      echo "<td>" . $row["description"] . "</td>"; // Adapt to your table schema
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  }

  $conn->close(); // Close the database connection
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Products</title>
</head>
<body>

  <h1>Search Products</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// ... (Database connection setup - same as before)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (still recommended for other reasons)
  $searchTerm = htmlspecialchars($searchTerm);

  // Use a prepared statement to prevent SQL injection
  $sql = "SELECT * FROM products WHERE name LIKE ?"; // Use a placeholder '?'

  // Example using mysqli
  $conn = new mysqli("your_db_host", "your_db_user", "your_db_password", "your_db_name");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $searchTerm); // "s" indicates a string parameter

  $result = $stmt->execute();

  if ($result) {
    $rows = $stmt->fetch_all(MYSQLI_ASSOC); // Fetch all results as an array
    if (!empty($rows)) {
      echo "<h2>Search Results for: " . $searchTerm . "</h2>";
      echo "<table border='1'>";
      echo "<tr><th>ID</th><th>Name</th><th>Description</th></tr>";
      foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "</tr>";
      }
      echo "</table>";
    } else {
      echo "<p>No results found for: " . $searchTerm . "</p>";
    }
  } else {
    echo "<p>Error executing query: " . $conn->error . "</p>";
  }

  $stmt->close();
  $conn->close();
}
?>


<!DOCTYPE html>
<html>
<head>
<title>Search Bar Example</title>
<style>
  .search-container {
    margin-bottom: 10px;
  }
  input[type="text"] {
    width: 300px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
  }
</style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search..." onkeyup="searchFunction()">
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function searchFunction() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsDiv = document.getElementById("results");
      resultsDiv.innerHTML = ""; // Clear previous results

      // Example data (replace with your actual data source)
      var data = [
        { name: "Apple", description: "A delicious fruit." },
        { name: "Banana", description: "A yellow fruit." },
        { name: "Orange", description: "A citrus fruit." },
        { name: "Grape", description: "Small and juicy." },
        { name: "Mango", description: "Tropical fruit." }
      ];


      // Loop through the data and display matching results
      for (var i = 0; i < data.length; i++) {
        if (data[i].name.toLowerCase().includes(searchTerm) || data[i].description.toLowerCase().includes(searchTerm)) {
          var listItem = document.createElement("p");
          listItem.textContent = data[i].name + " - " + data[i].description;
          resultsDiv.appendChild(listItem);
        }
      }

      // If no results are found, display a message
      if (resultsDiv.innerHTML === "") {
        resultsDiv.innerHTML = "<p>No results found.</p>";
      }
    }
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px;
      margin: 20px auto;
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
    <input type="text" id="searchInput" placeholder="Search...">
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    const searchInput = document.getElementById('searchInput');
    const resultsDiv = document.getElementById('results');

    searchInput.addEventListener('keyup', function() {
      const searchTerm = searchTerm.trim(); // Trim whitespace
      let resultsHTML = '';

      // Simulate a data source (replace with your actual data source)
      const data = [
        { title: 'Apple iPhone 14', description: 'The latest iPhone...' },
        { title: 'Samsung Galaxy S23', description: 'A powerful Android phone...' },
        { title: 'Sony WH-1000XM5 Headphones', description: 'Noise-canceling headphones...' },
        { title: 'Google Pixel 7 Pro', description: 'Google\'s flagship phone...' },
        { title: 'Amazon Echo Dot (5th Gen)', description: 'A smart speaker...' }
      ];

      if (searchTerm) {
        data.forEach(item => {
          const title = item.title.toLowerCase();
          const description = item.description.toLowerCase();

          if (title.includes(searchTerm.toLowerCase()) || description.includes(searchTerm.toLowerCase())) {
            resultsHTML += `
              <div class="search-result">
                <h3>${item.title}</h3>
                <p>${item.description}</p>
              </div>
            `;
          }
        });
      }

      if (resultsHTML) {
        resultsDiv.innerHTML = resultsHTML;
      } else {
        resultsDiv.innerHTML = '<p>No results found.</p>';
      }
    });
  </script>

</body>
</html>


<?php
// Example PHP code to return search results
// Replace this with your actual database query

$searchTerm = $_GET['search']; // Get the search term from the URL
if (!$searchTerm) {
  $results = [];
} else {
  $results = [
    ['title' => 'Apple iPhone 14', 'description' => 'The latest iPhone...'],
    ['title' => 'Samsung Galaxy S23', 'description' => 'A powerful Android phone...'],
    ['title' => 'Sony WH-1000XM5 Headphones', 'description' => 'Noise-canceling headphones...'],
    ['title' => 'Google Pixel 7 Pro', 'description' => 'Google\'s flagship phone...'],
    ['title' => 'Amazon Echo Dot (5th Gen)', 'description' => 'A smart speaker...']
  ];
}

// Output the results as JSON
header('Content-Type: application/json');
echo json_encode($results);
?>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// For demonstration purposes, we'll use a placeholder array instead of a real database.
$data = [
    ['id' => 1, 'name' => 'Apple', 'description' => 'A sweet fruit'],
    ['id' => 2, 'name' => 'Banana', 'description' => 'A yellow fruit'],
    ['id' => 3, 'name' => 'Orange', 'description' => 'A citrus fruit'],
    ['id' => 4, 'name' => 'Grape', 'description' => 'Small, juicy fruit'],
];

// Get the search term from the GET request (e.g., from a URL like ?search=apple)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection (IMPORTANT!)
$searchTerm = htmlspecialchars(trim($searchTerm)); 

// Handle empty search term
if (empty($searchTerm)) {
    $searchResults = $data; // Show all results if nothing is searched
} else {
    $searchResults = [];
    foreach ($data as $item) {
        // Case-insensitive search
        if (stripos($item['name'], $searchTerm) !== false ||
            stripos($item['description'], $searchTerm) !== false) {
            $searchResults[] = $item;
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
</head>
<body>

    <h1>Search for Fruits</h1>

    <form method="GET" action="">
        <input type="text" name="search" placeholder="Enter fruit name" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($searchResults)): ?>
        <h2>Search Results:</h2>
        <ul>
            <?php foreach ($searchResults as $result): ?>
                <li>
                    <strong>Name:</strong> <?php echo $result['name']; ?><br>
                    <strong>Description:</strong> <?php echo $result['description']; ?><br>
                    <strong>ID:</strong> <?php echo $result['id']; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No results found for <?php echo htmlspecialchars($searchTerm); ?></p>
    <?php endif; ?>

</body>
</html>


// Using mysqli
$conn = mysqli_connect("localhost", "your_username", "your_password", "your_database");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

$searchTerm = htmlspecialchars(trim($_GET['search']));

$sql = "SELECT id, name, description FROM fruits WHERE name LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";

$result = mysqli_query($conn, $sql);

// Process the results
// ... (Display the results as in the HTML example)

mysqli_close($conn);


<?php

// Example HTML for the search bar
?>

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
</head>
<body>

  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
    <input type="text" name="search_term" placeholder="Search..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
    <input type="submit" value="Search">
  </form>

  <?php
  // Example: Processing the search term (replace with your actual logic)
  if (isset($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']);
    echo "<p>You searched for: " . $search_term . "</p>";

    // Example:  Simple string matching (replace with your actual search logic)
    if (strpos($search_term, 'example') !== false) {
      echo "<p>Found a match for 'example'!</p>";
    } else {
      echo "<p>No results found.</p>";
    }
  }
  ?>

</body>
</html>


<?php

// Assuming you're working in a web environment (e.g., using HTML to display the form)

// 1. HTML Form for the Search Bar

?>
<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
</head>
<body>

  <h1>Search</h1>

  <form action="search_results.php" method="GET">
    <input type="text" name="search_term" placeholder="Enter search term" style="width: 300px;">
    <button type="submit">Search</button>
  </form>

</body>
</html>

<?php

// 2. PHP Code to Process the Search Query (search_results.php - This is just an example)

// Check if the search term is set in the GET request
if (isset($_GET['search_term'])) {

  // Sanitize the input to prevent SQL injection and other security vulnerabilities
  $search_term = htmlspecialchars(trim($_GET['search_term'])); 

  //  You can now use the $search_term in your search logic.
  //  Example:  Search through a database, array, or other data source.

  //  IMPORTANT:  Replace this with your actual search logic!
  $results = searchDatabase($search_term);  //  Example function - replace with yours

  // Display the results.  This is just a placeholder.
  echo "<h2>Search Results for: " . $search_term . "</h2>";
  if (empty($results)) {
    echo "<p>No results found.</p>";
  } else {
    echo "<ul>";
    foreach ($results as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  }
} else {
  echo "<p>No search term provided.</p>";
}


// Example function to simulate a database search (Replace this!)
function searchDatabase($term) {
    // In a real application, you would connect to your database here.
    // This is just a placeholder for demonstration.
    $data = [
        "Apple",
        "Banana",
        "Orange",
        "Grape",
        "Strawberry",
        "Pineapple"
    ];
    $results = [];
    foreach ($data as $item) {
      if (stripos($item, $term) !== false) { // Case-insensitive search
        $results[] = $item;
      }
    }
    return $results;
}

?>


<?php

// Assuming you have a form to capture the search input
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search</h1>

  <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term" value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
    <input type="submit" value="Search">
  </form>

  <?php
  // Example of processing the search term and displaying results
  if (isset($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']);

    // Replace this with your actual search logic
    $results = searchDatabase($search_term);

    if (!empty($results)) {
      echo "<h2>Search Results for: " . $search_term . "</h2>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . $result . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for: " . $search_term . "</p>";
    }
  }
  ?>

  <script>
    // Optional: Add a little styling or behavior to the search bar
    // Example:  Hide the submit button when the input is focused
    document.getElementById('search_term').addEventListener('focus', function() {
      document.getElementById('search_submit').style.display = 'none';
    });

    document.getElementById('search_term').addEventListener('blur', function() {
      document.getElementById('search_submit').style.display = 'block';
    });
  </script>

</body>
</html>


function searchDatabase($search_term) {
    // Sanitize the search term (add more sanitization as needed)
    $search_term = mysqli_real_escape_string($GLOBALS['conn'], $search_term);

    // Construct the SQL query (using prepared statement)
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

    // Execute the query
    $result = mysqli_query($GLOBALS['conn'], $sql);

    if ($result) {
        $results = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row['name']; // Adjust this based on your table structure
        }
        mysqli_free_result($result);
        return $results;
    } else {
        return false; // Handle the error
    }
}


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-container {
      margin-bottom: 10px;
    }

    input[type="text"] {
      width: 300px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box; /* Ensures padding and border are included in width */
    }

    button {
      padding: 8px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function search() {
      const searchTerm = document.getElementById("searchInput").value;
      const resultsDiv = document.getElementById("results");

      // **Replace this with your actual search logic**
      // This is a placeholder for demonstration purposes.
      const data = [
        { title: "Apple Pie Recipe", description: "A classic apple pie recipe." },
        { title: "Chocolate Cake", description: "Delicious chocolate cake recipe." },
        { title: "PHP Tutorial", description: "Learn PHP programming." },
        { title: "Search Bar Example", description: "This is a demonstration of a search bar." }
      ];

      if (searchTerm.trim() === "") {
        resultsDiv.innerHTML = "Please enter a search term.";
        return;
      }

      const resultsHtml = data
        .filter(item => item.title.toLowerCase().includes(searchTerm.toLowerCase()) || item.description.toLowerCase().includes(searchTerm.toLowerCase()))
        .map(item => `
          <div>
            <h3>${item.title}</h3>
            <p>${item.description}</p>
          </div>
        `)
        .join('');

      resultsDiv.innerHTML = resultsHtml;
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's assume you've connected to a database named 'mydatabase'
// and have a table named 'products' with a column 'name'

// Example database connection (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "mydatabase";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Connection error: " . $e->getMessage());
}


// Get the search term from the search bar (e.g., from a POST request)
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';


// Sanitize the search term to prevent SQL injection
$searchTerm = $conn->real_escape_string($searchTerm);


// Build the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'"; // Use LIKE for partial matches


// Execute the query
$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Products</title>
</head>
<body>

    <h1>Search Products</h1>

    <form method="post" action="">
        <input type="text" name="searchTerm" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>"; // Assuming you have a 'description' column
        }
        echo "</ul>";
    } else {
        echo "<p>No products found.</p>";
    }
    ?>

</body>
</html>


<?php

// Assuming you have a form to collect the search query
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search</h1>

  <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term" value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
    <button type="submit">Search</button>
  </form>

  <?php

  // Handle the search query
  if (isset($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']);

    // Example: Search through a simple array of products
    $products = [
      'Laptop' => 'High-performance laptop',
      'Mouse' => 'Wireless mouse',
      'Keyboard' => 'Mechanical keyboard',
      'Monitor' => '27-inch monitor',
      'Headphones' => 'Noise-canceling headphones'
    ];

    $results = [];

    foreach ($products as $product => $description) {
      if (strpos($description, $search_term) !== false) { // Case-insensitive search
        $results[$product] = $description;
      }
    }

    if (!empty($results)) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($results as $product => $description) {
        echo "<li>" . $product . ": " . $description . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for '" . $search_term . "'.</p>";
    }
  }

  ?>

</body>
</html>


<?php

// This code creates a basic search bar with a simple text field and a button.
// It then searches the text in the field for the entered term.
// This is a basic example; you'll likely want to customize it for your specific needs
// (e.g., database queries, more complex search logic, HTML styling, etc.).

// HTML form for the search bar
?>

<form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <label for="search">Search:</label>
  <input type="text" id="search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
  <button type="submit">Search</button>
</form>

<?php

// Process the search form submission
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  // Get the search term from the form
  $searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

  // Perform the search (replace with your actual search logic)
  if ($searchTerm != "") {
    // Example: Search through a simple array
    $items = ["apple", "banana", "orange", "grape", "kiwi"];
    $results = [];

    foreach ($items as $item) {
      if (stripos($item, $searchTerm) !== false) { // Use stripos for case-insensitive searching
        $results[] = $item;
      }
    }

    // Display the results
    if (count($results) > 0) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . $result . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for '" . $searchTerm . "'.</p>";
    }
  }
?>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
    }
    input[type="text"] {
      padding: 8px;
      width: 300px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box; /* Ensures padding doesn't add to width */
    }
    button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .results {
      margin-top: 20px;
      list-style: none;
      padding: 0;
    }
    .results li {
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #eee;
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <h1>Simple Search</h1>
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div id="results" class="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsDiv = document.getElementById("results");

      // Example data - replace this with your actual data source
      var data = [
        { title: "Apple iPhone 13", description: "Latest iPhone model." },
        { title: "Samsung Galaxy S22", description: "Premium Android phone." },
        { title: "Google Pixel 6", description: "Best camera phone." },
        { title: "Laptop - Dell XPS 13", description: "Powerful laptop for work." },
        { title: "Keyboard - Logitech MX Keys", description: "Ergonomic wireless keyboard." }
      ];

      // Clear previous results
      resultsDiv.innerHTML = "";

      // Filter data based on search term
      var filteredData = data.filter(function(item) {
        return item.title.toLowerCase().includes(searchTerm) || item.description.toLowerCase().includes(searchTerm);
      });

      // Display results
      if (filteredData.length > 0) {
        filteredData.forEach(function(item) {
          var listItem = document.createElement("li");
          listItem.innerHTML = "<b>" + item.title + "</b><br>" + item.description;
          resultsDiv.appendChild(listItem);
        });
      } else {
        var noResults = document.createElement("p");
        noResults.innerHTML = "No results found.";
        resultsDiv.appendChild(noResults);
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db is your PDO connection)
// and you're working with a database table named 'products' (adjust as needed)

// 1. Get the Search Term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// 2. Sanitize the Search Term (Crucial for Security)
$searchTerm = htmlspecialchars($searchTerm); // Prevents XSS attacks
//  Consider adding further sanitization if needed, depending on your database and input handling.

// 3. Build the SQL Query
$tableName = 'products'; // Replace with your table name
$columnName = 'name';   // Replace with the column you want to search in
$sql = "SELECT * FROM $tableName WHERE $columnName LIKE '%$searchTerm%'";

// 4. Execute the Query
try {
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch results as an associative array

  // 5. Display the Results (Example - Adapt to your needs)
  if ($results) {
    echo "<form action='search.php' method='get'>";
    echo "<input type='text' name='search' value='$searchTerm'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";

    echo "<h2>Search Results:</h2>";
    echo "<table border='1'>";
    echo "<thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th></tr></thead>";
    echo "<tbody>";
    foreach ($results as $row) {
      echo "<tr>";
      echo "<td>" . $row['id'] . "</td>";
      echo "<td>" . $row['name'] . "</td>";
      echo "<td>" . $row['description'] . "</td>";
      echo "<td>" . $row['price'] . "</td>";
      echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
  } else {
    echo "<p>No results found for '$searchTerm'.</p>";
  }

} catch (PDOException $e) {
  echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}

?>


<?php

// Assuming you have a database connection established (e.g., using mysqli)
// $db = new mysqli("localhost", "username", "password", "database_name");
// if (!$db->connect_error) {
//   // Handle connection error
// }

// Function to handle search queries
function searchData($search_term, $data) {
  $results = [];

  foreach ($data as $item) {
    // Normalize the search term to lowercase for case-insensitive search
    $search_term = strtolower($search_term);

    // Check if the search term exists in any of the item's fields.
    // Modify this section based on the fields you want to search in.
    if (strpos(strtolower($item['title']), $search_term) !== false ||  // Check title
        strpos(strtolower($item['description']), $search_term) !== false || // Check description
        strpos(strtolower($item['name']), $search_term) !== false) { // Check name
      $results[] = $item;
    }
  }

  return $results;
}


// Example Data (replace with your actual data from the database)
$items = [
  ['id' => 1, 'title' => 'Awesome Product', 'description' => 'A great product to buy.', 'name' => 'Product A'],
  ['id' => 2, 'title' => 'Another Product', 'description' => 'A product with a unique feature.', 'name' => 'Product B'],
  ['id' => 3, 'title' => 'Cool Gadget', 'description' => 'A very cool gadget!', 'name' => 'Product C'],
];

// Get the search term from the input field (assuming you have an input field with id="search")
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Perform the search
$search_results = searchData($search_term, $items);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
</head>
<body>

  <h1>Search Results</h1>

  <form method="get" action="">
    <input type="text" id="search" name="search" placeholder="Enter search term" value="<?php echo htmlspecialchars($search_term); ?>">
    <input type="submit" value="Search">
  </form>

  <?php if (empty($search_results)) {
    echo "<p>No results found.</p>";
  } else {
    echo "<ul>";
    foreach ($search_results as $result) {
      echo "<li>ID: " . $result['id'] . ", " . $result['title'] . "</li>";
    }
    echo "</ul>";
  } ?>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      margin: 20px;
    }

    input[type="text"] {
      width: 300px;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Include padding and border in the element's total width and height */
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <h1>Search</h1>
    <input type="text" id="searchInput" placeholder="Enter search term">
    <button onclick="performSearch()">Search</button>
  </div>


  <script>
    function performSearch() {
      var searchTerm = document.getElementById("searchInput").value;

      //  You'll replace this with your actual search logic
      //  This is just a placeholder to demonstrate the functionality.

      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      // Example:  Echo the search term back to the page
      document.getElementById("results").innerHTML = "<p>Searching for: " + searchTerm + "</p>";

      //  Implement your actual search logic here:
      //  - Fetch data from a database or other source
      //  - Filter the data based on the searchTerm
      //  - Display the results on the page.


    }
  </script>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

</body>
</html>


$searchTerm = $_GET['search'];  // Example: getting search term from a URL parameter

$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :searchTerm OR description LIKE :searchTerm");
$searchTerm = "%" . $searchTerm . "%"; // Add wildcards for partial matches

$stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Display $results


<?php

// Sample data (replace this with your actual data source)
$products = [
    "Red T-Shirt",
    "Blue Jeans",
    "Black Leather Jacket",
    "Gray Wool Sweater",
    "Black Boots",
    "Red Hat",
    "Blue Scarf",
    "Black Gloves",
];

// Function to perform the search
function searchProducts($searchTerm, $products) {
    $results = [];

    foreach ($products as $product) {
        if (stripos($product, $searchTerm) !== false) { // Case-insensitive search
            $results[] = $product;
        }
    }

    return $results;
}

// Get the search term from the form submission (or directly from URL if needed)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Perform the search
$searchResults = searchProducts($searchTerm, $products);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Search</title>
</head>
<body>

    <h1>Product Search</h1>

    <form method="GET" action="">
        <input type="text" name="search" placeholder="Enter search term" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Search</button>
    </form>

    <?php if (empty($searchResults)): ?>
        <p>No products found matching your search.</p>
    <?php else: ?>
        <h2>Search Results:</h2>
        <ul>
            <?php foreach ($searchResults as $product): ?>
                <li><?php echo htmlspecialchars($product); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)
// and a table named 'products' with a 'name' column.

// Get the search term from the form.
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security).
$searchTerm = htmlspecialchars($searchTerm);

// Prepare the SQL query.  This is crucial for preventing SQL injection.
// Use prepared statements with placeholders.  This is the safest method.
$query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query.
// $result = mysqli_query($conn, $query); // Assuming mysqli

// Example using PDO (more modern and often recommended):
try {
  $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE :search");
  $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  // Handle database errors.  Display an error message to the user.
  echo "Error: " . $e->getMessage();
  //  Or, for a more user-friendly experience:
  //  echo "An error occurred while searching: " . $e->getMessage();
  exit(); // Stop execution if a critical error occurs
}


// Output the search form
echo '<form method="GET" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">
      <input type="text" name="search" placeholder="Search products...">
      <button type="submit">Search</button>
    </form>';

// Display the search results.
if (count($result) > 0) {
  echo '<h2>Search Results:</h2>';
  echo '<ul>';
  foreach ($result as $row) {
    echo '<li>' . $row['name'] . ' - ' . $row['description'] . '</li>'; // Assuming 'description' column
  }
  echo '</ul>';
} else {
  echo '<p>No products found matching your search.</p>';
}

?>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's use a placeholder for the database connection

// Example:
// $db = mysqli_connect("localhost", "username", "password", "database_name");
// if (!$db) {
//     die("Connection failed: " . mysqli_connect_error());
// }


// **Basic Search Function**
function searchDatabase($search_term, $table_name, $column_to_search = 'name') {
  //  Replace this with your actual database connection and query
  //  This is a simplified example using a hardcoded array of data.
  //  You should replace this with your database query.

  $data = [
    ['id' => 1, 'name' => 'Apple', 'description' => 'A red fruit'],
    ['id' => 2, 'name' => 'Banana', 'description' => 'A yellow fruit'],
    ['id' => 3, 'name' => 'Orange', 'description' => 'A citrus fruit'],
    ['id' => 4, 'name' => 'Grape', 'description' => 'Small, sweet fruit']
  ];


  $results = [];

  foreach ($data as $row) {
    // Perform case-insensitive search
    if (stripos($row[$column_to_search], $search_term) !== false) {
      $results[] = $row;
    }
  }

  return $results;
}


// **Search Bar HTML Form**
?>

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search</title>
</head>
<body>

  <h1>Search</h1>

  <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php
  // **Process the Search Form**

  if (isset($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']);  // Sanitize input
    $results = searchDatabase($search_term, 'name');  // Call your search function

    if (!empty($results)) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . htmlspecialchars($result['name']) . " - " . htmlspecialchars($result['description']) . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found.</p>";
    }
  }
  ?>

</body>
</html>


<?php

// Assuming you have a data source (e.g., an array, a database query result)
// called $data
//  $data = [
//      "apple", "banana", "orange", "grape", "watermelon"
//  ]; 

// Example data (replace with your actual data)
$data = [
    "apple", "banana", "orange", "grape", "watermelon",
    "pineapple", "kiwi", "mango", "pear"
];

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security - prevent XSS)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Case-insensitive search
$searchTerm = strtolower($searchTerm); // Convert to lowercase for case-insensitive matching

// Search the data
$results = [];
foreach ($data as $item) {
    $itemLower = strtolower($item);
    if (strpos($itemLower, $searchTerm) !== false) {
        $results[] = $item; // Return the original item (case-preserved)
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>

    <h1>Search</h1>

    <form method="GET" action="">
        <input type="text" name="search" value="<?php echo $searchTerm; ?>">
        <button type="submit">Search</button>
    </form>

    <?php if (empty($results)) {
        echo "<p>No results found for '" . $searchTerm . "'</p>";
    } else {
        echo "<ul>";
        foreach ($results as $result) {
            echo "<li>" . $result . "</li>";
        }
        echo "</ul>";
    } ?>

</body>
</html>


<?php

// Assuming you have a form with an input field named 'search_term'
// and you want to search through a data source (e.g., a database, an array, etc.).
// This example demonstrates searching through a simple array.

// **1. Get the Search Term from the Form**

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $search_term = htmlspecialchars($_POST["search_term"]); // Sanitize input

  // **2. Define Your Data Source (Example: Array)**
  $data = [
    "apple", "banana", "cherry", "date", "elderberry",
    "fig", "grape", "honeydew", "kiwi", "lemon"
  ];

  // **3. Perform the Search**

  $results = [];
  foreach ($data as $item) {
    // Convert both the item and the search term to lowercase for case-insensitive search
    if (stristr($item, $search_term)) { // stristr is case-insensitive
      $results[] = $item;
    }
  }

  // **4. Display the Results**
  echo "<form method='post' action=''>"; // Simple form to allow refreshing the search
  echo "Search: <input type='text' name='search_term'>";
  echo "<input type='submit' value='Search'>";
  echo "</form>";

  if (empty($results)) {
    echo "<p>No results found for '" . htmlspecialchars($search_term) . "'</p>";
  } else {
    echo "<ul>";
    foreach ($results as $result) {
      echo "<li>" . htmlspecialchars($result) . "</li>";
    }
    echo "</ul>";
  }
} else {
  // If the form hasn't been submitted, display a basic form
  echo "<form method='post' action=''>";
  echo "Search: <input type='text' name='search_term'>";
  echo "<input type='submit' value='Search'>";
  echo "</form>";
}

?>


<?php

// Example: Simple Search Bar in PHP

// Check if the search term is submitted
if (isset($_GET['search']) && isset($_GET['submit'])) {
  // Get the search term from the GET variable 'search'
  $searchTerm = $_GET['search'];

  // Sanitize the search term (Important for security!)
  $searchTerm = htmlspecialchars(trim($searchTerm));

  // You can add your logic here to search through data (e.g., a database, array, etc.)
  // For this example, we'll just display the search term.

  // Example:  Search through an array of products
  $products = [
    'Laptop - 15 inch' => 'https://example.com/laptop1',
    'Smartphone - Latest Model' => 'https://example.com/smartphone',
    'Wireless Mouse' => 'https://example.com/mouse',
    'Gaming Keyboard' => 'https://example.com/keyboard',
  ];

  $searchResults = [];
  foreach ($products as $key => $value) {
    if (strpos($key, $searchTerm) !== false || strpos($value, $searchTerm) !== false) {
      $searchResults[$key] = $value;
    }
  }

  $resultsHtml = '';
  if (!empty($searchResults)) {
    $resultsHtml = '<ul>';
    foreach ($searchResults as $key => $value) {
      $resultsHtml .= '<li><a href="' . $value . '">' . $key . '</a></li>';
    }
    $resultsHtml .= '</ul>';
  }

  // Display the search bar and results
  echo '<form method="get" action="">
          <input type="text" name="search" placeholder="Search..." value="' . $searchTerm . '">
          <input type="submit" name="submit" value="Search">
        </form>
        ' . $resultsHtml;


} else {
  // If no search term is submitted, display the search bar
  echo '<form method="get" action="">
          <input type="text" name="search" placeholder="Search..." value="">
          <input type="submit" name="submit" value="Search">
        </form>';
}

?>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-container {
      margin-bottom: 10px;
    }

    #search-input {
      width: 300px;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    #search-button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }

    #search-results {
      margin-top: 20px;
      list-style: none;
      padding: 0;
    }

    #search-results li {
      padding: 10px;
      border-bottom: 1px solid #eee;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <ul id="search-results"></ul>

  <script>
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const searchResults = document.getElementById('search-results');

    searchButton.addEventListener('click', function() {
      const searchTerm = searchInput.value.trim();

      if (searchTerm) {
        // Simulate searching (replace with your actual search logic)
        const results = simulateSearch(searchTerm);

        searchResults.innerHTML = ''; // Clear previous results

        if (results.length > 0) {
          results.forEach(result => {
            const listItem = document.createElement('li');
            listItem.textContent = result;
            searchResults.appendChild(listItem);
          });
        } else {
          searchResults.innerHTML = '<p>No results found.</p>';
        }
      }
    });

    // Example simulateSearch function (replace with your actual search logic)
    function simulateSearch(searchTerm) {
      // In a real application, you'd query a database or search through an array.
      const allData = ['Apple', 'Banana', 'Orange', 'Grape', 'Pineapple', 'Strawberry'];
      const filteredData = allData.filter(item => item.toLowerCase().includes(searchTerm.toLowerCase()));
      return filteredData;
    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established (e.g., $db)

// ... (HTML & CSS from above)

// JavaScript
const searchInput = document.getElementById('search-input');
const searchButton = document.getElementById('search-button');
const searchResults = document.getElementById('search-results');

searchButton.addEventListener('click', function() {
  const searchTerm = searchInput.value.trim();

  if (searchTerm) {
    // Assuming a 'products' table with a 'name' column
    $sql = "SELECT name FROM products WHERE name LIKE '%" . $db->real_escape_string($searchTerm) . "%'";
    $result = $db->query($sql);

    searchResults.innerHTML = '';

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        const listItem = document.createElement('li');
        listItem.textContent = $row['name'];
        searchResults.appendChild(listItem);
      }
    } else {
      searchResults.innerHTML = '<p>No results found.</p>';
    }
  }
});
?>


<?php

// Assuming you have a database connection established (e.g., $conn)
// This is a basic example, you'll need to adapt it to your database structure and query.

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security - prevents SQL injection)
$searchTerm = htmlspecialchars($searchTerm);

// Check if the search term is empty
if (empty($searchTerm)) {
  // Display a message or show results for all items if you prefer
  echo "Please enter a search term.";
  // Or, if you want to display all items without filtering
  // $results = get_all_items(); // Replace with your function
  // echo $results;
} else {
  // Build the SQL query (adapt this to your database structure)
  $sql = "SELECT * FROM items WHERE name LIKE '%" . $searchTerm . "%'"; 

  // Execute the query
  $result = mysqli_query($conn, $sql);  // Replace $conn with your actual database connection

  if ($result) {
    echo "<form method='get' action='search_results.php'>
          <input type='text' name='search' value='" . $searchTerm . "'><button type='submit'>Search</button>
          </form>";

    echo "<h2>Search Results for: " . $searchTerm . "</h2>";

    // Display the search results
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<p>" . $row['name'] . " - " . $row['description'] . "</p>"; // Adapt to your column names
    }

    // Close the database connection (important!)
    mysqli_free_result($result);
    mysqli_close($conn);
  } else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  }
}
?>


<?php

// Define the search term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars($searchTerm);

// Handle the search action -  This is where the searching logic would go.
// For now, we'll just display the search term for demonstration.

?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search:</h1>

  <form method="GET">
    <input type="text" name="search" value="<?php echo $searchTerm; ?>" placeholder="Enter search term...">
    <button type="submit">Search</button>
  </form>

  <?php
    // Example of how to search in a simple array
    $items = array(
      "apple", "banana", "cherry", "date", "elderberry", "fig"
    );

    if ($searchTerm) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($items as $item) {
        $lowerCaseItem = strtolower($item);
        $lowerCaseSearchTerm = strtolower($searchTerm);
        if (strpos($lowerCaseItem, $lowerCaseSearchTerm) !== false) {
          echo "<li>" . $item . "</li>";
        }
      }
      echo "</ul>";
    }

  ?>

</body>
</html>


<?php
// Assuming you're using MySQLi
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ... (rest of your search code) ...

$conn->close(); // Close the connection when you're done
?>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search query from the form
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search query (important to prevent XSS)
$search_query = htmlspecialchars($search_query);

// Escape the search query for the database (to prevent SQL injection)
$search_query = $conn->real_escape_string($search_query);


// Build the SQL query
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $search_query . "%'";

// Execute the query
$result = $conn->query($sql);

?>

<form method="GET" action="">
  <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search_query); ?>" style="width:300px;">
  <button type="submit">Search</button>
</form>

<?php

if ($result->num_rows > 0) {
  echo "<ul>";
  while($row = $result->fetch_assoc()) {
    echo "<li>" . $row["your_column_name"] . "</li>"; // Replace with actual column name
  }
  echo "</ul>";
} else {
  echo "No results found.";
}

?>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's call it $conn

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (important to prevent SQL injection)
  $searchTerm = $conn->real_escape_string($searchTerm);

  // Perform the search (replace with your actual database query)
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'"; // Example for a 'name' column
  $result = $conn->query($sql);

  // Display the search results
  if ($result->num_rows > 0) {
    echo "<h3>Search Results:</h3>";
    echo "<form method='post' action=''>";
    echo "<input type='text' name='search_term' value='" . htmlspecialchars($searchTerm) . "' placeholder='Enter search term'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";

    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
      echo "<li>" . htmlspecialchars($row["name"]) . " - " . htmlspecialchars($row["description"]) . "</li>"; // Adjust based on your table columns
    }
    echo "</ul>";
  } else {
    echo "<p>No results found.</p>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search Products</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter product name">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    #search-container {
      width: 300px;
      margin: 20px auto;
      text-align: center;
    }

    #search-input {
      width: 80%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    #search-button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    #search-button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div id="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <?php
    // Example data (replace with your actual data source)
    $data = [
      ["name" => "Apple", "description" => "A red fruit"],
      ["name" => "Banana", "description" => "A yellow fruit"],
      ["name" => "Orange", "description" => "A citrus fruit"],
      ["name" => "Grape", "description" => "Small, juicy fruit"]
    ];

    // Handle the search button click
    if (isset($_GET['search_term']) && !empty($_GET['search_term'])) {
      $searchTerm = $_GET['search_term'];

      // Perform the search
      $results = [];
      foreach ($data as $item) {
        // Case-insensitive search
        $name = strtolower($item['name']);
        $description = strtolower($item['description']);
        $searchTermLower = strtolower($searchTerm);

        if (strpos($name, $searchTermLower) !== false || strpos($description, $searchTermLower) !== false) {
          $results[] = $item;
        }
      }

      // Display the search results
      if (!empty($results)) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        foreach ($results as $result) {
          echo "<li>Name: " . $result['name'] . ", Description: " . $result['description'] . "</li>";
        }
        echo "</ul>";
      } else {
        echo "<p>No results found for '" . $searchTerm . "'.</p>";
      }
    }
  ?>

</body>
</html>


<?php
// Assuming you have a database connection established in a separate file (e.g., $db)
// and that you've executed a query to fetch the data.

// Example:
// $data = $db->query("SELECT name, description FROM products WHERE name LIKE '%" . $searchTerm . "%'")->fetchAll(PDO::FETCH_ASSOC);

$searchTerm = isset($_GET['search_term']) ? $_GET['search_term'] : '';

// Perform the search (similar logic as before, but using the fetched data)
$results = [];
if (!empty($searchTerm)) {
    // Replace this with your database query to fetch the results
    $results = $db->query("SELECT name, description FROM products WHERE name LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'")->fetchAll(PDO::FETCH_ASSOC);
}

// Display the results
if (!empty($results)) {
    echo "<h2>Search Results:</h2>";
    echo "<ul>";
    foreach ($results as $result) {
        echo "<li>Name: " . $result['name'] . ", Description: " . $result['description'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
}
?>


<?php

// Assuming you're in a web environment (e.g., using a web server like Apache)

// 1. Get the Search Query from the Form
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $search_term = $_GET['search'];
} else {
  $search_term = ''; // Reset if nothing is entered
}

// 2. Sanitize the Search Term (Important for Security!)
$search_term = htmlspecialchars($search_term); // Prevents XSS attacks.  This is crucial.
// You can add more sanitization here, depending on where the data comes from.

// 3.  Search Logic (Replace this with your actual search implementation)
// This is a simple example.  You'll likely want to use a database or other data source.

$results = [];
if ($search_term != '') {
    // Replace this with your database query or data source search logic
    // This is just a placeholder that simulates a search based on the term.
    // Example:
    if (strpos($search_term, 'apple') !== false) {
      $results[] = 'Found apple!';
    }
    if (strpos($search_term, 'banana') !== false) {
      $results[] = 'Found banana!';
    }
}

// 4.  Display the Search Form and Results

echo '<form action="" method="get">'; // Empty action means it will automatically redirect
echo 'Search: <input type="text" name="search" value="' . htmlspecialchars($search_term) . '" />';
echo '<input type="submit" value="Search">';
echo '</form>';

if (!empty($results)) {
    echo '<h2>Search Results:</h2>';
    echo '<ul>';
    foreach ($results as $result) {
        echo '<li>' . $result . '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>No results found for "' . htmlspecialchars($search_term) . '"</p>';
}

?>


<?php
// Database credentials (replace with your actual values)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}

// Get the search term
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Construct the SQL query
$query = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

// Execute the query
$result = $conn->query($query);

// Display the search form and results
echo '<form action="" method="get">';
echo 'Search: <input type="text" name="search" value="' . htmlspecialchars($search_term) . '" />';
echo '<input type="submit" value="Search">';
echo '</form>';

if ($result->num_rows > 0) {
  echo '<h2>Search Results:</h2>';
  echo '<ul>';
  while ($row = $result->fetch_assoc()) {
    echo '<li>' . $row['name'] . ' - ' . $row['description'] . '</li>';
  }
  echo '</ul>';
} else {
  echo '<p>No products found for "' . htmlspecialchars($search_term) . '"</p>';
}

$conn->close(); // Close the database connection
?>


<?php

// Basic Search Bar Example

// Check if the search term is submitted
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $_GET['search'];

  // Perform the search (replace with your logic)
  $searchResults = searchDatabase($searchTerm); // Assuming you have a searchDatabase function
} else {
  $searchTerm = ""; // Reset the search term if not submitted
  $searchResults = []; // Reset search results
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
</head>
<body>

  <h1>Search</h1>

  <form method="GET" action="">
    <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php if (!empty($searchResults)) { ?>
    <h2>Search Results:</h2>
    <ul>
      <?php foreach ($searchResults as $result) {
        echo "<li>" . $result . "</li>";
      } ?>
    </ul>
  <?php } else { ?>
    <p>No results found for: <?php echo htmlspecialchars($searchTerm); ?></p>
  <?php } ?>

</body>
</html>


   function searchDatabase($searchTerm) {
     // Replace this with your actual database query logic
     // This is just a placeholder
     $results = [
       "Product A - This is awesome!",
       "Product B - Another great product.",
       "Product C - Not found",
     ];
     return $results;
   }
   
   * **Database Connection:**  You'll need to establish a connection to your database using your database driver (e.g., `mysqli`, `PDO`).
   * **SQL Query:** Construct a SQL query to search your database.  Use `LIKE` for partial matches (e.g., `WHERE name LIKE '%$searchTerm%'`).  **Important:**  *Always* use prepared statements (with `mysqli_stmt` or PDO's prepared statements) to prevent SQL injection attacks.  Don't directly concatenate user input into your SQL query string.
   * **Return Results:**  Return the results of your database query as an array of strings, or an array of objects if you're using objects.

**Example with Prepared Statements (MySQLi):**



<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px; /* Adjust as needed */
      margin: 20px auto;
      text-align: center;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Ensures padding and border are included in width */
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }

    .results {
      margin-top: 20px;
      list-style: none;
      padding: 0;
    }

    .result-item {
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #eee;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div class="results" id="searchResults">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsContainer = document.getElementById("searchResults");

      // Example data (replace with your actual data source)
      var data = [
        { title: "Apple iPhone 15", description: "The latest iPhone..." },
        { title: "Samsung Galaxy S23", description: "A great Android phone..." },
        { title: "Google Pixel 7", description: "Powerful camera and AI..." },
        { title: "OnePlus 11", description: "Fast charging and smooth performance..." },
        { title: "Amazon Echo Dot", description: "Voice control and smart home integration..." }
      ];

      // Clear previous results
      resultsContainer.innerHTML = "";

      // Filter data based on the search term
      var filteredData = data.filter(function(item) {
        return item.title.toLowerCase().includes(searchTerm) || item.description.toLowerCase().includes(searchTerm);
      });

      // Display the filtered results
      if (filteredData.length > 0) {
        filteredData.forEach(function(item) {
          var resultItem = document.createElement("div");
          resultItem.classList.add("result-item");
          resultItem.innerHTML = `<strong>${item.title}</strong><br>${item.description}`;
          resultsContainer.appendChild(resultItem);
        });
      } else {
        var noResultsMessage = document.createElement("p");
        noResultsMessage.textContent = "No results found.";
        resultsContainer.appendChild(noResultsMessage);
      }
    }
  </script>

</body>
</html>


<?php

// Basic Search Bar with Simple Text Matching

// Get the search term from the query string
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent XSS attacks
$search_term = htmlspecialchars($search_term);

// Example data to search through (replace with your actual data)
$products = [
    'Apple iPhone 13' => 'A great smartphone',
    'Samsung Galaxy S22' => 'Another excellent smartphone',
    'Google Pixel 6' => 'A powerful camera phone',
    'Laptop Dell XPS 13' => 'A lightweight and powerful laptop',
    'Gaming PC RTX 3070' => 'Ideal for gaming',
];


// Perform the search
$results = [];
foreach ($products as $key => $value) {
    // Case-insensitive search
    if (stripos($key, $search_term) !== false || stripos($value, $search_term) !== false) {
        $results[$key] = $value;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
</head>
<body>

    <h1>Search Products</h1>

    <form method="GET" action="">
        <input type="text" name="search" placeholder="Enter search term" value="<?php echo htmlspecialchars($search_term); ?>" />
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($results)): ?>
        <h2>Search Results:</h2>
        <ul>
            <?php foreach ($results as $key => $value): ?>
                <li><?php echo htmlspecialchars($key) ?> - <?php echo htmlspecialchars($value) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No results found for <?php echo htmlspecialchars($search_term); ?></p>
    <?php endif; ?>

</body>
</html>


<?php
// Assuming you have a database connection established as $conn

// Get the search term from the query string
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term
$search_term = htmlspecialchars($search_term);

// Construct the database query
$query = "SELECT product_name, description FROM products WHERE product_name LIKE '%" . $search_term . "%' OR description LIKE '%" . $search_term . "%'";

// Execute the query
$result = mysqli_query($conn, $query);

$results = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $results[$row['product_name']] = $row['description'];
    }
    mysqli_free_result($result); // Important: Free the result set
} else {
    // Handle database error
    echo "Error: " . mysqli_error($conn);
}

// ... (rest of the HTML code as above) ...
?>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
    }

    input[type="text"] {
      padding: 10px;
      border: 1px solid #ccc;
      width: 300px;
      font-size: 16px;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <h2>Search:</h2>
    <input type="text" id="searchInput" placeholder="Enter search term...">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      //  Replace this with your actual search logic.
      //  This is a placeholder for demonstration purposes.

      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      // Example:  Display the search term in an alert
      alert("You searched for: " + searchTerm);

      //  More sophisticated search logic would go here, 
      //  e.g., searching a database, a list, etc.
    }
  </script>

</body>
</html>


<?php

// Check if the search form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $searchTerm = isset($_POST["search_term"]) ? trim($_POST["search_term"]) : "";

  // Sanitize the search term - IMPORTANT for security
  $searchTerm = htmlspecialchars($searchTerm);

  // Perform your search logic here.  This is just a basic example.
  // You'll likely replace this with your actual database query or search algorithm.
  $searchResults = [];

  if ($searchTerm) {
    // Example:  Assume you have an array of data to search through
    $data = [
      "apple", "banana", "cherry", "date", "elderberry",
      "fig", "grape", "honeydew", "kiwi", "lemon"
    ];

    foreach ($data as $item) {
      if (strpos($item, $searchTerm) !== false) { // Case-sensitive search
        $searchResults[] = $item;
      }
    }
  }

  // Display the results
  if (empty($searchResults)) {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  } else {
    echo "<p>Search Results for: " . $searchTerm . "</p>";
    echo "<ul>";
    foreach ($searchResults as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli)
// For this example, we'll use a placeholder connection.  Replace with your actual connection.
$db = new mysqli("localhost", "username", "password", "database_name");

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize and prepare the search term for the database query
$searchTerm = $db->real_escape_string($searchTerm); // Escape for SQL injection

// Build the SQL query
$query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";
// Replace 'your_table_name' and 'your_column_name' with your actual table and column names.
// 'LIKE' with '%' allows for partial matches.


// Execute the query
$result = $db->query($query);

?>

<!DOCTYPE html>
<html>
<head>
<title>Search Bar</title>
</head>
<body>

<h2>Search</h2>

<form method="GET" action="">
  <input type="text" name="search" value="<?php echo $searchTerm; ?>">
  <button type="submit">Search</button>
</form>

<?php

if ($result) {
  if ($result->num_rows > 0) {
    echo "<br>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
      echo "<li>" . htmlspecialchars($row['your_column_name']) . "</li>"; //Escape for XSS
    }
    echo "</ul>";
  } else {
    echo "<p>No results found.</p>";
  }
} else {
  echo "<p>Error executing query.</p>";
}

$db->close(); // Close the database connection
?>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)
// This is a simplified example and you'll need to adapt it to your specific database and setup.

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize and validate the search term (important for security)
  $searchTerm = htmlspecialchars(trim($searchTerm)); // Escape HTML entities and remove whitespace

  // Example query (replace with your database query)
  $sql = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'";

  // Execute the query
  $result = mysqli_query($conn, $sql); // Replace $conn with your database connection variable

  // Display the results
  if (mysqli_num_rows($result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<li>" . $row['your_column'] . "</li>"; // Replace 'your_column' with the actual column name
    }
    echo "</ul>";
  } else {
    echo "No results found for '" . $searchTerm . "'.";
  }

}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
</head>
<body>

  <h1>Search</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// ... (Database connection setup as before) ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["search_term"];
  $searchTerm = htmlspecialchars($searchTerm);

  // Prepare the statement (this is the key for security)
  $stmt = mysqli_prepare($conn, "SELECT * FROM your_table WHERE your_column LIKE '%s%'");  // 's' indicates a string parameter

  if ($stmt) {
    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "s", $searchTerm);  // 's' means string

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Process the results (same as before)
    if ($result) {
      echo "<ul>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['your_column'] . "</li>";
      }
      echo "</ul>";
      mysqli_free_result($result); // Important to free the result set
      mysqli_stmt_close($stmt);
    } else {
      // Handle errors
      echo "Error executing query.";
    }

  } else {
    // Handle errors preparing the statement
    echo "Error preparing statement.";
  }

}
?>


<?php

// Assuming you have a database connection established
// Replace 'your_database_connection' with your actual database connection
// and adjust the query accordingly.

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
  // Check if the search term is provided
  if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']); // Sanitize input to prevent XSS
    
    // Build your database query based on the search term
    $query = "SELECT * FROM your_table WHERE column1 LIKE '%" . $searchTerm . "%' OR column2 LIKE '%" . $searchTerm . "%'"; 
    // Replace 'your_table' and 'column1', 'column2' with your actual table and column names.
    //  'column1', 'column2' are just examples.  Use the columns that make sense for your search.
    //  The '%' wildcard allows for partial matches.

    // Example:
    // $query = "SELECT * FROM products WHERE product_name LIKE '%" . $searchTerm . "%'";
    
    // You would typically execute the query here using your database connection.
    // Example:
    // $result = mysqli_query($your_database_connection, $query); // Assuming mysqli
    
    // ... (Your code to process the query results) ...
    
    // Display the search term for demonstration purposes:
    echo "<p>Searching for: " . $searchTerm . "</p>";
    
    // Example: Displaying the search results
    // while ($row = mysqli_fetch_assoc($result)) {
    //     echo "<p>" . $row['column1'] . "</p>";
    // }
    
    // After the search, you'll usually reset the search term in your session or other storage.
    unset($searchTerm);  // Optional: Clear the search term from the session.
    
  } else {
    // If no search term is provided, display a default message
    echo "<p>Please enter a search term.</p>";
  }
} else {
  // Handle other request methods (e.g., POST) if needed.  This is a basic GET example.
  echo "<p>Invalid request method.</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
</head>
<body>

    <h1>Search</h1>

    <form method="get" action="">
        <input type="text" name="search" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (Important for security - prevents SQL injection)
$searchTerm = htmlspecialchars(trim($searchTerm));


// --- Example 1: Simple Search Across a Table (e.g., 'products') ---
// This example searches all columns in a table named 'products'

// Check if the search term is empty
if (empty($searchTerm)) {
    // No search term, display all products or a default message
    echo "<p>Enter a search term.</p>";
    // Or, display all products
    // $sql = "SELECT * FROM products";
    // $result = mysqli_query($conn, $sql);
    // if ($result) {
    //     echo "<ul>";
    //     while ($row = mysqli_fetch_assoc($result)) {
    //         echo "<li>" . htmlspecialchars($row['name']) . "</li>"; // Adjust 'name' to the actual column
    //     }
    //     echo "</ul>";
    // } else {
    //     echo "Error querying the database.";
    // }
} else {
    // Perform the search
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";  // 'LIKE' is used for partial matches
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>" . htmlspecialchars($row['name']) . "</li>"; // Adjust 'name' to the actual column
        }
        echo "</ul>";
    } else {
        echo "<p>No products found matching your search.</p>";
    }
}

// --- Example 2:  Searching a Specific Column (e.g., 'name') ---
// This is generally better for performance, especially with large datasets.

// if (empty($searchTerm)) {
//     echo "<p>Enter a search term.</p>";
// } else {
//     $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
//     $result = mysqli_query($conn, $sql);

//     if ($result) {
//         echo "<ul>";
//         while ($row = mysqli_fetch_assoc($result)) {
//             echo "<li>" . htmlspecialchars($row['name']) . "</li>";
//         }
//         echo "</ul>";
//     } else {
//         echo "<p>No products found matching your search.</p>";
//     }
// }



?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
</head>
<body>

    <h1>Search Products</h1>

    <form method="get" action="">  <!-- Form action should be the same as the PHP script's file name (e.g., search_script.php) -->
        <input type="text" name="search" value="<?php echo $searchTerm; ?>">
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

</body>
</html>


<?php
// Assuming you have a database connection established in $conn

if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = htmlspecialchars($_GET['search']);

  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<p>Product: " . $row['name'] . ", Description: " . $row['description'] . "</p>";
    }
    mysqli_free_result($result); // Free the result set
  } else {
    echo "<p>Error executing query.</p>";
  }
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin-bottom: 10px;
    }

    input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px;
      box-sizing: border-box; /* Include padding and border in the element's total width and height */
    }

    button {
      padding: 8px 15px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div id="searchResults">
    <!-- Results will be displayed here -->
  </div>


  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var searchResultsDiv = document.getElementById("searchResults");
      searchResultsDiv.innerHTML = ""; // Clear previous results

      // Replace this with your actual data source (e.g., database query)
      var data = [
        "Apple",
        "Banana",
        "Orange",
        "Grape",
        "Pineapple",
        "Strawberry",
        "Cherry"
      ];

      for (var i = 0; i < data.length; i++) {
        var term = data[i].toLowerCase();
        if (term.indexOf(searchTerm) !== -1) {
          var resultItem = document.createElement("p");
          resultItem.textContent = data[i];
          searchResultsDiv.appendChild(resultItem);
        }
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established and a database table named 'products'
// with a column named 'name' for searching.  Replace these with your actual details.

// Example database connection (replace with your actual credentials)
// $db = new mysqli("localhost", "username", "password", "database_name");
// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (important to prevent SQL injection)
  $searchTerm = $db->real_escape_string($searchTerm);

  // Perform the search in the database
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
  $result = $db->query($sql);

  // Display the search results
  if ($result->num_rows > 0) {
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    echo "<table border='1'>";
    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row["name"] . "</td>";
      // Add other columns from the 'products' table here
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Products</title>
</head>
<body>

  <h1>Search Products</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term">
    <input type="submit" value="Search">
  </form>

</body>
</html>


<?php

// Define the search term (for demonstration purposes - ideally, this would come from a form)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent XSS vulnerabilities
$searchTerm = htmlspecialchars($searchTerm);

// Handle the search request
if ($searchTerm) {
    // Perform your search logic here. This is just a placeholder.
    // You would likely query a database or search through an array.
    $searchResults = ['apple', 'banana', 'orange', 'grape', 'watermelon'];

    // Filter the results based on the search term
    $filteredResults = array_filter($searchResults, function($item) use ($searchTerm) {
        return stripos($item, $searchTerm) !== false; // case-insensitive search
    });

    // Display the results
    echo '<h2>Search Results for: ' . $searchTerm . '</h2>';
    if (empty($filteredResults)) {
        echo '<p>No results found for: ' . $searchTerm . '</p>';
    } else {
        echo '<ul>';
        foreach ($filteredResults as $result) {
            echo '<li>' . $result . '</li>';
        }
        echo '</ul>';
    }

} else {
    // Display a form to enter the search term
    echo '<form action="" method="get">';
    echo '<input type="text" name="search" value="' . htmlspecialchars($searchTerm) . '" placeholder="Enter search term">';
    echo '<button type="submit">Search</button>';
    echo '</form>';
}
?>


<?php

// Assuming you have a database connection established
// (e.g., mysqli, PDO) - Replace with your actual connection details
// For this example, we'll use a simplified placeholder:
// $db = new mysqli("localhost", "username", "password", "database_name");
// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }


// Function to handle the search
function performSearch($search_term, $table_name, $search_columns = null) {
  // $db is assumed to be your database connection object.

  $sql = "SELECT * FROM " . $table_name . " WHERE ";

  if ($search_columns) {
    $conditions = [];
    foreach ($search_columns as $column) {
      $conditions[] = "`" . $column . "`" . " LIKE '%" . $search_term . "%'";
    }
    $sql .= implode(" OR ", $conditions);
  } else {
    // Search across all columns.  Be cautious about this in a production environment
    // as it can be very slow and vulnerable to SQL injection.
    $columns = array_keys($db->query("SHOW COLUMNS FROM " . $table_name));
    $conditions = [];
    foreach ($columns as $column) {
        $conditions[] = "`" . $column . "`" . " LIKE '%" . $search_term . "%'";
    }
    $sql .= implode(" OR ", $conditions);
  }


  $result = $db->query($sql);

  if ($result->num_rows > 0) {
    return $result; // Return the result set
  } else {
    return false; // No results found
  }
}



// ---  Example Usage  ---

// Placeholder for your database connection
// $db = new mysqli("localhost", "username", "password", "database_name");

// Example table name
$table_name = "products"; // Replace with your actual table name

// Example search term
$search_term = "laptop";

// You can optionally specify the columns to search within
// $search_columns = ["name", "description"];

// Perform the search
$results = performSearch($search_term, $table_name);

// Display the results
if ($results) {
  echo "<form action=\"search.php\" method=\"get\">
        <input type=\"text\" name=\"search\" placeholder=\"Enter search term\" value=\"" . htmlspecialchars($search_term) . "\">
        <input type=\"submit\" value=\"Search\">
      </form>";

  echo "<br><br>Search Results:<br>";
  if ($results->num_rows > 0) {
    while ($row = $results->fetch_assoc()) {
      echo "Product Name: " . htmlspecialchars($row["name"]) . "<br>";
      echo "Description: " . htmlspecialchars($row["description"]) . "<br>";
      echo "<br>";
    }
  } else {
    echo "No results found for '" . htmlspecialchars($search_term) . "'.";
  }

} else {
  echo "Search failed. Check your database connection and table name.";
}

// Close the database connection (important!)
// $db->close();
?>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search query from the user (e.g., from a form submission)
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search query (important to prevent SQL injection)
$search_query = htmlspecialchars(trim($search_query));

// You'll need to replace this with your actual database query logic
// This is a basic example assuming you have a 'products' table with a 'name' column
//  that you want to search.  Adjust the query to match your database schema.

// Check if the search query is empty to avoid unnecessary queries
if (empty($search_query)) {
  $results = []; // Or display a message "No search term provided"
} else {
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $search_query . "%'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    $results = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $results[] = $row;
    }
  } else {
    // Handle the error - important for debugging
    echo "Error: " . mysqli_error($conn);
  }
}


?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Products</title>
</head>
<body>

  <h1>Search Products</h1>

  <form method="GET" action="">
    <input type="text" name="search" placeholder="Enter search term" value="<?php echo htmlspecialchars($search_query); ?>">
    <button type="submit">Search</button>
  </form>

  <?php if (!empty($results)) { ?>
    <h2>Search Results:</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Price</th>
          <!-- Add other columns as needed -->
        </tr>
      </thead>
      <tbody>
        <?php foreach ($results as $row) { ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <!-- Add more cells based on your table structure -->
          </tr>
        <?php } ?>
      </tbody>
    </table>
  <?php } else {
    echo "<p>No products found matching your search.</p>";
  } ?>

</body>
</html>


<?php
// ... (Database connection, etc.)

$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Use prepared statement
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE name LIKE ?");

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $search_query); // 's' indicates a string parameter
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $results = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($conn);
}
?>


<?php

// Example PHP code to create a simple search bar

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (important for security)
  $searchTerm = htmlspecialchars(trim($searchTerm)); 

  // Perform the search (example: searching through a simple array)
  $searchResults = searchArray($searchResults, $searchTerm);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
</head>
<body>

  <h1>Search</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term...">
    <button type="submit">Search</button>
  </form>

  <?php
  // Display the search results (if any)
  if (isset($searchResults)) {
    echo "<h2>Search Results:</h2>";
    if (empty($searchResults)) {
      echo "<p>No results found.</p>";
    } else {
      echo "<ul>";
      foreach ($searchResults as $result) {
        echo "<li>" . $result . "</li>";
      }
      echo "</ul>";
    }
  }
  ?>

</body>
</html>


<?php

$searchResults = [
  "Apple iPhone 13",
  "Samsung Galaxy S22",
  "Google Pixel 6",
  "OnePlus 10 Pro",
  "Sony WH-1000XM5 Headphones",
];

// ... (rest of the code from above)
?>


<?php

// Function to handle the search bar
function handleSearch($searchTerm) {
  // Sanitize the search term to prevent XSS attacks.  This is CRUCIAL.
  $searchTerm = htmlspecialchars($searchTerm); 

  // Implement your search logic here.  This is just a basic example.
  // You'll likely want to replace this with a database query or 
  // more sophisticated logic.

  $results = []; // Array to store the search results

  // Basic search - replace this with your actual search implementation
  if ($searchTerm != "") {
    $results = array(
      "message" => "Search results for: " . $searchTerm,
      "items" => array(
        "Item 1" => "Description of Item 1 matching " . $searchTerm,
        "Item 2" => "Description of Item 2 matching " . $searchTerm,
        "Item 3" => "Description of Item 3 matching " . $searchTerm,
      )
    );
  }


  return $results;
}


// Get the search term from the form (assuming it's submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["search_term"]; // Assuming the form has a field named "search_term"
  $results = handleSearch($searchTerm);

  // Display the search results
  echo "<h1>Search Results</h1>";
  if (isset($results["message"])) {
    echo "<p>" . $results["message"] . "</p>";
  }

  if (isset($results["items"])) {
    echo "<ul>";
    foreach ($results["items"] as $key => $value) {
      echo "<li>" . $key . ": " . $value . "</li>";
    }
    echo "</ul>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>PHP Search Bar</title>
</head>
<body>

  <h1>Search</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Assuming you have a form for the search bar
?>

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
</head>
<body>

  <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Search..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
    <button type="submit">Search</button>
  </form>

  <?php

  // This part handles the search logic.
  // It's just a basic example. You'll likely want to replace this
  // with your actual database query or search logic.

  if (isset($_GET['search_term']) && !empty($_GET['search_term'])) {
    $search_term = $_GET['search_term'];

    // *** Replace this with your actual search logic ***
    // Example:  If you have a database table called "products" with a "name" column
    // $results = searchDatabase($search_term, "products", "name");

    // Simple example:  Just displaying the search term
    echo "<p>You searched for: " . htmlspecialchars($search_term) . "</p>";
  }
  ?>

</body>
</html>


<?php

// Assuming you're in a web environment (like a web server)
// This example demonstrates a basic search bar with some simple filtering.

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = isset($_POST["search_term"]) ? trim($_POST["search_term"]) : "";

    // Perform your search logic here
    $searchResults = searchDatabase($searchTerm);  // Replace with your actual search function

    // Display the search results
    echo "<form method='post' action=''>";  // Close the form tag if necessary.
    echo "Search: <input type='text' name='search_term' value='" . htmlspecialchars($searchTerm) . "' />";
    echo "<button type='submit'>Search</button>";
    echo "</form>";

    if ($searchTerm) {
        echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
        if (empty($searchResults)) {
            echo "<p>No results found.</p>";
        } else {
            echo "<ul>";
            foreach ($searchResults as $result) {
                echo "<li>" . htmlspecialchars($result) . "</li>";
            }
            echo "</ul>";
        }
    }
} else {
    // If the form is not submitted, display an empty search bar
    echo "<form method='post' action=''>";
    echo "Search: <input type='text' name='search_term' />";
    echo "<button type='submit'>Search</button>";
    echo "</form>";
}

// Example searchDatabase function (replace with your actual implementation)
function searchDatabase($searchTerm) {
    // This is just a placeholder.  You would connect to your database here,
    // build a query, and retrieve the results.  For example:

    $results = [];
    // Example using a dummy array:
    if ($searchTerm == "apple") {
        $results = ["Red Apple", "Green Apple"];
    } elseif ($searchTerm == "banana") {
        $results = ["Cavendish Banana", "Plantain"];
    } else {
        // You'll need to replace this with your database query here.
        // Example using mysqli (assuming you have a database connection)
        // $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
        // $result = $this->mysqli->query($query);
        // while($row = $result->fetch_assoc()) {
        //     $results[] = $row['name'];
        // }
    }

    return $results;
}

?>


<?php
// Assuming you have a $conn mysqli object
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = isset($_POST["search_term"]) ? trim($_POST["search_term"]) : "";

    $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

    // Example using prepared statements to prevent SQL injection
    // $stmt = $this->mysqli->prepare($query);
    // $stmt->bind_param("s", $searchTerm);  // "s" indicates a string parameter
    // $stmt->execute();
    // $result = $stmt->get_result();


    // ... (rest of the code for displaying results)

}
?>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Replace 'your_db_connection' with your actual connection variable
// Example: $conn = mysqli_connect("localhost", "username", "password", "database_name");

// Get the search term from the GET request (e.g., if you have a URL like: ?search=keyword)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term - Important for security!
$searchTerm = htmlspecialchars(trim($searchTerm));

// Database connection (replace with your connection details)
// Example: $conn = mysqli_connect("localhost", "username", "password", "database_name");

// Table name to search in (replace with your actual table name)
$tableName = 'your_table_name';

// Column to search in (replace with the column you want to search)
$searchColumn = 'your_column_name';

// Prepare the SQL query
$query = "SELECT * FROM $tableName WHERE $searchColumn LIKE '%$searchTerm%'";

// Execute the query
// Example using mysqli
// $result = mysqli_query($conn, $query);

// **Important Security Considerations:**

// 1. **Prepared Statements (Strongly Recommended):**  The above code is vulnerable to SQL injection. Always use prepared statements to prevent this.  Prepared statements separate the SQL code from the user-supplied data, preventing malicious code from being executed.  I'll provide a complete example using prepared statements below.

// 2. **Sanitization:**  `htmlspecialchars()` is used to escape characters that could be interpreted as HTML or SQL code.  This is a good practice but doesn't fully protect against all injection attacks.

// 3. **Case Sensitivity:** `LIKE '%$searchTerm%'` is case-insensitive in many databases. If you need case-sensitive searching, use `BINARY LIKE` (MySQL) or equivalent for your database.

// **Example using Prepared Statements (Highly Recommended):**

// Assuming a $conn mysqli connection variable exists.
// If not, you must establish a connection using mysqli_connect().

// Prepare the statement
// $stmt = mysqli_prepare($conn, "SELECT * FROM $tableName WHERE $searchColumn LIKE ?");

// Bind the parameter (the search term)
// mysqli_stmt_bind_param($stmt, "s", $searchTerm);

// Execute the statement
// mysqli_stmt_execute($stmt);

// Get the results
// $result = mysqli_stmt_get_result($stmt); // or mysqli_stmt_use_result($stmt);

// Output the search results
// while ($row = mysqli_fetch_assoc($result)) {
//   echo "ID: " . $row['id'] . "<br>"; // Assuming your table has an 'id' column
//   echo "Name: " . $row['name'] . "<br>";
//   // ... other columns ...
//   echo "<br>";
// }

// Close the result set
// mysqli_free_result($result);

// Close the statement
// mysqli_close($stmt);



// **Complete Example with Prepared Statements and HTML Output:**

?>

<!DOCTYPE html>
<html>
<head>
<title>Search Bar Example</title>
</head>
<body>

<h1>Search Results</h1>

<form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <input type="text" name="search" value="<?php echo $searchTerm; ?>" />
  <input type="submit" value="Search" />
</form>

<?php

// Assume a $conn mysqli connection variable exists.
// If not, you must establish a connection using mysqli_connect().

//  Example Connection (replace with your actual details)
// $conn = mysqli_connect("localhost", "username", "password", "database_name");

//  Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';  // Get the search term

// Sanitize the search term - Important for security!
$searchTerm = htmlspecialchars(trim($searchTerm));

// Table name to search in (replace with your actual table name)
$tableName = 'your_table_name';

// Column to search in (replace with the column you want to search)
$searchColumn = 'your_column_name';

// Prepare the SQL query
$query = "SELECT * FROM $tableName WHERE $searchColumn LIKE '%$searchTerm%'";

// Execute the query
// $result = mysqli_query($conn, $query);

// Assume a $conn mysqli connection variable exists.
// If not, you must establish a connection using mysqli_connect().

// Prepare the statement
// $stmt = mysqli_prepare($conn, "SELECT * FROM $tableName WHERE $searchColumn LIKE ?");

// Bind the parameter (the search term)
// mysqli_stmt_bind_param($stmt, "s", $searchTerm);

// Execute the statement
// mysqli_stmt_execute($stmt);

// Get the results
// $result = mysqli_stmt_get_result($stmt); // or mysqli_stmt_use_result($stmt);


// Example with hardcoded data (replace with your table structure)
$data = array(
    array('id' => 1, 'name' => 'Apple', 'description' => 'A delicious fruit'),
    array('id' => 2, 'name' => 'Banana', 'description' => 'A yellow fruit'),
    array('id' => 3, 'name' => 'Orange', 'description' => 'A citrus fruit'),
);

// Output the search results
if ($searchTerm) {
  echo "<h3>Search Results for: " . htmlspecialchars($searchTerm) . "</h3>";
  echo "<ul>";
  foreach ($data as $row) {
    if (strpos(strtolower($row['name']), strtolower($searchTerm)) !== false ||
        strpos(strtolower($row['description']), strtolower($searchTerm)) !== false) {
      echo "<li>ID: " . $row['id'] . ", Name: " . $row['name'] . ", Description: " . $row['description'] . "</li>";
    }
  }
  echo "</ul>";
} else {
  echo "<p>Enter a search term above.</p>";
}



// Close the result set
// mysqli_free_result($result);

// Close the statement
// mysqli_close($stmt);

?>

</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO) -  Replace this placeholder with your actual connection

// Example using mysqli:
// $conn = new mysqli("localhost", "username", "password", "database_name");
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }

// Placeholder connection - Remove this and use your actual connection
$conn = null; // Initialize to null

// Search query
$searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; // Get search term from URL

// Sanitize the search term (IMPORTANT for security)
$searchTerm = htmlspecialchars($searchTerm);

// Build the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";  //  Adjust 'products' table name and 'name' column

// Execute the query
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Products</title>
</head>
<body>

  <h1>Search Products</h1>

  <form method="GET">
    <input type="text" name="search" placeholder="Enter search term" value="<?php echo $searchTerm; ?>" />
    <button type="submit">Search</button>
  </form>

  <?php
  if ($result->num_rows > 0) {
    echo "<h2>Search Results:</h2>";
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
      echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>"; // Adjust column names as needed
    }
    echo "</ul>";
  } else {
    echo "<p>No products found matching your search.</p>";
  }
  ?>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px; /* Adjust as needed */
      margin: 0 auto;
      text-align: center;
    }
    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Include padding and border in the element's total width */
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
  </div>

  <script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
      // Get the value of the input field
      var searchTerm = document.getElementById('searchInput').value.toLowerCase();

      // Perform your search logic here
      // This is a basic example that just alerts the search term
      alert("Searching for: " + searchTerm);

      // Example of filtering an array (replace with your actual data)
      // var data = ["apple", "banana", "orange", "grape", "avocado"];
      // var filteredData = data.filter(function(item) {
      //   return item.toLowerCase().indexOf(searchTerm) > -1;
      // });
      // console.log(filteredData); // Output to the console
    });
  </script>

</body>
</html>


// Assuming you have an array of strings called $data
var data = ["apple", "banana", "orange", "grape", "avocado"];
var searchTerm = document.getElementById('searchInput').value.toLowerCase();

var filteredData = data.filter(function(item) {
  return item.toLowerCase().indexOf(searchTerm) > -1;
});


<?php

// Configuration
$search_term = ""; // Default search term
$results = [];      // Array to store search results (replace with your data source)

// Function to handle the search
function search($search_term, $data) {
    global $results;

    $results = []; // Clear previous results

    if (empty($search_term)) {
        return $results; // Return empty results if no search term
    }

    $search_term = trim($search_term); // Remove leading/trailing whitespace

    foreach ($data as $item) {
        // Adapt this logic based on your data structure.
        // This example assumes your data is an array of associative arrays:
        // e.g., ['title' => 'Product A', 'description' => '...', 'id' => 123]

        if (
            strpos($item['title'], $search_term) !== false ||  // Check title
            strpos($item['description'], $search_term) !== false // Check description
        ) {
            $results[] = $item;
        }
    }
    return $results;
}


// Example Data (Replace with your actual data source)
$products = [
    ['title' => 'Laptop X1', 'description' => 'Powerful laptop for work and gaming.', 'id' => 1],
    ['title' => 'Wireless Mouse', 'description' => 'Ergonomic wireless mouse.', 'id' => 2],
    ['title' => 'Keyboard Pro', 'description' => 'Mechanical keyboard with RGB lighting.', 'id' => 3],
    ['title' => 'Monitor 27"', 'description' => '27-inch LED monitor for crisp visuals.', 'id' => 4],
    ['title' => 'Headphones Z900', 'description' => 'Noise-cancelling headphones with excellent sound quality.', 'id' => 5]
];


// Handle the search form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = isset($_POST['search_term']) ? trim($_POST['search_term']) : "";
    search($search_term, $products); // Call the search function
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Example</title>
</head>
<body>

<h1>Search Products</h1>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" value="<?php echo htmlspecialchars($search_term); ?>">
    <button type="submit">Search</button>
</form>

<?php if (!empty($results)) { ?>
    <h2>Search Results:</h2>
    <ul>
        <?php foreach ($results as $result) { ?>
            <li>ID: <?php echo htmlspecialchars($result['id']); ?> - <?php echo htmlspecialchars($result['title']); ?></li>
        <?php } ?>
    </ul>
<?php } else { ?>
    <p>No results found for "<?php echo htmlspecialchars($search_term); ?>"</p>
<?php } ?>

</body>
</html>


if (
    stripos($item['title'], $search_term) !== false ||
    stripos($item['description'], $search_term) !== false
) {
    $results[] = $item;
}


<?php

// Example: Simple Search Bar

// 1. Get Input from the Form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = isset($_POST["search_term"]) ? htmlspecialchars($_POST["search_term"]) : ""; // Sanitize input
} else {
  $searchTerm = "";
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
</head>
<body>

  <h1>Search</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" value="<?php echo $searchTerm; ?>" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php
  // Example Search Logic (Replace with your actual search logic)
  if ($searchTerm != "") {
    echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
    echo "<p>No results found for: " . htmlspecialchars($searchTerm) . "</p>";

    //  Example:  Dummy Search through an array
    $items = ["apple", "banana", "orange", "grapefruit", "watermelon"];

    if (strpos($searchTerm, "apple") !== false) {
      echo "<p>You might like apples!</p>";
    }

    if (strpos($searchTerm, "banana") !== false) {
      echo "<p>Bananas are a great choice!</p>";
    }

    //  More robust search would use LIKE operator in a database query
  }
?>

</body>
</html>


<?php

// Assuming you're in a web environment where you can handle user input

// 1. Get the search term from the input field
if (isset($_GET['search'])) {
  $searchTerm = $_GET['search'];

  // 2. Sanitize the input (very important!)
  $searchTerm = htmlspecialchars($searchTerm); // Protect against XSS
  $searchTerm = trim($searchTerm); // Remove leading/trailing whitespace

  // 3. Perform your search logic here
  // This is where you'd typically query a database,
  // search a file, or perform another operation based on
  // the search term.

  // Example:  (Illustrative - Replace with your actual search)
  $searchResults = searchDatabase($searchTerm); //Function call to handle your search

  // 4. Display the results
  echo "<form method='get' action=''>"; // Form to allow re-searching
  echo "<input type='text' name='search' value='" . $searchTerm . "' placeholder='Search...'>";
  echo "<button type='submit'>Search</button>";
  echo "</form>";


  if (empty($searchResults)) {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
  } else {
    echo "<ul>";
    foreach ($searchResults as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  }
} else {
  // If no search term is provided, display a default form
  echo "<form method='get' action=''>";
  echo "<input type='text' name='search' value='' placeholder='Search...'>";
  echo "<button type='submit'>Search</button>";
  echo "</form>";
}

// --- Placeholder Function:  Replace with your actual search logic ---
function searchDatabase($term) {
  // This is a placeholder - replace with your real search implementation
  // Example:  Query a database based on the search term
  // You'll likely need to connect to a database (MySQL, PostgreSQL, etc.)
  // and construct a query.

  // For demonstration, return some dummy results
  $results = ["Result 1", "Result 2", "Result 3", "Another result"];
  return $results;
}

?>


<?php

// Assuming you have a form to capture the search term
// This is a basic example, you might want to adapt it to your specific needs.

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (important to prevent XSS vulnerabilities)
  $searchTerm = htmlspecialchars($searchTerm);

  //  You can now use $searchTerm for your search logic

  // Example: Display the search term
  echo "<p>You searched for: " . $searchTerm . "</p>";

  //  Example:  Search through an array (replace with your actual data source)
  $myArray = array(
    "apple", "banana", "orange", "grapefruit", "watermelon"
  );

  $results = array_filter($myArray, function($item) use ($searchTerm) {
    return stripos($item, $searchTerm) !== false; // Case-insensitive search
  });

  if (!empty($results)) {
    echo "<p>Search Results:</p>";
    echo "<ul>";
    foreach ($results as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  }

}
?>

<!DOCTYPE html>
<html>
<head>
  <title>PHP Search Bar</title>
</head>
<body>

  <h1>Search</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli)
//  $db = new mysqli("localhost", "username", "password", "database_name");
//  if (!$db) {
//    die("Connection failed!");
//  }

// Initialize a variable to hold the search query
$search_query = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize the input to prevent SQL injection
  $search_query = $db->real_escape_string($_POST["search_term"]);

  // Perform the search
  $query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $search_query . "%'";
  $result = $db->query($query);

  // Display the results
  if ($result->num_rows > 0) {
    echo "<h2>Search Results:</h2>";
    while ($row = $result->fetch_assoc()) {
      echo "<div>";
      // Display the data from your table here.  Example:
      echo "<p>Name: " . htmlspecialchars($row["name"]) . "</p>";
      echo "<p>Description: " . htmlspecialchars($row["description"]) . "</p>";
      echo "</div>";
    }
  } else {
    echo "<p>No results found.</p>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
</head>
<body>

  <h1>Search Your Data</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-container {
      margin-bottom: 10px;
    }
    input[type="text"] {
      padding: 5px;
      border: 1px solid #ccc;
      width: 300px;
      box-sizing: border-box;
    }
    button {
      padding: 5px 10px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      cursor: pointer;
      margin-left: 5px;
    }
    .results {
      margin-top: 20px;
      border: 1px solid #ccc;
      padding: 10px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div id="searchResults" class="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      var resultsDiv = document.getElementById("searchResults");

      if (searchTerm.trim() === "") {
        resultsDiv.innerHTML = ""; // Clear previous results
        return;
      }

      // *** Replace this with your actual search logic ***
      // This is just a placeholder to demonstrate the search function.
      // Replace this with your database query or other search implementation.
      var results = [
        "Result 1: Example Item 1",
        "Result 2: Example Item 2",
        "Result 3: Example Item 3",
        "Result 4: Another item"
      ];

      //  Display the results
      resultsDiv.innerHTML = "<h3>Search Results:</h3>";
      resultsDiv.innerHTML += results.map(function(result) {
        return "<div>" + result + "</div>";
      }).join("");
    }
  </script>

</body>
</html>


<?php

// Assuming you have a search term variable passed from a form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection and cross-site scripting (XSS)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Example: Search through a simple array of items
$items = [
    'Apple',
    'Banana',
    'Orange',
    'Grapefruit',
    'Strawberry',
];

// Search logic
$searchResults = [];
if ($searchTerm) {
    foreach ($items as $item) {
        if (stripos($item, $searchTerm) !== false) { // Use stripos for case-insensitive search
            $searchResults[] = $item;
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Example</title>
</head>
<body>

    <h1>Search Results</h1>

    <form method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <input type="text" name="search" placeholder="Enter search term" value="<?php echo $searchTerm; ?>">
        <input type="submit" value="Search">
    </form>

    <?php if (empty($searchResults)): ?>
        <p>No results found for <strong><?php echo htmlspecialchars($searchTerm); ?></strong></p>
    <?php else: ?>
        <ul>
            <?php foreach ($searchResults as $result): ?>
                <li><?php echo htmlspecialchars($result); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>


<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize and validate the search term (important for security)
  $searchTerm = htmlspecialchars(trim($searchTerm)); // Prevents XSS, removes whitespace

  //  You can add more validation here, e.g., character limits, allowed characters
  //  if ($searchTerm == "") {
  //    $results = [];  // Empty results if nothing is searched.
  //    echo "<p>Please enter a search term.</p>";
  //    $displayResults = false;
  //  } else {
  //     $displayResults = true;
  //  }



  // Here, you would typically query a database or search through an array of data
  // based on the $searchTerm.
  //  For demonstration, let's create a simple array of data.
  $data = [
    ["id" => 1, "name" => "Apple"],
    ["id" => 2, "name" => "Banana"],
    ["id" => 3, "name" => "Orange"],
    ["id" => 4, "name" => "Grape"],
  ];

  // Search through the data array
  $searchResults = [];
  foreach ($data as $item) {
    if (strpos(strtolower($item["name"]), strtolower($searchTerm)) !== false) {
      $searchResults[] = $item;
    }
  }

  // Display the results
  echo "<h2>Search Results for: " . $searchTerm . "</h2>";
  if (empty($searchResults)) {
    echo "<p>No results found.</p>";
  } else {
    echo "<ul>";
    foreach ($searchResults as $result) {
      echo "<li>ID: " . $result["id"] . ", Name: " . $result["name"] . "</li>";
    }
    echo "</ul>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>PHP Search Bar</title>
</head>
<body>

  <h1>Search</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (CRUCIAL - use prepared statements instead)
  $searchTerm = $conn->real_escape_string($searchTerm); //minimal sanitization, for demonstration

  // Build the SQL query (USE PREPARED STATEMENTS FOR REAL APPLICATIONS)
  $sql = "SELECT * FROM your_table WHERE name LIKE '%" . $searchTerm . "%'";

  // Execute the query (USE PREPARED STATEMENTS)
  $result = $conn->query($sql);

  // Display the results
  echo "<h2>Search Results for: " . $searchTerm . "</h2>";
  if ($result->num_rows > 0) {
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
      echo "<li>ID: " . $row["id"] . ", Name: " . $row["name"] . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>No results found.</p>";
  }

  $conn->close();
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
    }
    input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px;
      box-sizing: border-box;
    }
    button {
      padding: 8px 15px;
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
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      // **Important: Replace this with your actual search logic**
      // This is just a placeholder to demonstrate how you might filter data.
      var data = [
        "Apple", "Banana", "Orange", "Grape", "Strawberry",
        "Kiwi", "Mango", "Pineapple", "Watermelon"
      ];

      var results = [];
      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().indexOf(searchTerm.toLowerCase()) > -1) {
          results.push(data[i]);
        }
      }

      // Display the results (e.g., in a list)
      var resultsList = document.createElement("ul");
      for (var j = 0; j < results.length; j++) {
        var listItem = document.createElement("li");
        listItem.appendChild(document.createTextNode(results[j]));
        resultsList.appendChild(listItem);
      }

      // You can display the resultsList anywhere on the page (e.g., below the search bar)
      document.getElementById("resultsContainer").innerHTML = resultsList; // Add a resultsContainer element to your HTML
    }
  </script>

  <!-- Add a container for the results -->
  <div id="resultsContainer"></div>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Products</title>
  <style> /* ... (same CSS as above) ... */ </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search Products...">
    <button onclick="search()">Search</button>
  </div>

  <div id="resultsContainer"></div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      // **Important: Replace this with your actual database connection and query**
      // This is just a placeholder.

      // Example Database Connection (replace with your credentials)
      $servername = "localhost";
      $username = "your_username";
      $password = "your_password";
      $dbname = "your_database";

      // Construct the SQL query (using LIKE for partial matching)
      var query = "SELECT * FROM products WHERE name LIKE '%" + searchTerm + "%'";  // Use LIKE operator

      // Create a MySQLi connection
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // Execute the query
      $result = $conn->query($query);

      // Display the results
      var resultsList = document.createElement("ul");
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          var listItem = document.createElement("li");
          listItem.appendChild(document.createTextNode(JSON.stringify($row))); // Convert to string for display
          resultsList.appendChild(listItem);
        }
      }

      document.getElementById("resultsContainer").innerHTML = resultsList;

      $conn->close();
    }
  </script>

</body>
</html>

