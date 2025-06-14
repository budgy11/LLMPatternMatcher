
<?php
// Get the search query from the URL
$query = $_GET['query'];

// Connect to database (assuming you have a MySQL database)
$conn = mysqli_connect("localhost", "username", "password", "database");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to search for matches
$sql = "SELECT * FROM table_name WHERE column_name LIKE '%$query%'";

// Execute the query and store results in a variable
$result = mysqli_query($conn, $sql);

// Close connection
mysqli_close($conn);
?>

<!-- Search bar HTML -->
<form action="search.php" method="get">
    <input type="text" name="query" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<!-- Display search results -->
<?php if ($result): ?>
    <h2>Search Results:</h2>
    <ul>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <li><a href="<?php echo $row['url']; ?>"><?php echo $row['title']; ?></a></li>
        <?php endwhile; ?>
    </ul>
<?php endif; ?>


$stmt = mysqli_prepare($conn, "SELECT * FROM table_name WHERE column_name LIKE ?");
mysqli_stmt_bind_param($stmt, "s", $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


<?php
require_once 'connect.php'; // include your database connection script

// initialize variables
$search_query = '';
$results = array();

if (isset($_GET['search'])) {
  $search_query = $_GET['search'];
  $query = "SELECT * FROM table_name WHERE column_name LIKE '%$search_query%' LIMIT 10";
  $result = mysqli_query($conn, $query);
  while ($row = mysqli_fetch_assoc($result)) {
    $results[] = $row;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar</title>
  <style>
    /* add some basic styling */
    #search-box {
      width: 500px;
      height: 30px;
      padding: 10px;
      border: 1px solid #ccc;
    }
  </style>
</head>
<body>

  <!-- create the search bar -->
  <input type="text" id="search-box" name="search" placeholder="Search...">
  <button type="submit">Search</button>

  <?php if (!empty($results)) { ?>
    <!-- display the search results -->
    <h2>Search Results:</h2>
    <ul>
      <?php foreach ($results as $result) { ?>
        <li><?php echo $result['column_name']; ?></li>
      <?php } ?>
    </ul>
  <?php } ?>

</body>
</html>


<?php
// your database connection script
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>


<?php
// Assuming 'names' table with 'name' field

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $query = trim($_POST['query']);
    if ($query) { // Check for empty query
        try {
            // Connect to database (adjust your connection settings)
            require_once('database.php'); // Assuming a separate file for database connection details
            
            $conn = mysqli_connect($host, $username, $password, $dbname);
            
            // Query processing
            $sql = "SELECT * FROM names WHERE name LIKE '%$query%'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                // Display results
                echo '<div id="result">';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<p>" . $row['name'] . "</p>";
                }
                echo '</div>';
            } else {
                echo "No matches found.";
            }

        } catch (Exception $e) {
            // Handle database error
            echo "An error occurred: " . $e->getMessage();
        }
    }
}

// Example of displaying the query in case it's empty or no results are found
echo "<p>Query: " . $_POST['query'] ?? '' . "</p>";
?>


<?php
  // Connect to database
  $conn = mysqli_connect("localhost", "username", "password", "database");

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Get the search query from the form
  if (isset($_GET['q'])) {
    $searchQuery = $_GET['q'];
  } else {
    $searchQuery = '';
  }
?>

<!-- HTML for the search bar -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
  <input type="text" name="q" placeholder="Search..." value="<?php echo $searchQuery; ?>">
  <button type="submit">Search</button>
</form>

<?php
  // If a search query is entered, display the results
  if (!empty($searchQuery)) {
    // SQL query to retrieve data based on search query
    $sql = "SELECT * FROM table_name WHERE column_name LIKE '%$searchQuery%'";

    // Execute the query and store the result in an array
    $result = mysqli_query($conn, $sql);

    // Display the results
    echo "<h2>Search Results:</h2>";
    while ($row = mysqli_fetch_array($result)) {
      echo "<p>" . $row['column_name'] . "</p>";
    }
  }

  // Close the database connection
  mysqli_close($conn);
?>


<?php
// Configuration - You can adjust this as needed
$databaseHost = 'localhost';
$databaseName = 'your_database_name';
$databaseUsername = 'your_username';
$databasePassword = 'your_password';

// Connect to database
$conn = new mysqli($databaseHost, $databasePassword);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to search database and return results as a string
function searchDatabase($searchTerm) {
    global $conn;
    $query = mysqli_query($conn, "SELECT * FROM your_table_name WHERE column_name LIKE '%$searchTerm%'");
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            echo '<p>' . $row['column_name'] . '</p>';
        }
    } else {
        echo '<p>No results found.</p>';
    }
}

// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the search term from the form
    $searchTerm = $_POST["search"];
    
    // If search term is not empty, run the search query
    if ($searchTerm) {
        searchDatabase($searchTerm);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Page</title>
</head>

<body>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input type="text" name="search" placeholder="Enter your search term here...">
        <button type="submit">Search</button>
    </form>
    
    <?php if (isset($_POST['search']) && $_POST['search'] != ''): ?>
        <div class="results">
            <!-- Search results will be displayed here -->
        </div>
    <?php endif; ?>

</body>

</html>


<?php

// Configuration settings (update with your db credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Connect to the database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If form is submitted
if (isset($_POST['search'])) {

    // Get search term from the form
    $search_term = $_POST['search'];

    // SQL query to search for items
    $sql = "SELECT * FROM items WHERE name LIKE '%$search_term%' OR description LIKE '%$search_term%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Name: " . $row["name"]. " - Description: " . $row["description"]. "<br>";
        }
    } else {
        echo "No items found matching your search";
    }

} else {

?>

<!-- Basic HTML Form for Search Bar -->
<form action="" method="post">
    <input type="text" name="search" placeholder="Search here...">
    <button type="submit" name="search">Search</button>
</form>

<?php
}

?>


<?php
// database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// connect to the database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// form handling
if (isset($_POST['search'])) {
    // get the search query from the form
    $search_query = $_POST['search'];

    // prepare and execute a SQL query to retrieve results
    $stmt = $conn->prepare("SELECT * FROM mytable WHERE title LIKE :query");
    $stmt->bindParam(':query', '%' . $search_query . '%');
    $stmt->execute();

    // fetch and display the results
    $results = $stmt->fetchAll();
    if ($results) {
        echo '<h2>Search Results:</h2>';
        foreach ($results as $result) {
            echo '<p>' . $result['title'] . '</p>';
        }
    } else {
        echo '<p>No results found.</p>';
    }
}

// display the search form
?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit" name="search">Search</button>
</form>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
</head>
<body>

<form action="" method="get">
    <input type="text" name="search" placeholder="Enter your search query...">
    <button type="submit">Search</button>
</form>

<?php
if (isset($_GET['search'])) {
    $query = $_GET['search'];
    displayResults($query);
}
?>

</body>
</html>


function displayResults($query) {
    // Database credentials
    $servername = "localhost";
    $username = "your_username";
    $password = "your_password";

    try {
        // Create connection
        $conn = new mysqli($servername, $username, $password);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $conn->select_db('your_database');

        $sql = "SELECT * FROM table_name WHERE name LIKE '%$query%' OR description LIKE '%$query%'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>";
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["name"] . "</td><td>" . $row["description"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "0 results";
        }

        // Close connection
        $conn->close();
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}


<?php include 'connect.php'; // Assuming you have a connect.php script for database connection ?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Page</title>
</head>
<body>
    <h2>Search for Books</h2>
    <form action="search_result.php" method="post">
        <input type="text" name="search_query" placeholder="Enter search query...">
        <input type="submit" value="Search">
    </form>
    <?php if (isset($_GET['error'])): ?>
        <p style="color:red"><?php echo $_GET['error']; ?></p>
    <?php endif; ?>
</body>
</html>


<?php
include 'connect.php'; // Your connection script

// If form is submitted
if (isset($_POST['search_query'])) {
    $query = $_POST['search_query'];

    // SQL query to search the database for books matching the query
    $sql = "SELECT * FROM books WHERE title LIKE '%$query%' OR author LIKE '%$query%'";
    
    // Execute query and fetch results
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Search Results:</h2>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<p>' . $row['title'] . ' by ' . $row['author'] . '</p>';
        }
    } else {
        echo '<p>No results found.</p>';
    }
} else {
    // If no query is submitted, display an error message
    echo "<script>alert('Please enter a search query.');</script>";
}
?>


<?php
$servername = "localhost";
$username = "username"; // Your MySQL username
$password = "password"; // Your MySQL password
$dbname = "books_database"; // Name of the database you're using

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
// Include database connection script or use PDO to connect
include 'db_connection.php';

$keyword = $_GET['keyword'];
if ($keyword) {
    $sql = "SELECT * FROM items WHERE name LIKE '%$keyword%' OR description LIKE '%$keyword%'";
    $result = mysqli_query($conn, $sql);
    include 'search_results.php'; // Display search results
} else {
    echo '<p>Please enter a keyword to search.</p>';
}
?>


<?php
if ($result) {
    while($row = mysqli_fetch_array($result)) {
        echo '<h2>' . $row['name'] . '</h2>';
        echo '<p>' . $row['description'] . '</p><br>';
    }
} else {
    echo '<p>No results found.</p>';
}

// Close database connection
mysqli_close($conn);
?>


<?php
  // Database connection settings
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "your_database_name";

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Define a function to search for data in the database
  function search_data($query) {
    global $conn;

    // Prepare the query
    $stmt = $conn->prepare("SELECT * FROM your_table_name WHERE column_name LIKE ?");
    $stmt->bind_param("s", "%$query%");

    // Execute the query
    $stmt->execute();

    // Fetch all results
    $results = array();
    while ($row = $stmt->get_result()) {
      $results[] = array(
        'id' => $row['id'],
        'name' => $row['name']
      );
    }

    return $results;
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Bar</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    #search-bar {
      width: 50%;
      height: 40px;
      padding: 10px;
      border: none;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>

  <h1>Search Bar Example</h1>

  <form action="" method="post">
    <input type="text" id="search-bar" name="query" placeholder="Search...">
    <button type="submit">Search</button>
  </form>

  <?php
    if (isset($_POST['query'])) {
      $query = $_POST['query'];
      $results = search_data($query);

      // Display results
      if ($results) {
        echo "<h2>Results:</h2>";
        foreach ($results as $result) {
          echo "<p>ID: " . $result['id'] . ", Name: " . $result['name'] . "</p>";
        }
      } else {
        echo "<p>No results found.</p>";
      }
    }
  ?>

</body>
</html>


<?php
// Connect to database (assuming MySQL)
$conn = new mysqli("localhost", "username", "password", "database");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search query from GET request
$search_query = $_GET['search'];

// Query database for results
if (!empty($search_query)) {
    $query = "SELECT * FROM table_name WHERE column_name LIKE '%$search_query%'";
    $result = $conn->query($query);

    // Display results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>" . $row['column_name'] . "</p>";
        }
    } else {
        echo "No results found.";
    }

} else {
    echo "Please enter a search query.";
}

// Close database connection
$conn->close();
?>


<?php
// Connect to database (replace with your own connection code)
$db = new PDO('sqlite:example.db');

// Define the search function
function search($keyword) {
  global $db;
  
  // Prepare query
  $stmt = $db->prepare("SELECT * FROM table_name WHERE column_name LIKE :keyword");
  $stmt->bindParam(':keyword', '%' . $keyword . '%');
  $stmt->execute();
  
  // Fetch results
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  return $results;
}

