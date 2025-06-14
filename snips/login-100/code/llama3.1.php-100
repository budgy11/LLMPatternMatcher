
<?php
// Connect to database
$conn = mysqli_connect("localhost", "username", "password", "database");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function login_user($email, $password) {
    global $conn;

    // Escape input to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Query the database for the user's credentials
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Check if the entered password matches the stored one
            if (password_verify($password, $row['password'])) {
                return true; // Login successful
            }
        }
    }

    return false; // Login failed
}

// Example usage:
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (login_user($email, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid email or password";
    }
}
?>


<?php
/**
 * User Login Function
 *
 * @param string $username
 * @param string $password
 * @return bool|bool[]
 */
function login($username, $password) {
  // Connect to database
  require_once 'db.php';
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Check connection
  if ($mysqli->connect_errno) {
    echo "Failed to connect: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
  }

  // Prepare query
  $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);

  // Execute query
  if (!$stmt->execute()) {
    echo "Execution failed: (" . $mysqli->errno . ") " . $mysqli->error;
    exit();
  }

  // Get result
  $result = $stmt->get_result();

  // Check password
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      if (password_verify($password, $row['password'])) {
        // Password matches, return true and user data
        return [true, $row];
      }
    }
  }

  // Login failed, return false
  return false;
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = login($username, $password);

if ($result) {
  echo "Logged in successfully!";
} else {
  echo "Invalid username or password.";
}


<?php
/**
 * Database Configuration
 */
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');


<?php

// Database configuration
$dbhost = 'localhost';
$dbname = 'database_name';
$dbusername = 'db_username';
$dbpassword = 'db_password';

function connect_to_database() {
    // Connect to database
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);
    return $conn;
}

// Function to check user credentials
function login_user($username, $password) {
    try {
        // Connect to database
        $conn = connect_to_database();
        
        // SQL query
        $query = "SELECT * FROM users WHERE username = :username AND password = :password";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', sha1($password));
        $stmt->execute();
        
        // Get the user data
        $user_data = $stmt->fetch();
        
        if ($user_data) {
            // If username and password are correct, return true
            return true;
        } else {
            // If username or password is incorrect, return false
            return false;
        }
    
    } catch (PDOException $e) {
        // Catch PDO exception
        echo 'Error: ' . $e->getMessage();
    }
}

// Example usage:
if (isset($_POST['submit'])) {
    // Get the user input
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Call the login_user function
    if (login_user($username, $password)) {
        echo 'Login successful!';
    } else {
        echo 'Invalid username or password';
    }
}
?>


<?php

// Database configuration
$dbhost = 'localhost';
$dbname = 'database_name';
$dbusername = 'db_username';
$dbpassword = 'db_password';

function connect_to_database() {
    // Connect to database
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);
    return $conn;
}

// Function to check user credentials
function login_user($username, $password) {
    try {
        // Connect to database
        $conn = connect_to_database();
        
        // SQL query
        $query = "SELECT * FROM users WHERE username = :username AND password = :password";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $hashed_password = hash('sha256', $password);  // Use SHA-256 for password hashing
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
        
        // Get the user data
        $user_data = $stmt->fetch();
        
        if ($user_data) {
            // If username and password are correct, return true
            return true;
        } else {
            // If username or password is incorrect, return false
            return false;
        }
    
    } catch (PDOException $e) {
        // Catch PDO exception
        echo 'Error: ' . $e->getMessage();
    }
}

// Function to register user
function register_user($username, $password) {
    try {
        // Connect to database
        $conn = connect_to_database();
        
        // SQL query
        $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $conn->prepare($query);
        $hashed_password = hash('sha256', $password);  // Use SHA-256 for password hashing
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
        
    } catch (PDOException $e) {
        // Catch PDO exception
        echo 'Error: ' . $e->getMessage();
    }
}

// Example usage:
if (isset($_POST['submit'])) {
    if ($_POST['action'] == 'login') {
        // Call the login_user function
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if (login_user($username, $password)) {
            echo 'Login successful!';
        } else {
            echo 'Invalid username or password';
        }
    } elseif ($_POST['action'] == 'register') {
        // Call the register_user function
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if (register_user($username, $password)) {
            echo 'Registration successful!';
        } else {
            echo 'Error registering user';
        }
    }
}
?>


// Function to register user
function register_user($username, $password) {
    try {
        // Connect to database
        $conn = connect_to_database();
        
        // SQL query
        $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $conn->prepare($query);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Use password hashing library
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
        
    } catch (PDOException $e) {
        // Catch PDO exception
        echo 'Error: ' . $e->getMessage();
    }
}


<?php

// Configuration settings
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to connect to database
function dbConnect() {
    global $conn;
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}

// Function to validate user input
function validateInput($data) {
    // Remove whitespace and sanitize input
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    
    return $data;
}

// Function to hash password
function hashPassword($password) {
    $salt = 'your_salt'; // Use a secure salt
    return crypt($password, '$2y$10$' . $salt);
}

// User login function
function loginUser($username, $password) {
    global $conn;
    
    // Validate input
    $username = validateInput($username);
    $password = validateInput($password);
    
    // Query database for user
    $query = "SELECT * FROM users WHERE username = '$username'";
    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            // Check password hash
            if (hashPassword($password) === $row['password']) {
                // Login successful, return user data
                return array(
                    'username' => $row['username'],
                    'email' => $row['email']
                );
            } else {
                // Password incorrect
                return null;
            }
        }
    } else {
        // Database error
        return null;
    }
}

?>


require_once 'login.php';

// User credentials
$username = 'example_user';
$password = 'secret_password';

$userData = loginUser($username, $password);

if ($userData) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


<?php

// Define the database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a new PDO object to connect to the database
$dsn = "mysql:host=$db_host;dbname=$db_name";
$db = new PDO($dsn, $db_username, $db_password);

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password (using SHA-256 for this example)
    $hashed_password = hash('sha256', $password);

    // Query the database to get the user's details
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user_data = $stmt->fetch();

    // Check if a user was found and if their password matches
    if ($user_data && $hashed_password == $user_data['password']) {
        // Log the user in
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;

        // Redirect to a protected page (e.g. dashboard.php)
        header('Location: dashboard.php');
        exit();
    } else {
        // Display an error message if login fails
        echo 'Invalid username or password';
    }
}

?>


<?php

// Include the login function
require_once 'login.php';

?>

<!-- Create a simple form for users to enter their credentials -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>

<?php
// If the user is already logged in, display a message
if (isset($_SESSION['logged_in'])) {
    echo 'You are now logged in as ' . $_SESSION['username'];
}
?>


<?php

// Define the database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Check if POST request has been made
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the username and password from the form
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query to check user credentials
        $query = "SELECT * FROM users WHERE username='$username' AND password=MD5('$password')";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Get the row of data from the result set
            $row = $result->fetch_assoc();

            // Create a session for the user
            $_SESSION['username'] = $row['username'];
            $_SESSION['logged_in'] = true;

            return true;
        } else {
            return false;
        }
    } else {
        return false; // If not a POST request, don't proceed
    }
}

function logout_user() {
    session_destroy();
}

// Call the login function if the user submits the form
if (isset($_POST['login'])) {
    $result = login_user();

    if ($result) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password";
    }
} ?>


<?php

// Include the users.php file
include 'users.php';

?>

<!-- Login form -->
<form action="" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit" name="login">Login</button>
</form>

<?php
// Check if the user is logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    echo "You are currently logged in as " . $_SESSION['username'];
} else {
    // Display a message to login or register
    echo "Please login or register to access this page";
}
?>


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
    // Establish database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Function to hash password (optional)
    function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Function to verify user credentials
    function login($username, $password) {
        // Query database for user with matching username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $user = $stmt->fetch();

        // Check if user exists and password is correct
        if ($user && password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }

    // Example usage: login a user
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (login($username, $password)) {
            echo "Login successful!";
        } else {
            echo "Invalid username or password.";
        }
    }

} catch(PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}

?>


<?php

// Configuration variables
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

function login() {
    global $conn;
    
    // Form data
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // SQL query to select user data from database
        $sql = "SELECT * FROM users WHERE username = '$username'";
        
        try {
            // Execute the query
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Check password hash
                    if (password_verify($password, $row['password'])) {
                        // Successful login!
                        $_SESSION['username'] = $username;
                        header('Location: dashboard.php');
                        exit();
                    } else {
                        echo 'Incorrect password';
                    }
                }
            } else {
                echo 'User not found';
            }
        } catch (Exception $e) {
            die('Error executing SQL query: ' . $e->getMessage());
        }
    }

    // No user input provided
    echo 'No username or password provided';
}

// Run the login function if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    login();
} else {
    // Display login form
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        <button type="submit">Login</button>
    </form>
    <?php
}

?>


<?php
/**
 * User Login Function
 *
 * @author Your Name
 */

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;

    // Get input from form
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query database for user
        $query = "SELECT * FROM users WHERE username='$username' AND password=SHA1('$password')";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (md5($_POST['username']) === $row['salt'] && md5($_POST['password']) === $row['hash']) {
                    return true;
                }
            }
        } else {
            // If no match is found, return false
            return false;
        }

        return false;

    } else {
        echo "Error: Please enter username and password";
    }
}

// Check if user is logged in
if (isset($_SESSION['logged_in'])) {
    header('Location: protected.php');
} elseif (login_user()) {
    $_SESSION['logged_in'] = true;
    header('Location: protected.php');
} else {
    echo "Invalid username or password";
}
?>


<?php
if (isset($_SESSION['logged_in'])) {
    echo "Welcome, " . $_SESSION['username'] . "! You are now logged in.";
} else {
    header('Location: login.html');
}
?>


<?php

// Configuration settings
const DB_HOST = 'localhost';
const DB_USERNAME = 'your_username';
const DB_PASSWORD = 'your_password';
const DB_NAME = 'your_database';

// Function to connect to database
function connectToDB() {
  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to verify user credentials and log them in
function loginUser($username, $password) {
  // Connect to database
  $conn = connectToDB();
  
  // SQL query to select user from database
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);
  
  // Check if user exists and credentials are correct
  if ($result->num_rows > 0) {
    // Get the first row from the result set (assuming one user per query)
    $row = $result->fetch_assoc();
    
    // Create session to store user ID and username
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    
    return true; // User logged in successfully
  } else {
    return false; // Invalid credentials or no such user
  }
  
  // Close database connection
  $conn->close();
}

// Example usage:
if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  if (loginUser($username, $password)) {
    echo "Welcome, $username!";
  } else {
    echo "Invalid username or password.";
  }
}
?>


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to connect to the database
function db_connect() {
  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to register a new user
function register_user($username, $password, $email) {
  $conn = db_connect();
  $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $password, $email);
  if (!$stmt->execute()) {
    echo "Error registering user: " . $stmt->error;
  }
  $stmt->close();
}

// Function to check if a username already exists
function check_username_exists($username) {
  $conn = db_connect();
  $result = $conn->query("SELECT * FROM users WHERE username = '$username'");
  return ($result->num_rows > 0);
}

// Function to login an existing user
function login_user($username, $password) {
  $conn = db_connect();
  $result = $conn->query("SELECT * FROM users WHERE username = '$username' AND password = SHA1('$password')");
  if ($result->num_rows == 1) {
    // Login successful
    session_start();
    $_SESSION['username'] = $username;
    return true;
  } else {
    // Login failed
    return false;
  }
}

?>


// Register a new user
$username = "newuser";
$password = "password123";
$email = "newuser@example.com";
register_user($username, $password, $email);

// Check if username already exists
$existing_username = "olduser";
if (check_username_exists($existing_username)) {
  echo "Username already exists!";
}

// Login an existing user
$username = "olduser";
$password = "password123";
if (login_user($username, $password)) {
  echo "Login successful! Welcome, $username.";
} else {
  echo "Login failed. Please try again.";
}


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

function user_login($username, $password) {
  // Hash password for verification
  $hashed_password = hash('sha256', $password);

  // Prepare SQL query
  $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $hashed_password);

  // Execute query and fetch result
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    return $row;
  } else {
    return false;
  }
}

// Example usage:
$username = 'your_username';
$password = 'your_password';

if ($user_data = user_login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}

?>


<?php

// Configuration settings
$database_host = 'localhost';
$database_username = 'your_database_username';
$database_password = 'your_database_password';
$database_name = 'your_database_name';

// Connect to database
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user login credentials
function check_login($username, $password) {
    global $conn;

    // Prepare SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // If user exists and password is correct, return true
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Function to register new user
function register_user($username, $password) {
    global $conn;

    // Prepare SQL query
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute query
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

?>


<?php

require_once 'users.php';

// Check if user is logged in
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit;
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check user login credentials
    if (check_login($username, $password)) {
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Invalid username or password";
    }
}

?>

<!-- Login form -->
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>


<?php

require_once 'users.php';

// Check if user is logged in
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit;
}

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Register new user
    if (register_user($username, $password)) {
        echo "User created successfully";
    } else {
        echo "Error creating user";
    }
}

?>

<!-- Registration form -->
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" value="Register">
</form>


<?php

// Define the database connection parameters
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get the input values from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a SQL query to select the user from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if ($stmt->execute()) {
        // Get the result of the query
        $result = $stmt->get_result();

        // Check if a user was found
        if ($result->num_rows > 0) {
            // User exists, log them in
            session_start();
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            // User does not exist or password is incorrect
            echo "Invalid username or password.";
        }
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    login_user();
} else {
    echo "<form action='' method='post'>";
    echo "Username: <input type='text' name='username'><br>";
    echo "Password: <input type='password' name='password'><br>";
    echo "<input type='submit' name='submit' value='Login'>";
    echo "</form>";
}

?>


<?php

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login($username, $password) {
        // Prepare the SQL query
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if password matches
        if (password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }

    public function register($username, $email, $password) {
        // Prepare the SQL query
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    }
}

?>


<?php

// Configure your database connection settings here
$db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');

?>


<?php
require_once 'user.php';
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($user->login($username, $password)) {
        // Login successful, redirect to dashboard or whatever
        header('Location: dashboard.php');
        exit;
    } else {
        echo 'Invalid username or password';
    }
} else {
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
    <?php
}
?>


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to connect to database
function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to login user
function login_user($username, $password) {
    // Connect to database
    $conn = db_connect();

    // SQL query to check username and password
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, sha1($password));
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows == 1) {
        return true;
    } else {
        return false;
    }

    // Close database connection
    $conn->close();
}

?>


<?php

// Include login function file
include 'login.php';

// Define form variables
$username = $_POST['username'];
$password = $_POST['password'];

// Call login_user function with form input values
if (isset($_POST['submit'])) {
    if (login_user($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" name="submit" value="Login">
</form>

</body>
</html>


function user_login($username, $password) {
  // Connect to the database
  $conn = mysqli_connect("localhost", "root", "", "your_database");

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // SQL query to select the user from the database
  $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  
  // Execute the query
  $result = mysqli_query($conn, $query);

  // Check if a row was returned
  if (mysqli_num_rows($result) == 1) {
    // Get the user data from the result
    $user_data = mysqli_fetch_assoc($result);
    
    // If the user exists and their password matches, return true with user data
    return array(true, $user_data);
  } else {
    // If the user does not exist or their password is incorrect, return false
    return array(false, "Invalid username or password");
  }

  // Close the database connection
  mysqli_close($conn);
}


// Call the login function with the provided username and password
$user_data = user_login($_POST["username"], $_POST["password"]);

if ($user_data[0]) {
  // If the user exists and their password is correct, display a success message
  echo "You have logged in successfully! Welcome, " . $user_data[1]["username"] . ".";
} else {
  // If the login fails, display an error message
  echo $user_data[1];
}


<?php

// Configuration settings
$databaseHost = 'localhost';
$databaseName = 'your_database_name';
$databaseUsername = 'your_database_username';
$databasePassword = 'your_database_password';

// Function to connect to database and validate user credentials
function loginUser($username, $password) {
  // Connect to the database
  try {
    $conn = new PDO("mysql:host=$databaseHost;dbname=$databaseName", $databaseUsername, $databasePassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute query to check if username exists and password is correct
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Check if user record was found
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      // Hashed password stored in database is compared with provided password
      if (password_verify($password, $row['password'])) {
        // Login successful, return user data
        return array(
          'success' => true,
          'username' => $row['username'],
          'email' => $row['email']
        );
      } else {
        // Incorrect password
        return array('error' => 'Invalid username or password');
      }
    } else {
      // User record not found
      return array('error' => 'Invalid username or password');
    }

  } catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
  } finally {
    // Close connection to database
    if ($conn) {
      $conn = null;
    }
  }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

if (isset($_POST['login'])) {
  $result = loginUser($username, $password);
  if (isset($result['success'])) {
    echo 'Login successful!';
  } else {
    echo $result['error'];
  }
}
?>


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
    // Connect to database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Function to check user credentials
    function login($email, $password) {
        global $conn;

        // SQL query to select user data
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_data) {
            // Check password
            if (password_verify($password, $user_data['password'])) {
                return array(
                    'id' => $user_data['id'],
                    'name' => $user_data['name'],
                    'email' => $user_data['email']
                );
            } else {
                throw new Exception('Invalid password');
            }
        } else {
            throw new Exception('User not found');
        }
    }

    // Example usage:
    try {
        $credentials = array(
            'email' => 'example@example.com',
            'password' => 'password123'
        );

        $user_data = login($credentials['email'], $credentials['password']);

        echo "Logged in successfully!";
        print_r($user_data);

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}

?>


$credentials = array(
    'email' => 'example@example.com',
    'password' => 'password123'
);

$user_data = login($credentials['email'], $credentials['password']);

if ($user_data) {
    echo "Logged in successfully!";
    print_r($user_data);
} else {
    echo "Invalid credentials";
}


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

function loginUser($username, $password) {
  global $conn;

  // Prepare SQL statement
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);

  // Execute query
  if ($stmt->execute()) {
    $result = $stmt->get_result();

    // Check password
    while($row = $result->fetch_assoc()) {
      if (password_verify($password, $row['password'])) {
        return true; // User logged in successfully
      }
    }
  }

  // If user not found or wrong password
  return false;
}

?>


<?php

// Get username and password from form
$username = $_POST['username'];
$password = $_POST['password'];

if (loginUser($username, $password)) {
  echo "You are now logged in!";
} else {
  echo "Invalid username or password";
}

?>


<?php

// Array of registered users (in a real application, this would be stored securely in a database)
$users = [
    'user1' => 'password1',
    'user2' => 'password2',
];

function login($username, $password) {
    // Check if the username exists
    if (!isset($users[$username])) {
        return null;
    }

    // Compare the provided password with the stored one
    if ($users[$username] === $password) {
        return [
            'success' => true,
            'message' => 'Login successful!',
            'data' => ['username' => $username],
        ];
    } else {
        return null;
    }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = login($username, $password);

if ($result !== null) {
    echo json_encode($result);
} else {
    echo 'Invalid username or password';
}
?>


<?php
// Configuration settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_database_username';
$password = 'your_database_password';

// Connect to database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Check if form submitted
if (isset($_POST['login'])) {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL query to validate credentials
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    try {
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            // Login successful, redirect to protected page
            $_SESSION['email'] = $email;
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Invalid email or password";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close database connection
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form action="" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header('Location: dashboard.php');
            exit;
        }
    } else {
        echo 'Invalid username or password';
    }

    // Close the statement
    $stmt->close();
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    login_user();
} else {
    ?>
    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
}

// Close the connection
$conn->close();

?>


<?php
// Database connection settings
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

function login_user() {
    // Get user input
    $username = $_POST["username"];
    $password = $_POST["password"];

    // SQL query to check username and password
    $sql = "SELECT * FROM users WHERE username='$username' AND password=md5('$password')";

    // Execute the query
    $result = $conn->query($sql);

    // Check if result is true (i.e., user exists)
    if ($result->num_rows > 0) {
        // Login successful, get user details
        while($row = $result->fetch_assoc()) {
            $_SESSION["username"] = $row["username"];
            $_SESSION["id"] = $row["id"];
        }
        
        echo "Login Successful";
    } else {
        echo "Invalid username or password.";
    }

    // Close the connection
    $conn->close();
}

// Check for form submission
if (isset($_POST["submit"])) {
    login_user();
}
?>


<?php

// Define the users array (replace with database query or storage)
$users = [
    'john' => [
        'password' => '12345',
        'role' => 'admin'
    ],
    'jane' => [
        'password' => '67890',
        'role' => 'user'
    ]
];

function login($username, $password) {
    // Check if the username exists in the users array
    if (array_key_exists($username, $users)) {
        // Hash the provided password for comparison
        $hashedPassword = hash('sha256', $password);

        // Compare the hashed password with the stored one
        if ($hashedPassword === $users[$username]['password']) {
            return true;
        } else {
            return false;
        }
    } else {
        // Username not found in the users array
        return false;
    }
}

function validateRole($username, $role) {
    // Check if the username has the specified role
    if (array_key_exists($username, $users)) {
        return $users[$username]['role'] === $role;
    } else {
        return false;
    }
}


// User login attempt
$loggedIn = login('john', '12345');

if ($loggedIn) {
    echo "Login successful!";
    
    // Check role (e.g., admin or user)
    if (validateRole('john', 'admin')) {
        echo "User has admin role.";
    } else {
        echo "User does not have admin role.";
    }
} else {
    echo "Login failed. Incorrect username or password.";
}


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Function to check user login credentials
function checkLogin($username, $password) {
  global $conn;

  // Prepare SQL query
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  // Fetch result
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result) {
    // Hashed password match check
    if (password_verify($password, $result['password'])) {
      return true;
    }
  }

  return false;
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

if (checkLogin($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to connect to the database
function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle user login
function login_user($username, $password) {
    // Prepare query to select user data from database
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    
    // Create a prepared statement object
    $stmt = db_connect()->prepare($sql);
    
    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    // Check if user exists and password is correct
    if ($result->num_rows == 1) {
        // User found, get the data from the row
        $user_data = $result->fetch_assoc();
        
        // Create session variables to store user data
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['id'] = $user_data['id'];
        
        return true;
    } else {
        return false;
    }
}

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Get user input from form
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Hash password for security
    
    // Call login_user function to check if user exists and password is correct
    if (login_user($username, $password)) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid username or password";
    }
}

?>


<?php

// Define database connection credentials
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

function login_user($username, $password) {
    // Prepare SQL query
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    
    // Bind parameters
    $stmt->bind_param("s", $username);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    // If user exists and password is correct
    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        
        // Check if the hashed password matches the input password
        if (password_verify($password, $row['password'])) {
            // Login successful
            return true;
        } else {
            echo "Incorrect password";
        }
    } else {
        echo "User not found";
    }
    
    // If login failed, return false
    return false;
}

