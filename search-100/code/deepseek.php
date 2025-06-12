
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        
        .search-box {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-right: 10px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<?php
$connection = new mysqli("localhost", "root", "", "test");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = $_POST['search_query'];
    $search_terms = explode(' ', $search_query);

    // Prepare the SQL query
    $where_clause = array();
    foreach ($search_terms as $term) {
        $where_clause[] = "name LIKE '%" . mysqli_real_escape_string($connection, $term) . "%'";
        $where_clause[] = "description LIKE '%" . mysqli_real_escape_string($connection, $term) . "%'";
    }

    if (!empty($where_clause)) {
        $sql = "SELECT * FROM products WHERE (" . implode(' OR ', $where_clause) . ")";
        $result = $connection->query($sql);
    } else {
        $sql = "SELECT * FROM products";
        $result = $connection->query($sql);
    }

    if ($result->num_rows > 0) {
        echo "<table class='results'>";
        echo "<tr><th>Name</th><th>Description</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["name"] . "</td><td>" . $row["description"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='results'><p>No results found.</p></div>";
    }
}
?>

<div class="search-container">
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" name="search_query" class="search-box" placeholder="Search here...">
        <button type="submit" class="search-button">Search</button>
    </form>
</div>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .content {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="search-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
        <input type="text" placeholder="Search..." name="query" class="search-bar">
    </form>

    <?php
    // Sample content to search from (you can replace this with data from your database)
    $content = array(
        array('id' => 1, 'title' => 'PHP Tutorial'),
        array('id' => 2, 'title' => 'MySQL Database'),
        array('id' => 3, 'title' => 'Web Development'),
        array('id' => 4, 'title' => 'HTML Basics'),
        array('id' => 5, 'title' => 'CSS Design'),
    );

    // Get search query from form submission
    $query = isset($_GET['query']) ? $_GET['query'] : '';

    // Filter content based on the search query
    $results = array();
    if (!empty($query)) {
        foreach ($content as $item) {
            if (stripos($item['title'], $query) !== false) {
                $results[] = $item;
            }
        }
    }

    // Display search results or message
    echo "<div class='content'>";
    if (!empty($results)) {
        echo "<h3>Search Results:</h3>";
        foreach ($results as $result) {
            echo "<p>" . $result['title'] . "</p>";
        }
    } else {
        if (!empty($query)) {
            echo "<p>No results found for: " . htmlspecialchars($query) . "</p>";
        } else {
            echo "<p>Type something in the search bar to get started.</p>";
        }
    }
    echo "</div>";
    ?>

</div>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php
    // Check if form is submitted
    if (isset($_POST['search'])) {
        $search_term = $_POST['query'];
        
        // Clean the input to prevent XSS attacks
        $search_term = htmlspecialchars($search_term, ENT_QUOTES, 'UTF-8');
        
        // Here you would typically connect to a database and query your data
        // For this example, we'll use sample data
        $sample_data = array(
            "Apple",
            "Banana",
            "Cherry",
            "Date",
            "Elderberry",
            "Fig",
            "Grape",
            "Honeydew",
            "Kiwi",
            "Lemon"
        );
        
        // Simulate search results
        $results = array();
        foreach ($sample_data as $item) {
            if (stripos($item, $search_term) !== false) {
                $results[] = $item;
            }
        }
    } else {
        // If form not submitted, set default message
        $message = "Enter a search term above.";
    }
    ?>

    <div class="search-container">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="query" class="search-bar" placeholder="Search...">
            <button type="submit" name="search" class="search-button">Search</button>
        </form>

        <?php
        if (isset($_POST['search'])) {
            if (!empty($results)) {
                echo "<div class='results'>";
                echo "<h3>Results:</h3>";
                echo "<ul>";
                foreach ($results as $result) {
                    echo "<li>$result</li>";
                }
                echo "</ul>";
                echo "</div>";
            } else {
                echo "<p>No results found.</p>";
            }
        } elseif (isset($message)) {
            echo "<p>$message</p>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        
        .search-box {
            background-color: #f9f9f9;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 20px;
        }
        
        .search-box input[type="text"] {
            width: 80%;
            padding: 5px;
            font-size: 16px;
        }
        
        .search-box input[type="submit"] {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-box input[type="submit"]:hover {
            background-color: #45a049;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
            // Check if form has been submitted
            if (isset($_POST['search_term'])) {
                $searchTerm = $_POST['search_term'];
                
                // Database connection details
                $host = "localhost";
                $username = "root";
                $password = "";
                $database = "test_db";
                
                // Connect to database
                $conn = new mysqli($host, $username, $password, $database);
                
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                
                // SQL query with user input
                $sql = "SELECT * FROM users WHERE name LIKE '%" . mysqli_real_escape_string($conn, $searchTerm) . "%'";
                $result = $conn->query($sql);
                
                // Display results if any found
                if ($result->num_rows > 0) {
                    echo "<table><tr><th>ID</th><th>Name</th><th>Email</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row['id'] . "</td><td>" . $row['name'] . "</td><td>" . $row['email'] . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No results found.";
                }
                
                // Close database connection
                $conn->close();
            }
        ?>
        
        <div class="search-box">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="text" name="search_term" placeholder="Search..." required>
                <input type="submit" value="Search">
            </form>
        </div>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <!-- Include Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Search Form -->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="input-group mb-4">
                <input type="text" name="search_term" class="form-control" placeholder="Search...">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <!-- Search Results -->
        <?php
            // Check if form has been submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $search_term = $_POST['search_term'];

                // Connect to database
                $db_host = 'localhost';
                $db_user = 'username';
                $db_pass = 'password';
                $db_name = 'database_name';

                try {
                    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
                    
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Sanitize the search term
                    $search_term = htmlspecialchars($search_term);

                    // SQL query to search for matching records
                    $sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $search_term . "%'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        echo "<table class='table table-striped'>";
                        echo "<thead><tr><th>Column Name</th></tr></thead>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tbody><tr><td>" . $row['column_name'] . "</td></tr></tbody>";
                        }
                        echo "</table>";
                    } else {
                        echo "No results found.";
                    }

                    // Close connection
                    mysqli_close($conn);
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-btn:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search_query" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-btn">Search</button>
        </form>
        
        <?php
        // Check if search query is provided
        if (isset($_GET['search_query'])) {
            $searchQuery = $_GET['search_query'];
            
            // Sanitize the input to prevent SQL injection
            $searchQuery = htmlspecialchars($searchQuery);
            
            // Database connection details
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "mydatabase";
            
            // Create database connection
            $conn = mysqli_connect($servername, $username, $password, $dbname);
            
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // SQL query to search for matching results
            $sql = "SELECT * FROM your_table WHERE column_name LIKE ?";
            
            // Prepare and bind the statement
            $stmt = mysqli_prepare($conn, $sql);
            $searchValue = "%" . $searchQuery . "%";
            mysqli_stmt_bind_param($stmt, "s", $searchValue);
            
            // Execute the query
            mysqli_stmt_execute($stmt);
            
            // Get the result set
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                echo "<div class='results'>";
                echo "<h3>Search Results:</h3>";
                while ($row = mysqli_fetch_assoc($result)) {
                    // Display the results according to your table structure
                    echo "<p>" . $row['column_name'] . "</p>";
                }
                echo "</div>";
            } else {
                echo "<div class='results'>";
                echo "<p>No results found.</p>";
                echo "</div>";
            }
            
            // Close connections
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            width: 70%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-button {
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="query" placeholder="Search..." class="search-bar">
            <button type="submit" name="submit" class="search-button">Search</button>
        </form>

        <?php
        // Sample data - replace this with your database connection and query
        $sample_data = array(
            "Apple",
            "Banana",
            "Cherry",
            "Date",
            "Grape",
            "Kiwi",
            "Mango",
            "Orange",
            "Peach",
            "Pear"
        );

        if (isset($_GET['submit'])) {
            $search_query = $_GET['query'];
            
            // Sanitize input
            $search_query = htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8');
            $search_query = trim($search_query);
            $search_query = strtolower($search_query);

            echo "<div class='results'>";
                if (empty($sample_data)) {
                    echo "No results found.";
                } else {
                    foreach ($sample_data as $item) {
                        $item_lower = strtolower($item);
                        if (strpos($item_lower, $search_query) !== false) {
                            echo "<p>$item</p>";
                        }
                    }
                    // If no matches found
                    $matches_found = 0;
                    foreach ($sample_data as $item) {
                        $item_lower = strtolower($item);
                        if (strpos($item_lower, $search_query) !== false) {
                            $matches_found++;
                        }
                    }
                    if ($matches_found == 0) {
                        echo "No results found.";
                    }
                }
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['submit'])) {
    $search_query = $_GET['query'];
    
    // Prepare and bind
    $stmt = $conn->prepare("SELECT item FROM items WHERE item LIKE ?");
    $search_query = "%$search_query%";
    $stmt->bind_param("s", $search_query);
    
    $stmt->execute();
    
    // Get result set
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        echo "<p>" . htmlspecialchars($row['item'], ENT_QUOTES, 'UTF-8') . "</p>";
    }
}
?>


<?php
// Connect to your database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term
$searchTerm = htmlspecialchars(trim($_POST['query']));

// SQL query to get results
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "No results found.";
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar Example</title>
    <style>
        /* Add some basic styling */
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-input {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        /* Style for search results */
        .search-results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search form -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="search" class="search-input" placeholder="Search here...">
            <button type="submit" name="submit" class="search-button">Search</button>
        </form>

        <!-- Search results will be displayed here -->
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "mydatabase";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Process search query
        if (isset($_GET['submit'])) {
            $search = htmlspecialchars(trim($_GET['search']));

            // SQL query to search for the term in your database table
            $sql = "SELECT * FROM your_table_name WHERE title LIKE '%" . $search . "%' OR description LIKE '%" . $search . "%'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                // Display search results
                echo "<div class='search-results'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='result-item'>";
                    echo "<strong>" . $row['title'] . "</strong><br>";
                    echo $row['description'];
                    echo "</div>";
                }
                echo "</div>";
            } else {
                // No results found
                echo "<p>No results found for '" . $search . "'.</p>";
            }
        }

        // Close database connection
        mysqli_close($conn);
        ?>
    </div>
</body>
</html>


<?php
// Get the search term from the form
$query = isset($_POST['query']) ? $_POST['query'] : '';

// Sanitize the input to prevent SQL injection
$safe_query = htmlspecialchars($query);

// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if query is not empty
if (!empty($safe_query)) {
    // SQL query to fetch data from the table
    $sql = "SELECT * FROM your_table WHERE title LIKE '%".$safe_query."%' OR description LIKE '%".$safe_query."%'";
    
    // Execute the query
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Output the results in a table
        echo "<table border='1'>";
        echo "<tr><th>Title</th><th>Description</th><th>URL</th></tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>" . $row['url'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        // If no results found
        echo "No results found!";
    }
} else {
    // If query is empty
    echo "Please enter a search term!";
}

// Close the database connection
mysqli_close($conn);

// Go back to search page
echo "<br><a href='index.html'>Back to Search Page</a>";
?>


<?php
// Get the search term from the form
$query = isset($_POST['query']) ? $_POST['query'] : '';

// Sanitize the input to prevent SQL injection
$safe_query = htmlspecialchars($query);

// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if query is not empty
if (!empty($safe_query)) {
    // Prepare the SQL statement with placeholders
    $sql = "SELECT * FROM your_table WHERE title LIKE ? OR description LIKE ?";
    
    // Create prepared statement
    $stmt = mysqli_prepare($conn, $sql);
    
    // Bind parameters to the placeholders
    $search_term = '%' . $safe_query . '%';
    mysqli_stmt_bind_param($stmt, 'ss', $search_term, $search_term);
    
    // Execute the query
    mysqli_stmt_execute($stmt);
    
    // Get the result set
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        // Output the results in a table
        echo "<table border='1'>";
        echo "<tr><th>Title</th><th>Description</th><th>URL</th></tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>" . $row['url'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        // If no results found
        echo "No results found!";
    }
    
    // Close the prepared statement
    mysqli_stmt_close($stmt);
} else {
    // If query is empty
    echo "Please enter a search term!";
}

// Close the database connection
mysqli_close($conn);

// Go back to search page
echo "<br><a href='index.html'>Back to Search Page</a>";
?>


<?php
// This is search.php

if (isset($_POST['query'])) {
    $query = htmlspecialchars(trim($_POST['query']));
    
    // Database connection details
    $host = 'localhost';
    $db_username = 'root'; // Change to your database username
    $db_password = '';     // Change to your database password
    $dbname = 'search_db';  // Change to your database name
    
    try {
        // Connect to the database
        $conn = new mysqli($host, $db_username, $db_password, $dbname);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // SQL query to search for the keyword in your table
        $sql = "
            SELECT 
                id, title, description 
            FROM 
                products 
            WHERE 
                title LIKE '%{$query}%'
                OR description LIKE '%{$query}%'
            LIMIT 10
        ";
        
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="result-item">';
                echo "<h3>{$row['title']}</h3>";
                echo "<p>{$row['description']}</p>";
                echo '</div>';
            }
        } else {
            echo '<div class="result-item">';
            echo "No results found for: {$query}";
            echo '</div>';
        }
        
        $conn->close();
    } catch (Exception $e) {
        die("Error occurred: " . $e->getMessage());
    }
}
?>


<?php
// Check if a search query was submitted
if (isset($_GET['query'])) {
    $search_query = $_GET['query'];
    
    // Here you would typically connect to your database and run a search query
    // For this example, we'll use a simple array of sample data
    $sample_data = array(
        "Apple",
        "Banana",
        "Cherry",
        "Date",
        "Grape",
        "Mango",
        "Orange",
        "Peach"
    );
    
    // Filter the results based on the search query
    $results = array();
    foreach ($sample_data as $item) {
        if (strtolower($item) == strtolower($search_query)) {
            $results[] = $item;
        }
    }
    
    // Display the results
    echo "<h2>Search Results</h2>";
    if (!empty($results)) {
        echo "<p>Found " . count($results) . " result(s):</p>";
        echo "<ul>";
        foreach ($results as $result) {
            echo "<li>" . htmlspecialchars($result, ENT_QUOTES, 'UTF-8') . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No results found for: " . htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8') . "</p>";
    }
} else {
    // If no search query was submitted
    header("Location: index.html");
    exit();
}
?>


<?php
// search.php

// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'my_database';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search query from form submission
$query = $_POST['query'];

// SQL query to search for matching results
$sql = "SELECT * FROM users WHERE name LIKE '%" . mysqli_real_escape_string($conn, $query) . "%'";
$result = mysqli_query($conn, $sql);

// Display the results
echo "<div class='results'>";
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='result-item'>";
        echo "Name: " . $row['name'] . "<br>";
        echo "Email: " . $row['email'] . "<br>";
        echo "</div>";
    }
} else {
    echo "No results found!";
}
echo "</div>";

// Close database connection
mysqli_close($conn);
?>


<?php
// Connect to the database
$connection = mysqli_connect("localhost", "username", "password", "database");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term from form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = mysqli_real_escape_string($connection, $_POST['search']);

    // Search query
    $query = "SELECT * FROM users WHERE name LIKE '%" . $search_term . "%'";
    $result = mysqli_query($connection, $query);

    // Display results
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<ul>";
            echo "<li>" . $row['name'] . "</li>";
            echo "</ul>";
        }
    } else {
        echo "No results found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="search" placeholder="Search...">
        <button type="submit">Search</button>
    </form>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php
    // Database configuration
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "mydatabase";

    // Connect to database
    $conn = mysqli_connect($host, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if form is submitted
    if (isset($_GET['search'])) {
        $query = $_GET['search'];

        // Sanitize input
        $search = mysqli_real_escape_string($conn, $query);

        // Search query
        $sql = "SELECT * FROM users WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<h3>Search Results:</h3>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='search-results'>";
                echo "<p>Name: " . $row['name'] . "</p>";
                echo "<p>Email: " . $row['email'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<h3>No results found.</h3>";
        }
    }

    // Close database connection
    mysqli_close($conn);
    ?>

    <div class="search-container">
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search by name or email...">
                <button type="submit">Search</button>
            </div>
        </form>
    </div>

</body>
</html>


// Replace these with your actual database credentials
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM your_table WHERE column LIKE '%" . $searchTerm . "%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Display your results here
        echo "<div class='result'>" . $row['column_name'] . "</div>";
    }
} else {
    echo "No results found.";
}


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <!-- Add some basic CSS styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        
        .search-box {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Create the search form -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="search_query" class="search-box" placeholder="Enter your search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <?php
    // Check if form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the search query from POST data
        $search_query = isset($_POST['search_query']) ? $_POST['search_query'] : '';
        
        // Sanitize the input to prevent SQL injection or XSS attacks
        $search_query = htmlspecialchars($search_query, ENT_QUOTES);
        
        // Connect to the database (replace with your actual database credentials)
        $host = 'localhost';
        $db_username = 'root';
        $db_password = '';
        $database_name = 'my_database';
        
        try {
            // Create a new PDO instance
            $pdo = new PDO("mysql:host=$host;dbname=$database_name", $db_username, $db_password);
            
            // Prepare the SQL query with a LIKE clause for search functionality
            $sql = "SELECT * FROM your_table WHERE column_name LIKE ?";
            $statement = $pdo->prepare($sql);
            $search_term = "%" . $search_query . "%";
            $statement->bindParam(1, $search_term);
            
            // Execute the query
            $statement->execute();
            
            // Fetch the results
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            // Display the results
            if (!empty($results)) {
                echo "<h3>Search Results:</h3>";
                foreach ($results as $row) {
                    // Output each result in a readable format
                    echo "<pre>";
                    print_r($row);
                    echo "</pre>";
                }
            } else {
                echo "<p>No results found.</p>";
            }
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    ?>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'mydatabase';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term from form input
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Sanitize the input to prevent SQL injection and XSS attacks
$safe_query = htmlspecialchars($query);
$safe_query = mysqli_real_escape_string($conn, $safe_query);

// Validate the input (you can add more validation as needed)
if ($safe_query == '') {
    echo "Please enter a valid search term.";
    exit();
}

// Create SQL query to search for matching records
$sql = "
    SELECT * FROM users 
    WHERE first_name LIKE '%$safe_query%' 
    OR last_name LIKE '%$safe_query%'
";

// Execute the query
$result = mysqli_query($conn, $sql);

// Check if there are any results
if (mysqli_num_rows($result) > 0) {
    // Display the results in a table
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
            </tr>";
    
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>".$row['id']."</td>
                <td>".$row['first_name']."</td>
                <td>".$row['last_name']."</td>
                <td>".$row['email']."</td>
              </tr>";
    }
    
    echo "</table>";
} else {
    // If no results found
    echo "No results found for: $safe_query";
}

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="search.php" method="post">
            <input type="text" name="search_term" class="search-bar" placeholder="Search...">
            <button type="submit">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Get search term from form
$search = $_POST['search_term'];

// Sanitize input to prevent SQL injection
$search = htmlspecialchars($search);

// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'my_database';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to search for the term
$sql = "SELECT * FROM my_table WHERE column_name LIKE ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $search);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['column_name'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "No results found.";
}

// Close database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .search-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        input[type="text"] {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        input[type="submit"] {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Database configuration
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'test_db';

        // Connect to database
        try {
            $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit;
        }

        // Check if search parameter exists
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            
            // Prepare and execute query
            try {
                $stmt = $conn->prepare("SELECT * FROM users WHERE name LIKE :search OR email LIKE :search");
                $search_term = '%' . $search . '%';
                $stmt->bindParam(':search', $search_term);
                $stmt->execute();
                
                // Display results
                echo "<h3>Search Results:</h3>";
                echo "<div class='results'>";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<p>ID: " . $row['id'] . "</p>";
                    echo "<p>Name: " . $row['name'] . "</p>";
                    echo "<p>Email: " . $row['email'] . "</p>";
                    echo "<hr>";
                }
                if ($stmt->rowCount() == 0) {
                    echo "<p>No results found.</p>";
                }
                echo "</div>";
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "<h3>Search Bar</h3>";
        }

        // Close connection
        $conn = null;
        ?>

        <form class="search-form" method="GET">
            <input type="text" name="search" placeholder="Enter search term...">
            <input type="submit" value="Search">
        </form>
    </div>
</body>
</html>


<?php
// Simulated database items
$items = array(
    array('id' => 1, 'title' => 'Apple', 'description' => 'Fruit'),
    array('id' => 2, 'title' => 'Banana', 'description' => 'Fruit'),
    array('id' => 3, 'title' => 'Carrot', 'description' => 'Vegetable'),
    array('id' => 4, 'title' => 'Potato', 'description' => 'Vegetable'),
);

// Function to search items
function search_items($query) {
    global $items;
    
    $results = array();
    foreach ($items as $item) {
        if (stripos($item['title'], $query) !== false || 
            stripos($item['description'], $query) !== false) {
            $results[] = $item;
        }
    }
    return $results;
}

// Handle search query
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = $_POST['query'];
    
    if ($query != "") {
        $results = search_items($query);
        
        // Display results
        foreach ($results as $result) {
            echo "<div class='card mb-3'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>ID: " . $result['id'] . "</h5>";
            echo "<p class='card-text'>Title: " . $result['title'] . "</p>";
            echo "<p class='card-text'>Description: " . $result['description'] . "</p>";
            echo "</div></div>";
        }
        
        if (empty($results)) {
            echo "No results found!";
        }
    } else {
        echo "Please enter a search query!";
    }
}
?>


<?php
// Connect to the database
$connection = mysqli_connect("DB_HOST", "DB_USERNAME", "DB_PASSWORD", "DB_NAME");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term from form input
$search_term = $_POST['search_term'];
// Filter and sanitize the input
$search_term = htmlspecialchars($search_term);
$search_term = trim($search_term);

// SQL query to fetch results
$sql = "SELECT * FROM products WHERE product_name LIKE ? OR description LIKE ?";
$stmt = mysqli_prepare($connection, $sql);

// Bind parameters
mysqli_stmt_bind_param($stmt, 'ss', "%$search_term%", "%$search_term%");

mysqli_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr><th>Product ID</th><th>Product Name</th><th>Description</th></tr>";
    
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['product_id']."</td>";
        echo "<td>".$row['product_name']."</td>";
        echo "<td>".$row['description']."</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "No results found.";
}

// Close the database connection
mysqli_close($connection);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>

<?php
// Database connection details
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Connect to database
$conn = mysqli_connect($host, $username, $password, $database);

// Check if connection failed
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term from form submission
if (isset($_POST['submit'])) {
    $search_term = $_POST['search'];
    
    // Sanitize the input to prevent SQL injection
    $search_term = mysqli_real_escape_string($conn, $search_term);
    
    // Query database for matching results
    $query = "SELECT * FROM users WHERE name LIKE '%" . $search_term . "%'";
    
    // Execute the query
    $result = mysqli_query($conn, $query);
    
    // Check if query was successful
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    // Display results
    echo "<h2>Search Results:</h2>";
    if (mysqli_num_rows($result) > 0) {
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>" . $row['name'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No results found.";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!-- Search form -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="text" name="search" placeholder="Search...">
    <input type="submit" name="submit" value="Search">
</form>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-form {
            display: flex;
            gap: 10px;
        }
        
        .search-form input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .search-form input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .search-form input[type="submit"]:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 10px;
        }
        
        .results li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Your existing PHP code here...
        ?>

        <!-- Search form -->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="search-form">
            <input type="text" name="search" placeholder="Search...">
            <input type="submit" name="submit" value="Search">
        </form>

        <?php
        // Display results in a styled container
        if (isset($_POST['submit'])) {
            echo "<div class='results'>";
            // Your existing result display code here...
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<?php
// Connect to database
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'your_database';

$conn = mysqli_connect($host, $username, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search query from form
$search_query = $_POST['search_query'];

// Sanitize the input to prevent SQL injection
$search_query = mysqli_real_escape_string($conn, $search_query);

// Query database
$sql = "SELECT * FROM users WHERE first_name LIKE '%$search_query%' 
        OR last_name LIKE '%$search_query%'
        OR email LIKE '%$search_query%'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}

// Display results
echo "<h2>Search Results</h2>";
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div style='border-bottom: 1px solid #ddd; padding: 15px 0;'>";
        echo "<h3>" . $row['first_name'] . " " . $row['last_name'] . "</h3>";
        echo "<p>Email: " . $row['email'] . "</p>";
        echo "<a href='view_user.php?id=" . $row['id'] . "' style='color: #4CAF50; text-decoration: none;'>View Profile</a>";
        echo "</div>";
    }
} else {
    echo "<p>No results found.</p>";
}

// Close database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Include Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-4">Search Page</h2>
    
    <!-- Search Form -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="input-group mb-3">
            <input type="text" name="search_term" class="form-control" placeholder="Enter search term...">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Search Results -->
    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search_term = $_POST['search_term'];
        
        // Connect to database
        $dbhost = 'localhost';
        $dbuser = 'username';
        $dbpass = 'password';
        $dbname = 'database_name';

        $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Escape the search term to prevent SQL injection
        $search_term = $conn->real_escape_string($search_term);

        // Search query
        $sql = "SELECT * FROM your_table_name 
                WHERE name LIKE '%{$search_term}%' 
                OR description LIKE '%{$search_term}%'";
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table class='table table-striped'>";
            echo "<tr><th>Name</th><th>Description</th><th>Actions</th></tr>";
            
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "<td>
                        <a href='edit.php?id=" . $row['id'] . "' class='btn btn-success btn-sm'>Edit</a> 
                        <a href='delete.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                     </td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='alert alert-info'>";
            echo "No results found!";
            echo "</div>";
        }

        // Close database connection
        $conn->close();
    } else {
        // If form is not submitted, show welcome message
        echo "<p class='text-muted'>Enter a search term above to find your items!</p>";
    }
    ?>

</div>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .results {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="search" class="search-bar" placeholder="Search...">
            <input type="submit" value="Search" style="padding: 10px; font-size: 16px;">
        </form>

        <?php
        // Check if the form has been submitted
        if (isset($_GET['search'])) {
            $search_term = $_GET['search'];
            
            // Sanitize the input to prevent SQL injection or XSS attacks
            $search_term = htmlspecialchars($search_term, ENT_QUOTES, 'UTF-8');
            
            echo "<div class='results'>";
            echo "<h3>Search Results for: '$search_term'</h3>";
            
            // Here you would typically connect to your database and query the data
            // For this example, we'll use some sample data
            $sample_data = array(
                "Apple",
                "Banana",
                "Cherry",
                "Orange",
                "Grapes",
                "Mango"
            );
            
            $results = array();
            
            foreach ($sample_data as $item) {
                if (stripos($item, $search_term) !== false) {
                    $results[] = $item;
                }
            }
            
            if (!empty($results)) {
                echo "<ul>";
                foreach ($results as $result) {
                    echo "<li>$result</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No results found for '$search_term'</p>";
            }
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<?php
if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    echo "You searched for: " . htmlspecialchars($searchQuery);
} else {
    echo "Please enter your search term.";
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
    <style>
        .search-box {
            width: 300px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin: 20px auto;
        }
        
        .search-input {
            width: 80%;
            padding: 5px;
            border: none;
            border-radius: 3px;
            font-size: 16px;
        }
        
        .search-button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php
    // Check if search form is submitted
    if (isset($_POST['submit'])) {
        $searchQuery = $_POST['search'];
        
        // Database connection details
        $dbHost = 'localhost';
        $dbUser = 'username';
        $dbPass = 'password';
        $dbName = 'database_name';
        
        // Connect to database
        $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Escape the search query to prevent SQL injection
        $searchQuery = $conn->real_escape_string($searchQuery);
        
        // SQL query to search for matching records
        $sql = "SELECT * FROM your_table_name 
                WHERE column_name LIKE '%{$searchQuery}%' 
                LIMIT 0, 10";
        
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            echo "<h3>Search Results:</h3>";
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li><a href='details.php?id=" . $row['id'] . "'>";
                echo $row['column_name'];
                echo "</a></li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No results found.</p>";
        }
        
        // Close database connection
        $conn->close();
    }
    ?>
    
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="search-box">
            <input type="text" name="search" class="search-input" placeholder="Search...">
            <button type="submit" name="submit" class="search-button">Search</button>
        </div>
    </form>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex-grow: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Check if form is submitted
        $searchTerm = "";
        $results = [];
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchTerm = $_POST['search'];
            
            // Database connection
            $dbHost = 'localhost';
            $dbName = 'your_database';
            $dbUser = 'username';
            $dbPass = 'password';
            
            $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
            
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // Sanitize input
            $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
            
            // Query the database
            $sql = "SELECT * FROM your_table WHERE field LIKE '%" . $searchTerm . "%'";
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Display results
                    echo "<div class='results'>";
                    echo "<p>" . $row['field'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<div class='results'>";
                echo "<p>No results found.</p>";
                echo "</div>";
            }
            
            // Close database connection
            mysqli_close($conn);
        }
        ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="search-form">
            <input type="text" name="search" value="<?php echo $searchTerm; ?>" placeholder="Search..." class="search-input">
            <button type="submit" name="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-container form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-container input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-container button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
            // Check if form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $query = $_POST['search'];

                // Clean and validate input
                function test_input($data) {
                    $data = trim($data);
                    $data = stripslashes($data);
                    $data = htmlspecialchars($data);
                    return $data;
                }

                $search_query = test_input($query);

                if (empty($search_query)) {
                    echo "<p style='color:red;'>Please enter a search query!</p>";
                } else {
                    // Simulate database results
                    // In a real application, you would connect to your database here
                    $results = array(
                        "John Doe",
                        "Jane Smith",
                        "Bob Johnson",
                        "Alice Williams"
                    );

                    $output = "<h3>Search Results:</h3>";
                    $found = 0;

                    foreach ($results as $result) {
                        if (strpos(strtolower($result), strtolower($search_query)) !== false) {
                            $output .= "<p>".$result."</p>";
                            $found++;
                        }
                    }

                    if ($found > 0) {
                        echo $output;
                    } else {
                        echo "<p>No results found for: " . $search_query . "</p>";
                    }
                }
            } else {
                // Show the search form
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search" placeholder="Search here..." required>
            <button type="submit">Search</button>
        </form>
        <?php } ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <!-- Add some basic styling -->
    <style>
        .search-box {
            width: 300px;
            padding: 10px;
            margin: 50px auto;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="text"] {
            width: 80%;
            padding: 5px;
            font-size: 16px;
        }
        button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
            padding: 10px;
        }
    </style>
</head>
<body>

<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'test';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['search'])) {
    // Get search term
    $search_term = mysqli_real_escape_string($conn, $_POST['query']);

    // Search in database table
    $sql = "SELECT * FROM users WHERE name LIKE '%$search_term%' OR email LIKE '%$search_term%'";
    $result = mysqli_query($conn, $sql);

    // Display results
    if (mysqli_num_rows($result) > 0) {
        echo "<div class='results'>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>Email: " . $row['email'] . "</p>";
            echo "<hr>";
        }
        echo "</div>";
    } else {
        echo "<div class='results'>";
        echo "<p>No results found.</p>";
        echo "</div>";
    }
}
?>

<!-- Search form -->
<div class="search-box">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="query" placeholder="Search..." required>
        <button type="submit" name="search">Search</button>
    </form>
</div>

</body>
</html>


// Using Prepared Statements
$stmt = $conn->prepare("SELECT * FROM users WHERE name LIKE ? OR email LIKE ?");
$search_term = '%' . $search_term . '%';
$stmt->bind_param("ss", $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add CSS styling -->
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-form input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .search-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-form button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            background-color: white;
            cursor: pointer;
        }

        .result-item:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <h2>Search</h2>
        
        <?php
        // Check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchQuery = $_POST['query'];
            
            // Sanitize the input
            $searchQuery = htmlspecialchars($searchQuery);

            // Database connection details
            $host = 'localhost';
            $dbUsername = 'root';  // Replace with your database username
            $dbPassword = '';      // Replace with your database password
            $dbName = 'test_db';    // Replace with your database name
            
            // Connect to database
            $conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // SQL query to search for matching records
            $sql = "SELECT * FROM your_table WHERE column_name LIKE ?";
            
            $stmt = mysqli_prepare($conn, $sql);
            $searchQuery = "%$searchQuery%";
            mysqli_stmt_bind_param($stmt, "s", $searchQuery);

            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Display results
            echo "<div class='results'>";
            
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='result-item'>";
                    foreach ($row as $key => $value) {
                        echo "$key: $value <br>";
                    }
                    echo "</div>";
                }
            } else {
                echo "No results found.";
            }

            echo "</div>";

            // Close database connection
            mysqli_close($conn);
        }
        ?>

        <!-- Search form -->
        <form class="search-form" method="post">
            <input type="text" name="query" placeholder="Enter search query..." required>
            <button type="submit">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Check if a query was submitted
if (isset($_GET['query'])) {
    $query = $_GET['query'];
    
    // Sanitize the input to prevent SQL injection
    $query = htmlspecialchars($query);
    $query = trim($query);

    // Database connection details
    $host = 'localhost';
    $username = 'root';  // Change to your database username
    $password = '';      // Change to your database password
    $database = 'search_db';  // Change to your database name

    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM your_table WHERE column_name LIKE ?");
    
    // Bind the parameter with wildcards
    $search_term = "%" . $query . "%";
    $stmt->bind_param("s", $search_term);

    // Execute the query
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Display the results
    if ($result->num_rows > 0) {
        echo "<div class='results'>";
        while ($row = $result->fetch_assoc()) {
            // Output each row's data
            echo "<p>" . htmlspecialchars($row['column_name']) . "</p>";
        }
        echo "</div>";
    } else {
        echo "<div class='results'>";
        echo "<p>No results found.</p>";
        echo "</div>";
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
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        input[type="text"] {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Check if the form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search_term = $_POST['search_term'];
            
            // Sanitize and validate input
            if (empty($search_term)) {
                echo "<p style='color: red;'>Please enter a search term.</p>";
            } else {
                $search_term = trim($search_term);
                $search_term = htmlspecialchars($search_term, ENT_QUOTES, 'UTF-8');
                
                // Here you would typically connect to your database and query the results
                // For this example, we'll just display the search term
                echo "<h3>Search Results for: '$search_term'</h3>";
                echo "<div class='results'>";
                // Add your database query logic here and display results
                echo "</div>";
            }
        } else {
            // Display search form
            echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            echo "<div class='search-container'>";
            echo "<input type='text' name='search_term' placeholder='Search here...'>";
            echo "<button type='submit'>Search</button>";
            echo "</div>";
            echo "</form>";
        }
        ?>
    </div>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search query logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = $_POST['search_term'];
    
    if (empty($search_term)) {
        echo "<p style='color: red;'>Please enter a search term.</p>";
    } else {
        // Escape the search term to prevent SQL injection
        $search_term = $conn->real_escape_string(trim($search_term));
        
        // Query the database
        $sql = "SELECT * FROM your_table WHERE column_name LIKE '%{$search_term}%'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            echo "<h3>Search Results for: '$search_term'</h3>";
            while ($row = $result->fetch_assoc()) {
                // Display your results here
                echo "<div class='result'>";
                echo "<p>" . htmlspecialchars($row['column_name']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No results found for '$search_term'.<p>";
        }
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
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
            if (isset($_GET['search'])) {
                $query = $_GET['search'];
                // Process the search query here
                echo "<h3>Search Results for: " . htmlspecialchars($query) . "</h3>";
                // Add your database query or other search logic here
                // For this example, we'll just display a placeholder message
                echo "<div class='results'>";
                echo "<p>No results found. Implement your search functionality below.</p>";
                echo "</div>";
            } else {
                echo "<h2>Search Bar</h2>";
            }
        ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
            <input type="text" class="search-bar" name="search" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .search-container {
            text-align: center;
            margin-top: 100px;
        }
        .search-form {
            display: inline-block;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"] {
            width: 300px;
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: white;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="search-form">
            <input type="text" name="search_query" placeholder="Search here...">
            <button type="submit">Search</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get search term from form
            $search_term = $_POST['search_query'];

            // Sanitize the input
            $search_term = htmlspecialchars($search_term);
            $search_term = trim($search_term);

            // Database connection
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'my_database';

            // Connect to database
            $conn = mysqli_connect($host, $username, $password, $database);

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // SQL query
            $sql = "SELECT * FROM users WHERE name LIKE '%" . $search_term . "%'";
            
            $result = mysqli_query($conn, $sql);

            if ($result === false) {
                die("Query failed: " . mysqli_error($conn));
            }

            if (mysqli_num_rows($result) > 0) {
                echo "<div class='results'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<p>" . $row['name'] . "</p>";
                }
                echo "</div>";
            } else {
                echo "<div class='results'>";
                echo "No results found for: " . $search_term;
                echo "</div>";
            }

            // Close database connection
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        /* Basic styling for the search bar */
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            padding: 10px 20px;
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
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="search" class="search-bar" placeholder="Search here...">
            <button type="submit" class="search-button">Search</button>
        </form>
        
        <?php
        // Check if search parameter is set
        if (isset($_GET['search'])) {
            $searchTerm = $_GET['search'];
            
            // Display search results or perform database query here
            echo "<h3>Search Results for: '" . htmlspecialchars($searchTerm) . "'</h3>";
            
            // Example of displaying sample data
            $results = array(
                "Result 1 related to your search",
                "Result 2 related to your search",
                "Result 3 related to your search"
            );
            
            if (!empty($results)) {
                foreach ($results as $result) {
                    echo "<p>" . htmlspecialchars($result) . "</p>";
                }
            } else {
                echo "<p>No results found.</p>";
            }
        }
        ?>
    </div>
</body>
</html>


// Database connection details
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to search database
$sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $searchQuery . "%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<div class="result-item">' . $row["column_name"] . '</div>';
    }
} else {
    echo '<p>No results found.</p>';
}

// Close connection
$conn->close();


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="search" class="search-box" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search = $_POST['search'];

            // Database connection details
            $host = 'localhost';
            $dbUsername = 'root';
            $dbPassword = '';
            $dbName = 'test';

            // Connect to database
            $conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);

            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Sanitize input to prevent SQL injection
            $search = mysqli_real_escape_string($conn, $search);

            // Search query
            $sql = "SELECT * FROM your_table WHERE 
                    column1 LIKE '%" . $search . "%' OR 
                    column2 LIKE '%" . $search . "%'";
                    
            // Limit the number of results
            $sql .= " LIMIT 10";

            $result = mysqli_query($conn, $sql);

            // Check if any results were found
            if (mysqli_num_rows($result) > 0) {
                echo "<div class='results'>";
                echo "<table>";
                echo "<tr><th>ID</th><th>Name</th></tr>";
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</div>";
            } else {
                // No results found
                echo "<div class='results'>";
                echo "No results found. Try a different search term.";
                echo "</div>";
            }

            // Close database connection
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-input {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search_query = $_POST['search_query'];
            
            // Process search query and display results
            echo "<h3>Search Results:</h3>";
            echo "<div class='results'>";
            
            // For this example, we'll use a simple array of items
            $items = array(
                "apple", "banana", "orange", "grape", 
                "mango", "strawberry", "blueberry", "kiwi"
            );
            
            // Search processing function
            function searchItems($query, $items) {
                $results = array();
                foreach ($items as $item) {
                    if (stripos($item, $query) !== false) {
                        $results[] = $item;
                    }
                }
                return $results;
            }
            
            $search_results = searchItems($search_query, $items);
            
            if (!empty($search_results)) {
                echo "<ul>";
                foreach ($search_results as $result) {
                    echo "<li>" . $result . "</li>";
                }
                echo "</ul>";
            } else {
                echo "No results found for: " . $search_query;
            }
            
            echo "</div>";
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search_query" class="search-input"
                   placeholder="Search..." required>
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Connect to the database
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the search query from form submission
$query = $_POST['query'];

// Sanitize the input to prevent SQL injection
$sanitized_query = mysqli_real_escape_string($conn, trim(htmlspecialchars($query)));

// Search in the database
$sql = "SELECT * FROM users WHERE name LIKE '%$sanitized_query%'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}

// Display the results
echo "<h2>Search Results</h2>";
echo "<ul>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<li>" . $row['name'] . "</li>";
}

echo "</ul>";

// Close the database connection
mysqli_close($conn);
?>


<?php
// This script handles the search functionality

// Database connection details
$host = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "test_db"; // Replace with your database name

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Search query
if (isset($_POST['search'])) {
    $search_query = validateInput($_POST['search']);

    // SQL query to search for the keyword in your database table
    $sql = "SELECT * FROM your_table_name WHERE column1 LIKE '%" . $search_query . "%' 
            OR column2 LIKE '%" . $search_query . "%'";
            
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Display the results
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<h3>" . $row['title'] . "</h3>";
            echo "<p>" . $row['description'] . "</p>";
            // Add more fields as needed
        }
    } else {
        echo "No results found!";
    }
}

// Function to validate input
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search" class="search-bar" placeholder="Search...">
            <br><br>
            <input type="submit" value="Search" class="search-button">
        </form>
    </div>

    <?php
    // Display any search results here if needed
    ?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
        }
        
        .result-item {
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            background-color: white;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form class="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" class="search-input" name="query" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search_query = htmlspecialchars(trim($_POST['query']));
            
            // Database connection details
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'my_database';
            
            // Connect to database
            $conn = mysqli_connect($host, $username, $password, $database);
            
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // SQL query to search for the term
            $sql = "SELECT * FROM products WHERE name LIKE '%{$search_query}%' 
                    OR description LIKE '%{$search_query}%'";
            
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                echo "<div class='results'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='result-item'>";
                    echo "<h3>" . $row['name'] . "</h3>";
                    echo "<p>" . $row['description'] . "</p>";
                    echo "<p>Price: $" . $row['price'] . "</p>";
                    echo "<a href='edit.php?id=" . $row['id'] . "'>Edit</a> | ";
                    echo "<a href='delete.php?id=" . $row['id'] . "'>Delete</a>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p>No results found.</p>";
            }
            
            mysqli_close($conn);
        } elseif (!empty($_POST['query'])) {
            // If query is empty
            echo "<p>Please enter a search term.</p>";
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
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="search.php" method="post">
            <input type="text" name="query" placeholder="Search here..." class="search-box">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>

<?php
// This is search.php

// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to MySQL database
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term from form
$search_term = $_POST['query'];
$trimmed_search = trim($search_term);
if (empty($trimmed_search)) {
    // Redirect back to search page if no query is entered
    header("Location: index.php");
    exit();
}

// Escape special characters to prevent SQL injection
$search_term_escaped = mysqli_real_escape_string($conn, $search_term);

// Search in the database
$sql = "SELECT * FROM your_table_name 
        WHERE title LIKE '%$search_term_escaped%' 
        OR content LIKE '%$search_term_escaped%'";
$result = mysqli_query($conn, $sql);

if ($result) {
    // Display search results
    echo "<div class='results'>";
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Output the data you want to display
            echo "<h3>" . $row['title'] . "</h3>";
            echo "<p>" . substr($row['content'], 0, 200) . "...</p><br/>";
        }
    } else {
        echo "No results found.";
    }
    
    echo "</div>";
} else {
    // Display error message if query fails
    echo "Error: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-container h2 {
            text-align: center;
            color: #333;
        }

        .search-form {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .search-form input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <h2>Search Records</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="search-form">
            <input type="text" name="search_term" placeholder="Enter search term...">
            <button type="submit">Search</button>
        </form>

        <?php
        // Database connection details
        $host = "localhost";
        $db_username = "root";  // Replace with your database username
        $db_password = "";      // Replace with your database password
        $database_name = "test_db";  // Replace with your database name

        // Connect to the database
        $connection = mysqli_connect($host, $db_username, $db_password, $database_name);

        if (mysqli_connect_errno()) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check if search term is provided
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search_term = mysqli_real_escape_string($connection, $_POST['search_term']);

            // SQL query to search for the term in relevant columns
            $sql = "SELECT * FROM your_table_name 
                    WHERE column1 LIKE '%{$search_term}%' 
                    OR column2 LIKE '%{$search_term}%'";
            
            if ($result = mysqli_query($connection, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    echo "<div class=\"results\">";
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Display your results here
                        echo "<div class=\"result-item\">";
                        echo "<h3>" . $row['column1'] . "</h3>";
                        echo "<p>" . $row['column2'] . "</p>";
                        echo "</div>";
                    }
                    echo "</div>";
                } else {
                    echo "<div class=\"results\"><p>No results found.</p></div>";
                }
            } else {
                echo "Error executing query: " . mysqli_error($connection);
            }

            // Free result set
            mysqli_free_result($result);
        }

        // Close database connection
        mysqli_close($connection);
        ?>

    </div>
</body>
</html>


// Database connection
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... sanitization code from above ...

    $sql = "SELECT * FROM your_table WHERE column LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    $search_term = '%' . $search_term . '%';
    mysqli_stmt_bind_param($stmt, "s", $search_term);

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Display results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h3>" . $row['title'] . "</h3>";
            echo "<p>" . $row['description'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "No results found.";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);


<?php
// Connect to the database
$host = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search term from form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = $_POST['search'];
    
    // Sanitize the input to prevent SQL injection
    $search_term = mysqli_real_escape_string($conn, htmlspecialchars(trim($search_term)));

    if ($search_term != "") {
        // Search query
        $sql = "SELECT * FROM your_table WHERE 
                column1 LIKE ? OR 
                column2 LIKE ?";
        
        // Prepare statement
        $stmt = $conn->prepare($sql);
        $term = "%$search_term%";
        $stmt->bind_param("ss", $term, $term);
        
        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Display results
        while ($row = $result->fetch_assoc()) {
            echo "<div class='result'>";
            echo "ID: " . $row['id'] . "<br>";
            echo "Column1: " . $row['column1'] . "<br>";
            echo "Column2: " . $row['column2'] . "<br>";
            echo "</div>";
        }
    } else {
        echo "Please enter a search term.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-box {
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 70%;
            padding: 8px;
            font-size: 16px;
        }
        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .result {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h2>Search Bar</h2>
    
    <div class="search-box">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="text" name="search" placeholder="Enter search term...">
            <button type="submit">Search</button>
        </form>
    </div>

    <?php
    // Handle any potential errors and display them
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .results {
            list-style-type: none;
            padding: 0;
        }

        .results li {
            padding: 10px;
            margin-bottom: 5px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="search-box">
                <input type="text" name="query" placeholder="Search...">
                <button type="submit">Search</button>
            </div>
        </form>

        <?php
        // Database connection details
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'test';

        // Connect to database
        $conn = mysqli_connect($host, $username, $password, $database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize input
            $searchQuery = trim($_POST['query']);
            $searchQuery = htmlspecialchars($searchQuery);
            $searchQuery = mysqli_real_escape_string($conn, $searchQuery);

            // Check if search query is not empty
            if (!empty($searchQuery)) {
                // Query the database
                $sql = "SELECT * FROM your_table WHERE column LIKE ?";
                $stmt = mysqli_prepare($conn, $sql);
                $searchTerm = "%$searchQuery%";
                
                mysqli_stmt_bind_param($stmt, 's', $searchTerm);
                mysqli_stmt_execute($stmt);

                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    echo '<ul class="results">';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<li>' . $row['column'] . '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>No results found.</p>';
                }
            }
        }

        // Close database connection
        mysqli_close($conn);
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <!-- Add some basic CSS styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search form -->
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search" class="search-box" placeholder="Search...">
            <input type="submit" value="Search" class="search-button">
        </form>
        
        <?php
        // Check if search parameter is set
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            
            // Database connection details
            $host = 'localhost';
            $username = 'root'; // replace with your database username
            $password = '';     // replace with your database password
            $database = 'mydatabase'; // replace with your database name
            
            // Connect to MySQL database
            $conn = mysqli_connect($host, $username, $password, $database);
            
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // Sanitize the search input
            $search = mysqli_real_escape_string($conn, $search);
            
            // SQL query to fetch data from database
            $sql = "
                SELECT * FROM users 
                WHERE CONCAT_WS(' ', first_name, last_name, email) 
                LIKE '%{$search}%'
            ";
            
            // Execute the query
            $result = mysqli_query($conn, $sql);
            
            // Check if any results were found
            if (mysqli_num_rows($result) > 0) {
                echo "<div class='results'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    // Display search results
                    echo "<p>Name: " . $row['first_name'] . " " . $row['last_name'] . "</p>";
                    echo "<p>Email: " . $row['email'] . "</p>";
                    echo "<hr>";
                }
                echo "</div>";
            } else {
                // No results found
                echo "<div class='results'>";
                echo "<p>No results found for '" . $search . "'</p>";
                echo "</div>";
            }
            
            // Close database connection
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        
        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .search-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="query" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
        
        <?php
        // Display search results if query is provided
        if (isset($_GET['query'])) {
            $searchQuery = $_GET['query'];
            
            // Simulate database connection and search
            // In a real application, you would connect to your database here
            $results = array(
                "Related Result 1",
                "Related Result 2",
                "Matching Item",
                "Search Query Found"
            );
            
            echo "<div class='results'>";
            if (!empty($results)) {
                foreach ($results as $result) {
                    echo "<p>$result</p>";
                }
            } else {
                echo "<p>No results found for '$searchQuery'</p>";
            }
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .search-results {
            background-color: white;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        .result-item {
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php
    // Initialize session to store search history
    session_start();

    $searchQuery = "";
    $results = array();
    
    if (isset($_SESSION['search_history'])) {
        $searchHistory = $_SESSION['search_history'];
    } else {
        $searchHistory = array();
    }

    // Handle form submission
    if (isset($_POST['submit'])) {
        $searchQuery = trim($_POST['search']);
        
        if (!empty($searchQuery)) {
            // Add to search history
            array_unshift($searchHistory, $searchQuery);
            $_SESSION['search_history'] = array_slice($searchHistory, 0, 10); // Keep only last 10 searches
            
            // Simulated data - replace this with actual database query
            $data = array(
                "Apple", "Banana", "Cherry", "Date", "Elderberry",
                "Fig", "Grape", "Honeydew", "Kiwi", "Lemon", "Mango"
            );
            
            // Search through data and display results
            foreach ($data as $item) {
                if (stripos($item, $searchQuery) !== false) {
                    $results[] = $item;
                }
            }
        }
    }

    // Display search bar
    ?>
    
    <div class="search-container">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search" class="search-bar" 
                   placeholder="Search for fruits..." 
                   value="<?php if (isset($_POST['search'])) { echo $searchQuery; } ?>">
            <button type="submit" name="submit">Search</button>
        </form>

        <?php
        // Display search results
        if (!empty($results)) {
            echo "<div class='search-results'>";
            foreach ($results as $result) {
                echo "<div class='result-item'>" . $result . "</div>";
            }
            echo "</div>";
        }

        // Display search history
        if (!empty($searchHistory)) {
            echo "<h3>Search History:</h3>";
            foreach (array_slice($searchHistory, 0, 5) as $history) { // Show last 5 searches
                echo "<a href=\"#\" onclick=\"document.querySelector('input[name=search]').value='$history';\">$history</a><br>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f0f0f0;
        }
        
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-form {
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Database connection
        $db = new mysqli('localhost', 'username', 'password', 'database_name');

        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        // Check if the form has been submitted
        if (isset($_GET['query'])) {
            $search_query = $_GET['query'];
            
            // Sanitize the input to prevent SQL injection
            $search_query = mysqli_real_escape_string($db, $search_query);

            // Search query in database
            $sql = "SELECT * FROM products WHERE name LIKE '%{$search_query}%' OR description LIKE '%{$search_query}%'";
            $result = $db->query($sql);

            if ($result->num_rows > 0) {
                echo "<h2>Search Results</h2>";
                echo "<div class='results'>";
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='result-item'>";
                    echo "<h3>{$row['name']}</h3>";
                    echo "<p>{$row['description']}</p>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p>No results found for your query.</p>";
            }
        } else {
            // Show welcome message if no search has been performed
            echo "<h2>Welcome to Search Page</h2>";
        }

        $db->close();
        ?>

        <form class="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="query" placeholder="Search..." class="search-input" required>
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'search_db';

// Connect to database
$conn = mysqli_connect($host, $user, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term from form
$search_term = $_POST['search_term'];

// Sanitize the input
$search_term = mysqli_real_escape_string($conn, $search_term);

// Query to get results
$sql = "SELECT * FROM users WHERE first_name LIKE '%$search_term%' OR last_name LIKE '%$search_term%'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<h3>No results found!</h3>";
} else {
    echo "<div class='search-results'>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='result-item'>";
        echo "<p>First Name: " . $row['first_name'] . "</p>";
        echo "<p>Last Name: " . $row['last_name'] . "</p>";
        echo "</div>";
    }
    echo "</div>";
}

// Close database connection
mysqli_close($conn);

// Link to go back to search page
echo "<br><a href='search.php'>Back to Search</a>";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-container form {
            display: flex;
            gap: 10px;
        }
        
        .search-container input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .search-container button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-container button:hover {
            background-color: #45a049;
        }

        .result {
            margin-top: 20px;
            padding: 10px;
            background-color: #e8f5e9;
            border-radius: 4px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
                // Sanitize the input
                $searchTerm = htmlspecialchars(trim($_POST['search']));
                
                if (!empty($searchTerm)) {
                    echo "<div class='result'>";
                    echo "You searched for: <strong>{$searchTerm}</strong>";
                    echo "</div>";
                    
                    // Here you would typically connect to a database and perform the search
                    // This example only shows the basic implementation
                } else {
                    echo "<div style='color: red;'>Please enter something to search.</div>";
                }
            }
        ?>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="search" placeholder="Search here...">
            <button type="submit">Search</button>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>PHP Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-input {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php
        // Check if the form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
            $query = $_GET['query'];
            
            // Process the search query here
            echo "<h3>Your Search Query: " . htmlspecialchars($query) . "</h3>";
            // Add your search functionality below
            
        } elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['clear'])) {
            // Clear the search input field
            $query = "";
        }
    ?>
    
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
            <input type="text" name="query" placeholder="Enter your search..." 
                   class="search-input" value="<?php if (isset($query)) { echo $query; } ?>">
            <br><br>
            <button type="submit" name="search" class="search-button">Search</button>
            <button type="submit" name="clear" class="search-button">Clear</button>
        </form>
        
        <?php
            // Display message if search query is empty
            if (!isset($query) || $query == "") {
                echo "<p style='color: red; margin-top: 10px;'>Please enter a search term!</p>";
            }
        ?>
    </div>
</body>
</html>


<?php
// Connect to the database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get search term from form
    $search_term = $_POST['search'];

    // Sanitize the input to prevent SQL injection
    $search_term = trim($search_term); 
    $search_term = htmlspecialchars($search_term);
    $search_term = mysqli_real_escape_string($conn, $search_term);

    // Search query
    $sql = "SELECT * FROM products WHERE product_name LIKE '%".$search_term."%'";
    $result = $conn->query($sql);

    // Display results
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<p>" . $row['product_name'] . "</p>";
            echo "<p>" . $row['description'] . "</p>";
            echo "<hr>";
        }
    } else {
        echo "No results found.";
    }
}
?>

<!-- HTML search form -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit">Go</button>
</form>

<?php
$conn->close();
?>


<?php
// Database connection settings
$host = "localhost";
$username = "root";
$password = "";
$database_name = "your_database";

// Create connection
$conn = new mysqli($host, $username, $password, $database_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search term from form submission
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search_term'];
    
    // Escape special characters to prevent SQL injection
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    
    // Query database
    $query = "SELECT * FROM users WHERE name LIKE '%$searchTerm%'";
    $result = $conn->query($query);
    
    // Display search results
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No results found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        input[type="text"] {
            padding: 8px;
            width: 70%;
        }
        
        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Search Users</h2>
    
    <!-- Search form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="search-container">
            <input type="text" name="search_term" placeholder="Enter search term...">
            <button type="submit" name="search">Search</button>
        </div>
    </form>

    <?php
    // Close database connection
    $conn->close();
    ?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        
        .search-box {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'search_db';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get search term from form
    $search_term = $_POST['search'];
    
    // Validate input
    $search_term = trim($search_term);
    $search_term = mysqli_real_escape_string($conn, $search_term);
    
    if ($search_term != "") {
        // SQL query to search for the term in the database
        $sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $search_term . "%' LIMIT 10";
        
        // Execute the query
        $result = $conn->query($sql);
        
        // Check if results were found
        if ($result->num_rows > 0) {
            // Display the results
            while($row = $result->fetch_assoc()) {
                echo "<div class='results'>";
                echo "<p>" . $row['column_name'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<div class='results'>";
            echo "<p>No results found.</p>";
            echo "</div>";
        }
    }
}
?>

<!-- Search form -->
<div class="search-container">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" name="search" class="search-box" placeholder="Search...">
        <button type="submit" class="search-button">Search</button>
    </form>
</div>

<?php
// Close database connection
$conn->close();
?>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
</head>
<body>

<?php

// Check if form is submitted
if (isset($_GET['search'])) {
    $searchTerm = $_GET['query'];
    
    // Sanitize the input
    $searchTerm = htmlspecialchars($searchTerm, ENT_QUOTES);
    
    // Process the search query and fetch results from database
    // This part depends on your database structure and what you're searching for
    
    echo "<h2>Search Results</h2>";
    // Display your search results here based on $searchTerm
    
}

?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
    <input type="text" name="query" placeholder="Enter search term...">
    <input type="submit" name="search" value="Go">
</form>

<div id="results">
    <!-- Search results will be displayed here -->
    <?php 
        // This is where you would display your actual search results
        if (isset($_GET['search'])) {
            echo "<p>Searching for: " . $searchTerm . "</p>";
            // Add your database query and result display logic here
        }
    ?>
</div>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-box {
            display: flex;
            align-items: center;
        }
        #searchInput {
            width: 80%;
            padding: 10px;
            font-size: 16px;
        }
        #searchButton {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #searchButton:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="search-box">
                <input type="text" id="searchInput" name="query" placeholder="Search here...">
                <button type="submit" id="searchButton">Search</button>
            </div>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $query = isset($_GET['query']) ? $_GET['query'] : '';
            
            // Sanitize input to prevent SQL injection
            $search = htmlspecialchars($query);
            
            // Database connection
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'your_database';

            $conn = new mysqli($host, $username, $password, $database);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Search query
            $sql = "
                SELECT *
                FROM your_table
                WHERE column1 LIKE '%$search%'
                OR column2 LIKE '%$search%'
            ";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<div class='results'>";
                while ($row = $result->fetch_assoc()) {
                    // Display your results here
                    echo "<p>Result: " . $row['column1'] . "</p>";
                }
                echo "</div>";
            } else {
                echo "<div class='results'><p>No results found.</p></div>";
            }

            $conn->close();
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        input[type="text"] {
            flex: 1;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php
    // Connect to the database
    $conn = mysqli_connect("localhost", "username", "password", "database_name");
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (isset($_POST['submit'])) {
        // Get search term
        $search_term = $_POST['search'];
        
        // Sanitize the input
        $search_term = htmlspecialchars($search_term);
        
        // SQL query to search for the term in the database
        $sql = "SELECT * FROM table_name WHERE name LIKE ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt === false) {
            die("Error in statement preparation: " . mysqli_error($conn));
        }
        
        // Bind parameter and execute
        mysqli_stmt_bind_param($stmt, "s", $search_term);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result->num_rows > 0) {
            echo "<h3>Search Results:</h3>";
            echo "<table border='1'>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row['name'] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No results found.</p>";
        }
    } else {
        // Display the search form
        ?>
        <div class="search-form">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="text" name="search" placeholder="Search here..." required>
                <button type="submit" name="submit">Search</button>
            </form>
        </div>
        <?php
    }
    
    // Close the database connection
    mysqli_close($conn);
    ?>
    <p><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Perform a new search</a></p>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-input {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            margin-right: 10px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="query" placeholder="Search here..." class="search-input">
            <input type="submit" name="search" value="Search" class="search-button">
        </form>

        <?php
        // Check if form is submitted
        if (isset($_POST['search'])) {
            $searchQuery = $_POST['query'];

            // Display search results
            echo "<div class='results'>";
            echo "<h3>Search Results for: '$searchQuery'</h3>";
            
            // Here you would typically connect to your database and fetch results
            // For this example, we'll just display a message
            
            echo "<p>No results found. You can implement database connection here.</p>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="search.php" method="POST">
            <input type="text" name="search_query" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Connect to database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'my_database';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search query from POST request
$search_query = isset($_POST['search_query']) ? $_POST['search_query'] : '';

// Sanitize input to prevent SQL injection
$search_query = htmlspecialchars($search_query);
$search_query = trim($search_query);

// Prepare and execute the database query
$sql = "SELECT * FROM users WHERE first_name LIKE ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $search_query);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    // Output the results
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<h3>" . $row['first_name'] . " " . $row['last_name'] . "</h3>";
        echo "<p>Email: " . $row['email'] . "</p>";
        echo "<hr>";
    }
} else {
    // No results found
    echo "<p>No results found for '" . $search_query . "'</p>";
}

// Close database connection
mysqli_close($conn);
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .search-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 500px;
        }

        .search-form {
            display: flex;
        }

        #searchInput {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        #searchButton {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #searchButton:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="search.php" method="POST" class="search-form">
            <input type="text" id="searchInput" name="query" placeholder="Search...">
            <button type="submit" id="searchButton">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Handle the search query
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = $_POST['query'];
    
    // Sanitize input
    $query = trim($query);
    $query = mysqli_real_escape_string($conn, $query);

    // Database connection
    $host = 'localhost';
    $username = 'your_username';
    $password = 'your_password';
    $database = 'your_database';

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Search query
    $sql = "SELECT * FROM your_table WHERE column_name LIKE ?";
    
    $stmt = $conn->prepare($sql);
    $search_term = "%" . $query . "%";
    $stmt->bind_param('s', $search_term);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;

        echo "<h3>Results:</h3>";
        
        if ($num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Display your results here
                echo "<div class='result'>";
                echo "<a href='view.php?id=" . $row['id'] . "'>" . $row['column_name'] . "</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No results found.</p>";
        }
    }

    // Close database connection
    $conn->close();
}
?>


<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = $_POST['query'];

    // Sanitize input
    $query = htmlspecialchars($query);
    
    // Validate input (you can add more validation as needed)
    if ($query != "" && strlen($query) >= 2) {

        // Database connection parameters
        $host = "localhost";
        $db_username = "root"; // Change this to your database username
        $db_password = "";     // Change this to your database password
        $database = "my_database"; // Change this to your database name

        // Connect to database
        $conn = mysqli_connect($host, $db_username, $db_password, $database);
        
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // SQL query with search terms
        $sql = "SELECT * FROM your_table WHERE 
                first_name LIKE '%" . $query . "%' 
                OR last_name LIKE '%" . $query . "%'";
        
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Display results
            echo "<h3>Search Results:</h3>";
            echo "<table border='1'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['first_name'] . "</td>";
                echo "<td>" . $row['last_name'] . "</td>";
                // Add more columns as needed
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No results found.";
        }

        // Close database connection
        mysqli_close($conn);
    } else {
        // Empty or invalid query
        echo "Please enter a valid search term.";
    }
} else {
    // If form wasn't submitted, show instructions
    echo "Please enter your search term and click Search.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
</head>
<body>

<?php
// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get the search term from the query parameter
    $searchTerm = isset($_GET['query']) ? $_GET['query'] : '';

    // Connect to the database
    $host = 'localhost';
    $username = 'your_username';
    $password = 'your_password';
    $database = 'your_database';

    $connection = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Prepare the SQL query with a LIKE condition for partial matches
    $searchTerm = $connection->real_escape_string($searchTerm);
    
    // Assuming you have a table named 'users' with columns 'first_name' and 'last_name'
    $query = "SELECT * FROM users WHERE first_name LIKE '%" . $searchTerm . "%' OR last_name LIKE '%" . $searchTerm . "%'";
    $result = $connection->query($query);

    // Display the results
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>First Name</th><th>Last Name</th></tr>";
        
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['first_name'] . "</td>";
            echo "<td>" . $row['last_name'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "No results found.";
    }

    // Close the database connection
    $connection->close();
}
?>

<!-- Search Form -->
<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="text" name="query" placeholder="Search...">
    <button type="submit">Search</button>
</form>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add CSS for styling -->
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }
        
        .search-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="query" class="search-bar" placeholder="Search here...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchQuery = $_POST['query'];

            // Sanitize input to prevent SQL injection and XSS attacks
            $searchQuery = htmlspecialchars($searchQuery);
            $searchQuery = trim($searchQuery);

            // Connect to database
            try {
                $conn = new mysqli("localhost", "username", "password", "database_name");
                
                if ($conn->connect_error) {
                    throw new Exception("Connection failed: " . $conn->connect_error);
                }

                // Prepare SQL statement with prepared statements
                $stmt = $conn->prepare("SELECT * FROM your_table WHERE column_name LIKE ?");
                $searchTerm = "%" . $searchQuery . "%";
                $stmt->bind_param("s", $searchTerm);

                $stmt->execute();
                $result = $stmt->get_result();

                // Display search results
                if ($result->num_rows > 0) {
                    echo "<h3>Search Results:</h3>";
                    while ($row = $result->fetch_assoc()) {
                        // Display your data here
                        echo "<p>" . $row['column_name'] . "</p>";
                    }
                } else {
                    echo "No results found.";
                }

                // Close database connection
                $stmt->close();
                $conn->close();
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            display: flex;
            gap: 10px;
            width: 100%;
        }

        .search-input {
            flex-grow: 1;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="search-bar">
                <input type="text" name="search_query" class="search-input" placeholder="Search...">
                <button type="submit" class="search-button">Search</button>
            </div>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchQuery = $_POST['search_query'];
            
            // Here you would typically connect to your database and perform a search query
            // For this example, we'll just display the search results section
            echo "<div class='results'>";
            echo "<p>Your search for: <strong>" . htmlspecialchars($searchQuery) . "</strong></p>";
            echo "</div>";
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
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        
        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .search-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Check if the form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchTerm = $_POST['search'];
            
            // Validate input
            if (empty($searchTerm)) {
                echo "<p style='color: red'>Please enter a search term.</p>";
            } else {
                // Here you would typically connect to your database and perform the search query
                echo "<p>You searched for: " . htmlspecialchars($searchTerm) . "</p>";
            }
        }
        ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Connect to database if needed
// $conn = mysqli_connect("localhost", "username", "password", "database_name");

if (isset($_GET['search'])) {
    $search_query = trim($_GET['search']); // Get and trim the search query

    // Here you can add your own search logic
    // For example, querying a database or searching through an array of data

    echo "<h3>Search Results:</h3>";
    echo "<p>You searched for: " . htmlspecialchars($search_query) . "</p>";

    // Example search logic:
    if ($search_query == 'php') {
        echo "<p>Found PHP results!</p>";
    } elseif ($search_query == 'html') {
        echo "<p>Found HTML results!</p>";
    } else {
        echo "<p>No results found for your query.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
        }
        
        .search-form input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .search-form input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-form input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search here...">
            <input type="submit" value="Search">
        </form>
    </div>
</body>
</html>


$escaped_query = mysqli_real_escape_string($conn, $search_query);


// Example search logic:
if ($search_query == 'php') {
    echo "<p>Found PHP results!</p>";
} elseif ($search_query == 'html') {
    echo "<p>Found HTML results!</p>";
} else {
    echo "<p>No results found for your query.</p>";
}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-box {
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 16px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .search-results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Search Results</h1>

    <!-- Search Form -->
    <div class="search-box">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="search" placeholder="Enter search term...">
            <button type="submit" name="submit">Search</button>
        </form>
    </div>

    <?php
    // Database connection details
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "test_db";

    // Connect to database
    $conn = mysqli_connect($host, $user, $password, $database);

    // Check if form is submitted
    if (isset($_POST['submit'])) {
        // Get search term
        $search = $_POST['search'];
        
        // Sanitize input
        $search = mysqli_real_escape_string($conn, $search);
        
        // SQL query to fetch data based on search term
        $sql = "SELECT * FROM users WHERE name LIKE '%{$search}%'";
        $result = mysqli_query($conn, $sql);
        
        if (!$result) {
            die("Error: " . mysqli_error($conn));
        }
        
        // Display search results
        echo "<div class='search-results'>";
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>" . $row['name'] . "</p>";
            }
        } else {
            echo "No results found.";
        }
        echo "</div>";
    }
    
    // Close database connection
    mysqli_close($conn);
    ?>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        
        .search-box {
            width: 70%;
            padding: 10px;
            font-size: 16px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="search" class="search-box" placeholder="Search here...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

<?php
// Database connection details
$host = 'localhost';
$username = 'root'; // Replace with your database username
$password = '';      // Replace with your database password
$database = 'test_db'; // Replace with your database name

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    
    // Sanitize the input
    $searchQuery = htmlspecialchars(trim($searchQuery));
    
    // SQL query to search in the database
    $sql = "SELECT * FROM your_table_name WHERE column_name LIKE ?";
    
    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $searchQuery . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    
    // Get the result set
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<h3>Search Results:</h3>";
        while ($row = $result->fetch_assoc()) {
            // Display your results here
            // For example, display a name and email:
            echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ddd;'>";
            echo "Name: " . $row['name'] . "<br>";
            echo "Email: " . $row['email'];
            echo "</div>";
        }
    } else {
        echo "<p>No results found.</p>";
    }
    
    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <!-- Add CSS styling if needed -->
    <style>
        .search-box {
            width: 300px;
            margin: 50px auto;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="search-box">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="search" placeholder="Search here..." autocomplete="off">
            <button type="submit" name="submit">Search</button>
        </form>
    </div>

    <?php
    // Database connection details
    $dbHost = 'localhost';
    $dbUser = 'root';  // Change to your database username
    $dbPass = '';      // Change to your database password
    $dbName = 'search_db';  // Change to your database name

    // Connect to database
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get search term
        $search = $_POST['search'];
        
        // Escape special characters to prevent SQL injection
        $search = mysqli_real_escape_string($conn, $search);

        // Search query
        $sql = "SELECT * FROM your_table_name WHERE column_name LIKE ?";
        
        $stmt = $conn->prepare($sql);
        $searchTerm = "%$search%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p>" . $row['column_name'] . "</p>";
            }
        } else {
            echo "No results found";
        }

        // Close statement
        $stmt->close();
    }

    // Close database connection
    $conn->close();
    ?>

</body>
</html>


<?php
// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get search query from form
    $search_query = $_POST['search_query'];
    
    // Sanitize the input (prevent SQL injection)
    $search_query = htmlspecialchars($search_query, ENT_QUOTES);
    
    // Database connection
    $conn = new mysqli("localhost", "username", "password", "database_name");
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // SQL query to search for the term in the database
    $sql = "SELECT * FROM table_name WHERE column_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_term = "%$search_query%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    
    // Get results
    $result = $stmt->get_result();
    
    // Display results
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='result'>";
            echo "ID: " . $row["id"] . "<br>";
            echo "Name: " . $row["name"] . "<br>";
            // Display other columns as needed
            echo "</div>";
        }
    } else {
        echo "No results found";
    }
    
    // Close database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search_query" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-input {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            margin-bottom: 5px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="search-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="query" class="search-input" placeholder="Search...">
        <button type="submit" class="search-button">Search</button>
    </form>

    <?php
    // Sample data - Replace this with your actual data source (e.g., database)
    $data = array(
        "apple",
        "banana",
        "orange",
        "grape",
        "mango",
        "kiwi",
        "strawberry",
        "blueberry"
    );

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $query = strtolower(trim($_POST['query']));
        
        // Search through the data array
        $results = array();
        foreach ($data as $item) {
            if (strpos(strtolower($item), $query) !== false) {
                array_push($results, $item);
            }
        }

        // Display results
        if (!empty($results)) {
            echo "<div class='results'>";
            echo "<h3>Results:</h3>";
            foreach ($results as $result) {
                echo "<div class='result-item'>$result</div>";
            }
            echo "</div>";
        } else {
            echo "<div class='results'>";
            echo "<p>No results found.</p>";
            echo "</div>";
        }
    }
    ?>
</div>

</body>
</html>


<?php
// search.php

if (isset($_POST['query'])) {
    $search = $_POST['query'];
    
    // Connect to database
    $conn = mysqli_connect("localhost", "root", "", "your_database");
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Sanitize the input
    $search = htmlspecialchars($search);
    
    // SQL query to search in database table
    $sql = "SELECT * FROM your_table WHERE name LIKE '%" . $search . "%'";
    
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div>";
            echo "<h3>" . $row['name'] . "</h3>";
            // Display other relevant information
            echo "</div>";
        }
    } else {
        echo "No results found";
    }
    
    // Close database connection
    mysqli_close($conn);
}
?>


<?php
if (isset($_POST['query'])) {
    $search = $_POST['query'];
    
    // Sanitize input
    $search = htmlspecialchars(trim($search));
    
    // Connect to database
    $conn = new mysqli("localhost", "root", "", "your_database");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare SQL statement with a prepared statement
    $stmt = $conn->prepare("SELECT * FROM your_table WHERE name LIKE ?");
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("s", $searchTerm);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div>";
                echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                // Display other relevant information
                echo "</div>";
            }
        } else {
            echo "No results found";
        }
    } else {
        echo "Error: " . $stmt->error;
    }
    
    // Close database connection
    $conn->close();
}
?>


<?php
// search.php

// Get the search term from the POST request
$searchTerm = $_POST['query'];

// Sanitize the input to prevent SQL injection
$searchTerm = htmlspecialchars($searchTerm);

// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'your_database';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $database);

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Escape special characters in the search term
$searchTerm = mysqli_real_escape_string($conn, $searchTerm);

// SQL query to search for the term
$sql = "SELECT * FROM your_table WHERE column_name LIKE '%".$searchTerm."%'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Output the results
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='result'>";
        echo "ID: " . $row['id'] . "<br>";
        echo "Name: " . $row['name'] . "<br>";
        // Add more fields as needed
        echo "</div>";
    }
} else {
    echo "<div class='result'>";
    echo "No results found.";
    echo "</div>";
}

// Close the database connection
mysqli_close($conn);
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'your_database';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term from form
if (isset($_POST['submit'])) {
    $search_term = trim($_POST['search']);
    $search_term = mysqli_real_escape_string($conn, $search_term);

    // Search in the database
    $query = "SELECT * FROM users WHERE first_name LIKE '%{$search_term}%' OR last_name LIKE '%{$search_term}%'";
    $result = mysqli_query($conn, $query);

    // Display results
    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>First Name</th><th>Last Name</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['first_name'] . "</td>";
            echo "<td>" . $row['last_name'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No results found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-bar input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-bar input[type="submit"] {
            width: 20%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-bar input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search...">
                <input type="submit" name="submit" value="Search">
            </div>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <!-- Add any CSS styling here -->
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .search-form {
            display: flex;
            gap: 10px;
        }
        input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search Form -->
        <form class="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="search" placeholder="Search..." <?php if(isset($_GET['search'])) { echo 'value="'.$_GET['search'].'"'; } ?>>
            <button type="submit">Search</button>
        </form>

        <!-- Search Results -->
        <?php
        // Check if search parameter exists
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = trim(htmlspecialchars($_GET['search']));
            
            // Database connection
            $dbHost = 'localhost';
            $dbName = 'your_database_name';
            $dbUser = 'your_username';
            $dbPass = 'your_password';

            $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
            
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL query to search for the term
            $sql = "SELECT * FROM your_table_name WHERE column_name LIKE ?";
            $stmt = $conn->prepare($sql);
            $searchTerm = "%$search%";
            $stmt->bind_param("s", $searchTerm);
            $stmt->execute();
            
            // Get results
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                    echo "<li>" . $row['column_name'] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "No results found.";
            }

            // Close connections
            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .search-button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <?php
    // Search results array (example data)
    $items = array(
        "Apple",
        "Banana",
        "Cherry",
        "Date",
        "Grape",
        "Orange",
        "Peach",
        "Pear"
    );

    // Get search query from POST request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $query = $_POST['search_query'];
        
        if (!empty($query)) {
            // Filter results based on search query
            $results = array_filter($items, function($item) use ($query) {
                return strtolower($item) === strtolower($query);
            });

            if (count($results) > 0) {
                echo "<div class='results'>";
                echo "<h3>Search Results for: " . htmlspecialchars($query) . "</h3>";
                echo "<ul>";
                foreach ($results as $result) {
                    echo "<li>" . $result . "</li>";
                }
                echo "</ul>";
                echo "</div>";
            } else {
                echo "<div class='results'>";
                echo "<p>No results found for: " . htmlspecialchars($query) . "</p>";
                echo "<p>Available options: " . implode(", ", $items) . "</p>";
                echo "</div>";
            }
        }
    }
    ?>

    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search_query" class="search-bar" placeholder="Enter search term...">
            <br>
            <input type="submit" value="Search" class="search-button">
        </form>
    </div>

</body>
</html>


<?php
// Get the search query from the GET request
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Sanitize the input to prevent SQL injection
$sanitizedQuery = htmlspecialchars(trim($query));

if ($sanitizedQuery !== '') {
    // Database connection details
    $host = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'mydatabase';

    // Connect to database
    $conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // SQL query to search for the term
    $sql = "SELECT * FROM users WHERE name LIKE '%" . mysqli_real_escape_string($conn, $sanitizedQuery) . "%'";
    
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error in query: " . mysqli_error($conn));
    }

    // Display the results
    echo "<h2>Search Results</h2>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div>";
        echo "<p>User ID: " . $row['id'] . "</p>";
        echo "<p>Name: " . $row['name'] . "</p>";
        echo "</div>";
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    echo "<h2>Please enter a search term</h2>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add some basic styling -->
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .search-container h2 {
            color: #333;
            text-align: center;
        }
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-form input {
            flex-grow: 1;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-form button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <h2>Search Products</h2>
        
        <?php
        // Check if the form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search_query = $_POST['query'];
            
            // Database connection details
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'test_db';
            
            // Connect to database
            $conn = mysqli_connect($host, $username, $password, $database);
            
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // Escape the search query to prevent SQL injection
            $search_query = mysqli_real_escape_string($conn, $search_query);
            
            // Search query
            $sql = "SELECT * FROM products WHERE product_name LIKE '%{$search_query}%' ORDER BY id ASC";
            
            $result = mysqli_query($conn, $sql);
            
            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }
            
            // Display search results
            echo "<div class='search-results'>";
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='product'>";
                    echo "<h3>" . $row['product_name'] . "</h3>";
                    echo "<p>Price: $" . number_format($row['price'], 2) . "</p>";
                    echo "<p>Category: " . $row['category'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No products found matching your search.</p>";
            }
            echo "</div>";
            
            // Close database connection
            mysqli_close($conn);
        }
        ?>
        
        <form class="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="query" placeholder="Search products..." required>
            <button type="submit">Search</button>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-container form {
            display: flex;
            gap: 10px;
            width: 100%;
        }

        .search-container input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .search-container button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="search_query" placeholder="Search...">
            <button type="submit">Go</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search_query = htmlspecialchars($_POST['search_query']);
            
            // Here you would typically connect to a database and perform a search query
            // For this example, we'll just display the search term
            echo "<div class='results'>";
            echo "<h3>Search Results for: '$search_query'</h3>";
            
            // This is a simplified example - replace with actual database search logic
            $sample_data = array("apple", "banana", "orange", "grape", "melon");
            $matches = array();
            
            foreach ($sample_data as $item) {
                if (stripos($item, $search_query) !== false) {
                    $matches[] = $item;
                }
            }
            
            if (!empty($matches)) {
                echo "<p>Found " . count($matches) . " results:</p>";
                foreach ($matches as $match) {
                    echo "<li>" . $match . "</li>";
                }
            } else {
                echo "<p>No results found.</p>";
            }
            
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        .search-results {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search Form -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="text" name="keyword" class="search-bar" placeholder="Enter search keyword...">
            <select name="category">
                <option value="">All Categories</option>
                <option value="technology">Technology</option>
                <option value="sports">Sports</option>
                <option value="entertainment">Entertainment</option>
                <option value="politics">Politics</option>
            </select>
            <button type="submit" class="submit-btn">Search</button>
        </form>

        <!-- Search Results -->
        <?php
            // Database connection details
            $host = "localhost";
            $username = "root";
            $password = "";
            $database = "test_db";

            // Connect to database
            $conn = mysqli_connect($host, $username, $password, $database);

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Get search keyword and category from form submission
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $keyword = isset($_POST['keyword']) ? mysqli_real_escape_string($conn, $_POST['keyword']) : '';
                $category = isset($_POST['category']) ? mysqli_real_escape_string($conn, $_POST['category']) : '';

                // SQL query to search in database
                if ($keyword) {
                    if ($category) {
                        $sql = "SELECT * FROM search_table WHERE title LIKE '%$keyword%' OR description LIKE '%$keyword%' AND category = '$category'";
                    } else {
                        $sql = "SELECT * FROM search_table WHERE title LIKE '%$keyword%' OR description LIKE '%$keyword%'";
                    }

                    // Execute query
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        echo "<div class='search-results'>";
                        echo "<h3>Search Results</h3>";
                        echo "<table border='1'>";
                        echo "<tr><th>ID</th><th>Title</th><th>Description</th><th>Category</th></tr>";

                        // Output data of each row
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>".$row['id']."</td>";
                            echo "<td>".$row['title']."</td>";
                            echo "<td>".$row['description']."</td>";
                            echo "<td>".$row['category']."</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        echo "</div>";
                    } else {
                        echo "<div class='search-results'>";
                        echo "No results found.";
                        echo "</div>";
                    }
                }
            }

            // Close database connection
            mysqli_close($conn);
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-box {
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
        }
        input[type="text"] {
            width: 70%;
            padding: 8px;
            margin-right: 10px;
        }
        input[type="submit"] {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php
    // Sample data for search functionality
    $data = array(
        "Apple",
        "Banana",
        "Cherry",
        "Date",
        "Grape",
        "Orange",
        "Peach",
        "Pear"
    );

    if (isset($_POST['submit'])) {
        $search_keyword = $_POST['keyword'];
        $results = array();

        // Simple search functionality
        foreach ($data as $item) {
            if (strtolower($item) == strtolower($search_keyword)) {
                array_push($results, $item);
            }
        }

        // Display results
        if (!empty($results)) {
            echo "<h2>Search Results:</h2>";
            echo "<ul>";
            foreach ($results as $result) {
                echo "<li>$result</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No results found.</p>";
        }
    }

    // Display search box
    if (!isset($_POST['submit'])) {
        echo '<form method="post" action="">';
        echo '<div class="search-box">';
        echo '<input type="text" name="keyword" placeholder="Search...">';
        echo '<input type="submit" name="submit" value="Search">';
        echo '</div>';
        echo '</form>';
    }

    // Display this link to reset the search
    if (isset($_POST['submit'])) {
        echo '<br><a href="?">Reset Search</a>';
    }
    ?>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 5px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <h2>Search Here</h2>
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="query" class="search-bar" placeholder="Enter search query...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <?php
    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $searchQuery = $_GET['query'];
        
        // Validate input
        if (!empty($searchQuery)) {
            // Connect to database
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'test_db';

            $conn = new mysqli($host, $username, $password, $database);
            
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Escape the input to prevent SQL injection
            $searchQuery = $conn->real_escape_string(trim($searchQuery));

            // Search query
            $sql = "SELECT * FROM your_table_name 
                    WHERE column1 LIKE '%{$searchQuery}%' 
                    OR column2 LIKE '%{$searchQuery}%'";
            
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<h3>Search Results:</h3>";
                while ($row = $result->fetch_assoc()) {
                    // Display your results here
                    echo "<p>" . $row['column_name'] . "</p>";
                }
            } else {
                echo "<p>No results found.</p>";
            }

            $conn->close();
        }
    }
    ?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-box {
            display: flex;
            gap: 10px;
        }

        .search-box input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .search-box button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-box button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Search Form -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="search-box">
            <input type="text" name="query" placeholder="Search here..." value="<?php if (isset($_GET['query'])) { echo htmlspecialchars($_GET['query']); } ?>">
            <button type="submit">Search</button>
            <button type="reset">Clear</button>
        </form>

        <!-- Display Search Results -->
        <?php
            // Get the search query from GET parameters
            if (isset($_GET['query'])) {
                $searchQuery = htmlspecialchars($_GET['query']);
                
                // Simulate a database with sample data
                $sampleData = array(
                    array('title' => 'PHP Programming', 'description' => 'Learn PHP programming language'),
                    array('title' => 'Web Development', 'description' => 'Complete web development guide'),
                    array('title' => 'Database Management', 'description' => 'MySQL database management tutorial'),
                    array('title' => 'JavaScript Guide', 'description' => 'Essential JavaScript concepts')
                );

                echo "<div class='results'>";
                $found = false;

                foreach ($sampleData as $item) {
                    // Search both title and description
                    if (stripos($item['title'], $searchQuery) !== false || 
                        stripos($item['description'], $searchQuery) !== false) {
                        echo "<div class='result-item'>";
                        echo "<h3>" . htmlspecialchars($item['title']) . "</h3>";
                        echo "<p>" . htmlspecialchars($item['description']) . "</p>";
                        echo "</div>";
                        $found = true;
                    }
                }

                if (!$found) {
                    echo "<div class='result-item'>";
                    echo "<p>No results found for: '" . $searchQuery . "'</p>";
                    echo "</div>";
                }

                echo "</div>";
            }
        ?>
    </div>
</body>
</html>


<?php
// Connect to database
$host = 'localhost';
$dbname = 'your_database';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get search query from POST data
    $searchQuery = isset($_POST['query']) ? $_POST['query'] : '';

    // Sanitize the input
    $searchQuery = htmlspecialchars(trim($searchQuery));

    // Prepare SQL statement to search for the query in your table
    $stmt = $conn->prepare("SELECT * FROM your_table WHERE column1 LIKE ? OR column2 LIKE ?");
    
    // Bind parameters (to prevent SQL injection)
    $searchQuery = '%' . $searchQuery . '%';
    $stmt->bindParam(1, $searchQuery);
    $stmt->bindParam(2, $searchQuery);

    // Execute the query
    $stmt->execute();

    // Get the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="query" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php if (isset($results)) { ?>
            <table style="width: 100%; margin-top: 20px;">
                <tr>
                    <!-- Add table headers -->
                    <th>Column 1</th>
                    <th>Column 2</th>
                    <!-- Add more columns as needed -->
                </tr>
                <?php foreach ($results as $row) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['column1']); ?></td>
                        <td><?php echo htmlspecialchars($row['column2']); ?></td>
                        <!-- Add more table data cells as needed -->
                    </tr>
                <?php } ?>
            </table>

            <?php if (count($results) == 0) { ?>
                <p style="color: red;">No results found!</p>
            <?php } ?>
        <?php } else { ?>
            <p>Type your search query and click Search to begin!</p>
        <?php } ?>

    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        /* Add some basic CSS styling */
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-box {
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="search-box">
                <input type="text" name="search_term" placeholder="Search here...">
                <button type="submit" name="submit">Search</button>
            </div>
        </form>

        <?php
        // Check if form is submitted
        if (isset($_POST['submit'])) {
            $search_term = trim($_POST['search_term']);

            // Database connection details
            $host = 'localhost';
            $username = 'your_username';
            $password = 'your_password';
            $database = 'your_database';

            // Connect to database
            $conn = new mysqli($host, $username, $password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Sanitize the input
            $search_term = mysql_real_escape_string($search_term);

            // SQL query to fetch data based on search term
            $sql = "SELECT * FROM users WHERE name = '$search_term'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div>";
                    echo "ID: " . $row['id'] . "<br>";
                    echo "Name: " . $row['name'] . "<br>";
                    echo "</div>";
                }
            } else {
                echo "No entries found matching your search.";
            }

            // Close database connection
            $conn->close();
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            width: 15%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<?php
// Check if form is submitted
if (!isset($_GET['submit'])) {
    // Display search bar
?>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <input type="text" name="query" class="search-bar" placeholder="Search...">
            <button type="submit" name="submit" class="search-button">Search</button>
        </form>
    </div>
<?php
} else {
    // Process the search query
    $query = isset($_GET['query']) ? trim($_GET['query']) : '';
    
    if ($query !== '') {
        echo "<div class='search-container'>";
        echo "<h3>Search Results for: '" . htmlspecialchars($query) . "'</h3>";
        // Add your search logic here
        // For example, query a database or perform a search operation
        echo "</div>";
    } else {
        echo "<div class='search-container'>";
        echo "<p>Please enter a search query.</p>";
        echo "</div>";
    }
}
?>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: inline-block;
        }
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="query" class="search-box" placeholder="Search...">
            <button type="submit" name="submit" class="search-button">Search</button>
        </form>
    </div>

    <?php
    // Display search results
    if (isset($_POST['submit'])) {
        $search_query = $_POST['query'];

        // Here you would typically connect to a database and query your data
        // For this example, we'll use sample data
        $sample_data = array(
            "Apple",
            "Banana",
            "Cherry",
            "Date",
            "Elderberry",
            "Fig",
            "Grape",
            "Honeydew",
            "Kiwi",
            "Lemon",
            "Mango"
        );

        echo "<h3>Search Results:</h3>";
        echo "<ul>";

        foreach ($sample_data as $item) {
            if (strtolower($item) == strtolower($search_query)) {
                echo "<li>$item</li>";
            }
        }

        echo "</ul>";

        // If no results found
        if (empty($sample_data)) {
            echo "<p>No results found.</p>";
        }
    }
    ?>
</body>
</html>


$search_query = mysqli_real_escape_string($connection, $_POST['query']);


<?php
// Get the search query from the URL parameter
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Sanitize the input to prevent SQL injection or XSS attacks
$search_query = htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8');

// For this example, we'll use a simple array of items
// In a real application, you would typically query a database

$items = [
    ['id' => 1, 'name' => 'Apple', 'description' => 'A red fruit'],
    ['id' => 2, 'name' => 'Banana', 'description' => 'A yellow fruit'],
    ['id' => 3, 'name' => 'Orange', 'description' => 'An orange fruit'],
    // Add more items as needed
];

// Filter the items based on the search query
$results = array();
foreach ($items as $item) {
    if (stripos($item['name'], $search_query) !== false || 
        stripos($item['description'], $search_query) !== false) {
        $results[] = $item;
    }
}

// Display the results
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            background-color: white;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php if (empty($results) && $search_query !== ''): ?>
            <p>No results found for "<?php echo htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8'); ?>".</p>
        <?php elseif ($search_query === ''): ?>
            <p>Please enter a search query.</p>
        <?php else: ?>
            <h3>Results:</h3>
            <div class="results">
                <?php foreach ($results as $item): ?>
                    <div class="result-item">
                        <h4><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <p><?php echo htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Back to search -->
        <form action="index.html">
            <input type="submit" value="Back to Search" class="search-button">
        </form>
    </div>
</body>
</html>


<?php
// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to MySQL database
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the search term from the form
$query = isset($_GET['query']) ? $_GET['query'] : '';
$query = htmlspecialchars($query); // Sanitize input

// Escape special characters to prevent SQL injection
$search = mysqli_real_escape_string($conn, $query);

// Assuming you have a table named 'search_table' with columns: id, title, content, date
$sql = "SELECT * FROM search_table 
        WHERE title LIKE '%" . $search . "%' 
        OR content LIKE '%" . $search . "%'
        LIMIT 10";

$result = mysqli_query($conn, $sql);

if ($result) {
    // Display the results
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="result">';
            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
            echo '<p>' . htmlspecialchars($row['content']) . '</p>';
            echo '<small>Posted on: ' . $row['date'] . '</small>';
            echo '</div>';
        }
    } else {
        echo '<div class="result">';
        echo 'No results found!';
        echo '</div>';
    }
} else {
    // Display error message if query fails
    echo '<div class="result">';
    echo 'Error: ' . mysqli_error($conn);
    echo '</div>';
}

// Close the database connection
mysqli_close($conn);
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
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-container {
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 16px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="query" placeholder="Search here..." <?php if (isset($_GET['query'])) { echo 'value="' . $_GET['query'] . '"'; } ?>>
            <input type="submit" value="Search">
        </form>
    </div>

    <?php
    // Sample data - you can replace this with your database query
    $items = array('apple', 'banana', 'orange', 'grape', 'mango', 'kiwi', 'pear', 'melon');

    if (isset($_GET['query'])) {
        $searchQuery = $_GET['query'];
        $results = array();

        foreach ($items as $item) {
            if (stripos($item, $searchQuery) !== false) {
                $results[] = $item;
            }
        }

        echo '<div class="results">';
        if (!empty($results)) {
            echo "<h3>Results:</h3>";
            echo "<ul>";
            foreach ($results as $result) {
                echo "<li>" . $result . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No results found for your query: '" . $searchQuery . "'</p>";
        }
        echo '</div>';
    }
    ?>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username = 'root'; // your database username
$password = '';     // your database password
$dbname = 'search_db'; // your database name

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search query from form
$query = trim($_POST['query']);

// Escape the input to prevent SQL injection
$search_query = mysqli_real_escape_string($conn, $query);

// SQL query to fetch results based on the search term
$sql = "SELECT * FROM search_table WHERE title LIKE '%{$search_query}%' OR description LIKE '%{$search_query}%'";
$result = $conn->query($sql);

// Display results
echo "<h2>Search Results</h2>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div style='padding:10px;margin:5px;border:1px solid #ddd;'>";
        echo "<h3>" . $row['title'] . "</h3>";
        echo "<p>" . $row['description'] . "</p>";
        echo "</div>";
    }
} else {
    echo "No results found.";
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-form {
            display: flex;
            gap: 10px;
        }
        input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search Form -->
        <form class="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="query" placeholder="Search..." required>
            <button type="submit">Search</button>
        </form>

        <?php
        // Database connection details
        $host = 'localhost';
        $dbUsername = 'username';
        $dbPassword = 'password';
        $dbName = 'database_name';

        try {
            // Create database connection
            $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

            // Check if form is submitted
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                if (!empty($_GET['query'])) {
                    $searchQuery = $_GET['query'];

                    // Prepare SQL statement to prevent SQL injection
                    $stmt = $conn->prepare("SELECT * FROM your_table_name WHERE column_name LIKE ?");
                    $searchTerm = "%" . $searchQuery . "%";
                    $stmt->bind_param('s', $searchTerm);

                    // Execute the query
                    $stmt->execute();

                    // Get result set
                    $result = $stmt->get_result();

                    // Display search results
                    if ($result->num_rows > 0) {
                        echo "<table border='1' style='width:100%; margin-top:20px;'>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($row as $value) {
                                echo "<td>$value</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        // No results found
                        echo "<p style='color:red; margin-top:20px;'>No results found.</p>";
                    }

                    // Close statement and connection
                    $stmt->close();
                }
            }
        } catch (mysqli_sql_exception $e) {
            // Display error if database connection fails
            die("Connection failed: " . $e->getMessage());
        }
        ?>
    </div>
</body>
</html>


// Replace the sample_data loop with:
$host = 'localhost';
$dbname = 'your_database';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM your_table WHERE column_name LIKE ?");
    $searchQuery = '%' . $query . '%';
    $stmt->execute([$searchQuery]);

    while ($row = $stmt->fetch()) {
        $results[] = $row['column_name'];
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-bar input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        .search-bar button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="search-bar">
                <input type="text" name="search_query" placeholder="Search...">
                <button type="submit">Go</button>
            </div>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get search query from form
            $searchQuery = $_POST['search_query'];
            
            // Sanitize input
            $searchQuery = htmlspecialchars($searchQuery);

            // Connect to database (replace with your actual database credentials)
            $dbHost = 'localhost';
            $dbName = 'your_database';
            $dbUser = 'root';
            $dbPass = '';

            try {
                $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Search query
                $stmt = $conn->prepare("SELECT * FROM your_table WHERE column_name LIKE ?");
                $searchQuery = "%$searchQuery%";
                $stmt->bindParam(1, $searchQuery);
                $stmt->execute();

                // Display results
                echo "<div class='search-results'>";
                echo "<h3>Search Results:</h3>";
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<p>".$row['column_name']."</p>"; // Replace with your actual column name(s)
                }

                if ($stmt->rowCount() == 0) {
                    echo "<p>No results found.</p>";
                }
                echo "</div>";

            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        ?>
    </div>
</body>
</html>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchQuery = $_POST['search_query'];
    $searchQuery = htmlspecialchars($searchQuery);

    // Sample data
    $data = array(
        'apple',
        'banana',
        'orange',
        'grape',
        'strawberry',
        'blueberry'
    );

    $results = array();
    
    foreach ($data as $item) {
        if (stripos($item, $searchQuery) !== false) {
            $results[] = $item;
        }
    }

    echo "<div class='search-results'>";
    echo "<h3>Search Results:</h3>";
    
    if (!empty($results)) {
        foreach ($results as $result) {
            echo "<p>".$result."</p>";
        }
    } else {
        echo "<p>No results found.</p>";
    }
    echo "</div>";
}


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-form {
            display: flex;
            gap: 10px;
        }
        .search-input {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
        .result {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Sample data - you would typically get this from a database
        $dataset = array(
            array('name' => 'Apple', 'description' => 'A fruit with red or green skin'),
            array('name' => 'Banana', 'description' => 'A yellow fruit'),
            array('name' => 'Cherry', 'description' => 'A small, red fruit'),
            array('name' => 'Grape', 'description' => 'A small, sweet fruit'),
            // Add more items as needed
        );

        if (isset($_GET['query']) && !empty($_GET['query'])) {
            $searchQuery = $_GET['query'];
            
            function performSearch($query, $data) {
                $results = array();
                foreach ($data as $item) {
                    if (stripos($item['name'], $query) !== false || 
                        stripos($item['description'], $query) !== false) {
                        $results[] = $item;
                    }
                }
                return $results;
            }

            $searchResults = performSearch($searchQuery, $dataset);

            if (empty($searchResults)) {
                echo "<div class='result'>No results found for '$searchQuery'</div>";
            } else {
                echo "<div class='result'><h3>Search Results</h3>";
                foreach ($searchResults as $result) {
                    echo "<p><strong>{$result['name']}</strong><br/>";
                    echo "{$result['description']}</p>";
                }
                echo "</div>";
            }
        }
        ?>

        <form class="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="query" class="search-input" placeholder="Search for fruits...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term from form submission
$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : '';

// Sanitize the input to prevent SQL injection
$search_term = htmlspecialchars($search_term);

// Escape special characters
$search_term = mysqli_real_escape_string($conn, $search_term);

// Search query
$sql = "SELECT * FROM your_table WHERE column_name LIKE '%$search_term%'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<div class='results'>";
    while ($row = mysqli_fetch_assoc($result)) {
        // Display the results according to your table structure
        echo "<p>" . $row['column_name'] . "</p>";
    }
    echo "</div>";
} else {
    echo "<div class='results'><p>No results found.</p></div>";
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Start session
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $searchTerm = trim(htmlspecialchars(stripslashes($_POST['search'])));
    
    // Database connection
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "database_name";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    try {
        // Prepare SQL query with LIKE clause for partial matches
        $stmt = $conn->prepare("SELECT * FROM your_table WHERE column_name LIKE ?");
        $searchTerm = '%' . $searchTerm . '%'; // Add wildcards for partial matching
        $stmt->bind_param("s", $searchTerm);

        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Output results
            while($row = $result->fetch_assoc()) {
                echo "<p>" . $row["column_name"] . "</p>";
            }
        } else {
            echo "No results found!";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();

    } catch (mysqli_sql_exception $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
    }

} else {
    // Redirect to search page if not submitted via POST
    header("Location: search_page.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Page</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-bar {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="search.php" method="POST">
            <!-- CSRF Token -->
            <?php
                // Generate CSRF token
                session_start();
                if (!isset($_SESSION['csrf_token'])) {
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <input type="text" class="search-bar" placeholder="Search here..." name="search">
            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>

    <?php
    // Display any messages from the search result
    if (isset($_GET['message'])) {
        echo "<p>" . htmlspecialchars($_GET['message']) . "</p>";
    }
    ?>

    <!-- Optional: Show error message if no query was provided -->
    <?php
    if (isset($_GET['error'])) {
        echo "<p style='color:red;'>" . htmlspecialchars($_GET['error']) . "</p>";
    }
    ?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .search-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background-color: white;
            border-radius: 25px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar input {
            flex: 1;
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 25px;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search here..." value="<?php if(isset($_GET['search'])) { echo htmlspecialchars($_GET['search']); } ?>">
                <button type="submit" name="submit">Search</button>
            </div>
        </form>

        <?php
        // Search results section
        if (isset($_GET['submit'])) {
            $search_term = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
            
            if (empty($search_term) || trim($search_term) == '') {
                echo "<div class='results'><p style='color: red;'>Please enter a valid keyword to search.</p></div>";
            } else {
                // Database connection
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

                // Prepare and bind the statement to prevent SQL injection
                $stmt = $conn->prepare("SELECT * FROM your_table WHERE description LIKE ?");
                $search_term = '%' . mysqli_real_escape_string($conn, $search_term) . '%';
                $stmt->bind_param("s", $search_term);

                // Execute query
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<div class='results'>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='result-item'>";
                        echo "<p><strong>Item:</strong> " . htmlspecialchars($row['item_name']) . "</p>";
                        echo "<p><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>";
                        echo "</div>";
                    }
                    echo "</div>";
                } else {
                    echo "<div class='results'><p style='color: red;'>No results found.</p></div>";
                }

                // Close connection
                $conn->close();
            }
        }
        ?>

        <!-- Example database records for demonstration -->
        <?php
        // This is just an example array - replace with your actual database query results
        $example_results = [
            ['item_name' => 'Laptop', 'description' => 'High performance laptop with 16GB RAM and 512GB SSD'],
            ['item_name' => 'Smartphone', 'description' => 'Latest smartphone model with 64GB storage'],
            ['item_name' => 'Tablet', 'description' => 'Lightweight tablet for daily use']
        ];

        // Display example results
        if (isset($_GET['submit']) && isset($_GET['search'])) {
            $search_term = strtolower(htmlspecialchars($_GET['search']));
            echo "<div class='results'>";
            foreach ($example_results as $result) {
                if (strpos(strtolower($result['description']), $search_term) !== false) {
                    echo "<div class='result-item'>";
                    echo "<p><strong>Item:</strong> " . htmlspecialchars($result['item_name']) . "</p>";
                    echo "<p><strong>Description:</strong> " . htmlspecialchars($result['description']) . "</p>";
                    echo "</div>";
                }
            }
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="">
            <input type="text" name="search" class="search-bar" placeholder="Search products...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Connect to the database
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "my_database";

        $conn = mysqli_connect($host, $username, $password, $database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check if search form is submitted
        if (isset($_POST['search'])) {
            $searchTerm = $_POST['search'];
            
            // Clean the input to prevent SQL injection and XSS
            $searchTerm = mysqli_real_escape_string($conn, htmlspecialchars($searchTerm));
            
            // Query the database for matching results
            $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
            $result = mysqli_query($conn, $sql);

            echo "<div class='results'>";
            if (mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<tr><th>Product ID</th><th>Product Name</th></tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr><td>" . $row['id'] . "</td><td>" . $row['name'] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "No products found matching your search.";
            }
            echo "</div>";
        }
        ?>

    </div>
</body>
</html>


<?php
// search.php

// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'search_db';

// Connect to database
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search term from form input
$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : '';

// Sanitize the input to prevent SQL injection
$search_term = mysqli_real_escape_string($conn, $search_term);

// Query database for matching results
$sql = "SELECT * FROM your_table_name WHERE column_name LIKE '%{$search_term}%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Display the results
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>" . $row['title'] . "</h3>";
        echo "<p>" . $row['description'] . "</p>";
        echo "</div>";
    }
} else {
    // If no results found
    echo "No results found for: " . $search_term;
}

// Close database connection
$conn->close();
?>


<?php
// search.php

// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'search_db';

// Connect to database
$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : '';

// Prepare and bind
$stmt = $conn->prepare("SELECT * FROM your_table_name WHERE column_name LIKE ?");
$stmt->bind_param('s', $search_term);

// Execute query
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>" . $row['title'] . "</h3>";
        echo "<p>" . $row['description'] . "</p>";
        echo "</div>";
    }
} else {
    echo "No results found for: " . $search_term;
}

// Close database connection
$stmt->close();
$conn->close();
?>


<?php
// process_search.php

if (isset($_POST['search_term'])) {
    $searchTerm = mysqli_real_escape_string($connection, $_POST['search_term']);
    $category = mysqli_real_escape_string($connection, $_POST['search_category']);

    // Perform your database query here
    if ($category == 'products') {
        // Query products table
        $query = "SELECT * FROM products WHERE name LIKE '%$searchTerm%'";
    } elseif ($category == 'users') {
        // Query users table
        $query = "SELECT * FROM users WHERE username LIKE '%$searchTerm%'";
    } else {
        // Search all tables
        $query = "...";
    }

    // Execute the query and display results
    // Add your result handling code here
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
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
            // Check if the search query is set
            $query = isset($_GET['query']) ? $_GET['query'] : '';

            // Sanitize the input to prevent SQL injection and XSS attacks
            $query = htmlspecialchars($query, ENT_QUOTES);

            // Database connection details
            $host = 'localhost';
            $dbUsername = 'root';
            $dbPassword = '';
            $dbName = 'mydatabase';

            // Connect to database
            $mysqli = new mysqli($host, $dbUsername, $dbPassword, $dbName);

            if ($mysqli->connect_error) {
                die('Connection failed: ' . $mysqli->connect_error);
            }

            if (!empty($query)) {
                try {
                    // Prepare SQL query with placeholders for the search term
                    $stmt = $mysqli->prepare("SELECT * FROM users WHERE name LIKE ?");
                    $searchTerm = '%' . $query . '%';
                    
                    // Bind the parameter to prevent SQL injection
                    $stmt->bind_param('s', $searchTerm);
                    $stmt->execute();

                    // Get the result set from the executed statement
                    $result = $stmt->get_result();
                } catch (mysqli_sql_exception $e) {
                    die("MySQL Error: " . $e->getMessage());
                }

                // Display search results
                echo '<h3>Search Results:</h3>';
                echo '<div class="results">';

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div style="margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">';
                        echo '<strong>Name:</strong> ' . htmlspecialchars($row['name'], ENT_QUOTES) . '<br>';
                        echo '<strong>Email:</strong> ' . htmlspecialchars($row['email'], ENT_QUOTES);
                        echo '</div>';
                    }
                } else {
                    echo '<p>No results found.</p>';
                }

                echo '</div>';
            }
        ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="query" class="search-bar" placeholder="Search here..." value="<?php echo $query; ?>">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <!-- Close database connection -->
    <?php
        if (isset($mysqli)) {
            $mysqli->close();
        }
    ?>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 5px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search_query" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchQuery = $_POST['search_query'];
            
            // Here you would typically connect to a database and query your data
            // For this example, we'll just display the search results as sample data
            
            echo "<div class='results'>";
            echo "<h3>Results for: '" . htmlspecialchars($searchQuery) . "'</h3>";
            
            // Example results - replace with actual database results
            $exampleResults = array(
                "Result 1 related to your search",
                "Result 2 related to your search",
                "Result 3 related to your search"
            );
            
            if (!empty($exampleResults)) {
                foreach ($exampleResults as $result) {
                    echo "<div class='result-item'>$result</div>";
                }
            } else {
                echo "<p>No results found.</p>";
            }
            
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-box {
            width: 500px;
            margin: 50px auto;
        }
        
        input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 4px;
        }
        
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<?php
// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $search_term = trim($_POST['search']);
    $search_term = htmlspecialchars($search_term);
    $search_term = stripslashes($search_term);

    // Database connection
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'your_database';

    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Search query
    $sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $search_term . "%'";
    $result = mysqli_query($conn, $sql);

    // Display results
    if (mysqli_num_rows($result) > 0) {
        echo "<div class='results'>";
        echo "<table border='1'>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . $value . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "No results found";
    }

    // Close connection
    mysqli_close($conn);
}
?>

<div class="search-box">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="search" placeholder="Search...">
        <input type="submit" value="Search">
    </form>
</div>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-container {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }
        input[type="text"] {
            width: 70%;
            padding: 8px;
            margin-right: 5px;
        }
        button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
            $search = htmlspecialchars($_GET['search']);
            
            // You would typically connect to your database here
            
            // For this example, we'll use a sample array
            $data = [
                'apple',
                'banana',
                'cherry',
                'date',
                'grapefruit',
                'kiwi',
                'lemon',
                'mango'
            ];
            
            // Search logic (simplified)
            $results = [];
            foreach ($data as $item) {
                if (stripos($item, $search) !== false) {
                    $results[] = $item;
                }
            }
        }
        ?>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="search" placeholder="Search here...">
            <button type="submit">Search</button>
        </form>

        <?php if (isset($results) && count($results) > 0): ?>
            <div class="results">
                <h3>Results:</h3>
                <ul>
                    <?php foreach ($results as $result): ?>
                        <li><?php echo htmlspecialchars($result); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif (isset($_GET['search'])): ?>
            <div class="results">
                <h3>No results found.</h3>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="search.php" method="POST">
            <input type="text" name="query" class="search-input" placeholder="Search here...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search query from form
$query = $_POST['query'];
$sanitized_query = htmlspecialchars($query);

// SQL query to search for the term
$sql = "SELECT * FROM your_table WHERE column_name LIKE '%?%'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $sanitized_query);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Display the results
        echo "<div class='search-result'>";
        echo "<p>" . $row['column_name'] . "</p>";
        echo "</div>";
    }
} else {
    echo "No results found.";
}

// Close database connection
$stmt->close();
$conn->close();
?>


<?php
// Get the search term from the form
$search_term = isset($_GET['query']) ? $_GET['query'] : '';

// Sanitize the input to prevent SQL injection
$search_term = htmlspecialchars($search_term, ENT_QUOTES);

// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'my_database';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $database);

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to search for the term in your table
$sql = "SELECT * FROM your_table WHERE name LIKE '%".$search_term."%'";
$result = mysqli_query($conn, $sql);

// Check if there were any errors in the query
if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}

// Display the results
echo "<table border='1'>";
echo "<tr><th>Name</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>" . $row['name'] . "</td></tr>";
}

echo "</table>";

// Close the database connection
mysqli_close($conn);
?>


<?php
// Get the search query from the GET request
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Sanitize the input to prevent SQL injection and XSS
$sanitized_query = htmlspecialchars($search_query, ENT_QUOTES);

// Connect to database
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if search query is not empty
if (!empty($sanitized_query)) {
    // Prepare SQL statement using prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM your_table WHERE column LIKE ?");
    $search = "%{$sanitized_query}%";
    $stmt->bind_param('s', $search);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        // Display the results
        while ($row = $result->fetch_assoc()) {
            echo "<h3>Result:</h3>";
            print_r($row); // Replace this with your actual result display logic
            
            // Example: Display specific columns from the table
            // echo "<p>{$row['column_name']}</p>";
        }
        
        if ($result->num_rows == 0) {
            echo "No results found for '{$sanitized_query}'.";
        }
    } else {
        echo "Error executing query.";
    }

    $stmt->close();
} else {
    echo "Please enter a search query.";
}

// Close database connection
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        /* Basic CSS styling */
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-container {
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 70%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        input[type="submit"] {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <!-- Search form -->
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="search_query" placeholder="Search here..." required>
            <input type="submit" value="Search">
        </form>
    </div>

    <?php
    // Database connection details
    $host = "localhost";
    $username = "root"; // Change this to your database username
    $password = "";     // Change this to your database password
    $database = "search_db"; // Change this to your database name

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search_query = mysqli_real_escape_string($conn, $_POST['search_query']);

        // Connect to database
        $conn = new mysqli($host, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to search in database
        $sql = "SELECT * FROM users WHERE name LIKE ?";
        $stmt = $conn->prepare($sql);
        $search_query = "%$search_query%";
        $stmt->bind_param("s", $search_query);

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Display results
        if ($result->num_rows > 0) {
            echo "<div class='results'>";
            echo "<h2>Results:</h2>";
            echo "<table border='1'>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>";

            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["name"]."</td>";
                echo "<td>".$row["email"]."</td>";
                echo "</tr>";
            }

            echo "</table>";
            echo "</div>";
        } else {
            echo "<p>No results found.</p>";
        }

        // Close connection
        $conn->close();
    }
    ?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .search-container {
            max-width: 600px;
            margin: 0 auto;
        }
        #searchForm {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        #searchInput {
            flex-grow: 1;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        #searchButton {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        #searchButton:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Database connection details
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'test_db';

        // Connect to database
        $conn = mysqli_connect($host, $username, $password, $database);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check if search was performed
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get the search term
            $search = mysqli_real_escape_string($conn, $_POST['search']);

            // Search query
            $query = "
                SELECT * FROM users 
                WHERE name LIKE '%{$search}%' 
                OR email LIKE '%{$search}%'
                OR description LIKE '%{$search}%'
            ";

            // Execute the query
            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }

            // Display results
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div>";
                echo "<h3>{$row['name']}</h3>";
                echo "<p>Email: {$row['email']}</p>";
                echo "<p>Description: {$row['description']}</p>";
                echo "</div><hr/>";
            }

            // Free result and close connection
            mysqli_free_result($result);
        }
        ?>

        <!-- Search Form -->
        <form id="searchForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" id="searchInput" name="search" placeholder="Search here...">
            <button type="submit" id="searchButton">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// search.php

// Connect to database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search query from form input
$query = $_GET['query'];

// Sanitize the input to prevent SQL injection
$query = mysqli_real_escape_string($conn, $query);

// Search in all tables and columns (you can specify particular tables and columns if needed)
$sql = "
SELECT * 
FROM information_schema.columns 
WHERE table_name LIKE '%{$query}%' 
OR column_name LIKE '%{$query}%';
";

$result = mysqli_query($conn, $sql);

if ($result) {
    // Display the results
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div>";
        print_r($row);
        echo "</div>";
    }
} else {
    echo "No results found.";
}

// Close database connection
mysqli_close($conn);
?>


<?php
// search.php

try {
    $conn = new PDO("mysql:host=localhost;dbname=myDB", "username", "password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (isset($_GET['query'])) {
        $searchTerm = $_GET['query'];
        
        // Using prepared statement to prevent SQL injection
        $stmt = $conn->prepare("
            SELECT * 
            FROM information_schema.columns 
            WHERE table_name LIKE ? 
            OR column_name LIKE ?";
        );
        
        $likeTerm = '%' . $searchTerm . '%';
        $stmt->execute([$likeTerm, $likeTerm]);
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($results)) {
            foreach ($results as $row) {
                echo "<div>";
                print_r($row);
                echo "</div>";
            }
        } else {
            echo "No results found.";
        }
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
            // Check if search term is provided
            $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
            
            // Sanitize input to prevent SQL injection/XSS attacks
            $searchTerm = htmlspecialchars(trim($searchTerm));
            
            // Database connection (replace with your actual database credentials)
            $dbHost = 'localhost';
            $dbName = 'test_db';
            $dbUser = 'root';
            $dbPass = '';
            
            try {
                // Connect to database
                $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Query the database
                if (!empty($searchTerm)) {
                    // Prepare SQL statement with LIKE clause for search functionality
                    $stmt = $conn->prepare("SELECT * FROM your_table WHERE column_name LIKE ?");
                    $searchPattern = '%' . $searchTerm . '%';
                    $stmt->execute([$searchPattern]);
                    
                    // Display results
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='results'>";
                        foreach ($row as $key => $value) {
                            echo "$key: " . htmlspecialchars($value) . "<br>";
                        }
                        echo "</div><hr>";
                    }
                } else {
                    // Display search form
                    echo <<<_FORM
                    <form action="search.php" method="get">
                        <input type="text" name="search" class="search-box" placeholder="Search here..." value="$searchTerm">
                        <button type="submit" class="search-button">Search</button>
                    </form>
                    _FORM;
                }
                
                // Close database connection
                $conn = null;
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            flex-grow: 1;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php
    // Check if the form has been submitted
    if (isset($_GET['query'])) {
        $searchQuery = $_GET['query'];
        
        // Sanitize the input to prevent SQL injection or XSS attacks
        $searchQuery = htmlspecialchars(trim($searchQuery));
        
        if ($searchQuery === "") {
            echo "<div class='results'>";
            echo "Please enter a search term.";
            echo "</div>";
        } else {
            echo "<div class='results'>";
            echo "Search results for: " . $searchQuery;
            // Here you would typically connect to your database and display actual results
            echo "</div>";
        }
    } else {
        // If the form hasn't been submitted yet, show the search box
        echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='GET' class='search-form'>";
        echo "<input type='text' name='query' placeholder='Search here...' class='search-input'>";
        echo "<button type='submit' class='search-button'>Search</button>";
        echo "</form>";
    }
    ?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 25px;
            margin-right: 10px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchQuery = htmlspecialchars($_POST['search_query']);
        
        if (!empty($searchQuery)) {
            echo "<div class='search-container'>";
            echo "<h3>Search Results for: " . $searchQuery . "</h3>";
            // Here you would typically connect to a database and fetch results
            // For this example, we'll just display the search query
            echo "</div>";
        } else {
            echo "<script>alert('Please enter something to search');</script>";
        }
    }
?>
    
    <div class="search-container">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" 
                   name="search_query" 
                   placeholder="Search here..." 
                   class="search-bar"
                   value="<?php if (isset($_POST['search_query'])) { echo htmlspecialchars($_POST['search_query']); } ?>">
            <button type="submit" class="search-button">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>

    <!-- Font Awesome for search icon -->
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
</body>
</html>


$stmt = $conn->prepare("SELECT * FROM your_table WHERE column_name LIKE ?");
$stmt->bind_param("s", "%" . $search . "%");
$stmt->execute();
$result = $stmt->get_result();


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex: 1;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <?php
    // Connect to database
    $conn = new mysqli("localhost", "username", "password", "database");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input
        $searchQuery = htmlspecialchars($_POST['query']);
        
        // SQL injection prevention
        $searchQuery = mysqli_real_escape_string($conn, $searchQuery);

        // Search query
        $sql = "SELECT * FROM your_table WHERE name LIKE '%".$searchQuery."%'";
        $result = $conn->query($sql);
    }

    ?>

    <div class="search-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="search-bar">
                <input type="text" name="query" class="search-input" placeholder="Search...">
                <button type="submit" class="search-button">Search</button>
            </div>
        </form>

        <?php
        if (isset($_POST['query']) && $result->num_rows > 0) {
            echo "<div class='results'>";
            while($row = $result->fetch_assoc()) {
                echo "<div class='result-item'>".$row["name"]."</div>";
            }
            echo "</div>";
        } elseif (isset($_POST['query'])) {
            echo "<p>No results found.</p>";
        }
        ?>

    </div>

    <?php
    // Close database connection
    $conn->close();
    ?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        
        .search-bar {
            display: inline-block;
            padding: 10px;
            width: 400px;
            border: 2px solid #333;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .search-button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="search_query" class="search-bar" placeholder="Search...">
            <button type="submit" name="submit" class="search-button">Search</button>
        </form>
        
        <?php
        // Display search results
        if (isset($_POST['submit'])) {
            $search_query = $_POST['search_query'];
            
            // Connect to database
            $host = 'localhost';
            $username = 'your_username';
            $password = 'your_password';
            $database = 'your_database';
            
            $conn = mysqli_connect($host, $username, $password, $database);
            
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // Sanitize input
            $search_query = mysqli_real_escape_string($conn, htmlspecialchars($search_query));
            
            // Perform search query
            $sql = "SELECT * FROM your_table WHERE name LIKE '%{$search_query}%'";
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                echo "<h3>Search Results:</h3>";
                echo "<ul>";
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<li>" . $row['name'] . "</li>";
                }
                
                echo "</ul>";
            } else {
                echo "<p>No results found.</p>";
            }
            
            // Close database connection
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search query from request
$query = isset($_GET['query']) ? $_GET['query'] : '';
$query = $conn->real_escape_string($query); // Sanitize input

// SQL query to fetch results
$sql = "SELECT name FROM your_table WHERE name LIKE '%$query%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<ul class="suggestions-list">';
    while ($row = $result->fetch_assoc()) {
        echo '<li class="suggestion-item">' . $row['name'] . '</li>';
    }
    echo '</ul>';
} else {
    echo '<div class="no-results">No results found</div>';
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-bar {
            width: 70%;
            padding: 10px;
            font-size: 16px;
        }
        .search-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        $searchQuery = "";
        
        // Check if search form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['search_query'])) {
                // Sanitize input data
                $searchQuery = mysqli_real_escape_string($conn, $_POST['search_query']);
                
                // Database connection
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "your_database";
                
                // Create connection
                $conn = mysqli_connect($servername, $username, $password, $dbname);
                
                // Check connection
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                
                // Search query
                $sql = "SELECT * FROM your_table WHERE column1 LIKE '%" . $searchQuery . "%' 
                        OR column2 LIKE '%" . $searchQuery . "%'";
                
                $result = mysqli_query($conn, $sql);
                
                if (mysqli_num_rows($result) > 0) {
                    // Output the results
                    echo "<h3>Search Results:</h3>";
                    echo "<div class='results'>";
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div style='margin-bottom:10px; padding:10px; border:1px solid #ddd;'>";
                        echo "<p><strong>Column 1:</strong> " . $row["column1"] . "</p>";
                        echo "<p><strong>Column 2:</strong> " . $row["column2"] . "</p>";
                        // Add more fields as needed
                        echo "</div>";
                    }
                    
                    echo "</div>";
                } else {
                    echo "<h3>No results found!</h3>";
                }
                
                // Close connection
                mysqli_close($conn);
            }
        }
        ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search_query" class="search-bar" placeholder="Search here..." value="<?php echo $searchQuery; ?>">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Connect to database
include('db_connection.php');

// Get search term from form submission
if (isset($_POST['submit'])) {
    $search_query = mysqli_real_escape_string($conn, htmlspecialchars($_POST['query']));

    // SQL query to fetch data based on the search term
    $sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $search_query . "%'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Display the results
            echo "<div class='result'>";
            echo "<h3>" . $row['title'] . "</h3>";
            echo "<p>" . $row['description'] . "</p>";
            echo "</div>";
        }
    } else {
        // If no results found
        echo "No results found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .search-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .search-input {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .result {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: white;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="query" class="search-input" placeholder="Search...">
            <button type="submit" name="submit" class="search-button">Search</button>
        </form>
    </div>

    <?php 
    if (isset($_POST['submit'])) {
        // Display results here
        echo "<div class='results'>";
        // Results will be displayed above this line
        echo "</div>";
    }
    ?>
</body>
</html>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'your_database';

// Create connection
$conn = mysqli_connect($host, $username, $password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-box {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .results {
            list-style-type: none;
            padding: 0;
        }

        .result-item {
            padding: 10px;
            background-color: white;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .result-item:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="query" placeholder="Search..." class="search-box">
            <button type="submit">Search</button>
        </form>

        <?php
        // Sample data - replace this with your actual database query
        $products = array(
            "Apple",
            "Banana",
            "Orange",
            "Grapes",
            "Mango",
            "Pineapple"
        );

        if (isset($_GET['query'])) {
            $searchQuery = $_GET['query'];
            
            // Search through products
            $results = array();
            foreach ($products as $product) {
                if (stripos($product, $searchQuery) !== false) {
                    $results[] = $product;
                }
            }

            if (!empty($results)) {
                echo "<h3>Search Results:</h3>";
                echo "<ul class=\"results\">";
                foreach ($results as $result) {
                    echo "<li class=\"result-item\">$result</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No results found for your search.</p>";
            }
        } else {
            echo "<p>No search query entered.</p>";
        }
        ?>
    </div>
</body>
</html>


// Connect to database
$conn = mysqli_connect("localhost", "username", "password", "database");

if (isset($_GET['query'])) {
    $searchQuery = mysqli_real_escape_string($conn, $_GET['query']);
    
    // Query database
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchQuery . "%'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li class=\"result-item\">" . $row['name'] . "</li>";
        }
    } else {
        echo "<p>No results found for your search.</p>";
    }
    
    // Close database connection
    mysqli_close($conn);
}


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        
        .search-results {
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search" placeholder="Search here..." class="search-box">
            <button type="submit" name="submit">Search</button>
        </form>
        
        <?php
        // Sample data - you can replace this with your database connection
        $data = array(
            "Apple",
            "Banana",
            "Cherry",
            "Date",
            "Grape",
            "Mango",
            "Orange"
        );

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if search input is set and not empty
            if (isset($_POST['search']) && !empty(trim($_POST['search']))) {
                $searchQuery = trim($_POST['search']);
                
                // Filter the data based on the search query
                $results = array_filter($data, function($value) use ($searchQuery) {
                    return stripos($value, $searchQuery) !== false;
                });
                
                // Display results
                if (!empty($results)) {
                    echo "<div class=\"search-results\">";
                    echo "<h3>Search Results:</h3>";
                    foreach ($results as $result) {
                        echo "<p>$result</p>";
                    }
                    echo "</div>";
                } else {
                    echo "<div class=\"search-results\">";
                    echo "<p>No results found.</p>";
                    echo "</div>";
                }
            } else {
                // If search input is empty
                echo "<div class=\"search-results\">";
                echo "<p>Please enter a search query.</p>";
                echo "</div>";
            }
        }
        ?>
    </div>
</body>
</html>


// Database connection
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query the database
$sql = "SELECT name FROM your_table WHERE name LIKE ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $searchQuery);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch results
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row['name'];
}

// Close the database connection
mysqli_close($conn);


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="query" class="search-bar" placeholder="Search here...">
            <button type="submit">Search</button>
        </form>

        <?php
        // Check if search query is set
        if (isset($_GET['query'])) {
            $search_query = $_GET['query'];

            // Database configuration
            $db_config = array(
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'database' => 'test_db'
            );

            // Connect to database
            $conn = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['database']);

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Search query
            $sql = "SELECT * FROM users WHERE name LIKE ? OR email LIKE ?";
            $stmt = mysqli_prepare($conn, $sql);
            $search_term = "%" . $search_query . "%";
            mysqli_stmt_bind_param($stmt, "ss", $search_term, $search_term);

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                
                // Display results
                echo "<div class='results'>";
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<h4>" . $row['name'] . "</h4>";
                        echo "<p>" . $row['email'] . "</p>";
                    }
                } else {
                    echo "No results found.";
                }
                echo "</div>";
            }

            // Close database connection
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<?php
// Database configuration
$host = "localhost";
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$db_name = "test_db"; // Change to your database name

// Connect to database
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search term from form input
$query = $_POST['query'];

// Clean the input to prevent SQL injection and XSS attacks
$clean_query = htmlspecialchars(trim($query));

// Escape special characters for database query
$escaped_query = mysqli_real_escape_string($conn, $clean_query);

// Search in the database table (e.g., 'users')
$sql = "SELECT * FROM users 
        WHERE name LIKE '%$escaped_query%' 
        OR email LIKE '%$escaped_query%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<div style='margin: 10px; padding: 10px; border: 1px solid #ddd;'>";
        echo "Name: " . $row["name"] . "<br>";
        echo "Email: " . $row["email"] . "<br>";
        echo "</div>";
    }
} else {
    echo "No results found.";
}

// Close database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            background-color: white;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <div class="search-bar">
                <input type="text" name="query" placeholder="Search here..." class="search-input" <?php if(isset($_GET['query'])) { echo 'value="' . htmlspecialchars($_GET['query']) . '"'; } ?>>
                <button type="submit" class="search-button">Search</button>
            </div>
        </form>

        <?php
        // Check if search query is set
        if (isset($_GET['query']) && $_GET['query'] !== '') {
            $searchQuery = $_GET['query'];

            // Here you would typically connect to your database and perform a search query
            // For this example, we'll use some sample data
            $sampleResults = array(
                array('name' => 'Product 1', 'description' => 'This is product 1 description'),
                array('name' => 'Product 2', 'description' => 'This is product 2 description'),
                array('name' => 'Product 3', 'description' => 'This is product 3 description')
            );

            // Display results
            echo '<div class="results">';
            foreach ($sampleResults as $result) {
                echo '<div class="result-item">';
                    echo '<h3>' . htmlspecialchars($result['name']) . '</h3>';
                    echo '<p>' . htmlspecialchars($result['description']) . '</p>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            // If no search query is entered
            echo '<div class="results">';
                echo '<p>Please enter a search query to find results.</p>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
        }
        
        .search-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .search-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Check if form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve and sanitize the search term
            $searchTerm = htmlspecialchars($_POST['search']);
            
            // Here you would typically connect to a database
            // and query for results based on the searchTerm
            
            // For this example, we'll just display the search term
            echo "<h3>Search Results for: '" . $searchTerm . "'</h3>";
            // In a real application, you would display actual search results here
        }
        ?>
        
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search" class="search-box" placeholder="Search here...">
            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to MySQL database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = $_POST['search'];
    
    // Sanitize the input
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    
    // Query the database
    $sql = "SELECT * FROM your_table WHERE column LIKE '%" . $searchTerm . "%'";
    $result = $conn->query($sql);
    
    // Display results
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='result'>";
            echo "<h3>" . $row['title'] . "</h3>";
            echo "<p>" . $row['description'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "No results found.";
    }
}

// Close database connection
$conn->close();
?>


<?php
// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize the search term
    $search_term = htmlspecialchars($_POST['search_term']);

    // Display the search results or query database
    echo "<h2>Search Results for: '" . $search_term . "'</h2>";
    
    // Here you would typically connect to a database
    // and run your search query
    
    // Example of simple search result display
    if (empty($search_term)) {
        die("Please enter a valid search term.");
    }
    
    echo "<p>Showing results for: " . $search_term . "</p>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
        }
        
        .search-box {
            display: inline-block;
            position: relative;
        }
        
        .search-input {
            width: 300px;
            height: 40px;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 25px;
            font-size: 16px;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 20px;
            border: 2px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="search-box">
                <input type="text" name="search_term" class="search-input" placeholder="Search...">
                <button type="submit" class="search-button">Search</button>
            </div>
        </form>

        <?php
        // Check if form is submitted
        if (isset($_POST['search_term'])) {
            $searchTerm = $_POST['search_term'];
            
            // Sanitize input
            $searchTerm = htmlspecialchars($searchTerm);
            
            // Connect to database
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "mydatabase";

            try {
                $conn = new mysqli($servername, $username, $password, $dbname);
                
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                
                // Search query
                $sql = "SELECT * FROM users WHERE name LIKE '%" . $searchTerm . "%'";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    echo "<div class='results'><h3>Search Results</h3>";
                    while($row = $result->fetch_assoc()) {
                        echo "<p>" . $row["name"] . "</p>";
                    }
                    echo "</div>";
                } else {
                    echo "<div class='results'><h3>No results found</h3></div>";
                }
                
                // Close connection
                $conn->close();
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-input {
            flex-grow: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Search..." class="search-input">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Sample data (replace this with your database query)
        $data = array(
            array('id' => 1, 'title' => 'PHP Tutorial', 'description' => 'Learn PHP programming language'),
            array('id' => 2, 'title' => 'MySQL Guide', 'description' => 'Complete MySQL database guide'),
            array('id' => 3, 'title' => 'Web Development', 'description' => 'Web development resources and tutorials'),
            array('id' => 4, 'title' => 'HTML Basics', 'description' => 'Introduction to HTML programming'),
            array('id' => 5, 'title' => 'CSS Styling', 'description' => 'Learn CSS for web design')
        );

        // Check if search query is submitted
        if (isset($_GET['query'])) {
            $search_query = $_GET['query'];
            echo "<h3>Search Results</h3>";
            echo "<div class='results'>";
            
            foreach ($data as $item) {
                // Search in both title and description
                if (stripos($item['title'], $search_query) !== false || stripos($item['description'], $search_query) !== false) {
                    echo "<div class='result-item'>";
                    echo "<h4>" . htmlspecialchars($item['title']) . "</h4>";
                    echo "<p>" . htmlspecialchars($item['description']) . "</p>";
                    echo "</div>";
                }
            }
            
            echo "</div>";
        } else {
            echo "<p>Type something in the search bar and press enter to search!</p>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php
    // Check if the search form has been submitted
    if (isset($_GET['search'])) {
        $search_query = $_GET['search'];
        
        // Sanitize the input to prevent SQL injection or other attacks
        $search_query = htmlspecialchars($search_query);
        
        // Here you would typically connect to your database and perform a search query
        // For this example, we'll use some sample data
        $sample_data = array(
            "Apple",
            "Banana",
            "Cherry",
            "Date",
            "Elderberry",
            "Fig",
            "Grape"
        );
        
        $results = array();
        foreach ($sample_data as $item) {
            if (strtolower($item) == strtolower($search_query)) {
                $results[] = $item;
            }
        }
    } else {
        // No search query submitted
        $results = array();
    }
    ?>

    <div class="search-container">
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search" class="search-bar" placeholder="Search..." value="<?php if (isset($_GET['search'])) { echo htmlspecialchars($_GET['search']); } ?>">
            <button type="submit" class="search-button">Search</button>
        </form>
        
        <?php if (!empty($results)): ?>
            <h3>Results:</h3>
            <ul>
                <?php foreach ($results as $result): ?>
                    <li><?php echo htmlspecialchars($result); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php elseif (isset($_GET['search'])): ?>
            <p>No results found for "<?php echo htmlspecialchars($search_query); ?>".</p>
        <?php endif; ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .search-input {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .result {
            margin-top: 20px;
            padding: 15px;
            background-color: #e8f5e9;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="search" class="search-input" placeholder="Search...">
            <button type="submit" name="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if form is submitted
        if (isset($_POST['submit'])) {
            $searchQuery = $_POST['search'];

            // Sanitize the input to prevent SQL injection or XSS attacks
            $searchQuery = htmlspecialchars($searchQuery);

            if (!empty($searchQuery)) {
                // Display search results
                echo "<div class='result'>";
                echo "Searching for: <strong>" . $searchQuery . "</strong>";
                echo "</div>";

                // Here you would typically connect to a database and perform the actual search query

            } else {
                // If no search query entered
                echo "<div class='result' style='background-color: #f8d7da; color: #721c24'>";
                echo "Please enter a search term!";
                echo "</div>";
            }
        }

        // Clear search link
        if (isset($_GET['clear'])) {
            header("Location: " . $_SERVER['PHP_SELF']);
        }
        ?>
    </div>
</body>
</html>


<?php
// Database connection
$host = "localhost";
$username = "username";
$password = "password";
$db_name = "mydatabase";

// Connect to database
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search query
if (isset($_POST['search'])) {
    $search_term = $_POST['search_query'];
    
    // Sanitize the input
    function sanitize($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    $search_term = sanitize($search_term);
    
    // Prepare and bind
    $stmt = $conn->prepare("SELECT title, description FROM my_table WHERE title LIKE ?");
    $search_term = "%$search_term%";
    $stmt->bind_param("s", $search_term);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    // Display results
    while ($row = $result->fetch_assoc()) {
        echo "<p>Title: " . $row['title'] . "</p>";
        echo "<p>Description: " . $row['description'] . "</p><br>";
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search_query" class="search-box" placeholder="Search...">
            <button type="submit" name="search" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Connect to database
$connection = mysqli_connect("localhost", "username", "password", "database");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term from form
$search_term = $_POST['search_query'];

// Sanitize the input to prevent SQL injection
$search_term = mysqli_real_escape_string($connection, $search_term);

// Perform search query
$query = "SELECT * FROM your_table WHERE column LIKE '%" . $search_term . "%'";
$result = mysqli_query($connection, $query);

if ($result) {
    // Display results
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<p>" . $row['column_name'] . "</p>";
    }
} else {
    // If no results found
    echo "No results found.";
}

// Close database connection
mysqli_close($connection);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .search-results {
            background-color: white;
            padding: 10px;
            border-radius: 5px;
        }

        .search-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Connect to database
        $conn = mysqli_connect("localhost", "username", "password", "database_name");
        
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            // Check if search parameter is set
            if (isset($_GET['search'])) {
                $search_term = $_GET['search'];
                
                // Sanitize the input
                $search_term = mysqli_real_escape_string($conn, $search_term);
                
                // SQL query to search for the term
                $sql = "SELECT * FROM your_table_name WHERE column_name LIKE '%" . $search_term . "%'";
                
                // Execute the query
                $result = mysqli_query($conn, $sql);
                
                if (!$result) {
                    die("Error: " . mysqli_error($conn));
                }
                
                // Display the results
                echo "<h3>Search Results:</h3>";
                echo "<div class='search-results'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='search-item'>";
                    echo $row['column_name']; // Replace with your column name
                    echo "</div>";
                }
                echo "</div>";
            } else {
                // Display search bar if no search term is provided
                echo "<h2>Search:</h2>";
                echo "<form action='" . $_SERVER["PHP_SELF"] . "' method='GET'>";
                echo "<input type='text' name='search' class='search-bar' placeholder='Enter search term...'>";
                echo "<br><br>";
                echo "<input type='submit' value='Search'>";
                echo "</form>";
            }
        }
        
        // Close the database connection
        mysqli_close($conn);
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>
    <!-- HTML form with a search input -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="text" name="search" placeholder="Enter your search term...">
        <input type="submit" value="Search">
    </form>

    <?php
    // Check if the form is submitted and the 'search' parameter exists
    if (isset($_GET['search'])) {
        $searchQuery = $_GET['search'];

        // Trim whitespace from the search query
        $searchQuery = trim($searchQuery);

        // Display the search results or perform a database query here
        if ($searchQuery !== '') {
            echo "<h2>Search Results for: " . htmlspecialchars($searchQuery) . "</h2>";
            // Add your search logic here (e.g., query a database)
            // For this example, we'll just display the search term
            echo "<p>You searched for: " . htmlspecialchars($searchQuery) . "</p>";
        } else {
            echo "<p>Please enter a search term.</p>";
        }
    }
    ?>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        
        .search-box input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-box button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-box button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="search-box">
                <input type="text" name="search_query" placeholder="Search here...">
                <button type="submit" name="search">Search</button>
            </div>
        </form>

        <?php
        // Database connection details
        $host = 'localhost';
        $username = 'root'; // Change to your database username
        $password = '';    // Change to your database password
        $database = 'test_db'; // Change to your database name

        // Connect to database
        $conn = mysqli_connect($host, $username, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Get search query from form
        if (isset($_POST['search'])) {
            $search_query = $_POST['search_query'];
            
            // Sanitize the input to prevent SQL injection
            $search_query = mysql_real_escape_string($search_query);

            // Search in the database
            $sql = "SELECT * FROM your_table_name WHERE keyword LIKE '%" . $search_query . "%'";
            
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<tr><th>ID</th><th>Name</th><th>Keyword</th></tr>"; // Modify headers according to your table structure
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['keyword'] . "</td>";
                    echo "</tr>";
                }
                
                echo "</table>";
            } else {
                echo "No results found.";
            }
        }

        // Close the database connection
        mysqli_close($conn);
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Search functionality
        $search_query = "";
        
        if (isset($_POST['search'])) {
            $search_query = $_POST['search'];
            $search_query = htmlspecialchars($search_query);
            $search_query = mysqli_real_escape_string($conn, $search_query);
            
            // Database connection
            $servername = "localhost";
            $username = "username";
            $password = "password";
            $dbname = "myDB";
            
            // Create connection
            $conn = mysqli_connect($servername, $username, $password, $dbname);
            
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // SQL query to search for the term
            $sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $search_query . "%'";
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                // Output data of each row
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='results'>";
                    echo "Result: " . $row["column_name"] . "<br>";
                    echo "</div>";
                }
            } else {
                echo "<div class='results'>";
                echo "No results found";
                echo "</div>";
            }
            
            // Close connection
            mysqli_close($conn);
        }
        ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search" class="search-box" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        /* Basic styling for the search container */
        #searchContainer {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Styling for the search input field */
        #searchInput {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        /* Styling for the search button */
        #searchButton {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #searchButton:hover {
            background-color: #45a049;
        }

        /* Styling for search results */
        #results {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div id="searchContainer">
        <?php
        // Check if form was submitted
        if (isset($_POST['searchQuery'])) {
            $searchQuery = htmlspecialchars($_POST['searchQuery']);
            echo "<h3>Your search query: " . $searchQuery . "</h3>";
            
            // Here you would typically connect to a database
            // and perform a search using the query above.
            // For this example, we'll just display the query.
        }
        ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" id="searchInput" name="searchQuery" placeholder="Search here...">
            <button type="submit" id="searchButton">Search</button>
        </form>

        <!-- Display search results -->
        <?php
        if (isset($_POST['searchQuery'])) {
            $results = ""; // Replace this with your actual search results
            echo "<div id='results'>";
            // Add your code to display search results here
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .search-container {
            text-align: center;
            width: 500px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        input[type="text"] {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .search-results {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="search-form">
            <input type="text" name="query" placeholder="Search here...">
            <button type="submit">Search</button>
        </form>

        <?php
            if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
                $searchQuery = $_GET['query'];
                echo "<div class=\"search-results\">";
                echo "You searched for: " . htmlspecialchars($searchQuery, ENT_QUOTES);
                echo "</div>";
            }
        ?>

    </div>
</body>
</html>


<?php
// This script handles the search functionality
if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];

    // Connect to database
    $conn = mysqli_connect("localhost", "username", "password", "database_name");
    
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize the input
    $searchQuery = htmlspecialchars($searchQuery);

    // Escape special characters for SQL query
    $searchQuery = mysqli_real_escape_string($conn, $searchQuery);

    // Search query
    $sql = "SELECT * FROM table_name WHERE column_name LIKE '%" . $searchQuery . "%'";
    
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        while ($row = mysqli_fetch_assoc($result)) {
            // Display the results
            echo "<tr><td>" . $row['column_name'] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No results found.";
    }

    // Close database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .result {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="query" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <?php if (isset($_GET['query'])) { ?>
        <div class="result">
            <?php echo isset($results) ? $results : ''; ?>
        </div>
    <?php } ?>

    <!-- Optional: Add some styling for the results -->
    <style>
        .result {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
            min-height: 100px;
        }
    </style>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add some basic styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-container {
            margin-bottom: 20px;
        }
        #searchForm {
            display: flex;
            gap: 10px;
        }
        #searchInput {
            flex-grow: 1;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        #searchButton {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        #searchButton:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $query = $_POST['query'];
        
        // Call the search function
        performSearch($query);
    }

    // Function to perform search
    function performSearch($query) {
        // In a real application, you would connect to a database and query it
        // Here we'll just create some sample data
        $sampleData = array(
            "Apple",
            "Banana",
            "Cherry",
            "Orange",
            "Peach",
            "Plum",
            "Grape"
        );
        
        // Sanitize the input (remove whitespace and special characters)
        $query = trim($query);
        $query = htmlspecialchars($query);
        
        // Search for matches
        $results = array();
        foreach ($sampleData as $item) {
            if (preg_match("/$query/i", $item)) { // Case-insensitive search
                $results[] = $item;
            }
        }
        
        // Display results
        if (empty($results)) {
            echo "<h3>No results found</h3>";
        } else {
            echo "<h3>Results:</h3>";
            echo "<ul>";
            foreach ($results as $result) {
                echo "<li>$result</li>";
            }
            echo "</ul>";
        }
    }
    ?>

    <!-- Create the search form -->
    <div class="search-container">
        <form id="searchForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" id="searchInput" name="query" placeholder="Search...">
            <button type="submit" id="searchButton">Search</button>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
            background-color: #f5f5f5;
            border-radius: 4px;
            padding: 10px;
        }
        
        .search-input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #0056b3;
        }
        
        .results {
            margin-top: 20px;
            background-color: white;
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="search-form">
            <input type="text" name="query" placeholder="Search here..." class="search-input" required>
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $searchQuery = htmlspecialchars(trim($_POST['query']));

                // Database connection details
                $host = 'localhost';
                $username = 'root';
                $password = '';
                $database = 'your_database';

                try {
                    $conn = new mysqli($host, $username, $password, $database);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // SQL query to search in the database
                    $sql = "SELECT * FROM users WHERE first_name LIKE '%" . $searchQuery . "%' OR last_name LIKE '%" . $searchQuery . "%'";
                    
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo "<div class='results'>";
                        echo "<h3>Search Results:</h3>";
                        while($row = $result->fetch_assoc()) {
                            echo "<p>" . $row['first_name'] . " " . $row['last_name'] . "</p>";
                        }
                        echo "</div>";
                    } else {
                        echo "<div class='results'><p>No results found.</p></div>";
                    }

                    $conn->close();
                } catch (Exception $e) {
                    die("An error occurred: " . $e->getMessage());
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add some basic CSS styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        
        .search-bar {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="text" name="search_query" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get the search query from form submission
            $searchQuery = $_POST['search_query'];
            
            // Sanitize the input
            $searchQuery = htmlspecialchars($searchQuery);
            
            // Connect to database (replace with your own credentials)
            $conn = mysqli_connect("localhost", "username", "password", "database_name");
            
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // SQL query
            $sql = "SELECT * FROM table_name WHERE column_name LIKE '%" . mysqli_real_escape_string($conn, $searchQuery) . "%'";
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                echo "<div class='results'><h3>Search Results:</h3><ul>";
                while ($row = mysqli_fetch_assoc($result)) {
                    // Display the results
                    echo "<li>" . $row["column_name"] . "</li>";
                }
                echo "</ul></div>";
            } else {
                echo "<div class='results'><p>No results found.</p></div>";
            }
            
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-container {
            margin-bottom: 20px;
        }
        #searchInput {
            width: 70%;
            padding: 10px;
            font-size: 16px;
        }
        #searchButton {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #searchButton:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" id="searchInput" name="search" placeholder="Enter your search...">
            <button type="submit" id="searchButton">Search</button>
        </form>
    </div>

    <?php
    // Check if the form has been submitted
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        
        // Clean and validate the search term
        $search = htmlspecialchars($search);
        if ($search == "") {
            echo "<p style='color:red;'>Please enter a search term.</p>";
        } else {
            // In a real application, you would query your database here
            // For this example, we'll just display the search results as text
            
            // Simulated data
            $data = array("Apple", "Banana", "Cherry", "Date", "Elderberry", "Fig", "Grape");
            
            echo "<div class='results'>";
            echo "<h3>Results for: '$search'</h3>";
            
            if (!empty($data)) {
                foreach ($data as $item) {
                    // Check if the item contains the search term
                    if (stripos($item, $search) !== false) {
                        echo "<p>$item</p>";
                    }
                }
            } else {
                echo "<p>No results found.</p>";
            }
            
            echo "</div>";
        }
    }
    ?>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        
        .search-input {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="query" class="search-input" placeholder="Search here..." autocomplete="off">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <?php
    // Check if search query is provided
    if (isset($_GET['query'])) {
        $searchQuery = $_GET['query'];
        
        try {
            // Here you would typically connect to a database and perform a search
            // For this example, we'll just display the search results message
            echo "<h3>Search Results for: " . htmlspecialchars($searchQuery) . "</h3>";
            
            // You can add your database query logic here
            
        } catch (Exception $e) {
            echo "Error occurred while searching: " . $e->getMessage();
        }
    }
    
    if (empty($_GET['query'])) {
        echo "<p>Please enter a valid search term.</p>";
    }
    ?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-container {
            margin-bottom: 20px;
        }

        #searchForm {
            display: flex;
            gap: 10px;
        }

        #searchInput {
            flex-grow: 1;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        button[type="submit"] {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <?php
    // Check if search query is provided
    if (isset($_GET['query'])) {
        $searchQuery = $_GET['query'];
        
        // Database connection details
        $host = 'localhost';  // Replace with your host
        $username = 'root';    // Replace with your database username
        $password = '';       // Replace with your database password
        $database = 'test_db'; // Replace with your database name
        
        // Connect to database
        $conn = new mysqli($host, $username, $password, $database);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Prepare SQL query to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM your_table_name WHERE column_name LIKE ?");
        $searchTerm = "%" . $searchQuery . "%";
        $stmt->bind_param("s", $searchTerm);
        
        // Execute the query
        $stmt->execute();
        
        // Get result set
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<div class='results'><h3>Search Results:</h3>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='result-item'>";
                // Display relevant data from your table
                echo "ID: " . $row['id'] . "<br />";
                echo "Name: " . $row['name'] . "<br />";
                // Add more fields as needed
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>No results found for your search.</p>";
        }
        
        // Close database connection
        $conn->close();
    }
    ?>

    <form class="search-container" id="searchForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
        <input type="text" id="searchInput" name="query" placeholder="Search here...">
        <button type="submit">Search</button>
    </form>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .search-container {
            max-width: 600px;
            margin: 0 auto;
        }
        #searchForm {
            margin-bottom: 20px;
        }
        #searchInput {
            width: 80%;
            padding: 10px;
            font-size: 16px;
        }
        #searchButton {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        #searchButton:hover {
            background-color: #45a049;
        }
        .result-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .result-table th, .result-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Search form
        echo "<form id='searchForm' action='search.php' method='get'>";
        echo "Search: <input type='text' name='search' id='searchInput' placeholder='Enter search query...'>";
        echo "<input type='submit' id='searchButton' value='Search'>";
        echo "</form>";
        ?>

        <?php
        // Display results if search is performed
        if (isset($_GET['search'])) {
            $searchQuery = $_GET['search'];
            
            // Sanitize input to prevent SQL injection
            $searchQuery = htmlspecialchars($searchQuery);
            
            // Database connection details
            $host = 'localhost';
            $dbUsername = 'root';
            $dbPassword = '';
            $dbName = 'your_database_name';

            // Create database connection
            $conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);

            // Check if connection is successful
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // SQL query to search in the table
            $sql = "SELECT * FROM your_table_name WHERE column1 LIKE ? OR column2 LIKE ?";
            $searchTerm = "%$searchQuery%";
            
            // Prepare and bind
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ss', $searchTerm, $searchTerm);

            // Execute the statement
            mysqli_execute($stmt);

            // Get result
            $result = mysqli_stmt_get_result($stmt);

            // Display results in a table
            echo "<table class='result-table'>";
            if (mysqli_num_rows($result) > 0) {
                echo "<tr>";
                // Create header based on columns
                while ($fieldInfo = mysqli_fetch_field($result)) {
                    echo "<th>" . $fieldInfo->name . "</th>";
                }
                echo "</tr>";
                
                // Output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>" . $value . "</td>";
                    }
                    echo "</tr>";
                }
            } else {
                // No results found
                echo "<tr><td colspan='3'>No results found.</td></tr>";
            }
            echo "</table>";

            // Close database connection
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<?php
// Connect to database
$host = 'localhost';
$username = 'username';
$password = 'password';
$database = 'myDB';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term from form submission
$searchTerm = $_POST['query'];

// Sanitize the input to prevent SQL injection
$safeSearchTerm = mysqli_real_escape_string($conn, htmlspecialchars($searchTerm));

// Search query
$sql = "SELECT * FROM users WHERE name LIKE '%" . $safeSearchTerm . "%'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Output the results
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<p>" . $row['name'] . "</p>";
    }
} else {
    echo "No results found";
}

// Close database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            background-color: #f9f9f9;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form class="search-bar" method="POST" action="">
            <input type="text" name="query" placeholder="Search..." required>
            <button type="submit">Search</button>
        </form>

        <?php
            // Search processing script
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $query = $_POST['query'];

                // Sanitize the input to prevent SQL injection and XSS attacks
                $query = htmlspecialchars($query, ENT_QUOTES, 'UTF-8');

                // Connect to the database (replace with your database connection details)
                try {
                    $db = new PDO('sqlite:search.db');
                    
                    // Create a table if it doesn't exist
                    $db->exec("CREATE TABLE IF NOT EXISTS data (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        title TEXT,
                        description TEXT
                    )");

                    // Insert some sample data (only run once)
                    $sampleData = [
                        ['title' => 'Sample 1', 'description' => 'This is a sample description for item 1'],
                        ['title' => 'Sample 2', 'description' => 'Another sample description for item 2'],
                        ['title' => 'Sample 3', 'description' => 'Yet another sample description for item 3']
                    ];

                    foreach ($sampleData as $data) {
                        try {
                            $db->exec("INSERT INTO data (title, description) VALUES (?, ?)",
                                [$data['title'], $data['description']]);
                        } catch(PDOException $e) {}
                    }

                    // Search query
                    $stmt = $db->prepare("SELECT * FROM data WHERE title LIKE :query OR description LIKE :query");
                    $searchQuery = "%$query%";
                    $stmt->bindParam(':query', $searchQuery);
                    $stmt->execute();

                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (empty($results)) {
                        echo '<div class="search-results">';
                        echo '<p>No results found for: "' . $query . '"</p>';
                        echo '</div>';
                    } else {
                        echo '<div class="search-results">';
                        foreach ($results as $result) {
                            echo '<div class="result-item">';
                            echo '<h3>' . $result['title'] . '</h3>';
                            echo '<p>' . $result['description'] . '</p>';
                            echo '</div>';
                        }
                        echo '</div>';
                    }

                } catch(PDOException $e) {
                    die("Connection failed: " . $e->getMessage());
                }
            }
        ?>
    </div>
</body>
</html>


<?php
// search_results.php

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search term from the POST request
    $search_term = $_POST['search'];
    
    // Trim whitespace and escape special characters
    $search_term = trim($search_term);
    $search_term = htmlspecialchars($search_term);

    // Here you would typically connect to a database and query your data
    // For this example, we'll use sample data
    $products = array(
        array('id' => 1, 'name' => 'Apple', 'description' => 'Red fruit', 'price' => '$1.00'),
        array('id' => 2, 'name' => 'Banana', 'description' => 'Yellow fruit', 'price' => '$0.50'),
        array('id' => 3, 'name' => 'Orange', 'description' => 'Citrus fruit', 'price' => '$1.50'),
    );

    // Search through the products
    $results = array();
    foreach ($products as $product) {
        if (stripos($product['name'], $search_term) !== false || stripos($product['description'], $search_term) !== false) {
            $results[] = $product;
        }
    }

    // Display the results
    echo "<h2>Search Results:</h2>";
    if (!empty($results)) {
        echo "<table border='1'>";
        echo "<tr><th>Name</th><th>Description</th><th>Price</th></tr>";
        foreach ($results as $result) {
            echo "<tr>";
            echo "<td>".$result['name']."</td>";
            echo "<td>".$result['description']."</td>";
            echo "<td>".$result['price']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No results found.</p>";
    }
}
?>


<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$dbname = "my_database";

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term from form input
$query = $_POST['query'];

// Escape special characters to prevent SQL injection
$query = mysqli_real_escape_string($conn, $query);

// Search query
$sql = "
SELECT * FROM users 
WHERE name LIKE '%$query%' 
OR email LIKE '%$query%'
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}

// Display results
echo "<h3>Search Results:</h3>";
echo "<table border='1'>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['name'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "</tr>";
}

echo "</table>";

// Close connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        
        .search-input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search" class="search-input" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <?php
    // Check if search parameter is set
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        
        // Display search results
        echo "<h3>Results for: " . htmlspecialchars($search) . "</h3>";
        
        // Add your actual search logic here
        // For this example, we'll just display the search term
    }
    ?>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-bar {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search_query" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
        
        <?php
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search_query = $_POST['search_query'];
            
            // Sanitize the input
            $search_query = htmlspecialchars($search_query);
            $search_query = trim($search_query);
            
            // Display search results
            echo "<div class='results'>";
            echo "<h3>Search Results for: '" . $search_query . "'</h3>";
            
            // Here you would typically connect to your database and fetch real results
            // For this example, we'll just display a simulated result
            
            $sample_data = array(
                "Apple",
                "Banana",
                "Cherry",
                "Orange",
                "Peach"
            );
            
            $results = array();
            foreach ($sample_data as $item) {
                if (stripos($item, $search_query) !== false) {
                    $results[] = $item;
                }
            }
            
            if (!empty($results)) {
                echo "<ul>";
                foreach ($results as $result) {
                    echo "<li>" . $result . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No results found.</p>";
            }
            
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    if (!empty($search)) {
        // Escape the search term to prevent SQL injection
        $search = $conn->real_escape_string($search);
        
        // Perform the search query
        $query = "SELECT * FROM your_table WHERE column_name LIKE '%{$search}%'";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            echo "<table>";
            while ($row = $result->fetch_assoc()) {
                // Display the results
                echo "<tr><td>" . $row['column_name'] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No results found.";
        }
    }
}

// Close connection
$conn->close();
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'search_db';

try {
    // Create database connection
    $conn = new mysqli($host, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $results = array();
    
    // If search form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
        // Get the search term
        $search_term = $_POST['query'];
        
        // Sanitize input to prevent SQL injection
        $search_term = $conn->real_escape_string($search_term);
        
        // Query database
        $sql = "SELECT * FROM search_table WHERE name LIKE '%{$search_term}%' OR description LIKE '%{$search_term}%'";
        
        if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
            $result->free();
        } else {
            throw new Exception("Query failed: " . $conn->error);
        }
    }

} catch (Exception $e) {
    // Handle any errors
    echo 'Error: ' . $e->getMessage();
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .search-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            background-color: white;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form class="search-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="query" placeholder="Search here..." <?php if (isset($search_term)) { echo "value='$search_term'"; } ?>>
            <button type="submit" name="submit">Search</button>
        </form>

        <?php if (!empty($results) && isset($_POST['submit'])): ?>
            <div class="results">
                <h3>Results:</h3>
                <?php foreach ($results as $row): ?>
                    <div class="result-item">
                        <h4><?php echo $row['name']; ?></h4>
                        <p><?php echo $row['description']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (isset($_POST['submit'])): ?>
            <div class="results">
                <p>No results found.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>


<?php
// Connect to the database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'my_database';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    $search_term = $_POST['search'];
    
    // Escape special characters to prevent SQL injection
    $search_term = mysqli_real_escape_string($conn, $search_term);
    
    // Search in the database
    $query = "SELECT * FROM my_table WHERE 
              column1 LIKE '%{$search_term}%' OR 
              column2 LIKE '%{$search_term}%'";
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    // Display results
    echo "<table border='1'>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['column1'] . "</td>";
        echo "<td>" . $row['column2'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" name="search" placeholder="Search...">
        <input type="submit" name="submit" value="Search">
    </form>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-container {
            margin-bottom: 30px;
        }

        #searchForm {
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            padding: 10px;
            width: 70%;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php
    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search_query = $_POST['search_query'];

        // Sanitize the input
        $search_query = htmlspecialchars($search_query);

        // Database connection parameters
        $host = 'localhost';
        $username = 'your_username';
        $password = 'your_password';
        $database = 'your_database';

        try {
            // Create connection
            $conn = mysqli_connect($host, $username, $password, $database);
            
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // SQL query to search for the term in the database
            $sql = "SELECT * FROM your_table WHERE name LIKE '%" . mysqli_real_escape_string($conn, $search_query) . "%'";
            
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                // Output the results
                echo "<div class='results'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "Name: " . $row['name'] . "<br>";
                    // Add more fields as needed
                }
                echo "</div>";
            } else {
                echo "<div class='results'>";
                echo "No results found for: " . $search_query;
                echo "</div>";
            }

            // Close the database connection
            mysqli_close($conn);
        } catch (Exception $e) {
            die("An error occurred: " . $e->getMessage());
        }
    }
    ?>

    <form class="search-container" id="searchForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="search_query" placeholder="Search..." required>
        <input type="submit" value="Search">
    </form>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add some basic CSS styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        /* Style for search results */
        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <?php
    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search_term = $_POST['search'];

        // Sanitize the input to prevent SQL injection
        $search_term = htmlspecialchars($search_term);

        // Database connection details
        $host = 'localhost';
        $username = 'your_username';
        $password = 'your_password';
        $database = 'your_database';

        // Connect to database
        $conn = mysqli_connect($host, $username, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // SQL query to search for the term
        $sql = "SELECT * FROM your_table WHERE column_name LIKE ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $search_term);
        
        // Add % signs around the search term for partial matching
        $search_term = "%$search_term%";
        
        // Execute query
        mysqli_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Display results
        if ($result->num_rows > 0) {
            echo "<div class='results'>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='result-item'>";
                // Output your result fields here, for example:
                echo "<h3>" . $row['title'] . "</h3>";
                echo "<p>" . $row['description'] . "</p>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>No results found.</p>";
        }

        // Close database connection
        mysqli_close($conn);
    }
    ?>

    <div class="search-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search" class="search-box" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <!-- Add some basic CSS styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Check if the form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search_query = $_POST['search_query'];
            
            // Sanitize the input to prevent SQL injection or XSS attacks
            $search_query = htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8');
            
            // Here you would typically connect to your database and perform a search query
            
            // For this example, we'll just display the search query
            echo "<h3>Your search query: " . $search_query . "</h3>";
        }
        ?>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="search_query" class="search-box" placeholder="Search here...">
            <br>
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


// Connect to your database
$mysqli = new mysqli("localhost", "username", "password", "database_name");

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}

// Prepare and bind the statement
$stmt = $mysqli->prepare("SELECT * FROM your_table WHERE column LIKE ?");
$stmt->bind_param("s", $search_query);

// Add % signs around the search query for partial matches
$search_query = "%" . $search_query . "%";

if ($stmt->execute()) {
    // Get the results
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        // Display your results here
        echo "<p>" . $row['column_name'] . "</p>";
    }
} else {
    echo "Error executing query: (" . $mysqli->errno . ") " . $mysqli->error;
}

// Close the statement and connection
$stmt->close();
$mysqli->close();


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>

<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'your_database';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $database);

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term from form submission
if (isset($_POST['submit'])) {
    $search_term = mysqli_real_escape_string($conn, $_POST['search']);

    // Search query
    $query = "SELECT * FROM your_table WHERE ";
    
    // Add conditions for each column you want to search in
    $columns = ['column1', 'column2', 'column3'];
    $conditions = array();
    foreach ($columns as $column) {
        $conditions[] = "$column LIKE '%$search_term%'";
    }
    
    $query .= implode(' OR ', $conditions);
    
    // Execute the query
    $result = mysqli_query($conn, $query);

    // Display results
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Column1</th><th>Column2</th><th>Column3</th></tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['column1'] . "</td>";
            echo "<td>" . $row['column2'] . "</td>";
            echo "<td>" . $row['column3'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "No results found.";
    }
}
?>

<!-- Search form -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit" name="submit">Search</button>
</form>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        /* Add some basic styling */
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-box {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
        }

        .result-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <?php
    // Database connection parameters
    $host = 'localhost';
    $username = 'root';  // Replace with your database username
    $password = '';      // Replace with your database password
    $database = 'mydb';   // Replace with your database name

    // Connect to the database
    $connection = mysqli_connect($host, $username, $password, $database);

    // Check connection
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // If search form is submitted
    if (isset($_POST['search'])) {
        $search_term = $_POST['search_term'];
        
        // Sanitize the input to prevent SQL injection
        $search_term = mysqli_real_escape_string($connection, $search_term);

        // Query to get results from database
        $sql = "SELECT * FROM your_table WHERE 
                column1 LIKE '%$search_term%' OR 
                column2 LIKE '%$search_term%'";

        $result = mysqli_query($connection, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo "<div class='results'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='result-item'>";
                    // Display the search results
                    // Modify this part according to your table structure
                    echo "<h3>" . $row['column1'] . "</h3>";
                    echo "<p>" . $row['column2'] . "</p>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p>No results found.</p>";
            }
        } else {
            echo "Error: " . mysqli_error($connection);
        }

        // Close the result set
        mysqli_free_result($result);
    }

    // Close the database connection
    mysqli_close($connection);
    ?>

    <div class="search-container">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search_term" class="search-box" placeholder="Search...">
            <button type="submit" name="search" class="search-button">Search</button>
        </form>
    </div>

</body>
</html>


if (isset($_POST['search'])) {
    $search_term = $_POST['search_term'];

    $sql = "SELECT * FROM your_table WHERE 
            column1 LIKE ? OR 
            column2 LIKE ?";
    
    if ($stmt = mysqli_prepare($connection, $sql)) {
        // Bind parameters
        $search_term = '%' . $search_term . '%';
        mysqli_stmt_bind_param($stmt, 'ss', $search_term, $search_term);

        // Execute statement
        mysqli_stmt_execute($stmt);
        
        // Get result set
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                // Display results as before...
            }
        }
    }

    // Close the statement and connection
    mysqli_stmt_close($stmt);
}


<?php
// Connect to database
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search query from input
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Sanitize the input to prevent SQL injection
$query = mysqli_real_escape_string($conn, $query);

// Search query
$sql = "SELECT * FROM your_table WHERE name LIKE '%" . $query . "%' OR description LIKE '%" . $query . "%'";
$result = $conn->query($sql);

// Display results
echo "<h2>Search Results</h2>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='search-result'>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo "<p>" . $row['description'] . "</p>";
        echo "</div>";
    }
} else {
    echo "No results found.";
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
        .search-result {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="query" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Connect to database
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = isset($_GET['query']) ? $_GET['query'] : '';

$stmt = $conn->prepare("SELECT * FROM your_table WHERE name LIKE ? OR description LIKE ?");
$search = '%' . $query . '%';
$stmt->bind_param('ss', $search, $search);

$stmt->execute();
$result = $stmt->get_result();

// Display results...
?>


<?php
// Connect to your database
$connection = new mysqli('localhost', 'username', 'password', 'database_name');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Handle search form submission
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
    $search = $_GET['search'];
    // Search query
    $query = "SELECT * FROM your_table WHERE column_name LIKE '%" . $connection->real_escape_string($search) . "%'";
    
    // Execute the query
    $result = $connection->query($query);
    
    // Display results
    if ($result->num_rows > 0) {
        echo "<h2>Search Results:</h2>";
        while ($row = $result->fetch_assoc()) {
            // Display your results here
            echo "<div>" . $row['column_name'] . "</div>";
        }
    } else {
        echo "<p>No results found.</p>";
    }
}

// Close the database connection
$connection->close();
?>


// autocomplete.php

<?php
// Connect to your database
$connection = new mysqli('localhost', 'username', 'password', 'database_name');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Get search term
 searchTerm = isset($_GET['term']) ? $_GET['term'] : '';

// Query database for suggestions
$query = "SELECT column_name FROM your_table WHERE column_name LIKE '%" . $connection->real_escape_string(searchTerm) . "%'";
$result = $connection->query($query);

// Return results as JSON
$suggestions = array();
while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row['column_name'];
}

echo json_encode($suggestions);

// Close the database connection
$connection->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
        }
        
        #searchInput {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        #searchButton {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        #searchButton:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" id="searchInput" name="search" placeholder="Search...">
            <button type="submit" id="searchButton">Search</button>
        </form>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchTerm = $_POST['search'];
            
            // Sample data to search from
            $items = array(
                "Apple",
                "Banana",
                "Cherry",
                "Date",
                "Elderberry",
                "Fig",
                "Grape",
                "Kiwi",
                "Lemon",
                "Mango"
            );
            
            // Search logic
            $results = array();
            foreach ($items as $item) {
                if (stripos($item, $searchTerm) !== false) {
                    array_push($results, $item);
                }
            }
            
            // Display results
            if (!empty($results)) {
                echo "<div class='results'>";
                echo "<h3>Results:</h3>";
                echo "<ul>";
                foreach ($results as $result) {
                    echo "<li>" . $result . "</li>";
                }
                echo "</ul>";
                echo "</div>";
            } else {
                if (!empty($searchTerm)) {
                    echo "<div class='results'>";
                    echo "No results found!";
                    echo "</div>";
                }
            }
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        
        .search-container {
            max-width: 600px;
            margin: 0 auto;
        }
        
        #searchInput {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        #results {
            margin-top: 20px;
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = $_POST['search'];
    
    // Simulate some data to search through
    $sampleData = array(
        "apple", 
        "banana",
        "orange",
        "grape",
        "kiwi",
        "melon"
    );
    
    // Filter results based on search term
    $results = array();
    foreach ($sampleData as $item) {
        if (strpos($item, $searchTerm) !== false) {
            $results[] = $item;
        }
    }
}
?>

<div class="search-container">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" id="searchInput" name="search" placeholder="Search here...">
        <br><br>
        <input type="submit" value="Search">
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
    <div id="results">
        <?php
            if (!empty($results)) {
                echo "<h3>Results:</h3>";
                foreach ($results as $result) {
                    echo "- $result<br>";
                }
            } else {
                echo "<p>No results found.</p>";
            }
        ?>
    </div>
    <?php } ?>
</div>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        /* Add some basic styling */
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .result {
            margin-top: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search form -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
            <input type="text" name="search" class="search-box" placeholder="Search here...">
            <button type="submit">Search</button>
        </form>

        <?php
        // Check if search parameter is set
        if (isset($_GET['search'])) {
            $search = trim($_GET['search']); // Get and trim the search value

            // If search value is not empty
            if (!empty($search)) {
                // Database connection details
                $host = "localhost";
                $db_username = "root"; // Change to your database username
                $db_password = "";    // Change to your database password
                $database_name = "test_db"; // Change to your database name

                try {
                    // Connect to the database
                    $conn = new mysqli($host, $db_username, $db_password, $database_name);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // SQL query to search for matching results
                    $sql = "SELECT * FROM your_table_name WHERE column LIKE ?";
                    $stmt = $conn->prepare($sql);

                    // Bind the parameter and execute the statement
                    $search_term = "%$search%";
                    $stmt->bind_param("s", $search_term);
                    $stmt->execute();

                    // Get the result set
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // Display the results
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='result'>";
                            // Display your desired output, for example:
                            echo "ID: " . $row['id'] . "<br>";
                            echo "Name: " . $row['name'] . "<br>";
                            echo "</div>";
                        }
                    } else {
                        // If no results found
                        echo "<div class='result'>";
                        echo "No results found for your search.";
                        echo "</div>";
                    }

                    // Close the database connection
                    $conn->close();
                } catch (mysqli_sql_exception $e) {
                    // Handle any SQL errors
                    die("SQL Error: " . $e->getMessage());
                }
            }
        }
        ?>
    </div>
</body>
</html>


<?php
// search.php

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'test_db';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if search form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the search term
    $search_term = $_POST['query'];
    
    // Sanitize the input
    $search_term = trim($search_term);
    $search_term = mysqli_real_escape_string($conn, $search_term);

    // Search query
    $sql = "SELECT * FROM your_table WHERE column_name LIKE '%$search_term%'";
    $result = mysqli_query($conn, $sql);

    // Display results
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>" . $row['column_name'] . "</p>";
        }
    } else {
        echo "No results found.";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-container {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="query" placeholder="Search here...">
            <button type="submit">Search</button>
        </form>
    </div>

    <?php
    // This section will display the results if any are found
    if (isset($result) && mysqli_num_rows($result) > 0) {
        echo "<h3>Results:</h3>";
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>" . $row['column_name'] . "</li>";
        }
        echo "</ul>";
    } elseif (isset($result)) {
        echo "<p>No results found for your search.</p>";
    }
    ?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-container input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin-right: 10px;
        }
        
        .search-container input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-container input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
            // Check if form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
                $searchTerm = $_GET['search'];
                
                try {
                    // Sanitize the input to prevent SQL injection or XSS attacks
                    $sanitizedSearchTerm = htmlspecialchars(trim($searchTerm));
                    
                    // Display the search term back to the user
                    echo "<p>You searched for: <strong>" . $sanitizedSearchTerm . "</strong></p>";
                    
                    // Here you can add your database query logic
                    
                } catch (Exception $e) {
                    // Handle any exceptions
                    echo "An error occurred while processing your search.";
                }
            }
        ?>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="search" placeholder="Search here...">
            <input type="submit" value="Search">
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
        }
        
        .search-container {
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search form -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="keyword" placeholder="Search here..." <?php if(isset($_GET['keyword'])) { echo 'value="'.$_GET['keyword'].'"'; } ?>>
            <button type="submit">Search</button>
        </form>
    </div>

    <?php
    // Search results section
    if (isset($_GET['keyword']) && $_GET['keyword'] !== '') {
        $search_term = htmlspecialchars($_GET['keyword']);
        
        // Connect to your database here
        // For this example, we'll use a simple array of data
        $sample_data = array(
            array('id' => 1, 'name' => 'Product 1', 'description' => 'This is product one'),
            array('id' => 2, 'name' => 'Product 2', 'description' => 'This is product two'),
            array('id' => 3, 'name' => 'Product 3', 'description' => 'This is product three')
        );
        
        // Search logic
        $results = array();
        foreach ($sample_data as $item) {
            if (stripos($item['name'], $search_term) !== false || 
                stripos($item['description'], $search_term) !== false) {
                $results[] = $item;
            }
        }
        
        // Display results
        echo "<div class='results'>";
        if (!empty($results)) {
            foreach ($results as $result) {
                echo "<h3>" . $result['name'] . "</h3>";
                echo "<p>" . $result['description'] . "</p>";
            }
        } else {
            echo "<p>No results found for: " . $search_term . "</p>";
        }
        echo "</div>";
    }
    ?>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Perform the search query
if (isset($_GET['keyword']) && $_GET['keyword'] !== '') {
    $search_term = mysqli_real_escape_string($conn, $_GET['keyword']);
    
    // Query to search in both name and description columns
    $query = "
        SELECT * FROM products 
        WHERE name LIKE '%{$search_term}%' 
        OR description LIKE '%{$search_term}%'
    ";
    
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>" . $row['description'] . "</p>";
        }
    } else {
        echo "<p>No results found for: " . $search_term . "</p>";
    }
    
    // Free result and close connection
    mysqli_free_result($result);
    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        #searchInput {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" id="searchInput" name="query" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <?php
    // Process the search query if submitted
    if (isset($_GET['query'])) {
        $searchQuery = htmlspecialchars(trim($_GET['query']));
        
        // Here you would typically connect to a database and perform a search query
        
        echo "<h3>Search Results for: " . $searchQuery . "</h3>";
        // Add your search results display logic here
    }
    ?>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query = isset($_GET['query']) ? $_GET['query'] : '';
    
    // Display search form
    echo '<div class="search-container">';
    echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="get">';
    echo '<input type="text" name="query" class="search-box" placeholder="Search here..." value="' . htmlspecialchars($query) . '">';
    echo '<button type="submit" class="search-button">Search</button>';
    echo '</form>';
    
    // Display search results
    if ($query !== '') {
        echo '<h3>Results for: "' . htmlspecialchars($query) . '"</h3>';
        
        // Here you would typically connect to a database and fetch real results
        // For this example, we'll just display the search query
        echo '<p>Your search results for "' . htmlspecialchars($query) . '" will appear here.</p>';
    } else {
        echo '<p>Please enter something to search.</p>';
    }
    
    echo '</div>';
}
?>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search" class="search-box" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search = trim($_POST['search']);
        
        // Sanitize the input
        $search = htmlspecialchars($search);
        
        // Here you would typically connect to a database and perform a search query
        // For this example, we'll just display what was searched
        echo "<h3>Search Results for: " . $search . "</h3>";
        
        // Example of displaying results (you would replace this with actual search results)
        $results = array(
            "Example Result 1",
            "Example Result 2",
            "Example Result 3"
        );
        
        if (!empty($results)) {
            foreach ($results as $result) {
                echo "<div style='padding: 5px; border-bottom: 1px solid #ddd;'>" . $result . "</div>";
            }
        } else {
            echo "<p>No results found for your search.</p>";
        }
    }
    ?>
</body>
</html>


<?php
// Get the search query from the GET request
$query = isset($_GET['query']) ? $_GET['query'] : '';

if (!empty($query)) {
    // For this example, we'll use a sample array of data
    $sample_data = array(
        array('id' => 1, 'name' => 'Apple', 'description' => 'A fruit'),
        array('id' => 2, 'name' => 'Banana', 'description' => 'Another fruit'),
        array('id' => 3, 'name' => 'Carrot', 'description' => 'A vegetable')
    );
    
    // Search through the sample data
    $results = array();
    foreach ($sample_data as $item) {
        if (strpos(strtolower($item['name']), strtolower($query)) !== false ||
            strpos(strtolower($item['description']), strtolower($query)) !== false) {
            $results[] = $item;
        }
    }
    
    // Display the results
    echo "<h2>Search Results:</h2>";
    if (!empty($results)) {
        foreach ($results as $result) {
            echo "<div style='margin-bottom: 10px;'>";
            echo "<strong>{$result['name']}</strong><br />";
            echo "{$result['description']}<br />";
            echo "</div>";
        }
    } else {
        echo "No results found for your query.";
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
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-form input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 16px;
        }
        
        .search-form button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        
        .results {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        $connection = new mysqli("localhost", "username", "password", "database_name");

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        if (isset($_GET['query'])) {
            $query = $_GET['query'];
            $search_query = $connection->real_escape_string(trim($query));

            // Assuming you have a table named 'users' with columns 'id', 'name', and 'email'
            $sql = "SELECT id, name, email FROM users WHERE name LIKE '%$search_query%'";
            $result = $connection->query($sql);

            if ($result) {
                echo "<div class='results'>";
                echo "<h3>Search Results:</h3>";
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='result-item'>";
                    echo "ID: " . $row['id'] . "<br/>";
                    echo "Name: " . $row['name'] . "<br/>";
                    echo "Email: " . $row['email'];
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "Error executing query: " . $connection->error;
            }

        } else {
            // Display search form
            ?>
            <form class="search-form" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="text" name="query" placeholder="Enter search term...">
                <button type="submit">Search</button>
            </form>
        <?php } ?>

        <?php $connection->close(); ?>
    </div>
</body>
</html>


<?php
// This file will handle the search functionality

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = $_POST['search'];

    // Sanitize input to prevent SQL injection
    $search_query = htmlspecialchars($search_query);
    
    // Display user message or results based on search query
    echo "<h3>Search Results for: <em>" . $search_query . "</em></h3>";
    
    // Connect to the database
    $connection = mysqli_connect("localhost", "username", "password", "database_name");
    
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Query the database for search results
    $sql = "SELECT * FROM your_table WHERE column LIKE '%" . $search_query . "%'";
    $result = mysqli_query($connection, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Output each row as a result
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='search-result'>";
            echo "<h4>" . $row['column'] . "</h4>";
            // Display other relevant data from the table
            echo "</div>";
        }
    } else {
        echo "No results found for: " . $search_query;
    }
    
    // Close database connection
    mysqli_close($connection);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <!-- Add some CSS styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #45a049;
        }
        
        .search-results {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="search-container">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="search-form">
            <input type="text" name="search" placeholder="Search..." required>
            <button type="submit">Search</button>
        </div>
    </form>

    <?php
    // Check if the form was submitted and display results
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search_query = $_POST['search'];
        
        // Display search results
        echo "<div class='search-results'>";
        echo "<h3>Search Results for: <em>" . htmlspecialchars($search_query) . "</em></h3>";
        
        // Add your database connection and query here to display actual results
        // This example just displays a sample result
        
        $sample_results = array(
            "Result 1",
            "Result 2",
            "Result 3"
        );
        
        if (!empty($sample_results)) {
            foreach ($sample_results as $result) {
                echo "<div class='search-result'>";
                echo "<p>" . $result . "</p>";
                echo "</div>";
            }
        } else {
            echo "No results found for: " . htmlspecialchars($search_query);
        }
        
        echo "</div>";
    }
    ?>
</div>

</body>
</html>


<?php
// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$database = "test_db";

try {
    // Create database connection
    $conn = new mysqli($host, $username, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Get search query from POST request
    $searchQuery = isset($_POST['query']) ? $_POST['query'] : '';
    
    // Sanitize the input to prevent SQL injection
    $searchQuery = mysqli_real_escape_string($conn, $searchQuery);

    // SQL query to search in database table
    $sql = "SELECT * FROM your_table_name WHERE title LIKE '%{$searchQuery}%'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<ul>";
        while($row = $result->fetch_assoc()) {
            echo "<li>".$row['title']."</li>";
        }
        echo "</ul>";
    } else {
        echo "No results found.";
    }

} catch (Exception $e) {
    // Handle errors
    die("An error occurred: " . $e->getMessage());
}

// Close database connection
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="search.php" method="post">
            <input type="text" name="search_term" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

<?php
// search.php

// Database connection details
$host = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize input
function sanitise_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = sanitise_input($_POST['search_term']);

    // SQL query to search for the term
    $sql = "SELECT * FROM your_table_name WHERE column_name LIKE ?";
    
    // Prepare statement
    $stmt = $conn->prepare($sql);
    $search_term = "%$search_term%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='results'>";
        while ($row = $result->fetch_assoc()) {
            // Display your results here
            echo "<p>" . $row['column_name'] . "</p>";
        }
        echo "</div>";
    } else {
        echo "<div class='results'><p>No results found.</p></div>";
    }

    // Close statement and connection
    $stmt->close();
}

$conn->close();
?>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            margin-right: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
        }

        .search-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            background-color: white;
            border: 1px solid #ddd;
            margin-bottom: 5px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="search_query" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if the form has been submitted
        if (isset($_POST['search_query'])) {
            $searchQuery = $_POST['search_query'];
            
            // Sanitize the input
            $searchQuery = htmlspecialchars(trim($searchQuery));

            // Here you would typically connect to a database and query your data
            // For this example, we'll just display some sample results
            
            echo "<div class='results'>";
            echo "<h3>Search Results for: '$searchQuery'</h3>";
            
            // Example dataset
            $data = array(
                "PHP Tutorial",
                "Web Development Guide",
                "Programming Basics",
                "Learn MySQL",
                "JavaScript Fundamentals"
            );

            foreach ($data as $item) {
                if (stripos($item, $searchQuery) !== false) {
                    echo "<div class='result-item'>";
                    echo "<h4>$item</h4>";
                    // You can add more information here like description and link
                    echo "</div>";
                }
            }

            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add some basic CSS styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-box {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 5px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="search" class="search-box" placeholder="Search here...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search = $_POST['search'];
            
            // Clean and validate the input
            $search = trim($search);
            $search = htmlspecialchars($search);
            $search = stripslashes($search);

            echo "<h3>Search Results for: '$search'</h3>";
            
            // Here you would typically connect to your database and run a query
            // For demonstration purposes, let's assume we're searching in a MySQL database
            try {
                $conn = new mysqli('localhost', 'username', 'password', 'database_name');
                
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // SQL query with search term (make sure to sanitize input in real application)
                $sql = "SELECT * FROM your_table WHERE column LIKE '%" . $search . "%'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div style='margin: 10px 0; padding: 10px; background-color: #fff; border-radius: 4px;'>";
                        // Display your results here
                        echo "<p>" . $row['column_name'] . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p style='color: red;'>No results found.</p>";
                }

                $conn->close();
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
            }
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <!-- Add some basic styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .search-box {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .results {
            margin-top: 20px;
            padding: 10px;
        }
        
        .no-results {
            color: red;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Check if the form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize the input to prevent SQL injection or XSS attacks
            $searchTerm = htmlspecialchars($_POST['search_term'], ENT_QUOTES, 'UTF-8');
            
            // Here you would typically connect to your database and perform a query
            // For this example, we'll use some sample data
            $sampleData = array(
                "Apple",
                "Banana",
                "Cherry",
                "Orange",
                "Grape",
                "Mango"
            );
            
            // Search through the sample data
            $results = array();
            foreach ($sampleData as $item) {
                if (stripos($item, $searchTerm) !== false) {
                    $results[] = $item;
                }
            }
        } else {
            // If form not submitted, set default values
            $searchTerm = "";
            $results = array();
        }
        ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="text" name="search_term" class="search-box"
                   placeholder="Search..." value="<?php echo $searchTerm;?>">
            <button type="submit" class="search-button">Search</button>
        </form>
        
        <?php if (!empty($results)): ?>
            <div class="results">
                <h3>Results:</h3>
                <ul>
                    <?php foreach ($results as $result): ?>
                        <li><?php echo $result; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($searchTerm !== ""): ?>
            <div class="no-results">
                No results found for "<?php echo $searchTerm; ?>"
            </div>
        <?php endif; ?>
    </div>
</body>
</html>


// Database connection details
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to search for the term
$sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $searchTerm . "%'";
$result = $conn->query($sql);

// Output results
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<li>" . $row['column_name'] . "</li>";
    }
} else {
    echo "No results found.";
}

// Close the database connection
$conn->close();


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="search_term" class="search-bar" placeholder="Search here...">
            <button type="submit" name="submit" class="search-button">Search</button>
        </form>
        
        <?php
        // Check if form is submitted
        if (isset($_POST['submit'])) {
            $search_term = htmlspecialchars($_POST['search_term']);
            
            // Here you can add your search logic
            // For example, query a database or perform some operations
            
            echo "<h3>Search Results for: " . $search_term . "</h3>";
            // Add your results display logic here
        }
        ?>
    </div>
</body>
</html>


<?php
// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $search_term = mysqli_real_escape_string($conn, $_POST['search_term']);
    
    // Query the database
    $sql = "SELECT * FROM your_table WHERE column LIKE '%$search_term%'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='search-result'>";
            echo "<h3>" . $row['title'] . "</h3>";
            echo "<p>" . $row['description'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "No results found.";
    }
}
?>


<?php
// Connect to database
$connection = mysqli_connect("localhost", "username", "password", "database_name");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = $_POST['search'];

    // Sanitize input
    $search_term = trim($search_term);
    $search_term = htmlspecialchars($search_term, ENT_QUOTES);

    // Escape special characters
    $search_term = mysqli_real_escape_string($connection, $search_term);

    // SQL query to search for the term in the database
    $sql = "SELECT * FROM table_name 
            WHERE field1 LIKE '%$search_term%' 
            OR field2 LIKE '%$search_term%'";
            
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }

    // Display search results
    echo "<h3>Search Results:</h3>";
    echo "<table border='1'>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        foreach ($row as $field => $value) {
            if ($field == 'id') continue; // Skip the ID field
            echo "<td>" . htmlspecialchars($value, ENT_QUOTES) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";

    mysqli_free_result($result);
} else {
    // Display search form
    echo "<h3>Search Bar</h3>";
    echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
    echo "<input type='text' name='search' placeholder='Enter your search term...'>";
    echo "<br><br>";
    echo "<input type='submit' value='Search'>";
    echo "</form>";
}

// Close database connection
mysqli_close($connection);
?>


<?php
// Database configuration
$hostname = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

// Connect to database
$conn = mysqli_connect($hostname, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Search functionality
if (isset($_POST['submit'])) {
    // Get search term from form
    $search_term = $_POST['search'];
    
    // Sanitize input
    $search_term = trim($search_term);
    $search_term = mysqli_real_escape_string($conn, $search_term);

    // Query database
    $query = "SELECT * FROM your_table_name 
              WHERE column1 LIKE '%{$search_term}%' 
              OR column2 LIKE '%{$search_term}%' 
              OR column3 LIKE '%{$search_term}%'";
    
    $result = mysqli_query($conn, $query);

    // Display results
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='result'>";
            echo "ID: " . $row['id'] . "<br>";
            echo "Name: " . $row['name'] . "<br>";
            // Display other columns as needed
            echo "</div>";
        }
    } else {
        echo "No results found.";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-bar input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #45a049;
        }

        .result {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search...">
                <button type="submit" name="submit">Search</button>
            </div>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            // Results will be displayed here automatically
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-container {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = $_POST['search'];

    // Sanitize input to prevent SQL injection and XSS
    $searchTerm = trim($searchTerm);
    $searchTerm = htmlspecialchars($searchTerm, ENT_QUOTES);

    // Database connection details
    $host = 'localhost';
    $username = 'root'; // Change to your database username
    $password = '';     // Change to your database password
    $database = 'test_db'; // Change to your database name

    // Connect to the database
    $conn = mysqli_connect($host, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // SQL query to search for the term in the table
    $sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $searchTerm . "%'";
    
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<div class='results'><h3>Results:</h3>";
        while ($row = mysqli_fetch_assoc($result)) {
            // Display the results
            echo "<p>" . $row['column_name'] . "</p>";
        }
        echo "</div>";
    } else {
        echo "<div class='results'><p>No results found.</p></div>";
    }

    // Close database connection
    mysqli_close($conn);
}
?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="search-container">
            <input type="text" name="search" class="search-box" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </div>
    </form>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .results {
            list-style-type: none;
            padding: 0;
        }

        .result-item {
            padding: 8px;
            background-color: #fff;
            margin-bottom: 2px;
            cursor: pointer;
            border-radius: 4px;
        }

        .result-item:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <?php
    // Sample data - you can replace this with your actual database query
    $sampleData = array(
        "Apple",
        "Banana",
        "Cherry",
        "Date",
        "Grape",
        "Kiwi",
        "Mango",
        "Orange",
        "Peach",
        "Pear"
    );

    // Function to perform the search
    function search($query, $data) {
        $results = array();
        foreach ($data as $item) {
            if (stripos($item, $query) !== false) {
                array_push($results, $item);
            }
        }
        return $results;
    }

    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $query = isset($_GET['search']) ? $_GET['search'] : '';
        $results = search($query, $sampleData);
    } else {
        $results = array();
    }
    ?>

    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <input type="text" name="search" class="search-bar" placeholder="Search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" style="padding: 12px; background-color: #4CAF50; color: white; border: none; border-radius: 4px;">Search</button>
        </form>

        <?php if (!empty($results)): ?>
            <ul class="results">
                <?php foreach ($results as $result): ?>
                    <li class="result-item"><?php echo htmlspecialchars($result); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <?php if (!empty($query)): ?>
                <div style="padding: 10px; color: #999;">No results found for "<?php echo htmlspecialchars($query); ?>".</div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>


// Replace sample data with database connection
$connection = mysqli_connect("localhost", "username", "password", "database");
$query = mysqli_real_escape_string($connection, $_GET['search']);

// Perform database query
$result = mysqli_query($connection, "SELECT name FROM your_table WHERE name LIKE '%$query%'");
$results = array();
while ($row = mysqli_fetch_assoc($result)) {
    $results[] = $row['name'];
}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        /* Add some basic styling */
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar input {
            width: 70%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-btn:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="search-bar">
            <input type="text" name="search_term" placeholder="Search here...">
            <button type="submit" name="search" class="search-btn">Search</button>
        </form>

        <?php
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search_term = htmlspecialchars($_POST['search_term']);

            // Database connection details
            $host = 'localhost';
            $username = 'root'; // Change to your database username
            $password = ''; // Change to your database password
            $database = 'your_database'; // Change to your database name

            // Connect to database
            $conn = new mysqli($host, $username, $password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL query to fetch data based on search term
            $sql = "SELECT * FROM your_table WHERE name LIKE '%" . $search_term . "%'";
            
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<div class='results'>";
                while($row = $result->fetch_assoc()) {
                    // Display the results
                    echo "<p>" . $row['name'] . "</p>";
                }
                echo "</div>";
            } else {
                echo "<div class='results'><p>No results found.</p></div>";
            }

            // Close database connection
            $conn->close();
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .search-bar {
            width: 75%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<?php
// Get search term from form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_term = filter_var($_POST['search_term'], FILTER_SANITIZE_STRING);
} else {
    // If no search term, set default message
    $search_term = '';
}

// Example data for demonstration (you would typically fetch this from a database)
$products = array(
    "Apple",
    "Banana",
    "Cherry",
    "Date",
    "Elderberry",
    "Fig",
    "Grape",
    "Honeydew",
    "Kiwi",
    "Lemon"
);
?>

<div class="search-container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="search_term" class="search-bar" placeholder="Search products..." value="<?php if ($search_term) { echo htmlspecialchars($search_term); } ?>">
        <button type="submit" class="search-button">Search</button>
        <button type="button" class="search-button" onclick="clearSearch()">Clear</button>
    </form>

    <?php
    // Display search results or default message
    if ($search_term) {
        echo "<h3>Search Results for: '" . htmlspecialchars($search_term) . "'</h3>";
        echo "<ul>";
        foreach ($products as $product) {
            if (stripos($product, $search_term) !== false) {
                echo "<li>" . $product . "</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<h3>All Products</h3>";
        echo "<ul>";
        foreach ($products as $product) {
            echo "<li>" . $product . "</li>";
        }
        echo "</ul>";
    }
    ?>

</div>

<script>
function clearSearch() {
    document.querySelector('input[type="text"]').value = '';
    return false;
}
</script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 5px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: white;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchTerm = $_POST['search'];
        
        // Sanitize input
        $searchTerm = htmlspecialchars($searchTerm);
        
        // Database connection
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'test_db';
        
        try {
            $conn = new mysqli($host, $username, $password, $database);
            
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }
            
            // SQL query to search for matching records
            $sql = "SELECT * FROM products WHERE title LIKE ?";
            $stmt = $conn->prepare($sql);
            $searchTerm = "%$searchTerm%";
            $stmt->bind_param("s", $searchTerm);
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Display results
            if ($result->num_rows > 0) {
                echo "<div class='results'>";
                while ($row = $result->fetch_assoc()) {
                    echo "<h3>" . $row['title'] . "</h3>";
                    echo "<p>" . $row['description'] . "</p>";
                }
                echo "</div>";
            } else {
                echo "<div class='results'><p>No results found.</p></div>";
            }
            
            // Close connections
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
    ?>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="search-container">
            <input type="text" name="search" class="search-bar" placeholder="Search..." required>
            <button type="submit" class="search-button">Search</button>
        </div>
    </form>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 2rem auto;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 10px;
        }

        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 5px;
        }

        .search-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-btn:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 15px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php
    // Search query handling
    $search = isset($_GET['search']) ? $_GET['search'] : "";
    
    // Sanitize the input to prevent SQL injection
    $search = htmlspecialchars(trim($search));
    
    // Database connection parameters
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'test_db';

    // Connect to database
    $conn = mysqli_connect($host, $username, $password, $database);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if search query is not empty
    if ($search != "") {
        // SQL query with LIKE for partial matches
        $sql = "SELECT * FROM your_table WHERE column1 LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
        
        // You can add more columns to search in by adding OR conditions
        // For example: OR column2 LIKE '%...%'
        
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='results'>";
                echo "Result: " . $row['column1'] . "<br>";
                // Add more rows to display as needed
                echo "</div>";
            }
        } else {
            echo "<div class='results'>";
            echo "No results found.";
            echo "</div>";
        }
    }

    // Close database connection
    mysqli_close($conn);
    ?>

    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="search" class="search-bar" placeholder="Search here..." value="<?php echo $search; ?>">
            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .search-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="GET">
            <input type="text" name="query" class="search-box" placeholder="Search here...">
            <input type="submit" value="Search" style="padding:10px; font-size:16px;">
        </form>
        
        <?php
        if (isset($_GET['submit'])) {
            $search_query = trim($_GET['query']);
            
            if (!empty($search_query)) {
                // Here you would typically connect to a database and query your data
                // For this example, we'll use some sample data
                $example_data = array(
                    "Apple",
                    "Banana",
                    "Cherry",
                    "Date",
                    "Elderberry",
                    "Fig",
                    "Grapefruit"
                );
                
                echo "<div class='results'><h3>Search Results:</h3>";
                foreach ($example_data as $item) {
                    if (stripos($item, $search_query) !== false) {
                        echo "<p>$item</p>";
                    }
                }
                echo "</div>";
            } else {
                echo "<p>Please enter a search query.</p>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <!-- Add some basic CSS styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f0f0f0;
        }
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
        .result-item {
            padding: 10px;
            background-color: #f8f8f8;
            border-radius: 4px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form class="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="search" placeholder="Search..." <?php if (isset($_GET['search'])) { echo 'value="' . htmlspecialchars($_GET['search']) . '"'; } ?>>
            <button type="submit">Search</button>
        </form>

        <?php
        // Display results if search query is present
        if (isset($_GET['search'])) {
            $searchTerm = $_GET['search'];
            
            // Here you would typically connect to your database and fetch the results
            // For this example, we'll just use a sample array of data
            $sampleData = array(
                "Apple",
                "Banana",
                "Orange",
                "Grapes",
                "Mango",
                "Peach",
                "Cherry",
                "Strawberry"
            );
            
            // Search through the sample data and display matches
            echo "<div class=\"results\">";
            foreach ($sampleData as $item) {
                if (strtolower($item) == strtolower($searchTerm)) {
                    echo "<div class=\"result-item\">" . htmlspecialchars($item) . "</div>";
                }
            }
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .search-container {
            max-width: 600px;
            margin: 0 auto;
        }
        #searchBox {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        #results {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <input type="text" id="searchBox" placeholder="Search...">
        <div id="results"></div>
    </div>

    <script>
        const searchBox = document.getElementById('searchBox');
        const resultsDiv = document.getElementById('results');

        searchBox.addEventListener('input', function() {
            const searchTerm = this.value;
            
            if (searchTerm.length > 0) {
                fetch(`search.php?q=${encodeURIComponent(searchTerm)}`)
                    .then(response => response.text())
                    .then(html => {
                        if (html.trim() !== '') {
                            resultsDiv.innerHTML = html;
                            resultsDiv.style.display = 'block';
                        } else {
                            resultsDiv.style.display = 'none';
                        }
                    });
            } else {
                resultsDiv.style.display = 'none';
            }
        });

        // Handle click on search results
        document.addEventListener('click', function(event) {
            if (event.target.closest('#results')) {
                const searchTerm = event.target.textContent;
                searchBox.value = searchTerm;
                resultsDiv.style.display = 'none';
            }
        });
    </script>
</body>
</html>

<?php
// This is the PHP script that handles the search query
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $searchTerm = $_GET['q'];
    
    // Connect to database
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'your_database';

    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // SQL query to search for the term
    $sql = "SELECT * FROM users WHERE name LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo '<div class="search-results">';
        while($row = $result->fetch_assoc()) {
            echo "<div onclick='selectSearchTerm(\"{$row['name']}\")'>{$row['name']}</div>";
        }
        echo '</div>';
    } else {
        echo '';
    }
    
    $conn->close();
}
?>


<?php
// process_search.php

// Sanitize input to prevent SQL injection
$searchQuery = htmlspecialchars(trim($_POST['searchQuery']));

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'your_database';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to search for the query in your database
$sql = "SELECT * FROM your_table WHERE column_name LIKE ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $searchQuery);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

echo "<div class='results'>";
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<p>" . $row['column_name'] . "</p>";
    }
} else {
    echo "No results found.";
}
echo "</div>";

mysqli_close($conn);
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'username';
$password = 'password';
$dbname = 'database_name';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search query from form submission
$query = isset($_POST['query']) ? $_POST['query'] : '';

// If query is not empty, perform search
if (!empty($query)) {
    // Sanitize the query to prevent SQL injection
    $search_query = mysqli_real_escape_string($conn, $query);

    // Search in the database table (e.g., 'users')
    $sql = "SELECT * FROM users WHERE name LIKE '%$search_query%' OR email LIKE '%$search_query%'";
    
    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Check if there are results
    if ($result && mysqli_num_rows($result) > 0) {
        // Display search results
        echo "<h3>Search Results:</h3>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Name: " . $row['name'] . "</p>";
            echo "<p>Email: " . $row['email'] . "</p>";
            echo "<hr>";
        }
    } else {
        // No results found
        echo "<h3>Sorry, no results were found.</h3>";
    }
}

// Close database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            <input type="text" name="query" class="search-bar" placeholder="Search here...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if form is submitted and query is not empty
        if (isset($_GET['query']) && $_GET['query'] !== '') {
            $searchQuery = $_GET['query'];
            
            // Here you would typically connect to a database and perform a search query
            // For this example, we'll just display the search query
            
            echo "<div class='results'>";
            echo "<p>Search Results for: <strong>" . htmlspecialchars($searchQuery) . "</strong></p>";
            echo "</div>";
            
            // Example of how you might display actual results from a database
            // This is just a placeholder example
            $dummyResults = array(
                "Result 1 related to your search",
                "Result 2 related to your search",
                "Result 3 related to your search"
            );
            
            echo "<div class='results'>";
            foreach ($dummyResults as $result) {
                echo "<p>" . htmlspecialchars($result) . "</p>";
            }
            echo "</div>";
        } else if (isset($_GET['query'])) {
            // If query is empty
            echo "<div class='results' style='color: red;'>";
            echo "<p>Please enter a search term.</p>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-button {
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #0056b3;
        }

        .results {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="search-form">
            <input type="text" name="search_query" placeholder="Search here..." class="search-input" value="<?php if (isset($_GET['search_query'])) { echo $_GET['search_query']; } ?>">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Sample data for search results
        $sample_data = [
            'Apple',
            'Banana',
            'Cherry',
            'Date',
            'Elderberry',
            'Fig',
            'Grape',
            'Honeydew Melon',
            'Kiwi',
            'Lemon',
            'Mango'
        ];

        // Handle search query
        if (isset($_GET['search_query']) && $_GET['search_query'] !== '') {
            $search_query = strtolower($_GET['search_query']);
            
            echo "<div class='results'>";
                echo "<h3>Results for: '$search_query'</h3>";
                echo "<ul>";

                foreach ($sample_data as $item) {
                    if (strtolower($item) === $search_query || strpos(strtolower($item), $search_query) !== false) {
                        echo "<li>$item</li>";
                    }
                }

                // If no results found
                if (!empty($sample_data)) {
                    $found_results = array_filter($sample_data, function($item) use ($search_query) {
                        return (strtolower($item) === $search_query || strpos(strtolower($item), $search_query) !== false);
                    });

                    if (count($found_results) === 0) {
                        echo "<p>No results found for '$search_query'</p>";
                    }
                }

                echo "</ul>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add some basic CSS styling -->
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-box {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search form -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="query" class="search-box" placeholder="Search here..." value="<?php if(isset($_GET['query'])) { echo $_GET['query']; } ?>">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Search results section
        echo "<div class='results'>";
        
        // Check if the form has been submitted
        if (isset($_GET['query'])) {
            $search_query = $_GET['query'];
            
            // Database connection details
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database_name = 'your_database';

            // Connect to database
            $connection = mysqli_connect($host, $username, $password, $database_name);

            // Check if connection was successful
            if (!$connection) {
                die("Connection failed: " . mysqli_error());
            }

            // Escape special characters to prevent SQL injection
            $search_query = mysqli_real_escape_string($connection, $search_query);

            // SQL query to search for the keyword in your table
            $sql = "SELECT * FROM your_table WHERE keyword LIKE '%{$search_query}%'";
            
            // Execute the query
            $result = mysqli_query($connection, $sql);

            // Check if any results were found
            if (mysqli_num_rows($result) > 0) {
                echo "<h3>Results:</h3>";
                
                while ($row = mysqli_fetch_assoc($result)) {
                    // Display the results in a readable format
                    echo "<div style='border:1px solid #ddd; padding:10px; margin-bottom:10px;'>";
                    echo "<p>" . $row['your_column'] . "</p>";
                    echo "</div>";
                }
            } else {
                // No results found
                echo "<h3>No results found!</h3>";
            }

            // Close the database connection
            mysqli_close($connection);
        }

        echo "</div>";
        ?>

        <!-- Reset search link -->
        <?php if (isset($_GET['query'])) { ?>
        <p><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Reset Search</a></p>
        <?php } ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-container {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
        }
        input[type="text"] {
            width: 70%;
            padding: 8px;
            margin-right: 5px;
        }
        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="query" placeholder="Search..." <?php if (isset($_POST['query'])) { echo 'value="' . htmlspecialchars($_POST['query']) . '"'; } ?>>
            <button type="submit">Search</button>
        </form>

        <?php
        // Database connection details
        $host = "localhost";
        $username = "root"; // Change to your database username
        $password = "";     // Change to your database password
        $database = "test_db"; // Change to your database name

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['query'])) {
            // Sanitize the input query
            $search_term = mysqli_real_escape_string($conn, $_POST['query']);

            // Connect to the database
            $conn = mysqli_connect($host, $username, $password, $database);
            
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // SQL query to search for matching results
            $sql = "SELECT name FROM users WHERE name LIKE '%" . strtolower($search_term) . "%' ORDER BY name";
            
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo "<div class=\"results\">";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "- " . htmlspecialchars($row['name']) . "<br>";
                }
                echo "</div>";
            } else {
                echo "<div class=\"results\">No results found.</div>";
            }

            // Close the database connection
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-form input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            font-size: 16px;
        }

        .search-form button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="process_search.php" method="post" class="search-form">
            <input type="text" name="query" placeholder="Search..." required>
            <button type="submit">Search</button>
        </form>

        <?php
        // This code should be in process_search.php
        if (isset($_POST['query'])) {
            $query = $_POST['query'];
            
            // Sanitize input
            $search_query = htmlspecialchars($query);
            
            // Connect to database
            $conn = mysqli_connect("localhost", "username", "password", "database");
            
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // Escape special characters in query
            $search_query = mysqli_real_escape_string($conn, $search_query);
            
            // SQL query to search for the term
            $sql = "SELECT * FROM your_table WHERE column LIKE '%$search_query%'";
            $result = mysqli_query($conn, $sql);
            
            // Display results
            if (mysqli_num_rows($result) > 0) {
                echo "<div class='results'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    // Assuming you have a column named 'title' and 'id'
                    echo "<div><a href='view.php?id=" . $row['id'] . "'>" . $row['title'] . "</a></div>";
                }
                echo "</div>";
            } else {
                echo "<p>No results found.</p>";
            }
            
            // Close connection
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-box {
            display: flex;
            gap: 10px;
        }
        
        #searchInput {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            width: 70%;
        }
        
        #searchButton {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        #searchButton:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="search-box">
                <input type="text" name="query" id="searchInput" placeholder="Search here...">
                <button type="submit" id="searchButton">Search</button>
            </div>
        </form>

        <?php
        if (isset($_POST['query'])) {
            $searchQuery = $_POST['query'];
            
            // Here you would typically query your database
            // For this example, we'll use sample data
            $sampleData = array('apple', 'banana', 'cherry', 'date', 'elderberry');
            $results = array();
            
            foreach ($sampleData as $item) {
                if (stripos($item, $searchQuery) !== false) {
                    $results[] = $item;
                }
            }
            
            echo "<div class='results'>";
            echo "<h3>Search Results:</h3>";
            echo "<p>Your search for: <strong>" . htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8') . "</strong></p>";
            
            if (count($results) > 0) {
                echo "<ul>";
                foreach ($results as $result) {
                    echo "<li>" . htmlspecialchars($result, ENT_QUOTES, 'UTF-8') . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No results found for your search.</p>";
            }
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="query" placeholder="Search...">
            <input type="submit" name="search" value="Search">
        </form>

        <?php
        if (isset($_POST['search'])) {
            $searchQuery = trim($_POST['query']);
            
            // Add your search logic here
            // This is just a simple example with sample data
            $sampleData = array(
                "Apple",
                "Banana",
                "Cherry",
                "Date",
                "Grape",
                "Kiwi",
                "Mango",
                "Orange"
            );

            echo "<div class='results'>";
            echo "<h3>Search Results:</h3>";
            echo "<ul>";

            foreach ($sampleData as $item) {
                if (stripos($item, $searchQuery) !== false) {
                    echo "<li>$item</li>";
                }
            }

            echo "</ul>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="search_query" class="search-bar" placeholder="Search...">
            <input type="submit" value="Search" style="padding: 10px; font-size: 16px;">
        </form>
    </div>

    <?php
    // Check if the form has been submitted
    if (isset($_POST['search_query'])) {
        $search_query = htmlspecialchars(trim($_POST['search_query']));
        
        // Here you would typically connect to your database and perform a search query
        // For this example, we'll just display the search results mock-up
        
        // Mock search results
        $results = array(
            "Result 1 related to: $search_query",
            "Result 2 related to: $search_query",
            "Result 3 related to: $search_query"
        );
        
        echo "<h3>Search Results for '$search_query'</h3>";
        foreach ($results as $result) {
            echo "<p>$result</p>";
        }
    } else {
        // Show this message when no search has been performed yet
        echo "<h3>Welcome! Enter your search above and press enter.</h3>";
    }
    ?>
</body>
</html>


<?php
// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (isset($_POST['search_query'])) {
    $search_query = htmlspecialchars(trim($_POST['search_query']));
    
    // Prepare SQL statement with a wildcard search
    $stmt = $conn->prepare("SELECT * FROM your_table WHERE column_name LIKE ?");
    $search_term = '%' . $search_query . '%';
    $stmt->execute([$search_term]);
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($results) > 0) {
        echo "<h3>Search Results for '$search_query'</h3>";
        foreach ($results as $result) {
            // Display your results appropriately
            echo "<p>" . $result['column_name'] . "</p>";
        }
    } else {
        echo "<p>No results found for '$search_query'.</p>";
    }
}
?>


<?php
// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search query from form submission
if (isset($_POST['search'])) {
    $search_query = $_POST['query'];
    
    // Sanitize the input
    $search_query = htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8');
    
    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM your_table WHERE column_name LIKE ?");
    $search_value = "%$search_query%";
    $stmt->bind_param('s', $search_value);
    
    // Execute the query
    $stmt->execute();
    
    // Get result set
    $result = $stmt->get_result();
    
    // Display results
    while ($row = $result->fetch_assoc()) {
        echo "<div class='result'>";
        echo "<h3>" . $row['column_name'] . "</h3>";
        // Add more fields as needed
        echo "</div>";
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        #searchInput {
            padding: 12px;
            width: 80%;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        #searchButton {
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        #searchButton:hover {
            background-color: #45a049;
        }
        
        .result {
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: white;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="search-bar">
                <input type="text" id="searchInput" name="query" placeholder="Search...">
                <button type="submit" name="search" id="searchButton">Search</button>
            </div>
        </form>
    </div>

    <?php
    // Display search results here
    if (isset($_POST['search'])) {
        // Your database query result display code goes here
    }
    ?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .search-container {
            text-align: center;
            width: 500px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-box {
            display: flex;
            width: 100%;
        }

        input[type="text"] {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            width: 75%;
        }

        button {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 5px;
        }

        button:hover {
            background-color: #45a049;
        }

        .search-results {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <div class="search-box">
                <input type="text" name="query" placeholder="Search here...">
                <button type="submit">Search</button>
            </div>
        </form>

        <?php
        if (isset($_GET['query'])) {
            $searchQuery = $_GET['query'];
            echo "<div class='search-results'>";
            echo "You searched for: <strong>" . htmlspecialchars($searchQuery) . "</strong>";
            // Here you would typically connect to a database and perform a search query
            // For this example, we're just displaying the search term
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        #searchInput {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        #searchButton {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #searchButton:hover {
            background-color: #45a049;
        }

        .result {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <input type="text" id="searchInput" name="query" placeholder="Search...">
            <button type="submit" id="searchButton">Search</button>
        </form>
        
        <?php
        if (isset($_POST['query'])) {
            $search = $_POST['query'];
            
            // Database connection
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'my_database';

            try {
                $conn = new mysqli($host, $username, $password, $database);
                
                if ($conn->connect_error) {
                    throw new Exception("Connection failed: " . $conn->connect_error);
                }

                // Sanitize input
                $search = $conn->real_escape_string($search);

                // Query database
                $sql = "SELECT name, email FROM users WHERE name LIKE '%{$search}%' OR email LIKE '%{$search}%'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='result'>";
                        echo "Name: " . $row['name'] . "<br>";
                        echo "Email: " . $row['email'];
                        echo "</div><br>";
                    }
                } else {
                    echo "<div class='result'>No results found!</div>";
                }

                $conn->close();
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .search-container {
            width: 500px;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <?php
    // Sample data - you can replace this with your database connection and query
    $items = [
        ['id' => 1, 'name' => 'Apple'],
        ['id' => 2, 'name' => 'Banana'],
        ['id' => 3, 'name' => 'Cherry'],
        ['id' => 4, 'name' => 'Date'],
        ['id' => 5, 'name' => 'Grape']
    ];

    // Search function
    function searchItems($searchTerm, $items) {
        $results = [];
        foreach ($items as $item) {
            if (stripos($item['name'], $searchTerm) !== false) {
                $results[] = $item;
            }
        }
        return $results;
    }

    // Handle form submission
    if (isset($_POST['search'])) {
        $searchTerm = $_POST['term'];
        $results = searchItems($searchTerm, $items);
    } else {
        $results = [];
    }
    ?>

    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="search-bar">
                <input type="text" name="term" placeholder="Search..." value="<?php if (isset($searchTerm)) { echo $searchTerm; } ?>">
                <button type="submit" name="search">Search</button>
            </div>
        </form>

        <?php if (!empty($results)): ?>
            <div class="results">
                <h3>Results:</h3>
                <?php foreach ($results as $item): ?>
                    <div class="result-item"><?php echo $item['name']; ?></div>
                <?php endforeach; ?>
            </div>
        <?php elseif (isset($searchTerm)): ?>
            <div class="results">
                <p>No results found for "<?php echo $searchTerm; ?>".</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>


<?php
// Get the search query from POST request
$query = isset($_POST['query']) ? htmlspecialchars(trim($_POST['query'])) : '';

if (empty($query)) {
    die("Please enter a search term.");
}

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'your_database';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Sanitize the input
$search_query = mysqli_real_escape_string($conn, $query);

// Search query in the database (example table 'users')
$sql = "SELECT * FROM users WHERE 
        name LIKE '%$search_query%' OR 
        email LIKE '%$search_query%'";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Check if any results were found
if (mysqli_num_rows($result) == 0) {
    echo "<h2>No results found.</h2>";
} else {
    // Display the results in a table
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Name</th><th>Email</th></tr>";
    
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .search-button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-results {
            margin-top: 20px;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Connect to database
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "mydatabase";

        // Create connection
        $conn = mysqli_connect($host, $username, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Handle search query
        $searchQuery = "";

        if (isset($_POST['submit'])) {
            $searchQuery = $_POST['search'];
            
            // Sanitize input
            $searchQuery = trim($searchQuery);
            $searchQuery = mysqli_real_escape_string($conn, $searchQuery);

            // SQL query to search for the term
            $sql = "SELECT * FROM users WHERE first_name LIKE '%{$searchQuery}%'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='search-results'>";
                    echo "<p>Name: " . $row['first_name'] . " " . $row['last_name'] . "</p>";
                    echo "<p>Email: " . $row['email'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<div class='search-results'>";
                echo "<p>No results found.</p>";
                echo "</div>";
            }
        }

        // Close database connection
        mysqli_close($conn);
        ?>

        <form class="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="search" class="search-input" placeholder="Search...">
            <button type="submit" name="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="query" class="search-bar" placeholder="Search here...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Database configuration
        $dbHost = 'localhost';
        $dbName = 'your_database_name';
        $dbUser = 'your_username';
        $dbPass = 'your_password';

        if (isset($_GET['query'])) {
            $searchQuery = $_GET['query'];
            
            // Connect to database
            $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare and bind the SQL statement
            $stmt = $conn->prepare("SELECT * FROM your_table_name WHERE column_name LIKE ?");
            $searchTerm = "%" . $searchQuery . "%";
            $stmt->bind_param("s", $searchTerm);

            // Execute the query
            $stmt->execute();
            $result = $stmt->get_result();

            // Display results
            echo "<div class='results'>";
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<p>" . $row['column_name'] . "</p>";
                }
            } else {
                echo "<p>No results found.</p>";
            }
            echo "</div>";

            // Close connections
            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .search-bar input[type="text"] {
            width: 75%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .search-bar button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-results {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f8f8;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
            // Check if search query is set
            $query = isset($_GET['query']) ? $_GET['query'] : '';
            
            // Sample data for demonstration
            $sample_data = array(
                'apple', 
                'banana', 
                'orange', 
                'grape',
                'mango'
            );
            
            // Initialize results array
            $results = array();
            
            if (!empty($query)) {
                foreach ($sample_data as $item) {
                    // Check if query matches any item in the sample data
                    if (stripos($item, $query) !== false) {
                        $results[] = $item;
                    }
                }
            }
        ?>

        <!-- Search Form -->
        <form class="search-bar" method="GET">
            <input type="text" name="query" placeholder="Search..." value="<?php echo htmlspecialchars($query); ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Display Results -->
        <?php if (!empty($results)): ?>
            <div class="search-results">
                <h3>Results:</h3>
                <ul>
                    <?php foreach ($results as $result): ?>
                        <li><?php echo htmlspecialchars($result); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif (!empty($query)): ?>
            <div class="search-results">
                <p>No results found for "<?php echo htmlspecialchars($query); ?>".</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>


// Database connection code
$connection = new mysqli('localhost', 'username', 'password', 'database_name');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Search query
$query = isset($_GET['query']) ? $_GET['query'] : '';
$sql = "SELECT * FROM your_table WHERE column LIKE '%" . mysqli_real_escape_string($connection, $query) . "%'";
$result_set = $connection->query($sql);

if ($result_set->num_rows > 0) {
    while ($row = $result_set->fetch_assoc()) {
        // Display results
    }
} else {
    echo "No results found";
}

// Close database connection
$connection->close();


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }

        .search-btn:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: white;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="search" class="search-bar" placeholder="Search here...">
            <button type="submit" class="search-btn">Search</button>
        </form>

        <?php
        if (isset($_GET['search'])) {
            // Get the search term
            $searchTerm = $_GET['search'];

            // Connect to the database
            $host = 'localhost';
            $username = 'your_username';
            $password = 'your_password';
            $database = 'your_database';

            $conn = new mysqli($host, $username, $password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Sanitize the input to prevent SQL injection
            $searchTerm = mysqli_real_escape_string($conn, htmlspecialchars($searchTerm));

            // Query the database
            $query = "SELECT * FROM your_table WHERE column1 LIKE '%".$searchTerm."%' 
                      OR column2 LIKE '%".$searchTerm."%'";
            
            if ($result = $conn->prepare($query)) {
                // Bind parameters
                $result->bind_param("s", $searchTerm);
                
                // Execute the query
                $result->execute();
                
                // Get the result set
                $resultSet = $result->get_result();
                
                // Display results
                echo "<div class='results'>";
                if ($resultSet->num_rows > 0) {
                    while($row = $resultSet->fetch_assoc()) {
                        echo "<p>" . $row['column1'] . " - " . $row['column2'] . "</p>";
                    }
                } else {
                    echo "<p>No results found.</p>";
                }
                echo "</div>";
            }

            // Close the connection
            $conn->close();
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
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .search-box {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .search-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-btn:hover {
            background-color: #45a049;
        }

        .result-container {
            margin-top: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="search" class="search-box" placeholder="Search...">
            <button type="submit" class="search-btn">Search</button>
        </form>

        <?php
        if (isset($_POST['search'])) {
            // Sanitize input
            $search = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['search'])));
            
            // Database connection
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'your_database';

            $conn = mysqli_connect($host, $username, $password, $database);

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Search query
            $sql = "SELECT * FROM your_table WHERE column1 LIKE ? OR column2 LIKE ?";
            $stmt = mysqli_prepare($conn, $sql);
            $search_term = '%' . $search . '%';
            
            if (mysqli_stmt_bind_param($stmt, 'ss', $search_term, $search_term)) {
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        echo "<table border='1' class='result-container'>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Display your results here
                            echo "<tr>";
                            echo "<td>" . $row['column1'] . "</td>";
                            echo "<td>" . $row['column2'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<div class='result-container'>No results found.</div>";
                    }
                }
            }

            // Close connection
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search_query" class="search-bar" placeholder="Search...">
            <button type="submit" name="search_submit" class="search-button">Search</button>
        </form>
    </div>

    <?php
    // Check if form is submitted
    if (isset($_POST['search_submit'])) {
        $search_query = $_POST['search_query'];
        
        // Display search results or query database here
        echo "<h3>Search Results for: '" . htmlspecialchars($search_query) . "'</h3>";
        
        // You would typically connect to a database and fetch results here
    }
    ?>
</body>
</html>


<?php
// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['search_submit'])) {
    // Get search query and sanitize it
    $search_query = mysqli_real_escape_string($conn, $_POST['search_query']);
    
    // SQL query to search database
    $sql = "SELECT * FROM your_table WHERE column_name LIKE '%{$search_query}%'";
    
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Output the results
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div>";
            echo "ID: " . $row['id'] . "<br>";
            echo "Name: " . $row['name'] . "<br>";
            // Add more fields as needed
            echo "</div><hr>";
        }
    } else {
        echo "No results found.";
    }
}

// Close database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
    <style>
        .search-box {
            width: 300px;
            padding: 10px;
            margin: 50px auto;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-box input[type="text"] {
            width: 70%;
            padding: 5px;
            font-size: 16px;
        }
        .search-box input[type="submit"] {
            width: 28%;
            padding: 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .search-box input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<?php
// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Search query
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    
    // SQL query to search for the term in database
    $sql = "SELECT * FROM your_table_name WHERE column_name LIKE '%" . $search . "%'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Search Results:</h2>";
        while ($row = mysqli_fetch_assoc($result)) {
            // Display the results
            echo "<p>" . $row['column_name'] . "</p>";
        }
    } else {
        echo "No results found";
    }
}
?>

<form class="search-box" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
    <input type="text" name="search" placeholder="Search...">
    <input type="submit" value="Go">
</form>

<?php
// Close database connection
mysqli_close($conn);
?>
</body>
</html>


<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$dbname = "testdb";

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function searchDatabase($keyword, $conn) {
    $keyword = '%' . $keyword . '%';
    
    $query = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?";
    
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
        
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        
        return $result;
    } else {
        echo "Error: " . mysqli_error($conn);
        return false;
    }
}

if (isset($_POST['search'])) {
    $keyword = htmlspecialchars($_POST['keyword']);
    
    // Call the search function
    $results = searchDatabase($keyword, $conn);
}
?>


<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=database_name", "username", "password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (isset($_GET['query'])) {
        $search = $_GET['query'];
        
        $stmt = $conn->prepare("SELECT * FROM your_table WHERE name LIKE ?");
        $stmt->execute(["%$search%"]);
        
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<p>ID: " . $row['id'] . ", Name: " . $row['name'] . "</p>";
            }
        } else {
            echo "<p>No results found.</p>";
        }
    }
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .search-box {
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex-grow: 1;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="search-box">
            <input type="text" name="query" placeholder="Search here..." class="search-input">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Display search results only if query is submitted
        if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
            $query = trim($_GET['query']);
            echo "<h3>Search Results for: '$query'</h3>";
            
            // Split the query into individual keywords
            $keywords = explode(' ', $query);
            
            // Sample data (you would typically fetch this from a database)
            $sample_data = array(
                "Apple",
                "Banana",
                "Orange",
                "Grape",
                "Mango",
                "Pineapple",
                "Strawberry",
                "Blueberry"
            );
            
            // Search for matching results
            $results = array();
            foreach ($sample_data as $item) {
                $matches = 0;
                foreach ($keywords as $keyword) {
                    if (preg_match("/$keyword/i", $item)) {
                        $matches++;
                    }
                }
                if ($matches == count($keywords)) {
                    $results[] = $item;
                }
            }
            
            // Display results
            if (!empty($results)) {
                echo "<div class=\"results\">";
                foreach ($results as $result) {
                    echo "<p>$result</p>";
                }
                echo "</div>";
            } else {
                echo "<div class=\"results\" style=\"color: red;\">
                        No results found for your search.
                     </div>";
            }
        }
        ?>
    </div>
</body>
</html>


<?php
// Database connection details
$host = "localhost";
$user = "root";
$password = "";
$db_name = "your_database";

// Connect to database
$conn = mysqli_connect($host, $user, $password, $db_name);

// Check if connection failed
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search query from URL parameter
$search_query = $_GET['search'] ?? '';

// Sanitize the input to prevent SQL injection
$search_query = mysqli_real_escape_string($conn, $search_query);

// SQL query with LIKE clause for partial matching
$sql = "SELECT * FROM products WHERE name LIKE '%$search_query%' OR description LIKE '%$search_query%'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table class='table'>";
    echo "<tr><th>Name</th><th>Description</th></tr>";
    
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['description'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "No results found.";
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Database configuration
$host = "localhost";
$user = "root";
$password = "";
$db_name = "test_db";

// Connect to database
$conn = mysqli_connect($host, $user, $password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle search query
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = mysqli_real_escape_string($conn, $_POST['search_query']);
    
    // Search query
    $sql = "SELECT * FROM users WHERE name LIKE '%$search_query%' OR email LIKE '%$search_query%'";
    $result = mysqli_query($conn, $sql);
    
    // Display results
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='result'>";
            echo "<p>Name: " . $row['name'] . "</p>";
            echo "<p>Email: " . $row['email'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "No results found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .result {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search_query" id="search_query" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to database
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if search query is provided
if (isset($_POST['query']) && !empty($_POST['query'])) {
    $search = $_POST['query'];
    
    // Escape special characters to prevent SQL injection
    $search = mysqli_real_escape_string($conn, trim($search));
    
    // Query database
    $sql = "SELECT * FROM your_table WHERE 
            column1 LIKE '%$search%' OR
            column2 LIKE '%$search%'";
            
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<div class='results'>";
        echo "<table border='1' style='width:100%'>";
        echo "<tr><th>Column1</th><th>Column2</th></tr>";
        
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["column1"]."</td><td>".$row["column2"]."</td></tr>";
        }
        
        echo "</table>";
        echo "</div>";
    } else {
        echo "No results found";
    }
} else {
    echo "Please enter a search term";
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar Example</title>
    <!-- Add some basic CSS styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-input {
            width: 70%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        .search-button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="search.php" method="post">
            <input type="text" name="search_term" class="search-input" placeholder="Search...">
            <button type="submit" name="search_submit" class="search-button">Search</button>
        </form>
    </div>

    <?php
    // Check if the form was submitted
    if (isset($_POST['search_submit'])) {
        $searchTerm = $_POST['search_term'];
        
        // Connect to the database
        $conn = new mysqli('localhost', 'username', 'password', 'database_name');
        
        // Sanitize the input
        $searchTerm = $conn->escape_string($searchTerm);
        
        // Check if search term is not empty
        if (!empty($searchTerm)) {
            // Query to get results from database
            $sql = "SELECT * FROM your_table WHERE column_name LIKE '%$searchTerm%'";
            $result = $conn->query($sql);
            
            // Display the results
            echo "<h3>Search Results:</h3>";
            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>$value</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No results found!";
            }
        } else {
            echo "Please enter a search term!";
        }
        
        // Close the database connection
        $conn->close();
    }
    ?>
</body>
</html>


<?php
// Connect to database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'your_database';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Sanitize input
$search_term = mysqli_real_escape_string($conn, $_POST['query']);

// Query database
$sql = "SELECT * FROM your_table 
        WHERE first_name LIKE '%{$search_term}%' 
        OR last_name LIKE '%{$search_term}%'";
        
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Display results
echo "<h3>Search Results:</h3>";
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<p>" . $row['first_name'] . " " . $row['last_name'] . "</p>";
    }
} else {
    echo "<p>No results found.</p>";
}

// Close connection
mysqli_close($conn);
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
            margin: 20px;
        }
        .search-container {
            max-width: 800px;
            margin: 0 auto;
        }
        #searchForm {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        #searchInput {
            flex-grow: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        #searchButton {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #searchButton:hover {
            background-color: #45a049;
        }
        .result {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form id="searchForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" id="searchInput" name="query" placeholder="Search here...">
            <button type="submit" id="searchButton">Search</button>
        </form>

        <?php
        // Check if the form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $query = $_POST['query'];

            // Sanitize input to prevent SQL injection
            $query = htmlspecialchars($query);
            
            // Database connection parameters
            $host = 'localhost';
            $username = 'root'; // Change according to your database username
            $password = '';     // Change according to your database password
            $database = 'test_db'; // Change to your database name

            // Connect to the database
            $connection = mysqli_connect($host, $username, $password, $database);

            if (!$connection) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // SQL query to search for the term in your table
            $sql = "SELECT * FROM your_table_name 
                    WHERE name LIKE '%" . mysqli_real_escape_string($connection, $query) . "%' 
                    OR description LIKE '%" . mysqli_real_escape_string($connection, $query) . "%'";
            
            // Execute the query
            $result = mysqli_query($connection, $sql);

            if (mysqli_num_rows($result) > 0) {
                // Display the results in a table
                echo "<table border='1'>";
                echo "<tr><th>ID</th><th>Name</th><th>Description</th></tr>";
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "</tr>";
                }
                
                echo "</table>";
            } else {
                // No results found
                echo "<div class='result'>No results found.</div>";
            }

            // Close the database connection
            mysqli_close($connection);
        }
        ?>
    </div>
</body>
</html>


<?php
// Get search query from form submission
$searchQuery = $_POST['query'];

// Database connection details
$host = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$database = "mydatabase"; // Replace with your database name

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize the search query
$searchQuery = mysqli_real_escape_string($conn, $searchQuery);

// Search query to database
$sql = "SELECT * FROM users WHERE name LIKE '%$searchQuery%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output results
    echo "<h3>Search Results:</h3>";
    while($row = $result->fetch_assoc()) {
        echo "<p>" . $row['name'] . "</p>";
    }
} else {
    // No results found
    echo "No results found!";
}

// Close database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <!-- Add some CSS styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="text" name="query" class="search-bar" placeholder="Search...">
            <button type="submit" name="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if form was submitted
        if (isset($_POST['submit'])) {
            $searchQuery = trim(htmlspecialchars($_POST['query']));

            // Database connection details
            $host = 'localhost';
            $username = 'your_username';
            $password = 'your_password';
            $database = 'your_database';

            try {
                // Connect to database
                $conn = new mysqli($host, $username, $password, $database);

                // Check for connection errors
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // SQL query to search for the term
                $sql = "SELECT * FROM your_table_name WHERE column_name LIKE ?";
                $stmt = $conn->prepare($sql);
                $searchTerm = "%" . $searchQuery . "%";
                $stmt->bind_param("s", $searchTerm);

                // Execute the query
                $stmt->execute();
                $result = $stmt->get_result();

                // Display the results
                if ($result->num_rows > 0) {
                    echo "<div class='results'>";
                    while ($row = $result->fetch_assoc()) {
                        // Display your data here (modify according to your table structure)
                        echo "<p>" . $row['column_name'] . "</p>";
                    }
                    echo "</div>";
                } else {
                    echo "<div class='results'><p>No results found for: " . $searchQuery . "</p></div>";
                }

                // Close the connection
                $conn->close();
            } catch (Exception $e) {
                die("An error occurred: " . $e->getMessage());
            }
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            width: 50%;
            margin: 50px auto;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
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
    <?php
    // Check if search query is set
    if (isset($_GET['search_query'])) {
        $query = $_GET['search_query'];
        
        // Here you would typically connect to your database and perform a query
        // For this example, we'll just simulate some results
        $results = array(
            "Apple",
            "Banana",
            "Cherry",
            "Date",
            "Grape",
            "Kiwi",
            "Mango",
            "Orange"
        );
        
        echo "<h2>Search Results:</h2>";
        if (!empty($query)) {
            $matches = array();
            foreach ($results as $item) {
                // Simple case-insensitive search
                if (stripos($item, $query) !== false) {
                    $matches[] = $item;
                }
            }
            
            if (!empty($matches)) {
                echo "<ul>";
                foreach ($matches as $match) {
                    echo "<li>$match</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No results found for '$query'</p>";
            }
        }
    }
    
    // Display the search form
    ?>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="search_query" placeholder="Search...">
            <button type="submit">Search</button>
        </form>
    </div>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add some basic CSS styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .search-input {
            flex-grow: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="search-form" enctype="multipart/form-data">
            <input type="text" name="search" id="search" class="search-input" placeholder="Search here...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <!-- Search results -->
        <?php
        // Check if the form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchTerm = $_POST['search'];
            
            // Sanitize the input to prevent SQL injection or XSS attacks
            $searchTerm = htmlspecialchars(trim($searchTerm));
            
            echo "<h3>Searching for: " . $searchTerm . "</h3>";
            
            // Here you would typically connect to your database and perform a search query
            // For now, we'll just display the search term
            
            // Example of where you would put your search results
            // Replace this section with your actual search logic
            
            echo "<div class='search-results'>";
                // Add your search results here
                // For example:
                // while ($row = $result->fetch_assoc()) {
                //     echo "<p>" . $row['title'] . "</p>";
                // }
                
                // If no results are found
                echo "<p>No results found.</p>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-form {
            display: flex;
            gap: 10px;
            width: 100%;
        }

        .search-input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
            // Check if the form has been submitted
            if (isset($_POST['search_term'])) {
                $searchTerm = $_POST['search_term'];
                echo "<h3>Search Results for: '$searchTerm'</h3>";
                
                // Here you would typically connect to a database and fetch results
                // For this example, we'll just display the search term
                echo "<p>Your search term has been submitted!</p>";
            }
        ?>

        <form class="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="search_term" class="search-input" placeholder="Enter your search...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <div class="results">
            <!-- Search results would be displayed here -->
        </div>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .search-box input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .search-box button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-box button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="search-box">
                <input type="text" name="query" placeholder="Search here..." <?php if(isset($_GET['query'])) { echo 'value="' . htmlspecialchars($_GET['query']) . '"'; } ?>>
                <button type="submit" name="submit">Search</button>
            </div>
        </form>

        <?php
        // Connect to database
        $host = "localhost";
        $username = "root"; // Replace with your database username
        $password = "";     // Replace with your database password
        $database = "testdb"; // Replace with your database name

        $conn = mysqli_connect($host, $username, $password, $database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        if (isset($_GET['submit'])) {
            $searchQuery = mysqli_real_escape_string($conn, $_GET['query']);

            // SQL query to search for the term
            $sql = "SELECT * FROM your_table_name WHERE column_name LIKE '%" . $searchQuery . "%'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo "<h3>Search Results:</h3>";
                while ($row = mysqli_fetch_assoc($result)) {
                    // Display your results here
                    // For example:
                    echo "<p>" . htmlspecialchars($row['column_name']) . "</p>";
                }
            } else {
                echo "<p>No results found.</p>";
            }
        }

        // Close database connection
        mysqli_close($conn);
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-box {
            display: flex;
            gap: 10px;
        }
        input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h2>Search Bar</h2>
    
    <form action="search.php" method="GET">
        <div class="search-box">
            <input type="text" name="query" placeholder="Enter search term...">
            <button type="submit">Search</button>
        </div>
    </form>

<?php
// Check if the form is submitted
if (isset($_GET['query'])) {
    $search_term = mysqli_real_escape_string($conn, htmlspecialchars($_GET['query']));

    // Database connection
    $host = 'localhost';
    $username = 'your_username';
    $password = 'your_password';
    $database = 'your_database';

    $conn = mysqli_connect($host, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Search query
    $sql = "SELECT * FROM your_table WHERE column1 LIKE '%{$search_term}%' OR column2 LIKE '%{$search_term}%'";
    
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Display results
    echo "<div class='results'>";
    echo "<h3>Search Results</h3>";
    echo "<table border='1'>";

    // Display table headers
    $fields = mysqli_fetch_fields($result);
    echo "<tr>";
    foreach ($fields as $field) {
        echo "<th>{$field->name}</th>";
    }
    echo "</tr>";

    // Display search results
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>$value</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";

    // Close database connection
    mysqli_close($conn);
}
?>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="search_term" class="search-box" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search_term = htmlspecialchars($_POST['search_term']);

            // Database connection details
            $host = 'localhost';
            $db_username = 'root';
            $db_password = '';
            $dbname = 'test_db';

            // Create database connection
            function get_db_connection() {
                static $connection;
                if (!$connection) {
                    $connection = mysqli_connect('localhost', 'root', '', 'test_db');
                }
                return $connection;
            }

            // Function to search database
            function search_database($search_term) {
                $conn = get_db_connection();
                $sql = "SELECT * FROM products WHERE name LIKE ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt === false) {
                    die("mysqli_prepare failed: " . mysqli_error($conn));
                }

                mysqli_stmt_bind_param($stmt, "s", $search_term);
                mysqli_execute($stmt);
                $result = mysqli_get_result($stmt);

                return $result;
            }

            // Perform search
            $results = search_database("%$search_term%");
            
            if (mysqli_num_rows($results) > 0) {
                echo "<div class='results'>";
                while ($row = mysqli_fetch_assoc($results)) {
                    echo "<p>" . $row['name'] . "</p>";
                }
                echo "</div>";
            } else {
                echo "<div class='results'><p>No results found.</p></div>";
            }

            // Close database connection
            mysqli_close(get_db_connection());
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-box input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .search-box button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-box button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Search Products</h1>
    
    <?php
    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_term'])) {
        $searchTerm = $_POST['search_term'];
        
        // Database connection details
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "test_db";
        
        // Connect to database
        $conn = new mysqli($host, $username, $password, $database);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Prepare SQL query
        $sql = "SELECT * FROM products WHERE title LIKE ? OR description LIKE ?";
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $searchTerm = '%' . $searchTerm . '%';
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        
        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<div class='results'>";
            while ($row = $result->fetch_assoc()) {
                echo "<h3>" . $row['title'] . "</h3>";
                echo "<p>" . $row['description'] . "</p>";
            }
            echo "</div>";
        } else {
            echo "<p>No results found.</p>";
        }
        
        // Close database connection
        $conn->close();
    } else {
        echo "<p>Please enter a search term above and click the Search button to find products.</p>";
    }
    ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="search-box">
        <input type="text" name="search_term" placeholder="Enter your search...">
        <button type="submit">Search</button>
    </form>
</body>
</html>


<?php
// This is the PHP file that will handle the search functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_query = trim($_POST['search_query']);

    // Connect to your database here if needed
    // For this example, we'll use a simple array as our "database"
    $data = [
        'apple',
        'banana',
        'cherry',
        'date',
        'elderberry'
    ];

    $results = [];
    
    foreach ($data as $item) {
        if (stripos($item, $search_query) !== false) {
            $results[] = $item;
        }
    }

    // Display the results
    echo "<h2>Search Results:</h2>";
    if (!empty($results)) {
        echo "<ul>";
        foreach ($results as $result) {
            echo "<li>" . htmlspecialchars($result, ENT_QUOTES) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No results found.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-container {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }
        #search_input {
            width: 70%;
            padding: 8px;
            margin-right: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        #search_button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        #search_button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="text" id="search_input" name="search_query" placeholder="Search...">
            <button type="submit" id="search_button">Search</button>
        </form>
    </div>

    <?php
    // Display any search results here if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $search_query = trim($_POST['search_query']);
        if ($search_query !== '') {
            echo "<h3>Search Results for: '" . htmlspecialchars($search_query, ENT_QUOTES) . "'</h3>";
        }
    }
    ?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add some basic styling -->
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .results {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
        }

        .result-item {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
        }

        .result-item:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
<?php
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    
    // Sanitize the input to prevent SQL injection or XSS attacks
    $searchTerm = htmlspecialchars(trim($searchTerm));
    
    // Here you would typically connect to your database and query for results
    // For this example, we'll simulate some results
    $results = [
        ['title' => 'Result 1', 'description' => 'This is the first search result.'],
        ['title' => 'Result 2', 'description' => 'This is the second search result.'],
        ['title' => 'Result 3', 'description' => 'This is the third search result.']
    ];
} else {
    // No results to display
    $results = [];
}

?>

<div class="search-container">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" name="search" class="search-box" placeholder="Search here..." 
               value="<?php if (isset($searchTerm)) { echo $searchTerm; } ?>">
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($results)): ?>
        <div class="results">
            <h3>Results for: "<?php echo $searchTerm; ?>"</h3>
            <?php foreach ($results as $result): ?>
                <div class="result-item">
                    <h4><?php echo $result['title']; ?></h4>
                    <p><?php echo $result['description']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <?php if (isset($searchTerm)): ?>
            <div class="results">
                <h3>No results found for "<?php echo $searchTerm; ?>".</h3>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-box {
            display: flex;
            gap: 10px;
        }
        
        input[type="text"] {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 75%;
        }
        
        button {
            padding: 8px 16px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="search-box">
                <input type="text" name="query" placeholder="Search here...">
                <button type="submit">Search</button>
            </div>
        </form>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $query = $_POST['query'];
            
            // Sanitize the input to prevent SQL injection
            $query = htmlspecialchars(trim($query));
            
            // Database connection details
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'mydatabase';
            
            // Connect to database
            $conn = mysqli_connect($host, $username, $password, $database);
            
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // SQL query to search for matching results
            $sql = "SELECT * FROM users WHERE name LIKE '%" . mysqli_real_escape_string($conn, $query) . "%'";
            
            $result = mysqli_query($conn, $sql);
            
            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }
            
            if (mysqli_num_rows($result) > 0) {
                echo "<div class='results'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<p>" . $row['name'] . "</p>";
                }
                echo "</div>";
            } else {
                echo "<div class='results'>";
                echo "No results found!";
                echo "</div>";
            }
            
            // Close the database connection
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        
        .search-bar {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            min-height: 100px;
        }
    </style>
</head>
<body>
    <?php
    $searchQuery = "";
    $results = array();
    
    // Sample data for demonstration
    $data = array(
        "Apple",
        "Banana",
        "Cherry",
        "Date",
        "Elderberry",
        "Fig",
        "Grape",
        "Honeydew Melon"
    );
    
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Check if search term is provided
        if (isset($_GET['search'])) {
            $searchQuery = $_GET['search'];
            
            // Search through the data array
            foreach ($data as $item) {
                if (stripos($item, $searchQuery) !== false) {
                    array_push($results, $item);
                }
            }
        } else {
            // If no search term provided, show all items
            $results = $data;
        }
    }
    
    // Display error message if no search query is provided
    if (empty($searchQuery)) {
        echo "<div style='color: red; margin-bottom: 10px;'>Please enter a search term.</div>";
    }
    ?>
    
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            <input type="text" name="search" class="search-bar" placeholder="Search..." value="<?php echo $searchQuery; ?>">
            <button type="submit" class="search-button">Search</button>
        </form>
        
        <div class="results">
            <?php if (!empty($results)) : ?>
                <h3>Results:</h3>
                <ul>
                    <?php foreach ($results as $result) : ?>
                        <li><?php echo $result; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>


<?php
// Get the search query from the URL parameter
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Sanitize the input
$query = htmlspecialchars($query);

if ($query == '') {
    // If no query is provided, display a message
    echo "<h2>Search Results</h2>";
    echo "<p>Please enter a search term.</p>";
} else {
    // Connect to the database (replace with your own credentials)
    $conn = mysqli_connect('localhost', 'username', 'password', 'database_name');
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Prepare and execute the SQL query
    $query = mysqli_real_escape_string($conn, $query);
    $sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $query . "%'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        echo "<h2>Search Results</h2>";
        echo "<p>No results found for: " . $query . "</p>";
    } else {
        echo "<h2>Search Results</h2>";
        while ($row = mysqli_fetch_assoc($result)) {
            // Display your results here
            echo "<div class='results'>";
            echo "<p>" . $row['column_name'] . "</p>";
            echo "</div>";
        }
    }
    
    // Close the database connection
    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-container {
            margin-bottom: 20px;
        }
        
        input[type="text"] {
            width: 75%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Search Bar Example</h1>
    
    <!-- Search form -->
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="search_query" placeholder="Enter your search..." <?php if (isset($_POST['search_query'])) { echo 'value="' . $_POST['search_query'] . '"'; } ?>>
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Display search results -->
    <?php
    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $searchQuery = $_POST['search_query'];
        
        // Here you would typically connect to a database and fetch results based on the search query
        // For this example, we'll just display the search query
        
        echo "<h2>Search Results</h2>";
        echo "<p>You searched for: " . htmlspecialchars($searchQuery) . "</p>";
    }
    ?>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-form {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        input[type="text"] {
            width: 70%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 5px;
        }
        
        input[type="submit"] {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: white;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" class="search-form">
            <input type="text" name="search_term" placeholder="Search here...">
            <input type="submit" name="search" value="Search">
        </form>

        <?php
        // Database connection
        $host = 'localhost';
        $username = 'your_username';
        $password = 'your_password';
        $database = 'your_database';

        // Create connection
        $conn = mysqli_connect($host, $username, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        if (isset($_POST['search'])) {
            $search_term = $_POST['search_term'];
            
            // Sanitize the input to prevent SQL injection
            $search_term = mysqli_real_escape_string($conn, $search_term);

            // Query database
            if ($search_term != '') {
                // Search query
                $sql = "SELECT * FROM your_table WHERE column1 LIKE '%{$search_term}%' 
                        OR column2 LIKE '%{$search_term}%'";
            } else {
                // Display all records
                $sql = "SELECT * FROM your_table";
            }

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo "<div class='results'>";
                echo "<table border='1'>
                        <tr>
                            <th>Column1</th>
                            <th>Column2</th>
                            <!-- Add more columns as needed -->
                        </tr>";
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['column1'] . "</td>";
                    echo "<td>" . $row['column2'] . "</td>";
                    // Add more columns as needed
                    echo "</tr>";
                }
                
                echo "</table>";
                echo "</div>";
            } else {
                echo "<p>No results found.</p>";
            }

            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        /* Add some basic styling */
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-input {
            width: 80%;
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        .result-row {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <?php
    // Database connection parameters
    $host = "localhost";
    $username = "your_username";
    $password = "your_password";
    $database = "your_database";

    // Create database connection
    $conn = mysqli_connect($host, $username, $password, $database);

    // Check if connection failed
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Handle search query
    if (isset($_POST['submit'])) {
        $search_term = $_POST['search'];

        // Sanitize the input
        $search_term = trim($search_term);
        $search_term = mysqli_real_escape_string($conn, $search_term);

        // Query database
        $sql = "SELECT * FROM users WHERE 
                first_name LIKE '%$search_term%' OR 
                last_name LIKE '%$search_term%' OR 
                email LIKE '%$search_term%'";
        
        $result = mysqli_query($conn, $sql);

        // Display results
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='result-row'>";
                echo "Name: " . $row['first_name'] . " " . $row['last_name'];
                echo "<br>Email: " . $row['email'];
                echo "</div>";
            }
        } else {
            echo "<p>No results found.</p>";
        }
    }
    ?>

    <div class="search-container">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search" class="search-input" placeholder="Search...">
            <button type="submit" name="submit" class="search-button">Search</button>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            echo "<div class='results'>";
            // Results will be displayed here
            echo "</div>";
        }
        ?>
    </div>

    <!-- Close database connection -->
    <?php mysqli_close($conn); ?>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar Example</title>
    <!-- Add some basic styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 5px;
        }
        
        .search-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search form -->
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search" class="search-bar" placeholder="Search here...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if search parameter is set
        if (isset($_GET['search'])) {
            $search = htmlspecialchars($_GET['search']);
            
            // Database connection
            $host = 'localhost';
            $username = 'your_username';
            $password = 'your_password';
            $database = 'your_database';

            // Connect to database
            $conn = new mysqli($host, $username, $password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL query to search for the keyword
            $sql = "SELECT * FROM products WHERE name LIKE '%{$search}%'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<h3>Search Results:</h3>";
                while ($row = $result->fetch_assoc()) {
                    echo "<p>" . $row['name'] . "</p>";
                }
            } else {
                echo "No results found.";
            }

            // Close database connection
            $conn->close();
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="search_query" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if search query is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search_query = htmlspecialchars($_POST['search_query']);

            // Database connection details
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'my_database';

            // Connect to database
            $conn = mysqli_connect($host, $username, $password, $database);

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Search query
            $sql = "SELECT * FROM my_table WHERE column1 LIKE %$search_query% OR column2 LIKE %$search_query%";
            
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $search_query);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if ($result->num_rows > 0) {
                // Output the results
                while ($row = $result->fetch_assoc()) {
                    echo "<div style='margin: 10px 0; padding: 10px; background-color: white; border-radius: 4px;'>".
                         "ID: " . $row['id'] . "<br>" .
                         "Name: " . $row['name'] . "<br>" .
                         "</div>";
                }
            } else {
                echo "<p>No results found for '" . $search_query . "'</p>";
            }

            // Close database connection
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        #searchForm {
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search Form -->
        <form id="searchForm" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="query" placeholder="Search here...">
            <button type="submit">Search</button>
        </form>

        <!-- Display Search Results -->
        <?php
        if (isset($_GET['query'])) {
            $searchTerm = htmlspecialchars($_GET['query']);
            
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "mydatabase";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL query to search for the term in your database
            $sql = "SELECT * FROM posts WHERE title LIKE ? OR content LIKE ?";
            $stmt = $conn->prepare($sql);
            $searchTerm = "%$searchTerm%";
            $stmt->bind_param("ss", $searchTerm, $searchTerm);

            // Execute the query
            $stmt->execute();
            
            // Get the result set
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<div class='search-results'>";
                while ($row = $result->fetch_assoc()) {
                    echo "<div style='border-bottom: 1px solid #ddd; padding: 10px; margin-bottom: 10px;'>";
                    echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                    echo "<p>" . htmlspecialchars($row['content']) . "</p>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<div class='search-results'><p>No results found for: " . $searchTerm . "</p></div>";
            }

            // Close the database connection
            $conn->close();
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <!-- Add some basic styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-bar {
            width: 75%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            <input type="text" name="query" class="search-bar" placeholder="Search..." <?php if(isset($_GET['query'])) { echo 'value="' . $_GET['query'] . '"'; } ?>>
            <button type="submit" class="search-button">Search</button>
        </form>

        <!-- Display results -->
        <?php
            // Connect to database
            $db_host = "localhost";
            $db_user = "root";
            $db_pass = "";
            $db_name = "test_db";

            $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Get search term
            $search_term = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : "";

            // SQL query to fetch results
            $sql = "SELECT * FROM your_table WHERE 
                    column1 LIKE '%$search_term%' OR 
                    column2 LIKE '%$search_term%'";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo "<div class='results'><h3>Results:</h3><table>";
                while($row = mysqli_fetch_assoc($result)) {
                    // Display the results in a table format
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        if ($key !== "id") { // Skip the ID column or modify according to your needs
                            echo "<td>" . htmlspecialchars($value) . "</td>";
                        }
                    }
                    echo "</tr>";
                }
                echo "</table></div>";
            } else {
                echo "<p>No results found.</p>";
            }

            // Close database connection
            mysqli_close($conn);
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-container {
            margin-bottom: 20px;
        }
        
        #searchForm {
            display: flex;
            align-items: center;
        }
        
        #searchInput {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        #searchButton {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        #searchButton:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form id="searchForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" id="searchInput" name="search" placeholder="Search...">
            <button type="submit" id="searchButton">Search</button>
        </form>
    </div>

    <?php
    // Connect to the database
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'your_database_name';

    try {
        $conn = new mysqli($host, $username, $password, $database);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        if (isset($_POST['search'])) {
            // Sanitize input
            $searchTerm = htmlspecialchars(stripslashes(trim($_POST['search'])));
            
            // Prepare and bind the SQL statement
            $stmt = $conn->prepare("SELECT * FROM your_table_name WHERE column1 LIKE ? OR column2 LIKE ?");
            $searchPattern = "%$searchTerm%";
            $stmt->bind_param("ss", $searchPattern, $searchPattern);
            
            // Execute the query
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Display results
            if ($result->num_rows > 0) {
                echo "<div class='results'>";
                echo "<h2>Search Results:</h2>";
                echo "<table border='1'>
                        <tr>
                            <th>Column 1</th>
                            <th>Column 2</th>
                            <!-- Add more columns as needed -->
                        </tr>";
                
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['column1']}</td>
                            <td>{$row['column2']}</td>
                            <!-- Add more columns as needed -->
                          </tr>";
                }
                
                echo "</table>";
                echo "</div>";
            } else {
                echo "<p>No results found.</p>";
            }
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    } finally {
        // Close the database connection
        if ($conn != null) {
            $conn->close();
        }
    }
    ?>

</body>
</html>


<?php
// Get the search term from the form
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Sanitize the input
$safe_query = htmlspecialchars($query);

// Database connection details
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "mydatabase";

// Create database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Escape the query to prevent SQL injection
$escaped_query = mysqli_real_escape_string($conn, $safe_query);

// Search in the database
$sql = "SELECT * FROM users WHERE name LIKE '%{$escaped_query}%'";

$result = mysqli_query($conn, $sql);

// Check if any results were found
if (mysqli_num_rows($result) > 0) {
    // Output the results
    while ($row = mysqli_fetch_assoc($result)) {
        echo "Name: " . $row["name"] . "<br>";
        // Add more fields as needed
    }
} else {
    echo "No results found!";
}

// Close database connection
mysqli_close($conn);

// Link back to the search form
echo "<p><a href='search_form.php'>Back to search</a></p>";
?>


<?php
// Search handler (search.php)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $query = isset($_GET['query']) ? $_GET['query'] : '';
    
    // Sanitize the input
    $query = mysqli_real_escape_string($conn, $query);
    
    // Database connection
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'your_database';
    
    $conn = mysqli_connect($host, $username, $password, $database);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Search query
    $sql = "SELECT * FROM users WHERE first_name LIKE '%{$query}%' OR last_name LIKE '%{$query}%'";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }
    
    // Display results
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='result'>";
        echo "<h3>" . $row['first_name'] . " " . $row['last_name'] . "</h3>";
        echo "<p>Email: " . $row['email'] . "</p>";
        echo "</div>";
    }
    
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
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .result {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="search.php" method="GET">
            <input type="text" name="query" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <!-- Add some basic styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }

        /* Style for search results */
        .result {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<?php
// Connect to the database
$host = "localhost";
$username = "root"; // Change to your username
$password = "";     // Change to your password
$db_name = "testdb"; // Change to your database name

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search query from POST data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = $conn->real_escape_string($_POST['search']);

    // Query the database for results
    $sql = "SELECT * FROM your_table WHERE column LIKE '%$search_query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Display each result row (modify this based on your table structure)
            echo "<div class='result'>";
            echo "ID: " . $row['id'] . "<br>";
            echo "Name: " . $row['name'] . "<br>";
            echo "Description: " . $row['description'];
            echo "</div>";
        }
    } else {
        echo "<div class='result'>No results found for '" . $search_query . "'.</div>";
    }
}
?>

<div class="search-container">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" name="search" class="search-bar" placeholder="Search...">
        <button type="submit" class="search-button">Search</button>
    </form>
</div>

<?php
// Close database connection
$conn->close();
?>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add CSS styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-container {
            margin-bottom: 20px;
        }
        #searchInput {
            width: 70%;
            padding: 10px;
            font-size: 16px;
        }
        #searchButton {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        #searchButton:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
        .result-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Search Bar Example</h1>

    <!-- Search form -->
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="text" id="searchInput" name="searchTerm" placeholder="Enter search term...">
            <button type="submit" id="searchButton">Search</button>
        </form>
    </div>

    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchTerm = htmlspecialchars(trim($_POST["searchTerm"]));
        
        // Connect to database (replace with your own credentials)
        $dbHost = 'localhost';
        $dbName = 'my_database';
        $dbUser = 'username';
        $dbPass = 'password';

        try {
            $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
            
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Search query (replace with your table and columns)
            $sql = "SELECT * FROM my_table WHERE name LIKE ?";
            $stmt = $conn->prepare($sql);
            $searchTerm = "%$searchTerm%";
            $stmt->bind_param("s", $searchTerm);
            
            $stmt->execute();
            $result = $stmt->get_result();

            // Display results
            if ($result->num_rows > 0) {
                echo "<div class=\"results\">";
                while($row = $result->fetch_assoc()) {
                    echo "<div class=\"result-item\">";
                    echo "<h3>" . htmlspecialchars($row["name"]) . "</h3>";
                    echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p>No results found.</p>";
            }

            // Close connections
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
    ?>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-container {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 20px;
        }

        .search-bar input[type="text"] {
            width: 75%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-bar button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search...">
                <button type="submit">Go</button>
            </div>
        </form>
    </div>

    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchTerm = sanitizeInput($_POST['search']);

        // Database connection details
        $host = 'localhost';
        $username = 'your_username';
        $password = 'your_password';
        $database = 'your_database';

        try {
            // Connect to database
            $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // SQL query
            $sql = "SELECT * FROM your_table WHERE column LIKE :searchTerm";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['searchTerm' => '%' . $searchTerm . '%']);

            // Display results
            echo "<div class='search-results'>";
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch()) {
                    echo "<p>" . $row['column'] . "</p>";
                }
            } else {
                echo "No results found.";
            }
            echo "</div>";
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        // Close connection
        $conn = null;
    }

    // Function to sanitize input
    function sanitizeInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
    ?>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        
        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
            // Check if the form has been submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $searchTerm = $_POST['search'];
                
                // Sanitize input
                $searchTerm = htmlspecialchars(trim($searchTerm));
                
                // Display search results
                echo "<h3>Search Results for: '$searchTerm'</h3>";
                echo "<div class='results'>";
                
                if (empty($searchTerm)) {
                    echo "<p>Please enter a search term.</p>";
                } else {
                    // Here you would typically connect to your database and query the results
                    // For this example, we'll just display the search term
                    
                    // Example of connecting to a MySQL database:
                    $host = "localhost";
                    $dbUsername = "root";
                    $dbPassword = "";
                    $dbName = "my_database";
                    
                    // Connect to database
                    $conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);
                    
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }
                    
                    // SQL query with prepared statement
                    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE name LIKE ?");
                    mysqli_stmt_bind_param($stmt, "s", $searchTerm);
                    mysqli_stmt_execute($stmt);
                    
                    $result = mysqli_stmt_get_result($stmt);
                    
                    if (mysqli_num_rows($result) > 0) {
                        // Display the results in a table
                        echo "<table border='1'>";
                        echo "<tr><th>Name</th><th>Email</th></tr>";
                        
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>No results found.</p>";
                    }
                    
                    // Close database connection
                    mysqli_close($conn);
                }
                
                echo "</div>";
            }
        ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add some basic CSS styling -->
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: white;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search form -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="query" class="search-box" placeholder="Search here...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if form is submitted
        if (isset($_GET['submit'])) {
            $search = htmlspecialchars($_GET['query']);

            // Database connection
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }

            // SQL query
            $sql = "SELECT * FROM your_table WHERE column_name LIKE ?";
            
            // Prepare and execute the statement
            try {
                $stmt = $pdo->prepare($sql);
                $searchQuery = '%' . $search . '%';
                $stmt->execute([$searchQuery]);

                // Display results
                if ($stmt->rowCount() > 0) {
                    echo "<div class='results'>";
                    echo "<h3>Results:</h3>";
                    echo "<table border='1'>";
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        foreach ($row as $value) {
                            echo "<td>$value</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "<div class='results'>";
                    echo "No results found.";
                    echo "</div>";
                }
            } catch(PDOException $e) {
                die("Query failed: " . $e->getMessage());
            }
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <!-- Add some basic CSS styling -->
    <style>
        .search-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        .search-form {
            display: flex;
            gap: 10px;
        }
        input[type="text"] {
            padding: 10px;
            width: 70%;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search form -->
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="search-form">
            <input type="text" name="search_term" placeholder="Search here..." value="<?php if(isset($_GET['search_term'])) { echo $_GET['search_term']; } ?>">
            <button type="submit" name="search">Search</button>
        </form>

        <!-- Results will be displayed here -->
        <div class="results">
            <?php
                // Database configuration
                $host = 'localhost';
                $username = 'your_username';
                $password = 'your_password';
                $database = 'your_database';

                try {
                    // Create database connection
                    $conn = new mysqli($host, $username, $password, $database);

                    if ($conn->connect_error) {
                        throw new Exception("Connection failed: " . $conn->connect_error);
                    }

                    if (isset($_GET['search'])) {
                        // Get search term
                        $search_term = $conn->real_escape_string(trim($_GET['search_term']));

                        // Query database
                        $query = "
                            SELECT id, title, description 
                            FROM your_table_name 
                            WHERE title LIKE '%{$search_term}%' 
                            OR description LIKE '%{$search_term}%'
                        ";

                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            // Display results
                            while ($row = $result->fetch_assoc()) {
                                echo "<h3>" . $row['title'] . "</h3>";
                                echo "<p>" . $row['description'] . "</p>";
                                echo "<hr>";
                            }
                        } else {
                            echo "No results found.";
                        }
                    }

                } catch (Exception $e) {
                    // Display error message
                    echo "An error occurred: " . $e->getMessage();
                }

                // Close database connection
                if ($conn) {
                    $conn->close();
                }
            ?>
        </div>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>
    <!-- Search Form -->
    <form action="process_search.php" method="GET">
        <input type="text" name="query" placeholder="Enter your search query...">
        <input type="submit" value="Search">
    </form>
</body>
</html>


<?php
// Retrieve the search term entered by user
$query = $_GET['query'];

// Process the search query here
// You can connect to your database and perform a search based on $query

echo "You searched for: " . htmlspecialchars($query);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="text" name="search" class="search-box" placeholder="Search...">
            <button type="submit" name="submit" class="search-button">Search</button>
        </form>
        
        <?php
        // Check if form is submitted
        if (isset($_POST['submit'])) {
            $search = mysqli_real_escape_string($conn, $_POST['search']);
            
            // Database connection
            $conn = mysqli_connect("localhost", "username", "password", "database_name");
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // Search query
            $sql = "SELECT * FROM table_name WHERE column_name LIKE '%" . $search . "%'";
            $result = mysqli_query($conn, $sql);
            
            // Display results
            if (mysqli_num_rows($result) > 0) {
                echo "<div class='results'>";
                echo "<table>";
                while ($row = mysqli_fetch_assoc($result)) {
                    // Display your table headers and rows here
                    // For example:
                    echo "<tr><td>" . $row['column_name'] . "</td></tr>";
                }
                echo "</table>";
                echo "</div>";
            } else {
                echo "<p>No results found.</p>";
            }
            
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


// Number of results per page
$results_per_page = 10;

// Get current page number
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_offset = ($page - 1) * $results_per_page;

$sql = "SELECT * FROM your_table WHERE title LIKE '%" . $search_term . "%' OR description LIKE '%" . $search_term . "%' LIMIT " . $start_offset . ", " . $results_per_page;

$result = $conn->query($sql);

// Calculate total number of results
$total_results = $conn->query("SELECT COUNT(*) as count FROM your_table WHERE title LIKE '%" . $search_term . "%' OR description LIKE '%" . $search_term . "%'");
$total_results_row = $total_results->fetch_assoc();
$total_pages = ceil($total_results_row['count'] / $results_per_page);

// Display pagination links
if ($total_pages > 1) {
    echo "<div class='pagination'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $page) {
            echo "<span>" . $i . "</span>";
        } else {
            echo "<a href='".$_SERVER['PHP_SELF']."?page=" . $i ."'>" . $i . "</a>";
        }
    }
    echo "</div>";
}


<?php
// Get search term from form input
$search = $_POST['search'];

// Sanitize the input to prevent SQL injection
$escaped_search = mysqli_real_escape_string($connection, $search);

// Connect to database
$host = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to search for the term in your database table
$sql = "SELECT * FROM your_table WHERE column1 LIKE '%$escaped_search%' 
        OR column2 LIKE '%$escaped_search%'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Output the results
    while ($row = mysqli_fetch_assoc($result)) {
        echo "Found: " . $row['column1'] . "<br>";
    }
} else {
    echo "No results found";
}

// Close database connection
mysqli_close($conn);
?>


<?php
if (isset($_GET['query']) && $_GET['query'] != '') {
    $searchQuery = htmlspecialchars($_GET['query']);
    
    // Connect to database
    $db_host = 'localhost';
    $db_username = 'username';
    $db_password = 'password';
    $db_name = 'database_name';
    
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare SQL statement
    $sql = "SELECT * FROM your_table WHERE column LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchValue = "%" . $searchQuery . "%";
    $stmt->bind_param("s", $searchValue);
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<h3>Search Results for: '" . $searchQuery . "'</h3>";
        while ($row = $result->fetch_assoc()) {
            // Display your results here
            echo "<div class='result'>";
            echo "<p>" . $row['column_name'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No results found for '" . $searchQuery . "'</p>";
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "<p>Enter a search query above and press Enter or click Search.</p>";
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php
    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search_query = $_POST['search_query'];
        
        // Connect to database
        $db_host = 'localhost';
        $db_user = 'username';
        $db_pass = 'password';
        $db_name = 'database_name';
        
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Sanitize input
        $search_query = mysqli_real_escape_string($conn, $search_query);
        
        // Search query
        $sql = "SELECT * FROM users WHERE first_name LIKE '%{$search_query}%' OR last_name LIKE '%{$search_query}%'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>First Name</th><th>Last Name</th><th>Email</th></tr>";
            
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['first_name'] . "</td>";
                echo "<td>" . $row['last_name'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
        } else {
            echo "No results found.";
        }
        
        $conn->close();
    }
    ?>
    
    <div class="search-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search_query" class="search-bar" placeholder="Search...">
            <button type="submit" name="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
// Search function to query the database
function search() {
    // Database connection parameters
    $host = 'localhost';  // Replace with your database host
    $user = 'root';       // Replace with your database username
    $pass = '';           // Replace with your database password
    $db_name = 'test_db'; // Replace with your database name

    // Connect to the database
    $conn = new mysqli($host, $user, $pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get search query from form input
    $query = $_POST['search'];

    // Sanitize the input to prevent SQL injection
    $query = mysqli_real_escape_string($conn, $query);

    // SQL query to fetch data based on the search term
    $sql = "SELECT * FROM your_table_name WHERE column_name LIKE '%$query%'";
    
    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display results in a table
        echo "<table class='table table-striped'>";
        echo "<tr><th>Column 1</th><th>Column 2</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row['column1'] . "</td><td>" . $row['column2'] . "</td></tr>";
        }
        echo "</table>";
    } else {
        // Display message if no results found
        echo "No results found.";
    }

    // Close the database connection
    $conn->close();
}

// Check if form is submitted and run search function
if (isset($_POST['submit'])) {
    search();
}
?>

<!-- HTML for the search bar -->
<div class="container mt-5">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="input-group mb-3">
            <input type="text" name="search" class="form-control" placeholder="Search...">
            <button type="submit" name="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </form>

    <!-- Display search results -->
    <?php 
    // If form is submitted, display results
    if (isset($_POST['submit'])) {
        echo "<h3 class='mt-4'>Search Results:</h3>";
    }
    ?>
</div>

<!-- Font Awesome for icons -->
<script src="https://kit.fontawesome.com/a076d05b99.js"></script>

</body>
</html>


<?php
// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Get the search term from the form
    $search_term = $_POST['search'];

    // Connect to the database
    $db_host = 'localhost';
    $db_user = 'username';
    $db_pass = 'password';
    $db_name = 'database_name';

    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Escape the search term to prevent SQL injection
    $search_term = mysqli_real_escape_string($conn, $search_term);

    // Query the database for matching results
    $sql = "SELECT * FROM products WHERE name LIKE '%{$search_term}%' OR description LIKE '%{$search_term}%'";
    $result = mysqli_query($conn, $sql);

    // Display the results
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<h2>" . $row['name'] . "</h2>";
            echo "<p>" . $row['description'] . "</p>";
        }
    } else {
        echo "No results found.";
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="search" placeholder="Search...">
        <button type="submit" name="submit">Search</button>
    </form>
</body>
</html>


<?php
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = $_POST['query'];

    // Database connection
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'your_database';

    // Connect to database
    $connection = mysqli_connect($host, $username, $password, $database);

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // SQL query with search functionality
    $sql = "SELECT * FROM your_table 
            WHERE title LIKE '%" . htmlspecialchars($query) . "%' 
            OR description LIKE '%" . htmlspecialchars($query) . "%'";
    
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Display results
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="card mb-3">';
                echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row['title'] . '</h5>';
                    echo '<p class="card-text">' . $row['description'] . '</p>';
                echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="alert alert-info">No results found!</div>';
    }

    // Close database connection
    mysqli_close($connection);
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <!-- Add some basic styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="search-container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
        <input type="text" placeholder="Search..." name="query" class="search-box">
        <button type="submit" class="search-button">Search</button>
    </form>
    
    <?php
    // Check if form is submitted
    if (isset($_GET['query'])) {
        $search = $_GET['query'];
        
        // Sanitize the input to prevent SQL injection
        $search = htmlspecialchars(trim($search));
        
        // Database connection details
        $host = "localhost";
        $dbusername = "root";
        $dbpassword = "";
        $dbname = "your_database_name";
        
        // Connect to database
        $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // SQL query to search for the term in your table
        $sql = "SELECT * FROM your_table_name WHERE 
                name LIKE '%" . $search . "%' 
                OR description LIKE '%" . $search . "%'";
                
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            // Output the results
            echo "<table border='1'>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["description"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No results found.";
        }
        
        // Close the database connection
        $conn->close();
    }
    ?>
</div>

</body>
</html>


<?php
// process.php

// Get the search query from POST request
$searchQuery = isset($_POST['search_query']) ? $_POST['search_query'] : '';

if (!empty($searchQuery)) {
    // Sanitize the input
    $searchQuery = htmlspecialchars(trim($searchQuery));

    // Connect to your database (replace with your actual credentials)
    $host = 'localhost';
    $user = 'username';
    $pass = 'password';
    $db   = 'database_name';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL query
        $stmt = $conn->prepare("SELECT * FROM your_table WHERE column LIKE :search");
        
        // Bind parameters
        $searchTerm = '%' . $searchQuery . '%';
        $stmt->bindParam(':search', $searchTerm);
        
        // Execute the query
        $stmt->execute();

        // Fetch results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            echo "<div class='results'>";
            foreach ($results as $row) {
                // Display your results here based on your table structure
                echo "<p>" . $row['column_name'] . "</p>";
            }
            echo "</div>";
        } else {
            echo "No results found.";
        }

    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // If no search query is provided
    echo "Please enter a search term.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
        }
        
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 5px;
        }
        
        .search-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .search-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="search_query" class="search-bar" placeholder="Search...">
            <button type="submit" name="submit" class="search-btn">Search</button>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $search_query = isset($_POST['search_query']) ? $_POST['search_query'] : '';
            
            // Sanitize input to prevent SQL injection or XSS attacks
            $search_query = trim($search_query);
            $search_query = htmlspecialchars($search_query);
            
            if (!empty($search_query)) {
                echo "<h3>Search Results for: " . $search_query . "</h3>";
                
                // Here you would typically connect to your database and query the results
                // For this example, we'll just display the search term
                echo "<p>Your search term is: " . $search_query . "</p>";
            } else {
                echo "<p>Please enter a search term.</p>";
            }
        }
        ?>
    </div>
</body>
</html>


<?php
// Connect to database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'my_database';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Perform search query
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchQuery = $_POST['search'];
    $searchQuery = mysqli_real_escape_string($conn, $searchQuery);
    
    $sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $searchQuery . "%'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Display results
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>" . $row['column_name'] . "</p>";
        }
    } else {
        echo "No results found.";
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search query from form
$search_query = mysqli_real_escape_string($conn, $_POST['query']);

// Search query
$sql = "SELECT * FROM your_table_name WHERE title LIKE '%$search_query%' OR description LIKE '%$search_query%'";
$result = mysqli_query($conn, $sql);

echo "<table border='1'>
<tr>
<th>ID</th>
<th>Title</th>
<th>Description</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['title'] . "</td>";
    echo "<td>" . $row['description'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Close database connection
mysqli_close($conn);
?>

<?php if (mysqli_num_rows($result) == 0) { ?>
    <div style="color: red; text-align: center;">No results found!</div>
<?php } ?>


<?php
// This is a simple PHP script for a search functionality

// Database connection details (you need to modify these)
$host = "localhost";
$username = "root";
$password = "";
$database = "search_db";

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search query
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get search term from URL parameters (using GET method)
    $search = isset($_GET['query']) ? $_GET['query'] : '';
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get search term from form submission (using POST method)
    $search = isset($_POST['query']) ? $_POST['query'] : '';
}

// Clean the input to prevent SQL injection
$search = $conn->real_escape_string($search);

// Check if search term is not empty
if ($search != '') {
    // Search in the database table (replace 'your_table' with your actual table name)
    $sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $search . "%'";
    
    // Execute the query
    $result = $conn->query($sql);
    
    // Display results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"] . "<br>";
            echo "Name: " . $row["name"] . "<br>";
            // Add more fields as needed
            echo "<hr>";
        }
    } else {
        echo "No results found.";
    }
} else {
    echo "Please enter a search term.";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <!-- Add some basic CSS styling -->
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-bar {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .submit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search form using GET method -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <input type="text" name="query" class="search-bar" placeholder="Search here...">
            <button type="submit" class="submit-btn">Search</button>
        </form>

        <!-- Alternatively, you can use POST method -->
        <!-- 
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="query" class="search-bar" placeholder="Search here...">
            <button type="submit" class="submit-btn">Search</button>
        </form>
        -->
    </div>
</body>
</html>


$stmt = $conn->prepare("SELECT * FROM your_table WHERE column_name LIKE ?");
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();


try {
    // Database operations
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-box {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php
    // Check if form was submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get search query from POST data
        $searchQuery = $_POST['search_query'];
        
        // Sanitize the input to prevent SQL injection or XSS attacks
        $searchQuery = htmlspecialchars($searchQuery);
        $searchQuery = trim($searchQuery);

        // Database connection parameters
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "mydatabase";

        // Connect to database
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // SQL query to search for the query in your database
        $sql = "SELECT * FROM products WHERE title LIKE '%" . $searchQuery . "%' OR description LIKE '%" . $searchQuery . "%'";
        
        // Execute the query
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Title</th><th>Description</th><th>Price</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['title'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "<td>" . $row['price'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No results found";
        }

        // Close database connection
        mysqli_close($conn);
    }
    ?>

    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="search_query" class="search-box" placeholder="Search...">
            <input type="submit" value="Search" class="search-button">
        </form>
    </div>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
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
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <div class="search-bar">
                <input type="text" name="search_query" placeholder="Search here..." <?php if(isset($_GET['search_query'])) { echo 'value="' . htmlspecialchars($_GET['search_query']) . '"'; } ?>>
                <button type="submit">Search</button>
            </div>
        </form>

        <?php
        // Handle the search query
        if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
            $searchQuery = htmlspecialchars($_GET['search_query']);
            echo "<h3>Search Results for: " . $searchQuery . "</h3>";
            
            // Here you would typically connect to your database and perform the search
            // For this example, we'll just display the search query
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-box input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .search-box button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-box button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            font-family: Arial, sans-serif;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="search-box">
                <input type="text" name="search" placeholder="Search...">
                <button type="submit">Search</button>
            </div>
        </form>

        <?php
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchTerm = $_POST['search'];

            // Sanitize input to prevent SQL injection and XSS
            $searchTerm = htmlspecialchars($searchTerm);
            $searchTerm = trim($searchTerm);

            // Database connection details
            $host = 'localhost';
            $dbUsername = 'root'; // Change this to your database username
            $dbPassword = '';     // Change this to your database password
            $dbName = 'your_database_name'; // Change this to your database name

            // Connect to database
            try {
                $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
                
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // SQL query to search for the term in your table
                $sql = "SELECT * FROM your_table_name WHERE column_name LIKE '%" . $searchTerm . "%'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<div class='results'>";
                    while($row = $result->fetch_assoc()) {
                        // Display the results according to your table structure
                        echo "<div>" . $row['column_name'] . "</div>";
                    }
                    echo "</div>";
                } else {
                    echo "<p class='error'>No results found.</p>";
                }

                // Close database connection
                $conn->close();
            } catch (Exception $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>
    <?php
        // If form is submitted
        if (isset($_GET['query'])) {
            $searchQuery = $_GET['query'];
            
            // Connect to the database
            $dbHost = 'localhost';
            $dbName = 'your_database_name';
            $dbUser = 'your_username';
            $dbPass = 'your_password';

            $connection = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
            
            if (!$connection) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // Sanitize the input
            $searchQuery = htmlspecialchars($searchQuery);
            
            // Escape special characters
            $searchQuery = mysqli_real_escape_string($connection, $searchQuery);
            
            // Query the database
            $sql = "SELECT * FROM products WHERE name LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%'";
            $result = mysqli_query($connection, $sql);
            
            if (!$result) {
                die("Query failed: " . mysqli_error($connection));
            }
            
            // Display the results
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Name</th><th>Description</th></tr>";
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            
            // Close the database connection
            mysqli_close($connection);
        } else {
            // Display search form
    ?>
    
    <form action="search.php" method="get">
        <input type="text" name="query" placeholder="Search...">
        <button type="submit">Search</button>
    </form>

<?php
        }
?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .search-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: inline-block;
        }
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .search-button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="search" placeholder="Search here..." class="search-box">
            <button type="submit" class="search-button">Search</button>
        </form>
        
        <?php
        // Simulating a database connection (you would replace this with your actual database connection)
        $mock_database = array(
            array('id' => 1, 'title' => 'PHP Tutorial', 'description' => 'Learn PHP programming'),
            array('id' => 2, 'title' => 'JavaScript Guide', 'description' => 'Master JavaScript for web development'),
            array('id' => 3, 'title' => 'HTML Basics', 'description' => 'Introduction to HTML and web pages'),
            array('id' => 4, 'title' => 'CSS Styling', 'description' => 'Learn how to style your web pages')
        );

        // Check if search term is provided
        if (isset($_GET['search'])) {
            $search_term = $_GET['search'];
            
            if (empty($search_term)) {
                echo "<p style='color: red;'>Please enter a search term</p>";
            } else {
                // Perform the search
                echo "<h3>Search Results for: '" . $search_term . "'</h3>";
                echo "<div class='results'>";
                
                $found = false;
                foreach ($mock_database as $item) {
                    if (strpos(strtolower($item['title']), strtolower($search_term)) !== false ||
                        strpos(strtolower($item['description']), strtolower($search_term)) !== false) {
                        $found = true;
                        echo "<div style='padding: 10px; border-bottom: 1px solid #ddd; margin-top: 5px;'>";
                            echo "<h4>" . $item['title'] . "</h4>";
                            echo "<p>" . $item['description'] . "</p>";
                        echo "</div>";
                    }
                }
                
                if (!$found) {
                    echo "<p style='color: red;'>No results found for '" . $search_term . "'</p>";
                }
                echo "</div>";
            }
        }
        ?>
    </div>
</body>
</html>


// Replace mock database with actual database query
$connection = mysqli_connect("localhost", "username", "password", "database");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM your_table WHERE title LIKE '%" . mysqli_real_escape_string($connection, $search_term) . "%' OR description LIKE '%" . mysqli_real_escape_string($connection, $search_term) . "%'";
$result = mysqli_query($connection, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Display results
    }
} else {
    echo "No results found";
}

mysqli_close($connection);


// Database connection
$mysqli = new mysqli("localhost", "username", "password", "database");

// Check for errors
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Prepare the query
$stmt = $mysqli->prepare("SELECT item_name FROM items WHERE item_name LIKE ?");
$searchTerm = "%$searchTerm%";
$stmt->bind_param("s", $searchTerm);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Display results
while ($row = $result->fetch_assoc()) {
    echo "<p>" . htmlspecialchars($row['item_name']) . "</p>";
}

// Close the connection
$mysqli->close();


<?php
// Database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Connect to database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get search query from POST
    if (isset($_POST['query'])) {
        $searchQuery = $_POST['query'];
        
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE name LIKE ?");
        $searchTerm = '%' . $searchQuery . '%';
        $stmt->bindParam(1, $searchTerm);

        // Execute query
        $stmt->execute();

        // Fetch results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Display results in a table
        if (!empty($results)) {
            echo "<div class='results'>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th><th>Email</th></tr>";

            foreach ($results as $row) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
            echo "</div>";
        } else {
            echo "<p>No results found.</p>";
        }
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Close database connection
$conn = null;
?>


<?php
// search.php

// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'mydatabase';

// Connect to database
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search query from form input
$search_query = $_GET['query'];

// Sanitize the input to prevent SQL injection
$sanitized_query = mysqli_real_escape_string($conn, $search_query);

// SQL query to search for the term
$sql = "SELECT * FROM mytable WHERE title LIKE '%$sanitized_query%' OR description LIKE '%$sanitized_query%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output results in a table
    echo "<table border='1'>";
    echo "<tr><th>Title</th><th>Description</th></tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>" . $row['description'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    // No results found
    echo "No results found.";
}

// Close database connection
$conn->close();
?>


// Prepare the statement
$stmt = $conn->prepare("SELECT * FROM mytable WHERE title LIKE ? OR description LIKE ?");
$like = '%' . $sanitized_query . '%';
$stmt->bind_param('ss', $like, $like);

// Execute the statement
$stmt->execute();

// Get the result set
$result = $stmt->get_result();


<?php
// Connect to database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Get search query
    $query = $_POST['query'];
    
    // Sanitize input
    $query = mysqli_real_escape_string($conn, $query);
    
    // Search in the database
    $sql = "SELECT * FROM table_name WHERE title LIKE '%$query%' OR description LIKE '%$query%'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                </tr>";
        
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "No results found.";
    }
    
    $conn->close();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" name="search" class="search-box" placeholder="Search here...">
            <button type="submit" class="search-button">Search</button>
        </form>
        
        <?php
        // Connect to the database
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'my_database';

        $conn = mysqli_connect($host, $username, $password, $database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check if search parameter is set
        if (isset($_GET['search'])) {
            $search_term = $_GET['search'];
            
            // Escape special characters to prevent SQL injection
            $search_term = mysqli_real_escape_string($conn, $search_term);
            
            // Query the database
            $sql = "SELECT * FROM my_table WHERE title LIKE '%{$search_term}%'";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    echo "<div class='results'>";
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Title</th></tr>";
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>" . $row['id'] . "</td><td>" . $row['title'] . "</td></tr>";
                    }
                    
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "<div class='results'>";
                    echo "No results found.";
                    echo "</div>";
                }
            } else {
                die("Error in query: " . mysqli_error($conn));
            }

            // Close the connection
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add your CSS styles here -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php
    // Check if form was submitted with GET method
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
        
        if (!empty($searchQuery)) {
            // Perform your search logic here
            echo "<h2>Search Results for: " . htmlspecialchars($searchQuery) . "</h2>";
            
            // Add your database query or search logic here
            
            // Example placeholder results:
            echo "<p>Your search results would appear here.</p>";
        } else {
            // Display search bar when no search query is entered
    ?>
    
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <input type="text" name="search" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
    
    <?php
        }
    } else {
        // If form was submitted with POST method, redirect to GET
        header("Location: " . $_SERVER['PHP_SELF'] . "?search=" . urlencode($_POST['search']));
        exit();
    }
    ?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if search parameter is set
        if (isset($_GET['search'])) {
            $searchQuery = $_GET['search'];
            
            // Sanitize the input to prevent SQL injection or XSS attacks
            $searchQuery = htmlspecialchars($searchQuery, ENT_QUOTES);

            echo "<div class='results'>";
            echo "<h3>Search Results:</h3>";
            echo "<p>You searched for: <strong>$searchQuery</strong></p>";
            
            // Here you would typically connect to a database and perform
            // your search query. For now, we'll just display the search term.
            echo "</div>";
        } else {
            echo "<div class='results'>";
            echo "<h3>Search Bar</h3>";
            echo "<p>Please enter your search term above.</p>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
</head>
<body>
    <?php
    // Search bar HTML form
    echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post">';
    echo '<input type="text" name="search_query" placeholder="Enter search query...">';
    echo '<input type="submit" value="Search">';
    echo '</form>';

    // Process the search query
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchQuery = $_POST['search_query'];

        // Sanitize input to prevent SQL injection or other attacks
        $searchQuery = htmlspecialchars($searchQuery);

        // Database connection
        $servername = "localhost";
        $username = "your_username";
        $password = "your_password";
        $dbname = "your_database";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // SQL query to search for the term
        $sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $searchQuery . "%' LIMIT 10";
        
        // Error checking for SQL query
        if (!$result = mysqli_query($conn, $sql)) {
            die("Error in query: " . mysqli_error($conn));
        }

        // Display the results
        echo "<h3>Search Results:</h3>";
        while ($row = mysqli_fetch_assoc($result)) {
            // Assuming you have a column named 'field_name' in your table
            echo "<p>" . $row['field_name'] . "</p>";
        }

        // Close connection
        mysqli_close($conn);
    }
    ?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-form input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .search-form button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<?php
// Database connection
$host = 'your_host';
$username = 'your_username';
$password = 'your_password';
$db_name = 'your_database';

// Create connection
$conn = mysqli_connect($host, $username, $password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the input to prevent SQL injection and XSS attacks
$safe_search = htmlspecialchars($search_term);
$safe_search = mysqli_real_escape_string($conn, $safe_search);

?>

<div class="search-container">
    <form class="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
        <input type="text" name="search" placeholder="Search here..." value="<?php echo $search_term; ?>">
        <button type="submit">Search</button>
    </form>

    <?php
    if ($safe_search) {
        // Perform search query
        $sql = "SELECT * FROM your_table_name WHERE column_name LIKE '%{$safe_search}%'";
        
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<div class='results'>";
            echo "<h3>Results:</h3>";
            while ($row = mysqli_fetch_assoc($result)) {
                // Display your results here based on your table structure
                echo "<p>{$row['column_name']}</p>"; 
            }
            echo "</div>";
        } else {
            echo "<div class='results'>";
            echo "No results found for: {$search_term}";
            echo "</div>";
        }
    }
    ?>

</div>

<?php
// Close database connection
mysqli_close($conn);
?>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add some basic styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .search-container {
            width: 500px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .search-bar input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-bar input[type="text"]:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="search-bar">
                <input type="text" name="query" placeholder="Search here...">
                <input type="submit" name="search" value="Search">
            </div>
        </form>

        <?php
        // Check if form is submitted
        if (isset($_POST['search'])) {
            $query = $_POST['query'];
            
            // Display the search query
            echo "<div class='results'>";
            echo "<h3>Results for: '" . htmlspecialchars($query) . "'</h3>";
            echo "</div>";

            // Here you would typically connect to a database and run your search query
            // For this example, we'll just display the search term back to the user

        } else {
            // If no search has been performed yet
            echo "<div class='results'>";
            echo "<h3>Enter a search term above</h3>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="search" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search = $_POST['search'];

            // Connect to the database
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'my_database';

            $mysqli = new mysqli($host, $username, $password, $database);

            // Check connection
            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }

            // Escape and sanitize the input
            $search = $mysqli->real_escape_string(trim($search));

            // Prepare the SQL query
            $query = "SELECT * FROM users WHERE first_name LIKE ?";
            
            // Use prepared statement to prevent SQL injection
            if ($stmt = $mysqli->prepare($query)) {
                // Bind parameters
                $stmt->bind_param("s", $search);
                
                // Execute the query
                $stmt->execute();
                
                // Get result set
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<div class='results'>";
                    echo "<h3>Search Results:</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th></tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['first_name'] . "</td>";
                        echo "<td>" . $row['last_name'] . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "<div class='results'>";
                    echo "No results found.";
                    echo "</div>";
                }
            } else {
                echo "Error: " . $mysqli->error;
            }

            // Close the database connection
            $mysqli->close();
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-form {
            display: flex;
            gap: 10px;
        }
        input[type="text"] {
            flex: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search = $_POST['search'];
        
        // Sanitize the input
        $search = mysqli_real_escape_string($conn, $search);
        
        // Database connection
        $conn = new mysqli("localhost", "username", "password", "database_name");
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Search query
        $sql = "SELECT * FROM table_name WHERE column_name LIKE '%" . $search . "%'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='result'>";
                // Display the results
                foreach ($row as $key => $value) {
                    echo "$key: " . nl2br($value) . "<br>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No results found.</p>";
        }
        
        $conn->close();
    }
    ?>
    
    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="search-form">
            <input type="text" name="search" placeholder="Search..." required>
            <button type="submit">Search</button>
        </form>
    </div>

    <?php
    // Optional: Add some styling for the results
    if (isset($_POST['search'])) {
    ?>
    <style>
        .result {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
    <?php
    }
    ?>
</body>
</html>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

// Get search term and category from form submission
$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : '';
$category = isset($_POST['category']) ? $_POST['category'] : '';

// Sanitize input to prevent SQL injection
$search_term = htmlspecialchars(trim($search_term));

function getSearchResults($search_term, $category) {
    global $host, $username, $password, $dbname;
    
    // Connect to database
    $conn = new mysqli($host, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare SQL query with LIKE condition for search
    $sql = "SELECT * FROM your_table WHERE";
    
    $conditions = [];
    $params = [];
    
    if (!empty($search_term)) {
        $conditions[] = "(title LIKE ? OR description LIKE ?)";
        $params[] = "%$search_term%";
        $params[] = "%$search_term%";
    }
    
    if ($category !== "") {
        $conditions[] = "category = ?";
        $params[] = $category;
    }
    
    // Combine conditions
    if (!empty($conditions)) {
        $sql .= " " . implode(" AND ", $conditions);
    } else {
        // If no search criteria, return all results
        $sql .= " 1";
    }
    
    // Prepare and execute query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    // Close database connection
    $conn->close();
    
    return $result;
}

// Get search results
$results = getSearchResults($search_term, $category);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        .results-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-result {
            border-bottom: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }

        .result-title {
            font-weight: bold;
            color: #333;
        }

        .result-description {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="results-container">
        <?php if ($results->num_rows > 0): ?>
            <?php while ($row = $results->fetch_assoc()): ?>
                <div class="search-result">
                    <div class="result-title"><?php echo htmlspecialchars($row['title']); ?></div>
                    <div class="result-description"><?php echo htmlspecialchars($row['description']); ?></div>
                    <div>Category: <?php echo htmlspecialchars($row['category']); ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        
        .search-bar input {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="search-container">
    <div class="search-bar">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search_query" placeholder="Search here...">
            <button type="submit">Search</button>
        </form>
    </div>

    <?php
    // Connect to database (replace with your actual database connection)
    $db = new SQLite3('search.db');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize the input
        $search_query = htmlspecialchars(trim($_POST['search_query']));

        // Create table if not exists
        $db->exec("CREATE TABLE IF NOT EXISTS data (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT NOT NULL
        )");

        // Insert sample data (for testing purposes)
        $sampleData = [
            ["Title 1", "Description for Title 1"],
            ["Title 2", "Description for Title 2"],
            ["Title 3", "Description for Title 3"]
        ];

        foreach ($sampleData as $data) {
            $db->exec("INSERT INTO data (title, description) VALUES ('{$data[0]}', '{$data[1]}')");
        }

        // Search query
        $stmt = $db->prepare("SELECT * FROM data WHERE title LIKE '%' || :query || '%' OR description LIKE '%' || :query || '%'");
        $stmt->bindValue(':query', $search_query);
        $result = $stmt->execute();

        if ($result) {
            echo "<div class='search-results'>";
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                echo "<div class='result-item'>";
                echo "<h4>" . $row['title'] . "</h4>";
                echo "<p>" . $row['description'] . "</p>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "No results found!";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-container input {
            width: 75%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-container button {
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-container button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search term from POST data
    $search_term = htmlspecialchars($_POST['search']);

    // Simulate a database connection (replace with actual database code)
    $mock_database = array(
        'apple',
        'banana',
        'orange',
        'grape',
        'melon'
    );

    // Function to search for matching items
    function searchItems($search_term, $database) {
        $results = array();
        foreach ($database as $item) {
            if (stripos($item, $search_term) !== false) {
                array_push($results, $item);
            }
        }
        return $results;
    }

    // Get search results
    $results = searchItems($search_term, $mock_database);

    // Display the results
    echo "<div class='results'>";
    if (!empty($results)) {
        echo "<h3>Search Results:</h3>";
        foreach ($results as $result) {
            echo "<li>" . $result . "</li>";
        }
    } else {
        echo "<p>No results found.</p>";
    }
    echo "</div>";
}
?>

<div class="search-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="search" placeholder="Search for...">
        <button type="submit">Search</button>
    </form>
</div>

</body>
</html>


<?php
// Database configuration
$host = "localhost";
$user = "username";
$password = "password";
$database = "my_database";

// Connect to database
$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search query from form input
$query = $_POST['query'];

// Sanitize the input
$sanitized_query = mysqli_real_escape_string($conn, $query);

// SQL query to search database
$sql = "SELECT * FROM users WHERE name LIKE '%$sanitized_query%' 
        OR email LIKE '%$sanitized_query%'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Display results
echo "<h2>Search Results:</h2>";
echo "<ul>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<li>Name: " . htmlspecialchars($row['name']) . "<br />";
    echo "Email: " . htmlspecialchars($row['email']) . "</li><hr />";
}

echo "</ul>";

// Close database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>
    <!-- Search Form -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="query" placeholder="Search...">
        <button type="submit">Search</button>
    </form>

    <?php
    // Connect to database
    $db_host = "localhost";
    $db_user = "username";
    $db_pass = "password";
    $db_name = "database_name";

    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if form is submitted
    if (isset($_POST['query'])) {
        $query = $_POST['query'];
        
        // Escape special characters to prevent SQL injection
        $search = mysqli_real_escape_string($conn, $query);

        // Search query
        $sql = "SELECT * FROM your_table_name WHERE title LIKE '%{$search}%' OR description LIKE '%{$search}%'";
        
        // Execute the query
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        // Display results
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div>";
            echo "<h3>" . $row['title'] . "</h3>";
            echo "<p>" . $row['description'] . "</p>";
            echo "</div>";
        }
    }

    // Close database connection
    mysqli_close($conn);
    ?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            width: 75%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-btn {
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-btn:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 4px;
            display: none;
        }
    </style>
</head>
<body>

<?php
// Check if search term is provided
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    // Here you would typically connect to a database and fetch results based on the search term
    // For this example, we'll just display the search term
    echo "<div class='results' style='display: block;'>";
    echo "Search results for: " . htmlspecialchars($searchTerm);
    echo "</div>";
} else {
    // Display default content
    echo "<div class='results'>";
    echo "Search results will appear here.";
    echo "</div>";
}
?>

<div class="search-container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
        <input type="text" name="search" class="search-bar" placeholder="Enter your search term...">
        <button type="submit" class="search-btn">Search</button>
    </form>
</div>

<script>
// Add clear button functionality
document.querySelector('.search-container').addEventListener('click', function(e) {
    if (e.target.type === 'reset') {
        document.querySelector('input[type="text"]').value = '';
    }
});
</script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-container {
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 25%;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="search-container">
            <input type="text" placeholder="Search for something..." name="search">
            <button type="submit" name="submit">Search</button>
        </div>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get search term from form
        $search = trim($_POST['search']);
        
        // Connect to database
        $conn = mysqli_connect("localhost", "username", "password", "database_name");
        
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Sanitize input and prepare SQL query
        $search = htmlspecialchars($search);
        $search = mysql_escape_string($search);
        
        // Search in the database table
        $sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $search . "%'";
        $result = mysqli_query($conn, $sql);
        
        if ($result) {
            echo "<h3>Search Results:</h3>";
            
            while ($row = mysqli_fetch_assoc($result)) {
                // Display the results
                echo "<p>" . $row['column_name'] . "</p>";
            }
            
            // Free result set
            mysqli_free_result($result);
        } else {
            echo "No results found.";
        }
        
        // Close database connection
        mysqli_close($conn);
    }
    ?>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-input {
            flex-grow: 1;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="search-bar">
                <input type="text" name="search_query" class="search-input" placeholder="Search here...">
                <button type="submit" class="search-button">Search</button>
            </div>
        </form>

        <?php
        // Display search results if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_query'])) {
            $searchQuery = mysqli_real_escape_string($conn, trim($_POST['search_query']));
            
            // Connect to database
            $conn = mysqli_connect('localhost', 'username', 'password', 'database_name');
            
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // Search query
            $sql = "SELECT * FROM your_table WHERE title LIKE '%$searchQuery%'";
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                echo "<div class='results'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<p>" . $row['title'] . "</p>";
                }
                echo "</div>";
            } else {
                echo "<div class='results' style='color: red;'>No results found.</div>";
            }
            
            // Close database connection
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<?php
// Retrieve the search term from the POST request
$searchTerm = isset($_POST['query']) ? $_POST['query'] : '';

// Sanitize the input to prevent SQL injection
$searchTerm = htmlspecialchars($searchTerm, ENT_QUOTES);

// Database connection details
$host = 'localhost';
$username = 'root'; // Replace with your database username
$password = '';     // Replace with your database password
$dbname = 'testdb';  // Replace with your database name

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to search for products matching the search term
$sql = "SELECT * FROM products WHERE name LIKE '%$searchTerm%'";
$result = $conn->query($sql);

// Display the results
echo "<h2>Search Results:</h2>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p>" . $row['name'] . "</p>";
    }
} else {
    echo "No products found matching your search.";
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
        }

        .search-box {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="search_query" class="search-box" placeholder="Search here...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchQuery = isset($_POST['search_query']) ? $_POST['search_query'] : '';
            
            // Sanitize the input to prevent SQL injection or XSS attacks
            $searchQuery = htmlspecialchars(trim($searchQuery));

            if (!empty($searchQuery)) {
                echo "<h3>Search Results for: '" . $searchQuery . "'</h3>";
                // Here you would typically connect to your database and perform a search query
                // For now, we're just displaying the search term
                echo "<p>Your search results would go here...</p>";
            } else {
                echo "<p>Please enter a search query.</p>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Add some basic CSS styling -->
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            flex-grow: 1;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .search-button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search Form -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="search-form">
            <input type="text" name="search_term" id="search_term" class="search-input" placeholder="Enter your search term...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchTerm = $_POST['search_term'];
            
            // Sanitize the input to prevent SQL injection
            $searchTerm = htmlspecialchars($searchTerm);
            
            // Connect to database
            try {
                $conn = new mysqli("localhost", "username", "password", "database_name");
                
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                
                // Prepare SQL query with LIKE clause for search functionality
                $sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $searchTerm . "%'";
                
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    echo "<div class='results'>";
                    while($row = $result->fetch_assoc()) {
                        // Display results in a readable format
                        echo "<div style='padding:10px;border-bottom:1px solid #ddd;'>";
                            // Replace 'column_name' with your actual column name
                            echo "<p><strong>Result:</strong> " . $row['column_name'] . "</p>";
                        echo "</div>";
                    }
                    echo "</div>";
                } else {
                    echo "<p style='color:red;'>No results found!</p>";
                }
                
                // Close database connection
                $conn->close();
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
</head>
<body>
    <?php
    // Simple search functionality without a database
    if (isset($_POST['submit'])) {
        $search_query = htmlspecialchars($_POST['search_query']);

        // For demonstration purposes, let's use some sample data
        $sample_data = array(
            "Apple",
            "Banana",
            "Cherry",
            "Orange",
            "Mango"
        );

        // Search the sample data
        $results = array();
        foreach ($sample_data as $item) {
            if (stripos($item, $search_query) !== false) {
                $results[] = $item;
            }
        }

        if (!empty($results)) {
            echo "<h3>Search Results:</h3>";
            foreach ($results as $result) {
                echo "$result<br>";
            }
        } else {
            echo "No results found for: $search_query";
        }
    }
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="search_query" placeholder="Search...">
        <input type="submit" name="submit" value="Search">
    </form>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
</head>
<body>
    <?php
    // Database connection details
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "my_database";

    // Create connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST['submit'])) {
        $search_query = $_POST['search_query'];

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM your_table WHERE column_name LIKE ?");
        $search_query = "%$search_query%";
        $stmt->bind_param("s", $search_query);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "Result: " . $row['column_name'] . "<br>";
            }
        } else {
            echo "No results found for: $search_query";
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $conn->close();
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="search_query" placeholder="Search...">
        <input type="submit" name="submit" value="Search">
    </form>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {background-color: #f5f5f5;}
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Connect to database
        $conn = mysqli_connect("localhost", "username", "password", "database_name");
        
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check if form is submitted
        if (isset($_POST['search'])) {
            $search_query = mysqli_real_escape_string($conn, $_POST['search_query']);
            
            // Search in the database
            $sql = "SELECT * FROM products WHERE name LIKE '%{$search_query}%' OR description LIKE '%{$search_query}%'";
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                echo "<table><tr><th>Product ID</th><th>Name</th><th>Description</th><th>Price</th></tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['price']}</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "No results found.";
            }
        } else {
            // If form not submitted, show empty search bar
            echo "<h2>Search Products</h2>";
        }

        // Close database connection
        mysqli_close($conn);
        ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="search_query" class="search-bar" placeholder="Enter search term...">
            <button type="submit" name="search" class="search-button">Search</button>
        </form>

        <!-- Display results here -->
        <div class="results">
            
        </div>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        
        .search-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Search here..." value="<?php if(isset($_GET['query'])) { echo htmlspecialchars($_GET['query']); } ?>">
            <button type="submit">Search</button>
        </form>

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "your_database_name";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get search term
        if (isset($_GET['query'])) {
            $search_term = $_GET['query'];
            
            // Sanitize input
            $search_term = htmlspecialchars($search_term);

            // Escape special characters
            $search_term = mysqli_real_escape_string($conn, $search_term);

            // Search query
            $sql = "SELECT * FROM your_table_name WHERE 
                    column1 LIKE '%$search_term%' OR
                    column2 LIKE '%$search_term%'";
                    
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                echo "<div class='results'>";
                while($row = $result->fetch_assoc()) {
                    // Display your results here, modify this part according to your table structure
                    echo "<p>".$row['column1']."</p>";
                    echo "<p>".$row['column2']."</p>";
                    echo "<br>";
                }
                echo "</div>";
            } else {
                echo "<p>No results found.</p>";
            }
        }

        $conn->close();
        ?>

        <?php if (!isset($_GET['query'])) { ?>
            <p>Enter a search term above to find results.</p>
        <?php } ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-form input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 16px;
        }
        .search-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .search-form button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $query = $_POST['search'];
            perform_search($query);
        } else {
            // If not submitted, show empty search bar
            display_search_form();
        }
        ?>

    </div>

<?php
function display_search_form() {
    echo '<form class="search-form" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post">
                <input type="text" name="search" placeholder="Search...">
                <button type="submit">Search</button>
            </form>';
}

function perform_search($query) {
    if ($query == "") {
        echo "<div class='results'><p>Please enter a search query.</p></div>";
        return;
    }

    // Here you would typically connect to your database and perform the actual search
    // For this example, we'll just display sample results

    $sample_results = array(
        array('name' => 'Product 1', 'price' => '$9.99'),
        array('name' => 'Product 2', 'price' => '$14.99'),
        array('name' => 'Product 3', 'price' => '$19.99')
    );

    if (count($sample_results) > 0) {
        echo "<div class='results'><h3>Search Results:</h3><table>";
        foreach ($sample_results as $result) {
            echo "<tr><td>" . htmlspecialchars($result['name']) . "</td><td>" . htmlspecialchars($result['price']) . "</td></tr>";
        }
        echo "</table></div>";
    } else {
        echo "<div class='results'><p>No results found for your search.</p></div>";
    }
}
?>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
        }
        
        .search-box {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            width: 15%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<?php
// Sample data - you can replace this with your database query results
$sample_data = array(
    "Apple",
    "Banana",
    "Cherry",
    "Date",
    "Grape",
    "Kiwi",
    "Mango",
    "Orange",
    "Peach",
    "Pear"
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = trim($_POST['search']);
    
    // Search for matching results
    if (!empty($sample_data) && !empty($search_query)) {
        foreach ($sample_data as $item) {
            if (stripos($item, $search_query) !== false) {
                echo "<div class='results'>$item</div>";
            }
        }
    }
}
?>

<div class="search-container">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="search" class="search-box" placeholder="Search here...">
        <button type="submit" class="search-button">Search</button>
    </form>
</div>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .search-bar input {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search here...">
                <button type="submit">Search</button>
            </div>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchQuery = htmlspecialchars($_POST['search']);
            
            // Here you would typically connect to a database and fetch results based on the search query
            
            echo "<div class='results'>";
            echo "<p>Search Results for: <strong>" . $searchQuery . "</strong></p>";
            // Add your actual search results here
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<?php
// This file handles the search query and displays results

// Get the search query from GET request
$search_query = isset($_GET['query']) ? $_GET['query'] : '';

// Sanitize the input to prevent SQL injection or other attacks
$search_query = trim($search_query);
$search_query = htmlspecialchars($search_query);

// Here you would typically connect to your database and run a query
// For now, we'll just display some sample results

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        .results-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }

        .result-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="results-container">
        <?php if ($search_query !== '') : ?>
            <h2>Search Results for "<?php echo $search_query; ?>":</h2>
            
            <!-- Here you would typically loop through your database results -->
            <!-- For this example, we'll show some sample data -->
            <div class="result-item">
                <h3>Product 1 - <?php echo $search_query; ?></h3>
                <p>Description related to the search query.</p>
                <p>$99.99</p>
            </div>

            <div class="result-item">
                <h3>Product 2 - <?php echo $search_query; ?></h3>
                <p>Description related to the search query.</p>
                <p>$149.99</p>
            </div>
            
        <?php else : ?>
            <h2>Please enter a search term above.</h2>
        <?php endif; ?>

        <!-- If no results are found -->
        <?php if ($search_query !== '' && false) : ?> <!-- Replace 'false' with your actual condition -->
            <p>Sorry, we didn't find any results for "<?php echo $search_query; ?>".</p>
        <?php endif; ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
            background-color: #f0f0f0;
        }
        
        .search-container {
            max-width: 600px;
            margin: 0 auto;
        }
        
        .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .search-box input[type="text"] {
            flex: 1;
            padding: 10px;
            border-radius: 25px;
            border: 1px solid #ddd;
            outline: none;
        }
        
        .search-box button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
        }
        
        .search-box button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
        }
        
        .result-item {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="search-box">
            <input type="text" name="query" placeholder="Search here...">
            <button type="submit">Search</button>
        </form>

        <?php
        // Check if search query is set
        if (isset($_GET['query']) && !empty($_GET['query'])) {
            $search_query = htmlspecialchars($_GET['query']);
            
            // Connect to database and fetch results (this part depends on your database setup)
            // For this example, we'll use a sample array of items
            $items = [
                "Apple",
                "Banana",
                "Cherry",
                "Date",
                "Elderberry",
                "Fig",
                "Grape",
                "Kiwi",
                "Lemon",
                "Mango"
            ];
            
            // Display results
            echo "<div class='results'>";
            foreach ($items as $item) {
                if (strtolower($item) == strtolower($search_query)) {
                    echo "<div class='result-item'>$item</div>";
                }
            }
            echo "</div>";
            
            // If no results found
            if (!isset($found_results)) {
                echo "<div class='results'><p>No results found.</p></div>";
            }
        }
        ?>
    </div>

    <script>
        // Add search functionality on Enter key press
        document.querySelector('input[type="text"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
    </script>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-box {
            width: 300px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        .search-box input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-box input[type="submit"] {
            width: 20%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-box input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-box">
        <form action="search.php" method="GET">
            <input type="text" name="query" placeholder="Search..." required>
            <input type="submit" value="Search">
        </form>
    </div>
</body>
</html>


<?php
// Connect to database
$host = 'localhost';
$username = 'root'; // replace with your database username
$password = '';      // replace with your database password
$dbname = 'test_db'; // replace with your database name

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term
$search_term = $_GET['query'];

// Sanitize the input to prevent SQL injection and XSS attacks
$search_term = htmlspecialchars($search_term);
$search_term = mysqli_real_escape_string($conn, $search_term);

// Create query
$sql = "SELECT * FROM your_table_name 
        WHERE column1 LIKE '%{$search_term}%' 
        OR column2 LIKE '%{$search_term}%'
        OR column3 LIKE '%{$search_term}%'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<h2>No results found</h2>";
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        // Display your results here
        echo "<div>";
            echo "<h3>{$row['title']}</h3>";   // replace with your column names
            echo "<p>{$row['description']}</p>"; // replace with your column names
        echo "</div>";
    }
}

// Close database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-container input[type="text"] {
            width: 75%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .search-container input[type="submit"] {
            width: 23%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-container input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search_query" placeholder="Search...">
            <input type="submit" name="search_submit" value="Search">
        </form>

        <?php
        // Check if form is submitted
        if (isset($_POST['search_submit'])) {
            $searchQuery = $_POST['search_query'];

            // sanitize the input
            $searchQuery = htmlspecialchars($searchQuery);

            echo "<h3>Search Results for: '" . $searchQuery . "'</h3>";
            
            // Here you would typically connect to your database and query the results
            // For this example, we'll just display the search query
            
            // Example of connecting to MySQL database:
            // Replace with your actual database credentials
            // $conn = mysqli_connect('localhost', 'username', 'password', 'database_name');
            
            // Check connection
            // if (!$conn) {
                // die("Connection failed: " . mysqli_connect_error());
            // }

            // Search query in database
            // $sql = "SELECT * FROM your_table WHERE column LIKE '%" . $searchQuery . "%'";
            // $result = mysqli_query($conn, $sql);
            
            // Display results
            // while ($row = mysqli_fetch_assoc($result)) {
                // echo "<div>" . $row['column_name'] . "</div>";
            // }

            // Close connection
            // mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="text" name="search" placeholder="Enter your search term...">
    <input type="submit" name="submit" value="Search">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $search = $_POST['search'];
        // Sanitize input
        $search = htmlspecialchars($search);
        
        // Database connection
        $host = "localhost";
        $db_username = "root";
        $db_password = "";
        $database_name = "my_database";
        
        // Connect to database
        $conn = mysqli_connect($host, $db_username, $db_password, $database_name);
        
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // SQL query
        $sql = "SELECT * FROM my_table WHERE column LIKE '%".$search."%'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<h3>Search Results:</h3>";
            while($row = mysqli_fetch_assoc($result)) {
                // Display the results
                echo "<p>" . $row['column'] . "</p>";
            }
        } else {
            echo "No results found.";
        }
        
        // Close database connection
        mysqli_close($conn);
    }
}
?>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-container {
            margin-bottom: 20px;
        }
        #searchInput {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }
        #results {
            list-style-type: none;
            padding: 0;
        }
        #results li {
            margin-bottom: 5px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" id="searchInput" name="query" placeholder="Search...">
            <button type="submit">Search</button>
        </form>
    </div>

    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchQuery = $_POST['query'];
        
        // Here you would typically connect to a database and query your data
        // For this example, we'll use a simple array
        $items = array("Apple", "Banana", "Orange", "Grapes", "Mango");
        $results = array();
        
        foreach ($items as $item) {
            if (stripos($item, $searchQuery) !== false) {
                array_push($results, $item);
            }
        }
    ?>
    
    <h3>Results</h3>
    <ul id="results">
        <?php
        if (!empty($results)) {
            foreach ($results as $result) {
                echo "<li>" . $result . "</li>";
            }
        } else {
            echo "<li>No results found</li>";
        }
        ?>
    </ul>
    
    <?php
    }
    ?>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-bar {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="query" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get the search query from form
            $searchQuery = trim($_POST['query']);
            
            // Database connection
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'my_database';
            
            $conn = new mysqli($host, $username, $password, $database);
            
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            // Sanitize the input to prevent SQL injection
            $searchQuery = $conn->real_escape_string($searchQuery);
            
            // Create the SQL query
            $sql = "SELECT * FROM users WHERE name LIKE '%" . $searchQuery . "%'";
            
            // Execute the query
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                echo "<h3>Search Results:</h3>";
                echo "<ul>";
                
                while ($row = $result->fetch_assoc()) {
                    echo "<li>" . $row['name'] . "</li>";
                }
                
                echo "</ul>";
            } else {
                echo "No results found.";
            }
            
            // Close the database connection
            $conn->close();
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        
        .search-box input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        
        .search-box input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-box input[type="submit"]:hover {
            background-color: #45a049;
        }
        
        #result {
            margin-top: 20px;
            padding: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
<?php
// Check if the form has been submitted
if (isset($_GET['query'])) {
    $searchTerm = $_GET['query'];
    
    // Sanitize the input
    $searchTerm = htmlspecialchars($searchTerm);
    
    // Here you would typically query your database with $searchTerm
    // For this example, we'll just display the search term
    echo "<div id='result'>Search Results for: " . $searchTerm . "</div>";
} else {
    echo "<div id='result' style='color: red;'>Please enter a search term.</div>";
}
?>

<div class="search-container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="search-box">
        <input type="text" name="query" placeholder="Enter your search..." required>
        <input type="submit" value="Search">
    </form>
</div>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Check if the form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize and validate input
            $searchQuery = htmlspecialchars(trim($_POST['search']));

            if (!empty($searchQuery) && strlen($searchQuery) <= 100) {
                // Here you would typically connect to your database and query the results
                // For now, we'll just display the search query
                echo "<h3>Your Search Query:</h3>";
                echo "<p>" . $searchQuery . "</p>";
            } else {
                // Display error message if input is invalid
                echo "<span style='color: red;'>Please enter a valid search query (1-100 characters)</span>";
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="search" class="search-bar" placeholder="Search here...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


// Database configuration
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind the statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM your_table WHERE column_name LIKE ?");
$searchQuery = '%' . $searchQuery . '%';
$stmt->bind_param('s', $searchQuery);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Output the results
    while($row = $result->fetch_assoc()) {
        echo "<div class='search-result'>";
        echo "<h3>" . $row['title'] . "</h3>";
        echo "<p>" . $row['description'] . "</p>";
        echo "</div>";
    }
} else {
    echo "No results found.";
}

// Close the statement and connection
$stmt->close();
$conn->close();


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
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
        }

        button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php
    // Check if form is submitted
    if (isset($_POST['search'])) {
        $query = $_POST['query'];
        
        // Here you would typically connect to a database and fetch results
        // For this example, we'll just display the search query
        echo "<div class='results'>";
        echo "<h3>Search Results for: '$query'</h3>";
        echo "<p>Your search results would appear here</p>";
        echo "</div>";
    }
    ?>

    <div class="search-container">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="search-bar">
                <input type="text" name="query" id="searchInput" placeholder="Search here...">
                <button type="submit" name="search">Search</button>
            </div>
        </form>
    </div>
</body>
</html>


<?php
// Simple Search Bar Example

// Check if search query is submitted
if (isset($_GET['search'])) {
    $query = htmlspecialchars($_GET['search']);
    echo "<h2>Search Results for: " . $query . "</h2>";
    
    // Add your search logic here
    // For example, you can connect to a database and fetch results
    
} else {
    // Display the search form
    ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="text" name="search" placeholder="Enter your search..." required>
        <button type="submit">Search</button>
    </form>

<?php } ?>

<style>
.search-container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    text-align: center;
}

input[type="text"] {
    width: 80%;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}

h2 {
    color: #333;
    margin-bottom: 20px;
}
</style>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
</head>
<body>
    <?php
    // Check if form has been submitted
    if (isset($_POST['submit'])) {
        $search = mysqli_real_escape_string($conn, $_POST['search']);
        
        // Database connection
        $conn = mysqli_connect("localhost", "username", "password", "mydatabase");
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Search query
        $sql = "SELECT * FROM users WHERE name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>" . $row['name'] . "</p>";
                echo "<p>" . $row['email'] . "</p>";
                // Display other user details as needed
            }
        } else {
            echo "No results found.";
        }
        
        mysqli_close($conn);
    }
    ?>
    
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" name="search" placeholder="Search...">
        <button type="submit" name="submit">Search</button>
    </form>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <!-- Include Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    // Sample dataset (you would typically get this from a database)
    $products = [
        ['id' => 1, 'name' => 'Laptop', 'description' => 'High-performance laptop with 8GB RAM and 500GB HDD', 'price' => '$999'],
        ['id' => 2, 'name' => 'Smartphone', 'description' => 'Latest smartphone with 6.5-inch display', 'price' => '$799'],
        ['id' => 3, 'name' => 'Tablet', 'description' => 'Portable tablet for on-the-go computing', 'price' => '$499'],
        ['id' => 4, 'name' => 'Printer', 'description' => 'Multi-functional printer with wireless capability', 'price' => '$299']
    ];

    // Check if the form has been submitted
    $searchTerm = isset($_POST['search']) ? trim(strtolower($_POST['search'])) : '';

    // Function to search products
    function searchProducts($products, $searchTerm) {
        $results = [];
        foreach ($products as $product) {
            // Convert to lowercase for case-insensitive comparison
            $nameLower = strtolower($product['name']);
            $descriptionLower = strtolower($product['description']);
            
            if (strpos($nameLower, $searchTerm) !== false || strpos($descriptionLower, $searchTerm) !== false) {
                $results[] = $product;
            }
        }
        return $results;
    }

    // Get search results
    $results = searchProducts($products, $searchTerm);
    ?>

    <div class="container mt-5">
        <!-- Search Form -->
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo $searchTerm; ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <!-- Display Results -->
        <?php if (!empty($results)) : ?>
            <h3>Results:</h3>
            <div class="row">
                <?php foreach ($results as $product) : ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                <p class="card-text"><?php echo $product['description']; ?></p>
                                <p class="card-text"><strong>Price:</strong> <?php echo $product['price']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <?php if ($searchTerm !== '') : ?>
                <h3 class="text-danger">No results found for "<?php echo htmlspecialchars($searchTerm); ?>"</h3>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Display all products when no search term is entered -->
        <?php if ($searchTerm === '' && empty($_POST)) : ?>
            <h3>Products:</h3>
            <div class="row">
                <?php foreach ($products as $product) : ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                <p class="card-text"><?php echo $product['description']; ?></p>
                                <p class="card-text"><strong>Price:</strong> <?php echo $product['price']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }

        .search-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-box {
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="POST" action="">
            <div class="search-box">
                <input type="text" name="search_term" placeholder="Search here...">
                <button type="submit" name="search">Search</button>
            </div>
        </form>

        <?php
            // Database connection details
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "mydatabase";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if (isset($_POST['search'])) {
                $search_term = $_POST['search_term'];
                $search_term = htmlspecialchars($search_term);

                // SQL query to search for the term
                $sql = "SELECT * FROM mytable WHERE title LIKE ?";
                $stmt = $conn->prepare($sql);
                $search = "%" . $search_term . "%";
                $stmt->bind_param("s", $search);
                
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        echo "<div class='results'>";
                        while($row = $result->fetch_assoc()) {
                            echo "<div class='result-item'>" . $row['title'] . "</div>";
                        }
                        echo "</div>";
                    } else {
                        echo "<p>No results found.</p>";
                    }
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

                // Close the statement
                $stmt->close();
            }

            // Close database connection
            $conn->close();
        ?>
    </div>
</body>
</html>


<?php
// Get the search term and category from the GET request
$searchTerm = isset($_GET['query']) ? $_GET['query'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Sanitize the input to prevent SQL injection or other attacks
$searchTerm = htmlspecialchars($searchTerm);
$category = htmlspecialchars($category);

// Display a message showing what was searched for
echo "<h2>You searched for: '$searchTerm' in $category</h2>";

// Add your database query logic here
// For example, you could connect to a database and search for results matching the searchTerm

// Example of how you might display results:
$results = array(
    'Result 1',
    'Result 2',
    'Result 3'
);

echo "<div class='results'>";
foreach ($results as $result) {
    echo "<div class='result'>$result</div>";
}
echo "</div>";
?>


<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = $_POST['query'];

    // SQL query to fetch results
    $sql = "SELECT * FROM products WHERE name LIKE '%" . mysqli_real_escape_string($conn, $query) . "%' 
            OR description LIKE '%" . mysqli_real_escape_string($conn, $query) . "%'";
    
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        echo "<div class='results'>";
        echo "<h3>Search Results for: '" . $query . "'</h3>";
        echo "<ul>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>";
            echo "<strong>" . $row['name'] . "</strong>: " . $row['description'];
            echo "</li>";
        }
        
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<div class='results'>";
        echo "<p>No results found for: '" . $query . "'</p>";
        echo "</div>";
    }
}

mysqli_close($conn);
?>


<?php
// Get the search query from GET request
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Sanitize the input to prevent SQL injection or other attacks
$safe_query = htmlspecialchars($query);

// Display the search results
echo "<h2>Search Results for: $safe_query</h2>";
?>

<p>(This is where you would typically display your actual search results from a database)</p>

<?php
// Example of how to output content based on the query
if (!empty($safe_query)) {
    echo "<p>Searching for: " . $safe_query . "</p>";
} else {
    echo "<p>Please enter a search term.</p>";
}
?>


$conn = mysqli_connect("localhost", "username", "password", "database_name");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="search-form">
            <input type="text" name="search_term" placeholder="Search...">
            <button type="submit">Search</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchTerm = $_POST['search_term'];
            
            // Sample data to search through
            $keywords = array(
                'php', 
                'mysql', 
                'html', 
                'css', 
                'javascript'
            );

            // Search logic
            if (in_array(strtolower($searchTerm), $keywords)) {
                echo "<div class='results'>";
                switch ($searchTerm) {
                    case 'php':
                        echo "PHP is a server scripting language.";
                        break;
                    case 'mysql':
                        echo "MySQL is a popular database management system.";
                        break;
                    case 'html':
                        echo "HTML is the standard markup language for documents designed to be displayed in a web browser.";
                        break;
                    case 'css':
                        echo "CSS is used to style and layout web pages.";
                        break;
                    case 'javascript':
                        echo "JavaScript is a lightweight, most widely used programming language.";
                        break;
                }
                echo "</div>";
            } else {
                echo "<div class='results'>";
                echo "No results found for: " . $searchTerm . ".";
                echo "</div>";
            }
        }
        ?>
    </div>
</body>
</html>


<?php
// This is where you would typically connect to your database and perform the search

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = htmlspecialchars($_POST['query']);
    
    if (!empty(trim($query))) {
        // Display the search results or process the query here
        echo "<h2>Search Results</h2>";
        echo "You searched for: " . $query;
        
        // Add your database connection and query logic here
        
    } else {
        die("<script>alert('Please enter a search query.'); window.location.href = 'index.html';</script>");
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
        .search-container {
            width: 50%;
            margin: 50px auto;
            text-align: center;
        }

        .search-box {
            padding: 10px;
            width: 60%;
            font-size: 16px;
            border: 2px solid #333;
            border-radius: 4px;
        }

        .search-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php
    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchTerm = $_POST['search'];
        
        // Display the search results or process the search term here
        echo "<h2>Search Results for: '" . htmlspecialchars($searchTerm) . "'</h2>";
        
        // You can add your database query logic here
    }
    ?>

    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search" class="search-box" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Connect to your database
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = $_POST['search'];
    
    // Sanitize input to prevent SQL injection
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    
    // Query the database
    $sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $searchTerm . "%'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Output the search results
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div>" . $row['column_name'] . "</div>";
        }
    } else {
        echo "No results found.";
    }
}

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .search-button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="search-container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="search_query" class="search-bar" placeholder="Search...">
        <button type="submit" class="search-button">Search</button>
    </form>

    <?php
    // Database configuration
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "mydatabase";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $search_query = sanitizeInput($_POST['search_query']);

        // Connect to database
        $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // SQL query with placeholder
        $sql = "SELECT * FROM users WHERE name LIKE ?";
        $stmt = mysqli_prepare($conn, $sql);
        $search_query = '%' . $search_query . '%';
        mysqli_stmt_bind_param($stmt, "s", $search_query);

        // Execute the query
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "<div class='results'><h3>Results:</h3><ul>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li>" . $row['name'] . "</li>";
            }
            echo "</ul></div>";
        } else {
            echo "<div class='results'><p>No results found.</p></div>";
        }

        // Close connection
        mysqli_close($conn);
    }

    function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    ?>
</div>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .search-box input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .search-box button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .search-box button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="search-box">
                <input type="text" name="query" placeholder="Search here...">
                <button type="submit">Search</button>
            </div>
        </form>
    </div>

    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchQuery = $_POST['query'];

        // Sanitize the input to prevent SQL injection or XSS attacks
        $searchQuery = htmlspecialchars($searchQuery, ENT_QUOTES);

        if (!empty($searchQuery)) {
            // Here you would typically connect to a database and run a query
            // For this example, we'll just display the search results
            echo "<h3>Search Results for: " . $searchQuery . "</h3>";
            
            // Add your actual search logic here
            // This could be querying a database and displaying results
            
            // Example output:
            echo "<p>You searched for: " . htmlspecialchars($searchQuery) . "</p>";
        } else {
            echo "<p>Please enter something to search!</p>";
        }
    }
    ?>
</body>
</html>


// Connect to database (replace with your credentials)
$host = 'localhost';
$user = 'username';
$password = 'password';
$dbname = 'database_name';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM your_table WHERE column LIKE ?");
$stmt->bind_param("s", "%$searchQuery%");
$stmt->execute();

$result = $stmt->get_result();


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar Example</title>
</head>
<body>
    <?php
    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $search = $_POST['search'];
        
        // Connect to database
        $db = new mysqli('localhost', 'username', 'password', 'database_name');
        
        // Check connection
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        
        // Prepare and bind statement to prevent SQL injection
        $stmt = $db->prepare("SELECT * FROM table_name WHERE field_name LIKE ?");
        $search_term = "%" . $search . "%";
        $stmt->bind_param("s", $search_term);
        
        // Execute the query
        $stmt->execute();
        
        // Get result set
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>" . implode(" ", $row) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "No results found.";
        }
        
        // Close database connection
        $db->close();
    }
    ?>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="search" placeholder="Search...">
        <button type="submit">Search</button>
    </form>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .search-input {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="search-container">
    <form action="search.php" method="GET">
        <input type="text" name="query" class="search-input" placeholder="Search here...">
        <button type="submit" class="search-button">Search</button>
    </form>
</div>

<?php
// This is the search.php file

// Get the search query from URL parameters
$query = isset($_GET['query']) ? $_GET['query'] : '';

if ($query != '') {
    // Connect to database
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'your_database';

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Escape the query to prevent SQL injection
    $searchQuery = $conn->real_escape_string($query);

    // Perform search query in database
    $sql = "SELECT * FROM your_table WHERE column_name LIKE '%{$searchQuery}%'";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<h3>Search Results:</h3>";
        while ($row = $result->fetch_assoc()) {
            // Display the search results
            echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 5px 0;'>";
            echo "<p>".$row['column_name']."</p>";
            echo "</div>";
        }
    } else {
        echo "<h3>No results found!</h3>";
    }

    $conn->close();
} else {
    // If no query is entered
    echo "<h3>Please enter a search query.</h3>";
}
?>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="GET">
            <input type="text" name="query" class="search-box" placeholder="Search...">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if the form has been submitted
        if (isset($_GET['submit'])) {
            $query = $_GET['query'];

            // Sanitize user input
            $query = htmlspecialchars(trim($query));

            // Database connection parameters
            $host = "localhost";
            $dbusername = "root";
            $dbpassword = "";
            $dbname = "test";

            // Connect to database
            $conn = mysqli_connect($host, $dbusername, $dbpassword, $dbname);

            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // SQL query
            $sql = "SELECT title FROM products WHERE title LIKE '%" . mysqli_real_escape_string($conn, $query) . "%'";
            
            // Execute query
            $result = mysqli_query($conn, $sql);

            // Display results
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<h3>" . $row['title'] . "</h3>";
                }
            } else {
                echo "No results found.";
            }

            // Close database connection
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
</head>
<body>
    <?php
        // Check if search form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $search_term = $_POST['search_term'];
            
            // Connect to database
            $conn = mysqli_connect("localhost", "username", "password", "database_name");
            
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // SQL query to search for the term
            $sql = "SELECT * FROM your_table WHERE title LIKE '%" . mysqli_real_escape_string($conn, $search_term) . "%'";
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                // Output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div>" . $row['title'] . "</div>";
                }
            } else {
                echo "No results found";
            }
            
            // Close database connection
            mysqli_close($conn);
        }
    ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="search_term" placeholder="Search here...">
        <input type="submit" value="Search">
    </form>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
        }
        
        input[type="text"] {
            flex: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
            // Same PHP code as before...
        ?>
        
        <form class="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="search_term" placeholder="Search here...">
            <input type="submit" value="Search">
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-form {
            display: flex;
            gap: 10px;
        }
        input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="search-form">
            <input type="text" name="query" placeholder="Search here...">
            <button type="submit">Search</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get the search query
            $query = isset($_POST['query']) ? $_POST['query'] : '';
            
            // Sanitize the input to prevent SQL injection or XSS attacks
            $search_query = htmlspecialchars(trim($query));

            // Here you would typically connect to your database and perform a search query
            // For this example, we'll assume we have an array of data
            $data = array(
                "Apple",
                "Banana",
                "Cherry",
                "Date",
                "Elderberry",
                "Fig",
                "Grape",
                "Honeydew"
            );

            // Perform a simple search (case-insensitive)
            if (!empty($search_query)) {
                $results = preg_grep('/'.preg_quote($search_query, '/').'/i', $data);

                if (count($results) > 0) {
                    echo "<h3>Search Results:</h3>";
                    foreach ($results as $result) {
                        echo "<p>".$result."</p>";
                    }
                } else {
                    echo "<p>No results found for: '$search_query'</p>";
                }
            } else {
                echo "<p>Please enter a search query</p>";
            }
        }
        ?>

        <a href="<?php echo $_SERVER['PHP_SELF']; ?>">New Search</a>
    </div>
</body>
</html>


<?php
// Check if the form was submitted
if (isset($_POST['query'])) {
    $searchQuery = $_POST['query'];

    // Sanitize input to prevent SQL injection and XSS attacks
    $searchQuery = htmlspecialchars(trim($searchQuery));

    // Here you would typically connect to your database and perform a search query
    // For now, we'll just display the search results message
    echo "<h2>Search Results for: " . $searchQuery . "</h2>";
    echo "<p>Displaying results related to your search...</p>";

    // You can add your database connection and query logic here

} else {
    // If no query was provided, redirect back to the search page
    header("Location: index.html");
    exit();
}
?>


<?php
// This script demonstrates a simple search functionality

// Display form for inputting search query
echo "<form action='search.php' method='get'>";
echo "<input type='text' name='query' placeholder='Search...'>";
echo "<button type='submit'>Search</button>";
echo "</form>";

// Process the search query if submitted
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $searchQuery = $_GET['query'];
    
    // Connect to database (replace with your actual database connection)
    $conn = mysqli_connect('localhost', 'username', 'password', 'database_name');
    
    // Escape the search query to prevent SQL injection
    $searchQuery = mysqli_real_escape_string($conn, $searchQuery);
    
    // Search in the database (example table and columns)
    $sql = "SELECT * FROM products WHERE name LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%'";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='search-result'>";
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>" . $row['description'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "No results found.";
    }
    
    // Close database connection
    mysqli_close($conn);
}
?>


<?php
// Connect to the database
$host = "localhost";
$username = "username";
$password = "password";
$database = "my_database";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search term from form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = $_POST['search'];
    
    // Escape special characters to prevent SQL injection
    $search = mysqli_real_escape_string($conn, $search);

    // Search query
    $sql = "SELECT * FROM users WHERE first_name LIKE '%$search%' 
            OR last_name LIKE '%$search%' 
            OR email LIKE '%$search%'";
            
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                </tr>";
                
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['first_name'] . "</td>";
            echo "<td>" . $row['last_name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "No results found.";
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
        }
        
        input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="search-form">
                <input type="text" name="search" placeholder="Search...">
                <button type="submit">Search</button>
            </div>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <!-- Add some basic styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f0f0f0;
        }
        
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .search-form {
            display: flex;
            gap: 10px;
        }
        
        #searchInput {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        button {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #0056b3;
        }
        
        .results {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <!-- Search Form -->
        <?php
        // Check if form has been submitted
        $submitted = isset($_POST['search_query']);
        
        // Sample data (you can replace this with database query results)
        $items = array(
            'Apple',
            'Banana',
            'Cherry',
            'Date',
            'Elderberry',
            'Fig',
            'Grapefruit',
            'Kiwi',
            'Lemon',
            'Mango'
        );
        
        // Process search query
        if ($submitted) {
            $searchQuery = $_POST['search_query'];
            $searchResults = array();
            
            foreach ($items as $item) {
                // Case-insensitive comparison
                if (stripos($item, strtolower($searchQuery)) !== false) {
                    $searchResults[] = $item;
                }
            }
        }
        ?>
        
        <form class="search-form" method="post">
            <input type="text" id="searchInput" name="search_query" placeholder="Search..." <?php if ($submitted) { echo 'value="' . htmlspecialchars($searchQuery) . '"'; } ?>>
            <button type="submit">Search</button>
        </form>

        <!-- Display search results -->
        <?php if ($submitted): ?>
        <div class="results">
            <h3>Results for "<?php echo htmlspecialchars($searchQuery); ?>":</h3>
            <ul>
                <?php foreach ($searchResults as $result): ?>
                    <li><?php echo $result; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>


<?php
// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchQuery = $_POST['search_query'];

    // Validate input
    if (empty($searchQuery)) {
        die("Please enter a search term.");
    }

    // Database connection details
    $host = 'localhost';
    $username = 'your_username';
    $password = 'your_password';
    $database = 'your_database';

    // Connect to database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to search for the term
    $sql = "SELECT * FROM your_table WHERE column_name LIKE ?";
    
    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param("s", $searchTerm);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Display results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>" . $row['column_name'] . "</p>";
        }
    } else {
        echo "No results found.";
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-bar {
            width: 70%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-btn {
            padding: 12px 25px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-btn:hover {
            background-color: #45a049;
        }

        .result-row {
            margin: 10px 0;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <?php
        // Check if form has been submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get search term from form submission
            $search = $_POST['search'];
            
            // Sanitize the input to prevent SQL injection
            $search = htmlspecialchars(trim($search), ENT_QUOTES);
            
            // Database connection parameters
            $host = "localhost";
            $dbUsername = "username";
            $dbPassword = "password";
            $dbName = "database_name";

            try {
                // Create database connection
                $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Prepare and execute SQL query
                $stmt = $conn->prepare("SELECT * FROM users WHERE name LIKE ?");
                $searchTerm = '%' . $search . '%';
                $stmt->bind_param('s', $searchTerm);

                $stmt->execute();
                $result = $stmt->get_result();

                // Display search results
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='result-row'>";
                        echo "ID: " . $row['id'] . "<br>";
                        echo "Name: " . $row['name'] . "<br>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='result-row'>";
                    echo "No results found.";
                    echo "</div>";
                }

                // Close database connection
                $conn->close();
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
            }
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="text" name="search" class="search-bar" placeholder="Search...">
            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
</head>
<body>
    <?php
        // Check if the form has been submitted
        if (isset($_POST['submit'])) {
            $search_term = $_POST['search_term'];
            
            // Connect to the database
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'my_database';

            $mysqli = new mysqli($host, $username, $password, $database);

            // Check for connection errors
            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }

            // Sanitize the input
            $search_term = htmlspecialchars($search_term);

            // Query the database
            $query = "SELECT * FROM my_table WHERE title LIKE '%".$search_term."%'";
            
            if ($result = $mysqli->query($query)) {
                // Display the results
                while ($row = $result->fetch_assoc()) {
                    echo "<h3>" . $row['title'] . "</h3>";
                    echo "<p>" . $row['description'] . "</p>";
                    echo "<hr>";
                }
            } else {
                echo "No results found!";
            }

            // Close the database connection
            $mysqli->close();
        }
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="text" name="search_term" placeholder="Search...">
        <button type="submit" name="submit">Search</button>
    </form>
</body>
</html>


<?php
// Handle the search query
if (isset($_POST['search_query']) && $_POST['search_query'] !== '') {
    $searchQuery = $_POST['search_query'];
    
    // Here you would typically connect to a database and perform a search query
    // For now, we'll just echo back the search query
    
    echo "You searched for: " . htmlspecialchars($searchQuery);
} else {
    echo "Please enter a search query!";
}
?>

<br><br>
<a href="index.html">Back to Search</a>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Bar Example</title>
</head>
<body>
    <?php
        // Check if the form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchQuery = $_POST['query'];
            
            // Here you can add your search logic, e.g., querying a database
            echo "You searched for: " . htmlspecialchars($searchQuery);
        }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="query" placeholder="Search here...">
        <button type="submit">Search</button>
    </form>

</body>
</html>


<?php
// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search functionality
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchQuery = $_POST['query'];
    
    // SQL query to search for the term in your database
    $sql = "SELECT * FROM your_table WHERE column_name LIKE '%" . $conn->real_escape_string($searchQuery) . "%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "Result: " . htmlspecialchars($row["column_name"]) . "<br>";
        }
    } else {
        echo "No results found.";
    }
}

// Close the database connection
$conn->close();
?>


<?php
// Get search term from form input
$ searchTerm = $_POST['search_term'];
$sanitizedSearchTerm = mysqli_real_escape_string($connection, $searchTerm);

// Database connection details
$host = 'localhost';
$username = 'root'; // Change this to your database username
$password = ''; // Change this to your database password
$database = 'your_database_name'; // Change this to your database name

// Create database connection
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to search for the term in the database
$sql = "SELECT * FROM your_table_name WHERE column_name LIKE '%" . $sanitizedSearchTerm . "%'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<h2>" . $row['column_name'] . "</h2>";
        // Add more output as needed based on your table structure
    }
} else {
    echo "No results found!";
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Get search term from form input
if (isset($_POST['search_term'])) {
    $searchTerm = $_POST['search_term'];
} else {
    die("No search term provided.");
}

$sanitizedSearchTerm = htmlspecialchars(trim($searchTerm));

// Database connection details
$host = 'localhost';
$username = 'root'; // Change to your database username
$password = ''; // Change to your database password
$database = 'your_database_name'; // Change to your database name

try {
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind the statement
    $stmt = $conn->prepare("SELECT * FROM your_table_name WHERE column_name LIKE ?");
    $searchTermWithWildcards = '%' . $sanitizedSearchTerm . '%';
    $stmt->bind_param('s', $searchTermWithWildcards);

    // Execute the statement
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<h2>" . $row['column_name'] . "</h2>";
            // Add more output as needed based on your table structure
        }
    } else {
        echo "No results found!";
    }

    // Close connections
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>


<?php
// Database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get search term and type from POST data
    $search_term = isset($_POST['search_term']) ? $_POST['search_term'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';

    // Sanitize input to prevent SQL injection
    $search_term = htmlspecialchars($search_term);
    
    // Prepare the SQL query
    $stmt = $pdo->prepare("SELECT * FROM $type WHERE title LIKE ?");
    $search_query = "%{$search_term}%";
    $stmt->execute([$search_query]);

    // Fetch all matching records
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($results)) {
        foreach ($results as $result) {
            echo "<h3>".$result['title']."</h3>";
            echo "<p>".$result['content']."</p>";
            echo "<hr>";
        }
    } else {
        echo "No results found!";
    }

} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Close the database connection
$pdo = null;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .search-form {
            display: flex;
            gap: 10px;
            width: 100%;
        }

        .search-input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <h2>Search Bar</h2>
        
        <?php
        // Check if the form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $searchTerm = $_GET['search'] ?? '';
            
            // Sanitize input
            $searchTerm = htmlspecialchars($searchTerm);
            
            // Display search results or message
            if (!empty($searchTerm)) {
                echo "<div class='results'>";
                echo "<p>Search Results for: " . $searchTerm . "</p>";
                
                // Add your actual search logic here
                // This is just a simulation of search results
                $sampleResults = array(
                    "Related result 1",
                    "Matching item found",
                    "This is a sample result"
                );
                
                echo "<ul>";
                foreach ($sampleResults as $result) {
                    echo "<li>" . $result . "</li>";
                }
                echo "</ul>";
                echo "</div>";
            } else {
                echo "<div class='results'>";
                echo "<p>Please enter a search term to begin your search.</p>";
                echo "</div>";
            }
        }
        ?>

        <form class="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input type="text" class="search-input" name="search" placeholder="Enter your search...">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
</body>
</html>


<?php
// Connect to your database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if search term is provided
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_term = mysqli_real_escape_string($conn, $_POST['search_term']);

    // Write your SQL query here (example for MySQL)
    $sql = "SELECT * FROM your_table WHERE name LIKE '%" . $search_term . "%'";
    
    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Display the results
            echo "<pre>";
            print_r($row);
            echo "</pre>";
        }
    } else {
        echo "No results found.";
    }

    // Close the database connection
    mysqli_close($conn);
}
?>


<?php
// search.php

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_term = $_POST['search_term'];
    
    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM your_table WHERE name LIKE ?");
    $search = "%" . $search_term . "%";
    $stmt->bind_param("s", $search);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo "<pre>";
            print_r($row);
            echo "</pre>";
        }
    } else {
        echo "No results found.";
    }

    // Close the database connection
    mysqli_close($conn);
}
?>


<?php
// Get the search query from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = $_POST['query'];

    // Sanitize the input to prevent SQL injection
    $search_query = htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8');

    // Database connection details
    $db_host = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $db_name = 'your_database';

    // Connect to the database
    $conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Search query (assuming you have a table named 'users')
    $sql = "SELECT * FROM users 
            WHERE firstname LIKE '%" . $search_query . "%' 
            OR lastname LIKE '%" . $search_query . "%' 
            OR email LIKE '%" . $search_query . "%'";
            
    // Execute the query
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error in query: " . mysqli_error($conn));
    }

    // Display the results
    echo "<h3>Search Results:</h3>";
    
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div style='border-bottom: 1px solid #ddd; padding: 10px 0;'>";
        echo "<p>Name: " . $row['firstname'] . " " . $row['lastname'] . "</p>";
        echo "<p>Email: " . $row['email'] . "</p>";
        // Add more fields as needed
        echo "</div>";
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
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #45a049;
        }
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search_term" class="search-bar" placeholder="Search...">
            <button type="submit" name="submit" class="search-button">Search</button>
        </form>

        <?php
        // Check if form is submitted
        if (isset($_POST['submit'])) {
            $search_term = trim(htmlspecialchars($_POST['search_term']));

            // Sample data - replace with your database connection
            $items = array(
                array('title' => 'PHP Programming', 'description' => 'Learn PHP programming language'),
                array('title' => 'Web Development', 'description' => 'Master web development concepts'),
                array('title' => 'JavaScript', 'description' => 'Understanding JavaScript fundamentals'),
                array('title' => 'MySQL', 'description' => 'Working with MySQL database'),
            );

            echo "<div class='results'>";
            echo "<h3>Search Results:</h3>";
            echo "<table border='1'>";
            echo "<tr><th>Title</th><th>Description</th></tr>";

            $found = false;
            foreach ($items as $item) {
                // Convert to lowercase for case-insensitive search
                $title_match = strtolower($search_term);
                $description_match = strtolower($search_term);

                if (stripos($item['title'], $search_term) !== false || stripos($item['description'], $search_term) !== false) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($item['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($item['description']) . "</td>";
                    echo "</tr>";
                    $found = true;
                }
            }

            if (!$found) {
                echo "<tr><td colspan='2'>No results found.</td></tr>";
            }

            echo "</table>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar Example</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 2rem auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 10px;
            background-color: white;
            border: 1px solid #ddd;
            margin-bottom: 5px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="text" name="query" class="search-bar" placeholder="Search here...">
            <button type="submit" name="search" class="search-button">Search</button>
        </form>

        <?php
        // Check if form is submitted
        if (isset($_POST['search'])) {
            $searchQuery = $_POST['query'];
            
            // Sanitize input
            $searchQuery = htmlspecialchars($searchQuery);

            echo "<div class='results'><h3>Search Results for: '" . $searchQuery . "'</h3>";
            
            // Simulate database query (replace with your actual database connection)
            // Here we'll use a sample array of items
            $sampleItems = [
                ['id' => 1, 'title' => 'Item One'],
                ['id' => 2, 'title' => 'Item Two'],
                ['id' => 3, 'title' => 'Item Three'],
                // Add more items as needed
            ];

            $results = [];
            foreach ($sampleItems as $item) {
                if (stripos($item['title'], $searchQuery) !== false) {
                    $results[] = $item;
                }
            }

            if (!empty($results)) {
                echo "<div class='results'>";
                foreach ($results as $result) {
                    echo "<div class='result-item'>
                            <h4>" . $result['title'] . "</h4>
                          </div>";
                }
                echo "</div>";
            } else {
                echo "<p>No results found.</p>";
            }

            echo "</div>";
        }
        ?>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-bar input[type="text"] {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f8f8;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search_term" placeholder="Enter search term...">
            <button type="submit" class="search-button">Search</button>
        </form>
        
        <?php
            // Database connection details
            $host = "localhost";
            $username = "root";
            $password = "";
            $database = "my_database";

            // Connect to database
            $conn = mysqli_connect($host, $username, $password, $database);

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Check if search term is provided
            if (isset($_GET['search_term'])) {
                $search_term = mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['search_term'])));

                // SQL query to search for the term in the database
                $sql = "SELECT * FROM your_table WHERE field1 LIKE '%$search_term%' 
                        OR field2 LIKE '%$search_term%'";
                
                $result = mysqli_query($conn, $sql);

                if (!$result) {
                    die("Query failed: " . mysqli_error($conn));
                }

                // Display the results
                if (mysqli_num_rows($result) > 0) {
                    echo "<div class='results'>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Display the results in a table format or as needed
                        echo "<p>" . $row['field1'] . " - " . $row['field2'] . "</p>";
                    }
                    echo "</div>";
                } else {
                    echo "<div class='results'>";
                    echo "<p>No results found for: " . $search_term . "</p>";
                    echo "</div>";
                }
            }

            // Close the database connection
            mysqli_close($conn);
        ?>
    </div>
</body>
</html>


<?php
// Connect to database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term from form
$search = $_POST['search'];

// SQL query to search for the term in relevant columns
$sql = "SELECT * FROM table_name 
        WHERE column1 LIKE ? 
        OR column2 LIKE ?";
        
$stmt = $conn->prepare($sql);
$search_term = "%" . $search . "%";
$stmt->bind_param("ss", $search_term, $search_term);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Output the results in a table
    echo "<table border='1'>
            <tr>
                <th>Column1</th>
                <th>Column2</th>
                <!-- Add more columns as needed -->
            </tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["column1"] . "</td>
                <td>" . $row["column2"] . "</td>
                <!-- Add more columns as needed -->
              </tr>";
    }
    echo "</table>";
} else {
    echo "No results found";
}

// Close database connection
$stmt->close();
$conn->close();
?>


<?php
if (isset($_POST['submit'])) {
    $query = $_POST['query'];
    
    // Sanitize input to prevent SQL injection/XSS attacks
    $query = htmlspecialchars(trim($query));
    
    if (!empty($query)) {
        // Here you would typically connect to your database and perform a search query
        // For this example, we'll just display the search term
        echo "<h2>Search Results for: '" . $query . "'</h2>";
        
        // You would add your actual search logic here
        // For example, querying a database and displaying results
        
    } else {
        echo "Please enter a search term.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Search Bar</title>
    <style>
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        
        .search-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background-color: #45a049;
        }
        
        .results {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="search_query" class="search-box" placeholder="Search here...">
            <button type="submit" class="search-button">Search</button>
        </form>
        
        <?php
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchQuery = $_POST['search_query'];
            
            // Sanitize the input
            $searchQuery = htmlspecialchars($searchQuery);
            $searchQuery = trim($searchQuery);
            
            // Database connection
            $host = 'localhost';
            $username = 'root'; // Change to your database username
            $password = '';    // Change to your database password
            $database = 'test_db'; // Change to your database name
            
            // Connect to database
            $conn = new mysqli($host, $username, $password, $database);
            
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            // Escape special characters in the query
            $searchQuery = $conn->real_escape_string($searchQuery);
            
            // Search query
            $sql = "SELECT * FROM your_table_name WHERE column_name LIKE '%" . $searchQuery . "%'";
            
            // Execute the query
            $result = $conn->query($sql);
            
            // Check if any results found
            if ($result->num_rows > 0) {
                echo "<div class='results'>";
                echo "<table border='1'>";
                echo "<tr><th>Column Name</th></tr>"; // Change to your column name
                
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row['column_name'] . "</td></tr>"; // Change to your column name
                }
                
                echo "</table>";
                echo "</div>";
            } else {
                echo "<div class='results'>";
                echo "No results found!";
                echo "</div>";
            }
            
            // Close the database connection
            $conn->close();
        }
        ?>
    </div>
</body>
</html>