// Get the search keyword from the GET request
$keyword = $_GET['q'] ?? '';

// If there is a search query, execute it
if (!empty($keyword)) {
  $results = search($keyword);
} else {
  // No search query, show all records
  $results = array(); // Replace with actual database data
}

?>

<!-- HTML form for the search bar -->
<form action="" method="get">
  <input type="text" name="q" value="<?php echo htmlspecialchars($keyword); ?>">
  <button type="submit">Search</button>
</form>

<!-- Display results -->
<?php if (!empty($results)): ?>
  <h2>Results:</h2>
  <ul>
    <?php foreach ($results as $result): ?>
      <li><?php echo $result['column_name']; ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<?php
// Close database connection (optional)
$db = null;
?>


<?php
// Include the database connection file
require_once 'db.php';

// Initialize variables
$keyword = '';
$results = array();

// Check if form has been submitted
if (isset($_POST['search'])) {
    // Get the keyword from the form
    $keyword = $_POST['search'];

    // Query the database to search for results
    $query = "SELECT * FROM your_table WHERE field LIKE '%$keyword%'";
    $result = mysqli_query($conn, $query);

    // Fetch and store the results
    while ($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="text" name="search" placeholder="Enter keyword">
    <button type="submit" name="search">Search</button>
</form>

<?php if (!empty($keyword)) : ?>
    <?php foreach ($results as $result) : ?>
        <p><?= $result['field']; ?></p>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>


<?php
// Define the database connection settings
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>


$stmt = $conn->prepare("SELECT * FROM your_table WHERE field LIKE ?");
$stmt->bind_param('s', $keyword);
$stmt->execute();


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        #search-bar {
            width: 50%;
            height: 30px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div id="search-container">
    <input type="text" id="search-bar" placeholder="Search...">
    <button id="search-button">Search</button>
</div>

<?php
// Database connection settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Connect to database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search query from input field
$searchQuery = $_POST['search-query'];

// Check if search query is not empty
if (!empty($searchQuery)) {

    // Prepare SQL query to search in database
    $sql = "SELECT * FROM your_table WHERE column_name LIKE '%$searchQuery%'";

    // Execute SQL query
    $result = $conn->query($sql);

    // Display results
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<p>" . $row["column_name"] . "</p>";
        }
    } else {
        echo "No results found.";
    }

} else {
    echo "Please enter a search query.";
}

// Close database connection
$conn->close();
?>
</body>
</html>


<?php
// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search query from URL
$search_query = $_GET['search'];

// Query database for matching results
$query = "SELECT * FROM mytable WHERE column_name LIKE '%$search_query%'";

$result = mysqli_query($conn, $query);

// Display search results
?>
<form action="" method="get">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<h2>Search Results:</h2>
<?php
if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        echo "<p>" . $row['column_name'] . "</p>";
    }
} else {
    echo "No results found.";
}
?>


<?php
// Assume we have a database connection established
require_once 'db_connect.php';

// Define the form action and method
$action = $_SERVER['PHP_SELF'];
$method = 'GET';

// Get the query from the URL (if it exists)
$query = isset($_GET['q']) ? $_GET['q'] : '';

// If the query is not empty, perform a search
if (!empty($query)) {
    $searchQuery = "SELECT * FROM your_table_name WHERE column_name LIKE '%$query%'";

    try {
        // Prepare and execute the query
        $stmt = $pdo->prepare($searchQuery);
        $stmt->execute();
        $results = $stmt->fetchAll();

        // Display results
        if (!empty($results)) {
            echo '<h2>Search Results:</h2>';
            foreach ($results as $result) {
                echo '<p>' . $result['column_name'] . '</p>';
            }
        } else {
            echo 'No results found.';
        }

    } catch (PDOException $e) {
        // Handle database error
        echo 'Error: ' . $e->getMessage();
    }
}
?>

<!-- Search form -->
<form action="<?php echo $action; ?>" method="<?php echo $method; ?>">
    <input type="search" name="q" placeholder="Search...">
    <button type="submit">Search</button>
</form>


<?php
// Establish a database connection using PDO
$dsn = 'mysql:host=localhost;dbname=your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    // Handle database connection error
    echo 'Error: ' . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        /* Add some basic styling to our search bar */
        #search-bar {
            width: 50%;
            height: 30px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <h2>Search Bar</h2>
    <form action="" method="GET">
        <input type="text" id="search-bar" name="q" placeholder="Enter your search query...">
        <button type="submit">Search</button>
    </form>

    <?php
    // Check if the form has been submitted
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
        $results = searchDatabase($query);

        // Display the results
        echo "<h2>Results:</h2>";
        foreach ($results as $result) {
            echo "<p>$result</p>";
        }
    }
    ?>

    <script>
        // Add some JavaScript magic to make our search bar a bit more interactive
        const searchBar = document.getElementById('search-bar');
        searchBar.addEventListener('input', () => {
            const query = searchBar.value.trim();
            if (query !== '') {
                fetch('/search.php?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        const resultsContainer = document.querySelector('#results-container');
                        resultsContainer.innerHTML = '';
                        data.forEach(result => {
                            const resultElement = document.createElement('p');
                            resultElement.textContent = result;
                            resultsContainer.appendChild(resultElement);
                        });
                    })
                    .catch(error => console.error(error));
            }
        });

        // Clear the search bar when clicking the "Clear" button
        const clearButton = document.getElementById('clear-button');
        clearButton.addEventListener('click', () => {
            searchBar.value = '';
            const resultsContainer = document.querySelector('#results-container');
            resultsContainer.innerHTML = '';
        });
    </script>
</body>
</html>

<?php
function searchDatabase($query) {
    // This is a placeholder function for searching the database.
    // In a real application, you'd connect to your database and perform the actual search query.
    return array('Result 1', 'Result 2', 'Result 3');
}
?>


<?php
// Assuming mysqli is used. For PDO, the process would be slightly different.
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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search</title>
</head>
<body>

<form action="" method="post">
    <input type="text" name="search" placeholder="Enter your search here...">
    <button type="submit">Search</button>
</form>

<?php
// If form is submitted, proceed with the search.
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    
    // SQL query to select data from database where name or description matches the search term.
    $sql = "SELECT * FROM items WHERE name LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%'";
    
    // Execute SQL statement
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "id: " . $row["id"]. " - Name: " . $row["name"]. " " . $row["description"] . "<br>";
        }
    } else {
        echo "0 results";
    }
    
    // Close connection
    $conn->close();
}
?>
</body>
</html>


$statement = $conn->prepare("SELECT * FROM items WHERE name LIKE ? OR description LIKE ?");
$statement->bind_param('ss', '%' . $searchTerm . '%');
$statement->execute();
$result = $statement->get_result();


<?php
// Define the database connection settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database_name';

// Create a connection to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the search function
function searchDB($searchTerm) {
    global $conn;
    
    // SQL query to search for matching records in the database
    $query = "SELECT * FROM your_table_name WHERE column_name LIKE '%$searchTerm%'";

    // Execute the query and fetch the results
    $result = $conn->query($query);
    return $result;
}

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Get the search term from the form input
    $searchTerm = $_POST['search'];

    // Call the search function and store the results in a variable
    $results = searchDB($searchTerm);

    // Display the search results
    echo "<h2>Search Results:</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "<p>" . $row['column_name'] . "</p>";
    }
} else {
    // Display the search form if no search term has been submitted
    ?>
    <form action="" method="post">
        <input type="text" name="search" placeholder="Search...">
        <button type="submit" name="submit">Search</button>
    </form>
    <?php
}
?>


<?php
// include database connection file
include 'dbconnect.php';

// if form is submitted
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    // query to search in the database
    $query = "SELECT * FROM table_name WHERE column_name LIKE '%$search%' LIMIT 10";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>" . $row['column_name'] . "</p>";
        }
    } else {
        echo "No results found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>
    <!-- search form -->
    <form action="" method="get">
        <input type="text" name="search" placeholder="Search...">
        <button type="submit">Search</button>
    </form>

    <?php if (isset($_GET['search'])) : ?>
        <div class="results">
            <h2>Results:</h2>
            <!-- display results here -->
        </div>
    <?php endif; ?>
</body>
</html>


<?php
// database connection settings
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

// create a new MySQLi object
$conn = new mysqli($servername, $username, $password, $dbname);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
// Connect to database (replace with your own connection code)
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to search database
function searchDatabase($query){
    global $conn;
    
    // SQL query for searching
    $sql = "SELECT * FROM table_name WHERE column_name LIKE '%$query%'";
    $result = $conn->query($sql);

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div>" . $row["column_name"] . "</div>";
    }
    
    // Close the connection
    $conn->close();
}

// Check if form has been submitted
if (isset($_GET['search'])) {
    $query = $_GET['search'];
    searchDatabase($query);
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        body { font-family: Arial, sans-serif; }
        #search-bar { width: 300px; height: 30px; padding: 5px; border: 1px solid #ccc; }
    </style>
</head>

<body>
    <h2>Search Database</h2>
    
    <!-- Search form -->
    <form action="" method="get">
        <input type="text" id="search-bar" name="search" placeholder="Enter search query...">
        <button type="submit">Search</button>
    </form>

<?php } ?>


<?php
// Connect to database (assuming MySQL)
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Function to search database
function searchDatabase($query) {
    // SQL query to select data from table where name or description contains the search query
    $sql = "SELECT * FROM table_name WHERE name LIKE '%$query%' OR description LIKE '%$query%'";
    
    // Execute query and return result
    $result = $conn->query($sql);
    return $result;
}

// Check if form has been submitted
if (isset($_POST['search'])) {
    // Get search query from form
    $query = $_POST['search'];

    // Call function to search database
    $result = searchDatabase($query);

    // Display results
    echo '<h2>Search Results:</h2>';
    while ($row = $result->fetch_assoc()) {
        echo '<p>' . $row['name'] . ' - ' . $row['description'] . '</p>';
    }
}
?>

<!-- Search form -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit" name="search">Search</button>
</form>


<?php
// Connect to database
$conn = mysqli_connect("localhost", "username", "password", "database");

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Get the search query from the URL (or set a default value)
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Query to retrieve results
$query = "SELECT * FROM table_name WHERE column_name LIKE '%$search_query%'";

// Execute the query and store the result
$result = mysqli_query($conn, $query);

// Display the search bar and results
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
  <input type="text" name="search" placeholder="Search...">
  <button type="submit">Search</button>
</form>

<?php
// Display the search results
if ($result) {
  while ($row = mysqli_fetch_array($result)) {
    echo $row['column_name'] . '<br>';
  }
} else {
  echo 'No results found.';
}
?>

<!-- Close connection -->
mysqli_close($conn);
?>


<!-- index.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <link rel="stylesheet" href="style.css"> <!-- If you have a CSS file for styling -->
</head>
<body>

<h1>Search Bar Example</h1>

<form action="" method="get">
    <input type="text" name="searchQuery" placeholder="Enter your search query...">
    <button type="submit">Search</button>
</form>

<?php
if (isset($_GET['searchQuery'])) {
    $searchQuery = $_GET['searchQuery'];
    // Process the search query here, see below.
}
?>

</body>
</html>


// Include your database connection if you're using one
include_once 'db_connection.php';