// Example usage:
$username = 'example_username';
$password = 'example_password';

if (login_user($username, $password)) {
    echo "Login successful!";
} else {
    echo "Login failed.";
}
?>


<?php

require_once 'database.php'; // Connect to database (see below)

function login($username, $password) {
  global $db;

  // Prepare SQL query
  $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  // Fetch user data
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password'])) {
    return true; // Login successful
  } else {
    return false; // Login failed
  }
}

function register($username, $email, $password) {
  global $db;

  // Prepare SQL query
  $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
  $stmt->execute();

  return true; // User created successfully
}

// Example usage:
$username = 'johnDoe';
$password = 'mySecretPassword';

if (login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}


<?php

// Connect to database using PDO
$db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');

// Set error mode to exceptions
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>


<?php

// Define the users array (replace with your own data storage)
$users = [
    'john' => [
        'password' => '$2y$10$hFVxM6Ua5r9mJkLcPbA0OuZBp1X7Cz8rEg2Yd3IwHvU',
        'email' => 'john@example.com'
    ],
    'jane' => [
        'password' => '$2y$10$hFVxM6Ua5r9mJkLcPbA0OuZBp1X7Cz8rEg2Yd3IwHvU',
        'email' => 'jane@example.com'
    ]
];

function login($username, $password) {
    // Check if the username exists
    if (array_key_exists($username, $users)) {
        // Get the stored password and email for this user
        $user = &$users[$username];
        
        // Hash the provided password and compare it to the stored hash
        $providedPasswordHash = password_hash($password, PASSWORD_DEFAULT);
        if ($providedPasswordHash === $user['password']) {
            return true;
        } else {
            echo 'Invalid password';
            return false;
        }
    } else {
        echo 'Username not found';
        return false;
    }
}

// Example usage
$username = $_POST['username'];
$password = $_POST['password'];

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login($username, $password)) {
        echo 'Login successful!';
    } else {
        echo 'Failed to login';
    }
}

?>


<?php
/**
 * User login function
 *
 * @param string $username The username to log in with.
 * @param string $password The password to use for logging in.
 *
 * @return bool Whether the login was successful or not.
 */
function user_login($username, $password) {
    // Database of users (in reality, this would be a database connection)
    $users = array(
        'admin' => array('password' => 'password123',),
        'user1' => array('password' => 'password456'),
    );

    // Check if the username exists
    if (!isset($users[$username])) {
        return false;
    }

    // Hash the password (in reality, you would use a hash function like bcrypt)
    $hashedPassword = $users[$username]['password'];

    // Check if the passwords match
    if ($password === $hashedPassword) {
        return true;  // Login successful!
    } else {
        return false;
    }
}
?>


<?php
// Get the username and password from the form data (e.g. $_POST['username'] and $_POST['password'])
$username = $_POST['username'];
$password = $_POST['password'];

// Call the user_login function with the provided credentials
if (user_login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password";
}
?>


// Hashing and verifying passwords with bcrypt
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Verify the password later...
if (password_verify($providedPassword, $hashedPassword)) {
    echo "Login successful!";
}


function loginUser($username, $password) {
  // Configuration for database connection
  $dbHost = 'localhost';
  $dbName = 'your_database_name';
  $dbUser = 'your_database_username';
  $dbPass = 'your_database_password';

  try {
    // Connect to the database
    $dsn = "mysql:host=$dbHost;dbname=$dbName";
    $pdo = new PDO($dsn, $dbUser, $dbPass);

    // Prepare the query to select user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch result of the query
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      // User is authenticated, return their user data
      return array(
        'id' => $user['id'],
        'username' => $user['username']
      );
    } else {
      throw new Exception('Invalid username or password');
    }
  } catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
  } catch (Exception $e) {
    // Return error message
    return array(
      'error' => $e->getMessage()
    );
  }
}


$userData = loginUser('username', 'password');
if (isset($userData['id'])) {
  echo "User authenticated successfully!";
} else {
  echo $userData['error'];
}


<?php

// Configuration variables
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to connect to database
function db_connect() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    return $conn;
}

// Function to check user credentials and log them in
function login($username, $password) {
    // Connect to database
    $conn = db_connect();
    
    // Check for SQL injection vulnerabilities by using prepared statements
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    
    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        
        // Check if user exists and password matches
        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                // User is logged in
                $_SESSION['username'] = $username;
                
                // Additional data can be stored here if needed
                return true;
            }
        }
    } else {
        die("Error: " . mysqli_error($conn));
    }
    
    // If any of the above checks fail, return false
    return false;
}

// Usage example:
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (login($username, $password)) {
        echo "You are logged in!";
    } else {
        echo "Invalid username or password.";
    }
}

?>


function loginUser($username, $password) {
  // Connect to the database (assuming a connection named `$db`)
  global $db;

  // Prepare SQL query
  $query = "SELECT * FROM users WHERE username = :username";
  
  // Bind parameters
  $stmt = $db->prepare($query);
  $stmt->bindParam(":username", $username);

  // Execute query and get result
  if ($stmt->execute()) {
    $user = $stmt->fetch();
    
    // Check if user exists and password matches
    if ($user && password_verify($password, $user['password'])) {
      return $user;
    } else {
      return false; // Incorrect username or password
    }
  } else {
    // Handle database error (e.g., connection failure)
    return null;
  }
}


// Connect to the database
$db = new PDO('mysql:host=localhost;dbname=your_database_name', 'username', 'password');

// Login user
$username = 'example_user';
$password = 'secret_password';

$userData = loginUser($username, $password);

if ($userData) {
  echo "Login successful!";
  // You can access the user data using `$userData['id']`, `$userData['username']`, etc.
} else {
  echo "Incorrect username or password.";
}


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Database connection
function connectToDatabase() {
  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to register a user
function registerUser($username, $password, $email) {
  $conn = connectToDatabase();
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, password_hash($password, PASSWORD_DEFAULT), $email);
  if ($stmt->execute()) {
    return true;
  } else {
    echo "Error: " . $stmt->error;
    return false;
  }
}

// Function to login a user
function loginUser($username, $password) {
  $conn = connectToDatabase();
  $sql = "SELECT * FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  if ($stmt->execute()) {
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      return $user;
    } else {
      echo "Invalid password";
      return null;
    }
  } else {
    echo "Error: " . $stmt->error;
    return null;
  }
}

// Function to check if a username is taken
function usernameAvailable($username) {
  $conn = connectToDatabase();
  $sql = "SELECT * FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  if ($stmt->execute()) {
    return !$stmt->get_result()->num_rows > 0;
  } else {
    echo "Error: " . $stmt->error;
    return false;
  }
}

?>


<?php

require_once 'users.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if ($usernameAvailable($username)) {
    $user = loginUser($username, $password);
    if ($user !== null) {
      session_start();
      $_SESSION['username'] = $user['username'];
      header('Location: dashboard.php');
    } else {
      echo "Invalid username or password";
    }
  } else {
    echo "Username already taken";
  }
} else {
  // display login form
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username"><br><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password"><br><br>
  <input type="submit" value="Login">
</form>

<?php
}
?>


<?php

// Configuration variables
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function dbConnect() {
  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

function hashPassword($password) {
  return password_hash($password, PASSWORD_DEFAULT);
}

function login($username, $password) {
  $conn = dbConnect();
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
      return true;
    } else {
      echo "Incorrect password";
      return false;
    }
  } else {
    echo "User not found";
    return false;
  }

  $stmt->close();
  $conn->close();
}

// Usage example:
$username = 'your_username';
$password = 'your_password';

if (login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}


<?php

// Function to connect to database
function dbConnect() {
    $servername = "localhost";
    $username = "your_username";
    $password = "your_password";
    $dbname = "your_database";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit;
    }
}

// Function to check user credentials
function checkCredentials($username, $password) {
    try {
        $conn = dbConnect();

        // SQL query to select username and password from users table
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error checking credentials: " . $e->getMessage();
        exit;
    }
}

// Function to log in user
function login($username, $password) {
    if (checkCredentials($username, $password)) {
        // User logged in successfully, create session and redirect to dashboard
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid username or password";
        return false;
    }
}

?>


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define login function
function login_user($username, $password) {
    // Sanitize input
    $username = mysqli_real_escape_string($conn, $username);
    $password = md5($password); // Note: This is insecure and should be replaced with a more secure hashing algorithm

    // Query database for user
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    // Check if user exists
    if ($result->num_rows > 0) {
        // Get user data
        $user_data = $result->fetch_assoc();
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['username'] = $user_data['username'];

        return true;
    } else {
        return false;
    }
}

// Handle login form submission
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login_user($username, $password)) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo 'Invalid username or password';
    }
}

?>


<?php
// Configuration
$database = array(
    'host' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'database' => 'your_database'
);

// Function to connect to database
function dbConnect() {
    $conn = new mysqli($database['host'], $database['username'], $database['password'], $database['database']);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to authenticate user
function authenticateUser($username, $password) {
    $conn = dbConnect();
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Function to get user data
function getUserData($username) {
    $conn = dbConnect();
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}


<?php
require_once 'auth.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (authenticateUser($username, $password)) {
        $userData = getUserData($username);
        // User is authenticated, redirect to protected page
        header('Location: protected.php');
        exit;
    } else {
        echo 'Invalid username or password.';
    }
}
?>

<form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <button type="submit">Login</button>
</form>


<?php
  $servername = "localhost";
  $username = "your_username";
  $password = "your_password";
  $dbname = "your_database";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    die("ERROR: " . $e->getMessage());
  }
?>


<?php
require_once 'db.php';

function login_user($email, $password) {
  // Sanitize input
  $email = htmlspecialchars($email);
  $password = htmlspecialchars($password);

  try {
    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // Fetch results
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
      return true; // Login successful
    } else {
      return false; // Incorrect email or password
    }
  } catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage();
  }
}
?>


<?php
require_once 'login.php';

$email = $_POST['email'];
$password = $_POST['password'];

if (isset($_POST['submit'])) {
  if (login_user($email, $password)) {
    echo "Login successful!";
  } else {
    echo "Incorrect email or password.";
  }
}
?>


// Define the database connection settings
$host = 'localhost';
$dbname = 'database_name';
$user = 'username';
$password = 'password';

// Connect to the database
function db_connect() {
    global $host, $dbname, $user, $password;
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        return $conn;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit();
    }
}

// Define the user login function
function user_login($username, $password) {
    // Connect to the database
    $conn = db_connect();

    // Prepare and execute the query
    $query = "SELECT * FROM users WHERE username=:username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Check if a matching user exists
    $user_data = $stmt->fetch();
    if ($user_data) {
        // Check the password (using plain text for simplicity)
        if ($password == $user_data['password']) {
            return true; // Login successful
        } else {
            return false; // Incorrect password
        }
    } else {
        return false; // No matching user found
    }

    // Close the database connection
    $conn = null;
}

// Example usage:
$username = 'exampleuser';
$password = 'examplepassword';

if (user_login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password";
}


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get POST data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query database for user
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, log them in
        session_start();
        $_SESSION['logged_in'] = true;
        echo 'Login successful!';
    } else {
        // User not found, display error message
        echo 'Invalid username or password.';
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    login_user();
}

?>


<?php
// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Connect to database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Check if user is logged in
if (isset($_SESSION['logged_in'])) {
  echo "You are already logged in.";
  exit;
}

// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Prepare SQL query to check user credentials
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
  $stmt->bindParam(":username", $username);
  $stmt->bindParam(":password", $password);

  try {
    // Execute SQL query
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
      // User credentials are valid, log them in
      $_SESSION['logged_in'] = true;
      $_SESSION['username'] = $username;

      echo "You have been logged in successfully.";
    } else {
      echo "Invalid username or password.";
    }
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

// Close database connection
$conn = null;
?>


<?php
require 'login.php';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>
  <h1>Login</h1>

  <?php if (isset($_SESSION['logged_in'])) : ?>
    <p>Welcome, <?= $_SESSION['username']; ?>!</p>
    <a href="logout.php">Logout</a>
  <?php else : ?>

  <form action="" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" value="Login">
  </form>

  <?php endif; ?>

</body>
</html>


/**
 * User Login Function
 *
 * Handles user login functionality.
 *
 * @param string $username  The username entered by the user.
 * @param string $password  The password entered by the user.
 *
 * @return array|bool      An array containing the user's data or false on failure.
 */
function loginUser($username, $password) {
    // Connect to the database (replace with your own connection code)
    include 'db.php';

    // Query the database for the user
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    // Check if the user exists and the password is correct
    if (mysqli_num_rows($result) > 0) {
        // Get the user's data from the result
        $user_data = mysqli_fetch_assoc($result);
        return $user_data;
    } else {
        // If the user does not exist or the password is incorrect, return false
        return false;
    }

    // Close the database connection (replace with your own connection code)
    include 'db_close.php';
}


// Get the username and password from the form submission
$username = $_POST['username'];
$password = $_POST['password'];

// Call the loginUser function
$user_data = loginUser($username, $password);

if ($user_data) {
    // If the user exists and the password is correct, log them in
    $_SESSION['user_id'] = $user_data['id'];
    header('Location: dashboard.php');
} else {
    // If the user does not exist or the password is incorrect, display an error message
    echo 'Invalid username or password';
}


function login($username, $password) {
    // Database connection settings
    $dbHost = 'localhost';
    $dbName = 'mydatabase';
    $user = 'root';
    $pass = '';

    // Create a new MySQLi object
    $conn = mysqli_connect($dbHost, $user, $pass, $dbName);

    if (!$conn) {
        die('Error connecting to database: ' . mysqli_error());
    }

    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query the database for user details
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // If a match is found, verify password using MD5 (or any other hashing algorithm)
        $row = mysqli_fetch_assoc($result);
        if ($password === md5($row['password'])) {
            return true; // User authenticated successfully
        } else {
            return false; // Password mismatch
        }
    } else {
        return false; // No user found with the given username
    }

    mysqli_close($conn); // Close database connection

}


// Set login credentials
$username = $_POST['username'];
$password = $_POST['password'];

// Call the login function and store result in a variable
$loginResult = login($username, $password);

if ($loginResult) {
    echo 'User authenticated successfully!';
} else {
    echo 'Invalid username or password';
}


<?php

// Configuration variables
$database_host = 'localhost';
$database_username = 'your_username';
$database_password = 'your_password';
$database_name = 'your_database';

// Connect to the database
function connect_to_database() {
  $conn = new mysqli($database_host, $database_username, $database_password, $database_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to check user credentials
function check_credentials($username, $password) {
  // Connect to the database
  $conn = connect_to_database();
  
  // SQL query to select user data
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    // Get user data from the database
    $row = $result->fetch_assoc();
    
    // Check password hash
    $password_hash = $row['password'];
    if (password_verify($password, $password_hash)) {
      // Login successful, return user data
      $user_data = array(
        'username' => $row['username'],
        'email' => $row['email']
      );
      return $user_data;
    }
  } else {
    // User not found or password incorrect
    return null;
  }
  
  // Close the database connection
  $conn->close();
}

// Function to login user
function login_user($username, $password) {
  // Check if username and password are provided
  if (empty($username) || empty($password)) {
    return array(
      'error' => 'Username or password is required'
    );
  }
  
  // Get user credentials from database
  $credentials = check_credentials($username, $password);
  
  if ($credentials !== null) {
    // Login successful, return user data and a token
    $token = bin2hex(random_bytes(32));
    $_SESSION['user_data'] = $credentials;
    $_SESSION['token'] = $token;
    
    // Return user data and token
    return array(
      'data' => $credentials,
      'token' => $token
    );
  } else {
    // Login failed, return an error message
    return array(
      'error' => 'Username or password is incorrect'
    );
  }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$login_result = login_user($username, $password);

if ($login_result['data']) {
  echo "Login successful!";
} else {
  echo "Error: " . $login_result['error'];
}
?>


<?php

// Configuration settings
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get user input from form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password for comparison
    $hashed_password = md5($password);

    // SQL query to select user data
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User credentials are valid, login successful!
        $_SESSION['username'] = $username;
        header('Location: /dashboard');
        exit();
    } else {
        // Invalid user credentials
        echo "Invalid username or password";
    }
}

if (isset($_POST['login'])) {
    login_user();
} else {
    // Display login form
    ?>
    <form action="" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" name="login" value="Login">
    </form>
    <?php
}

$conn->close();

?>


<?php

// Configuration
$DB_HOST = 'localhost';
$DB_USER = 'your_username';
$DB_PASSWORD = 'your_password';
$DB_NAME = 'your_database';

function login($username, $password) {
  // Establish database connection
  try {
    $conn = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL query to select user by username
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch user data if found
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check password (using SHA-256 for illustration purposes)
    if ($user && hash('sha256', $password) === $user['password']) {
      return true; // Login successful
    } else {
      return false; // Incorrect username or password
    }
  } catch (PDOException $e) {
    echo 'Error connecting to database: ' . $e->getMessage();
    return false;
  } finally {
    $conn = null;
  }
}

?>


<?php

// Define user input
$username = $_POST['username'];
$password = $_POST['password'];

// Call login function
if (login($username, $password)) {
  echo 'Login successful!';
} else {
  echo 'Invalid username or password.';
}

