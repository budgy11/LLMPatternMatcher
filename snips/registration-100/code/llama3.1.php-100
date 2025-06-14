
<?php
require_once 'db.php'; // assume you have a db.php file for database connection

function registerUser($name, $email, $password) {
  // sanitize input
  $name = strip_tags(trim($name));
  $email = filter_var($email, FILTER_VALIDATE_EMAIL);
  $password = password_hash($password, PASSWORD_DEFAULT);

  // prepare query to insert user into database
  $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $password);

  // execute query and get last insert id
  try {
    $stmt->execute();
    $userId = $pdo->lastInsertId();

    return array('success' => true, 'message' => 'User registered successfully!', 'userId' => $userId);
  } catch (PDOException $e) {
    return array('success' => false, 'message' => 'Error registering user: ' . $e->getMessage());
  }
}

// example usage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $result = registerUser($name, $email, $password);
  echo json_encode($result);
}
?>


<?php
$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
  $pdo = new PDO($dsn, $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo 'Connection failed: ' . $e->getMessage();
}
?>


<?php

// Configuration
require_once 'config.php'; // Your config file with database credentials

function registerUser($username, $email, $password) {
  global $pdo; // Use your PDO instance from the config file

  try {
    // Create a new user in the users table
    $stmt = $pdo->prepare('INSERT INTO users (username, email) VALUES (:username, :email)');
    $stmt->execute([':username' => $username, ':email' => $email]);

    // Get the new user's ID
    $newUserId = $pdo->lastInsertId();

    // Hash the password and store it in the user_credentials table
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO user_credentials (user_id, password_hash) VALUES (:userId, :passwordHash)');
    $stmt->execute([':userId' => $newUserId, ':passwordHash' => $passwordHash]);

    return true; // Registration successful
  } catch (PDOException $e) {
    echo "Error registering user: " . $e->getMessage();
    return false;
  }
}

// Example usage:
$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

if (registerUser($username, $email, $password)) {
  echo "User registered successfully!";
} else {
  echo "Registration failed.";
}


<?php

// Configuration
$db_host = 'localhost';
$db_user = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Function to register a new user
function registerUser($username, $email, $password) {
  // Validate input
  if (empty($username) || empty($email) || empty($password)) {
    return array('success' => false, 'message' => 'All fields are required');
  }

  // Connect to database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Encrypt password
  $password = md5($password);

  // Prepare SQL query
  $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $email, $password);

  // Execute query
  if ($stmt->execute()) {
    return array('success' => true, 'message' => 'User registered successfully');
  } else {
    return array('success' => false, 'message' => 'Error registering user');
  }

  // Close connection
  $conn->close();
}

?>


<?php

// Include register function
include_once('register.php');

// Registration form data
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// Call registerUser function
$result = registerUser($username, $email, $password);

// Output result
if ($result['success']) {
  echo json_encode($result);
} else {
  echo json_encode(array('success' => false, 'message' => $result['message']));
}

?>


function registerUser($username, $email, $password) {
    // Define the database connection details (replace with your own)
    $dbHost = 'localhost';
    $dbUsername = 'your_username';
    $dbPassword = 'your_password';
    $dbName = 'your_database';

    try {
        // Connect to the database
        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);

        // Hash the password (using a secure method such as bcrypt)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the SQL query to insert the user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the query and check for errors
        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception('Database error: ' . $conn->errorInfo()[2]);
        }
    } catch (PDOException $e) {
        echo "Error connecting to database: " . $e->getMessage();
        return false;
    }
}

// Example usage
$username = 'john_doe';
$email = 'johndoe@example.com';
$password = 'password123';

if (registerUser($username, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Error registering user";
}


<?php

function registerUser($username, $email, $password) {
    // Database connection
    $conn = new mysqli("localhost", "username", "password", "database");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute SQL query to check for existing username or email
    $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return false; // Return False on duplicate username or email
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if ($conn->query($sql) === TRUE) {
        return true; // Return True on successful registration
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return false;
    }

    $conn->close();
}

// Example usage:
$success = registerUser("newuser", "newuser@example.com", "password123");
echo "Registration successful: " . ($success ? 'true' : 'false');

?>


<?php

// Database connection settings
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

// Create a database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser($username, $email, $password, $confirmPassword)
{
    // Validate input data
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        return array('error' => 'All fields are required.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address.');
    }

    if ($password !== $confirmPassword) {
        return array('error' => 'Passwords do not match.');
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute query to insert new user into database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    $result = $stmt->execute();

    // Check if the registration was successful
    if ($result === TRUE) {
        return array('success' => 'User registered successfully.');
    } else {
        return array('error' => 'Error registering user. Please try again.');
    }
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];

$result = registerUser($username, $email, $password, $confirmPassword);

// Output the result
if (isset($result['error'])) {
    echo 'Error: ' . $result['error'];
} elseif (isset($result['success'])) {
    echo 'Success: ' . $result['success'];
}

?>


<?php

// Database connection settings
$db_host = 'localhost';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Create database connection
function db_connect() {
    global $db_host, $db_username, $db_password, $db_name;
    try {
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
        return $conn;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}

// User registration function
function register_user($username, $email, $password) {
    // Check if user already exists
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if ($stmt->fetch()) {
        return 'User already exists';
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    if ($stmt->execute()) {
        return 'User registered successfully';
    } else {
        return 'Error registering user';
    }
}

// Example usage
$username = 'newuser';
$email = 'newuser@example.com';
$password = 'password123';

echo register_user($username, $email, $password);

?>


<?php

// Configuration settings
$minPasswordLength = 8;
$maxUsernameLength = 50;

function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username)) {
        throw new Exception('Username is required');
    }
    if (empty($email)) {
        throw new Exception('Email address is required');
    }
    if (empty($password)) {
        throw new Exception('Password is required');
    }
    if (strlen($username) > $maxUsernameLength || strlen($username) < 3) {
        throw new Exception('Username must be between 3 and ' . $maxUsernameLength . ' characters long');
    }
    if (strlen($password) < $minPasswordLength) {
        throw new Exception('Password must be at least ' . $minPasswordLength . ' characters long');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into database
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    if ($stmt->execute()) {
        return true;
    } else {
        throw new Exception('Failed to register user');
    }
}

?>


try {
    registerUser($_POST['username'], $_POST['email'], $_POST['password']);
    echo "User registered successfully";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<?php

// Configuration variables
define('DB_HOST', 'your_database_host');
define('DB_USER', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

function registerUser($data) {
    // Validate input data
    $errors = validateData($data);
    if (!empty($errors)) {
        return $errors;
    }

    // Hash password
    $passwordHash = hashPassword($data['password']);

    // Insert user into database
    try {
        $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->execute();

        // Return success message
        return 'User created successfully!';
    } catch (PDOException $e) {
        // Handle database error
        return 'Error creating user: ' . $e->getMessage();
    }
}

// Function to validate input data
function validateData($data) {
    $errors = array();

    if (!isset($data['username']) || empty($data['username'])) {
        $errors[] = 'Username is required';
    }

    if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address';
    }

    if (!isset($data['password']) || strlen($data['password']) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    }

    return $errors;
}

// Function to hash password
function hashPassword($password) {
    return crypt($password, '$2y$10$' . random_bytes(22));
}

?>


$data = array(
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
);

$result = registerUser($data);
if (is_array($result)) {
    // Display errors to user
} else {
    echo $result; // User created successfully!
}


<?php

// Configuration variables
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'your_database_name';

// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    // Get form data
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate form data
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email address";
            return false;
        }

        if (strlen($password) < 8 || strlen($username) < 3) {
            echo "Username must be at least 3 characters long and password at least 8 characters long.";
            return false;
        }

        // Hash and salt the password
        $salt = 'your_salt_value'; // Replace with a secure random value
        $hashedPassword = hash('sha256', $password . $salt);

        // Prepare SQL query to insert new user
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("sss", $username, $email, $hashedPassword);
            if ($stmt->execute()) {
                echo "User successfully registered.";
                return true;
            } else {
                echo "Error: " . $stmt->error;
                return false;
            }
        }

        // Close statement
        $stmt->close();
    }

    return false;
}

// Check if form has been submitted and call registerUser function
if (isset($_POST['register'])) {
    registerUser();
} else {
    echo "Please submit the registration form.";
}
?>


<?php

// Database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a new PDO instance
$dsn = "mysql:host=$db_host;dbname=$db_name";
$conn = new PDO($dsn, $db_username, $db_password);

function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username)) {
        throw new Exception('Username cannot be empty');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }
    if (strlen($password) < 8) {
        throw new Exception('Password must be at least 8 characters long');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into database
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        throw new Exception('Error registering user: ' . $e->getMessage());
    }
}

?>


try {
    registerUser('johnDoe', 'johndoe@example.com', 'mysecretpassword');
} catch (Exception $e) {
    echo 'Error registering user: ' . $e->getMessage();
}


function registerUser($data) {
  // Input validation
  if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
    throw new Exception("All fields are required.");
  }

  if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Invalid email address.");
  }

  // Password hashing
  $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

  // Database insertion
  $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
  $stmt = db()->prepare($query);
  $stmt->execute([
    ':username' => $data['username'],
    ':email' => $data['email'],
    ':password' => $hashedPassword
  ]);

  // Return the newly inserted user ID
  return db()->lastInsertId();
}


$data = [
  'username' => 'johnDoe',
  'email' => 'johndoe@example.com',
  'password' => 'password123'
];

try {
  $userId = registerUser($data);
  echo "User registered successfully with ID: $userId";
} catch (Exception $e) {
  echo "Error registering user: " . $e->getMessage();
}


/**
 * Register a new user.
 *
 * @param string $username  The desired username for the account.
 * @param string $email     The email address to associate with the account.
 * @param string $password  The password for the account (will be hashed).
 */
function registerUser(string $username, string $email, string $password): bool
{
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new InvalidArgumentException('All fields are required.');
    }

    try {
        // Hash the password before storing it in the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Connect to the database and insert the user data
        $dbConnection = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');
        $stmt = $dbConnection->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Return true to indicate a successful registration
        return true;
    } catch (PDOException $e) {
        // Handle any database-related errors
        error_log('Error registering user: ' . $e->getMessage());
        return false;
    }
}


try {
    $success = registerUser('newuser', 'newuser@example.com', 'password123');
    if ($success) {
        echo "User registered successfully.";
    } else {
        echo "Failed to register user.";
    }
} catch (InvalidArgumentException $e) {
    // Handle any validation errors
    echo "Error: " . $e->getMessage();
}


<?php

// Configuration settings for the database connection
$dsn = 'mysql:host=localhost;dbname=users';
$username = 'root';
$password = '';

try {
    // Establish a connection to the database
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// User registration function
function registerUser($name, $email, $password, $confirmPassword)
{
    // Validate user input
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        throw new Exception('Please fill in all fields.');
    }

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address.');
    }

    // Check if password and confirmation password match
    if ($password !== $confirmPassword) {
        throw new Exception('Passwords do not match.');
    }

    // Hash the password using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Prepare and execute an INSERT query to store the new user's information
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the query
        $stmt->execute();

        // Return a success message
        return 'User registered successfully.';
    } catch (PDOException $e) {
        throw new Exception('Error registering user: ' . $e->getMessage());
    }
}

?>


require_once 'UserRegistration.php';

try {
    // Call the registerUser function with user input
    $result = registerUser('John Doe', 'johndoe@example.com', 'mysecretpassword', 'mysecretpassword');

    // Display a success message or redirect to a confirmation page
    echo $result;
} catch (Exception $e) {
    // Handle any validation errors that occurred during registration
    echo 'Error registering user: ' . $e->getMessage();
}