if (isset($_GET['searchQuery'])) {
    $searchQuery = $_GET['searchQuery'];
    
    // Assuming your data is in a table named 'data' with columns id, name, description
    $query = "SELECT * FROM data WHERE name LIKE '%$searchQuery%'";
    
    try {
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            // Output the results
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<p>Name: ' . $row['name'] . '</p>';
                echo '<p>Description: ' . $row['description'] . '</p><hr>';
            }
        } else {
            echo "No matching records found.";
        }
        
    } catch (Exception $e) {
        die("Error executing query: " . $e->getMessage());
    }
    
    mysqli_close($conn);
}
?>


<?php
// Connect to database (for example MySQL)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to search for results
function searchResults($query) {
    global $conn;

    // SQL query to retrieve data based on search query
    $sql = "SELECT * FROM your_table_name WHERE column1 LIKE '%$query%' OR column2 LIKE '%$query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["id"] . "'>" . $row["column_name"] . "</option>";
        }
    } else {
        echo "<option>No results found</option>";
    }

    return;
}

// Get query from user
if (isset($_GET['q'])) {
    $query = $_GET['q'];
} else {
    $query = '';
}

// Display search bar and dropdown
?>

<form action="" method="get">
    <input type="text" name="q" placeholder="Search...">
    <button type="submit">Submit</button>
</form>

<?php

// Call function to display results in dropdown
searchResults($query);

?>


$stmt = $conn->prepare("SELECT * FROM your_table_name WHERE column1 LIKE ? OR column2 LIKE ?");
$stmt->bind_param("ss", "%$query%", "%$query%");
$stmt->execute();
$result = $stmt->get_result();

// ...


<?php
// Connect to database (replace with your own connection code)
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>

<form action="" method="post">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<?php
if (isset($_POST['search'])) {
    // Process search query
    $searchQuery = $_POST['search'];
    $query = "SELECT * FROM table_name WHERE column_name LIKE '%$searchQuery%'";

    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>" . $row['column_name'] . "</p>";
        }
    } else {
        echo "Error: " . $conn->error;
    }

    // Close database connection
    $conn->close();
}
?>

</body>
</html>


<?php
  // Connect to database
  $conn = mysqli_connect("localhost", "username", "password", "database");

  if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
    $sql = "SELECT * FROM table_name WHERE column_name LIKE '%$search_query%'";
    $result = mysqli_query($conn, $sql);
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
    }
  } else {
    $data = array(); // empty array for no search results
  }

  // Close database connection
  mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar</title>
</head>
<body>
  <h1>Search Bar</h1>
  <form action="" method="post">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit">Search</button>
  </form>

  <?php if (count($data) > 0): ?>
    <h2>Search Results:</h2>
    <ul>
      <?php foreach ($data as $row): ?>
        <li><?php echo $row['column_name']; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>No search results found.</p>
  <?php endif; ?>
</body>
</html>


<?php
  // Connect to the database
  $conn = mysqli_connect("localhost", "username", "password", "database");

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Define search query variables
  $search_term = isset($_GET['search']) ? $_GET['search'] : '';

  // Search the database for matching records
  $query = "SELECT * FROM table_name WHERE column_name LIKE '%$search_term%'";

  // Execute the query
  $result = mysqli_query($conn, $query);

  // Close the connection
  mysqli_close($conn);
?>

<!-- HTML to display the search bar and results -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
  <input type="search" name="search" placeholder="Search...">
  <button type="submit">Search</button>
</form>

<?php
  // Display search results
  if ($result) {
    while($row = mysqli_fetch_array($result)) {
      echo $row['column_name'] . '<br>';
    }
  } else {
    echo "No results found.";
  }
?>


<?php
// Configuration settings for your database
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Establish a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search term from URL or form submission (we'll use both for simplicity)
$searchTerm = $_GET['search_term'] ?? '';

// Query to fetch results based on the search term
$query = "SELECT * FROM users WHERE name LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%'";
$result = $conn->query($query);

// Prepare an array to store results (for displaying in HTML)
$resultsArray = [];

// Display any errors that occur during query execution
if (!$result) {
    echo "Error: " . $conn->error;
} else {
    while ($row = $result->fetch_assoc()) {
        $resultsArray[] = $row;
    }
}

$conn->close();
?>

<!-- HTML Form and Search Results Section -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
    <input type="text" name="search_term" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<h2>Search Results:</h2>
<ul>
    <?php foreach ($resultsArray as $result) : ?>
        <li><?php echo $result['name'] . " (" . $result['email'] . ")"; ?></li>
    <?php endforeach; ?>
</ul>


<?php
// Connect to database (replace with your own connection code)
$conn = mysqli_connect("localhost", "username", "password", "database");

// If user submits the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get search term from input field
  $search_term = $_POST['search'];

  // Sanitize and escape the search term to prevent SQL injection
  $search_term = mysqli_real_escape_string($conn, $search_term);

  // Prepare SQL query to select data based on search term
  $query = "SELECT * FROM table_name WHERE column_name LIKE '%$search_term%'";

  // Execute query
  $result = mysqli_query($conn, $query);

  // Check if result has any rows
  if (mysqli_num_rows($result) > 0) {
    // Display search results
    echo "<h2>Search Results:</h2>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<p>" . $row['column_name'] . "</p>";
    }
  } else {
    echo "<p>No results found.</p>";
  }
}
?>

<!-- HTML form to submit search query -->
<form action="" method="post">
  <input type="text" name="search" placeholder="Search...">
  <button type="submit">Search</button>
</form>


<?php

// For simplicity, let's assume we have a database or array of items.
// Here, we're using an array for demonstration purposes.
$data = [
    'item1' => ['title' => 'Item 1', 'description' => 'This is item 1.'],
    'item2' => ['title' => 'Another Item', 'description' => 'This is another item.']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = trim($_POST['query']); // Get the search query from the form.
    
    if (!empty($query)) { // Check if there's a query to search for.
        echo "<h3>Search Results:</h3>";
        
        foreach ($data as $key => $item) {
            if (strpos(strtolower($item['title']), strtolower($query)) !== false || 
                strpos(strtolower($item['description']), strtolower($query)) !== false) {
                
                // Display the search result.
                echo "<p><b>$key</b> - $item[title]: $item[description]</p>";
            }
        }
    } else {
        echo '<p>Please enter a search query.</p>';
    }
}

?>


<?php
// Assuming you have a database connection set up here
// For simplicity, we'll use an array to store our search results
$searchResults = array(
    "Apple",
    "Orange",
    "Banana",
    "Cherry"
);

function handleSearchRequest() {
    if (isset($_GET['q'])) {
        $searchTerm = $_GET['q'];
        
        // Simulate a database query here, or connect to your actual database
        global $searchResults;
        
        $results = array_filter($searchResults, function($item) use ($searchTerm) {
            return strpos(strtolower($item), strtolower($searchTerm)) !== false;
        });
        
        echo json_encode(array('status' => 'success', 'data' => $results));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Search term is required'));
    }
}

if (isset($_GET['ajax'])) {
    handleSearchRequest();
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple Search Bar</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<form id="search-form">
    <input type="text" name="q" placeholder="Search..." />
    <button type="submit">Go!</button>
</form>

<script>
    $(document).ready(function() {
        $('#search-form').submit(function(e) {
            e.preventDefault();
            
            var searchQuery = $('input[name="q"]').val().trim();
            if (searchQuery !== '') {
                $.ajax({
                    url: '?ajax=1&q=' + encodeURIComponent(searchQuery),
                    method: 'GET',
                    dataType: 'json'
                }).done(function(data) {
                    if (data.status === 'success') {
                        var resultHtml = '';
                        $.each(data.data, function(index, item) {
                            resultHtml += '<p>' + item + '</p>';
                        });
                        $('#search-results').html(resultHtml);
                    } else {
                        alert(data.message);
                    }
                }).fail(function() {
                    console.log('Error searching');
                });
            }
        });
    });
</script>
<div id="search-results"></div>

<?php
}
?>


<?php
// Connect to database (for searching)
$conn = mysqli_connect("localhost", "username", "password", "database");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$query = $_GET['query']; // Get search query from URL

// Prepare SQL query
$sql = "SELECT * FROM table WHERE column LIKE '%$query%'";

// Execute SQL query and get results
$result = mysqli_query($conn, $sql);

?>

<!-- HTML for search bar -->
<form action="" method="get">
    <input type="text" name="query" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<!-- Display search results -->
<?php if (mysqli_num_rows($result) > 0): ?>
    <h2>Search Results:</h2>
    <table border="1">
        <?php while ($row = mysqli_fetch_array($result)): ?>
            <tr>
                <td><?php echo $row['column']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No results found.</p>
<?php endif; ?>

<!-- Close database connection -->
mysqli_close($conn);
?>


<?php
// Database connection settings
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'search_database';

// Connect to the database
$mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check if the connection was successful
if ($mysqli->connect_errno) {
    printf("Connect failed: %s
", $mysqli->connect_error);
    exit();
}

// Form action and process search query
if (isset($_POST['search'])) {
    // Retrieve the search query from the form
    $searchQuery = $_POST['search'];

    // SQL query to fetch products based on the search query
    $sql = "SELECT * FROM products WHERE name LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%'";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        // Display results in a table
        echo "<table border='1'>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No results found.";
    }
}

// Create form for user input
?>

<form action="" method="post">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<?php
// Close the database connection
$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        #search-bar {
            width: 50%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input id="search-bar" type="text" name="q" placeholder="Search...">
        <button type="submit">Search</button>
    </form>

    <?php
    // Connect to database (replace with your own database connection code)
    $conn = new mysqli('localhost', 'username', 'password', 'database_name');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get search query from URL
    $q = $_GET['q'];

    // Check if search query is not empty
    if (!empty($q)) {
        // SQL query to retrieve results
        $query = "SELECT * FROM table_name WHERE column_name LIKE '%$q%'";

        // Execute the query and store result in a variable
        $result = $conn->query($query);

        // Check if there are any results
        if ($result->num_rows > 0) {
            // Display search results
            echo "<h2>Search Results:</h2>";
            while ($row = $result->fetch_assoc()) {
                echo "<p>" . $row['column_name'] . "</p>";
            }
        } else {
            echo "<p>No results found.</p>";
        }

        // Close the database connection
        $conn->close();
    }
    ?>
</body>
</html>


<?php
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
?>


<?php
include 'config.php';

// Check if the form has been submitted
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $query = "SELECT * FROM users WHERE name LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%'";
    
    // Prepare and execute query
    $result = $conn->prepare($query);
    $result->execute();
    
    // Fetch results
    $results = $result->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
</head>
<body>

<h2>Search Results for '<?php echo $searchTerm; ?>' </h2>

<table border="1">
  <tr>
    <th>Name</th>
    <th>Email</th>
  </tr>
  
  <?php
  while ($row = $results->fetch_assoc()) {
      echo "<tr><td>" . $row['name'] . "</td><td>" . $row['email'] . "</td></tr>";
  }
  ?>
</table>

<?php
} else {
?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="text" name="search" placeholder="Enter search term...">
        <button type="submit">Search</button>
    </form>
<?php
}
?>

</body>
</html>


