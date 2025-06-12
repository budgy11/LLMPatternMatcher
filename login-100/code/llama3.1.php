
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

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

// Connect to database
function connect_to_db() {
  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to check user login credentials
function check_login($username, $password) {
  $db = connect_to_db();
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = $db->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      if (password_verify($password, $row['password'])) {
        // Login successful
        return array('success' => true, 'user_id' => $row['id'], 'username' => $row['username']);
      } else {
        // Password is incorrect
        return array('success' => false);
      }
    }
  } else {
    // User does not exist
    return array('success' => false);
  }
}

// Function to login a user
function login_user($username, $password) {
  $login_result = check_login($username, $password);
  if ($login_result['success']) {
    // Store user session data
    $_SESSION['user_id'] = $login_result['user_id'];
    $_SESSION['username'] = $login_result['username'];
    return array('message' => 'Login successful!');
  } else {
    // Login failed
    return array('error_message' => 'Invalid username or password');
  }
}

// Example usage:
$username = 'example_user';
$password = 'password123';

$login_result = login_user($username, $password);

if ($login_result['success']) {
  echo "Login successful! User ID: " . $_SESSION['user_id'];
} else {
  echo "Error: " . $login_result['error_message'];
}


// database.php (example connection file)
<?php
  $host = 'localhost';
  $username = 'your_username';
  $password = 'your_password';
  $db_name = 'your_database';

  // Create connection
  $conn = new mysqli($host, $username, $password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>


// login.php (example login file)
<?php
  require_once 'database.php';

  // Get user input
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Prepare SQL query
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      // Validate user data (e.g. ensure it matches the stored hash)
      if (password_verify($password, $row['password'])) {
        session_start();
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit;
      }
    }
  } else {
    echo "Invalid username or password.";
  }

  // Close connection
  $conn->close();
?>


// register.php (example registration file)
<?php
  require_once 'database.php';

  // Get user input
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Prepare SQL query to create new user
  $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
  $conn->query($sql);

  echo "User created successfully.";
?>


<?php

// Connect to database (replace with your own connection method)
$conn = new mysqli("localhost", "username", "password", "database");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;

    // Get form data
    if (isset($_POST["submit"])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check for empty fields
        if (empty($email) || empty($password)) {
            echo "Email and password are required.";
            return;
        }

        // Query database to check if user exists and password is correct
        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result = mysqli_query($conn, $sql);

        // If query returns a result, the user has been found and logged in successfully
        if ($result->num_rows == 1) {
            $_SESSION['user_id'] = $result->fetch_assoc()['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            echo "Invalid email or password.";
        }
    }

    // If form is not submitted, display login form
    if (!isset($_POST["submit"])) {
        echo '
        <form action="" method="post">
            Email: <input type="email" name="email"><br>
            Password: <input type="password" name="password"><br>
            <button type="submit" name="submit">Login</button>
        </form>';
    }
}

// Run the login function
login_user();

?>


<?php

// Define database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a function to connect to the database
function db_connect() {
    global $db_host, $db_username, $db_password, $db_name;
    try {
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
        return $conn;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}

// Define the login function
function login($username, $password) {
    global $db_host, $db_username, $db_password, $db_name;

    // Connect to database
    $conn = db_connect();

    if ($conn === null) return false; // Failed to connect

    try {
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result !== false) {
            // Compare entered password with stored hash
            $stored_password_hash = $result['password'];
            $entered_password_hash = hash('sha256', $password); // Use SHA-256 for password hashing

            if (hash_equals($stored_password_hash, $entered_password_hash)) {
                return true; // Login successful
            } else {
                echo "Invalid username or password";
            }
        } else {
            echo "Invalid username or password";
        }

    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    } finally {
        $conn = null;
    }

    return false; // Login failed
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

if (login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password";
}


<?php
// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Database connection
function db_connect() {
  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// User login function
function user_login($username, $password) {
  // Prepare database query
  $conn = db_connect();
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  // Fetch user data
  $user_data = $result->fetch_assoc();

  // Check if user exists and password is correct
  if ($user_data && password_verify($password, $user_data['password_hash'])) {
    return true;
  } else {
    return false;
  }
}

// Form validation function
function validate_login_form() {
  // Get form data
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Validate input fields
  if (empty($username) || empty($password)) {
    return array(
      'error' => true,
      'message' => 'Please enter both username and password.'
    );
  }

  // Hash the password for comparison
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Call user login function
  if (user_login($username, $hashed_password)) {
    return array(
      'error' => false,
      'message' => 'Login successful!'
    );
  } else {
    return array(
      'error' => true,
      'message' => 'Invalid username or password.'
    );
  }
}
?>


<?php
include_once 'login.php';

if (isset($_POST['submit'])) {
  $result = validate_login_form();
  if ($result['error']) {
    echo '<p style="color: red;">' . $result['message'] . '</p>';
  } else {
    // User logged in successfully, redirect to protected page
    header('Location: protected_page.php');
    exit;
  }
}
?>


function login_user($username, $password) {
    // Connect to the database
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

    // Prepare the query
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");

    // Bind parameters
    $stmt->bindParam(':username', $username);

    // Execute the query
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch();

    // Check if the user exists and the password is correct
    if ($result && password_verify($password, $result['password'])) {
        return true;
    } else {
        return false;
    }
}


$username = 'your_username';
$password = 'your_password';

if (login_user($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


// database.php

<?php
class Database {
    private $host = 'localhost';
    private $databaseName = 'your_database_name';
    private $username = 'your_username';
    private $password = 'your_password';

    public function connect() {
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->databaseName, $this->username, $this->password);
            return $this->conn;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }
    }

    public function close() {
        $this->conn = null;
    }
}

// login.php

<?php
require_once 'database.php';

function checkUserCredentials($username, $password) {
    try {
        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare('SELECT * FROM users WHERE username=:username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch();

        if ($user) {
            // verify password
            $passwordVerified = hash('sha256', $password, true) === substr($user['password'], 0, 64);

            if ($passwordVerified) {
                return array(
                    'id' => $user['id'],
                    'username' => $user['username']
                );
            }
        }

        return false;
    } catch (PDOException $e) {
        echo 'Error checking user credentials: ' . $e->getMessage();
        exit;
    } finally {
        $db->close();
    }
}

function login($username, $password) {
    try {
        $credentials = checkUserCredentials($username, $password);
        
        if ($credentials) {
            // successful login
            session_start();
            $_SESSION['user_id'] = $credentials['id'];
            $_SESSION['username'] = $credentials['username'];

            return array('success' => true, 'message' => 'Logged in successfully');
        } else {
            throw new Exception('Invalid username or password');
        }
    } catch (Exception $e) {
        return array(
            'success' => false,
            'error_message' => $e->getMessage()
        );
    }
}

// Usage example
$login = login('your_username', 'your_password');

if ($login['success']) {
    echo 'Logged in successfully';
} else {
    echo $login['error_message'];
}


<?php
// Configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_database_username';
$password = 'your_database_password';

try {
    // Connect to database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Process login form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Hash password for comparison (use a library like BCrypt or Argon2)
        $hashedPassword = hash('sha256', $password);

        // Prepare SQL query
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute query and retrieve user data
        if ($stmt->execute()) {
            $user = $stmt->fetch();
            if ($user) {
                // User found, log them in
                $_SESSION['uid'] = $user['id'];
                header('Location: dashboard.php');
                exit;
            } else {
                echo 'Invalid email or password.';
            }
        } else {
            echo 'Error logging in. Please try again later.';
        }
    }

} catch (PDOException $e) {
    echo 'Error connecting to database: ' . $e->getMessage();
}

// Close connection
$conn = null;
?>


<?php
require_once('login.php');

if (!isset($_SESSION['uid'])) : ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Email: <input type="text" name="email"><br><br>
        Password: <input type="password" name="password"><br><br>
        <button type="submit">Login</button>
    </form>
<?php endif; ?>


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

// Create database connection
function createConnection() {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    return $conn;
}

// Hashing password function (using SHA-256)
function hashPassword($plainText) {
    return sha256( $plainText );
}

// User login function
function userLogin($username, $password) {
    try {
        // Create database connection
        $conn = createConnection();

        // Prepare SQL query to retrieve user data from database
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $conn->prepare( $query );
        $stmt->bindParam(':username', $username);

        // Execute query and fetch result
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            // Compare hashed password with input password
            if (hashPassword($password) === $result['password']) {
                // Login successful, return user data
                return array(
                    'username' => $result['username'],
                    'email' => $result['email']
                );
            } else {
                throw new Exception("Invalid username or password");
            }
        } else {
            throw new Exception("Username not found");
        }

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        // Close database connection
        unset($conn);
    }
}

// Example usage:
$username = 'john';
$password = 'password123';

try {
    $userData = userLogin($username, $password);

    if ($userData) {
        echo "Login successful. Welcome, $userData[username]!";
    } else {
        throw new Exception("Invalid login credentials");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
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
  // Database connection settings
  $host = 'localhost';
  $db_name = 'mydatabase';
  $user = 'myuser';
  $pass = 'mypassword';

  // Create a new MySQL connection
  $conn = mysqli_connect($host, $user, $pass, $db_name);

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Get the username and password from the form data
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Prepare the SQL query to select the user's details
  $query = "SELECT * FROM users WHERE username = '$username'";

  // Execute the query
  $result = mysqli_query($conn, $query);

  // Check if a user with that username exists in the database
  if (mysqli_num_rows($result) == 1) {
    // If a user is found, retrieve their details
    $row = mysqli_fetch_array($result);
    $hashed_password = $row['password'];

    // Compare the submitted password with the stored hash
    if (password_verify($password, $hashed_password)) {
      // If the passwords match, log in the user and redirect to a secured page
      session_start();
      $_SESSION['username'] = $username;
      header('Location: securepage.php');
      exit;
    } else {
      echo 'Invalid password';
    }
  } else {
    echo 'User not found';
  }

  // Close the MySQL connection
  mysqli_close($conn);
?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check username and password
function login_user($username, $password) {
    // SQL query to select user from database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute query
    $result = $conn->query($sql);
    
    // Check if result is true (i.e., user exists and password is correct)
    if ($result->num_rows > 0) {
        // Get user data from database
        while($row = $result->fetch_assoc()) {
            return array(
                'id' => $row['id'],
                'username' => $row['username']
            );
        }
    } else {
        // Return null if user does not exist or password is incorrect
        return null;
    }
}

// Check for POST request and call login_user function
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Call login_user function
    $user_data = login_user($username, $password);
    
    if ($user_data !== null) {
        // If user is logged in, set session variables and redirect to dashboard
        session_start();
        $_SESSION['id'] = $user_data['id'];
        $_SESSION['username'] = $user_data['username'];
        
        header('Location: dashboard.php');
        exit;
    } else {
        // Display login error message if user is not logged in
        echo "Invalid username or password";
    }
}

// Close connection to database
$conn->close();

?>


<?php
// Include login function file
require_once 'login.php';

// Check for GET request and display login form
if (isset($_GET['action']) && $_GET['action'] == 'login') {
    ?>
    <form action="" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
} else {
    // Display login button and link to registration page
    ?>
    <a href="?action=login">Login</a> | <a href="register.php">Register</a>
    <?php
}
?>


<?php

// Configuration settings
$login_table = 'users';
$username_field = 'username';
$password_field = 'password';

function validate_credentials($username, $password) {
    global $login_table, $username_field, $password_field;

    // Escape special characters to prevent SQL injection
    $username = mysqli_real_escape_string($GLOBALS['mysqli'], $username);
    $password = mysqli_real_escape_string($GLOBALS['mysqli'], $password);

    // Retrieve user data from database
    $query = "SELECT * FROM `$login_table` WHERE `$username_field` = '$username' AND `$password_field` = PASSWORD('$password')";
    $result = mysqli_query($GLOBALS['mysqli'], $query);

    if (mysqli_num_rows($result) == 1) {
        return true;
    } else {
        return false;
    }
}

function login_user($username, $password) {
    global $login_table, $username_field;

    // Validate credentials
    if (validate_credentials($username, $password)) {
        // Retrieve user data from database
        $query = "SELECT * FROM `$login_table` WHERE `$username_field` = '$username'";
        $result = mysqli_query($GLOBALS['mysqli'], $query);

        if ($row = mysqli_fetch_assoc($result)) {
            // Store user data in session variables
            $_SESSION['username'] = $row[$username_field];
            $_SESSION['id'] = $row['id'];

            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

?>


// Connect to database (assuming mysqli extension is used)
mysqli_connect('localhost', 'username', 'password', 'database');

// Store the connection in a global variable
$GLOBALS['mysqli'] = mysqli_connect('localhost', 'username', 'password', 'database');

// User submits login form with username and password fields
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the login_user function to authenticate user
    if (login_user($username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Invalid username or password";
    }
}


<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
  // Get form data
  if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // SQL query to select user by email and password
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        // User found, start session and redirect to dashboard
        $_SESSION["user_id"] = $row["id"];
        header("Location: dashboard.php");
        exit();
      }
    } else {
      echo "Invalid email or password";
    }
  }

  // Close connection
  $conn->close();
}

// Check if user is already logged in
if (isset($_SESSION["user_id"])) {
  header("Location: dashboard.php");
  exit();
}

// Display login form
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="email">Email:</label>
  <input type="text" id="email" name="email"><br><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password"><br><br>
  <button type="submit" name="submit">Login</button>
</form>


<?php
// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}

// Display dashboard content
?>
<h1>Dashboard</h1>
<p>Welcome, <?php echo $_SESSION["user_id"]; ?></p>


<?php
// Get form data
if (isset($_POST["submit"])) {
  $email = $_POST["email"];
  $password = $_POST["password"];

  // SQL query to insert new user into database
  $sql = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
  $conn->query($sql);

  header("Location: login.php");
  exit();
}

// Display registration form
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="email">Email:</label>
  <input type="text" id="email" name="email"><br><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password"><br><br>
  <button type="submit" name="submit">Register</button>
</form>


<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);


<?php
// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>


<?php
require_once 'config.php';

// Define the login form data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute query to verify user credentials
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
        $user_data = $stmt->fetch();

        // Check if user credentials are valid
        if ($user_data) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;

            // Redirect to protected area (e.g., dashboard)
            header('Location: dashboard.php');
            exit();
        } else {
            echo 'Invalid username or password.';
        }
    } else {
        echo 'Error verifying user credentials.';
    }
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
?>


// Define the database connection parameters
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create a PDO object to connect to the database
function getConnection() {
    try {
        $conn = new PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName, $dbUsername, $dbPassword);
        return $conn;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit();
    }
}

// Function to login a user
function loginUser($username, $password) {
    try {
        // Connect to the database
        $conn = getConnection();

        // Prepare the SQL query
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username AND password = :password');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        // Execute the query and get the result
        $stmt->execute();
        $result = $stmt->fetch();

        // If the user exists, close the connection and return true
        if ($result) {
            $conn = null;
            return true;
        } else {
            // If the user does not exist or the password is incorrect, close the connection and return false
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo 'An error occurred: ' . $e->getMessage();
        exit();
    }
}

// Example usage:
$username = 'your_username';
$password = 'your_password';

if (loginUser($username, $password)) {
    echo 'Login successful!';
} else {
    echo 'Invalid username or password.';
}


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Error handling function
function error($message, $code = 1) {
    http_response_code($code);
    echo json_encode(array('error' => $message));
    exit;
}

// Database connection
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
} catch (PDOException $e) {
    error('Database connection failed: ' . $e->getMessage());
}

// Form validation function
function validate_form($data) {
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        error('Missing required fields', 400);
    }
    
    if (empty($_POST['username']) || empty($_POST['password'])) {
        error('Username and password are required');
    }
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        error('Invalid username', 400);
    }
    
    if (strlen($password) < 8) {
        error('Password must be at least 8 characters long', 400);
    }
}

// Hashing and verifying password function
function hash_password($plain_text) {
    return crypt($plain_text, 'your_salt');
}

function verify_password($hashed, $plain_text) {
    if (hash_equals($hashed, crypt($plain_text, 'your_salt'))) {
        return true;
    } else {
        return false;
    }
}

// Login function
function login_user() {
    validate_form($_POST);
    
    // Retrieve user data from database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $_POST['username']);
    $stmt->execute();
    
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user_data) {
        error('Invalid username or password');
    }
    
    // Verify password
    if (verify_password($user_data['password_hash'], $_POST['password'])) {
        // Successful login, create session and redirect to dashboard
        session_start();
        $_SESSION['username'] = $user_data['username'];
        header('Location: /dashboard');
        exit;
    } else {
        error('Invalid username or password', 400);
    }
}

// Login form handler
if (isset($_POST['login'])) {
    login_user();
} elseif (isset($_GET['logout'])) {
    // Destroy session and redirect to login page
    session_destroy();
    header('Location: /login');
    exit;
}
?>


<?php
require 'user_login.php';

// Display login form if user is not logged in
if (!isset($_SESSION['username'])) {
?>
    <form action="" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <button type="submit" name="login">Login</button>
    </form>
<?php
} else {
?>
    <!-- Display dashboard content -->
    Welcome, <?php echo $_SESSION['username']; ?>
    
    <a href="?logout=true">Logout</a>
<?php
}
?>


function login_user($username, $password) {
  // Connect to database (replace with your own DB connection code)
  require_once 'dbconnect.php';
  
  $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = mysqli_query($conn, $query);
  
  if ($result && mysqli_num_rows($result) > 0) {
    // If user exists and password is correct
    return true;
  } else {
    // Return false on failure
    return false;
  }
}

// Example usage:
if (login_user('user1', 'password123')) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}


function login_user($username, $password) {
  // Connect to database (replace with your own DB connection code)
  require_once 'dbconnect.php';
  
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  
  if ($result && mysqli_num_rows($result) > 0) {
    // If user exists and password is correct
    return true;
  } else {
    // Return false on failure
    return false;
  }
}

// Example usage:
if (login_user('user1', 'password123')) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database_name');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define user data array
$user_data = array();

// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query database for user
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Get user data from query result
        while ($row = $result->fetch_assoc()) {
            $user_data[] = array(
                'id' => $row['id'],
                'username' => $row['username'],
                'password' => $row['password']
            );
        }

        // Check if password matches
        if (password_verify($_POST['password'], $user_data[0]['password'])) {
            // Login successful, set session variables
            $_SESSION['username'] = $user_data[0]['username'];
            $_SESSION['id'] = $user_data[0]['id'];

            header("Location: /dashboard.php");
            exit();
        } else {
            // Incorrect password
            echo "Incorrect password";
        }
    } else {
        // User not found
        echo "User not found";
    }
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
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Create a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if the user is already logged in
if (isset($_SESSION['logged_in'])) {
    header("Location: index.php");
    exit;
}

// Get the form data
$username = $_POST['username'] ?? "";
$password = $_POST['password'] ?? "";

// Sanitize the input to prevent SQL injection
$mysqli_username = $conn->real_escape_string($username);
$mysqli_password = password_hash($password, PASSWORD_DEFAULT);

// Query the database for the user's credentials
$query = "SELECT * FROM users WHERE username = '$mysqli_username'";
$result = $conn->query($query);

// Check if the query returned any results
if ($result->num_rows == 1) {
    // Retrieve the row data
    $row = $result->fetch_assoc();
    
    // Compare the hashed password from the database with the provided password
    if (password_verify($mysqli_password, $row['password'])) {
        // Login successful!
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $mysqli_username;
        
        header("Location: index.php");
        exit;
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "User not found.";
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

// Define the connection details for your database
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database_name';

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login() {
  global $conn;
  
  // Get the username and password from the form
  $username = $_POST['username'];
  $password = hash('sha256', $_POST['password']);
  
  // Prepare a SQL query to select the user's details
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
  
  // Bind the parameters
  $stmt->bind_param("ss", $username, $password);
  
  // Execute the query
  $stmt->execute();
  
  // Get the result
  $result = $stmt->get_result();
  
  // Check if a user was found
  if ($result->num_rows > 0) {
    // Login successful, redirect to dashboard or whatever you want to do next
    header("Location: dashboard.php");
    exit;
  } else {
    echo "Invalid username or password";
  }
}

// Call the login function when a form is submitted
if (isset($_POST['login'])) {
  login();
} else {
  // Display a form if no submission has been made yet
?>

<form action="" method="post">
  <input type="text" name="username" placeholder="Username">
  <input type="password" name="password" placeholder="Password">
  <button type="submit" name="login">Login</button>
</form>

<?php
}

// Close the connection to the database
$conn->close();

?>


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Database connection
function connectToDatabase() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Login function
function login($username, $password) {
    // Connect to the database
    $db = connectToDatabase();
    
    // SQL query to select the user from the database
    $sql = "SELECT * FROM users WHERE username='" . $username . "' AND password='" . md5($password) . "'";
    
    // Execute the query and get the result
    $result = $db->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return true; // Login successful
        }
    } else {
        return false; // Login failed
    }
    
    $db->close();
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password";
    }
}

?>


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Database connection
function connectToDatabase() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Login function with prepared statements
function login($username, $password) {
    // Connect to the database
    $db = connectToDatabase();
    
    // SQL query using prepared statements
    $sql = "SELECT * FROM users WHERE username=? AND password=?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ss", $username, md5($password));
    
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        return true; // Login successful
    } else {
        return false; // Login failed
    }
    
    $db->close();
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password";
    }
}

?>


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

// Establish database connection
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function login($username, $password) {
    // Prepare SQL query to retrieve user data
    $stmt = $conn->prepare('SELECT * FROM users WHERE username=:username');
    
    // Bind parameters
    $stmt->bindParam(':username', $username);
    
    // Execute query
    $stmt->execute();
    
    // Fetch result
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Check password using SHA-256 hashing (you should use a more secure method like bcrypt or Argon2)
        if (sha1($password) === sha1($user['password'])) {
            return true;
        } else {
            echo 'Incorrect password';
        }
    } else {
        echo 'User not found';
    }
}

// Example usage:
$username = 'john_doe';
$password = 'password123';

if (login($username, $password)) {
    echo 'Login successful!';
} else {
    echo 'Failed to login';
}


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database_name');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
  // Prepare query
  $sql = "SELECT * FROM users WHERE username = ?";

  // Execute query with prepared statement
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);

  // Get result
  if ($stmt->execute()) {
    $result = $stmt->get_result();
  } else {
    echo "Error executing query: " . $conn->error;
    return false;
  }

  // Fetch user data
  $user_data = $result->fetch_assoc();

  // Check password
  if (password_verify($password, $user_data['password'])) {
    session_start();
    $_SESSION['username'] = $username;
    $_SESSION['logged_in'] = true;

    return true;
  } else {
    echo "Invalid username or password";
    return false;
  }
}

// Close connection
$conn->close();

?>


<?php

require_once 'login.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (login($username, $password)) {
    header('Location: dashboard.php');
    exit;
  } else {
    echo "Invalid login attempt";
  }
}

?>

<form action="" method="post">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username"><br><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password"><br><br>
  <input type="submit" value="Login">
</form>


// Users Array
$users = [
    'admin' => ['password' => '$2y$10$NjQ4ZTBlMmYzMDc3NmJiZS1wY0d6', 'name' => 'Admin'],
    'user'  => ['password' => '$2y$10$tWJvVgF2RlKtRkZfY0xuOu', 'name' => 'User']
];

function login($username, $password) {
    // Check if username exists
    if (!isset($users[$username])) {
        return false;
    }

    // Get stored password hash and user details
    $storedPassword = $users[$username]['password'];
    $userDetails   = $users[$username];

    // Compare provided password with stored hash using password_verify()
    if (password_verify($password, $storedPassword)) {
        // If passwords match, return user details
        return $userDetails;
    } else {
        // Password mismatch, return false
        return false;
    }
}


// Login form handler
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call login() function and store result in $user variable
    $user = login($username, $password);

    if ($user) {
        // Passwords match, redirect to secure area or display welcome message
        echo 'Welcome, ' . $user['name'] . '!';
    } else {
        // Password mismatch, display error message
        echo 'Incorrect username or password.';
    }
}


<?php

// Sample user data (DO NOT USE IN PRODUCTION)
$users = [
    'user1' => 'password123',
    'user2' => 'password456'
];

function login($username, $password) {
    // Check if the username and password match a stored account
    if (!isset($users[$username]) || $users[$username] !== $password) {
        return null;  // Return null on invalid credentials
    }

    // Create a session for the user
    $_SESSION['user'] = $username;

    return true;
}

// Example usage:
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (login($username, $password)) {
        header('Location: loggedin.php');
        exit();
    } else {
        echo 'Invalid username or password';
    }
}

?>

<form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" name="login" value="Login">
</form>



<?php
// Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Function to check user login credentials
function check_login($username, $password) {
    // Prepare query
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    
    // Bind parameters
    $stmt->bind_param("ss", $username, $password);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    // Check if user exists and login credentials are correct
    return ($result && $result->num_rows > 0);
}

// Function to get user data (optional)
function get_user_data($username) {
    // Prepare query
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    
    // Bind parameter
    $stmt->bind_param("s", $username);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    // Return user data if user exists
    return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : false;
}

// Check login form submission
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check if username and password are not empty
    if (!empty($username) && !empty($password)) {
        // Hash password for comparison (optional)
        $hashed_password = md5($password);
        
        // Check user login credentials
        if (check_login($username, $hashed_password)) {
            // User logged in successfully!
            echo "Logged in successfully!";
            
            // Get user data (optional)
            $user_data = get_user_data($username);
            echo "User data: ";
            print_r($user_data);
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Please enter both username and password.";
    }
}

// Close database connection
$mysqli->close();
?>


<?php

// Configuration
$databaseHost = 'localhost';
$databaseName = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Database Connection Settings
$conn = mysqli_connect($databaseHost, $username, $password, $databaseName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function checkLogin($username, $password) {
    global $conn;

    // Query for user
    $query = "SELECT id FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $userData = mysqli_fetch_assoc($result);
        
        // Check hashed password against input password
        if (password_verify($password, $userData['id'])) {
            return true;
        }
    }

    return false;
}

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password before checking
    $hashedPassword = hashPassword($password);

    if (checkLogin($username, $hashedPassword)) {
        echo "Login Successful!";
    } else {
        echo "Invalid username or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>
    <input type="submit" name="submit" value="Login">
</form>

</body>
</html>


function login_user($username, $password) {
  // Connect to the database (replace with your own connection method)
  $conn = new mysqli("localhost", "username", "password", "database");

  // Check if the username exists in the database
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = $conn->query($query);
  
  // If the user exists, check their password
  if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    
    // Hashed passwords are stored in the database
    // We'll compare the provided password with the hashed one using a salt (for security)
    $password_hash = hash('sha256', "$username" . $password);
    
    if ($password_hash === $user_data['password_hash']) {
      return array(
        'success' => true,
        'message' => "Login successful!",
        'user_id' => $user_data['id'],
        'username' => $user_data['username']
      );
    } else {
      return array('success' => false, 'message' => "Incorrect password.");
    }
  } else {
    // If the user doesn't exist in the database
    return array('success' => false, 'message' => "User not found.");
  }

  // Close the connection to the database (for cleanliness)
  $conn->close();
}


$username = $_POST['username'];
$password = $_POST['password'];

$user_login_result = login_user($username, $password);

// Display the result (for demonstration purposes)
echo json_encode($user_login_result);


<?php

// Configuration settings
$databaseHost = 'localhost';
$databaseName = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Connect to database
$conn = new mysqli($databaseHost, $username, $password, $databaseName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
    global $conn;
    
    // SQL query to check username and password
    $sql = "SELECT * FROM users WHERE username='$username' AND password=MD5('$password')";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return true; // User exists and password is correct
    } else {
        return false; // User does not exist or password is incorrect
    }
}

function register($username, $password) {
    global $conn;
    
    // SQL query to insert new user into database
    $sql = "INSERT INTO users (username, password) VALUES ('$username', MD5('$password'))";
    
    if ($conn->query($sql) === TRUE) {
        return true; // User created successfully
    } else {
        return false; // Failed to create user
    }
}

// Login function call
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}

// Register function call
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (register($username, $password)) {
        echo "User created successfully!";
    } else {
        echo "Failed to create user.";
    }
}

?>


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
// Configuration settings
$servername = "localhost";
$username = "root"; // or your MySQL username
$password = ""; // or your MySQL password
$dbname = "your_database_name";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get user input
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if the email and password fields are not empty
    if (empty($email) || empty($password)) {
        return array("error" => "Email or Password field is required.");
    }

    // Query to check for valid user credentials
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // If the query returns a result, it means the user is valid.
        while ($row = $result->fetch_assoc()) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["email"] = $row["email"];
            return array("success" => "You are now logged in.");
        }
    } else {
        // If the query does not return a result, it means the user is invalid.
        return array("error" => "Invalid email or password.");
    }

    // Close database connection
    $conn->close();
}

// Get POST request data
$email = $_POST["email"];
$password = $_POST["password"];

// Call the login_user function and store its response in a variable
$response = login_user();

// Print the response
if (isset($response["error"])) {
    echo json_encode(array("error" => $response["error"]));
} else {
    header("Location: dashboard.php");
    exit();
}


// db.php (database connection file)
$host = 'localhost';
$dbname = 'users_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}

// login.php (login form and function)
<?php
require_once 'db.php';

function authenticateUser($email, $password) {
    global $conn;

    // Check if email and password are not empty
    if (empty($email) || empty($password)) {
        return array('error' => 'Please enter both email and password');
    }

    try {
        // Query to retrieve user data from database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return array('success' => true, 'user_id' => $user['id']);
        } else {
            return array('error' => 'Invalid email or password');
        }
    } catch (PDOException $e) {
        echo "Error querying database: " . $e->getMessage();
    }
}

// Form handling
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = authenticateUser($email, $password);

    if ($result['success']) {
        // User is authenticated, redirect to protected page or set session variable
        $_SESSION['user_id'] = $result['user_id'];
        header('Location: protected-page.php');
        exit;
    } elseif ($result['error']) {
        echo "Error: " . $result['error'];
    }
}

// Login form
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <button type="submit">Login</button>
</form>


<?php

// Include database connection settings
require_once 'db.php';

function login($username, $password) {
  // Hash the input password for comparison
  $hashed_password = hash('sha256', $password);

  // Query database to retrieve user data
  $query = "SELECT * FROM users WHERE username = '$username' AND password_hash = '$hashed_password'";
  $result = mysqli_query($db, $query);

  if ($result->num_rows == 1) {
    // Login successful, return user ID and session variables
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];

    return true;
  } else {
    // Login failed, display error message
    echo 'Invalid username or password';
    return false;
  }
}

?>


<?php

// Database connection settings
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'users';

// Establish database connection
$db = new mysqli($host, $username, $password, $dbname);

if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}

?>


<?php

require_once 'db.php';
require_once 'login_function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Handle form submission
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (login($username, $password)) {
    header('Location: index.php');
    exit;
  }
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username"><br><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password"><br><br>
  <button type="submit">Login</button>
</form>

<?php if (isset($_GET['error'])) : ?>
  <p style="color: red;"><?php echo $_GET['error']; ?></p>
<?php endif; ?>

</body>
</html>


<?php

// Configuration variables
$username_db = "your_database_username";
$password_db = "your_database_password";
$database_name = "your_database_name";

// Connect to the database
$conn = new mysqli($username_db, $password_db, "", $database_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define variables for form data
$username = $_POST['username'];
$password = $_POST['password'];

// Check if the username and password are set
if (empty($username) || empty($password)) {
    echo "Both username and password fields must be filled";
} else {

    // Hash the password using a library like bcrypt
    $hashed_password = crypt($password, '$2y$10$' . substr(md5(uniqid(mt_rand(), true)), 0, 22));

    // Prepare and execute query to check if username exists and password matches
    $sql = "SELECT * FROM users WHERE username=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password);

    $result = $stmt->execute();
    if (!$result) {
        echo "Error executing query: " . $conn->error;
    } else {

        // If the username and password are correct
        if ($result->num_rows > 0) {

            // Get user data from the database
            while ($row = $result->fetch_assoc()) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['id'];

                // Login successful, redirect to dashboard or other page
                header("Location: dashboard.php");
                exit;
            }
        } else {

            // If username and password do not match
            echo "Invalid username or password";
        }
    }

    // Close the prepared statement
    $stmt->close();
}

// Close database connection
$conn->close();

?>


// database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    print "Error connecting to database: " . $e->getMessage();
}


function user_login($email, $password) {
    global $conn;

    // Prepare SQL query to select username and password hash from the users table
    $stmt = $conn->prepare("SELECT id, email, password_hash FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);

    try {
        // Execute prepared statement
        $stmt->execute();

        // Fetch result
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user_data) {
            // Check password hash using PHP's built-in crypt function (using a one-way hash)
            if (crypt($password, $user_data['password_hash']) === $user_data['password_hash']) {
                return $user_data; // Successful login
            } else {
                return false; // Password incorrect
            }
        } else {
            return false; // User not found
        }

    } catch (PDOException $e) {
        print "Error logging in: " . $e->getMessage();
        return false;
    }
}


$email = 'user@example.com';
$password = 'password123';

$user_data = user_login($email, $password);

if ($user_data) {
    echo 'Successful login!';
} else {
    echo 'Login failed. Please try again.';
}


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database_name');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user login credentials
function check_login($username, $password) {
    global $conn;
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check login credentials
    if (check_login($username, $password)) {
        session_start();
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Invalid username or password";
    }
}

?>


<?php

// Include connection settings
require 'database.php';

// Initialize variables
$username = '';
$password = '';

// Check if user submitted login form
if (isset($_POST['submit'])) {

    // Get the input data from the form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password for security
    $password = md5($password);

    // SQL query to select user with given username and password
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // If user exists, return a success message
        if ($stmt->fetch()) {
            session_start();
            $_SESSION['username'] = $username;
            header('Location: index.php');
        } else {
            echo "Invalid username or password.";
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<form action="" method="POST">
    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>
    <input type="submit" value="Login" name="submit">
</form>

<?php
// Close database connection if it's still open
if ($pdo) {
    $pdo = null;
}
?>

</body>
</html>


<?php
$dsn = 'mysql:host=localhost;dbname=my_database';
$username = 'my_username';
$password = 'my_password';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>


$password = password_hash($password, PASSWORD_DEFAULT);


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
function connectDatabase() {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Hash password (using bcrypt)
function hashPassword($password) {
  $salt = strtr(random_bytes(16), '+', '.');
  $hash = crypt($password, '$2y$10$' . $salt . '$');
  return array('hashed_password' => $hash, 'salt' => $salt);
}

// Verify password
function verifyPassword($hashed_password, $provided_password) {
  $salt = substr($hashed_password, strpos($hashed_password, '$2y$10$') + strlen('$2y$10$'));
  $new_hash = crypt($provided_password, '$2y$10$' . $salt);
  return $new_hash === $hashed_password;
}

// Login function
function login($username, $password) {
  // Connect to database
  $conn = connectDatabase();

  // Retrieve user data
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = $conn->query($query);
  if ($result->num_rows > 0) {
    // Fetch user data
    $user_data = $result->fetch_assoc();
    
    // Verify password
    $hashed_password = $user_data['password'];
    $verified = verifyPassword($hashed_password, $password);
    
    if ($verified) {
      // Login successful
      return true;
    } else {
      // Password incorrect
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close database connection
  $conn->close();
}

// Example usage:
$username = 'example_user';
$password = 'example_password';

if (login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}

?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
function connectDatabase() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Register a user
function registerUser($username, $email, $password) {
    $conn = connectDatabase();
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $username, $email, password_hash($password, PASSWORD_DEFAULT));
    if ($stmt->execute()) {
        echo "User created successfully";
    } else {
        echo "Error creating user: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}

// Login a user
function loginUser($username, $password) {
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                echo "Login successful";
                // You can now log the user in
                $_SESSION['username'] = $username;
                return true;
            } else {
                echo "Incorrect password";
                return false;
            }
        } else {
            echo "User not found";
            return false;
        }
    } else {
        echo "Error checking username: " . $conn->error;
        return false;
    }
    $stmt->close();
    $conn->close();
}

// Check if a username is already taken
function checkUsername($username) {
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    if ($stmt->execute()) {
        return $stmt->get_result()->num_rows > 0;
    } else {
        echo "Error checking username: " . $conn->error;
        return false;
    }
    $stmt->close();
    $conn->close();
}

// Example usage
registerUser('johnDoe', 'johndoe@example.com', 'password123');
if (loginUser('johnDoe', 'password123')) {
    echo "Welcome, John Doe!";
} else {
    echo "Failed to login";
}
?>


<?php

// Configuration settings
$mysql_host = 'localhost';
$mysql_database = 'mydatabase';
$mysql_user = 'myusername';
$mysql_password = 'mypassword';

// Connect to the database
$conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Retrieve user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = hash('sha256', $password);

    // SQL query to retrieve user data
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // User found, return their user ID
            echo "Welcome back, {$row['username']}!";
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header('Location: dashboard.php');
            exit;
        }
    } else {
        echo "Invalid username or password.";
    }

    // Close the database connection
    $conn->close();
}

if (isset($_POST['login'])) {
    login_user();
} else {
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login" name="login">
    </form>
    <?php
}
?>


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


// config.php (store database credentials)
$dbHost = 'localhost';
$dbUsername = 'username';
$dbPassword = 'password';
$dbName = 'database_name';

// connect to database
function dbConnect() {
  $conn = new mysqli($GLOBALS['dbHost'], $GLOBALS['dbUsername'], $GLOBALS['dbPassword'], $GLOBALS['dbName']);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// user login function
function loginUser($username, $password) {
  // prepare database query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  
  // connect to database
  $conn = dbConnect();
  
  // execute prepared statement
  if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ss', $username, md5($password));
    mysqli_stmt_execute($stmt);
    
    // store result
    $result = mysqli_stmt_get_result($stmt);
    
    // check if user exists
    if (mysqli_num_rows($result) > 0) {
      return true;
    } else {
      return false;
    }
    
    // close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}

// example usage:
$username = 'exampleuser';
$password = 'examplepassword';

if (loginUser($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define login function
function login_user($username, $password) {
    global $conn;

    // Query database for user credentials
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                return true;
            } else {
                return false;
            }
        }
    }

    return false;
}

// Example usage
$username = $_POST['username'];
$password = $_POST['password'];

if (isset($_POST['submit'])) {
    if (login_user($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}
?>


<?php
// Define the database connection settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create a new MySQLi connection object
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the login function
function userLogin() {
    global $conn;

    // Get the username and password from the form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the input data
    if (empty($username) || empty($password)) {
        return array('error' => 'Username or password is required');
    }

    // Hash the password for security
    $hashedPassword = hash('sha256', $password);

    // Query the database to check the username and password
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashedPassword'";
    $result = $conn->query($query);

    // Check if there's a match
    if ($result->num_rows > 0) {
        return array('success' => 'Login successful');
    } else {
        return array('error' => 'Invalid username or password');
    }
}

// Handle the form submission
if (isset($_POST['login'])) {
    $response = userLogin();
    if ($response['success']) {
        // Login successful, redirect to protected page
        header('Location: protected-page.php');
        exit;
    } else {
        // Display error message
        echo '<p style="color: red;">' . $response['error'] . '</p>';
    }
}

// Close the database connection
$conn->close();
?>


<?php
// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function userLogin($username, $password) {
    // Prepare query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    
    // Bind parameters
    $stmt->bind_param("s", $username);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Retrieve user data
        $row = $result->fetch_assoc();
        
        // Check password
        if (password_verify($password, $row['password'])) {
            return array(
                'id' => $row['id'],
                'username' => $row['username'],
                'email' => $row['email']
            );
        }
    }
    
    return null;
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$userData = userLogin($username, $password);

if ($userData) {
    echo "User logged in successfully!";
} else {
    echo "Invalid username or password.";
}
?>


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
    global $conn;

    // Get user input from form
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query the database to retrieve the user's hashed password
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (password_verify($password, $row['password'])) {
                    // If the password is correct, log the user in
                    $_SESSION['username'] = $username;
                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "Incorrect password";
                }
            }
        } else {
            echo "User not found";
        }
    } else {
        // If no input is provided, display the login form
        include('login_form.php');
    }

}

// Call the login_user function on page load
if (isset($_POST['submit'])) {
    login_user();
} else {
    login_user();
}

?>


<?php
/**
 * User login function.
 *
 * @author [Your Name]
 */

// Configuration variables
$users = [
    'admin' => '$2y$10$1234567890$hashed_password',
    // Add more users as needed
];

function login($username, $password) {
    global $users;

    // Check if the username exists
    if (!isset($users[$username])) {
        return false;
    }

    // Verify password using bcrypt
    if (password_verify($password, $users[$username])) {
        return true;
    }

    return false;
}
?>


// In another PHP file (e.g. `index.php`)
require 'login.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login($username, $password)) {
        // Login successful!
        echo "Welcome, $username!";
    } else {
        // Login failed. Display error message.
        echo "Invalid username or password.";
    }
}


<?php
// Configuration settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'users_database');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');

function login_user($username, $password) {
    // Connect to the database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query the users table for a match
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Session variables
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            return true;
        }
    }

    return false;
}

function register_user($username, $password, $email) {
    // Connect to the database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert new user into the users table
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
    if ($conn->query($sql)) {
        return true;
    } else {
        return false;
    }
}

// Usage example:
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login_user($username, $password)) {
        echo "Logged in successfully!";
    } else {
        echo "Invalid username or password.";
    }
}


<?php
// Configuration settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'users_database');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');

?>


<?php

// Predefined array of users for demonstration purposes
$users = [
    'john' => ['password' => 'hello', 'role' => 'admin'],
    'jane' => ['password' => 'world', 'role' => 'user']
];

function login($username, $password) {
    global $users;

    // Check if username exists
    if (!isset($users[$username])) {
        return ['success' => false, 'message' => 'Invalid username'];
    }

    // Compare password with stored hash
    if ($users[$username]['password'] !== $password) {
        return ['success' => false, 'message' => 'Incorrect password'];
    }

    // Check if user is active (this could be a separate check in a real application)
    // For this example, we'll assume all users are active
    // return ['success' => true, 'user_id' => $username, 'role' => $users[$username]['role']];

    return ['success' => true, 'user_id' => $username, 'role' => $users[$username]['role']];
}

// Example usage:
$username = 'john';
$password = 'hello';

$result = login($username, $password);

if ($result['success']) {
    echo "Login successful! User ID: {$result['user_id']}, Role: {$result['role']}";
} else {
    echo "Error: {$result['message']}";
}

?>


<?php

// Configuration settings
$database_host = 'localhost';
$database_username = 'your_username';
$database_password = 'your_password';
$database_name = 'your_database';

// Establish database connection
$mysqli = new mysqli($database_host, $database_username, $database_password, $database_name);

if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Function to hash password (using PHP 5.5+ built-in function)
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to verify user credentials
function verifyUser($username, $password) {
    global $mysqli;

    // Prepare and execute query to select user record
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Compare stored password with provided password
            if (password_verify($password, $row['password'])) {
                return true;
            }
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Database error: " . $mysqli->error;
    }

    return false;
}

// Function to login user and set session variables
function loginUser($username, $password) {
    global $mysqli;

    // Verify user credentials
    if (verifyUser($username, $password)) {
        // Select user record with matching username and password
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                // Set session variables
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                return true;
            }
        } else {
            echo "Database error: " . $mysqli->error;
        }

        return false;
    }

    return false;
}

?>


<?php
// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to connect to the database
function connectToDatabase() {
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
  return $conn;
}

// Function to check user credentials
function authenticateUser($username, $password) {
  $conn = connectToDatabase();
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    if ($password === $row['password']) {
      return true; // credentials match
    }
  }
  return false; // invalid username or password
}

// Main login function
function loginUser($username, $password) {
  if (authenticateUser($username, $password)) {
    // User is authenticated, create session and redirect to dashboard
    session_start();
    $_SESSION['username'] = $username;
    header("Location: dashboard.php");
    exit;
  } else {
    // Invalid username or password, display error message
    echo "Invalid username or password";
  }
}

// Handle form submission
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  loginUser($username, $password);
}
?>


<?php
include 'login.php';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Page</title>
</head>
<body>
  <form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <button type="submit" name="login">Login</button>
  </form>
</body>
</html>


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

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Database connection function
function connect_to_db() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Login function
function login_user($username, $password) {
    // Connect to database
    $conn = connect_to_db();

    // Query users table for user with matching username and password
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, hash('sha256', $password));
    $stmt->execute();
    $result = $stmt->get_result();

    // If user exists, create a new session
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $session_id = generate_session_id($conn);
        insert_into_sessions($conn, $session_id, $user_data['id']);

        // Return user data and session ID
        return array(
            'success' => true,
            'username' => $username,
            'session_id' => $session_id,
            'message' => 'Logged in successfully'
        );
    } else {
        return array('success' => false, 'message' => 'Invalid username or password');
    }

    // Close database connection
    $conn->close();
}

// Generate a unique session ID
function generate_session_id($conn) {
    return bin2hex(random_bytes(32));
}

// Insert into sessions table
function insert_into_sessions($conn, $session_id, $user_id) {
    $stmt = $conn->prepare("INSERT INTO sessions (id, user_id, session_data) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $session_id, $user_id, json_encode(array('last_seen' => time())));
    $stmt->execute();
}

// Call login_user function
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash password for security (not shown in this example)
    $hashed_password = hash('sha256', $password);

    $result = login_user($username, $hashed_password);
    echo json_encode($result);
}


<?php

// Configuration settings
define('DB_HOST', 'your_database_host');
define('DB_USERNAME', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Connect to the database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

function login_user($username, $password) {
    // Prevent SQL injection
    $username = $mysqli->real_escape_string($username);
    
    // Select the user from the database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);

    if ($result->num_rows == 1) {
        $user_data = $result->fetch_assoc();

        // Hashed password for comparison (assuming you used password_hash())
        $stored_password = $user_data['password'];

        // Compare hashed passwords
        if (password_verify($password, $stored_password)) {
            return true;
        } else {
            return false; // Incorrect password
        }
    } else {
        return false; // User not found
    }
}

// Usage example:
$username = 'your_username';
$password = 'your_password';

if (login_user($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
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

function login($username, $password) {
    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login successful, return user data
        $row = $result->fetch_assoc();
        return array(
            'id' => $row['id'],
            'username' => $row['username']
        );
    } else {
        // Login failed, return error message
        return array('error' => 'Invalid username or password');
    }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$userData = login($username, $password);

if ($userData) {
    echo "Login successful!";
} else {
    echo "Error: " . $userData['error'];
}
?>


<form action="login.php" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Login</button>
</form>


<?php

// Configuration settings
$database = 'mysql'; // database type (e.g. mysql, mysqli, PDO)
$username = 'your_username';
$password = 'your_password';

// Database connection settings
$dbhost = 'localhost';
$dbname = 'your_database_name';

// Function to connect to the database
function dbConnect() {
    global $database, $dbhost, $dbname;
    try {
        if ($database == 'mysql') {
            // MySQLi
            $conn = new mysqli($dbhost, $username, $password, $dbname);
        } elseif ($database == 'mysqli') {
            // MySQLi (alternative syntax)
            $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $username, $password);
        } elseif ($database == 'PDO') {
            // PDO
            $conn = new PDO("{$database}:host=$dbhost;dbname=$dbname", $username, $password);
        }
        return $conn;
    } catch (PDOException $e) {
        echo "Error connecting to database: " . $e->getMessage();
        exit();
    }
}

// Function to validate user credentials
function validateUser($email, $password) {
    global $dbhost, $dbname;
    try {
        // Connect to the database
        $conn = dbConnect();

        // Prepare and execute query
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $result = $stmt->fetch();

        // Check if user exists
        if ($result) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error validating user: " . $e->getMessage();
        exit();
    }
}

// Function to handle login
function loginUser($email, $password) {
    global $dbhost, $dbname;

    // Validate user credentials
    if (validateUser($email, $password)) {
        // User exists and password is correct
        echo "Login successful!";
        return true;
    } else {
        echo "Invalid email or password.";
        return false;
    }
}

// Example usage:
$email = 'example@example.com';
$password = 'your_password';

if (loginUser($email, $password)) {
    // User logged in successfully
} else {
    // Login failed
}
?>


<?php
require_once 'db.inc.php'; // database connection settings

function login($username, $password) {
  global $mysqli;
  
  // prepare query to check if username exists in database
  $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  
  // execute query and retrieve results
  $stmt->execute();
  $result = $stmt->get_result();
  
  // check if user exists in database
  if ($result->num_rows > 0) {
    // get the user's hashed password from database
    $user_data = $result->fetch_assoc();
    
    // verify password using hash library (e.g. bcrypt, argon2)
    require_once 'password_hash.inc.php'; // password hashing library settings
    if (verify_password($password, $user_data['password'])) {
      // login successful, create session and redirect to dashboard
      $_SESSION['username'] = $username;
      header('Location: dashboard.php');
      exit();
    } else {
      // incorrect password, display error message
      echo "Incorrect password";
    }
  } else {
    // user does not exist in database, display error message
    echo "Invalid username or password";
  }
}

if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  login($username, $password);
}
?>


<?php
$mysqli = new mysqli('localhost', 'username', 'password', 'database');
?>


<?php

// Configuration
const DB_HOST = 'localhost';
const DB_NAME = 'mydatabase';
const DB_USER = 'myuser';
const DB_PASSWORD = 'mypassword';

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to register user
function registerUser($username, $password) {
    // Hash password
    $hashedPassword = hash('sha256', $password);

    // Insert into database
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashedPassword);
    $stmt->execute();

    return true;
}

// Function to login user
function loginUser($username, $password) {
    // Query database for matching username and password
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, hash('sha256', $password));
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        return true; // User authenticated
    } else {
        return false; // User not authenticated
    }
}

// Close database connection
$conn->close();

?>


<?php

require_once 'users.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
} else {
    ?>
    <form action="" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
}

?>


<?php
require_once 'dbconfig.php'; // assuming your database connection settings are in dbconfig.php

function loginUser($username, $password) {
    // Prepare the query
    $query = "SELECT * FROM users WHERE username = :username AND password = :password";

    try {
        // Execute the query
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        // Execute the query and fetch result
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            // If user is found, return their details
            return array(
                'id' => $user['id'],
                'username' => $user['username'],
                'name' => $user['name']
            );
        } else {
            throw new Exception('Invalid username or password');
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    return false;
}

// Example usage
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // using md5 for simplicity, consider a more secure method

    $user = loginUser($username, $password);

    if ($user) {
        echo "Login successful!";
        print_r($user);
    } else {
        echo "Invalid username or password";
    }
}
?>


<?php
$pdo = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');
?>


// Define the users array (replace with your own database query or method)
$users = [
    'user1' => ['password' => 'password123', 'email' => 'user1@example.com'],
    'user2' => ['password' => 'password456', 'email' => 'user2@example.com']
];

function login($username, $password) {
    global $users;

    // Check if the username exists in the array
    if (!isset($users[$username])) {
        return false;
    }

    // Compare the provided password with the stored one
    if ($users[$username]['password'] !== $password) {
        return false;
    }

    // If both checks pass, return true (and other details)
    return ['success' => true, 'username' => $username];
}

// Example usage:
$username = 'user1';
$password = 'password123';

$loginResult = login($username, $password);

if ($loginResult['success']) {
    echo "Logged in successfully!";
} else {
    echo "Invalid username or password.";
}


<?php

// Configuration variables
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

// Function to login user
function login_user($username, $password) {
    // SQL query to select username and password from users table
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute query
    $result = $conn->query($sql);
    
    // Check if user exists
    if ($result->num_rows > 0) {
        // Get user data
        $user_data = $result->fetch_assoc();
        
        // Create session variables
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['id'] = $user_data['id'];
        
        return true;
    } else {
        return false;
    }
}

// Function to check if user is logged in
function is_logged_in() {
    if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
        return true;
    } else {
        return false;
    }
}

// Check for login form submission
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Login user
    if (login_user($username, $password)) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Invalid username or password";
    }
}

// Check for logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}
?>


<?php
require_once 'login.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" name="login" value="Login">
</form>

<?php
if (is_logged_in()) {
    echo "You are logged in as: " . $_SESSION['username'];
} else {
    ?>
    <?php } ?>


<?php
  // Database connection settings
  $dbHost = 'localhost';
  $dbName = 'mydatabase';
  $user = 'root';
  $pass = '';

  try {
    // Establish database connection
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $user, $pass);
    echo "Connected to database";
  } catch (PDOException $e) {
    die('ERROR: Could not connect. ' . $e->getMessage());
  }
?>


<?php

require_once 'db.php';

function login($username, $password) {
  // Query the database to retrieve the user's details
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  $userDetails = $stmt->fetch();

  // Check if the provided credentials match those in the database
  if (password_verify($password, $userDetails['password'])) {
    return array(
      'success' => true,
      'message' => 'Login successful',
      'id' => $userDetails['id'],
      'username' => $userDetails['username']
    );
  } else {
    return array(
      'success' => false,
      'message' => 'Invalid username or password'
    );
  }
}

// Example usage
$username = 'exampleUser';
$password = 'examplePassword';

$result = login($username, $password);

if ($result['success']) {
  echo "Login successful! User ID: " . $result['id'];
} else {
  echo "Login failed. Error message: " . $result['message'];
}
?>


<?php

function hashPassword($password) {
  // Hash the password using PHP's built-in hash function
  return hash('sha256', $password);
}

// Example usage
$password = 'examplePassword';
$hashedPassword = hashPassword($password);

echo "Hashed Password: $hashedPassword";
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
  // Configuration settings
  $dbHost = 'your_host';
  $dbUsername = 'your_username';
  $dbPassword = 'your_password';
  $dbName = 'your_database';

  // Create connection to database
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Check if form has been submitted
  if (isset($_POST['username']) && isset($_POST['password'])) {

    // Prepare SQL query to retrieve user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $_POST['username']);

    // Execute the query and get result
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

      // Check password (in a real application, use password hashing)
      if ($_POST['password'] == $row['password']) {
        $_SESSION['username'] = $_POST['username'];
        header('Location: dashboard.php');
        exit();
      } else {
        echo "Invalid username or password";
      }
    } else {
      echo "Invalid username or password";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

  }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>
    <button type="submit">Login</button>
</form>

<?php
  include_once 'login.php';
?>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>

<?php
  if (isset($_SESSION['username'])) {
    echo "Welcome, " . $_SESSION['username'] . "!";
  } else {
    header('Location: index.php');
    exit();
  }
?>

</body>
</html>


<?php
// Configuration variables
$database_host = 'localhost';
$database_username = 'your_database_username';
$database_password = 'your_database_password';
$database_name = 'your_database_name';

// Connect to database
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get user input from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to select user from database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = md5('$password')";

    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, return true and user data
        while ($row = $result->fetch_assoc()) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['password'] = md5($row['password']);
            $_SESSION['email'] = $row['email'];

            return array(true, $row);
        }
    } else {
        // User not found, return false
        return array(false, null);
    }

    // Close the database connection
    $conn->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    list($success, $user_data) = login_user();

    if ($success) {
        // User logged in successfully, redirect to protected page
        header('Location: /protected-page.php');
        exit;
    } else {
        echo "Invalid username or password";
    }
}
?>


<?php
// Configuration variables
$database_host = 'localhost';
$database_name = 'your_database_name';
$database_username = 'your_database_username';
$database_password = 'your_database_password';

// Connect to the database
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check username and password
function login_user($username, $password) {
    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute the query
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            return $row['id'];
        }
    } else {
        return false;
    }
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = hash('sha256', $password);

    // Check if user exists and password is correct
    $user_id = login_user($username, $hashed_password);
    
    if ($user_id !== false) {
        echo "Login successful!";
        session_start();
        $_SESSION['user_id'] = $user_id;
        header("Location: index.php");
        exit;
    } else {
        echo "Invalid username or password";
    }
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
?>


<?php

// Configuration
$hashed_password = 'your_hashed_password_here'; // Replace with hashed password from your database
$user_name = 'admin'; // Replace with user name from your database

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Input validation
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        echo "Please fill in all fields.";
        exit;
    }

    // Password hashing and verification
    $hashed_input_password = hash('sha256', $password);
    if ($hashed_input_password === $hashed_password) {

        // Successful login, redirect to secure area
        session_start();
        $_SESSION['username'] = $username;
        header('Location: secured_area.php');
        exit;

    } else {
        echo "Invalid username or password.";
    }
}

?>

<!-- Simple HTML form for user input -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Username:</label>
    <input type="text" name="username" required>

    <br/>

    <label>Password:</label>
    <input type="password" name="password" required>

    <br/>

    <button type="submit">Login</button>
</form>


<?php

// Configuration settings
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUsername = 'your_database_username';
$dbPassword = 'your_database_password';

// Create connection to database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check if database connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;

    // Get input values
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if fields are not empty
    if (empty($username) || empty($password)) {
        return array('success' => false, 'message' => 'Please fill in both username and password');
    }

    // Prepare query to select login credentials from database
    $query = "SELECT * FROM logins WHERE username = '$username'";
    $result = $conn->query($query);

    // Check if result exists
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Compare password with stored one
            if (password_verify($password, $row['password'])) {
                return array('success' => true, 'message' => 'Login successful');
            } else {
                return array('success' => false, 'message' => 'Invalid username or password');
            }
        }
    } else {
        // Handle case when user does not exist
        return array('success' => false, 'message' => 'Username does not exist');
    }
}

// Call function to check login credentials
if (isset($_POST['login'])) {
    $result = login_user();
    echo json_encode($result);
}


<?php

// Database Connection
$servername = "localhost";
$username = "username";
$password = "password";

try {
    $conn = new PDO("mysql:host=$servername;dbname=database_name", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// If form is submitted...
if (isset($_POST["username"]) && isset($_POST["password"])) {

    // Define Variables
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the Form
    if (empty($username) || empty($password)) {
        echo "Both fields are required.";
    } else {
        // SQL Query to select data from database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', md5($password));
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Login Successful. You can now access your account.";
        } else {
            echo "Invalid Username or Password";
        }
    }

} else {
    // Display form
    ?>

    <form action="" method="post">
        Username: <input type="text" name="username"><br><br>
        Password: <input type="password" name="password"><br><br>
        <input type="submit" value="Submit">
    </form>

<?php

}

// Close connection
$conn = null;

?>


// config.php (assuming you have a database connection file)
$db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');

function loginUser($username, $password) {
    global $db;
    
    // Prepare SQL query to get user data from the database
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    // Get the result of the query
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Verify password (assuming we're using plaintext passwords for simplicity)
        if ($password == $user['password']) {
            return true;
        }
        
        // Passwords don't match
        return false;
    } else {
        // User doesn't exist
        return false;
    }
}

// Usage example:
$username = 'your_username';
$password = 'your_password';

if (loginUser($username, $password)) {
    echo 'Login successful!';
} else {
    echo 'Invalid username or password.';
}


<?php

// Database connection settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user login credentials
function checkUserLogin($username, $password) {
    global $conn;

    // SQL query to select user data from database
    $sql = "SELECT * FROM users WHERE username='$username' AND password=PASSWORD('$password')";

    // Execute query and get result
    $result = $conn->query($sql);

    // Check if result is not empty
    if ($result->num_rows > 0) {
        // Return user data as an array
        return $result->fetch_array(MYSQLI_ASSOC);
    } else {
        return false;
    }
}

// Function to login user
function loginUser() {
    global $conn;

    // Get form input values
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username and password are set
    if (empty($username) || empty($password)) {
        return false;
    }

    // Call checkUserLogin function to verify credentials
    $userData = checkUserLogin($username, $password);

    // If user data is returned, login the user
    if ($userData !== false) {
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['logged_in'] = true;
        return true;
    } else {
        return false;
    }
}

// Login form handler
if (isset($_POST['submit'])) {
    // Call loginUser function to check login credentials
    if (loginUser()) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
    // Query the database for the user's credentials
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                // User has been authenticated, return user data
                return array(
                    'id' => $row['id'],
                    'username' => $row['username']
                );
            }
        }
    }

    // If no matching user was found or the password is incorrect
    return null;
}

// Handle login form submission
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user_data = login_user($username, $password);

    if ($user_data) {
        // User has been authenticated, redirect to dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Invalid username or password";
    }
}

?>


<?php

// Config variables
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'your_database_name');

// Function to connect to database
function db_connect() {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to login user
function login_user($username, $password) {
  // Connect to database
  $db = db_connect();

  // Prepare query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Bind parameters
  $stmt = $db->prepare($query);
  $stmt->bind_param("ss", $username, $password);

  // Execute query
  $stmt->execute();

  // Get result
  $result = $stmt->get_result();

  // Check if user exists and password is correct
  if ($result->num_rows > 0) {
    return true;
  } else {
    return false;
  }
}

// Example usage:
$username = $_POST['username'];
$password = md5($_POST['password']);

if (login_user($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
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
// Configuration variables
$database_host = 'localhost';
$database_username = 'your_database_username';
$database_password = 'your_database_password';
$database_name = 'your_database_name';

// Connect to the database
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Retrieve the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL query to select the user's data from the database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '".md5($password)."'";

    // Execute the query
    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Get the user's data from the result set
        $row = $result->fetch_assoc();

        // Set the session variables for the logged in user
        $_SESSION['username'] = $row['username'];
        $_SESSION['password'] = md5($password);

        // Redirect to a protected page (e.g. admin.php)
        header('Location: admin.php');
        exit;
    } else {
        // Display an error message if the username or password is incorrect
        echo 'Incorrect username or password';
    }
}

// Close the database connection
$conn->close();
?>


<?php
// Check if the user is logged in (session variables set)
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit;
}

// Display a welcome message for the logged in user
echo 'Welcome, ' . $_SESSION['username'] . '!';
?>


<?php
// Define a function to handle user login
function login_user($username, $password) {
    // Predefined array of usernames and passwords for demonstration purposes only!
    $users = [
        "admin" => "secret",
        "user1" => "password123"
    ];

    // Check if the username exists in the users array
    if (array_key_exists($username, $users)) {
        // Check if the provided password matches the stored password
        if ($password === $users[$username]) {
            return true; // Login successful!
        } else {
            echo "Invalid password for user '$username'.";
            return false;
        }
    } else {
        echo "User '$username' not found.";
        return false;
    }
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the login_user function
    if (login_user($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Login failed.";
    }
}
?>


<?php

// Define the database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to the database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Function to check if user exists and password is correct
function login_user($username, $password) {
  global $conn;
  
  // Prepare SQL query to check if user exists
  $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username");
  $stmt->bindParam(':username', $username);
  
  // Execute the query and get result
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  
  // Check if user exists and password is correct
  if ($result && password_verify($password, $result['password'])) {
    return true;
  } else {
    return false;
  }
}

// Function to register new user
function register_user($username, $email, $password) {
  global $conn;
  
  // Prepare SQL query to insert new user
  $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
  
  // Execute the query
  $stmt->execute();
}

// Example usage:
$username = 'exampleuser';
$password = 'examplepass';

if (login_user($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}

?>


<?php

// Configuration settings
$database_username = 'your_database_username';
$database_password = 'your_database_password';
$database_name = 'your_database_name';

// Connect to the database
$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// User login function
function user_login($username, $password) {
    global $conn;

    // SQL query to select user from database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row['id'];
        }
        return false;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return false;
    }

    // Close the database connection
    $conn->close();
}

// Example usage:
$username = 'your_username';
$password = 'your_password';

if (user_login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}
?>


function user_login($username, $password) {
    global $conn;

    // SQL query to select user from database
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            while ($row = mysqli_fetch_assoc($result)) {
                return $row['id'];
            }
            return false;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            return false;
        }
    } else {
        echo "Error preparing statement.";
        return false;
    }

    // Close the database connection
    $conn->close();
}


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Database Connection
function db_connect() {
    global $db_host, $db_username, $db_password, $db_name;
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Login Function
function login($username, $password) {
    global $db_host, $db_username, $db_password, $db_name;

    // Database Connection
    $conn = db_connect();

    // SQL Query
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return array(
                'success' => true,
                'user_id' => $row['id'],
                'username' => $row['username']
            );
        }
    } else {
        return array(
            'success' => false
        );
    }

    // Close Database Connection
    $conn->close();
}

// Example Usage:
$username = $_POST['username'];
$password = md5($_POST['password']); // Use a secure password hashing method for production use!

$result = login($username, $password);

if ($result['success']) {
    echo "Login Successful!";
} else {
    echo "Invalid username or password.";
}


<?php

// Define the array that stores user credentials (in a real-world scenario, this should be stored securely in a database)
$users = [
    'user1' => 'password123',
    'admin' => 'admin123'
];

function login($username, $password) {
    // Check if the username exists in the users array
    if (!array_key_exists($username, $users)) {
        return false;
    }
    
    // If the username is found, check if the provided password matches the stored one
    if ($password === $users[$username]) {
        return true;
    }
    
    // Return false on failed login attempt
    return false;
}

// Example usage: Try logging in with valid credentials
$username = 'user1';
$password = 'password123';
if (login($username, $password)) {
    echo "Login successful for user $username.";
} else {
    echo "Invalid username or password. Please try again.";
}

?>


<?php

// Define the array that stores user credentials (in a real-world scenario, this should be stored securely in a database)
$users = [
    'user1' => '$2y$10$hX3xjVhJUoPzKbG7ZdLgCqW8Y5w6kRQ5TlHtKcNpB4uV5Dm0Vn9e',
    'admin' => '$2y$10$F1i3tSjRdI2ePfLsM7aJ6.KeW7eQr5xTlHvKcNpB4uV5Dm0Vn9e'
];

function login($username, $password) {
    // Check if the username exists in the users array
    if (!array_key_exists($username, $users)) {
        return false;
    }
    
    // If the username is found, check if the provided password matches the stored one (hashed)
    if (password_verify($password, $users[$username])) {
        return true;
    }
    
    // Return false on failed login attempt
    return false;
}

// Example usage: Try logging in with valid credentials
$username = 'user1';
$password = 'password123';
if (login($username, $password)) {
    echo "Login successful for user $username.";
} else {
    echo "Invalid username or password. Please try again.";
}

?>


<?php

// Configuration
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

try {
    // Establish database connection
    $dsn = "mysql:host=$dbHost;dbname=$dbName";
    $pdo = new PDO($dsn, $dbUsername, $dbPassword);

    // Define the user login function
    function loginUser($username, $password) {
        global $pdo;

        // Prepare and execute query to retrieve user data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and password matches
        if ($user && password_verify($password, $user['password'])) {
            return true; // Login successful
        } else {
            return false; // Invalid username or password
        }
    }

    // Example usage:
    $username = 'john_doe';
    $password = 'my_secret_password';

    if (loginUser($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }

} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}

?>


<?php

// Configuration settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Connect to the database
$mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($mysqli->connect_errno) {
    die('Could not connect to database: ' . $mysqli->connect_error);
}

function login_user() {
    global $mysqli;

    // Get form data
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Hash the password for comparison
        $hashedPassword = md5($password);

        // Query to check user exists and password is correct
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashedPassword'";
        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            // User exists, set session variables
            while ($row = $result->fetch_assoc()) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['id'];
                return true;
            }
        } else {
            return false; // Incorrect username or password
        }
    }

    return false; // No form data provided
}

// Check if the user has submitted the login form
if (isset($_POST['login'])) {
    $loggedIn = login_user();

    if ($loggedIn) {
        echo "You have been logged in successfully.";
        header("Location: dashboard.php"); // Redirect to a protected page
        exit();
    } else {
        echo "Invalid username or password.";
    }
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

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        echo "Please enter both username and password.";
    } else {
        // Hash the password for comparison with stored hash
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Query database to check user credentials
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashedPassword'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // User exists and password is correct, log them in
            session_start();
            $_SESSION['username'] = $username;
            header('Location: welcome.php');
            exit();
        } else {
            echo "Invalid username or password.";
        }
    }
}

// Close connection
$conn->close();

?>


<?php include 'login.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>

<form action="" method="post">
    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>

</body>
</html>


<?php
session_start();

if (isset($_SESSION['username'])) {
    echo "Welcome, " . $_SESSION['username'] . "!";
} else {
    header('Location: index.php');
}
?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database_name');

// Connect to the database
function dbConnect() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Login function
function login($username, $password) {
    // Connect to the database
    $conn = dbConnect();
    
    // SQL query to get user data
    $query = "SELECT * FROM users WHERE username = '$username'";
    
    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            // If password is correct, return the user ID
            if (password_verify($password, $row['password'])) {
                return $row;
            }
        }
        
        // Close the database connection
        $conn->close();
        
        // Password was incorrect or username not found
        return null;
    } else {
        echo "Error: " . $conn->error;
        return null;
    }
}

// Example usage:
$username = 'example_user';
$password = 'password';

$result = login($username, $password);

if ($result !== null) {
    // Login successful!
    echo "Welcome, {$result['username']}!";
} else {
    // Login failed
    echo "Invalid username or password.";
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

// Function to login user
function login_user($username, $password) {
    // SQL query to select user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User exists, return true
        return true;
    } else {
        // User does not exist or password is incorrect, return false
        return false;
    }
}

// Function to get user data
function get_user_data($username) {
    // SQL query to select user data
    $sql = "SELECT * FROM users WHERE username = '$username'";

    // Execute query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Get user data from result
        return $result->fetch_assoc();
    } else {
        // User does not exist, return false
        return false;
    }
}

// Check if user is logged in
if (isset($_SESSION['username'])) {
    echo "User already logged in";
} else {
    // Handle login form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Call login_user function to check user credentials
        if (login_user($username, $password)) {
            // User logged in successfully, get user data and set session variables
            $user_data = get_user_data($username);
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['email'] = $user_data['email'];

            echo "Welcome, " . $_SESSION['username'] . "! You are now logged in.";
        } else {
            // User credentials incorrect, display error message
            echo "Invalid username or password";
        }
    }

    // Display login form
    ?>
    <form action="" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
}
?>


/**
 * User Login Function
 *
 * @param string $username The username to log in with.
 * @param string $password The password to use for authentication.
 * @return array|null|bool|null An array containing the user data on success, or null on failure.
 */
function login($username, $password) {
    // Database connection settings
    $dbHost = 'localhost';
    $dbUsername = 'your_username';
    $dbPassword = 'your_password';
    $dbName = 'your_database';

    try {
        // Connect to the database
        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query for the user by username
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch the result
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Hash the provided password and compare it to the stored hash
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                return null; // Incorrect password
            }
        } else {
            return null; // User not found
        }

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        return null;
    } finally {
        // Close the database connection
        if ($conn !== false) {
            $conn = null;
        }
    }
}


$username = 'johndoe';
$password = 'mysecretpassword';

$userData = login($username, $password);

if ($userData) {
    echo "Welcome, " . $user['name'] . "! Your email is: " . $user['email'];
} else {
    echo "Invalid username or password";
}


<?php

// Configuration
$databaseHost = 'localhost';
$databaseUsername = 'your_username';
$databasePassword = 'your_password';
$databaseName = 'your_database';

// Connect to database
$conn = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);

    // Execute query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists and password is correct
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $hashed_password = $row['password'];
            if (password_verify($password, $hashed_password)) {
                // Login successful, set session variables
                $_SESSION['username'] = $username;
                $_SESSION['id'] = $row['id'];

                return true; // or redirect to a protected page
            }
        }
    }

    return false; // or display an error message
}

if (isset($_POST['login'])) {
    if (login_user()) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password";
    }
}

// Close connection
$conn->close();

?>


// database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

// create a PDO object
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

function user_login($email, $password) {
  // prepare SQL query to check if email and password exist in database
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', md5($password)); // use md5 for simplicity, but consider using a secure password hashing library like bcrypt
  $stmt->execute();

  // get the result of the query
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result) {
    // login successful, return user data
    return $result;
  } else {
    // login failed, return null
    return null;
  }
}

// example usage:
$email = "example@example.com";
$password = "mysecretpassword";

$user_data = user_login($email, $password);

if ($user_data) {
  echo "Login successful! User data: ";
  print_r($user_data);
} else {
  echo "Invalid email or password.";
}


<?php
// Config file for database connection and other settings
require 'config.php';

// Check if form has been submitted
if (isset($_POST['username']) && isset($_POST['password'])) {
  // Get username and password from form
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Query database to check if user exists
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $query);

  // Check if query was successful
  if (mysqli_num_rows($result) > 0) {
    // Get user data from result
    $row = mysqli_fetch_assoc($result);
    // Hashed password stored in database for security
    $hashed_password = $row['password'];

    // Compare hashed password with input password
    if (password_verify($password, $hashed_password)) {
      // If passwords match, log user in and set session variables
      $_SESSION['username'] = $username;
      $_SESSION['logged_in'] = true;

      echo "Login successful! You are now logged in.";
    } else {
      echo "Incorrect password. Please try again.";
    }
  } else {
    echo "Username not found. Please try again.";
  }

} // end if form has been submitted

// Check if user is already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
  echo "You are already logged in.";
}
?>


<?php
// Database connection settings
$conn = mysqli_connect("localhost", "username", "password", "database");

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 'On');
?>


<?php

// Configuration settings
const DB_HOST = 'localhost';
const DB_USER = 'your_username';
const DB_PASSWORD = 'your_password';
const DB_NAME = 'your_database';

function connectToDatabase() {
    // Connect to the database using PDO
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
}

function login($username, $password) {
    // Connect to the database
    $pdo = connectToDatabase();

    // SQL query to check user credentials
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);

    try {
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user exists
        if ($user) {
            // Hashed password from database
            $hashedPassword = $user['password'];

            // Verify hashed password with provided password
            if (password_verify($password, $hashedPassword)) {
                return true;
            } else {
                echo "Incorrect password";
                return false;
            }
        } else {
            echo "Username not found";
            return false;
        }
    } catch (PDOException $e) {
        echo 'Error fetching user data: ' . $e->getMessage();
        return false;
    }

    // Close the database connection
    $pdo = null;

    // Return login result
    return true;
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Login failed";
    }
}


<form action="" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit" name="login">Login</button>
</form>

<?php
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Login failed";
    }
}
?>


<?php
// Define database connection parameters
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if there is a problem with the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the function to login the user
function loginUser() {
  // Get the input values from the form
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  // Create a query to select the user
  $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

  // Execute the query and get the result
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // If there is a match, create an array with the user data
    while ($row = $result->fetch_assoc()) {
      $_SESSION['username'] = $row['username'];
      $_SESSION['email'] = $row['email'];
      $_SESSION['logged_in'] = true;
    }
  } else {
    echo "Invalid username or password";
  }

  // Close the database connection
  $conn->close();
}

// Check if the form has been submitted
if (isset($_POST['submit'])) {
  loginUser();
}
?>


<?php
require_once 'connection.php'; // Assuming your database connection script is named "connection.php"
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "Both fields are required.";
    } else {
        $sqlQuery = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $sqlQuery);
        
        if ($result && mysqli_num_rows($result) > 0) {
            // For simplicity, we'll use SHA-256 here. In a real application, you should use a secure password hashing function.
            $user = mysqli_fetch_assoc($result);
            
            if (sha1($password) == $user['password']) { // Again, for demonstration purposes only; do not use plain SHA-1 in production code
                $_SESSION['username'] = $username;
                header('Location: dashboard.php');
                exit();
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "User not found.";
        }
    }
}
?>

<form method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>

<?php
if (isset($_SESSION['username'])) {
    echo "Welcome, ".$_SESSION['username'];
}
?>


<?php
// Configuration
$config = array(
    'database' => array(
        'host' => 'localhost',
        'username' => 'your_username',
        'password' => 'your_password',
        'name' => 'your_database_name'
    ),
    'salt' => 'your_salt_value' // Keep this secret!
);

// Connect to database
$conn = new PDO("mysql:host={$config['database']['host']};dbname={$config['database']['name']}", 
                $config['database']['username'], $config['database']['password']);

// Function to hash password
function hash_password($password) {
    return crypt($password, '$2a$10$' . $config['salt']);
}

// Function to login user
function login_user($username, $password) {
    // Prepare query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username");
    $stmt->bindParam(':username', $username);
    
    // Execute query and fetch result
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && hash_password($password) === $user['password']) {
        // Login successful, return user data
        return array('success' => true, 'username' => $username, 'email' => $user['email']);
    } else {
        // Login failed, return error message
        return array('error' => 'Invalid username or password');
    }
}

// Example usage
$username = $_POST['username'];
$password = $_POST['password'];

$result = login_user($username, $password);

if ($result['success']) {
    echo "Welcome, {$result['username']}!";
} else {
    echo $result['error'];
}
?>


// config.php (database connection settings)
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// login.php (login function)
require_once('config.php');

function login($username, $password) {
  global $dbHost, $dbUsername, $dbPassword, $dbName;

  // Connect to database
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute query to retrieve user data
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);

  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    return false; // Invalid username
  }

  // Retrieve user data from result set
  $userData = $result->fetch_assoc();

  // Hash password and compare with input password
  $passwordHash = hash('sha256', $password);

  if ($passwordHash === $userData['password']) {
    return true; // Login successful
  } else {
    return false; // Incorrect password
  }

  // Close database connection
  $conn->close();
}

// Example usage:
$username = 'your_username';
$password = 'your_password';

if (login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}


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


function login($username, $password) {
  // Query database for username and check if it exists
  $sql = "SELECT u.id, uc.password_hash FROM users u INNER JOIN user_credentials uc ON u.id = uc.user_id WHERE u.username = :username";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  
  // Fetch the result
  $result = $stmt->fetch();
  
  if ($result) {
    // Verify password using a hash function (e.g. bcrypt)
    if (password_verify($password, $result['password_hash'])) {
      return array(
        'success' => true,
        'user_id' => $result['id']
      );
    } else {
      return array(
        'success' => false,
        'error' => 'Invalid password'
      );
    }
  } else {
    return array(
      'success' => false,
      'error' => 'Username not found'
    );
  }
}


$pdo = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');

$username = 'example_user';
$password = 'password';

$result = login($username, $password);

if ($result['success']) {
  echo "User logged in successfully with ID: {$result['user_id']}";
} else {
  echo "Error logging in: {$result['error']}";
}


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Database connection function
function dbConnect() {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Login function
function login($username, $password) {
  // Validate input
  if (empty($username) || empty($password)) {
    throw new Exception('Username and password are required');
  }

  // Hash password for comparison
  $hashedPassword = hash('sha256', $password);

  // Database connection
  $conn = dbConnect();

  // Prepare query
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $hashedPassword);

  // Execute query
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if user exists and credentials are correct
  if ($result && $result->num_rows > 0) {
    return true;
  } else {
    throw new Exception('Invalid username or password');
  }

  // Close database connection
  $conn->close();
}

?>


<?php

// Include login function
require_once 'login.php';

try {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Call login function
  if (login($username, $password)) {
    echo 'Login successful!';
  } else {
    throw new Exception('Error logging in');
  }
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}

?>


<?php

// Configuration variables
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$username = $_POST['username'];
$password = $_POST['password'];

// SQL query to select the user from the database
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Login successful, redirect to a protected page
    header('Location: protected-page.php');
} else {
    // Login failed, display an error message
    echo 'Invalid username or password';
}

// Close the database connection
$conn->close();

?>


function login($username, $password) {
  // Database connection settings
  $host = 'localhost';
  $db_name = 'your_database_name';
  $user = 'your_database_user';
  $pass = 'your_database_password';

  // Establish database connection
  try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    return false;
  }

  // Prepare SQL query to select user data
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  // Fetch user data
  $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

  // Check if user exists and password is correct
  if ($user_data && password_verify($password, $user_data['password'])) {
    return true;
  } else {
    return false;
  }

  // Close database connection
  $conn = null;

  return false;
}


$username = 'your_username';
$password = 'your_password';

if (login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}


<?php

// Configuration
define('DB_HOST', 'your_database_host');
define('DB_USER', 'your_database_user');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Database Connection
function connect_to_db() {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// User Login Function
function login_user($username, $password) {
  // Connect to database
  $conn = connect_to_db();

  // Hash password for comparison
  $hashed_password = hash('sha256', $password);

  // Query users table for matching username and hashed password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, return true
    return true;
  } else {
    // User not found or incorrect credentials
    return false;
  }

  // Close database connection
  $conn->close();
}

// Example usage
$username = $_POST['username'];
$password = $_POST['password'];

if (isset($_POST['login'])) {
  if (login_user($username, $password)) {
    echo "Login successful!";
  } else {
    echo "Invalid username or password";
  }
}
?>


function login($username, $password) {
    // Connect to database
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

    // Prepare query
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);

    // Execute query
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        // Verify password using MD5 ( Note: MD5 is insecure, use a secure hashing library like bcrypt)
        if (md5($password) == $user['password']) {
            return true; // Login successful
        } else {
            return false; // Password incorrect
        }
    } else {
        return false; // Username not found
    }

    // Close database connection
    $db = null;
}


// User attempts to login
if (login('john', 'secret')) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


// File: auth.php

function login_user($username, $password) {
  // Database connection settings
  $db_host = 'localhost';
  $db_username = 'your_username';
  $db_password = 'your_password';
  $db_name = 'your_database';

  // Connect to database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);

  // Execute query
  if ($stmt->execute()) {
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    // Check if user exists and password is correct
    if (isset($user_data['id']) && $user_data['password'] === md5($password)) {
      return array('success' => true, 'user_id' => $user_data['id']);
    } else {
      return array('success' => false, 'error' => 'Invalid username or password');
    }
  } else {
    return array('success' => false, 'error' => 'Database error');
  }

  // Close database connection
  $conn->close();
}


// File: index.php

require_once 'auth.php';

$username = $_POST['username'];
$password = $_POST['password'];

$result = login_user($username, $password);

if ($result['success']) {
  echo "Login successful!";
  // Redirect to protected page or set session variables
} else {
  echo "Error: " . $result['error'];
}


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
    // Input validation
    if (empty($username)) {
        return array(false, 'Username is required');
    }

    if (empty($password)) {
        return array(false, 'Password is required');
    }

    // Password hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query to retrieve user data from database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($hashed_password, $row['password'])) {
                // Successful login
                return array(true, 'Login successful');
            } else {
                // Incorrect password
                return array(false, 'Incorrect username or password');
            }
        }
    } else {
        // Username not found
        return array(false, 'Username not found');
    }

    $conn->close();
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = login($username, $password);

if ($result[0]) {
    echo "Logged in successfully!";
} else {
    echo "Error: " . $result[1];
}
?>


<?php

// Config file for database connection and encryption keys
require_once 'config.php';

function validateUser($username, $password) {
    // Prepare SQL query to select the user's data from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    
    // Bind the username parameter
    $stmt->bindParam(':username', $username);
    
    // Execute the query and fetch the result
    if ($stmt->execute()) {
        $user = $stmt->fetch();
        
        // Verify password using hashed password
        if (password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function loginUser($username) {
    // Check if the user exists in the database
    if (validateUser($username, $_POST['password'])) {
        // Start session to store the user's data
        session_start();
        
        // Store user's data in the session
        $_SESSION['username'] = $username;
        $_SESSION['userId'] = $user['id'];
        
        // Redirect to a protected page (e.g., dashboard)
        header('Location: dashboard.php');
        exit;
    } else {
        return false;
    }
}

// Handle login form submission
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (loginUser($username)) {
        echo 'Logged in successfully!';
    } else {
        echo 'Invalid username or password.';
    }
}

?>


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Establish database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Function to login user
function login($username, $password) {
    global $conn;

    // Hash password (if you're using a recent version of PHP)
    if (version_compare(PHP_VERSION, '7.2.0', '<')) {
        $hashed_password = md5($password);
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    try {
        // SQL query to select user from database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        // Fetch user data (if a match is found)
        if ($row = $stmt->fetch()) {
            return array('success' => true, 'user_id' => $row['id'], 'username' => $row['username']);
        } else {
            return array('success' => false);
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return array('success' => false);
    }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$user_data = login($username, $password);

if ($user_data['success']) {
    // Successful login
    echo "Login successful! User ID: {$user_data['user_id']}";
} else {
    // Failed login (show error message or redirect to login page)
    echo "Invalid username or password";
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
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to hash password
function hashPassword($password) {
  return password_hash($password, PASSWORD_DEFAULT);
}

// Function to check if username and password are correct
function login_user($username, $password) {
  global $conn;

  // Prepare statement
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param('s', $username);

  // Execute query
  $stmt->execute();

  // Bind results
  $stmt->bind_result($id, $user_name, $hashed_password);

  // Fetch result
  if ($stmt->fetch()) {
    if (password_verify($password, $hashed_password)) {
      return true;
    } else {
      return false;
    }
  }

  // If no match is found, return false
  return false;

}

// Example usage:
$login = login_user('your_username', 'your_password');

if ($login) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}

?>


<?php

// Define the database connection parameters
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
    // Hash the password (you should use a stronger hashing function like bcrypt)
    $hashed_password = hash('sha256', $password);

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found with the given credentials
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function register($username, $password) {
    // Hash the password (you should use a stronger hashing function like bcrypt)
    $hashed_password = hash('sha256', $password);

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();

    return true;
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($conn->real_query("SELECT * FROM users WHERE username = '$username' AND password = '$password'")) {
            echo "Logged in successfully!";
        } else {
            echo "Invalid credentials.";
        }
    } elseif (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($conn->real_query("SELECT * FROM users WHERE username = '$username'")) {
            echo "Username already exists.";
        } else {
            register($username, $password);
            echo "User created successfully!";
        }
    }
}

?>


<?php

// Configuration
$database = 'your_database_name';
$username = 'your_database_username';
$password = 'your_database_password';

// Connect to database
$conn = new mysqli($database, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Input validation
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Password hashing
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to select user from database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password_hash = '$password_hash'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, log them in
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;

        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
} else {
    echo "Please enter both username and password.";
}

// Close connection to database
$conn->close();

?>


<?php
// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

// Database connection settings
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
    global $conn;
    
    // SQL query to select user from database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute the query and get the result
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return true; // User found, login successful
    } else {
        return false; // User not found, login failed
    }
}

function authenticateUser() {
    global $conn;
    
    // Get the username and password from form submission
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Call the login function to check if user exists
    $isLoggedIn = login($username, $password);
    
    // If user is logged in, set session variables
    if ($isLoggedIn) {
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        
        // Redirect to protected page (e.g. dashboard)
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Invalid username or password.";
    }
}


<?php
// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

// Database connection settings
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
    global $conn;
    
    // SQL query to select user from database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute the query and get the result
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return true; // User found, login successful
    } else {
        return false; // User not found, login failed
    }
}

function authenticateUser() {
    global $conn;
    
    // Get the username and password from form submission
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Call the login function to check if user exists
    $isLoggedIn = login($username, $password);
    
    // If user is logged in, set session variables
    if ($isLoggedIn) {
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        
        // Redirect to protected page (e.g. dashboard)
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Invalid username or password.";
    }
}

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    authenticateUser();
}
?>


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

// Connect to the database
function connect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Function to login a user
function login($username, $password) {
    // Create connection
    $conn = connect();
    
    // Query the database for the username and password
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        // Get user data from result
        $row = $result->fetch_assoc();
        
        // Verify the provided password with the stored hash
        if (password_verify($password, $row['password'])) {
            // If the passwords match, return the user's data and a success message
            return array(
                'status' => true,
                'data' => $row
            );
        } else {
            // If the passwords don't match, return an error message
            return array(
                'status' => false,
                'message' => 'Invalid password'
            );
        }
    } else {
        // If no user is found with that username, return an error message
        return array(
            'status' => false,
            'message' => 'User not found'
        );
    }
    
    $conn->close();
}

// Example usage:
$username = "exampleuser";
$password = "password";

$result = login($username, $password);

if ($result['status']) {
    echo "Login successful!
";
} else {
    echo "Error: " . $result['message'] . "
";
}


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'users');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

// Function to hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Login function
function login($username, $password) {
    // Validate input
    if (empty($username) || empty($password)) {
        return array('error' => 'Invalid username or password');
    }

    // SQL query to select user from database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($sql);

    if ($result->num_rows === 0) {
        return array('error' => 'Username not found');
    }

    // Get user data
    $user_data = $result->fetch_assoc();

    // Check password
    if (password_verify($password, $user_data['password'])) {
        // Login successful
        $_SESSION['username'] = $username;
        return array('success' => true);
    } else {
        return array('error' => 'Invalid username or password');
    }
}

// Handle login request
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call login function and store result in variable
    $result = login($username, $password);

    if ($result['success']) {
        echo "Logged in successfully!";
    } else {
        echo "Error: " . $result['error'];
    }
}

?>


<?php
require_once 'login.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Example</title>
</head>
<body>

<form action="" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <button type="submit" name="login">Login</button>
</form>

<?php if (isset($_GET['success'])) { echo "Logged in successfully!"; } ?>


<?php

// Configuration settings
$databaseHost = 'localhost';
$databaseName = 'users';
$databaseUser = 'root';
$databasePassword = '';

// Connect to the database
$conn = mysqli_connect($databaseHost, $databaseUser, $databasePassword, $databaseName);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

function userLogin($username, $password)
{
    // SQL query to select user data from the database
    $query = "SELECT * FROM users WHERE username='$username'";
    
    // Execute the query and fetch the result
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $userData = mysqli_fetch_assoc($result);
        
        // Check if the user exists
        if (!empty($userData)) {
            // Verify the password
            if (password_verify($password, $userData['password'])) {
                return true;  // User logged in successfully
            } else {
                echo 'Invalid password';
            }
        } else {
            echo 'User not found';
        }
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }

    return false;  // Login failed
}

// Form handling function
function handleLogin()
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo 'Please fill all fields';
    } else {
        userLogin($username, $password);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    handleLogin();
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Display login form
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <button type="submit">Login</button>
    </form>
    <?php
}

?>


// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'username';
$password = 'password';

// Function to connect to database
function dbConnect() {
    global $host, $dbname, $username, $password;
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit();
    }
}


// Function to register a new user
function registerUser($email, $password) {
    global $host, $dbname, $username, $password;
    $conn = dbConnect();

    // Hash the password for storage in database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $query = "INSERT INTO users (email, password) VALUES (:email, :password)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        echo "User registered successfully.";
    } catch(PDOException $e) {
        echo "Registration failed: " . $e->getMessage();
    }

    $conn = null;
}


// Function to login an existing user
function loginUser($email, $password) {
    global $host, $dbname, $username, $password;

    $conn = dbConnect();
    try {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($row = $stmt->fetch()) {
            // Check password with hashed stored in database
            if (password_verify($password, $row['password'])) {
                echo "Login successful. Welcome, " . $email;
                return true;
            } else {
                echo "Invalid password.";
                return false;
            }
        } else {
            echo "Email not found.";
            return false;
        }
    } catch(PDOException $e) {
        echo "Login failed: " . $e->getMessage();
        return false;
    }

    $conn = null;
}


// Function to reset a user's password
function resetPassword($email, $newPassword) {
    global $host, $dbname, $username, $password;

    $conn = dbConnect();

    // Hash the new password for storage in database
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    try {
        $query = "UPDATE users SET password = :password WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedNewPassword);
        $stmt->execute();

        echo "Password reset successfully.";
    } catch(PDOException $e) {
        echo "Password reset failed: " . $e->getMessage();
    }

    $conn = null;
}


registerUser('user@example.com', 'password123');
loginUser('user@example.com', 'password123');

// Optionally reset a user's password via email:
resetPassword('user@example.com', 'newpassword456');


<?php

/**
 * User Login Function
 *
 * Handles user login functionality.
 *
 * @author [Your Name]
 */

// Configuration Variables
$loginFormUrl = 'login.php'; // URL for the login form
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUsername = 'your_database_username';
$dbPassword = 'your_database_password';

// Connect to Database
function connectToDatabase() {
  $conn = new mysqli($GLOBALS['dbHost'], $GLOBALS['dbUsername'], $Globals['dbPassword'], $GLOBALS['dbName']);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Check User Credentials
function checkUserCredentials($username, $password) {
  // SQL Query to select user with matching username and password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  
  // Connect to Database
  $conn = connectToDatabase();
  
  // Execute SQL Query
  $result = $conn->query($sql);
  
  // Check if query returned any results
  if ($result->num_rows > 0) {
    return true; // User credentials are valid
  } else {
    return false; // User credentials are invalid
  }
}

// Login Function
function login($username, $password) {
  // Check user credentials
  if (checkUserCredentials($username, $password)) {
    // If valid, redirect to secure area
    header("Location: secure_area.php");
    exit;
  } else {
    // If invalid, display error message
    echo "Invalid username or password";
  }
}

// Example usage:
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  login($username, $password);
}

?>


<form action="login.php" method="post">
  <input type="text" name="username" placeholder="Username">
  <input type="password" name="password" placeholder="Password">
  <button type="submit" name="login">Login</button>
</form>


<?php
// Set the database connection settings
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user login credentials
function checkLogin($username, $password) {
    global $conn;

    // SQL query to select the user's details from the database
    $sql = "SELECT * FROM users WHERE username='$username' AND password=MD5('$password')";

    // Execute the query and get the result
    $result = $conn->query($sql);

    // If there is a match, return true
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $row['id'];
            return true;
        }
    } else {
        // If there is no match, return false
        return false;
    }

    // Close the database connection
    $conn->close();
}

// Check if the user has submitted the login form
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the checkLogin function and store the result in a variable
    $result = checkLogin($username, $password);

    // If the user's credentials are valid, redirect them to the dashboard page
    if ($result) {
        header('Location: dashboard.php');
        exit;
    } else {
        // If the user's credentials are invalid, display an error message
        echo 'Invalid username or password';
    }
}
?>


<?php
// Include the login function file
include_once 'login.php';

// Create a form to handle the user's input
?>

<form action="" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" name="login" value="Login">
</form>

<?php
// If the user has submitted the form, call the checkLogin function to verify their credentials
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


// db.php (database connection file)
<?php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function get_user($email, $password) {
  global $conn;
  $sql = "SELECT * FROM users WHERE email=? AND password=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  return $stmt->get_result()->fetch_assoc();
}

$conn->close();
?>


// login.php (login function file)
<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($user = get_user($email, password_hash($password, PASSWORD_DEFAULT))) {
        // Login successful, create session and redirect to dashboard
        session_start();
        $_SESSION['id'] = $user['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Invalid email or password";
    }
} else {
    echo "Only POST requests are allowed";
}
?>


// index.php (login form file)
<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require 'login.php';
}

?>

<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Login</button>
</form>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
include 'database.php';

function registerUser($username, $email, $password) {
    global $conn;
    
    // Check if user already exists
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        return false;  // User already exists
    }
    
    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '".md5($password)."')";
    $conn->query($query);
    
    return true;
}

function loginUser($username, $password) {
    global $conn;
    
    // Check if user exists
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Check password
        if (md5($password) == $user['password']) {
            return true;  // Login successful
        } else {
            return false;  // Incorrect password
        }
    } else {
        return false;  // User does not exist
    }
}

function isLoggedIn() {
    global $conn;
    
    session_start();
    
    if (isset($_SESSION['username'])) {
        return true;
    } else {
        return false;
    }
}
?>


<?php
include 'functions.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if (registerUser($username, $email, $password)) {
        echo "Registration successful!";
    } else {
        echo "Username already exists.";
    }
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (loginUser($username, $password)) {
        session_start();
        $_SESSION['username'] = $username;
        
        echo "Login successful!";
    } else {
        echo "Incorrect username or password.";
    }
}

if (isLoggedIn()) {
    echo "You are currently logged in as " . $_SESSION['username'];
} else {
    echo "Please log in to access this page.";
}
?>


<?php
require_once 'config.php'; // Include your database configuration file

function login($username, $password) {
  global $conn; // Assume you have defined a connection variable

  // Query to check if the username exists in the database
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Fetch user data from the result
    $user_data = mysqli_fetch_assoc($result);
    
    // Check if the password matches
    if (password_verify($password, $user_data['password'])) {
      return true; // Login successful
    } else {
      return false; // Incorrect password
    }
  } else {
    return false; // Username not found
  }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

if (login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}
?>


/**
 * User Login Function
 *
 * Checks if the provided username and password match the stored credentials.
 *
 * @param string $username The username to check.
 * @param string $password  The password to check.
 *
 * @return bool True if the login is successful, false otherwise.
 */
function user_login($username, $password) {
    // Define a sample array of users for demonstration purposes
    $users = [
        'admin' => ['password' => '$2y$10$TKdPj4eZnW3R5lGzBqC6fOe/'],
        'user1' => ['password' => '$2y$10$aQc8N8oVpT0Ib2YxKJL0we/']
    ];

    // Check if the username exists in the array
    if (array_key_exists($username, $users)) {
        // Compare the provided password with the stored hash
        if (password_verify($password, $users[$username]['password'])) {
            // Login successful!
            return true;
        } else {
            // Password incorrect
            return false;
        }
    } else {
        // Username not found
        return false;
    }
}


$username = 'admin';
$password = 'mysecretpassword';

if (user_login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


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

function login_user($username, $password) {
    global $conn;

    // Prepare SQL query to select user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute query and store result
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch user data from the result
    if ($user_data = $result->fetch_assoc()) {
        // Hashed password comparison using bcrypt (assuming you're using a library like PHPass or Argon2)
        $password_hash = $user_data['password'];
        if (password_verify($password, $password_hash)) {
            return true; // User authenticated successfully
        } else {
            return false; // Incorrect password
        }
    } else {
        return false; // User not found
    }

    // Close prepared statement
    $stmt->close();
}

function register_user($username, $password) {
    global $conn;

    // Prepare SQL query to insert new user data
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    // Execute query and store result
    if ($stmt->execute()) {
        return true; // User created successfully
    } else {
        return false; // Error creating user
    }

    // Close prepared statement
    $stmt->close();
}

// Example usage:
$username = "test_user";
$password = "test_password";

if (register_user($username, $password)) {
    echo "User created successfully.
";
} else {
    echo "Error creating user: " . $conn->error . "
";
}

if (login_user($username, $password)) {
    echo "User logged in successfully.
";
} else {
    echo "Invalid username or password.
";
}


function login($username, $password) {
    // Database connection settings
    $dbHost = 'localhost';
    $dbName = 'mydatabase';
    $dbUser = 'myuser';
    $dbPass = 'mypassword';

    // Create a database connection
    try {
        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL query to select user data from the database
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);

        // Execute the prepared statement with the provided credentials
        $stmt->execute();

        // Fetch the selected user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a matching user exists in the database and if their password matches
        if ($user && password_verify($password, $user['password'])) {
            return true;
        } else {
            throw new Exception('Invalid username or password');
        }
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        return false;
    }

    // Close the database connection if it was opened
    if ($conn !== null) {
        $conn = null;
    }
}


$username = 'myusername';
$password = 'mypassword';

if (login($username, $password)) {
    echo 'Login successful!';
} else {
    echo 'Invalid username or password';
}


<?php

// Array containing users and passwords
$users = [
    'user1' => 'password123',
    'user2' => 'password456'
];

function login($username, $password) {
    global $users;

    // Check if username exists in the array
    if (array_key_exists($username, $users)) {
        // Check if password matches
        if ($users[$username] === $password) {
            return true;
        }
    }

    return false;
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

if (login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


<form action="login.php" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Login</button>
</form>


<?php
// Configuration
$databaseHost = 'localhost';
$databaseName = 'mydatabase';
$databaseUsername = 'myusername';
$databasePassword = 'mypassword';

// Connect to the database
$conn = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
    global $conn;
    
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // SQL query to check user credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute the query
    $result = $conn->query($sql);

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Get the user data from the result set
        $row = $result->fetch_assoc();

        // If we found a match, return an array of user data
        return array(
            'id' => $row['id'],
            'username' => $row['username']
        );
    } else {
        // No match was found. Return null.
        return null;
    }
}

// Handle POST request to login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the login_user function with the provided credentials
    $user_data = login_user($username, $password);

    if ($user_data !== null) {
        // If we found a match, redirect to protected area
        header('Location: /protected_area');
        exit;
    } else {
        // Display error message
        echo 'Invalid username or password';
    }
}
?>


<?php include 'login.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


<?php
function login_user($username, $password) {
  // Database connection settings (replace with your actual database settings)
  $host = 'localhost';
  $db_name = 'my_database';
  $username_db = 'my_username';
  $password_db = 'my_password';

  // Create a connection to the database
  try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username_db, $password_db);
  } catch(PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
  }

  // SQL query to check if the user exists and password matches
  $query = "
    SELECT * FROM users
    WHERE username = :username AND password = :password;
  ";

  // Prepare the query with the provided credentials
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', $password);

  // Execute the query and fetch results
  try {
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
      // User credentials match, return user data
      return array(
        'id' => $result['id'],
        'username' => $result['username']
      );
    } else {
      // Incorrect username or password
      return null;
    }
  } catch(PDOException $e) {
    die("Error executing query: " . $e->getMessage());
  }

  finally {
    $conn = null; // Close the database connection
  }
}
?>


$username = 'my_username';
$password = 'my_password';

$user_data = login_user($username, $password);

if ($user_data) {
  echo "User logged in successfully!";
  print_r($user_data);
} else {
  echo "Invalid username or password.";
}


<?php
// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

// Function to connect to the database
function db_connect() {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to check user credentials
function login_user($username, $password) {
  // Connect to the database
  $conn = db_connect();

  // Prepare query to retrieve user data
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password);

  // Execute query and get result
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if user exists
  if ($result->num_rows > 0) {
    // User exists, return true
    return true;
  } else {
    // User doesn't exist or credentials are incorrect
    return false;
  }

  // Close connection
  $conn->close();
}

// Function to get user data by username
function get_user_data($username) {
  // Connect to the database
  $conn = db_connect();

  // Prepare query to retrieve user data
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);

  // Execute query and get result
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if user exists
  if ($result->num_rows > 0) {
    // User exists, return data as an array
    return $result->fetch_assoc();
  } else {
    // User doesn't exist or credentials are incorrect
    return null;
  }

  // Close connection
  $conn->close();
}

// Example usage:
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (login_user($username, $password)) {
    $user_data = get_user_data($username);
    // User is logged in, display welcome message or redirect to dashboard
    echo "Welcome, $user_data[name]!";
  } else {
    // User credentials are incorrect or doesn't exist
    echo "Invalid username or password.";
  }
}

// Form for user login
?>
<form action="" method="post">
  <label>Username:</label>
  <input type="text" name="username"><br><br>
  <label>Password:</label>
  <input type="password" name="password"><br><br>
  <input type="submit" name="login" value="Login">
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
// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = mysqli_connect($db_host, $db_username, $db_password);
if (!$conn) {
    die("Connection failed: " . mysqli_error($conn));
}

// Select database
mysqli_select_db($conn, $db_name);

function login_user($username, $password) {
  // Check if username and password are not empty
  if (empty($username) || empty($password)) {
      return false;
  }

  // Escape input data to prevent SQL injection
  $username = mysqli_real_escape_string($conn, $username);
  $password = mysqli_real_escape_string($conn, $password);

  // Hash password using md5 (not recommended for security reasons)
  // $hashed_password = md5($password);

  // Query database to check if user exists and password is correct
  $query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) == 1) {
      return true;
  } else {
      return false;
  }
}

function get_user_data($username) {
  // Query database to retrieve user data
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) == 1) {
      return mysqli_fetch_assoc($result);
  } else {
      return null;
  }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

if (login_user($username, $password)) {
    echo "Login successful!";
    $user_data = get_user_data($username);
    // Store user data in session or use it as needed
} else {
    echo "Invalid username or password";
}
?>


<?php

// Define the database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Establish a connection to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

function login($username, $password) {
    global $conn;

    // Prepare a SQL query to retrieve the user's password
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');

    // Bind the parameters
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the user data
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_data) {
        // Hash the provided password and compare it with the stored one
        if (password_verify($password, $user_data['password'])) {
            // Successful login, return true and the user's ID
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_data['id'];
            return true;
        } else {
            // Incorrect password
            echo "Incorrect password";
            return false;
        }
    } else {
        // User not found
        echo "User not found";
        return false;
    }
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login($username, $password)) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Invalid credentials";
    }
}

?>


<?php

// Configuration variables
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

function login_user($username, $password) {
    // Prepare query
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    
    // Bind parameters
    $stmt->bind_param("ss", $username, $password);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    // Check if user exists and password is correct
    if ($result && $row = $result->fetch_assoc()) {
        return true; // Login successful
    } else {
        return false; // Login failed
    }
}

// Handle login request
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (login_user($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password";
    }
}

?>


<?php

function authenticateUser($username, $password) {
    // Assume we have a database connection established
    $conn = mysqli_connect("localhost", "username", "password", "database");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query the users table to check for the username and password match
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return true; // User authenticated successfully
    } else {
        return false; // User authentication failed
    }

    mysqli_close($conn);
}

function createUserAccount($username, $password) {
    // Assume we have a database connection established
    $conn = mysqli_connect("localhost", "username", "password", "database");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Insert the new user into the users table
    $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        return true; // User created successfully
    } else {
        return false; // User creation failed
    }

    mysqli_close($conn);
}

?>


<?php

function authenticateUser($username, $password) {
    // Assume we have a database connection established
    require_once 'database.php';

    try {
        $stmt = $db->prepare('SELECT * FROM users WHERE username=? AND password=?');
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() !== null;
    } catch (Exception $e) {
        return false; // User authentication failed
    }
}

function createUserAccount($username, $password) {
    // Assume we have a database connection established
    require_once 'database.php';

    try {
        $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        return true; // User created successfully
    } catch (Exception $e) {
        return false; // User creation failed
    }
}

?>


<?php

function authenticateUser($username, $password) {
    // Assume we have a database connection established
    require_once 'database.php';

    try {
        $stmt = $db->prepare('SELECT * FROM users WHERE username=?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            return true; // User authenticated successfully
        } else {
            return false; // User authentication failed
        }
    } catch (Exception $e) {
        return false; // User authentication failed
    }
}

function createUserAccount($username, $password) {
    // Assume we have a database connection established
    require_once 'database.php';

    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $stmt->bind_param('ss', $username, $hashedPassword);
        $stmt->execute();
        return true; // User created successfully
    } catch (Exception $e) {
        return false; // User creation failed
    }
}

?>


// File: includes/login.php

// Define database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$user = 'your_database_username';
$password = 'your_database_password';

// Attempt to connect to the database
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
    exit;
}

function login($username, $password)
{
    // Hash the password
    $hashed_password = hash('sha256', $password);

    // Prepare SQL query to select user from database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);

    // Execute the statement and fetch results
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
        // Return user data (e.g., for further processing)
        return array(
            'id' => $result['id'],
            'username' => $result['username']
        );
    } else {
        // Return error message
        throw new Exception('Invalid username or password');
    }
}

function logout()
{
    session_destroy();
}

// Example usage:
if (isset($_POST['submit'])) {
    try {
        $user_data = login($_POST['username'], $_POST['password']);
        if ($user_data) {
            // User is logged in, proceed with further processing...
            print_r($user_data);
        } else {
            throw new Exception('Error logging in');
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    include 'login_form.php';
}


<?php

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to connect to database
function dbConnect() {
  $conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
  return $conn;
}

// Function to verify user credentials
function login($username, $password) {
  // Connect to database
  $conn = dbConnect();

  // Prepare SQL query
  $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
  mysqli_stmt_bind_param($stmt, "s", $username);

  // Execute query and store result
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  // Fetch the stored record
  $record = mysqli_fetch_assoc($result);

  // Check if user exists and password matches
  if ($record && password_verify($password, $record['password'])) {
    return true; // Login successful
  } else {
    return false; // Incorrect username or password
  }

  // Close database connection
  mysqli_close($conn);
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

if (isset($_POST['login'])) {
  $success = login($username, $password);

  if ($success) {
    echo "Login successful!";
  } else {
    echo "Incorrect username or password.";
  }
}

?>


// config.php
$host = 'localhost';
$dbname = 'login_system';
$user = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}


// login.php
require_once 'config.php';

function loginUser($username, $password)
{
    global $conn;

    // Clean input to prevent SQL injection
    $username = htmlspecialchars(strip_tags($username));
    $password = md5(htmlspecialchars(strip_tags($password)));

    $query = "SELECT * FROM users WHERE username = :username AND password = :password";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    try {
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Login successful, store user data in session
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $result['id'];

            return true;
        } else {
            // Login failed
            return false;
        }
    } catch (PDOException $e) {
        echo "ERROR: " . $e->getMessage();
        return false;
    }
}


// login_example.php
require_once 'login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Invalid username or password";
    }
} else {
    // Display login form
    ?>
    <form action="" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
    <?php
}


<?php

// Database configuration
$host = 'localhost';
$db_name = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

$conn = new mysqli($host, $username, $password, $db_name);

?>


<?php

// Include the config file for database connection settings
include_once 'config.php';

function user_login($username, $password) {
  // Query to select the user's data from the database
  $query = "SELECT * FROM users WHERE username='$username' AND password=SHA1('$password')";
  
  // Execute the query and store the result
  $result = mysqli_query($conn, $query);
  
  // If there is a match (one row is returned), proceed with the login process
  if ($row = mysqli_fetch_assoc($result)) {
    session_start();
    
    // Set user's data in session variables
    $_SESSION['username'] = $row['username'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['logged_in'] = true;
    
    // Redirect to the protected page or a success message
    header('Location: protected_page.php');
  } else {
    // Login failed, display an error message
    echo 'Invalid username and/or password';
  }
}

// Example usage:
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = SHA1($_POST['password']);
  
  user_login($username, $password);
}
?>


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);

function login($username, $password) {
  global $conn;
  
  // Sanitize input data
  $username = htmlspecialchars($username);
  $password = htmlspecialchars($password);
  
  // Prepare the SQL query
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', $password);
  
  try {
    // Execute the query
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
      // Login successful, return user data
      return array(
        'id' => $result['id'],
        'username' => $result['username']
      );
    } else {
      // Login failed, return an error message
      return array('error' => 'Invalid username or password');
    }
  } catch (PDOException $e) {
    // Database connection error
    return array('error' => 'Database connection error');
  }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  $result = login($username, $password);
  
  if ($result) {
    // Login successful
    header('Location: dashboard.php');
    exit;
  } else {
    // Login failed
    echo 'Invalid username or password';
  }
}

?>


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Create a database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to hash the password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to check user credentials
function checkUserCredentials($username, $password) {
    global $conn;
    
    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Get the user's hashed password
        $user_data = $result->fetch_assoc();
        
        // Check if the provided password matches the stored hash
        return password_verify($password, $user_data['password_hash']);
    }
    
    // If no match found, return false
    return false;
}

// Function to handle user login
function loginUser() {
    global $conn;
    
    // Get input values from form submission (e.g., username and password)
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        // Hash the provided password
        $hashed_password = hashPassword($password);
        
        // Check user credentials
        if (checkUserCredentials($username, $hashed_password)) {
            // User is authenticated, set session variables and redirect to protected page
            session_start();
            $_SESSION['username'] = $username;
            header('Location: protected-page.php');
            exit;
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Please fill in both fields";
    }
}

// Handle form submission (login)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    loginUser();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Display login form
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type of="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
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

// Configuration
$config = array(
    'db_host' => 'localhost',
    'db_name' => 'mydatabase',
    'db_user' => 'myuser',
    'db_password' => 'mypassword'
);

// Connect to database
$conn = new mysqli($config['db_host'], $config['db_user'], $config['db_password'], $config['db_name']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to login user
function login_user($username, $password) {
    global $conn;
    
    // Prepare query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    
    // Bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    // Check if user exists
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Function to check if username is available
function check_username($username) {
    global $conn;
    
    // Prepare query
    $sql = "SELECT * FROM users WHERE username = ?";
    
    // Bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    // Check if user exists
    if ($result->num_rows > 0) {
        return false;
    } else {
        return true;
    }
}

// Main logic
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check if username is available
    if (!check_username($username)) {
        echo "Username already exists.";
    } else {
        // Hash password (for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user into database
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password);
        $stmt->execute();
        
        // Login user
        if (login_user($username, $password)) {
            echo "Login successful.";
        } else {
            echo "Invalid username or password.";
        }
    }
}

?>


<?php include 'login.php'; ?>
<form action="" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>


function loginUser($username, $password) {
  // Predefined database of users
  $users = [
    'admin' => ['password' => '$2y$10$92AdtjH4r1dLkajsdfo23FbA9u4e5r6T7g8hJiKlMnOpqRtUY0', 'email' => 'admin@example.com'],
    // Add more users as needed
  ];

  // Check if the username exists in the database
  if (array_key_exists($username, $users)) {
    // Check if the provided password matches the stored hash
    if (password_verify($password, $users[$username]['password'])) {
      return 'User logged in successfully!';
    } else {
      return 'Incorrect password. Please try again.';
    }
  } else {
    return 'Username not found. Please create an account first.';
  }
}


// Log in an existing user
echo loginUser('admin', 'password123'); // Output: User logged in successfully!

// Attempt to log in with invalid credentials
echo loginUser('invalid_user', 'wrong_password'); // Output: Username not found. Please create an account first.


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'database_name');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to hash password (using SHA-256)
function hash_password($password) {
    return sha256($password);
}

// Function to check user login
function check_login($username, $password) {
    // Query users table for matching username and hashed password
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '" . hash_password($password) . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return true; // Login successful
    } else {
        return false; // Login failed
    }
}

// User login function
function user_login() {
    // Get POST data from login form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if user credentials are valid
    if (check_login($username, $password)) {
        session_start();
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Invalid username or password.";
    }
}

// Run the login function when this script is executed
if (isset($_POST['login'])) {
    user_login();
} else {
    // Display a login form if not submitted yet
?>

<!DOCTYPE html>
<html>
<head>
    <title>Log In</title>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br><br>
        Password: <input type="password" name="password"><br><br>
        <input type="submit" name="login" value="Log In">
    </form>

<?php
}
?>


<?php

// Configuration settings
$database_host = 'localhost';
$database_username = 'your_username';
$database_password = 'your_password';
$database_name = 'your_database';

// Connect to the database
$mysqli = new mysqli($database_host, $database_username, $database_password, $database_name);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

// Function to check user credentials
function login_user($username, $password) {
    global $mysqli;

    // Prepare query to retrieve user data
    $query = 'SELECT * FROM users WHERE username = ? AND password = ?';

    // Bind parameters
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ss', $username, $password);

    // Execute query and get result
    $result = $stmt->execute();
    $user_data = $stmt->get_result()->fetch_assoc();

    if ($result && $user_data) {
        return true; // User found
    } else {
        return false; // User not found or incorrect credentials
    }
}

// Function to login user and set session variables
function do_login($username, $password) {
    global $mysqli;

    // Check user credentials
    if (login_user($username, $password)) {
        // Get user data from database
        $query = 'SELECT * FROM users WHERE username = ?';
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('s', $username);
        $result = $stmt->execute();
        $user_data = $stmt->get_result()->fetch_assoc();

        // Set session variables
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $user_data['id'];
        $_SESSION['email'] = $user_data['email'];

        return true; // Login successful
    } else {
        return false; // Login failed
    }
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (do_login($username, $password)) {
        echo "Login successful! You are now logged in.";
    } else {
        echo "Invalid username or password.";
    }
}

?>


<?php

// Database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username and password are set
    if (empty($username) || empty($password)) {
        return array('error' => 'Username or password is required');
    }

    // Hash the password for comparison
    $hashed_password = hash('sha256', $password);

    // Query to check user credentials
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // User exists, return their ID and role
        $row = $result->fetch_assoc();
        return array('success' => true, 'user_id' => $row['id'], 'role' => $row['role']);
    } else {
        // User does not exist or incorrect password
        return array('error' => 'Invalid username or password');
    }
}

// Check if login form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Call the login_user function and store result in $result variable
    $result = login_user();

    // Check if user logged in successfully
    if ($result['success']) {
        // Redirect to dashboard or other protected page
        header('Location: dashboard.php');
        exit;
    } else {
        // Display error message
        echo 'Error logging in: ' . $result['error'];
    }
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

?>


function loginUser($username, $password) {
  // Connect to the database (replace with your own DB connection code)
  $db = mysqli_connect("localhost", "username", "password", "database");

  if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // SQL query to select user from database
  $query = "SELECT * FROM users WHERE username = '$username'";

  // Execute the query and get the result
  $result = mysqli_query($db, $query);

  if (mysqli_num_rows($result) > 0) {
    // Get the user's data from the result
    $user_data = mysqli_fetch_assoc($result);

    // Check the password against the stored hash
    if (password_verify($password, $user_data['password'])) {
      return true; // Login successful
    } else {
      return false; // Incorrect password
    }
  } else {
    return false; // User not found
  }

  // Close the database connection
  mysqli_close($db);
}


$username = "john";
$password = "secret";

if (loginUser($username, $password)) {
  echo "Login successful!";
} else {
  echo "Login failed.";
}


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
    // Prepare query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    
    // Execute query with prepared statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, retrieve data and return
        $user_data = $result->fetch_assoc();
        return $user_data;
    } else {
        // User not found, return false
        return false;
    }
}

// Handle login request
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user_data = login_user($username, $password);

    if ($user_data) {
        // Login successful, redirect to dashboard or other protected page
        echo "Login successful! Welcome, " . $user_data['username'] . ".";
        header('Location: /dashboard');
        exit;
    } else {
        // Login failed, display error message
        echo "Invalid username or password.";
    }
}

// Close database connection
$conn->close();

?>


<?php
require_once 'config.php';

function login_user($username, $password) {
  // Prepare query to retrieve user data from database
  $sql = "SELECT * FROM users WHERE username = :username";
  
  try {
    // Execute prepared statement and bind parameters
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    
    // Run query and store results in `$user` array
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
      // Login successful, return user data
      return [
        'id' => $user['id'],
        'username' => $user['username']
      ];
    } else {
      throw new Exception('Invalid username or password');
    }
    
  } catch (PDOException $e) {
    // Handle database errors and exceptions
    echo "Database error: " . $e->getMessage();
    return false;
  }
  
}

// Example usage:
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  $result = login_user($username, $password);
  
  if ($result) {
    // Login successful, store user data in session
    $_SESSION['user'] = $result;
    
    header('Location: index.php');
    exit();
  } else {
    echo "Invalid username or password";
  }
}
?>


<?php
$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

// Enable error reporting and display errors on screen
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Function to login a user
function login_user($username, $password) {
    // Connect to the database
    $conn = db_connect();

    // Prepare the query
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";

    // Bind parameters and execute the query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // User found, return their details
        $row = $result->fetch_assoc();
        session_start();
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_id'] = $row['id'];

        // Redirect to a secure page
        header('Location: /secure-page.php');
    } else {
        // User not found, display an error message
        echo "Invalid username or password";
    }

    // Close the database connection
    $conn->close();
}

// Check if the login form has been submitted
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = hash('sha256', $password);

    // Login the user
    login_user($username, $hashed_password);
}
?>


<?php

// Configuration settings
$config = array(
    'db_host' => 'localhost',
    'db_username' => 'root',
    'db_password' => '',
    'db_name' => 'login_example'
);

// Database connection settings
$dsn = 'mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'];
$db = new PDO($dsn, $config['db_username'], $config['db_password']);

function user_login() {
    // Check if the form has been submitted
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Get the username and password from the form
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query the database to check for a matching user
        $query = "SELECT * FROM users WHERE username = :username AND password = :password";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Check if a user was found
        $result = $stmt->fetch();
        if ($result) {
            // User was found, start a session to store the user's data
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['username'] = $result['username'];

            // Redirect the user to a protected page (e.g. dashboard)
            header('Location: /dashboard.php');
        } else {
            // User was not found, display an error message
            echo 'Invalid username or password';
        }
    }

    // Display the login form if it has not been submitted yet
    $login_form = '
        <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
            <label>Username:</label>
            <input type="text" name="username"><br><br>
            <label>Password:</label>
            <input type="password" name="password"><br><br>
            <input type="submit" value="Login">
        </form>
    ';
}

// Call the user_login function
user_login();

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


<?php

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to connect to database
function db_connect() {
  $conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
  return $conn;
}

// Function to check user credentials
function login_user($username, $password) {
  $conn = db_connect();
  
  // Prepare SQL query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";
  
  // Bind parameters
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "ss", $username, $password);
  
  // Execute query
  if (mysqli_stmt_execute($stmt)) {
    // Fetch results
    mysqli_stmt_bind_result($stmt, $result);
    
    // Check if user exists and password is correct
    while (mysqli_stmt_fetch($stmt)) {
      if ($result == '1') {
        // Login successful, return true and user data
        return array(true, $_SESSION['username'], $_SESSION['password']);
      }
    }
  }
  
  // Login failed, return false
  return array(false, null);
}

// Check for POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  // Hash password before checking user credentials
  $hashed_password = hash('sha256', $password);
  
  // Call login_user function
  $result = login_user($username, $hashed_password);
  
  if ($result[0]) {
    // Login successful, redirect to protected page
    header("Location: protected.php");
    exit;
  } else {
    // Login failed, display error message
    echo "Invalid username or password";
  }
}

?>


<?php

// Configuration settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Connect to database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Function to login user
function loginUser($email, $password) {
  global $conn;

  // Prepare SQL query to check for matching email and password
  $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email AND password = :password');
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $password);

  // Execute query and fetch result
  $stmt->execute();
  $result = $stmt->fetch();

  // Check if user exists and password matches
  if ($result) {
    // Store session variables for the user
    $_SESSION['user_id'] = $result['id'];
    $_SESSION['email'] = $email;
    return true;
  } else {
    return false;
  }
}

// Form handling function to login user
function loginForm() {
  if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email and password are filled in
    if ($email && $password) {
      // Login user
      $loginResult = loginUser($email, $password);

      if ($loginResult) {
        header('Location: dashboard.php');
        exit;
      } else {
        echo 'Invalid email or password';
      }
    } else {
      echo 'Please fill in both fields';
    }
  }
}

// Initialize login form
loginForm();

?>


<?php

// Configuration variables
$database_host = 'localhost';
$database_username = 'username';
$database_password = 'password';
$database_name = 'database';

// Connect to database
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check if user exists and password is correct
function login_user() {
  global $conn;
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    return true;
  } else {
    return false;
  }
}

// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Call the login function and store result in a variable
  $logged_in = login_user();

  // If user is logged in, set session variables and redirect to dashboard
  if ($logged_in) {
    session_start();
    $_SESSION['username'] = $_POST['username'];
    header('Location: dashboard.php');
    exit;
  } else {
    echo 'Invalid username or password';
  }
} else {
  // If form has not been submitted, display login form
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  Username: <input type="text" name="username"><br><br>
  Password: <input type="password" name="password"><br><br>
  <input type="submit" value="Login">
</form>

<?php
}
?>


<?php

// Check if user is logged in and redirect to login page if not
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit;
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
</head>
<body>

Welcome, <?php echo $_SESSION['username']; ?>!

<!-- Dashboard content here -->


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
    // Check if user is already logged in
    if (isset($_SESSION['logged_in'])) {
        return true;
    }

    // Get the username and password from the form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize the input data to prevent SQL injection attacks
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query the database to check if the username and password are valid
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    // Check if the query returned any results
    if ($result->num_rows > 0) {
        // If the query returned a result, log the user in and return true
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        return true;
    } else {
        // If the query did not return any results, display an error message
        echo "Invalid username or password";
        return false;
    }
}

// Check if the form has been submitted and call the login_user function
if (isset($_POST['login'])) {
    login_user();
} else {
    // Display a login form to the user
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" name="login" value="Login">
</form>

<?php
}
?>


<?php

// Define the database connection settings
define('DB_HOST', 'your_database_host');
define('DB_USER', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Create a function to connect to the database
function db_connect() {
  $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
  return $conn;
}

// Define the login function
function login($username, $password) {
  // Connect to the database
  $conn = db_connect();

  // Prepare the SQL query
  $query = "SELECT * FROM users WHERE username = :username AND password = :password";
  $stmt = $conn->prepare($query);

  // Bind the parameters
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', md5($password));

  // Execute the query
  $stmt->execute();

  // Fetch the result
  $result = $stmt->fetch();

  // Check if the user exists and the password is correct
  if ($result) {
    return true;
  } else {
    return false;
  }
}

// Define a function to register new users
function register($username, $password, $email) {
  // Connect to the database
  $conn = db_connect();

  // Prepare the SQL query
  $query = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
  $stmt = $conn->prepare($query);

  // Bind the parameters
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', md5($password));
  $stmt->bindParam(':email', $email);

  // Execute the query
  try {
    $stmt->execute();
    return true;
  } catch (PDOException $e) {
    return false;
  }
}

?>


<?php

// Include the login.php file
include 'login.php';

// Set the username and password variables
$username = $_POST['username'];
$password = $_POST['password'];

// Call the login function
if (login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}

?>


function login_user($username, $password) {
    // Connect to the database
    include_once 'db_config.php';
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to select user data from the database
    $sql = "SELECT * FROM users WHERE username='$username'";

    // Execute the SQL query and store the result in a variable
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Hashed password comparison
            if (password_verify($password, $row['password'])) {
                // Login successful, return user data
                return array('success' => true, 'username' => $username);
            } else {
                echo "Invalid Password";
            }
        }
    } else {
        echo "No such User Found.";
    }

    // Close the database connection
    $conn->close();

    // Return an error if login fails
    return array('success' => false, 'message' => 'Login Failed');
}


// Define a sample username and password
$username = "testuser";
$password = "password123";

// Call the function with the provided credentials
$result = login_user($username, $password);

if ($result['success']) {
    // Login successful, you can proceed further
    echo "Login Successful";
} else {
    // Display the error message if login fails
    echo $result['message'];
}


<?php
/**
 * User Login Function
 *
 * @author Your Name
 */

// Define an array of valid users for testing purposes only!
$users = [
    'john' => 'password123',
    'jane' => 'ilovephp'
];

function login($username, $password) {
    global $users;

    // Check if the username and password match a user in our array
    if (isset($users[$username]) && $users[$username] === $password) {
        return true;
    } else {
        return false;
    }
}

// Example usage:
$username = 'john';
$password = 'password123';

if (login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}
?>


<?php

// Database connection settings
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Create database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get the username and password from the form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = hash('sha256', $password);

    // SQL query to select user from database where username and hashed password match
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User exists, log them in
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;

        // Redirect to protected page (e.g. dashboard)
        header("Location: dashboard.php");
    } else {
        // Incorrect username or password
        echo "Incorrect username or password";
    }
}

// Check if the login form has been submitted
if (isset($_POST['login'])) {
    login_user();
}
?>


<?php

// Configuration variables
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to the database
$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

function login_user($username, $password) {
    global $mysqli;

    // Prepare the SQL query
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows > 0) {
        return true; // User logged in successfully
    } else {
        return false; // User not found or password incorrect
    }
}

// Example usage:
$login_username = 'example_user';
$login_password = 'example_password';

if (login_user($login_username, $login_password)) {
    echo "User logged in successfully!";
} else {
    echo "Invalid username or password.";
}

// Clean up resources
$stmt->close();
$mysqli->close();

?>


// config.php

<?php
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

?>


// login.php

<?php

require_once 'config.php';

function login($username, $password) {
  // Connect to database
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare query
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);

  // Execute query and get result
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
      return true;
    } else {
      echo "Incorrect password";
      return false;
    }
  } else {
    echo "User not found";
    return false;
  }

  $conn->close();
}

// Example usage:
$login_status = login('your_username', 'your_password');
if ($login_status) {
  echo "Login successful!";
} else {
  echo "Login failed.";
}
?>


$login_status = login($_POST['username'], $_POST['password']);
if ($login_status) {
  // Redirect to secured area or display success message
} else {
  // Display error message or redirect back to login form
}


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
// Configuration
require_once 'config.php';

function loginUser($username, $password) {
  // Check if username and password are set
  if (empty($username) || empty($password)) {
    return false;
  }

  // Query database to retrieve user information
  $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = mysqli_query($conn, $query);

  // Check if query was successful
  if (!$result) {
    die("Error: " . mysqli_error($conn));
  }

  // Retrieve user information from result set
  $user = mysqli_fetch_assoc($result);

  // If user exists and password matches, return true
  if ($user && $user['password'] == md5($password)) {
    $_SESSION['username'] = $username;
    $_SESSION['logged_in'] = true;
    return true;
  }

  // If user does not exist or password does not match, return false
  return false;
}
?>


// Assume we have already connected to the database using $conn = new mysqli('host', 'username', 'password', 'database');

$username = $_POST['username'];
$password = md5($_POST['password']);

if (loginUser($username, $password)) {
  echo "Logged in successfully!";
} else {
  echo "Invalid username or password.";
}


<?php

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'login_system');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
  // Hash the password before comparison
  $hashed_password = hash('sha256', $password);

  // Query to select user data from database
  $sql = "SELECT * FROM users INNER JOIN passwords ON users.id = passwords.user_id WHERE username = '$username' AND password_hash = '$hashed_password'";
  
  // Execute the query and get result
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    return true;
  } else {
    return false;
  }
}

// Example usage:
$username = 'john';
$password = 'password123';

if (login_user($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}

?>


<?php

// Database connection settings
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

// Define the login function
function user_login($username, $password) {
    // Hash password before comparing with stored hash
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query to retrieve user data from database
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
    $result = $conn->query($query);

    // Check if query was successful and user exists
    if ($result->num_rows > 0) {
        // Get the first row of the result set (assuming only one user)
        $user_data = $result->fetch_assoc();

        // Set session variables for the logged-in user
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['user_id'] = $user_data['id'];

        // Redirect to dashboard or other protected page
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Invalid username or password.";
    }
}

// Check if form has been submitted (login button clicked)
if (isset($_POST['login'])) {
    user_login($_POST['username'], $_POST['password']);
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
?>


include 'login.php';

// ...

<form action="" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" name="login" value="Login">
</form>

<?php
if (isset($_POST['login'])) {
    user_login($_POST['username'], $_POST['password']);
}
?>


<?php

// Configuration variables
$databaseHost = 'localhost';
$databaseUsername = 'your_username';
$databasePassword = 'your_password';
$databaseName = 'your_database';

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($GLOBALS['databaseHost'], $GLOBALS['databaseUsername'], $GLOBALS['databasePassword'], $GLOBALS['databaseName']);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to login the user
function loginUser($username, $password) {
  // Connect to the database
  $conn = connectToDatabase();

  // Prepare SQL query to retrieve user data
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if the user exists in the database
  if ($result->num_rows == 0) {
    return false;
  }

  // Retrieve user data from the database
  $row = $result->fetch_assoc();

  // Hash and compare passwords
  $hashedPassword = hash('sha256', $password);
  if (hash_equals($hashedPassword, $row['password'])) {
    // Login successful
    return array(
      'success' => true,
      'username' => $username,
      'id' => $row['id']
    );
  } else {
    // Login failed due to incorrect password
    return false;
  }

  // Close the database connection
  $conn->close();
}

// Example usage:
$username = $_POST['username'];
$password = hash('sha256', $_POST['password']); // Hash the password before sending it

$result = loginUser($username, $password);
if ($result) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}

?>


<?php

// Configuration
$host = 'localhost';
$dbname = 'users.db';

// Connect to database
$conn = new PDO("sqlite:$dbname");

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Retrieve data from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Query the database to select user
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {

            // Verify password using password_verify()
            if (password_verify($password, $user['password'])) {

                // Login successful
                echo 'Login successful';
            } else {
                // Incorrect password
                echo 'Incorrect password';
            }
        } else {
            // User not found
            echo 'User not found';
        }
    } catch (PDOException $e) {
        // Database error
        echo 'Database error: ' . $e->getMessage();
    }

} else {

    // Display login form
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username"><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
}

?>


<?php

// Configuration settings
$dsn = 'mysql:host=localhost;dbname=your_database';
$username = 'your_username';
$password = 'your_password';

// Function to connect to the database and check credentials
function authenticateUser($username, $password) {
  try {
    // Connect to the database using PDO
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare a SQL query to retrieve user data
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the user data from the database
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // If a user is found, check their password
    if ($userData) {
      // Hash the provided password and compare it to the stored hash
      $passwordHash = md5($password);
      if ($passwordHash === $userData['password']) {
        // User authenticated successfully, return true
        return true;
      } else {
        // Password incorrect, return false
        return false;
      }
    } else {
      // No user found with that username, return false
      return false;
    }

  } catch (PDOException $e) {
    // Handle any database errors
    echo 'Error connecting to the database: ' . $e->getMessage();
    return false;
  }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (authenticateUser($username, $password)) {
    // User authenticated successfully, log them in
    session_start();
    $_SESSION['username'] = $username;
    header('Location: /dashboard');
  } else {
    // Authentication failed, display error message
    echo 'Invalid username or password';
  }
}


// db_config.php (database connection settings)
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
function connect_to_db() {
  $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
  return $conn;
}

// User login function
function user_login($username, $password) {
  // Connect to database
  $conn = connect_to_db();

  // Prepare query
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
  $stmt->bindParam(':username', $username);

  try {
    // Execute query
    $stmt->execute();
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and password is correct
    if ($user_data && password_verify($password, $user_data['password'])) {
      return true;
    } else {
      throw new Exception('Invalid username or password');
    }
  } catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
  }

  // Disconnect from database
  $conn = null;

  return false; // Return False if an exception occurred
}


// Set database connection settings
require_once 'db_config.php';

// Call user_login function with username and password
$username = $_POST['username'];
$password = $_POST['password'];

if (user_login($username, $password)) {
  echo 'Login successful!';
} else {
  echo 'Invalid username or password';
}


<?php

// Database credentials
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to hash and verify passwords
function password_verify_hashed($password, $hashedPassword)
{
    return crypt($password, $hashedPassword) === $hashedPassword;
}

// Check if the user exists in the database
$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (password_verify_hashed($password, $row['password'])) {
            // Password is correct, let the user in
            session_start();
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit;
        }
    }
} else {
    echo "User not found.";
}

// Close connection
$conn->close();

?>


<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

echo "Hello, ".$_SESSION['username']."!";

?>


$database = [
    'users' => [
        ['id' => 1, 'username' => 'admin', 'password' => hash('sha256', 'password123')],
        ['id' => 2, 'username' => 'user1', 'password' => hash('sha256', 'pass123')]
    ]
];


function login($username, $password) {
    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        return false;
    }

    // Fetch user data from database
    foreach ($GLOBALS['database']['users'] as $user) {
        if ($user['username'] == $username && hash('sha256', $password) == $user['password']) {
            // User found, return true
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            return true;
        }
    }

    // User not found or password incorrect, return false
    return false;
}


include 'login_function.php';

$username = $_POST['username'];
$password = $_POST['password'];

if (login($username, $password)) {
    header('Location: dashboard.php');
} else {
    echo 'Invalid username or password';
}


// config/db.php

$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


// login.php

require_once 'config/db.php';

function userLogin($username, $password) {
    global $pdo;

    // Prepare query to retrieve user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch user data
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData) {
        // Check if hashed password matches input password
        if (password_verify($password, $userData['password'])) {
            return true; // User logged in successfully
        } else {
            echo "Incorrect password";
            return false;
        }
    } else {
        echo "User not found";
        return false;
    }
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (userLogin($username, $password)) {
        // User logged in successfully
        echo "Welcome, $username!";
    } else {
        echo "Error logging in";
    }
}


// login.html

<form action="login.php" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>


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


// users.php
$userCredentials = [
    'user1' => [
        'password' => '$2y$10$C3B6kW4xJiF5eGfjE0q7uPvzN9DpR8bOu5MkTcI4r6sDgP7oMhRr.',
        'role' => 'admin'
    ],
    'user2' => [
        'password' => '$2y$10$C3B6kW4xJiF5eGfjE0q7uPvzN9DpR8bOu5MkTcI4r6sDgP7oMhRr.',
        'role' => 'user'
    ]
];


// login.php
require_once 'users.php';

function loginUser($username, $password) {
    global $userCredentials;

    if (!isset($userCredentials[$username])) {
        return ['error' => 'Invalid username'];
    }

    $storedPassword = $userCredentials[$username]['password'];

    // Verify password using bcrypt
    if (password_verify($password, $storedPassword)) {
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $userCredentials[$username]['role'];
        return ['success' => 'You are now logged in!'];
    } else {
        return ['error' => 'Invalid password'];
    }
}


// index.php
require_once 'login.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = loginUser($username, $password);

    if ($result['success']) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo 'Error: ' . $result['error'] . '<br>';
    }
} else {
    echo 'Please login with your credentials';
}


<?php

/**
 * User Login Function
 *
 * This function checks if the provided username and password match a record in the database.
 *
 * @param string $username The username to check
 * @param string $password The password to check
 *
 * @return array An array containing the user data or an error message on failure
 */
function login($username, $password) {
    // Connect to the database (replace with your own connection method)
    $db = new PDO('mysql:host=localhost;dbname=example', 'username', 'password');

    // Prepare a query to select the user's password hash from the database
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);

    // Execute the query and get the result
    $result = $stmt->execute();

    if ($result) {
        // Get the user's password hash from the result
        $userPasswordHash = $db->fetchColumn('password_hash');

        // Compare the provided password with the stored hash using password_verify()
        if (password_verify($password, $userPasswordHash)) {
            // If the passwords match, retrieve the user data from the database
            $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->bindParam(':username', $username);

            $userData = $stmt->fetch();

            // Return an array containing the user data
            return [
                'success' => true,
                'userData' => $userData
            ];
        } else {
            // If the passwords don't match, return an error message
            return ['error' => 'Invalid password'];
        }
    } else {
        // If there's a database error, return an error message
        return ['error' => 'Database error'];
    }
}

?>


// Call the login function with the provided username and password
$loginResult = login($_POST['username'], $_POST['password']);

if ($loginResult['success']) {
    // If the login was successful, output the user data
    echo json_encode($loginResult['userData']);
} else {
    // If there's an error, output the error message
    echo $loginResult['error'];
}


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myuser';
$password = 'mypassword';

// Create a PDO object for database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "ERROR: Could not connect. " . $e->getMessage();
}

?>


<?php
require_once 'db.php';

function login_user($username, $password) {
    // Prepare the SQL query to select the username and password from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    
    // Bind the parameters
    $stmt->bindParam(':username', $username);
    
    // Execute the query
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If the user exists and the password is correct, return true
    if ($result && password_verify($password, $result['password'])) {
        return true;
    }
    
    // If not, return false
    return false;
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (login_user($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}

?>


<?php

function loginUser($username, $password) {
  // Example: Check against a predefined array of users (replace with database query)
  $users = [
    'user1' => 'password1',
    'user2' => 'password2',
    // ...
  ];

  // Check if username and password are provided
  if (!$username || !$password) {
    return false;
  }

  // Get the stored password for the given username (or null if not found)
  $storedPassword = isset($users[$username]) ? $users[$username] : null;

  // Compare input password with stored password (case-insensitive)
  $isCorrectPassword = ($password === $storedPassword);

  return $isCorrectPassword;
}

// Example usage:
$username = 'user1';
$password = 'password1';

if (loginUser($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}


<?php

// Configuration variables
$databaseHost = 'localhost';
$databaseName = 'mydatabase';
$username = 'myuser';
$password = 'mypassword';

// Function to hash password using bcrypt
function hashPassword($password) {
    return crypt($password, '$2y$10$');
}

// Function to check user login credentials
function login($username, $password) {
    // Connect to database
    $mysqli = new mysqli($databaseHost, $username, $password, $databaseName);

    if ($mysqli->connect_error) {
        die('Connect Error: ' . $mysqli->connect_error);
    }

    // Prepare query
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute query
    $stmt->execute();

    // Fetch result
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Hashed password from database
        $hashedPassword = $row['password'];

        // Check password match using bcrypt
        if (hashPassword($password) === $hashedPassword) {
            return true;
        }
    }

    return false;
}

// Example usage:
$username = 'myusername';
$password = 'mypassword';

if (login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


<?php
// Configuration settings
$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Connect to database
$conn = new mysqli($server, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Input validation and sanitization
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Check if input fields are empty
if (empty($email) || empty($password)) {
    echo 'Please fill in all the fields.';
} else {
    // Hash password for security
    $hashed_password = hash('sha256', $password);

    // SQL query to select user data from database
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$hashed_password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // If user exists, create session and redirect to dashboard
        session_start();
        $_SESSION['user_id'] = $result->fetch_assoc()['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        echo 'Invalid email or password.';
    }
}

// Close database connection
$conn->close();
?>


// Config file for database connection details
require_once 'config.php';

// Define a function to handle user login
function loginUser($username, $password) {
    // Validate input
    if (empty($username) || empty($password)) {
        throw new Exception('Username and password are required');
    }

    // Escape special characters in username
    $username = mysqli_real_escape_string($GLOBALS['db'], $username);

    // Query database for user existence and credentials
    $query = "SELECT * FROM users WHERE username = '$username'";
    if ($result = mysqli_query($GLOBALS['db'], $query)) {
        // Fetch user data
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            // Login successful, return user data
            return array(
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email']
            );
        } else {
            // Password mismatch, throw an exception
            throw new Exception('Invalid password');
        }
    } else {
        // User not found, throw an exception
        throw new Exception('User not found');
    }
}

// Example usage:
try {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash password before storing in database (not shown here)
    // ...

    $user = loginUser($username, $password);
    echo json_encode($user); // Return user data as JSON
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(array('error' => $e->getMessage()));
}


<?php
require_once 'config.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize the input values
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Hash the password using a salt (optional, but recommended for security)
    $salt = 'your_secret_salt_here'; // replace with your own secret salt
    $hashedPassword = hash('sha256', $password . $salt);

    // Query the database to check if the username and hashed password match
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashedPassword'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    // Check if the user was found and is active
    if ($user && $user['active'] == 1) {
        // Log in the user
        $_SESSION['username'] = $username;
        $_SESSION['logged_in'] = true;

        header('Location: dashboard.php');
        exit();
    } else {
        echo "Invalid username or password";
    }
} else {
    // Display a login form if the page is not submitted
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>
    </form>
    <?php
}
?>


<?php
// Configuration settings for the database connection
$servername = "your_host_here";
$username = "your_username_here";
$password = "your_password_here";
$dbname = "your_database_name_here";

// Connect to the database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    header('Location: login.php');
    exit();
}

// Display a welcome message to the logged-in user
echo "Welcome, " . $_SESSION['username'] . "!";

?>


<?php

// Check if the form has been submitted
if (isset($_POST['username']) && isset($_POST['password'])) {

  // Include database connection script
  require_once 'db_connection.php';

  // Retrieve input values
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = md5(mysqli_real_escape_string($conn, $_POST['password']));

  // SQL query to select the user from the database
  $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {

    // User is found, store their data in a session variable
    while ($row = mysqli_fetch_assoc($result)) {
      $_SESSION['logged_in'] = true;
      $_SESSION['username'] = $row['username'];
      header('Location: index.php');
      exit;

    }

  } else {

    echo "Invalid username or password";

  }

} else {

  echo "No data submitted";

}

?>


<?php

// Include database connection script
require_once 'db_connection.php';

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login Form</title>
</head>

<body>

<form action="login.php" method="post">
  <label for="username">Username:</label><br>
  <input type="text" id="username" name="username" required><br>
  <label for="password">Password:</label><br>
  <input type="password" id="password" name="password" required><br>
  <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database_name";

// Create a new connection to the database
$conn = mysqli_connect($servername, $username, $password, $dbname);

?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

// Database connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

function login($username, $password) {
    global $mysqli;

    // Prepare the query
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // User exists and credentials are correct
            return true;
        } else {
            // Incorrect username or password
            return false;
        }
    }

    // Query execution failed
    return false;
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
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
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

function login_user($username, $password) {
  global $db_host, $db_username, $db_password, $db_name;

  // Connect to database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Get user data from database
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, check password
    while ($row = $result->fetch_assoc()) {
      if (password_verify($password, $row['password'])) {
        return true; // Login successful
      }
    }
  }

  return false; // Login failed
}

?>


<?php

// Get user credentials from form or elsewhere
$username = $_POST['username'];
$password = $_POST['password'];

// Call login_user function
if (login_user($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}

?>


<?php

// Database connection settings
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Create the connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define a function to check if username and password are correct
function login_user($username, $password) {
    global $conn;
    
    // Create the SQL query
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute the query
    $result = $conn->query($sql);
    
    // Check if there are results from the query
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Example usage of login_user function:
$username = 'testuser';
$password = 'password123';

if (login_user($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}

?>


function login_user($username, $password) {
    global $conn;
    
    // Create prepared statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    
    // Bind parameters to prevent SQL injection
    $stmt->bind_param("ss", $username, $password);
    
    // Execute query and retrieve results
    $result = $stmt->get_result();
    
    // If there are rows matching the credentials, return true; otherwise, return false.
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}


function register_user($username, $password) {
    global $conn;
    
    // Hash password using a random salt
    $salt = substr(hash('sha256', microtime(true)), 0, 16);
    $hashed_password = hash('sha256', $salt . $password);
    
    // Create prepared statement with parameterized query
    $stmt = $conn->prepare("INSERT INTO users (username, password, salt) VALUES (?, ?, ?)");
    
    // Bind parameters to prevent SQL injection
    $stmt->bind_param("sss", $username, $hashed_password, $salt);
    
    // Execute query and commit changes
    $stmt->execute();
}


function user_login($username, $password) {
    // Database connection settings
    $host = 'localhost';
    $db = 'your_database_name';
    $user = 'your_username';
    $pass = 'your_password';

    // Create a database connection
    $conn = new mysqli($host, $user, $pass, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL query to check for existing user
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);

    // Execute the prepared statement
    if (!$stmt->execute()) {
        echo "Error: Unable to fetch user data.";
        return false;
    }

    // Get result
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Check if user exists and password matches (hashed)
    if ($row && password_verify($password, $row['password'])) {
        // Login successful!
        $_SESSION['username'] = $username;
        return true;
    } else {
        echo "Error: Incorrect username or password.";
        return false;
    }

    // Close connection
    $conn->close();
}


// Call the function with a valid user's credentials
if (user_login('johnDoe', 'mysecretpassword')) {
    echo "Login successful!";
} else {
    echo "Failed to log in.";
}

// You can also use this function on form submission, checking if both fields are filled
if ($_POST['username'] && $_POST['password']) {
    user_login($_POST['username'], $_POST['password']);
}


<?php
// Configuration settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Establish database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define variables for login form data
$username = $_POST['username'];
$password = md5($_POST['password']);

// Query to check if username and password match
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();

$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if ($userData) {
    // Login successful, set session variables
    $_SESSION['username'] = $username;
    $_SESSION['logged_in'] = true;

    header('Location: dashboard.php');
} else {
    echo 'Invalid username or password';
}
?>


<?php
// Configuration settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Establish database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define variables for registration form data
$username = $_POST['username'];
$password = md5($_POST['password']);

// Query to insert new user into database
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();

if ($stmt->affected_rows == 1) {
    echo 'User created successfully';
} else {
    echo 'Error creating user';
}
?>


<?php
// Configuration settings
$databaseHost = 'localhost';
$databaseUsername = 'your_username';
$databasePassword = 'your_password';
$databaseName = 'your_database';

// Create connection to database
$mysqli = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

// Check if the connection was successful
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

// User registration function
function registerUser($username, $email, $password) {
    // Escape special characters in user input
    $username = mysqli_real_escape_string($GLOBALS['mysqli'], $username);
    $email = mysqli_real_escape_string($GLOBALS['mysqli'], $email);

    // Hash password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare query to insert new user into database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('sss', $username, $email, $hashedPassword);
    if ($stmt->execute()) {
        return true;
    } else {
        echo "Failed to register user: (" . $mysqli->errno . ") " . $mysqli->error;
        exit();
    }
}

// User login function
function loginUser($username, $password) {
    // Escape special characters in user input
    $username = mysqli_real_escape_string($GLOBALS['mysqli'], $username);

    // Prepare query to select user from database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('s', $username);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Fetch user data from result
            $user = $result->fetch_assoc();

            // Check password against stored hash
            if (password_verify($password, $user['password'])) {
                return array('success' => true, 'username' => $username);
            } else {
                echo "Incorrect username or password.";
            }
        } else {
            echo "Username not found.";
        }
    } else {
        echo "Failed to retrieve user: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    return array('success' => false);
}

// Register new user
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        registerUser($username, $email, $password);
    } else {
        echo "Please fill in all fields.";
    }
}

// Login existing user
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $result = loginUser($username, $password);
        if ($result['success']) {
            // User logged in successfully
            session_start();
            $_SESSION['username'] = $username;
            echo "Welcome, $username!";
        } else {
            echo "Incorrect username or password.";
        }
    } else {
        echo "Please fill in all fields.";
    }
}
?>


<?php
// Configuration
$database_host = 'localhost';
$database_name = 'mydb';
$database_username = 'myusername';
$database_password = 'mypassword';

// Connect to database
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user credentials
function check_credentials($username, $password) {
    // SQL query to select user data from database
    $sql = "SELECT * FROM users WHERE username='$username' AND password=MD5('$password')";
    
    // Execute query and get result
    $result = $conn->query($sql);
    
    // Check if user exists
    if ($result->num_rows > 0) {
        return true; // Credentials are valid
    } else {
        return false; // Credentials are invalid
    }
}

// Function to login user
function login_user($username, $password) {
    global $conn;
    
    // Check if credentials are valid
    if (check_credentials($username, $password)) {
        // Get user data from database
        $sql = "SELECT * FROM users WHERE username='$username'";
        
        // Execute query and get result
        $result = $conn->query($sql);
        
        // Fetch user data
        $user_data = $result->fetch_assoc();
        
        // Set session variables
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['email'] = $user_data['email'];
        
        return true; // User logged in successfully
    } else {
        return false; // Credentials are invalid
    }
}

// Login form handler
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (login_user($username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo 'Invalid credentials';
    }
}
?>


<?php

// Configuration settings
$database_host = 'localhost';
$database_name = 'mydb';
$database_username = 'myuser';
$database_password = 'mypassword';

// Create a connection to the database
function connect_to_database() {
  global $database_host, $database_name, $database_username, $database_password;
  $conn = new mysqli($database_host, $database_username, $database_password, $database_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to check user credentials
function check_user_credentials($username, $password) {
  global $database_host, $database_name, $database_username, $database_password;
  $conn = connect_to_database();
  $query = "SELECT * FROM users WHERE username='$username' AND password=SHA1('$password')";
  $result = $conn->query($query);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      return array(
        'success' => true,
        'user_id' => $row['id'],
        'username' => $row['username']
      );
    }
  } else {
    return array('success' => false, 'error' => 'Invalid username or password');
  }
  $conn->close();
}

// Function to log in a user
function login_user($username, $password) {
  global $database_host, $database_name, $database_username, $database_password;
  $user_credentials = check_user_credentials($username, $password);
  if ($user_credentials['success']) {
    return array(
      'success' => true,
      'message' => 'You have successfully logged in'
    );
  } else {
    return array('success' => false, 'error' => $user_credentials['error']);
  }
}

// Example usage:
$username = 'example_user';
$password = 'password123';

$user_login_result = login_user($username, $password);
if ($user_login_result['success']) {
  echo "You have successfully logged in";
} else {
  echo "Login failed: " . $user_login_result['error'];
}

?>


<?php

// Predefined user data (replace with database connection)
$users = array(
    'admin' => array('password' => 'admin123', 'name' => 'Admin User'),
    'user1' => array('password' => 'user1pass', 'name' => 'User 1')
);

function login($username, $password) {
    global $users;

    // Check if username exists
    if (!isset($users[$username])) {
        return false;
    }

    // Compare password
    if ($users[$username]['password'] === $password) {
        return true; // Login successful
    } else {
        return false; // Incorrect password
    }
}

// Example usage:
$success = login('admin', 'admin123');
if ($success) {
    echo "Login successful! Welcome, Admin User.";
} else {
    echo "Invalid username or password.";
}


<?php

// Configuration variables (update as needed)
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'users';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
  global $conn;

  // Get user input
  if (!isset($_POST['username']) || !isset($_POST['password'])) {
    return array('error' => 'Missing required fields');
  }

  $username = $_POST['username'];
  $password = $_POST['password'];

  // Query database for user
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Check password (in a real application, use a secure password hashing algorithm)
    while ($row = $result->fetch_assoc()) {
      if ($password == $row['password']) {
        // User exists and password is correct
        return array('success' => true, 'message' => 'User logged in successfully');
      }
    }
  }

  // Return error message
  return array('error' => 'Invalid username or password');
}

if (isset($_POST['login'])) {
  $response = login_user();
  echo json_encode($response);
}


<?php

// Configuration constants
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connection to database
function dbConnect() {
    try {
        $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo 'ERROR: Unable to connect. '. $e->getMessage();
        exit();
    }
}

function login($username, $password) {
    try {
        // Connect to database
        $db = dbConnect();

        // Query for user
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login successful, store user data in session
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $user['id'];
            return true;
        } else {
            throw new Exception('Invalid username or password');
        }
    } catch (PDOException $e) {
        echo 'ERROR: '. $e->getMessage();
        exit();
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage() . '<br>';
        return false;
    }
}

?>


<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form input
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login($username, $password)) {
        echo 'Login successful';
        header('Location: dashboard.php');
        exit();
    } else {
        echo 'Invalid username or password';
    }
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>


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
/**
 * User Login Function
 *
 * @package     YourProjectName
 * @author      Your Name
 */

// Configuration
define('DB_HOST', 'your_database_host');
define('DB_USER', 'your_database_user');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
    global $conn;

    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if username and password exist in database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, login successful
        return true;
    } else {
        // User not found or incorrect password
        return false;
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login_user($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password";
    }
}

// Close connection
$conn->close();
?>


<?php
// Include database connection file
require_once 'db.php';

function login_user($username, $password) {
    // Check if username and password are not empty
    if (empty($username) || empty($password)) {
        return array(false, "Username and password cannot be empty.");
    }

    // Prepare SQL query
    $query = "
        SELECT * 
        FROM users 
        WHERE username = :username 
        AND password = :password
    ";

    try {
        // Execute query with prepared statement
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Fetch user data if credentials are correct
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and password is correct
        if ($user_data && password_verify($password, $user_data['password'])) {
            return array(true, $user_data);
        } else {
            return array(false, "Invalid username or password.");
        }

    } catch (PDOException $e) {
        // Handle database error
        return array(false, "Database error: " . $e->getMessage());
    }
}


// Set up database connection in db.php file
require_once 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$result = login_user($username, $password);

if ($result[0]) {
    // Login successful, redirect to dashboard or perform other actions
    echo "Login successful. Welcome, {$username}!";
} else {
    // Display error message
    echo "Error: " . $result[1];
}


<?php

// Define the database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
    // Hash the password for security
    $hashed_password = hash('sha256', $password);

    // Query the database to check if the username and hashed password match
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
    $result = $conn->query($sql);

    // Check if there is a match
    if ($result->num_rows > 0) {
        // If there is a match, retrieve the user's data and return it
        while ($row = $result->fetch_assoc()) {
            return array(
                "id" => $row["id"],
                "username" => $row["username"],
                "email" => $row["email"]
            );
        }
    } else {
        // If there is no match, return false
        return false;
    }
}

// Example usage:
$username = "your_username";
$password = "your_password";

$user_data = login_user($username, $password);

if ($user_data) {
    print("Login successful! User data:");
    print_r($user_data);
} else {
    print("Invalid username or password.");
}
?>


<?php

// Database connection settings (update these)
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
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare and execute SQL query to select user from database
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Hashed password in the database
                if (password_verify($password, $row['password'])) {
                    $_SESSION['logged_in'] = true;
                    header('Location: index.php'); // Redirect to index page after login
                    exit();
                }
            }
        }

        echo "Invalid username or password";
    } else {
        echo "Not a POST request";
    }
}

// Start the session
session_start();

// Check if user is already logged in
if (isset($_SESSION['logged_in'])) {
    header('Location: index.php'); // Redirect to index page after login
    exit();
}

?>

<!-- HTML form for user input -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" name="login" value="Login">
</form>

<?php login_user(); ?>


<?php

// Connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Create a PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to database: " . $e->getMessage());
}

function login_user($username, $password) {
    try {
        // Prepare a query to select the user's credentials
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        
        // Bind the username parameter
        $stmt->bindParam(':username', $username);
        
        // Execute the query
        $stmt->execute();
        
        // Get the result
        $result = $stmt->fetch();
        
        if ($result) {
            // Hashed password from database is compared with provided hashed password
            // Use a library like `password_hash` to securely hash passwords
            if (hash_equals($result['password'], password_hash($password, PASSWORD_DEFAULT))) {
                return array('success' => true, 'message' => 'Login successful!');
            } else {
                return array('success' => false, 'message' => 'Invalid username or password.');
            }
        } else {
            return array('success' => false, 'message' => 'Invalid username or password.');
        }
    } catch (PDOException $e) {
        return array('success' => false, 'message' => 'An error occurred: ' . $e->getMessage());
    }
}

// Example usage
$username = $_POST['username'];
$password = $_POST['password'];

$result = login_user($username, $password);

if ($result['success']) {
    // Login was successful, display a success message or proceed to the secured area
    echo '<p style="color:green;">' . $result['message'] . '</p>';
} else {
    // Display an error message for failed login attempts
    echo '<p style="color:red;">' . $result['message'] . '</p>';
}

?>


<?php
// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Establish database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    if (isset($_POST['login'])) {
        // Prepare and execute login query
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $_POST['username']);
        $stmt->bindParam(':password', $_POST['password']);
        $stmt->execute();
        
        // Check if user exists and password matches
        if ($stmt->rowCount() == 1) {
            // User logged in successfully, retrieve data
            $userData = $stmt->fetch();
            
            // Session variables for logged-in user
            $_SESSION['username'] = $userData['username'];
            $_SESSION['id'] = $userData['id'];
            
            echo "Logged in successfully!";
        } else {
            echo "Invalid username or password.";
        }
    }
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>


/**
 * User Login Function
 *
 * @param string $username The username to log in with.
 * @param string $password  The password to use for logging in.
 *
 * @return bool True if the login was successful, false otherwise.
 */
function loginUser($username, $password) {
    // Connect to database
    require_once 'dbconfig.php';
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Check connection
    if ($mysqli->connect_errno) {
        throw new Exception("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    }

    // Prepare query
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute query
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute query: (" . $mysqli->errno . ") " . $mysqli->error);
    }

    // Get result
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check password
    if ($user && password_verify($password, $user['password'])) {
        return true;
    } else {
        return false;
    }
}


$username = 'john';
$password = 'secret';

if (loginUser($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


<?php

// Database connection settings
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

// Function to check user credentials
function login_user($username, $password) {
    // Connect to the database
    $conn = db_connect();

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");

    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // If user exists and credentials are correct, return true
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }

    // Close connection
    $conn->close();
}

// Example usage:
$username = "example_user";
$password = "password123";

if (login_user($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}

?>


<?php

// Define database connection settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create connection to database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get form data from POST request
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query database for user credentials
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Get user data from query result
        while ($row = $result->fetch_assoc()) {
            $stored_password = $row['password'];

            // Check password match
            if (password_verify($password, $stored_password)) {
                return true; // Login successful
            }
        }
    }

    return false; // Login failed
}

// Call login_user function and store result in a variable
$login_result = login_user();

if ($login_result) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}

?>


<?php
require_once 'config.php'; // Load your database configuration file

// Define the form data
function validate_user($username, $password) {
    global $db; // Access to the database connection variable

    // Sanitize and escape user input
    $username = mysqli_real_escape_string($db, $username);
    $password = hash('sha256', $password); // Use a secure password hashing algorithm like SHA-256

    // Query the database for a matching username and password
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($db, $query);

    // Check if there's at least one row returned from the query
    if (mysqli_num_rows($result) > 0) {
        // If there's a match, return the user data as an associative array
        $user_data = mysqli_fetch_assoc($result);
        return $user_data;
    } else {
        // If no match found, return false or throw an exception
        return false;
    }
}

// Function to handle login form submission
function process_login() {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate the user input using the `validate_user` function
        $user_data = validate_user($username, $password);

        if ($user_data !== false) {
            // Login successful! Set session variables and redirect to protected area
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $user_data['username'];
            header('Location: protected_area.php');
            exit();
        } else {
            // Login failed, display error message
            echo '<p>Invalid username or password.</p>';
        }
    }
}

// Initialize the login process on page load (if form is submitted)
if (isset($_POST['submit'])) {
    process_login();
}
?>


<?php
$db = new mysqli('localhost', 'username', 'password', 'database_name');
mysqli_set_charset($db, 'utf8'); // Set character encoding

// Define error handling for database operations
function db_error_handler() {
    echo "Database error: ";
    var_dump(mysqli_error($GLOBALS['db']));
}

// Call the error handler on any query execution errors
mysqli_report(MYSQLI_REPORT_ALL | MYSQLI_REPORT_ERROR);
?>


<form action="login.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <button type="submit" name="submit">Login</button>
</form>


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


function check_credentials($username, $password) {
    // Connect to the database (example using PDO)
    $conn = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');

    // Query the database for the user's information
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the user's data
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a match was found and the password is correct
    if ($result && password_verify($password, $result['password'])) {
        return true;
    } else {
        return false;
    }

    // Close the database connection
    $conn = null;
}


function login_user($username) {
    // Get the user's data from the database (example using PDO)
    $conn = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a match was found
    if ($result) {
        // Start the session and set the user's ID and name
        session_start();
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['username'] = $username;

        // Redirect to a secure page (e.g., dashboard)
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Invalid username or password.";
    }

    // Close the database connection
    $conn = null;
}


function logout_user() {
    // Destroy the session
    session_start();
    $_SESSION = array();
    session_destroy();

    // Redirect to the login page
    header('Location: login.php');
    exit;
}


// Check credentials
if (check_credentials($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}

// Login user
login_user($username);

// Logout user
logout_user();


// config.php (store database credentials)
$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myusername';
$password = 'mypassword';

// login.php (login form and logic)
<?php
require_once 'config.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input from form
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Connect to database using PDO
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL query to select user by email and password
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email AND password = :password');
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Get the result of the query
        $result = $stmt->fetch();

        if ($result) {
            // User found, log them in and redirect to dashboard
            $_SESSION['user_id'] = $result['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            // Invalid email or password
            echo 'Invalid email or password';
        }
    } catch (PDOException $e) {
        // Handle database error
        echo 'Database error: ' . $e->getMessage();
    }
} else {
    // Show login form if not submitted
    ?>
    <form method="post">
        <label>Email:</label>
        <input type="email" name="email" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
    <?php
}
?>


// Define database connection settings
$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Set up PDO to connect to database
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Database connection failed: ' . $e->getMessage();
}


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Login function
function login($username, $password) {
    // Check if username exists in database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Fetch user data
        $user_data = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user_data['password_hash'])) {
            return true; // Password matches, login successful
        } else {
            return false; // Password does not match
        }
    } else {
        return false; // Username not found in database
    }
}

// Main logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo 'Please fill in both username and password.';
    } else {
        $login_result = login($username, $password);

        if ($login_result) {
            // Login successful, redirect to dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            echo 'Invalid username or password.';
        }
    }
}

?>


<?php
/**
 * User Login Function
 *
 * This function checks if the username and password entered by the user match the stored credentials.
 *
 * @param string $username The username entered by the user
 * @param string $password  The password entered by the user
 *
 * @return bool Whether the login was successful or not
 */
function user_login($username, $password) {
    // Database connection settings
    $db_host = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $db_name = 'mydatabase';

    // Connect to database
    try {
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL query to retrieve user data
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch user data from database
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if username exists in database and password matches
        if ($user_data && password_verify($password, $user_data['password'])) {
            // Login successful, store user data in session variables
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['email'] = $user_data['email'];

            return true;
        } else {
            // Incorrect username or password
            return false;
        }
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        return false;
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}
?>


<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (user_login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}
?>


<?php

// Database configuration
$host = 'localhost';
$dbname = 'users_database';
$user = 'your_username';
$password = 'your_password';

// Connect to the database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);

?>


<?php

require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch result
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Hashed password comparison (using a library like bcrypt)
        require_once 'password.php';
        if (verify_password($password, $user['password'])) {
            // User authenticated successfully
            session_start();
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit;
        } else {
            echo "Incorrect password";
        }
    } else {
        echo "User not found";
    }

} else {
    // Display login form
?>

<form method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>

<?php
}
?>


<?php

function verify_password($password, $hash) {
    // Bcrypt library implementation
    require_once 'vendor/autoload.php';
    use Benschmidt\Bcrypt\Bcrypt;

    return (new Bcrypt())->verify($password, $hash);
}

?>


<?php
/**
 * User Login Function
 *
 * @param string $username Username to log in with
 * @param string $password Password to log in with
 * @return bool True if login successful, False otherwise
 */
function userLogin($username, $password) {
    // Database connection parameters (replace with your own)
    $host = 'localhost';
    $database = 'your_database_name';
    $username_db = 'your_database_username';
    $password_db = 'your_database_password';

    try {
        // Establish database connection
        $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL query to select user data from database
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch result and check if user exists
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password (hashed in database)
            $hash = hash('sha256', $password . config_get('salt'));
            if ($hash === $user['password']) {
                return true;
            }
        }

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    // Login failed
    return false;
}
?>


<?php
$loginSuccessful = userLogin('your_username', 'your_password');

if ($loginSuccessful) {
    echo 'Login successful!';
} else {
    echo 'Invalid username or password';
}

// Note: Passwords should be hashed before storing in the database, and this example uses a very basic form of hashing.
?>


<?php

// Configuration
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Connect to database
$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

function login_user() {
  global $conn;

  if (isset($_POST['username']) && isset($_POST['password'])) {

    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to select the user from database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {

          // Login successful
          $_SESSION['logged_in'] = true;
          $_SESSION['username'] = $username;

          header('Location: dashboard.php');
          exit();
        }
      }
    } else {
      echo 'Invalid username or password.';
    }
  }
}

if (isset($_POST['login'])) {
  login_user();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

<form action="" method="post">
  <label>Username:</label><br>
  <input type="text" name="username"><br>
  <label>Password:</label><br>
  <input type="password" name="password"><br>
  <button type="submit" name="login">Login</button>
</form>

</body>
</html>


<?php

// Configuration settings
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'mydatabase';

try {
    // Establish database connection
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function login($username, $password) {
        global $conn;

        // Query to select user by username and password
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        // Execute query and fetch result
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // If user exists, return their ID
            return $result['id'];
        } else {
            // If user does not exist or password is incorrect, return false
            return false;
        }
    }

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

?>


// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$userId = login($username, $password);

if ($userId !== false) {
    // User has logged in successfully. Store their ID in a session variable.
    $_SESSION['user_id'] = $userId;
} else {
    // User has failed to log in. Display an error message or redirect them back to the login page.
}


<?php

// Configuration settings
$databaseHost = 'localhost';
$databaseName = 'mydatabase';
$databaseUsername = 'myusername';
$databasePassword = 'mypassword';

// Connect to database
$conn = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to hash password (for security)
function password_hasher($password) {
    return crypt($password, '$2y$10$' . substr(hash('sha256', microtime(true)), 0, 22));
}

// Login function
function login_user($username, $password) {
    // Prepare SQL query to select user
    $sql = "SELECT * FROM users WHERE username = '$username'";
    
    // Execute query and fetch result
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Check if hashed password matches input password
            if (password_verify($password, $row['password'])) {
                return array(
                    'success' => true,
                    'user_id' => $row['id'],
                    'username' => $row['username']
                );
            } else {
                echo "Invalid password";
                exit();
            }
        }
    } else {
        echo "User not found";
        exit();
    }
}

// Example usage
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $result = login_user($username, $password);
    
    if ($result) {
        // Login successful, store user data in session variable
        $_SESSION['user'] = $result;
        
        echo "Login successful!";
    } else {
        echo "Invalid username or password";
    }
}

// Close database connection
$conn->close();

?>


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Establish database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Login function
function login($email, $password) {
    global $conn;

    // Prepare SQL statement to check for user existence and password match
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");

    // Bind parameters
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    // Execute SQL statement
    if ($stmt->execute()) {
        return true; // User exists and password matches
    } else {
        return false; // Error occurred or user does not exist
    }
}

// Example usage:
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (login($email, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid email or password.";
    }
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
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Connect to database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

function login($username, $password) {
  global $conn;

  // Prepare SQL query
  $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  // Get result from database
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result && password_verify($password, $result['password'])) {
    // User found and password correct
    $_SESSION['username'] = $username;
    $_SESSION['logged_in'] = true;

    return true; // Login successful

  } else {
    // User not found or incorrect password
    return false; // Login failed
  }
}

if (isset($_POST['login'])) {
  // Get user input from form submission
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Call login function
  if (login($username, $password)) {
    header('Location: dashboard.php');
    exit();
  } else {
    echo 'Invalid username or password';
  }
}

?>


<?php
// Configuration settings
$database_host = 'localhost';
$database_username = 'your_username';
$database_password = 'your_password';
$database_name = 'your_database';

// Create database connection
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to login user
function login_user($username, $password) {
    global $conn;
    
    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User found, return true and user data
        $row = $result->fetch_assoc();
        return array(true, $row);
    } else {
        // User not found, return false
        return array(false, null);
    }
}

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from POST data
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Hash password (optional)
    $hashed_password = hash('sha256', $password);
    
    // Login user
    $result = login_user($username, $hashed_password);
    
    if ($result[0]) {
        // User logged in successfully, redirect to dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        // User not logged in, display error message
        echo 'Invalid username or password';
    }
}

// Close database connection
$conn->close();
?>


<?php
// Configuration settings
$database_host = 'localhost';
$database_username = 'your_username';
$database_password = 'your_password';
$database_name = 'your_database';

// Create database connection
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user data from session (optional)
$user_data = $_SESSION['user_data'];

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
</head>
<body>
  <?php echo 'Welcome, ' . $user_data['username']; ?>
  <!-- Dashboard content here -->
</body>
</html>


<?php

// Configuration
define('DB_HOST', 'your_database_host');
define('DB_USER', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);

// Function to login user
function login($username, $password) {
    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    
    // Bind parameters
    $stmt->bindParam(':username', $username);
    
    // Execute query
    $stmt->execute();
    
    // Fetch result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if user exists and password is correct
    if ($result && password_verify($password, $result['password'])) {
        return true;
    } else {
        return false;
    }
}

// Example usage:
$username = 'your_username';
$password = 'your_password';

if (login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


// Define an array of users for demonstration purposes only
$users = [
    'user1' => ['password' => 'password123', 'role' => 'admin'],
    'user2' => ['password' => 'password456', 'role' => 'moderator']
];

/**
 * User Login Function
 *
 * @param string $username The username to login with
 * @param string $password  The password to login with
 * @return array|false     An array of user data if the login is successful, false otherwise
 */
function loginUser($username, $password) {
    global $users;

    // Check if the username exists in the users array
    if (isset($users[$username])) {
        // Check if the provided password matches the stored password
        if ($password === $users[$username]['password']) {
            return $users[$username];
        }
    }

    // If the login fails, return false
    return false;
}

// Example usage:
$username = 'user1';
$password = 'password123';

if (loginUser($username, $password)) {
    print('Login successful! User data: ');
    print_r(loginUser($username, $password));
} else {
    print('Invalid username or password.');
}


<?php

// Database connection settings
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

  // Get user input
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Prepare SQL query
  $query = "SELECT * FROM users WHERE username=? AND password=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $username, $password);

  // Execute query and get result
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  // Close statement and connection
  $stmt->close();
  $conn->close();
}

if (isset($_POST['submit'])) {
  $login_result = login_user();

  if ($login_result) {
    echo "Login successful!";
  } else {
    echo "Invalid username or password.";
  }
}


<?php
// Define the database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

// Create a new connection to the database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Define the function to login the user
function login_user() {
    global $conn;

    // Get the username and password from the form submission
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // Query the database for a match
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    // Check if there is a match
    if (mysqli_num_rows($result) > 0) {
        // Get the user's ID from the database
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['id'];
        return true;
    } else {
        return false;
    }
}

// Check if the login form has been submitted
if (isset($_POST['submit'])) {
    // Call the login_user function to attempt the login
    $login_result = login_user();

    // If the user was logged in, redirect them to a protected page
    if ($login_result) {
        header("Location: protected-page.php");
        exit();
    } else {
        echo "Invalid username or password";
    }
}
?>


<?php
// Define database connection details
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define login function
function loginUser($username, $password) {
    global $conn;

    // Query the database for user existence and password match
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '" . md5($password) . "'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Check login credentials
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    if (loginUser($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}

// Close database connection
$conn->close();
?>


<?php
require_once 'login.php';

// Create login form
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" name="login" value="Login">
</form>

<?php
?>


<?php

// Database configuration
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;
    
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate user input
    if (empty($username) || empty($password)) {
        echo "Please enter both username and password.";
        return false;
    }

    // Prepare SQL query
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute the query
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['id'] = $row['id'];
            return true;
        }
    } else {
        echo "Invalid username or password.";
        return false;
    }
}

// Example usage:
if (isset($_POST['login'])) {
    if (login_user()) {
        header('Location: dashboard.php');
        exit();
    }
}

?>


$query = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $_POST['username'], $_POST['password']);


<?php

function user_login($username, $password) {
    // Database connection settings
    $host = 'localhost';
    $db_name = 'users';
    $user = 'root';
    $pass = '';

    // Establish database connection
    try {
        $conn = new PDO('mysql:host=' . $host . ';dbname=' . $db_name, $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL query to retrieve user data from the database
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);

        try {
            $stmt->execute();
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user_data !== false) {
                // Verify password
                if (password_verify($password, $user_data['password'])) {
                    // User logged in successfully
                    session_start();
                    $_SESSION['username'] = $username;
                    return true; // Return true to indicate successful login
                } else {
                    // Password is incorrect
                    echo "Incorrect password";
                    return false; // Return false to indicate failed login
                }
            } else {
                // User not found
                echo "User not found";
                return false; // Return false to indicate failed login
            }
        } catch (PDOException $e) {
            // Handle database errors
            echo 'Database error: ' . $e->getMessage();
            return false;
        }

    } catch (PDOException $e) {
        // Handle PDO connection errors
        echo 'Error connecting to database: ' . $e->getMessage();
        return false;
    }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

if ($user_login($username, $password)) {
    header('Location: dashboard.php');
} else {
    echo "Login failed";
}
?>


function login_user($username, $password) {
  // Connect to the database
  require_once 'database.php';
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute query
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if user exists
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      // Compare passwords using password_verify
      if (password_verify($password, $row['password'])) {
        return true; // User logged in successfully
      } else {
        return false; // Incorrect password
      }
    }
  }

  // If no user exists or incorrect password
  return false;
}


require_once 'login_user.php';

$username = "john_doe";
$password = "password123";

if (login_user($username, $password)) {
  echo "User logged in successfully!";
} else {
  echo "Incorrect username or password.";
}


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

// Check if user has submitted the login form
if (isset($_POST['username']) && isset($_POST['password'])) {

    // Extract user input from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to database
    $servername = "localhost";
    $usernameDB = "root";
    $passwordDB = "";
    $dbname = "mydatabase";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $usernameDB, $passwordDB);
        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute SQL query to select user data from database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch the result of the SQL query
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_data) {

            // Check if password matches stored in database
            if (password_verify($password, $user_data['password'])) {

                // Login successful, redirect to dashboard or other protected page
                header('Location: dashboard.php');
                exit;

            } else {
                echo "Invalid username or password";
            }

        } else {
            echo "User not found";
        }
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    // Close database connection
    $conn = null;
}

?>


<?php
// Database connection settings
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login() {
    global $conn;
    
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username and password are not empty
    if (empty($username) || empty($password)) {
        echo "Please fill in all fields";
        return;
    }

    // Query database to check for the username and password
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // If there is a match, then we create an array to store the user data
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $row['username'];
            header('Location: dashboard.php');
        }
    } else {
        echo "Incorrect username or password";
    }
}

if (isset($_POST['login'])) {
    login();
}
?>


<?php
require_once 'config.php'; // assumes config.php has database connection settings

function login_user($username, $password) {
    global $db; // assuming $db is the database object

    // Sanitize input
    $username = mysqli_real_escape_string($db, $username);
    $password = mysqli_real_escape_string($db, $password);

    // Query to check if user exists and password matches
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($db, $query);

    // Check if query was successful
    if (!$result) {
        return array('error' => 'Database error');
    }

    // Get user data from result
    $user_data = mysqli_fetch_assoc($result);

    // If user exists and password matches, return user data
    if ($user_data) {
        return array(
            'success' => true,
            'username' => $user_data['username'],
            'email' => $user_data['email']
        );
    } else {
        return array('error' => 'Invalid username or password');
    }
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = login_user($username, $password);

    if ($result['success']) {
        echo "Welcome, $result[username]!";
    } else {
        echo $result['error'];
    }
}
?>


<?php
// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$user = 'myuser';
$password = 'mypassword';

// Create database object
$db = new mysqli($host, $user, $password, $dbname);

if ($db->connect_errno) {
    die("Connect failed: " . $db->connect_error);
}
?>


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

// Establish database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate user credentials
function login_user($username, $password) {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $username);
    $password = md5(mysqli_real_escape_string($conn, $password));

    // Query database for user information
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // User found, retrieve user data
        $user_data = $result->fetch_assoc();

        // Create session to store user data
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['id'] = $user_data['id'];

        return true;
    } else {
        return false;
    }
}

// Function to check if user is already logged in
function is_user_logged_in() {
    if (isset($_SESSION['username'])) {
        return true;
    } else {
        return false;
    }
}

?>


<?php

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login_user($username, $password)) {
        echo "Logged in successfully!";
    } else {
        echo "Invalid username or password.";
    }
}

?>


<?php

// Database connection settings
$database = array(
    'host' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'name' => 'your_database_name'
);

// Function to connect to the database
function db_connect() {
    global $database;
    return new mysqli($database['host'], $database['username'], $database['password'], $database['name']);
}

// Function to disconnect from the database
function db_disconnect($conn) {
    $conn->close();
}

// Function to hash passwords (using PHP's built-in password_hash function)
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Login function
function user_login($username, $password) {
    global $database;
    
    // Connect to the database
    $conn = db_connect();
    
    // Check if the username exists in the users table
    $query = "SELECT id FROM users WHERE username = '$username'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        // Get the user's ID from the result
        $row = $result->fetch_assoc();
        $user_id = $row['id'];
        
        // Retrieve the hashed password for the given username
        $query = "SELECT password_hash FROM user_credentials WHERE user_id = '$user_id'";
        $result2 = $conn->query($query);
        if ($result2->num_rows > 0) {
            // Get the hashed password from the result
            $row2 = $result2->fetch_assoc();
            $hashed_password = $row2['password_hash'];
            
            // Check if the submitted password matches the stored hashed password
            if (password_verify($password, $hashed_password)) {
                // Successful login! Return true and the user's ID.
                return array(true, $user_id);
            } else {
                // Invalid credentials. Return false.
                return array(false, null);
            }
        } else {
            // No stored hashed password for this username. Return false.
            return array(false, null);
        }
    } else {
        // Username not found in the users table. Return false.
        return array(false, null);
    }
    
    // Disconnect from the database
    db_disconnect($conn);
}

// Example usage:
$credentials = user_login('username', 'password');
if ($credentials[0]) {
    echo "Login successful! Your user ID is: " . $credentials[1];
} else {
    echo "Invalid login credentials.";
}


<?php

// Connect to MySQL database
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
  global $conn;

  if (isset($_POST['username']) && isset($_POST['password'])) {
      $username = $_POST['username'];
      $password = md5($_POST['password']); // use md5 for password hashing

      $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $_SESSION['user_id'] = $row['id'];
              return true; // login successful
          }
      } else {
          echo "Invalid username or password";
      }

      return false; // login failed
  }

  return false;
}

?>


<?php
include 'login.php';

if (isset($_POST['submit'])) {
  if (login_user()) {
    echo "Login successful!";
  } else {
    echo "Invalid username or password";
  }
}
?>

<form action="" method="post">
  <input type="text" name="username" placeholder="Username">
  <input type="password" name="password" placeholder="Password">
  <button type="submit">Submit</button>
</form>


<?php

// Configuration settings
$database_host = 'localhost';
$database_username = 'your_username';
$database_password = 'your_password';
$database_name = 'your_database';

// Establish database connection
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;

    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input (escape special characters)
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query database for matching username and password
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $row['id'];
            header('Location: dashboard.php');
            exit;
        }
    } else {
        echo 'Invalid username or password';
    }

    // Close database connection
    $conn->close();
}

if (isset($_POST['login'])) {
    login_user();
} else {
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        <button type="submit" name="login">Login</button>
    </form>
    <?php
}

?>


<?php
// Configuration variables
$databaseHost = 'localhost';
$databaseName = 'your_database_name';
$databaseUsername = 'your_database_username';
$databasePassword = 'your_database_password';

// Connect to the database
$mysqli = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

// Check connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

function login_user() {
    global $mysqli;

    // Get user input from form
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare query to check username and password
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists and password is correct
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // If user exists, log them in and return their ID
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                header('Location: dashboard.php');
                exit;
            }
        } else {
            echo "Invalid username or password";
        }

        // Close the statement
        $stmt->close();
    }
}

// Check if form has been submitted and call login_user function
if (isset($_POST['login'])) {
    login_user();
}
?>


<?php
require 'login.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Username:</label><br>
    <input type="text" name="username"><br>
    <label>Password:</label><br>
    <input type="password" name="password"><br>
    <button type="submit" name="login">Login</button>
</form>

<?php
if (isset($_SESSION['user_id'])) {
    echo "Welcome, " . $_SESSION['username'] . "!";
}
?>


<?php

// Define the users array
$users = [
    'admin' => 'password123',
    'user1' => 'password456'
];

function login($username, $password) {
    // Check if the username exists in the users array
    if (array_key_exists($username, $users)) {
        // If the password matches, return true and a message
        if ($password === $users[$username]) {
            return ['success' => true, 'message' => 'Logged in successfully'];
        } else {
            // Password mismatch, return an error message
            return ['error' => 'Incorrect password', 'message' => null];
        }
    } else {
        // Username not found, return an error message
        return ['error' => 'Username not found', 'message' => null];
    }
}

// Example usage:
$credentials = [
    'username' => 'admin',
    'password' => 'password123'
];

$result = login($credentials['username'], $credentials['password']);

if ($result['success']) {
    echo "Logged in successfully!";
} elseif ($result['error']) {
    echo "Error: {$result['message']}";
}

?>


$result = login('admin', 'password123');
echo $result['message']; // "Logged in successfully!"


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Connect to database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

function login_user($email, $password) {
    // SQL query to retrieve user data
    $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Fetch the user's password and hash it for comparison
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        return array(
            'success' => true,
            'message' => 'Login successful',
            'user_id' => $user['id'],
            'username' => $user['username']
        );
    } else {
        return array(
            'success' => false,
            'message' => 'Invalid email or password'
        );
    }
}

function authenticate_user() {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $response = login_user($email, $password);

        if ($response['success'] == true) {
            // Set session variables
            $_SESSION['user_id'] = $response['user_id'];
            $_SESSION['username'] = $response['username'];

            header('Location: dashboard.php');
            exit;
        } else {
            // Display error message
            echo 'Invalid email or password';
        }
    } else {
        // Redirect to login page if no email or password provided
        header('Location: index.php');
        exit;
    }
}

// Call the authenticate_user function on each request
authenticate_user();

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

// Configuration variables
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database_name';

// Establish connection to database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
  // Get form data
  if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate form data
    if (empty($username) || empty($password)) {
      echo 'Please fill in all fields.';
    } else {
      // Hash password for verification
      $hashed_password = hash('sha256', $password);

      // Prepare SQL query
      $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          session_start();
          $_SESSION['logged_in'] = true;
          $_SESSION['username'] = $username;
          header('Location: dashboard.php');
          exit;
        }
      } else {
        echo 'Invalid username or password.';
      }
    }
  }
}

// Close database connection
$conn->close();

?>


<?php

// Database connection settings
$host = 'localhost';
$db_name = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to the database
$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
    global $conn;

    // Check if user exists in the database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Get the hashed password from the database
        $user_data = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user_data['password'])) {
            return true;
        }
    }

    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input data
    if (empty($username) || empty($password)) {
        echo "Please enter both username and password.";
    } else {
        $result = login_user($username, $password);

        if ($result == true) {
            // User is logged in successfully
            session_start();
            $_SESSION["username"] = $username;
            header('Location: index.php');
            exit;
        } else {
            echo "Invalid username or password.";
        }
    }
}

?>


// How you would add password hash using password_hash
$hashed_password = password_hash($password, PASSWORD_DEFAULT);


<?php

// Database connection settings
$host = 'localhost';
$db_name = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

function login_user($username, $password) {
    global $conn;

    // Check if user exists in the database
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Get the hashed password from the database
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if (password_verify($password, $user_data['password'])) {
            return true;
        }
    }

    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input data
    if (empty($username) || empty($password)) {
        echo "Please enter both username and password.";
    } else {
        $result = login_user($username, $password);

        if ($result == true) {
            // User is logged in successfully
            session_start();
            $_SESSION["username"] = $username;
            header('Location: index.php');
            exit;
        } else {
            echo "Invalid username or password.";
        }
    }
}

?>


// How you would add password hash using password_hash
$hashed_password = password_hash($password, PASSWORD_DEFAULT);


<?php

// Configuration
$databaseHost = 'localhost';
$databaseName = 'users_db';
$username = 'username';
$password = 'password';

// Connect to database
$mysqli = new mysqli($databaseHost, $username, $password, $databaseName);

// Check connection
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Login function
function login($username, $password) {
    // Prepare query
    $stmt = $mysqli->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
    
    // Bind parameters
    $stmt->bind_param('ss', $username, $password);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Check if user exists and password is correct
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Handle login form submission
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash password (for security)
    $hashedPassword = hash('sha256', $password);

    if (login($username, $hashedPassword)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}

// Display login form
?>
<form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" name="submit" value="Login">
</form>


<?php

// Define database connection settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create a new PDO instance
$pdo = new PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName, $dbUsername, $dbPassword);

// Define the login function
function user_login($username, $password) {
    global $pdo;

    // Prepare and execute query to check if username exists
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch user data from database
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if password is correct (assuming password is stored as plaintext)
    if ($user_data && password_verify($password, $user_data['password'])) {
        // Login successful, return user ID and username
        return array(
            'id' => $user_data['id'],
            'username' => $user_data['username']
        );
    } else {
        // Login failed
        return false;
    }
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($user_login($username, $password)) {
        echo 'Login successful!';
    } else {
        echo 'Invalid username or password';
    }
}
?>


<?php include 'login.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Example</title>
</head>
<body>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>
    <button type="submit" name="login">Login</button>
</form>

<?php if (isset($user_data)) { ?>
    You are logged in as <?php echo $user_data['username']; ?>
<?php } ?>

</body>
</html>


function login_user($username, $password) {
  // Connect to the database
  $conn = new mysqli('localhost', 'username', 'password', 'database_name');

  // Check if connection was successful
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare SQL query to select user from database
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param('ss', $username, $password);

  // Execute the prepared statement
  $stmt->execute();

  // Get result of prepared statement
  $result = $stmt->get_result();

  // Check if user exists in database
  if ($result->num_rows > 0) {
    // User exists, retrieve user data
    $row = $result->fetch_assoc();
    return array('status' => 'success', 'user_id' => $row['id']);
  } else {
    // User does not exist, return error message
    return array('status' => 'error', 'message' => 'Invalid username or password');
  }

  // Close the database connection
  $stmt->close();
  $conn->close();
}


$username = 'example';
$password = 'password123';

$result = login_user($username, $password);

if ($result['status'] == 'success') {
  echo "User logged in successfully!";
  echo "User ID: " . $result['user_id'];
} else {
  echo "Login failed: " . $result['message'];
}


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to check if a user exists in the database
function checkUserExists($username, $password) {
    global $conn;

    // Prepare and execute query
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // If a row is found, check the password
        while ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                return true;
            }
        }
    }

    // If no rows are found or password does not match
    return false;
}

// Function to log in a user
function loginUser($username, $password) {
    global $conn;

    // Check if the username and password combination is valid
    if (checkUserExists($username, $password)) {
        // If valid, set a session variable to indicate that the user is logged in
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;

        return true;
    } else {
        return false;
    }
}

// Check if the user has submitted the login form
if (isset($_POST['login'])) {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Login the user
    if (loginUser($username, $password)) {
        echo "Welcome, " . $_SESSION['username'] . "! You are now logged in.";
    } else {
        echo "Invalid username or password.";
    }
}

// Close the database connection
mysqli_close($conn);

?>


<?php

// Define the array of users for demonstration purposes only.
$users = [
    'admin' => ['password' => '$2y$10$1tQp5mEz6f0G3lK9H7hAqOnB.9T', 'email' => 'admin@example.com'],
    // Add more users here...
];

function login($username, $password) {
    global $users;

    if (empty($username) || empty($password)) {
        return ['success' => false, 'message' => 'Please enter both username and password.'];
    }

    if (!isset($users[$username])) {
        return ['success' => false, 'message' => 'Invalid username or password.'];
    }

    $hashedPassword = $users[$username]['password'];

    // Check the provided password against the stored hash.
    if (password_verify($password, $hashedPassword)) {
        return ['success' => true, 'user' => $users[$username]];
    } else {
        return ['success' => false, 'message' => 'Invalid username or password.'];
    }
}

// Example usage:
$username = 'admin';
$password = 'secret';

$result = login($username, $password);

if ($result['success']) {
    echo 'Login successful!';
} else {
    echo $result['message'];
}


<?php

// Database settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Create a new mysqli object
$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

function getDb() {
    return $mysqli;
}


<?php

require_once 'db.php';

function validateLogin($username, $password) {
    // Sanitize user input
    $username = trim($username);
    $password = trim($password);

    // Hash password for comparison (assuming bcrypt)
    $hashedPassword = hash('sha256', $password . $_SERVER['HTTP_HOST']);

    // Query database for user existence and password match
    $query = "SELECT * FROM users WHERE username='$username' AND password='$hashedPassword'";
    $result = getDb()->query($query);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function login() {
    // Check for POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (validateLogin($username, $password)) {
            echo "You are now logged in!";
        } else {
            echo "Invalid username or password.";
        }
    }
}


<?php

require_once 'login.php';

?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <button type="submit">Login</button>
</form>

<?php
login();
?>


<?php

// Define the database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
    exit;
}

// Define the login function
function login($email, $password) {
    global $pdo;

    // Prepare and execute a SQL query to retrieve the user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch();

    if ($result) {
        return true; // User exists and password is correct
    } else {
        return false; // User does not exist or password is incorrect
    }
}

// Example usage:
$email = $_POST['email'];
$password = $_POST['password'];

if (isset($_POST['submit'])) {
    if (login($email, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid email or password";
    }
}
?>


<?php

// Configuration variables (change these to match your database setup)
$servername = "localhost";
$usernameDB = "username";
$passwordDB = "password";
$dbname = "database";

// Create a connection to the database
$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to hash and verify passwords
function passwordHashVerify($password, $hashedPassword) {
  return password_verify($password, $hashedPassword);
}

// Function to login a user
function login($username, $password) {
  global $conn;

  // Prepare SQL statement
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);

  // Execute query and store result
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if user exists
  if ($result->num_rows > 0) {
    // Fetch user data from database
    $user = $result->fetch_assoc();

    // Hash and verify password
    $hashedPassword = $user["password"];
    $isVerified = passwordHashVerify($password, $hashedPassword);

    // If password is valid, return true and store user data in session
    if ($isVerified) {
      $_SESSION["username"] = $username;
      $_SESSION["id"] = $user["id"];

      return true;
    }
  }

  // If no match found or password is invalid, return false
  return false;
}

// Example usage:
if (isset($_POST["login"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (login($username, $password)) {
    echo "Login successful!";
  } else {
    echo "Invalid username or password.";
  }
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

// Configuration variables
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

// Function to check user credentials
function check_credentials($username, $password) {
    $conn = db_connect();
    
    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    
    // Bind the parameter
    $stmt->bind_param("s", $username);
    
    // Execute the query
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->get_result();
    
    // Check if a matching user exists
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                return true;
            }
        }
    }
    
    return false;
}

// Function to login the user
function user_login($username, $password) {
    if (check_credentials($username, $password)) {
        // Successful login - set session variables
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        
        return true;
    } else {
        return false;
    }
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (user_login($username, $password)) {
        echo "You are now logged in as $username!";
    } else {
        echo "Invalid username or password.";
    }
}

?>


<?php

// Define database connection settings
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection to database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define user login function
function login_user() {
    // Get input values from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to select user data
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '".md5($password)."'";

    // Execute SQL query
    $result = $conn->query($sql);

    // Check if user exists and password is correct
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // User data exists, set session variables
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['id'] = $row['id'];

            // Redirect to dashboard or homepage
            header("Location: dashboard.php");
            exit;
        }
    } else {
        // User does not exist or password is incorrect, display error message
        echo "Invalid username or password";
    }

    // Close database connection
    $conn->close();
}

// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    login_user();
}
?>


<?php

// Configuration file for database connection
require_once 'config.php';

function login($username, $password) {
  // Validate input
  if (empty($username) || empty($password)) {
    return array('error' => 'Username and password are required.');
  }

  // Query to select user data from the database
  $query = "SELECT * FROM users WHERE username = :username AND password = :password";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', hash('sha256', $password));
  $stmt->execute();

  // Fetch user data
  $userData = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($userData) {
    return array(
      'success' => true,
      'data' => $userData
    );
  } else {
    return array('error' => 'Invalid username or password.');
  }
}

?>


<?php

// Database connection settings
$dsn = 'mysql:host=localhost;dbname=your_database';
$username = 'your_username';
$password = 'your_password';

try {
  $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
  echo 'Connection failed: ' . $e->getMessage();
  exit;
}

?>


require_once 'login.php';

$username = 'example_user';
$password = 'example_password';

$result = login($username, $password);

if ($result['success']) {
  print_r($result['data']);
} else {
  echo 'Error: ' . $result['error'];
}


<?php

// Array of users (in a real app, use a database)
$users = [
    'admin' => [
        'password' => 'password123',
        'role' => 'admin'
    ],
    'user1' => [
        'password' => 'user1pass',
        'role' => 'normal user'
    ]
];

function loginUser($username, $password) {
    // Validate input
    if (empty($username) || empty($password)) {
        return false; // Return false for invalid username or password
    }

    // Get the corresponding user from the array
    $user = $users[$username] ?? null;

    if ($user && password_verify($password, $user['password'])) {
        // Validate roles (optional)
        if (!in_array($user['role'], ['admin', 'normal user'])) {
            return false; // Invalid role
        }

        // Login successful!
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role'];

        return true;
    } else {
        return false; // Incorrect username or password
    }
}

// Example usage:
if (loginUser('admin', 'password123')) {
    echo "Login successful!";
} else {
    echo "Incorrect username or password.";
}


<?php
// Configuration
$database = array(
  'host' => 'localhost',
  'username' => 'your_username',
  'password' => 'your_password',
  'database' => 'your_database'
);

// Connect to database
$conn = new mysqli($database['host'], $database['username'], $database['password'], $database['database']);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if user exists in database
function checkUser($username, $password) {
  global $conn;
  $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    return true; // User exists
  } else {
    return false; // User does not exist
  }
}

// Login function
function login($username, $password) {
  global $conn;
  if (checkUser($username, $password)) {
    // Get user ID from database
    $query = "SELECT id FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);
    $user_id = $result->fetch_assoc()['id'];

    // Store session variables
    $_SESSION['username'] = $username;
    $_SESSION['user_id'] = $user_id;

    return true; // Login successful
  } else {
    return false; // Login failed
  }
}

// Example usage:
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (login($username, $password)) {
    echo "Login successful!";
  } else {
    echo "Login failed. Please try again.";
  }
}

// Close connection
$conn->close();
?>


<?php
// Include login function
include 'login.php';

// Display login form
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username"><br><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password"><br><br>
  <input type="submit" name="login" value="Login">
</form>

<?php
// Display message if login was successful or failed
if (isset($_SESSION['username'])) {
  echo "Welcome, " . $_SESSION['username'] . "! You are now logged in.";
} else {
  echo "Not logged in. Please log in to access this page.";
}
?>


<?php
  // Define the database connection details
  $db_host = 'localhost';
  $db_username = 'your_database_username';
  $db_password = 'your_database_password';
  $db_name = 'your_database_name';

  // Create a new PDO instance
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
  }

  // Define the login function
  function user_login($username, $password) {
    global $pdo;

    // Prepare and execute a SELECT query to retrieve the user's data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch();

    if ($result) {
      // If the user exists, verify their password using SHA-256 hashing
      $hashed_password = hash('sha256', $password);

      if (hash_equals($result['password'], $hashed_password)) {
        return true;
      }
    }

    return false;
  }

  // Example usage:
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (user_login($username, $password)) {
    echo "Login successful!";
  } else {
    echo "Invalid username or password.";
  }
?>


<?php

// Configuration variables
$databaseHost = 'localhost';
$databaseUsername = 'your_username';
$databasePassword = 'your_password';
$databaseName = 'your_database';

// Connect to the database
$conn = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
    // SQL query to select user data from the database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute the query and get the result
    $result = $conn->query($sql);
    
    // Check if the user exists in the database
    if ($result->num_rows > 0) {
        // Get the user data from the result set
        $row = $result->fetch_assoc();
        
        // Return the user ID and username for further use
        return array('user_id' => $row['id'], 'username' => $row['username']);
    } else {
        // If no matching user is found, return false
        return false;
    }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = login_user($username, $password);

if ($result) {
    echo "Login successful. User ID: " . $result['user_id'] . ", Username: " . $result['username'];
} else {
    echo "Invalid username or password.";
}

// Close the database connection
$conn->close();

?>


<?php
// Configuration variables
$DB_HOST = 'localhost';
$DB_USER = 'your_username';
$DB_PASSWORD = 'your_password';
$DB_NAME = 'your_database';

// Connect to the database
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
  // Get user input
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Prepare query to select user from database
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password);

  // Execute query and store result in a variable
  $stmt->execute();
  $result = $stmt->get_result();

  // If user exists and password is correct, return true
  if ($result->num_rows > 0) {
    return true;
  } else {
    return false;
  }

  // Close the statement
  $stmt->close();
}

// Check if login button was clicked
if (isset($_POST['login'])) {
  // Call the login_user function
  $success = login_user();

  // If user is logged in successfully, redirect to a new page
  if ($success) {
    header("Location: welcome.php");
    exit;
  } else {
    echo "Invalid username or password";
  }
}

// Close database connection
$conn->close();
?>


<?php

// Database connection settings
$db_host = 'localhost';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Establish database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user credentials and login
function check_login($username, $password) {
    global $conn;

    // SQL query to retrieve password hash for given username
    $sql = "SELECT password_hash FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // If user exists, retrieve their password hash and compare with provided password
        while ($row = $result->fetch_assoc()) {
            $password_hash = $row['password_hash'];
            if (password_verify($password, $password_hash)) {
                // User login successful. Start session and redirect to protected page.
                session_start();
                $_SESSION['username'] = $username;
                header('Location: protected_page.php');
                exit;
            } else {
                echo 'Incorrect password';
            }
        }
    } else {
        echo 'User not found';
    }

    return false; // If user login fails, function returns false
}

// Function to register new user
function register_user($username, $password) {
    global $conn;

    // SQL query to insert new user into database
    $sql = "INSERT INTO users (username, password_hash)
            VALUES ('$username', '" . password_hash($password, PASSWORD_DEFAULT) . "')";

    if ($conn->query($sql)) {
        echo 'User registered successfully';
        return true;
    } else {
        echo 'Error registering user: ' . $conn->error;
        return false;
    }
}

// Login form processing
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call check_login function to verify user credentials
    check_login($username, $password);
} elseif (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call register_user function to create new user account
    register_user($username, $password);
}

?>


<?php
// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
    // Prepare query to select user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    
    // Bind parameters
    $stmt->bind_param("ss", $username, md5($password));
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User found, get data from row
        $user_data = $result->fetch_assoc();
        
        // Set session variables
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['email'] = $user_data['email'];
        $_SESSION['role'] = $user_data['role'];
        
        return true;
    } else {
        return false;
    }
}

// Handle form submission (login)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get posted data
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Login user
    if (login_user($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}
?>


<?php
require_once 'login.php';

if (!isset($_SESSION['username'])) {
    // Show login form
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
} else {
    // User is logged in, show dashboard
    echo "Welcome, ".$_SESSION['username']."!";
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

// Database connection settings
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Create database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;

    // Get user input from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query database for user
    $query = "SELECT * FROM users WHERE username='$username' AND password=md5('$password')";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // User found, retrieve their ID
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];

        return true;
    } else {
        return false;
    }
}

// Check for form submission
if (isset($_POST['submit'])) {
    if (login_user()) {
        // Redirect to dashboard or other protected page
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Invalid username or password";
    }
} else {
    // Display login form
    ?>
    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br><br>
        Password: <input type="password" name="password"><br><br>
        <input type="submit" name="submit" value="Login">
    </form>
    <?php
}
?>


<?php

// Set up database connection parameters (replace with your own values)
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Establish a connection to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Function to check user credentials and login
    function login_user($email, $password) {
        global $conn;

        // Prepare SQL query to select user details
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Fetch the first result (assuming one row)
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Hashed password is stored in the database, so we hash the input password for comparison
            $hashed_password = md5($password);
            if ($hashed_password === $result['password']) {
                return true;
            }
        }

        return false;
    }

    // Function to register new user
    function register_user($email, $password) {
        global $conn;

        // Hash password before storing it in the database
        $hashed_password = md5($password);

        try {
            // Prepare SQL query to insert new user details
            $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            // Handle any errors that occur during registration
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

} catch (PDOException $e) {
    // Handle any errors that occur during database connection or query execution
    echo "Database error: " . $e->getMessage();
}

?>


<?php

// Set up user credentials for login/register functionality
$email = 'user@example.com';
$password = 'password123';

if (login_user($email, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid email or password.";
}

// Register a new user
if (register_user('newuser@example.com', 'newpassword')) {
    echo "User registered successfully!";
} else {
    echo "Error registering user.";
}

?>


<?php

// Define the database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a PDO object to connect to the database
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Define the login function
function login_user($username, $password) {
    global $pdo;

    // Prepare a SQL query to select the user's data from the database
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the user's data
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_data) {
        // Hash the input password and compare it with the stored hash
        $hash = hash('sha256', $password . $user_data['salt']);
        if (strcmp($hash, $user_data['password']) === 0) {
            return true;
        } else {
            echo 'Incorrect password';
            return false;
        }
    } else {
        echo 'User not found';
        return false;
    }
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login_user($username, $password)) {
        echo 'Login successful!';
        // Redirect to a secure page or session
        header('Location: secure_page.php');
        exit;
    }
}

?>


<?php
// Configuration settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create database connection
$mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

// Define function to validate user input
function validate_input($data) {
    global $mysqli;
    // Remove special characters and convert to lowercase
    $cleaned_data = strtolower(trim($data));
    return $cleaned_data;
}

// Function to login user
function login_user() {
    global $mysqli;
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = validate_input($_POST['username']);
        $password = validate_input($_POST['password']);

        // Hash password for security
        $hashed_password = hash('sha256', $password);

        // Query database to check user credentials
        $query = "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'";
        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                // Redirect user to dashboard
                header('Location: dashboard.php');
                exit;
            }
        } else {
            echo 'Invalid username or password';
        }
    }
}

// Initialize session
session_start();

// Login user if form submitted
if (isset($_POST['login'])) {
    login_user();
}
?>


<?php

// Configuration variables
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create a database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
    global $conn;
    
    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    
    // Bind parameters
    $stmt->bind_param("s", $username);
    
    // Execute the statement
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Compare password (not recommended for production use)
            if (password_verify($password, $row['password'])) {
                return true; // Login successful
            }
        }
    } else {
        // Username not found
        return false;
    }
    
    // Password incorrect or invalid credentials
    return false;
}

// Call the login function and store result in a variable
$username = 'your_username';
$password = 'your_password';

if (login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password";
}


<?php

// Set database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to the database
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
    exit;
}

function login($username, $password) {
    global $conn;

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);

    // Execute the query and fetch results
    if ($stmt->execute()) {
        $result = $stmt->fetch();

        // Check if user exists in database
        if ($result && password_verify($password, $result['password'])) {
            return true;
        }
    }

    return false;
}

// Example usage:
$username = 'your_username';
$password = 'your_password';

if (login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}

?>


<?php
// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Establish database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}
?>


<?php
require_once 'config.php';

// Define constants for error messages
define('INVALID_USERNAME', 'Invalid username or password');
define('USERNAME_NOT_FOUND', 'Username not found');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if fields are filled
    if (empty($username) || empty($password)) {
        echo json_encode(array('error' => 'Please fill in all fields'));
        exit;
    }

    // Prepare SQL query to retrieve user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch result
    $user = $stmt->fetch();

    if ($user) {
        // Verify password (in a real-world scenario, you should use a secure hashing algorithm)
        if ($password == $user['password']) {
            echo json_encode(array('success' => 'You are now logged in'));
        } else {
            echo json_encode(array('error' => INVALID_USERNAME));
        }
    } else {
        echo json_encode(array('error' => USERNAME_NOT_FOUND));
    }
}
?>


// config.php

$database = array(
    'host' => 'your_host',
    'username' => 'your_username',
    'password' => 'your_password',
    'db_name' => 'your_database'
);


// login.php

require_once 'config.php';

function userLogin($username, $password) {
    // Connect to the database
    $conn = mysqli_connect($database['host'], $database['username'], $database['password']);

    if (!$conn) {
        die("Connection failed: " . mysqli_error($conn));
    }

    // Select the database
    $db_name = $database['db_name'];
    if (!mysqli_select_db($conn, $db_name)) {
        die("Database selection failed: " . mysqli_error($conn));
    }

    // Query to retrieve user data from the database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Retrieve the stored password
        $row = mysqli_fetch_assoc($result);
        $stored_password = $row['password'];

        // Check if the provided password matches the stored one
        if (password_verify($password, $stored_password)) {
            return array(true, "Login successful!");
        } else {
            return array(false, "Incorrect password.");
        }
    } else {
        return array(false, "Username not found.");
    }

    // Close the database connection
    mysqli_close($conn);
}

// Example usage:
$username = 'your_username';
$password = 'your_password';

$result = userLogin($username, $password);

if ($result[0]) {
    echo $result[1];
} else {
    echo "Error: " . $result[1];
}


<?php

// Configuration settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create connection to database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check if database is connected
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to check login credentials
function checkLoginCredentials($username, $password) {
  global $conn;

  // Prepare query to select user data from database
  $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);

  // Execute query and store result
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if user exists in database
  if ($result->num_rows > 0) {
    // Fetch user data from result
    $row = $result->fetch_assoc();

    // Compare stored password with provided password (hashed)
    $storedPassword = hash('sha256', $row['password']);
    $providedPassword = hash('sha256', $password);

    if ($storedPassword === $providedPassword) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }

  // Close prepared statement and database connection
  $stmt->close();
  $conn->close();

  // Return false by default (if no user found)
  return false;
}

// Get posted data from login form
$loggedInUsername = $_POST['username'];
$loggedInPassword = $_POST['password'];

// Call function to check login credentials
$isLoginSuccessful = checkLoginCredentials($loggedInUsername, $loggedInPassword);

// If login is successful, redirect to secured area (e.g., dashboard)
if ($isLoginSuccessful) {
  header('Location: dashboard.php');
} else {
  echo "Invalid username or password.";
}

?>


function authenticate_user($username, $password) {
    // Database connection settings
    define('DB_HOST', 'localhost');
    define('DB_USERNAME', 'your_username');
    define('DB_PASSWORD', 'your_password');
    define('DB_NAME', 'your_database');

    try {
        // Connect to database
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);

        // Prepare SQL query
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username AND password=:password");

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        // Execute query
        $stmt->execute();

        // Check if user exists and password is correct
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


if (authenticate_user('john', 'password123')) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


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
// Configuration for database and hashing algorithm
$databaseHost = 'localhost';
$databaseUsername = 'username';
$databasePassword = 'password';
$databaseName = 'database_name';

$hashAlgorithm = 'sha512';

function connectToDatabase() {
    $conn = new mysqli($GLOBALS['databaseHost'], $GLOBALS['databaseUsername'],
        $GLOBALS['databasePassword'], $GLOBALS['databaseName']);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function hashPassword($password, $salt) {
    $hash = hash_hmac($hashAlgorithm, $salt . $password, 'secret_key');
    return $hash;
}

function validateUserInput($username, $password) {
    if (empty($username)) {
        throw new Exception('Username cannot be empty.');
    }

    if (empty($password)) {
        throw new Exception('Password cannot be empty.');
    }
}

function getUserFromDatabase($conn, $username) {
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function checkPassword($password, $hashedPassword, $salt) {
    return hash_password($password, $salt) === $hashedPassword;
}

function login($username, $password) {
    try {
        validateUserInput($username, $password);

        $conn = connectToDatabase();
        $user = getUserFromDatabase($conn, $username);

        if ($user !== null) {
            $salt = hash('sha256', random_bytes(10));
            $hashedPassword = hash_password($password, $salt);
            $query = "INSERT INTO passwords (user_id, salt, hashed_password)
                      VALUES (" . $user['id'] . ", '$salt', '$hashedPassword')";
            $conn->query($query);

            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['password_hashed'] = $hashedPassword;

            return true;
        } else {
            throw new Exception('Invalid username or password.');
        }
    } catch (Exception $e) {
        return false;
    } finally {
        if ($conn instanceof mysqli) {
            $conn->close();
        }
    }
}
?>


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (login($username, $password)) {
            echo "Login successful!";
        } else {
            echo "Invalid username or password.";
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>


<?php
// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check user credentials
function login_user($username, $password) {
    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    
    // Bind parameters
    $stmt->bind_param("ss", $username, $password);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    // Check if user exists and password matches
    if ($result && $result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Function to login a user
function authenticate_user($username, $password) {
    // Check user credentials
    $logged_in = login_user($username, $password);
    
    if ($logged_in) {
        // Create session variables
        $_SESSION['username'] = $username;
        
        return true; // Login successful
    } else {
        return false; // Login failed
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (authenticate_user($username, $password)) {
        echo 'Login successful!';
    } else {
        echo 'Invalid username or password.';
    }
}
?>


<?php

// Configuration settings
$database = array(
  'host' => 'localhost',
  'username' => 'your_username',
  'password' => 'your_password',
  'database_name' => 'your_database_name'
);

// Connect to the database
$conn = mysqli_connect($database['host'], $database['username'], $database['password']);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Select the database
mysqli_select_db($conn, $database['database_name']);

function login_user() {
  // Get the user's input data
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Hash the password (using SHA-256 in this example)
  $hashed_password = hash('sha256', $password);

  // SQL query to check if the username and hashed password match a row in the database
  $query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) == 1) {
    // Login successful! Get the user's ID and create a session for them
    $user_data = mysqli_fetch_assoc($result);
    $_SESSION['user_id'] = $user_data['id'];
    $_SESSION['username'] = $username;
    header('Location: dashboard.php');
    exit();
  } else {
    // Login failed! Display an error message
    echo 'Invalid username or password';
  }
}

// Check if the form has been submitted (i.e., when the user clicks the login button)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  login_user();
}
?>


<?php
include 'login.php';

if (isset($_SESSION['user_id'])) {
  echo 'Welcome, ' . $_SESSION['username'];
} else {
?>
<form action="" method="post">
  <label>Username:</label>
  <input type="text" name="username"><br><br>
  <label>Password:</label>
  <input type="password" name="password"><br><br>
  <button type="submit">Login</button>
</form>
<?php
}
?>


<?php
include 'login.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit();
}

echo 'Welcome, ' . $_SESSION['username'];
?>


<?php
// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'mydatabase');

// Function to connect to database
function dbConnect() {
  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to login user
function loginUser($username, $password) {
  // Connect to database
  $conn = dbConnect();

  // Prepare SQL query
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, hash('sha256', $password));

  // Execute query
  $stmt->execute();
  $result = $stmt->get_result();

  // Close statement and connection
  $stmt->close();
  $conn->close();

  // If result is not empty, return user data
  if ($result->num_rows > 0) {
    return $result->fetch_assoc();
  } else {
    return false;
  }
}

// Example usage:
$username = 'exampleuser';
$password = 'examplepassword';

if (loginUser($username, $password)) {
  // Login successful, print user data
  echo "Login successful!";
  print_r(loginUser($username, $password));
} else {
  // Login failed, print error message
  echo "Invalid username or password.";
}
?>


<?php
// Database connection settings
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function userLogin($username, $password) {
    // SQL query to retrieve the user's information from the database
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    
    try {
        // Execute the query and get the result
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            // Get the user's information from the result
            $row = $result->fetch_assoc();
            
            // If username and password match, return a success message
            return "Login successful";
        } else {
            // If no match found, return an error message
            return "Invalid username or password";
        }
    } catch (Exception $e) {
        // Handle any exceptions that may occur during the query execution
        echo "Error: " . $e->getMessage();
        return "";
    }
}

// Example usage:
$username = "testuser";
$password = "testpassword";

$result = userLogin($username, $password);

if ($result != "") {
    echo $result;
} else {
    echo "Failed to login. Please try again.";
}
?>


$stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();

$result = $stmt->get_result();


// When creating a new user
$password = "testpassword";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// When verifying an existing user's credentials
$password_input = "testpassword";
if (password_verify($password_input, $row['password'])) {
    // Login successful
}


<?php

// Database configuration
$db_host = 'localhost';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Create PDO instance
$dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_name;
try {
    $pdo = new PDO($dsn, $db_username, $db_password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Function to hash the password
function hashPassword($password) {
    return crypt($password, '$2y$10$.iB5R0t7mHwXg4vM5jZsYe');
}

// Login function
function login($username, $password) {
    // Retrieve user data from database
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists
        if (!$user_data) {
            return false;
        }

        // Compare hashed password from database with input password
        if (crypt($password, $user_data['password']) === $user_data['password']) {
            return true; // Login successful
        } else {
            return false; // Incorrect password
        }
    } catch (PDOException $e) {
        echo "Error retrieving user data: " . $e->getMessage();
        return false;
    }
}

// Example usage:
$username = 'example_user';
$password = 'example_password';

if (login($username, $password)) {
    echo 'Login successful!';
} else {
    echo 'Invalid username or password.';
}


<?php
// database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

// create database connection
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

function login($conn) {
  // retrieve POST data
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // hash the password for comparison
    $hashedPassword = md5($password);

    // query database to verify username and password
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    // fetch result
    $result = $stmt->fetch();

    if ($result) {
      return true;
    } else {
      return false;
    }
  }

  return null;
}

// call login function
if (login($conn)) {
  echo 'Login successful!';
} else {
  echo 'Invalid username or password';
}
?>


<form action="login.php" method="post">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username"><br><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password"><br><br>
  <input type="submit" value="Login">
</form>


<?php

// Configuration settings
require_once 'config.php';

// Function to login a user
function loginUser($username, $password) {
  // Query database for user with matching username
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($GLOBALS['db'], $query);
  
  if ($row = mysqli_fetch_assoc($result)) {
    // Hashed password stored in db, so we hash the input as well
    $inputPassword = hash('sha256', $password);
    
    if ($inputPassword === $row['password']) {
      // Login successful, return user data
      return array(
        'id' => $row['id'],
        'username' => $row['username']
      );
    } else {
      echo "Invalid password";
    }
  } else {
    echo "User not found";
  }
  
  return false;
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = loginUser($username, $password);

if ($result) {
  // User logged in successfully
  session_start();
  $_SESSION['user_id'] = $result['id'];
  $_SESSION['username'] = $result['username'];
  
  echo "Welcome, {$result['username']}!";
} else {
  echo "Login failed";
}

?>


<?php

// Database configuration settings
$server = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

// Create a database connection object
$conn = mysqli_connect($server, $username, $password);
if (!$conn) {
  die('Connection failed: '.mysqli_error($conn));
}

// Select the database
mysqli_select_db($conn, $dbname);

// Store the database connection for later use
$db = array(
  'connection' => $conn,
  'selectdb' => mysqli_select_db
);

?>


<?php
// Configuration
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Connect to database
$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function login_user($username, $password) {
    // Prepare SQL query
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '".mysqli_real_escape_string($conn, $password)."'";

    // Execute query and store result
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function verify_user($username, $password) {
    // Hash password before comparing with stored hash
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";

    // Execute query and store result
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Login user
    if (login_user($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
} else {
    ?>
    <html>
    <body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br><br>
        Password: <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
}
?>


<?php

// Sample database connection (replace with your own)
$servername = "localhost";
$username = "username";
$password = "password";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the database and table for storing user credentials
$db_name = "users";
$table_name = "user_credentials";

// Function to login a user
function login_user($username, $password) {
    // Query to retrieve user data from the database
    $sql = "SELECT * FROM `$db_name`.`$table_name` WHERE username='$username'";
    
    // Execute query and fetch result
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Compare hashed password from database with input password (hashed)
            if (password_verify($password, $row['password'])) {
                return true; // Password is valid
            }
        }
    } else {
        return false; // No user found or incorrect credentials
    }

    // Close the connection to prevent resource leak
    $conn->close();
    
    return false;
}

// Example usage:
$username = "exampleuser";
$password = "hashed_password"; // Replace with actual hashed password from database

if (login_user($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
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
  // Configuration settings
  $db_host = 'your_database_host';
  $db_username = 'your_database_username';
  $db_password = 'your_database_password';
  $db_name = 'your_database_name';

  // Connect to the database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  function login_user() {
    global $conn;

    // Get user input from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize and hash password (use a library like password_hash for security)
    $password = sha1($password);

    // SQL query to retrieve user data
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $row['id'];
        header('Location: dashboard.php');
        exit();
      }
    } else {
      echo 'Invalid username or password';
    }

    // Close the database connection
    $conn->close();
  }

  if (isset($_POST['login'])) {
    login_user();
  }
?>


<?php
// Database connection settings
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define form data variables
$username = $_POST['username'];
$password = $_POST['password'];

// Hash password (using PHP's built-in hash function)
$hashed_password = hash('sha256', $password);

// SQL query to retrieve user data from database
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
$result = $conn->query($sql);

// Check if user exists and password is correct
if ($result->num_rows > 0) {
    // User exists, log them in
    session_start();
    $_SESSION['username'] = $username;
    header('Location: dashboard.php');
} else {
    echo 'Invalid username or password';
}

// Close database connection
$conn->close();

?>


<?php
// Check if user is logged in
if (isset($_SESSION['username'])) {
    // Display dashboard content for logged-in user
    echo 'Welcome, ' . $_SESSION['username'] . '! You are now logged in.';
} else {
    // Redirect to login page if not logged in
    header('Location: login.html');
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

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  function validate_user($username, $password) {
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      return true;
    } else {
      return false;
    }
  }

  function login_user($username, $password) {
    if (validate_user($username, $password)) {
      // User is valid, set session variables
      $_SESSION['username'] = $username;
      $_SESSION['logged_in'] = true;

      header('Location: index.php');
      exit();
    } else {
      echo 'Invalid username or password';
    }
  }

  if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    login_user($username, $password);
  }
?>


<?php
  // Start session
  session_start();

  if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    ?>
    <form action="" method="post">
      <label>Username:</label>
      <input type="text" name="username"><br><br>
      <label>Password:</label>
      <input type="password" name="password"><br><br>
      <input type="submit" name="submit" value="Login">
    </form>
    <?php
  } else {
    // User is logged in, display welcome message
    echo 'Welcome, ' . $_SESSION['username'] . '!';
  }
?>


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Connect to database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Function to hash password using password_hash()
    function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Function to check user credentials
    function login($username, $password) {
        global $conn;

        // Query database for username and hashed password
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($row = $stmt->fetch()) {
            // Check if provided password matches the stored hash
            if (password_verify($password, $row['password'])) {
                return true; // Login successful
            } else {
                echo "Invalid username or password";
                return false;
            }
        } else {
            echo "User not found";
            return false;
        }
    }

    // Example usage:
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (login($username, $password)) {
            echo "Login successful!";
        }
    }
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>


<?php

// Define database connection settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define function to handle user login
function loginUser($username, $password) {
    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    
    // Bind parameters
    $stmt->bind_param("ss", $username, $password);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    // If user found, return true and user data
    if ($result->num_rows > 0) {
        return array(true, $result->fetch_assoc());
    } else {
        // Return false for failed login attempt
        return array(false);
    }
}

// Handle form submission (login)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Hash password before checking against database
    $hashedPassword = hash('sha256', $password);
    
    // Call login function with hashed password
    $loginResult = loginUser($username, $hashedPassword);
    
    if ($loginResult[0]) {
        // Login successful, set session variables and redirect to protected page
        $_SESSION['username'] = $loginResult[1]['username'];
        $_SESSION['user_id'] = $loginResult[1]['id'];
        
        header('Location: protected_page.php');
        exit();
    } else {
        // Login failed, display error message
        echo 'Invalid username or password';
    }
}

// Close database connection
$conn->close();

?>


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'users');

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
    // Prepare and execute query to verify username and password
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, retrieve user data
        $user_data = $result->fetch_assoc();
        return array('success' => true, 'user_id' => $user_data['id'], 'username' => $user_data['username']);
    } else {
        return array('success' => false);
    }
}

function authenticate_user() {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Hash password for comparison
        $hashed_password = hash('sha256', $password);

        // Call login function with hashed password
        $login_result = login($username, $hashed_password);
        return $login_result;
    }
}

if (isset($_POST['submit'])) {
    $authenticate_user();
} else {
    echo 'Error: No form data submitted';
}


<?php
// Configuration
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

// Function to validate user credentials
function login_user($email, $password) {
    // SQL query to select user from database
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    
    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful, return user details
        while ($row = $result->fetch_assoc()) {
            return array(
                'id' => $row['id'],
                'name' => $row['name']
            );
        }
    } else {
        // Login failed, return false
        return false;
    }

    // Close connection
    $conn->close();
}

// Function to register new user
function register_user($email, $password) {
    // SQL query to insert new user into database
    $sql = "INSERT INTO users (email, password)
            VALUES ('$email', '$password')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Main logic to handle login and registration
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user_data = login_user($email, $password);

    if ($user_data !== false) {
        session_start();
        $_SESSION['id'] = $user_data['id'];
        $_SESSION['name'] = $user_data['name'];
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Invalid email or password";
    }
} elseif (isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (register_user($email, $password)) {
        echo "User registered successfully!";
    } else {
        echo "Failed to register user.";
    }
}

// Close connection
$conn->close();
?>


<?php
include 'login.php';
?>

<html>
<head>
    <title>Login Page</title>
</head>

<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Email: <input type="email" name="email"><br><br>
        Password: <input type="password" name="password"><br><br>
        <input type="submit" name="login" value="Login">
    </form>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Email: <input type="email" name="email"><br><br>
        Password: <input type="password" name="password"><br><br>
        <input type="submit" name="register" value="Register">
    </form>

    <?php
    if (isset($_GET['error'])) {
        echo $_GET['error'];
    }
    ?>
</body>
</html>


<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['name'])) {
    echo "Welcome, ".$_SESSION['name']. "!";
} else {
    header('Location: index.php');
    exit;
}
?>


// Connect to database (replace with your own credentials)
$dsn = 'mysql:host=localhost;dbname=your_database';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Function to check user login
function login($username, $password) {
    global $pdo;

    // Prepare query
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    
    try {
        // Execute query and fetch result
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

// Example usage
$username = 'example';
$password = 'example';

$user = login($username, $password);

if ($user) {
    echo 'User logged in successfully!';
} else {
    echo 'Invalid username or password.';
}


<?php
// Configuration variables
$databaseHost = 'localhost';
$databaseName = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Connect to database
$conn = new mysqli($databaseHost, $username, $password, $databaseName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function validateUser($username, $password) {
    global $conn;
    // Prepare query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    
    // Bind parameters
    $stmt->bind_param("ss", $username, $password);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    // Check if user exists
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function loginUser($username, $password) {
    global $conn;
    
    // Validate user credentials
    if (validateUser($username, $password)) {
        // Get user data
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        
        // Bind parameters
        $stmt->bind_param("ss", $username, $password);
        
        // Execute query
        $stmt->execute();
        
        // Get result
        $result = $stmt->get_result();
        
        // Store user data in session
        $_SESSION['user_id'] = $result->fetch_assoc()['id'];
        $_SESSION['username'] = $result->fetch_assoc()['username'];
        
        return true;
    } else {
        return false;
    }
}

// Handle login form submission
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (loginUser($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}

// Close connection
$conn->close();
?>


<?php

// Database connection settings
$dsn = 'mysql:host=localhost;dbname=your_database';
$username = 'your_username';
$password = 'your_password';

try {
    // Create database connection
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Login function
function login($email, $password) {
    global $pdo;

    // Prepare query to select user data
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Fetch user data
    $user_data = $stmt->fetch();

    if ($user_data && password_verify($password, $user_data['password'])) {
        // User exists and password is valid, return true
        return true;
    } else {
        // User does not exist or password is invalid, return false
        return false;
    }
}

// Example usage:
$email = 'your_email@example.com';
$password = 'your_password';

if (login($email, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid email or password.";
}
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
// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function login($username, $password) {
    // Connect to database
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute SQL query
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch results
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo 'Connection error: ' . $e->getMessage();
        return null;
    } finally {
        // Close database connection
        if ($conn !== null) {
            $conn = null;
        }
    }
}

// Example usage:
$login_result = login('testuser', 'testpassword');
if ($login_result === true) {
    echo "Login successful!";
} else {
    echo "Login failed.";
}
?>


// Database connection settings
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a new MySQLi object
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}

function login($username, $password) {
    global $mysqli;
    
    // SQL query to retrieve the user's details
    $query = "SELECT id, username, password FROM users WHERE username = '$username'";
    
    try {
        // Execute the SQL query
        $result = $mysqli->query($query);
        
        if ($result->num_rows == 0) {
            throw new Exception('No such user exists');
        }
        
        // Fetch the result of the query as an associative array
        $userDetails = $result->fetch_assoc();
        
        // Compare hashed password with given password
        $hash = hash('sha256', $password);
        if ($hash == $userDetails['password']) {
            return true;
        } else {
            throw new Exception('Incorrect Password');
        }
    } catch (Exception $e) {
        echo 'Error: ', $e->getMessage();
    }
}

// Login function usage
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (login($username, $password)) {
        echo 'Login Successful';
    } else {
        echo 'Invalid username or password';
    }
}


function login($username, $password) {
    global $mysqli;
    
    // Create a new SQL query object with the user details and encrypted password
    $stmt = $mysqli->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    
    try {
        // Execute the prepared statement
        $stmt->execute();
        
        // Fetch the result of the query as an associative array
        $userDetails = $stmt->get_result()->fetch_assoc();
        
        // Compare hashed password with given password
        if ($userDetails['password'] == hash('sha256', $password)) {
            return true;
        } else {
            throw new Exception('Incorrect Password');
        }
    } catch (Exception $e) {
        echo 'Error: ', $e->getMessage();
    } finally {
        // Close the prepared statement
        $stmt->close();
    }
}


<?php

// Configuration
require_once 'config.php';

// Function to login user
function login_user($username, $password) {
    // SQL query to select user data from database
    $query = "SELECT * FROM users WHERE username = :username";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        // Check if user exists and password matches
        if ($stmt->rowCount() > 0) {
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Hash the input password for comparison
            $input_password_hash = hash('sha256', $password . config::$secret_key);
            
            if (password_verify($input_password_hash, $user_data['password'])) {
                // Login successful! Return user data
                return array(
                    'success' => true,
                    'user_id' => $user_data['id'],
                    'username' => $user_data['username']
                );
            } else {
                // Incorrect password
                throw new Exception('Invalid password');
            }
        } else {
            // User not found
            throw new Exception('User not found');
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        return array(
            'success' => false,
            'error' => 'Database error'
        );
    } catch (Exception $e) {
        return array(
            'success' => false,
            'error' => $e->getMessage()
        );
    }
}

// Example usage
$username = $_POST['username'];
$password = $_POST['password'];

$user_data = login_user($username, $password);

if ($user_data['success']) {
    // Login successful! Redirect to dashboard or next page
    echo "Login successful!";
} else {
    // Display error message
    echo $user_data['error'] . "
";
}

?>


$username = $_POST['username'];
$password = $_POST['password'];

$user_data = login_user($username, $password);

if ($user_data['success']) {
    // Login successful! Redirect to dashboard or next page
    header('Location: dashboard.php');
} else {
    // Display error message
    echo $user_data['error'] . "
";
}


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'mydatabase');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check if connection failed
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

function login_user($username, $password) {
    // Prepare SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

    // Bind parameters
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $username, md5($password));

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        // User exists, return true
        return true;
    } else {
        // User does not exist, return false
        return false;
    }
}

function register_user($username, $password) {
    // Prepare SQL query
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

    // Bind parameters
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $username, md5($password));

    // Execute query
    $stmt->execute();

    // Get last inserted ID
    $id = $mysqli->insert_id;

    // Return user data
    return array(
        'id' => $id,
        'username' => $username
    );
}

// Example usage:
$username = "john";
$password = "password123";

if (login_user($username, $password)) {
    echo "User logged in successfully!";
} else {
    echo "Invalid username or password.";
}

?>


// config.php (store database connection settings)
<?php
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// db.php (database class for connecting to the database)
class Database {
    private $conn;

    public function connect() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }

    public function query($sql) {
        $result = $this->conn->query($sql);
        if (!$result) {
            trigger_error('Invalid query: ' . $this->conn->error, E_USER_ERROR);
        }
        return $result;
    }
}

// login.php (user login function)
class Login {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db->connect();
    }

    public function userLogin($username, $password) {
        // Validate input
        if (empty($username) || empty($password)) {
            return 'Error: Both username and password are required.';
        }

        // Query database for matching user
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $this->db->query($sql);
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            return 'Login successful!';
        } else {
            return 'Error: Invalid username or password.';
        }
    }

    public function closeConnection() {
        $this->db->close();
    }
}

// Usage example
$login = new Login();

$username = $_POST['username'];
$password = $_POST['password'];

echo $login->userLogin($username, $password);

$login->closeConnection();


function login($username, $password) {
  // Connect to database (e.g. MySQL)
  $db = new mysqli("localhost", "username", "password", "database");

  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  // Prepare SQL query
  $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);

  // Execute query and store result
  if ($stmt->execute()) {
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check password (insecure, use a secure method like hashing/salting in real-world app)
    if (password_verify($password, $user["password"])) {
      return array("success" => true, "username" => $user["username"]);
    } else {
      return array("success" => false, "message" => "Incorrect password");
    }
  } else {
    // Handle query execution error
    return array("success" => false, "message" => "Database error: " . $db->error);
  }

  // Close database connection
  $stmt->close();
  $db->close();

  return array("success" => false, "message" => "Unknown error");
}


$username = "john";
$password = "secret";

$result = login($username, $password);

if ($result["success"]) {
  echo "Login successful for user {$result['username']}";
} else {
  echo "Error: " . $result["message"];
}


<?php

// Define the database connection settings
$host = 'localhost';
$dbname = 'database_name';
$username = 'username';
$password = 'password';

// Create a new PDO instance
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Define the user login function
function loginUser($username, $password) {
    global $conn;

    // Prepare the SQL query to retrieve the user's credentials
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the result of the query
    $user = $stmt->fetch();

    // Check if the user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
        return true;
    } else {
        return false;
    }
}

// Define a function to get the user's data after login
function getUserData($username) {
    global $conn;

    // Prepare the SQL query to retrieve the user's data
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the result of the query
    return $stmt->fetch();
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        // User is logged in successfully
        echo 'Welcome, ' . $username . '! You have been logged in.';
        $userData = getUserData($username);
        // Store the user's session data
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $userData['id'];
    } else {
        // User login failed
        echo 'Invalid username or password. Please try again.';
    }
}
?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
  global $conn;
  
  // Prepare query
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  
  // Execute query
  $stmt->execute();
  $result = $stmt->get_result();
  
  // Check if user exists
  if ($result->num_rows == 0) {
    return false;
  }
  
  // Fetch user data
  $user = $result->fetch_assoc();
  
  // Verify password
  if (password_verify($password, $user['password'])) {
    return true;
  } else {
    return false;
  }
}

// Input validation
if (!isset($_POST['username']) || !isset($_POST['password'])) {
  die("Invalid input");
}

$username = $_POST['username'];
$password = $_POST['password'];

// Login user
if (login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password";
}

?>


<?php
/**
 * User Login Function
 *
 * @package    MyPHPApp
 * @author     Your Name
 */

// Configuration settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

function login_user($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to select user data
  $sql = "SELECT * FROM users WHERE username='$username' AND password=SHA2('$password', 256)";

  // Execute the query and store result in a variable
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User exists, retrieve user data
    while ($row = $result->fetch_assoc()) {
      return array('success' => true, 'user_id' => $row['id'], 'username' => $row['username']);
    }
  } else {
    // User does not exist or password is incorrect
    return array('success' => false, 'message' => 'Invalid username or password');
  }

  // Close the database connection
  $conn->close();
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$user_data = login_user($username, $password);

if ($user_data['success']) {
  echo "Logged in successfully!";
  echo "User ID: " . $user_data['user_id'] . ", Username: " . $user_data['username'];
} else {
  echo $user_data['message'];
}
?>


// db_config.php

<?php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


// login.php

<?php
require 'db_config.php';

function userLogin($username, $password) {
    global $pdo;

    // SQL query to retrieve the hashed password for the given username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=:username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the provided password against the stored hash
        if (password_verify($password, $user['password'])) {
            return true; // login successful
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    return false;
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (userLogin($username, $password)) {
        // User has logged in successfully
        echo "Welcome, $username!";
    }
}
?>


/**
 * @param string $username The username to log in.
 * @param string $password  The password to verify against the stored hash.
 *
 * @return bool True if login is successful, false otherwise.
 */
function userLogin($username, $password) {
    // function implementation...
}


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


<?php
// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
require_once 'config.php';

// Form data
$username = $_POST['username'];
$password = $_POST['password'];

// Query to check username and password
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Login successful, retrieve user data
    while ($row = $result->fetch_assoc()) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        header('Location: dashboard.php');
        exit();
    }
} else {
    echo "Invalid username or password.";
}

// Close database connection
$conn->close();
?>


function login_user($username, $password) {
  // Connect to the database
  $db = mysqli_connect('localhost', 'your_username', 'your_password', 'your_database');

  // Check connection
  if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Prepare SQL query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Bind parameters
  $stmt = mysqli_prepare($db, $query);
  mysqli_stmt_bind_param($stmt, "ss", $username, $password);

  // Execute query
  if (mysqli_stmt_execute($stmt)) {
    // Fetch results
    $result = mysqli_stmt_get_result($stmt);

    // Check if user exists
    if ($row = mysqli_fetch_assoc($result)) {
      // User found, return true and user data
      return array(
        'success' => true,
        'username' => $row['username'],
        'email' => $row['email']
      );
    } else {
      // User not found
      return array('success' => false);
    }
  } else {
    // Query execution failed
    return array('success' => false);
  }

  // Clean up
  mysqli_stmt_close($stmt);
  mysqli_close($db);
}


// Get user input
$username = $_POST['username'];
$password = $_POST['password'];

// Call login function
$result = login_user($username, $password);

// Check result
if ($result['success']) {
  // User logged in successfully
  echo "Welcome, " . $result['username'] . "! Your email is: " . $result['email'];
} else {
  // Login failed
  echo "Login failed. Please try again.";
}


<?php
// Configuration variables
$hostname = 'your_host';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

// Create a connection to the database
$conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

function login($username, $password) {
  global $conn;
  
  // Prepare and execute query to check user credentials
  $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  
  // Get the user data from the database
  $userData = $stmt->fetch(PDO::FETCH_ASSOC);
  
  if ($userData !== false) {
    // Check if the password matches (using PHP's built-in hash comparison)
    $passwordHash = $userData['password_hash'];
    if (password_verify($password, $passwordHash)) {
      // If credentials are valid, log in the user
      return true;
    } else {
      // Password does not match
      echo "Password is incorrect.";
      return false;
    }
  } else {
    // User does not exist or credentials are invalid
    echo "Username and password do not match.";
    return false;
  }
}

// Check if the user wants to log in
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  // Login attempt
  if (login($username, $password)) {
    echo "Login successful.";
  } else {
    echo "Error logging in.";
  }
}
?>


<?php

// Database connection settings
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Establish database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate user login credentials
function validateLogin($username, $password) {
    global $conn;

    // Query to select username and password from database
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    
    // Execute query
    $result = $conn->query($sql);
    
    // Check if result is true (i.e., user exists in the system)
    if ($result->num_rows > 0) {
        return true;  // User's login credentials are valid
    } else {
        return false; // User's login credentials are invalid
    }
}

// Function to handle user login request
function loginUser($username, $password) {
    global $conn;

    // Validate user's login credentials
    if (validateLogin($username, $password)) {
        // Get the logged-in user's details from database
        $sql = "SELECT * FROM users WHERE username='$username'";
        
        // Execute query and fetch result
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        // Set session variables for the logged-in user
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $username;
        
        return true;  // User has successfully logged in
    } else {
        return false; // User's login credentials are invalid
    }
}

// Example usage of login functions
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        echo 'You have successfully logged in!';
    } else {
        echo 'Invalid username or password';
    }
}

?>


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

function login($username, $password) {
    // Prepare query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    
    // Bind parameters
    $stmt->bind_param("s", $username);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Check password (hashed)
        if (password_verify($password, $row['password'])) {
            return true;
        }
    }

    return false;
}

// Handle login request
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
} else {
    // Display login form
    ?>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        <button type="submit">Login</button>
    </form>
    <?php
}

// Close database connection
$conn->close();

?>


<?php

// Configuration variables
$servername = "localhost";
$username_db = "your_username";
$password_db = "your_password";
$dbname = "your_database";

// Create connection to database
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
    global $conn;
    
    // Prepare and execute query to retrieve user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Retrieve the user's password and hash it with the provided password
        $row = $result->fetch_assoc();
        $password_hash = $row['password'];
        
        // Compare the provided password with the stored hash (not recommended for production)
        // Consider using a secure comparison method like `password_verify()`
        if ($password === $password_hash) {
            return true; // Successful login
        } else {
            return false; // Incorrect password
        }
    } else {
        return false; // User not found
    }

    // Close the prepared statement
    $stmt->close();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($result = login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}
?>


<?php
// Include database connection file (e.g., db.php)
require_once 'db.php';

function login($username, $password) {
  // SQL query to retrieve user data from the database
  $sql = "SELECT * FROM users WHERE username = ?";
  
  // Prepare and execute the query with parameterized input
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    // Fetch user data from result set
    $user_data = $result->fetch_assoc();

    // Verify password using hash comparison (recommended)
    if (password_verify($password, $user_data['password'])) {
      // Password matches; return success array with user data
      return [
        'success' => true,
        'username' => $user_data['username'],
        'email' => $user_data['email']
      ];
    } else {
      // Incorrect password; return error message
      return ['error' => 'Invalid username or password'];
    }
  } else {
    // User not found in database; return error message
    return ['error' => 'Invalid username or password'];
  }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$login_result = login($username, $password);

if ($login_result['success']) {
  // Login successful; set session variables and redirect to dashboard
  $_SESSION['username'] = $login_result['username'];
  $_SESSION['email'] = $login_result['email'];
  header('Location: dashboard.php');
  exit;
} else {
  // Login failed; display error message
  echo '<p class="error">' . $login_result['error'] . '</p>';
}


<?php
// db.php

// Define database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
?>


<?php
// login.php

require 'db.php';

function user_login($username, $password) {
    // Check if username and password are not empty
    if (empty($username) || empty($password)) {
        return array('error' => 'Please fill in both fields');
    }

    // Query to select the user from database
    $query = "SELECT * FROM users WHERE username = '$username'";

    // Execute query
    $result = $mysqli->query($query);

    // Check if user exists
    if ($result->num_rows == 0) {
        return array('error' => 'Invalid username or password');
    }

    // Fetch the user data
    $user_data = $result->fetch_assoc();

    // Hash the input password and compare it with stored hash
    $input_password_hash = md5($password);
    if ($input_password_hash != $user_data['password']) {
        return array('error' => 'Invalid username or password');
    }

    // If both conditions pass, log in the user and return success message
    return array('success' => true, 'message' => 'You are now logged in!');
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = user_login($username, $password);
if (isset($result['error'])) {
    echo '<p style="color: red;">' . $result['error'] . '</p>';
} elseif (isset($result['success'])) {
    echo '<p style="color: green;">' . $result['message'] . '</p>';
}
?>


<?php

class Database {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli('localhost', 'username', 'password', 'database');
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function query($sql, $params = []) {
        if (!empty($params)) {
            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bind_param($key, $value);
            }
            $stmt->execute();
            return $stmt->get_result();
        } else {
            return $this->conn->query($sql);
        }
    }

    public function close() {
        $this->conn->close();
    }
}

?>


<?php

require_once 'database.php';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db = new Database();

    // Hash the password using a secure hashing algorithm (e.g. bcrypt)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if user exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $db->query($sql, ['s', $username]);
    $result = $stmt->fetch_assoc();

    if ($result) {
        // Verify the hashed password
        if (password_verify($password, $result['password'])) {
            // User login successful
            session_start();
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit;
        } else {
            // Incorrect password
            echo 'Incorrect password';
        }
    } else {
        // User not found
        echo 'User not found';
    }

    $db->close();
}

?>


<?php

require_once 'database.php';

session_start();

if (isset($_SESSION['username'])) {
    echo "Welcome, " . $_SESSION['username'] . "!";

} else {
    header('Location: login.php');
    exit;
}

?>


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to verify user credentials
function verifyCredentials($username, $password) {
    global $conn;

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute query
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch user data
    if ($row = $result->fetch_assoc()) {
        // Compare hashed password with provided password
        if (password_verify($password, $row['password'])) {
            return array(
                'success' => true,
                'username' => $row['username'],
                'email' => $row['email']
            );
        } else {
            return array('error' => 'Invalid password');
        }
    }

    // If user not found
    return array('error' => 'User not found');
}

// Function to login user
function loginUser($data) {
    global $conn;

    $username = $data['username'];
    $password = $data['password'];

    // Verify credentials
    $credentials = verifyCredentials($username, $password);

    if (isset($credentials['success'])) {
        return array('success' => true);
    } else {
        return array('error' => $credentials['error']);
    }
}

// Example usage
$data = array(
    'username' => 'your_username',
    'password' => 'your_password'
);

$result = loginUser($data);

if ($result['success']) {
    echo "Logged in successfully!";
} else {
    echo "Error: " . $result['error'];
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


function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}


function user_login($username, $password) {
    // Connect to database (replace with your actual database connection code)
    require_once 'db_config.php';
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute query and get result
    if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->fetch($result);
            if (password_verify($password, $result[0])) {
                return true; // Login successful
            } else {
                return false; // Password incorrect
            }
        } else {
            return false; // Username not found
        }
    }

    return false; // Query execution failed
}


$username = 'example_user';
$password = 'password123';

if (user_login($username, $password)) {
    echo 'Login successful!';
} else {
    echo 'Invalid username or password.';
}


// sample user data stored as an associative array
$users = [
    "john" => ["password" => "123456", "email" => "john@example.com"],
    "jane" => ["password" => "789012", "email" => "jane@example.com"]
];


function login($username, $password) {
    global $users;

    // check if username exists
    if (array_key_exists($username, $users)) {

        // hash the provided password to compare with stored hash
        // for simplicity, we'll use a basic hashing function here
        $hashedPassword = hash("sha256", $password);

        // retrieve the user's stored hashed password and email
        $storedPassword = $users[$username]["password"];
        $storedEmail = $users[$username]["email"];

        // compare the provided password with the stored one
        if ($hashedPassword === $storedPassword) {

            // login successful, return user data
            return [
                "username" => $username,
                "email" => $storedEmail
            ];

        } else {
            // incorrect password
            echo "Incorrect password";
            return null;
        }

    } else {
        // username not found
        echo "Username not found";
        return null;
    }
}


// attempt a login
$loginResult = login("john", "123456");

if ($loginResult !== null) {

    // login successful, access user data
    var_dump($loginResult);

} else {
    echo "Login failed";
}


<?php
// Database connection settings
$host = 'localhost';
$dbname = 'users_database';
$username = 'your_username';
$password = 'your_password';

// Establish database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define user login function
function user_login($username, $password) {
    global $conn;
    
    // Escape and sanitize input
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if user exists
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute query and get result
    $result = $conn->query($query);
    
    // Check if user was found
    if ($result->num_rows > 0) {
        // If user exists, return true to indicate successful login
        return true;
    } else {
        // If user does not exist or password is incorrect, return false
        return false;
    }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

if (user_login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}
?>


function user_login($username, $password) {
  // Connect to database
  $mysqli = new mysqli('localhost', 'username', 'password', 'database');

  // Prepare and execute query
  if ($stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?")) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch user data
    while ($row = $result->fetch_assoc()) {
      if (password_verify($password, $row['password'])) {
        return array(
          'id' => $row['id'],
          'username' => $row['username']
        );
      }
    }

    // If no match is found, return null
  } else {
    // Error handling for query preparation
  }

  // Close database connection
  $mysqli->close();

  return null;
}


$username = 'john_doe';
$password = 'password123';

$user_data = user_login($username, $password);

if ($user_data !== null) {
  echo "User logged in successfully!";
  var_dump($user_data);
} else {
  echo "Invalid username or password.";
}


<?php

function login_user($username, $password) {
    // Connect to the database (replace with your own connection code)
    $db = mysqli_connect('localhost', 'username', 'password', 'database');

    if (!$db) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare and execute SQL query
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($db, $sql);

    // Check if user exists
    if (mysqli_num_rows($result) > 0) {
        // If user exists, create a session to store their data
        while ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['id'] = $row['id'];
            return true; // User logged in successfully
        }
    } else {
        return false; // User not found or password incorrect
    }

    // Close database connection
    mysqli_close($db);
}

// Example usage:
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Note: MD5 is insecure, consider using a more secure hashing algorithm

    if (login_user($username, $password)) {
        echo "Logged in successfully!";
    } else {
        echo "Invalid username or password.";
    }
}

?>


// db.php (database connection file)
<?php
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>


// login.php (login function file)
<?php

require_once 'db.php';

function user_login($username, $password) {
    // Prepare the SQL query
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    
    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        // Get the user data from the result
        $user_data = $result->fetch_assoc();
        
        // Check the password (for simplicity, we're using plain text here)
        if ($password == $user_data['password']) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// Login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (user_login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}
?>


<form action="" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>


<?php
require_once 'config.php';

// Define variables
$username = '';
$password = '';

if (isset($_POST['username'])) {
    $username = $_POST['username'];
}
if (isset($_POST['password'])) {
    $password = $_POST['password'];
}

// Hash the password for comparison (assuming you're using a library like bcrypt)
use PasswordHash;

// Verify user credentials
function verifyUser($username, $hashedPassword) {
    // Simulating database query to retrieve hashed password from db
    $pdo = new PDO('mysql:host=localhost;dbname=yourdb', 'youruser', 'yourpass');
    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch();

    if ($hashedPassword === $row['password']) {
        return true; // Passwords match
    } else {
        return false;
    }
}

// Login function
function login() {
    global $username, $password;

    // Hash the password for comparison (if you're using a library like bcrypt)
    // We'll assume we have it already hashed in the database for this example

    if ($username && $password) {
        $pdo = new PDO('mysql:host=localhost;dbname=yourdb', 'youruser', 'yourpass');
        $hashedPassword = retrieveUserPassword($pdo, $username);
        
        // Compare password with hashed password from db
        $isValidLogin = verifyUser($username, $hashedPassword);

        if ($isValidLogin) {
            $_SESSION['username'] = $username;
            $_SESSION['isLoggedIn'] = true;

            echo 'Login successful!';
            return true;
        } else {
            echo 'Invalid username or password';
            return false;
        }
    } else {
        echo 'Please enter both username and password';
        return false;
    }
}

// Retrieve hashed user password from database
function retrieveUserPassword($pdo, $username) {
    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch();

    return $row['password'];
}

// Execute the login function
login();
?>


<?php

require_once 'dbconfig.php'; // Include your database configuration file

function login($username, $password) {
  global $mysqli;

  // Prepare SQL statement
  $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);

  try {
    // Execute query and store result
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      throw new Exception('Invalid username or password');
    }

    // Get user data from result
    $user_data = $result->fetch_assoc();

    // Verify password using hash_equals (PHP >= 7.2)
    if (!hash_equals($password, crypt($password, $user_data['salt']))) {
      throw new Exception('Invalid username or password');
    }

    return $user_data;
  } catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    return null;
  }
}


require_once 'login.php';

// Get user input
$username = $_POST['username'];
$password = $_POST['password'];

try {
  // Call login function and store result in a variable
  $user_data = login($username, $password);

  if ($user_data) {
    // Login successful! Store user data in session or redirect to protected area
    session_start();
    $_SESSION['user_id'] = $user_data['id'];
    header('Location: protected_area.php');
    exit;
  } else {
    echo 'Invalid username or password';
  }
} catch (Exception $e) {
  // Handle any exceptions that occur during login attempt
}


// db.php (database connection file)
<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php

require_once 'db.php'; // include database connection file

function user_login($email, $password) {
    global $conn; // access the database connection variable

    // Prepare SQL query to select users with matching email and password
    $sql = "SELECT * FROM your_table_name WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password); // bind parameters

    if ($stmt->execute()) {
        // Fetch user data from database
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // If login credentials are valid, return true and user data
        if (count($row) > 0 && password_verify($password, $row['password'])) {
            return array(true, $row);
        } else {
            // Return false for incorrect email or password
            return array(false, null);
        }
    }

    // If database query failed, return false and error message
    return array(false, 'Database error');
}

// Handle user login attempt
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = user_login($email, $password);

    if ($result[0]) {
        // Login successful, redirect to protected area or display welcome message
        echo "Login successful. Welcome, {$result[1]['name']}!";
    } else {
        // Display login error message
        echo 'Invalid email or password';
    }
}

?>


class User {
    private $username;
    private $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = hash('sha256', $password); // Hash password for security
    }

    public function checkLogin($inputUsername, $inputPassword) {
        if ($this->username === $inputUsername && $this->password === hash('sha256', $inputPassword)) {
            return true;
        } else {
            return false;
        }
    }
}


function loginUser($username, $password) {
    try {
        // Check if user exists in database or array
        if (checkUserExists($username)) { // This function should check the database for the username
            // If user exists, create a new User object and call checkLogin method
            $user = new User($username, $password);
            return $user->checkLogin($username, $password) ? true : false;
        } else {
            throw new Exception('Username not found');
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}


if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        echo 'Login successful!';
        // Redirect to protected area or perform other actions
    } else {
        echo 'Invalid username or password';
    }
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


// config/db.php
$DB_HOST = 'localhost';
$DB_USERNAME = 'your_username';
$DB_PASSWORD = 'your_password';
$DB_NAME = 'your_database';

$conn = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// models/User.php
class User {
    private $db;

    public function __construct() {
        $this->db = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $stmt->bind_param('ss', $email, $password);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function register($name, $email, $password) {
        // You'll need to hash the password using a library like bcrypt or Argon2
        // For simplicity, we're not hashing it here
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $name, $email, $password);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function close() {
        $this->db->close();
    }
}


// controllers/LoginController.php
class LoginController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function login($request) {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->user->login($email, $password);
            if ($user) {
                // User logged in successfully
                session_start();
                $_SESSION['username'] = $user['name'];
                header('Location: /dashboard');
                exit;
            } else {
                echo 'Invalid email or password';
            }
        }
    }

    public function register($request) {
        if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->user->register($name, $email, $password)) {
                echo 'User registered successfully';
            } else {
                echo 'Failed to register user';
            }
        }
    }
}


// routes/web.php
use LoginController;

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [LoginController::class, 'register']);


function login($username, $password) {
  // Database connection settings
  $dbHost = 'localhost';
  $dbName = 'mydatabase';
  $dbUser = 'myuser';
  $dbPassword = 'mypassword';

  try {
    // Connect to database
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare statement
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);

    // Execute query
    $stmt->execute();

    // Fetch result
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      // Login successful, return user data
      return array('success' => true, 'userData' => $user);
    } else {
      // Invalid credentials
      return array('success' => false, 'error' => 'Invalid username or password');
    }

  } catch (PDOException $e) {
    // Database error occurred
    return array('success' => false, 'error' => 'Database error: ' . $e->getMessage());
  }
}


$username = $_POST['username'];
$password = $_POST['password'];

$result = login($username, $password);

if ($result['success']) {
  // User logged in successfully!
  echo 'Welcome, ' . $result['userData']['username'] . '!';
} else {
  // Display error message to user
  echo $result['error'];
}


<?php

// Include configuration file
require_once 'config.php';

// Define constants for encryption and error messages
define('SALT', 'my_secret_salt');
define('ERROR_MESSAGES', [
    'username' => 'Username is required',
    'password' => 'Password is required'
]);

function login($username, $password) {
    // Validate input
    if (empty($username)) {
        throw new Exception(ERROR_MESSAGES['username']);
    }
    if (empty($password)) {
        throw new Exception(ERROR_MESSAGES['password']);
    }

    // Hash password for comparison
    $hashedPassword = hash('sha256', $password . SALT);

    try {
        // Connect to database and select user
        $conn = new PDO(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD);
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        // Check if user exists and password matches
        if ($user && $hashedPassword === $user['password']) {
            // Log in successful, return user data
            return [
                'id' => $user['id'],
                'username' => $user['username']
            ];
        } else {
            throw new Exception('Invalid username or password');
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo "Database error: " . $e->getMessage();
    }
}

// Example usage:
$username = 'example_user';
$password = 'my_secret_password';

try {
    $userData = login($username, $password);
    if ($userData) {
        print_r($userData); // Output: Array ( [id] => 1 [username] => example_user )
    } else {
        echo "Login failed";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<?php
  // Database connection settings
  $db_host = 'localhost';
  $db_username = 'your_username';
  $db_password = 'your_password';
  $db_name = 'your_database';

  try {
    // Connect to the database
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);

    // Check if username and password are provided
    if (isset($_POST['username']) && isset($_POST['password'])) {
      // Prepare user credentials for login
      $username = $_POST['username'];
      $password = $_POST['password'];

      // Hash the password (optional)
      $hashed_password = hash('sha256', $password);

      // SQL query to check if username and password are valid
      $query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
      $stmt = $conn->prepare($query);
      $stmt->execute();
      $result = $stmt->fetch();

      // If user is found, log them in
      if ($result) {
        session_start(); // Start a new PHP session
        $_SESSION['username'] = $username;
        $_SESSION['logged_in'] = true;

        header('Location: index.php'); // Redirect to the index page
        exit();
      } else {
        echo 'Invalid username or password';
      }
    } else {
      echo 'Please enter both username and password';
    }

  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  } finally {
    // Close the database connection
    if ($conn) {
      $conn = null;
    }
  }
?>


<?php

// Define an array of users
$users = [
    'admin' => 'password123',
    'user1' => 'password456',
];

function login($username, $password) {
    global $users;

    // Check if the username exists in the array
    if (!isset($users[$username])) {
        return false;
    }

    // Check if the password is correct for the given username
    if ($users[$username] !== $password) {
        return false;
    }

    return true; // Successful login
}

function register($username, $password) {
    global $users;

    // Check if the username already exists in the array
    if (isset($users[$username])) {
        return 'Username already taken';
    }

    // Add new user to the array
    $users[$username] = $password;
    return true; // New user created successfully
}

// Example usage:
$username = 'admin';
$password = 'password123';

if (login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}

?>


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
function connect_to_db() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Check user login credentials
function check_login($username, $password) {
    $conn = connect_to_db();
    $query = "SELECT id FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row['id'];
        }
    } else {
        return false;
    }
}

// Login function
function login_user($username, $password) {
    // Hash the password (if you're not using a secure hashing algorithm like bcrypt)
    // $hashed_password = hash('sha256', $password);

    $user_id = check_login($username, $password);
    if ($user_id !== false) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user_id;

        // Redirect to a protected page
        header("Location: /protected-page");
        exit();
    } else {
        return "Invalid username or password";
    }
}

?>


<?php

// Include the login function file
require_once 'login_function.php';

// Get the form data from the user
$username = $_POST['username'];
$password = $_POST['password'];

// Call the login_user function
echo login_user($username, $password);

?>


// Define the database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a PDO instance
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);


require_once 'database.php';

function check_credentials($username, $password) {
  // Prepare the SQL query to select the user's details from the database
  $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
  $stmt->bindParam(':username', $username);
  
  try {
    // Execute the query and retrieve the result
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
      // Check if the hashed password matches the input password
      if (password_verify($password, $result['password'])) {
        return true; // Login successful
      }
    } else {
      return false; // User not found
    }
  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    return false;
  }
  
  return false; // Invalid credentials
}

function login_user($username, $password) {
  if (check_credentials($username, $password)) {
    // Set session variables for the logged-in user
    $_SESSION['username'] = $username;
    $_SESSION['logged_in'] = true;
    
    // Redirect to a secure page or dashboard
    header('Location: /dashboard');
    exit;
  } else {
    return false; // Login failed
  }
}


require_once 'login.php';

// Form data from the user's input
$username = $_POST['username'];
$password = $_POST['password'];

if (isset($_POST['submit'])) {
  if (login_user($username, $password)) {
    echo 'Login successful!';
  } else {
    echo 'Invalid username or password.';
  }
}


<?php
// Define the database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
    exit;
}

// Define the login function
function login($username, $password) {
    global $pdo;

    // Prepare the SQL query
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");

    // Bind the parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    // Execute the query
    $stmt->execute();

    // Fetch the result
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // User found, return their ID and username
        return array('id' => $user['id'], 'username' => $user['username']);
    } else {
        // User not found, return null
        return null;
    }
}

// Handle the login request
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = login($username, $password);

    if ($result) {
        // User logged in successfully
        echo "Welcome, " . $result['username'] . "!";
    } else {
        // Login failed
        echo "Invalid username or password";
    }
}

// Display the login form
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" name="login" value="Login">
</form>


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
function dbConnect() {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// User login function
function userLogin($username, $password) {
  // Create a database connection
  $conn = dbConnect();
  
  // Prepare the SQL query
  $query = "SELECT * FROM users WHERE username = ?";
  
  // Bind the parameters
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  
  // Execute the query
  $stmt->execute();
  
  // Get the result
  $result = $stmt->get_result();
  
  if ($result) {
    // Check if the username and password match
    while ($row = $result->fetch_assoc()) {
      if (password_verify($password, $row['password'])) {
        return array(
          'success' => true,
          'username' => $row['username'],
          'id' => $row['id']
        );
      }
    }
  }
  
  // If the username or password is incorrect
  return array('success' => false, 'message' => 'Invalid username or password');
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = userLogin($username, $password);

if ($result['success']) {
  echo "Logged in successfully!";
} else {
  echo "Error: " . $result['message'];
}
?>


<?php

// Configuration constants
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Database connection function
function connectToDatabase() {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Login function
function login($username, $password) {
  // Connect to database
  $conn = connectToDatabase();
  
  // Prepare SQL query
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  
  // Execute query
  $stmt->execute();
  $result = $stmt->get_result();
  
  // Check if user exists and password matches
  if ($result && $result->num_rows > 0) {
    while ($user = $result->fetch_assoc()) {
      if (password_verify($password, $user['password'])) {
        return true; // Login successful
      }
    }
  }
  
  // Close database connection and return false
  $conn->close();
  return false;
}

// Example usage:
$username = 'your_username';
$password = 'your_password';
if (login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
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

// Configuration settings
$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
    // Create connection to database
    $conn = new PDO($dsn, $username, $password);

    // Set error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error connecting to database: ' . $e->getMessage();
    exit;
}

// Function to check user credentials
function login_user($username, $password) {
    global $conn;

    // Prepare query to select user data
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username AND password = :password');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', md5($password)); // Hash the password

    // Execute query and get result
    $stmt->execute();
    $result = $stmt->fetch();

    // If user exists, return true
    if ($result) {
        return true;
    }

    // If no user found, return false
    return false;
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login_user($username, $password)) {
        echo 'Login successful!';
    } else {
        echo 'Invalid username or password.';
    }
}

?>


<?php
// Configuration
$database_host = 'localhost';
$database_name = 'my_database';
$database_user = 'root';
$database_password = '';

// Connect to database
$conn = new mysqli($database_host, $database_user, $database_password, $database_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
    // Prepare SQL query
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";

    // Bind parameters
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, hash('sha256', $password));

    // Execute query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return true;
    } else {
        return false;
    }
}

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate user input
    if (!empty($username) && !empty($password)) {
        if (login($username, $password)) {
            echo "Login successful!";
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Please enter both username and password.";
    }
}
?>


<?php

// User data array (replace with database queries in production)
$users = [
    'admin' => ['password' => 'password123', 'email' => 'admin@example.com'],
    // Add more users as needed...
];

function login($username, $password) {
    global $users;

    if (!isset($users[$username])) {
        return ['success' => false, 'error' => 'Invalid username'];
    }

    $storedPassword = $users[$username]['password'];

    if (hash('sha256', $password) === $storedPassword) {
        // Authentication successful!
        return ['success' => true];
    } else {
        return ['success' => false, 'error' => 'Incorrect password'];
    }
}

function validateUserInput($username, $password) {
    if (!isset($username)) {
        throw new Exception('Username is required');
    }

    if (empty($username)) {
        throw new Exception('Invalid username');
    }

    if (!isset($password)) {
        throw new Exception('Password is required');
    }

    if (strlen($password) < 8) {
        throw new Exception('Password must be at least 8 characters long');
    }
}

// Example usage:
try {
    $username = $_POST['username'];
    $password = $_POST['password'];

    validateUserInput($username, $password);

    $result = login($username, $password);
    if ($result['success']) {
        // User authenticated successfully! Redirect to protected area...
        header('Location: /protected-area');
        exit;
    } else {
        echo 'Error: ' . $result['error'];
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

?>


<?php
// Configuration settings
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Connect to database
$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

// Define function to validate login credentials
function validateLogin($username, $password) {
    global $mysqli;

    // Prepare SQL query to select user data
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");

    // Bind parameters
    $stmt->bind_param('s', $username);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Check if user exists and password matches
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                return true;
            }
        }
    }

    return false;
}

// Define function to login user
function loginUser($username, $password) {
    global $mysqli;

    // Validate login credentials
    if (validateLogin($username, $password)) {
        // If valid, set session variables and redirect to home page
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit();
    } else {
        // If invalid, display error message
        echo "Invalid username or password";
    }
}
?>


<?php
require_once 'login.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
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
} else {
    // Display welcome message for logged-in user
    echo "Welcome, ".$_SESSION['username']."!";
}
?>


<?php

// Define database connection settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create PDO instance
$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);

function login($username, $password) {
  global $pdo;

  // Prepare SQL statement
  $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  // Fetch user data
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password'])) {
    return true;
  } else {
    return false;
  }
}

// Example usage:
$username = 'your_username';
$password = 'your_password';

if (login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}


<?php
// Configuration
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Connect to Database
$connection = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

function login_user() {
    // Form Data
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate Input
        if (!empty($username) && !empty($password)) {
            // Hash Password for Security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // SQL Query to Select User
            $sql_query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
            $result = $connection->query($sql_query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "Welcome, " . $row["username"] . "!";
                }
            } else {
                echo "Invalid username or password";
            }

            // Close the Connection
            $connection->close();
        } else {
            echo "Please fill out all fields.";
        }
    } else {
        echo "Form not submitted. Please submit form to login.";
    }
}

// Call the Login Function
login_user();

?>


<?php

// Function to check if the username exists in the database
function check_username($username) {
    $conn = mysqli_connect("localhost", "root", "", "your_database_name");
    
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return true; // Username exists
    } else {
        return false; // Username does not exist
    }
}

// Function to authenticate user credentials
function login_user($username, $password) {
    $conn = mysqli_connect("localhost", "root", "", "your_database_name");
    
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Get the user data
        $row = mysqli_fetch_assoc($result);
        
        // Hashed password check (not recommended to store passwords in plain text)
        if ($password === $row['password']) { 
            return true; // Password is correct
        } else {
            return false; // Incorrect password
        }
    } else {
        return false; // Username does not exist
    }
}

// Usage example:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username !== '' && $password !== '') { 
        if (login_user($username, $password)) {
            echo "Login successful!";
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Please enter both username and password";
    }
}

?>


function login_user($username, $password) {
    // Retrieve user's hashed password from database using SQL query.
    $hashed_password = retrieveHashedPasswordFromDatabase($username);

    if (password_verify($password, $hashed_password)) { 
        return true; // Password is correct
    } else {
        return false; // Incorrect password
    }
}

// Usage example:
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username !== '' && $password !== '') { 
        if (login_user($username, $password)) {
            echo "Login successful!";
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Please enter both username and password";
    }
}


<?php
// Configuration
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define variables for form data
$username = $_POST['username'];
$password = $_POST['password'];

// Escape user input to prevent SQL injection
$username = mysqli_real_escape_string($conn, $username);
$password = mysqli_real_escape_string($conn, $password);

// Query the database
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($query);

// Check if user exists and password is correct
if ($result->num_rows > 0) {
    // User exists, log them in
    session_start();
    $_SESSION['username'] = $username;
    header('Location: index.php');
} else {
    // Incorrect username or password
    echo "Incorrect username or password";
}

// Close database connection
$conn->close();
?>


<?php
session_start();

if (isset($_SESSION['username'])) {
    // User is logged in, display welcome message
    echo "Welcome, " . $_SESSION['username'] . "!";

    // Display logout link
    echo "<a href='logout.php'>Logout</a>";
} else {
    // User is not logged in, display login form
    ?>
    <form action="login.php" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
    <?php
}
?>


<?php
session_start();
session_destroy();
header('Location: index.php');
?>


<?php
// Configuration
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

// Get user input
$username = $_POST['username'];
$password = $_POST['password'];

// Sanitize user input (using prepared statements for security)
$stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows == 1) {
    // Get the user's data from the database
    $row = $result->fetch_assoc();

    // Hash the password and compare it with the one in the database
    $hashed_password = hash('sha256', $password);
    if ($hashed_password === $row['password']) {
        // Login successful, set session variables
        $_SESSION['username'] = $username;
        $_SESSION['logged_in'] = true;

        echo "Login successful!";
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Invalid password";
    }
} else {
    echo "User not found";
}

// Close database connection
$conn->close();

?>


function login($username, $password) {
    // Retrieve stored credentials from database (replace with your actual database code)
    $storedCredentials = retrieveStoredCredentials();

    // Check if username exists in stored credentials
    if (!array_key_exists($username, $storedCredentials)) {
        return false;
    }

    // Hash the input password and compare it to the stored hash
    if (password_verify($password, $storedCredentials[$username])) {
        return true; // Credentials are valid
    } else {
        return false; // Password is incorrect
    }
}

// Example usage:
$username = 'johnDoe';
$password = 'secretPassword';

if (login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


function retrieveStoredCredentials() {
    // Replace with your actual database code to retrieve stored credentials
    $db = new PDO('sqlite:users.db');
    $stmt = $db->prepare('SELECT * FROM users');
    $stmt->execute();
    $results = $stmt->fetchAll();

    $storedCredentials = array();
    foreach ($results as $row) {
        $storedCredentials[$row['username']] = $row['password_hash'];
    }

    return $storedCredentials;
}


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

// Define the database connection details
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

// Connect to the database
function connect_to_db() {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    return $conn;
}

// Login function
function login_user($username, $password) {
    // Check if the username and password are not empty
    if (empty($username) || empty($password)) {
        return array('error' => 'Username or Password is required');
    }

    // Connect to the database
    $conn = connect_to_db();

    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    // Execute the query
    $stmt->execute();

    // Check if the user exists and the password is correct
    $result = $stmt->fetch();
    if ($result) {
        return array('success' => true, 'user_id' => $result['id']);
    } else {
        return array('error' => 'Invalid username or password');
    }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = login_user($username, $password);

if ($result['success']) {
    echo "Welcome, $username!";
} elseif ($result['error']) {
    echo "Error: " . $result['error'];
}
?>


<?php
if (isset($_POST['username']) && isset($_POST['password'])) {
    $result = login_user($_POST['username'], $_POST['password']);
    if ($result['success']) {
        // Redirect user to a secure page or dashboard
    } else {
        echo "Error: " . $result['error'];
    }
}
?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
function db_connect() {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Hash password function
function hash_password($password) {
  $salt = uniqid();
  $hashed_password = crypt($password, '$2y$10$.salt' . $salt);
  return array('salt' => $salt, 'hashed_password' => $hashed_password);
}

// Login function
function login_user($username, $password) {
  // Connect to database
  $conn = db_connect();
  
  // Query users table for username
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = $conn->query($query);
  
  if ($result->num_rows > 0) {
    // Get user data from result
    $user_data = $result->fetch_assoc();
    
    // Hash password and compare with stored hash
    $hashed_password = hash_password($password);
    if (crypt($user_data['password'], '$2y$10$.salt' . $user_data['salt']) === $hashed_password['hashed_password']) {
      // Login successful, return user data
      return array('username' => $username, 'id' => $user_data['id']);
    } else {
      // Invalid password, return error message
      return array('error' => 'Invalid username or password');
    }
  } else {
    // No matching username found, return error message
    return array('error' => 'No matching username found');
  }
}

?>


<?php

// Get user input from form
$username = $_POST['username'];
$password = $_POST['password'];

// Call login function and store result in variable
$result = login_user($username, $password);

// Check if login was successful
if ($result !== false && isset($result['error'])) {
  echo '<p>' . $result['error'] . '</p>';
} else {
  // Login successful, display user data or redirect to dashboard
  echo '<p>Login successful!</p>';
}

?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function hashPassword($password) {
    // Use a secure hashing algorithm like bcrypt or argon2
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hashedPassword) {
    // Verify the provided password against the hashed one
    return password_verify($password, $hashedPassword);
}

// Handle login form submission
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve user data from database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $hashedPassword = $user['password'];

        // Verify password
        if (verifyPassword($password, $hashedPassword)) {
            // Login successful, store user data in session
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit;
        } else {
            echo 'Invalid username or password';
        }
    } else {
        echo 'User not found';
    }
}

// Close database connection
$conn->close();

?>


<?php
$host = 'localhost';
$dbname = 'login_system';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>


<?php
require_once 'database.php';

// POST request from form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to select username and password from database
    $query = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $query->bindParam(':username', $username);
    $query->execute();

    if ($query->rowCount() > 0) {
        // Get user data
        $data = $query->fetch();
        
        // Verify password
        if (password_verify($password, $data['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;

            echo "Welcome, " . $username . "!";
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Username not found.";
    }
}
?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}

function login($username, $password) {
    global $mysqli;

    // Check if username and password are not empty
    if (empty($username) || empty($password)) {
        return array('error' => 'Username or password is required.');
    }

    // Prepare query
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);

    // Execute query
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Check if user exists and password is correct
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return array('success' => true, 'username' => $user['username'], 'email' => $user['email']);
        } else {
            return array('error' => 'Invalid username or password.');
        }
    } else {
        return array('error' => 'Database error.');
    }

    // Close statement
    $stmt->close();
}

// Example usage:
$username = 'example';
$password = 'password';

$result = login($username, $password);

if (isset($result['success'])) {
    echo "Logged in as: {$result['username']} ({$result['email']})";
} else {
    echo "Error: {$result['error']}";
}

?>


<?php
// Configuration variables
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

    // Sanitize user input
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query the database for the user
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        // Get the user data from the result set
        $user_data = $result->fetch_assoc();

        // Hash the provided password and compare it with the stored hash
        $stored_hash = $user_data['password'];
        $provided_password = md5($password);

        if ($provided_password == $stored_hash) {
            // Login successful, return a success message
            echo "Login successful!";
            return true;
        } else {
            // Password mismatch
            echo "Invalid password";
            return false;
        }
    } else {
        // User not found
        echo "User not found";
        return false;
    }
}

// Handle form submission
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login_user($username, $password)) {
        // Login successful, redirect to a protected page
        header("Location: protected_page.php");
        exit();
    } else {
        // Login failed, display an error message
        echo "Invalid username or password";
    }
}

// Close the database connection
$conn->close();
?>


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($mysqli->connect_errno) {
    printf("Connect failed: %s
", $mysqli->connect_error);
    exit();
}

function login_user($username, $password) {
    // Prepare SQL statement
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    
    // Bind parameters
    $stmt->bind_param('ss', $username, md5($password));
    
    // Execute query
    $stmt->execute();
    
    // Fetch results
    $result = $stmt->get_result();
    
    // Check if user exists
    if ($result->num_rows == 1) {
        // User found, return true
        return true;
    } else {
        // User not found, return false
        return false;
    }
}

// Handle login form submission
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    
    if (login_user($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}

// Close database connection
$mysqli->close();

?>


<form action="" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit" name="submit">Login</button>
</form>


<?php

// Configuration settings
$login_config = array(
    'db_host' => 'localhost',
    'db_user' => 'username',
    'db_pass' => 'password',
    'db_name' => 'database'
);

// Function to connect to the database
function db_connect() {
    global $login_config;
    $conn = mysqli_connect($login_config['db_host'], $login_config['db_user'], $login_config['db_pass'], $login_config['db_name']);
    if (!$conn) {
        die('Could not connect: ' . mysqli_error());
    }
    return $conn;
}

// Function to check user login credentials
function check_login($username, $password) {
    global $login_config;
    $conn = db_connect();
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        return true; // User logged in successfully
    } else {
        return false; // Incorrect login credentials
    }
}

// Function to handle user login form submission
function login_user() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (check_login($username, $password)) {
            // User logged in successfully
            session_start();
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit;
        } else {
            // Incorrect login credentials
            echo 'Invalid username or password';
        }
    }
}

// Initialize the login function
login_user();

?>


<?php

// Configuration file (store your database credentials here)
require_once 'config.php';

function userLogin($username, $password) {
    // Connect to the database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to select user by username and password
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, log them in
        $row = $result->fetch_assoc();
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $row['id'];
        return true; // Return a success code
    } else {
        echo "Invalid username or password";
        return false; // Return a failure code
    }

    // Close the database connection
    $conn->close();
}

// Check if user is already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo "Welcome, " . $_SESSION['username'];
} else {
    // User login form
    ?>
    <form action="" method="post">
        Username: <input type="text" name="username"><br><br>
        Password: <input type="password" name="password"><br><br>
        <button type="submit">Login</button>
    </form>

    <?php

    // Process login form
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        userLogin($username, $password);
    }
}
?>


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');

function connect_to_db() {
    // Create a connection to the database using the configuration variables
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

function user_login($username, $password) {
    // Connect to the database
    $db = connect_to_db();
    
    // Check if username exists in database
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $db->query($query);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                // If password matches and user exists in database
                return true; // Login successful
            } else {
                // Incorrect password
                return false;
            }
        }
    } else {
        // Username does not exist in database
        return false;
    }
    
    // Close the connection to the database
    $db->close();
}

// Usage example:
$username = 'example';
$password = 'password';

if (user_login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


$password = 'plaintext';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);


if (password_verify('plaintext', $hashed_password)) {
    echo "Login successful!";
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

// Include database connection settings
require_once 'config.php';

// Create user registration form
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert new user into database
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $password]);

    echo 'User created successfully!';
} else {
    ?>
    <form action="" method="POST">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Register">
    </form>
    <?php
}
?>


<?php

// Include database connection settings
require_once 'config.php';

// Create user login form
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve user from database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        echo 'Login successful!';
        // Set session variables
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $user['id'];
    } else {
        echo 'Invalid username or password!';
    }
} else {
    ?>
    <form action="" method="POST">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
}
?>


<?php

// Database connection settings
$pdo = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>


<?php

// Define the users database (replace with your own DB or file-based storage)
$users = [
    'admin' => ['password' => 'secret', 'name' => 'Admin User'],
    // Add more users here...
];

function login($username, $password) {
    global $users;

    // Check if the username exists
    if (!isset($users[$username])) {
        return false;
    }

    // Get the user's details
    $user = $users[$username];

    // Hash the input password for comparison (use a secure hashing algorithm like bcrypt)
    $password_hashed = hash('sha256', $password);

    // Compare the hashed passwords
    if ($password_hashed === $user['password']) {
        return true;
    }

    return false;
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the input data (e.g., check for empty fields, etc.)
    if (!empty($username) && !empty($password)) {
        if (login($username, $password)) {
            echo "Login successful!";
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Please fill in both fields.";
    }
} else {
    // Display the login form
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


<?php
// Configuration
$dbhost = 'localhost';
$dbname = 'your_database_name';
$dbuser = 'your_database_username';
$dbpass = 'your_database_password';

// Connect to database
$conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

function login_user($username, $password) {
  // Prepare SQL query
  $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username AND password = :password');
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', $password);
  
  // Execute query and fetch results
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result) {
    return true;
  } else {
    return false;
  }
}

// Check for POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get username and password from form data
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Check if user exists in database
  if (login_user($username, $password)) {
    echo "Login successful!";
  } else {
    echo "Invalid username or password.";
  }
} else {
  // Display login form
  ?>
  <form action="" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" value="Login">
  </form>
  <?php
}


// login.php

<?php

// Configuration variables
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myuser';
$password = 'mypassword';

// Connect to database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

function loginUser($username, $password) {
    global $conn;

    // Prepare SQL query
    $stmt = $conn->prepare('SELECT id, password FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch result
    $result = $stmt->fetch();

    if ($result) {
        // Hashed password comparison
        if (password_verify($password, $result['password'])) {
            return true;
        }
    }

    return false;
}

// Example usage:
$username = 'testuser';
$password = 'testpassword';

if (loginUser($username, $password)) {
    echo 'Login successful!';
} else {
    echo 'Invalid username or password.';
}


<?php
  // Configuration settings
  define('DB_HOST', 'localhost');
  define('DB_USERNAME', 'your_username');
  define('DB_PASSWORD', 'your_password');
  define('DB_NAME', 'your_database');

  // Database connection
  $conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  function login_user($username, $password) {
    global $conn;

    // Prepare the SQL query
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

    // Execute the query
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
      // User exists and password is correct
      while ($row = mysqli_fetch_assoc($result)) {
        // Create a session for the user
        $_SESSION['username'] = $row['username'];
        $_SESSION['logged_in'] = true;
        return true;
      }
    } else {
      // User does not exist or password is incorrect
      return false;
    }

    mysqli_close($conn);
  }

  if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
      // Validate the username and password
      if (ctype_alnum($username) && ctype_alnum($password)) {
        // Hash the password for security (optional)
        $hashed_password = hash('sha256', $password);

        if ($hashed_password !== $_POST['password']) {
          echo "Password mismatch";
          return;
        }

        // Attempt to log in
        if (login_user($username, $password)) {
          header("Location: dashboard.php");
          exit();
        } else {
          echo "Invalid username or password";
        }
      } else {
        echo "Invalid username or password format";
      }
    } else {
      echo "Please enter a valid username and password";
    }
  }

  // Include the HTML form
  include 'login_form.html';
?>


<?php

define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

?>


<?php

// Include configuration file
require_once 'database.php';

// Connect to database
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Process login form data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query database to check user credentials
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check password
        if (password_verify($password, $user_data['password'])) {
            // Log user in
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['username'] = $user_data['username'];

            header('Location: dashboard.php');
            exit;
        } else {
            echo 'Invalid password';
        }
    } else {
        echo 'Invalid username';
    }
}

?>


/**
 * User login function.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 *
 * @return bool True if the login was successful, false otherwise.
 */
function loginUser($username, $password) {
  // Connect to database
  require_once 'database.php';
  $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

  // Check if username and password are not empty
  if (empty($username) || empty($password)) {
    return false;
  }

  // Prepare SQL query to select user from database
  $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
  $stmt->bindParam(':username', $username);

  try {
    // Execute query and get result
    $stmt->execute();
    $user = $stmt->fetch();

    // Check if user exists
    if (!$user) {
      return false;
    }

    // Hash the provided password to compare with stored hash
    $hashedPassword = hash('sha256', $password);

    // Compare hashed passwords
    if ($hashedPassword === $user['password']) {
      return true; // Login successful
    } else {
      return false; // Password incorrect
    }
  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    return false;
  }

  // Close database connection
  $conn = null;

  return false;
}


$loginStatus = loginUser('testuser', 'password123');
if ($loginStatus) {
  echo 'Login successful!';
} else {
  echo 'Invalid username or password.';
}


<?php
function login($username, $password) {
  // Define database connection parameters
  $db_host = 'localhost';
  $db_username = 'your_username';
  $db_password = 'your_password';
  $db_name = 'your_database';

  // Connect to the database
  try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
  } catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    return false;
  }

  // Prepare and execute the query
  try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', sha1($password));
    $stmt->execute();

    // Fetch and return the user data
    $result = $stmt->fetch();
    if ($result) {
      $_SESSION['user_id'] = $result['id'];
      $_SESSION['username'] = $result['username'];
      return true;
    } else {
      return false;
    }
  } catch (PDOException $e) {
    echo 'Error executing query: ' . $e->getMessage();
    return false;
  }

  // Close the database connection
  $conn = null;
}
?>


<?php
// Assume the user input is stored in these variables:
$username = $_POST['username'];
$password = $_POST['password'];

if (login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}
?>


<?php

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

// Function to check user credentials
function check_credentials($username, $password) {
    global $mysqli;

    // Query to get user data from database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);

    if ($result->num_rows == 0) {
        return array(false, 'Invalid username or password');
    }

    // Get the stored hash of the user's password
    $row = $result->fetch_assoc();
    $stored_hash = $row['password'];

    // Check if the submitted password matches the stored hash
    if (password_verify($password, $stored_hash)) {
        return array(true, 'Login successful');
    } else {
        return array(false, 'Invalid username or password');
    }
}

// Function to login user and set session variables
function login_user($username) {
    global $mysqli;

    // Query to get user data from database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);

    if ($result->num_rows == 0) {
        return array(false, 'Invalid username or password');
    }

    // Get the stored data for the user
    $row = $result->fetch_assoc();

    // Set session variables
    $_SESSION['username'] = $row['username'];
    $_SESSION['email'] = $row['email'];

    return array(true, 'Login successful');
}

// Function to handle login form submission
function process_login($username, $password) {
    list($success, $message) = check_credentials($username, $password);

    if ($success) {
        login_user($username);
        $_SESSION['logged_in'] = true;
        header('Location: index.php');
        exit();
    } else {
        return array(false, 'Invalid username or password');
    }
}

// Example usage:
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    list($success, $message) = process_login($username, $password);

    if ($success) {
        echo 'You have been logged in successfully';
    } else {
        echo 'Login failed: ' . $message;
    }
}


<?php
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli("localhost", "username", "password", "database_name");

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare SQL query
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);

  // Execute the query
  $stmt->execute();

  // Get result
  $result = $stmt->get_result();

  // Check if user exists
  if ($result->num_rows > 0) {
    // Fetch user data
    $user_data = $result->fetch_assoc();

    // Verify password using SHA-256 hash
    $password_hashed = sha1($password);
    if ($user_data['password'] === $password_hashed) {
      return true;
    } else {
      echo "Invalid password";
      return false;
    }
  } else {
    echo "User not found";
    return false;
  }

  // Close connection
  $conn->close();
}


// Call the function to login a user
if (loginUser("username", "password")) {
  echo "Login successful!";
} else {
  echo "Login failed.";
}


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


function loginUser($username, $password)
{
    // Initialize variables
    $dbHost = 'your_host';
    $dbName = 'your_database';
    $dbUsername = 'your_username';
    $dbPassword = 'your_password';

    // Connect to database
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);

    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");

    // Bind parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    // Execute query
    $stmt->execute();

    // Fetch user data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // User found, return user data
        return array(
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email']
        );
    } else {
        // Invalid login credentials, return error message
        return null;
    }
}


$username = 'example_user';
$password = 'secret_password';

$userData = loginUser($username, $password);

if ($userData) {
    echo "User logged in successfully!";
    print_r($userData);
} else {
    echo "Invalid login credentials.";
}


function login($username, $password) {
    require_once 'database.php'; // Assuming your database connection settings are in a separate file.

    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);

    if ($stmt->execute()) {
        $user = $stmt->fetch();

        // Verify the password using SHA-256
        $hashedPassword = hash('sha256', $password . $salt); // Use a salt to prevent rainbow table attacks

        if ($user && $user['password'] === $hashedPassword) {
            // Login successful, create a new session
            $sessionKey = bin2hex(random_bytes(16));
            $query = "INSERT INTO sessions (user_id, session_key) VALUES (:user_id, :session_key)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':user_id', $user['id']);
            $stmt->bindParam(':session_key', $sessionKey);

            if ($stmt->execute()) {
                return ['success' => true, 'session_key' => $sessionKey];
            } else {
                return ['error' => 'Failed to create session'];
            }
        } else {
            return ['error' => 'Invalid username or password'];
        }
    } else {
        return ['error' => 'Database error'];
    }
}


$username = $_POST['username'];
$password = $_POST['password'];

$result = login($username, $password);

if ($result['success']) {
    echo 'Logged in successfully!';
} else {
    echo $result['error'];
}


<?php
/**
 * User Login Function
 *
 * @param string $username
 * @param string $password
 *
 * @return bool|stdClass
 */
function user_login($username, $password) {
  // Connect to database (replace with your own connection method)
  $db = mysqli_connect("localhost", "username", "password", "database");

  // Check if username and password are valid
  $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = mysqli_query($db, $query);

  // If query returns a row, user is authenticated
  if (mysqli_num_rows($result) == 1) {
    // Get the user's ID from the database
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['id'];

    // Return user data as an object
    return new stdClass();
    $user_data->id = $user_id;
    $user_data->username = $username;
    $user_data->email = $row['email'];
  } else {
    // If query returns no rows, return false to indicate authentication failed
    return false;
  }

  // Close database connection
  mysqli_close($db);
}


$username = "john";
$password = "secret";

if ($user = user_login($username, $password)) {
  echo "Welcome, " . $user->username . "!";
} else {
  echo "Authentication failed.";
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
        global $conn;
        
        // Get user input
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate user input
        if (empty($username) || empty($password)) {
            echo 'Please enter both username and password';
            return false;
        }

        // Prepare SQL query
        $query = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($query);

        // Bind parameters
        $stmt->bind_param('ss', $username, $password);

        // Execute query
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Login successful, redirect to home page or dashboard
                header('Location: /');
                exit;
                
            } else {
                echo 'Invalid username or password';
                return false;
            }
        } else {
            echo 'Error logging in: ' . $stmt->error;
            return false;
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();

    }

?>


<?php

// Configuration variables
$databaseHost = 'localhost';
$databaseName = 'your_database_name';
$databaseUsername = 'your_database_username';
$databasePassword = 'your_database_password';

// Connect to the database
$conn = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;
    
    // Get input from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database to check user credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User is authenticated, start session
        session_start();
        $_SESSION['username'] = $username;
        
        header('Location: dashboard.php');
        exit;
    } else {
        echo 'Invalid username or password';
    }
}

// Check for POST request (login form submission)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    login_user();
}

?>


<?php

// Configuration settings
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password) {
    // Query to select user from database
    $query = "SELECT * FROM users WHERE username = '$username'";
    
    // Execute query and store result
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        // User found, hash password for comparison
        $user_data = $result->fetch_assoc();
        $hashed_password = md5($password); // Note: MD5 is not recommended for password storage, consider using a more secure method like bcrypt
        
        if ($hashed_password === $user_data['password']) {
            // Login successful, return user data
            return array(
                'username' => $username,
                'role' => $user_data['role'],
                'id' => $user_data['id']
            );
        }
    }
    
    // Login failed
    return null;
}

// Example usage:
$username = $_POST['username'];
$password = md5($_POST['password']); // Note: MD5 is not recommended for password storage, consider using a more secure method like bcrypt

$login_result = login_user($username, $password);

if ($login_result !== null) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}

?>


<?php

// Configuration
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function validateInput($input) {
    // Sanitize and escape user input
    return htmlspecialchars(trim($input));
}

function login($username, $password) {
    global $conn;

    // Validate username and password
    if (empty($username) || empty($password)) {
        return array('error' => 'Both username and password are required.');
    }

    // Escape user input
    $username = validateInput($username);
    $password = validateInput($password);

    // Query database to retrieve user data
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Retrieve user data
        $user_data = $result->fetch_assoc();

        // Verify password using md5 (for simplicity; consider using a secure hashing algorithm)
        if (md5($password) === $user_data['password']) {
            return array('success' => true, 'message' => 'Login successful.');
        } else {
            return array('error' => 'Invalid username or password.');
        }
    } else {
        return array('error' => 'Invalid username or password.');
    }

    $stmt->close();
}

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = login($username, $password);

    if ($result['success']) {
        echo json_encode(array('message' => 'Login successful'));
    } else {
        echo json_encode($result);
    }
}

?>


<?php

// Configuration
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Connect to database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

function login($username, $password) {
    global $pdo;

    // Prepare and execute query
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch user data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify password using hash comparison (recommended)
        if (password_verify($password, $user['password'])) {
            return array(
                'success' => true,
                'username' => $username,
                'email' => $user['email']
            );
        } else {
            return array('success' => false);
        }
    } else {
        return array('success' => false);
    }
}

if (isset($_POST['submit'])) {
    // Handle form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = login($username, $password);

    if ($result['success']) {
        echo 'Login successful!';
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $result['email'];
        header('Location: dashboard.php');
        exit();
    } else {
        echo 'Invalid username or password';
    }
}


<?php

// Configuration settings
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'mydatabase';

// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
  global $conn;

  // Prepare SQL query to select the user
  $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
  $stmt->bind_param("s", $username);

  // Execute the query and get results
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Get user data from database
    $row = $result->fetch_assoc();
    
    // Check password
    if (password_verify($password, $row['password'])) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }

  // Close statement and connection
  $stmt->close();
  $conn->close();

}

// Example usage:
$username = 'testuser';
$password = 'testpass';

if (login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
    exit;
}

// Define the login function
function login($email, $password) {
    // Sanitize input
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);

    // Prepare SQL query
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->fetch();

    // If a matching user is found, return their data
    if ($result) {
        return array(
            'id' => $result['id'],
            'email' => $result['email'],
            'username' => $result['username']
        );
    } else {
        return null;
    }
}

// Handle login form submission
if (isset($_POST['login'])) {
    // Get the email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Login the user
    $user_data = login($email, $password);

    if ($user_data) {
        // User is logged in, store their data in a session variable
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['username'] = $user_data['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Invalid email or password";
    }
}

?>


<?php
// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    echo "Welcome, " . $_SESSION['username'];
} else {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <!-- Dashboard content here -->
</body>
</html>


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

// Define database connection settings
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

// Define function to validate login credentials
function validateLogin($email, $password) {
    global $conn;

    // Prepare SQL query
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Define function to login user
function loginUser($email, $password) {
    global $conn;

    // Validate login credentials
    if (validateLogin($email, $password)) {
        // If valid, create session and redirect to protected page
        $_SESSION['user_email'] = $email;
        header('Location: protected.php');
        exit();
    } else {
        // If invalid, display error message
        echo "Invalid email or password.";
    }
}

// Handle login form submission
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    loginUser($email, $password);
}

// Close database connection
$conn->close();

?>


<?php

// Database Connection Settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to connect to database
function dbConnect() {
  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to login user
function loginUser($username, $password) {
  // Connect to database
  $conn = dbConnect();

  // Prepare SQL query
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password);

  // Execute SQL query
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if user exists and password is correct
  if ($result && $row = $result->fetch_assoc()) {
    return true; // User logged in successfully
  } else {
    return false; // Invalid username or password
  }

  // Close database connection
  $conn->close();
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
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

// Configuration
$hostname = 'your_host_name';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to database
$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Clean input data
$username = $_POST['username'];
$password = $_POST['password'];

// Hash password (basic example using MD5)
$hashed_password = md5($password);

// SQL query to check user credentials
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
  // User exists and credentials are correct, log them in
  while ($row = $result->fetch_assoc()) {
    session_start();
    $_SESSION['username'] = $username;
    header('Location: index.php'); // Redirect to index page after login
    exit;
  }
} else {
  echo "Invalid username or password";
}

// Close connection
$conn->close();

?>


<?php
// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Function to check login credentials
function check_login($username, $password) {
    global $mysqli;
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        if (password_verify($password, $user_data['password'])) {
            return true;
        }
    }

    return false;
}

// Function to login user
function login_user($username, $password) {
    global $mysqli;

    // Check if username and password are set
    if (!isset($username) || !isset($password)) {
        return array('error' => 'Username or password not set');
    }

    // Check login credentials
    if (check_login($username, $password)) {
        // Login successful, store user data in session
        $_SESSION['username'] = $username;
        $_SESSION['logged_in'] = true;

        return array('success' => 'Login successful');
    } else {
        return array('error' => 'Invalid username or password');
    }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = login_user($username, $password);

if (isset($result['error'])) {
    echo '<p style="color: red;">' . $result['error'] . '</p>';
} else if (isset($result['success'])) {
    echo '<p style="color: green;">' . $result['success'] . '</p>';

    // Redirect to protected page
    header('Location: protected-page.php');
    exit;
}
?>


<?php

// Define database connection settings
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'users';

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login() {
  global $conn;

  // Get user input from form
  $email = $_POST['email'];
  $password = md5($_POST['password']);

  // Prepare and execute query
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();

  // Get result from database
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Login successful, get user data and store in session
    while($row = $result->fetch_assoc()) {
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['email'] = $row['email'];
      $_SESSION['name'] = $row['name'];

      header("Location: dashboard.php");
      exit;
    }
  } else {
    // Login failed, display error message
    echo "Invalid email or password";
  }

  // Close statement and connection
  $stmt->close();
  $conn->close();
}

// Check if form has been submitted
if (isset($_POST['submit'])) {
  login();
} else {
  // Display login form
?>

<form action="" method="post">
  <label>Email:</label>
  <input type="email" name="email"><br><br>
  <label>Password:</label>
  <input type="password" name="password"><br><br>
  <input type="submit" name="submit" value="Login">
</form>

<?php
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

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define login function
function user_login($username, $password) {
    // Prepare SQL query to select user from database
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    
    // Prepare statement and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows == 1) {
        // User found, return true
        return true;
    } else {
        // User not found, return false
        return false;
    }
}

// Check if login form was submitted
if (isset($_POST['username']) && isset($_POST['password'])) {
    // Call user_login function with form values
    $login = user_login($_POST['username'], $_POST['password']);

    // Display message based on login result
    if ($login) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}

// Close database connection
$conn->close();

?>


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Establish database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function validateUser($username, $password) {
    global $conn;

    // Query to retrieve user data from database
    $query = "SELECT * FROM users WHERE username = '$username'";

    // Execute query and store result
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Fetch the row from the result set
        $row = $result->fetch_assoc();

        // Compare provided password with stored hashed password
        if (password_verify($password, $row['password'])) {
            return true;
        }
    }

    return false;
}

function login($username, $password) {
    global $conn;

    // Validate user credentials
    if (validateUser($username, $password)) {
        echo "Login successful! Welcome, " . $username . ".";
    } else {
        echo "Invalid username or password.";
    }

    // Close database connection
    $conn->close();
}

// Example usage:
$loginUsername = 'exampleuser';
$loginPassword = 'mysecretpassword';

login($loginUsername, $loginPassword);

?>


<?php

// Configuration
$database = array(
    'host' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'name' => 'your_database_name'
);

// Function to connect to database
function connectDatabase() {
    $conn = new PDO("mysql:host={$database['host']};dbname={$database['name']}", 
                    $database['username'], $database['password']);
    return $conn;
}

// Function to register user
function registerUser($email, $username, $password) {
    $conn = connectDatabase();
    $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $hashedPassword = hash('sha256', $password); // Hash password before storing
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();
}

// Function to login user
function loginUser($email, $password) {
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $stmt->bindParam(':email', $email);
    $hashedPassword = hash('sha256', $password); // Hash password before comparison
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();
    $user = $stmt->fetch();
    if ($user) {
        return $user;
    } else {
        return null;
    }
}

// Example usage:
if (isset($_POST['submit'])) {
    // Get user input
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Register or login user
    if ($_POST['action'] == 'register') {
        registerUser($email, $username, $password);
    } elseif ($_POST['action'] == 'login') {
        $user = loginUser($email, $password);
        if ($user) {
            // User logged in successfully, save session or redirect to dashboard
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            echo "Invalid email or password";
        }
    }
}

?>


<?php
// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database_name');

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
    // Prepare SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    
    // Bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Login successful
        return true;
    } else {
        // Login failed
        return false;
    }
}

function register($username, $password, $email) {
    // Prepare SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    
    // Bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $email);
    
    // Execute query
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
} elseif (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    
    if (register($username, $password, $email)) {
        echo "Registration successful!";
    } else {
        echo "Failed to register.";
    }
}
?>


<?php
  $server = 'your_server_name';
  $username = 'your_database_username';
  $password = 'your_database_password';
  $database = 'your_database_name';

  try {
    $pdo = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
?>


<?php
require_once 'db.php';

function login_user($username, $password) {
  try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return true;
    } else {
      return false;
    }
  } catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    return false;
  }
}

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if ($login_user($username, $password)) {
    // User logged in successfully
    echo 'You are now logged in!';
  } else {
    echo 'Invalid username or password.';
  }
}
?>


<?php require_once 'login.php'; ?>

<form action="" method="post">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username"><br><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password"><br><br>
  <button type="submit" name="login">Login</button>
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


function login_user($username, $password) {
  // Define our users array (in a real application, this would be stored securely in a database)
  $users = [
    "admin" => ["password" => "123456", "access_level" => "superuser"],
    "user1" => ["password" => "qwerty", "access_level" => "normal"]
  ];

  // Check if the username exists
  if (!array_key_exists($username, $users)) {
    return ["error" => "Invalid username or password"];
  }

  // Check if the provided password matches the stored one
  if ($password !== $users[$username]["password"]) {
    return ["error" => "Invalid username or password"];
  }

  // If both checks pass, return a success message with user details
  return [
    "success" => true,
    "user_data" => [
      "username" => $username,
      "access_level" => $users[$username]["access_level"]
    ]
  ];
}


// Try to login as 'admin' with password '123456'
$result = login_user("admin", "123456");
print_r($result); // Should return ["success" => true, ...]

// Try to login as 'non-existent-user' with any password
$result = login_user("non-existent-user", "any_password");
print_r($result); // Should return ["error" => "Invalid username or password"]

// Try to login as 'admin' with an incorrect password
$result = login_user("admin", "wrong_password");
print_r($result); // Should return ["error" => "Invalid username or password"]


// User data in an associative array
$users = [
    'john' => ['password' => 'hello', 'email' => 'john@example.com'],
    'jane' => ['password' => 'world', 'email' => 'jane@example.com']
];


/**
 * User login function.
 *
 * @param string $username Username to verify.
 * @param string $password Password to verify.
 * @return bool True if credentials are valid, false otherwise.
 */
function login($username, $password) {
    global $users; // Access the users array

    // Validate input
    if (empty($username) || empty($password)) {
        return false;
    }

    // Check if user exists
    if (!isset($users[$username])) {
        return false;
    }

    // Verify password
    if ($users[$username]['password'] !== $password) {
        return false;
    }

    // If we reach this point, credentials are valid
    return true;
}


// Attempt to login with correct credentials
$username = 'john';
$password = 'hello';

if (login($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}

// Attempt to login with incorrect credentials
$username = 'jane';
$password = 'wrong';

if (login($username, $password)) {
    echo "Login successful!"; // This should not be printed
} else {
    echo "Invalid username or password.";
}


<?php

// Database connection settings
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for errors in the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get input data from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL query to select user's password hash and salt
    $stmt = $conn->prepare("SELECT password_hash, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch user's data
        $row = $result->fetch_assoc();
        $password_hash = $row['password_hash'];
        $user_email = $row['email'];

        // Verify password using hashing and comparison
        $is_valid_password = password_verify($password, $password_hash);

        if ($is_valid_password) {
            // Login successful, generate a session for the user
            $_SESSION['logged_in'] = true;
            $_SESSION['user_email'] = $user_email;

            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }

    // Close prepared statement and connection
    $stmt->close();
    $conn->close();
}

// Check for POST request to the login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    login_user();
} else {
    // Display login form if there is no POST request
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Email: <input type="email" name="email"><br><br>
        Password: <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
}
?>


<?php
// Database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database for the user's credentials
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                // User credentials match, set session variables
                $_SESSION['username'] = $username;
                $_SESSION['logged_in'] = true;
                return true;
            }
        }
    }

    // If user credentials don't match or user not found, return false
    return false;
}

if (isset($_POST['login'])) {
    if (login_user()) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo 'Invalid username or password';
    }
}
?>


<?php
// Config file with database connection details
require_once 'config.php';

function login($username, $password) {
    // Prepare SQL statement to retrieve user credentials
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    
    // Execute query and store result in array
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Verify password using hash comparison
        if (password_verify($password, $user['password'])) {
            return true;
        } else {
            echo "Invalid username or password.";
            return false;
        }
    } else {
        echo "Invalid username or password.";
        return false;
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (login($username, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid login attempt.";
    }
}
?>


<?php
require_once 'config.php'; // assume config.php has db connection details

function validateUser($username, $password) {
  global $db; // access the database connection

  // SQL query to retrieve user data
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($db, $query);

  if (mysqli_num_rows($result) == 1) {
    // Retrieve user data from result set
    $userData = mysqli_fetch_assoc($result);
    $storedPassword = $userData['password'];

    // Verify password using hash comparison (assuming you're using a secure hashing algorithm)
    if (password_verify($password, $storedPassword)) {
      return true; // login successful
    } else {
      return false; // incorrect password
    }
  } else {
    return false; // no user found with given username
  }
}

function loginUser($username) {
  global $db; // access the database connection

  // SQL query to update last login time
  $query = "UPDATE users SET last_login = NOW() WHERE username = '$username'";
  mysqli_query($db, $query);

  return true;
}
?>


require_once 'login.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (validateUser($username, $password)) {
    loginUser($username);
    // redirect to protected area or display success message
  } else {
    echo "Invalid username or password";
  }
}


function loginUser($username, $password) {
  // Connect to database (you should use prepared statements for security)
  require_once 'db.php';
  $conn = mysqli_connect($GLOBALS['dbHost'], $GLOBALS['dbUser'], $GLOBALS['dbPass']);
  if (!$conn) {
    die("Connection failed: " . mysqli_error($conn));
  }

  // Get user data
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $storedHashedPassword = $row['password'];

      // Hash input password
      $hashedInputPassword = hash('sha256', $password);

      // Check if hashed passwords match
      if ($hashedInputPassword === $storedHashedPassword) {
        return true;
      }
    }
  }

  return false;
}


// Connect to database (you should use prepared statements for security)
require_once 'db.php';
$conn = mysqli_connect($GLOBALS['dbHost'], $GLOBALS['dbUser'], $GLOBALS['dbPass']);

$username = $_POST['username'];
$password = $_POST['password'];

if (loginUser($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid username or password.";
}


/**
 * User login function.
 *
 * @param string $username The username to log in with.
 * @param string $password  The password to log in with.
 *
 * @return bool True if the login is successful, false otherwise.
 */
function loginUser($username, $password) {
    // Define the database connection settings
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'database_name');
    define('DB_USER', 'database_username');
    define('DB_PASSWORD', 'database_password');

    // Connect to the database
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query the database for the user's credentials
    $query = "SELECT * FROM users WHERE username = '$username' AND password = MD5('$password')";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }

    // Close the database connection
    mysqli_close($conn);
}


// Set the username and password variables
$username = "example_user";
$password = "password123";

// Call the loginUser function with the set credentials
if (loginUser($username, $password)) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}


<?php

function login($username, $password) {
  // Connect to the database
  $mysqli = new mysqli("localhost", "username", "password", "database");

  // Check connection
  if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
  }

  // Prepare SQL query
  $stmt = $mysqli->prepare("SELECT * FROM users WHERE username=?");
  $stmt->bind_param("s", $username);

  // Execute the query and get the result
  if ($stmt->execute()) {
    $result = $stmt->get_result();

    // If user found, check password
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      if (password_verify($password, $row['password'])) {
        return true; // Successful login
      } else {
        echo "Invalid password";
        return false;
      }
    } else {
      echo "User not found";
      return false;
    }
  } else {
    echo "Error: " . $mysqli->error;
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $mysqli->close();

  // If no user or password is invalid, return false
  return false;
}

?>


<?php

$username = "exampleuser";
$password = "password123";

if (login($username, $password)) {
  echo "Login successful!";
} else {
  echo "Login failed.";
}

?>


<?php

// Set up database connection
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

function login_user($username, $password) {
    global $conn;
    
    // Prepare SQL query to select user from database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    
    // Bind parameters to prevent SQL injection
    $stmt->bind_param("ss", $username, $password);
    
    // Execute query
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        // Check if user exists in database
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function get_user_id($username, $password) {
    global $conn;
    
    // Prepare SQL query to select user id from database
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
    
    // Bind parameters to prevent SQL injection
    $stmt->bind_param("ss", $username, $password);
    
    // Execute query
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        // Get user id from database
        $user_id = 0;
        while ($row = $result->fetch_assoc()) {
            $user_id = (int) $row['id'];
        }
        
        return $user_id;
    } else {
        return false;
    }
}

// Example usage:
$login_username = 'example_user';
$login_password = 'example_password';

if (login_user($login_username, $login_password)) {
    echo "User logged in successfully.";
    
    // Get user id
    $user_id = get_user_id($login_username, $login_password);
    
    if ($user_id !== false) {
        echo "User ID: $user_id";
    } else {
        echo "Failed to retrieve user ID.";
    }
} else {
    echo "Invalid username or password.";
}

// Close database connection
$conn->close();

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

// Configuration settings
$database = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Database connection function
function connectDatabase() {
    try {
        $dsn = "mysql:host=localhost;dbname=$database";
        $pdo = new PDO($dsn, $username, $password);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
}

// User login function
function loginUser($email, $password) {
    try {
        // Connect to database
        $pdo = connectDatabase();

        // Prepare SQL query
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);

        // Execute query
        $stmt->execute();
        $user = $stmt->fetch();

        // Check password
        if ($user && crypt($password, $user['password']) == $user['password']) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo 'Login failed: ' . $e->getMessage();
        exit;
    }
}

// Example usage:
$email = 'example@example.com';
$password = 'your_password';

if (loginUser($email, $password)) {
    echo 'Login successful!';
} else {
    echo 'Invalid email or password.';
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

  function login_user() {
    global $conn;

    // Get input from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check for empty fields
    if (empty($username) || empty($password)) {
      echo "Please enter both username and password";
      return;
    }

    // Hash password (optional)
    // $hashedPassword = hash('sha256', $password);

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // Login successful
      echo "Welcome, " . $_POST['username'];
    } else {
      // Login failed
      echo "Invalid username or password";
    }

    $conn->close();
  }
?>


<?php
  $server = 'your_server';
  $username = 'your_username';
  $password = 'your_password';
  $dbname = 'your_database';

  try {
    $pdo = new PDO("mysql:host=$server;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
  }
?>


<?php

// Include the database connection settings
include('config.php');

if(isset($_POST['submit'])){
  // Get form data
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  // SQL query to check if username and password match in the database
  try {
    $query = "SELECT * FROM users WHERE username = :username AND password = :password";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    // Execute query and store the result in a variable
    $result = $stmt->execute();

    if ($result) {
      // If username and password match, redirect to dashboard page
      header('Location: dashboard.php');
      exit;
    } else {
      echo 'Invalid username or password';
    }
  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }
}
?>

<!-- HTML form for user login -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <label>Username:</label>
  <input type="text" name="username"><br><br>
  <label>Password:</label>
  <input type="password" name="password"><br><br>
  <input type="submit" name="submit" value="Login">
</form>


<?php

// Include the database connection settings
include('config.php');

if(isset($_SESSION['username'])){
  // If user is already logged in, display dashboard content
  echo 'Welcome, ' . $_SESSION['username'] . '! You are now logged in.';
} else {
  // If user is not logged in, redirect to login page
  header('Location: login.php');
  exit;
}
?>


// db_config.php (example database connection file)
<?php
    $db_host = 'localhost';
    $db_username = 'your_username';
    $db_password = 'your_password';
    $db_name = 'your_database';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>


// login.php (user login function)
<?php
    include 'db_config.php';

    // Check if form is submitted
    if (isset($_POST['login'])) {

        // Get input values
        $username = $_POST['username'];
        $password = md5($_POST['password']);  // Note: MD5 is not recommended for password hashing in production, use a library like bcrypt or argon2 instead

        // SQL query to select user from database
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User found, log them in
            while ($row = $result->fetch_assoc()) {
                session_start();
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $row['username'];
                header("Location: dashboard.php");
                exit;
            }
        } else {
            echo "Invalid username or password";
        }

    } else {
        // Display login form
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            Username: <input type="text" name="username"><br>
            Password: <input type="password" name="password"><br>
            <input type="submit" name="login" value="Login">
        </form>
        <?php
    }
?>


<?php

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to connect to database
function dbConnect() {
  $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
  return $conn;
}

// Function to login user
function loginUser($username, $password) {
  // Connect to database
  $conn = dbConnect();

  // Query to select user by username and password
  $query = "SELECT * FROM users WHERE username = :username AND password = :password";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', $password);
  $stmt->execute();

  // Get user data from query
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // Check if user exists and password is correct
  if ($user) {
    return true;
  } else {
    return false;
  }

  // Close database connection
  $conn = null;
}

// Function to register new user
function registerUser($username, $password, $email) {
  // Connect to database
  $conn = dbConnect();

  // Query to insert new user into users table
  $query = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', $password);
  $stmt->bindParam(':email', $email);
  $stmt->execute();

  // Close database connection
  $conn = null;
}

// Example usage:
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    echo "Login successful!";
  } else {
    echo "Invalid username or password.";
  }
}

if (isset($_POST['register'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];

  registerUser($username, $password, $email);
  echo "User registered successfully!";
}
?>


// config.php (required for database connection)
<?php
$host = 'localhost';
$dbname = 'database_name';
$username = 'database_username';
$password = 'database_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>


// login.php
<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if email and password match a record in the database
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email AND password = :password');
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    try {
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // User exists, login successful
            session_start();
            $_SESSION['user_id'] = $stmt->fetchColumn(0); // assuming user ID is first column
            header('Location: dashboard.php');
            exit;
        } else {
            // Login failed, display error message
            echo 'Invalid email or password';
        }
    } catch (PDOException $e) {
        echo 'Error logging in: ' . $e->getMessage();
    }
}

?>


<!-- index.html (example form to submit login credentials) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="login.php" method="POST">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>


<?php
// Configuration settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define functions for user login and registration
function loginUser($username, $password) {
    global $conn;
    
    // SQL query to retrieve user data based on username
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();
        
        // Verify password using hashed value
        if (password_verify($password, $user['password'])) {
            // Successful login; create a session for the user
            $_SESSION['username'] = $user['username'];
            return true;
        }
    }

    return false;
}

function registerUser($username, $email, $password) {
    global $conn;

    // Hash password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert new user data into table
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if ($conn->query($sql)) {
        return true; // User registration successful
    } else {
        return false; // Error registering user
    }
}

// Check for POST requests to login or register a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (isset($_POST['login'])) {
        if (loginUser($username, $password)) {
            // Redirect to secured area after successful login
            header('Location: secured.php');
            exit;
        } else {
            echo 'Invalid username or password';
        }
    } elseif (isset($_POST['register'])) {
        $email = $_POST['email'];
        if (registerUser($username, $email, $password)) {
            // Redirect to login page after successful registration
            header('Location: login.php');
            exit;
        } else {
            echo 'Error registering user';
        }
    }
}
?>


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a database connection
function connect_to_db() {
    global $db_host, $db_username, $db_password, $db_name;
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Check user credentials and login
function user_login($username, $password) {
    global $db_host, $db_username, $db_password, $db_name;

    // Create a database connection
    $conn = connect_to_db();

    // Query to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Retrieve user data
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Login successful
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            header('Location: /dashboard'); // Redirect to dashboard after login
            exit();
        } else {
            echo 'Invalid password';
        }
    } else {
        echo 'Username not found';
    }

    // Close database connection
    $conn->close();
}

// Call the user_login function on form submission
if (isset($_POST['username']) && isset($_POST['password'])) {
    user_login($_POST['username'], $_POST['password']);
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

// Function to check login credentials
function check_login($username, $password) {
    global $conn;
    
    // Prepare query
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute query
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        return true; // Login successful
    } else {
        return false; // Login failed
    }
}

// Function to login user
function login_user($username, $password) {
    global $conn;
    
    // Check if username and password are valid
    if (check_login($username, $password)) {
        // Get user data from database
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($query);
        $user_data = $result->fetch_assoc();
        
        // Set session variables
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['password'] = $user_data['password'];
        
        return true; // Login successful
    } else {
        return false; // Login failed
    }
}

// Function to handle login form submission
function handle_login_form_submission() {
    global $conn;
    
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Login user
    if (login_user($username, $password)) {
        header('Location: index.php');
        exit();
    } else {
        echo 'Invalid username or password';
    }
}

// Check if login form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    handle_login_form_submission();
} else {
?>
<form action="" method="post">
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
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Display user data (e.g. username, email)
echo 'Welcome, ' . $_SESSION['username'] . '!';
?>


<?php
// Configuration
$database = 'users.db';
$usernameField = 'username';
$passwordField = 'password';

// Function to check if the username exists
function checkUsernameExists($conn, $username) {
  $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  return ($stmt->rowCount() > 0);
}

// Function to login a user
function loginUser($conn, $username, $password) {
  if (checkUsernameExists($conn, $username)) {
    // Get the password from the database
    $stmt = $conn->prepare('SELECT password FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch();
    
    if (password_verify($password, $row['password'])) {
      // User is logged in
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // Username does not exist
    return false;
  }
}

// Connect to the database
$conn = new PDO('sqlite:' . $database);

// Process login form submission
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($conn, $username, $password)) {
    // User is logged in, redirect to dashboard
    header('Location: dashboard.php');
    exit;
  } else {
    // Incorrect login credentials, display error message
    echo 'Incorrect username or password';
  }
} else {
  // Display login form
  ?>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    Username: <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>
    <input type="submit" value="Login">
  </form>
  <?php
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
// Configuration settings
$host = 'localhost';
$dbname = 'database_name'; // Replace with your database name
$username = 'username'; // Replace with your username
$password = 'password'; // Replace with your password

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Function to register a new user
function registerUser($username, $email, $password, $confirm_password)
{
    global $conn;

    if ($password !== $confirm_password) {
        throw new Exception('Passwords do not match');
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insert into users table
        $stmt = $conn->prepare("INSERT INTO users (username, email, password)
                                VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            echo "User registered successfully!";
        } else {
            throw new Exception('Failed to register user');
        }
    } catch (PDOException $e) {
        echo 'Registration failed: ' . $e->getMessage();
    }

}

// Function to login a user
function loginUser($username, $password)
{
    global $conn;

    try {
        // Select from users table
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);

        if ($stmt->execute()) {
            $user = $stmt->fetch();

            if ($user) {
                // Check if the password is correct
                if (password_verify($password, $user['password'])) {
                    echo "User logged in successfully!";
                    return true;
                } else {
                    throw new Exception('Incorrect username or password');
                }
            } else {
                throw new Exception('Username does not exist');
            }

        } else {
            throw new Exception('Failed to login user');
        }

    } catch (PDOException $e) {
        echo 'Login failed: ' . $e->getMessage();
    }

}

// Example usage
$username = "example_user";
$password = "password123";

try {
    registerUser($username, "user@example.com", $password, $password);
} catch (Exception $e) {
    echo $e->getMessage();
}

try {
    loginUser($username, $password);
} catch (Exception $e) {
    echo $e->getMessage();
}


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'mydatabase');

// Function to connect to database
function db_connect() {
    $conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

// Function to query user data from database
function get_user_data($username, $password) {
    $conn = db_connect();
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        return true;
    } else {
        return false;
    }
}

// Function to validate user input
function validate_input($username, $password) {
    // Check for empty inputs
    if (empty($username) || empty($password)) {
        return false;
    }
    
    // Check for valid username and password length
    if (strlen($username) < 3 || strlen($password) < 6) {
        return false;
    }
    
    return true;
}

// Function to handle login
function login_user($username, $password) {
    // Validate user input
    if (!validate_input($username, $password)) {
        echo "Invalid username or password.";
        return;
    }
    
    // Check user credentials against database data
    if (get_user_data($username, $password)) {
        session_start();
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    
    login_user($username, $password);
}

?>


function loginUser($username, $password) {
    // Define array of existing users with hashed passwords
    $users = [
        "admin" => "$2y$10$.l5fGv0J6kP8aB4cL3pT..",
        "user1" => "$2y$10$.l5fGv0J6kP8aB4cL3pT..",
        // Add more users as needed
    ];

    // Hash the input password (optional, but recommended for security)
    $hashedPassword = hash("sha256", $password);

    // Check if username exists in users array
    if (array_key_exists($username, $users)) {
        // Compare hashed passwords
        if ($hashedPassword === $users[$username]) {
            // Authentication successful, return user's details
            return [
                "name" => $username,
                "email" => "$username@example.com", // Example email address
                // Add more user data as needed
            ];
        } else {
            // Password mismatch, return error message
            return ["error" => "Incorrect password"];
        }
    } else {
        // Username does not exist, return error message
        return ["error" => "Username not found"];
    }
}


$credentials = [
    "username" => "admin",
    "password" => "mysecretpassword"
];

$result = loginUser($credentials["username"], $credentials["password"]);

if (isset($result["error"])) {
    echo "Error: " . $result["error"];
} else {
    echo "Login successful for user: " . $result["name"];
}


<?php
require_once 'db_config.php'; // Include database connection settings

if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = md5($_POST['password']); // Use MD5 for password hashing, not recommended for actual production use; consider using a library like `password_hash` or `bcrypt`

  $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) == 1) {
    // Login successful
    session_start();
    $_SESSION['username'] = $username;
    header('Location: dashboard.php'); // Redirect to a protected area after login
    exit;
  } else {
    echo 'Invalid username or password';
  }
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="text" name="username" placeholder="Username"><br><br>
  <input type="password" name="password" placeholder="Password"><br><br>
  <button type="submit">Login</button>
</form>


<?php
$servername = 'localhost'; // Hostname or IP address of your MySQL server
$username_db = 'root'; // Database username
$password_db = ''; // Database password

$conn = new mysqli($servername, $username_db, $password_db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>


<?php
session_start();

if (!isset($_SESSION['username'])) {
  header('Location: login.php'); // Redirect back to login page if not logged in
  exit;
}

echo 'Welcome, ' . $_SESSION['username'] . '! You are now accessing the protected dashboard.';
?>


<?php
// Configuration settings
$database = 'mysql'; // MySQL or SQLite
$username = 'your_username';
$password = 'your_password';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Retrieve the username and password from the form data
  $usernameInput = $_POST['username'];
  $passwordInput = $_POST['password'];

  // Hash the password (for security)
  $hashedPassword = hash('sha256', $passwordInput);

  try {
    // Connect to the database
    if ($database == 'mysql') {
      $conn = new PDO("mysql:host=localhost;dbname=your_database", $username, $password);
    } elseif ($database == 'sqlite') {
      $conn = new PDO('sqlite:your_database.db');
    }

    // Prepare and execute the query to check for a valid user
    $query = "SELECT * FROM users WHERE username = :username AND password = :password";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $usernameInput, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->execute();

    // Get the result
    $result = $stmt->fetch();

    if ($result) {
      // If a valid user is found, set the session variables and redirect to the dashboard
      $_SESSION['username'] = $usernameInput;
      $_SESSION['logged_in'] = true;

      header('Location: dashboard.php');
      exit();
    } else {
      // Display an error message if no valid user is found
      echo 'Invalid username or password';
    }
  } catch (PDOException $e) {
    // Handle any database connection errors
    die("Error connecting to the database: " . $e->getMessage());
  }
}

// If the form has not been submitted, display it
?>
<form action="" method="post">
  <label>Username:</label>
  <input type="text" name="username"><br><br>
  <label>Password:</label>
  <input type="password" name="password"><br><br>
  <input type="submit" value="Login">
</form>


// login.php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

function login_user() {
  // Get user input
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Query database for user
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Get hashed password from database
    $row = mysqli_fetch_assoc($result);
    $db_password = $row['password'];

    // Hash user input password
    $hashed_password = hash('sha256', $password . 'your_secret_key');

    if ($db_password == $hashed_password) {
      // Login successful, return user ID and email
      return array(
        'id' => $row['id'],
        'email' => $row['email']
      );
    } else {
      echo "Incorrect password";
    }
  } else {
    echo "User not found";
  }

  mysqli_close($conn);
}

// Check if form submitted
if (isset($_POST['submit'])) {
  // Call login_user function
  $user_data = login_user();
}


<?php
// Define database connection settings
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to login user
function login_user($username, $password) {
  // Prepare SQL query
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);

  // Execute query and get result
  $stmt->execute();
  $result = $stmt->get_result();

  // Fetch row from result
  $row = $result->fetch_assoc();

  // Check if user exists
  if ($row) {
    // Hash password to compare with stored hash
    $hash = password_hash($password, PASSWORD_DEFAULT);
    if (password_verify($hash, $row['password'])) {
      return true; // User logged in successfully
    } else {
      echo "Invalid username or password";
    }
  } else {
    echo "User not found";
  }

  // Close prepared statement and connection
  $stmt->close();
  $conn->close();

  return false;
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

if (login_user($username, $password)) {
  echo "Login successful!";
} else {
  echo "Invalid login credentials";
}
?>


<?php
// Configuration variables
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate user login credentials
function validateLogin($username, $password) {
    global $conn;

    // SQL query to select user data from database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Check for POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input from form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate user login credentials
    if (validateLogin($username, $password)) {
        // Login successful, redirect to dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        // Login failed, display error message
        echo 'Invalid username or password';
    }
}

// Close database connection
$conn->close();
?>


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Connect to database
$connection = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

function login_user() {
    global $connection;

    // Get form data
    if (isset($_POST['username']) && isset($_POST['password'])) {

        // Trim and sanitize input
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Validate username and password length
        if (!empty($username) && !empty($password)) {
            // Hash the password (only do this when a user creates their account)
            // if ($connection->query("SELECT * FROM users WHERE username='$username'") != false) {
            //     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // }

            // Check for valid username
            $result = $connection->query("SELECT * FROM users WHERE username='$username'");
            if ($result->num_rows > 0) {

                // Retrieve user data from database
                $user_data = $result->fetch_assoc();

                // Verify password
                if (password_verify($password, $user_data['password'])) {
                    session_start();
                    $_SESSION['username'] = $user_data['username'];
                    $_SESSION['id'] = $user_data['id'];

                    header("Location: /dashboard");
                } else {
                    echo "Invalid username or password";
                }
            } else {
                echo "Invalid username or password";
            }
        } else {
            echo "Please enter both a username and password to login.";
        }
    }

    // Close connection
    $connection->close();
}

?>


// ...

if (isset($_POST['submit'])) {
    login_user();
}

?>


// Connect to database

// ...

function register_user($username, $password) {
    global $connection;

    // Trim and sanitize input
    $username = trim($username);
    $password = trim($password);

    // Validate username and password length
    if (!empty($username) && !empty($password)) {

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into database
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
        if ($connection->query($query)) {
            echo "User registered successfully.";
        } else {
            echo "Error registering user: " . $connection->error;
        }
    } else {
        echo "Please enter both a username and password to register.";
    }

    // Close connection
    $connection->close();
}


// ...

if (isset($_POST['submit'])) {
    register_user($_POST['username'], $_POST['password']);
}

?>


<?php
require_once 'config/db.php'; // database connection settings

function login_user($username, $password) {
  global $conn; // connect to the database

  // Prepare query to retrieve user data
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  // Fetch user data if exists
  $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user_data) {
    // Verify password using hash_equals function (PHP >= 7.0)
    if (hash_equals($user_data['password'], md5($password))) {
      return true; // login successful
    } else {
      return false; // incorrect password
    }
  } else {
    return false; // user not found
  }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

if (login_user($username, $password)) {
  echo 'Login successful!';
} else {
  echo 'Invalid username or password.';
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


<?php

// Database connection settings
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
  global $conn;

  // Retrieve username and password from form data
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Prepare SQL query to retrieve user data
  $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);

  // Execute query and fetch result
  if ($stmt->execute()) {
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Compare provided password with stored hashed password
    if (password_verify($password, $row['password'])) {
      echo "Login successful!";
      return true;
    } else {
      echo "Invalid username or password.";
      return false;
    }
  } else {
    echo "Error: " . $stmt->error;
    return false;
  }

  // Close prepared statement
  $stmt->close();
}

// Check if form data is submitted
if (isset($_POST['login'])) {
  login_user();
}

// Disconnect from database
$conn->close();

?>


<?php

// Configuration variables
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

// Connect to database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Function to login user
function login_user($email, $password) {
  global $conn;

  // Prepare query
  $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email AND password = :password');
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $password);

  try {
    // Execute query
    $stmt->execute();

    // Get user data
    $user_data = $stmt->fetch();

    if ($user_data) {
      // User exists, login successful
      return $user_data;
    } else {
      // User does not exist or password is incorrect
      return false;
    }
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    return false;
  }
}

// Example usage:
$email = 'example@example.com';
$password = 'password123';

$user_data = login_user($email, $password);

if ($user_data) {
  // Login successful
  echo "Welcome, {$user_data['name']}!";
} else {
  // Login failed
  echo "Invalid email or password";
}

?>


<?php
// Configuration
$dbhost = 'localhost';
$dbname = 'your_database_name';
$dbuser = 'your_database_user';
$dbpass = 'your_database_password';

// Connect to database
$conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

// Define function to check user credentials
function login($username, $password) {
  global $conn;
  
  // Prepare query to select user data
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  
  // Fetch result
  $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
  
  if ($user_data && password_verify($password, $user_data['password'])) {
    // Login successful, return user data
    return array('success' => true, 'data' => $user_data);
  } else {
    // Login failed, return error message
    return array('success' => false, 'error' => 'Invalid username or password');
  }
}

// Handle login request
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  // Call login function and display result
  $result = login($username, $password);
  echo json_encode($result);
} else {
  echo 'Error: Missing username or password';
}
?>


// functions.php

function login_user($username, $password) {
  // Connect to the database
  require_once 'db.php';
  $conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Query the database for the user
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $query);

  // Check if a user exists
  if (mysqli_num_rows($result) == 1) {
    // Get the user's data from the result set
    $user_data = mysqli_fetch_assoc($result);
    
    // Hash the password provided by the user with salt
    $salt = "your_salt";
    $password_hashed = hash('sha256', $password . $salt);

    // Check if the hashed password matches the one in the database
    if ($password_hashed == $user_data['password']) {
      // If it matches, create a session for the user and return their data
      $_SESSION['username'] = $user_data['username'];
      $_SESSION['email'] = $user_data['email'];

      echo "Login successful!";
      return true;
    } else {
      echo "Incorrect password";
      return false;
    }
  } else {
    // If no user exists, display an error message
    echo "User does not exist";
    return false;
  }

  // Close the connection
  mysqli_close($conn);
}


// login.php

require_once 'functions.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (login_user($username, $password)) {
    // If the login is successful, redirect to a protected page
    header('Location: protected-page.php');
    exit;
  }
}

?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="text" name="username" placeholder="Username">
  <input type="password" name="password" placeholder="Password">
  <button type="submit">Login</button>
</form>


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

function hashPassword($password) {
    // Hash the password using a strong algorithm (e.g. bcrypt)
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyUser($username, $password) {
    global $conn;

    // Prepare query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute query
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch row
    $row = $result->fetch_assoc();

    // If user exists, verify password
    if ($row) {
        if (password_verify($password, $row['password'])) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }

    // Close statement and result
    $stmt->close();
    $result->close();

    return false; // If no row is found, return false
}

function loginUser($username, $password) {
    global $conn;

    // Verify user credentials
    if (verifyUser($username, $password)) {
        // User exists and password is correct

        // Prepare query to select user data
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);

        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch row
        $row = $result->fetch_assoc();

        // Return user data as an array
        return array(
            'id' => $row['id'],
            'username' => $row['username'],
            'email' => $row['email']
        );

    } else {
        return false; // User credentials are incorrect or user does not exist
    }

    // Close statement and result
    $stmt->close();
    $result->close();

    return false;
}

// Example usage:
$username = "your_username";
$password = "your_password";

$userData = loginUser($username, $password);

if ($userData) {
    echo "Login successful!";
    print_r($userData);
} else {
    echo "Invalid username or password.";
}


<?php

// Configuration settings
$config = array(
    'db_host' => 'localhost',
    'db_name' => 'my_database',
    'db_user' => 'my_username',
    'db_password' => 'my_password'
);

function connect_to_db() {
    // Connect to the database
    $conn = mysqli_connect($config['db_host'], $config['db_user'], $config['db_password'], $config['db_name']);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

function login_user($username, $password) {
    // Connect to the database
    $conn = connect_to_db();

    // Check if username and password are set
    if (empty($username) || empty($password)) {
        echo 'Error: Username and password must be filled out.';
        return false;
    }

    // Prepare query to check for valid user
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, md5($password));
    mysqli_stmt_execute($stmt);

    // Check if user is found
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 1) {
        return true;
    } else {
        echo 'Error: Invalid username or password.';
        return false;
    }

    // Close database connection
    mysqli_close($conn);
}

function register_user($username, $password) {
    // Connect to the database
    $conn = connect_to_db();

    // Check if username is already taken
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    // Check if user already exists
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 1) {
        echo 'Error: Username is already taken.';
        return false;
    }

    // Prepare query to insert new user
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ss", $username, md5($password));
    mysqli_stmt_execute($stmt);

    // Close database connection
    mysqli_close($conn);
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

if (isset($_POST['login'])) {
    if (login_user($username, $password)) {
        echo 'Login successful!';
    } else {
        echo 'Error: Invalid username or password.';
    }
} elseif (isset($_POST['register'])) {
    register_user($username, $password);
}

?>


<?php

// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database_name';

// Create a database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    // Get the form data from the request
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to select a user with matching username and password
    $sql = "SELECT * FROM users WHERE username='$username' AND password=MD5('$password')";

    // Execute the query
    $result = $conn->query($sql);

    // Check if there is a match
    if ($result->num_rows > 0) {
        // If a user with matching credentials exists, login and display a success message
        while($row = $result->fetch_assoc()) {
            echo "Welcome, " . $row["username"] . "! You have successfully logged in.";
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $row['username'];
            // Redirect the user to another page
            header("Location: dashboard.php");
        }
    } else {
        // If there is no match, display an error message
        echo "Invalid username or password!";
    }

    // Close the connection
    $conn->close();
}

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    login_user();
} else {
    // Display a login form if it's not submitted
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


// Create an index.html file and add the following code:
<html>
    <body>
        <form action="login.php" method="post">
            <label>Username:</label>
            <input type="text" name="username"><br><br>
            <label>Password:</label>
            <input type="password" name="password"><br><br>
            <input type="submit" name="submit" value="Login">
        </form>
    </body>
</html>


<?php

// Configuration constants
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Database connection function
function dbConnect() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Login function
function login($username, $password) {
    // Connect to database
    $conn = dbConnect();

    // Prepare query
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, md5($password));

    // Execute query
    if ($stmt->execute()) {
        // Fetch result
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            return array('success' => true, 'id' => $row['id']);
        }
    } else {
        echo "Error: " . $conn->error;
    }

    // No result found
    return array('success' => false);
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];

$result = login($username, $password);

if ($result['success']) {
    echo "Login successful. User ID: " . $result['id'];
} else {
    echo "Invalid username or password.";
}


// Database configuration (replace with your own DB credentials)
$db_host = "localhost";
$db_username = "username";
$db_password = "password";
$db_name = "your_database";

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function validateLogin() {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Check for SQL injection attempts
        $user = mysqli_real_escape_string($GLOBALS['conn'], $_POST['username']);
        $pass = mysqli_real_escape_string($GLOBALS['conn'], $_POST['password']);

        // Prepare the query to avoid SQL injection
        $query = "SELECT * FROM users WHERE username = '$user' AND password = '$pass'";
        
        // Execute the query and store results in a variable
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                session_start();
                $_SESSION['username'] = $user;
                $_SESSION['id'] = $row['id'];
                return true;
            }
        } else {
            // Handle incorrect login
            echo "Invalid username or password";
            return false;
        }
    } else {
        // Handle no post data submitted
        return null;
    }
}

// Call the function when a form is submitted (e.g., in your HTML/PHP template)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!validateLogin()) {
        echo "Invalid request";
    } else {
        // Redirect to protected area after successful login
        header('Location: protected_area.php');
        exit;
    }
}

// Example usage: Display a simple login form on a page
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="username">Username:</label><br>
  <input type="text" id="username" name="username" required><br>
  <label for="password">Password:</label><br>
  <input type="password" id="password" name="password" required><br><br>
  <button type="submit">Login</button>
</form>

<?php
// Close the database connection when done
$conn->close();
?>


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

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function loginUser($username, $password) {
    global $conn;

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Hashed password comparison
        if (password_verify($password, $user['password'])) {
            return true; // Login successful
        }
    }

    return false; // Invalid credentials
}

// Example usage:
$login_result = loginUser($_POST['username'], $_POST['password']);

if ($login_result) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}
?>


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

// Configuration settings
$hostname = 'your_host';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

// Create connection to database
$conn = new mysqli($hostname, $username, $password, $dbname);

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($email, $password) {
    // Hash password (just for demonstration purposes)
    $hashed_password = md5($password);

    // SQL query to select user from database
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$hashed_password'";
    
    // Execute query and store result
    $result = $conn->query($query);

    // Check for errors
    if (!$result) {
        die("Error: " . $conn->error);
    }

    // Fetch data from result
    $data = $result->fetch_assoc();

    // If user exists, return username and email
    if ($data) {
        return array(
            'username' => $data['username'],
            'email' => $data['email']
        );
    } else {
        return null;
    }
}

// Example usage:
$email = 'example@example.com';
$password = 'password';

$user_data = login_user($email, $password);

if ($user_data) {
    echo "Login successful! Username: {$user_data['username']}, Email: {$user_data['email']}";
} else {
    echo "Invalid email or password.";
}

// Close database connection
$conn->close();

?>


// Configuration settings
$hostname = 'your_host';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

// Create connection to database
$conn = new mysqli($hostname, $username, $password, $dbname);

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($email, $password) {
    // SQL query using prepared statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $hashed_password);

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Execute query
    $stmt->execute();

    // Fetch data from result
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    // If user exists, return username and email
    if ($data) {
        return array(
            'username' => $data['username'],
            'email' => $data['email']
        );
    } else {
        return null;
    }
}

// Example usage:
$email = 'example@example.com';
$password = 'password';

$user_data = login_user($email, $password);

if ($user_data) {
    echo "Login successful! Username: {$user_data['username']}, Email: {$user_data['email']}";
} else {
    echo "Invalid email or password.";
}

// Close database connection
$conn->close();

?>


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