<?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "users";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }

  function prepare_query($query, $params = array()) {
    global $conn;
    return $conn->prepare($query);
  }
?>


<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the user input
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Validate the user input
  if (empty($username) || empty($email) || empty($password)) {
    echo "Please fill in all fields";
  } else {
    try {
      // Check for duplicate usernames and emails
      $stmt = prepare_query("SELECT * FROM users WHERE username = ? OR email = ?");
      $stmt->execute(array($username, $email));
      $count = $stmt->rowCount();
      if ($count > 0) {
        echo "Username or Email already exists";
      } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = prepare_query("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute(array($username, $email, $hashed_password));
        echo "User registered successfully";
      }
    } catch (PDOException $e) {
      echo "Error registering user: " . $e->getMessage();
    }
  }
} else {
  // Display the registration form
?>
<form action="" method="post">
  <label>Username:</label>
  <input type="text" name="username"><br><br>
  <label>Email:</label>
  <input type="email" name="email"><br><br>
  <label>Password:</label>
  <input type="password" name="password"><br><br>
  <input type="submit" value="Register">
</form>
<?php
}
?>


<?php

// Function to register new users
function registerUser($name, $email, $password, $confirmPassword) {
    // Validate input fields
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        return array("success" => false, "error" => "All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array("success" => false, "error" => "Invalid email address.");
    }

    if ($password != $confirmPassword) {
        return array("success" => false, "error" => "Passwords do not match.");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Store user in database (e.g., using PDO or a library like doctrine)
    try {
        // Connect to database and insert new user record
        // For example:
        //   $db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        //   $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        //   $stmt->bindParam(":name", $name);
        //   $stmt->bindParam(":email", $email);
        //   $stmt->bindParam(":password", $hashedPassword);
        //   $stmt->execute();

        // For demonstration purposes, return a success message
        return array("success" => true, "message" => "User registered successfully.");
    } catch (PDOException $e) {
        return array("success" => false, "error" => "Failed to register user: " . $e->getMessage());
    }
}

// Example usage:
$name = "John Doe";
$email = "john@example.com";
$password = "mysecretpassword";
$confirmPassword = "mysecretpassword";

$result = registerUser($name, $email, $password, $confirmPassword);

if ($result["success"]) {
    echo "Registration successful!";
} else {
    echo "Error: " . $result["error"];
}

?>


<?php

// Configuration settings
$dbHost = 'localhost';
$dbName = 'database_name';
$dbUsername = 'username';
$dbPassword = 'password';

function registerUser($name, $email, $password) {
    // Connect to database
    try {
        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Input validation
        if (empty($name)) {
            throw new Exception('Name is required');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address');
        }
        if (strlen($password) < 8) {
            throw new Exception('Password must be at least 8 characters long');
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return true; // User registration successful
        } else {
            throw new Exception('Failed to register user');
        }

    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}

// Example usage:
try {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (registerUser($name, $email, $password)) {
        echo 'Registration successful!';
    } else {
        echo 'Failed to register user';
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

?>


function registerUser($username, $email, $password, $confirmPassword) {
    // Check if all fields are filled out
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        throw new Exception('All fields must be filled out.');
    }

    // Check if password and confirm password match
    if ($password !== $confirmPassword) {
        throw new Exception('Passwords do not match.');
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Create a new user
    try {
        // Assume we have a database connection established and a table called "users" with fields: id, username, email, hashed_password
        $query = "INSERT INTO users (username, email, hashed_password) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        $stmt->execute();

        // Return the new user's ID
        return $mysqli->insert_id;
    } catch (Exception $e) {
        throw new Exception('Failed to create new user: ' . $e->getMessage());
    }
}


try {
    $userId = registerUser($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password']);
    echo "New user created with ID: $userId";
} catch (Exception $e) {
    echo "Error registering new user: " . $e->getMessage();
}


// User class to store user data
class User {
    public $id;
    public $username;
    public $email;
    public $password;

    // Constructor to initialize user data
    public function __construct($id, $username, $email, $password) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = hash('sha256', $password);
    }
}

// Function to register a new user
function registerUser($data) {
    // Validate input data
    if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
        return array('success' => false, 'message' => 'Please fill in all fields');
    }

    $existingUsers = getUsers();

    foreach ($existingUsers as $user) {
        // Check if username or email already exists
        if ($user->username == $data['username'] || $user->email == $data['email']) {
            return array('success' => false, 'message' => 'Username or email already taken');
        }
    }

    // Register new user
    $newUser = new User(null, $data['username'], $data['email'], $data['password']);
    array_push($existingUsers, $newUser);

    return array('success' => true, 'message' => 'Registration successful');
}

// Function to get all existing users (in-memory database)
function getUsers() {
    // In a real application, this would be replaced with a database query
    $users = array(
        new User(1, 'john', 'john@example.com', 'password123'),
        new User(2, 'jane', 'jane@example.com', 'password456')
    );

    return $users;
}

// Example usage:
$data = array('username' => 'newuser', 'email' => 'newuser@example.com', 'password' => 'newpassword');

$result = registerUser($data);

if ($result['success']) {
    echo "Registration successful!";
} else {
    echo "Error: " . $result['message'];
}


<?php

// Database connection settings
$host = 'your_host';
$dbname = 'your_database';
$username = 'your_username';
$password = 'your_password';

// Create a PDO object to connect to the database
try {
    $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

function registerUser($name, $email, $password, $confirm_password)
{
    // Validate user input
    if ($name == '' || $email == '' || $password == '') {
        return array('error' => 'Please fill in all fields');
    }
    
    if (strlen($password) < 8) {
        return array('error' => 'Password must be at least 8 characters long');
    }
    
    if ($password != $confirm_password) {
        return array('error' => 'Passwords do not match');
    }

    // Hash the password for security
    $hashed_password = hash('sha256', $password);

    // Prepare and execute SQL query to insert user data into database
    try {
        $sql = 'INSERT INTO your_table (name, email, password) VALUES (:name, :email, :password)';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            return array('success' => 'User registered successfully');
        } else {
            return array('error' => 'Failed to register user');
        }
    } catch (PDOException $e) {
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}

// Example usage:
$name = 'John Doe';
$email = 'john@example.com';
$password = 'password123';
$confirm_password = 'password123';

$result = registerUser($name, $email, $password, $confirm_password);
if (isset($result['error'])) {
    echo 'Error: ' . $result['error'];
} else {
    echo 'Success: ' . $result['success'];
}

?>


function registerUser($username, $email, $password) {
    // Check if the username and email are not empty
    if (empty($username) || empty($email)) {
        return array('error' => 'Username and Email are required');
    }

    // Hash the password using MD5 or a more secure hash like bcrypt
    $hashedPassword = md5($password);

    // Connect to the database
    $conn = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');

    try {
        // Insert user data into the users table
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return array('message' => 'User registered successfully');
        } else {
            return array('error' => 'Error registering user');
        }
    } catch (PDOException $e) {
        return array('error' => 'Database error: ' . $e->getMessage());
    }

    // Close the database connection
    $conn = null;
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

$result = registerUser($username, $email, $password);

if ($result['error']) {
    echo $result['error'];
} else {
    echo $result['message'];
}


<?php

// Configuration
$host = 'localhost';
$dbname = 'users';
$username = 'root';
$password = '';

// Connect to the database
function connectToDb() {
    global $host, $dbname, $username, $password;
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        return $conn;
    } catch (PDOException $e) {
        echo "Error connecting to database: " . $e->getMessage();
        exit;
    }
}

// Register a user
function registerUser($username, $email, $password) {
    global $host, $dbname, $username, $password;

    // Check for valid input
    if (empty($username) || empty($email) || empty($password)) {
        return array(false, "Please enter all fields.");
    }

    // Check if username already exists
    $conn = connectToDb();
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    if ($stmt->fetch()) {
        return array(false, "Username is already taken.");
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if ($stmt->fetch()) {
        return array(false, "Email is already registered.");
    }

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $passwordHash);
    try {
        $stmt->execute();
        return array(true, "User registered successfully.");
    } catch (PDOException $e) {
        echo "Error registering user: " . $e->getMessage();
        exit;
    }
}

// Example usage
$registerResult = registerUser('johnDoe', 'johndoe@example.com', 'password123');
echo json_encode($registerResult);

?>


function registerUser($username, $email, $password)
{
    // Validate input data (basic example)
    if (!$username || !$email || !$password) {
        throw new Exception('All fields are required');
    }

    // Hash password using a secure method (e.g., bcrypt)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database
    $conn = mysqli_connect("localhost", "username", "password", "database");

    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    // Prepare and execute query
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);
    mysqli_stmt_execute($stmt);

    if (!mysqli_stmt_affected_rows($stmt)) {
        throw new Exception('Registration failed');
    }

    // Close database connection
    mysqli_close($conn);

    return true;
}


try {
    registerUser('johnDoe', 'johndoe@example.com', 'password123');
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}


<?php

// Configuration settings
$minPasswordLength = 8;
$maxUsernameLength = 50;

function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    // Validate username length
    if (strlen($username) > $maxUsernameLength || strlen($username) < 3) {
        return array('error' => 'Username must be between 3 and ' . $maxUsernameLength . ' characters long');
    }

    // Validate password strength
    if (strlen($password) < $minPasswordLength) {
        return array('error' => 'Password must be at least ' . $minPasswordLength . ' characters long');
    }

    try {
        // Connect to database
        $conn = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');

        // Check if username is unique
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return array('error' => 'Username already exists');
        }

        // Insert new user into database
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashPassword);
        $stmt->execute();

        // Return success message
        return array('success' => 'User registered successfully');
    } catch (PDOException $e) {
        // Handle database error
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}

?>


require_once 'register_user.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$response = registerUser($username, $email, $password);

if ($response['success']) {
    echo 'User registered successfully!';
} else {
    echo '<pre>' . print_r($response) . '</pre>';
}


function registerUser($username, $email, $password) {
  // Input validation
  if (empty($username)) {
    throw new Exception("Username is required");
  }
  if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
    throw new Exception("Invalid username");
  }
  if (empty($email)) {
    throw new Exception("Email is required");
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Invalid email");
  }

  // Password hashing
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Database interaction
  try {
    $conn = new PDO('mysql:host=localhost;dbname=your_database_name', 'username', 'password');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();
    $conn = null;
  } catch (PDOException $e) {
    throw new Exception("Database error: " . $e->getMessage());
  }

  return true;
}


try {
  registerUser('johnDoe', 'johndoe@example.com', 'password123');
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}


<?php

// Configuration
$dsn = 'mysql:host=localhost;dbname=mydb';
$username = 'myuser';
$password = 'mypassword';

