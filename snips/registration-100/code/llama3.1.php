
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

class User {
    private $username;
    private $email;
    private $password;

    public function __construct($username, $email, $password) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public function register() {
        // Validate input
        if (!$this->validateInput()) {
            return false;
        }

        // Hash password
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        // Insert user into database
        try {
            $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');
            $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error registering user: " . $e->getMessage();
            return false;
        }

        // Send verification email
        try {
            mail($this->email, 'Verify Your Email Address', 'Please click this link to verify your email address: <a href="' . $_SERVER['HTTP_HOST'] . '/verify.php?token=' . md5($this->username) . '">Verify Email</a>');
        } catch (Exception $e) {
            echo "Error sending verification email: " . $e->getMessage();
            return false;
        }

        return true;
    }

    private function validateInput() {
        // Check if username and email are not empty
        if (empty($this->username) || empty($this->email)) {
            echo 'Username and email are required';
            return false;
        }

        // Check if password meets requirements
        if (strlen($this->password) < 8) {
            echo 'Password must be at least 8 characters long';
            return false;
        }

        // Check if username and email already exist in database
        try {
            $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');
            $stmt = $db->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':email', $this->email);
            $result = $stmt->execute();
            if ($result->fetch()) {
                echo 'Username or email already exists';
                return false;
            }
        } catch (PDOException $e) {
            echo "Error checking for existing users: " . $e->getMessage();
            return false;
        }

        return true;
    }
}

?>


<?php

require_once 'User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User($username, $email, $password);
    if ($user->register()) {
        echo "User registered successfully";
    } else {
        echo "Error registering user";
    }
}

?>


<!-- index.php -->

<form action="registration.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" value="Register">
</form>



<?php

// Configuration settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myuser';
$password = 'mypassword';

function registerUser() {
    // Connect to the database
    $conn = new mysqli($GLOBALS['host'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error;
        exit();
    }

    // Define the registration form data
    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];
    $email = $_POST["email"];
    $password1 = $_POST["password1"];
    $password2 = $_POST["password2"];

    // Validate user input
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password1) || empty($password2)) {
        echo "Please fill in all fields.";
        return;
    }

    if ($password1 != $password2) {
        echo "Passwords do not match.";
        return;
    }

    // Hash password
    $hashedPassword = password_hash($password1, PASSWORD_DEFAULT);

    // Create a query to insert the new user into the database
    $query = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";

    // Prepare and execute the query
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "User registered successfully.";
        } else {
            echo "Error registering user: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to prepare query: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}

?>


<form action="" method="post">
    <label>First Name:</label><br>
    <input type="text" name="firstname"><br>
    <label>Last Name:</label><br>
    <input type="text" name="lastname"><br>
    <label>Email:</label><br>
    <input type="email" name="email"><br>
    <label>Password:</label><br>
    <input type="password" name="password1"><br>
    <label>Confirm Password:</label><br>
    <input type="password" name="password2"><br>
    <button type="submit">Register</button>
</form>

<?php
if (isset($_POST["register"])) {
    registerUser();
}
?>


// Email validation example:
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
    return;
}


<?php

// Configuration
$config = array(
    'db_host' => 'localhost',
    'db_username' => 'your_username',
    'db_password' => 'your_password',
    'db_name' => 'your_database'
);

// Database connection function
function connectToDatabase() {
    global $config;
    
    try {
        $conn = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']}", $config['db_username'], $config['db_password']);
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

// User registration function
function createUser($username, $email, $password, $confirmPassword) {
    global $config;

    // Check if database is connected
    if (!connectToDatabase()) {
        return false;
    }

    // Validate user data
    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill in all fields.";
        return false;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        return false;
    }

    // Check for strong password (at least 8 characters long and containing at least one lowercase letter, one uppercase letter, and one number)
    $uppercase = preg_match('/[A-Z]/', $password);
    $lowercase = preg_match('/[a-z]/', $password);
    $number    = preg_match('/\d/', $password);
    
    if (strlen($password) < 8 || !$uppercase || !$lowercase || !$number) {
        echo "Password must be at least 8 characters long and contain one uppercase letter, one lowercase letter, and one number.";
        return false;
    }

    // Check for matching passwords
    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
        return false;
    }
    
    // Hash password using SHA-256 (note: you might want to use a more secure hashing algorithm)
    $hashedPassword = hash('sha256', $password);

    try {
        // Create user query
        $conn = connectToDatabase();
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        
        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute query
        if ($stmt->execute()) {
            echo "User created successfully.";
            return true;
        } else {
            echo "Error creating user.";
            return false;
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        return false;
    }

    // Close database connection
    $conn = null;

    return false;  // Should never reach this point
}

?>


<?php
$database = array(
    'host' => 'your_host',
    'username' => 'your_username',
    'password' => 'your_password',
    'name' => 'your_database'
);
?>


<?php
require_once 'config.php';

function registerUser($name, $email, $password, $confirmPassword) {
    // Input validation
    if (empty($name)) {
        return array('error' => 'Name is required.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address.');
    }
    if ($password != $confirmPassword) {
        return array('error' => 'Passwords do not match.');
    }

    // Password hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Query to insert new user into database
    try {
        $mysqli = new mysqli($database['host'], $database['username'], $database['password'], $database['name']);
        if ($mysqli->connect_errno) {
            throw new Exception("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        }

        // Insert new user into database
        $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        if (!$stmt = $mysqli->prepare($query)) {
            throw new Exception("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        // Bind parameters to query
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        // Execute query
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        return array('success' => 'User created successfully.');
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
        return array('error' => $e->getMessage());
    }
}

// Call the registerUser function with form data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    $result = registerUser($name, $email, $password, $confirmPassword);
    if (isset($result['error'])) {
        echo $result['error'];
    } else {
        echo $result['success'];
    }
}
?>


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function registerUser($username, $email, $password) {
  // Connect to the database
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Hash the password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Prepare and execute SQL query to register user
  $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $email, $hashedPassword);
  if ($stmt->execute()) {
    echo "User registered successfully!";
  } else {
    echo "Error registering user: " . $conn->error;
  }

  // Close the database connection
  $conn->close();
}

// Example usage:
registerUser("john_doe", "johndoe@example.com", "password123");

?>


function registerUser($username, $email, $password) {
  // Input validation
  if (empty($username)) {
    throw new Exception("Username cannot be empty");
  }
  if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
    throw new Exception("Invalid username characters");
  }
  if (strlen($username) > 50) {
    throw new Exception("Username too long");
  }

  // Email validation
  if (!preg_match("/[^@]+@[^@]+\.[^@]+/", $email)) {
    throw new Exception("Invalid email address");
  }

  // Password validation
  if (strlen($password) < 8) {
    throw new Exception("Password must be at least 8 characters long");
  }
  if (!preg_match("/[a-zA-Z]/", $password)) {
    throw new Exception("Password must contain a letter");
  }
  if (!preg_match("/[0-9]/", $password)) {
    throw new Exception("Password must contain a number");
  }

  // Proceed with registration
  ...
}


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'mydatabase');

// Database connection function
function connectToDatabase() {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Registration function
function registerUser($name, $email, $password) {
  // Connect to database
  $conn = connectToDatabase();

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Prepare SQL query
  $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $email, $hashedPassword);
  $stmt->execute();

  // Close database connection
  $conn->close();

  return true;
}

// Validate user input
function validateUserInput($name, $email, $password) {
  if (empty($name) || empty($email) || empty($password)) {
    return array('error' => 'Please fill in all fields');
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return array('error' => 'Invalid email address');
  }
  // Password validation (e.g., length > 8)
  // ...
}

// Registration process
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $errors = validateUserInput($name, $email, $password);
  if ($errors) {
    echo json_encode($errors);
    exit;
  }

  registerUser($name, $email, $password);
  echo "Registration successful!";
} else {
  ?>
  <form method="post">
    Name: <input type="text" name="name"><br>
    Email: <input type="email" name="email"><br>
    Password: <input type="password" name="password"><br>
    <button type="submit">Register</button>
  </form>
  <?php
}
?>


function registerUser($username, $email, $password) {
  // Validate input data
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception('All fields are required.');
  }

  // Hash password for secure storage
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Connect to database
  try {
    $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    throw new Exception('Database connection failed: ' . $e->getMessage());
  }

  // Prepare and execute INSERT query
  try {
    $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();
  } catch (PDOException $e) {
    throw new Exception('User registration failed: ' . $e->getMessage());
  }

  // Close database connection
  $db = null;

  return true;
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

try {
  if (registerUser($username, $email, $password)) {
    echo "User registered successfully!";
  } else {
    throw new Exception('Registration failed.');
  }
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}


<?php

// Configuration settings
$hostname = 'your_host';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

// Create database connection
$conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get user input
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        echo "Error: All fields are required.";
        return;
    }

    // Check if username or email already exists in database
    $query = "SELECT * FROM users WHERE username=:username OR email=:email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if ($stmt->fetch()) {
        echo "Error: Username or email already exists.";
        return;
    }

    // Hash password
    $hashed_password = hash('sha256', $password);

    // Insert user data into database
    $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->execute();

    // Redirect to login page
    header('Location: login.php');
}

// Close database connection
$conn = null;

?>


function registerUser($username, $email, $password, $confirm_password) {
  // Check if all fields are filled
  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    throw new Exception("All fields must be filled");
  }

  // Check if passwords match
  if ($password !== $confirm_password) {
    throw new Exception("Passwords do not match");
  }

  // Hash password using bcrypt
  $hashed_password = password_hash($password, PASSWORD_BCRYPT);

  // Insert user into database (using PDO for example)
  $db = new PDO('mysql:host=localhost;dbname=users', 'username', 'password');
  $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $hashed_password);
  $stmt->execute();

  // Return user ID
  return $db->lastInsertId();
}


try {
  $user_id = registerUser('johnDoe', 'johndoe@example.com', 'mysecretpassword', 'mysecretpassword');
  echo "User created successfully! User ID: $user_id";
} catch (Exception $e) {
  echo "Error creating user: " . $e->getMessage();
}


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

/**
 * User Registration Function
 *
 * @param array $data Array containing username, email, and password.
 * @return bool True if registration is successful, false otherwise.
 */
function registerUser(array $data): bool
{
    // Validate input data
    if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
        return false;
    }

    // Validate username and email
    if (strlen($data['username']) < 3 || strlen($data['email']) < 5) {
        return false;
    }

    // Hash password
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    // Connect to database
    try {
        $db = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        return false;
    }

    // Prepare and execute query to insert user
    try {
        $stmt = $db->prepare('INSERT INTO users SET username = ?, email = ?, password = ?');
        $stmt->execute([$data['username'], $data['email'], $hashedPassword]);
        return true;
    } catch (PDOException $e) {
        echo "Error inserting user: " . $e->getMessage();
        return false;
    }

    // Close database connection
    $db = null;

    return false; // Return false if execution reaches here
}

?>


$data = [
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
];

if (registerUser($data)) {
    echo "User registered successfully!";
} else {
    echo "Registration failed.";
}


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

function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields');
    }

    // Check if email already exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return array('error' => 'Email address is already taken');
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

// Test the function
$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

$result = registerUser($username, $email, $password);

if (isset($result['error'])) {
    echo $result['error'];
} else {
    echo $result['success'];
}


/**
 * Register a new user.
 *
 * @param array $data User data to register (username, email, password)
 *
 * @return bool True if registration was successful, false otherwise
 */
function registerUser($data) {
    // Validation: Ensure required fields are set
    if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
        throw new Exception('Missing required data');
    }

    // Validate username (minimum 3 characters, maximum 20)
    $minUsernameLength = 3;
    $maxUsernameLength = 20;
    if (strlen($data['username']) < $minUsernameLength || strlen($data['username']) > $maxUsernameLength) {
        throw new Exception('Username must be between ' . $minUsernameLength . ' and ' . $maxUsernameLength . ' characters long');
    }

    // Validate email (email address should match the format of a valid email)
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    // Validate password (minimum 8 characters, maximum 30)
    $minPasswordLength = 8;
    $maxPasswordLength = 30;
    if (strlen($data['password']) < $minPasswordLength || strlen($data['password']) > $maxPasswordLength) {
        throw new Exception('Password must be between ' . $minPasswordLength . ' and ' . $maxPasswordLength . ' characters long');
    }

    // Hash the password for secure storage
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    // Insert user data into database (example using PDO)
    try {
        $db = new PDO('sqlite:users.db');
        $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->execute([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $hashedPassword,
        ]);
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }

    return true;
}


$data = [
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword123',
];

try {
    if (registerUser($data)) {
        echo 'Registration successful!';
    } else {
        throw new Exception('Registration failed');
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}


<?php

// Configuration settings
require_once 'config.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation checks
    if (empty($username) || empty($email) || empty($password)) {
        $error_message[] = 'All fields are required.';
    }

    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $error_message[] = 'Username can only contain letters and numbers.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message[] = 'Invalid email address.';
    }

    if ($password !== $confirm_password) {
        $error_message[] = 'Passwords do not match.';
    }

    // If no errors, proceed with registration
    if (empty($error_message)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into database
        try {
            $stmt = $db->prepare('INSERT INTO users SET username = ?, email = ?, password = ?');
            $stmt->execute([$username, $email, $hashed_password]);

            header('Location: index.php?success=1');
            exit;
        } catch (PDOException $e) {
            echo 'Error registering user: ' . $e->getMessage();
        }
    }

    // Display any error messages
    foreach ($error_message as $message) {
        echo '<div class="error">' . $message . '</div>';
    }
}

?>

<!-- Registration form -->
<form action="" method="post">
    <label>Username:</label>
    <input type="text" name="username" required>

    <br>

    <label>Email:</label>
    <input type="email" name="email" required>

    <br>

    <label>Password:</label>
    <input type="password" name="password" required>

    <br>

    <label>Confirm Password:</label>
    <input type="password" name="confirm_password" required>

    <br>

    <button type="submit">Register</button>
</form>


<?php

// Database settings
$db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');

?>


<?php

// Configuration variables
define('MIN_USERNAME_LENGTH', 3);
define('MAX_USERNAME_LENGTH', 32);
define('MIN_PASSWORD_LENGTH', 6);

function registerUser() {
    // Check if form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Extract user input from form
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validate username and password
        $errors = array();
        if (strlen($username) < MIN_USERNAME_LENGTH || strlen($username) > MAX_USERNAME_LENGTH) {
            $errors[] = 'Username must be between ' . MIN_USERNAME_LENGTH . ' and ' . MAX_USERNAME_LENGTH . ' characters long.';
        }
        if (strlen($password) < MIN_PASSWORD_LENGTH) {
            $errors[] = 'Password must be at least ' . MIN_PASSWORD_LENGTH . ' characters long.';
        }
        if ($password != $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }

        // Check for errors
        if (!empty($errors)) {
            return array(false, $errors);
        } else {
            // Hash password and add user to database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Insert into database (assuming a connection to the database is already established)
            // You should replace this with your actual database code
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            try {
                $stmt->execute();
                return array(true, "Registration successful!");
            } catch (PDOException $e) {
                return array(false, "Failed to register: " . $e->getMessage());
            }
        }
    }

    // If form hasn't been submitted
    return array(false, "Invalid request method.");
}

?>


<?php include 'register_user.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>

<body>
    <?php if (isset($response)) { ?>
        <?php if ($response[0]) { ?>
            <p><?php echo $response[1]; ?></p>
        <?php } else { ?>
            <ul>
                <?php foreach ($response[1] as $error) { ?>
                    <li><?php echo $error; ?></li>
                <?php } ?>
            </ul>
        <?php } ?>
    <?php } else { ?>
        <form action="" method="post">
            Username: <input type="text" name="username"><br><br>
            Password: <input type="password" name="password"><br><br>
            Confirm Password: <input type="password" name="confirm_password"><br><br>
            <input type="submit" value="Register">
        </form>
    <?php } ?>
</body>
</html>

<?php
$response = registerUser();
?>


<?php
function registerUser($username, $email, $password) {
    // Check if username already exists
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($GLOBALS['conn'], $query);
    if (mysqli_num_rows($result) > 0) {
        return "Username already exists";
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if (!mysqli_query($GLOBALS['conn'], $query)) {
        return mysqli_error($GLOBALS['conn']);
    }

    return "User created successfully";
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if ($result !== true) {
    echo $result;
} else {
    echo "Registration successful!";
}


$conn = mysqli_connect("localhost", "username", "password", "database_name");


<?php

// Configuration
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

// User registration function
function registerUser($username, $email, $password) {
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("All fields are required.");
    }

    // Check if username already exists
    $conn = connectToDatabase();
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        throw new Exception("Username is already taken.");
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$passwordHash')";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        throw new Exception("Error registering user: " . $conn->error);
    }

    // Close database connection
    $conn->close();
}

// Example usage:
try {
    registerUser('johnDoe', 'johndoe@example.com', 'password123');
} catch (Exception $e) {
    echo "Registration failed: " . $e->getMessage() . "
";
}
?>


<?php

// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to register user
function registerUser($username, $email, $password)
{
    global $conn;

    // Hash password using SHA-256
    $hashedPassword = hash('sha256', $password);

    // Prepare SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Bind parameters and execute query
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        $stmt->execute();
        $stmt->close();

        return true;
    } else {
        echo "Error preparing statement: " . $conn->error;
        return false;
    }
}

// Example usage:
$username = "john_doe";
$email = "johndoe@example.com";
$password = "password123";

if (registerUser($username, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Registration failed.";
}
?>


function registerUser($username, $email, $password) {
  // Hash the password for security
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Prepare the SQL query to insert a new user
  $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  
  try {
    // Execute the query with prepared statements
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username, $email, $hashedPassword]);
    
    // Get the ID of the newly inserted user
    $userId = $pdo->lastInsertId();
    
    return [
      'success' => true,
      'message' => 'User registered successfully.',
      'userId' => $userId,
    ];
  } catch (PDOException $e) {
    return [
      'success' => false,
      'message' => 'Error registering user: ' . $e->getMessage(),
    ];
  }
}


$pdo = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if ($result['success']) {
  echo "User registered successfully. ID: " . $result['userId'];
} else {
  echo "Error registering user: " . $result['message'];
}


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
function connectToDatabase() {
  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Register a new user
function registerUser($name, $email, $password) {
  // Create the database connection
  $conn = connectToDatabase();

  // Hash the password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Prepare and execute the SQL query to insert the new user into the database
  $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $email, $hashedPassword);
  if ($stmt->execute()) {
    // If the registration is successful, return true and a success message
    return array(true, "User registered successfully.");
  } else {
    // If there's an error, return false and an error message
    return array(false, "Error registering user: " . $conn->error);
  }

  // Close the database connection
  $conn->close();
}

// Example usage:
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($name, $email, $password);

if ($result[0]) {
  echo "Registration successful!";
} else {
  echo "Error: " . $result[1];
}
?>


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
  // Validate input
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception('All fields are required.');
  }

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Connect to database
  $conn = mysqli_connect("localhost", "username", "password", "database");

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Prepare SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);

  // Bind parameters
  mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);

  // Execute query
  if (mysqli_stmt_execute($stmt)) {
    echo "User registered successfully!";
  } else {
    echo "Error registering user: " . mysqli_error($conn);
  }

  // Close statement and connection
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

try {
  registerUser($username, $email, $password);
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}


function registerUser($username, $email, $password) {
  // Input validation
  if (empty($username) || empty($email) || empty($password)) {
    return array('error' => 'All fields are required');
  }

  if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    return array('error' => 'Username can only contain letters, numbers and underscores');
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return array('error' => 'Invalid email address');
  }

  // Password hashing
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  try {
    // Connect to database
    $dbConnection = new PDO('mysql:host=localhost;dbname=users', 'username', 'password');

    // Prepare and execute query
    $stmt = $dbConnection->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    if ($stmt->execute()) {
      // User registration successful
      return array('success' => 'User registered successfully');
    } else {
      // Error registering user
      return array('error' => 'Error registering user');
    }
  } catch (PDOException $e) {
    // Database error
    return array('error' => 'Database error: ' . $e->getMessage());
  }
}


$data = registerUser('johnDoe', 'johndoe@example.com', 'password123');

if (isset($data['error'])) {
  echo $data['error'];
} else {
  echo $data['success'];
}


<?php

function registerUser($username, $email, $password) {
    // Connect to database
    include_once 'dbconnect.php';
    
    // Check if username is available
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return array("error" => "Username already exists");
    }
    
    // Hash password
    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$passwordHashed')";
    mysqli_query($conn, $query);
    
    return array("message" => "User registered successfully");
}

?>


$registered = registerUser('johnDoe', 'johndoe@example.com', 'mysecretpassword');
if ($registered['error']) {
    echo $registered['error'];
} else {
    echo "Registration successful!";
}


<?php

// Configuration
$host = 'localhost';
$dbName = 'mydatabase';
$username = 'root';
$password = '';

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    // Get user input from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate the input data
    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill in all fields.";
        return false;
    }

    // Check if the username or email is already taken
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "Username or email already exists.";
        return false;
    }

    // Hash the password
    $password = hash('sha256', $password);

    // Insert user data into the database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    $result = $conn->query($query);

    if ($result === TRUE) {
        echo "User registered successfully.";
        return true;
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
        return false;
    }
}

if (isset($_POST['register'])) {
    registerUser();
}

// Close the database connection
$conn->close();

?>


<?php

// Configuration settings
$requiredFields = array('username', 'email', 'password');
$passwordHash = 'sha256'; // Use SHA-256 for password hashing
$dbConnection = mysqli_connect('localhost', 'username', 'password', 'database');

/**
 * Registers a new user.
 *
 * @param string $username The username chosen by the user.
 * @param string $email The email address of the user.
 * @param string $password The password chosen by the user.
 *
 * @return bool True on successful registration, false otherwise.
 */
function registerUser($username, $email, $password) {
    // Validate input
    foreach ($requiredFields as $field) {
        if (empty(${$field})) {
            throw new Exception("Missing required field: $field");
        }
    }

    // Hash password
    $hashedPassword = hash($passwordHash, $password);

    // Prepare and execute SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($dbConnection, $sql);
    mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hashedPassword);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error registering user: " . mysqli_error($dbConnection));
    }

    // Close the prepared statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($dbConnection);

    return true;
}

// Example usage:
try {
    registerUser('johnDoe', 'johndoe@example.com', 'password123');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>


function registerUser($username, $email, $password) {
    // Check if the username and email are not empty
    if (empty($username) || empty($email)) {
        return array('error' => 'Username and Email cannot be empty');
    }

    // Hash the password
    $hashedPassword = hash('sha256', $password);

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "your_database");

    // Check if there's a connection error
    if ($conn->connect_error) {
        return array('error' => 'Connection failed');
    }

    // Create a query to insert the user data into the database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";

    // Execute the query
    if ($conn->query($query)) {
        return array('success' => 'User registered successfully');
    } else {
        return array('error' => 'Failed to register user');
    }

    // Close the connection
    $conn->close();
}

// Example usage:
$result = registerUser("example", "example@example.com", "password123");
print_r($result);


function registerUser($username, $email, $password) {
    // Check if the username and email are not empty
    if (empty($username) || empty($email)) {
        return array('error' => 'Username and Email cannot be empty');
    }

    // Hash the password
    $hashedPassword = hash('sha256', $password);

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "your_database");

    // Check if there's a connection error
    if ($conn->connect_error) {
        return array('error' => 'Connection failed');
    }

    // Create a prepared statement
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

    // Bind the parameters
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    // Execute the query
    if ($stmt->execute()) {
        return array('success' => 'User registered successfully');
    } else {
        return array('error' => 'Failed to register user');
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Example usage:
$result = registerUser("example", "example@example.com", "password123");
print_r($result);


function register_user($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill out all fields');
    }

    // Hash password for storage
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database
    $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');

    try {
        // Prepare SQL query
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        // Execute query
        $stmt->execute();

        return array('success' => 'User registered successfully');
    } catch (PDOException $e) {
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

$result = register_user($username, $email, $password);

if ($result['success']) {
    echo 'User registered successfully!';
} elseif ($result['error']) {
    echo 'Error: ' . $result['error'];
}


<?php

// Configuration settings
$database_host = 'localhost';
$database_username = 'your_username';
$database_password = 'your_password';
$database_name = 'your_database';

// Function to register a new user
function register_user($username, $email, $password) {
  // Check for valid input
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception('All fields are required.');
  }

  // Connect to the database
  $conn = mysqli_connect($database_host, $database_username, $database_password);
  if (!$conn) {
    throw new Exception('Database connection failed.');
  }

  // Query to register a new user
  $query = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '".hash('sha256', $password)."')";
  mysqli_query($conn, $query);

  // Check if the registration was successful
  if (mysqli_affected_rows($conn) > 0) {
    return true;
  } else {
    throw new Exception('Registration failed.');
  }

  // Close the database connection
  mysqli_close($conn);
}

// Example usage:
try {
  $username = 'john_doe';
  $email = 'johndoe@example.com';
  $password = 'password123';

  if (register_user($username, $email, $password)) {
    echo "User registered successfully.";
  } else {
    throw new Exception('Registration failed.');
  }
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}

?>


<?php

// Define the database connection settings
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Create a connection to the database
$connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);

function register_user($username, $email, $password) {
  // Validate the input data
  if (empty($username)) {
    throw new Exception('Username is required');
  }
  if (empty($email)) {
    throw new Exception('Email is required');
  }
  if (empty($password)) {
    throw new Exception('Password is required');
  }

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Prepare and execute the query to insert a new user
  $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
  $stmt = $connection->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $hashed_password);

  try {
    $stmt->execute();
  } catch (PDOException $e) {
    throw new Exception('Error registering user: ' . $e->getMessage());
  }

  // Return the newly created user's ID
  return $connection->lastInsertId();
}

?>


try {
  $new_user_id = register_user('john', 'john@example.com', 'password123');
  echo "User registered with ID: $new_user_id";
} catch (Exception $e) {
  echo "Error registering user: " . $e->getMessage();
}


<?php

function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("All fields are required.");
    }

    // Check if username and email already exist in database
    $db = connectToDatabase(); // Assume this function connects to the database
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        throw new Exception("Username or email already exists.");
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $query = "INSERT INTO users (username, email, password_hash) VALUES ('$username', '$email', '$passwordHash')";
    mysqli_query($db, $query);
}

?>


registerUser('johnDoe', 'johndoe@example.com', 'password123');


function connectToDatabase() {
    $db = new mysqli("localhost", "username", "password", "database_name");
    if ($db->connect_error) {
        throw new Exception("Failed to connect to database.");
    }
    return $db;
}


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

// Configuration settings
$database_name = 'my_database';
$user_table = 'users';

// Function to register new users
function registerUser($username, $email, $password) {
    // Connect to database
    $conn = mysqli_connect('localhost', 'your_username', 'your_password', $database_name);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user data into database
    $sql = "INSERT INTO $user_table (username, email, password)
            VALUES ('$username', '$email', '$hashed_password')";
    
    if (mysqli_query($conn, $sql)) {
        echo "User registered successfully!";
        return true;
    } else {
        echo "Error registering user: " . mysqli_error($conn);
        return false;
    }
    
    // Close database connection
    mysqli_close($conn);
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if (registerUser($username, $email, $password)) {
        // User registered successfully!
    }
}

?>


<?php

// Configuration
$allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
$maxFileSize = 2097152; // 2 MB

function registerUser($firstName, $lastName, $email, $password, $confirmPassword, $username) {
    try {
        // Validate input
        if (empty($firstName)) {
            throw new Exception("First name is required.");
        }

        if (empty($lastName)) {
            throw new Exception("Last name is required.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email address.");
        }

        if ($password !== $confirmPassword) {
            throw new Exception("Passwords do not match.");
        }

        // Hash password
        $hashedPassword = hash('sha256', $password);

        // Connect to database (use your own db connection method)
        // For this example, we'll use a fictional "users" table with columns: id, username, email, password
        $db = new PDO('sqlite:database.db');
        $query = 'INSERT INTO users (username, email, password) VALUES (:username, :email, :password)';
        $stmt = $db->prepare($query);
        $stmt->execute(array(
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword
        ));

        // If the query was successful, the user has been registered
        if ($stmt) {
            return "User registered successfully.";
        } else {
            throw new Exception("Failed to register user.");
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage() . '<br>';
        return false;
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage() . '<br>';
        return false;
    }
}

?>


registerUser("John", "Doe", "john@example.com", "password123", "password123", "johndoe");


<?php

function register_user($username, $email, $password) {
  // Connect to database (assuming MySQL)
  $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');

  // Hash password using SHA-256
  $hashed_password = hash('sha256', $password);

  // Prepare SQL query to insert user data into database
  $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $hashed_password);

  // Execute query
  try {
    $stmt->execute();
    return true;
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    return false;
  }

  // Close database connection
  $db = null;
}

?>


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

if (register_user($username, $email, $password)) {
  echo "User registered successfully!";
} else {
  echo "Error registering user.";
}


$password = password_hash($password, PASSWORD_DEFAULT);


<?php

// Define database connection parameters
$host = 'your_host';
$dbname = 'your_database';
$user = 'your_username';
$password = 'your_password';

// Connect to database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
    exit;
}

// Define registration function
function register($username, $email, $password) {
    // Validate form data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('All fields are required.');
    }
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        throw new Exception('Username can only contain letters and numbers.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address.');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error inserting user: " . $e->getMessage();
        return false;
    }
}

// Example usage
try {
    register($_POST['username'], $_POST['email'], $_POST['password']);
} catch (Exception $e) {
    echo 'Registration failed: ' . $e->getMessage();
}

?>


function registerUser($username, $email, $password) {
  // Validate input data
  if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
    return "Invalid username";
  }
  
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address";
  }
  
  if (strlen($password) < 8) {
    return "Password must be at least 8 characters long";
  }

  // Hash password
  $hashedPassword = hash('sha256', $password);

  // Connect to database
  $conn = new mysqli("localhost", "username", "password", "database");

  // Check if user already exists
  $query = "SELECT * FROM users WHERE username='$username'";
  $result = $conn->query($query);
  
  if ($result->num_rows > 0) {
    return "Username already taken";
  }

  // Insert new user into database
  $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
  $result = $conn->query($query);
  
  if ($result === TRUE) {
    return "User registered successfully";
  } else {
    return "Error registering user: " . $conn->error;
  }
}


$username = "johnDoe";
$email = "johndoe@example.com";
$password = "mysecretpassword";

$result = registerUser($username, $email, $password);

if ($result === "User registered successfully") {
  echo "Registration successful!";
} else {
  echo "Error: " . $result;
}


<?php

// Configuration variables
$required_fields = array('username', 'email', 'password');
$max_password_length = 50;

// Function to register a new user
function register_user($data) {
    // Validate input data
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            throw new Exception("Missing required field: " . $field);
        }
    }

    // Check password length
    if (strlen($data['password']) > $max_password_length) {
        throw new Exception("Password is too long. Maximum length is $max_password_length characters.");
    }

    // Hash the password
    $hashed_password = hash('sha256', $data['password']);

    // Create a new user in the database
    try {
        // Connect to the database (using PDO)
        $conn = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_database_username', 'your_database_password');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert the new user into the users table
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        // Return the newly created user's ID
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Database error: " . $e->getMessage());
    }
}

?>


// Input data from a form submission
$data = array(
    'username' => $_POST['username'],
    'email' => $_POST['email'],
    'password' => $_POST['password']
);

try {
    // Register the new user
    $new_user_id = register_user($data);
    echo "User created successfully. ID: $new_user_id";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


function register_user($name, $email, $password) {
    // Check if email already exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($link, $query);
    if (mysqli_num_rows($result) > 0) {
        return array('error' => 'Email already exists');
    }

    // Hash password using SHA-256
    $hashed_password = hash('sha256', $password);

    // Insert new user into database
    $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
    if (!mysqli_query($link, $query)) {
        return array('error' => 'Error registering user');
    }

    return array('success' => 'User registered successfully');
}


// Assume $link is a valid MySQL connection link
$name = 'John Doe';
$email = 'johndoe@example.com';
$password = 'password123';

$result = register_user($name, $email, $password);
if ($result['success']) {
    echo "User registered successfully!";
} else {
    echo "Error registering user: " . $result['error'];
}


<?php

// Define the database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

// Define the registration form fields
$fields = array(
    'username' => '',
    'email' => '',
    'password' => ''
);

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate the form data
    $errors = array();
    foreach ($fields as $field => $value) {
        if (empty($value)) {
            $errors[] = "$field is required";
        } elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "$field must be a valid email address";
        }
    }

    // Check for password strength
    if (strlen($_POST['password']) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Insert the user into the database
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sss", $_POST['username'], $_POST['email'], $hashed_password);
        if ($stmt->execute()) {
            echo "User registered successfully!";
        } else {
            echo "Error registering user: " . $stmt->error;
        }
    } else {
        // Display the errors
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }
}

?>


function registerUser($username, $email, $password) {
    // Check if the input fields are not empty
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("Please fill out all required fields.");
    }

    // Hash the password using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Connect to the database
    require_once 'database.php';
    $conn = connectToDatabase();

    // Prepare and execute query to insert user into database
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    try {
        // Execute the query and commit changes
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert user into database.");
        }
        $id = $conn->insert_id;

        // Close connection to database
        $conn->close();

        return array('success' => true, 'message' => "User registered successfully.", 'id' => $id);
    } catch (Exception $e) {
        // Roll back changes if there is an error
        $conn->rollBack();
        $conn->close();

        throw new Exception("Error registering user: " . $e->getMessage());
    }
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

try {
    $result = registerUser($username, $email, $password);
    print_r($result);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('All fields are required.');
    }

    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        throw new Exception('Username must contain only letters and numbers.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address.');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database (replace with your own database connection)
    $conn = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    // Return user ID
    return $conn->lastInsertId();
}


try {
    $userId = registerUser('johnDoe', 'johndoe@example.com', 'mysecretpassword');
    echo "User created with ID: $userId";
} catch (Exception $e) {
    echo "Error creating user: " . $e->getMessage();
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
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'your_database_name';

// Create a database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    // Input validation and sanitization
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {

        // Sanitize input data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate email and username
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (strlen($username) > 2 && strlen($username) < 32) {

                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Prepare SQL query to insert user data into database
                $sql = "INSERT INTO users (username, email, password)
                    VALUES (?, ?, ?)";

                // Bind parameters
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hashedPassword);

                    // Execute query
                    if (mysqli_stmt_execute($stmt)) {
                        echo "User registered successfully!";
                    } else {
                        echo "Error registering user: " . mysqli_error($conn);
                    }

                    // Close prepared statement
                    mysqli_stmt_close($stmt);
                }
            } else {
                echo "Username must be between 3 and 32 characters long.";
            }
        } else {
            echo "Invalid email address.";
        }
    } else {
        echo "Missing required fields: username, email, password";
    }

    // Close database connection
    mysqli_close($conn);
}

?>


<?php registerUser(); ?>


<?php
// Configuration variables
$mysql_server = 'localhost';
$mysql_username = 'your_username';
$mysql_password = 'your_password';
$database_name = 'your_database';

// Connect to the database
$conn = new mysqli($mysql_server, $mysql_username, $mysql_password, $database_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function register_user() {
    // Get user input from registration form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password using SHA-256
    $hashed_password = hash('sha256', $password);

    // Prepare SQL query to insert new user into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "User registered successfully!";
    } else {
        echo "Registration failed. Please try again.";
    }
}

// Check if user submitted the registration form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    register_user();
}
?>


<?php
?>

<!-- HTML form for user registration -->
<form action="register.php" method="post">
    <label>Username:</label>
    <input type="text" name="username" required><br><br>
    <label>Email:</label>
    <input type="email" name="email" required><br><br>
    <label>Password:</label>
    <input type="password" name="password" required><br><br>
    <button type="submit">Register</button>
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

// Function to register user
function registerUser($username, $email, $password) {
    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    // Execute query
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill in all fields.";
        exit;
    }

    // Register user
    if (registerUser($username, $email, $password)) {
        echo "Registration successful!";
    } else {
        echo "Error registering user. Please try again.";
    }
}

// Close database connection
$conn->close();
?>


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

// Create database connection
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

function registerUser($data) {
    // Check if all required fields are provided
    if (!isset($data['email']) || !isset($data['username']) || !isset($data['password'])) {
        return array('error' => 'Missing required field(s)');
    }

    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    // Hash password
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    // Insert new user into database
    try {
        $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':password', $hashedPassword);
        $result = $stmt->execute();

        if ($result) {
            return array('success' => true, 'message' => 'User registered successfully');
        } else {
            return array('error' => 'Database error: unable to register user');
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return array('error' => 'Database error: unable to register user');
    }
}

// Example usage
$data = array(
    'email' => 'user@example.com',
    'username' => 'johnDoe',
    'password' => 'password123'
);

$result = registerUser($data);
print_r($result);

?>


function createUser($username, $email, $password) {
    // Check if username or email already exists
    $exists = checkUserExists($username, $email);
    if ($exists) {
        return array('error' => 'Username or Email already exists');
    }

    // Hash password using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Create new user
    $newUser = createNewUser($username, $email, $hashedPassword);

    if ($newUser) {
        return array('success' => 'User created successfully');
    } else {
        return array('error' => 'Failed to create user');
    }
}

function checkUserExists($username, $email) {
    // Database query to check if username or email already exists
    $query = "SELECT * FROM users WHERE username = :username OR email = :email";
    $stmt = dbConnect()->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

function createNewUser($username, $email, $password) {
    // Database query to insert new user
    $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = dbConnect()->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    return $stmt->execute();
}

// Example usage
$userData = array(
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'password123'
);

$result = createUser($userData['username'], $userData['email'], $userData['password']);
print_r($result);


function dbConnect() {
    // Database configuration
    $host = 'localhost';
    $database = 'users_db';
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        return $conn;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
}


<?php

function register_user($data) {
    // Validation
    if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
        return array('error' => 'All fields are required.');
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
        return array('error' => 'Username can only contain letters, numbers and underscores.');
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address.');
    }

    if (strlen($data['password']) < 8) {
        return array('error' => 'Password must be at least 8 characters long.');
    }

    // Hash password
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

    // Insert into database
    try {
        $db = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        return array('success' => 'User registered successfully.');
    } catch (PDOException $e) {
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}

// Example usage
$data = array(
    'username' => 'john_doe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
);

$result = register_user($data);
print_r($result);

?>


function registerUser($userData) {
    // Validate input data
    if (!isset($userData['name']) || !isset($userData['email']) || !isset($userData['password'])) {
        throw new Exception("Invalid input data");
    }

    $name = filter_var($userData['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($userData['email'], FILTER_VALIDATE_EMAIL);

    if (!$email) {
        throw new Exception("Invalid email address");
    }

    // Hash the password
    $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);

    try {
        // Store user data in database (example using PDO)
        $db = new PDO('sqlite:user_database.db');
        $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Return success message
        return array('message' => 'User registered successfully');
    } catch (PDOException $e) {
        // Handle database error
        return array('error' => 'Error registering user: ' . $e->getMessage());
    }
}


$userData = array(
    'name' => 'John Doe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
);

try {
    $result = registerUser($userData);
    print_r($result);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


function registerUser($username, $email, $password) {
  // Validate user input
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception('All fields are required');
  }

  if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
    throw new Exception('Invalid username. Only letters and numbers allowed');
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception('Invalid email address');
  }

  // Hash password
  $password = hash('sha256', $password);

  try {
    // Connect to database
    $dbConnection = mysqli_connect('localhost', 'username', 'password', 'database');

    if (!$dbConnection) {
      throw new Exception('Failed to connect to database');
    }

    // Insert user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    mysqli_query($dbConnection, $query);

    // Close database connection
    mysqli_close($dbConnection);

    return true;
  } catch (Exception $e) {
    throw new Exception('Failed to register user: ' . $e->getMessage());
  }
}


try {
  $registered = registerUser('johnDoe', 'john.doe@example.com', 'password123');
  if ($registered) {
    echo "User registered successfully!";
  } else {
    throw new Exception('Failed to register user');
  }
} catch (Exception $e) {
  echo "Error registering user: " . $e->getMessage();
}


// config.php: database connection settings
$dbhost = 'localhost';
$dbname = 'mydatabase';
$dbuser = 'myusername';
$dbpass = 'mypassword';

function createUser($firstName, $lastName, $email, $password, $confirmPassword)
{
    // Connect to the database using PDO
    try {
        $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare a SQL query to insert user data into the users table
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)");

        // Bind parameters to prevent SQL injection
        $stmt->bindParam(":first_name", $firstName);
        $stmt->bindParam(":last_name", $lastName);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            throw new PDOException($pdo->errorInfo()[2]);
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

// Validate form data before calling createUser function
if (isset($_POST['submit'])) {

    // Get form values from POST request
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        echo "All fields are required.";
        return;
    }

    // Validate email using regular expression
    $emailPattern = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/";
    if (!preg_match($emailPattern, $email)) {
        echo "Invalid email address.";
        return;
    }

    // Validate password length
    if (strlen($password) < 8) {
        echo "Password must be at least 8 characters long.";
        return;
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
        return;
    }

    // Create user account
    $createUserResult = createUser($firstName, $lastName, $email, password_hash($password, PASSWORD_DEFAULT), $confirmPassword);

    if ($createUserResult === true) {
        header('Location: login.php');
        exit();
    } else {
        echo "Account creation failed.";
    }
}
?>


<?php

require_once 'config.php'; // database configuration file

function register($username, $email, $password) {
    global $db;

    // Validate input fields
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all required fields');
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    // Check for existing username and email
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        return array('error' => 'Username already taken');
    }

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        return array('error' => 'Email already in use');
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$passwordHash')";
    if (!mysqli_query($db, $query)) {
        return array('error' => 'Failed to register user');
    }

    return array('success' => true);
}

?>


<?php

require_once 'register.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = register($username, $email, $password);

if ($result['success']) {
    echo "User registered successfully!";
} else {
    echo "Error: " . $result['error'];
}

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
// Configuration settings (replace with your database credentials)
$DB_HOST = 'localhost';
$DB_USERNAME = 'root';
$DB_PASSWORD = 'password';
$DB_NAME = 'your_database_name';

function dbConnect() {
    $conn = new mysqli($GLOBALS['DB_HOST'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function dbDisconnect($conn) {
    $conn->close();
}

// Function to register a new user
function createUser($name, $email, $password) {
    // Validate input data
    if (empty($name) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required.');
    }

    // Check for existing users with the same email address
    $conn = dbConnect();
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return array('error' => 'Email already exists.');
    }

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $sql = "INSERT INTO users (name, email, password_hash) VALUES ('$name', '$email', '$passwordHash')";
    if ($conn->query($sql)) {
        return array('success' => 'User created successfully.');
    } else {
        return array('error' => 'Failed to create user.');
    }

    dbDisconnect($conn);
}

// Example usage
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = createUser($name, $email, $password);

if ($result['success']) {
    echo "User created successfully!";
} elseif ($result['error']) {
    echo "Error: " . $result['error'];
}
?>


<?php
// Configuration settings for database connection and email sending
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database_name';

function registerUser() {
    // Get form data from the HTML form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "Please fill in all fields.";
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        return;
    }

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        return;
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database
    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    if ($conn->connect_error) {
        echo "Failed to connect to database: " . $conn->connect_error;
        return;
    }

    // Create user account in the database
    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$hashedPassword')";
    if (!$conn->query($sql)) {
        echo "Failed to create user account.";
        return;
    }

    // Send a confirmation email to the new user
    $toEmail = $email;
    $subject = 'Account Confirmation';
    $body = 'Hello, your account has been created successfully!';
    $fromEmail = 'your_email@example.com';

    try {
        mail($toEmail, $subject, $body, "From: $fromEmail");
        echo "User registered successfully!";
    } catch (Exception $e) {
        echo "Error sending email: " . $e->getMessage();
    }

    // Close database connection
    $conn->close();
}

// Check if the registration form has been submitted
if (isset($_POST['register'])) {
    registerUser();
}
?>


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function register_user() {
    global $conn;

    // Get user input
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username or email already exists in database
    $query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "Error: Username or Email already taken.";
        return;
    }

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    $conn->query($query);

    echo "User registered successfully!";
}

// Check if POST request is made to register.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    register_user();
} else {
    // Display registration form
    ?>
    <h1>Register</h1>
    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username"><br><br>
        <label for="email">Email:</label>
        <input type="email" name="email"><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Register">
    </form>
    <?php
}

?>


<?php

// Configuration variables
$minUsernameLength = 3;
$maxUsernameLength = 20;
$minPasswordLength = 8;

function registerUser() {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        header('Location: index.php');
        exit;
    }

    // Sanitize and validate form data
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING);
    $password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING);

    if ($username === null || $password1 === null || $password2 === null) {
        return array('error' => 'Missing fields');
    }

    // Validate username length
    if (strlen($username) < $minUsernameLength || strlen($username) > $maxUsernameLength) {
        return array('error' => 'Invalid username length');
    }

    // Validate password lengths
    if (strlen($password1) < $minPasswordLength || strlen($password2) < $minPasswordLength) {
        return array('error' => 'Passwords must be at least ' . $minPasswordLength . ' characters long');
    }

    // Check for matching passwords
    if ($password1 !== $password2) {
        return array('error' => 'Passwords do not match');
    }

    // Create new user
    try {
        // Connect to database
        $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');

        // Create query
        $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', hash('sha256', $password1));

        // Execute query
        $stmt->execute();

        // Close database connection
        $db = null;

        return array('success' => 'User created successfully');
    } catch (PDOException $e) {
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}

// Check for POST request and call registerUser function
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = registerUser();
    if (isset($result['success'])) {
        echo json_encode(array('message' => $result['success']));
    } else {
        echo json_encode(array('error' => $result['error']));
    }
} else {
    // Display registration form
    ?>
    <h1>Register</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br><br>
        <label for="password1">Password:</label>
        <input type="password" id="password1" name="password1"><br><br>
        <label for="password2">Confirm Password:</label>
        <input type="password" id="password2" name="password2"><br><br>
        <button type="submit">Register</button>
    </form>
    <?php
}
?>


<?php

// Configuration settings for database connection
$host = 'your_host';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser($name, $email, $password, $confirmPassword)
{
    // Hash the password using SHA-256
    $hashedPassword = hash('sha256', $password);

    // Check if email is already registered
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return array("error" => "Email already exists");
    }

    // Register new user
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
    if ($conn->query($sql) === TRUE) {
        return array("success" => true);
    } else {
        return array("error" => "Error registering user");
    }
}

// Example usage
$name = 'John Doe';
$email = 'john@example.com';
$password = 'password123';
$confirmPassword = 'password123';

$response = registerUser($name, $email, $password, $confirmPassword);

if ($response['success']) {
    echo "User registered successfully!";
} elseif ($response['error']) {
    echo "Error: " . $response['error'];
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

function registerUser() {
    // Check if all fields are filled
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        $result = $stmt->execute();

        if ($result) {
            echo "User registered successfully!";
        } else {
            echo "Error registering user: " . $stmt->error;
        }
    } else {
        echo "Please fill in all fields";
    }
}

// Check for POST request
if (isset($_POST['register'])) {
    registerUser();
}

?>


function registerUser($username, $email, $password) {
    // Database connection settings
    $dbHost = 'your_host';
    $dbUsername = 'your_username';
    $dbPassword = 'your_password';
    $dbName = 'your_database';

    try {
        // Create a new PDO object
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);

        // Prepare SQL statement for inserting user data
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', hash('sha256', $password));

        // Execute the prepared statement
        if ($stmt->execute()) {
            return true; // User registered successfully!
        } else {
            throw new Exception("Error registering user");
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        return false;
    }
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

if (registerUser($username, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Error registering user";
}


function registerUser($username, $email, $password) {
    // Validate inputs
    if (empty($username)) {
        throw new Exception('Username is required');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    // Hash password using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to database ( assumed to be a MySQL database )
        $conn = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

        // Prepare and execute INSERT statement
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute statement and get ID of newly inserted user
        $conn->exec($stmt);

        // Close connection
        $conn = null;

        return true;
    } catch (PDOException $e) {
        // Handle database errors
        throw new Exception('Failed to register user: ' . $e->getMessage());
    }
}


try {
    $registered = registerUser('johnDoe', 'johndoe@example.com', 'password123');
    if ($registered) {
        echo "User registered successfully!";
    } else {
        echo "Failed to register user";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define'type( DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to register a new user
function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array(false, "Please fill in all fields.");
    }

    // Connect to database
    $conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    if (!mysqli_stmt_prepare($conn, $query)) {
        die("Error preparing statement: " . mysqli_error($conn));
    }
    $stmt = mysqli_stmt_bind_param($conn, 'sss', $username, $email, $hashedPassword);
    if (!mysqli_stmt_execute($stmt)) {
        die("Error executing query: " . mysqli_error($conn));
    }

    // Get new user ID
    $newUserId = mysqli_insert_id($conn);

    // Close connection
    mysqli_close($conn);

    return array(true, $newUserId);
}

// Handle form submission
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = registerUser($username, $email, $password);

    if ($result[0]) {
        echo "Registration successful! Your new user ID is: " . $result[1];
    } else {
        echo "Error registering user: " . $result[1];
    }
}

?>


<?php

// Configuration settings
$requiredFields = array('username', 'email', 'password');
$minPasswordLength = 8;

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input data
    $username = trim($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);

    // Validate user input data
    if (!in_array($username, array_filter($requiredFields))) {
        echo "Please fill out all required fields.";
        return;
    }

    if (strlen($password) < $minPasswordLength) {
        echo "Your password must be at least $minPasswordLength characters long.";
        return;
    }

    // Hash the user's password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database (replace with your own connection code)
    require_once 'db.php';
    $conn = new PDO('sqlite:database.db');

    // Insert new user into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    // Close database connection
    $conn = null;

    // Display success message and redirect to login page
    echo "Registration successful! Please log in.";
    header('Location: login.php');
    exit;
}

?>

<!-- Registration form HTML -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <button type="submit">Register</button>
</form>


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

// Define the database connection details
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    global $conn;

    // Get the user's details from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if the password and confirm password match
    if ($password != $confirmPassword) {
        echo "Passwords do not match";
        return;
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user's details into the database
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
    if ($conn->query($sql) === TRUE) {
        echo "User registered successfully!";
    } else {
        echo "Error registering user: " . $conn->error;
    }
}

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    registerUser();
}

?>


function registerUser($data) {
    // Input Validation
    if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
        return array('success' => false, 'message' => 'Invalid input data');
    }

    $username = trim($data['username']);
    $email = trim($data['email']);
    $password = trim($data['password']);

    // Validate Username
    if (strlen($username) < 3 || strlen($username) > 32) {
        return array('success' => false, 'message' => 'Username must be between 3 and 32 characters long');
    }

    // Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('success' => false, 'message' => 'Invalid email address');
    }

    // Hash Password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Create User Record in Database ( example using PDO )
        $db = new PDO('mysql:host=localhost;dbname=yourdatabase', 'username', 'password');
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        return array('success' => true, 'message' => 'User created successfully');
    } catch (PDOException $e) {
        return array('success' => false, 'message' => 'Database error: ' . $e->getMessage());
    }
}


$data = array(
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
);

$result = registerUser($data);
if ($result['success']) {
    echo "User created successfully!";
} else {
    echo "Error: " . $result['message'];
}


<?php
require_once 'dbconfig.php'; // include database connection settings

function registerUser($username, $email, $password) {
    // validate user input
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    // check for existing user with same username or email
    $query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = mysqli_query($GLOBALS['conn'], $query);
    if (mysqli_num_rows($result) > 0) {
        return array('error' => 'Username or email already taken');
    }

    // hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // store new user details in database
    $query = "INSERT INTO users (username, email, password)
              VALUES ('$username', '$email', '$passwordHash')";
    mysqli_query($GLOBALS['conn'], $query);

    return array('success' => 'User registered successfully');
}

// example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = registerUser($username, $email, $password);
    if (isset($result['error'])) {
        echo '<p style="color: red;">' . $result['error'] . '</p>';
    } else {
        echo '<p>' . $result['success'] . '</p>';
    }
}
?>


<?php

// Database connection settings
$database_host = 'your_database_host';
$database_username = 'your_database_username';
$database_password = 'your_database_password';
$database_name = 'your_database_name';

// Create database connection
$mysqli = new mysqli($database_host, $database_username, $database_password, $database_name);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

function register_user() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Form validation
        if (empty($username) || empty($email) || empty($password)) {
            return array('error' => 'All fields are required.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array('error' => 'Invalid email address.');
        }

        if ($password != $confirm_password) {
            return array('error' => 'Passwords do not match.');
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // SQL query to insert user data into database
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        $result = $stmt->execute();

        if ($result) {
            return array('success' => 'User registered successfully.');
        } else {
            return array('error' => 'Failed to register user.');
        }
    }

    // If form data is not posted, display registration form
    return '<form action="" method="post">
                <label>Username:</label>
                <input type="text" name="username"><br><br>
                <label>Email:</label>
                <input type="email" name="email"><br><br>
                <label>Password:</label>
                <input type="password" name="password"><br><br>
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password"><br><br>
                <input type="submit" value="Register">
            </form>';
}

// Call the function to register user
echo register_user();

?>


<?php
// Configuration settings
$host = "localhost";
$dbname = "your_database_name";
$username = "your_username";
$password = "your_password";

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to register a user
function registerUser() {
    global $conn;

    if (isset($_POST['register'])) {

        // Sanitize the input data
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Validate user input
        if (empty($username) || empty($email) || empty($password)) {
            echo "Please fill in all fields";
            return false;
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Query to insert new user into database
        $sql = "INSERT INTO users (username, email, password)
                VALUES ('$username', '$email', '$hashedPassword')";

        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

    }
}

// Call the registerUser function
registerUser();
?>


function register_user($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required');
    }

    // Check for duplicate username or email
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($GLOBALS['db'], $query);
    if (mysqli_num_rows($result) > 0) {
        return array('error' => 'Username or email already exists');
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    mysqli_query($GLOBALS['db'], $query);

    return array('success' => 'User created successfully');
}


$db = new mysqli('localhost', 'username', 'password', 'database');

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = register_user($username, $email, $password);

if ($result['success']) {
    echo "User created successfully!";
} else {
    echo "Error: " . $result['error'];
}


<?php

// Configuration variables
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to register a new user
function registerUser($username, $password, $email) {
    try {
        // Connect to the database
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
        
        // Hash the password for security
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $stmt = $conn->prepare('INSERT INTO users (username, password_hash, email) VALUES (:username, :passwordHash, :email)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':passwordHash', $passwordHash);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Generate a verification token
        $verificationToken = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 32);

        // Send a verification email to the user
        sendVerificationEmail($username, $email, $verificationToken);

        return array(
            'status' => true,
            'message' => 'Registration successful. Check your email for verification.'
        );
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return array(
            'status' => false,
            'message' => 'Database error. Please try again later.'
        );
    }
}

// Function to send a verification email
function sendVerificationEmail($username, $email, $verificationToken) {
    // Your mail configuration here...
    $to = $email;
    $subject = "Verify your account";
    $message = "
    Dear $username,
    
    To verify your account, please click on the following link:
    http://your_website.com/verify/$verificationToken
    
    Best regards,
    Your Website
    ";
    mail($to, $subject, $message);
}

// Example usage:
$username = 'newuser';
$password = 'password123';
$email = 'newuser@example.com';

$result = registerUser($username, $password, $email);

echo json_encode($result);

?>


// Define the database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Create a PDO object to connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

function registerUser($name, $email, $password, $confirm_password) {
    // Validate the input data
    if (!$name || !$email || !$password || !$confirm_password) {
        return array('error' => 'All fields are required.');
    }

    if ($password !== $confirm_password) {
        return array('error' => 'Passwords do not match.');
    }

    // Hash the password using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insert the user data into the database
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return array('message' => 'User registered successfully.');
        } else {
            return array('error' => 'Failed to register user.');
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return array('error' => 'Failed to register user.');
    }
}

// Example usage
$name = 'John Doe';
$email = 'john@example.com';
$password = 'password123';
$confirm_password = 'password123';

$result = registerUser($name, $email, $password, $confirm_password);
print_r($result);


<?php

// Configuration settings
$config = array(
    'database' => array(
        'host' => 'localhost',
        'username' => 'your_username',
        'password' => 'your_password',
        'database' => 'your_database'
    ),
    'hash_salt' => 'your_hash_salt' // Keep this secret!
);

// Function to register a new user
function registerUser($firstName, $lastName, $email, $password) {
    global $config;

    // Validate input data
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    // Hash password
    $hashedPassword = hash('sha256', $password . $config['hash_salt']);

    try {
        // Connect to database
        $conn = new PDO('mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['database'], $config['database']['username'], $config['database']['password']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute query
        $stmt = $conn->prepare('INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)');
        $stmt->bindParam(1, $firstName);
        $stmt->bindParam(2, $lastName);
        $stmt->bindParam(3, $email);
        $stmt->bindParam(4, $hashedPassword);
        $stmt->execute();

        // Close connection
        $conn = null;

        return array('success' => 'User registered successfully');
    } catch (PDOException $e) {
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}

// Call the function and print result
$result = registerUser($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password']);

print_r($result);

?>


<?php

// Configuration variables
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'mydatabase';

function registerUser($name, $email, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password_hash')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}

?>


registerUser('John Doe', 'john@example.com', 'mysecretpassword');


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

// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

try {
    // Establish database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // User registration function
    function registerUser($conn, $name, $email, $password) {
        // Validate input data
        if (empty($name) || empty($email) || empty($password)) {
            throw new Exception('Please fill in all fields');
        }

        // Check email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address');
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception('Error registering user');
        }
    }

    // Example usage:
    try {
        registerUser($conn, 'John Doe', 'johndoe@example.com', 'password123');

        echo "User registered successfully";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}


<?php

// Configuration variables
$required_fields = array('username', 'email', 'password');
$max_password_length = 128;

function register_user($data) {
    // Validate the data
    if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
        throw new Exception('Missing required fields');
    }

    // Check for valid email address
    $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        throw new Exception('Invalid email address');
    }

    // Check password length
    if (strlen($data['password']) > $max_password_length) {
        throw new Exception('Password too long');
    }

    // Hash the password
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

    // Store user data in database (example using PDO)
    try {
        $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception('Database error');
    }
}

// Example usage:
$data = array(
    'username' => 'john_doe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
);
try {
    register_user($data);
    echo "User registered successfully";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'database');

// Function to register new users
function registerUser($username, $email, $password) {
  // Check if username and email are valid (e.g. not empty)
  if (!$username || !$email) {
    return array(
      'success' => false,
      'error' => 'Invalid username or email'
    );
  }

  // Connect to database
  try {
    $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
  } catch (PDOException $e) {
    return array(
      'success' => false,
      'error' => 'Database connection failed: ' . $e->getMessage()
    );
  }

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  try {
    $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    // Return success response
    return array(
      'success' => true,
      'message' => 'User registered successfully'
    );
  } catch (PDOException $e) {
    return array(
      'success' => false,
      'error' => 'Database error: ' . $e->getMessage()
    );
  }

  // Close database connection
  $conn = null;
}

// Call the function with POST data from form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $response = registerUser($username, $email, $password);
  echo json_encode($response);
}

?>


<?php

// Include connection to MySQL database
require_once 'db_config.php';

function registerUser($username, $email, $password, $firstname, $lastname) {
    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($firstname) || empty($lastname)) {
        throw new Exception("All fields must be filled.");
    }

    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Prepare and execute query to add user to database
        $stmt = $conn->prepare('INSERT INTO users (username, email, password, firstname, lastname) VALUES (:username, :email, :password, :firstname, :lastname)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->execute();

        // If query executes successfully, return user ID
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Database error: " . $e->getMessage());
    }
}

?>


// Assuming you're using this function in a larger script or application

try {
    // Get input from form submission, for example
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    // Call the function to register user
    $newUserId = registerUser($username, $email, $password, $firstname, $lastname);

    echo "New user registered with ID: $newUserId";
} catch (Exception $e) {
    echo "Registration failed: " . $e->getMessage();
}


function registerUser($username, $email, $password) {
    // Check if the username is valid (at least 3 characters)
    if (strlen($username) < 3) {
        throw new Exception('Username must be at least 3 characters long');
    }

    // Check if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    // Hash the password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database (replace with your own credentials)
    $dbHost = 'localhost';
    $dbName = 'mydatabase';
    $dbUser = 'root';
    $dbPass = '';

    try {
        // Establish a new connection
        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create the user
        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the query
        if (!$stmt->execute()) {
            throw new Exception('Failed to create user');
        }

        return true; // User created successfully

    } catch (PDOException $e) {
        // Handle database errors
        echo 'Database error: ' . $e->getMessage();
        return false;

    } finally {
        if ($conn !== null) {
            // Close the connection
            $conn = null;
        }
    }
}


try {
    $username = 'johnDoe';
    $email = 'johndoe@example.com';
    $password = 'mysecretpassword';

    if (registerUser($username, $email, $password)) {
        echo 'User created successfully!';
    } else {
        throw new Exception('Failed to create user');
    }

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}


<?php

// Set up error messages array
$errors = [];

// Define database connection settings (replace with your own)
$dbHost = 'localhost';
$dbUsername = 'username';
$dbPassword = 'password';
$dbName = 'database';

// Create a new PDO object for database connection
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
    exit;
}

// Function to register a user
function registerUser($username, $email, $password)
{
    global $pdo;

    // Check if username or email already exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=:username OR email=:email");
    $stmt->execute([':username' => $username, ':email' => $email]);
    $userExists = $stmt->fetch();

    // Check if user data is valid
    if ($userExists) {
        return ['error' => 'Username or email already exists'];
    }

    // Hash password before storing in database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->execute([':username' => $username, ':email' => $email, ':password' => $hashedPassword]);
    } catch (PDOException $e) {
        echo "Error registering user: " . $e->getMessage();
        return ['error' => 'Database error'];
    }

    // Return success message
    return ['success' => true];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        // Validate input data
        if (strlen($username) < 3 || strlen($username) > 30) {
            $errors[] = 'Username must be between 3 and 30 characters';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address';
        }
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }

        // If no errors, register the user
        if (empty($errors)) {
            $result = registerUser($username, $email, $password);
            if ($result['success']) {
                echo "User registered successfully!";
            } elseif ($result['error']) {
                echo $result['error'];
            }
        } else {
            // Display error messages
            echo 'Error: ';
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
        }
    } else {
        echo 'Please fill in all required fields!';
    }
}

?>


function registerUser($username, $email, $password) {
  // Database connection settings
  $host = 'localhost';
  $dbname = 'mydatabase';
  $user = 'myuser';
  $pass = 'mypassword';

  try {
    // Connect to database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
      throw new Exception('All fields must be filled in.');
    }

    // Check for existing user with same username or email address
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->fetch()) {
      throw new Exception('Username or email address already in use.');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    return true;

  } catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
    return false;
  } catch (Exception $e) {
    echo "Error registering user: " . $e->getMessage();
    return false;
  }
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

if (registerUser($username, $email, $password)) {
  echo "User registered successfully!";
} else {
  echo "Error registering user.";
}


function register_user($username, $email, $password) {
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill out all fields');
    }

    // Hash password using bcrypt
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database
    $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');

    // Prepare and execute SQL query
    $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    try {
        $stmt->execute();
        return array('success' => true, 'message' => 'User created successfully');
    } catch (PDOException $e) {
        return array('error' => 'Error creating user: ' . $e->getMessage());
    }
}


$user_data = register_user('john_doe', 'johndoe@example.com', 'mysecretpassword');
if ($user_data['success']) {
    echo 'User created successfully!';
} else {
    echo $user_data['error'];
}


<?php

// Configuration settings
define('MIN_USERNAME_LENGTH', 3);
define('MAX_USERNAME_LENGTH', 20);
define('MIN_PASSWORD_LENGTH', 6);
define('MAX_PASSWORD_LENGTH', 40);

function registerUser($username, $email, $password) {
    // Input validation
    if (strlen($username) < MIN_USERNAME_LENGTH || strlen($username) > MAX_USERNAME_LENGTH) {
        throw new InvalidArgumentException("Username must be between " . MIN_USERNAME_LENGTH . " and " . MAX_USERNAME_LENGTH . " characters.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException("Invalid email address.");
    }
    if (strlen($password) < MIN_PASSWORD_LENGTH || strlen($password) > MAX_PASSWORD_LENGTH) {
        throw new InvalidArgumentException("Password must be between " . MIN_PASSWORD_LENGTH . " and " . MAX_PASSWORD_LENGTH . " characters.");
    }

    // Connect to database
    $db = new PDO('sqlite:users.db');

    try {
        // Insert user into database
        $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $passwordHash = hash('sha256', $password); // Use a secure password hashing algorithm
        $stmt->bindParam(':password', $passwordHash);
        $stmt->execute();

        // Commit changes and close database connection
        $db->commit();
        $db = null;

        return true;
    } catch (PDOException $e) {
        // Rollback changes if an error occurs
        $db->rollBack();
        throw new RuntimeException("Failed to register user: " . $e->getMessage());
    }
}

// Example usage:
try {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (registerUser($username, $email, $password)) {
        echo "User registered successfully!";
    } else {
        echo "Registration failed.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser($email, $password)
{
    // Hash and salt password
    $hashedPassword = hash('sha256', $password . 'your_salt');

    // Prepare query
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashedPassword);

    try {
        // Execute query
        $stmt->execute();

        return true;
    } catch (\Exception $e) {
        echo "Error registering user: " . $e->getMessage();
        return false;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}

?>


<?php

require 'user_registration.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $registered = registerUser($email, $password);
        if ($registered) {
            echo "User registered successfully!";
        } else {
            echo "Error registering user. Please try again.";
        }
    } else {
        echo "Please fill in both email and password fields.";
    }
}

?>


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

// Database configuration
$dbhost = 'localhost';
$dbname = 'registration_db';
$dbuser = 'your_username';
$dbpass = 'your_password';

// Connect to the database
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function createUser($name, $email, $password) {
    global $conn;

    // SQL query for creating a new user
    $sql = "INSERT INTO users (name, email, password)
            VALUES ('$name', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

function validateForm() {
    global $_POST;

    // Validate user data
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        echo "Please fill in all the fields.";
        return false;
    }

    if ($_POST['password'] != $_POST['confirm_password']) {
        echo "Password and confirm password do not match.";
        return false;
    }

    return true;
}

function registerUser() {
    global $_POST;

    // Get user data from form
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate the form data
    if (validateForm()) {
        // Create a new user
        createUser($name, $email, md5($password));
        echo "User created successfully!";
    } else {
        return false;
    }
}

// Check if the form has been submitted
if (!empty($_POST)) {
    registerUser();
} else {
    ?>
    <form action="" method="post">
        Name: <input type="text" name="name"><br><br>
        Email: <input type="email" name="email"><br><br>
        Password: <input type="password" name="password"><br><br>
        Confirm Password: <input type="password" name="confirm_password"><br><br>
        <input type="submit" value="Register">
    </form>
    <?php
}
?>


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function registerUser($name, $email, $password) {
  // Connect to database
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Hash password using SHA-256
  $hashedPassword = hash('sha256', $password);

  // Prepare SQL query to insert new user
  $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

  // Execute query with prepared statement
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $name, $email, $hashedPassword);

  if ($stmt->execute()) {
    echo "User registered successfully!";
  } else {
    echo "Error registering user: " . $conn->error;
  }

  // Close connection
  $conn->close();
}

// Example usage:
registerUser('John Doe', 'john@example.com', 'mysecretpassword');

?>


function registerUser($username, $email, $password)
{
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("All fields are required.");
    }

    // Check for existing username or email
    if ($this->getUserByUsername($username) !== null) {
        throw new Exception("Username already exists.");
    }
    if ($this->getUserByEmail($email) !== null) {
        throw new Exception("Email already exists.");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Store user data in database
    try {
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        throw new Exception("Error storing user data: " . $e->getMessage());
    }
}

// Helper functions to check for existing username or email
function getUserByUsername($username)
{
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    return $stmt->fetch();
}

function getUserByEmail($email)
{
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetch();
}


class UserRegistration {
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // ... registerUser function defined above ...
}

// Create a PDO instance and pass it to the UserRegistration class
$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

$userRegistration = new UserRegistration($pdo);

try {
    // Register a new user
    $userRegistration->registerUser('johnDoe', 'johndoe@example.com', 'password123');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


// db.php (database connection file)
$dsn = 'mysql:host=localhost;dbname=mydb';
$username = 'myuser';
$password = 'mypassword';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

function registerUser($data)
{
    // Input validation
    if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
        return array('error' => 'Please fill in all fields');
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    // Prepare and execute query to insert user into database
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        return array('success' => 'User registered successfully');
    } catch (PDOException $e) {
        echo 'Error registering user: ' . $e->getMessage();
        return array('error' => 'Error registering user');
    }
}

// Example usage:
$data = array(
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
);

$result = registerUser($data);
print_r($result);


<?php

// Configuration settings
require_once 'config.inc.php';

// Set up PDO connection
$dsn = "mysql:host={$database['host']};dbname={$database['database']}";
$username = $database['username'];
$password = $database['password'];

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Function to register a user
function registerUser() {
    global $pdo;

    // Validate form data
    if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
        throw new Exception('Please fill in all fields');
    }

    // Hash password
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $_POST['username'],
            ':email' => $_POST['email'],
            ':password' => $hashedPassword
        ]);

        echo "User created successfully!";
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') { // Duplicate entry error for email uniqueness check
            throw new Exception('Email already exists');
        }
        echo "Error creating user: " . $e->getMessage();
    }

    return true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    registerUser();
}


<form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username"><br><br>

    <label for="email">Email:</label>
    <input type="email" name="email"><br><br>

    <label for="password">Password:</label>
    <input type="password" name="password"><br><br>

    <button type="submit" name="register">Register</button>
</form>


<?php

function registerUser($username, $email, $password) {
  // Connect to database (assuming you're using MySQL)
  $conn = mysqli_connect("localhost", "username", "password", "database");

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Prepare SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);

  // Bind parameters
  mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);

  // Execute query
  if (!mysqli_stmt_execute($stmt)) {
    die("Query failed: " . mysqli_error());
  }

  // Get ID of newly inserted user
  $id = mysqli_insert_id($conn);

  // Close connection and statement
  mysqli_close($conn);
  mysqli_stmt_close($stmt);

  return array(
    'success' => true,
    'message' => 'User registered successfully',
    'user_id' => $id
  );
}

?>


// Assume we have a form with input fields for username, email, and password

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get submitted values
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Hash password (using PHP's built-in hash library)
  $password = hash('sha256', $password);

  // Call registration function
  $result = registerUser($username, $email, $password);

  if ($result['success']) {
    echo "User registered successfully!";
  } else {
    echo "Error: " . $result['message'];
  }
}


// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'your_database_name');

function registerUser($username, $email, $password) {
    // Connect to database
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare query
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    // Execute query
    if ($stmt->execute()) {
        echo "User registered successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    // Close connection and statement
    $conn->close();
    $stmt->close();

    return true;
}

// Example usage:
$username = 'newuser';
$email = 'newuser@example.com';
$password = password_hash('password123', PASSWORD_DEFAULT);

registerUser($username, $email, $password);


<?php

// Configuration variables
require_once 'config.php';

// Validation errors array
$errors = array();

// Registration form data
if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  // Validate user input
  if (empty($username) || empty($email) || empty($password)) {
    $errors[] = 'Please fill out all fields';
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email address';
  }

  if ($password !== $confirm_password) {
    $errors[] = 'Passwords do not match';
  }

  // Hash password
  if (empty($errors)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    try {
      // Connect to database and insert new user record
      $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

      if ($conn) {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        mysqli_query($conn, $sql);
        echo 'User registered successfully!';
      } else {
        throw new Exception('Error connecting to database');
      }
    } catch (Exception $e) {
      echo 'An error occurred while registering user: ' . $e->getMessage();
    }
  }
}

?>

<!-- Registration form -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label>Username:</label>
  <input type="text" name="username"><br><br>
  <label>Email:</label>
  <input type="email" name="email"><br><br>
  <label>Password:</label>
  <input type="password" name="password"><br><br>
  <label>Confirm Password:</label>
  <input type="password" name="confirm_password"><br><br>
  <?php if (!empty($errors)) : ?>
    <ul style="color: red;">
      <?php foreach ($errors as $error) : ?>
        <li><?php echo $error; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
  <input type="submit" name="submit" value="Register">
</form>


<?php

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

?>


function register_user($name, $email, $password) {
    // Database connection
    include_once 'db_config.php';

    try {
        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Execute statement with hashed password
        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (empty($name) || empty($email) || empty($password)) {
        echo "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
    } elseif (strlen($password) < 8) {
        echo "Password must be at least 8 characters long.";
    } else {
        // Call registration function
        if (register_user($name, $email, $password)) {
            echo "User registered successfully!";
        }
    }
}
?>


// Create a new user named "John Doe"
register_user('John Doe', 'john.doe@example.com', 'mysecretpassword');


function registerUser($username, $email, $password) {
    // Input Validation
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('All fields are required');
    }

    // Password Hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to database
        require_once 'db.php';
        $conn = connectToDatabase();

        // Check if user already exists
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            throw new Exception('Email already in use');
        }

        // Register user
        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$username, $email, $hashedPassword]);

        // Send verification email
        sendVerificationEmail($email);

        return true;
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }
}


function sendVerificationEmail($email) {
    // Generate verification code
    $verificationCode = bin2hex(random_bytes(16));

    try {
        // Connect to database
        require_once 'db.php';
        $conn = connectToDatabase();

        // Update user's verification status
        $stmt = $conn->prepare('UPDATE users SET verified = 1 WHERE email = ?');
        $stmt->execute([$email]);

        // Send email using PHPMailer or similar library
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';
        $mail->Port = 587;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_password';
        $mail->setFrom('your_email@example.com', 'Your Name');
        $mail->addAddress($email);
        $mail->Subject = 'Verify your account';
        $mail->Body = '
            Click on this link to verify your account:
            <a href="https://example.com/verify.php?code=' . $verificationCode . '">Verify</a>
        ';
        $mail->send();
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }
}


try {
    registerUser('johnDoe', 'johndoe@example.com', 'password123');
} catch (Exception $e) {
    echo $e->getMessage();
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

// Configuration
$config = array(
    'db_host' => 'localhost',
    'db_username' => 'your_username',
    'db_password' => 'your_password',
    'db_name' => 'your_database'
);

function registerUser($username, $email, $password) {
    // Connect to the database
    $conn = new mysqli($config['db_host'], $config['db_username'], $config['db_password'], $config['db_name']);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if user already exists
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return false; // User already exists
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    if (!$conn->query($query)) {
        return false; // Error inserting user
    }

    return true; // User registered successfully
}

// Example usage:
$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

if (registerUser($username, $email, $password)) {
    echo "User $username registered successfully!";
} else {
    echo "Registration failed.";
}
?>


// config.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// register.php
require_once 'config.php';

function registerUser($email, $username, $password) {
    // Sanitize input
    $email = mysqli_real_escape_string($conn, $email);
    $username = mysqli_real_escape_string($conn, $username);

    // Hash password with bcrypt
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$hashedPassword')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Check for POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get post data
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Register user
    registerUser($email, $username, $password);
} else {
    echo 'Invalid request method';
}


// Example of how to implement email validation using filter_var function
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email';
} else {
    registerUser($email, $username, $password);
}


$stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $username, $hashedPassword);


function registerUser($name, $email, $password) {
    /**
     * Register a new user.
     *
     * @param string $name User's full name.
     * @param string $email User's email address.
     * @param string $password User's password (hashed).
     *
     * @return bool Whether the registration was successful.
     */
    try {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Create a new user entry in the database
        $db = connectToDatabase(); // Assume this function connects to the DB and returns a PDO object
        $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->execute();

        // Return true to indicate successful registration
        return true;
    } catch (PDOException $e) {
        // Log any errors that occur during the process
        error_log("Error registering user: " . $e->getMessage());
        return false; // Return false to indicate failed registration
    }
}


$name = 'John Doe';
$email = 'john@example.com';
$password = 'password123';

if (registerUser($name, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Error registering user.";
}


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function registerUser($username, $email, $password) {
    // Connect to database
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query to insert user data into database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Bind parameters and execute query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    $result = $stmt->execute();

    if (!$result) {
        echo "Error inserting user data: " . $conn->error;
        return false;
    }

    // Close database connection
    $conn->close();

    return true;
}

?>


// Assume this is in a separate file, e.g. register.php

require_once 'register.php';

if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        if (registerUser($username, $email, $password)) {
            echo "Registration successful!";
        } else {
            echo "Error registering user.";
        }
    } else {
        echo "Please fill in all fields.";
    }
} else {
    // Display registration form
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
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


<?php

// Configuration variables
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to register a user
function register_user() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the posted data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate the input data
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            echo "Please fill in all fields.";
            return;
        }

        if ($password !== $confirm_password) {
            echo "Passwords do not match.";
            return;
        }

        // Check if the username is already taken
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "Username already exists.";
            return;
        }

        // Hash the password
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user data into the database
        $sql = "INSERT INTO users (username, email, password)
                VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "User created successfully.";
        } else {
            echo "Error creating user: " . $conn->error;
        }

        // Close the database connection
        $conn->close();
    }
}

?>


register_user();

// The following code is for the registration form.
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Email:</label>
    <input type="email" name="email"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <label>Confirm Password:</label>
    <input type="password" name="confirm_password"><br><br>
    <input type="submit" value="Register">
</form>


<?php

// Configuration settings
$required_fields = array('username', 'email', 'password');
$db_host = 'localhost';
$db_username = 'your_db_username';
$db_password = 'your_db_password';
$db_name = 'your_database_name';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function validate_fields($data) {
    // Validate username and email fields
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    
    // Check if username already exists in database
    $query = "SELECT * FROM users WHERE username = '$data[username]'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        return false;
    }
    
    // Validate password field (at least 8 characters long)
    if (strlen($data['password']) < 8) {
        return false;
    }
    
    return true;
}

function register_user($data) {
    global $conn;
    
    // Check if all required fields are filled
    if (!array_intersect_key(array_flip($required_fields), $data)) {
        echo "Please fill out all fields";
        exit();
    }
    
    // Validate user input
    if (!validate_fields($data)) {
        echo "Invalid email or username";
        exit();
    }
    
    // Hash password for storage
    $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
    
    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$data[username]', '$data[email]', '$password_hash')";
    if (!$conn->query($query)) {
        echo "Error registering user";
        exit();
    }
    
    // Send confirmation email (example)
    mail($data['email'], 'Account Confirmation', 'Welcome to our website!');
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    register_user($_POST);
}

?>


// Configuration settings
$requiredFields = array('username', 'email', 'password');
$maxUsernameLength = 20;
$maxEmailLength = 100;
$maxPasswordLength = 50;

function registerUser() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = $_POST;
        
        // Validate form data
        if (validateData($data)) {
            // Hash password
            $passwordHash = hash('sha256', $data['password']);
            
            // Connect to database and insert user
            require_once 'dbConnect.php';
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if ($mysqli->connect_error) {
                echo "Connection failed: " . $mysqli->connect_error;
                exit();
            }
            
            // Insert user data into database
            $query = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('sss', $data['username'], $data['email'], $passwordHash);
            if ($stmt->execute()) {
                echo "User registered successfully!";
            } else {
                echo "Error registering user: " . $mysqli->error;
            }
            
            // Close database connection
            $mysqli->close();
        } else {
            // Show form with validation errors
            require_once 'registerForm.php';
            echo "<p class='error'>" . join("<br>", getValidationErrors()) . "</p>";
        }
    } else {
        // Display registration form
        require_once 'registerForm.php';
    }
}

function validateData($data) {
    $validationErrors = array();
    
    if (!in_array('username', $requiredFields)) {
        $validationErrors[] = "Username is required.";
    } elseif (strlen($data['username']) > $maxUsernameLength || strlen($data['username']) < 3) {
        $validationErrors[] = "Username must be between 3 and $maxUsernameLength characters long.";
    }
    
    if (!in_array('email', $requiredFields)) {
        $validationErrors[] = "Email is required.";
    } elseif (strlen($data['email']) > $maxEmailLength || !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $data['email'])) {
        $validationErrors[] = "Invalid email address.";
    }
    
    if (!in_array('password', $requiredFields)) {
        $validationErrors[] = "Password is required.";
    } elseif (strlen($data['password']) > $maxPasswordLength || strlen($data['password']) < 8) {
        $validationErrors[] = "Password must be between 8 and $maxPasswordLength characters long.";
    }
    
    if (!empty($validationErrors)) {
        return false;
    }
    
    return true;
}

function getValidationErrors() {
    global $validationErrors;
    return $validationErrors;
}


function register_user($username, $email, $password) {
  // Check if the username and email are already taken
  $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
  $result = mysqli_query($link, $query);
  if (mysqli_num_rows($result) > 0) {
    return array("error" => "Username or email is already taken.");
  }

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert the new user into the database
  $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
  mysqli_query($link, $query);

  return array("message" => "User created successfully.");
}


$username = "johnDoe";
$email = "johndoe@example.com";
$password = "mysecretpassword";

$result = register_user($username, $email, $password);
if ($result["error"]) {
  echo "Error: " . $result["error"];
} else {
  echo "User created successfully.";
}


function registerUser($username, $email, $password) {
  // Check if username and email already exist
  $query = "SELECT * FROM users WHERE username = :username OR email = :email";
  $stmt = db::getInstance()->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  
  if ($stmt->rowCount() > 0) {
    return array('error' => 'Username or email already exists');
  }
  
  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  
  // Insert new user into database
  $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
  $stmt = db::getInstance()->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $hashedPassword);
  $stmt->execute();
  
  return array('message' => 'User registered successfully');
}


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if (isset($result['error'])) {
  echo json_encode(array('error' => $result['error']));
} else {
  echo json_encode($result);
}


<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function registerUser($data) {
    // Validate input data
    if (!validateData($data)) {
        return false;
    }

    // Create a new user object
    $user = [
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => hashPassword($data['password'])
    ];

    // Store the user in the database (e.g., using PDO)
    storeUserInDB($user);

    // Send a confirmation email
    sendConfirmationEmail($user['email']);

    return $user;
}

function validateData($data) {
    // Check for required fields
    if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
        return false;
    }

    // Check email validity
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Check password strength (e.g., minimum length of 8 characters)
    if (strlen($data['password']) < 8) {
        return false;
    }

    return true;
}

function hashPassword($password) {
    // Use a secure hashing algorithm like bcrypt or Argon2
    $options = [
        'cost' => 12,
        'salt' => random_bytes(16),
        'algorithm' => 'sha512',
    ];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
    return $hashedPassword;
}

function storeUserInDB($user) {
    // Connect to the database and execute an INSERT query
    $pdo = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->execute([
        ':name' => $user['name'],
        ':email' => $user['email'],
        ':password' => $user['password']
    ]);
}

function sendConfirmationEmail($email) {
    // Use a library like PHPMailer to send an email
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->Port = 587;
    $mail->Username = 'myusername@example.com';
    $mail->Password = 'mypassword';
    $mail->setFrom('no-reply@example.com', 'Example');
    $mail->addAddress($email);
    $mail->Subject = 'Confirm your email address';
    $mail->Body = 'Please click this link to confirm your email address: https://example.com/confirm-email';
    $mail->send();
}

// Example usage:
$data = [
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'password' => 'mysecretpassword'
];
$user = registerUser($data);
print_r($user);

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


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to register a user
function registerUser($username, $email, $password) {
    // Connect to the database
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Check connection
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        return false;
    }

    // Prepare and execute SQL query (using prepared statements)
    $stmt = $mysqli->prepare("INSERT INTO users SET username=?, email=?, password=?");
    $stmt->bind_param("sss", $username, $email, hash('sha256', $password));

    // Execute statement
    if (!$stmt->execute()) {
        echo "Error registering user: " . $stmt->error;
        return false;
    }

    // Close the connection and statement
    $stmt->close();
    $mysqli->close();

    return true;
}

// Example usage:
$username = 'newuser';
$email = 'new@example.com';
$password = 'password';

if (registerUser($username, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Failed to register user.";
}

?>


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Function to register a user
    function registerUser($email, $username, $password) {
        // Validate input data
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address');
        }
        if (strlen($username) < 3 || strlen($username) > 20) {
            throw new Exception('Username must be between 3 and 20 characters long');
        }
        if (strlen($password) < 8 || strlen($password) > 50) {
            throw new Exception('Password must be between 8 and 50 characters long');
        }

        // Insert user data into database
        $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        // Hash the password before inserting
        $password = password_hash($password, PASSWORD_DEFAULT);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception('Error registering user');
        }
    }

    // Example usage:
    try {
        registerUser('example@example.com', 'johnDoe', 'mysecretpassword');
        echo "User registered successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}


<?php

// Configuration settings
$requiredFields = array('username', 'email', 'password');
$maxUsernameLength = 30;
$maxEmailLength = 254;

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user input
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            echo "Please fill in all required fields.";
            return;
        }
    }

    if (strlen($username) > $maxUsernameLength) {
        echo "Username too long. Please keep it under 30 characters.";
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        return;
    }

    // Hash password
    $password = hash('sha256', $password);

    // Connect to database (example using MySQLi)
    $mysqli = new mysqli("localhost", "username", "password", "database");

    if ($mysqli->connect_error) {
        echo "Error connecting to database: " . $mysqli->connect_error;
        return;
    }

    // Create new user account
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $username, $email, $password);
    $result = $stmt->execute();

    if (!$result) {
        echo "Error creating user account: " . $mysqli->error;
        return;
    }

    // Close database connection
    $mysqli->close();

    // Redirect to login page
    header("Location: login.php");
    exit();
}

?>

<!-- Form template -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <button type="submit">Register</button>
</form>


function registerUser($data)
{
  // Input validation and sanitization
  $validatedData = validateRegistrationData($data);

  if (!$validatedData) {
    return ['error' => 'Invalid data'];
  }

  try {
    // Hash password
    $passwordHash = hashPassword($validatedData['password']);

    // Insert user into database
    $userId = insertUserIntoDatabase($validatedData);

    return [
      'success' => true,
      'message' => 'User registered successfully',
      'user_id' => $userId
    ];
  } catch (Exception $e) {
    return ['error' => 'Error registering user'];
  }
}

function validateRegistrationData($data)
{
  // Validate username and email
  if (!isset($data['username']) || !isset($data['email'])) {
    return false;
  }

  if (strlen($data['username']) < 3 || strlen($data['username']) > 255) {
    return false;
  }

  if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
    return false;
  }

  // Validate password
  if (!isset($data['password'])) {
    return false;
  }

  if (strlen($data['password']) < 8 || strlen($data['password']) > 255) {
    return false;
  }

  return [
    'username' => $data['username'],
    'email' => $data['email'],
    'password' => $data['password']
  ];
}

function hashPassword($password)
{
  // Implement your preferred password hashing algorithm (e.g. bcrypt, Argon2)
  // For demonstration purposes, we're using simple SHA-256
  return hash('sha256', $password);
}

function insertUserIntoDatabase($user)
{
  global $db;

  try {
    $stmt = $db->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->execute([$user['username'], $user['email'], $user['password']]);

    return $db->lastInsertId();
  } catch (Exception $e) {
    throw new Exception('Error inserting user into database');
  }
}


$data = [
  'username' => 'johnDoe',
  'email' => 'johndoe@example.com',
  'password' => 'mysecretpassword'
];

$response = registerUser($data);

if ($response['success']) {
  echo "User registered successfully";
} else {
  echo "Error: " . $response['error'];
}


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

// Function to register a user
function register_user($username, $email, $password) {
    // Hash password
    $password = hash('sha256', $password);

    // Query to insert new user
    $query = "
        INSERT INTO users (username, email, password)
        VALUES ('$username', '$email', '$password')
    ";

    // Execute query
    if ($conn->query($query) === TRUE) {
        echo "User registered successfully!";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

// Check for form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get posted values
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Register user
    register_user($username, $email, $password);
}

// Close connection
$conn->close();

?>


<?php

// Configuration variables
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die('Connection failed: ' . mysqli_error($conn));
}

function registerUser() {
    global $conn;

    // Get user input from POST request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Sanitize and validate user input
        if (empty($username) || empty($email) || empty($password)) {
            echo 'Please fill in all fields.';
            return;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL query to insert new user into database
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            die('Error preparing query: ' . mysqli_error($conn));
        }

        // Bind parameters to SQL query
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);

        // Execute the query
        if (!mysqli_stmt_execute($stmt)) {
            echo 'Error inserting user into database.';
            return;
        }

        // Get ID of newly inserted user
        $userId = mysqli_insert_id($conn);
    }
}

// Call the registerUser function when this file is run directly
if (php_sapi_name() == 'cli') {
    registerUser();
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        registerUser();
    } else {
        // Display registration form
        echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
        echo '<label for="username">Username:</label>';
        echo '<input type="text" id="username" name="username"><br><br>';
        echo '<label for="email">Email:</label>';
        echo '<input type="email" id="email" name="email"><br><br>';
        echo '<label for="password">Password:</label>';
        echo '<input type="password" id="password" name="password"><br><br>';
        echo '<button type="submit">Register</button>';
        echo '</form>';
    }
}

?>


<?php

function registerUser($username, $email, $password) {
    // Check for empty fields
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required.');
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database
    try {
        $conn = new PDO('mysql:host=localhost;dbname=mydb', 'myuser', 'mypassword');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        return array('error' => 'Database connection failed.');
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
        return array('error' => 'Username or email already exists.');
    }

    // Insert new user into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    // Return success message
    return array('message' => 'User registered successfully.');
    } catch (PDOException $e) {
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}

?>


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if ($result['error']) {
    echo 'Error: ' . $result['error'];
} else {
    echo 'Success: ' . $result['message'];
}


<?php

// Configuration settings
$minUsernameLength = 3;
$maxUsernameLength = 50;
$minPasswordLength = 8;

function registerUser($username, $email, $password) {
    // Validation checks
    if (strlen($username) < $minUsernameLength || strlen($username) > $maxUsernameLength) {
        throw new Exception("Username must be between $minUsernameLength and $maxUsernameLength characters.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address.");
    }

    if (strlen($password) < $minPasswordLength) {
        throw new Exception("Password must be at least $minPasswordLength characters long.");
    }

    // Database connection
    $dbHost = 'your_host';
    $dbUsername = 'your_username';
    $dbPassword = 'your_password';
    $dbName = 'your_database';

    try {
        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);

        if (!$stmt->execute()) {
            throw new Exception("Failed to register user.");
        }

        return true;

    } catch (PDOException $e) {
        // Handle database connection errors
        echo "Database error: " . $e->getMessage();
        return false;
    }
}

?>


try {
    if (registerUser('johnDoe', 'john.doe@example.com', 'mysecretpassword')) {
        echo "User registered successfully.";
    } else {
        echo "Failed to register user.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


function registerUser($username, $email, $password) {
    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required.');
    }

    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        return array('error' => 'Username can only contain letters and numbers.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address.');
    }

    // Connect to the database
    $mysqli = new mysqli('localhost', 'username', 'password', 'database');

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query to insert user into database
    $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        return array('error' => 'Database error: unable to prepare statement.');
    }

    // Bind parameters and execute the query
    $stmt->bind_param('sss', $username, $email, $hashedPassword);
    $result = $stmt->execute();

    if ($result === false) {
        return array('error' => 'Database error: unable to insert user.');
    }

    // Return a success message and user data (optional)
    return array(
        'message' => 'User created successfully.',
        'userData' => array(
            'id' => $mysqli->insert_id,
            'username' => $username,
            'email' => $email
        )
    );
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

$result = registerUser($username, $email, $password);

if (isset($result['error'])) {
    echo 'Error: ', $result['error'];
} else {
    echo 'User created successfully!';
    print_r($result);
}


function registerUser($data) {
    // Check if all required fields are present
    if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
        throw new Exception('All fields must be provided');
    }

    // Validate username and email
    $username = trim($data['username']);
    $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);

    if (empty($username) || empty($email)) {
        throw new Exception('Username and email are required');
    }

    // Hash password
    $passwordHash = hash('sha256', $data['password']);

    // Store user data in an array
    $userData = [
        'username' => $username,
        'email' => $email,
        'password_hash' => $passwordHash,
    ];

    return $userData;
}


// Define the input data
$data = [
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword',
];

try {
    // Call the registerUser function
    $userData = registerUser($data);
    print_r($userData);

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
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

// Configuration
$database = 'users';
$usernameField = 'username';
$emailField = 'email';
$passwordField = 'password';

// Function to hash password
function hashPassword($password) {
  return crypt($password, '$2y$10$' . substr(hash('sha256', microtime(true)), 0, 22));
}

// Function to register user
function registerUser($username, $email, $password) {
  // Prepare SQL query
  $sql = "INSERT INTO $database SET $usernameField = ?, $emailField = ?, $passwordField = ?";

  try {
    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $email, hashPassword($password)]);

    if ($stmt === true) {
      echo "User registered successfully!";
    } else {
      throw new Exception("Error registering user: " . json_encode($stmt));
    }
  } catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }

  return true;
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  if (empty($username) || empty($email) || empty($password)) {
    echo "Please fill in all fields!";
  } else {
    registerUser($username, $email, $password);
  }
}

?>


<?php

function registerUser($username, $email, $password) {
    // Define database credentials and connection settings
    $dbHost = 'localhost';
    $dbUsername = 'your_username';
    $dbPassword = 'your_password';
    $dbName = 'your_database';

    try {
        // Connect to the database
        $conn = new PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName, $dbUsername, $dbPassword);

        // Prepare a query with placeholders for username and email
        $stmt = $conn->prepare('INSERT INTO users (username, email) VALUES (:username, :email)');
        
        // Bind the user input to the placeholders
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);

        try {
            // Execute the query and insert the new user data
            $stmt->execute();

            // Hash the password for secure storage
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert the hashed password into the users table
            $conn->exec('UPDATE users SET password = :password WHERE username = :username', array(
                ':password' => $hashedPassword,
                ':username' => $username
            ));

            return true;
        } catch (PDOException $e) {
            echo 'Error registering user: ' . $e->getMessage();
            return false;
        }
    } catch (PDOException $e) {
        echo 'Database connection failed: ' . $e->getMessage();
        return false;
    }
}

?>


// Create a new PDO instance with database credentials
$dbh = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

try {
    // Prepare a query to insert new user data
    $stmt = $dbh->prepare('INSERT INTO users (username, email) VALUES (:username, :email)');
    
    // Bind the user input to the placeholders
    $stmt->bindParam(':username', $_POST['username']);
    $stmt->bindParam(':email', $_POST['email']);

    try {
        // Execute the query and insert the new user data
        $stmt->execute();

        // Hash the password for secure storage
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Update the hashed password in the users table
        $dbh->exec('UPDATE users SET password = :password WHERE username = :username', array(
            ':password' => $hashedPassword,
            ':username' => $_POST['username']
        ));

        return true;
    } catch (PDOException $e) {
        echo 'Error registering user: ' . $e->getMessage();
        return false;
    }
} catch (PDOException $e) {
    echo 'Database connection failed: ' . $e->getMessage();
    return false;
}


<?php

// Load dependencies and database connection
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

function createUser($data) {
    // Validate form data (adapt this to your specific validation needs)
    if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
        throw new Exception("Invalid request: Missing required fields");
    }

    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    // Prepare SQL query to insert user into database
    $sql = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password)";

    try {
        // Execute query with prepared statement
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => $hashedPassword,
        ]);

        return true;
    } catch (PDOException $e) {
        echo "Error creating user: " . $e->getMessage();
        return false;
    }
}

// Example usage:
$userData = [
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'password123', // Store securely, never hard-code passwords
];

if (createUser($userData)) {
    echo "User created successfully!";
} else {
    echo "Failed to create user.";
}

?>


/**
 * User registration function
 *
 * @param array $data User data to be registered (name, email, password)
 * @return bool True if registration successful, false otherwise
 */
function registerUser($data) {
  // Check for valid input
  if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
    return false;
  }

  // Validate email address
  $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
  if ($email === false) {
    return false;
  }

  // Hash password using bcrypt
  $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

  // Prepare SQL query to insert new user into database
  $sqlQuery = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
  $stmt = pdo()->prepare($sqlQuery);
  $stmt->bindParam(':name', $data['name']);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $hashedPassword);

  // Execute SQL query
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}


$data = array(
  'name' => 'John Doe',
  'email' => 'john@example.com',
  'password' => 'mysecretpassword'
);

if (registerUser($data)) {
  echo "User registered successfully!";
} else {
  echo "Registration failed.";
}


function registerUser($username, $email, $password) {
    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    if (strlen($password) < 8) {
        throw new Exception('Password must be at least 8 characters long');
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database
    require 'database.php';
    $conn = connectToDatabase();

    try {
        // Insert user into database
        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Close database connection
        disconnectFromDatabase($conn);

        return true;
    } catch (PDOException $e) {
        // Handle database error
        echo 'Error registering user: ' . $e->getMessage();
        return false;
    }
}


try {
    registerUser('johnDoe', 'john.doe@example.com', 'password123');
} catch (Exception $e) {
    echo $e->getMessage();
}


<?php
require 'config.php';

function connectToDatabase() {
    $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    return $conn;
}

function disconnectFromDatabase($conn) {
    unset($conn);
}


function registerUser($username, $email, $password) {
    // Hash the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to the database
        $conn = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the statement
        $stmt->execute();

        // Return true if user created successfully
        return true;
    } catch (PDOException $e) {
        // Return false if there was an error creating the user
        echo "Error creating user: " . $e->getMessage();
        return false;
    }
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

if (registerUser($username, $email, $password)) {
    echo "User created successfully!";
} else {
    echo "Error creating user.";
}


function register_user($name, $email, $password) {
  // Validate input
  if (empty($name) || empty($email) || empty($password)) {
    return array('error' => 'Please fill in all fields');
  }

  // Check for valid email format
  if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
    return array('error' => 'Invalid email address');
  }

  // Hash password
  $hashed_password = hash('sha256', $password);

  // Check if user already exists
  try {
    $db->query("SELECT * FROM users WHERE email = :email", array(':email' => $email));
    if ($db->fetch()) {
      return array('error' => 'User already exists');
    }
  } catch (PDOException $e) {
    // Handle database connection error
    echo "Error: " . $e->getMessage();
    exit;
  }

  // Insert new user into database
  try {
    $db->query("INSERT INTO users SET name = :name, email = :email, password = :password", array(':name' => $name, ':email' => $email, ':password' => $hashed_password));
  } catch (PDOException $e) {
    // Handle database connection error
    echo "Error: " . $e->getMessage();
    exit;
  }

  // Send email verification link to user
  try {
    $verification_link = 'http://example.com/verify.php?email=' . urlencode($email);
    mail($email, 'Email Verification', 'Please click on this link to verify your email: ' . $verification_link, 'From: example@example.com');
  } catch (Exception $e) {
    // Handle email sending error
    echo "Error: " . $e->getMessage();
    exit;
  }

  return array('success' => true);
}


$user_data = register_user('John Doe', 'john.doe@example.com', 'mysecretpassword');
if ($user_data['success']) {
  echo "User registered successfully!";
} else {
  echo "Error: " . $user_data['error'];
}


function registerUser($userData) {
  // Database connection settings
  $dbHost = 'localhost';
  $dbName = 'database_name';
  $username = 'database_username';
  $password = 'database_password';

  try {
    // Connect to the database
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute query to insert user data into database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->bindParam(':name', $userData['name']);
    $stmt->bindParam(':email', $userData['email']);
    $stmt->bindParam(':password', $userData['password']);

    // Check if query was successful
    if ($stmt->execute()) {
      return true; // User registration successful
    } else {
      throw new Exception("Error registering user");
    }
  } catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
    return false;
  }
}


// Assume this form data comes from a POST request:
$userData = array(
  'name' => $_POST['name'],
  'email' => $_POST['email'],
  'password' => $_POST['password']
);

// Register the user
if (registerUser($userData)) {
  echo "User registered successfully!";
} else {
  echo "Error registering user.";
}


function registerUser($email, $password) {
    // Hash the password for security
    $hashedPassword = hash('sha256', $password);

    // Connect to database (replace with your own connection method)
    $conn = mysqli_connect("localhost", "username", "password", "database");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare and execute query
    $query = "INSERT INTO users (email, password_hash) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $email, $hashedPassword);
    mysqli_stmt_execute($stmt);

    if (mysqli_affected_rows($conn)) {
        return true;
    } else {
        echo "Failed to register user.";
        return false;
    }

    // Close connection
    mysqli_close($conn);
}


$email = "user@example.com";
$password = "password123";

if (registerUser($email, $password)) {
    echo "User successfully registered!";
} else {
    echo "Registration failed.";
}


function registerUser($email, $password) {
    // Hash the password for security
    $hashedPassword = hash('sha256', $password);

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=database", "username", "password");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "INSERT INTO users (email, password_hash) VALUES (:email, :password)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        } else {
            echo "Failed to register user.";
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }

    // Close PDO connection
    unset($pdo);
}


function registerUser($username, $email, $password, $confirmPassword) {
    // Check if all fields are provided
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        return array('status' => 'error', 'message' => 'All fields are required');
    }

    // Validate username length and character restrictions
    if (strlen($username) < 3 || strlen($username) > 30) {
        return array('status' => 'error', 'message' => 'Username must be between 3-30 characters');
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return array('status' => 'error', 'message' => 'Username can only contain letters, numbers, and underscores');
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('status' => 'error', 'message' => 'Invalid email address');
    }

    // Validate password length and character restrictions
    if (strlen($password) < 8 || strlen($password) > 50) {
        return array('status' => 'error', 'message' => 'Password must be between 8-50 characters');
    }
    if (!preg_match('/[a-zA-Z]/', $password)) {
        return array('status' => 'error', 'message' => 'Password must contain at least one letter');
    }
    if (!preg_match('/\d/', $password)) {
        return array('status' => 'error', 'message' => 'Password must contain at least one number');
    }

    // Validate confirm password
    if ($password !== $confirmPassword) {
        return array('status' => 'error', 'message' => 'Passwords do not match');
    }

    // Create user account (insert into database)
    try {
        // Connect to database and insert user data
        $dbConnection = new PDO('sqlite:users.db');
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $dbConnection->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
        $stmt->execute();
        return array('status' => 'success', 'message' => 'User registered successfully');
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => 'Error registering user: ' . $e->getMessage());
    }
}


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];

$response = registerUser($username, $email, $password, $confirmPassword);

if ($response['status'] == 'success') {
    echo 'User registered successfully!';
} else {
    echo 'Error: ' . $response['message'];
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

// Configuration array for password hashing and email sending
$config = [
    'hash_cost' => 12,
    'email_subject' => "Registration Confirmation",
];

function registerUser($data) {
    // Input validation
    $requiredFields = ['name', 'username', 'password'];
    if (!array_reduce($requiredFields, function ($carry, $field) use ($data) {
        return $carry && !empty($data[$field]);
    }, true)) {
        throw new Exception("All fields are required");
    }

    // Check username and email availability
    global $db;
    if (checkUsernameExists($data['username'])) {
        throw new Exception("Username already exists");
    }
    if (checkEmailExists($data['email'])) {
        throw new Exception("Email address is already registered");
    }

    // Hash password
    $password = hash('sha256', $data['password'], true);
    $hashedPassword = base64_encode($password);

    // Insert data into database
    global $db;
    $sql = "INSERT INTO users (name, username, email, hashed_password) VALUES (:name, :username, :email, :hashed_password)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':name' => $data['name'],
        ':username' => $data['username'],
        ':email' => $data['email'],
        ':hashed_password' => $hashedPassword,
    ]);

    // Send email confirmation
    sendConfirmationEmail($data['email']);

    return true;
}

// Function to check if username exists in database
function checkUsernameExists($username) {
    global $db;
    $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->execute([':username' => $username]);
    return (int)$stmt->fetchColumn() > 0;
}

// Function to check if email exists in database
function checkEmailExists($email) {
    global $db;
    $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->execute([':email' => $email]);
    return (int)$stmt->fetchColumn() > 0;
}

// Function to send email confirmation
function sendConfirmationEmail($email) {
    // Email sending logic here...
}



<?php

// Configuration settings
$database_host = 'localhost';
$database_username = 'username';
$database_password = 'password';
$database_name = 'users';

// Database connection
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to register a user
function registerUser() {
  global $conn;

  // Get form data
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  // Validate input data
  if (empty($name) || empty($email) || empty($password)) {
      echo "Please fill in all fields.";
      return false;
  }

  if ($password !== $confirm_password) {
    echo "Passwords do not match.";
    return false;
  }

  // Hash password using SHA-256
  $hashed_password = hash('sha256', $password);

  // Insert user data into database
  $sql = "INSERT INTO users (name, email, password)
          VALUES ('$name', '$email', '$hashed_password')";
  if ($conn->query($sql) === TRUE) {
    echo "New user created successfully.";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  // Close database connection
  $conn->close();
}

// Check for form submission
if (isset($_POST['submit'])) {
  registerUser();
}
?>


<?php
?>

<!-- Registration Form -->
<form action="register.php" method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name"><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password"><br><br>
    <input type="submit" name="submit" value="Register">
</form>


function register_user($username, $email, $password) {
    // Check if username and email are not empty
    if (empty($username) || empty($email)) {
        return array('error' => 'Username and email are required.');
    }

    // Check if password meets the minimum length requirement
    if (strlen($password) < 8) {
        return array('error' => 'Password must be at least 8 characters long.');
    }

    // Hash password using bcrypt
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Connect to database
    require_once 'db.php';
    $conn = mysqli_connect($host, $username, $password, $database);
    if (!$conn) {
        return array('error' => 'Database connection failed.');
    }

    // Prepare SQL query to insert user data into database
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        return array('error' => 'SQL error: ' . mysqli_error($conn));
    }

    // Bind parameters to SQL query
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

    // Execute SQL query
    if (mysqli_stmt_execute($stmt)) {
        return array('success' => 'User registered successfully.');
    } else {
        return array('error' => 'SQL error: ' . mysqli_error($conn));
    }

    // Close database connection
    mysqli_close($conn);
}


// Call the register_user function with username, email, and password as arguments
$result = register_user($_POST['username'], $_POST['email'], $_POST['password']);

// Check if user was registered successfully
if ($result['success']) {
    echo "User registered successfully!";
} else {
    // Display any error messages
    echo $result['error'];
}


<?php

// Configuration settings
require 'config.php';

function registerUser($username, $email, $password) {
    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('All fields are required.');
    }

    // Check for duplicate username and email
    $stmt = $db->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
    $stmt->execute([$username, $email]);
    if ($stmt->rowCount() > 0) {
        throw new Exception('Username or email already exists.');
    }

    // Hash password using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user data into database
    $insertQuery = 'INSERT INTO users (username, email, password) VALUES (?, ?, ?)';
    $stmt = $db->prepare($insertQuery);
    $stmt->execute([$username, $email, $hashedPassword]);

    // Return the newly created user's ID
    return $db->lastInsertId();
}

// Example usage:
try {
    $username = 'johnDoe';
    $email = 'johndoe@example.com';
    $password = 'mysecretpassword';

    $userId = registerUser($username, $email, $password);
    echo "User created with ID: $userId";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>


<?php

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>


<?php

require_once 'database.php';

// Registration function
function register_user($name, $email, $password) {
    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    
    // Bind parameters to prevent SQL injection attacks
    $stmt->bind_param("sss", $name, $email, $password);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "User registered successfully!";
        return true;
    } else {
        echo "Error registering user: " . $conn->error;
        return false;
    }
}

// Registration form handler
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    if (!empty($name) && !empty($email) && !empty($password)) {
        register_user($name, $email, $password);
    } else {
        echo "Please fill in all fields.";
    }
}

?>


function register_user($username, $email, $password) {
    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required');
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database
    require_once 'database.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        return array('error' => 'Database connection failed');
    }

    // Prepare and execute INSERT query
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    $result = $stmt->execute();

    if (!$result) {
        return array('error' => 'Database query failed');
    }

    // Close database connection
    $conn->close();

    // Return success message
    return array('message' => 'User registered successfully');
}


$username = 'john_doe';
$email = 'johndoe@example.com';
$password = 'password123';

$result = register_user($username, $email, $password);

if (isset($result['error'])) {
    echo "Error: " . $result['error'];
} else {
    echo "Success! User registered with ID: " . $result['message'];
}


<?php

// Configuration variables
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Establish database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    // Get user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate input data
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo 'Error: All fields are required.';
        return false;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Error: Invalid email address.';
        return false;
    }

    if ($password !== $confirmPassword) {
        echo 'Error: Passwords do not match.';
        return false;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query to insert user data into database
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo 'User registered successfully!';
    } else {
        echo 'Error: Registration failed.';
    }

    // Close database connection
    $conn->close();
}

if (isset($_POST['register'])) {
    registerUser();
} else {
    ?>
    <form action="" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name"><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password"><br><br>
        <input type="submit" name="register" value="Register">
    </form>
    <?php
}

?>


<?php

// Configuration variables
define('USERNAME_MIN_LENGTH', 5);
define('PASSWORD_MIN_LENGTH', 8);

function registerUser($username, $email, $password) {
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("All fields are required.");
    }

    if (strlen($username) < USERNAME_MIN_LENGTH) {
        throw new Exception("Username must be at least " . USERNAME_MIN_LENGTH . " characters long.");
    }

    if (strlen($password) < PASSWORD_MIN_LENGTH) {
        throw new Exception("Password must be at least " . PASSWORD_MIN_LENGTH . " characters long.");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Create user data array
    $userData = [
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword
    ];

    // Store user data in database (replace with your actual database logic)
    try {
        // Replace this with your actual database logic
        echo "User registered successfully.";
    } catch (Exception $e) {
        throw new Exception("Failed to register user: " . $e->getMessage());
    }

    return true;
}

// Example usage:
try {
    $username = 'johnDoe';
    $email = 'johndoe@example.com';
    $password = 'mySecurePassword';

    if (registerUser($username, $email, $password)) {
        echo "Registration successful.";
    } else {
        throw new Exception("Registration failed.");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>


<?php

// Configuration variables
$databaseHost = 'localhost';
$databaseUsername = 'root';
$databasePassword = '';
$databaseName = 'users';

// Connect to the database
$mysqli = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

// Check connection
if ($mysqli->connect_errno) {
    printf("Connect failed: %s
", $mysqli->connect_error);
    exit();
}

function registerUser($username, $password, $email) {
    global $mysqli;

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Bind variables to prevent SQL injection
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sss', $username, $email, $hashedPassword);

    try {
        // Execute the query
        $stmt->execute();

        // Get the last inserted ID (assuming user ID is auto-incrementing)
        $userId = $mysqli->insert_id;

        return $userId;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return null; // Return null to indicate registration failed
    }
}

?>


$username = 'johnDoe';
$password = 'mysecretpassword';
$email = 'johndoe@example.com';

$userId = registerUser($username, $password, $email);

if ($userId !== null) {
    echo "Registration successful. User ID: $userId";
} else {
    echo "Registration failed.";
}


<?php

// Database connection credentials (replace with your own)
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to register a new user
function registerUser($name, $email, $password) {
    // Connect to the database
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL statement to check if email already exists in the database
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Check if user with this email already exists
        if ($stmt->rowCount() > 0) {
            return 'Email already registered';
        }

        // Hash password before inserting into database (for security reasons)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user data into the database
        $insertStmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $insertStmt->bindParam(':name', $name);
        $insertStmt->bindParam(':email', $email);
        $insertStmt->bindParam(':password', $hashedPassword);
        $insertStmt->execute();

        // Return confirmation message
        return 'User successfully registered';
    } catch (PDOException $e) {
        // Handle any database connection errors
        echo 'Error connecting to the database: ' . $e->getMessage();
        return false;
    }
}

// Example usage:
$name = 'John Doe';
$email = 'johndoe@example.com';
$password = 'password123';

$successMessage = registerUser($name, $email, $password);
echo $successMessage;

?>


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

// Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Database connection settings
function connect_to_db() {
    global $conn;
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
}

// User registration function
function register_user($username, $email, $password) {
    // Connect to the database
    connect_to_db();
    
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('status' => 'error', 'message' => 'Please fill in all fields');
    }

    // Check for existing username
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        return array('status' => 'error', 'message' => 'Username already exists');
    }

    // Hash the password
    $hashed_password = hash('sha256', $password);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    
    if (mysqli_query($conn, $query)) {
        return array('status' => 'success', 'message' => 'User registered successfully');
    } else {
        return array('status' => 'error', 'message' => 'Failed to register user');
    }
}

// Example usage
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = register_user($username, $email, $password);
echo json_encode($result);

?>


<?php

// Database connection settings
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'users';

function registerUser($firstName, $lastName, $email, $password) {
  // Create connection to database
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
  
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  
  // Check for existing user with the same email
  $sql = "SELECT * FROM users WHERE email='$email'";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    return array('status' => 'error', 'message' => 'User already exists');
  }
  
  // Hash the password
  $hashedPassword = hash('sha256', $password);
  
  // Insert new user into database
  $sql = "INSERT INTO users (firstName, lastName, email, password)
          VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";
  if ($conn->query($sql) === TRUE) {
    return array('status' => 'success', 'message' => 'User created successfully');
  } else {
    return array('status' => 'error', 'message' => 'Error creating user: ' . $conn->error);
  }
  
  // Close database connection
  $conn->close();
}

// Example usage:
$registrationData = array(
  'firstName' => 'John',
  'lastName' => 'Doe',
  'email' => 'john@example.com',
  'password' => 'password123'
);

$result = registerUser($registrationData['firstName'], $registrationData['lastName'], $registrationData['email'], $registrationData['password']);

if ($result['status'] == 'success') {
  echo "Registration successful: " . $result['message'];
} elseif ($result['status'] == 'error') {
  echo "Error registering user: " . $result['message'];
}

?>


<?php

function registerUser($username, $email, $password) {
  // Check for invalid or empty inputs
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception('All fields are required');
  }

  // Validate email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception('Invalid email address');
  }

  // Hash password using Bcrypt
  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

  // Connect to database (for example, MySQL)
  $mysqli = new mysqli('localhost', 'username', 'password', 'database');

  // Prepare and execute INSERT query
  if (!$mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)")) {
    throw new Exception($mysqli->error);
  }

  $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param('sss', $username, $email, $hashedPassword);

  if (!$stmt->execute()) {
    throw new Exception($mysqli->error);
  }

  // Close database connection
  $mysqli->close();

  return true;
}

?>


try {
  registerUser($_POST['username'], $_POST['email'], $_POST['password']);
  echo 'User created successfully!';
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}


// config.php (database connection settings)
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'users';

// Register User Function
function register_user($data) {
    // Extract data from the request
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];
    $confirm_password = $data['confirm_password'];

    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill out all fields');
    }

    if ($password !== $confirm_password) {
        return array('error' => 'Passwords do not match');
    }

    // Check for existing user
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($GLOBALS['db'], $query);
    if (mysqli_num_rows($result) > 0) {
        return array('error' => 'Username already taken');
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users SET username = '$username', email = '$email', password = '$hashed_password'";
    if (mysqli_query($GLOBALS['db'], $query)) {
        return array('success' => 'User created successfully');
    } else {
        return array('error' => 'Error creating user');
    }
}

// Example usage
$data = array(
    'username' => 'john_doe',
    'email' => 'johndoe@example.com',
    'password' => 'password123',
    'confirm_password' => 'password123'
);
$result = register_user($data);

if ($result['success']) {
    echo "User created successfully!";
} elseif ($result['error']) {
    echo $result['error'];
}


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'users');

// Create connection to database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check if there's an error in the connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function registerUser($username, $email, $password) {
    // Hash password using SHA-256 (you can use a library like bcrypt for better security)
    $hashedPassword = hash('sha256', $password);

    // SQL query to insert user data into database
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    $result = $stmt->execute();

    if ($result === TRUE) {
        // User registered successfully
        return true;
    } else {
        // Error registering user
        echo "Error: " . $mysqli->error;
        return false;
    }
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if (isset($_POST['register'])) {
    if (registerUser($username, $email, $password)) {
        echo "User registered successfully!";
    } else {
        echo "Error registering user.";
    }
}

?>


<?php

// Database connection settings
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

// Function to register a user
function registerUser() {
    // Get user input from form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if all fields are filled in
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "Please fill in all fields.";
        return;
    }

    // Check if password and confirm password match
    if ($password != $confirm_password) {
        echo "Passwords do not match.";
        return;
    }

    // Hash the password for security
    $hashed_password = hash('sha256', $password);

    // Insert user data into database
    $query = "INSERT INTO users (username, email, password)
              VALUES ('$username', '$email', '$hashed_password')";
    if ($conn->query($query)) {
        echo "User registered successfully.";
    } else {
        echo "Error registering user: " . $conn->error;
    }
}

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    registerUser();
}

// Close the database connection
$conn->close();

?>


<?php

// Configuration
define('SECRET_KEY', 'your_secret_key_here');

// Function to register a new user
function registerUser($username, $email, $password) {
    // Check if the username and email are valid
    if (empty($username) || empty($email)) {
        return array(
            'error' => 'Please enter both a username and an email address',
            'success' => false
        );
    }

    // Validate the email address
    $isValidEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$isValidEmail) {
        return array(
            'error' => 'Invalid email address',
            'success' => false
        );
    }

    // Check if the user already exists
    try {
        require_once 'database.php';
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':username' => $username));
        if ($stmt->fetch()) {
            return array(
                'error' => 'Username already taken',
                'success' => false
            );
        }
    } catch (PDOException $e) {
        // Handle database error
        return array(
            'error' => 'Database error: ' . $e->getMessage(),
            'success' => false
        );
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        require_once 'database.php';
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':username' => $username, ':email' => $email, ':password' => $hashedPassword));
        return array(
            'error' => '',
            'success' => true,
            'message' => 'Registration successful!'
        );
    } catch (PDOException $e) {
        // Handle database error
        return array(
            'error' => 'Database error: ' . $e->getMessage(),
            'success' => false
        );
    }
}

?>


<?php

require_once 'register.php';

// Get form data
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// Register the user
$result = registerUser($username, $email, $password);

// Display result
if ($result['success']) {
    echo '<p>' . $result['message'] . '</p>';
} else {
    echo '<p>' . $result['error'] . '</p>';
}

?>


<?php

// Define the database connection settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create a new PDO instance
$conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);

function registerUser($email, $username, $password) {
  // Check if the user already exists
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  $result = $stmt->fetchAll();

  if (!empty($result)) {
    // User already exists, return an error
    return array('error' => 'User with this email or username already exists');
  }

  // Hash the password using bcrypt
  $passwordHash = password_hash($password, PASSWORD_BCRYPT);

  // Insert the new user into the database
  $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', $passwordHash);
  $stmt->execute();

  // Return the new user's data
  return array('success' => true, 'user_data' => array(
    'email' => $email,
    'username' => $username
  ));
}

?>


$userData = registerUser('example@example.com', 'johnDoe', 'password123');

if ($userData['error']) {
  echo "Error: " . $userData['error'];
} else {
  echo "New User Registered!";
  print_r($userData);
}


<?php

// Configuration
$mysqli = new mysqli("localhost", "username", "password", "database");

function registerUser($data) {
  // Validate input data
  if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
    return array('error' => 'Missing required fields');
  }

  $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
  $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);

  // Check if email already exists
  $query = "SELECT * FROM users WHERE email='$email'";
  $result = mysqli_query($mysqli, $query);

  if (mysqli_num_rows($result) > 0) {
    return array('error' => 'Email address already in use');
  }

  // Hash password
  $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

  // Insert user into database
  $insertQuery = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$passwordHash')";
  mysqli_query($mysqli, $insertQuery);

  return array('success' => true, 'user_id' => mysqli_insert_id($mysqli));
}

?>


$data = array(
  'name' => 'John Doe',
  'email' => 'john@example.com',
  'password' => 'password123'
);

$result = registerUser($data);

if ($result['success']) {
  echo "User registered successfully! User ID: {$result['user_id']}";
} else {
  echo "Error: {$result['error']}";
}


// config.php (assuming you have your database credentials here)
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

// connect to the database
function dbConnect() {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  return $conn;
}

// close the connection
function dbClose($conn) {
  $conn->close();
}

// registration function
function registerUser() {
  // get values from form submission
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // validate input
  if (empty($name) || empty($email) || empty($password)) {
    return array('success' => false, 'error' => 'Please fill in all fields');
  }

  // hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // connect to database
  $conn = dbConnect();

  // query to create new user
  $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
  if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("sss", $name, $email, $hashedPassword);
    if ($stmt->execute()) {
      $user_id = $conn->insert_id;
      $stmt->close();

      // log user in automatically
      loginUser($user_id);

      return array('success' => true, 'error' => null);
    } else {
      return array('success' => false, 'error' => "Failed to create new user");
    }
  }

  $conn->close();
}

// login function (login user automatically after registration)
function loginUser($user_id) {
  // connect to database
  $conn = dbConnect();

  // query to get user data
  $query = "SELECT * FROM users WHERE id = ?";
  if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // get user data
    $name = $row['name'];
    $email = $row['email'];

    // log user in automatically (just for this example, you should not store passwords in plain text)
    session_start();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;

    return true;
  }

  $conn->close();
}


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

function registerUser($username, $email, $password) {
    // Check if username or email already exists
    if (checkExistingUsername($username)) {
        return array('error' => 'Username already exists');
    }
    
    if (checkExistingEmail($email)) {
        return array('error' => 'Email already exists');
    }

    // Hash password
    $hashedPassword = hash('sha256', $password);

    // Insert user into database
    insertUserIntoDatabase($username, $email, $hashedPassword);
    
    // Return success message
    return array('success' => 'Registration successful');
}

function checkExistingUsername($username) {
    // Connect to database (example using MySQL)
    $conn = mysqli_connect("localhost", "username", "password", "database");

    if (!$conn) {
        return true; // username already exists
    }

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return true; // username already exists
    } else {
        return false;
    }
}

function checkExistingEmail($email) {
    // Connect to database (example using MySQL)
    $conn = mysqli_connect("localhost", "username", "password", "database");

    if (!$conn) {
        return true; // email already exists
    }

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return true; // email already exists
    } else {
        return false;
    }
}

function insertUserIntoDatabase($username, $email, $hashedPassword) {
    // Connect to database (example using MySQL)
    $conn = mysqli_connect("localhost", "username", "password", "database");

    if (!$conn) {
        die('Connection failed: ' . mysqli_error($conn));
    }

    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    mysqli_query($conn, $query);

    // Close connection
    mysqli_close($conn);
}

// Example usage:
$username = "johnDoe";
$email = "johndoe@example.com";
$password = "password123";

$result = registerUser($username, $email, $password);

if (isset($result['error'])) {
    echo 'Error: ' . $result['error'];
} else {
    echo 'Registration successful';
}


<?php

// Configuration variables
$databaseHost = 'localhost';
$databaseName = 'your_database_name';
$databaseUsername = 'your_database_username';
$databasePassword = 'your_database_password';

function registerUser($username, $email, $password) {
    // Connect to the database
    try {
        $pdo = new PDO("mysql:host=$databaseHost;dbname=$databaseName", $databaseUsername, $databasePassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the query to register the user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            // Registration successful
            return true;
        } else {
            // Registration failed (e.g. due to duplicate username or email)
            return false;
        }
    } catch (PDOException $e) {
        // Handle database connection error
        echo "Database connection error: " . $e->getMessage();
        return false;
    } finally {
        // Close the PDO object
        $pdo = null;
    }
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($registerUser($username, $email, $password)) {
        echo "Registration successful!";
    } else {
        echo "Registration failed. Please try again.";
    }
}
?>


<?php

// Configuration settings
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'users';

// Database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Error handling for database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser($username, $email, $password)
{
    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        echo 'Error: All fields must be filled';
        return false;
    }

    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query to insert new user into database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    try {
        // Execute SQL query with prepared statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        $stmt->execute();

        // Return true if registration is successful
        return true;

    } catch (Exception $e) {
        echo 'Error: Registration failed';
        echo 'Error details: ' . $e->getMessage();
        return false;
    }
}

// Example usage:
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (registerUser($username, $email, $password)) {
        echo 'Registration successful!';
    } else {
        echo 'Registration failed';
    }
}
?>


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_NAME', 'mydatabase');
define('DB_USER', 'myuser');
define('DB_PASSWORD', 'mypassword');

// Function to register a new user
function registerUser($name, $email, $password) {
  // Connect to the database
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Escape user input to prevent SQL injection attacks
  $name = mysqli_real_escape_string($conn, $name);
  $email = mysqli_real_escape_string($conn, $email);
  $password = mysqli_real_escape_string($conn, $password);

  // Hash the password with bcrypt
  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

  // Insert new user into database
  $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sss", $name, $email, $hashedPassword);
  $result = $stmt->execute();

  // Close the database connection
  $conn->close();

  return $result;
}

// Example usage:
$name = "John Doe";
$email = "john.doe@example.com";
$password = "mysecretpassword";

if (registerUser($name, $email, $password)) {
  echo "User registered successfully!";
} else {
  echo "Error registering user.";
}


function registerUser($username, $email, $password) {
  // Validate user input
  if (empty($username) || empty($email) || empty($password)) {
    return array('error' => 'All fields are required.');
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return array('error' => 'Invalid email address.');
  }

  // Hash the password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  try {
    // Connect to database
    $conn = new PDO("mysql:host=localhost;dbname=mydatabase", "myusername", "mypassword");

    // Insert user data into table
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $hashedPassword);

    // Execute the query
    $stmt->execute();

    // Close database connection
    $conn = null;

    // Return success message
    return array('success' => 'User registered successfully.');
  } catch (PDOException $e) {
    // Handle any database errors
    return array('error' => 'Database error: ' . $e->getMessage());
  }
}


// User data to be registered
$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

$result = registerUser($username, $email, $password);

if ($result['error']) {
  echo 'Error: ' . $result['error'];
} else {
  echo 'Success: ' . $result['success'];
}


<?php

function registerUser($name, $email, $password) {
    // Check if the input fields are not empty
    if (empty($name) || empty($email) || empty($password)) {
        return array("error" => "Please fill all fields", "success" => false);
    }

    // Check if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array("error" => "Invalid email address", "success" => false);
    }

    // Connect to the database
    $db = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($db->connect_errno > 0) {
        return array("error" => "Connection failed: (" . $db->connect_errno . ") " . $db->connect_error, "success" => false);
    }

    // Hash the password
    $hashedPassword = md5($password);

    // Prepare and execute query to insert user into database
    if (!$result = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)")) {
        return array("error" => "Prepare failed: (" . $db->errno . ") " . $db->error, "success" => false);
    }

    // Bind parameters
    if (!$result->bind_param('sss', $name, $email, $hashedPassword)) {
        return array("error" => "Binding parameters failed", "success" => false);
    }

    // Execute query
    if (!$result->execute()) {
        return array("error" => "Execute failed: (" . $db->errno . ") " . $db->error, "success" => false);
    }

    // If everything is fine, commit the transaction and close the connection
    $db->commit();
    $db->close();

    // Return a success message
    return array("error" => "", "message" => "User registered successfully", "success" => true);
}

// Example usage:
print_r(registerUser('John Doe', 'john@example.com', 'password123'));

?>


function registerUser($name, $email, $password) {
    // Check if the input fields are not empty
    if (empty($name) || empty($email) || empty($password)) {
        return array("error" => "Please fill all fields", "success" => false);
    }

    // Check if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array("error" => "Invalid email address", "success" => false);
    }

    // Connect to the database
    $db = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($db->connect_errno > 0) {
        return array("error" => "Connection failed: (" . $db->connect_errno . ") " . $db->connect_error, "success" => false);
    }

    // Hash the password securely using PHP's built-in function
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute query to insert user into database
    if (!$result = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)")) {
        return array("error" => "Prepare failed: (" . $db->errno . ") " . $db->error, "success" => false);
    }

    // Bind parameters
    if (!$result->bind_param('sss', $name, $email, $hashedPassword)) {
        return array("error" => "Binding parameters failed", "success" => false);
    }

    // Execute query
    if (!$result->execute()) {
        return array("error" => "Execute failed: (" . $db->errno . ") " . $db->error, "success" => false);
    }

    // If everything is fine, commit the transaction and close the connection
    $db->commit();
    $db->close();

    // Return a success message
    return array("error" => "", "message" => "User registered successfully", "success" => true);
}


$result = registerUser('John Doe', 'john@example.com', 'password123');
print_r($result);


// config.php: database connection settings and constants
require 'config.php';

function registerUser($username, $email, $password) {
    // Hash password using SHA-256
    $hashedPassword = sha256($password);

    try {
        // Create query to insert user data into the users table
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the query
        if ($stmt->execute()) {
            // Send confirmation email to the user
            sendConfirmationEmail($email, $username);

            return true;
        } else {
            throw new Exception('Error registering user');
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo 'Database error: ' . $e->getMessage();
    }

    return false;
}

function sendConfirmationEmail($email, $username) {
    // Email configuration settings
    $from = 'your_email@example.com';
    $subject = 'Account Confirmation';

    // Create the email body
    $body = 'Hello ' . $username . ', <br><br> Your account has been created successfully. Please click on the link below to activate your account.';
    $link = 'http://example.com/activate/' . urlencode($email);

    // Send the email using PHPMailer or a similar library
    // For simplicity, we'll use a placeholder function
    sendEmail($from, $email, $subject, $body);
}

// Placeholder function for sending emails (replace with a real email library)
function sendEmail($from, $to, $subject, $body) {
    echo 'Sent confirmation email to ' . $to;
}


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if (registerUser($username, $email, $password)) {
    echo 'User registered successfully!';
} else {
    echo 'Error registering user';
}


<?php

// Define the database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

// Function to register new users
function registerUser($username, $email, $password) {
    // Connect to the database
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hash the password using SHA-256
    $hashedPassword = hash('sha256', $password);

    // Prepare and execute the query to register new user
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    if ($stmt->execute()) {
        echo "User registered successfully!";
    } else {
        echo "Error registering user: " . $stmt->error;
    }

    // Close the database connection
    $conn->close();
}

// Example usage:
registerUser('johnDoe', 'johndoe@example.com', 'password123');

?>


<?php

// Database connection details
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
function connectToDatabase() {
  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to register user
function registerUser($username, $email, $password) {
  // Connect to database
  $conn = connectToDatabase();

  // Hash password (for security)
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, $email, $hashedPassword);
  if (!$stmt->execute()) {
    die("Error registering user: " . $conn->error);
  }

  // Close database connection
  $conn->close();

  return true;
}

// Example usage:
$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

if (registerUser($username, $email, $password)) {
  echo "User registered successfully!";
} else {
  echo "Error registering user.";
}


<?php

// Define the database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to the database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Function to register a user
function registerUser($data) {
    // Extract the input data from the array
    $name = $data['name'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $confirmPassword = $data['confirm_password'];

    // Validate the input data
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        return array('error' => 'Please fill in all fields');
    }

    if ($password !== $confirmPassword) {
        return array('error' => 'Passwords do not match');
    }

    // Prepare the query to insert the new user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    // Execute the query
    try {
        $stmt->execute();
        return array('success' => 'User registered successfully');
    } catch (PDOException $e) {
        return array('error' => 'Error registering user: ' . $e->getMessage());
    }
}

// Example usage:
$data = array(
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'mysecretpassword',
    'confirm_password' => 'mysecretpassword'
);

$result = registerUser($data);
print_r($result);

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


function registerUser($name, $email, $password, $confirmPassword) {
    // Validate input data
    if (!$name || !$email || !$password || !$confirmPassword) {
        throw new Exception('All fields are required');
    }

    if (strlen($password) < 8) {
        throw new Exception('Password must be at least 8 characters long');
    }

    if ($password !== $confirmPassword) {
        throw new Exception('Passwords do not match');
    }

    // Connect to database
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');

    // Check connection
    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    // Prepare and execute query
    $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sss', $name, $email, password_hash($password, PASSWORD_DEFAULT));
    mysqli_stmt_execute($stmt);

    // Get new user ID
    $newUserId = mysqli_insert_id($conn);

    // Close connection
    mysqli_close($conn);

    return array('success' => true, 'message' => 'User created successfully', 'userId' => $newUserId);
}


try {
    $result = registerUser('John Doe', 'john@example.com', 'mysecretpassword', 'mysecretpassword');
    if ($result['success']) {
        echo "New user created successfully! ID: " . $result['userId'];
    } else {
        throw new Exception($result['message']);
    }
} catch (Exception $e) {
    echo "Error creating new user: " . $e->getMessage();
}


<?php

function registerUser($username, $email, $password) {
    // Database connection (replace with your own database credentials)
    $dbhost = 'localhost';
    $dbuser = 'your_username';
    $dbpass = 'your_password';
    $dbname = 'your_database';

    try {
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return true; // User successfully registered
        } else {
            return false; // Error registering user
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

?>


if (registerUser('newuser', 'newuser@example.com', 'password123')) {
    echo "User successfully registered!";
} else {
    echo "Error registering user.";
}


if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
    echo "Invalid username.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
} elseif (strlen($password) < 8) {
    echo "Password must be at least 8 characters long.";
}


function registerUser($username, $email, $password) {
    // Input Validation
    if (empty($username) || empty($email) || empty($password)) {
        return array('success' => false, 'message' => "All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('success' => false, 'message' => "Invalid email address.");
    }

    if (strlen($username) < 3 || strlen($username) > 20) {
        return array('success' => false, 'message' => "Username must be between 3 and 20 characters long.");
    }

    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        return array('success' => false, 'message' => "Username can only contain letters and numbers.");
    }

    if (strlen($password) < 8 || strlen($password) > 50) {
        return array('success' => false, 'message' => "Password must be between 8 and 50 characters long.");
    }

    // Password hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to the database (for demonstration purposes)
        $conn = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert user into the database
        $query = "INSERT INTO users SET username = ?, email = ?, password = ?";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $hashed_password);

        if ($stmt->execute()) {
            return array('success' => true, 'message' => "User registered successfully.");
        } else {
            throw new PDOException("Failed to register user.", 1);
        }
    } catch (PDOException $e) {
        return array('success' => false, 'message' => "Database error: " . $e->getMessage());
    }

    // Close the database connection
    $conn = null;

    // Return an empty response in case of unexpected errors
    return array();
}

// Example usage:
$username = "john_doe";
$email = "johndoe@example.com";
$password = "password123";

$response = registerUser($username, $email, $password);
print_r($response);



function register_user($username, $email, $password) {
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('All fields are required');
    }

    // Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

    try {
        // Prepare query
        $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        // Execute query
        $stmt->execute();

        // Get the newly inserted user ID
        $user_id = $db->lastInsertId();

        return array('success' => true, 'message' => 'User registered successfully!', 'user_id' => $user_id);
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }
}


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

try {
    $result = register_user($username, $email, $password);
    if ($result['success']) {
        echo 'User registered successfully!';
    } else {
        echo 'Error registering user: ' . $result['message'];
    }
} catch (Exception $e) {
    echo 'Error registering user: ' . $e->getMessage();
}


function registerUser($name, $email, $password) {
  // Validate input data
  if (empty($name) || empty($email) || empty($password)) {
    throw new Exception("All fields are required");
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Invalid email address");
  }

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Connect to database
  $dbConnection = mysqli_connect("localhost", "username", "password", "database");

  // Check if user already exists
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($dbConnection, $query);
  if (mysqli_num_rows($result) > 0) {
    throw new Exception("Email address is already in use");
  }

  // Insert new user into database
  $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
  mysqli_query($dbConnection, $query);

  // Close database connection
  mysqli_close($dbConnection);

  return true;
}


try {
  registerUser("John Doe", "john@example.com", "password123");
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}


$query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($dbConnection, $query);
mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashedPassword);
mysqli_stmt_execute($stmt);


// register.php

// Connect to database (replace with your own connection string)
$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    throw new Exception($e->getMessage());
}

function registerUser($data)
{
    // Validate input
    if (!isset($data['username']) || empty($data['username'])) {
        throw new Exception('Username is required.');
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address.');
    }
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $data['password'])) {
        throw new Exception('Password must be at least 8 characters long and contain uppercase and lowercase letters, as well as numbers.');
    }

    // Prepare and execute INSERT query
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $stmt->bindParam(':username', $data['username']);
    $stmt->bindParam(':email', $data['email']);
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    $stmt->bindParam(':password', $hashedPassword);

    if ($stmt->execute()) {
        return 'User registered successfully!';
    } else {
        throw new Exception('Error registering user.');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Get form data
        $data = $_POST;

        // Register user
        echo registerUser($data);
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}


<?php

// Configuration variables
define('MIN_USERNAME_LENGTH', 3);
define('MAX_USERNAME_LENGTH', 20);
define('MIN_PASSWORD_LENGTH', 8);

function registerUser($username, $email, $password) {
    // Validate input data
    if (strlen($username) < MIN_USERNAME_LENGTH || strlen($username) > MAX_USERNAME_LENGTH) {
        return 'Username must be between ' . MIN_USERNAME_LENGTH . ' and ' . MAX_USERNAME_LENGTH . ' characters long.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Invalid email address.';
    }

    if (strlen($password) < MIN_PASSWORD_LENGTH) {
        return 'Password must be at least ' . MIN_PASSWORD_LENGTH . ' characters long.';
    }

    // Check for existing user
    $db = connectToDatabase(); // You'll need to implement a database connection function
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
    $stmt->execute([':username' => $username, ':email' => $email]);
    if ($stmt->fetch()) {
        return 'Username or email address already in use.';
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    try {
        $db = connectToDatabase();
        $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->execute([':username' => $username, ':email' => $email, ':password' => $hashedPassword]);
        return 'User registered successfully.';
    } catch (PDOException $e) {
        return 'Error registering user: ' . $e->getMessage();
    }
}

?>


// Assume we have a form with the following fields:
// <input type="text" name="username">
// <input type="email" name="email">
// <input type="password" name="password">

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    echo registerUser($username, $email, $password);
}


<?php
// Configuration variables
$host = 'localhost';
$dbname = 'database_name';
$username = 'username';
$password = 'password';

try {
    // Connect to database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Get form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check for empty fields
        if (empty($name) || empty($email) || empty($password)) {
            throw new Exception("Please fill out all required fields.");
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL query
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");

        try {
            // Bind parameters and execute query
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            $stmt->execute();

            echo "Registration successful. You can log in now.";
        } catch (PDOException $e) {
            throw new Exception("An error occurred while registering the user: " . $e->getMessage());
        }
    }

} catch (PDOException $e) {
    throw new Exception("Error connecting to database: " . $e->getMessage());
}

// Close connection
$conn = null;
?>


<?php

// Configuration
$minUsernameLength = 3;
$maxUsernameLength = 20;
$maxPasswordLength = 50;

function registerUser($username, $password, $email) {
    // Validate input data
    if (!validateUsername($username)) {
        throw new Exception('Invalid username. Must be between ' . $minUsernameLength . ' and ' . $maxUsernameLength . ' characters.');
    }
    if (strlen($password) > $maxPasswordLength || !preg_match('/^[a-zA-Z0-9]+$/', $password)) {
        throw new Exception('Invalid password. Must be no more than 50 characters long and only contain letters and numbers.');
    }
    if (!validateEmail($email)) {
        throw new Exception('Invalid email address.');
    }

    // Hash the password
    $hashedPassword = hash('sha256', $password);

    // Insert user into database (example using PDO)
    try {
        $dsn = 'mysql:host=localhost;dbname=database';
        $usernameDb = 'db_username';
        $passwordDb = 'db_password';

        $pdo = new PDO($dsn, $usernameDb, $passwordDb);
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }
}

function validateUsername($username) {
    return strlen($username) >= $minUsernameLength && strlen($username) <= $maxUsernameLength;
}

function validateEmail($email) {
    return preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
}


try {
    registerUser('johnDoe', 'mysecretpassword123', 'johndoe@example.com');
    echo "User created successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<?php

// Configuration variables (replace with your own)
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function dbConnect() {
    $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
    return $conn;
}

function registerUser($username, $email, $password) {
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('All fields are required');
    }

    try {
        // Create connection to database
        $db = dbConnect();

        // Prepare SQL query
        $stmt = $db->prepare("INSERT INTO users (username, email, password)
                              VALUES (:username, :email, :password)");

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        // Execute query
        if ($stmt->execute()) {
            $newUserId = $db->lastInsertId();

            // Generate session token and insert into sessions table
            $sessionToken = uniqid();
            $stmt = $db->prepare("INSERT INTO sessions (user_id, session_token)
                                  VALUES (:user_id, :session_token)");
            $stmt->bindParam(':user_id', $newUserId);
            $stmt->bindParam(':session_token', $sessionToken);
            $stmt->execute();

            // Return user data and session token
            return array(
                'username' => $username,
                'email' => $email,
                'sessionId' => $db->lastInsertId(),
                'sessionToken' => $sessionToken
            );
        } else {
            throw new Exception('Registration failed');
        }
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }
}

?>


$username = "johnDoe";
$email = "johndoe@example.com";
$password = "password123";

try {
    $userData = registerUser($username, $email, $password);
    echo "Registration successful! User data:
";
    print_r($userData);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


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


<?php

// Configuration variables
$database = 'users.db';
$username = 'root';

// Function to register new users
function registerUser($name, $email, $password) {
  // Check if email already exists in database
  if (checkEmailExists($email)) {
    echo "Error: Email already exists";
    return false;
  }

  // Hash password for secure storage
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  try {
    $conn = new PDO('sqlite:' . $database);
    $stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    echo "User registered successfully!";
    return true;
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    return false;
  }
}

// Function to check if email already exists in database
function checkEmailExists($email) {
  try {
    $conn = new PDO('sqlite:' . $database);
    $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if ($stmt->fetch()) {
      return true;
    }
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
  return false;
}

// Main registration form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get user input
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Register new user
  registerUser($name, $email, $password);
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
</head>
<body>
  <h1>Register</h1>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
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
$max_username_length = 20;
$max_email_length = 50;

function validate_registration($data) {
    global $required_fields, $max_username_length, $max_email_length;

    // Validate required fields
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            throw new Exception("Please fill in all required fields.");
        }
    }

    // Validate username length
    if (strlen($data['username']) > $max_username_length) {
        throw new Exception("Username must be less than or equal to " . $max_username_length . " characters.");
    }

    // Validate email address
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address.");
    }

    // Hash password
    $data['password'] = hash('sha256', $data['password']);

    return true;
}

function register_user($username, $email, $password) {
    global $db;

    try {
        validate_registration(array(
            'username' => $username,
            'email' => $email,
            'password' => $password
        ));

        // Insert user into database
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute(array($username, $email, hash('sha256', $password)));

        return true;

    } catch (Exception $e) {
        // Handle validation errors
        print "Error: " . $e->getMessage() . "
";
        return false;
    }
}

?>


<?php

// Define the database connection details
$host = 'localhost';
$dbname = 'users';
$username = 'root';
$password = '';

// Create a new PDO object
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

function registerUser($name, $email, $password)
{
    // Validate input data
    if (empty($name) || empty($email) || empty($password)) {
        throw new Exception("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address.");
    }

    if (strlen($password) < 8) {
        throw new Exception("Password must be at least 8 characters long.");
    }

    // Hash the password using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Prepare and execute the query to insert a new user
    try {
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        throw new Exception("Error registering user: " . $e->getMessage());
    }
}

// Example usage:
try {
    registerUser('John Doe', 'johndoe@example.com', 'password123');
} catch (Exception $e) {
    echo $e->getMessage();
}


<?php

// Function to register new users
function registerUser($username, $email, $password) {
  // Connect to the database (replace with your own connection code)
  $conn = mysqli_connect("localhost", "username", "password", "database");

  // Check if the database is connected
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Escape special characters in the input data
  $username = mysqli_real_escape_string($conn, $username);
  $email = mysqli_real_escape_string($conn, $email);

  // Hash the password using a secure algorithm (e.g. bcrypt)
  $passwordHash = password_hash($password, PASSWORD_DEFAULT);

  // Check if the user already exists in the database
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $query);
  if ($result->num_rows > 0) {
    echo "Username already exists";
    return false;
  }

  // Insert new user data into the database
  $query = "INSERT INTO users (username, email, password_hash) VALUES ('$username', '$email', '$passwordHash')";
  if (mysqli_query($conn, $query)) {
    echo "User registered successfully";
    return true;
  } else {
    echo "Error registering user: " . mysqli_error($conn);
    return false;
  }

  // Close the database connection
  mysqli_close($conn);
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if (registerUser($username, $email, $password)) {
  echo "Registration successful";
} else {
  echo "Registration failed";
}
?>


function registerUser($firstName, $lastName, $email, $password1, $password2) {
    // Basic input validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password1) || empty($password2)) {
        throw new Exception('All fields are required');
    }

    // Check that the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    // Check that the two passwords match
    if ($password1 !== $password2) {
        throw new Exception('Passwords do not match');
    }

    // Hash password for security
    $hashedPassword = password_hash($password1, PASSWORD_DEFAULT);

    try {
        // Assuming you have a database connection established as `$conn`
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:firstName, :lastName, :email, :password)");
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the prepared statement
        $stmt->execute();

        // If everything went well, return true to indicate successful registration
        return true;
    } catch (PDOException $e) {
        throw new Exception('Failed to register user: ' . $e->getMessage());
    }
}


try {
    // Attempt registration with the function provided above
    if (!registerUser('John', 'Doe', 'john@example.com', 'password123', 'password123')) {
        throw new Exception('Registration failed');
    }

    echo "Registration successful!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<?php

// Configuration
require_once 'config.php';

function registerUser($username, $email, $password) {
  // Validate user input
  if (empty($username) || empty($email) || empty($password)) {
    return array('error' => 'Please fill in all fields');
  }

  // Hash password for security
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  $query = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password)";
  try {
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    if ($stmt->execute()) {
      return array('success' => 'User created successfully');
    } else {
      return array('error' => 'Failed to create user');
    }
  } catch (PDOException $e) {
    return array('error' => 'Database error: ' . $e->getMessage());
  }
}

?>


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);
if ($result['success']) {
  echo "User created successfully!";
} elseif ($result['error']) {
  echo "Error: " . $result['error'];
}


function registerUser($username, $email, $password) {
    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields.');
    }

    // Check for duplicate usernames and emails
    $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        return array('error' => 'Username or email already exists.');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    mysqli_query($conn, $sql);

    // Return success message
    return array('message' => 'User registered successfully.');
}


// Connect to database
$conn = new mysqli('localhost', 'your_username', 'your_password', 'your_database');

// Call the registration function
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

// Print result (assuming you're using a template engine or something similar)
if ($result['error']) {
    echo '<p style="color: red;">' . $result['error'] . '</p>';
} elseif ($result['message']) {
    echo '<p style="color: green;">' . $result['message'] . '</p>';
}


<?php
require 'config.php';

function registerUser($username, $email, $password) {
    // Input validation
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('Please fill in all fields');
    }

    try {
        // Connect to the database
        $pdo = new PDO(DB_HOST, DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create a SQL query to insert the user into the database
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the query
        $result = $stmt->execute();

        // Check if the user was created successfully
        if ($result) {
            return 'User created';
        } else {
            throw new Exception('Error creating user');
        }

    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
        return false;
    }
}
?>


try {
    registerUser('johnDoe', 'johndoe@example.com', 'password123');
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}


<?php

function registerUser($firstName, $lastName, $email, $password) {
    // Validate input data
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        return array('error' => 'Please fill all fields');
    }

    // Check for valid email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    // Create a new user in the database (for demonstration purposes, we'll use an array)
    $users = array();
    foreach ($_SESSION['users'] as $user) {
        if ($user['email'] == $email) {
            return array('error' => 'Email already taken');
        }
    }

    // Hash password for storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Add user to database (array)
    $_SESSION['users'][] = array(
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'password' => $hashedPassword
    );

    return array('message' => 'User created successfully');
}

?>


if (isset($_POST['submit'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = registerUser($firstName, $lastName, $email, $password);

    if (array_key_exists('error', $result)) {
        echo 'Error: ' . $result['error'];
    } else {
        echo 'Success: ' . $result['message'];
    }
}


// Function to register new users
function registerUser($username, $email, $password) {
    // Connect to database (you should replace this with your own database connection)
    $db = mysqli_connect("localhost", "username", "password", "database");

    if (!$db) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query to check if username already exists
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        return array("error" => "Username already taken");
    }

    // Query to register new user
    $query = "INSERT INTO users (username, email, password)
              VALUES ('$username', '$email', '$password')";
    $result = mysqli_query($db, $query);

    if (!$result) {
        return array("error" => "Failed to register user");
    }

    // Close database connection
    mysqli_close($db);

    // Return success message
    return array("message" => "User registered successfully");
}

// Example usage:
$username = "johnDoe";
$email = "johndoe@example.com";
$password = password_hash("password123", PASSWORD_DEFAULT);
$result = registerUser($username, $email, $password);

if (isset($result["error"])) {
    echo "Error: " . $result["error"] . "
";
} else {
    echo "Success: " . $result["message"] . "
";
}


$stmt = $db->prepare("INSERT INTO users (username, email, password)
                      VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $password);
$stmt->execute();

if (!$stmt) {
    return array("error" => "Failed to register user");
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


function registerUser($data) {
  // Validate input data
  if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
    return array('error' => 'Invalid request', 'status' => 400);
  }

  $name = trim(filter_var($data['name'], FILTER_SANITIZE_STRING));
  $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
  $password = password_hash($data['password'], PASSWORD_DEFAULT);

  // Check if email already exists in database
  if (checkEmailExists($email)) {
    return array('error' => 'Email already exists', 'status' => 400);
  }

  // Insert new user into database
  $userId = insertUser($name, $email, $password);

  // Return success response with user ID
  return array('message' => 'User registered successfully', 'user_id' => $userId, 'status' => 201);
}

// Helper function to check if email exists in database
function checkEmailExists($email) {
  global $db; // assume you have a database connection variable named $db

  $query = "SELECT * FROM users WHERE email = :email";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':email', $email);
  $stmt->execute();

  return $stmt->fetchColumn() > 0;
}

// Helper function to insert new user into database
function insertUser($name, $email, $password) {
  global $db; // assume you have a database connection variable named $db

  $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $password);
  $stmt->execute();

  return $db->lastInsertId();
}


$data = array(
  'name' => 'John Doe',
  'email' => 'johndoe@example.com',
  'password' => 'mysecretpassword'
);

$result = registerUser($data);

if ($result['status'] == 201) {
  echo "User registered successfully! User ID: {$result['user_id']}";
} else {
  echo $result['error'];
}


function registerUser($name, $email, $password) {
    // Input Validation
    if (empty($name)) {
        throw new Exception("Name cannot be empty.");
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address.");
    }
    
    if (strlen($password) < 8) {
        throw new Exception("Password must be at least 8 characters long.");
    }

    // Connect to database
    $mysqli = new mysqli("localhost", "username", "password", "database");

    // Check connection
    if ($mysqli->connect_errno) {
        throw new Exception("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Create SQL query
    $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

    // Prepare and execute query
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: (" . $mysqli->errno . ") " . $mysqli->error);
    }

    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if (!$stmt->execute()) {
        throw new Exception("Failed to execute query: (" . $mysqli->errno . ") " . $mysqli->error);
    }

    // Close statement and connection
    $stmt->close();
    $mysqli->close();

    return true;
}


try {
    registerUser('John Doe', 'john@example.com', 'password123');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "
";
}


<?php

/**
 * User registration function.
 *
 * @param array $data Registration data (username, email, password).
 *
 * @return array|bool Registration result or error message on failure.
 */
function registerUser(array $data) {
    // Input Validation
    if (!isset($data['username']) || empty($data['username'])) {
        return ['error' => 'Username is required'];
    }

    if (!isset($data['email']) || empty($data['email'])) {
        return ['error' => 'Email is required'];
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return ['error' => 'Invalid email address'];
    }

    if (!isset($data['password']) || strlen($data['password']) < 8) {
        return ['error' => 'Password must be at least 8 characters long'];
    }

    // Prepare data for database insertion
    $user = [
        'username' => trim($data['username']),
        'email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL),
        'password' => password_hash(trim($data['password']), PASSWORD_DEFAULT),
    ];

    // Simulated database connection and query (replace with actual database operations)
    $db = new mysqli('localhost', 'username', 'password', 'database');

    if ($db->connect_error) {
        return ['error' => 'Database connection failed: ' . $db->connect_error];
    }

    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);

    if (!$stmt) {
        return ['error' => 'Prepare statement error'];
    }

    $result = $stmt->bind_param('sss', $user['username'], $user['email'], $user['password']);

    if (!$result) {
        return ['error' => 'Bind parameter error'];
    }

    if ($stmt->execute()) {
        // Successful registration
        return [
            'success' => true,
            'message' => 'User registered successfully',
            'data' => ['username' => $user['username'], 'email' => $user['email']],
        ];
    } else {
        // Registration failure
        return ['error' => 'Database query error'];
    }

    $stmt->close();
    $db->close();

    return false;
}

// Example usage:
$data = [
    'username' => 'newuser',
    'email' => 'example@example.com',
    'password' => 'strongpassword123',
];

$result = registerUser($data);

if (isset($result['error'])) {
    echo 'Error: ' . $result['error'] . "
";
} elseif (isset($result['success'])) {
    var_dump($result);
}


function register_user($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required');
    }

    // Validate username length
    if (strlen($username) < 3 || strlen($username) > 30) {
        return array('error' => 'Username must be between 3 and 30 characters long');
    }

    // Validate email format
    $email_regex = '/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/';
    if (!preg_match($email_regex, $email)) {
        return array('error' => 'Invalid email address');
    }

    // Validate password length
    if (strlen($password) < 8 || strlen($password) > 50) {
        return array('error' => 'Password must be between 8 and 50 characters long');
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into database (assuming $db is a PDO instance)
    try {
        $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
    } catch (PDOException $e) {
        return array('error' => 'Database error: ' . $e->getMessage());
    }

    // Return success message
    return array('success' => true, 'message' => 'User registered successfully');
}


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = register_user($username, $email, $password);
if ($result['error']) {
    echo 'Error: ' . $result['error'];
} else {
    echo 'User registered successfully!';
}


<?php
function registerUser($username, $email, $password) {
    // Connect to the MySQL database
    require_once 'config.php'; // include your database connection settings here
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Check if the user exists already
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        return array('error' => 'Username already taken');
    }

    // Hash the password using a secure algorithm (e.g., bcrypt)
    require_once 'password_hash.php'; // include your hashing library here
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if ($conn->query($query)) {
        return array('success' => 'User registered successfully');
    } else {
        return array('error' => 'Database error: ' . $conn->error);
    }

    // Close the database connection
    $conn->close();
}

// Example usage:
$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

$result = registerUser($username, $email, $password);

if ($result['success']) {
    echo "Registration successful!";
} elseif ($result['error']) {
    echo "Error: " . $result['error'];
}
?>


<?php

// Configuration (adjust these to suit your setup)
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

function registerUser($username, $email, $password) {
  // Connect to database
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Check for valid data
  if (empty($username) || empty($email) || empty($password)) {
    echo 'Please fill in all fields';
    return false;
  }

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert user into database
  $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('sss', $username, $email, $hashedPassword);

  if ($stmt->execute()) {
    echo 'User registered successfully';
    return true;
  } else {
    echo 'Error registering user: ' . $conn->error;
    return false;
  }

  // Close connection
  $conn->close();
}

?>


registerUser('johnDoe', 'johndoe@example.com', 'password123');


function registerUser($username, $email, $password) {
  // Validate input data
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception("All fields are required.");
  }

  // Check for valid email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Invalid email address.");
  }

  // Hash password for security
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert user data into database
  try {
    // Connect to database (replace with your own connection method)
    $conn = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');

    // Create SQL query
    $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";

    // Prepare and execute query
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    // Execute query
    $stmt->execute();

    // Close database connection
    $conn = null;

    // Return success message
    return array("message" => "User registered successfully.", "username" => $username, "email" => $email);
  } catch (PDOException $e) {
    // Handle database error
    throw new Exception("Database error: " . $e->getMessage());
  }
}


try {
  $result = registerUser('johnDoe', 'johndoe@example.com', 'password123');
  print_r($result);
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}


<?php

/**
 * User Registration Function
 *
 * Registers a new user with the provided email, password, and other details.
 *
 * @param string $email    The user's email address.
 * @param string $password The user's password (hashed before storing).
 * @param string $name     The user's name.
 * @return array          An array containing the registered user's data or an error message if registration fails.
 */
function registerUser($email, $password, $name)
{
    // Check for empty fields
    if (empty($email) || empty($password) || empty($name)) {
        return [
            'success' => false,
            'message' => 'Please fill in all fields.'
        ];
    }

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [
            'success' => false,
            'message' => 'Invalid email address.'
        ];
    }

    // Hash the password for storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database (example using PDO)
    try {
        $dbConnection = new PDO('sqlite:users.db');
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Database connection failed.'
        ];
    }

    // Insert new user data into database
    try {
        $query = "INSERT INTO users (email, password, name) VALUES (:email, :password, :name)";
        $stmt = $dbConnection->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':name', $name);
        $result = $stmt->execute();
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Database insertion failed.'
        ];
    }

    // Return registered user data
    if ($result) {
        return [
            'success' => true,
            'email'   => $email,
            'name'    => $name
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Registration failed.'
        ];
    }
}

?>


$email = 'user@example.com';
$password = 'password123';
$name = 'John Doe';

$result = registerUser($email, $password, $name);

if ($result['success']) {
    echo "User registered successfully!";
} else {
    echo "Registration failed: " . $result['message'];
}


<?php

// Configuration for database connection
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

function registerUser($username, $email, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Create a new user account
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    if (!$stmt->execute()) {
        die("Error registering user: " . $stmt->error);
    }

    // Close the database connection
    $stmt->close();
    $conn->close();

    return true;
}

?>


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if (registerUser($username, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Error registering user.";
}


<?php

// Database connection settings
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

// Create a new database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    // Get the user's data from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash the password before storing it

    // Validate the input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields');
    }

    // Prepare and execute a query to insert new user into database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        return array('success' => 'User successfully registered');
    } else {
        return array('error' => 'Error registering user: ' . $conn->error);
    }
}

// Check if the registration form has been submitted
if (isset($_POST['register'])) {
    // Call the registerUser function and store its response in a variable
    $response = registerUser();
    
    // If there was an error, display it to the user
    if (array_key_exists('error', $response)) {
        echo '<p style="color: red;">' . $response['error'] . '</p>';
    } else {
        // If registration is successful, log in the user and redirect them to the homepage
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    }
}

// Close database connection
$conn->close();

?>


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


<?php

// Configuration settings
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

function registerUser() {
    global $conn;

    // Get user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate user input
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "Please fill in all fields.";
        return false;
    }

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        return false;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to insert user data into the database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        return false;
    }

    // Bind parameters
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    // Execute query
    if (!$stmt->execute()) {
        echo "Error executing query: " . $conn->error;
        return false;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect user to login page
    header("Location: login.php");
}

if (isset($_POST['register'])) {
    registerUser();
} else {
    // Display registration form if the user clicks the "Register" button
    echo "
        <form action='' method='post'>
            <label for='name'>Name:</label>
            <input type='text' id='name' name='name'><br><br>
            <label for='email'>Email:</label>
            <input type='email' id='email' name='email'><br><br>
            <label for='password'>Password:</label>
            <input type='password' id='password' name='password'><br><br>
            <label for='confirm_password'>Confirm Password:</label>
            <input type='password' id='confirm_password' name='confirm_password'><br><br>
            <button type='submit' name='register'>Register</button>
        </form>
    ";
}
?>


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser($username, $email, $password)
{
    // Hash password using SHA-256
    $hashedPassword = sha1($password);

    // SQL query to insert user data into database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return false;
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Register user
    if (registerUser($username, $email, $password)) {
        echo "Registration successful!";
    } else {
        echo "Registration failed.";
    }
}

// Close connection
$conn->close();

?>


<?php

require_once 'register.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (registerUser($username, $email, $password)) {
        echo "Registration successful!";
    } else {
        echo "Registration failed.";
    }
}

?>


<?php

// Database connection settings (replace with your own)
$database_host = 'localhost';
$database_name = 'your_database';
$database_username = 'your_username';
$database_password = 'your_password';

// Function to register new user
function registerUser($name, $email, $password) {
  // Input validation
  if (!isset($name) || !isset($email) || !isset($password)) {
    return array('error' => 'Missing required fields');
  }

  if (empty($name) || empty($email) || empty($password)) {
    return array('error' => 'All fields are required');
  }

  // Check for valid email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return array('error' => 'Invalid email address');
  }

  // Hash password using SHA-256
  $hashed_password = hash('sha256', $password);

  try {
    // Connect to database
    $conn = new PDO("mysql:host=$database_host;dbname=$database_name", $database_username, $database_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert new user into database
    $stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->execute();

    // Return success message
    return array('success' => 'User registered successfully');
  } catch (PDOException $e) {
    // Handle database errors
    echo "Error: " . $e->getMessage();
    return array('error' => 'Failed to register user');
  }
}

// Example usage:
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($name, $email, $password);

if (isset($result['success'])) {
  echo "User registered successfully!";
} elseif (isset($result['error'])) {
  echo "Error: " . $result['error'];
}

?>


<?php

// Configuration settings
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function registerUser($username, $email, $password) {
    // Input validation
    if (empty($username)) {
        throw new Exception("Username is required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address");
    }
    if ($password === '') {
        throw new Exception("Password is required");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database
    $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($mysqli->connect_error) {
        throw new Exception("Database connection failed: " . $mysqli->connect_error);
    }

    // Insert user into database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('sss', $username, $email, $hashedPassword);
    if (!$stmt->execute()) {
        throw new Exception("Failed to insert user: " . $mysqli->error);
    }

    // Close database connection
    $mysqli->close();

    return true;
}

// Example usage:
try {
    registerUser('johnDoe', 'johndoe@example.com', 'password123');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


function registerUser($username, $email, $password) {
    // Check if username and email are valid (e.g., not empty)
    if (empty($username) || empty($email)) {
        return array("error" => "Username and Email cannot be blank.");
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to database
        $conn = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare query
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute query
        if ($stmt->execute()) {
            return array("success" => "User registered successfully.");
        } else {
            throw new Exception('Error registering user.');
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
        return array("error" => "An error occurred while registering the user.");
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);
if (isset($result["error"])) {
    echo "Error: " . $result["error"];
} else if (isset($result["success"])) {
    echo "Success: " . $result["success"];
}


function registerUser($username, $email, $password) {
    // Input Validation
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("All fields are required.");
    }

    if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        throw new Exception("Username can only contain alphanumeric characters and underscores.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address.");
    }

    // Password Validation
    if (strlen($password) < 8 || !preg_match("/[a-zA-Z]/", $password) || !preg_match("/\d/", $password)) {
        throw new Exception("Password must be at least 8 characters, contain at least one letter and one number.");
    }

    // Database Connection (Replace with your own database connection)
    $db = mysqli_connect("localhost", "username", "password", "database");

    if (!$db) {
        throw new Exception("Failed to connect to database: " . mysqli_error($db));
    }

    try {
        // Insert user into database
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, password_hash($password, PASSWORD_DEFAULT));
        mysqli_stmt_execute($stmt);

        if (!mysqli_stmt_affected_rows($stmt)) {
            throw new Exception("Failed to insert user into database.");
        }

        // Return true on successful registration
        return true;
    } catch (Exception $e) {
        // Rollback changes if an error occurs
        mysqli_rollback($db);
        throw $e;
    }
}

// Example usage:
try {
    registerUser("johnDoe", "johndoe@example.com", "password123");
    echo "User registered successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


function register_user($username, $email, $password, $confirm_password) {
    // Validation
    if (empty($username)) {
        throw new Exception("Username is required");
    }

    if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        throw new Exception("Invalid username. Only alphanumeric characters and underscores are allowed.");
    }

    if (empty($email)) {
        throw new Exception("Email is required");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    if (empty($password)) {
        throw new Exception("Password is required");
    }

    if ($password !== $confirm_password) {
        throw new Exception("Passwords do not match");
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    try {
        // Assuming a MySQL database and the user table has columns 'username', 'email', 'password'
        $db = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        // Return user data
        return array('success' => true, 'message' => "User created successfully", 'data' => array('username' => $username, 'email' => $email));
    } catch (PDOException $e) {
        throw new Exception("Database error: " . $e->getMessage());
    }
}


try {
    // Call the function with user input data
    $user_data = register_user('johnDoe', 'johndoe@example.com', 'password123', 'password123');

    // Check if registration was successful
    if ($user_data['success']) {
        echo "User created successfully!";
        print_r($user_data['data']);
    } else {
        throw new Exception($user_data['message']);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<?php

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to register a new user
function registerUser($name, $email, $password, $confirmPassword) {
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL query
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $hashedPassword);
    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }
}

// Function to validate the registration form
function validateRegistrationForm() {
    // Get form data from POST request
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    // Validate input fields
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo "Error: All fields are required.";
        return false;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Error: Invalid email address.";
        return false;
    }

    if ($password !== $confirmPassword) {
        echo "Error: Passwords do not match.";
        return false;
    }

    // If all input fields are valid, register the user
    registerUser($name, $email, $password, $confirmPassword);
    return true;
}

// Handle form submission
if (isset($_POST["submit"])) {
    validateRegistrationForm();
}

?>


<?php

function registerUser($username, $password, $email) {
  // Validate input data
  if (empty($username) || empty($password) || empty($email)) {
    throw new Exception('Please fill out all fields');
  }

  if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
    throw new Exception('Invalid username. Only letters and numbers are allowed.');
  }

  if (strlen($password) < 8 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
    throw new Exception('Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one digit.');
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception('Invalid email address');
  }

  // Hash password
  $passwordHash = password_hash($password, PASSWORD_DEFAULT);

  // Insert user into database (assuming we have a connection to the db established)
  try {
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $passwordHash);
    $stmt->execute();
  } catch (PDOException $e) {
    throw new Exception('Error registering user: ' . $e->getMessage());
  }

  // Return the newly created user's ID
  return $db->lastInsertId();
}

?>


$username = 'john_doe';
$password = 'MyP@ssw0rd';
$email = 'johndoe@example.com';

try {
  $userId = registerUser($username, $password, $email);
  echo "User registered with ID: $userId";
} catch (Exception $e) {
  echo "Error registering user: " . $e->getMessage();
}


<?php

// Define the configuration for the database connection and email settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to register a new user
function registerUser($name, $email, $password) {
    // Connect to the database
    $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check connection
    if ($mysqli->connect_errno) {
        return "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }

    // Hash the password using SHA-256
    $hashedPassword = hash('sha256', $password);

    // Prepare the SQL statement
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

    // Bind the parameters
    if (!$stmt = $mysqli->prepare($sql)) {
        return "Error preparing statement: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    // Bind the parameters to the prepared statement
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    // Execute the query
    if (!$stmt->execute()) {
        return "Error executing query: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    // Close the connection and statement
    $mysqli->close();
    $stmt->close();

    // Return a success message with the new user's ID
    return array(true, 'User successfully registered. Your new ID is: ' . $mysqli->insert_id);
}

// Example usage:
$name = 'John Doe';
$email = 'john@example.com';
$password = 'mysecretpassword';

$result = registerUser($name, $email, $password);

if ($result[0]) {
    echo $result[1];
} else {
    echo $result[1];
}


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

// Define the database connection parameters
$host = 'localhost';
$dbname = 'usersdb';
$username = 'root';
$password = '';

// Connect to the database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

function registerUser($username, $email, $password) {
    // Check if user already exists in the database
    $stmt = $conn->prepare('SELECT * FROM users WHERE username=:username OR email=:email');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        return 'User already exists';
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insert the user into the database
        $stmt = $conn->prepare('INSERT INTO users SET username=:username, email=:email, password=:password');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Return success message
        return 'User created successfully';
    } catch (PDOException $e) {
        echo "An error occurred: " . $e->getMessage();
        return null;
    }
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

echo registerUser($username, $email, $password);

?>


function registerUser($username, $email, $password) {
    // Check if the username or email already exists in the database
    $checkUsername = query("SELECT * FROM users WHERE username = '$username' LIMIT 1");
    $checkEmail = query("SELECT * FROM users WHERE email = '$email' LIMIT 1");

    if ($checkUsername || $checkEmail) {
        return array('error' => 'User already exists');
    }

    // Hash the password
    $passwordHashed = hash('sha256', $password);

    // Insert new user into database
    query("INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$passwordHashed')");

    return array('message' => 'User created successfully');
}

function query($sql) {
    global $db;
    $result = mysqli_query($db, $sql);
    if (!$result) {
        die('Error: ' . mysqli_error($db));
    }
    return $result;
}


function registerUser($username, $email, $password) {
    global $db;

    // Prepare statement
    $stmt = mysqli_prepare($db, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        die('Error: ' . mysqli_error($db));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, hash('sha256', $password));

    // Execute statement
    if (!mysqli_stmt_execute($stmt)) {
        die('Error: ' . mysqli_error($db));
    }

    return array('message' => 'User created successfully');
}


<?php

// Configuration settings
$database_host = 'localhost';
$database_username = 'root';
$database_password = '';
$database_name = 'example_database';

// Function to register new user
function registerUser($username, $email, $password) {
    // Create a connection to the database
    $conn = new mysqli($database_host, $database_username, $database_password, $database_name);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hash password using SHA-256 (not recommended for password storage)
    $hashedPassword = hash('sha256', $password);

    // Prepare SQL query to insert new user data into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "User registered successfully!";
    } else {
        echo "Error registering user: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}

// Handle form submission (e.g. from HTML form)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    registerUser($username, $email, $password);
} else {
    // Handle GET requests (e.g. to display registration form)
}

?>


function registerUser($db, $username, $email, $password) {
    // Check if username and email are already taken
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        throw new Exception("Username or email is already taken.");
    }

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user details into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$passwordHash')";
    mysqli_query($db, $query);
}


$db = new mysqli('localhost', 'username', 'password', 'database');

try {
    registerUser($db, 'johnDoe', 'johndoe@example.com', 'password123');
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

$db->close();


<?php
require 'vendor/autoload.php'; // Composer autoloader

use Illuminate\Database\Capsule\Manager as Capsule;

// Initialize database connection using Laravel's Eloquent ORM
$capsule = new Capsule();
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'my_database',
    'username' => 'my_username',
    'password' => 'my_password',
]);
$capsule->setAsGlobal();

// User registration function
function registerUser($name, $email, $password) {
    // Validate user input
    if (empty($name) || empty($email) || empty($password)) {
        throw new Exception('Please fill in all required fields');
    }

    try {
        // Hash password using bcrypt
        $hashedPassword = bcrypt($password);

        // Create a new user instance
        $user = new App\Models\User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $hashedPassword;

        // Save the user to the database
        $user->save();

        // Send verification email (optional)
        sendVerificationEmail($user);

        return 'User created successfully!';
    } catch (Exception $e) {
        throw new Exception('Error creating user: ' . $e->getMessage());
    }
}

// Verification email function (optional)
function sendVerificationEmail($user) {
    // Set up email variables
    $from = 'your_email@example.com';
    $to = $user->email;
    $subject = 'Verify your account';

    // Generate a random verification code
    $verificationCode = str_random(32);

    // Store the verification code in the user's record
    $user->verification_code = $verificationCode;

    // Send the email using a mail library (e.g. PHPMailer)
    $mail = new PHPMailer();
    $mail->setFrom($from);
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->Body = 'Please click this link to verify your account: ' . url('/verify', ['code' => $verificationCode]);
    $mail->send();

    return 'Verification email sent successfully!';
}

// Example usage
$name = 'John Doe';
$email = 'john@example.com';
$password = 'password123';

try {
    echo registerUser($name, $email, $password);
} catch (Exception $e) {
    echo $e->getMessage();
}


function registerUser($data) {
    // Validate input data
    $errors = validateInputData($data);
    if (!empty($errors)) {
        return array('success' => false, 'message' => 'Error registering user: ' . implode(', ', $errors));
    }

    // Hash password
    $hashedPassword = hashPassword($data['password']);

    // Insert new user into database
    try {
        $db = connectToDatabase();
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
    } catch (PDOException $e) {
        return array('success' => false, 'message' => 'Error registering user: ' . $e->getMessage());
    }

    // Return success message
    return array('success' => true, 'message' => 'User registered successfully');
}

// Helper function to validate input data
function validateInputData($data) {
    $errors = array();
    if (empty($data['username'])) {
        $errors[] = 'Username is required';
    }
    if (empty($data['email'])) {
        $errors[] = 'Email is required';
    }
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $data['email'])) {
        $errors[] = 'Invalid email format';
    }
    if (empty($data['password'])) {
        $errors[] = 'Password is required';
    }
    return $errors;
}

// Helper function to hash password
function hashPassword($password) {
    // Use a secure hashing algorithm like bcrypt or PBKDF2
    // For simplicity, we'll use SHA-256 here
    return sha1($password);
}

// Helper function to connect to database (using PDO)
function connectToDatabase() {
    $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');
    return $db;
}


$data = array(
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
);

$result = registerUser($data);
if ($result['success']) {
    echo "User registered successfully!";
} else {
    echo $result['message'];
}


<?php

function registerUser($username, $email, $password) {
    // Connect to the database (replace with your own connection method)
    include 'db.php';
    
    try {
        // Query to insert new user into users table
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        // Hash the password before inserting into database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->execute();
        
        return true; // Return success if user was added successfully
        
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage(); // If there is an error inserting into database
        return false;
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (registerUser($username, $email, $password)) {
        echo "User registered successfully!";
    } else {
        echo "Error registering user.";
    }
}
?>


function register_user($username, $email, $password) {
    // Sanitize input data
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to database (assuming MySQL)
        require_once 'db_config.php';
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);

        // Prepare and execute query
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception('Error registering user');
        }

    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if (register_user($username, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Error registering user. Please try again.";
}


// db_config.php

$db_host = 'localhost';
$db_name = 'database_name';
$db_username = 'database_username';
$db_password = 'database_password';

$conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);


<?php
require 'dbconnect.php'; // assume you have this file which connects to your database

// validate input fields
if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {
    die('Invalid input');
}

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// hash the password (using PHP's built-in `password_hash` function)
$passwordHashed = password_hash($password, PASSWORD_DEFAULT);

// insert new user into database
$query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $passwordHashed);

if ($stmt->execute()) {
    echo 'User created successfully!';
} else {
    echo 'Error creating user: ' . $pdo->errorInfo()[2];
}
?>


<?php
$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Error connecting to database: ' . $e->getMessage();
}
?>


<?php

// Set up database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define registration form fields
$fieldnames = array(
    'username' => '',
    'email' => '',
    'password' => ''
);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize input data
    $fieldnames['username'] = trim($_POST['username']);
    $fieldnames['email'] = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $fieldnames['password'] = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    // Validate form fields
    if (empty($fieldnames['username']) || empty($fieldnames['email']) || empty($fieldnames['password'])) {
        echo "Please fill out all fields.";
    } elseif (!filter_var($fieldnames['email'], FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
    } else {

        // Insert new user into database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $fieldnames['username'], $fieldnames['email'], $fieldnames['password']);
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error registering user.";
        }
    }

}

// Close database connection
$conn->close();

?>


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

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to register a new user
function register_user($username, $email, $password) {
  // Connect to the database
  $conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

  if (!$conn) {
    die("Connection failed: " . mysqli_error($conn));
  }

  // Escape special characters in input data
  $username = mysqli_real_escape_string($conn, $username);
  $email = mysqli_real_escape_string($conn, $email);
  $password = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into the database
  $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
  if (mysqli_query($conn, $query)) {
    echo "User registered successfully!";
  } else {
    echo "Error registering user: " . mysqli_error($conn);
  }

  // Close the database connection
  mysqli_close($conn);
}

?>


<?php
require_once 'user_registration.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

register_user($username, $email, $password);
?>


<?php

// Define database credentials and connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    // Get user input from the form
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    // Check for empty fields
    if (empty($email) || empty($password) || empty($name)) {
        echo 'Please fill in all fields.';
        return;
    }

    // Hash the password using SHA-256
    $hashed_password = hash('sha256', $password);

    // Prepare and execute the query to insert new user into database
    $stmt = $conn->prepare("INSERT INTO users (email, password, name) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $hashed_password, $name);
    $result = $stmt->execute();

    // Check for errors during query execution
    if ($result === false) {
        echo 'Error registering user.';
        return;
    }

    // Redirect to a success page (optional)
    header('Location: success.php');
}

// Check for POST requests and call the registerUser function
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    registerUser();
} else {
    // Display registration form if not submitting data
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Email: <input type="email" name="email"><br><br>
        Password: <input type="password" name="password"><br><br>
        Name: <input type="text" name="name"><br><br>
        <input type="submit" value="Register">
    </form>
    <?php
}

// Close the database connection
$conn->close();

?>


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

function registerUser($username, $email, $password) {
    // Check if username and email are not empty
    if (empty($username) || empty($email)) {
        return array(
            'success' => false,
            'error_message' => 'Please fill in all fields.'
        );
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to database
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare query
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute query
        if ($stmt->execute()) {
            return array(
                'success' => true,
                'message' => 'User registered successfully.'
            );
        } else {
            return array(
                'success' => false,
                'error_message' => 'Failed to register user.'
            );
        }
    } catch (PDOException $e) {
        return array(
            'success' => false,
            'error_message' => 'Database error: ' . $e->getMessage()
        );
    } finally {
        // Close connection
        if ($conn !== null) {
            $conn = null;
        }
    }
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$response = registerUser($username, $email, $password);

if ($response['success']) {
    echo json_encode(array('message' => $response['message']));
} else {
    echo json_encode(array('error_message' => $response['error_message']));
}
?>


function registerUser($data) {
    // Validate input data
    if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
        return array('error' => 'Missing required fields');
    }

    $username = trim($data['username']);
    $email = trim($data['email']);
    $password = trim($data['password']);

    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required');
    }

    // Check if username and email already exist
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($GLOBALS['db'], $query);
    $count = mysqli_num_rows($result);

    if ($count > 0) {
        return array('error' => 'Username or email already exists');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    mysqli_query($GLOBALS['db'], $query);

    return array('message' => 'User registered successfully');
}

// Example usage:
$data = array(
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'password123'
);
$result = registerUser($data);

if (isset($result['error'])) {
    echo $result['error'];
} else {
    echo $result['message'];
}


$data = array(
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'password123'
);
$result = registerUser($data);

if (isset($result['error'])) {
    echo $result['error'];
} else {
    echo $result['message'];
}


<?php

// Configuration constants
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to register a new user
function registerUser($username, $email, $password) {
    // Input validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    if (strlen($username) < 3 || strlen($username) > 20) {
        return array('error' => 'Username must be between 3 and 20 characters long');
    }

    if (strlen($password) < 8 || strlen($password) > 50) {
        return array('error' => 'Password must be between 8 and 50 characters long');
    }

    // Data encryption
    $hashed_password = md5($password);

    // Database interaction
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        return array('error' => 'Database connection failed: ' . $conn->connect_error);
    }

    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    if (!$stmt->execute()) {
        return array('error' => 'Database query failed: ' . $stmt->error);
    }

    // Return registration success message
    return array('success' => 'User registered successfully');
}

// Example usage:
$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

$result = registerUser($username, $email, $password);
if (isset($result['error'])) {
    echo "Error: " . $result['error'];
} else {
    echo "Success: " . $result['success'];
}


function registerUser($username, $email, $password) {
  // Validate input data
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception("All fields are required.");
  }

  // Hash password
  $hashedPassword = hash('sha256', $password);

  // Connect to database
  $db = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');

  // Check if user already exists
  $query = "SELECT * FROM users WHERE username = :username OR email = :email";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  if ($stmt->fetch()) {
    throw new Exception("Username or email already exists.");
  }

  // Insert new user into database
  $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $hashedPassword);
  $stmt->execute();

  // Return user's ID
  return $db->lastInsertId();
}


try {
  $userId = registerUser('johnDoe', 'johndoe@example.com', 'mysecretpassword');
  echo "User registered successfully. User ID: $userId";
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}


<?php
// Database in the form of an associative array.
// In real-world applications, consider using a more robust database solution.
$users = [];

function registerUser($email, $password, $name) {
    global $users;
    
    // Password hashing for security reasons. Use something like PHPass or BCrypt library
    $hashedPassword = md5($password); // This is very insecure, use password_hash() in real applications.
    
    // Check if email already exists.
    foreach ($users as $user) {
        if ($user['email'] == $email) {
            return ['error' => 'Email already taken.'];
        }
    }

    // Add the user to the database.
    $users[] = [
        'email' => $email,
        'password' => $hashedPassword,  // Insecure method of storing password. Please use secure methods for password storage in a real application
        'name' => $name,
        'verified' => false  // Initially set to false for unverified accounts
    ];
    
    // Send email verification link.
    sendVerificationEmail($email);
    
    return ['success' => 'User registered successfully. Check your inbox for the email verification link.'];
}

function sendVerificationEmail($email) {
    $link = "http://example.com/verify-email?token=token&email=$email";
    
    // Send mail using a PHP mailer or any other library you prefer
    echo "Email sent to: $email with verification link: $link.";
}

// Example usage:
$email = 'your@email.com';
$password = 'password123';
$name = 'John Doe';

$result = registerUser($email, $password, $name);
print_r($result);

?>


function registerUser($username, $email, $password) {
    // Connect to the database (assuming MySQL)
    $conn = mysqli_connect("localhost", "username", "password", "database");

    if (!$conn) {
        die("Connection failed: " . mysqli_error($conn));
    }

    // Check if username already exists
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "Username already taken.";
        return false;
    } else {
        // Hash password using SHA-256
        $passwordHash = hash("sha256", $password);
        
        // Insert new user into database
        $query = "INSERT INTO users (username, email, password_hash) VALUES ('$username', '$email', '$passwordHash')";
        mysqli_query($conn, $query);

        echo "User registered successfully.";
        return true;
    }

    mysqli_close($conn);
}

// Example usage:
registerUser("johnDoe", "johndoe@example.com", "mysecretpassword");


function registerUser($username, $email, $password) {
  // Validate input data
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception('All fields are required');
  }

  // Hash password for storage
  $hashedPassword = hash('sha256', $password);

  // Connect to database
  $conn = mysqli_connect('localhost', 'username', 'password', 'database');

  // Check if user already exists
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
    throw new Exception('Username already taken');
  }

  // Insert new user into database
  $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
  mysqli_query($conn, $query);

  // Close database connection
  mysqli_close($conn);
}


try {
  registerUser('johnDoe', 'johndoe@example.com', 'password123');
  echo 'User registered successfully!';
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}


<?php

// Configuration settings
require_once 'config.php';

function registerUser($name, $email, $password) {
    // Check if email already exists in database
    $existingEmail = getUserByEmail($email);
    if ($existingEmail !== false) {
        return array('error' => 'Email already registered.');
    }

    // Hash password using SHA-256
    $hashedPassword = hash('sha256', $password);

    // Insert new user into database
    $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    if ($stmt->execute()) {
        // User created successfully
        return array('success' => true, 'message' => 'User registered successfully.');
    } else {
        // Error creating user
        return array('error' => 'Error registering user. Please try again.');
    }
}

function getUserByEmail($email) {
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    if ($stmt->execute()) {
        return $stmt->fetch();
    } else {
        return false;
    }
}

// Create a new PDO instance
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

// Register user example usage:
$name = 'John Doe';
$email = 'john.doe@example.com';
$password = 'mysecretpassword';

$response = registerUser($name, $email, $password);
echo json_encode($response);

?>


<?php

// Database configuration settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'database_name');
define('DB_USER', 'database_username');
define('DB_PASSWORD', 'database_password');

?>


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


<?php

function registerUser($username, $email, $password) {
    // Connect to the database
    $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if username and email already exist in the database
    $query = "SELECT * FROM users WHERE username = :username OR email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetchAll();

    // If username or email already exist, return an error message
    if ($result) {
        return array('error' => 'Username or email already exists');
    }

    // Hash the password using a secure hashing algorithm (e.g. bcrypt)
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user into the database
    $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    // Return a success message
    return array('success' => 'User registered successfully');
}

?>


$userData = array('username' => 'john_doe', 'email' => 'johndoe@example.com', 'password' => 'mysecretpassword');
$result = registerUser($userData['username'], $userData['email'], $userData['password']);
if ($result['success']) {
    echo "User registered successfully!";
} else {
    echo $result['error'];
}


function registerUser($username, $email, $password) {
  // Validate input data
  if (empty($username) || empty($email) || empty($password)) {
    return array('error' => 'All fields are required.');
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return array('error' => 'Invalid email address.');
  }

  // Hash the password
  $hashedPassword = hash('sha256', $password);

  try {
    // Connect to database
    $conn = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert new user into database
    $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    if ($stmt->execute()) {
      return array('success' => 'User registered successfully.');
    } else {
      return array('error' => 'Failed to register user.');
    }

  } catch (PDOException $e) {
    // Handle database connection error
    return array('error' => 'Database connection error: ' . $e->getMessage());
  }
}


$registerResult = registerUser('johnDoe', 'john@example.com', 'password123');

if (isset($registerResult['success'])) {
  echo $registerResult['success'];
} elseif (isset($registerResult['error'])) {
  echo $registerResult['error'];
}


function registerUser($name, $email, $password) {
  // Validate input data
  if (empty($name) || empty($email) || empty($password)) {
    return array('error' => 'Please fill out all fields');
  }

  // Check email format
  if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
    return array('error' => 'Invalid email address');
  }

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Connect to database (assuming MySQL)
  $mysqli = new mysqli("localhost", "username", "password", "database");

  if ($mysqli->connect_errno) {
    return array('error' => 'Failed to connect to database');
  }

  // Insert user data into database
  $stmt = $mysqli->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $email, $hashedPassword);

  if (!$stmt->execute()) {
    return array('error' => 'Failed to insert user data');
  }

  // Close database connection
  $mysqli->close();

  // Return success message
  return array('success' => 'User registered successfully!');
}


$name = "John Doe";
$email = "johndoe@example.com";
$password = "mysecretpassword";

$result = registerUser($name, $email, $password);

if (isset($result['error'])) {
  echo "Error: " . $result['error'];
} else {
  echo "Success: " . $result['success'];
}


// configuration.php
require_once 'db_config.php';

function registerUser($username, $email, $password) {
    // validate user input
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    // hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // insert user data into database
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':username' => $username, ':email' => $email, ':password' => $hashedPassword));

        return array('success' => 'User registered successfully');
    } catch (PDOException $e) {
        // error handling
        if ($e->getCode() == 1062) { // duplicate entry error
            return array('error' => 'Username already exists');
        } else {
            return array('error' => 'Error registering user: ' . $e->getMessage());
        }
    }
}


// example usage in register.php
require_once 'configuration.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if ($result['success']) {
    echo "User registered successfully";
} elseif ($result['error']) {
    echo "Error: " . $result['error'];
}


<?php

// Configuration
$required_fields = array('username', 'email', 'password');
$max_password_length = 50;

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user input
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $error[] = 'Please enter your ' . ucfirst($field);
        }
    }

    // Check password length
    if (strlen($password) > $max_password_length) {
        $error[] = 'Password must be less than or equal to 50 characters';
    }

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Invalid email address';
    }

    // Check for duplicate username and email
    $query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $error[] = 'Username or email already exists';
    }

    // If no errors, insert new user into database
    if (empty($error)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        mysqli_query($conn, $query);

        // Set session variables
        $_SESSION['username'] = $username;
        header('Location: index.php');
    }
}

// Display registration form if not submitted
if (empty($_POST)) {
    echo '<form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <button type="submit">Register</button>
    </form>';
}
?>


<?php

// Database connection settings
$conn = new mysqli('localhost', 'username', 'password', 'database');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>


<?php

// Configuration
$dbHost = 'localhost';
$dbName = 'database_name';
$dbUsername = 'username';
$dbPassword = 'password';

// Function to register a new user
function registerUser($userData) {
    // Validate input data
    if (!isset($userData['email']) || !filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    if (!isset($userData['username']) || strlen($userData['username']) < 3 || strlen($userData['username']) > 32) {
        throw new Exception('Username must be between 3 and 32 characters long');
    }

    if (!isset($userData['password']) || strlen($userData['password']) < 8) {
        throw new Exception('Password must be at least 8 characters long');
    }

    // Hash password
    $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);

    // Connect to database
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert user data into database
    try {
        $stmt = $conn->prepare('INSERT INTO users (email, username, password) VALUES (:email, :username, :password)');
        $stmt->bindParam(':email', $userData['email']);
        $stmt->bindParam(':username', $userData['username']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Return success message
        return 'User registered successfully';
    } catch (PDOException $e) {
        throw new Exception('Failed to register user: ' . $e->getMessage());
    }
}

?>


// User data array
$userData = [
    'email' => 'john.doe@example.com',
    'username' => 'johndoe',
    'password' => 'mysecretpassword'
];

try {
    // Call registerUser function with user data
    $result = registerUser($userData);
    echo $result; // Output: User registered successfully
} catch (Exception $e) {
    echo $e->getMessage(); // Output: Error message
}


function registerUser($name, $email, $password) {
    // Input Validation
    if (empty($name)) {
        throw new Exception("Name cannot be empty");
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address");
    }

    if (strlen($password) < 8) {
        throw new Exception("Password must be at least 8 characters long");
    }

    // Password Hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Database Connection
        require 'config.php';
        $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        
        // Insert User Data into Database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if (!$stmt->execute()) {
            throw new Exception("Failed to insert user data");
        }

        // User Registration Successful
        return "Registration successful";

    } catch (PDOException $e) {
        // Error Handling for Database Connection and Queries
        echo "Error: " . $e->getMessage();

    } finally {
        // Close Database Connection
        if ($conn !== null) {
            $conn = null;
        }
    }

}


try {
    $name = 'John Doe';
    $email = 'johndoe@example.com';
    $password = 'strongpassword';

    echo registerUser($name, $email, $password);

} catch (Exception $e) {
    // Handle Exceptions for Input Validation and Database Errors
    echo "Error: " . $e->getMessage();
}


<?php

function registerUser($email, $password, $username) {
  // Check if the email already exists in the database
  $sql = "SELECT * FROM users WHERE email = :email";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  if ($stmt->rowCount() > 0) {
    // Email already exists, return an error
    return array('error' => 'Email already exists');
  }

  // Hash the password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  $sql = "INSERT INTO users (email, password, username) VALUES (:email, :password, :username)";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $hashedPassword);
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  // Return the new user's ID
  return array('id' => $pdo->lastInsertId());
}

// Example usage:
$email = 'example@example.com';
$password = 'password123';
$username = 'johnDoe';

$result = registerUser($email, $password, $username);
if ($result['error']) {
  echo "Error: " . $result['error'];
} else {
  echo "New user created with ID: " . $result['id'];
}


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required.');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Create query to insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    $result = $stmt->execute();

    // Check if user was inserted successfully
    if ($result) {
        return array('success' => 'User created successfully.');
    } else {
        return array('error' => 'Failed to create user. Please try again.');
    }
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if (isset($result['success'])) {
    echo json_encode(array('message' => 'User created successfully!'));
} elseif (isset($result['error'])) {
    echo json_encode(array('error' => $result['error']));
}

// Close database connection
$conn->close();

?>


<?php
require 'config/database.php'; // assume you have a database connection configuration

function registerUser($username, $email, $password)
{
  // Validate user input
  if (empty($username) || empty($email) || empty($password)) {
    return array('error' => 'Please fill out all fields');
  }

  // Check for duplicate username or email
  $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
  $result = mysqli_query($db, $query);
  if (mysqli_num_rows($result) > 0) {
    return array('error' => 'Username or email already exists');
  }

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
  $result = mysqli_query($db, $query);
  if ($result === TRUE) {
    return array('success' => 'User created successfully');
  } else {
    return array('error' => 'Error creating user');
  }
}


$registerData = array(
  'username' => $_POST['username'],
  'email' => $_POST['email'],
  'password' => $_POST['password']
);

$result = registerUser($registerData['username'], $registerData['email'], $registerData['password']);
if ($result['success']) {
  echo "User created successfully!";
} else {
  echo "Error: " . $result['error'];
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
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

// Connect to the database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>


<?php
require_once 'config.php';

// Registration form data
function registerUser() {
    // Get user input from the registration form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate user input
    if (!isset($username) || !isset($email) || !isset($password) || !isset($confirm_password)) {
        die("Invalid input");
    }

    // Check for required fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        return array('error' => 'Please fill in all required fields');
    }

    // Password validation
    if (strlen($password) < 8) {
        return array('error' => 'Password must be at least 8 characters long');
    }
    if ($password !== $confirm_password) {
        return array('error' => 'Passwords do not match');
    }

    // Hash the password
    $hashed_password = hash('sha256', $password);

    // Insert new user into the database
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    if (!$stmt->execute()) {
        die("Error: " . $mysqli->error);
    }

    return array('success' => 'User created successfully');
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = registerUser();
    echo json_encode($result);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
</head>
<body>

<!-- Registration form -->
<form id="register-form" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username"><br><br>
    <label for="email">Email:</label>
    <input type="email" name="email"><br><br>
    <label for="password">Password:</label>
    <input type="password" name="password"><br><br>
    <label for="confirm_password">Confirm Password:</label>
    <input type="password" name="confirm_password"><br><br>
    <button type="submit">Register</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#register-form').submit(function(e) {
            e.preventDefault();
            var data = { username: $('input[name=username]').val(), email: $('input[name=email]').val(), password: $('input[name=password]').val(), confirm_password: $('input[name=confirm_password]').val() };
            $.ajax({
                type: 'POST',
                url: 'userregistration.php',
                data: JSON.stringify(data),
                contentType: "application/json; charset=utf-8",
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('User created successfully!');
                    } else {
                        alert(response.error);
                    }
                }
            });
        });
    });
</script>

</body>
</html>


<?php

function registerUser($email, $username, $password) {
    // Define the database connection parameters (replace with your own)
    $host = 'localhost';
    $db_name = 'users_db';
    $user = 'root';
    $pass = '';

    try {
        // Connect to the database
        $conn = new PDO('mysql:host=' . $host . ';dbname=' . $db_name, $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute a SQL query to insert a new user into the database
        $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            echo "User registered successfully!";
            return true;
        } else {
            echo "Failed to register user. Please try again.";
            return false;
        }
    } catch (PDOException $e) {
        // Handle any database connection errors
        echo "Error: " . $e->getMessage();
        return false;
    } finally {
        // Close the database connection, regardless of whether an exception occurred or not
        if ($conn !== null) {
            $conn = null;
        }
    }
}

?>


<?php

// Include the function that will be used to register users
require 'register_user.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data from POST request
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the registerUser function to attempt registration
    if (registerUser($email, $username, password_hash($password, PASSWORD_DEFAULT))) {
        // Redirect user to a success page or perform any necessary actions after successful registration
        header('Location: login.php');
        exit;
    } else {
        echo "Registration failed. Please try again.";
    }
}

// Display the registration form
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="email">Email:</label>
    <input type="text" id="email" name="email"><br><br>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <button type="submit">Register</button>
</form>


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
$dbh = new PDO($dsn, DB_USERNAME, DB_PASSWORD);

function registerUser() {
  // Check if user is already registered
  $stmt = $dbh->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
  $stmt->bindParam(':username', $_POST['username']);
  $stmt->bindParam(':email', $_POST['email']);
  $stmt->execute();
  if ($stmt->fetch()) {
    echo "Username or email already exists.";
    return;
  }

  // Validate form data
  if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {
    echo "Please fill out all fields.";
    return;
  }

  // Hash password
  $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // Insert user into database
  $stmt = $dbh->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
  $stmt->bindParam(':username', $_POST['username']);
  $stmt->bindParam(':email', $_POST['email']);
  $stmt->bindParam(':password', $hashedPassword);
  $stmt->execute();

  echo "User registered successfully!";
}

if (isset($_POST['register'])) {
  registerUser();
} else {
?>
<form method="post">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username"><br><br>
  <label for="email">Email:</label>
  <input type="email" id="email" name="email"><br><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password"><br><br>
  <input type="submit" value="Register" name="register">
</form>
<?php
}


<?php

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'users');

function register_user($name, $email, $password) {
    // Create a new database connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Check for any errors in the database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hash the password using a secure hashing algorithm (in this case, bcrypt)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $hashed_password);
    if (!$stmt->execute()) {
        echo "Error registering user: " . $stmt->error;
    }

    // Close the database connection
    $conn->close();

    // Return the inserted ID (if successful)
    return $conn->insert_id;
}

?>


$user_id = register_user('John Doe', 'john@example.com', 'password123');
if ($user_id) {
    echo "User registered successfully. User ID: $user_id";
} else {
    echo "Error registering user.";
}


<?php
// Database configuration
$host = "localhost";
$user = "root";
$password = "";
$dbname = "mydatabase";

// Connect to database
$mysqli = new mysqli($host, $user, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function validateInput($data) {
    // Remove backslashes
    $data = stripslashes($data);
    
    // Escape string
    $data = $mysqli->real_escape_string($data);
    
    return $data;
}

function registerUser() {
    global $mysqli;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Input validation and sanitization
        $username = validateInput($_POST['username']);
        $email = validateInput($_POST['email']);
        $password = validateInput($_POST['password']);

        // Check for empty fields
        if (empty($username) || empty($email) || empty($password)) {
            echo "Please fill in all fields";
            return;
        }

        // Password hashing
        $saltedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Query to insert user into database
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$saltedPassword')";

        if ($mysqli->query($query)) {
            echo "User created successfully";
        } else {
            echo "Error creating user: " . $mysqli->error;
        }
    }
}

registerUser();

// Close database connection
$mysqli->close();
?>


function registerUser($userData) {
    // Input Validation
    if (!isset($userData['username']) || !isset($userData['email']) || !isset($userData['password'])) {
        return array('error' => 'Missing required fields');
    }

    $username = trim(filter_var($userData['username'], FILTER_SANITIZE_STRING));
    $email = trim(filter_var($userData['email'], FILTER_VALIDATE_EMAIL));
    $password = trim($userData['password']);

    // Password Hashing
    if (empty($password)) {
        return array('error' => 'Password cannot be empty');
    }

    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Database Interaction
        $mysqli = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_NAME'));
        if ($mysqli->connect_errno) {
            throw new Exception("Failed to connect to MySQL: " . $mysqli->connect_error);
        }

        // SQL Query to Insert User Data
        $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param('sss', $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            return array('success' => true, 'message' => 'User created successfully');
        } else {
            throw new Exception("Error creating user: " . $mysqli->error);
        }
    } catch (Exception $e) {
        // Log any exceptions
        error_log($e->getMessage());

        // Return an error message to the client
        return array('error' => 'An unexpected error occurred');
    } finally {
        if ($stmt instanceof mysqli_stmt) {
            $stmt->close();
        }
        $mysqli->close();
    }
}


// Sample user registration data
$userData = array(
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
);

$result = registerUser($userData);
if (isset($result['error'])) {
    echo "Error: " . $result['error'];
} else {
    echo "Success! User created with ID " . $mysqli->insert_id;
}


<?php

function registerUser($username, $email, $password) {
    // Database connection settings
    $host = 'localhost';
    $dbname = 'mydatabase';
    $user = 'myuser';
    $pass = 'mypass';

    // Connect to database
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    } catch (PDOException $e) {
        echo "Error connecting to database: " . $e->getMessage();
        return false;
    }

    // Hash password
    $passwordHash = hash('sha256', $password);

    // Prepare SQL query
    try {
        $query = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    } catch (PDOException $e) {
        echo "Error preparing SQL query: " . $e->getMessage();
        return false;
    }

    // Bind parameters
    try {
        $query->bindParam(':username', $username);
        $query->bindParam(':email', $email);
        $query->bindParam(':password', $passwordHash);
    } catch (PDOException $e) {
        echo "Error binding parameters: " . $e->getMessage();
        return false;
    }

    // Execute query
    try {
        if ($query->execute()) {
            return true;  // Registration successful
        } else {
            throw new PDOException("Failed to register user");
        }
    } catch (PDOException $e) {
        echo "Error registering user: " . $e->getMessage();
        return false;
    }

    // Close database connection
    try {
        $conn = null;
    } catch (PDOException $e) {
        echo "Error closing database connection: " . $e->getMessage();
    }
}

?>


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

if (registerUser($username, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Failed to register user";
}


<?php

// Configuration
require_once 'config.php'; // This file should contain your database connection settings

function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required.');
    }

    // Check for unique email
    $stmt = $GLOBALS['mysqli']->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->fetch_assoc()) {
        return array('error' => 'Email already in use.');
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $insertUser = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $GLOBALS['mysqli']->prepare($insertUser);
    $stmt->bind_param("sss", $username, $email, $passwordHash);
    if (!$stmt->execute()) {
        return array('error' => 'Database error.');
    }

    // Return success message
    return array('success' => true, 'message' => 'Registration successful!');
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);
echo json_encode($result);

?>


<?php

$mysqli = new mysqli('your_host', 'your_username', 'your_password', 'your_database');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

?>


<?php

require 'config/db.php';

function registerUser($username, $email, $password) {
  // Validate input data
  if (empty($username) || empty($email) || empty($password)) {
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

  try {
    // Connect to database and insert user data
    $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    // Close database connection
    $conn = null;

    return true;
  } catch (PDOException $e) {
    throw new Exception('Error registering user: ' . $e->getMessage());
  }
}

?>


<?php

require 'register_user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    registerUser(
      $_POST['username'],
      $_POST['email'],
      $_POST['password']
    );

    // Redirect to login page or display success message
    header('Location: login.php');
    exit;
  } catch (Exception $e) {
    echo 'Error registering user: ' . $e->getMessage();
  }
}

?>


// Connect to database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Registration form
if (isset($_POST['register'])) {

    // Input validation
    if (!$_POST['username']) {
        $error = 'Please enter a username';
    } elseif (!$_POST['email']) {
        $error = 'Please enter an email address';
    } elseif (!$_POST['password'] || !$_POST['confirm_password']) {
        $error = 'Passwords do not match or are missing';
    } else {

        // Hash password
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Insert into database
        $sql = "INSERT INTO users (username, email, password) 
                VALUES ('" . $_POST['username'] . "', '" . $_POST['email'] . "', '" . $hashedPassword . "')";

        if ($conn->query($sql) === TRUE) {
            echo 'User created successfully';
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Registration form HTML
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="text" name="username" placeholder="Username" required>
  <input type="email" name="email" placeholder="Email Address" required>
  <input type="password" name="password" placeholder="Password" required>
  <input type="password" name="confirm_password" placeholder="Confirm Password" required>
  <button type="submit" name="register">Register</button>
  <?php if ($error) { echo '<div style="color: red;">' . $error . '</div>'; } ?>
</form>

<?php
// Close database connection
$conn->close();
?>


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

function registerUser($username, $email, $password) {
    // Check if the username is already taken
    if (getUserCountByField('username', $username) > 0) {
        return array('error' => 'Username already exists');
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Create a new user record
    $user = array(
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword
    );

    // Insert the user into the database
    insertUserRecord($user);

    return array('success' => true, 'message' => 'Registration successful');
}

?>


<?php

function getUserCountByField($field, $value) {
    // Get the user count from the database
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE $field = ?");
    $stmt->bind_param('s', $value);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_array(MYSQLI_NUM)[0];
}

function insertUserRecord($user) {
    // Insert the user into the database
    global $mysqli;
    $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $user['username'], $user['email'], $user['password']);
    $stmt->execute();
}

?>


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if (isset($result['error'])) {
    echo json_encode(array('error' => $result['error']));
} else {
    echo json_encode($result);
}


<?php

// Configuration
$dbHost = 'localhost';
$dbUsername = 'username';
$dbPassword = 'password';
$dbName = 'database_name';

// Connect to the database
function connectToDatabase() {
    global $dbHost, $dbUsername, $dbPassword, $dbName;
    try {
        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

// Register a new user
function registerUser($username, $email, $password) {
    global $dbHost, $dbUsername, $dbName;

    // Validate the input data
    if (!validateFormData($username, $email, $password)) {
        return array('success' => false, 'message' => 'Invalid form data');
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = connectToDatabase();

    // Insert the new user into the database
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        return array('success' => true, 'message' => 'User created successfully');
    } catch (PDOException $e) {
        echo "Error creating user: " . $e->getMessage();
        return array('success' => false, 'message' => 'Error creating user');
    }

    // Close the database connection
    $conn = null;
}

// Validate form data
function validateFormData($username, $email, $password) {
    if (empty($username)) {
        return false;
    }
    if (empty($email)) {
        return false;
    }
    if (empty($password)) {
        return false;
    }

    // Check for valid email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Minimum password length is 8 characters
    if (strlen($password) < 8) {
        return false;
    }

    return true;
}

?>


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = registerUser($username, $email, $password);
    if ($result['success']) {
        echo 'User created successfully!';
    } else {
        echo 'Error creating user: ' . $result['message'];
    }
}


<?php

// Configuration settings
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
function connect_to_db() {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
}

// Close the database connection
function close_db_connection($conn) {
    $conn->close();
}

// Function to register a new user
function register_user($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required.');
    }

    // Check if username is unique
    $mysqli = connect_to_db();
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
        close_db_connection($mysqli);
        return array('error' => 'Username already exists.');
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    if (!$mysqli->query($query)) {
        close_db_connection($mysqli);
        return array('error' => 'Failed to register user.');
    }

    // Get the newly created user's ID
    $user_id = $mysqli->insert_id;

    close_db_connection($mysqli);

    return array('success' => true, 'user_id' => $user_id);
}

// Example usage:
$username = 'john_doe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

$result = register_user($username, $email, $password);

if ($result['error']) {
    echo "Error: " . $result['error'];
} else {
    echo "User created successfully. User ID: " . $result['user_id'];
}


<?php

// Define database connection settings
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create a PDO object for database interaction
function db_connect() {
  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
  }
}

// User registration function
function register_user($username, $email, $password) {
  try {
    // Connect to the database
    $conn = db_connect();

    // Hash the password using bcrypt
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $hashed_password);

    // Execute the query and get the result
    $result = $stmt->execute();

    if ($result) {
      return true;
    } else {
      throw new Exception("Registration failed");
    }

  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if (!empty($username) && !empty($email) && !empty($password)) {
  if (register_user($username, $email, $password)) {
    echo "User registered successfully!";
  } else {
    echo "Registration failed. Please try again.";
  }
} else {
  echo "Please fill in all fields.";
}

?>


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
    die('Could not connect: ' . mysqli_error());
}

function registerUser() {
    global $conn;

    // Form data (replace with actual form submission data)
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields');
    }

    // Check for duplicate username and email
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return array('error' => 'Username already exists');
    }

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return array('error' => 'Email already exists');
    }

    // Hash password
    $password = hash('sha256', $password);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        return array('success' => 'User registered successfully');
    } else {
        return array('error' => 'Registration failed');
    }
}

// Call the registerUser function
$result = registerUser();

// Display result
if (isset($result['error'])) {
    echo $result['error'];
} elseif (isset($result['success'])) {
    echo $result['success'];
}

?>


function registerUser($username, $email, $password) {
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        return array('success' => false, 'error' => 'Please fill in all fields');
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('success' => false, 'error' => 'Invalid email address');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    try {
        // Assuming a MySQL connection is established
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        return array('success' => true, 'message' => 'User registered successfully');
    } catch (PDOException $e) {
        return array('success' => false, 'error' => 'Error registering user: ' . $e->getMessage());
    }
}


// Register a new user
$result = registerUser('johnDoe', 'johndoe@example.com', 'password123');

if ($result['success']) {
    echo "User registered successfully!";
} else {
    echo "Error registering user: " . $result['error'];
}


function registerUser($name, $email, $password) {
    // Connect to the database
    $mysqli = new mysqli("localhost", "username", "password", "mydb");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare SQL query
    $stmt = $mysqli->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, password_hash($password, PASSWORD_DEFAULT));

    try {
        $stmt->execute();
        return true; // User registered successfully
    } catch (Exception $e) {
        echo "Error registering user: " . $mysqli->error;
        return false; // Registration failed
    }

    $mysqli->close();
}


$name = "John Doe";
$email = "john@example.com";
$password = "password123";

if (registerUser($name, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Registration failed.";
}


<?php

// Configuration settings
$required_fields = array('username', 'email', 'password');
$max_username_length = 50;
$max_password_length = 128;

function validate_input($data) {
    // Remove any whitespace from the input data
    $data = trim($data);

    // Check if all required fields are present and not empty
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            return false;
        }
    }

    // Validate username length
    if (strlen($data['username']) > $max_username_length) {
        return false;
    }

    // Validate password length
    if (strlen($data['password']) > $max_password_length) {
        return false;
    }

    return true;
}

function register_user($data) {
    global $db;

    // Validate input data
    if (!validate_input($data)) {
        throw new Exception('Invalid input');
    }

    try {
        // Hash the password using a secure algorithm (e.g. bcrypt)
        $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);

        // Insert the user into the database
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        return true;
    } catch (Exception $e) {
        // Handle database errors
        throw new Exception('Database error: ' . $e->getMessage());
    }
}

// Example usage:
$data = array(
    'username' => $_POST['username'],
    'email' => $_POST['email'],
    'password' => $_POST['password']
);

try {
    register_user($data);
    echo 'User registered successfully!';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

?>


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'mydatabase');
define('DB_USER', 'myuser');
define('DB_PASSWORD', 'mypassword');

function register_user($username, $email, $password) {
  // Connect to database
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

  // Bind parameters and execute query
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, $email, $password);
  if (!$stmt->execute()) {
    echo "Error: " . $conn->error;
  } else {
    echo "User created successfully!";
  }

  // Close database connection
  $stmt->close();
  $conn->close();

  return true;
}

// Example usage:
$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

if (register_user($username, $email, $password)) {
  echo "Registration successful!";
} else {
  echo "Error registering user.";
}
?>


<?php

// User Registration Function
function registerUser($username, $email, $password, $confirm_password) {
    // Validate input fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        throw new Exception('All fields are required');
    }

    // Validate username length
    if (strlen($username) < 3 || strlen($username) > 32) {
        throw new Exception('Username must be between 3 and 32 characters long');
    }

    // Validate email address format
    $email_pattern = '/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/';
    if (!preg_match($email_pattern, $email)) {
        throw new Exception('Invalid email address');
    }

    // Validate password length and complexity
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        throw new Exception('Password must be at least 8 characters long and contain uppercase, lowercase letters, and numbers');
    }

    // Validate password confirmation
    if ($password !== $confirm_password) {
        throw new Exception('Passwords do not match');
    }

    // Hash the password using PHP's built-in hash function
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database (using PDO for example)
    try {
        $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            echo 'User registered successfully!';
        } else {
            throw new Exception('Error registering user');
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }

    return true;
}

?>


registerUser('username', 'email@example.com', 'password123', 'password123');


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


<?php

// Configuration
$host = 'localhost';
$dbname = 'users';
$username = 'root';
$password = '';

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    // Get user input
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required.');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$hashedPassword')";
    if ($conn->query($sql) === TRUE) {
        return array('message' => 'User created successfully!');
    } else {
        return array('error' => 'Error creating user: ' . $conn->error);
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = registerUser();
    echo json_encode($result);
} else {
    echo "Invalid request method.";
}

// Close database connection
$conn->close();

?>


<?php

// Configuration settings
$required_fields = array('username', 'email', 'password');
$password_min_length = 8;

function register_user($data) {
    // Check if all required fields are present
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            return array(false, "Please fill in all required fields.");
        }
    }

    // Validate email address
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return array(false, "Invalid email address.");
    }

    // Check password length
    if (strlen($data['password']) < $password_min_length) {
        return array(false, "Password must be at least $password_min_length characters long.");
    }

    // Hash the password for secure storage
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

    // Prepare query to insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";

    try {
        // Execute query with prepared statement
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        return array(true, "User registered successfully!");
    } catch (PDOException $e) {
        // Handle database error
        return array(false, "Error registering user: " . $e->getMessage());
    }
}

?>


$data = array(
    'username' => 'john_doe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
);

$result = register_user($data);
if ($result[0]) {
    echo $result[1];
} else {
    echo $result[1];
}


/**
 * User registration function
 *
 * @param array $userData Data submitted by the user during registration
 * @return bool True if the user was successfully registered, false otherwise
 */
function registerUser($userData) {
    // Check if the data is valid
    if (!isset($userData['username']) || !isset($userData['email']) || !isset($userData['password'])) {
        return false;
    }

    // Hash the password
    $hashedPassword = hash('sha256', $userData['password']);

    // Connect to the database
    $dbConnection = new mysqli("localhost", "username", "password", "database");

    if ($dbConnection->connect_error) {
        return false; // Error connecting to database
    }

    // Prepare and execute the insert query
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $dbConnection->prepare($query);
    $stmt->bind_param("sss", $userData['username'], $userData['email'], $hashedPassword);

    if (!$stmt->execute()) {
        return false; // Error executing query
    }

    // Close the database connection and return true
    $dbConnection->close();
    return true;
}


// Define the user data array
$userData = [
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
];

// Call the registerUser function and check if the user was successfully registered
if (registerUser($userData)) {
    echo "User registered successfully!";
} else {
    echo "Error registering user.";
}


<?php

// Database credentials
$host = 'localhost';
$dbname = 'yourdatabase';
$username = 'yourusername';
$password = 'yourpassword';

// Connect to the database
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

function registerUser($username, $email, $password1, $password2)
{
    // Validate inputs
    if ($password1 !== $password2) {
        return array(false, 'Passwords do not match');
    }

    try {
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $hashedPassword = password_hash($password1, PASSWORD_DEFAULT); // Hash the password
        $stmt->execute([$username, $email, $hashedPassword]);
        
        return array(true, 'User registered successfully');
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') { // Duplicate entry for email or username
            return array(false, 'Username or Email already exists');
        }
        echo "Error: " . $e->getMessage();
        return array(false, 'Registration failed');
    } finally {
        unset($conn);
    }
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];

$result = registerUser($username, $email, $password1, $password2);

if ($result[0]) {
    echo 'Registration successful';
} else {
    echo 'Error: ' . $result[1];
}

?>


// database connection settings (replace with your own)
$host = 'localhost';
$db_name = 'users_db';
$username = 'root';
$password = '';

function connectDB() {
    global $host, $db_name, $username, $password;
    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        return $conn;
    } catch (PDOException $e) {
        echo "Error connecting to database: " . $e->getMessage();
        exit();
    }
}

function registerUser($email, $username, $password) {
    // validate input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }
    
    // hash password (for security)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $conn = connectDB();
        
        // check for existing user with same username or email
        $query = "SELECT * FROM users WHERE username=:username OR email=:email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            throw new Exception('User already exists');
        }
        
        // insert new user
        $query = "INSERT INTO users (email, username, password) VALUES (:email, :username, :password)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        
        return true; // user registered successfully
    } catch (PDOException $e) {
        echo "Error registering user: " . $e->getMessage();
        exit();
    }
}

// example usage:
try {
    registerUser('example@example.com', 'johnDoe', 'password123');
} catch (Exception $e) {
    echo "Registration error: " . $e->getMessage();
}


<?php

// Configuration variables
$server = 'localhost';
$username = 'your_username';
$password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($server, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to register a user
function registerUser($name, $email, $password)
{
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert new user into database
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Example usage
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Call the registerUser function
    if (registerUser($name, $email, $password)) {
        echo "Registration successful!";
    } else {
        echo "Registration failed.";
    }
}

// Close database connection
$conn->close();

?>


<?php

// Function to register a new user
function registerUser($username, $email, $password) {
  // Check if the username or email are empty
  if (empty($username) || empty($email)) {
    return array('error' => 'Username and Email cannot be empty');
  }

  // Hash the password using SHA-256
  $hashedPassword = hash('sha256', $password);

  // Connect to the database
  $conn = mysqli_connect("localhost", "username", "password", "database");

  // Check connection
  if (!$conn) {
    return array('error' => 'Could not connect to database');
  }

  // Prepare query to insert new user into database
  $stmt = mysqli_prepare($conn, "
    INSERT INTO users (username, email, password)
    VALUES (?, ?, ?)
  ");

  // Bind parameters
  mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);

  // Execute query
  if (!mysqli_stmt_execute($stmt)) {
    return array('error' => 'Could not register user');
  }

  // Close statement and connection
  mysqli_stmt_close($stmt);
  mysqli_close($conn);

  // Return success message
  return array('success' => 'User registered successfully');
}

// Example usage:
$registerData = array(
  'username' => 'johnDoe',
  'email' => 'johndoe@example.com',
  'password' => 'secret'
);

$result = registerUser($registerData['username'], $registerData['email'], $registerData['password']);

if ($result['error']) {
  echo "Error: " . $result['error'];
} else {
  echo "Success: " . $result['success'];
}

?>


function registerUser($userData) {
    // Input Validation
    if (empty($userData['username']) || empty($userData['email']) || empty($userData['password'])) {
        return array('error' => 'Please fill out all fields');
    }

    $username = trim($userData['username']);
    $email = trim($userData['email']);
    $password = trim($userData['password']);

    // Password Hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Database Connection
    require_once 'database.php';
    $db = new mysqli($host, $user, $pass, $dbname);

    if ($db->connect_error) {
        return array('error' => 'Database connection failed');
    }

    // Query to Insert User Data
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    $result = $db->query($query);

    if (!$result) {
        return array('error' => 'Registration failed');
    }

    // Return Registration Success Response
    return array('success' => true);
}


$host = 'localhost';
$user = 'your_username';
$pass = 'your_password';
$dbname = 'your_database';

$db = new mysqli($host, $user, $pass, $dbname);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}


$registerData = array(
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
);

$result = registerUser($registerData);

if ($result['success']) {
    echo "Registration successful!";
} else {
    echo $result['error'];
}


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
function connectToDatabase() {
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
        return $conn;
    } catch (PDOException $e) {
        echo "Error connecting to database: " . $e->getMessage();
        exit;
    }
}

// Register user
function registerUser($name, $email, $password) {
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query
    $conn = connectToDatabase();
    $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    // Execute query
    try {
        if ($stmt->execute()) {
            echo "User registered successfully!";
        } else {
            throw new Exception('Error registering user');
        }
    } catch (Exception $e) {
        echo "Error registering user: " . $e->getMessage();
    }

    // Close database connection
    $conn = null;
}

// Example usage:
registerUser("John Doe", "john@example.com", "password123");

?>


function registerUser($username, $email, $password) {
    // Check for valid input
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('All fields are required');
    }

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    // Hash password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database
    $db = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), 'your_database');

    if ($db->connect_error) {
        throw new Exception('Failed to connect to database');
    }

    // Insert user into database
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $username, $email, $hashedPassword);
    $result = $stmt->execute();

    if (!$result) {
        throw new Exception('Failed to insert user into database');
    }

    // Close database connection
    $db->close();

    return array(
        'message' => 'User registered successfully',
        'id' => $db->insert_id,
    );
}


try {
    $result = registerUser('johnDoe', 'johndoe@example.com', 'password123');
    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(array('message' => $e->getMessage()));
}


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

// Include database configuration file
require_once 'dbconfig.php';

function registerUser($username, $email, $password) {
    // Check for empty fields and validate email address
    if (empty($username) || empty($email) || empty($password)) {
        return array('success' => false, 'error' => 'All fields are required.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('success' => false, 'error' => 'Invalid email address.');
    }

    // Hash the password
    $password = hash('sha256', $password);

    // Prepare query to insert user data into database
    $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    try {
        // Execute query to insert user data into database
        $stmt->execute();
        
        return array('success' => true, 'message' => 'User registered successfully.');
    
    } catch (PDOException $e) {
        // Return error message if any
        return array('success' => false, 'error' => 'Failed to register user: ' . $e->getMessage());
    }
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

echo json_encode($result);


<?php

$pdo = new PDO('mysql:host=localhost;dbname=yourdatabase', 'yourusername', 'yourpassword');

?>


<?php

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

function registerUser($username, $email, $password) {
    // Connect to the database
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check for existing username and email
    $query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return false; // User already exists
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $insertQuery = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if ($conn->query($insertQuery) === TRUE) {
        return true; // User registered successfully
    } else {
        return false; // Error registering user
    }

    $conn->close();
}

// Example usage:
$username = "newuser";
$email = "newuser@example.com";
$password = "password123";

if (registerUser($username, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Error registering user.";
}


/**
 * Register a new user.
 *
 * @param string $username Unique username.
 * @param string $email User email address.
 * @param string $password User password (hashed).
 * @return bool True on successful registration, false otherwise.
 */
function registerUser($username, $email, $password)
{
    // Validate input data
    if (!validateUsername($username) || !validateEmail($email)) {
        return false;
    }

    // Hash the password
    $hashedPassword = hashPassword($password);

    // Insert user into database
    try {
        $db = new Database();
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
    } catch (PDOException $e) {
        // Handle database error
        echo "Error registering user: " . $e->getMessage() . "
";
        return false;
    }

    return true;
}

/**
 * Validate a username.
 *
 * @param string $username Unique username to validate.
 * @return bool True if valid, false otherwise.
 */
function validateUsername($username)
{
    // Check length (min 3 characters)
    if (strlen($username) < 3) {
        return false;
    }

    // Check for invalid characters
    if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        return false;
    }

    return true;
}

/**
 * Validate an email address.
 *
 * @param string $email User email address to validate.
 * @return bool True if valid, false otherwise.
 */
function validateEmail($email)
{
    // Check format using regular expression
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        return false;
    }

    return true;
}

/**
 * Hash a password.
 *
 * @param string $password User password to hash.
 * @return string Hashed password.
 */
function hashPassword($password)
{
    // Use a secure hashing algorithm (e.g. bcrypt)
    return password_hash($password, PASSWORD_BCRYPT);
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

if (registerUser($username, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Error registering user.";
}


// Configuration
$minUsernameLength = 3;
$maxUsernameLength = 30;
$minPasswordLength = 8;

function registerUser($username, $email, $password) {
    global $minUsernameLength, $maxUsernameLength, $minPasswordLength;

    // Validate input
    if (strlen($username) < $minUsernameLength || strlen($username) > $maxUsernameLength) {
        throw new Exception("Invalid username. Must be between $minUsernameLength and $maxUsernameLength characters.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address.");
    }
    if (strlen($password) < $minPasswordLength) {
        throw new Exception("Password must be at least $minPasswordLength characters.");
    }

    // Hash password
    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    } catch (Exception $e) {
        throw new Exception("Failed to hash password: " . $e->getMessage());
    }

    // Store user in database (replace with your own database code)
    $query = "INSERT INTO users SET username = ?, email = ?, password = ?";
    try {
        $stmt = $GLOBALS['db']->prepare($query);
        $stmt->execute([$username, $email, $hashedPassword]);
    } catch (Exception $e) {
        throw new Exception("Failed to store user in database: " . $e->getMessage());
    }

    return true;
}


try {
    registerUser('johnDoe', 'johndoe@example.com', 'password123');
} catch (Exception $e) {
    echo 'Registration failed: ' . $e->getMessage();
}


<?php

// Define the connection settings for your database.
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Connect to the database.
$conn = new mysqli($db_host, $db_password, $db_password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check if email already exists in the database
function checkEmailExists($email) {
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    return (mysqli_num_rows($result) > 0);
}

// Function to register a user
function registerUser() {
    global $conn;

    if (isset($_POST['register'])) {

        // Retrieve data from the form.
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Check that password and confirm password match
        if ($password !== $confirm_password) {
            echo "Passwords don't match";
            return;
        }

        // Validate email address.
        if (!preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}+$/", $email)) {
            echo "Invalid Email Address.";
            return;
        }

        // Check that user hasn't already registered with this email address.
        if (checkEmailExists($email)) {
            echo "You have already registered. Try logging in instead";
            return;
        }

        // Insert the new user's data into the database.
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', md5('$password'))";

        if ($conn->query($sql) === TRUE) {
            echo "You have been successfully registered.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Register a new user.
registerUser();

// Close the database connection
$conn->close();
?>


<?php

// Configuration for database connection
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Create a connection to the database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data from registration form
$email = $_POST['email'];
$password = $_POST['password'];

// Hash password for secure storage
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Prepare SQL statement to insert user into database
$stmt = $conn->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");

// Bind parameters to prevent SQL injection attacks
$stmt->bind_param('ss', $email, $password_hash);

// Execute the prepared statement
$stmt->execute();

// Get the last inserted ID
$user_id = $conn->insert_id;

// Generate a verification token and store it in the database
$token = bin2hex(random_bytes(16));
$conn->query("UPDATE users SET verification_token = '$token' WHERE id = '$user_id'");

// Send email to user with verification link
$subject = "Verify Your Email";
$message = "
    <p>Hello $email,</p>
    <p>Click the following link to verify your email address:</p>
    <a href='verify.php?token=$token'>Verify</a>

";
$headers[] = 'From: admin@example.com';
$headers[] = 'Content-Type: text/html; charset=UTF-8';

mail($email, $subject, $message, implode("\r
", $headers));

// Redirect user to a success page
header("Location: register_success.php");
exit;

// Close the database connection
$conn->close();

?>


<?php

// Configuration for database connection
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Create a connection to the database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get GET data from verification link
$token = $_GET['token'];

// Prepare SQL statement to update user's verified status in database
$stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE verification_token = '$token'");

// Execute the prepared statement
$stmt->execute();

// Close the database connection
$conn->close();

?>


<?php

// Configuration
$required_fields = array('username', 'email', 'password');
$db_host = 'localhost';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function register_user() {
    global $required_fields;
    global $conn;

    // Get form data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate input fields
        foreach ($required_fields as $field) {
            if (empty($$field)) {
                echo 'Error: Please fill in all required fields.';
                return false;
            }
        }

        // Check for duplicate username and email
        $query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo 'Error: Username or Email already exists.';
            return false;
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into database
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        if ($conn->query($query)) {
            echo 'User registered successfully!';
            return true;
        } else {
            echo 'Error: Registration failed.';
            return false;
        }
    }

    // Form not submitted
    $page_content = '
    <h1>Register</h1>
    <form method="post">
        <label>Username:</label><br />
        <input type="text" name="username"><br /><br />
        <label>Email:</label><br />
        <input type="email" name="email"><br /><br />
        <label>Password:</label><br />
        <input type="password" name="password"><br /><br />
        <input type="submit" value="Register">
    </form>
    ';
}

// Call the register_user function
register_user();

?>


function registerUser($name, $email, $password) {
    // Validate input
    if (empty($name) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required');
    }

    // Check for valid email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Create a new user record in the database (for example using PDO)
    try {
        $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');

        $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        $result = $stmt->execute();

        if ($result) {
            return array('success' => 'User registered successfully');
        } else {
            return array('error' => 'Failed to register user');
        }
    } catch (PDOException $e) {
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}


$name = 'John Doe';
$email = 'john@example.com';
$password = 'password123';

$result = registerUser($name, $email, $password);

if ($result['success']) {
    echo "User registered successfully!";
} elseif ($result['error']) {
    echo "Error: " . $result['error'];
}


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function registerUser($username, $email, $password) {
    // Input validation
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required.');
    }

    // Password hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert new user
    $query = "INSERT INTO users (username, email, password)
              VALUES ('$username', '$email', '$hashedPassword')";

    if ($mysqli->query($query)) {
        return array('success' => 'User created successfully!');
    } else {
        return array('error' => 'Error creating user: ' . $mysqli->error);
    }
}

// Example usage
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

// Output result
echo json_encode($result);

$mysqli->close();
?>


<?php

function register_user($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required');
    }

    // Validate username and email
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        return array('error' => 'Invalid username');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into database
    try {
        $db = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        // Return success message
        return array('success' => 'User registered successfully');
    } catch (PDOException $e) {
        // Handle database error
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}

?>


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = register_user($username, $email, $password);

if ($result['error']) {
    echo 'Error: ' . $result['error'];
} else {
    echo 'Success: ' . $result['success'];
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


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


// Define constants for the database connection parameters
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Create a function to register a new user
function registerUser($username, $email, $password) {
    // Connect to the database
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if ($conn->query($sql) === TRUE) {
        echo "New user created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}

// Example usage:
registerUser('johnDoe', 'johndoe@example.com', 'mysecretpassword');


<?php

// Define the database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function register_user() {
    global $conn;

    // Get user input from the registration form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if username and email are already taken
    $check_username = $conn->prepare("SELECT * FROM users WHERE username=?");
    $check_email = $conn->prepare("SELECT * FROM users WHERE email=?");

    $check_username->bind_param('s', $username);
    $check_email->bind_param('s', $email);

    $check_username->execute();
    $check_email->execute();

    if ($check_username->get_result()->num_rows > 0 || $check_email->get_result()->num_rows > 0) {
        echo "Username or email already taken.";
        return;
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $insert_user = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $insert_user->bind_param('sss', $username, $email, $password_hash);

    if ($insert_user->execute()) {
        echo "User registered successfully!";
    } else {
        echo "Error registering user: " . $conn->error;
    }
}

// Call the register_user function when the registration form is submitted
if (isset($_POST['register'])) {
    register_user();
}

?>


<!-- registration.html -->
<form action="register.php" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Password">
    <button type="submit" name="register">Register</button>
</form>


<!-- register.php -->
<?php include 'register.php'; ?>


// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Function to connect to the database
function dbConnect() {
  global $host, $dbname, $username, $password;
  try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    return $conn;
  } catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
  }
}

// Function to register a user
function registerUser($data) {
  global $host, $dbname, $username, $password;

  // Connect to the database
  $conn = dbConnect();

  // Validation rules
  $nameValidationRules = array(
    'required' => true,
    'minLength' => 3,
    'maxLength' => 50
  );
  
  $emailValidationRules = array(
    'required' => true,
    'format' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
  );
  
  $passwordValidationRules = array(
    'required' => true,
    'minLength' => 8
  );

  // Validate the input data
  if (!validateInput($data['name'], $nameValidationRules)) {
    return array('error' => 'Invalid name');
  }
  
  if (!validateEmail($data['email'], $emailValidationRules)) {
    return array('error' => 'Invalid email address');
  }
  
  if (!validatePassword($data['password'], $passwordValidationRules)) {
    return array('error' => 'Invalid password');
  }

  // Insert the user data into the database
  try {
    $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':password', $data['password']);
    if ($stmt->execute()) {
      return array('message' => 'User registered successfully');
    } else {
      return array('error' => 'Failed to register user');
    }
  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }
}

// Function to validate the input data
function validateInput($data, $validationRules) {
  if ($validationRules['required'] && empty($data)) {
    return false;
  }

  if ($validationRules['minLength'] !== null && strlen($data) < $validationRules['minLength']) {
    return false;
  }
  
  if ($validationRules['maxLength'] !== null && strlen($data) > $validationRules['maxLength']) {
    return false;
  }
  
  if (isset($validationRules['format']) && !preg_match($validationRules['format'], $data)) {
    return false;
  }

  return true;
}

// Function to validate the email address
function validateEmail($email, $validationRules) {
  if ($validationRules['required'] && empty($email)) {
    return false;
  }
  
  if (isset($validationRules['format']) && !preg_match($validationRules['format'], $email)) {
    return false;
  }

  return true;
}

// Function to validate the password
function validatePassword($password, $validationRules) {
  if ($validationRules['required'] && empty($password)) {
    return false;
  }
  
  if (isset($validationRules['minLength']) && strlen($password) < $validationRules['minLength']) {
    return false;
  }

  return true;
}

// Example usage
$data = array(
  'name' => 'John Doe',
  'email' => 'johndoe@example.com',
  'password' => 'mysecretpassword'
);

$result = registerUser($data);
print_r($result);


function registerUser($username, $email, $password) {
    // Input validation
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("All fields are required.");
    }

    if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        throw new Exception("Username can only contain letters, numbers and underscores.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address.");
    }

    // Password hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Database interaction
    try {
        $conn = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashedPassword);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Failed to register user.");
        }
    } catch (PDOException $e) {
        throw new Exception($e->getMessage());
    } finally {
        // Close the database connection
        $conn = null;
    }

    return false;
}


try {
    $registered = registerUser("johnDoe", "johndoe@example.com", "password123");
    if ($registered) {
        echo "User registered successfully.";
    } else {
        throw new Exception("Failed to register user.");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("All fields are required.");
    }

    if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
        throw new Exception("Username can only contain letters and numbers.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address.");
    }

    if (strlen($password) < 8 || !preg_match("/[a-z]/", $password) || !preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
        throw new Exception("Password must be at least 8 characters and contain a lowercase letter, an uppercase letter, and a number.");
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to database (using PDO)
        $db = new PDO('sqlite:users.db');
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Close the database connection
        $db = null;

        return "User registered successfully!";
    } catch (PDOException $e) {
        throw new Exception("Error registering user: " . $e->getMessage());
    }
}


try {
    echo registerUser('johnDoe', 'johndoe@example.com', 'P@ssw0rd');
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}


function registerUser($username, $email, $password)
{
    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields');
    }

    // Check if username or email already exists
    $query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        return array('error' => 'Username or email already taken');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if (!mysqli_query($db, $query)) {
        return array('error' => 'Error registering user');
    }

    // Send confirmation email
    sendConfirmationEmail($username, $email);

    return array('success' => true);
}

function sendConfirmationEmail($username, $email)
{
    // Email configuration (replace with your own settings)
    $from = 'your-email@example.com';
    $subject = 'Account Confirmation';
    $body = "Hello $username,

Please click on the following link to activate your account:
http://example.com/activate?user=$username";

    mail($email, $subject, $body, "From: $from");
}

// Example usage
$db = mysqli_connect('localhost', 'your-username', 'your-password', 'your-database');
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if ($result['success']) {
    echo "User registered successfully!";
} else {
    echo json_encode($result);
}


<?php

function registerUser($username, $email, $password) {
  // Connect to the database (assuming you have a MySQL connection established)
  $conn = mysqli_connect("localhost", "your_username", "your_password", "your_database");

  // Check if the username and email are already in use
  $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    return array("error" => "Username or email already in use");
  }

  // Hash the password using SHA-256
  $passwordHash = hash('sha256', $password);

  // Insert new user into the database
  $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$passwordHash')";
  mysqli_query($conn, $query);

  return array("success" => true);
}

// Example usage:
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];

$result = registerUser($username, $email, $password);

if ($result["error"]) {
  echo "Error: " . $result["error"];
} else {
  echo "User registered successfully!";
}

?>


$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];

$result = registerUser($username, $email, $password);


<?php
require_once 'config/db.php'; // assuming you have db connection config in this file

function registerUser($username, $email, $password) {
  // Validate input
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception('All fields are required');
  }

  // Check for valid email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception('Invalid email format');
  }

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  try {
    // Prepare and execute SQL query to insert user into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    // Return user ID if registration is successful
    return $conn->lastInsertId();
  } catch (PDOException $e) {
    throw new Exception('Error registering user: ' . $e->getMessage());
  }
}

// Example usage:
try {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Validate and sanitize input from POST request
  if (!isset($username) || !isset($email) || !isset($password)) {
    throw new Exception('Invalid request');
  }

  $userId = registerUser($username, $email, $password);
  echo 'User registered successfully with ID: ' . $userId;
} catch (Exception $e) {
  echo 'Error registering user: ' . $e->getMessage();
}


function registerUser($name, $email, $password) {
    // Check if the input data is valid
    if (empty($name) || empty($email) || empty($password)) {
        throw new Exception('All fields are required');
    }

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Attempt to register the user in your database (using a database library like PDO)
    try {
        // Replace 'database' with your actual database connection credentials
        $conn = new PDO('sqlite:users.db');
        $stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
    } catch (PDOException $e) {
        // If the user already exists in the database
        throw new Exception('User already exists');
    }

    return true;
}

// Example usage:
try {
    $name = 'John Doe';
    $email = 'john.doe@example.com';
    $password = 'mysecretpassword';

    if (registerUser($name, $email, $password)) {
        echo 'User registered successfully!';
    } else {
        throw new Exception('Registration failed');
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database_name');

function registerUser($data) {
    // Form validation
    if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
        return array('error' => 'Invalid form data');
    }

    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    // Database connection
    $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

    try {
        // Query to insert user data into database
        $stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        // Execute query
        $stmt->execute();

        // User registration successful
        return array('success' => 'User registered successfully');
    } catch (PDOException $e) {
        // Error handling for database connection issues
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}

?>


$data = array(
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'mysecretpassword'
);

$result = registerUser($data);

if ($result['success']) {
    echo 'User registered successfully!';
} elseif ($result['error']) {
    echo $result['error'];
}


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

// Configuration
require_once 'config.php';

function registerUser($username, $email, $password) {
    // Input Validation
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address.');
    }

    if (strlen($password) < 8) {
        return array('error' => 'Password must be at least 8 characters long.');
    }

    // Password Hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Database Interaction
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return array('success' => true, 'message' => 'User created successfully.');
        } else {
            return array('error' => 'Failed to create user.');
        }
    } catch (PDOException $e) {
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


<?php

$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>


function registerUser($username, $email, $password)
{
    // Input validation
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    // Password hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Database connection and query
        $db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        $stmt->execute();

        // Email verification
        sendVerificationEmail($email, $username);

        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function sendVerificationEmail($email, $username)
{
    // Implement email sending logic here (e.g., using PHPMailer or SwiftMailer)
}


try {
    registerUser('johnDoe', 'johndoe@example.com', 'password123');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<?php

/**
 * User Registration Function
 *
 * @param array $data User registration data (username, email, password, confirm_password)
 * @return bool|bool|null Whether the registration was successful or not
 */
function registerUser(array $data): bool | null {
    // Validation rules
    $rules = [
        'username' => 'required|string|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:8',
        'confirm_password' => 'required|same:password'
    ];

    try {
        // Validate the input data
        $validator = new Validator($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Hash the password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Create a new user record
        $user = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $hashedPassword,
        ];

        // Insert the new user into the database
        $result = dbInsert('users', $user);

        if ($result) {
            return true;
        } else {
            throw new DatabaseException();
        }

    } catch (ValidationException $e) {
        // Return validation errors
        return [
            'errors' => $e->getErrors()
        ];
    } catch (DatabaseException $e) {
        // Log the error and return a message
        logError('Failed to register user: ' . $e->getMessage());
        return false;
    }
}

// Helper function to insert data into the database using PDO
function dbInsert(string $table, array $data): bool {
    try {
        $sql = 'INSERT INTO ' . $table . ' SET ';
        $params = [];

        foreach ($data as $key => $value) {
            $sql .= $key . ' = ?,';
            $params[] = $value;
        }

        $sql = rtrim($sql, ',');
        $stmt = db()->prepare($sql);

        return $stmt->execute($params);
    } catch (PDOException $e) {
        throw new DatabaseException();
    }
}

// Helper function to log errors using a custom logging class
function logError(string $message): void {
    // Implement your own logging functionality here
}

// Example usage:
$data = [
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'password123',
    'confirm_password' => 'password123'
];

$result = registerUser($data);

if ($result === true) {
    echo 'User registered successfully!';
} elseif (is_array($result)) {
    echo 'Validation errors:';
    print_r($result['errors']);
} else {
    echo 'Failed to register user.';
}


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Function to connect to database
function dbConnect() {
    global $host, $dbname, $username, $password;
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        return $conn;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit();
    }
}

// Function to register a user
function registerUser($name, $email, $password) {
    // Database connection
    $db = dbConnect();

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception('Database error');
        }
    } catch (Exception $e) {
        echo 'Error registering user: ' . $e->getMessage();
        return false;
    } finally {
        // Close database connection
        unset($db);
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate input
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (strlen($password) >= 8) {
                if (registerUser($name, $email, $password)) {
                    echo 'User registered successfully!';
                } else {
                    echo 'Registration failed. Please try again.';
                }
            } else {
                echo 'Password must be at least 8 characters long.';
            }
        } else {
            echo 'Invalid email address.';
        }
    } else {
        echo 'Please fill in all fields.';
    }
}

?>


// config.php (store your database credentials here)
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function registerUser($username, $email, $password) {
    // Check if the user already exists
    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($GLOBALS['db'], $query);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        // User already exists
        return array('error' => 'User with this username or email already exists.');
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($GLOBALS['db'], $query);
    mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hashedPassword);
    if (!mysqli_stmt_execute($stmt)) {
        // Error inserting user
        return array('error' => 'Failed to register user.');
    }

    // Return success message
    return array('success' => 'User successfully registered.');
}

// Initialize database connection (in main script)
$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}


registerUser('johnDoe', 'johndoe@example.com', 'password123');


<?php

// Configuration
$db_host = 'your_database_host';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    // Get user input
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        return array('error' => 'Please fill in all fields');
    }

    if ($password !== $confirm_password) {
        return array('error' => 'Passwords do not match');
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query to insert new user into database
    $query = "INSERT INTO users (username, email, password)
              VALUES ('$username', '$email', '$hashed_password')";

    if ($conn->query($query) === TRUE) {
        return array('success' => 'User created successfully');
    } else {
        return array('error' => 'Error creating user: ' . $conn->error);
    }
}

// Check for POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = registerUser();
    echo json_encode($response);
}
?>


// config.php

define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database_name');

$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);


// registration.php

require_once 'config.php';

function validateRegistrationData($data) {
    // Validate input data
    if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
        return array('error' => 'All fields are required');
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    if (strlen($data['password']) < 8) {
        return array('error' => 'Password must be at least 8 characters long');
    }

    return true;
}

function hashPassword($password) {
    // Hash password using bcrypt
    $options = array('cost' => 12);
    return password_hash($password, PASSWORD_BCRYPT, $options);
}

function registerUser($data) {
    global $db;

    // Validate input data
    if (validateRegistrationData($data)) {

        // Insert user into database
        $stmt = $db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', hashPassword($data['password']));
        if ($stmt->execute()) {
            return array('success' => 'User registered successfully');
        } else {
            return array('error' => 'Error registering user');
        }
    }

    // Return validation errors
    return $data;
}

// Example usage:
$data = array(
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'password' => 'mysecretpassword'
);

$result = registerUser($data);
print_r($result);


<?php

function registerUser($username, $email, $password)
{
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address.");
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database
    $dbConnection = mysqli_connect("localhost", "username", "password", "database");

    if (!$dbConnection) {
        throw new Exception("Failed to connect to database: " . mysqli_error());
    }

    // Prepare and execute query
    $sqlQuery = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($dbConnection, $sqlQuery);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Failed to execute query: " . mysqli_error());
    }

    // Get the ID of the newly inserted user
    $userId = mysqli_insert_id($dbConnection);

    // Clean up and return the user's data
    mysqli_close($dbConnection);
    return array(
        'id' => $userId,
        'username' => $username,
        'email' => $email
    );
}

?>


try {
    $userData = registerUser('johnDoe', 'johndoe@example.com', 'password123');
    print_r($userData);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<?php

// Database connection settings
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

try {
    // Establish a database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Registration function
function registerUser($name, $email, $password, $confirm_password) {
    // Validate input data
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        throw new Exception("Please fill in all fields.");
    }

    if ($password !== $confirm_password) {
        throw new Exception("Passwords do not match.");
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO users (name, email, password)
                              VALUES (:name, :email, :password)");
    
    try {
        // Bind input data to prepared statement
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        // Execute SQL query
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        echo "Error registering user: " . $e->getMessage();
        return false;
    }
}

// Example usage:
try {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    registerUser($name, $email, $password, $confirm_password);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>


function register_user($db, $username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields must be filled out');
    }

    // Check for existing users with the same username or email
    $stmt = $db->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
    $stmt->execute(array($username, $email));
    if ($stmt->fetch()) {
        return array('error' => 'Username or email already exists');
    }

    // Hash password using a secure algorithm
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    try {
        $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        $stmt->execute(array($username, $email, $hashed_password));
        return array('success' => 'User registered successfully');
    } catch (PDOException $e) {
        return array('error' => 'Error registering user: ' . $e->getMessage());
    }
}


$db = new PDO('sqlite:users.db');

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$results = register_user($db, $username, $email, $password);

echo json_encode($results);


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


function registerUser($username, $email, $password) {
  // Validate input data
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception('All fields are required.');
  }

  if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
    throw new Exception('Username can only contain letters and numbers.');
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception('Invalid email address.');
  }

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  try {
    // Connect to database
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

    // Prepare query
    $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');

    // Bind parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    // Execute query
    $stmt->execute();

    // Return user ID
    return $db->lastInsertId();
  } catch (PDOException $e) {
    throw new Exception('Database error: ' . $e->getMessage());
  }
}


try {
  $userId = registerUser('johnDoe', 'johndoe@example.com', 'password123');
  echo "User registered successfully! ID: $userId";
} catch (Exception $e) {
  echo "Error registering user: " . $e->getMessage();
}


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

function registerUser($email, $username, $password) {
    // Connect to database
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if username or email already exist in the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->fetch()) {
            return array('status' => false, 'message' => 'Email or Username already taken.');
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into database
        $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        return array('status' => true, 'message' => 'User registered successfully.');
    } catch (PDOException $e) {
        return array('status' => false, 'message' => 'An error occurred while connecting to the database: ' . $e->getMessage());
    }
}

// Example usage:
$email = 'example@example.com';
$username = 'johnDoe';
$password = 'mysecretpassword';

$result = registerUser($email, $username, $password);

print_r($result);
?>


<?php

// Configuration for database connection
$host = 'localhost';
$dbname = 'registration_system';
$username = 'your_username';
$password = 'your_password';

// Establishing the database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

function register_user() {
    global $conn;

    // Get posted values from the form
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Sanitize input data
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
        $confirm_password = filter_var($_POST['confirm_password'], FILTER_SANITIZE_STRING);

        // Check for errors
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            echo "Please fill in all the fields.";
            return;
        }

        // Validate password and confirm password match
        if ($password !== $confirm_password) {
            echo "Passwords do not match";
            return;
        }

        // Check for existing user
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username OR email=:email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Username or Email already exists.";
            return;
        }

        // Create a new user
        try {
            // Hash the password before storing in database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert into users table
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            if ($stmt->execute()) {
                echo "User successfully registered.";
            } else {
                throw new Exception('Failed to register user');
            }
        } catch (Exception $e) {
            echo 'Error registering user: ' . $e->getMessage();
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password"><br><br>

        <input type="submit" value="Register">
    </form>

    <?php register_user(); ?>
</body>
</html>


// users.php (or any other file you prefer)

class User {
    private $db; // database connection

    public function __construct($host, $username, $password, $database) {
        // establish a new MySQLi connection
        $this->db = new mysqli($host, $username, $password, $database);
    }

    /**
     * Register a new user.
     *
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function registerUser($name, $email, $password) {
        // validate input data
        if (!$this->validateInputData($name, $email, $password)) {
            return false; // validation failed
        }

        // hash password (you can use a library like password_hash)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // prepare SQL query to insert new user into the database
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if (!$stmt->execute([$name, $email, $hashedPassword])) {
            return false; // error inserting data into database
        }

        return true; // user registered successfully
    }

    /**
     * Validate input data for a new user.
     *
     * @param string $name
     * @param string $email
     * @param string $password
     */
    private function validateInputData($name, $email, $password) {
        if (empty($name)) return false; // name cannot be empty
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false; // email is not valid
        if (strlen($password) < 8 || !preg_match("/[a-zA-Z]/", $password)) return false; // password must have at least 8 characters and a letter

        return true;
    }
}


// include the users.php file
require_once 'users.php';

// establish a connection to the database (replace with your credentials)
$db = new User('localhost', 'username', 'password', 'database');

// register a new user
$name = 'John Doe';
$email = 'john@example.com';
$password = 'mysecretpassword123';

if ($db->registerUser($name, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Error registering user.";
}


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database_name');

// Connect to the database
function connect_to_database() {
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
        return $conn;
    } catch (PDOException $e) {
        echo "Error connecting to database: " . $e->getMessage();
        exit();
    }
}

// Register a user
function register_user($username, $email, $password) {
    // Validate the input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('Please fill in all fields');
    }

    try {
        // Connect to the database
        $conn = connect_to_database();

        // Check if username or email is already registered
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            throw new Exception('Username or email already registered');
        }

        // Hash the password
        $hashed_password = hash('sha256', $password);

        // Insert user data into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        echo "Error registering user: " . $e->getMessage();
        exit();
    }
}

// Example usage
$username = 'john_doe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

if (register_user($username, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Error registering user";
}
?>


<?php

function registerUser($username, $email, $password) {
    // Input validation
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address.');
    }

    // Password hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Database interaction
    require_once 'database.php'; // include database connection file

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($mysqli->connect_errno) {
        return array('error' => 'Failed to connect to the database.');
    }

    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if (!$stmt->execute()) {
        return array('error' => 'Failed to register user.');
    }

    $id = $mysqli->insert_id;
    $mysqli->close();

    // Return success message with user ID
    return array('success' => 'User registered successfully.', 'userId' => $id);
}

?>


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$response = registerUser($username, $email, $password);

if (isset($response['error'])) {
    echo json_encode(array('error' => $response['error']));
} else {
    echo json_encode($response);
}


function registerUser($username, $email, $password) {
  // Validate input data
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception("Please fill out all fields.");
  }

  // Hash password using PHP's built-in crypt() function
  $hashedPassword = crypt($password, 'salt');

  // Create user object and insert into database
  $user = array(
    'username' => $username,
    'email' => $email,
    'password' => $hashedPassword,
    'created_at' => date('Y-m-d H:i:s')
  );

  // Insert user data into database using PDO (PHP Data Objects)
  try {
    $conn = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
    $stmt = $conn->prepare("INSERT INTO users SET username=:username, email=:email, password=:password");
    $stmt->bindParam(':username', $user['username']);
    $stmt->bindParam(':email', $user['email']);
    $stmt->bindParam(':password', $user['password']);
    $stmt->execute();
  } catch (PDOException $e) {
    throw new Exception("Database error: " . $e->getMessage());
  }

  // Return user ID on success
  return $conn->lastInsertId();
}


$username = 'john_doe';
$email = 'johndoe@example.com';
$password = 'password123';

try {
  $userId = registerUser($username, $email, $password);
  echo "User registered successfully with ID: $userId";
} catch (Exception $e) {
  echo "Error registering user: " . $e->getMessage();
}


function registerUser($username, $email, $password) {
  // Hash the password using a secure algorithm (e.g., bcrypt)
  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

  try {
    // Connect to database (example using PDO)
    $db = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL query
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

    // Bind parameters
    $params = array(
      ':username' => $username,
      ':email' => $email,
      ':password' => $hashedPassword
    );

    // Execute query
    $stmt->execute($params);

    return true;

  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    return false;
  }
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'secret123';

if (registerUser($username, $email, $password)) {
  echo "User registered successfully!";
} else {
  echo "Registration failed.";
}


<?php

function registerUser($username, $email, $password) {
    // Check if the username is empty
    if (empty($username)) {
        return array('error' => 'Username cannot be empty');
    }

    // Check if the email address is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    // Check if the password meets the minimum length requirement (at least 8 characters)
    if (strlen($password) < 8) {
        return array('error' => 'Password must be at least 8 characters long');
    }

    try {
        // Connect to your database (you'll need to replace this with your actual DB connection code)
        $conn = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');

        // Check if the username is already in use
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return array('error' => 'Username is already taken');
        }

        // Hash the password
        $hashedPassword = hash('sha256', $password);

        // Insert the new user into the database
        $query = "INSERT INTO users (username, email, password)
                  VALUES (:username, :email, :password)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        return array('success' => 'User successfully registered');

    } catch (PDOException $e) {
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}

// Example usage:
$username = 'newuser';
$email = 'newuser@example.com';
$password = 'mysecretpassword';

$result = registerUser($username, $email, $password);

if ($result['success']) {
    echo 'User registered successfully!';
} elseif (isset($result['error'])) {
    echo 'Error: ' . $result['error'];
}


<?php

// Configuration variables
$requiredFields = array('username', 'email', 'password');
$minPasswordLength = 8;
$maxUsernameLength = 20;

function registerUser($username, $email, $password) {
    // Validate the input fields
    if (empty($username) || empty($email) || empty($password)) {
        return array('success' => false, 'message' => 'Please fill out all fields.');
    }

    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        return array('success' => false, 'message' => 'Username can only contain letters and numbers.');
    }

    if (strlen($password) < $minPasswordLength || strlen($password) > 128) {
        return array('success' => false, 'message' => 'Password must be between ' . $minPasswordLength . ' and 128 characters long.');
    }

    if (strlen($username) > $maxUsernameLength) {
        return array('success' => false, 'message' => 'Username is too long. It should not exceed ' . $maxUsernameLength . ' characters.');
    }

    // Check for duplicate username
    if (!checkDuplicateUsername($username)) {
        return array('success' => false, 'message' => 'Username already exists.');
    }

    // Hash the password and insert user data into database
    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        createUser($username, $email, $hashedPassword);
        return array('success' => true, 'message' => 'Registration successful!');
    } catch (Exception $e) {
        return array('success' => false, 'message' => 'Failed to register user: ' . $e->getMessage());
    }
}

function checkDuplicateUsername($username) {
    // Assume we have a database connection established
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    return !$result || mysqli_num_rows($result) == 0;
}

function createUser($username, $email, $hashedPassword) {
    // Assume we have a database connection established
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    return mysqli_query($conn, $query);
}

// Example usage:
$username = 'test_user';
$email = 'test@example.com';
$password = 'test_password';

$result = registerUser($username, $email, $password);

if ($result['success']) {
    echo "Registration successful! You can now log in with your credentials.";
} else {
    echo $result['message'];
}
?>


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


function registerUser($username, $email, $password, $confirmPassword) {
    // Check if all fields are filled
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        return array("error" => "Please fill in all fields");
    }

    // Validate username length and characters
    if (strlen($username) < 3 || strlen($username) > 50) {
        return array("error" => "Username must be between 3 and 50 characters long");
    }
    if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
        return array("error" => "Username can only contain letters and numbers");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array("error" => "Invalid email address");
    }

    // Validate password length and characters
    if (strlen($password) < 8 || strlen($password) > 128) {
        return array("error" => "Password must be between 8 and 128 characters long");
    }
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        return array("error" => "Password must contain at least one lowercase letter, one uppercase letter, one digit and one special character");
    }

    // Validate confirmation password
    if ($confirmPassword !== $password) {
        return array("error" => "Passwords do not match");
    }

    // Hash the password for storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database (example using PDO)
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        return array("success" => "User registered successfully");
    } catch (PDOException $e) {
        return array("error" => "Database error: " . $e->getMessage());
    }
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'P@ssw0rd';
$confirmPassword = 'P@ssw0rd';

$result = registerUser($username, $email, $password, $confirmPassword);
if (isset($result['error'])) {
    echo 'Error: ' . $result['error'];
} else {
    echo 'Success: ' . $result['success'];
}


<?php

function registerUser($firstName, $lastName, $email, $password, $confirmPassword) {
    // Input validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
        throw new Exception('All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    if ($password !== $confirmPassword) {
        throw new Exception('Passwords do not match');
    }

    // Password hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database (example using PDO)
    $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');

    $stmt = $db->prepare('INSERT INTO users (first_name, last_name, email, password) VALUES (:firstName, :lastName, :email, :hashedPassword)');
    $stmt->execute(array(
        ':firstName' => $firstName,
        ':lastName' => $lastName,
        ':email' => $email,
        ':hashedPassword' => $hashedPassword
    ));

    // Close database connection
    $db = null;

    return true;
}

// Example usage:
try {
    registerUser('John', 'Doe', 'johndoe@example.com', 'password123', 'password123');
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}


// Configuration variables
define('DB_HOST', 'localhost');
define('DB_NAME', 'database_name');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');

// Connect to the database
function connectToDatabase() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Validate and store user data in the database
function registerUser($data) {
    // Extract input data from the array
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];

    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields');
    }

    // Check for existing usernames
    $conn = connectToDatabase();
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        return array('error' => 'Username already exists');
    }
    $conn->close();

    // Hash password
    $passwordHashed = hash('sha256', $password);

    // Store user data in the database
    $conn = connectToDatabase();
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$passwordHashed')";
    if ($conn->query($query) === TRUE) {
        return array('success' => 'User registered successfully');
    } else {
        return array('error' => 'Error registering user');
    }
    $conn->close();
}

// Example usage
$data = array(
    'username' => 'newuser',
    'email' => 'newuser@example.com',
    'password' => 'newpassword'
);
$result = registerUser($data);

if (isset($result['error'])) {
    echo $result['error'];
} elseif (isset($result['success'])) {
    echo $result['success'];
}


function register_user($db, $username, $email, $password) {
  // Hash password using SHA-256
  $hashed_password = hash('sha256', $password);

  // Prepare INSERT statement
  $query = "INSERT INTO users (username, email, password)
            VALUES (?, ?, ?)";

  try {
    // Execute query with prepared statement
    $stmt = $db->prepare($query);
    $stmt->execute([$username, $email, $hashed_password]);

    // Return last inserted ID if successful
    return $db->lastInsertId();
  } catch (PDOException $e) {
    // Handle errors and rethrow exception
    echo "Error registering user: " . $e->getMessage() . "
";
    throw new Exception($e);
  }
}


$db = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if (register_user($db, $username, $email, $password)) {
  echo "User registered successfully!";
} else {
  echo "Error registering user.";
}


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
function dbConnect() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// User registration function
function registerUser($email, $password, $username) {
    // Connect to database
    $conn = dbConnect();
    
    // Hash password (e.g., using SHA-256)
    $hashedPassword = hash('sha256', $password);
    
    // Prepare SQL query
    $query = "INSERT INTO users (email, password, username) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    // Bind parameters
    $stmt->bind_param("sss", $email, $hashedPassword, $username);
    
    // Execute query
    if ($stmt->execute()) {
        echo "User registered successfully!";
        return true;
    } else {
        echo "Error registering user: " . $conn->error;
        return false;
    }
}

// Handle form submission (e.g., via a HTML form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username'];
    
    if (!empty($email) && !empty($password) && !empty($username)) {
        // Check for existing user (e.g., by email)
        $conn = dbConnect();
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "User already exists with this email address.";
        } else {
            // Register user
            $registered = registerUser($email, $password, $username);
            if (!$registered) {
                // Error registering user
            }
        }
    } else {
        echo "Please fill in all fields.";
    }
}
?>


// Function to register a new user
function registerUser($username, $email, $password) {
    // Connect to the database
    require_once 'db_config.php';
    $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);

    // Check if email already exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $count = $stmt->rowCount();

    if ($count > 0) {
        return "Email address already exists.";
    } else {
        // If the user has been verified, insert new user into the database
        $query = "INSERT INTO users (username, email, password)
                  VALUES (:username, :email, :password)";
        
        try {
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', hash('sha256', $password));
            $stmt->execute();
            
            return "Registration successful.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Close the database connection
    $conn = null;
}

// Example usage:
$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

echo registerUser($username, $email, $password);


<?php

// Configuration for the database connection
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
function connectToDatabase() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Register a new user
function registerUser($username, $email, $password) {
    // Connect to the database
    $db = connectToDatabase();

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    $result = $stmt->execute();

    // Check if the user was successfully inserted
    if ($result === true) {
        echo "User registered successfully!";
    } else {
        echo "Failed to register user.";
    }

    // Close the database connection
    $db->close();
}

// Example usage
registerUser("johnDoe", "johndoe@example.com", "password123");

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

function registerUser($username, $email, $password) {
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields');
    }

    // Check username and email uniqueness
    $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return array('error' => 'Username or email already exists');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if ($conn->query($sql)) {
        return array('success' => 'User registered successfully');
    } else {
        return array('error' => 'Failed to register user');
    }
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if ($result['success']) {
    echo "User registered successfully";
} elseif ($result['error']) {
    echo $result['error'];
}

// Close connection
$conn->close();

?>


function register_user($username, $email, $password) {
  // Database connection settings
  $db_host = 'localhost';
  $db_username = 'your_db_username';
  $db_password = 'your_db_password';
  $db_name = 'your_db_name';

  // Create a new database connection
  try {
    $conn = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name, $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the INSERT query
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    return false;
  }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get form data
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Validate user input
  if (empty($username) || empty($email) || empty($password)) {
    echo 'Error: All fields are required.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Error: Invalid email address.';
  } else {
    // Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Call the register_user function
    if (register_user($username, $email, $hashed_password)) {
      echo 'User registered successfully!';
    } else {
      echo 'Error: Unable to register user.';
    }
  }
}


<?php

// Config file to store database credentials and other settings
require_once 'config.php';

function registerUser($username, $email, $password) {
  // Check if username already exists in database
  $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
  $stmt->execute([':username' => $username]);
  if ($stmt->fetch()) {
    return 'Username already taken';
  }

  // Validate email address
  $emailValidated = validateEmail($email);
  if (!$emailValidated) {
    return 'Invalid email address';
  }

  // Hash password using bcrypt
  $hashedPassword = bcryptHash($password);

  // Insert new user into database
  try {
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $stmt->execute([':username' => $username, ':email' => $email, ':password' => $hashedPassword]);
    return 'Registration successful!';
  } catch (PDOException $e) {
    return 'Error registering user';
  }
}

// Helper function to validate email address
function validateEmail($email) {
  if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
    return false;
  }
  return true;
}

// Helper function to hash password using bcrypt
function bcryptHash($password) {
  return password_hash($password, PASSWORD_BCRYPT);
}


$pdo = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);
echo $result; // Registration successful! or error message


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


<?php

// Configuration settings
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields.');
    }

    // Check for duplicate usernames and passwords
    $query = "SELECT * FROM users WHERE username = '$username'";
    if ($result = mysqli_query($GLOBALS['db'], $query)) {
        if (mysqli_num_rows($result) > 0) {
            return array('error' => 'Username already exists.');
        }
    }

    // Check for duplicate emails
    $query = "SELECT * FROM users WHERE email = '$email'";
    if ($result = mysqli_query($GLOBALS['db'], $query)) {
        if (mysqli_num_rows($result) > 0) {
            return array('error' => 'Email already exists.');
        }
    }

    // Hash password
    $passwordHash = hash('sha256', $password);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$passwordHash')";
    if ($result = mysqli_query($GLOBALS['db'], $query)) {
        return array('success' => 'User registered successfully.');
    } else {
        return array('error' => 'Failed to register user: ' . mysqli_error($GLOBALS['db']));
    }
}

// Connect to database
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($db->connect_errno) {
    echo "Failed to connect to database.";
    exit();
}
?>


$username = 'newuser';
$email = 'newuser@example.com';
$password = 'password123';

$result = registerUser($username, $email, $password);
if (isset($result['success'])) {
    echo $result['success'];
} elseif (isset($result['error'])) {
    echo $result['error'];
}


<?php
/**
 * User Registration Function
 *
 * @param string $username Username for the new account
 * @param string $email Email address for the new account
 * @param string $password Password for the new account
 * @return bool|object True if registration is successful, false otherwise with an error message
 */
function registerUser($username, $email, $password) {
    // Database connection settings
    $dbHost = 'localhost';
    $dbUsername = 'your_database_username';
    $dbPassword = 'your_database_password';
    $dbName = 'your_database_name';

    try {
        // Establish database connection
        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if username or email already exists in the database
        $stmt = $conn->prepare('SELECT * FROM users WHERE username=:username OR email=:email');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            // Return error message if user already exists
            return array('error' => 'Username or email is already taken');
        }

        // Hash password for secure storage
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into database
        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Return true on successful registration
        return array('success' => 'User registered successfully');
    } catch (PDOException $e) {
        // Handle database connection errors
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}
?>


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

$result = registerUser($username, $email, $password);

if ($result['success']) {
    echo 'User registered successfully!';
} elseif ($result['error']) {
    echo 'Error: ' . $result['error'];
}


class User {
    private $users = [];

    public function register($username, $email, $password) {
        // Validate input
        if (empty($username) || empty($email) || empty($password)) {
            throw new Exception('All fields are required');
        }

        // Check for existing user
        foreach ($this->users as $user) {
            if ($user['email'] === $email) {
                throw new Exception('Email already exists');
            }
        }

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Create new user
        $newUser = [
            'username' => $username,
            'email' => $email,
            'password' => $passwordHash
        ];

        // Store in array
        $this->users[] = $newUser;

        return true;
    }

    public function authenticate($username, $password) {
        foreach ($this->users as $user) {
            if ($user['username'] === $username && password_verify($password, $user['password'])) {
                return true;
            }
        }
        return false;
    }
}


$user = new User();

try {
    // Register a new user
    $result = $user->register('johnDoe', 'johndoe@example.com', 'password123');
    echo "User created successfully" . PHP_EOL;

    // Authenticate the user
    if ($user->authenticate('johnDoe', 'password123')) {
        echo "Authentication successful" . PHP_EOL;
    } else {
        echo "Authentication failed" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}


<?php
// Define the database connection settings
$host = 'localhost';
$dbname = 'users_database';
$username = 'root'; // Replace with your username
$password = ''; // Replace with your password

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
    exit;
}

// Function to register a user
function register_user($name, $email, $password) {
    // Validate the input data
    if (!validate_input($name, $email, $password)) {
        return false; // Invalid input
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    if ($stmt->execute()) {
        return true; // User registered successfully
    } else {
        echo "Error registering user: " . $conn->errorInfo()[2];
        return false;
    }
}

// Function to validate input data
function validate_input($name, $email, $password) {
    if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        return false; // Invalid name
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false; // Invalid email address
    }
    if (strlen($password) < 8 || !preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/", $password)) {
        return false; // Password too short or not complex enough
    }
    return true;
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (register_user($name, $email, $password)) {
        echo "User registered successfully!";
    } else {
        echo "Error registering user. Please try again.";
    }
}

?>


function register_user($username, $email, $password) {
    // Check if the user already exists
    if (getUser($username)) {
        return array("error" => "Username already taken");
    }

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array("error" => "Invalid email address");
    }

    // Hash password
    $hashed_password = hash('sha256', $password);

    // Create new user
    $new_user = array(
        'username' => $username,
        'email' => $email,
        'password' => $hashed_password
    );

    // Store user in database (using a fictional `storeUser` function)
    storeUser($new_user);

    return array("success" => true);
}

// Helper function to check if user already exists
function getUser($username) {
    // Query the database for existing users
    $users = queryDatabase("SELECT * FROM users WHERE username = ?", array($username));
    if (count($users) > 0) {
        return true; // User already exists
    } else {
        return false;
    }
}

// Helper function to store user in database
function storeUser($new_user) {
    // Use a prepared statement to insert new user into the database
    queryDatabase("INSERT INTO users (username, email, password) VALUES (?, ?, ?)", array(
        $new_user['username'],
        $new_user['email'],
        $new_user['password']
    ));
}

// Helper function to execute a SQL query against the database
function queryDatabase($query, $params = null) {
    // Connect to database (not shown here)
    // Execute query and return result
}


$username = 'john_doe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

$result = register_user($username, $email, $password);
if ($result['success']) {
    echo "User registered successfully!";
} else {
    echo "Error: " . $result['error'];
}


class User {
    public $id;
    public $username;
    public $email;
    public $password;

    public function __construct($data) {
        $this->id = null;
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->password = password_hash($data['password'], PASSWORD_DEFAULT);
    }
}


function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('All fields are required.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address.');
    }

    if (strlen($password) < 8) {
        throw new Exception('Password must be at least 8 characters long.');
    }

    // Check for existing user
    // NOTE: This is a simple example and does not include database checks.
    // In a real-world application, you would query your database to check if the username or email already exists.

    try {
        $user = new User([
            'username' => $username,
            'email' => $email,
            'password' => $password
        ]);

        // If everything is valid, create a new user and return it
        return $user;

    } catch (Exception $e) {
        throw new Exception('Registration failed: ' . $e->getMessage());
    }
}


try {
    $user = registerUser('john_doe', 'johndoe@example.com', 'password123');

    echo "User registered successfully!";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<?php

// Configuration constants
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to register a new user
function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("All fields are required.");
    }

    // Check for username and email uniqueness
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, $query);

    if (mysqli_num_rows($result) > 0) {
        throw new Exception("Username or email already taken.");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    mysqli_query(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, $query);

    return true;
}

// Example usage:
try {
    registerUser('johnDoe', 'johndoe@example.com', 'password123');
    echo "Registration successful!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<?php

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data from the POST request
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Trim and validate input fields
    $username = trim($username);
    $email = trim($email);

    if (empty($username) || empty($email)) {
        echo 'Please fill in both username and email';
        exit;
    }

    // Check if email is valid using filter_var()
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email address';
        exit;
    }

    // Hash password
    $password = md5($password);

    // Connect to database (replace with your own db credentials)
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "users";

    try {
        // Create a connection to the database
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username_db, $password_db);
        // Check if email is already in use
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->fetch()) {
            echo 'Email address already exists';
            exit;
        }

        // Insert new user into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Close the database connection
        $conn = null;

        echo 'You have been registered!';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

?>


// db connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

function registerUser($username, $email, $password) {
  // Check if input is valid
  if (empty($username) || empty($email) || empty($password)) {
    return array('error' => 'Please fill in all fields');
  }

  // Validate email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return array('error' => 'Invalid email address');
  }

  // Check if username already exists
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    return array('error' => 'Username already exists');
  }

  // Hash the password
  $password_hash = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  $query = "INSERT INTO users (username, email, password_hash) VALUES ('$username', '$email', '$password_hash')";
  if ($conn->query($query)) {
    return array('success' => 'User registered successfully');
  } else {
    return array('error' => 'Failed to register user');
  }

  $conn->close();
}

// Usage example:
$username = "testuser";
$email = "test@example.com";
$password = "password123";

$response = registerUser($username, $email, $password);

if ($response['success']) {
  echo "User registered successfully!";
} elseif ($response['error']) {
  echo "Error: " . $response['error'];
}


<?php

// Configuration array
$config = [
    'username_min_length' => 3,
    'username_max_length' => 20,
    'password_min_length' => 8,
];

// Function to register a new user
function register_user($data) {
    // Validate input data
    if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
        return ['error' => 'All fields are required'];
    }

    $username = trim($data['username']);
    if (strlen($username) < $config['username_min_length'] || strlen($username) > $config['username_max_length']) {
        return ['error' => 'Username must be between ' . $config['username_min_length'] . ' and ' . $config['username_max_length'] . ' characters'];
    }

    $email = trim($data['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['error' => 'Invalid email address'];
    }

    $password = trim($data['password']);
    if (strlen($password) < $config['password_min_length']) {
        return ['error' => 'Password must be at least ' . $config['password_min_length'] . ' characters'];
    }

    // Insert user into database
    try {
        // Connect to database
        $db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

        // Prepare and execute query
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        // Hash the password using a library like bcrypt
        // For simplicity, we'll just use MD5 here (not recommended in production!)
        $stmt->bindParam(':password', md5($password));
        $stmt->execute();

        return ['success' => 'User registered successfully'];
    } catch (PDOException $e) {
        return ['error' => 'Database error: ' . $e->getMessage()];
    }
}

// Example usage:
$data = [
    'username' => 'john_doe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword',
];
$result = register_user($data);
print_r($result);

?>


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

// Define the database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Create a function to register a new user
function register_user($username, $email, $password) {
  // Connect to the database
  try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the user already exists
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user_count = $stmt->rowCount();

    if ($user_count > 0) {
      return array('error' => 'User already exists.');
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $insert_stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $insert_stmt->bindParam(':username', $username);
    $insert_stmt->bindParam(':email', $email);
    $insert_stmt->bindParam(':password', $hashed_password);
    $insert_stmt->execute();

    // Return a success message
    return array('success' => 'User registered successfully.');

  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }
}

// Example usage:
$username = 'john_doe';
$email = 'johndoe@example.com';
$password = 'password123';

$result = register_user($username, $email, $password);

if ($result['error']) {
  echo '<p>' . $result['error'] . '</p>';
} elseif ($result['success']) {
  echo '<p>' . $result['success'] . '</p>';
}

?>


// database configuration
$host = 'localhost';
$db_name = 'registration_system';
$username = 'root';
$password = '';

// create a new mysqli object
$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function register_user($username, $email, $password) {
    // sanitize input
    $username = mysqli_real_escape_string($GLOBALS['conn'], $username);
    $email = mysqli_real_escape_string($GLOBALS['conn'], $email);

    // check if username or email is already taken
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return false;
    }

    // hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // insert new user into database
    $query = "INSERT INTO users (username, email, password_hash) VALUES ('$username', '$email', '$password_hash')";
    if ($conn->query($query)) {
        return true;
    } else {
        return false;
    }
}


// registration form data
$username = 'john_doe';
$email = 'johndoe@example.com';
$password = 'password123';

// register user
if (register_user($username, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Registration failed. Please try again.";
}


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

// Function to register a user
function registerUser($username, $email, $password) {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);

    // Hash password (basic example: not recommended for production use)
    $hashedPassword = md5($password);

    // Insert user into database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if ($conn->query($sql)) {
        return true; // User registered successfully
    } else {
        echo "Error: " . $conn->error;
        return false; // Error registering user
    }
}

// Form data handling (example)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        // Register user
        $result = registerUser($username, $email, $password);

        if ($result) {
            echo "User registered successfully!";
        } else {
            echo "Error registering user.";
        }
    } else {
        echo "Please fill in all fields.";
    }
}

?>


// database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// establish a new connection
$conn = new mysqli($db_host, $db_password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// select the database
$conn->select_db($db_name);


function registerUser() {
    // get form data from $_POST (assuming this is where your user inputs are coming from)
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // check if the passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match";
        return false;
    }

    // prepare SQL statement for inserting new user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password)
                            VALUES (?, ?, ?)");
    
    // bind parameters to prevent SQL injection
    $stmt->bind_param("sss", $username, $email, password_hash($password, PASSWORD_DEFAULT));

    try {
        // execute the prepared statement
        if ($stmt->execute()) {
            return true; // user successfully registered
        } else {
            echo "Failed to register user";
            return false;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }

    // close the prepared statement and connection
    $stmt->close();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    registerUser(); // Call the function whenever form is submitted
}

// display a simple registration form for demonstration purposes only.
?>
<form method="post">
  <label>Username:</label>
  <input type="text" name="username"><br><br>
  <label>Email:</label>
  <input type="email" name="email"><br><br>
  <label>Password:</label>
  <input type="password" name="password"><br><br>
  <label>Confirm Password:</label>
  <input type="password" name="confirm_password"><br><br>
  <input type="submit" value="Register">
</form>


function register_user($username, $email, $password) {
  // Database connection (assuming you have a database setup)
  require_once 'db_config.php';
  $conn = new mysqli($host, $user, $pass, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Check for existing user
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    return array('error' => 'Username already taken');
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return array('error' => 'Invalid email address');
  }

  // Hash password
  $password = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
  $conn->query($query);

  // Close database connection
  $conn->close();

  return array('success' => 'Registration successful');
}


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = register_user($username, $email, $password);

if ($result['error']) {
  echo json_encode(array('message' => $result['error']));
} else {
  echo json_encode(array('message' => 'User registered successfully'));
}


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Establish database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if the user has submitted the registration form
if (isset($_POST['submit'])) {
  // Validate user input
  $errors = array();
  if (!$_POST['username']) {
    $errors[] = 'Username is required';
  }
  if (!$_POST['email']) {
    $errors[] = 'Email is required';
  }
  if (!$_POST['password'] || !$_POST['confirm_password']) {
    $errors[] = 'Password is required';
  } elseif ($_POST['password'] != $_POST['confirm_password']) {
    $errors[] = 'Passwords do not match';
  }

  // Check for duplicate usernames
  $query = "SELECT * FROM users WHERE username = '".$_POST['username']."'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
    $errors[] = 'Username already exists';
  }

  // If there are no errors, proceed with registration
  if (!count($errors)) {
    // Hash the password using bcrypt
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('".$_POST['username']."', '".$_POST['email']."', '".$password_hash."')";
    mysqli_query($conn, $query);
    echo 'Registration successful!';
  } else {
    echo '<ul>';
    foreach ($errors as $error) {
      echo '<li>'.$error.'</li>';
    }
    echo '</ul>';
  }
}

?>

<!-- Registration form -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label>Username:</label>
  <input type="text" name="username"><br><br>
  <label>Email:</label>
  <input type="email" name="email"><br><br>
  <label>Password:</label>
  <input type="password" name="password"><br><br>
  <label>Confirm Password:</label>
  <input type="password" name="confirm_password"><br><br>
  <button type="submit" name="submit">Register</button>
</form>

<?php
// Close database connection
mysqli_close($conn);
?>


function registerUser($username, $email, $password) {
    // Check if username or email already exists in the database
    if (checkExistingUsername($username)) {
        echo "Username already exists. Please choose a different username.";
        return false;
    }

    if (checkExistingEmail($email)) {
        echo "Email address already exists. Please use a different email address.";
        return false;
    }

    // Hash the password
    $hashedPassword = hash('sha256', $password);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if (!mysqli_query($connection, $query)) {
        echo "Error registering user: " . mysqli_error($connection);
        return false;
    }

    // Return true to indicate successful registration
    return true;
}

function checkExistingUsername($username) {
    // Check if username already exists in database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) {
        return true; // Username already exists
    }
    return false;
}

function checkExistingEmail($email) {
    // Check if email address already exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) {
        return true; // Email address already exists
    }
    return false;
}


// Register a new user with username "john", email "john@example.com", and password "password123"
if (registerUser("john", "john@example.com", "password123")) {
    echo "User successfully registered!";
} else {
    echo "Error registering user.";
}


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

function registerUser() {
    global $conn;

    // Get user input from form
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate user input
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo "Please fill in all fields.";
        return false;
    }

    // Check if email already exists in database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Email address is already taken.";
        return false;
    }

    // Hash password using PHP's built-in hash function
    $hashedPassword = hash('sha256', $password);

    // Insert user into database
    $sql = "INSERT INTO users (firstName, lastName, email, password) VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return false;
    }
}

// Call the registerUser function on form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        registerUser();
    }
}

?>


<?php

// Database Connection (replace with your own connection)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    global $conn;

    if (isset($_POST['register'])) {

        // Form validation
        if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"])) {
            echo 'Please fill all fields.';
            return;
        }

        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = md5($_POST["password"]); // Use a secure password hashing function like bcrypt or argon2

        // SQL query to register user
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $name, $email, $password);
            $result = $stmt->execute();

            if ($result === TRUE) {
                echo 'You have been registered successfully!';
            } else {
                echo 'Error: ' . $conn->error;
            }
        } else {
            echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        }

        // Close the connection
        $stmt->close();
    }
}

// Call the function
registerUser();

?>


<?php

// Configuration
$dbHost = 'localhost';
$dbName = 'mydatabase';
$dbUsername = 'myusername';
$dbPassword = 'mypassword';

function registerUser($data) {
    // Validate user data
    $errors = validateUserData($data);
    if (!empty($errors)) {
        return array('success' => false, 'message' => 'Invalid input', 'errors' => $errors);
    }

    // Connect to database
    try {
        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Hash password
        $passwordHash = hash('sha256', $data['password']);

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->execute();

        // Return success message
        return array('success' => true, 'message' => 'User registered successfully');
    } catch (PDOException $e) {
        // Return error message
        return array('success' => false, 'message' => 'Error registering user: ' . $e->getMessage());
    }
}

function validateUserData($data) {
    // Check for empty fields
    $errors = array();
    if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
        $errors[] = 'All fields are required';
    }

    // Check for valid email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address';
    }

    // Check for password length
    if (strlen($data['password']) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    }

    return $errors;
}

?>


$data = array(
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
);

$result = registerUser($data);
print_r($result);


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


<?php
// Configuration settings for the database connection and password hashing
require_once 'config.php';

function registerUser($username, $email, $password) {
    // Check if username already exists
    $query = "SELECT * FROM users WHERE username = :username";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result) {
            throw new Exception('Username already exists');
        }
    } catch (PDOException $e) {
        echo 'An error occurred while checking username: ' . $e->getMessage();
        return false;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    try {
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo 'An error occurred while registering user: ' . $e->getMessage();
        return false;
    }
}

// Example usage:
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($username && $email && $password) {
        if (registerUser($username, $email, $password)) {
            echo 'Registration successful!';
        } else {
            echo 'Registration failed.';
        }
    } else {
        echo 'Please fill in all fields.';
    }
}
?>


// config.php

$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}


function register_user($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("All fields are required.");
    }

    // Check for valid email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address.");
    }

    // Hash password using SHA-256
    $hashed_password = hash('sha256', $password);

    // Create a new user in the database
    try {
        // Connect to the database (replace with your own connection method)
        $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Error creating user: " . $e->getMessage());
    }

    // Return a success message
    return "User registered successfully.";
}


try {
    $username = "john_doe";
    $email = "john.doe@example.com";
    $password = "mysecretpassword";

    $result = register_user($username, $email, $password);
    echo $result;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


<?php

// Configuration variables
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

// Connect to database
$dsn = "mysql:host=$host;dbname=$dbname";
$conn = new PDO($dsn, $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function registerUser($email, $password) {
  // Validate user input
  if (empty($email) || empty($password)) {
    return array('error' => 'Email and password are required');
  }

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  try {
    // Insert new user into database
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    return array('success' => 'User registered successfully');
  } catch(PDOException $e) {
    return array('error' => 'Error registering user: ' . $e->getMessage());
  }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $result = registerUser($email, $password);

  if ($result['success']) {
    echo json_encode($result);
  } else {
    http_response_code(400);
    echo json_encode($result);
  }
}

?>


<?php
// Database connection settings
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

try {
    // Establish a database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Function to register a user
function registerUser($data) {
    // Validate input data
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address");
    }
    if (strlen($data['password']) < 8) {
        throw new Exception("Password must be at least 8 characters long");
    }

    try {
        // Insert user data into database
        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        echo "Error registering user: " . $e->getMessage();
        return false;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validate and process registration data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($username) || empty($email) || empty($password)) {
            throw new Exception("All fields are required");
        }

        $data = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        if (registerUser($data)) {
            echo "User registered successfully!";
        } else {
            echo "Error registering user.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// HTML form for registration
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Email:</label>
    <input type="email" name="email"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <button type="submit">Register</button>
</form>


function register_user($username, $email, $password) {
  // Validate input data
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception("All fields are required");
  }

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Connect to database
  try {
    $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      return true;
    } else {
      throw new Exception("Failed to register user");
    }
  } catch (PDOException $e) {
    // Handle database error
    echo "Error: " . $e->getMessage();
    return false;
  }

  // Close the connection
  $db = null;

  return false; // Should not reach here, but return false in case of unexpected errors
}


try {
  register_user('john_doe', 'johndoe@example.com', 'password123');
  echo "User registered successfully!";
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}


function registerUser($username, $email, $password)
{
    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('Please fill in all fields');
    }

    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Create query to insert user into database
    $query = "
        INSERT INTO users (username, email, password)
        VALUES (?, ?, ?)
    ";

    // Prepare and execute query
    $stmt = mysqli_prepare($GLOBALS['db'], $query);
    mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hashedPassword);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Error registering user');
    }

    // Get ID of newly inserted user
    $userId = mysqli_insert_id($GLOBALS['db']);

    return array(
        'id' => $userId,
        'username' => $username,
        'email' => $email,
    );
}


// Connect to database
$pdo = new PDO('mysql:host=localhost;dbname=yourdatabase', 'youruser', 'yourpassword');

// Register user
try {
    $registeredUser = registerUser('johnDoe', 'johndoe@example.com', 'password123');
    print_r($registeredUser);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}


<?php
// Database array to store users
$users = [];

function registerUser($username, $email, $password) {
    // Validate input data
    if (!validateUsername($username)) {
        return 'Invalid username';
    }
    if (!validateEmail($email)) {
        return 'Invalid email address';
    }
    if (strlen($password) < 8) {
        return 'Password must be at least 8 characters long';
    }

    // Hash password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Add new user to database array
    $newUser = [
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword,
    ];

    $users[] = $newUser;

    return 'User registered successfully!';
}

function validateUsername($username) {
    // Check if username contains only alphanumeric characters and underscores
    return preg_match('/^[a-zA-Z0-9_]+$/', $username);
}

function validateEmail($email) {
    // Check if email address is valid using a simple regex pattern
    return preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
}


// Register new user
$username = 'newuser';
$email = 'example@example.com';
$password = 'mysecretpassword';

$result = registerUser($username, $email, $password);

if ($result === 'User registered successfully!') {
    echo "Registration successful!
";
} else {
    echo "Error: " . $result . "
";
}


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

function registerUser($firstname, $lastname, $email, $username, $password) {
    // Connect to the database
    try {
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if user already exists
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $count = $stmt->rowCount();

        if ($count > 0) {
            echo "Username already exists.";
            return false;
        }

        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $query = "INSERT INTO users (firstname, lastname, email, username, password)
                  VALUES (:firstname, :lastname, :email, :username, :password)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password_hash);
        $stmt->execute();

        // Close connection
        $conn = null;

        echo "Registration successful.";
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

?>


<?php

// Call the registerUser function with user input
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];

if (isset($_POST['submit'])) {
    $success = registerUser($firstname, $lastname, $email, $username, $password);
}

?>


<form action="" method="post">
  <input type="text" name="firstname" placeholder="First Name"><br>
  <input type="text" name="lastname" placeholder="Last Name"><br>
  <input type="email" name="email" placeholder="Email Address"><br>
  <input type="text" name="username" placeholder="Username"><br>
  <input type="password" name="password" placeholder="Password"><br>
  <button type="submit" name="submit">Register</button>
</form>


// Function to hash passwords using bcrypt
function hashPassword($password) {
  return password_hash($password, PASSWORD_DEFAULT);
}

// User registration function
function registerUser($username, $email, $password) {
  // Validate user input
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception('All fields are required');
  }

  // Check for duplicate usernames and emails
  $conn = mysqli_connect('localhost', 'your_username', 'your_password', 'your_database');
  $query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
    throw new Exception('Username or email already exists');
  }

  // Hash password
  $hashedPassword = hashPassword($password);

  // Insert user into database
  $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
  mysqli_query($conn, $query);
  mysqli_close($conn);

  return true;
}

// Example usage:
try {
  registerUser('johnDoe', 'johndoe@example.com', 'mysecretpassword');
  echo "User registered successfully!";
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}


function register_user($username, $email, $password, $confirm_password) {
  // Validate input data
  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    throw new Exception("All fields are required.");
  }

  if ($password !== $confirm_password) {
    throw new Exception("Passwords do not match.");
  }

  try {
    // Connect to database
    $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if username already exists
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    if ($stmt->fetch()) {
      throw new Exception("Username already taken.");
    }

    // Hash password before storing in database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->execute([':username' => $username, ':email' => $email, ':password' => $hashed_password]);

    // Return a success message and the newly created user's ID
    return ["message" => "User created successfully.", "userId" => $db->lastInsertId()];
  } catch (PDOException $e) {
    // Handle any database errors
    throw new Exception("Failed to create user: " . $e->getMessage());
  }
}


try {
  $userData = register_user("newuser", "newuser@example.com", "password123", "password123");
  print_r($userData);
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}


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

function registerUser($username, $email, $password) {
    // Input Validation
    if (empty($username) || empty($email) || empty($password)) {
        return array("error" => "All fields are required.");
    }

    // Check for valid email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array("error" => "Invalid email address.");
    }

    // Password validation (min 8 characters)
    if (strlen($password) < 8) {
        return array("error" => "Password must be at least 8 characters long.");
    }

    // Password hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database (assuming you're using MySQLi)
    require_once 'config.php'; // Include your database configuration file

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create SQL query to insert user data
    $sql = "INSERT INTO users (username, email, password)
            VALUES (?, ?, ?)";

    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return array("error" => "Database error: " . $conn->error);
    }
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    $result = $stmt->execute();
    if (!$result) {
        return array("error" => "Database error: " . $conn->error);
    }

    // Close the database connection
    $conn->close();

    // Return success message with user data
    return array(
        "success" => true,
        "message" => "User registered successfully.",
        "data" => array("username" => $username, "email" => $email)
    );
}

?>


<?php

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if ($result['success']) {
    echo json_encode($result);
} else {
    echo json_encode(array("error" => "Registration failed."));
}

?>


<?php

// Database configuration settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Establish a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    global $conn;

    // Get input values from form submission (for example, using $_POST)
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email and password
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        return;
    }

    if (strlen($password) < 8 || !preg_match("#[0-9]#", $password) || !preg_match("#[A-Z]#", $password)) {
        echo "Password must be at least 8 characters long and contain both uppercase and lowercase letters, as well as a number.";
        return;
    }

    // Create hashed password using PHP's built-in hash library (use SHA-256 for example)
    $hashed_password = hash('sha256', $password);

    // Insert user into database
    $sql = "INSERT INTO users (email, password) VALUES ('$email', '$hashed_password')";
    if ($conn->query($sql) === TRUE) {
        echo "User created successfully.";
    } else {
        echo "Error creating user: " . $conn->error;
    }

    // Close database connection
    $conn->close();
}

// Call registerUser function when the form is submitted
if (isset($_POST['submit'])) {
    registerUser();
}
?>


<?php

// Configuration
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Function to register a new user
function registerUser($username, $email, $password) {
  // Connect to the database
  $conn = mysqli_connect($db_host, $db_user, $db_password);
  if (!$conn) {
    die('Error connecting to database: ' . mysqli_error($conn));
  }

  // Create a new user account
  $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '".password_hash($password, PASSWORD_DEFAULT)."')";
  if (!mysqli_query($conn, $query)) {
    die('Error creating user account: ' . mysqli_error($conn));
  }

  // Close the database connection
  mysqli_close($conn);

  return true;
}

// Example usage:
$username = 'john_doe';
$email = 'johndoe@example.com';
$password = 'password123';

if (registerUser($username, $email, $password)) {
  echo "User account created successfully!";
} else {
  echo "Error creating user account.";
}

?>


<?php

function registerUser($username, $email, $password) {
    // Check if username is valid (e.g., not empty, only letters and numbers)
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        return array('error' => 'Invalid username');
    }

    // Check if email is valid (e.g., matches email pattern)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    // Hash the password
    $hashedPassword = hash('sha256', $password);

    // Connect to database (for example using PDO)
    $pdo = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');

    try {
        // Create a prepared statement to insert user data into table
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the prepared statement
        if ($stmt->execute()) {
            return array('success' => 'User registered successfully');
        } else {
            return array('error' => 'Error registering user: ' . $pdo->errorCode());
        }
    } catch (PDOException $e) {
        return array('error' => 'Error connecting to database: ' . $e->getMessage());
    }

    // Close the PDO connection
    unset($pdo);
}

// Example usage:
$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

$result = registerUser($username, $email, $password);

if ($result['success']) {
    print("User registered successfully");
} elseif ($result['error']) {
    print("Error registering user: " . $result['error']);
}


<?php

// Define the database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Function to register a new user
function registerUser($username, $email, $password) {
  // Create a connection to the database
  $conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Hash the password using SHA-256
  $hashedPassword = hash('sha256', $password);

  // Prepare and execute a query to insert the new user into the database
  $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
  mysqli_query($conn, $query);

  if (mysqli_affected_rows($conn) > 0) {
    // User was successfully registered!
    return true;
  } else {
    // Something went wrong
    return false;
  }

  // Close the database connection
  mysqli_close($conn);
}

// Example usage:
$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

if (registerUser($username, $email, $password)) {
  echo "User registered successfully!";
} else {
  echo "Error registering user.";
}

?>


<?php

function registerUser($username, $email, $password) {
    // Check for empty fields
    if (empty($username) || empty($email) || empty($password)) {
        return "Error: All fields must be filled.";
    }

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Error: Invalid email address.";
    }

    // Hash the password using SHA-256 (a simple example)
    $hashedPassword = hash('sha256', $password);

    // Connect to database (PDO example)
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=database_name", 'username', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        return "Error: Unable to connect to database.";
    }

    // Insert user into database
    try {
        $stmt = $pdo->prepare("INSERT INTO users SET username = :username, email = :email, password = :password");
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashedPassword);

        if ($stmt->execute()) {
            return "User registered successfully.";
        } else {
            return "Error: Unable to register user.";
        }
    } catch (PDOException $e) {
        return "Error: Database error - unable to insert user.";
    }

    // Close database connection
    $pdo = null;
}

// Example usage:
$username = "exampleUser";
$email = "user@example.com";
$password = "password123";

$result = registerUser($username, $email, $password);
print_r($result);

?>


<?php
function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username)) {
        throw new Exception("Username cannot be empty");
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address");
    }

    if (strlen($password) < 8) {
        throw new Exception("Password must be at least 8 characters long");
    }

    try {
        // Connect to database
        $mysqli = new mysqli("localhost", "username", "password", "database");

        if ($mysqli->connect_errno) {
            throw new Exception("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        }

        // Check for existing username and email
        $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        
        if ($result = $mysqli->query($query)) {
            while ($row = $result->fetch_assoc()) {
                throw new Exception("Username or email already in use");
            }
        } else {
            throw new Exception("Failed to query database: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$passwordHash')";
        
        if ($mysqli->query($query)) {
            return true;
        } else {
            throw new Exception("Failed to insert user: (" . $mysqli->errno . ") " . $mysqli->error);
        }
    } catch (Exception $e) {
        // Log the error
        print "Error: " . $e->getMessage() . "
";
        
        return false;
    }

    finally {
        if (isset($mysqli)) {
            $mysqli->close();
        }
    }
}
?>


try {
    registerUser("john_doe", "johndoe@example.com", "mysecretpassword");
    print "User successfully registered
";
} catch (Exception $e) {
    print "Error registering user: " . $e->getMessage() . "
";
}


<?php

// Configuration constants
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

// Connect to database
function dbConnect() {
    global $conn;
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Error connecting to database: " . $e->getMessage());
    }
}

// User registration function
function registerUser($username, $email, $password) {
    global $conn;
    
    // Check if email and username already exist
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return array('error' => 'Email or username already taken');
        }
    } catch (PDOException $e) {
        die("Error checking for existing users: " . $e->getMessage());
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        // Insert new user into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        
        return array('success' => 'User registered successfully');
    } catch (PDOException $e) {
        die("Error registering user: " . $e->getMessage());
    }
}

// Example usage
dbConnect(); // Connect to database before using the function

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if (isset($result['error'])) {
    echo $result['error']; // Output: Email or username already taken
} else if (isset($result['success'])) {
    echo $result['success']; // Output: User registered successfully
}

?>


function registerUser($username, $email, $password) {
  // Input Validation
  if (empty($username)) {
    throw new Exception('Username is required');
  }
  if (empty($email)) {
    throw new Exception('Email is required');
  }
  if (empty($password)) {
    throw new Exception('Password is required');
  }

  // Validate email format
  $emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
  if (!preg_match($emailRegex, $email)) {
    throw new Exception('Invalid email address');
  }

  // Hash password
  $passwordHash = hash('sha256', $password);

  // Connect to database (using PDO for example)
  try {
    $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');
    $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $passwordHash);
    $stmt->execute();
  } catch (PDOException $e) {
    throw new Exception('Error registering user: ' . $e->getMessage());
  }

  // Return registration success message
  return 'User registered successfully';
}


try {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $result = registerUser($username, $email, $password);
  echo $result;
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}


function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception("All fields must be filled");
    }

    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database (using PDO)
    try {
        $conn = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute query and check for errors
        if ($stmt->execute()) {
            return true; // registration successful
        } else {
            throw new Exception("Database error: " . var_export($conn->errorInfo(), true));
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo "Error connecting to database: " . $e->getMessage();
    }

    return false; // registration failed
}


try {
    if (registerUser('johnDoe', 'johndoe@example.com', 'secretPassword')) {
        echo "Registration successful!";
    } else {
        echo "Registration failed. Please try again.";
    }
} catch (Exception $e) {
    // Handle other exceptions (e.g. validation errors)
    echo "Error: " . $e->getMessage();
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


function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('All fields are required');
    }

    // Check if username is unique
    if ($this->getUserByUsername($username)) {
        throw new Exception('Username already exists');
    }

    // Hash password using PHP's built-in hash function
    $hashedPassword = hash('sha256', $password);

    // Insert user into database (assuming a database connection is established)
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    try {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);
        return true;
    } catch (PDOException $e) {
        throw new Exception('Error registering user');
    }
}


try {
    $registerUser = registerUser('johnDoe', 'johndoe@example.com', 'password123');
    echo "User registered successfully!";
} catch (Exception $e) {
    echo $e->getMessage();
}


<?php

// Configuration
require_once 'config.php';

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Extract submitted data
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  // Validate inputs
  if (empty($username) || empty($email) || empty($password)) {
    echo 'Please fill in all fields.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address.';
  } else {
    // Hash the password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
      // Prepare and execute SQL query to insert new user
      $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
      $stmt->execute([
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword
      ]);

      // If successful, display success message and redirect to login page
      echo "Registration successful! Please log in.";
    } catch (PDOException $e) {
      echo 'Error registering user: ' . $e->getMessage();
    }
  }
}

// Display registration form if not already registered or logged in
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username"><br><br>
  <label for="email">Email:</label>
  <input type="email" id="email" name="email"><br><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password"><br><br>
  <button type="submit">Register</button>
</form>

<?php
// Close database connection when done
?>


<?php
$dsn = 'mysql:host=localhost;dbname=your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
  $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
  echo 'Error connecting to database: ' . $e->getMessage();
}
?>


<?php

// Configuration variables
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create connection to database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define registration function
function registerUser($username, $email, $password, $confirm_password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields');
    }

    if ($password !== $confirm_password) {
        return array('error' => 'Passwords do not match');
    }

    // Hash password using SHA-256
    $hashedPassword = hash('sha256', $password);

    // Prepare SQL query to insert new user data into database table
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    // Execute query and store result in $result variable
    if ($stmt->execute()) {
        return array('success' => true, 'message' => 'User registered successfully');
    } else {
        return array('error' => 'Error registering user');
    }
}

// Check if form has been submitted (e.g. through AJAX request)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Call registration function and store result in $result variable
    $result = registerUser($username, $email, $password, $confirm_password);

    if ($result['success']) {
        echo json_encode(array('status' => 'success', 'message' => $result['message']));
    } elseif ($result['error']) {
        echo json_encode(array('status' => 'error', 'message' => $result['error']));
    }
} else {
    // Display registration form if no data has been submitted
    ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Email:</label>
        <input type="email" name="email"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <label>Confirm Password:</label>
        <input type="password" name="confirm_password"><br><br>
        <button type="submit">Register</button>
    </form>
    <?php
}

?>


<?php

// Configuration settings
$DB_HOST = 'your_database_host';
$DB_USER = 'your_database_user';
$DB_PASSWORD = 'your_database_password';
$DB_NAME = 'your_database_name';

function registerUser($username, $email, $password) {
  // Connect to database
  $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Hash password for security
  $hashedPassword = hash('sha256', $password);

  // Check for existing users with same username or email
  $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    return array('success' => false, 'message' => 'Username or email already taken');
  }

  // Insert new user into database
  $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
  mysqli_query($conn, $query);

  // Return success message
  return array('success' => true, 'message' => 'User registered successfully');
}

?>


$success = registerUser('johnDoe', 'johndoe@example.com', 'mysecretpassword');
if ($success['success']) {
  echo "User registered successfully!";
} else {
  echo $success['message'];
}


// Include the database connection file
require_once 'db.php';

function registerUser($username, $email, $password) {
    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        return array('success' => false, 'message' => 'Please fill in all fields.');
    }

    // Check for duplicate usernames and emails
    if ($this->checkDuplicateUsername($username) || $this->checkDuplicateEmail($email)) {
        return array('success' => false, 'message' => 'Username or email already taken.');
    }

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $query = "INSERT INTO users (username, email) VALUES (:username, :email)";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    if ($stmt->execute()) {
        // Insert password hash into database
        $query = "INSERT INTO passwords (user_id, password_hash) VALUES (:userId, :passwordHash)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $this->db->lastInsertId());
        $stmt->bindParam(':passwordHash', $passwordHash);
        if ($stmt->execute()) {
            return array('success' => true, 'message' => 'User created successfully.');
        } else {
            return array('success' => false, 'message' => 'Failed to create user.');
        }
    } else {
        return array('success' => false, 'message' => 'Failed to create user.');
    }
}


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);
echo json_encode($result);


<?php

// Database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
  global $conn;

  // Get user input
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $username = $_POST['username'];

  // Check if email already exists in database
  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo 'Email already exists. Please choose a different email.';
    return false;
  }

  // Insert new user into database
  $sql = "INSERT INTO users (email, password, username) VALUES ('$email', '$password', '$username')";
  if ($conn->query($sql) === TRUE) {
    echo 'User created successfully!';
    return true;
  } else {
    echo 'Error creating user: ' . $conn->error;
    return false;
  }
}

// Check for POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  registerUser();
} else {
  // Handle GET request (display registration form)
  ?>
  <h1>Register</h1>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    Email: <input type="email" name="email"><br><br>
    Password: <input type="password" name="password"><br><br>
    Username: <input type="text" name="username"><br><br>
    <button type="submit">Register</button>
  </form>
  <?php
}

// Close database connection
$conn->close();

?>


function registerUser($name, $email, $password) {
    // Create a new connection to the database
    require_once 'config.php';  // assuming your database credentials are stored in this file
    $conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
    
    // Check if the password is strong enough (contains at least one lowercase letter, one uppercase letter and one digit)
    if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
        return "Password must contain at least one uppercase letter, one lowercase letter and one digit.";
    }
    
    // Check if the email already exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return "Email already exists. Please try a different email.";
    }
    
    // If all checks pass, create a new user
    $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '".md5($password)."')";
    if (!mysqli_query($conn, $query)) {
        return mysqli_error($conn);
    } else {
        return true;  // Return a success message or any other value indicating successful registration
    }
}

// Example usage:
$registrationStatus = registerUser('John Doe', 'john@example.com', 'password123');
if ($registrationStatus === true) {
    echo "Registration successful!";
} elseif (is_string($registrationStatus)) {
    echo $registrationStatus;  // Display any error message to the user
}


function registerUser($name, $email, $password) {
    // Create a new connection to the database
    require_once 'config.php';  // assuming your database credentials are stored in this file
    $conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
    
    // Check if the password is strong enough (contains at least one lowercase letter, one uppercase letter and one digit)
    if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
        return "Password must contain at least one uppercase letter, one lowercase letter and one digit.";
    }
    
    // Check if the email already exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return "Email already exists. Please try a different email.";
    }
    
    // If all checks pass, create a new user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
    if (!mysqli_query($conn, $query)) {
        return mysqli_error($conn);
    } else {
        return true;  // Return a success message or any other value indicating successful registration
    }
}


<?php

// Configuration file for database connection
require_once 'config.php';

function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields');
    }

    // Check for existing user
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($GLOBALS['db'], $query);
    if (mysqli_num_rows($result) > 0) {
        return array('error' => 'Username already exists');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if (mysqli_query($GLOBALS['db'], $query)) {
        return array('success' => 'User created successfully');
    } else {
        return array('error' => 'Error creating user: ' . mysqli_error($GLOBALS['db']));
    }
}

?>


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

$response = registerUser($username, $email, $password);
if ($response['success']) {
    echo 'User created successfully!';
} elseif ($response['error']) {
    echo 'Error creating user: ' . $response['error'];
}


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'mydatabase');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

// Function to register new user
function registerUser($username, $email, $password) {
  // Connect to database
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare SQL query to insert new user data into database
  $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $email, md5($password));

  // Execute SQL query and get the result
  if ($stmt->execute()) {
    $result = $conn->insert_id;
    return true; // Registration successful!
  } else {
    echo "Error: " . $stmt->error;
    return false; // Error occurred during registration
  }

  // Close database connection
  $conn->close();
}

// Example usage:
if (isset($_POST['register'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  if ($username && $email && $password) {
    $registered = registerUser($username, $email, $password);
    if ($registered) {
      echo "Registration successful!";
    } else {
      echo "Error occurred during registration.";
    }
  } else {
    echo "Please fill in all required fields.";
  }
}

?>


<?php
/**
 * User Registration Function
 *
 * @param array $userData - User input data (username, email, password)
 * @return bool|void - True if registration is successful, false otherwise
 */
function registerUser($userData) {
    // Database connection settings
    require 'db.php';

    try {
        // Validate user input
        if (!validateInput($userData)) {
            return false;
        }

        // Hash password
        $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);

        // Prepare SQL query to insert user data into database
        $query = "
            INSERT INTO users (username, email, password)
            VALUES (:username, :email, :password)
        ";

        // Execute SQL query with prepared statement
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':username' => $userData['username'],
            ':email' => $userData['email'],
            ':password' => $hashedPassword
        ]);

        // Commit changes to database
        $pdo->commit();

        return true;

    } catch (PDOException $e) {
        // Rollback changes in case of error
        $pdo->rollBack();
        echo "Error registering user: " . $e->getMessage();
        return false;
    }
}

/**
 * Validate User Input
 *
 * @param array $userData - User input data (username, email, password)
 * @return bool|void - True if validation is successful, false otherwise
 */
function validateInput($userData) {
    // Basic validation rules
    $rules = [
        'username' => ['required', 'min:3'],
        'email' => ['required', 'email'],
        'password' => ['required', 'min:8']
    ];

    foreach ($rules as $field => $options) {
        if (empty($userData[$field])) {
            echo "Error: Field '$field' is required.";
            return false;
        }

        switch ($options[0]) {
            case 'min':
                if (strlen($userData[$field]) < $options[1]) {
                    echo "Error: Field '$field' must be at least $options[1] characters long.";
                    return false;
                }
                break;

            default:
                break;
        }
    }

    return true;
}

// Example usage
$userData = [
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
];

if (registerUser($userData)) {
    echo "User registered successfully!";
} else {
    echo "Error registering user.";
}
?>


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


<?php

function registerUser($username, $email, $password) {
    // Check if the username and email are already in use
    $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0) {
        return array("error" => "Username or email is already taken");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    mysqli_query($link, $sql);

    return array("message" => "User registered successfully");
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$userData = registerUser($username, $email, $password);
print_r($userData);

?>


$stmt = mysqli_prepare($link, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hashedPassword);
mysqli_stmt_execute($stmt);


function registerUser($username, $email, $password) {
    // Database connection settings
    $dbHost = 'localhost';
    $dbUsername = 'your_username';
    $dbPassword = 'your_password';
    $dbName = 'your_database';

    try {
        // Create a database connection
        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the username is already taken
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        if ($stmt->fetch()) {
            throw new Exception('Username already exists');
        }

        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $insertQuery = 'INSERT INTO users (username, email, password) VALUES (:username, :email, :password)';
        $stmt = $conn->prepare($insertQuery);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->execute();

        // Return a success message
        return 'User registered successfully';
    } catch (PDOException $e) {
        // Handle any database connection errors
        echo 'Database error: ' . $e->getMessage();
        return null;
    }
}


// Register a new user
$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'password123';

$result = registerUser($username, $email, $password);
if ($result !== null) {
    echo $result;
} else {
    echo 'Registration failed';
}


<?php

// Database connection settings
$hostname = "your_host";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Establish a database connection
$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    global $conn;

    // Collect registration data from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if all fields are filled in
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "Please fill in all the required fields.";
        return;
    }

    // Validate email address
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        echo "Invalid email format.";
        return;
    }

    // Check for duplicate emails
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "Email address already exists.";
        return;
    }

    // Validate passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        return;
    }

    // Hash password
    $password = md5($password);

    // Insert new user into the database
    $sql = "INSERT INTO users (name, email, password)
            VALUES ('$name', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}

?>


<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name"><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password"><br><br>
    <button type="submit" name="register">Register</button>
</form>

<?php
if (isset($_POST['register'])) {
    registerUser();
}
?>


<?php

require 'config.php'; // Load database connection settings

function registerUser($name, $email, $password) {
  // Validate input data
  if (empty($name) || empty($email) || empty($password)) {
    return array('error' => 'All fields are required');
  }

  // Check for valid email address
  if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
    return array('error' => 'Invalid email address');
  }

  // Encrypt password using SHA256 (not recommended for secure applications)
  // For secure applications, use a library like PHPass or BCrypt
  $password = sha1($password);

  try {
    // Connect to database
    $conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);

    // Prepare query
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");

    // Bind parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    // Execute query
    if ($stmt->execute()) {
      return array('success' => 'User registered successfully');
    } else {
      return array('error' => 'Failed to register user');
    }

  } catch (PDOException $e) {
    return array('error' => 'Database connection error: ' . $e->getMessage());
  }
}

// Example usage:
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($name, $email, $password);

echo json_encode($result);


<?php

$host = 'localhost';
$db = 'mydatabase'; // Database name
$username = 'myuser'; // Database username
$password = 'mypassword'; // Database password


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'database_name';
$username = 'username';
$password = 'password';

function registerUser($firstName, $lastName, $email, $password, $confirmPassword) {
    // Check if the database is available
    try {
        // Create a connection to the database
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare an SQL query to insert user data into the users table
        $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (:firstName, :lastName, :email, :password)");

        // Bind the input parameters to the prepared statement
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        // Check if the user email already exists in the database
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt2 = $conn->prepare($query);
        $stmt2->bindParam(':email', $email);
        $stmt2->execute();
        $result = $stmt2->fetch();

        // Check if the user email already exists
        if ($result) {
            echo "Email already exists. Please choose a different email address.";
            return;
        }

        // Check if the passwords match
        if ($password !== $confirmPassword) {
            echo "Passwords do not match.";
            return;
        }

        // Insert new user data into the database
        $stmt->execute();

        echo "User registered successfully!";

    } catch (PDOException $e) {
        echo "Error connecting to database: " . $e->getMessage();
    }
}

?>


<?php

// Example usage:
$firstName = 'John';
$lastName = 'Doe';
$email = 'johndoe@example.com';
$password = 'password123';
$confirmPassword = 'password123';

registerUser($firstName, $lastName, $email, $password, $confirmPassword);

?>


// Include the required libraries
require_once 'database.php'; // Your database connection file

function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('Please fill in all fields.');
    }

    // Validate username and email length
    if (strlen($username) < 3 || strlen($username) > 30) {
        throw new Exception('Username must be between 3 and 30 characters long.');
    }
    if (strlen($email) < 5 || strlen($email) > 255) {
        throw new Exception('Email address is too short or too long.');
    }

    // Check for existing username
    $query = 'SELECT * FROM users WHERE username = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        throw new Exception('Username already exists.');
    }

    // Check for existing email address
    $query = 'SELECT * FROM users WHERE email = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        throw new Exception('Email address is already registered.');
    }

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into database
    $query = 'INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)';
    $stmt = $db->prepare($query);
    $stmt->execute([$username, $email, $passwordHash]);

    return true;
}


try {
    registerUser('johnDoe', 'johndoe@example.com', 'password123');
    echo "Registration successful!";
} catch (Exception $e) {
    echo $e->getMessage();
}


<?php

// Configuration variables for database connection and password hashing
$db_host = 'localhost';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

$hashed_password_cost = 12; // Higher values mean more computation required, but stronger passwords.
require_once 'vendor/autoload.php'; // Assuming you're using Composer for autoloading.

use Illuminate\Support\Facades\Hash;

function registerUser($username, $email, $password) {
    try {
        // Connect to database
        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // SQL query to insert user into database
        $sql = "INSERT INTO users (username, email, hashed_password) VALUES (?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        if (!$stmt->bind_param("sss", $username, $email, $hashed_password)) {
            throw new Exception("Parameter binding failed: " . $conn->error);
        }
        
        if ($stmt->execute()) {
            echo 'User registered successfully.';
        } else {
            throw new Exception("Registration failed. Please try again.");
        }
        
        // Close database connection
        $conn->close();
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage() . "
";
    }
}

// Example usage:
registerUser('john_doe', 'johndoe@example.com', 'password123');

?>


function registerUser($username, $email, $password)
{
    // Hash the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute a SQL query to insert new user data into the database
    try {
        $conn = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        return false;
    }

    // If the user was successfully created, return true
    return true;
}


if (registerUser('johnDoe', 'johndoe@example.com', 'mysecretpassword')) {
    echo "User account created successfully!";
} else {
    echo "Error creating user account.";
}


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database');

function registerUser($name, $email, $password) {
  // Connect to the database
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if ($conn->connect_error) {
    // Handle connection error
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare query
  $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

  try {
    // Bind parameters and execute query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, password_hash($password, PASSWORD_DEFAULT));
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
      return true; // User created successfully
    } else {
      throw new Exception('Failed to create user');
    }
  } catch (Exception $e) {
    echo "Error creating user: " . $e->getMessage();
    return false;
  }

  finally {
    // Close database connection
    $conn->close();
  }
}


// Register a new user
$result = registerUser('John Doe', 'john.doe@example.com', 'password123');

if ($result) {
  echo "User created successfully!";
} else {
  echo "Failed to create user.";
}


function registerUser($username, $email, $password) {
  // Check if the username or email already exists in the database
  $exists = checkIfExists($username, $email);
  if ($exists) {
    return array('error' => 'Username or email already taken');
  }

  // Hash the password using a secure hashing algorithm (e.g. bcrypt)
  $hashedPassword = hashPassword($password);

  // Insert new user into database
  $userId = insertUserIntoDatabase($username, $email, $hashedPassword);
  if ($userId === false) {
    return array('error' => 'Error registering user');
  }

  // Send verification email (optional)
  sendVerificationEmail($email);

  // Return the user's ID and a success message
  return array('id' => $userId, 'message' => 'User registered successfully');
}

// Helper function to check if username or email already exists in database
function checkIfExists($username, $email) {
  global $db;
  $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
  $result = mysqli_query($db, $query);
  return (mysqli_num_rows($result) > 0);
}

// Helper function to hash password using bcrypt
function hashPassword($password) {
  $salt = random_bytes(22);
  $hashedPassword = crypt($password, $salt);
  return $hashedPassword;
}

// Helper function to insert new user into database
function insertUserIntoDatabase($username, $email, $hashedPassword) {
  global $db;
  $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
  return mysqli_insert_id($db);
}

// Helper function to send verification email (optional)
function sendVerificationEmail($email) {
  // Email implementation goes here
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'secret';

$result = registerUser($username, $email, $password);
print_r($result);


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
function registerUser($name, $email, $password) {
    // Validate input
    if (empty($name) || empty($email) || empty($password)) {
        return array('success' => false, 'error' => 'Please fill in all fields.');
    }

    // Check for duplicate email
    $existingUser = getUserByEmail($email);
    if ($existingUser) {
        return array('success' => false, 'error' => 'Email already registered.');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    try {
        $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->execute(array(':name' => $name, ':email' => $email, ':password' => $hashedPassword));
        return array('success' => true, 'message' => 'User registered successfully.');
    } catch (PDOException $e) {
        return array('success' => false, 'error' => 'Error registering user: ' . $e->getMessage());
    }
}

function getUserByEmail($email) {
    // Query database to check for existing email
    try {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->execute(array(':email' => $email));
        return $stmt->fetch();
    } catch (PDOException $e) {
        // Handle database error
    }
}


// Assume you have a database connection set up in a variable named `$db`
$name = 'John Doe';
$email = 'johndoe@example.com';
$password = 'password123';

$response = registerUser($name, $email, $password);
print_r($response);

if ($response['success']) {
    echo "User registered successfully!";
} else {
    echo $response['error'];
}


function registerUser($name, $email, $password) {
    // Validate input fields
    if (empty($name) || empty($email) || empty($password)) {
        throw new Exception("All fields are required");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to database
        require_once 'database.php';
        $db = new Database();

        // Insert user into database
        $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Failed to register user");
        }
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
        return false;
    }

    // Close database connection
    $db = null;

    return false;
}


try {
    $name = 'John Doe';
    $email = 'johndoe@example.com';
    $password = 'password123';

    if (registerUser($name, $email, $password)) {
        echo "User registered successfully!";
    } else {
        echo "Failed to register user.";
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}


function registerUser($username, $email, $password)
{
    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to database
        $conn = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute SQL statement
        $stmt->execute();

        // Close connection
        $conn = null;

        return array('message' => 'User registered successfully');
    } catch (PDOException $e) {
        return array('error' => 'Error registering user: ' . $e->getMessage());
    }
}


$username = 'johnDoe';
$email = 'johndoe@example.com';
$password = 'mysecretpassword';

$result = registerUser($username, $email, $password);

if (isset($result['error'])) {
    echo 'Error: ' . $result['error'];
} else {
    echo 'Success: ' . $result['message'];
}


function registerUser($username, $email, $password) {
    // Check if username or email already exists
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return array("error" => "Username or Email already taken");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if (!mysqli_query($conn, $query)) {
        return array("error" => mysqli_error($conn));
    }

    return array("success" => true);
}


// Connect to database
$conn = mysqli_connect("localhost", "username", "password", "database");

// Get user details from form submission
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// Register new user
$result = registerUser($username, $email, $password);
if ($result["success"]) {
    echo "Registration successful!";
} else {
    echo "Error: " . $result["error"];
}

// Close database connection
mysqli_close($conn);


<?php
// Configuration
$dbhost = 'localhost';
$dbname = 'mydatabase';
$dbusername = 'root';
$dbpassword = '';

// Connect to database
$conn = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    // Get user input
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        echo "Error: All fields are required.";
        return false;
    }

    // Check for existing username and email
    $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Error: Username or email already exists.";
        return false;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    if ($conn->query($sql) === TRUE) {
        echo "User registered successfully!";
        return true;
    } else {
        echo "Error: Registration failed. Please try again.";
        return false;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $register = registerUser();
}

?>


<?php

/**
 * User Registration Function
 *
 * @param string $username The username chosen by the user
 * @param string $email The email address of the user
 * @param string $password The password chosen by the user (hashed)
 * @return array|bool A success message or false on error
 */
function registerUser($username, $email, $password) {
    // Check if any fields are empty
    if (empty($username) || empty($email) || empty($password)) {
        return false;
    }

    // Validate username and email
    if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
        return array('error' => 'Invalid username');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database (replace with your own connection script)
    require_once 'db.php';
    $conn = connectToDatabase();

    // Check if username already exists in database
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    if ($stmt->fetch()) {
        return array('error' => 'Username already exists');
    }

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    if ($stmt->execute()) {
        return array('success' => 'User registered successfully');
    } else {
        return false;
    }
}

?>


$username = 'johndoe';
$email = 'john@example.com';
$password = 'password123';

$result = registerUser($username, $email, $password);

if ($result['success']) {
    echo "User registered successfully!";
} elseif ($result['error']) {
    echo "Error: " . $result['error'];
}


// config.php

define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// register.php

require_once 'config.php';

function registerUser($username, $email, $password) {
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return array('success' => false, 'message' => 'Please fill in all fields');
    }

    // Check for existing username and email
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        return array('success' => false, 'message' => 'Username or email already exists');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if ($conn->query($query)) {
        return array('success' => true, 'message' => 'User registered successfully');
    } else {
        return array('success' => false, 'message' => 'Registration failed');
    }
}

// Example usage
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);
echo json_encode($result);


<?php

// Configuration variables
$mysqli = new mysqli("localhost", "username", "password", "database_name");

function registerUser($name, $email, $password) {
    // Check for errors with the database connection
    if ($mysqli->connect_errno) {
        echo "Connection failed: " . $mysqli->connect_error;
        exit();
    }

    // Hash the password
    $hashedPassword = hash('sha256', $password);

    // Prepare and execute SQL query to insert user data into the database
    $stmt = $mysqli->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);
    $result = $stmt->execute();

    // Check if the insertion was successful
    if (!$result) {
        echo "Error inserting user: " . $mysqli->error;
        exit();
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $mysqli->close();

    // Return true to indicate a successful registration
    return true;
}

// Test the function
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($name) && !empty($email) && !empty($password)) {
        registerUser($name, $email, $password);
        echo "Registration successful!";
    } else {
        echo "Error: All fields must be filled in.";
    }
}

// Close the database connection
$mysqli->close();

?>


<form action="register.php" method="post">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name"><br><br>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email"><br><br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password"><br><br>
    <button type="submit" name="register">Register</button>
</form>


<?php

// Configuration variables
$host = 'localhost';
$dbname = 'database_name';
$username = 'username';
$password = 'password';

function registerUser($name, $email, $password) {
    // Connect to the database
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Hash the password
        $hashedPassword = hash('sha256', $password);

        // Prepare and execute the insert query
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the insert query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    } finally {
        $conn = null;
    }
}

// Example usage:
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

if (registerUser($name, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Error registering user.";
}
?>


// db_config.php
$host = 'localhost';
$db_name = 'mydatabase';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $user, $password);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}


// register.php
require_once 'db_config.php';

function register_user($username, $email, $password) {
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('All fields are required.');
    }

    try {
        // Prepare and execute SQL query to insert user data into database
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', hash('sha256', $password));
        $stmt->execute();

        // Return true on successful registration
        return true;
    } catch (PDOException $e) {
        // Handle database error
        print("Error: " . $e->getMessage());
        return false;
    }
}


// register_user_example.php
require_once 'db_config.php';
require_once 'register.php';

try {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (register_user($username, $email, $password)) {
        echo "Registration successful!";
    } else {
        echo "Error registering user.";
    }
} catch (Exception $e) {
    // Handle validation error
    print("Error: " . $e->getMessage());
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


function registerUser($username, $email, $password) {
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        return array('error' => 'All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'Invalid email address');
    }

    // Check for existing user
    $existingUser = getUserByUsername($username);
    if ($existingUser) {
        return array('error' => 'Username already exists');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Register new user
    $newUser = array(
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword
    );
    registerNewUser($newUser);

    return array('success' => 'User registered successfully');
}

// Helper functions
function getUserByUsername($username) {
    // Query database to check if username exists
    // Replace with your own database connection and query
    $db = new PDO('sqlite:database.db');
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->execute(array(':username' => $username));
    return $stmt->fetch();
}

function registerNewUser($newUser) {
    // Insert new user into database
    // Replace with your own database connection and query
    $db = new PDO('sqlite:database.db');
    $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $stmt->execute(array(
        ':username' => $newUser['username'],
        ':email' => $newUser['email'],
        ':password' => $newUser['password']
    ));
}


registerUser('johnDoe', 'johndoe@example.com', 'password123');


<?php

// Configuration
require_once 'config.php';

function registerUser($data) {
    // Validate input data
    if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
        throw new Exception('Missing required fields');
    }

    $username = trim($data['username']);
    $email = trim($data['email']);
    $password = trim($data['password']);

    // Check for valid input
    if (empty($username) || empty($email) || empty($password)) {
        throw new Exception('All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to database
        $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert user data into database
        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception('Failed to register user');
        }

    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
        return false;

    } finally {
        // Close database connection
        unset($conn);
    }
}

?>


$data = [
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
];

if (registerUser($data)) {
    echo 'User registered successfully!';
} else {
    echo 'Failed to register user.';
}


<?php

// User Registration Function

function registerUser($username, $email, $password) {
  // Check if the username and email are valid (for simplicity, we'll assume they're valid for now)
  if (!ctype_alnum($username)) {
    throw new Exception("Invalid username");
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Invalid email address");
  }

  // Hash the password using a secure hashing algorithm (in this case, bcrypt)
  $passwordHash = password_hash($password, PASSWORD_BCRYPT);

  // Connect to the database
  $conn = new PDO('mysql:host=localhost;dbname=mydatabase', 'myusername', 'mypassword');

  // Insert the new user into the database
  $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $passwordHash);

  try {
    $stmt->execute();
  } catch (PDOException $e) {
    throw new Exception("Failed to register user: " . $e->getMessage());
  }

  // Close the database connection
  $conn = null;

  return true;
}

// Example usage:
$username = "johnDoe";
$email = "johndoe@example.com";
$password = "password123";

try {
  if (registerUser($username, $email, $password)) {
    echo "User registered successfully!";
  } else {
    throw new Exception("Failed to register user");
  }
} catch (Exception $e) {
  echo "Error registering user: " . $e->getMessage();
}


<?php

function registerUser($name, $email, $password) {
    // Check if the email already exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return array('error' => 'Email already exists');
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
    if (!mysqli_query($conn, $query)) {
        return array('error' => 'Database error');
    }

    // Return a success message
    return array('message' => 'User registered successfully');
}

// Example usage:
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($name, $email, $password);
echo json_encode($result);

?>


$conn = new mysqli('localhost', 'username', 'password', 'database');


<?php

// Configuration variables
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'database_name');

function registerUser($firstName, $lastName, $email, $password) {
    // Check if the form data is not empty
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        return "Please fill in all fields.";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // Hash the password using SHA-256
    $hashedPassword = hash('sha256', $password);

    // Connect to database
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        return "Connection failed: " . $conn->connect_error;
    }

    // Check if email already exists in the database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return "Email already registered.";
    } else {
        // Insert new user into the database
        $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";
        if ($conn->query($sql)) {
            return true;
        }
        return "Registration failed.";
    }

    // Close database connection
    $conn->close();
}

?>


<?php

// Define form data variables
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password'];

// Call registerUser function with form data
$result = registerUser($firstName, $lastName, $email, $password);

// Display the result to the user
echo $result;

?>


function registerUser($username, $email, $password)
{
    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        return "Error: All fields are required.";
    }

    // Check for valid email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Error: Invalid email address.";
    }

    // Hash password using SHA-256 algorithm
    $hashedPassword = hash('sha256', $password);

    try {
        // Connect to database
        $conn = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL query
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashedPassword);

        // Execute query
        if ($stmt->execute()) {
            return "User registered successfully.";
        } else {
            throw new Exception("Error registering user.");
        }
    } catch (PDOException $e) {
        return "Database error: " . $e->getMessage();
    } finally {
        // Close database connection
        $conn = null;
    }
}


$username = "johnDoe";
$email = "johndoe@example.com";
$password = "password123";

$result = registerUser($username, $email, $password);
echo $result; // Output: User registered successfully.


function register_user($username, $email, $password, $confirm_password) {
  // Validate input data
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception("Please fill in all fields.");
  }

  if ($password !== $confirm_password) {
    throw new Exception("Passwords do not match.");
  }

  // Hash password for security
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Connect to database
  $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  try {
    // Prepare query
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

    // Bind parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    // Execute query
    $stmt->execute();

    return true;
  } catch (PDOException $e) {
    throw new Exception("Database error: " . $e->getMessage());
  }
}


try {
  register_user('johnDoe', 'johndoe@example.com', 'password123', 'password123');
  echo "User registered successfully.";
} catch (Exception $e) {
  echo "Error registering user: " . $e->getMessage();
}


<?php

// Configuration settings
$dbHost = 'localhost';
$dbName = 'database_name';
$dbUsername = 'username';
$dbPassword = 'password';

// Create database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select database
$conn->select_db($dbName);

function registerUser() {
    // Get user input
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        echo 'Error: All fields must be filled in.';
        return;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if ($conn->query($sql)) {
        echo 'User registered successfully!';
    } else {
        echo 'Error: Registration failed.';
    }

    // Close database connection
    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    registerUser();
} else {
    // Display registration form if not submitted
    ?>
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
    <?php
}

?>


// Database Connection Variables
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to the database
function connect_to_db() {
    global $db_host, $db_username, $db_password, $db_name;
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Close the database connection
function close_db_connection($conn) {
    $conn->close();
}

// Function to register a new user
function register_user($username, $email, $password) {
    global $db_host, $db_username, $db_password, $db_name;

    // Connect to the database
    $conn = connect_to_db();

    // Check if the username already exists
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        close_db_connection($conn);
        return array('success' => false, 'message' => 'Username already taken');
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password_hash')";
    if ($conn->query($query)) {
        close_db_connection($conn);
        return array('success' => true, 'message' => 'User registered successfully');
    } else {
        close_db_connection($conn);
        return array('success' => false, 'message' => 'Error registering user');
    }
}

// Example usage:
$username = 'newuser';
$email = 'newuser@example.com';
$password = 'password123';

$result = register_user($username, $email, $password);

print_r($result); // Output: Array ( [success] => true [message] => User registered successfully )


<?php

// Configuration
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myusername';
$password = 'mypassword';

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser($username, $email, $password)
{
    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    
    // Bind parameters
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    
    // Execute query
    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error: " . $conn->error;
        return false;
    }
}

// Example usage:
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        if (registerUser($username, $email, $password)) {
            echo "User registered successfully!";
        }
    } else {
        echo "Please fill in all fields.";
    }
}

// Close database connection
$conn->close();
?>


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


function register_user($username, $email, $password) {
  // Input validation
  if (empty($username) || empty($email) || empty($password)) {
    throw new Exception("All fields are required.");
  }

  // Password hashing
  $password_hash = password_hash($password, PASSWORD_DEFAULT);

  // Connect to database
  $conn = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');

  try {
    // Prepare query
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");

    // Bind parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password_hash', $password_hash);

    // Execute query
    $stmt->execute();

    // Return user ID (auto-incremented primary key)
    return $conn->lastInsertId();
  } catch (PDOException $e) {
    throw new Exception("Database error: " . $e->getMessage());
  }
}


try {
  $userId = register_user('johnDoe', 'johndoe@example.com', 'password123');
  echo "User registered successfully. ID: $userId";
} catch (Exception $e) {
  echo "Error registering user: " . $e->getMessage();
}


<?php

// Configuration variables
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser($data)
{
    // Extract data from the array
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];

    // Validation checks
    if (empty($username) || empty($email) || empty($password)) {
        return 'Error: Please fill in all fields.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Error: Invalid email address.';
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    if ($conn->query($sql) === TRUE) {
        return 'User registered successfully.';
    } else {
        return 'Error: User registration failed. Please try again later.';
    }
}

// Example usage
$data = [
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'password123'
];

echo registerUser($data);

?>


$data = [
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'password123'
];

echo registerUser($data);


function registerUser($username, $email, $password) {
    // Validate input data
    if (!validateUsername($username)) {
        return array('error' => 'Invalid username');
    }

    if (!validateEmail($email)) {
        return array('error' => 'Invalid email address');
    }

    if (strlen($password) < 8) {
        return array('error' => 'Password must be at least 8 characters long');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to database and insert user data
        $conn = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Return success message
        return array('success' => 'User registered successfully');
    } catch (PDOException $e) {
        // Handle database errors
        return array('error' => 'Database error: ' . $e->getMessage());
    }
}

// Helper functions for validation

function validateUsername($username) {
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return false;
    }

    // Check username length (min 3, max 32 characters)
    if (strlen($username) < 3 || strlen($username) > 32) {
        return false;
    }

    return true;
}

function validateEmail($email) {
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        return false;
    }

    return true;
}


$userData = registerUser('newuser', 'newuser@example.com', 'mysecretpassword');
print_r($userData);


/**
 * User Registration Function
 *
 * @param array $data Registration data
 * @return bool|false|string Result of registration (true/false or error message)
 */
function registerUser(array $data) {
    // Database connection parameters
    $host = 'localhost';
    $username = 'your_username';
    $password = 'your_password';
    $database = 'your_database';

    try {
        // Connect to the database
        $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);

        // Validate input data
        if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
            return 'Error: Missing required fields';
        }

        // Check for existing username and email
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->execute();

        if ($stmt->fetch()) {
            return 'Error: Username or email already taken';
        }

        // Hash the password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Insert new user into database
        $sql = 'INSERT INTO users (username, email, password) VALUES (:username, :email, :password)';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        return 'Error: ' . $e->getMessage();
    }
}


$data = [
    'username' => 'johnDoe',
    'email' => 'johndoe@example.com',
    'password' => 'mysecretpassword'
];

$result = registerUser($data);

if ($result === true) {
    echo 'User registered successfully!';
} elseif (is_string($result)) {
    echo $result;
}


<?php

// Configuration variables
$dbHost = 'localhost';
$dbUsername = 'username';
$dbPassword = 'password';
$dbName = 'database_name';

// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser($username, $email, $password, $confirm_password) {
    // Check if the form has been submitted
    if (isset($_POST['register'])) {

        // Hash password before storing it in the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Validate user input
        if (empty($username) || empty($email) || empty($password)) {
            echo "Please fill out all fields!";
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email address";
            return false;
        }

        if ($password !== $confirm_password) {
            echo "Passwords do not match";
            return false;
        }

        // Prepare SQL query
        $sql = "INSERT INTO users (username, email, password)
                VALUES ('$username', '$email', '$hashedPassword')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
            return true;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            return false;
        }
    }

    // If form hasn't been submitted, render registration form
    if (!isset($_POST['register'])) {
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username"><br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email"><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password"><br><br>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password"><br><br>
            <button type="submit" name="register">Register</button>
        </form>
    <?php
    }
}

// Call the function
registerUser();

?>


// configuration.php (store your database connection settings here)
$db_host = 'your_database_host';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the input data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validation
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            echo "Please fill in all the fields";
            return;
        }

        if ($password !== $confirm_password) {
            echo "Passwords do not match";
            return;
        }

        // Hashing password
        $hashedPassword = hash('sha256', $password);

        // Query to insert new user
        $query = "INSERT INTO users (username, email, password)
                  VALUES ('$username', '$email', '$hashedPassword')";

        if ($conn->query($query)) {
            echo "User registered successfully";
        } else {
            echo "Error registering user: " . $conn->error;
        }
    }
}


$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $hashedPassword);


$stmt->execute();


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'users');

// Function to register a new user
function registerUser($name, $email, $password) {
    // Connect to database
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Check connection
    if (!$conn) {
        return array('error' => 'Failed to connect to database');
    }

    // Validate input data
    if (empty($name) || empty($email) || empty($password)) {
        return array('error' => 'Please fill in all fields');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
    if (!mysqli_query($conn, $query)) {
        return array('error' => 'Failed to register user');
    }

    // Close connection and return success message
    mysqli_close($conn);
    return array('success' => 'User registered successfully');
}

// Example usage:
$name = "John Doe";
$email = "john.doe@example.com";
$password = "password123";

$result = registerUser($name, $email, $password);

if (isset($result['error'])) {
    echo '<p style="color: red;">' . $result['error'] . '</p>';
} elseif (isset($result['success'])) {
    echo '<p>' . $result['success'] . '</p>';
}

?>


<?php

// Configuration variables
$db_host = 'your_database_host';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
  // Form data from the registration form
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // SQL query to insert user into database
  $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

  // Execute the query and check for errors
  if ($conn->query($query) === TRUE) {
    echo "User registered successfully!";
  } else {
    echo "Error: " . $query . "<br>" . $conn->error;
  }
}

// Check if the registration form has been submitted
if (isset($_POST['register'])) {
  registerUser();
}

?>


<?php
// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function createUser($email, $username, $password) {
    // Connect to database
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare SQL query
    $stmt = mysqli_prepare($conn, "INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sss', $email, $username, $password);

    if (!mysqli_stmt_execute($stmt)) {
        echo "Error creating user: " . mysqli_error($conn);
        return false;
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return true;
}

// Example usage:
$email = 'user@example.com';
$username = 'johnDoe';
$password = password_hash('password', PASSWORD_DEFAULT); // Hash the password

if (createUser($email, $username, $password)) {
    echo "User created successfully!";
} else {
    echo "Failed to create user.";
}
?>


<?php

/**
 * Register a new user
 *
 * @param array $data Array of user data to register (name, email, password)
 * @return bool|integer True if registration was successful, false otherwise. Returns the newly created user's ID.
 */
function registerUser(array $data) {
    // Validate user input
    $errors = validateRegistrationData($data);
    if (!empty($errors)) {
        return false; // Return false on validation errors
    }

    // Hash password for storage
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    // Create new user record
    $userId = createUserRecord($data['name'], $data['email'], $hashedPassword);
    if (!$userId) {
        return false; // Return false on database errors
    }

    return $userId;
}

/**
 * Validate registration data
 *
 * @param array $data Array of user data to validate (name, email, password)
 * @return array Array of validation errors
 */
function validateRegistrationData(array $data) {
    $errors = [];

    // Check for required fields
    if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
        $errors[] = 'All fields are required.';
    }

    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address.';
    }

    // Check for password length requirements
    if (strlen($data['password']) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }

    return $errors;
}

/**
 * Create new user record in database
 *
 * @param string $name User name
 * @param string $email User email address
 * @param string $password Hashed password
 * @return integer New user ID, or false on failure
 */
function createUserRecord(string $name, string $email, string $password) {
    // Database connection ( assume a PDO instance is available as $db )
    try {
        $stmt = $db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $password]);
        return $db->lastInsertId();
    } catch (PDOException $e) {
        // Handle database error
        echo 'Database error: ' . $e->getMessage();
        return false;
    }
}


$data = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'mysecretpassword'
];

$userId = registerUser($data);
if ($userId) {
    echo "New user created with ID: $userId";
} else {
    echo "Registration failed. Check error logs for more information.";
}


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


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Function to register new user
function register_user($username, $email, $password) {
  // Connect to database
  $conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

  if (!$conn) {
    die('Connection failed: ' . mysqli_error($conn));
  }

  // Hash password for security
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
  if (!mysqli_query($conn, $sql)) {
    die('Error registering user: ' . mysqli_error($conn));
  }

  // Close database connection
  mysqli_close($conn);

  return true;
}

// Example usage:
$username = 'john_doe';
$email = 'johndoe@example.com';
$password = 'password123';

if (register_user($username, $email, $password)) {
  echo "User registered successfully!";
} else {
  echo "Error registering user.";
}
?>


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    // Get user input
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user input
    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill in all fields";
        return;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "User registered successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close database connection
    $conn->close();
}

?>


// Define your database settings
$db_host = 'your_database_host';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Create a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function registerUser($name, $email, $password) {
    // Hash the password before storing it in the database.
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // SQL query to insert new user into the database.
    $sql = "INSERT INTO users (name, email, password)
            VALUES ('$name', '$email', '$hashedPassword')";
            
    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    // Close the database connection.
    $conn->close();
}


registerUser('John Doe', 'john@example.com', 'password123');


function registerUser($data) {
  // Input Validation
  if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
    throw new Exception("Please fill in all required fields.");
  }

  $username = trim($data['username']);
  $email = trim($data['email']);
  $password = trim($data['password']);

  // Password Hashing
  require 'vendor/autoload.php';
  use Illuminate\Support\Str;
  $hashedPassword = bcrypt($password);

  // Database Connection
  $db_username = 'your_db_username'; // Replace with your actual database username
  $db_password = 'your_db_password'; // Replace with your actual database password
  $db_name = 'your_db_name'; // Replace with your actual database name

  try {
    // Create connection
    $conn = new mysqli($db_username, $db_password, '', $db_name);

    if ($conn->connect_error) {
      throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Query to create user record
    $sql = "INSERT INTO users (username, email, password)
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
      throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if (!$stmt->execute()) {
      throw new Exception("Execute failed: (" . $conn->errno . ") " . $conn->error);
    }

    // Close connection
    $stmt->close();
    $conn->close();

    return array('success' => true, 'message' => 'User created successfully');
  } catch (Exception $e) {
    return array('success' => false, 'message' => "Error creating user: " . $e->getMessage());
  }
}


$data = array(
  'username' => 'johnDoe',
  'email' => 'johndoe@example.com',
  'password' => 'mysecretpassword'
);

try {
  $result = registerUser($data);
  print_r($result); // Output: Array ( [success] => 1 [message] => User created successfully )
} catch (Exception $e) {
  echo "Error creating user: " . $e->getMessage();
}


<?php

// Configuration settings
define('DB_FILE', 'users.db');
define('TABLE_NAME', 'users');

// Connect to database
function connectToDatabase() {
  $conn = new PDO('sqlite:' . DB_FILE);
  return $conn;
}

// Create table if it doesn't exist
function createTable(PDO $conn) {
  $query = "CREATE TABLE IF NOT EXISTS " . TABLE_NAME . "
    (id INTEGER PRIMARY KEY AUTOINCREMENT,
     name TEXT NOT NULL,
     email TEXT UNIQUE NOT NULL,
     password TEXT NOT NULL)";
  $conn->exec($query);
}

// Register a new user
function registerUser(PDO $conn, array $userData) {
  // Check if the required fields are provided
  foreach (['name', 'email', 'password'] as $field) {
    if (!isset($userData[$field])) {
      throw new Exception("Missing field: $field");
    }
  }

  // Hash the password
  $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);

  // Insert user data into database
  try {
    $query = "INSERT INTO " . TABLE_NAME . "
              (name, email, password)
              VALUES (:name, :email, :password)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $userData['name']);
    $stmt->bindParam(':email', $userData['email']);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();
  } catch (PDOException $e) {
    throw new Exception("Failed to register user: " . $e->getMessage());
  }
}

// Example usage:
$conn = connectToDatabase();
createTable($conn);

$userData = [
  'name' => 'John Doe',
  'email' => 'john@example.com',
  'password' => 'mysecretpassword'
];

registerUser($conn, $userData);

?>


<?php

/**
 * User registration function.
 *
 * @param array $data User registration data (name, email, password)
 * @return bool True if registration is successful, false otherwise
 */
function registerUser(array $data): bool
{
    // Database connection details
    $dbHost = 'localhost';
    $dbName = 'users_db';
    $dbUsername = 'root';
    $dbPassword = '';

    try {
        // Connect to database
        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);

        // Prepare query for registration
        $stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');

        // Bind parameters
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute query
        if ($stmt->execute()) {
            // Registration successful
            return true;
        } else {
            // Error occurred during registration
            throw new Exception('Error registering user');
        }
    } catch (PDOException $e) {
        // Database connection error
        echo 'Database connection failed: ' . $e->getMessage();
    } catch (Exception $e) {
        // Registration error
        echo 'Registration error: ' . $e->getMessage();
    }

    // Registration failed
    return false;
}

?>


// User registration data
$userData = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'password123'
];

// Call the registerUser function
if (registerUser($userData)) {
    echo 'Registration successful!';
} else {
    echo 'Registration failed.';
}


/**
 * User Registration Function
 *
 * @param array $data User input data (name, email, password)
 * @return bool|string True on successful registration, error message on failure
 */
function registerUser(array $data) {
    // Input validation
    if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
        return 'Error: Invalid input data';
    }

    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return 'Error: Invalid email address';
    }

    // Hash password
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    // Connect to database (replace with your own connection method)
    global $db;
    $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        // Handle database errors
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}


// Sample user input data
$data = [
    'name' => 'John Doe',
    'email' => 'johndoe@example.com',
    'password' => 'strongpassword123'
];

// Call the registration function
$result = registerUser($data);

if ($result === true) {
    echo 'Registration successful!';
} else {
    echo $result;
}


function registerUser($firstName, $lastName, $email, $password) {
    // Connect to database (replace with your own connection code)
    $conn = mysqli_connect("localhost", "username", "password", "database");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Escape user input
    $firstName = mysqli_real_escape_string($conn, $firstName);
    $lastName = mysqli_real_escape_string($conn, $lastName);
    $email = mysqli_real_escape_string($conn, $email);

    // Check if email is already registered
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return array('success' => false, 'message' => 'Email already exists');
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $query = "INSERT INTO users (firstName, lastName, email, password)
              VALUES ('$firstName', '$lastName', '$email', '$passwordHash')";
    mysqli_query($conn, $query);

    return array('success' => true, 'message' => 'User created successfully');
}

// Example usage:
$userData = registerUser("John", "Doe", "john@example.com", "password123");
print_r($userData);


<?php
require_once 'db.php'; // assume you have a db.php file that connects to your database

// Define the fields for registration
$requiredFields = array('username', 'email', 'password');

// Check if all required fields are present
if (!array_diff_key($_POST, array_flip($requiredFields))) {
    echo "Error: Missing required fields";
    exit;
}

// Sanitize and validate input
$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Check if user already exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
$stmt->execute([':username' => $username, ':email' => $email]);
$result = $stmt->fetch();

if ($result) {
    echo "Error: User already exists";
    exit;
}

// Insert new user into database
$stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
$stmt->execute([':username' => $username, ':email' => $email, ':password' => $password]);

echo "User created successfully!";
?>


<form method="POST">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <label>Email:</label>
    <input type="email" name="email"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" value="Register">
</form>


<?php

function registerUser($username, $email, $password) {
    // Check if the username or email already exists in the database
    require_once 'db.php'; // include your database connection file
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($link, $query);
    
    if (mysqli_num_rows($result) > 0) {
        return array("error" => "Username or email already exists");
    }

    // Hash the password
    $passwordHash = hash('sha256', $password);

    // Insert new user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$passwordHash')";
    if (!mysqli_query($link, $query)) {
        return array("error" => "Error registering user");
    }

    // Return success message
    return array("message" => "User registered successfully!");
}

// Example usage:
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if ($result["error"]) {
    echo json_encode(array("error" => $result["error"]));
} else {
    echo json_encode($result);
}

?>


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = registerUser($username, $email, $password);

if ($result["error"]) {
    echo json_encode(array("error" => $result["error"]));
} else {
    echo json_encode($result);
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


function registerUser($name, $email, $password) {
  // Validate input data
  if (empty($name) || empty($email) || empty($password)) {
    throw new Exception("All fields are required.");
  }

  try {
    // Check for existing user with same email address
    $existingUser = fetchUserByEmail($email);
    if ($existingUser) {
      throw new Exception("Email address already exists.");
    }

    // Hash password using PHP's built-in hash function (e.g. bcrypt)
    $hashedPassword = hash('sha256', $password);

    // Create a new user object and save to database
    $user = array(
      'name' => $name,
      'email' => $email,
      'password' => $hashedPassword
    );
    createUser($user);

    return true; // User successfully registered

  } catch (Exception $e) {
    echo "Error registering user: " . $e->getMessage();
    return false;
  }
}


function fetchUserByEmail($email) {
  global $db;

  $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute(array($email));

  if ($user = $stmt->fetch()) {
    return true; // User exists with given email address
  } else {
    return false;
  }
}

function createUser($user) {
  global $db;

  $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
  $stmt->execute(array(
    $user['name'],
    $user['email'],
    $user['password']
  ));
}


try {
  registerUser('John Doe', 'john@example.com', 'password123');
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}


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