<?php
// Database connection settings
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form submission handling
if (isset($_POST['search'])) {
    // Get search query from form
    $searchQuery = $_POST['search'];

    // SQL query to search database
    $sql = "SELECT * FROM your_table WHERE column LIKE '%$searchQuery%'";

    // Execute query and store result in an array
    $result = $conn->query($sql);

    // Fetch and display results
    while ($row = $result->fetch_assoc()) {
        echo "<p>" . $row['column_name'] . "</p>";
    }
}

// Close database connection
$conn->close();
?>

<!-- Search bar form -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="text" name="search" placeholder="Search...">
  <button type="submit" name="search">Search</button>
</form>

<?php
// Display any error messages (if query failed)
if ($conn->error) {
    echo "<p>Sorry, an error occurred: " . $conn->error . "</p>";
}
?>


<?php
// Example database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to database (for example using PDO)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch(PDOException $e) {
    echo "Error connecting to the database: " . $e->getMessage();
}

// Get search query from form
$q = $_GET['q'];

// Query to find relevant items based on the search query (for example)
$stmt = $pdo->prepare('SELECT * FROM your_table_name WHERE name LIKE :search');
$stmt->bindParam(':search', '%' . $q . '%');
$stmt->execute();

// Display results in a table
echo '<table>';
echo '<tr><th>Name</th></tr>'; // Example column headings
while ($row = $stmt->fetch()) {
    echo '<tr><td>' . $row['name'] . '</td></tr>';
}
echo '</table>';

// Close the database connection
$pdo = null;
?>


<?php
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
?>


<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body { font-family: Arial, sans-serif; }
        #search-input { width: 300px; height: 30px; padding-left: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 5px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05); }
        #search-input:focus { outline: none; box-shadow: 0 0 10px rgba(0,0,0,0.1) !important; border-color: #66cc00; }
    </style>
</head>
<body>

<form action="" method="post">
    <input type="text" id="search-input" name="search_query" placeholder="Type something to search...">
    <button type="submit">Search</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = $_POST['search_query'];
    
    if (empty($query)) {
        echo 'Please enter a valid query.';
    } else {
        // Insert the query into your database for later reference.
        $sql = "INSERT INTO search_db (query) VALUES ('$query')";
        $conn->query($sql);
        
        try {
            // Execute the search query. For simplicity, we'll use a LIKE statement.
            $search_query = "SELECT * FROM your_table_name WHERE column_name LIKE '%$query%'";
            $result = $conn->query($search_query);
            
            if ($result->num_rows > 0) {
                echo '<h2>Search Results:</h2>';
                while($row = $result->fetch_assoc()) {
                    echo '<p>' . $row['column_name'] . '</p>';
                }
            } else {
                echo "0 results";
            }
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
?>
</body>
</html>


<?php
// Initialize the database connection
$db = new mysqli('localhost', 'username', 'password', 'database');

// Check if the form has been submitted
if (isset($_POST['search'])) {
    // Get the search query from the form
    $query = $_POST['search'];

    // Prepare the SQL statement to retrieve data
    $stmt = $db->prepare("SELECT * FROM table_name WHERE column_name LIKE ?");
    $stmt->bind_param('s', '%' . $query . '%');
    $stmt->execute();
    $result = $stmt->get_result();

    // Display the search results
    while ($row = $result->fetch_assoc()) {
        echo '<p>' . $row['column_name'] . '</p>';
    }
}
?>

<!-- HTML form for the search bar -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<?php
// Close the database connection
$db->close();
?>


<?php

// Function to connect to database, replace 'your_database' and 'username' with your actual database credentials.
function db_connect() {
    $conn = new mysqli('localhost', 'username', 'password', 'your_database');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function search($query) {
    $conn = db_connect();
    $sql = "SELECT * FROM books WHERE title LIKE '%$query%' OR author LIKE '%$query%'";
    $result = $conn->query($sql);

    // Fetch results
    $results = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    }

    $conn->close();
    return $results;
}

// Example usage: Process search query from the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = $_POST['search_query'];
    if (!empty($query)) {
        $results = search($query);
        // Display results in a table.
        echo "<h2>Search Results</h2>";
        echo "<table>";
        echo "<tr><th>Title</th><th>Author</th></tr>";
        foreach ($results as $row) {
            echo "<tr>";
            echo "<td>$row[title]</td>";
            echo "<td>$row[author]</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Please enter a search query.";
    }
}

?>

<!-- HTML Form for inputting the search query -->
<form action="" method="post">
    <input type="text" name="search_query" placeholder="Enter your search term">
    <button type="submit">Search</button>
</form>


<?php
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$user = DB_USER;
$password = DB_PASSWORD;

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>


<form action="" method="post">
    <input type="text" name="search_query" placeholder="Enter your search here...">
    <button type="submit">Search</button>
</form>


<?php
if (isset($_POST['search_query'])) {
    $query = $_POST['search_query'];
    $sql = "SELECT * FROM products WHERE name LIKE :query";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':query', $query . '%');
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($results)) {
            // Display results
            foreach ($results as $result) {
                echo "Product Name: " . $result['name'] . "<br>";
                echo "Description: " . $result['description'] . "<br><hr>";
            }
        } else {
            echo "No results found.";
        }
    } catch (PDOException $e) {
        echo 'Error fetching data: ' . $e->getMessage();
    }
}
?>


<?php
// Connect to database (assuming you have a MySQL database)
$conn = mysqli_connect("localhost", "username", "password", "database");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form has been submitted
if (isset($_POST['search'])) {
    // Get search query from form
    $query = $_POST['search'];

    // SQL query to search database
    $sql = "SELECT * FROM table_name WHERE column_name LIKE '%$query%'";

    // Execute SQL query
    $result = mysqli_query($conn, $sql);

    // Check if result is empty
    if (mysqli_num_rows($result) > 0) {
        // Display results in a table
        echo "<table>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr><td>" . $row['column_name'] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No results found.";
    }
} else {
    // Display search form
    ?>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <input type="text" name="search" placeholder="Search...">
        <button type="submit">Search</button>
    </form>
    <?php
}
?>


<?php
if(isset($_GET['searchTerm'])) {
    $searchTerm = $_GET['searchTerm'];
    
    // Basic check for empty string
    if(empty($searchTerm)) {
        echo 'Please enter a term to search.';
    } else {
        // Display results (in real application, connect to database here)
        $results = array(
            "Result 1",
            "Result 2",
            "Result 3"
        );
        
        echo '<h3>Search Results for: ' . $searchTerm . '</h3>';
        foreach ($results as $result) {
            echo '<p>' . $result . '</p>';
        }
    }
}
?>


<?php
// Connecting to Database (Assuming you're using MySQL)
$conn = new mysqli("localhost", "username", "password", "database_name");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieving Search Query from Form
$search_query = $_GET['search'];

if (empty($search_query)) {
    echo "<p>Please enter a search term.</p>";
} else {
    // Prepare SQL Query to Search in Database
    $sql = "SELECT * FROM posts WHERE title LIKE '%$search_query%' OR content LIKE '%$search_query%'";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Displaying Results
            echo "Title: " . $row["title"]. "<br> Content: " . $row["content"]. "<br><hr>";
        }
    } else {
        echo "No results found";
    }

    $conn->close();
}
?>


<?php
// Configuration
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Connect to database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search query
$searchQuery = $_GET['search'];

// Check if search query is set
if (!empty($searchQuery)) {

    // Query database for matching records
    $query = "SELECT * FROM your_table WHERE column_name LIKE '%$searchQuery%'";

    // Execute query and store results in an array
    $results = $conn->query($query);

    // Display search results
    if ($results->num_rows > 0) {
        while ($row = $results->fetch_assoc()) {
            echo "<p>" . $row['column_name'] . "</p>";
        }
    } else {
        echo "No results found.";
    }

} else {
    echo "Please enter a search query.";
}
?>


// Assume this is included at the top of your script or configured elsewhere
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// After database connection setup or wherever you like in your code
?>

<form action="" method="get">
    <input type="text" name="search" placeholder="Enter your search here..."/>
    <button type="submit">Search</button>
</form>


function searchDatabase($searchTerm) {
    global $conn;
    
    // Prepare the search term to prevent SQL injection
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    
    $query = "SELECT * FROM your_table_name WHERE column_name LIKE '%$searchTerm%'";

    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>Search Result:</p>";
            // Display the result, for example:
            echo "ID: " . $row["id"] . "<br>";
            echo "Name: " . $row["name"] . "<br>";
            echo "Email: " . $row["email"] . "<br><hr>";
        }
        
        $result->close();
    } else {
        echo "Error fetching results: " . $conn->error;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $searchTerm = $_GET['search'];
    
    if (!empty($searchTerm)) {
        searchDatabase($searchTerm);
    } else {
        echo "Please enter something to search.";
    }
}


// Connect to database
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to search database
function searchDatabase($searchTerm) {
    global $conn;
    
    // Prepare the search term
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    
    $query = "SELECT * FROM your_table_name WHERE column_name LIKE '%$searchTerm%'";

    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>Search Result:</p>";
            // Display the result, for example:
            echo "ID: " . $row["id"] . "<br>";
            echo "Name: " . $row["name"] . "<br>";
            echo "Email: " . $row["email"] . "<br><hr>";
        }
        
        $result->close();
    } else {
        echo "Error fetching results: " . $conn->error;
    }
}

// Search form
?>

<form action="" method="get">
    <input type="text" name="search" placeholder="Enter your search here..."/>
    <button type="submit">Search</button>
</form>

<?php

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $searchTerm = $_GET['search'];
    
    if (!empty($searchTerm)) {
        searchDatabase($searchTerm);
    } else {
        echo "Please enter something to search.";
    }
}

// Close the database connection
$conn->close();
?>


<?php
// Connect to database (replace with your own database connection code)
$db = new PDO('sqlite:example.db');

// Get the search query from the form submission
$searchQuery = $_POST['search'];

// Prepare the SQL query
$stmt = $db->prepare("SELECT * FROM table_name WHERE column_name LIKE :query");
$stmt->bindParam(':query', '%' . $searchQuery . '%');
$stmt->execute();

// Fetch and display the results
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Search form -->
    <form action="" method="post">
        <input type="text" name="search" placeholder="Enter search query">
        <button type="submit">Search</button>
    </form>

    <!-- Display results -->
    <?php if ($results): ?>
        <h2>Search Results:</h2>
        <ul>
            <?php foreach ($results as $result): ?>
                <li><?= $result['column_name']; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No results found.</p>
    <?php endif; ?>
</body>
</html>


<?php
// Initialize an array to store data for searching
$data = [
    ['id' => 1, 'name' => 'John Doe'],
    ['id' => 2, 'name' => 'Jane Smith'],
    ['id' => 3, 'name' => 'Bob Johnson']
];

// Check if the search query is set
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    
    // Filter the data array based on the search query
    $filteredData = array_filter($data, function($item) use ($searchQuery) {
        return strpos(strtolower($item['name']), strtolower($searchQuery)) !== false;
    });
} else {
    $filteredData = $data; // If no search query is set, show all data
}
?>

<!-- Search form -->
<form action="" method="get">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<!-- Display the filtered data -->
<h2>Results:</h2>
<ul>
    <?php foreach ($filteredData as $item) : ?>
        <li>ID: <?= $item['id'] ?> - Name: <?= $item['name'] ?></li>
    <?php endforeach; ?>