try {
    // Connect to database
    $pdo = new PDO($dsn, $username, $password);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Process form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate user input
        if (empty($name) || empty($email) || empty($password)) {
            echo "Please fill out all fields.";
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email address.";
            exit;
        }

        // Hash password for secure storage
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Insert user data into database
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

            if ($stmt->execute()) {
                echo "User created successfully!";
            } else {
                throw new PDOException('Error creating user.', 0, null);
            }
        } catch (PDOException $e) {
            // Handle database errors
            echo "An error occurred: " . $e->getMessage();
        }

    }

} catch (PDOException $e) {
    // Handle connection errors
    echo "Could not connect to the database.";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>

<form action="" method="post">

    <label for="name">Name:</label>
    <input type="text" id="name" name="name"><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>

    <button type="submit">Register</button>

</form>

</body>
</html>


<?php

// Configuration settings
$required_fields = array('username', 'email', 'password');
$error_messages = array();
$username = '';
$email = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Handle form submission
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Validate user input
  foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
      $error_messages[] = 'Please fill out the required field: ' . $field;
    }
  }

  // Check for valid email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_messages[] = 'Invalid email address';
  }

  // Check for password requirements
  if (strlen($password) < 8 || !preg_match('/[a-zA-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
    $error_messages[] = 'Password must be at least 8 characters, contain a letter and a number';
  }

  // Check for duplicate username
  require_once 'database.php'; // Include your database connection file
  $query = "SELECT * FROM users WHERE username = :username";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  if ($stmt->fetch()) {
    $error_messages[] = 'Username already taken';
  }

  // If no errors, create new user
  if (empty($error_messages)) {
    try {
      $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
      $stmt = $pdo->prepare($query);
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
      $stmt->execute();
      echo 'User created successfully!';
    } catch (PDOException $e) {
      echo 'Error creating user: ' . $e->getMessage();
    }
  }
}

?>

<!-- Display form -->
<form action="" method="post">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username"><br><br>
  <label for="email">Email:</label>
  <input type="email" id="email" name="email"><br><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password"><br><br>
  <?php if (!empty($error_messages)): ?>
    <ul style="color: red;">
      <?php foreach ($error_messages as $message): ?>
        <li><?php echo $message; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
  <button type="submit">Register</button>
</form>



<?php

$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

?>


function registerUser($username, $email, $password) {
    // Validate username and email
    if (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
        return array("error" => "Invalid username");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array("error" => "Invalid email address");
    }

    // Validate password (minimum 8 characters)
    if (strlen($password) < 8) {
        return array("error" => "Password must be at least 8 characters long");
    }

    try {
        // Connect to database
        $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->execute();

        // Return success message
        return array("message" => "User created successfully");

    } catch (PDOException $e) {
        return array("error" => "Database error: " . $e->getMessage());
    }
}


// User input
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];

// Call the function
$result = registerUser($username, $email, $password);

// Display result to user
if (isset($result["error"])) {
    echo "Error: " . $result["error"];
} else {
    echo "Success: " . $result["message"];
}


<?php

// Configuration variables
$db_host = 'localhost';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to register user
function registerUser($username, $email, $password)
{
    // Hash password using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert into database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error: " . $stmt->error;
        return false;
    }
}

// Example usage:
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if username and email are already taken
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username or email already taken.";
    } else {
        // Register user
        registerUser($username, $email, $password);

        // Redirect to login page
        header("Location: login.php");
        exit();
    }
}

?>


<?php

function registerUser($db, $username, $password, $email) {
    // Hash the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Create a SQL query to insert the user's data into the database
    $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";

    try {
        // Prepare and execute the query
        $stmt = $db->prepare($query);
        $stmt->execute([
            ":username" => $username,
            ":email" => $email,
            ":password" => $hashedPassword
        ]);

        // If the user is successfully registered, return true
        return true;
    } catch (PDOException $e) {
        // If there's an error in the database query, print it and return false
        echo "Error registering user: " . $e->getMessage() . "
";
        return false;
    }
}

// Example usage:
$db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

if (registerUser($db, $username, $password, $email)) {
    echo "User successfully registered!";
} else {
    echo "Error registering user.";
}

?>


<?php

// Configuration settings for database connection and other security settings
$database = 'users';
$username = 'your_database_username';
$password = 'your_database_password';

function register_user($name, $email, $password) {
  // Connect to the database
  $conn = new mysqli($GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Hash the password for security
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sss", $name, $email, $hashed_password);

  if ($stmt->execute()) {
    return true; // Registration successful
  } else {
    return false; // Registration failed (likely due to duplicate email or other error)
  }

  $conn->close();
}

?>


// Call the register_user function with user input
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

if (register_user($name, $email, $password)) {
  echo "Registration successful!";
} else {
  echo "Registration failed.";
}


// Database configuration
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


function registerUser($name, $email, $password)
{
    // Validate input data
    if (empty($name) || empty($email) || empty($password)) {
        return array('status' => 'error', 'message' => 'All fields are required');
    }

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('status' => 'error', 'message' => 'Invalid email address');
    }

    // Hash password
    $passwordHash = hash('sha256', $password);

    // Prepare SQL query to insert new user into database
    try {
        $query = "INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);

        // Execute query and return result
        if ($stmt->execute()) {
            return array('status' => 'success', 'message' => 'User registered successfully');
        } else {
            return array('status' => 'error', 'message' => 'Failed to register user');
        }
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => 'Database error: ' . $e->getMessage());
    }
}


$name = "John Doe";
$email = "john@example.com";
$password = "password123";

$result = registerUser($name, $email, $password);

if ($result['status'] == 'success') {
    echo "User registered successfully!";
} else {
    echo "Error: " . $result['message'];
}


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function registerUser($username, $email, $password) {
    // Database connection
    $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
    
    // Sanitize input
    $username = htmlspecialchars($username);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    
    // Check if username is available
    $stmt = $conn->prepare('SELECT * FROM users WHERE username=:username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    if ($stmt->fetch()) {
        return array('error' => 'Username already exists');
    }
    
    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Create user
    try {
        $query = 'INSERT INTO users (username, email, password) VALUES (:username, :email, :password)';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->execute();
        
        // Return success message
        return array('success' => 'User created successfully');
    } catch (PDOException $e) {
        // Handle database errors
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}

?>


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if ($result['success']) {
    echo 'User created successfully!';
} elseif ($result['error']) {
    echo 'Error: ' . $result['error'];
}


function registerUser($username, $email, $password, &$mysqli)
{
    // Input validation
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("All fields are required.");
    }

    // Check for duplicate usernames and emails
    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        throw new Exception("Username or Email already exists.");
    }

    // Password hashing
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $query = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $username, $email, $passwordHash);
    $stmt->execute();

    // Generate and store a verification token
    $verificationToken = bin2hex(random_bytes(32));
    $query = "UPDATE users SET verified_at = NOW(), verification_token = ? WHERE id = LAST_INSERT_ID()";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $verificationToken);
    $stmt->execute();

    return [
        'id' => $mysqli->insert_id,
        'username' => $username,
        'email' => $email,
        'verificationToken' => $verificationToken
    ];
}

// Example usage:
$mysqli = new mysqli('localhost', 'username', 'password', 'database');

try {
    $result = registerUser('exampleuser', 'example@example.com', 'password123');
    echo "Registration successful! Verification token: " . $result['verificationToken'] . "
";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "
";
}

$mysqli->close();


use PHPMailer\PHPMailer\PHPMailer;

// ...

$mail = new PHPMailer(true);
$mail->isSMTP();

$mail->setFrom('no-reply@example.com', 'Example');
$mail->addAddress($email);

$mail->Subject = "Verify your account";
$mail->Body = "Click here to verify your email: <a href='http://example.com/verify?token=" . $verificationToken . "'>Verify Email</a>";

$mail->send();


function registerUser($firstName, $lastName, $email, $password) {
    // Validate input
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        throw new Exception('All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    if (strlen($password) < 8) {
        throw new Exception('Password must be at least 8 characters long');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    try {
        $db = new PDO('sqlite:user_database.db');
        $stmt = $db->prepare('INSERT INTO users (first_name, last_name, email, password) VALUES (:firstName, :lastName, :email, :hashedPassword)');
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':hashedPassword', $hashedPassword);
        $stmt->execute();

        return 'User registered successfully';
    } catch (PDOException $e) {
        throw new Exception('Error registering user: ' . $e->getMessage());
    }
}


try {
    echo registerUser('John', 'Doe', 'john@example.com', 'password123');
} catch (Exception $e) {
    echo $e->getMessage();
}


<?php

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

// Function to register new user
function registerUser($username, $email, $password)
{
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array(
            'status' => 'error',
            'message' => 'Please fill in all fields.'
        );
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array(
            'status' => 'error',
            'message' => 'Invalid email address.'
        );
    }

    // Hash password using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute query
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return array(
                'status' => 'success',
                'message' => 'User registered successfully.'
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'Failed to register user.'
            );
        }
    } catch (PDOException $e) {
        return array(
            'status' => 'error',
            'message' => 'Database connection failed: ' . $e->getMessage()
        );
    } finally {
        // Close database connection
        if ($conn !== null) {
            $conn = null;
        }
    }
}

// Example usage:
$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

$result = registerUser($username, $email, $password);

if ($result['status'] === 'success') {
    echo $result['message'];
} else {
    echo $result['message'];
}

?>


function registerUser($username, $email, $password) {
    // Input Validation
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields');
    }

    // Password validation
    if (strlen($password) < 8) {
        return array('error' => 'Password must be at least 8 characters long');
    }

    try {
        // Connect to database
        $conn = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');

        // Insert user data into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute query
        $stmt->execute();

        return array('success' => 'User registered successfully');
    } catch (PDOException $e) {
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

$result = registerUser($username, $email, $password);

if ($result['success']) {
    echo 'User registered successfully!';
} else {
    echo 'Error: ' . $result['error'];
}


<?php

// Configuration constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'users_db');

function registerUser($name, $email, $password) {
  // Validate user input data
  if (empty($name) || empty($email) || empty($password)) {
    throw new Exception('All fields are required.');
  }

  // Check for valid email address format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception('Invalid email address format.');
  }

  // Hash the password using SHA-256
  $hashedPassword = hash('sha256', $password);

  // Connect to database and insert user data
  $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
  try {
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    // Return true on successful registration
    return true;
  } catch (PDOException $e) {
    throw new Exception('Database error: ' . $e->getMessage());
  }
}

?>


try {
  $registered = registerUser($_POST['name'], $_POST['email'], $_POST['password']);
  if ($registered) {
    echo "User registered successfully!";
  } else {
    throw new Exception('Registration failed.');
  }
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}


<?php

// Configuration constants
define('DB_HOST', 'your_database_host');
define('DB_USERNAME', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Connect to the database
function connectToDatabase() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}

// Registration function
function registerUser($username, $email, $password) {
    // Check if user already exists
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo "User already exists!";
        return false;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    if ($stmt->execute()) {
        echo "User registered successfully!";
        return true;
    } else {
        echo "Registration failed.";
        return false;
    }
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

if (registerUser($username, $email, $password)) {
    echo "User successfully registered!";
} else {
    echo "Registration failed.";
}


function registerUser($data) {
    $requiredFields = ['username', 'email', 'password'];
    $userData = [];

    foreach ($data as $key => $value) {
        if (in_array($key, $requiredFields)) {
            $userData[$key] = $value;
        }
    }

    // Validate input data
    if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    if (strlen($userData['password']) < 8) {
        throw new Exception('Password must be at least 8 characters long');
    }

    // Hash password
    $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);

    // Insert user into database
    try {
        $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $userData['username']);
        $stmt->bindParam(':email', $userData['email']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception('Error inserting user into database: ' . $e->getMessage());
    }

    return true;
}


$data = [
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
];

if (registerUser($data)) {
    echo "User registered successfully!";
} else {
    echo "Error registering user.";
}


<?php

/**
 * Register a new user.
 *
 * @param string $username Username chosen by the user.
 * @param string $email Email address chosen by the user.
 * @param string $password Password chosen by the user.
 *
 * @return bool|mixed False on failure, true on success and array of user data.
 */