?>


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function login_user($username, $password) {
    // Hash the password for comparison
    $hashed_password = md5($password);

    // Query database to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username' AND password_hash = '$hashed_password'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Check user credentials
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login_user($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}

// Close database connection
$mysqli->close();

?>


<?php

// Configuration variables
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
function connectToDatabase() {
  $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
  return $conn;
}

// Check user credentials
function checkCredentials($username, $password) {
  // Prepare query
  $query = "SELECT * FROM users WHERE username = :username";
  
  // Execute query
  $stmt = connectToDatabase()->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  
  // Check if user exists and password is correct
  if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (password_verify($password, $row['password'])) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

// Login function
function login($username, $password) {
  // Check user credentials
  if (checkCredentials($username, $password)) {
    // If user is logged in, set a session variable to indicate success
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
    
    return true;
  } else {
    return false;
  }
}

?>


<?php

// Include the login function file
include('login.php');

// User input
$username = $_POST['username'];
$password = $_POST['password'];

// Login attempt
if (login($username, $password)) {
  echo "Welcome, $username!";
} else {
  echo "Invalid username or password.";
}

?>


function loginUser($username, $password) {
    // Database connection settings (modify to suit your database)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'your_username');
    define('DB_PASSWORD', 'your_password');
    define('DB_NAME', 'your_database');

    // Connect to the database
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");

    // Bind parameters
    $params = array(
        ':username' => $username,
        ':password' => hash('sha256', $password) // Hash the password for security
    );

    // Execute query and retrieve result
    try {
        $stmt->execute($params);
        $result = $stmt->fetch();

        if ($result !== false) {
            return true; // User credentials are valid
        } else {
            return false; // Invalid user credentials
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    } finally {
        $conn = null; // Close the database connection
    }
}


if (loginUser('username', 'password')) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


<?php
// Configuration
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check login credentials
function check_login($username, $password) {
    global $conn;
    // SQL query to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    // Check if result is not empty (i.e., user exists and credentials match)
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Function to get user data
function get_user_data($username) {
    global $conn;
    // SQL query to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    // Check if result is not empty (i.e., user exists)
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Handle login request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (check_login($username, $password)) {
        // If user exists and credentials match, get their data
        $user_data = get_user_data($username);

        // Set session variables
        $_SESSION['username'] = $username;
        $_SESSION['logged_in'] = true;

        // Redirect to protected page (e.g., dashboard)
        header("Location: /dashboard.php");
        exit();
    } else {
        // Display error message if credentials don't match
        echo "Invalid username or password.";
    }
}
?>


<?php
// Define the database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the function for user login
function login_user() {
    global $conn;

    // Get the username and password from the form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a query to select the user's hashed password from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute the query and store the result in a variable
    $result = $stmt->get_result();

    // If the user exists, hash their provided password to compare it with the stored hash
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $stored_hash = $row['password'];

            // Hash the provided password for comparison
            $hashed_password = md5($password);

            // Compare the hashed passwords (note: this is a simplified example and not recommended for production use)
            if ($hashed_password === $stored_hash) {
                return true; // User login successful
            } else {
                return false;
            }
        }
    }

    // If no match, user login failed
    return false;
}

// Handle the form submission (if it exists)
if (!empty($_POST['username']) && !empty($_POST['password'])) {
    if (login_user()) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}
?>


<?php
// Configuration
$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myuser';
$password = 'mypassword';

try {
    // Connect to database
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function loginUser($username, $password) {
        global $conn;

        // Prepare SQL query
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch user data
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            // Hashed password check
            if (password_verify($password, $userData['password'])) {
                return true; // Login successful
            } else {
                return false; // Incorrect password
            }
        } else {
            return false; // User not found
        }
    }

} catch (PDOException $e) {
    echo 'Error connecting to database: ' . $e->getMessage();
}
?>


// Assume we're calling this from another PHP file
require_once('login.php');

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        // Login successful, redirect to dashboard or protected page
        header('Location: dashboard.php');
        exit;
    } else {
        echo 'Invalid username or password';
    }
}


<?php
// Configuration
$database = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to database
try {
    $pdo = new PDO('mysql:host=localhost;dbname=' . $database, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

function login($email, $password) {
    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email AND password = :password');
    
    // Bind parameters
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    
    // Execute query
    $stmt->execute();
    
    // Fetch result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        // Login successful, return user data
        return $result;
    } else {
        // Login failed, return error message
        return 'Invalid email or password';
    }
}

// Handle form submission
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $user_data = login($email, $password);
    
    if ($user_data !== false) {
        // Login successful, redirect to dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        // Display error message
        echo 'Invalid email or password';
    }
}
?>


<?php include 'login.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form action="" method="post">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>


<?php
// Database settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Create a new PDO instance
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
?>


<?php
require_once 'config.php';

function login($username, $password) {
    // Prepare and execute the query to check if the username exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    // If no user is found, return false
    if (!$user) {
        return false;
    }

    // Hash the input password and compare it with the stored hashed password
    $hashPassword = hash('sha256', $password . $user['salt']);
    if ($hashPassword === $user['password']) {
        // If passwords match, create a new session for the user
        $_SESSION['username'] = $username;
        return true;
    } else {
        return false;
    }
}

// Example usage:
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}
?>


<?php
require_once 'config.php';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (login($username, $password)) {
        // Redirect to protected area or perform other actions
        header('Location: protected_area.php');
        exit;
    }
}

?>

<!-- Login form -->
<form action="" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit" name="login">Login</button>
</form>


// db_config.php

<?php

define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

?>


// models/User.php

<?php

class User {
    private $id;
    private $username;
    private $password;

    public function __construct($data) {
        $this->id = $data['id'];
        $this->username = $data['username'];
        $this->password = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    public static function findUserByUsername($username) {
        // Retrieve user data from database
        global $db;
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
}


// controllers/Login.php

<?php

class Login {
    public static function processLogin($username, $password) {
        // Retrieve user data from database
        $user = User::findUserByUsername($username);

        if ($user !== false && password_verify($password, $user['password'])) {
            // Successful login: store user data in session
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            return true;
        } else {
            // Invalid username or password
            return false;
        }
    }
}


// index.php (example usage)

<?php

require_once 'db_config.php';
require_once 'models/User.php';
require_once 'controllers/Login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (Login::processLogin($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}


<?php

// Define a configuration array
$config = [
    'dbHost' => 'localhost',
    'dbUsername' => 'your_username',
    'dbPassword' => 'your_password',
    'dbName' => 'your_database'
];

// Connect to the database
$conn = new mysqli($config['dbHost'], $config['dbUsername'], $config['dbPassword'], $config['dbName']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to hash a password using SHA-256
function passwordHasher($password) {
    return sha1(sha1($password));
}

// Function to check user login credentials
function loginUser($username, $password) {
    // Check if the username and password are not empty
    if (empty($username) || empty($password)) {
        return 'Please fill in both username and password';
    }

    // Hash the input password for comparison with the stored hash
    $hashedPassword = passwordHasher($password);

    // SQL query to retrieve user data from the database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashedPassword'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // If user is found, return their ID
            return $row['id'];
        }
    } else {
        // If no user found, return an error message
        return 'Invalid username or password';
    }

    // Close the database connection
    $conn->close();
}

// Call the login function with user input (username and password)
if (!empty($_POST['username']) && !empty($_POST['password'])) {
    echo loginUser($_POST['username'], $_POST['password']);
} else {
    echo 'Please fill in both username and password';
}
?>


<?php

// Define the database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a new PDO instance to connect to the database
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

function authenticate_user($username, $password) {
    // Prepare the SQL query to select the user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    
    // Execute the query and fetch the result
    if ($stmt->execute()) {
        $user_data = $stmt->fetch();
        
        // Check if a user exists with the provided credentials
        if ($user_data && password_verify($password, $user_data['password'])) {
            return true;
        }
    }
    
    return false;
}

function login_user($username, $password) {
    // Authenticate the user
    if (authenticate_user($username, $password)) {
        // Generate a session ID for the user
        $session_id = uniqid();
        
        // Insert the session ID into the sessions table
        $stmt = $pdo->prepare("INSERT INTO sessions (user_id, session_id) VALUES (:user_id, :session_id)");
        $stmt->bindParam(':user_id', $user_data['id']);
        $stmt->bindParam(':session_id', $session_id);
        
        if ($stmt->execute()) {
            // Set the session ID as a cookie
            setcookie('session_id', $session_id, time() + 3600); // expires in 1 hour
            
            // Return true to indicate that the login was successful
            return true;
        }
    } else {
        // Return false if the user is not authenticated or cannot be logged in
        return false;
    }
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $login_successful = login_user($username, $password);
    
    if ($login_successful) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}
?>


<?php
// Configuration
require_once 'config.php';

// Function to validate and log in user
function login($username, $password) {
    global $db;

    // Prepare statement for query
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);

    try {
        // Execute query
        $stmt->execute();

        // Fetch result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && password_verify($password, $result['password'])) {
            // User is authenticated, return user data
            return $result;
        } else {
            throw new Exception('Invalid username or password');
        }
    } catch (PDOException $e) {
        // Handle database error
        echo 'Database error: ' . $e->getMessage();
    }

    return null;
}
?>


<?php
// Database connection settings
$dsn = 'mysql:host=localhost;dbname=your_database';
$username = 'your_username';
$password = 'your_password';

try {
    // Establish database connection
    $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>


<?php
require_once 'login.php';

// User submits login form with username and password
$username = $_POST['username'];
$password = $_POST['password'];

// Log in user using `login` function
$userData = login($username, $password);

if ($userData) {
    // User is logged in, display success message or redirect to protected page
    echo 'You are now logged in!';
} else {
    // Display error message for invalid username or password
    echo 'Invalid username or password';
}
?>


<?php
/**
 * User Login Function
 *
 * This script checks if the provided username and password match with the stored credentials.
 *
 * @param string $username The username to check.
 * @param string $password The password to check.
 *
 * @return array|bool An array containing the user's data on success, or false on failure.
 */

function login($username, $password) {
  // Database connection settings
  $host = 'localhost';
  $db_name = 'database_name';
  $user = 'database_user';
  $pass = 'database_password';

  // Create a database connection
  try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL query to retrieve the user's data
    $stmt = $conn->prepare('SELECT * FROM users WHERE username=:username');
    $stmt->bindParam(':username', $username);

    // Execute the query and fetch the result
    $stmt->execute();
    $user_data = $stmt->fetch();

    // Check if a matching user exists
    if ($user_data !== false) {
      // Verify the password using hash
      if (password_verify($password, $user_data['password'])) {
        return $user_data; // Return the user's data on successful login
      } else {
        echo 'Invalid password';
      }
    } else {
      echo 'No matching user found';
    }

  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  } finally {
    if (isset($conn)) {
      $conn = null;
    }
  }
}
?>


// Call the login function with the username and password
$user_data = login('john_doe', 'my_secret_password');

if ($user_data !== false) {
  echo "Login successful! You are now logged in as: " . $user_data['username'];
} else {
  echo "Login failed!";
}


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate user credentials
function login_user($username, $password) {
    // SQL query to retrieve user data from the database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Retrieve user data from the result set
        $user_data = $result->fetch_assoc();

        // Verify password using SHA-256 hash
        $password_hash = sha1($password);
        if ($password_hash == $user_data['password']) {
            // Login successful, return user data
            return array('username' => $username, 'email' => $user_data['email'], 'role' => $user_data['role']);
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }

    return false;
}

// Handle POST request from the login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the $_POST array
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the login_user function to authenticate user credentials
    $user_data = login_user($username, $password);

    if ($user_data) {
        // Login successful, redirect to a protected page or dashboard
        header('Location: protected-page.php');
        exit;
    } else {
        // Login failed, display an error message
        echo "Login failed";
    }
}

?>


<?php
// Configuration settings
$hostname = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create a database connection
$conn = new mysqli($hostname, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user credentials
function check_credentials($username, $password) {
    // Query to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username'";

    // Execute the query
    $result = $conn->query($query);

    // Check if a match was found
    if ($result->num_rows > 0) {
        // Get the user's row from the result set
        $user_data = $result->fetch_assoc();

        // Verify the password (simple example: just compare the two strings)
        if (password_verify($password, $user_data['password'])) {
            return true; // credentials valid
        }
    }

    // Credentials invalid
    return false;
}

// Function to create a session for the user
function login_user($username) {
    // Retrieve the user's data from the database
    $query = "SELECT * FROM users WHERE username = '$username'";

    // Execute the query
    $result = $conn->query($query);

    // Check if a match was found
    if ($result->num_rows > 0) {
        // Get the user's row from the result set
        $user_data = $result->fetch_assoc();

        // Create a session for the user
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['role'] = $user_data['role'];

        return true; // login successful
    }

    // Login failed
    return false;
}

// Handle form submission
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (check_credentials($username, $password)) {
        login_user($username);
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid username or password";
    }
}

// Display the login form
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" name="login" value="Login">
</form>


<?php
// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user credentials
function login_user($username, $password) {
    global $conn;
    // SQL query to select the user from the database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    
    // Execute the query and store the result in an array
    $result = mysqli_query($conn, $sql);
    
    if ($result->num_rows > 0) {
        // If a user is found, retrieve their data
        while ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                // User credentials match; return the user's ID and username
                return array('user_id' => $row['id'], 'username' => $row['username']);
            }
        }
    }
    
    // If no user is found or passwords don't match, return null
    return null;
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $user_data = login_user($username, $password);
    
    if ($user_data !== null) {
        // User is logged in; store their ID and username as session variables
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user_data['user_id'];
        $_SESSION['username'] = $user_data['username'];
        
        // Redirect to a protected page (e.g. dashboard.php)
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid username or password.";
    }
}
?>


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to verify password
function verifyPassword($hashedPassword, $providedPassword) {
    return password_verify($providedPassword, $hashedPassword);
}

// Function to login user
function loginUser($username, $password) {
    // Prepare query
    $stmt = $conn->prepare("SELECT id, username, hashed_password FROM users WHERE username = ?");

    // Bind parameters
    $stmt->bind_param("s", $username);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch row
        $row = $result->fetch_assoc();

        // Verify password
        if (verifyPassword($row['hashed_password'], $password)) {
            return true;
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Username not found";
    }

    return false;
}

// Login user
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        // User logged in successfully
        echo "Welcome, " . $username;
    }
}

// Close connection
$conn->close();

?>


// Database connection settings
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
    // Prepare SQL query
    $sql_query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute SQL query
    $result = $conn->query($sql_query);

    if ($result->num_rows > 0) {
        // User found, log them in
        while ($row = $result->fetch_assoc()) {
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            return true; // user logged in successfully
        }
    } else {
        return false; // user not found or password incorrect
    }

    $conn->close();
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login_user($username, $password)) {
        echo "Logged in successfully!";
    } else {
        echo "Invalid username or password.";
    }
}


<?php

// Configuration
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database_name';

// Connect to database
$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

function loginUser() {
  global $conn;

  // Get form data
  $username = $_POST['username'];
  $password = sha1($_POST['password']);

  // Validate input
  if (empty($username) || empty($password)) {
    return 'Error: Both username and password are required.';
  }

  // Query database for user
  $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // User found, log them in
    while ($row = mysqli_fetch_assoc($result)) {
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      return 'Login successful.';
    }
  } else {
    // Incorrect login credentials
    return 'Invalid username or password. Please try again.';
  }

  mysqli_close($conn);
}

if (isset($_POST['login'])) {
  echo loginUser();
} else {
  // Display login form if not submitted
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="text" name="username" placeholder="Username">
  <input type="password" name="password" placeholder="Password">
  <button type="submit" name="login">Login</button>
</form>
<?php
}
?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function login($username, $password) {
    // Query to check user credentials
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute query
    $result = $mysqli->query($query);

    if ($result->num_rows == 1) {
        // If user is found, return true and session variables
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        return true;
    } else {
        // If user not found or password incorrect, return false
        return false;
    }
}

// Check if form has been submitted
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Use md5 for simplicity, consider using a more secure hashing function

    // Call login function and check if user is logged in successfully
    if (login($username, $password)) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo 'Invalid username or password';
    }
}

// Close connection
$mysqli->close();

?>


<?php

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    echo 'Welcome, ' . $_SESSION['username'] . '!';

} else {
    header('Location: index.html');
    exit();
}

?>


<?php

// Configuration variables
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
    global $conn;

    // Prepare query to select user data from the database
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if user exists and password is correct
    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Start a session
        session_start();

        // Set session variables
        $_SESSION['username'] = $user['username'];
        $_SESSION['logged_in'] = true;

        return true;
    } else {
        return false;
    }
}

// Check if login form has been submitted
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the login function
    if (login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}

?>


function userLogin($username, $password) {
    // Predefined array of users (replace with database query in production)
    $users = [
        'user1' => 'password123',
        'user2' => 'secret456'
    ];

    // Check if username exists in users array
    if (!isset($users[$username])) {
        return false; // Username does not exist
    }

    // Check if password matches the stored password
    $storedPassword = $users[$username];
    if ($password !== $storedPassword) {
        return false; // Password is incorrect
    }

    // Login successful, return true
    return true;
}


$username = 'user1';
$password = 'password123';

if (userLogin($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


<?php

// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Create a connection to the database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

?>


<?php

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Input validation
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Hash the password for comparison with stored hash
        $hashedPassword = hash('sha256', $password);

        try {
            // SQL query to retrieve user data from database
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            // Fetch and store user data
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userData) {
                // Compare hashed password with stored hash
                if ($hashedPassword === $userData['password']) {
                    $_SESSION['username'] = $username;
                    header('Location: dashboard.php');
                    exit();
                } else {
                    echo 'Incorrect password';
                }
            } else {
                echo 'User not found';
            }

        } catch (PDOException $e) {
            die('Database error: ' . $e->getMessage());
        }

    } else {
        echo 'Please enter both username and password';
    }

} else {
    // Display login form
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br><br>
        Password: <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>

    <?php
}
?>


<?php
require 'config.php'; // Database connection configuration file

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Get user input data from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Input validation
    if (empty($username) || empty($password)) {
        echo "Both username and password are required.";
    } else {
        // Hash the password for verification
        $hashedPassword = hash('sha256', $password);

        // Query to retrieve user from database
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            // Retrieve the hashed password from the query result
            while ($row = mysqli_fetch_assoc($result)) {
                $storedHashedPassword = $row['password'];

                // Verify the input password with the stored hash
                if ($hashedPassword == $storedHashedPassword) {
                    // Login successful, proceed to authenticated area
                    header('Location: dashboard.php');
                    exit;
                } else {
                    echo "Invalid username or password.";
                }
            }
        } else {
            echo "Invalid username or password.";
        }
    }
}
?>

<!-- Display form for user login -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <button type="submit" name="submit">Login</button>
</form>


<?php
// Database connection configuration file

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
// Define the database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the login function
function login_user($username, $password) {
    // Prepare the SQL query to check for the user's existence and password match
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    
    // Bind the parameters
    $stmt->bind_param("ss", $username, md5($password));
    
    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    // Check if a user exists with the provided credentials
    if ($result->num_rows > 0) {
        // Return true to indicate a successful login
        return true;
    } else {
        // Return false to indicate an unsuccessful login
        return false;
    }
}

// Define the function to insert a new user into the database (optional)
function create_user($username, $password) {
    // Prepare the SQL query to insert a new user
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    
    // Bind the parameters
    $stmt->bind_param("ss", $username, md5($password));
    
    // Execute the query
    $stmt->execute();
}

// Check if the login button has been clicked
if (isset($_POST['login'])) {
    // Get the username and password from the form submission
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Call the login function to check for a successful login
    if (login_user($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}

// Close the database connection
$conn->close();
?>


// config.php (store database connection settings)
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

// functions.php (login function)
<?php

function login($username, $password) {
  // Connect to the database
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if (!$conn) {
    die("Connection failed: " . mysqli_error($conn));
  }

  // Prepare SQL query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = mysqli_prepare($conn, $query);

  // Bind parameters
  mysqli_stmt_bind_param($stmt, 'ss', $username, $password);

  // Execute query
  if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
      // Login successful, return user data
      while ($row = mysqli_fetch_assoc($result)) {
        return array(
          'id' => $row['id'],
          'username' => $row['username']
        );
      }
    } else {
      // Incorrect username or password
      return false;
    }
  } else {
    // Query execution failed
    return false;
  }

  // Close the database connection
  mysqli_close($conn);
}

?>


<?php

// Include functions.php
require 'functions.php';

// Get user input
$username = $_POST['username'];
$password = $_POST['password'];

// Login user
$userData = login($username, $password);

if ($userData) {
  // Login successful, redirect to dashboard or home page
  header('Location: dashboard.php');
} else {
  // Login failed, display error message
  echo 'Invalid username or password.';
}
?>


<?php