</ul>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        #search-form {
            width: 50%;
            margin: 20px auto;
        }
        
        input[type="text"] {
            padding: 10px;
            font-size: 16px;
        }
        
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="search-form">
        <h2>Search Bar</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <input type="text" name="search" placeholder="Enter your search query...">
            <button type="submit">Search</button>
        </form>
    </div>

    <?php
    if (isset($_GET['search'])) {
        $searchQuery = $_GET['search'];
        $results = searchDatabase($searchQuery);

        if ($results) { ?>
            <h2>Results:</h2>
            <ul>
                <?php foreach ($results as $result) { ?>
                    <li><a href="<?php echo $result['url']; ?>"><?php echo $result['title']; ?></a></li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No results found.</p>
        <?php }
    }
    ?>

    <?php
    function searchDatabase($searchQuery) {
        // Connect to database
        $db = mysqli_connect('localhost', 'username', 'password', 'database');

        // Prepare query
        $query = "SELECT * FROM table WHERE column LIKE '%$searchQuery%'";

        // Execute query and retrieve results
        $result = mysqli_query($db, $query);
        return $result;
    }
    ?>
</body>
</html>


<form action="search.php" method="get">
    <!-- Rest of your form code remains the same -->
</form>


<?php
if (isset($_GET['q'])) {
    $query = $_GET['q'];
    echo '<h3>You searched for: ' . $query . '</h3>';
}
?>


<?php
// Connect to the database (replace with your own connection code)
$db = mysqli_connect("localhost", "username", "password", "database");

// Define the query to retrieve data from the database
$query = $_POST['query'];

// Sanitize user input to prevent SQL injection
$query = mysqli_real_escape_string($db, $query);

// Execute the query and retrieve results
$results = mysqli_query($db, "SELECT * FROM table_name WHERE column_name LIKE '%$query%'");

// Display search results
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="text" name="query" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<?php if (isset($_POST['query'])): ?>
    <?php while ($row = mysqli_fetch_assoc($results)): ?>
        <p><?= $row['column_name']; ?></p>
    <?php endwhile; ?>
<?php endif; ?>

<?php
// Close database connection
mysqli_close($db);
?>


<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="text" name="query" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<?php if (isset($_POST['query'])): ?>
    <?php while ($row = mysqli_fetch_assoc($results)): ?>
        <p><?= $row['title']; ?> by <?= $row['author']; ?></p>
    <?php endwhile; ?>
<?php endif; ?>


<?php
// Initialize variables
$keyword = '';

if (isset($_POST['search'])) {
  $keyword = $_POST['search'];
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Search Bar</title>
  <style>
    #search-bar {
      width: 50%;
      height: 30px;
      padding: 10px;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>
  <h1>Search Bar</h1>
  <form action="" method="post">
    <input type="text" id="search-bar" name="search" value="<?php echo $keyword; ?>">
    <button type="submit">Search</button>
  </form>
  <?php
  if ($keyword !== '') {
    // Search logic goes here (e.g. query database, etc.)
    $results = searchDatabase($keyword);

    if ($results) {
      ?>
      <h2>Results:</h2>
      <ul>
        <?php foreach ($results as $result) { ?>
          <li><?php echo $result['title']; ?></li>
        <?php } ?>
      </ul>
      <?php
    } else {
      echo '<p>No results found.</p>';
    }
  }
  ?>
</body>
</html>


<?php
function searchDatabase($keyword) {
  // Connect to database (e.g. MySQL)
  $conn = new mysqli('localhost', 'username', 'password', 'database');

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Search query
  $query = "SELECT * FROM table_name WHERE title LIKE '%$keyword%'";

  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    return $result;
  } else {
    return false;
  }
}
?>


// index.php

<?php
$dsn = 'mysql:host=localhost;dbname=database';
$username = 'username';
$password = 'password';

try {
  $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
  echo 'Connection failed: ' . $e->getMessage();
}

// ...

$searchDatabase($keyword);

// searchDatabase.php

<?php
function searchDatabase($keyword) {
  global $pdo;

  $query = 'SELECT * FROM table_name WHERE title LIKE :keyword';
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    return $stmt;
  } else {
    return false;
  }
}
?>


<?php
// Create a connection to the database (assuming you're using MySQL)
$conn = new mysqli("localhost", "username", "password", "database_name");

// Check if the search query is set and not empty
if (isset($_GET['q']) && !empty($_GET['q'])) {
    // Get the search query from the URL parameter
    $searchQuery = $_GET['q'];
    
    // Query the database using the search query
    $query = "SELECT * FROM table_name WHERE column_name LIKE '%$searchQuery%' LIMIT 10";
    $result = mysqli_query($conn, $query);
    
    // Display the results in a table
    echo "<table>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row['column_name'] . "</td></tr>";
    }
    echo "</table>";
} else {
    // Display an empty result if no search query is provided
    echo "No results found.";
}
?>

<!-- Create a form that sends the search query as a GET request -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
    <input type="text" name="q" placeholder="Search...">
    <button type="submit">Search</button>
</form>


$stmt = mysqli_prepare($conn, "SELECT * FROM table_name WHERE column_name LIKE ? LIMIT 10");
mysqli_stmt_bind_param($stmt, "s", $searchQuery);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


<?php
// Database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input from search bar
$search_term = $_GET['search'];

// SQL query to perform search
$sql = "SELECT * FROM your_table WHERE column_name LIKE '%$search_term%'";

// Execute query
$result = $conn->query($sql);

// Check if result is empty
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "Name: " . $row["column_name"] . "<br>";
    }
} else {
    echo "No results found";
}

// Close connection
$conn->close();
?>


// ...

// Get user input from search bar
$search_term = $_GET['search'];

// Prepare SQL statement
$stmt = $conn->prepare("SELECT * FROM your_table WHERE column_name LIKE ?");

// Bind parameters
$stmt->bind_param("s", $search_term);

// Execute query
$stmt->execute();

// ...


<?php
// Define the connection to your database
$conn = mysqli_connect("localhost", "username", "password", "database");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
        }
        #search-bar {
            width: 50%;
            height: 30px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Search bar form -->
<form id="search-form" action="" method="GET">
    <input type="text" id="search-bar" name="search" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<?php
// Check if the search query is set
if (isset($_GET['search'])) {
    // Get the search query
    $searchQuery = $_GET['search'];

    // Query the database to retrieve results
    $query = "SELECT * FROM table_name WHERE column_name LIKE '%$searchQuery%'";
    $result = mysqli_query($conn, $query);

    // Display results
    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Search Results:</h2>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>" . $row['column_name'] . "</p>";
        }
    } else {
        echo "<p>No results found.</p>";
    }
}
?>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>


<?php
  // Connect to the database
  $servername = "localhost";
  $username = "your_username";
  $password = "your_password";
  $dbname = "your_database_name";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Bar</title>
</head>

<body>

  <!-- Search form -->
  <form action="" method="post">
    <input type="text" name="search_term" placeholder="Search here...">
    <button type="submit">Search</button>
  </form>

  <?php
    if (isset($_POST['search_term'])) {
      // Get search term from POST request
      $search_term = $_POST['search_term'];

      // Query database to get results for the search term
      $query = "SELECT * FROM your_table_name WHERE column_name LIKE '%$search_term%'";

      // Execute query and store result in an array
      $result = $conn->query($query);

      // Display search results
      echo "<h2>Search Results:</h2>";
      while ($row = $result->fetch_assoc()) {
        echo $row['column_name'] . " (" . $row['other_column_name'] . ")";
        echo "<br>";
      }
    }
  ?>
</body>
</html>

<?php
  // Close connection to the database
  $conn->close();
?>


<?php
  // Connect to the database using PDO
  $dsn = "mysql:host=localhost;dbname=your_database_name";
  $username = "your_username";
  $password = "your_password";

  try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Bar</title>
</head>

<body>

  <!-- Search form -->
  <form action="" method="post">
    <input type="text" name="search_term" placeholder="Search here...">
    <button type="submit">Search</button>
  </form>

  <?php
    if (isset($_POST['search_term'])) {
      // Get search term from POST request
      $search_term = $_POST['search_term'];

      // Query database to get results for the search term
      $query = "SELECT * FROM your_table_name WHERE column_name LIKE '%$search_term%'";

      try {
        // Prepare and execute query
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();

        // Display search results
        echo "<h2>Search Results:</h2>";
        foreach ($result as $row) {
          echo $row['column_name'] . " (" . $row['other_column_name'] . ")";
          echo "<br>";
        }
      } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
      }
    }
  ?>
</body>
</html>

<?php
  // Close connection to the database
  $conn = null;
?>


<?php
// Database credentials
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If form has been submitted
if (isset($_POST['search'])) {

    // Get search term from the form input
    $searchTerm = $_POST['search'];

    // SQL query to select all records from books where title or author matches the search term
    $sql = "SELECT * FROM books WHERE title LIKE '%$searchTerm%' OR author LIKE '%$searchTerm%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        echo "<h2>Search Results:</h2>";
        while($row = $result->fetch_assoc()) {
            echo "Title: " . $row["title"]. " - Author: " . $row["author"]. "<br><br>";
        }
    } else {
        echo "0 results";
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
</head>
<body>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    Search: <input type="text" name="search"><br><br>
    <input type="submit" name="search" value="Search">
</form>

<?php
if (isset($_POST['search'])) {
    // Code to display search results above goes here if you want
}
?>

</body>
</html>


<?php
// assume we have a database table named 'books' with columns 'title', 'author'
$connection = mysqli_connect("localhost", "username", "password", "database");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = $_GET['q'] ?? '';

if (!empty($query)) {
    $query = "%{$query}%";
    $sql = "SELECT * FROM books WHERE title LIKE '{$query}' OR author LIKE '{$query}'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li>' . $row['title'] . ' by ' . $row['author'] . '</li>';
        }
    } else {
        echo '<p>No results found.</p>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        #search-form {
            width: 50%;
            margin: auto;
        }
    </style>
</head>
<body>
    <h2>Search Books:</h2>
    <form id="search-form" method="get">
        <input type="text" name="q" placeholder="Search...">
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($query)): ?>
        <ul>
            <!-- search results will be displayed here -->
        </ul>
    <?php endif; ?>

    <script>
        // add some basic JavaScript functionality
        const form = document.getElementById('search-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const query = document.getElementsByName('q')[0].value.trim();
            if (!query) return;
            window.location.href = '?q=' + encodeURIComponent(query);
        });
    </script>
</body>
</html>


<?php
// Get the query from the URL (if it exists)
$query = $_GET['q'] ?? '';

// Search for results in your database or array
$results = [];
if ($query) {
    // For demonstration purposes, we'll use an array of data
    $data = [
        ['id' => 1, 'name' => 'John Doe'],
        ['id' => 2, 'name' => 'Jane Doe'],
        ['id' => 3, 'name' => 'Bob Smith'],
        // ...
    ];

    // Search for matches in the data
    foreach ($data as $item) {
        if (stripos($item['name'], $query) !== false) {
            $results[] = $item;
        }
    }
}

?>

<form>
    <input type="text" id="search-query" name="q" value="<?= $query ?>" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<ul>
    <?php foreach ($results as $result) : ?>
        <li><?= $result['name'] ?></li>
    <?php endforeach; ?>
</ul>


<?php
// Initialize variables
$search_term = '';
$results = array();