function registerUser($username, $email, $password)
{
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        return 'Error: All fields are required.';
    }

    // Check if username is available
    global $db;
    $sql = "SELECT * FROM users WHERE username=:username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
        return 'Error: Username already taken.';
    }

    // Check if email is available
    $sql = "SELECT * FROM users WHERE email=:email";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
        return 'Error: Email already taken.';
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Create new user
    $sql = "INSERT INTO users (username, email, password)
            VALUES (:username, :email, :password)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $passwordHash);
    $stmt->execute();

    // Get the newly inserted user data
    $userId = $db->lastInsertId();
    $sql = "SELECT * FROM users WHERE id=:id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
    $user = $stmt->fetch();

    return true;
}

?>


// Set up your database connection
$db = new PDO('sqlite:database.db');

// Create a new user
$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

$result = registerUser($username, $email, $password);

if ($result === true) {
    echo "User registered successfully!";
} else {
    echo "Error registering user: " . $result;
}


<?php

// Function to register a new user
function registerUser($username, $password, $email) {
    // Validate input
    if (empty($username) || empty($password) || empty($email)) {
        return array('success' => false, 'message' => 'All fields are required.');
    }

    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        return array('success' => false, 'message' => 'Username can only contain letters and numbers.');
    }

    if (strlen($password) < 8 || !preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/', $password)) {
        return array('success' => false, 'message' => 'Password must be at least 8 characters long and contain a mix of letters and numbers.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('success' => false, 'message' => 'Invalid email address.');
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Create user data
    $user_data = array(
        'username' => $username,
        'email' => $email,
        'password' => $hashed_password
    );

    // Insert new user into database (example using PDO)
    try {
        // Replace with your actual database connection and query.
        $db = new PDO('sqlite:users.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindParam(':username', $user_data['username']);
        $stmt->bindParam(':email', $user_data['email']);
        $stmt->bindParam(':password', $user_data['password']);

        $stmt->execute();

        return array('success' => true, 'message' => 'User registered successfully.');
    } catch (PDOException $e) {
        echo 'Error creating user: ' . $e->getMessage();
        return array('success' => false, 'message' => 'Error creating user.');
    }
}

// Example usage
$username = 'johnDoe';
$password = 'P@ssw0rd1234';
$email = 'johndoe@example.com';

$result = registerUser($username, $password, $email);
print_r($result);

?>


class UserRegistration {
    private $db;

    public function __construct() {
        // Initialize database connection (e.g., MySQLi)
        $this->db = new mysqli('localhost', 'username', 'password', 'database');
    }

    /**
     * Registers a new user with the provided credentials.
     *
     * @param string  $name
     * @param string  $email
     * @param string  $password
     * @return bool   True if registration is successful, false otherwise.
     */
    public function registerUser($name, $email, $password) {
        // Validate input fields
        if (!$this->validateInput($name, $email, $password)) {
            return false;
        }

        // Hash password for storage
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL query to insert new user data
        $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $name, $email, $hashedPassword);
        $result = $stmt->execute();

        // Check if query execution was successful
        return $result;
    }

    /**
     * Validates the input fields for registration.
     *
     * @param string  $name
     * @param string  $email
     * @param string  $password
     * @return bool   True if all fields are valid, false otherwise.
     */
    private function validateInput($name, $email, $password) {
        // Check for empty input fields
        if (empty($name) || empty($email) || empty($password)) {
            return false;
        }

        // Validate email address format using regular expression
        if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
            return false;
        }

        // Check password strength (e.g., minimum 8 characters)
        if (strlen($password) < 8) {
            return false;
        }

        return true;
    }
}


$userRegistration = new UserRegistration();
$name = "John Doe";
$email = "johndoe@example.com";
$password = "strongpassword123";

if ($userRegistration->registerUser($name, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Error registering user.";
}


function register_user($username, $email, $password) {
    // Hash password using bcrypt
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Create connection to database
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

    // Prepare SQL query to insert user data into database
    $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $db->prepare($query);

    // Bind parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    // Execute query
    try {
        $stmt->execute();
        echo "User registered successfully!";
    } catch (PDOException $e) {
        echo "Error registering user: " . $e->getMessage();
    }

    // Close database connection
    $db = null;
}


register_user('john_doe', 'johndoe@example.com', 'password123');


// Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

function registerUser($username, $email, $password) {
  // Validate input data
  if (empty($username) || empty($email) || empty($password)) {
    return array('error' => 'All fields are required');
  }

  // Check for duplicate username and email
  $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    return array('error' => 'Username or Email already exists');
  }

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
  mysqli_query($conn, $query);

  return array('success' => 'User created successfully');
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $result = registerUser($username, $email, $password);

  if (isset($result['error'])) {
    echo json_encode(array('error' => $result['error']));
  } else {
    echo json_encode($result);
  }
}

mysqli_close($conn);


<?php

function register_user($name, $email, $password) {
    // Check if the email already exists in the database
    $db = connect_to_db(); // assume this function connects to your database and returns a MySQLi object
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        echo "Email already exists.";
        return false;
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password_hash')";
    $db->query($query);
    return true;
}

function connect_to_db() {
    // Connect to your database
    $mysqli = new mysqli("localhost", "username", "password", "database_name");
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        exit();
    }
    return $mysqli;
}

// Example usage:
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

if (register_user($name, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Registration failed.";
}
?>


<?php

function register_user($data) {
    $db = connect_to_db();
    $query = "SELECT * FROM users WHERE email = ?";

    try {
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $email);
        $result = $stmt->execute();

        if ($result && $stmt->num_rows > 0) {
            throw new Exception("Email already exists.");
        }

        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];

        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sss", $name, $email, $password_hash);
        $result = $stmt->execute();

        if ($result) {
            return true;
        } else {
            throw new Exception("Registration failed.");
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Example usage:
$data = array(
    'name' => $_POST['name'],
    'email' => $_POST['email'],
    'password' => $_POST['password']
);

if (register_user($data)) {
    echo "User registered successfully!";
} else {
    echo "Registration failed.";
}
?>


<?php
// Configuration for database connection
$dbhost = 'localhost';
$dbuser = 'your_username';
$dbpass = 'your_password';
$dbname = 'your_database';

// Create a new database connection
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function register_user($email, $username, $password, $confirm_password) {
    // Validate input data
    if (empty($email) || empty($username) || empty($password)) {
        return array('error' => 'Please fill in all fields');
    }

    if ($password !== $confirm_password) {
        return array('error' => 'Passwords do not match');
    }

    // Hash password using PHP's built-in hash function
    $hashed_password = hash('sha256', $password);

    // SQL query to insert new user into database
    $sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
    
    try {
        // Prepare and execute the query
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception('Prepared statement failed');
        }
        
        mysqli_stmt_bind_param($stmt, 'sss', $email, $username, $hashed_password);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Query execution failed');
        }

        // Get the ID of the newly inserted user
        $new_id = mysqli_insert_id($conn);

        return array('success' => true, 'id' => $new_id, 'email' => $email, 'username' => $username);
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
        return array('error' => $e->getMessage());
    }
}

// Test the function with some data
$email = 'user@example.com';
$username = 'example_user';
$password = 'mysecretpassword';
$confirm_password = 'mysecretpassword';

$result = register_user($email, $username, $password, $confirm_password);

if (isset($result['error'])) {
    echo "Error: " . $result['error'];
} else {
    echo "User registered successfully!";
    print_r($result);
}
?>


error_reporting(E_ALL);


function registerUser($name, $email, $password) {
  // Validate input
  if (empty($name) || empty($email) || empty($password)) {
    throw new Exception("All fields are required.");
  }

  // Check if email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Invalid email address.");
  }

  // Hash password
  $hashedPassword = hash('sha256', $password);

  try {
    // Connect to database
    $conn = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypass');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute query
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    // Execute query
    $stmt->execute();

    // Return user ID
    return $conn->lastInsertId();
  } catch (PDOException $e) {
    // Handle database error
    echo "Database error: " . $e->getMessage() . "
";
    throw new Exception("Failed to register user.");
  }
}


try {
  $userId = registerUser('John Doe', 'johndoe@example.com', 'mysecretpassword');
  echo "User registered successfully. User ID: $userId
";
} catch (Exception $e) {
  echo "Error registering user: " . $e->getMessage() . "
";
}


<?php

function registerUser($firstName, $lastName, $email, $password) {
    // Check if email already exists
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
        // Email already exists, return error message
        return array('error' => 'Email already registered');
    }

    // Hash password using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user into database
    $query = "INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':first_name', $firstName);
    $stmt->bindParam(':last_name', $lastName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    // Return success message
    return array('success' => 'User registered successfully');
}

// Example usage:
$pdo = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');

$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($firstName, $lastName, $email, $password);

if ($result['success']) {
    echo "User registered successfully!";
} else {
    echo "Error: " . $result['error'];
}
?>


function registerUser($username, $email, $password) {
    // Connect to database (in this case, we'll use a fictional database for simplicity)
    $conn = new PDO('sqlite:user_database.db');

    // Check if username or email already exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch();

    // If user or email already exists, return an error
    if ($result) {
        return array('error' => 'Username or email already taken');
    }

    // Hash the password using PHP's built-in hash function (MD5 is deprecated for security reasons; use a more secure library like bcrypt)
    $hashedPassword = md5($password);

    // Insert new user into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    // Return the newly created user's ID
    return array('success' => true, 'id' => $conn->lastInsertId());
}

// Example usage:
$username = 'exampleuser';
$email = 'example@example.com';
$password = 'mysecretpassword';

$result = registerUser($username, $email, $password);

if ($result['error']) {
    echo 'Error: ' . $result['error'];
} else {
    echo 'User registered successfully! ID: ' . $result['id'];
}


<?php

// User database array (replace with a real database)
$users = [];

function register_user($username, $email, $password) {
  global $users;

  // Check if the username already exists
  foreach ($users as $user) {
    if ($user['username'] == $username) {
      return false;
    }
  }

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Create a new user array
  $new_user = [
    'username' => $username,
    'email' => $email,
    'password' => $hashed_password,
  ];

  // Add the new user to the database
  $users[] = $new_user;

  return true;
}

function login_user($username, $password) {
  global $users;

  // Find the user by username
  foreach ($users as $user) {
    if ($user['username'] == $username) {
      // Check the password
      if (password_verify($password, $user['password'])) {
        return true;
      }
    }
  }

  return false;
}

// Example usage:
$username = 'john_doe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

if (register_user($username, $email, $password)) {
  echo "User registered successfully!
";
} else {
  echo "Username already exists.
";
}

?>


$username = 'jane_doe';
$email = 'janedoe@example.com';
$password = 'mysecretpassword';

if (register_user($username, $email, $password)) {
  echo "User registered successfully!
";
} else {
  echo "Username already exists.
";
}


$username = 'john_doe';
$password = 'mysecretpassword';

if (login_user($username, $password)) {
  echo "Logged in successfully!
";
} else {
  echo "Invalid username or password.
";
}


<?php

// Configuration settings
$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
    // Connect to database
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Error connecting to database: ' . $e->getMessage();
    exit;
}