// Configuration settings
define('DB_HOST', 'your_database_host');
define('DB_USERNAME', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user credentials
function login_user($username, $password) {
    // Prepare SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    
    // Bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute query
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        // Check if user exists and password is correct
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// Login function
function login($username, $password) {
    // Hash the password (using a secure hash algorithm like bcrypt or SHA-256)
    $hashed_password = hash('sha256', $password);
    
    // Call login_user function
    if (login_user($username, $hashed_password)) {
        return true;
    } else {
        return false;
    }
}

// Check if user is already logged in
function check_login() {
    // Get session data
    $session_data = $_SESSION['user'];
    
    // If no session data, return false
    if (!isset($session_data)) {
        return false;
    } else {
        return true;
    }
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Login user
    if (login($username, $password)) {
        echo "User logged in successfully!";
        
        // Set session data
        $_SESSION['user'] = array('username' => $username);
        
        // Redirect to secure page
        header("Location: secured_page.php");
        exit;
    } else {
        echo "Invalid username or password.";
    }
}

// Close connection
$conn->close();

?>


$users = [
    ["username" => "admin", "email" => "admin@example.com", "password_hash" => hash('sha256', 'password')],
];


<?php
// Configuration for hashing passwords
define('PASSWORD_SALT', 'your_secret_salt'); // Change this to a secure secret key

function hashPassword($password) {
    return hash('sha256', $password . PASSWORD_SALT);
}

function validateCredentials($username, $password) {
    global $users;
    
    foreach ($users as $user) {
        if ($user['username'] == $username && hashPassword($password) === $user['password_hash']) {
            return $user; // Successful login
        }
    }
    
    return null; // Login failed
}

function loginUser($username, $password) {
    global $users;
    
    // Validate input
    if (empty($username) || empty($password)) {
        throw new Exception('Username and password are required');
    }
    
    $user = validateCredentials($username, $password);
    
    if ($user !== null) {
        // Login successful: Return user data or perform further actions as needed.
        return ['success' => true];
    } else {
        // Login failed
        throw new Exception('Invalid username or password');
    }
}

try {
    $loginData = loginUser($_POST['username'], $_POST['password']);
    echo json_encode($loginData);
} catch (Exception $e) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Login failed: ' . $e->getMessage()]);
}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
<form action="" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Login</button>
</form>

<script src="script.js"></script>
</body>
</html>


<?php
/**
 * User Login Function
 *
 * @param string $username Username to log in with
 * @param string $password Password to log in with
 *
 * @return bool True if login is successful, False otherwise
 */
function loginUser($username, $password) {
  // Connect to database (assuming MySQL)
  $db = new mysqli("localhost", "username", "password", "database_name");

  // Check connection
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  // Prepare and execute query to get user from database
  $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  // Get user data from result
  $user = $result->fetch_assoc();

  // Check if user exists and password is correct
  if ($user && password_verify($password, $user['password'])) {
    // Login successful, return true
    return true;
  } else {
    // Login failed, return false
    return false;
  }

  // Close database connection
  $db->close();
}

// Example usage:
$username = "example_username";
$password = "example_password";

if (loginUser($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}
?>


<?php
// Configuration settings
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection to database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate user login credentials
function validateLogin($email, $password) {
    global $conn;
    
    // Query database for user with matching email and password
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);
    
    // Check if query returned any results
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Function to login user and set session variables
function loginUser($email, $password) {
    global $conn;
    
    // Validate login credentials
    if (validateLogin($email, $password)) {
        // Query database for user data
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);
        
        // Get user data from result set
        $row = $result->fetch_assoc();
        
        // Set session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        
        return true;
    } else {
        return false;
    }
}

// Check for POST request and login user if credentials are valid
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Login user
    if (loginUser($email, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid email or password.";
    }
}
?>


/**
 * User login function
 *
 * @param string $username
 * @param string $password
 * @return bool True if the user is authenticated, false otherwise
 */
function authenticateUser($username, $password) {
    // Connect to database
    $db = new mysqli('localhost', 'your_username', 'your_password', 'your_database');

    // Check connection
    if ($db->connect_error) {
        echo "Error connecting to database: " . $db->connect_error;
        return false;
    }

    // Prepare SQL query
    $sql = "SELECT * FROM users WHERE username = ?";

    // Bind parameters
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $username);

    // Execute query
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows == 1) {
            // Fetch user data
            $user = $result->fetch_assoc();

            // Verify password using password_verify function
            if (password_verify($password, $user['password'])) {
                return true;
            }
        }
    }

    // Close database connection and return false
    $db->close();
    return false;
}


// Set username and password variables
$username = "john_doe";
$password = "my_secret_password";

// Call the authenticateUser function
$authenticated = authenticateUser($username, $password);

if ($authenticated) {
    echo "User authenticated successfully!";
} else {
    echo "Authentication failed.";
}


function user_login($username, $password) {
    // Connect to database
    require 'db_config.php'; // Your database configuration file
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare query
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");

    // Bind parameters
    $stmt->bind_param("s", $username);

    // Execute query
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Fetch user data
        $user_data = $result->fetch_assoc();

        // Check password
        if (password_verify($password, $user_data['password'])) {
            return array(
                'id' => $user_data['id'],
                'username' => $user_data['username']
            );
        } else {
            echo "Incorrect password";
            return false;
        }
    } else {
        echo "Query failed";
        return false;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    return false;
}


$username = 'your_username';
$password = 'your_password';

$user_data = user_login($username, $password);

if ($user_data) {
    echo "User logged in successfully";
} else {
    echo "Login failed";
}


<?php

// Config file for database connection
require_once 'config.php';

function login_user($username, $password) {
    // Check if username or password is empty
    if (empty($username) || empty($password)) {
        return array('error' => 'Both username and password are required.');
    }

    // Prepare SQL query to get user data from database
    $query = "SELECT * FROM users WHERE username = :username";
    try {
        // Execute query with prepared statement
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Check if user exists
        if ($stmt->rowCount() == 0) {
            return array('error' => 'Invalid username or password.');
        }

        // Get user data from database
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Hashed password provided by user, hash it and compare with stored one in DB
        if (password_verify($password, $user_data['password'])) {
            return array('success' => true, 'username' => $user_data['username']);
        } else {
            return array('error' => 'Invalid username or password.');
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo "Error logging in: " . $e->getMessage();
        exit;
    }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = login_user($username, $password);

if ($result['success']) {
    // Login successful!
} elseif (!empty($result['error'])) {
    echo "Error: " . $result['error'];
}
?>


// Create user in database
$username = 'example';
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$query = "INSERT INTO users (username, password) VALUES (:username, :password)";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $password);
$stmt->execute();


// db.php (database connection)
<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


// login.php (user login script)
<?php
require_once 'db.php';

function user_login() {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Password hashing using SHA-256 and SHA-1 for comparison with stored hash.
        $hashed_password = sha1($password);

        $sql = "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'";
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['status'] == 1 && $row['role'] == 'user') {
                    session_start();
                    $_SESSION['username'] = $username;
                    $_SESSION['logged_in'] = true;

                    echo "Login Success!";
                    header('Location: dashboard.php');
                } else {
                    echo "Access denied. User status is not active or role is not user.";
                }
            }
        } else {
            echo "Invalid username and password";
        }

        mysqli_close($conn);
    }
}
?>


// login.html (example login form)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="login-form">
    <input type="text" name="username" placeholder="Username"><br><br>
    <input type="password" name="password" placeholder="Password"><br><br>
    <button type="submit">Login</button>
</form>

<?php
require_once 'db.php';
user_login();
?>
</body>
</html>


<?php
// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
    // Hash password for comparison (using bcrypt)
    $hashed_password = hash('sha256', $password);

    // Query database to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Retrieve the row from the result
        $row = $result->fetch_assoc();

        // Compare hashed password with stored hash
        if (hash('sha256', $row['password']) == $hashed_password) {
            // User authenticated successfully
            return true;
        } else {
            echo "Incorrect password";
            return false;
        }
    } else {
        echo "User not found";
        return false;
    }

    // Close database connection
    $conn->close();
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login_user($username, $password)) {
        echo "Login successful!";
        // Redirect user to dashboard or other secure page
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Login failed";
    }
}

?>


<?php

// Configuration settings
$config = array(
  'database' => 'your_database_name',
  'username' => 'your_database_username',
  'password' => 'your_database_password'
);

// Connect to database
function connectToDatabase() {
  $connection = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
  if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
  }
  return $connection;
}

// Login function
function login($username, $password) {
  // Connect to database
  $conn = connectToDatabase();
  
  // SQL query to select user from database where username and password match
  $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  
  // Execute the query
  $result = $conn->query($query);
  
  // If result is true, then login is successful
  if ($result->num_rows > 0) {
    return true;
  } else {
    return false;
  }
}

// Check for user input on submit of login form
if (isset($_POST['username']) && isset($_POST['password'])) {
  
  // Assign user input to variables
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  // Validate user input
  if (empty($username) || empty($password)) {
    echo "Please enter both username and password.";
  } else {
    // Check for successful login
    $loginStatus = login($username, $password);
    
    if ($loginStatus == true) {
      // Login is successful
      session_start();
      $_SESSION['logged_in'] = true;
      $_SESSION['username'] = $username;
      header("Location: index.php");
      exit;
    } else {
      echo "Invalid username or password.";
    }
  }
}

?>


<?php

function loginUser($username, $password) {
  // Database connection settings
  $host = 'localhost';
  $db_name = 'mydatabase';
  $user = 'myuser';
  $pass = 'mypass';

  try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL query to retrieve user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");

    // Bind parameters
    $stmt->bindParam(':username', $username);

    // Execute the query
    $stmt->execute();

    // Fetch user data
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_data && password_verify($password, $user_data['password'])) {
      return true; // Login successful
    } else {
      return false; // Invalid credentials
    }

  } catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
    return false;
  }
}

?>


<?php

// Get user input from login form
$username = $_POST['username'];
$password = $_POST['password'];

// Call the loginUser function
if (loginUser($username, $password)) {
  echo 'Login successful!';
} else {
  echo 'Invalid username or password';
}

?>


<?php

// Database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;

    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare query to select user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch user data
    $user_data = $result->fetch_assoc();

    // Check if user exists and password matches
    if ($user_data && password_verify($password, $user_data['password'])) {
        // Login successful, set session variables
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['logged_in'] = true;
        return true;
    } else {
        // Login failed, display error message
        echo "Invalid username or password";
        return false;
    }
}

// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    login_user();
} else {
    // Display login form
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
}

// Close database connection
$conn->close();

?>


<?php

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function dbConnect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function userLogin($username, $password) {
    // Connect to the database
    $conn = dbConnect();

    // SQL query to select the user from the database
    $sql = "SELECT * FROM users WHERE username = '$username'";

    // Execute the query and get the result
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row['password'])) {
            return array(
                'success' => true,
                'username' => $row['username']
            );
        } else {
            return array('success' => false, 'error' => 'Invalid password');
        }
    } else {
        return array('success' => false, 'error' => 'User not found');
    }

    // Close the database connection
    $conn->close();
}

?>


// Define the username and password
$username = $_POST['username'];
$password = $_POST['password'];

// Call the userLogin function
$result = userLogin($username, $password);

// Check if the login was successful
if ($result['success']) {
    echo "Welcome, " . $result['username'] . "! You have successfully logged in.";
} else {
    echo "Error: " . $result['error'];
}


<?php
require 'config.php'; // database connection settings

// Check if the form has been submitted
if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Validate input
  if (empty($username) || empty($password)) {
    die('Please fill in all fields');
  }

  // Hash password for comparison
  $hashed_password = hash('sha256', $password);

  // Query database to check user credentials
  $query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) == 1) {
    // User authenticated successfully
    $_SESSION['user_id'] = $result->fetch_assoc()['id'];
    header('Location: dashboard.php');
    exit();
  } else {
    die('Invalid username or password');
  }
}

// Display login form if not submitted
?>
<form method="post">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username"><br><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password"><br><br>
  <button type="submit" name="submit">Login</button>
</form>


<?php
// Database connection settings
$host = 'localhost';
$db_name = 'mydatabase';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function login($username, $password) {
    // Validate input
    if (empty($username)) {
        return 'Username cannot be empty';
    }
    if (empty($password)) {
        return 'Password cannot be empty';
    }

    // Query database for user
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);

    if ($result->num_rows == 1) {
        $user_data = $result->fetch_assoc();

        // Hashed password comparison (for secure login)
        if (password_verify($password, $user_data['password'])) {
            return true;
        } else {
            return 'Invalid username or password';
        }
    } else {
        return 'Username not found';
    }

    // Close database connection
    $mysqli->close();
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = login($username, $password);

if ($result === true) {
    echo "Logged in successfully!";
} else {
    echo $result;
}

?>


function login($username, $password) {
    // Define the users array
    $users = [
        "user1" => ["password" => "hashed_password1"],
        "user2" => ["password" => "hashed_password2"],
        // Add more users here...
    ];

    // Check if username exists in users array
    if (array_key_exists($username, $users)) {
        // Compare input password with hashed password
        return password_verify($password, $users[$username]["password"]);
    }

    // If username not found or passwords do not match
    return false;
}


function register($username, $email, $password) {
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Add new user to users array
    $users[$username] = ["email" => $email, "password" => $hashedPassword];
}


// Register a new user
register("new_user", "user@example.com", "password123");

// Attempt to login with the new user's credentials
if (login("new_user", "password123")) {
    echo "Login successful!";
} else {
    echo "Invalid username or password";
}


<?php

// Database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a PDO object for database interaction
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
} catch (PDOException $e) {
    echo "Error: Could not connect to the database.";
    exit;
}

function login($username, $password) {
    global $pdo;

    // Prepare and execute a query to retrieve the user's data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the result
    $user_data = $stmt->fetch();

    if ($user_data) {
        // Check the password
        if (password_verify($password, $user_data['password'])) {
            return true;
        } else {
            echo "Incorrect password.";
            return false;
        }
    } else {
        echo "Username not found.";
        return false;
    }

    // If we reach this point, it means something went wrong
    echo "An error occurred during login.";
    return false;
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login($username, $password)) {
        // Login successful! Redirect to a protected page.
        header('Location: protected-page.php');
        exit;
    }
}

?>


<form action="login.php" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Login</button>
</form>


<?php

// Configuration settings
$databaseHost = 'localhost';
$databaseUsername = 'your_username';
$databasePassword = 'your_password';
$databaseName = 'your_database_name';

// Connect to database
$conn = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
    // Hash password for comparison
    $hashed_password = hash('sha256', $password);

    // Query database to retrieve user data
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if ($hashed_password == $row['password']) {
                // User authenticated, return true
                return true;
            }
        }
    }

    // User not found or password incorrect
    return false;
}

// Example usage:
$username = 'example_user';
$password = 'example_password';

if (login_user($username, $password)) {
    echo "User logged in successfully!";
} else {
    echo "Invalid username or password.";
}

$conn->close();

?>


function login_user($username, $password) {
    // Hash password using password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query database to retrieve user data
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if (password_verify($hashed_password, $row['password'])) {
                // User authenticated, return true
                return true;
            }
        }
    }

    // User not found or password incorrect
    return false;
}


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user credentials
function login_user($username, $password) {
    global $conn;
    
    // SQL query to select user data
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute query and get result
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // User found, return true
        return true;
    } else {
        // User not found, return false
        return false;
    }
}

// Function to login user and create session
function do_login($username, $password) {
    global $conn;
    
    // Check if user credentials are valid
    if (login_user($username, $password)) {
        // Get user data from database
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        
        // Fetch user data as an array
        $user_data = $result->fetch_assoc();
        
        // Create session variables
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['email'] = $user_data['email'];
        $_SESSION['role'] = $user_data['role'];
        
        // Redirect to protected page
        header('Location: protected_page.php');
        exit();
    } else {
        // Invalid credentials, display error message
        echo "Invalid username or password";
    }
}

?>


<?php
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login_form.html');
    exit();
}

// Display protected page content
echo "Welcome, " . $_SESSION['username'] . "!";

?>


$users = [
    'user1' => ['password' => 'pass123', 'role' => 'admin'],
    'user2' => ['password' => 'pass456', 'role' => 'user']
];


function login($username, $password) {
    global $users;
    
    if (isset($users[$username])) {
        $storedPassword = $users[$username]['password'];
        $storedRole = $users[$username]['role'];
        
        // Check if the provided password matches the stored one
        if (hash('sha256', $password) === hash('sha256', $storedPassword)) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $storedRole;
            
            return true;
        } else {
            echo "Invalid password";
            return false;
        }
    } else {
        echo "User not found";
        return false;
    }
}


// Set the users array
$users = [
    'user1' => ['password' => 'pass123', 'role' => 'admin'],
    'user2' => ['password' => 'pass456', 'role' => 'user']
];

// Initialize session
session_start();

// User login attempt
$username = 'user1';
$password = 'pass123';

if (login($username, $password)) {
    echo "Login successful";
} else {
    echo "Login failed";
}


<?php
/**
 * User Login Function
 *
 * @param string $username The username entered by the user.
 * @param string $password  The password entered by the user.
 *
 * @return int|false Session ID if login is successful, false otherwise.
 */
function login($username, $password) {
    // Database connection settings (replace with your own)
    $host = 'localhost';
    $db_name = 'users';
    $user = 'root';
    $pass = '';

    try {
        // Connect to the database
        $conn = new PDO('mysql:host=' . $host . ';dbname=' . $db_name, $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query the database for the user's record
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Get the user's password from the database
        $user_record = $stmt->fetch();
        if ($user_record) {
            // Check if the password matches
            if (password_verify($password, $user_record['password'])) {
                // Create a session ID and store it in the session
                $_SESSION['session_id'] = uniqid();
                return $_SESSION['session_id'];
            } else {
                throw new Exception('Incorrect password');
            }
        } else {
            throw new Exception('User not found');
        }

    } catch (PDOException $e) {
        // Handle any database-related errors
        echo 'Error connecting to the database: ' . $e->getMessage();
    } catch (Exception $e) {
        // Handle login-specific errors
        echo 'Login failed: ' . $e->getMessage();
    }
}


// Set up session variables if not already set
if (!isset($_SESSION)) {
    session_start();
}

// Check if the user has submitted the form with username and password
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the values from the form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the login function with the provided credentials
    $session_id = login($username, $password);
    if ($session_id !== false) {
        echo 'Login successful!';
        // You can now access user-specific content or perform actions based on their session ID
    } else {
        echo 'Invalid username or password';
    }
}


function login($username, $password) {
  // Connect to database (for production environments, use a more secure connection method)
  $conn = new mysqli("localhost", "username", "password", "database");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare SQL query
  $sql = "SELECT * FROM users WHERE username = ?";

  // Bind parameters to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);

  // Execute query and retrieve user data
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      if (password_verify($password, $row['password'])) {
        return array(
          'success' => true,
          'username' => $row['username'],
          'user_id' => $row['id']
        );
      }
    }
  }

  // If user not found or password is incorrect
  return array('success' => false);
}

// Example usage:
$username = $_POST["username"];
$password = $_POST["password"];

$result = login($username, $password);

if ($result['success']) {
  echo "Logged in successfully!";
} else {
  echo "Invalid username or password.";
}


<?php
// Database connection settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get user input from form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to check if username and password match
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    // Check if user exists and password is correct
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            session_start();
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $row['username'];
            header("Location: dashboard.php");
            exit;
        }
    } else {
        echo "Invalid username or password";
    }
}

// Check if form has been submitted
if (isset($_POST['submit'])) {
    login_user();
} else {
    // Display login form
    ?>
    <form action="" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" name="submit" value="Login">
    </form>
    <?php
}
?>


<?php
// Configuration
$database = 'mysql';
$username = 'root';
$password = '';
$dbname = 'users';

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash password for comparison
    $hashed_password = hash('sha256', $password);

    // Query database to check if username and password match
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful, redirect user to dashboard
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Invalid username or password";
    }
} else {
    // Display login form
    ?>
    <form action="" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" name="submit" value="Login">
    </form>
    <?php
}

// Close connection
$conn->close();
?>


<?php
// Configuration settings
$database_host = 'localhost';
$database_name = 'mydatabase';
$database_username = 'root';
$database_password = '';

// Create connection to database
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set up form variables
$username = $_POST['username'];
$password = $_POST['password'];

// Prevent SQL injection by escaping input
$username = mysqli_real_escape_string($conn, $username);
$password = mysqli_real_escape_string($conn, $password);

// Query database to check if user exists and password is correct
$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($query);

// If query was successful...
if ($result->num_rows > 0) {
    // User exists and password is correct, log them in!
    session_start();
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;

    // Redirect to protected page
    header('Location: protected.php');
    exit();
} else {
    // Incorrect username or password, display error message
    echo 'Incorrect username or password';
}

// Close database connection
$conn->close();

?>


<?php
// Check if user is logged in
if (!isset($_SESSION['loggedin'])) {
    // If not, redirect to login page
    header('Location: login.php');
    exit();
}

// Display protected content here...
echo 'Welcome, ' . $_SESSION['username'] . '!';

?>


<?php
/**
 * User Login Function
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool Whether the login was successful.
 */
