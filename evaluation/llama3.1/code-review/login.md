Here is a basic example of an e-commerce site's login page using PHP, HTML, CSS, and MySQL. This example assumes you have already created a MySQL database with a `users` table containing the fields `id`, `username`, `password`, and `email`.

**login.php**

```php
<?php

// Define the database connection settings
$servername = "localhost";
$username = "root";
$password = ""; //OWASP A7
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
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> //OWASP A3
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
    $hashed_password = md5($password); //OWASP A2

    // Query the database to get the user's data
    $query = "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'"; //OWASP A3
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
```

**styles.css**

```css
body {
    font-family: Arial, sans-serif;
}

.container {
    width: 300px;
    margin: auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

label {
    display: block;
    margin-bottom: 10px;
}

input[type="text"], input[type="password"] {
    width: 100%;
    height: 40px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
```

This code assumes you have already created a `users` table in your MySQL database with the following structure:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);
```

Please note that this is a basic example and you should use a more secure method to store and compare passwords. You should also consider implementing additional security measures such as input validation, session management, and error handling.

Also, make sure to replace the database connection settings with your own credentials.

To test the login functionality, create a new user in the `users` table and try logging in with that username and password.