// If form has been submitted
if (isset($_POST['search'])) {
  // Get the search term from the form
  $search_term = $_POST['search'];
  
  // SQL query to retrieve results based on the search term
  $query = "SELECT * FROM table_name WHERE column_name LIKE '%$search_term%'";
  $result = mysqli_query($conn, $query);
  
  // Fetch and store the result in an array
  while ($row = mysqli_fetch_assoc($result)) {
    $results[] = $row;
  }
}
?>

<!-- HTML structure for search bar -->
<form action="" method="post">
  <input type="text" name="search" placeholder="Search...">
  <button type="submit">Search</button>
</form>

<!-- Display results (if any) -->
<?php if (!empty($results)): ?>
  <h2>Results:</h2>
  <ul>
    <?php foreach ($results as $result): ?>
      <li><?php echo $result['column_name']; ?></li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>No results found.</p>
<?php endif; ?>


// Using prepared statements for SQL injection protection
$stmt = $conn->prepare("SELECT * FROM table_name WHERE column_name LIKE ?");
$stmt->bind_param("s", $_POST['search']);
$stmt->execute();
$result = $stmt->get_result();


<?php
// Initialize the database connection (replace with your own code)
$db = new mysqli("localhost", "username", "password", "database");

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search query from the form
  $search_query = $_POST["search"];

  // Prepare the SQL query to search for matches in a database table (e.g. "products")
  $query = "SELECT * FROM products WHERE name LIKE '%$search_query%' OR description LIKE '%$search_query%'";

  // Execute the query and store the results
  $result = mysqli_query($db, $query);

  // Display the search results
  echo "<h2>Search Results:</h2>";
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<p><a href='" . $row["url"] . "'>" . $row["name"] . "</a></p>";
  }
} else {
  // Display the search form if no query has been submitted
  ?>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit">Search</button>
  </form>
  <?php
}
?>


// Prepare a statement to search for matches in a database table (e.g. "products")
$stmt = $db->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
$stmt->bind_param("ss", $search_query, $search_query);
$stmt->execute();
$result = $stmt->get_result();


<?php
// If the form has been submitted...
if (isset($_GET['submit'])) {
  // Get the search query from the URL parameter
  $searchQuery = $_GET['query'];

  // You can replace this with your own database query to fetch results based on the search query
  // For now, let's assume we have a simple array of names and descriptions
  $data = [
    ['name' => 'John Doe', 'description' => 'This is John Doe'],
    ['name' => 'Jane Smith', 'description' => 'This is Jane Smith'],
    ['name' => 'Bob Brown', 'description' => 'This is Bob Brown']
  ];

  // Use the search query to filter the results
  $results = [];
  foreach ($data as $item) {
    if (strpos($item['name'], $searchQuery) !== false || strpos($item['description'], $searchQuery) !== false) {
      $results[] = $item;
    }
  }

  // Display the search results
  echo '<h1>Search Results:</h1>';
  echo '<ul>';
  foreach ($results as $result) {
    echo '<li>' . $result['name'] . ' - ' . $result['description'] . '</li>';
  }
  echo '</ul>';
}
?>

<!-- The search form -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
  <input type="text" name="query" placeholder="Search...">
  <button type="submit" name="submit">Search</button>
</form>


<?php
  // Connect to database
  $db = new mysqli("localhost", "username", "password", "database");

  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  // Query for search results
  function get_search_results($search_term) {
    $query = "SELECT * FROM table_name WHERE column_name LIKE '%$search_term%'";
    $result = $db->query($query);

    while ($row = $result->fetch_assoc()) {
      echo "<li><a href='item.php?id=$row[id]'>$row[name]</a></li>";
    }

    if (!$result) {
      echo "No results found";
    }
  }

?>
<!DOCTYPE html>
<html>
<head>
  <title>Search Bar</title>
  <style>
    /* Add some basic styling to the search bar */
    #search-bar {
      width: 50%;
      height: 30px;
      padding: 10px;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>
  <input id="search-bar" type="text" placeholder="Search..." onkeyup="search_results(this.value)">
  <ul id="search-results"></ul>

  <script>
    function search_results(search_term) {
      // Send AJAX request to get search results
      fetch('search.php?query=' + search_term)
        .then(response => response.json())
        .then(data => {
          document.getElementById("search-results").innerHTML = "";
          data.forEach(item => {
            const li = document.createElement("li");
            li.innerHTML = `<a href="item.php?id=${item.id}">${item.name}</a>`;
            document.getElementById("search-results").appendChild(li);
          });
        })
        .catch(error => console.error('Error:', error));
    }
  </script>
</body>
</html>


<?php
  // Get search term from URL parameter
  $search_term = $_GET['query'];

  // Connect to database
  $db = new mysqli("localhost", "username", "password", "database");

  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  // Get search results from database
  get_search_results($search_term);

?>


<?php
  // Connect to database
  $db = new mysqli("localhost", "username", "password", "database");

  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  // Get item ID from URL parameter
  $id = $_GET['id'];

  // Retrieve item details from database
  $query = "SELECT * FROM table_name WHERE id = '$id'";
  $result = $db->query($query);

  if (!$result) {
    echo "Error retrieving item";
  } else {
    while ($row = $result->fetch_assoc()) {
      echo "<h1>$row[name]</h1>";
      echo "<p>$row[description]</p>";
    }
  }

?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        #search-bar {
            width: 300px;
            height: 40px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

<form action="" method="get">
    <input type="text" id="search-bar" name="q" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<?php
if (isset($_GET['q'])) {
    $query = $_GET['q'];
    // Perform search query using MySQLi or PDO
    $conn = new mysqli("localhost", "username", "password", "database");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM table WHERE column LIKE '%$query%' LIMIT 10";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>" . $row["column"] . "</p>";
        }
    } else {
        echo "No results found.";
    }
    $conn->close();
}
?>

</body>
</html>


<?php
  // Connect to database (e.g. MySQL)
  $conn = mysqli_connect("localhost", "username", "password", "database");

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Function to search for records in the database
  function search_records($search_term) {
    global $conn;
    $query = "SELECT * FROM table_name WHERE column_name LIKE '%$search_term%'";
    $result = mysqli_query($conn, $query);
    return $result;
  }
?>

<form action="" method="post">
  <input type="text" name="search_term" placeholder="Search...">
  <button type="submit">Search</button>
</form>

<?php
  // Check if form has been submitted
  if (isset($_POST["search_term"])) {
    $search_term = $_POST["search_term"];
    $result = search_records($search_term);

    // Display results
    echo "<h2>Results:</h2>";
    while ($row = mysqli_fetch_array($result)) {
      echo $row["column_name"] . "<br>";
    }
  }
?>


<?php
// Connect to database (assuming MySQL)
$conn = mysqli_connect("localhost", "username", "password", "database");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="search" placeholder="Search...">
        <button type="submit">Search</button>
    </form>

    <?php
    // Check if search query is set
    if (isset($_POST['search'])) {
        $search_query = $_POST['search'];

        // Prepare SQL query to retrieve results
        $query = "SELECT * FROM table_name WHERE column_name LIKE '%$search_query%'";

        // Execute query and fetch results
        $result = mysqli_query($conn, $query);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>" . $row['column_name'] . "</p>";
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        // Close connection
        mysqli_close($conn);
    }
    ?>
</body>
</html>


$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = $_POST['search_query'];
    $search_field = $_POST['search_field'];

    if ($search_query != '') {
        // Prepared statement for security
        $stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE `$search_field` LIKE ?");
        mysqli_stmt_bind_param($stmt, 's', '%' . $search_query . '%');
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo $row['product_name'] . ' - ' . $row['description'];
                echo '<br>';
            }
        } else {
            echo "No results found.";
        }

        // Cleaning up the statement
        mysqli_stmt_close($stmt);
    }
}


<?php
// Assuming you're connecting to your database, this part would typically go at the top of every PHP script.
$servername = "localhost";
$username = "your_username";
$password = "your_password";

$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query from the form.
$searchQuery = $_GET['search'];

// Query to search for books where title or author contains the search query.
$query = "SELECT * FROM books WHERE title LIKE '%$searchQuery%' OR author LIKE '%$searchQuery%'";
$result = $conn->query($query);