function login($username, $password) {
    // Database connection settings
    $dbHost = 'localhost';
    $dbName = 'your_database_name';
    $user = 'your_database_user';
    $pass = 'your_database_password';

    try {
        // Connect to database
        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $user, $pass);

        // Check if username exists in database
        $stmt = $conn->prepare('SELECT * FROM users WHERE username=:username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch user data from database
        $userData = $stmt->fetch();

        // If no user found, return false
        if (!$userData) {
            return false;
        }

        // Verify password using password_hash and password_verify functions
        if (password_verify($password, $userData['password'])) {
            // Login successful, return true with user data
            return array('success' => true, 'user_data' => $userData);
        } else {
            // Incorrect password, return false
            return false;
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


$username = 'example_user';
$password = 'example_password';

$result = login($username, $password);

if ($result) {
    echo "Login successful!";
    print_r($result['user_data']);
} else {
    echo "Incorrect username or password.";
}


<?php
// Connect to database
$conn = mysqli_connect("localhost", "username", "password", "database");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function login_user($email, $password) {
    global $conn;

    // Escape input to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Query the database for the user's credentials
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Check if the entered password matches the stored one
            if (password_verify($password, $row['password'])) {
                return true; // Login successful
            }
        }
    }

    return false; // Login failed
}

// Example usage:
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (login_user($email, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid email or password";
    }
}
?>


<?php
/**
 * User Login Function
 *
 * @param string $username
 * @param string $password
 * @return bool|bool[]
 */
function login($username, $password) {
  // Connect to database
  require_once 'db.php';
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Check connection
  if ($mysqli->connect_errno) {
    echo "Failed to connect: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
  }

  // Prepare query
  $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);

  // Execute query
  if (!$stmt->execute()) {
    echo "Execution failed: (" . $mysqli->errno . ") " . $mysqli->error;
    exit();
  }

  // Get result
  $result = $stmt->get_result();

  // Check password
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      if (password_verify($password, $row['password'])) {
        // Password matches, return true and user data
        return [true, $row];
      }
    }
  }

  // Login failed, return false
  return false;
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = login($username, $password);

if ($result) {
  echo "Logged in successfully!";
} else {
  echo "Invalid username or password.";
}


<?php
/**
 * Database Configuration
 */
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');


<?php

// Database configuration
$dbhost = 'localhost';
$dbname = 'database_name';
$dbusername = 'db_username';
$dbpassword = 'db_password';

function connect_to_database() {
    // Connect to database
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);
    return $conn;
}

// Function to check user credentials
function login_user($username, $password) {
    try {
        // Connect to database
        $conn = connect_to_database();
        
        // SQL query
        $query = "SELECT * FROM users WHERE username = :username AND password = :password";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', sha1($password));
        $stmt->execute();
        
        // Get the user data
        $user_data = $stmt->fetch();
        
        if ($user_data) {
            // If username and password are correct, return true
            return true;
        } else {
            // If username or password is incorrect, return false
            return false;
        }
    
    } catch (PDOException $e) {
        // Catch PDO exception
        echo 'Error: ' . $e->getMessage();
    }
}

// Example usage:
if (isset($_POST['submit'])) {
    // Get the user input
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Call the login_user function
    if (login_user($username, $password)) {
        echo 'Login successful!';
    } else {
        echo 'Invalid username or password';
    }
}
?>


<?php

// Database configuration
$dbhost = 'localhost';
$dbname = 'database_name';
$dbusername = 'db_username';
$dbpassword = 'db_password';

function connect_to_database() {
    // Connect to database
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);
    return $conn;
}

// Function to check user credentials
function login_user($username, $password) {
    try {
        // Connect to database
        $conn = connect_to_database();
        
        // SQL query
        $query = "SELECT * FROM users WHERE username = :username AND password = :password";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $hashed_password = hash('sha256', $password);  // Use SHA-256 for password hashing
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
        
        // Get the user data
        $user_data = $stmt->fetch();
        
        if ($user_data) {
            // If username and password are correct, return true
            return true;
        } else {
            // If username or password is incorrect, return false
            return false;
        }
    
    } catch (PDOException $e) {
        // Catch PDO exception
        echo 'Error: ' . $e->getMessage();
    }
}

// Function to register user
function register_user($username, $password) {
    try {
        // Connect to database
        $conn = connect_to_database();
        
        // SQL query
        $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $conn->prepare($query);
        $hashed_password = hash('sha256', $password);  // Use SHA-256 for password hashing
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
        
    } catch (PDOException $e) {
        // Catch PDO exception
        echo 'Error: ' . $e->getMessage();
    }
}

// Example usage:
if (isset($_POST['submit'])) {
    if ($_POST['action'] == 'login') {
        // Call the login_user function
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if (login_user($username, $password)) {
            echo 'Login successful!';
        } else {
            echo 'Invalid username or password';
        }
    } elseif ($_POST['action'] == 'register') {
        // Call the register_user function
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if (register_user($username, $password)) {
            echo 'Registration successful!';
        } else {
            echo 'Error registering user';
        }
    }
}
?>


// Function to register user
function register_user($username, $password) {
    try {
        // Connect to database
        $conn = connect_to_database();
        
        // SQL query
        $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $conn->prepare($query);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Use password hashing library
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
        
    } catch (PDOException $e) {
        // Catch PDO exception
        echo 'Error: ' . $e->getMessage();
    }
}


<?php

// Configuration settings
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to connect to database
function dbConnect() {
    global $conn;
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}

// Function to validate user input
function validateInput($data) {
    // Remove whitespace and sanitize input
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    
    return $data;
}

// Function to hash password
function hashPassword($password) {
    $salt = 'your_salt'; // Use a secure salt
    return crypt($password, '$2y$10$' . $salt);
}

// User login function
function loginUser($username, $password) {
    global $conn;
    
    // Validate input
    $username = validateInput($username);
    $password = validateInput($password);
    
    // Query database for user
    $query = "SELECT * FROM users WHERE username = '$username'";
    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            // Check password hash
            if (hashPassword($password) === $row['password']) {
                // Login successful, return user data
                return array(
                    'username' => $row['username'],
                    'email' => $row['email']
                );
            } else {
                // Password incorrect
                return null;
            }
        }
    } else {
        // Database error
        return null;
    }
}

?>


require_once 'login.php';

// User credentials
$username = 'example_user';
$password = 'secret_password';

$userData = loginUser($username, $password);

if ($userData) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


<?php

// Define the database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a new PDO object to connect to the database
$dsn = "mysql:host=$db_host;dbname=$db_name";
$db = new PDO($dsn, $db_username, $db_password);

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password (using SHA-256 for this example)
    $hashed_password = hash('sha256', $password);

    // Query the database to get the user's details
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user_data = $stmt->fetch();

    // Check if a user was found and if their password matches
    if ($user_data && $hashed_password == $user_data['password']) {
        // Log the user in
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;

        // Redirect to a protected page (e.g. dashboard.php)
        header('Location: dashboard.php');
        exit();
    } else {
        // Display an error message if login fails
        echo 'Invalid username or password';
    }
}

?>


<?php

// Include the login function
require_once 'login.php';

?>

<!-- Create a simple form for users to enter their credentials -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>

<?php
// If the user is already logged in, display a message
if (isset($_SESSION['logged_in'])) {
    echo 'You are now logged in as ' . $_SESSION['username'];
}
?>


<?php

// Define the database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Check if POST request has been made
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the username and password from the form
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query to check user credentials
        $query = "SELECT * FROM users WHERE username='$username' AND password=MD5('$password')";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Get the row of data from the result set
            $row = $result->fetch_assoc();

            // Create a session for the user
            $_SESSION['username'] = $row['username'];
            $_SESSION['logged_in'] = true;

            return true;
        } else {
            return false;
        }
    } else {
        return false; // If not a POST request, don't proceed
    }
}

function logout_user() {
    session_destroy();
}

// Call the login function if the user submits the form
if (isset($_POST['login'])) {
    $result = login_user();

    if ($result) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password";
    }
} ?>


<?php

// Include the users.php file
include 'users.php';

?>

<!-- Login form -->
<form action="" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit" name="login">Login</button>
</form>

<?php
// Check if the user is logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    echo "You are currently logged in as " . $_SESSION['username'];
} else {
    // Display a message to login or register
    echo "Please login or register to access this page";
}
?>


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
    // Establish database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Function to hash password (optional)
    function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Function to verify user credentials
    function login($username, $password) {
        // Query database for user with matching username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $user = $stmt->fetch();

        // Check if user exists and password is correct
        if ($user && password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }

    // Example usage: login a user
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (login($username, $password)) {
            echo "Login successful!";
        } else {
            echo "Invalid username or password.";
        }
    }

} catch(PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}

?>


<?php

// Configuration variables
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

function login() {
    global $conn;
    
    // Form data
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // SQL query to select user data from database
        $sql = "SELECT * FROM users WHERE username = '$username'";
        
        try {
            // Execute the query
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Check password hash
                    if (password_verify($password, $row['password'])) {
                        // Successful login!
                        $_SESSION['username'] = $username;
                        header('Location: dashboard.php');
                        exit();
                    } else {
                        echo 'Incorrect password';
                    }
                }
            } else {
                echo 'User not found';
            }
        } catch (Exception $e) {
            die('Error executing SQL query: ' . $e->getMessage());
        }
    }

    // No user input provided
    echo 'No username or password provided';
}

// Run the login function if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    login();
} else {
    // Display login form
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        <button type="submit">Login</button>
    </form>
    <?php
}

?>


<?php
/**
 * User Login Function
 *
 * @author Your Name
 */

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;

    // Get input from form
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query database for user
        $query = "SELECT * FROM users WHERE username='$username' AND password=SHA1('$password')";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (md5($_POST['username']) === $row['salt'] && md5($_POST['password']) === $row['hash']) {
                    return true;
                }
            }
        } else {
            // If no match is found, return false
            return false;
        }

        return false;

    } else {
        echo "Error: Please enter username and password";
    }
}

// Check if user is logged in
if (isset($_SESSION['logged_in'])) {
    header('Location: protected.php');
} elseif (login_user()) {
    $_SESSION['logged_in'] = true;
    header('Location: protected.php');
} else {
    echo "Invalid username or password";
}
?>


<?php
if (isset($_SESSION['logged_in'])) {
    echo "Welcome, " . $_SESSION['username'] . "! You are now logged in.";
} else {
    header('Location: login.html');
}
?>


<?php

// Configuration settings
const DB_HOST = 'localhost';
const DB_USERNAME = 'your_username';
const DB_PASSWORD = 'your_password';
const DB_NAME = 'your_database';

// Function to connect to database
function connectToDB() {
  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to verify user credentials and log them in
function loginUser($username, $password) {
  // Connect to database
  $conn = connectToDB();
  
  // SQL query to select user from database
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);
  
  // Check if user exists and credentials are correct
  if ($result->num_rows > 0) {
    // Get the first row from the result set (assuming one user per query)
    $row = $result->fetch_assoc();
    
    // Create session to store user ID and username
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    
    return true; // User logged in successfully
  } else {
    return false; // Invalid credentials or no such user
  }
  
  // Close database connection
  $conn->close();
}

// Example usage:
if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  if (loginUser($username, $password)) {
    echo "Welcome, $username!";
  } else {
    echo "Invalid username or password.";
  }
}
?>


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to connect to the database
function db_connect() {
  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to register a new user
function register_user($username, $password, $email) {
  $conn = db_connect();
  $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $password, $email);
  if (!$stmt->execute()) {
    echo "Error registering user: " . $stmt->error;
  }
  $stmt->close();
}

// Function to check if a username already exists
function check_username_exists($username) {
  $conn = db_connect();
  $result = $conn->query("SELECT * FROM users WHERE username = '$username'");
  return ($result->num_rows > 0);
}

// Function to login an existing user
function login_user($username, $password) {
  $conn = db_connect();
  $result = $conn->query("SELECT * FROM users WHERE username = '$username' AND password = SHA1('$password')");
  if ($result->num_rows == 1) {
    // Login successful
    session_start();
    $_SESSION['username'] = $username;
    return true;
  } else {
    // Login failed
    return false;
  }
}

?>


// Register a new user
$username = "newuser";
$password = "password123";
$email = "newuser@example.com";
register_user($username, $password, $email);

// Check if username already exists
$existing_username = "olduser";
if (check_username_exists($existing_username)) {
  echo "Username already exists!";
}

// Login an existing user
$username = "olduser";
$password = "password123";
if (login_user($username, $password)) {
  echo "Login successful! Welcome, $username.";
} else {
  echo "Login failed. Please try again.";
}


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

function user_login($username, $password) {
  // Hash password for verification
  $hashed_password = hash('sha256', $password);

  // Prepare SQL query
  $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $hashed_password);

  // Execute query and fetch result
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    return $row;
  } else {
    return false;
  }
}

// Example usage:
$username = 'your_username';
$password = 'your_password';

if ($user_data = user_login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}

?>


<?php

// Configuration settings
$database_host = 'localhost';
$database_username = 'your_database_username';
$database_password = 'your_database_password';
$database_name = 'your_database_name';

// Connect to database
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user login credentials
function check_login($username, $password) {
    global $conn;

    // Prepare SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // If user exists and password is correct, return true
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Function to register new user
function register_user($username, $password) {
    global $conn;

    // Prepare SQL query
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute query
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

?>


<?php

require_once 'users.php';

// Check if user is logged in
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit;
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check user login credentials
    if (check_login($username, $password)) {
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Invalid username or password";
    }
}

?>

<!-- Login form -->
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>


<?php

require_once 'users.php';

// Check if user is logged in
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit;
}

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Register new user
    if (register_user($username, $password)) {
        echo "User created successfully";
    } else {
        echo "Error creating user";
    }
}

?>

<!-- Registration form -->
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" value="Register">
</form>


<?php

// Define the database connection parameters
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get the input values from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a SQL query to select the user from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if ($stmt->execute()) {
        // Get the result of the query
        $result = $stmt->get_result();

        // Check if a user was found
        if ($result->num_rows > 0) {
            // User exists, log them in
            session_start();
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            // User does not exist or password is incorrect
            echo "Invalid username or password.";
        }
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    login_user();
} else {
    echo "<form action='' method='post'>";
    echo "Username: <input type='text' name='username'><br>";
    echo "Password: <input type='password' name='password'><br>";
    echo "<input type='submit' name='submit' value='Login'>";
    echo "</form>";
}

?>


<?php

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login($username, $password) {
        // Prepare the SQL query
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if password matches
        if (password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }

    public function register($username, $email, $password) {
        // Prepare the SQL query
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    }
}

?>


<?php

// Configure your database connection settings here
$db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');

?>


<?php
require_once 'user.php';
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($user->login($username, $password)) {
        // Login successful, redirect to dashboard or whatever
        header('Location: dashboard.php');
        exit;
    } else {
        echo 'Invalid username or password';
    }
} else {
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
    <?php
}
?>


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to connect to database
function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to login user
function login_user($username, $password) {
    // Connect to database
    $conn = db_connect();

    // SQL query to check username and password
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, sha1($password));
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows == 1) {
        return true;
    } else {
        return false;
    }

    // Close database connection
    $conn->close();
}

?>


<?php

// Include login function file
include 'login.php';

// Define form variables
$username = $_POST['username'];
$password = $_POST['password'];

// Call login_user function with form input values
if (isset($_POST['submit'])) {
    if (login_user($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" name="submit" value="Login">
</form>

</body>
</html>


function user_login($username, $password) {
  // Connect to the database
  $conn = mysqli_connect("localhost", "root", "", "your_database");

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // SQL query to select the user from the database
  $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  
  // Execute the query
  $result = mysqli_query($conn, $query);

  // Check if a row was returned
  if (mysqli_num_rows($result) == 1) {
    // Get the user data from the result
    $user_data = mysqli_fetch_assoc($result);
    
    // If the user exists and their password matches, return true with user data
    return array(true, $user_data);
  } else {
    // If the user does not exist or their password is incorrect, return false
    return array(false, "Invalid username or password");
  }

  // Close the database connection
  mysqli_close($conn);
}


// Call the login function with the provided username and password
$user_data = user_login($_POST["username"], $_POST["password"]);

if ($user_data[0]) {
  // If the user exists and their password is correct, display a success message
  echo "You have logged in successfully! Welcome, " . $user_data[1]["username"] . ".";
} else {
  // If the login fails, display an error message
  echo $user_data[1];
}


<?php

// Configuration settings
$databaseHost = 'localhost';
$databaseName = 'your_database_name';
$databaseUsername = 'your_database_username';
$databasePassword = 'your_database_password';

// Function to connect to database and validate user credentials
function loginUser($username, $password) {
  // Connect to the database
  try {
    $conn = new PDO("mysql:host=$databaseHost;dbname=$databaseName", $databaseUsername, $databasePassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute query to check if username exists and password is correct
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Check if user record was found
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      // Hashed password stored in database is compared with provided password
      if (password_verify($password, $row['password'])) {
        // Login successful, return user data
        return array(
          'success' => true,
          'username' => $row['username'],
          'email' => $row['email']
        );
      } else {
        // Incorrect password
        return array('error' => 'Invalid username or password');
      }
    } else {
      // User record not found
      return array('error' => 'Invalid username or password');
    }

  } catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
  } finally {
    // Close connection to database
    if ($conn) {
      $conn = null;
    }
  }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

if (isset($_POST['login'])) {
  $result = loginUser($username, $password);
  if (isset($result['success'])) {
    echo 'Login successful!';
  } else {
    echo $result['error'];
  }
}
?>


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
    // Connect to database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Function to check user credentials
    function login($email, $password) {
        global $conn;

        // SQL query to select user data
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_data) {
            // Check password
            if (password_verify($password, $user_data['password'])) {
                return array(
                    'id' => $user_data['id'],
                    'name' => $user_data['name'],
                    'email' => $user_data['email']
                );
            } else {
                throw new Exception('Invalid password');
            }
        } else {
            throw new Exception('User not found');
        }
    }

    // Example usage:
    try {
        $credentials = array(
            'email' => 'example@example.com',
            'password' => 'password123'
        );

        $user_data = login($credentials['email'], $credentials['password']);

        echo "Logged in successfully!";
        print_r($user_data);

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}

?>


$credentials = array(
    'email' => 'example@example.com',
    'password' => 'password123'
);

$user_data = login($credentials['email'], $credentials['password']);

if ($user_data) {
    echo "Logged in successfully!";
    print_r($user_data);
} else {
    echo "Invalid credentials";
}


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

function loginUser($username, $password) {
  global $conn;

  // Prepare SQL statement
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);

  // Execute query
  if ($stmt->execute()) {
    $result = $stmt->get_result();

    // Check password
    while($row = $result->fetch_assoc()) {
      if (password_verify($password, $row['password'])) {
        return true; // User logged in successfully
      }
    }
  }

  // If user not found or wrong password
  return false;
}

?>


<?php

// Get username and password from form
$username = $_POST['username'];
$password = $_POST['password'];

if (loginUser($username, $password)) {
  echo "You are now logged in!";
} else {
  echo "Invalid username or password";
}

?>


<?php

// Array of registered users (in a real application, this would be stored securely in a database)
$users = [
    'user1' => 'password1',
    'user2' => 'password2',
];

function login($username, $password) {
    // Check if the username exists
    if (!isset($users[$username])) {
        return null;
    }

    // Compare the provided password with the stored one
    if ($users[$username] === $password) {
        return [
            'success' => true,
            'message' => 'Login successful!',
            'data' => ['username' => $username],
        ];
    } else {
        return null;
    }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = login($username, $password);

if ($result !== null) {
    echo json_encode($result);
} else {
    echo 'Invalid username or password';
}
?>


<?php
// Configuration settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_database_username';
$password = 'your_database_password';

// Connect to database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Check if form submitted
if (isset($_POST['login'])) {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL query to validate credentials
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    try {
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            // Login successful, redirect to protected page
            $_SESSION['email'] = $email;
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Invalid email or password";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close database connection
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form action="" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header('Location: dashboard.php');
            exit;
        }
    } else {
        echo 'Invalid username or password';
    }

    // Close the statement
    $stmt->close();
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    login_user();
} else {
    ?>
    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
}

// Close the connection
$conn->close();

?>


<?php
// Database connection settings
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

function login_user() {
    // Get user input
    $username = $_POST["username"];
    $password = $_POST["password"];

    // SQL query to check username and password
    $sql = "SELECT * FROM users WHERE username='$username' AND password=md5('$password')";

    // Execute the query
    $result = $conn->query($sql);

    // Check if result is true (i.e., user exists)
    if ($result->num_rows > 0) {
        // Login successful, get user details
        while($row = $result->fetch_assoc()) {
            $_SESSION["username"] = $row["username"];
            $_SESSION["id"] = $row["id"];
        }
        
        echo "Login Successful";
    } else {
        echo "Invalid username or password.";
    }

    // Close the connection
    $conn->close();
}

// Check for form submission
if (isset($_POST["submit"])) {
    login_user();
}
?>


<?php

// Define the users array (replace with database query or storage)
$users = [
    'john' => [
        'password' => '12345',
        'role' => 'admin'
    ],
    'jane' => [
        'password' => '67890',
        'role' => 'user'
    ]
];

function login($username, $password) {
    // Check if the username exists in the users array
    if (array_key_exists($username, $users)) {
        // Hash the provided password for comparison
        $hashedPassword = hash('sha256', $password);

        // Compare the hashed password with the stored one
        if ($hashedPassword === $users[$username]['password']) {
            return true;
        } else {
            return false;
        }
    } else {
        // Username not found in the users array
        return false;
    }
}

function validateRole($username, $role) {
    // Check if the username has the specified role
    if (array_key_exists($username, $users)) {
        return $users[$username]['role'] === $role;
    } else {
        return false;
    }
}


// User login attempt
$loggedIn = login('john', '12345');

if ($loggedIn) {
    echo "Login successful!";
    
    // Check role (e.g., admin or user)
    if (validateRole('john', 'admin')) {
        echo "User has admin role.";
    } else {
        echo "User does not have admin role.";
    }
} else {
    echo "Login failed. Incorrect username or password.";
}


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Function to check user login credentials
function checkLogin($username, $password) {
  global $conn;

  // Prepare SQL query
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  // Fetch result
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result) {
    // Hashed password match check
    if (password_verify($password, $result['password'])) {
      return true;
    }
  }

  return false;
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

if (checkLogin($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to connect to the database
function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle user login
function login_user($username, $password) {
    // Prepare query to select user data from database
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    
    // Create a prepared statement object
    $stmt = db_connect()->prepare($sql);
    
    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    // Check if user exists and password is correct
    if ($result->num_rows == 1) {
        // User found, get the data from the row
        $user_data = $result->fetch_assoc();
        
        // Create session variables to store user data
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['id'] = $user_data['id'];
        
        return true;
    } else {
        return false;
    }
}

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Get user input from form
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Hash password for security
    
    // Call login_user function to check if user exists and password is correct
    if (login_user($username, $password)) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid username or password";
    }
}

?>


<?php

// Define database connection credentials
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

function login_user($username, $password) {
    // Prepare SQL query
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    
    // Bind parameters
    $stmt->bind_param("s", $username);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    // If user exists and password is correct
    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        
        // Check if the hashed password matches the input password
        if (password_verify($password, $row['password'])) {
            // Login successful
            return true;
        } else {
            echo "Incorrect password";
        }
    } else {
        echo "User not found";
    }
    
    // If login failed, return false
    return false;
}

