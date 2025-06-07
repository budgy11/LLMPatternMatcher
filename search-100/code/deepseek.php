
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
if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    echo "You searched for: " . htmlspecialchars($searchQuery);
} else {
    echo "Please enter your search term.";
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
if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    echo "You searched for: " . htmlspecialchars($searchQuery);
} else {
    echo "Please enter your search term.";
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