// User registration function
function register_user($email, $username, $password) {
    global $pdo;

    // Validate user input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }
    if (strlen($username) < 3 || strlen($username) > 32) {
        throw new Exception('Username must be between 3 and 32 characters long');
    }
    if (strlen($password) < 8 || !preg_match('/\d/', $password)) {
        throw new Exception('Password must be at least 8 characters long and contain a digit');
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    try {
        $stmt = $pdo->prepare('INSERT INTO users (email, username, password) VALUES (:email, :username, :password)');
        $stmt->execute([':email' => $email, ':username' => $username, ':password' => $hashed_password]);
        return true;
    } catch (PDOException $e) {
        echo 'Error inserting user into database: ' . $e->getMessage();
        return false;
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        if (register_user($email, $username, $password)) {
            echo 'User registered successfully!';
        } else {
            echo 'Error registering user. Please try again.';
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

?>


/**
 * User registration function.
 *
 * @param string $username The user's chosen username.
 * @param string $email The user's email address.
 * @param string $password The user's chosen password.
 * @return bool True if the registration is successful, false otherwise.
 */
function registerUser($username, $email, $password) {
  // Connect to database
  $mysqli = new mysqli("localhost", "username", "password", "database");

  // Check connection
  if ($mysqli->connect_errno) {
    echo "Connection failed: " . $mysqli->connect_error;
    return false;
  }

  // Hash password (using SHA-256 for simplicity)
  $hashedPassword = hash('sha256', $password);

  // Validate user input
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address";
    return false;
  }

  // Prepare and execute INSERT query
  $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $email, $hashedPassword);
  if (!$stmt->execute()) {
    echo "Failed to register user";
    return false;
  }

  // Close database connection
  $mysqli->close();

  return true;
}


// Set up your database credentials and table name here
$dbHost = "localhost";
$dbUsername = "username";
$dbPassword = "password";
$dbName = "database";

// Call the registration function with user input
$registered = registerUser("johnDoe", "johndoe@example.com", "mysecretpassword");
echo $registered ? "Registration successful!" : "Registration failed.";


<?php

// Configuration settings
require_once 'config.php';

function registerUser($username, $email, $password) {
    // Validate input data
    if (!validateUsername($username)) {
        return array('error' => 'Invalid username');
    }

    if (!validateEmail($email)) {
        return array('error' => 'Invalid email address');
    }

    if (!validatePassword($password)) {
        return array('error' => 'Invalid password');
    }

    try {
        // Hash and store the password securely
        $hashedPassword = hash('sha256', $password);

        // Insert new user into database
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Return success message
        return array('success' => 'User registered successfully');

    } catch (PDOException $e) {
        // Handle database error
        return array('error' => 'Error registering user: ' . $e->getMessage());
    }
}

function validateUsername($username) {
    // Simple username validation (6-20 characters, alphanumeric and underscores)
    if (strlen($username) >= 6 && strlen($username) <= 20) {
        if (ctype_alnum($username) || strpos($username, '_') !== false) {
            return true;
        }
    }

    return false;
}

function validateEmail($email) {
    // Simple email validation
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }

    return false;
}

function validatePassword($password) {
    // Simple password validation (8-20 characters, alphanumeric and special characters)
    if (strlen($password) >= 8 && strlen($password) <= 20) {
        if (ctype_alnum($password) || strpos($password, '_') !== false) {
            return true;
        }
    }

    return false;
}

// Example usage
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if ($result['success']) {
    echo 'User registered successfully!';
} elseif ($result['error']) {
    echo 'Error: ' . $result['error'];
}


function registerUser($username, $email, $password) {
    // Hash the password
    $hashedPassword = hash('sha256', $password);

    // Connect to database (assuming MySQL)
    $conn = new mysqli("localhost", "username", "password", "database");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if user already exists
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return false; // User already exists
    }

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    $conn->query($query);

    return true;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (registerUser($username, $email, $password)) {
        echo "User registered successfully!";
    } else {
        echo "Error: User already exists.";
    }
}


<?php
// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    exit("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}

// Function to register user
function registerUser($name, $email, $password, $confirm_password) {
    // Check if passwords match
    if ($password !== $confirm_password) {
        return array('success' => false, 'message' => 'Passwords do not match');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare query to insert user data into database
    $stmt = $mysqli->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if (!$stmt->bind_param("sss", $name, $email, $hashedPassword)) {
        return array('success' => false, 'message' => 'Error binding parameters');
    }

    // Execute query to insert user data into database
    if (!$stmt->execute()) {
        return array('success' => false, 'message' => 'Error registering user');
    }

    $user_id = $mysqli->insert_id;
    return array('success' => true, 'message' => 'User registered successfully', 'id' => $user_id);
}

// Get POST data from form submission
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Register user and output result
$result = registerUser($name, $email, $password, $confirm_password);
echo json_encode($result);

// Close database connection
$mysqli->close();
?>


function registerUser($firstName, $lastName, $email, $password, $confirmPassword) {
  // Validation checks
  if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
    throw new Exception("All fields are required");
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Invalid email address");
  }

  if ($password !== $confirmPassword) {
    throw new Exception("Passwords do not match");
  }

  // Hash the password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Store user data in database (example using PDO)
  try {
    $db = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
    $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:firstName, :lastName, :email, :hashedPassword)");
    $stmt->bindParam(':firstName', $firstName);
    $stmt->bindParam(':lastName', $lastName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':hashedPassword', $hashedPassword);
    $stmt->execute();

    return true;
  } catch (PDOException $e) {
    throw new Exception("Database error: " . $e->getMessage());
  }
}


try {
  $result = registerUser('John Doe', 'Jane Doe', 'johndoe@example.com', 'password123', 'password123');
  if ($result) {
    echo "User registered successfully!";
  } else {
    throw new Exception("Registration failed");
  }
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}


function register_user($username, $email, $password) {
    // Validation checks
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("All fields are required");
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address");
    }

    // Prepare data for database insertion
    $data = [
        'username' => $username,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ];

    try {
        // Connect to the database (replace with your own connection method)
        $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');

        // Prepare and execute query
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->execute($data);

        return true;
    } catch (PDOException $e) {
        echo "Error registering user: " . $e->getMessage();
        return false;
    }
}


try {
    register_user('johnDoe', 'johndoe@example.com', 'password123');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


function registerUser($data) {
    // Input validation and sanitization
    $username = filter_var($data['username'], FILTER_SANITIZE_STRING);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);

    if (!$username || !$password || !$email) {
        return array('success' => false, 'message' => 'Invalid input');
    }

    // Connect to database
    require_once 'db.php';
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert user into users table
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
    if (!$conn->query($sql)) {
        return array('success' => false, 'message' => 'Failed to create user');
    }

    // Generate session id and insert into sessions table
    $session_id = bin2hex(random_bytes(16));
    $sql = "INSERT INTO sessions (session_id, user_id) VALUES ('$session_id', LAST_INSERT_ID())";
    if (!$conn->query($sql)) {
        return array('success' => false, 'message' => 'Failed to create session');
    }

    // Close database connection
    $conn->close();

    // Return success message with new user details
    return array('success' => true, 'username' => $username, 'email' => $email);
}

// Example usage:
$data = array(
    'username' => 'john_doe',
    'password' => 'password123',
    'email' => 'johndoe@example.com'
);

$result = registerUser($data);

if ($result['success']) {
    echo "User created successfully!";
} else {
    echo "Error: " . $result['message'];
}


<?php

function register_user($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('Please fill in all fields');
    }

    // Sanitize input data
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username and email already exist in database
    $db = connect_to_database(); // assume this function exists to connect to the database
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        throw new Exception('Username or email already in use');
    }

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    mysqli_query($db, $query);

    return true;
}

?>


try {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    register_user($username, $email, $password);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}


<?php

// Configuration variables
$db_host = 'localhost';
$db_user = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

function registerUser($username, $email, $password) {
  // Connect to database
  try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute INSERT query
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    // Execute query
    try {
      $stmt->execute();
      return true; // User registered successfully
    } catch (PDOException $e) {
      echo "Error registering user: " . $e->getMessage() . "
";
      return false;
    }
  } catch (PDOException $e) {
    echo "Connection to database failed: " . $e->getMessage() . "
";
    return false;
  }
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if (registerUser($username, $email, $password)) {
  echo "User registered successfully!";
} else {
  echo "Error registering user.";
}
?>


// Validation function
function validateInput($username, $email, $password) {
  if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
    return false; // Username contains invalid characters
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Email is not valid
  }
  if (strlen($password) < 8) {
    return false; // Password is too short
  }
  return true;
}

// Update registerUser function to include validation:
function registerUser($username, $email, $password) {
  if (!validateInput($username, $email, $password)) {
    echo "Error: Invalid input.";
    return false;
  }

  // Rest of the code remains the same...
}


// Configuration variables
define('DB_HOST', 'your_host');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');

// Establish database connection
function connectToDatabase() {
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit(1);
    }
}

// User registration function
function registerUser($username, $email, $password) {
    // Validate input data
    if (!validateInputData($username, $email, $password)) {
        return false;
    }

    // Hash the password using SHA-256 (you may want to use a more secure hashing algorithm like bcrypt)
    $hashedPassword = hash('sha256', $password);

    try {
        // Connect to database
        $conn = connectToDatabase();

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute SQL statement
        $stmt->execute();

        // Close database connection
        $conn = null;

        return true;
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        return false;
    }
}

// Function to validate input data
function validateInputData($username, $email, $password) {
    // Validate username
    if (empty($username)) {
        echo "Error: Username is required.";
        return false;
    }

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Error: Email address is invalid.";
        return false;
    }

    // Validate password length
    if (strlen($password) < 8) {
        echo "Error: Password must be at least 8 characters long.";
        return false;
    }

    return true;
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if (registerUser($username, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Registration failed.";
}


<?php

// Configuration variables
define('MIN_USERNAME_LENGTH', 5);
define('MAX_USERNAME_LENGTH', 20);
define('MIN_PASSWORD_LENGTH', 8);

// Function to register a new user
function registerUser($username, $password, $email) {
    // Check for empty fields
    if (empty($username) || empty($password) || empty($email)) {
        return array(false, 'Please fill in all fields.');
    }

    // Check username length
    if (strlen($username) < MIN_USERNAME_LENGTH || strlen($username) > MAX_USERNAME_LENGTH) {
        return array(false, 'Username must be between ' . MIN_USERNAME_LENGTH . ' and ' . MAX_USERNAME_LENGTH . ' characters long.');
    }

    // Check password length
    if (strlen($password) < MIN_PASSWORD_LENGTH) {
        return array(false, 'Password must be at least ' . MIN_PASSWORD_LENGTH . ' characters long.');
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    try {
        $conn = new PDO('mysql:host=localhost;dbname=mydb', 'myuser', 'mypassword');
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :hashedPassword)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':hashedPassword', $hashedPassword);
        $stmt->execute();
        return array(true, 'Registration successful!');
    } catch (PDOException $e) {
        return array(false, 'Error registering user: ' . $e->getMessage());
    }
}

// Example usage:
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

$result = registerUser($username, $password, $email);
echo json_encode($result);

?>


<?php

// Configuration
require_once 'config.php';

// Validation Error Messages
$validationErrors = array();

function registerUser($firstName, $lastName, $email, $password) {
    // Input Validation
    if (empty($firstName)) {
        $validationErrors[] = "First name is required.";
    }
    if (empty($lastName)) {
        $validationErrors[] = "Last name is required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validationErrors[] = "Invalid email address.";
    }
    if (strlen($password) < 8) {
        $validationErrors[] = "Password must be at least 8 characters long.";
    }

    // If validation errors exist
    if (!empty($validationErrors)) {
        $_SESSION['validation_errors'] = $validationErrors;
        header('Location: register.php');
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare query to insert new user into database
    $query = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$firstName, $lastName, $email, $hashedPassword]);

    // Send confirmation email with token
    sendConfirmationEmail($email);

    $_SESSION['success_message'] = "Registration successful! Please check your email for verification.";
    header('Location: login.php');
}