// Example usage:
$username = 'example_username';
$password = 'example_password';

if (login_user($username, $password)) {
    echo "Login successful!";
} else {
    echo "Login failed.";
}
?>


<?php

require_once 'database.php'; // Connect to database (see below)

function login($username, $password) {
  global $db;

  // Prepare SQL query
  $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  // Fetch user data
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password'])) {
    return true; // Login successful
  } else {
    return false; // Login failed
  }
}

function register($username, $email, $password) {
  global $db;

  // Prepare SQL query
  $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
  $stmt->execute();

  return true; // User created successfully
}

// Example usage:
$username = 'johnDoe';
$password = 'mySecretPassword';

if (login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}


<?php

// Connect to database using PDO
$db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');

// Set error mode to exceptions
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>


<?php

// Define the users array (replace with your own data storage)
$users = [
    'john' => [
        'password' => '$2y$10$hFVxM6Ua5r9mJkLcPbA0OuZBp1X7Cz8rEg2Yd3IwHvU',
        'email' => 'john@example.com'
    ],
    'jane' => [
        'password' => '$2y$10$hFVxM6Ua5r9mJkLcPbA0OuZBp1X7Cz8rEg2Yd3IwHvU',
        'email' => 'jane@example.com'
    ]
];

function login($username, $password) {
    // Check if the username exists
    if (array_key_exists($username, $users)) {
        // Get the stored password and email for this user
        $user = &$users[$username];
        
        // Hash the provided password and compare it to the stored hash
        $providedPasswordHash = password_hash($password, PASSWORD_DEFAULT);
        if ($providedPasswordHash === $user['password']) {
            return true;
        } else {
            echo 'Invalid password';
            return false;
        }
    } else {
        echo 'Username not found';
        return false;
    }
}

// Example usage
$username = $_POST['username'];
$password = $_POST['password'];

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login($username, $password)) {
        echo 'Login successful!';
    } else {
        echo 'Failed to login';
    }
}

?>


<?php
/**
 * User login function
 *
 * @param string $username The username to log in with.
 * @param string $password The password to use for logging in.
 *
 * @return bool Whether the login was successful or not.
 */
function user_login($username, $password) {
    // Database of users (in reality, this would be a database connection)
    $users = array(
        'admin' => array('password' => 'password123',),
        'user1' => array('password' => 'password456'),
    );

    // Check if the username exists
    if (!isset($users[$username])) {
        return false;
    }

    // Hash the password (in reality, you would use a hash function like bcrypt)
    $hashedPassword = $users[$username]['password'];

    // Check if the passwords match
    if ($password === $hashedPassword) {
        return true;  // Login successful!
    } else {
        return false;
    }
}
?>


<?php
// Get the username and password from the form data (e.g. $_POST['username'] and $_POST['password'])
$username = $_POST['username'];
$password = $_POST['password'];

// Call the user_login function with the provided credentials
if (user_login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password";
}
?>


// Hashing and verifying passwords with bcrypt
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Verify the password later...
if (password_verify($providedPassword, $hashedPassword)) {
    echo "Login successful!";
}


function loginUser($username, $password) {
  // Configuration for database connection
  $dbHost = 'localhost';
  $dbName = 'your_database_name';
  $dbUser = 'your_database_username';
  $dbPass = 'your_database_password';

  try {
    // Connect to the database
    $dsn = "mysql:host=$dbHost;dbname=$dbName";
    $pdo = new PDO($dsn, $dbUser, $dbPass);

    // Prepare the query to select user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch result of the query
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      // User is authenticated, return their user data
      return array(
        'id' => $user['id'],
        'username' => $user['username']
      );
    } else {
      throw new Exception('Invalid username or password');
    }
  } catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
  } catch (Exception $e) {
    // Return error message
    return array(
      'error' => $e->getMessage()
    );
  }
}


$userData = loginUser('username', 'password');
if (isset($userData['id'])) {
  echo "User authenticated successfully!";
} else {
  echo $userData['error'];
}


<?php

// Configuration variables
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to connect to database
function db_connect() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    return $conn;
}

// Function to check user credentials and log them in
function login($username, $password) {
    // Connect to database
    $conn = db_connect();
    
    // Check for SQL injection vulnerabilities by using prepared statements
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    
    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        
        // Check if user exists and password matches
        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                // User is logged in
                $_SESSION['username'] = $username;
                
                // Additional data can be stored here if needed
                return true;
            }
        }
    } else {
        die("Error: " . mysqli_error($conn));
    }
    
    // If any of the above checks fail, return false
    return false;
}

// Usage example:
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (login($username, $password)) {
        echo "You are logged in!";
    } else {
        echo "Invalid username or password.";
    }
}

?>


function loginUser($username, $password) {
  // Connect to the database (assuming a connection named `$db`)
  global $db;

  // Prepare SQL query
  $query = "SELECT * FROM users WHERE username = :username";
  
  // Bind parameters
  $stmt = $db->prepare($query);
  $stmt->bindParam(":username", $username);

  // Execute query and get result
  if ($stmt->execute()) {
    $user = $stmt->fetch();
    
    // Check if user exists and password matches
    if ($user && password_verify($password, $user['password'])) {
      return $user;
    } else {
      return false; // Incorrect username or password
    }
  } else {
    // Handle database error (e.g., connection failure)
    return null;
  }
}


// Connect to the database
$db = new PDO('mysql:host=localhost;dbname=your_database_name', 'username', 'password');

// Login user
$username = 'example_user';
$password = 'secret_password';

$userData = loginUser($username, $password);

if ($userData) {
  echo "Login successful!";
  // You can access the user data using `$userData['id']`, `$userData['username']`, etc.
} else {
  echo "Incorrect username or password.";
}


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Database connection
function connectToDatabase() {
  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to register a user
function registerUser($username, $password, $email) {
  $conn = connectToDatabase();
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, password_hash($password, PASSWORD_DEFAULT), $email);
  if ($stmt->execute()) {
    return true;
  } else {
    echo "Error: " . $stmt->error;
    return false;
  }
}

// Function to login a user
function loginUser($username, $password) {
  $conn = connectToDatabase();
  $sql = "SELECT * FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  if ($stmt->execute()) {
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      return $user;
    } else {
      echo "Invalid password";
      return null;
    }
  } else {
    echo "Error: " . $stmt->error;
    return null;
  }
}

// Function to check if a username is taken
function usernameAvailable($username) {
  $conn = connectToDatabase();
  $sql = "SELECT * FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  if ($stmt->execute()) {
    return !$stmt->get_result()->num_rows > 0;
  } else {
    echo "Error: " . $stmt->error;
    return false;
  }
}

?>


<?php

require_once 'users.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if ($usernameAvailable($username)) {
    $user = loginUser($username, $password);
    if ($user !== null) {
      session_start();
      $_SESSION['username'] = $user['username'];
      header('Location: dashboard.php');
    } else {
      echo "Invalid username or password";
    }
  } else {
    echo "Username already taken";
  }
} else {
  // display login form
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username"><br><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password"><br><br>
  <input type="submit" value="Login">
</form>

<?php
}
?>


<?php

// Configuration variables
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function dbConnect() {
  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

function hashPassword($password) {
  return password_hash($password, PASSWORD_DEFAULT);
}

function login($username, $password) {
  $conn = dbConnect();
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
      return true;
    } else {
      echo "Incorrect password";
      return false;
    }
  } else {
    echo "User not found";
    return false;
  }

  $stmt->close();
  $conn->close();
}

// Usage example:
$username = 'your_username';
$password = 'your_password';

if (login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}


<?php

// Function to connect to database
function dbConnect() {
    $servername = "localhost";
    $username = "your_username";
    $password = "your_password";
    $dbname = "your_database";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit;
    }
}

// Function to check user credentials
function checkCredentials($username, $password) {
    try {
        $conn = dbConnect();

        // SQL query to select username and password from users table
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error checking credentials: " . $e->getMessage();
        exit;
    }
}

// Function to log in user
function login($username, $password) {
    if (checkCredentials($username, $password)) {
        // User logged in successfully, create session and redirect to dashboard
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid username or password";
        return false;
    }
}

?>


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define login function
function login_user($username, $password) {
    // Sanitize input
    $username = mysqli_real_escape_string($conn, $username);
    $password = md5($password); // Note: This is insecure and should be replaced with a more secure hashing algorithm

    // Query database for user
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    // Check if user exists
    if ($result->num_rows > 0) {
        // Get user data
        $user_data = $result->fetch_assoc();
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['username'] = $user_data['username'];

        return true;
    } else {
        return false;
    }
}

// Handle login form submission
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login_user($username, $password)) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo 'Invalid username or password';
    }
}

?>


<?php
// Configuration
$database = array(
    'host' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'database' => 'your_database'
);

// Function to connect to database
function dbConnect() {
    $conn = new mysqli($database['host'], $database['username'], $database['password'], $database['database']);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to authenticate user
function authenticateUser($username, $password) {
    $conn = dbConnect();
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Function to get user data
function getUserData($username) {
    $conn = dbConnect();
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}


<?php
require_once 'auth.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (authenticateUser($username, $password)) {
        $userData = getUserData($username);
        // User is authenticated, redirect to protected page
        header('Location: protected.php');
        exit;
    } else {
        echo 'Invalid username or password.';
    }
}
?>

<form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <button type="submit">Login</button>
</form>


<?php
  $servername = "localhost";
  $username = "your_username";
  $password = "your_password";
  $dbname = "your_database";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    die("ERROR: " . $e->getMessage());
  }
?>


<?php
require_once 'db.php';

function login_user($email, $password) {
  // Sanitize input
  $email = htmlspecialchars($email);
  $password = htmlspecialchars($password);

  try {
    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // Fetch results
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
      return true; // Login successful
    } else {
      return false; // Incorrect email or password
    }
  } catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage();
  }
}
?>


<?php
require_once 'login.php';

$email = $_POST['email'];
$password = $_POST['password'];

if (isset($_POST['submit'])) {
  if (login_user($email, $password)) {
    echo "Login successful!";
  } else {
    echo "Incorrect email or password.";
  }
}
?>


// Define the database connection settings
$host = 'localhost';
$dbname = 'database_name';
$user = 'username';
$password = 'password';

// Connect to the database
function db_connect() {
    global $host, $dbname, $user, $password;
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        return $conn;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit();
    }
}

// Define the user login function
function user_login($username, $password) {
    // Connect to the database
    $conn = db_connect();

    // Prepare and execute the query
    $query = "SELECT * FROM users WHERE username=:username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Check if a matching user exists
    $user_data = $stmt->fetch();
    if ($user_data) {
        // Check the password (using plain text for simplicity)
        if ($password == $user_data['password']) {
            return true; // Login successful
        } else {
            return false; // Incorrect password
        }
    } else {
        return false; // No matching user found
    }

    // Close the database connection
    $conn = null;
}

// Example usage:
$username = 'exampleuser';
$password = 'examplepassword';

if (user_login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password";
}


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get POST data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query database for user
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, log them in
        session_start();
        $_SESSION['logged_in'] = true;
        echo 'Login successful!';
    } else {
        // User not found, display error message
        echo 'Invalid username or password.';
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    login_user();
}

?>


<?php
// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Connect to database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Check if user is logged in
if (isset($_SESSION['logged_in'])) {
  echo "You are already logged in.";
  exit;
}

// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Prepare SQL query to check user credentials
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
  $stmt->bindParam(":username", $username);
  $stmt->bindParam(":password", $password);

  try {
    // Execute SQL query
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
      // User credentials are valid, log them in
      $_SESSION['logged_in'] = true;
      $_SESSION['username'] = $username;

      echo "You have been logged in successfully.";
    } else {
      echo "Invalid username or password.";
    }
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

// Close database connection
$conn = null;
?>


<?php
require 'login.php';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>
  <h1>Login</h1>

  <?php if (isset($_SESSION['logged_in'])) : ?>
    <p>Welcome, <?= $_SESSION['username']; ?>!</p>
    <a href="logout.php">Logout</a>
  <?php else : ?>

  <form action="" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" value="Login">
  </form>

  <?php endif; ?>

</body>
</html>


/**
 * User Login Function
 *
 * Handles user login functionality.
 *
 * @param string $username  The username entered by the user.
 * @param string $password  The password entered by the user.
 *
 * @return array|bool      An array containing the user's data or false on failure.
 */
function loginUser($username, $password) {
    // Connect to the database (replace with your own connection code)
    include 'db.php';

    // Query the database for the user
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    // Check if the user exists and the password is correct
    if (mysqli_num_rows($result) > 0) {
        // Get the user's data from the result
        $user_data = mysqli_fetch_assoc($result);
        return $user_data;
    } else {
        // If the user does not exist or the password is incorrect, return false
        return false;
    }

    // Close the database connection (replace with your own connection code)
    include 'db_close.php';
}


// Get the username and password from the form submission
$username = $_POST['username'];
$password = $_POST['password'];

// Call the loginUser function
$user_data = loginUser($username, $password);

if ($user_data) {
    // If the user exists and the password is correct, log them in
    $_SESSION['user_id'] = $user_data['id'];
    header('Location: dashboard.php');
} else {
    // If the user does not exist or the password is incorrect, display an error message
    echo 'Invalid username or password';
}


function login($username, $password) {
    // Database connection settings
    $dbHost = 'localhost';
    $dbName = 'mydatabase';
    $user = 'root';
    $pass = '';

    // Create a new MySQLi object
    $conn = mysqli_connect($dbHost, $user, $pass, $dbName);

    if (!$conn) {
        die('Error connecting to database: ' . mysqli_error());
    }

    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query the database for user details
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // If a match is found, verify password using MD5 (or any other hashing algorithm)
        $row = mysqli_fetch_assoc($result);
        if ($password === md5($row['password'])) {
            return true; // User authenticated successfully
        } else {
            return false; // Password mismatch
        }
    } else {
        return false; // No user found with the given username
    }

    mysqli_close($conn); // Close database connection

}


// Set login credentials
$username = $_POST['username'];
$password = $_POST['password'];

// Call the login function and store result in a variable
$loginResult = login($username, $password);

if ($loginResult) {
    echo 'User authenticated successfully!';
} else {
    echo 'Invalid username or password';
}


<?php

// Configuration variables
$database_host = 'localhost';
$database_username = 'your_username';
$database_password = 'your_password';
$database_name = 'your_database';

// Connect to the database
function connect_to_database() {
  $conn = new mysqli($database_host, $database_username, $database_password, $database_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to check user credentials
function check_credentials($username, $password) {
  // Connect to the database
  $conn = connect_to_database();
  
  // SQL query to select user data
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    // Get user data from the database
    $row = $result->fetch_assoc();
    
    // Check password hash
    $password_hash = $row['password'];
    if (password_verify($password, $password_hash)) {
      // Login successful, return user data
      $user_data = array(
        'username' => $row['username'],
        'email' => $row['email']
      );
      return $user_data;
    }
  } else {
    // User not found or password incorrect
    return null;
  }
  
  // Close the database connection
  $conn->close();
}

// Function to login user
function login_user($username, $password) {
  // Check if username and password are provided
  if (empty($username) || empty($password)) {
    return array(
      'error' => 'Username or password is required'
    );
  }
  
  // Get user credentials from database
  $credentials = check_credentials($username, $password);
  
  if ($credentials !== null) {
    // Login successful, return user data and a token
    $token = bin2hex(random_bytes(32));
    $_SESSION['user_data'] = $credentials;
    $_SESSION['token'] = $token;
    
    // Return user data and token
    return array(
      'data' => $credentials,
      'token' => $token
    );
  } else {
    // Login failed, return an error message
    return array(
      'error' => 'Username or password is incorrect'
    );
  }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$login_result = login_user($username, $password);

if ($login_result['data']) {
  echo "Login successful!";
} else {
  echo "Error: " . $login_result['error'];
}
?>


<?php

// Configuration settings
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get user input from form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password for comparison
    $hashed_password = md5($password);

    // SQL query to select user data
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User credentials are valid, login successful!
        $_SESSION['username'] = $username;
        header('Location: /dashboard');
        exit();
    } else {
        // Invalid user credentials
        echo "Invalid username or password";
    }
}

if (isset($_POST['login'])) {
    login_user();
} else {
    // Display login form
    ?>
    <form action="" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" name="login" value="Login">
    </form>
    <?php
}

$conn->close();

?>


<?php

// Configuration
$DB_HOST = 'localhost';
$DB_USER = 'your_username';
$DB_PASSWORD = 'your_password';
$DB_NAME = 'your_database';

function login($username, $password) {
  // Establish database connection
  try {
    $conn = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL query to select user by username
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch user data if found
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check password (using SHA-256 for illustration purposes)
    if ($user && hash('sha256', $password) === $user['password']) {
      return true; // Login successful
    } else {
      return false; // Incorrect username or password
    }
  } catch (PDOException $e) {
    echo 'Error connecting to database: ' . $e->getMessage();
    return false;
  } finally {
    $conn = null;
  }
}

?>


<?php

// Define user input
$username = $_POST['username'];
$password = $_POST['password'];

// Call login function
if (login($username, $password)) {
  echo 'Login successful!';
} else {
  echo 'Invalid username or password.';
}