echo "<h2>Search Results:</h2>";

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Title: " . $row["title"]. " - Author: " . $row["author"]. "<br>";
    }
} else {
    echo "No results found.";
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="text" name="search" placeholder="Search...">
        <button type="submit">Search</button>
    </form>

    <?php
    if (isset($_GET['search'])) {
        $searchTerm = $_GET['search'];
        // Query database for search results
        $query = "SELECT * FROM your_table WHERE column LIKE '%$searchTerm%'";

        try {
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($results) {
                echo "<h2>Search Results:</h2>";
                foreach ($results as $result) {
                    echo "<p>" . $result['column'] . "</p>";
                }
            } else {
                echo "<p>No results found.</p>";
            }

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    ?>
</body>
</html>


$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


// config.php

$servername = 'localhost';
$username = 'your_username';
$password = 'your_password';

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->select_db('your_database_name');

function getConn() {
    return $GLOBALS['conn'];
}


<?php
// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Connect to database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Define the search function
function searchQuery($conn, $searchTerm) {
  // SQL query to search for matching records
  $query = "SELECT * FROM mytable WHERE name LIKE :searchTerm";
  
  // Prepare the query with parameter binding
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
  
  // Execute the query and fetch results
  $stmt->execute();
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  return $results;
}

// Get search term from form submission
if (isset($_POST['search'])) {
  $searchTerm = $_POST['search'];
  
  // Call the search function with the search term
  $results = searchQuery($conn, $searchTerm);
} else {
  $searchTerm = '';
}

// Display results
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <input type="text" name="search" placeholder="Search...">
  <button type="submit">Search</button>
</form>

<?php if (!empty($results)) : ?>
  <h2>Results:</h2>
  <ul>
    <?php foreach ($results as $result) : ?>
      <li><a href="<?php echo $result['url']; ?>"><?php echo $result['name']; ?></a></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>


<?php
// Connect to database (assuming MySQL)
$conn = new mysqli("localhost", "username", "password", "database");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search query from form submission
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
    $query = "SELECT * FROM table_name WHERE column_name LIKE '%$search_query%'";

    // Prepare and execute query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $search_query);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display search results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>" . $row["column_name"] . "</p>";
        }
    } else {
        echo "No results found.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!-- HTML form to get search query -->
<form action="" method="post">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<!-- Optional: display search results if user clicks on a result -->
<?php
if (isset($_POST['show_result'])) {
    $result_id = $_POST['result_id'];
    $query = "SELECT * FROM table_name WHERE id = '$result_id'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    echo "<p>" . $row["column_name"] . "</p>";
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        /* Add some basic styling to our form */
        form {
            width: 50%;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <h1>Search Bar</h1>
    <form action="search.php" method="get">
        <!-- Search input field -->
        <input type="text" name="query" placeholder="Search here...">
        
        <!-- Submit button -->
        <button type="submit">Search</button>
    </form>

    <?php
    // If the search query is set, display results below
    if (isset($_GET['query'])) {
        $searchQuery = $_GET['query'];
        echo "<h2>Results for '$searchQuery':</h2>";
        // Display search results here (we'll get to this in a moment)
    }
    ?>
</body>
</html>


<?php
// Include database connection file (replace with your own DB connection code)
include 'db_connection.php';

// Get the search query from the URL parameter
$searchQuery = $_GET['query'];

// Check if the search query is not empty
if ($searchQuery != '') {
    // Prepare SQL query to retrieve results
    $sql = "SELECT * FROM table_name WHERE column_name LIKE '%$searchQuery%'";

    // Execute the query and store the result in an array
    $result = mysqli_query($conn, $sql);

    // Check if there are any results
    if (mysqli_num_rows($result) > 0) {
        // Display search results as a list
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>" . $row['column_name'] . "</li>";
        }
    } else {
        // If no results, display a message
        echo '<p>No results found.</p>';
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // If search query is empty, redirect back to index.php
    header('Location: index.php');
}
?>


<?php
// Replace with your own DB connection code
$host = 'localhost';
$dbname = 'database_name';
$username = 'username';
$password = 'password';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<?php
// Configuration settings for database connection
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "mydatabase";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search query handling
if (isset($_POST['search'])) {
    // Get the search query from form submission
    $searchQuery = $_POST['search'];

    // SQL query to fetch results based on the search query
    $sql = "SELECT * FROM users WHERE name LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%'";
    $result = $conn->query($sql);

    // Displaying results
    if ($result->num_rows > 0) {
        echo "<h2>Search Results:</h2>";
        while ($row = $result->fetch_assoc()) {
            echo "Name: " . $row["name"] . ", Email: " . $row["email"] . "</br>";
        }
    } else {
        echo "No results found.";
    }

    // Close database connection
    $conn->close();
}

// Search form
?>

<form action="" method="post">
    <input type="text" name="search" placeholder="Search..."><br>
    <button type="submit" name="submit">Search</button>
</form>



<?php
// Set up database connection
$conn = mysqli_connect("localhost", "username", "password", "database");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the search query from the URL or form submission
if (isset($_GET['q'])) {
    $query = $_GET['q'];
} elseif (isset($_POST['search'])) {
    $query = $_POST['search'];
} else {
    $query = "";
}

// Prepare the SQL query to search for the query in all columns of the table
$query_sql = "SELECT * FROM your_table WHERE CONCAT_WS('', column1, column2, column3) LIKE '%$query%'";

// Execute the SQL query and store the results
$result = mysqli_query($conn, $query_sql);

// Display the search results
if (mysqli_num_rows($result) > 0) {
    echo "<h2>Search Results:</h2>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<p>" . $row['column1'] . " - " . $row['column2'] . "</p>";
    }
} else {
    echo "<p>No results found.</p>";
}

// Close the database connection
mysqli_close($conn);
?>

<!-- HTML form to submit search query -->
<form action="" method="post">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<!-- Alternative: use a GET request with JavaScript and AJAX -->
<script>
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');

    searchButton.addEventListener('click', () => {
        const query = searchInput.value.trim();
        if (query) {
            // Send the query to the server using AJAX
            fetch('/search.php?q=' + encodeURIComponent(query))
                .then(response => response.text())
                .then(data => document.getElementById('results').innerHTML = data)
                .catch(error => console.error(error));
        }
    });
</script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="" method="get">
        <input type="text" id="search-input" name="search" placeholder="Search...">
        <button type="submit">Search</button>
    </form>

    <?php
    if (isset($_GET['search'])) {
        $searchTerm = $_GET['search'];
        $results = searchDatabase($searchTerm);
        displayResults($results);
    }
    ?>

    <script src="script.js"></script>
</body>
</html>


<?php
function searchDatabase($searchTerm) {
    // Connect to database
    $conn = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

    // Query database for matching records
    $stmt = $conn->prepare("SELECT * FROM your_table WHERE column_name LIKE :searchTerm");
    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function displayResults($results) {
    echo '<ul>';
    foreach ($results as $result) {
        echo '<li>' . $result['column_name'] . '</li>';
    }
    echo '</ul>';
}
?>


if (isset($_POST['query'])) {
    $searchQuery = $_POST['query'];
    $conn = new mysqli('localhost', 'username', 'password', 'database_name');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Sanitize the query input
    $searchQuery = trim($searchQuery);
    $searchQuery = htmlspecialchars($searchQuery);
    
    $sql = "SELECT * FROM articles WHERE title LIKE '%$searchQuery%' OR content LIKE '%$searchQuery%'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<p>" . $row["title"] . " - " . $row["content"] . "</p>";
        }
    } else {
        echo "No results found";
    }
    
    $conn->close();
}
?>


<?php
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
?>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = trim($_POST['search_query']);
    
    // Clean and prepare the search query for SQL
    $query = mysqli_real_escape_string($conn, $search_query);
    
    // SQL query to find items by name or description matching the search query
    $sql = "SELECT * FROM items WHERE name LIKE '%$query%' OR description LIKE '%$query%'";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Name: " . $row["name"]. " - Description: " . $row["description"]. "<br>";
        }
    } else {
        echo "No results found";
    }

    // Close the database connection
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Search bar -->
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search...">
        <button id="search-button">Search</button>
    </div>

    <!-- Display search results -->
    <div id="search-results"></div>

    <script src="script.js"></script>
</body>
</html>


// Connect to database (assuming MySQL)

// Get search query from URL parameter
$q = $_GET['q'];

// Perform search using your preferred method (e.g. SQL, NoSQL)
// For this example, we'll use a simple text-based search

$connection = mysqli_connect("localhost", "username", "password", "database");

$query = "SELECT * FROM table WHERE column LIKE '%$q%'";

$result = mysqli_query($connection, $query);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row['column'];
}

// Send JSON response back to JavaScript
header('Content-Type: application/json');
echo json_encode($data);


<?php
// Connect to database (replace with your own connection)
$conn = new mysqli("localhost", "username", "password", "database");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if search query exists
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
} else {
    $searchQuery = "";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        /* Add some basic styling to our search bar */
        #search-bar {
            width: 500px;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <h1>Search Bar</h1>
    <input type="text" id="search-bar" name="search" value="<?php echo $searchQuery; ?>">
    <button type="submit">Search</button>

    <?php
    // If search query exists, display results
    if (!empty($searchQuery)) {
        ?>
        <h2>Search Results:</h2>
        <ul>
            <?php
            // Query database for matching records
            $query = "SELECT * FROM table_name WHERE column_name LIKE '%$searchQuery%'";

            // Execute query and fetch results
            $result = mysqli_query($conn, $query);

            // Display results
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <li>
                    <?php echo $row['column_name']; ?>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
    } else {
        // Display no search query message if none exists
        echo "Enter a search query to see results.";
    }

    // Close database connection
    mysqli_close($conn);
    ?>
</body>
</html>


<form action="" method="post">
  <input type="text" name="search_query" placeholder="Enter your search query...">
  <button type="submit">Search</button>
</form>

<?php

if (isset($_POST['search_query'])) {
  $search_term = $_POST['search_query'];

  // Connect to the database
  $conn = mysqli_connect("localhost", "username", "password", "database");

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // SQL query to search for matching records
  $query = "SELECT * FROM table_name WHERE column_name LIKE '%$search_term%'";

  // Execute the query and fetch results
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<p>" . $row['column_name'] . "</p>";
    }
  } else {
    echo "No results found.";
  }

  // Close the database connection
  mysqli_close($conn);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        #search-container {
            width: 50%;
            margin: 40px auto;
            text-align: center;
        }
        
        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div id="search-container">
    <h2>Search Bar</h2>
    <form action="" method="post">
        <input type="text" name="search_query" placeholder="Enter your search query here...">
        <button type="submit">Search</button>
    </form>
    <?php
        if (isset($_POST['search_query'])) {
            $searchQuery = $_POST['search_query'];
            // Query database for items matching the search query
            $query = "SELECT * FROM items WHERE name LIKE '%$searchQuery%' LIMIT 10";
            
            try {
                $conn = new PDO('mysql:host=localhost;dbname=search_db', 'your_username', 'your_password');
                $stmt = $conn->prepare($query);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    echo "<h3>Search Results:</h3>";
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo $row['name'] . "<br>";
                    }
                } else {
                    echo "No results found.";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            } finally {
                unset($conn);
            }
        }
    ?>
</div>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar Example</title>
</head>
<body>

<form action="" method="post">
    <input type="text" name="search" placeholder="Enter your search query...">
    <button type="submit">Search</button>
</form>

<?php
if (isset($_POST['search'])) {
    include_once 'search.inc.php'; // Include the PHP script for handling searches.
}
?>

</body>
</html>


<?php
// Configuration for connecting to the database.
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// The search query handling function.
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    $query = "SELECT * FROM your_table_name WHERE column_name LIKE :term";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':term', $searchTerm . '%'); // Assuming you're searching in a text field.
        $stmt->execute();
        
        echo '<h2>Search Results:</h2>';
        echo '<table border="1">';
        while ($row = $stmt->fetch()) {
            echo '<tr>';
            foreach ($row as $column => $value) {
                echo '<td>' . $value . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
}

$pdo = null; // Close the PDO connection.
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Example</title>
    <!-- Add some CSS for layout -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        #search-form {
            width: 50%;
            margin: auto;
            padding: 10px;
            background-color: #f7f7f7;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        #search-form input[type="text"] {
            width: 80%;
            height: 30px;
            font-size: 16px;
            padding-left: 10px;
            border: none;
            border-radius: 5px 0 0 5px;
        }
        
        #search-form button[type="submit"] {
            width: 18.2%; /* to make it fit with the input */
            height: 30px;
            font-size: 16px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }
        
        #search-form button[type="submit"]:hover {
            background-color: #444;
        }
    </style>
</head>
<body>

<div id="search-form">
    <input type="text" name="query" placeholder="Search...">
    <button type="submit">Search</button>
</div>

<?php
if(isset($_POST['query'])) {
    $query = $_POST['query'];
    
    // Connect to your database (use the correct host, username, password, and database)
    $conn = mysqli_connect('localhost', 'your_username', 'your_password', 'your_database');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // SQL query to select data from a table where the column matches the search query
    $sql = "SELECT * FROM users WHERE name LIKE '%$query%'";
    
    // Execute the SQL statement and get results
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Search Results:</h2>";
        
        // Display each row in a table format
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>ID: " . $row['id'] . ", Name: " . $row['name'] . "</p>";
        }
    } else {
        echo "<p>No results found.</p>";
    }
    
    // Close the database connection
    mysqli_close($conn);
}
?>

</body>
</html>


$sql = "SELECT * FROM users WHERE name LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_POST['query']);


<?php
// For MySQL connections, you might need to adjust this based on your server setup.
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        /* add some basic styling */
        body {
            font-family: Arial, sans-serif;
        }
        
        #search-bar {
            width: 50%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<h1>Search Bar Example</h1>

<form action="" method="get">
    <input type="search" id="search-bar" name="query" placeholder="Enter search query...">
    <button type="submit">Search</button>
</form>

<?php
if (isset($_GET['query'])) {
    // display search results here...
    
    $query = $_GET['query'];
    echo "You searched for: $query";
}
?>

</body>
</html>


// assuming you have a database connection set up...
$dsn = 'mysql:host=localhost;dbname=example';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    // prepare and execute the search query
    $stmt = $pdo->prepare("SELECT * FROM example_table WHERE column_name LIKE :query");
    $stmt->bindParam(':query', '%' . $query . '%');
    $stmt->execute();
    
    // display search results
    echo "<h2>Search Results:</h2>";
    while ($row = $stmt->fetch()) {
        echo "<p>" . $row['column_name'] . "</p>";
    }
}


<?php
// Define the database connection parameters
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Establish a connection to the database
$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_error($conn));
}