// Function to send confirmation email with token
function sendConfirmationEmail($email) {
    $token = bin2hex(random_bytes(16));
    $query = "INSERT INTO tokens (user_id, token) VALUES ((SELECT id FROM users WHERE email = ?), ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$email, $token]);

    $subject = 'Verify your account';
    $body = 'Click the link to verify: <a href="verify.php?token=' . $token . '">Verify Account</a>';
    sendEmail($email, $subject, $body);
}

// Function to send email using PHPMailer
function sendEmail($to, $subject, $body) {
    require_once 'PHPMailerAutoload.php';
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'your-email@example.com';
    $mail->Password = 'your-password';
    $mail->setFrom('your-email@example.com', 'Your Name');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;
    if (!$mail->send()) {
        return "Error: Mailer failed";
    }
}

?>


<?php

// Database configuration
$dsn = 'mysql:host=localhost;dbname=your_database';
$username = 'your_username';
$password = 'your_password';

$conn = new PDO($dsn, $username, $password);

?>


function registerUser($name, $email, $password) {
    // Input validation
    if (empty($name) || empty($email) || empty($password)) {
        throw new InvalidArgumentException('All fields are required');
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Invalid email address');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Verify email (optional)
    try {
        verifyEmail($email);
    } catch (Exception $e) {
        // Handle email verification error
    }

    // Store user data in database (using PDO or other ORM)
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => $hashedPassword
    ]);

    // Return user ID on success
    return $pdo->lastInsertId();
}

// Email verification function (optional)
function verifyEmail($email) {
    // Send email verification link with token to registered email address

    // Check if token is valid
    if (!checkToken($_POST['token'])) {
        throw new Exception('Invalid token');
    }

    // Update user data in database with verified status
}


try {
    $userId = registerUser('John Doe', 'john.doe@example.com', 'password123');
    echo "User created successfully! User ID: $userId";
} catch (InvalidArgumentException $e) {
    // Handle input validation errors
} catch (Exception $e) {
    // Handle other exceptions
}


<?php
// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'users_database');

function connect_to_db() {
    $conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

function register_user($username, $email, $password) {
    // Check for empty fields
    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill in all fields.";
        return false;
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        return false;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Create database connection
    $conn = connect_to_db();

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    mysqli_query($conn, $query);
    if (!mysqliaffectedrows($conn)) {
        echo "Error inserting new user into database.";
        return false;
    }

    // Close database connection
    mysqli_close($conn);

    // Return true to indicate successful registration
    return true;
}

// Example usage:
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (register_user($username, $email, $password)) {
        echo "Registration successful!";
    } else {
        echo "Error registering user.";
    }
}
?>


<?php

function registerUser($username, $email, $password) {
    // Define the database connection settings
    $servername = "localhost";
    $usernameDB = "your_username";
    $passwordDB = "your_password";
    $dbname = "your_database";

    // Create a new connection to the database
    $conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hash the password
    $hashedPassword = hash('sha256', $password);

    // Prepare the SQL query to insert new user data into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Execute the prepared statement with parameters
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        $stmt->execute();

        // Check if the user was successfully registered
        if ($stmt->affected_rows == 1) {
            return true;
        } else {
            echo "Error: User not registered. Please try again.";
            return false;
        }
    }

    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();

    // Return false if there was an error registering the user
    return false;
}

?>


$username = "new_user";
$email = "newuser@example.com";
$password = "mysecretpassword";

if (registerUser($username, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Error registering user. Please try again.";
}


class User {
    private $username;
    private $email;
    private $password;

    public function __construct($username, $email, $password) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }
}


require_once 'User.php';

function registerUser($username, $email, $password) {
    // Input validation
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('All fields are required.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format.');
    }

    if (strlen($password) < 8) {
        throw new Exception('Password must be at least 8 characters long.');
    }

    try {
        // Attempt to create a user
        $user = new User($username, $email, password_hash($password, PASSWORD_DEFAULT));
        return $user;
    } catch (Exception $e) {
        throw new Exception('Error registering user: ' . $e->getMessage());
    }
}

try {
    // Example usage:
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = registerUser($username, $email, $password);

    // Save the user to a database (example)
    // ...
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}


function registerUser($username, $email, $password) {
    // Check if username and email are valid (for simplicity, we just check if they contain letters)
    if (!preg_match('/^[a-zA-Z]+$/', $username)) {
        throw new Exception("Invalid username. Username must only contain letters.");
    }
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        throw new Exception("Invalid email address.");
    }

    // Hash the password (we'll use SHA256 for simplicity)
    $passwordHash = hash('sha256', $password);

    // Connect to database (in a real application, you would want to use PDO or another ORM)
    $conn = mysqli_connect("localhost", "username", "password", "database");
    if (!$conn) {
        throw new Exception("Failed to connect to database.");
    }

    // Insert user into database
    $query = "INSERT INTO users (username, email, password_hash) VALUES ('$username', '$email', '$passwordHash')";
    mysqli_query($conn, $query);

    // Close the connection
    mysqli_close($conn);
}

// Example usage:
try {
    registerUser("JohnDoe", "johndoe@example.com", "mysecretpassword");
} catch (Exception $e) {
    echo $e->getMessage();
}


function registerUser($username, $email, $password) {
    // Check if username and email are valid (for simplicity, we just check if they contain letters)
    if (!preg_match('/^[a-zA-Z]+$/', $username)) {
        throw new Exception("Invalid username. Username must only contain letters.");
    }
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        throw new Exception("Invalid email address.");
    }

    // Hash the password (we'll use SHA256 for simplicity)
    $passwordHash = hash('sha256', $password);

    // Connect to database
    $conn = mysqli_connect("localhost", "username", "password", "database");
    if (!$conn) {
        throw new Exception("Failed to connect to database.");
    }

    // Prepare statement
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Failed to prepare statement.");
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $passwordHash);

    // Execute query
    mysqli_stmt_execute($stmt);

    // Close the connection and statement
    mysqli_close($conn);
    mysqli_stmt_close($stmt);
}

// Example usage:
try {
    registerUser("JohnDoe", "johndoe@example.com", "mysecretpassword");
} catch (Exception $e) {
    echo $e->getMessage();
}


function registerUser($userData) {
    // Validate data
    $errors = validateUserData($userData);
    
    if (!empty($errors)) {
        return array('success' => false, 'errors' => $errors);
    }
    
    // Hash the password
    $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
    
    try {
        // Insert data into database (using PDO)
        $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');
        
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $userData['username']);
        $stmt->bindParam(':email', $userData['email']);
        $stmt->bindParam(':password', $hashedPassword);
        
        if ($stmt->execute()) {
            return array('success' => true, 'message' => 'User created successfully');
        } else {
            throw new Exception('Error inserting user data');
        }
    } catch (PDOException $e) {
        // Handle database error
        return array('success' => false, 'errors' => array('Database Error: ' . $e->getMessage()));
    }
}

// Validation function for user data
function validateUserData($userData) {
    $errors = array();
    
    if (empty($userData['username']) || strlen($userData['username']) < 3) {
        $errors[] = 'Username must be at least 3 characters long';
    }
    
    if (empty($userData['email']) || !filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address';
    }
    
    if (empty($userData['password']) || strlen($userData['password']) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    }
    
    return $errors;
}


$userData = array(
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
);

$response = registerUser($userData);
print_r($response);


<?php

// Configuration settings
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'your_database';

// Connect to database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    global $conn;

    // Retrieve form data
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate user input
        if (!empty($name) && !empty($email) && !empty($password)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                if (strlen($password) >= 8) {
                    // Hash password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Insert user data into database
                    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
                    if ($conn->query($sql)) {
                        echo 'User registered successfully!';
                    } else {
                        echo 'Error: ' . $conn->error;
                    }
                } else {
                    echo 'Password must be at least 8 characters long.';
                }
            } else {
                echo 'Invalid email address.';
            }
        } else {
            echo 'Please fill out all fields.';
        }
    }

    // Close database connection
    $conn->close();
}

// Check if form has been submitted
if (isset($_POST['submit'])) {
    registerUser();
} else {
    // Display registration form
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="name">Name:</label>
        <input type="text" name="name"><br><br>
        <label for="email">Email:</label>
        <input type="email" name="email"><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password"><br><br>
        <button type="submit" name="submit">Register</button>
    </form>
    <?php
}
?>


<?php

// Database connection settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database_name';

// Create database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user exists
function checkUserExists($username) {
    global $conn;
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Register new user
function registerUser($username, $email, $password) {
    global $conn;

    // Check if username already exists
    if (checkUserExists($username)) {
        echo "Username already exists!";
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if ($conn->query($query)) {
        echo "User registered successfully!";
    } else {
        echo "Error registering user: " . $conn->error;
    }
}

// Handle form submission
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    registerUser($username, $email, $password);
} else {
    // Display registration form
    echo '<form action="" method="post">';
    echo 'Username: <input type="text" name="username"><br><br>';
    echo 'Email: <input type="email" name="email"><br><br>';
    echo 'Password: <input type="password" name="password"><br><br>';
    echo '<button type="submit" name="register">Register</button>';
    echo '</form>';
}
?>


function registerUser($username, $email, $password)
{
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("All fields are required.");
    }

    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to database
        $conn = new PDO('mysql:host=localhost;dbname=your_database_name', 'username', 'password');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert user data into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :hashedPassword)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':hashedPassword', $hashedPassword);

        // Execute query
        $stmt->execute();

        // Close database connection
        $conn = null;

        // Return user ID
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Database error: " . $e->getMessage());
    }
}


try {
    $userId = registerUser('johnDoe', 'johndoe@example.com', 'mysecretpassword');
    echo "User created successfully. ID: $userId";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<?php

// Function to register new user
function register_user($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array("error" => "Please fill out all fields");
    }

    try {
        // Hash password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect to database
        $db = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL statement
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashed_password);

        // Execute SQL statement
        if ($stmt->execute()) {
            return array("success" => "User registered successfully");
        } else {
            throw new Exception("Failed to register user");
        }

    } catch (PDOException $e) {
        return array("error" => "Database error: " . $e->getMessage());
    }
}

// Example usage
$username = "exampleuser";
$email = "example@example.com";
$password = "password123";

$result = register_user($username, $email, $password);

if ($result["success"]) {
    echo "User registered successfully!";
} elseif ($result["error"]) {
    echo "Error: " . $result["error"];
}


<?php

// Configuration variables
$minUsernameLength = 3;
$maxUsernameLength = 50;
$minPasswordLength = 8;

function validateInput($data) {
    // Validate username length
    if (strlen($data['username']) < $minUsernameLength || strlen($data['username']) > $maxUsernameLength) {
        return array('error' => 'Username must be between '. $minUsernameLength . ' and '. $maxUsernameLength . ' characters long.');
    }

    // Validate password length
    if (strlen($data['password']) < $minPasswordLength) {
        return array('error' => 'Password must be at least ' . $minPasswordLength . ' characters long.');
    }

    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    // If no errors, return success message
    return array('success' => true);
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function registerUser($data) {
    // Validate input data
    $validation = validateInput($data);

    if (!isset($validation['success'])) {
        return $validation;
    }

    // Hash password
    $hashedPassword = hashPassword($data['password']);

    try {
        // Insert user into database
        // Replace with your own database connection and query
        $dbConnection = new PDO('sqlite:users.db');
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $dbConnection->prepare($query);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        return array('success' => true, 'message' => 'User registered successfully');
    } catch (PDOException $e) {
        return array('error' => 'Database error: '. $e->getMessage());
    }
}

// Example usage
$data = array(
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
);

$result = registerUser($data);
print_r($result);

?>


// config.php (database configuration file)
$pdo = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');

function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("All fields are required.");
    }

    try {
        // Check for duplicate email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            throw new Exception("Email already in use.");
        }
    } catch (PDOException $e) {
        throw new Exception("Database error: " . $e->getMessage());
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insert user data into database
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $passwordHash]);
    } catch (PDOException $e) {
        throw new Exception("Database error: " . $e->getMessage());
    }

    return true;
}