?>


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function login_user($username, $password) {
    // Hash the password for comparison
    $hashed_password = md5($password);

    // Query database to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username' AND password_hash = '$hashed_password'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Check user credentials
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login_user($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}

// Close database connection
$mysqli->close();

?>


<?php

// Configuration variables
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
function connectToDatabase() {
  $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
  return $conn;
}

// Check user credentials
function checkCredentials($username, $password) {
  // Prepare query
  $query = "SELECT * FROM users WHERE username = :username";
  
  // Execute query
  $stmt = connectToDatabase()->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  
  // Check if user exists and password is correct
  if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (password_verify($password, $row['password'])) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

// Login function
function login($username, $password) {
  // Check user credentials
  if (checkCredentials($username, $password)) {
    // If user is logged in, set a session variable to indicate success
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
    
    return true;
  } else {
    return false;
  }
}

?>


<?php

// Include the login function file
include('login.php');

// User input
$username = $_POST['username'];
$password = $_POST['password'];

// Login attempt
if (login($username, $password)) {
  echo "Welcome, $username!";
} else {
  echo "Invalid username or password.";
}

?>


function loginUser($username, $password) {
    // Database connection settings (modify to suit your database)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'your_username');
    define('DB_PASSWORD', 'your_password');
    define('DB_NAME', 'your_database');

    // Connect to the database
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");

    // Bind parameters
    $params = array(
        ':username' => $username,
        ':password' => hash('sha256', $password) // Hash the password for security
    );

    // Execute query and retrieve result
    try {
        $stmt->execute($params);
        $result = $stmt->fetch();

        if ($result !== false) {
            return true; // User credentials are valid
        } else {
            return false; // Invalid user credentials
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    } finally {
        $conn = null; // Close the database connection
    }
}


if (loginUser('username', 'password')) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


<?php
// Configuration
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check login credentials
function check_login($username, $password) {
    global $conn;
    // SQL query to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    // Check if result is not empty (i.e., user exists and credentials match)
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Function to get user data
function get_user_data($username) {
    global $conn;
    // SQL query to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    // Check if result is not empty (i.e., user exists)
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Handle login request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (check_login($username, $password)) {
        // If user exists and credentials match, get their data
        $user_data = get_user_data($username);

        // Set session variables
        $_SESSION['username'] = $username;
        $_SESSION['logged_in'] = true;

        // Redirect to protected page (e.g., dashboard)
        header("Location: /dashboard.php");
        exit();
    } else {
        // Display error message if credentials don't match
        echo "Invalid username or password.";
    }
}
?>


<?php
// Define the database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the function for user login
function login_user() {
    global $conn;

    // Get the username and password from the form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a query to select the user's hashed password from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute the query and store the result in a variable
    $result = $stmt->get_result();

    // If the user exists, hash their provided password to compare it with the stored hash
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $stored_hash = $row['password'];

            // Hash the provided password for comparison
            $hashed_password = md5($password);

            // Compare the hashed passwords (note: this is a simplified example and not recommended for production use)
            if ($hashed_password === $stored_hash) {
                return true; // User login successful
            } else {
                return false;
            }
        }
    }

    // If no match, user login failed
    return false;
}

// Handle the form submission (if it exists)
if (!empty($_POST['username']) && !empty($_POST['password'])) {
    if (login_user()) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}
?>


<?php
// Configuration
$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myuser';
$password = 'mypassword';

try {
    // Connect to database
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function loginUser($username, $password) {
        global $conn;

        // Prepare SQL query
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch user data
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            // Hashed password check
            if (password_verify($password, $userData['password'])) {
                return true; // Login successful
            } else {
                return false; // Incorrect password
            }
        } else {
            return false; // User not found
        }
    }

} catch (PDOException $e) {
    echo 'Error connecting to database: ' . $e->getMessage();
}
?>


// Assume we're calling this from another PHP file
require_once('login.php');

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        // Login successful, redirect to dashboard or protected page
        header('Location: dashboard.php');
        exit;
    } else {
        echo 'Invalid username or password';
    }
}


<?php
// Configuration
$database = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to database
try {
    $pdo = new PDO('mysql:host=localhost;dbname=' . $database, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

function login($email, $password) {
    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email AND password = :password');
    
    // Bind parameters
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    
    // Execute query
    $stmt->execute();
    
    // Fetch result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        // Login successful, return user data
        return $result;
    } else {
        // Login failed, return error message
        return 'Invalid email or password';
    }
}

// Handle form submission
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $user_data = login($email, $password);
    
    if ($user_data !== false) {
        // Login successful, redirect to dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        // Display error message
        echo 'Invalid email or password';
    }
}
?>


<?php include 'login.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form action="" method="post">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>


<?php
// Database settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Create a new PDO instance
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
?>


<?php
require_once 'config.php';

function login($username, $password) {
    // Prepare and execute the query to check if the username exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    // If no user is found, return false
    if (!$user) {
        return false;
    }

    // Hash the input password and compare it with the stored hashed password
    $hashPassword = hash('sha256', $password . $user['salt']);
    if ($hashPassword === $user['password']) {
        // If passwords match, create a new session for the user
        $_SESSION['username'] = $username;
        return true;
    } else {
        return false;
    }
}

// Example usage:
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}
?>


<?php
require_once 'config.php';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (login($username, $password)) {
        // Redirect to protected area or perform other actions
        header('Location: protected_area.php');
        exit;
    }
}

?>

<!-- Login form -->
<form action="" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit" name="login">Login</button>
</form>


// db_config.php

<?php

define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

?>


// models/User.php

<?php

class User {
    private $id;
    private $username;
    private $password;

    public function __construct($data) {
        $this->id = $data['id'];
        $this->username = $data['username'];
        $this->password = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    public static function findUserByUsername($username) {
        // Retrieve user data from database
        global $db;
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
}


// controllers/Login.php

<?php

class Login {
    public static function processLogin($username, $password) {
        // Retrieve user data from database
        $user = User::findUserByUsername($username);

        if ($user !== false && password_verify($password, $user['password'])) {
            // Successful login: store user data in session
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            return true;
        } else {
            // Invalid username or password
            return false;
        }
    }
}


// index.php (example usage)

<?php

require_once 'db_config.php';
require_once 'models/User.php';
require_once 'controllers/Login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (Login::processLogin($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}


<?php

// Define a configuration array
$config = [
    'dbHost' => 'localhost',
    'dbUsername' => 'your_username',
    'dbPassword' => 'your_password',
    'dbName' => 'your_database'
];

// Connect to the database
$conn = new mysqli($config['dbHost'], $config['dbUsername'], $config['dbPassword'], $config['dbName']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to hash a password using SHA-256
function passwordHasher($password) {
    return sha1(sha1($password));
}

// Function to check user login credentials
function loginUser($username, $password) {
    // Check if the username and password are not empty
    if (empty($username) || empty($password)) {
        return 'Please fill in both username and password';
    }

    // Hash the input password for comparison with the stored hash
    $hashedPassword = passwordHasher($password);

    // SQL query to retrieve user data from the database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashedPassword'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // If user is found, return their ID
            return $row['id'];
        }
    } else {
        // If no user found, return an error message
        return 'Invalid username or password';
    }

    // Close the database connection
    $conn->close();
}

// Call the login function with user input (username and password)
if (!empty($_POST['username']) && !empty($_POST['password'])) {
    echo loginUser($_POST['username'], $_POST['password']);
} else {
    echo 'Please fill in both username and password';
}
?>


<?php

// Define the database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a new PDO instance to connect to the database
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

function authenticate_user($username, $password) {
    // Prepare the SQL query to select the user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    
    // Execute the query and fetch the result
    if ($stmt->execute()) {
        $user_data = $stmt->fetch();
        
        // Check if a user exists with the provided credentials
        if ($user_data && password_verify($password, $user_data['password'])) {
            return true;
        }
    }
    
    return false;
}

function login_user($username, $password) {
    // Authenticate the user
    if (authenticate_user($username, $password)) {
        // Generate a session ID for the user
        $session_id = uniqid();
        
        // Insert the session ID into the sessions table
        $stmt = $pdo->prepare("INSERT INTO sessions (user_id, session_id) VALUES (:user_id, :session_id)");
        $stmt->bindParam(':user_id', $user_data['id']);
        $stmt->bindParam(':session_id', $session_id);
        
        if ($stmt->execute()) {
            // Set the session ID as a cookie
            setcookie('session_id', $session_id, time() + 3600); // expires in 1 hour
            
            // Return true to indicate that the login was successful
            return true;
        }
    } else {
        // Return false if the user is not authenticated or cannot be logged in
        return false;
    }
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $login_successful = login_user($username, $password);
    
    if ($login_successful) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}
?>


<?php
// Configuration
require_once 'config.php';

// Function to validate and log in user
function login($username, $password) {
    global $db;

    // Prepare statement for query
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);

    try {
        // Execute query
        $stmt->execute();

        // Fetch result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && password_verify($password, $result['password'])) {
            // User is authenticated, return user data
            return $result;
        } else {
            throw new Exception('Invalid username or password');
        }
    } catch (PDOException $e) {
        // Handle database error
        echo 'Database error: ' . $e->getMessage();
    }

    return null;
}
?>


<?php
// Database connection settings
$dsn = 'mysql:host=localhost;dbname=your_database';
$username = 'your_username';
$password = 'your_password';

try {
    // Establish database connection
    $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>


<?php
require_once 'login.php';

// User submits login form with username and password
$username = $_POST['username'];
$password = $_POST['password'];

// Log in user using `login` function
$userData = login($username, $password);

if ($userData) {
    // User is logged in, display success message or redirect to protected page
    echo 'You are now logged in!';
} else {
    // Display error message for invalid username or password
    echo 'Invalid username or password';
}
?>


<?php
/**
 * User Login Function
 *
 * This script checks if the provided username and password match with the stored credentials.
 *
 * @param string $username The username to check.
 * @param string $password The password to check.
 *
 * @return array|bool An array containing the user's data on success, or false on failure.
 */

function login($username, $password) {
  // Database connection settings
  $host = 'localhost';
  $db_name = 'database_name';
  $user = 'database_user';
  $pass = 'database_password';

  // Create a database connection
  try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL query to retrieve the user's data
    $stmt = $conn->prepare('SELECT * FROM users WHERE username=:username');
    $stmt->bindParam(':username', $username);

    // Execute the query and fetch the result
    $stmt->execute();
    $user_data = $stmt->fetch();

    // Check if a matching user exists
    if ($user_data !== false) {
      // Verify the password using hash
      if (password_verify($password, $user_data['password'])) {
        return $user_data; // Return the user's data on successful login
      } else {
        echo 'Invalid password';
      }
    } else {
      echo 'No matching user found';
    }

  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  } finally {
    if (isset($conn)) {
      $conn = null;
    }
  }
}
?>


// Call the login function with the username and password
$user_data = login('john_doe', 'my_secret_password');

if ($user_data !== false) {
  echo "Login successful! You are now logged in as: " . $user_data['username'];
} else {
  echo "Login failed!";
}


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate user credentials
function login_user($username, $password) {
    // SQL query to retrieve user data from the database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Retrieve user data from the result set
        $user_data = $result->fetch_assoc();

        // Verify password using SHA-256 hash
        $password_hash = sha1($password);
        if ($password_hash == $user_data['password']) {
            // Login successful, return user data
            return array('username' => $username, 'email' => $user_data['email'], 'role' => $user_data['role']);
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }

    return false;
}

// Handle POST request from the login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the $_POST array
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the login_user function to authenticate user credentials
    $user_data = login_user($username, $password);

    if ($user_data) {
        // Login successful, redirect to a protected page or dashboard
        header('Location: protected-page.php');
        exit;
    } else {
        // Login failed, display an error message
        echo "Login failed";
    }
}

?>


<?php
// Configuration settings
$hostname = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create a database connection
$conn = new mysqli($hostname, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user credentials
function check_credentials($username, $password) {
    // Query to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username'";

    // Execute the query
    $result = $conn->query($query);

    // Check if a match was found
    if ($result->num_rows > 0) {
        // Get the user's row from the result set
        $user_data = $result->fetch_assoc();

        // Verify the password (simple example: just compare the two strings)
        if (password_verify($password, $user_data['password'])) {
            return true; // credentials valid
        }
    }

    // Credentials invalid
    return false;
}

// Function to create a session for the user
function login_user($username) {
    // Retrieve the user's data from the database
    $query = "SELECT * FROM users WHERE username = '$username'";

    // Execute the query
    $result = $conn->query($query);

    // Check if a match was found
    if ($result->num_rows > 0) {
        // Get the user's row from the result set
        $user_data = $result->fetch_assoc();

        // Create a session for the user
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['role'] = $user_data['role'];

        return true; // login successful
    }

    // Login failed
    return false;
}

// Handle form submission
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (check_credentials($username, $password)) {
        login_user($username);
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid username or password";
    }
}

// Display the login form
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" name="login" value="Login">
</form>


<?php
// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user credentials
function login_user($username, $password) {
    global $conn;
    // SQL query to select the user from the database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    
    // Execute the query and store the result in an array
    $result = mysqli_query($conn, $sql);
    
    if ($result->num_rows > 0) {
        // If a user is found, retrieve their data
        while ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                // User credentials match; return the user's ID and username
                return array('user_id' => $row['id'], 'username' => $row['username']);
            }
        }
    }
    
    // If no user is found or passwords don't match, return null
    return null;
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $user_data = login_user($username, $password);
    
    if ($user_data !== null) {
        // User is logged in; store their ID and username as session variables
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user_data['user_id'];
        $_SESSION['username'] = $user_data['username'];
        
        // Redirect to a protected page (e.g. dashboard.php)
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid username or password.";
    }
}
?>


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to verify password
function verifyPassword($hashedPassword, $providedPassword) {
    return password_verify($providedPassword, $hashedPassword);
}

// Function to login user
function loginUser($username, $password) {
    // Prepare query
    $stmt = $conn->prepare("SELECT id, username, hashed_password FROM users WHERE username = ?");

    // Bind parameters
    $stmt->bind_param("s", $username);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch row
        $row = $result->fetch_assoc();

        // Verify password
        if (verifyPassword($row['hashed_password'], $password)) {
            return true;
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Username not found";
    }

    return false;
}

// Login user
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        // User logged in successfully
        echo "Welcome, " . $username;
    }
}

// Close connection
$conn->close();

?>


// Database connection settings
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
    // Prepare SQL query
    $sql_query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute SQL query
    $result = $conn->query($sql_query);

    if ($result->num_rows > 0) {
        // User found, log them in
        while ($row = $result->fetch_assoc()) {
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            return true; // user logged in successfully
        }
    } else {
        return false; // user not found or password incorrect
    }

    $conn->close();
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login_user($username, $password)) {
        echo "Logged in successfully!";
    } else {
        echo "Invalid username or password.";
    }
}


<?php

// Configuration
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database_name';

// Connect to database
$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

function loginUser() {
  global $conn;

  // Get form data
  $username = $_POST['username'];
  $password = sha1($_POST['password']);

  // Validate input
  if (empty($username) || empty($password)) {
    return 'Error: Both username and password are required.';
  }

  // Query database for user
  $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // User found, log them in
    while ($row = mysqli_fetch_assoc($result)) {
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      return 'Login successful.';
    }
  } else {
    // Incorrect login credentials
    return 'Invalid username or password. Please try again.';
  }

  mysqli_close($conn);
}

if (isset($_POST['login'])) {
  echo loginUser();
} else {
  // Display login form if not submitted
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="text" name="username" placeholder="Username">
  <input type="password" name="password" placeholder="Password">
  <button type="submit" name="login">Login</button>
</form>
<?php
}
?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function login($username, $password) {
    // Query to check user credentials
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute query
    $result = $mysqli->query($query);

    if ($result->num_rows == 1) {
        // If user is found, return true and session variables
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        return true;
    } else {
        // If user not found or password incorrect, return false
        return false;
    }
}

// Check if form has been submitted
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Use md5 for simplicity, consider using a more secure hashing function

    // Call login function and check if user is logged in successfully
    if (login($username, $password)) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo 'Invalid username or password';
    }
}

// Close connection
$mysqli->close();

?>


<?php

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    echo 'Welcome, ' . $_SESSION['username'] . '!';

} else {
    header('Location: index.html');
    exit();
}

?>


<?php

// Configuration variables
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
    global $conn;

    // Prepare query to select user data from the database
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if user exists and password is correct
    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Start a session
        session_start();

        // Set session variables
        $_SESSION['username'] = $user['username'];
        $_SESSION['logged_in'] = true;

        return true;
    } else {
        return false;
    }
}

// Check if login form has been submitted
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the login function
    if (login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}

?>


function userLogin($username, $password) {
    // Predefined array of users (replace with database query in production)
    $users = [
        'user1' => 'password123',
        'user2' => 'secret456'
    ];

    // Check if username exists in users array
    if (!isset($users[$username])) {
        return false; // Username does not exist
    }

    // Check if password matches the stored password
    $storedPassword = $users[$username];
    if ($password !== $storedPassword) {
        return false; // Password is incorrect
    }

    // Login successful, return true
    return true;
}


$username = 'user1';
$password = 'password123';

if (userLogin($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


<?php

// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Create a connection to the database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

?>


<?php

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Input validation
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Hash the password for comparison with stored hash
        $hashedPassword = hash('sha256', $password);

        try {
            // SQL query to retrieve user data from database
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            // Fetch and store user data
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userData) {
                // Compare hashed password with stored hash
                if ($hashedPassword === $userData['password']) {
                    $_SESSION['username'] = $username;
                    header('Location: dashboard.php');
                    exit();
                } else {
                    echo 'Incorrect password';
                }
            } else {
                echo 'User not found';
            }

        } catch (PDOException $e) {
            die('Database error: ' . $e->getMessage());
        }

    } else {
        echo 'Please enter both username and password';
    }

} else {
    // Display login form
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br><br>
        Password: <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>

    <?php
}
?>


<?php
require 'config.php'; // Database connection configuration file

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Get user input data from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Input validation
    if (empty($username) || empty($password)) {
        echo "Both username and password are required.";
    } else {
        // Hash the password for verification
        $hashedPassword = hash('sha256', $password);

        // Query to retrieve user from database
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            // Retrieve the hashed password from the query result
            while ($row = mysqli_fetch_assoc($result)) {
                $storedHashedPassword = $row['password'];

                // Verify the input password with the stored hash
                if ($hashedPassword == $storedHashedPassword) {
                    // Login successful, proceed to authenticated area
                    header('Location: dashboard.php');
                    exit;
                } else {
                    echo "Invalid username or password.";
                }
            }
        } else {
            echo "Invalid username or password.";
        }
    }
}
?>

<!-- Display form for user login -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <button type="submit" name="submit">Login</button>
</form>


<?php
// Database connection configuration file

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
// Define the database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the login function
function login_user($username, $password) {
    // Prepare the SQL query to check for the user's existence and password match
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    
    // Bind the parameters
    $stmt->bind_param("ss", $username, md5($password));
    
    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    // Check if a user exists with the provided credentials
    if ($result->num_rows > 0) {
        // Return true to indicate a successful login
        return true;
    } else {
        // Return false to indicate an unsuccessful login
        return false;
    }
}

// Define the function to insert a new user into the database (optional)
function create_user($username, $password) {
    // Prepare the SQL query to insert a new user
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    
    // Bind the parameters
    $stmt->bind_param("ss", $username, md5($password));
    
    // Execute the query
    $stmt->execute();
}

// Check if the login button has been clicked
if (isset($_POST['login'])) {
    // Get the username and password from the form submission
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Call the login function to check for a successful login
    if (login_user($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}

// Close the database connection
$conn->close();
?>


// config.php (store database connection settings)
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

// functions.php (login function)
<?php

function login($username, $password) {
  // Connect to the database
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if (!$conn) {
    die("Connection failed: " . mysqli_error($conn));
  }

  // Prepare SQL query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = mysqli_prepare($conn, $query);

  // Bind parameters
  mysqli_stmt_bind_param($stmt, 'ss', $username, $password);

  // Execute query
  if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
      // Login successful, return user data
      while ($row = mysqli_fetch_assoc($result)) {
        return array(
          'id' => $row['id'],
          'username' => $row['username']
        );
      }
    } else {
      // Incorrect username or password
      return false;
    }
  } else {
    // Query execution failed
    return false;
  }

  // Close the database connection
  mysqli_close($conn);
}

?>


<?php

// Include functions.php
require 'functions.php';

// Get user input
$username = $_POST['username'];
$password = $_POST['password'];

// Login user
$userData = login($username, $password);

if ($userData) {
  // Login successful, redirect to dashboard or home page
  header('Location: dashboard.php');
} else {
  // Login failed, display error message
  echo 'Invalid username or password.';
}
?>


<?php

