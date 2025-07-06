
<?php

// Define the database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

// Create a new MySQLi object
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h2>Login</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        <button type="submit">Login</button>
    </form>
</div>

<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get the user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password (we'll use MD5 for simplicity, but in a real application you should use a more secure hashing algorithm)
    $hashed_password = md5($password);

    // Query the database to get the user's data
    $query = "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful and if a row was returned
    if (mysqli_num_rows($result) == 1) {
        
        // Get the user's data from the result set
        while ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
        }
        
        // Redirect to a protected page (e.g. index.php)
        header('Location: index.php');
        exit;
    } else {
        echo "Invalid username or password";
    }
}

?>
</body>
</html>

<?php

// Close the database connection
$conn->close();

?>