// Get the search query from the input field
$search_query = $_GET['search'];

// Prepare and execute a SQL query to search for matches in the database
$query = "SELECT * FROM your_table WHERE column_name LIKE '%$search_query%'";
$result = mysqli_query($conn, $query);

// Check if any results were found
if (mysqli_num_rows($result) > 0) {
    // Output the results as a table
    echo "<table>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['column_name'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    // If no results were found, output a message
    echo "No matches found.";
}

// Close the database connection
mysqli_close($conn);
?>


<?php
require_once 'database.php'; // Assuming you have a database connection script

// Get the search term from the GET or POST method (in this case, we're using GET)
$searchTerm = $_GET['search'];

// Query to retrieve matching items from the database
$query = "SELECT * FROM items WHERE item_name LIKE '%$searchTerm%'";

try {
    $result = mysqli_query($conn, $query);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

// Retrieve and display search results
while ($row = mysqli_fetch_assoc($result)) {
    // You might want to add some formatting here for your item names in the result list
    echo '<li>' . $row['item_name'] . '</li>';
}
?>


<form action="search.php" method="get">
    <input type="text" name="search" placeholder="Enter your search term...">
    <button type="submit">Search</button>
</form>


<?php
$conn = mysqli_connect("localhost", "username", "password", "database");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<?php
  // Connect to the database
  $db = mysqli_connect("localhost", "username", "password", "database_name");

  // Check connection
  if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Get the search query from the form
  $search_query = $_GET['q'];

  // SQL query to search for results in the database
  $query = "SELECT * FROM table_name WHERE column_name LIKE '%$search_query%'";

  // Execute the query and get the results
  $result = mysqli_query($db, $query);

  ?>
<!DOCTYPE html>
<html>
<head>
  <title>Search Results</title>
  <style>
    /* Add some basic styling to make it look nice */
    body {
      font-family: Arial, sans-serif;
    }
    #search-bar {
      width: 50%;
      padding: 10px;
      border: none;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>

  <!-- The search bar form -->
  <form id="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
    <input type="text" id="search-bar" name="q" placeholder="Search...">
    <button type="submit">Search</button>
  </form>

  <!-- Display the search results -->
  <?php if ($result) { ?>
    <h2>Search Results:</h2>
    <ul>
      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <li>
          <?php echo $row['column_name']; ?>
        </li>
      <?php } ?>
    </ul>
  <?php } else { ?>
    <p>No results found.</p>
  <?php } ?>

</body>
</html>

<?php
  // Close the database connection
  mysqli_close($db);
?>


$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

try {
    $pdo = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name, $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


<form action="" method="get">
    <input type="text" name="searchTerm" placeholder="Search for books...">
    <button type="submit">Search</button>
</form>

<?php
if (isset($_GET['searchTerm'])) {
    $searchTerm = $_GET['searchTerm'];
    // SQL query to search in title, author and description fields
    $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE :term OR author LIKE :term OR description LIKE :term");
    $stmt->bindParam(':term', '%' . $searchTerm . '%');
    $stmt->execute();
    
    $results = $stmt->fetchAll();
    
    if ($results) {
        echo '<h2>Search Results:</h2>';
        
        // Display results
        foreach ($results as $result) {
            echo "<p>" . $result['title'] . " by " . $result['author'] . "</p>";
        }
    } else {
        echo 'No matching books found';
    }
}
?>


<?php
// Database Connection Settings
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form Handling and Database Query
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    
    // SQL Injection Prevention using Prepared Statements
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ?");
    $stmt->bind_param("ss", "%$searchTerm%", "%$searchTerm%");
    $stmt->execute();
    $result = $stmt->get_result();

    // Display Results
    while ($row = $result->fetch_assoc()) {
        echo "<p>Book Title: " . $row['title'] . ", Author: " . $row['author']."</p>";
    }

    // Close the prepared statement and connection.
    $stmt->close();
    $conn->close();
}

// Display Search Form
?>

<form method="post">
    <input type="text" name="search" placeholder="Search for books...">
    <button type="submit" name="search">Search</button>
</form>

<?php

// If the user hasn't submitted a search, display an empty result set.
if (empty($_POST['search'])) {
    echo "<p>No results found.</p>";
}
?>


<?php
// Assuming you're using MySQLi for this example
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<!-- In your PHP file, or in a separate template file -->
<form action="" method="post">
    <input type="text" name="search_query" placeholder="Search here...">
    <button type="submit" name="search_submit">Search</button>
</form>


// In your script, add this code after the database connection and before the HTML output
if (isset($_POST['search_submit'])) {
    $query = $_POST['search_query'];
    $sql = "SELECT * FROM mytable WHERE column_name LIKE '%$query%'";
    
    // Execute the query
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>" . $row['column_name'] . "</p>";
        }
    } else {
        echo "No results found.";
    }
}

// Close the database connection
$conn->close();
?>


if (isset($_POST['search_submit'])) {
    $query = $_POST['search_query'];
    
    $sql = "SELECT * FROM mytable WHERE column_name LIKE ?";
    
    // Prepare the statement with a parameter for the search query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $query);
    
    // Execute the prepared statement
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>" . $row['column_name'] . "</p>";
        }
    } else {
        echo "No results found.";
    }
}


<?php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function query_db($query){
    global $conn;
    return $conn->query($query);
}
?>


<?php
require_once 'db.php';

// Check if form is submitted
if (isset($_GET['s'])) {
    $searchTerm = $_GET['s'];
    
    // SQL query that selects everything from the items table where name or description contains the search term
    $query = "SELECT * FROM items WHERE name LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%'";
    
    // Execute the query
    $result = query_db($query);
    
    if ($result->num_rows > 0) {
        echo '<h2>Search Results:</h2>';
        while($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"]. "<br>Name: " . $row["name"]. "<br>Description: " . $row["description"]. "<br><hr>";
        }
    } else {
        echo '<p>No results found.</p>';
    }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search</title>
</head>
<body>

<form action="search.php" method="get">
    Search: <input type="text" name="s"><br />
    <input type="submit" value="Submit">
</form>

</body>
</html>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $searchQuery = $_POST['search_query'];
    
    // You can echo the search query back as is for testing purposes.
    // In a real application, you would replace this with your database logic.

    echo "You searched for: <b>$searchQuery</b>";

    // Example of how to simulate searching a database
    $results = array(
        'Result 1',
        'Result 2',
        'Result 3'
    );
    echo "<h2>Search Results:</h2>";
    foreach ($results as $result) {
        echo "<p>$result</p>";
    }
}
?>


$serverName = 'your_server_name';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database';

$conn = new mysqli($serverName, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare a query based on the search term
$searchTerm = $_POST['search_query'];
$query = "SELECT * FROM your_table WHERE column LIKE '%$searchTerm%'";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<h2>Search Results:</h2>";
    while($row = $result->fetch_assoc()) {
        echo "<p>" . $row["column_name"] . "</p>";
    }
} else {
    echo "No results found";
}

$conn->close();


$serverName = 'your_server_name';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database';

try {
    $conn = new PDO("mysql:host=$serverName;dbname=$db_name", $db_username, $db_password);
    
    // Prepare a query based on the search term
    $searchTerm = $_POST['search_query'];
    $query = "SELECT * FROM your_table WHERE column LIKE '%$searchTerm%'";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<h2>Search Results:</h2>";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<p>" . $row["column_name"] . "</p>";
        }
    } else {
        echo "No results found";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$conn = null;


<?php
// Connect to database (example: MySQL)
$conn = mysqli_connect("localhost", "username", "password", "database");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search query from URL or form submission
$query = $_GET["q"] ?? "";

// Query the database for matching results
$sql = "SELECT * FROM table_name WHERE column_name LIKE '%$query%'";
$result = mysqli_query($conn, $sql);

// Check if there are any results
if (mysqli_num_rows($result) > 0) {
    // Display search results
    echo "<h2>Search Results:</h2>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<p>" . $row["column_name"] . "</p>";
    }
} else {
    // Display no results message
    echo "<p>No results found.</p>";
}

// Close database connection
mysqli_close($conn);
?>


<?php
require_once 'config.php'; // assuming your database config is in a file named "config.php"

// get the search query from the URL or post data
$search_query = $_GET['q'] ?? '';

// connect to the database
$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
    die('Connection failed: ' . mysqli_error($conn));
}

// create a prepared statement to search for data in the database
$stmt = mysqli_prepare($conn, "SELECT * FROM your_table WHERE title LIKE ?");
mysqli_stmt_bind_param($stmt, "s", "%$search_query%");

// execute the query and fetch results
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
</head>
<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
    <input type="text" name="q" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<?php if ($search_query): ?>
    <?php while ($row = mysqli_fetch_assoc($results)): ?>
        <h2><?= $row['title']; ?></h2>
        <!-- display other relevant information here -->
    <?php endwhile; ?>
<?php endif; ?>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
</body>
</html>


<?php
$db_host = 'your_database_host';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// establish a connection to the database
$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
    die('Connection failed: ' . mysqli_error($conn));
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        #search-bar {
            width: 50%;
            padding: 10px;
            border: 1px solid black;
            border-radius: 5px;
        }
        
        #results {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h2>Search Bar</h2>

<form id="search-form" method="post">
    <input type="text" id="search-input" name="search" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<div id="results"></div>

<?php
// Define an array of results
$products = array(
    "Product 1",
    "Product 2",
    "Product 3",
    "Product 4",
    "Product 5"
);

if (isset($_POST['search'])) {
    // Get the search query from the form input
    $query = $_POST['search'];

    // Use a simple LIKE query to find matching results
    $results = array_filter($products, function ($product) use ($query) {
        return strpos(strtolower($product), strtolower($query)) !== false;
    });

    // Display the search results
    if (!empty($results)) {
        echo "<h2>Search Results:</h2>";
        foreach ($results as $result) {
            echo "<p>$result</p>";
        }
    } else {
        echo "<p>No results found.</p>";
    }
}
?>

<script>
    document.getElementById('search-form').addEventListener('submit', function (e) {
        e.preventDefault();
        var searchInput = document.getElementById('search-input');
        var query = searchInput.value.trim();

        if (query !== '') {
            // Make a new request to the server
            fetch('/search', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `search=${query}`
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                // Update the UI with the search results
                var resultsDiv = document.getElementById('results');
                resultsDiv.innerHTML = '';
                data.forEach(result => {
                    var resultElement = document.createElement('p');
                    resultElement.textContent = result;
                    resultsDiv.appendChild(resultElement);
                });
            })
            .catch(error => console.error(error));
        }
    });
</script>

</body>
</html>


<?php

// Database connection settings
$dsn = 'sqlite:database.db';

try {
    // Connect to the database
    $conn = new PDO($dsn);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get search query from form input
    $searchQuery = $_POST['search'];

    if (!empty($searchQuery)) {

        try {
            // SQL query to search for users matching the search query
            $stmt = $conn->prepare("SELECT * FROM users WHERE name LIKE :search OR email LIKE :search");
            $stmt->bindParam(':search', '%' . $searchQuery . '%');
            $stmt->execute();

            // Fetch and display results
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                echo '<p>' . $row['name'] . ' (' . $row['email'] . ')</p>';
            }

        } catch (PDOException $e) {
            echo "Error searching database: " . $e->getMessage();
        }
    }
}

?>

<!-- HTML Form -->
<form action="" method="post">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit">Search</button>
</form>