try {
    registerUser('john_doe', 'johndoe@example.com', 'password123');
    echo "User registered successfully.";
} catch (Exception $e) {
    echo $e->getMessage();
}


<?php
// Define the connection settings for your database
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

function connectToDatabase() {
    global $dbHost, $dbUsername, $dbPassword, $dbName;
    try {
        // Attempt to connect to the database
        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
        return $conn;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit();
    }
}

function registerUser($username, $email, $password, $confirmPassword) {
    // Check if the input fields are not empty
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        return array('status' => 'error', 'message' => 'Please fill in all the required fields.');
    }

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('status' => 'error', 'message' => 'Invalid email address.');
    }

    // Check if password and confirm password match
    if ($password !== $confirmPassword) {
        return array('status' => 'error', 'message' => 'Passwords do not match.');
    }

    // Hash the password for security
    $hashedPassword = hash('sha256', $password);

    // Connect to database
    $conn = connectToDatabase();

    try {
        // Insert user data into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the query
        $stmt->execute();

        return array('status' => 'success', 'message' => 'User registered successfully.');
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
        return array('status' => 'error', 'message' => 'Failed to register user.');
    }
}
?>


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';
$confirmPassword = 'password123';

$result = registerUser($username, $email, $password, $confirmPassword);

if ($result['status'] == 'success') {
    echo 'User registered successfully.';
} else {
    echo 'Error: ' . $result['message'];
}


<?php

// Configuration settings
require_once 'config.php';

function registerUser($firstName, $lastName, $email, $password) {
    // Check if the user already exists in the database
    $query = "SELECT * FROM users WHERE email=:email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if ($stmt->fetch()) {
        return 'Email address already exists.';
    }

    // Hash the password
    $passwordHash = hash('sha256', $password);

    // Insert new user into database
    $query = "INSERT INTO users (firstName, lastName, email, password)
              VALUES (:firstName, :lastName, :email, :password)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':firstName', $firstName);
    $stmt->bindParam(':lastName', $lastName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $passwordHash);
    if ($stmt->execute()) {
        return 'User registered successfully!';
    } else {
        return 'Error registering user.';
    }
}


<?php

require_once 'register.php';

// Get form data from POST request
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$password = $_POST['password'];

// Register new user
$result = registerUser($firstName, $lastName, $email, $password);

// Display result to user
echo $result;

?>


/**
 * Registers a new user.
 *
 * @param string $username The username chosen by the user.
 * @param string $email    The email address chosen by the user.
 * @param string $password The password chosen by the user (hashed before storage).
 *
 * @return array An array containing the user's ID and any error messages.
 */
function registerUser($username, $email, $password)
{
    // Error handling for empty fields
    if (empty($username) || empty($email) || empty($password)) {
        return [
            'success' => false,
            'errors' => ['Please fill in all fields.'],
        ];
    }

    // Hash password using bcrypt (make sure to install the `password_hash` extension)
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Query to create new user
    try {
        // Assuming a database connection is established and available ($db)
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute query and get the last inserted ID
        $stmt->execute();
        $userId = $db->lastInsertId();

        return [
            'success' => true,
            'userId' => $userId,
        ];
    } catch (PDOException $e) {
        return [
            'success' => false,
            'errors' => ['Failed to register user: ' . $e->getMessage()],
        ];
    }
}


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if ($result['success']) {
    // User successfully registered. You can now log them in.
} else {
    echo 'Error message: ' . implode(', ', $result['errors']);
}


<?php
// Configuration
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

// Create a user
function createUser($username, $email, $password) {
    // Connect to the database
    $conn = dbConnect();
    
    // Check if username or email already exists in the database
    $query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        return false;
    }
    
    // Hash password before storing it
    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user into the database
    $query = "INSERT INTO users (username, email, password)
              VALUES ('$username', '$email', '$passwordHashed')";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        echo "Error: " . $conn->error;
    }
    
    // Close the database connection
    $conn->close();
}

// Registration form processing function
function registerUser() {
    // Validate input fields
    if (empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["password"])) {
        echo "Please fill in all fields.";
        return false;
    }
    
    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        return false;
    }
    
    // Hash password before storing it
    $passwordHashed = password_hash($_POST["password"], PASSWORD_DEFAULT);
    
    // Insert new user into the database
    if (createUser($_POST["username"], $_POST["email"], $_POST["password"])) {
        echo "You have successfully registered!";
        return true;
    } else {
        echo "Registration failed.";
    }
}

// Check for form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    registerUser();
}
?>


<?php

// Configuration
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'users';

// Connect to database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// User registration function
function registerUser($username, $email, $password) {
    global $conn;

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        return 'Error: All fields must be filled in';
    }

    // Check for existing username or email
    $sql = "SELECT * FROM users WHERE username='" . $username . "' OR email='" . $email . "'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return 'Error: Username and/or email already exists';
    }

    // Insert new user
    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password) VALUES ('" . $username . "', '" . $email . "', '$passwordHashed')";
    if ($conn->query($sql)) {
        return 'User created successfully';
    } else {
        return 'Error: Failed to create user';
    }
}

// Usage example
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);
echo $result;

?>


function registerUser($username, $email, $password) {
    // Sanitize input data
    $username = htmlspecialchars($username);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email || !$username) {
        return array('error' => 'Invalid email or username');
    }

    // Hash password using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Connect to database and insert new user
        $dbConn = connectToDatabase();
        $stmt = $dbConn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Send verification email
        sendVerificationEmail($email);

        return array('success' => 'User created successfully');
    } catch (PDOException $e) {
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}


function sendVerificationEmail($email) {
    // Use a library like PHPMailer to send emails
    require_once 'PHPMailer/PHPMailer.php';
    require_once 'PHPMailer/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->Username = 'your_email@example.com';
    $mail->Password = 'your_password';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';

    $body = '<p>Hello, please click on the following link to verify your email address:</p>';
    $body .= '<a href="http://example.com/verify/'.$email.'">Verify Email</a>';

    $mail->setFrom('your_email@example.com', 'Your Name');
    $mail->addAddress($email);
    $mail->Subject = 'Email Verification';
    $mail->Body = $body;
    $mail->AltBody = 'Please click on the link to verify your email address';

    if (!$mail->send()) {
        throw new Exception('Error sending email: ' . $mail->ErrorInfo);
    }
}


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if ($result['success']) {
    echo 'User created successfully!';
} else {
    echo 'Error: ' . $result['error'];
}


<?php

function registerUser($username, $email, $password) {
    // Connect to database
    require_once 'db.php';
    $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check if username and email already exist
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
        return array('error' => 'Username or Email already taken');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if ($mysqli->query($query)) {
        return array('success' => 'User registered successfully');
    } else {
        return array('error' => 'Error registering user');
    }

    // Close database connection
    $mysqli->close();
}

// Example usage:
$registerData = registerUser('johnDoe', 'johndoe@example.com', 'password123');
print_r($registerData);

?>


$registerData = registerUser('johnDoe', 'johndoe@example.com', 'password123');
print_r($registerData); // Output: Array ( [success] => User registered successfully )


$registerData = registerUser('johnDoe', 'johndoe@example.com', 'password123');
print_r($registerData); // Output: Array ( [error] => Username or Email already taken )


<?php

// Configuration file for database connection
require_once 'config.php';

function registerUser($username, $email, $password, $role_id) {
    // Validate input data
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }
    
    try {
        // Prepare SQL query to insert user into database
        $query = "
            INSERT INTO users (username, email, password, role_id)
            VALUES (:username, :email, :password, :role_id)
        ";
        
        // Prepare PDO statement with parameters
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role_id', $role_id);
        
        // Execute query and commit changes
        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception('Error registering user');
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
        return false;
    } catch (Exception $e) {
        echo 'Error registering user: ' . $e->getMessage();
        return false;
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role_id = (int) $_POST['role_id']; // Convert to integer
        
        if ($username && $email && $password && $role_id) {
            $registered = registerUser($username, $email, password_hash($password, PASSWORD_DEFAULT), $role_id);
            
            if ($registered) {
                echo 'User registered successfully!';
            } else {
                echo 'Error registering user. Please try again.';
            }
        } else {
            echo 'Please fill out all fields';
        }
    } catch (Exception $e) {
        echo 'An error occurred: ' . $e->getMessage();
    }
}

?>


<?php

// Configuration settings
require_once 'config.php';