// Configuration settings
define('DB_HOST', 'your_database_host');
define('DB_USERNAME', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user credentials
function login_user($username, $password) {
    // Prepare SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    
    // Bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute query
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        // Check if user exists and password is correct
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// Login function
function login($username, $password) {
    // Hash the password (using a secure hash algorithm like bcrypt or SHA-256)
    $hashed_password = hash('sha256', $password);
    
    // Call login_user function
    if (login_user($username, $hashed_password)) {
        return true;
    } else {
        return false;
    }
}

// Check if user is already logged in
function check_login() {
    // Get session data
    $session_data = $_SESSION['user'];
    
    // If no session data, return false
    if (!isset($session_data)) {
        return false;
    } else {
        return true;
    }
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Login user
    if (login($username, $password)) {
        echo "User logged in successfully!";
        
        // Set session data
        $_SESSION['user'] = array('username' => $username);
        
        // Redirect to secure page
        header("Location: secured_page.php");
        exit;
    } else {
        echo "Invalid username or password.";
    }
}

// Close connection
$conn->close();

?>


$users = [
    ["username" => "admin", "email" => "admin@example.com", "password_hash" => hash('sha256', 'password')],
];


<?php
// Configuration for hashing passwords
define('PASSWORD_SALT', 'your_secret_salt'); // Change this to a secure secret key

function hashPassword($password) {
    return hash('sha256', $password . PASSWORD_SALT);
}

function validateCredentials($username, $password) {
    global $users;
    
    foreach ($users as $user) {
        if ($user['username'] == $username && hashPassword($password) === $user['password_hash']) {
            return $user; // Successful login
        }
    }
    
    return null; // Login failed
}

function loginUser($username, $password) {
    global $users;
    
    // Validate input
    if (empty($username) || empty($password)) {
        throw new Exception('Username and password are required');
    }
    
    $user = validateCredentials($username, $password);
    
    if ($user !== null) {
        // Login successful: Return user data or perform further actions as needed.
        return ['success' => true];
    } else {
        // Login failed
        throw new Exception('Invalid username or password');
    }
}

try {
    $loginData = loginUser($_POST['username'], $_POST['password']);
    echo json_encode($loginData);
} catch (Exception $e) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Login failed: ' . $e->getMessage()]);
}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
<form action="" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Login</button>
</form>

<script src="script.js"></script>
</body>
</html>


<?php
/**
 * User Login Function
 *
 * @param string $username Username to log in with
 * @param string $password Password to log in with
 *
 * @return bool True if login is successful, False otherwise
 */
function loginUser($username, $password) {
  // Connect to database (assuming MySQL)
  $db = new mysqli("localhost", "username", "password", "database_name");

  // Check connection
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  // Prepare and execute query to get user from database
  $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  // Get user data from result
  $user = $result->fetch_assoc();

  // Check if user exists and password is correct
  if ($user && password_verify($password, $user['password'])) {
    // Login successful, return true
    return true;
  } else {
    // Login failed, return false
    return false;
  }

  // Close database connection
  $db->close();
}

// Example usage:
$username = "example_username";
$password = "example_password";

if (loginUser($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}
?>


<?php
// Configuration settings
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection to database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate user login credentials
function validateLogin($email, $password) {
    global $conn;
    
    // Query database for user with matching email and password
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);
    
    // Check if query returned any results
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Function to login user and set session variables
function loginUser($email, $password) {
    global $conn;
    
    // Validate login credentials
    if (validateLogin($email, $password)) {
        // Query database for user data
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);
        
        // Get user data from result set
        $row = $result->fetch_assoc();
        
        // Set session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        
        return true;
    } else {
        return false;
    }
}

// Check for POST request and login user if credentials are valid
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Login user
    if (loginUser($email, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid email or password.";
    }
}
?>


/**
 * User login function
 *
 * @param string $username
 * @param string $password
 * @return bool True if the user is authenticated, false otherwise
 */
function authenticateUser($username, $password) {
    // Connect to database
    $db = new mysqli('localhost', 'your_username', 'your_password', 'your_database');

    // Check connection
    if ($db->connect_error) {
        echo "Error connecting to database: " . $db->connect_error;
        return false;
    }

    // Prepare SQL query
    $sql = "SELECT * FROM users WHERE username = ?";

    // Bind parameters
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $username);

    // Execute query
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows == 1) {
            // Fetch user data
            $user = $result->fetch_assoc();

            // Verify password using password_verify function
            if (password_verify($password, $user['password'])) {
                return true;
            }
        }
    }

    // Close database connection and return false
    $db->close();
    return false;
}


// Set username and password variables
$username = "john_doe";
$password = "my_secret_password";

// Call the authenticateUser function
$authenticated = authenticateUser($username, $password);

if ($authenticated) {
    echo "User authenticated successfully!";
} else {
    echo "Authentication failed.";
}


function user_login($username, $password) {
    // Connect to database
    require 'db_config.php'; // Your database configuration file
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare query
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");

    // Bind parameters
    $stmt->bind_param("s", $username);

    // Execute query
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Fetch user data
        $user_data = $result->fetch_assoc();

        // Check password
        if (password_verify($password, $user_data['password'])) {
            return array(
                'id' => $user_data['id'],
                'username' => $user_data['username']
            );
        } else {
            echo "Incorrect password";
            return false;
        }
    } else {
        echo "Query failed";
        return false;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    return false;
}


$username = 'your_username';
$password = 'your_password';

$user_data = user_login($username, $password);

if ($user_data) {
    echo "User logged in successfully";
} else {
    echo "Login failed";
}


<?php

// Config file for database connection
require_once 'config.php';

function login_user($username, $password) {
    // Check if username or password is empty
    if (empty($username) || empty($password)) {
        return array('error' => 'Both username and password are required.');
    }

    // Prepare SQL query to get user data from database
    $query = "SELECT * FROM users WHERE username = :username";
    try {
        // Execute query with prepared statement
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Check if user exists
        if ($stmt->rowCount() == 0) {
            return array('error' => 'Invalid username or password.');
        }

        // Get user data from database
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Hashed password provided by user, hash it and compare with stored one in DB
        if (password_verify($password, $user_data['password'])) {
            return array('success' => true, 'username' => $user_data['username']);
        } else {
            return array('error' => 'Invalid username or password.');
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo "Error logging in: " . $e->getMessage();
        exit;
    }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = login_user($username, $password);

if ($result['success']) {
    // Login successful!
} elseif (!empty($result['error'])) {
    echo "Error: " . $result['error'];
}
?>


// Create user in database
$username = 'example';
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$query = "INSERT INTO users (username, password) VALUES (:username, :password)";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $password);
$stmt->execute();


// db.php (database connection)
<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


// login.php (user login script)
<?php
require_once 'db.php';

function user_login() {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Password hashing using SHA-256 and SHA-1 for comparison with stored hash.
        $hashed_password = sha1($password);

        $sql = "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'";
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['status'] == 1 && $row['role'] == 'user') {
                    session_start();
                    $_SESSION['username'] = $username;
                    $_SESSION['logged_in'] = true;

                    echo "Login Success!";
                    header('Location: dashboard.php');
                } else {
                    echo "Access denied. User status is not active or role is not user.";
                }
            }
        } else {
            echo "Invalid username and password";
        }

        mysqli_close($conn);
    }
}
?>


// login.html (example login form)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="login-form">
    <input type="text" name="username" placeholder="Username"><br><br>
    <input type="password" name="password" placeholder="Password"><br><br>
    <button type="submit">Login</button>
</form>

<?php
require_once 'db.php';
user_login();
?>
</body>
</html>


<?php
// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
    // Hash password for comparison (using bcrypt)
    $hashed_password = hash('sha256', $password);

    // Query database to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Retrieve the row from the result
        $row = $result->fetch_assoc();

        // Compare hashed password with stored hash
        if (hash('sha256', $row['password']) == $hashed_password) {
            // User authenticated successfully
            return true;
        } else {
            echo "Incorrect password";
            return false;
        }
    } else {
        echo "User not found";
        return false;
    }

    // Close database connection
    $conn->close();
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login_user($username, $password)) {
        echo "Login successful!";
        // Redirect user to dashboard or other secure page
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Login failed";
    }
}

?>


<?php

// Configuration settings
$config = array(
  'database' => 'your_database_name',
  'username' => 'your_database_username',
  'password' => 'your_database_password'
);

// Connect to database
function connectToDatabase() {
  $connection = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
  if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
  }
  return $connection;
}

// Login function
function login($username, $password) {
  // Connect to database
  $conn = connectToDatabase();
  
  // SQL query to select user from database where username and password match
  $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  
  // Execute the query
  $result = $conn->query($query);
  
  // If result is true, then login is successful
  if ($result->num_rows > 0) {
    return true;
  } else {
    return false;
  }
}

// Check for user input on submit of login form
if (isset($_POST['username']) && isset($_POST['password'])) {
  
  // Assign user input to variables
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  // Validate user input
  if (empty($username) || empty($password)) {
    echo "Please enter both username and password.";
  } else {
    // Check for successful login
    $loginStatus = login($username, $password);
    
    if ($loginStatus == true) {
      // Login is successful
      session_start();
      $_SESSION['logged_in'] = true;
      $_SESSION['username'] = $username;
      header("Location: index.php");
      exit;
    } else {
      echo "Invalid username or password.";
    }
  }
}

?>


<?php

function loginUser($username, $password) {
  // Database connection settings
  $host = 'localhost';
  $db_name = 'mydatabase';
  $user = 'myuser';
  $pass = 'mypass';

  try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL query to retrieve user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");

    // Bind parameters
    $stmt->bindParam(':username', $username);

    // Execute the query
    $stmt->execute();

    // Fetch user data
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_data && password_verify($password, $user_data['password'])) {
      return true; // Login successful
    } else {
      return false; // Invalid credentials
    }

  } catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
    return false;
  }
}

?>


<?php

// Get user input from login form
$username = $_POST['username'];
$password = $_POST['password'];

// Call the loginUser function
if (loginUser($username, $password)) {
  echo 'Login successful!';
} else {
  echo 'Invalid username or password';
}

?>


<?php

// Database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;

    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare query to select user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch user data
    $user_data = $result->fetch_assoc();

    // Check if user exists and password matches
    if ($user_data && password_verify($password, $user_data['password'])) {
        // Login successful, set session variables
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['logged_in'] = true;
        return true;
    } else {
        // Login failed, display error message
        echo "Invalid username or password";
        return false;
    }
}

// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    login_user();
} else {
    // Display login form
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
}

// Close database connection
$conn->close();

?>


<?php

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function dbConnect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function userLogin($username, $password) {
    // Connect to the database
    $conn = dbConnect();

    // SQL query to select the user from the database
    $sql = "SELECT * FROM users WHERE username = '$username'";

    // Execute the query and get the result
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row['password'])) {
            return array(
                'success' => true,
                'username' => $row['username']
            );
        } else {
            return array('success' => false, 'error' => 'Invalid password');
        }
    } else {
        return array('success' => false, 'error' => 'User not found');
    }

    // Close the database connection
    $conn->close();
}

?>


// Define the username and password
$username = $_POST['username'];
$password = $_POST['password'];

// Call the userLogin function
$result = userLogin($username, $password);

// Check if the login was successful
if ($result['success']) {
    echo "Welcome, " . $result['username'] . "! You have successfully logged in.";
} else {
    echo "Error: " . $result['error'];
}


<?php
require 'config.php'; // database connection settings

// Check if the form has been submitted
if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Validate input
  if (empty($username) || empty($password)) {
    die('Please fill in all fields');
  }

  // Hash password for comparison
  $hashed_password = hash('sha256', $password);

  // Query database to check user credentials
  $query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) == 1) {
    // User authenticated successfully
    $_SESSION['user_id'] = $result->fetch_assoc()['id'];
    header('Location: dashboard.php');
    exit();
  } else {
    die('Invalid username or password');
  }
}

// Display login form if not submitted
?>
<form method="post">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username"><br><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password"><br><br>
  <button type="submit" name="submit">Login</button>
</form>


<?php
// Database connection settings
$host = 'localhost';
$db_name = 'mydatabase';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function login($username, $password) {
    // Validate input
    if (empty($username)) {
        return 'Username cannot be empty';
    }
    if (empty($password)) {
        return 'Password cannot be empty';
    }

    // Query database for user
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);

    if ($result->num_rows == 1) {
        $user_data = $result->fetch_assoc();

        // Hashed password comparison (for secure login)
        if (password_verify($password, $user_data['password'])) {
            return true;
        } else {
            return 'Invalid username or password';
        }
    } else {
        return 'Username not found';
    }

    // Close database connection
    $mysqli->close();
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = login($username, $password);

if ($result === true) {
    echo "Logged in successfully!";
} else {
    echo $result;
}

?>


function login($username, $password) {
    // Define the users array
    $users = [
        "user1" => ["password" => "hashed_password1"],
        "user2" => ["password" => "hashed_password2"],
        // Add more users here...
    ];

    // Check if username exists in users array
    if (array_key_exists($username, $users)) {
        // Compare input password with hashed password
        return password_verify($password, $users[$username]["password"]);
    }

    // If username not found or passwords do not match
    return false;
}


function register($username, $email, $password) {
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Add new user to users array
    $users[$username] = ["email" => $email, "password" => $hashedPassword];
}


// Register a new user
register("new_user", "user@example.com", "password123");

// Attempt to login with the new user's credentials
if (login("new_user", "password123")) {
    echo "Login successful!";
} else {
    echo "Invalid username or password";
}


<?php

// Database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a PDO object for database interaction
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
} catch (PDOException $e) {
    echo "Error: Could not connect to the database.";
    exit;
}

function login($username, $password) {
    global $pdo;

    // Prepare and execute a query to retrieve the user's data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the result
    $user_data = $stmt->fetch();

    if ($user_data) {
        // Check the password
        if (password_verify($password, $user_data['password'])) {
            return true;
        } else {
            echo "Incorrect password.";
            return false;
        }
    } else {
        echo "Username not found.";
        return false;
    }

    // If we reach this point, it means something went wrong
    echo "An error occurred during login.";
    return false;
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login($username, $password)) {
        // Login successful! Redirect to a protected page.
        header('Location: protected-page.php');
        exit;
    }
}

?>


<form action="login.php" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Login</button>
</form>


<?php

// Configuration settings
$databaseHost = 'localhost';
$databaseUsername = 'your_username';
$databasePassword = 'your_password';
$databaseName = 'your_database_name';

// Connect to database
$conn = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
    // Hash password for comparison
    $hashed_password = hash('sha256', $password);

    // Query database to retrieve user data
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if ($hashed_password == $row['password']) {
                // User authenticated, return true
                return true;
            }
        }
    }

    // User not found or password incorrect
    return false;
}

// Example usage:
$username = 'example_user';
$password = 'example_password';

if (login_user($username, $password)) {
    echo "User logged in successfully!";
} else {
    echo "Invalid username or password.";
}

$conn->close();

?>


function login_user($username, $password) {
    // Hash password using password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query database to retrieve user data
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if (password_verify($hashed_password, $row['password'])) {
                // User authenticated, return true
                return true;
            }
        }
    }

    // User not found or password incorrect
    return false;
}


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user credentials
function login_user($username, $password) {
    global $conn;
    
    // SQL query to select user data
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute query and get result
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // User found, return true
        return true;
    } else {
        // User not found, return false
        return false;
    }
}

// Function to login user and create session
function do_login($username, $password) {
    global $conn;
    
    // Check if user credentials are valid
    if (login_user($username, $password)) {
        // Get user data from database
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        
        // Fetch user data as an array
        $user_data = $result->fetch_assoc();
        
        // Create session variables
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['email'] = $user_data['email'];
        $_SESSION['role'] = $user_data['role'];
        
        // Redirect to protected page
        header('Location: protected_page.php');
        exit();
    } else {
        // Invalid credentials, display error message
        echo "Invalid username or password";
    }
}

?>


<?php
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login_form.html');
    exit();
}

// Display protected page content
echo "Welcome, " . $_SESSION['username'] . "!";

?>


$users = [
    'user1' => ['password' => 'pass123', 'role' => 'admin'],
    'user2' => ['password' => 'pass456', 'role' => 'user']
];


function login($username, $password) {
    global $users;
    
    if (isset($users[$username])) {
        $storedPassword = $users[$username]['password'];
        $storedRole = $users[$username]['role'];
        
        // Check if the provided password matches the stored one
        if (hash('sha256', $password) === hash('sha256', $storedPassword)) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $storedRole;
            
            return true;
        } else {
            echo "Invalid password";
            return false;
        }
    } else {
        echo "User not found";
        return false;
    }
}


// Set the users array
$users = [
    'user1' => ['password' => 'pass123', 'role' => 'admin'],
    'user2' => ['password' => 'pass456', 'role' => 'user']
];

// Initialize session
session_start();

// User login attempt
$username = 'user1';
$password = 'pass123';

if (login($username, $password)) {
    echo "Login successful";
} else {
    echo "Login failed";
}


<?php
/**
 * User Login Function
 *
 * @param string $username The username entered by the user.
 * @param string $password  The password entered by the user.
 *
 * @return int|false Session ID if login is successful, false otherwise.
 */
function login($username, $password) {
    // Database connection settings (replace with your own)
    $host = 'localhost';
    $db_name = 'users';
    $user = 'root';
    $pass = '';

    try {
        // Connect to the database
        $conn = new PDO('mysql:host=' . $host . ';dbname=' . $db_name, $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query the database for the user's record
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Get the user's password from the database
        $user_record = $stmt->fetch();
        if ($user_record) {
            // Check if the password matches
            if (password_verify($password, $user_record['password'])) {
                // Create a session ID and store it in the session
                $_SESSION['session_id'] = uniqid();
                return $_SESSION['session_id'];
            } else {
                throw new Exception('Incorrect password');
            }
        } else {
            throw new Exception('User not found');
        }

    } catch (PDOException $e) {
        // Handle any database-related errors
        echo 'Error connecting to the database: ' . $e->getMessage();
    } catch (Exception $e) {
        // Handle login-specific errors
        echo 'Login failed: ' . $e->getMessage();
    }
}


// Set up session variables if not already set
if (!isset($_SESSION)) {
    session_start();
}

// Check if the user has submitted the form with username and password
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the values from the form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the login function with the provided credentials
    $session_id = login($username, $password);
    if ($session_id !== false) {
        echo 'Login successful!';
        // You can now access user-specific content or perform actions based on their session ID
    } else {
        echo 'Invalid username or password';
    }
}


function login($username, $password) {
  // Connect to database (for production environments, use a more secure connection method)
  $conn = new mysqli("localhost", "username", "password", "database");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare SQL query
  $sql = "SELECT * FROM users WHERE username = ?";

  // Bind parameters to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);

  // Execute query and retrieve user data
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      if (password_verify($password, $row['password'])) {
        return array(
          'success' => true,
          'username' => $row['username'],
          'user_id' => $row['id']
        );
      }
    }
  }

  // If user not found or password is incorrect
  return array('success' => false);
}

// Example usage:
$username = $_POST["username"];
$password = $_POST["password"];

$result = login($username, $password);

if ($result['success']) {
  echo "Logged in successfully!";
} else {
  echo "Invalid username or password.";
}


<?php
// Database connection settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get user input from form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to check if username and password match
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    // Check if user exists and password is correct
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            session_start();
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $row['username'];
            header("Location: dashboard.php");
            exit;
        }
    } else {
        echo "Invalid username or password";
    }
}

// Check if form has been submitted
if (isset($_POST['submit'])) {
    login_user();
} else {
    // Display login form
    ?>
    <form action="" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" name="submit" value="Login">
    </form>
    <?php
}
?>


<?php
// Configuration
$database = 'mysql';
$username = 'root';
$password = '';
$dbname = 'users';

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash password for comparison
    $hashed_password = hash('sha256', $password);

    // Query database to check if username and password match
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful, redirect user to dashboard
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Invalid username or password";
    }
} else {
    // Display login form
    ?>
    <form action="" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" name="submit" value="Login">
    </form>
    <?php
}

// Close connection
$conn->close();
?>


<?php
// Configuration settings
$database_host = 'localhost';
$database_name = 'mydatabase';
$database_username = 'root';
$database_password = '';

// Create connection to database
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set up form variables
$username = $_POST['username'];
$password = $_POST['password'];

// Prevent SQL injection by escaping input
$username = mysqli_real_escape_string($conn, $username);
$password = mysqli_real_escape_string($conn, $password);

// Query database to check if user exists and password is correct
$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($query);

// If query was successful...
if ($result->num_rows > 0) {
    // User exists and password is correct, log them in!
    session_start();
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;

    // Redirect to protected page
    header('Location: protected.php');
    exit();
} else {
    // Incorrect username or password, display error message
    echo 'Incorrect username or password';
}

// Close database connection
$conn->close();

?>


<?php
// Check if user is logged in
if (!isset($_SESSION['loggedin'])) {
    // If not, redirect to login page
    header('Location: login.php');
    exit();
}

// Display protected content here...
echo 'Welcome, ' . $_SESSION['username'] . '!';

?>


<?php
/**
 * User Login Function
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool Whether the login was successful.
 */
function login($username, $password) {
    // Database connection settings
    $dbHost = 'localhost';
    $dbName = 'your_database_name';
    $user = 'your_database_user';
    $pass = 'your_database_password';

    try {
        // Connect to database
        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $user, $pass);

        // Check if username exists in database
        $stmt = $conn->prepare('SELECT * FROM users WHERE username=:username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch user data from database
        $userData = $stmt->fetch();

        // If no user found, return false
        if (!$userData) {
            return false;
        }

        // Verify password using password_hash and password_verify functions
        if (password_verify($password, $userData['password'])) {
            // Login successful, return true with user data
            return array('success' => true, 'user_data' => $userData);
        } else {
            // Incorrect password, return false
            return false;
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


$username = 'example_user';
$password = 'example_password';

$result = login($username, $password);

if ($result) {
    echo "Login successful!";
    print_r($result['user_data']);
} else {
    echo "Incorrect username or password.";
}