function registerUser($name, $email, $password) {
    // Validate input
    if (empty($name)) {
        throw new Exception('Name cannot be empty.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address.');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Prepare SQL query to insert user into database
        $query = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute query
        if (!$stmt->execute()) {
            throw new Exception('Failed to create user record.');
        }

        return true;
    } catch (PDOException $e) {
        // Log any database errors
        error_log("Error: " . $e->getMessage());
        return false;
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (registerUser($name, $email, $password)) {
            echo "User created successfully.";
        } else {
            echo "Failed to create user record.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>


<?php
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>


<?php
require_once 'config.php';

function registerUser($name, $email, $password) {
    // Validate input data
    if (empty($name) || empty($email) || empty($password)) {
        throw new Exception('All fields are required.');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare query to insert user data into database
    $stmt = $mysqli->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);
    if ($stmt->execute()) {
        return true; // User registered successfully
    } else {
        throw new Exception('Failed to register user.');
    }
}

try {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = registerUser($name, $email, $password);
    if ($result) {
        echo 'User registered successfully!';
    } else {
        throw new Exception('Error registering user.');
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

$mysqli->close();
?>


<?php
// Configuration variables for the connection to your database.
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

function register_user($email, $password) {
    // Establish a connection to the database
    try {
        $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        // Create an SQL query for inserting a new user into the database.
        $query = "INSERT INTO users (email, password)
                  VALUES (?, ?)";
        
        // Prepare the SQL statement
        if (!$stmt = $conn->prepare($query)) {
            throw new Exception("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }
        
        // Bind the parameters to the prepared statement
        $stmt->bind_param('ss', $email, $password);
        
        // Execute the query
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }
        
        // Close and clean up resources
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "
";
    }
}

// Usage example:
register_user('test@example.com', md5('password'));
?>


register_user('test@example.com', password_hash('password', PASSWORD_DEFAULT));


<?php

// Define the database connection settings
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

function registerUser($username, $email, $password)
{
    // Check if the username and email are already taken
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        return array("error" => "Username or email already exists.");
    }

    // Hash the password using SHA-256
    $hashedPassword = hash('sha256', $password);

    // Insert the new user into the database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if ($mysqli->query($query)) {
        return array("message" => "User registered successfully.");
    } else {
        return array("error" => "Error registering user: " . $mysqli->error);
    }
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if (isset($result['message'])) {
    echo $result['message'];
} elseif (isset($result['error'])) {
    echo $result['error'];
}

?>


<?php
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

// Email configuration
define('EMAIL_FROM_ADDRESS', 'your_email_address');
define('EMAIL_VERIFICATION_LINK_SECRET', 'secret_key_for_email_verification');


<?php
require_once 'config.php';

function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields.');
    }

    try {
        // Connect to database
        $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

        // Check if email is already registered
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->fetch()) {
            return array('error' => 'Email address is already taken.');
        }

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into database
        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->execute();

        // Generate verification link
        $verificationLink = generateVerificationLink($conn, $email);

        return array('success' => 'User registered successfully. Please verify your email address by clicking the following link: ' . $verificationLink);

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return array('error' => 'An error occurred while registering user.');
    }
}

function generateVerificationLink($conn, $email) {
    // Generate secret token
    $secretToken = bin2hex(random_bytes(16));

    // Insert new verification record into database
    $stmt = $conn->prepare('INSERT INTO verification_tokens (token, email) VALUES (:token, :email)');
    $stmt->bindParam(':token', $secretToken);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Generate verification link
    $verificationLink = 'http://example.com/verify-email?token=' . urlencode($secretToken);

    return $verificationLink;
}


<?php
require_once 'config.php';

function verifyEmail($token) {
    // Validate input data
    if (empty($token)) {
        return array('error' => 'Invalid token.');
    }

    try {
        // Connect to database
        $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

        // Check if verification token is valid
        $stmt = $conn->prepare('SELECT * FROM verification_tokens WHERE token = :token AND email IS NOT NULL');
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        if (!$stmt->fetch()) {
            return array('error' => 'Invalid token or has been used.');
        }

        // Activate user account
        $stmt = $conn->prepare('UPDATE users SET active = 1 WHERE email IN (SELECT email FROM verification_tokens WHERE token = :token)');
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        return array('success' => 'Email address verified successfully.');

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return array('error' => 'An error occurred while verifying email.');
    }
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

$result = registerUser($username, $email, $password);
echo json_encode($result);


<?php

function registerUser($name, $email, $password) {
    // Validate input data
    if (empty($name) || empty($email) || empty($password)) {
        return "Error: All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Error: Invalid email address.";
    }

    if (strlen($password) < 8) {
        return "Error: Password must be at least 8 characters long.";
    }

    // Hash password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database and insert user data
    try {
        // Assume we have a database connection established using PDO
        $dbConnection = new PDO('sqlite:users.db');

        // Insert user data into the 'users' table
        $stmt = $dbConnection->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return "User registered successfully.";
        } else {
            throw new Exception("Error registering user.");
        }
    } catch (PDOException $e) {
        // Handle database connection error
        echo "Database error: " . $e->getMessage();
    }

    // Return an error message if registration fails
    return "Error registering user.";
}

// Example usage:
$userData = array(
    'name' => 'John Doe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
);

$result = registerUser($userData['name'], $userData['email'], $userData['password']);

echo $result;

?>


<?php

function registerUser($name, $email, $password) {
  // Check if email already exists
  $db = mysqli_connect("localhost", "username", "password", "database");
  if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($db, $query);
  if (mysqli_num_rows($result) > 0) {
    return array('error' => 'Email already exists');
  }

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
  mysqli_query($db, $query);

  // Close connection
  mysqli_close($db);

  return array('success' => 'User registered successfully');
}

?>


$data = array('name' => 'John Doe', 'email' => 'john@example.com', 'password' => 'password123');
$result = registerUser($data['name'], $data['email'], $data['password']);
echo json_encode($result);


<?php

function register_user($conn, $username, $email, $password) {
  // Prepare the query
  $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

  // Bind parameters
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $password);

  // Check if user already exists
  $stmt->execute();
  $result = $conn->lastInsertId();

  if ($result) {
    return true;
  } else {
    return false;
  }
}

// Example usage:
$conn = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

if (register_user($conn, $username, $email, $password)) {
  echo "User registered successfully!";
} else {
  echo "Error registering user.";
}

?>


function register_user($username, $email, $password) {
  // Check for empty fields
  if (empty($username) || empty($email) || empty($password)) {
    return array("error" => "Please fill out all required fields.");
  }

  // Hash the password
  $hashed_password = hash('sha256', $password);

  // Connect to database
  $db = new mysqli("localhost", "username", "password", "database");

  // Check connection
  if ($db->connect_error) {
    return array("error" => "Connection failed: " . $db->connect_error);
  }

  // Create SQL query
  $query = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$hashed_password')";

  // Execute query
  if (!$db->query($query)) {
    return array("error" => "Registration failed: " . $db->error);
  }

  // Close connection
  $db->close();

  // Return success message
  return array("message" => "User registered successfully.");
}


$username = "johnDoe";
$email = "johndoe@example.com";
$password = "password123";

$result = register_user($username, $email, $password);
if ($result["error"]) {
  echo $result["error"];
} else {
  echo $result["message"];
}


<?php

// Define the database connection settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to register a new user
function registerUser($data) {
    // Validate input data
    if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
        return array('error' => 'Missing required fields');
    }

    // Connect to the database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        return array('error' => 'Failed to connect to database: ' . $conn->connect_error);
    }

    // Hash and store password securely
    $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

    // Prepare SQL query to insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        return array('error' => 'Failed to prepare SQL statement: ' . $conn->error);
    }

    // Bind parameters and execute query
    $stmt->bind_param("sss", $data['username'], $data['email'], $passwordHash);
    if (!$stmt->execute()) {
        return array('error' => 'Failed to insert user data into database: ' . $conn->error);
    }

    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();

    // Return success message on successful registration
    return array('message' => 'User registered successfully');
}

// Example usage:
$data = array(
    'username' => 'john_doe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
);

$result = registerUser($data);
print_r($result);

?>


<?php
// Configuration settings
$requiredFields = array('username', 'email', 'password');
$dbHost = 'localhost';
$dbUsername = 'your_database_username';
$dbPassword = 'your_database_password';
$dbName = 'your_database_name';

// Connect to database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate user input
function validateInput($data) {
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            return false;
        }
    }
    return true;
}

// Function to register new user
function registerUser($username, $email, $password) {
    // Check if username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return array('error' => 'Username already taken');
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$passwordHash')";
    if ($conn->query($sql)) {
        return array('success' => 'User registered successfully');
    } else {
        return array('error' => 'Failed to register user');
    }
}

// Handle form submission
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (!validateInput(array('username' => $username, 'email' => $email, 'password' => $password))) {
        return array('error' => 'Please fill out all required fields');
    }

    // Register user
    $result = registerUser($username, $email, $password);
    if ($result['success']) {
        echo "You have been registered successfully!";
    } else {
        echo "Error: " . $result['error'];
    }
}

// Close database connection
$conn->close();
?>


<?php
include 'register.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        <button type="submit" name="register">Register</button>
    </form>

    <?php if (isset($_POST['register'])) { ?>
        <?php echo $result; ?>
    <?php } ?>
</body>
</html>


<?php
// Configuration settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to register user
function registerUser($username, $email, $password) {
    // Validate input fields
    if (empty($username) || empty($email) || empty($password)) {
        return array('success' => false, 'message' => 'All fields are required');
    }

    // Hash password using SHA-256
    $hashedPassword = sha256($password);

    // Prepare SQL query to insert user data into database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    // Execute query and get result
    if ($stmt->execute()) {
        return array('success' => true, 'message' => 'User registered successfully');
    } else {
        return array('success' => false, 'message' => 'Error registering user');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Call registerUser function and display result
    $result = registerUser($username, $email, $password);
    echo json_encode($result);
}

// Close database connection
$conn->close();
?>


function registerUser($firstName, $lastName, $email, $password) {
    // Validate input data
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        throw new Exception("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address.");
    }

    // Hash password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare user data to be stored in database
    $userData = [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'password' => $hashedPassword,
    ];

    // Connect to database and insert user data
    $conn = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)");
        $stmt->bindParam(':first_name', $userData['first_name']);
        $stmt->bindParam(':last_name', $userData['last_name']);
        $stmt->bindParam(':email', $userData['email']);
        $stmt->bindParam(':password', $userData['password']);

        if ($stmt->execute()) {
            return true; // User registered successfully
        } else {
            throw new Exception("Failed to register user.");
        }
    } catch (PDOException $e) {
        throw new Exception("Database error: " . $e->getMessage());
    } finally {
        $conn = null;
    }

    return false; // Registration failed
}


try {
    $registered = registerUser('John', 'Doe', 'john@example.com', 'password123');
    if ($registered) {
        echo "User registered successfully.";
    } else {
        throw new Exception("Failed to register user.");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<?php

// Configuration
const DB_HOST = 'localhost';
const DB_USER = 'your_username';
const DB_PASSWORD = 'your_password';
const DB_NAME = 'your_database';

// Function to connect to the database
function db_connect() {
  $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
  return $conn;
}

// Function to register a user
function register_user($username, $email, $password) {
  // Validate input data
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception('All fields are required.');
  }

  // Hash password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  try {
    // Connect to the database
    $conn = db_connect();

    // Prepare and execute query
    $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
    $stmt->execute([$username, $email, $hashed_password]);

    // Commit changes and close connection
    $conn->commit();
    $conn = null;

  } catch (PDOException $e) {
    throw new Exception('Database error: ' . $e->getMessage());
  }
}

?>


<?php

require_once 'register_user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Call the register function
    register_user($username, $email, $password);

    echo 'User registered successfully!';
  } catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
  }
} else {
  ?>
  <html>
    <body>
      <h1>Register</h1>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        <button type="submit">Register</button>
      </form>
    </body>
  </html>
  <?php
}
?>


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'users_database');

// Connect to the database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Function to register a user
function registerUser($name, $email, $password, $confirm_password) {
    // Validate input data
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        return array('error' => 'Please fill in all fields.');
    }

    if ($password != $confirm_password) {
        return array('error' => 'Passwords do not match.');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into database
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $hashedPassword);
    $result = $stmt->execute();

    if ($result) {
        return array('success' => 'User registered successfully.');
    } else {
        return array('error' => 'Error registering user.');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Register user
    $response = registerUser($name, $email, $password, $confirm_password);

    if ($response['error']) {
        echo '<p style="color: red;">' . $response['error'] . '</p>';
    } elseif ($response['success']) {
        echo '<p style="color: green;">' . $response['success'] . '</p>';
    }
}

?>


class User {
    public $id;
    public $username;
    public $email;
    public $password;

    function __construct($username, $email, $password) {
        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
}


class Database {
    private static $_connection;

    public function connect() {
        $this->_connection = mysqli_connect("localhost", "username", "password", "database");
    }

    public function registerUser($user) {
        // Prepare SQL query
        $sqlQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Execute SQL query with parameters
        if (!mysqli_stmt_prepare($stmt = mysqli_prepare($this->_connection, $sqlQuery))) {
            echo 'Error preparing statement: ' . mysqli_error($this->_connection);
            exit;
        }

        if (!mysqli_stmt_bind_param($stmt, "sss", $user->username, $user->email, $user->password)) {
            echo 'Error binding parameters: ' . mysqli_error($this->_connection);
            exit;
        }

        if (mysqli_stmt_execute($stmt)) {
            return true; // User successfully registered
        } else {
            echo 'Error executing statement: ' . mysqli_error($this->_connection);
            return false;
        }
    }
}


function registerUser($username, $email, $password) {
    // Initialize database connection
    Database::connect();

    // Create new user instance
    $user = new User($username, $email, $password);

    // Register user with database
    if (Database::registerUser($user)) {
        echo 'User successfully registered!';
    } else {
        echo 'Error registering user.';
    }

    // Close database connection (automatically closed by Database class)
}


// Call registerUser function to create new user
registerUser('johnDoe', 'johndoe@example.com', 'password123');

