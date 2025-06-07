
<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    // Check if email and password are provided
    if (empty($email) || empty($password)) {
        die("Please fill in all required fields.");
    }

    try {
        // Prepare SQL statement to select user with the given email
        $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        // Check if user exists
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            die("Invalid email or password.");
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            die("Invalid email or password.");
        }

        // Start session
        session_start();
        
        // Set session variables
        $_SESSION['id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['logged_in'] = true;

        // Redirect to dashboard after successful login
        header("Location: dashboard.php");
        exit();

    } catch (PDOException $e) {
        die("Error processing your request: " . $e->getMessage());
    }
}

// Display login form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .login-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .login-container h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
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
    <div class="login-container">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter your email" name="email" required>
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter your password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();

// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'your_database';

// Connect to database
$conn = mysqli_connect($host, $user, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "Please fill in all fields.";
        exit();
    }

    // Prepare and execute query
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];

            // Optional: set remember me cookie
            if (!empty($_POST['remember'])) {
                $remember_token = bin2hex(random_bytes(32));
                setcookie('remember_token', $remember_token, time() + 3600 * 24 * 7, '/', null, false, true);
            }

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Username not found.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
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

        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
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
    <div class="login-container">
        <h2>Login</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <input type="text" placeholder="Enter username" name="username">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter password" name="password">
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="remember"> Remember me</label>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();

// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$db_name = "user_login";

// Connect to database
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Prepare and bind statement
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User exists
        $row = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct
            
            // Store session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $row['id'];
            $_SESSION["email"] = $row['email'];
            
            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            echo "<div class='error'>Invalid email or password</div>";
        }
    } else {
        // User doesn't exist
        echo "<div class='error'>Invalid email or password</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
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

        .login-form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type='text'], input[type='password'] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="text" placeholder="Enter your email..." name="email" required>
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter your password..." name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>


<?php
// Include database configuration file
require_once("config/db.php");

function user_login($username, $password) {
    try {
        // Prepare SQL statement to select user with matching username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bindParam(1, $username);
        $stmt->execute();

        // Check if exactly one row was found
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password using PHP's built-in password_verify function
            if (password_verify($password, $row['password'])) {
                // Password matches
                return true;
            } else {
                // Incorrect password
                return false;
            }
        } else {
            // No user found with this username
            return false;
        }
    } catch(PDOException $e) {
        // Error occurred
        echo "Error: " . $e->getMessage();
        return false;
    }
}
?>


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (user_login($username, $password)) {
        // Start session or set cookies here
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}


<?php
// Database connection details
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$database_name = 'login_system';

// Create database connection
$conn = mysqli_connect($host, $username_db, $password_db, $database_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function loginUser($username, $password) {
    global $conn;

    // Sanitize input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if username exists in database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        return array('status' => 'error', 'message' => 'Account does not exist!');
    }

    // Get user data
    $user_data = mysqli_fetch_assoc($result);
    
    // Verify password
    if (hash_equals($user_data['password'], hash('sha256', $password))) {
        // Password is correct, create session
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['session_id'] = mysqli_real_escape_string($conn, session_id());
        
        // Generate a random token for security
        $token = bin_rand(32);
        setcookie('auth_token', $token, time() + 3600 * 24 * 30, '/', '', false, true); // Store cookie for 30 days
        
        // Update database with new token and session ID
        $update_query = "UPDATE users SET session_id = '".$_SESSION['session_id']."', auth_token = '$token' WHERE username = '$username'";
        mysqli_query($conn, $update_query);
        
        return array('status' => 'success', 'message' => 'Login successful!');
    } else {
        // Password is incorrect
        return array('status' => 'error', 'message' => 'Incorrect password!');
    }
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $result = loginUser($username, $password);
    
    if ($result['status'] == 'success') {
        echo "Welcome, " . $_SESSION['username'] . "!";
    } else {
        echo $result['message'];
    }
}
?>


<?php
// This code assumes you have a database connection established
session_start();

function login($username, $password, $conn) {
    // Check if username or password is empty
    if (empty($username) || empty($password)) {
        return "Username or Password cannot be empty.";
    }
    
    // Query the database for the user
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        return "Username not found.";
    }
    
    // Get user data
    $user = $result->fetch_assoc();
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        return "Incorrect Password.";
    }
    
    // Start session and store user data
    $_SESSION['id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    
    return "Login successful!";
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $result = login($username, $password, $conn);
    
    if ($result == "Login successful!") {
        header("Location: dashboard.php");
        exit();
    } else {
        echo $result;
    }
}
?>


$password = password_hash($_POST['password'], PASSWORD_DEFAULT);


if (!isset($_COOKIE['username'])) {
    $remember = $_POST['remember'];
    
    if ($remember) {
        setcookie('username', $username, time() + 3600); // Cookie valid for 1 hour
    }
}


<?php
// sanitize_input.php

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <?php
    // Configuration
    include_once('config.php');

    if (isset($_POST['submit'])) {
        $username = sanitizeInput($_POST['username']);
        $password = $_POST['password'];
        
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Prevent SQL Injection
        $username = mysqli_real_escape_string($conn, $username);
        
        $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $db_password = $row['password'];
            
            // Verify password
            if (md5($password) == $db_password) {
                session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                
                // Remember me cookie
                setcookie("stayLoggedIn", "yes", time() + 3600*24*7, "/");
                
                header('Location: dashboard.php');
                exit();
            } else {
                echo "<div style='color:red'>Incorrect username or password!</div>";
            }
        } else {
            echo "<div style='color:red'>User doesn't exist!</div>";
        }
        
        mysqli_close($conn);
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        Remember Me: <input type="checkbox" name="remember_me"><br>
        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'login_system';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle login form submission
if (isset($_POST['submit'])) {
    // Sanitize input data
    $email = htmlspecialchars(trim($_POST['email']));
    $pass = htmlspecialchars(trim($_POST['password']));

    // Check if inputs are not empty
    if ($email == "" || $pass == "") {
        $_SESSION['message'] = "Email and password are required";
        header("Location: login.php");
        exit();
    }

    // SQL injection prevention
    $email = mysqli_real_escape_string($conn, $email);
    $pass = mysqli_real_escape_string($conn, $pass);

    // Query the database for user with matching email
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $_SESSION['message'] = "Error querying database";
        header("Location: login.php");
        exit();
    }

    // Check if user exists
    if (mysqli_num_rows($result) == 0) {
        $_SESSION['message'] = "User not found. Please check your email.";
        header("Location: login.php");
        exit();
    }

    $user_data = mysqli_fetch_assoc($result);

    // Verify password
    if (!password_verify($pass, $user_data['password'])) {
        $_SESSION['message'] = "Incorrect password";
        header("Location: login.php");
        exit();
    }

    // Set session variables
    $_SESSION['id'] = $user_data['id'];
    $_SESSION['email'] = $user_data['email'];
    $_SESSION['username'] = $user_data['username'];
    $_SESSION['logged_in'] = true;

    // Optional: Check if user has exceeded login attempts
    if ($user_data['login_attempts'] >= 3) {
        $_SESSION['message'] = "Account locked. Please contact support.";
        header("Location: login.php");
        exit();
    }

    // Reset login attempts and update last login time
    $reset_login_attempts = "UPDATE users SET login_attempts = 0 WHERE id = {$user_data['id']}";
    mysqli_query($conn, $reset_login_attempts);

    // Redirect to dashboard after successful login
    $_SESSION['message'] = "Welcome back, " . $user_data['username'];
    header("Location: dashboard.php");
    exit();
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 10px;
            padding: 10px;
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            color: #6c6c6c;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Display message if exists
        if (isset($_SESSION['message'])) {
            echo "<div class='message'>";
            echo $_SESSION['message'];
            echo "</div>";
            unset($_SESSION['message']);
        }
        ?>

        <h2>Login</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password">
            </div>

            <button type="submit" name="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$db_name = 'your_database';

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Session start
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Query to check username and password
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        
        // Execute the query
        $stmt->execute();
        
        // Store result
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Start session and store user data
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Incorrect password!";
            }
        } else {
            echo "Username not found!";
        }
        
        // Close statement
        $stmt->close();
    }

    // Close connection
    $conn->close();
}
?>


$hashed_password = password_hash($password, PASSWORD_DEFAULT);


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $loginEmailOrUsername = $_POST['email_or_username'];
        $loginPassword = $_POST['password'];

        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $loginEmailOrUsername);
        $stmt->bindParam(':email', $loginEmailOrUsername);
        $stmt->execute();

        // Check if user exists
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($loginPassword, $user['password'])) {
                // Password is correct, start session
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                // Redirect to dashboard or other page
                header("Location: dashboard.php");
                die();
            } else {
                // Password is incorrect
                echo "Incorrect password!";
            }
        } else {
            // User not found
            echo "User not found!";
        }
    }

} catch(PDOException $e) {
    // Handle any database errors
    echo "Error: " . $e->getMessage();
}

// Close the connection
$conn = null;
?>


<?php
session_start();
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "Please fill in all fields";
        exit();
    }

    // Sanitize the input
    $email = htmlspecialchars(strip_tags(trim($email)));

    try {
        // Prepare a statement
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        // Check if user exists
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            echo "User does not exist";
            exit();
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            echo "Incorrect password";
            exit();
        }

        // If login is successful, create a session
        $_SESSION['id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];

        // Optional: Set a cookie for the user to stay logged in
        setcookie('remember_me', $user['id'], time() + 3600); 

        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        die("Could not connect to the database " . $e->getMessage());
    }
}
?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$username_db = 'root'; // Change to your database username
$password_db = '';     // Change to your database password
$db_name = 'testdb';

// Connect to the database
$conn = mysqli_connect($host, $username_db, $password_db, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";

if (isset($_POST['submit'])) {
    // Sanitize inputs
    $username_email = mysqli_real_escape_string($conn, $_POST['username_email']);
    $password = md5($_POST['password']); // Hash the password

    // Check if either username or email is provided
    $sql = "SELECT * FROM users WHERE username='$username_email' OR email='$username_email'";
    
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    $row = mysqli_fetch_assoc($result);
    
    // Check if account exists
    if (mysqli_num_rows($result) == 1) {
        // Verify password
        if ($password == $row['password']) {
            // Start session and store user data
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            
            // Handle Remember Me functionality
            if (isset($_POST['remember_me'])) {
                $token = bin2hex(random_bytes(32)); // Generate random token
                $cookie_expire = time() + 60 * 60 * 24 * 30; // Cookie expires in 30 days
                
                // Update the user's token in database
                $update_sql = "UPDATE users SET remember_token='$token' WHERE id=" . $_SESSION['id'];
                mysqli_query($conn, $update_sql);
                
                // Set cookies
                setcookie('username', $row['username'], $cookie_expire, '/');
                setcookie('remember_token', $token, $cookie_expire, '/');
            }
            
            // Redirect to dashboard or welcome page
            header("Location: welcome.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Username/Email not found! Please register.";
    }
}

mysqli_close($conn);
?>


<?php
session_start();
include('db_connection.php'); // Include your database connection file

function loginUser($usernameOrEmail, $password) {
    global $conn;

    // Sanitize input to prevent SQL injection
    $usernameOrEmail = filter_var($usernameOrEmail, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    // Check if username or email exists in the database
    $query = "SELECT * FROM users WHERE username = '$usernameOrEmail' OR email = '$usernameOrEmail'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct

            // Set session variables
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role']; // Assuming you have a role column for access control

            return true;
        } else {
            // Password is incorrect
            return "Invalid password";
        }
    } else {
        // Username or email not found
        return "Username or email not found";
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usernameOrEmail = $_POST['username_or_email'];
    $password = $_POST['password'];

    $loginResult = loginUser($usernameOrEmail, $password);

    if ($loginResult === true) {
        header("Location: dashboard.php"); // Redirect to dashboard
        exit();
    } else {
        echo $loginResult; // Display error message
    }
}
?>


// Add this after successful login
if (isset($_POST['remember'])) {
    // Set expiration time for 1 month
    $expire = time() + 30 * 24 * 60 * 60;

    // Generate a random token
    $token = bin2hex(random_bytes(32));

    // Store the token in cookies
    setcookie('remember_token', $token, $expire);

    // Store the token in database
    $userId = $_SESSION['id'];
    $query = "UPDATE users SET remember_token='$token' WHERE id='$userId'";
    mysqli_query($conn, $query);
}


<?php
// Database configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$database = 'test';

// Connect to the database
$conn = new mysqli($host, $username_db, $password_db, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session
session_start();

// Login function
function loginUser() {
    global $conn;

    // Check if form is submitted
    if (isset($_POST['login'])) {
        // Sanitize input data
        $username_email = mysqli_real_escape_string($conn, $_POST['username_email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Validate input data
        if (empty($username_email) || empty($password)) {
            $_SESSION['message'] = "Please fill in all fields!";
            header("Location: login.php");
            exit();
        }

        // Query the database for user with provided username or email
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
        $stmt->bind_param("ss", $username_email, $username_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Create session variables
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['logged_in'] = true;
                $_SESSION['login_time'] = time();

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                $_SESSION['message'] = "Incorrect password!";
                header("Location: login.php");
                exit();
            }
        } else {
            // User not found
            $_SESSION['message'] = "Username or email does not exist!";
            header("Location: login.php");
            exit();
        }

        $stmt->close();
    }
}

// Close database connection
$conn->close();
?>


<?php
// Database configuration
$host = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "mydatabase";

// Connect to the database
$conn = mysqli_connect($host, $db_username, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session
session_start();

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and trim input values
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Prepare the SQL statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM users WHERE username = ?");
    
    if ($stmt === false) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "s", $username);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Store result
    $result = mysqli_stmt_get_result($stmt);

    // Check if a user was found
    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            $error_message = "Incorrect username or password";
        }
    } else {
        // User not found
        $error_message = "Incorrect username or password";
    }

    mysqli_stmt_close($stmt);
}

// Close database connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        
        <?php
        if (isset($error_message)) {
            echo "<div class='error-message'>$error_message</div>";
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="text" placeholder="Enter your username" name="username" required>
            </div>

            <div class="form-group">
                <input type="password" placeholder="Enter your password" name="password" required>
            </div>

            <button type="submit">Login</button>
        </form>

        <p style="text-align: center; margin-top: 20px;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</body>
</html>


<?php
// Database configuration and connection code here...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and trim input values
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)");

    if ($stmt === false) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        echo "Registration successful! Please login.";
        header("Location: login.php");
        exit();
    } else {
        die("Registration failed: " . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);
}
?>


<?php
// Start the session
session_start();

// Database configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'my_database';

// Connect to the database
$conn = mysqli_connect($host, $db_username, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Query to check if username exists
    $sql = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        // User exists, fetch the user data
        $row = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct, start session
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];
            $_SESSION['start'] = time();
            
            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            $error_message = "Incorrect username or password";
        }
    } else {
        // Username does not exist
        $error_message = "Incorrect username or password";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>


if (isset($_POST['remember'])) {
    $cookie_username = $_POST['username'];
    setcookie("username", $cookie_username, time() + 3600); // Cookie expires after 1 hour
}


<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['role']);
session_unset();
session_destroy();
header("Location: login.php");
exit();
?>


<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    // Database connection parameters
    $host = 'localhost';
    $db_username = 'root';  // Change to your DB username
    $db_password = '';      // Change to your DB password
    $database = 'login_system';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$database", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare statement to select user
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Start session and set user data
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['id'] = $user['id'];

                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Incorrect password";
            }
        } else {
            $error = "Username not found";
        }

    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// If no error, redirect or show form again
if (!isset($error)) {
    header("Location: login_form.html");
} else {
    // Include the form with error message
    include('login_form.html');
}
?>


<?php
// Database configuration
$host = "localhost";
$username_db = "root"; // Replace with your database username
$password_db = "";     // Replace with your database password
$db_name = "user_login";

// Connect to the database
$conn = mysqli_connect($host, $username_db, $password_db, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function login_user() {
    global $conn;

    // Check if form is submitted
    if (isset($_POST['login_submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate input
        if (empty($email) || empty($password)) {
            return "Please fill in all fields!";
        }

        // Sanitize inputs to prevent SQL injection
        $email = trim(htmlspecialchars(stripslashes($email)));
        $password = trim(htmlspecialchars(stripslashes($password)));

        // Check if email exists in the database
        $query = "SELECT * FROM users WHERE email = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);

        mysqli_execute($stmt);
        $result = mysqli_get_result($stmt);

        // Check if user exists
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                session_start();
                
                // Store user data in session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['login_time'] = time();

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                return "Incorrect password!";
            }
        } else {
            return "User with this email doesn't exist!";
        }
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
session_start();
require_once('db_connect.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    if (empty($username) && empty($email)) {
        die("Please enter username or email");
    }
    if (empty($password)) {
        die("Please enter password");
    }

    // Connect to database
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the query
    $input = !empty($username) ? $username : $email;
    $column = !empty($username) ? 'username' : 'email';
    
    $sql = "SELECT * FROM users WHERE $column = '" . mysqli_real_escape_string($conn, $input) . "'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        die("Username or email is incorrect");
    }

    $user = mysqli_fetch_assoc($result);
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        die("Password is incorrect");
    }

    // Start session and store user data
    session_regenerate(true); // Prevent session fixation
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];

    // Close the database connection
    mysqli_close($conn);

    // Redirect to dashboard or home page
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p>{$_SESSION['message']}</p>";
        unset($_SESSION['message']);
    }
    ?>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Username or Email: <input type="text" name="username" placeholder="Enter username or email"><br>
        Password: <input type="password" name="password" placeholder="Enter password"><br>
        <input type="submit" value="Login">
    </form>

    <a href="register.php">Create Account</a> |
    <a href="reset_password.php">Forgot Password?</a>
</body>
</html>


<?php
session_start();
include('db_connection.php'); // Include your database connection file

function user_login() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = mysqli_real_escape_string($GLOBALS['conn'], $_POST['username']);
        $password = $_POST['password'];

        // Check if username exists
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        
        if (mysqli_num_rows($result) == 0) {
            echo "<script>alert('Account does not exist!');</script>";
            return;
        }

        // Get user details
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            echo "<script>alert('Incorrect password!');</script>";
            return;
        }

        // On successful login
        $_SESSION['username'] = $username;
        $_SESSION['last_activity'] = time(); // Session timeout implementation

        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    }
}

// Call the function when form is submitted
if (isset($_POST['login'])) {
    user_login();
}
?>


<?php
// Database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$dbuser = 'your_database_user';
$dbpass = 'your_database_password';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$ dbname", $dbuser, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function loginUser($username, $password) {
        global $conn;

        // Check if username and password are provided
        if (empty($username) || empty($password)) {
            return "Username and password are required!";
        }

        try {
            // Prepare the query to prevent SQL injection
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Password is correct
                    session_start();
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $username;
                    
                    // Redirect to dashboard or home page
                    header("Location: dashboard.php");
                    exit();
                } else {
                    return "Incorrect password!";
                }
            } else {
                return "Username not found!";
            }

        } catch (PDOException $e) {
            return "Database error occurred: " . $e->getMessage();
        }
    }

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


// In your login form processing file (e.g., login.php)
session_start();
include 'database_connection.php'; // Include the above code

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = loginUser($username, $password);

    if ($result === true) {
        // Login successful
        header("Location: dashboard.php");
        exit();
    } else {
        // Display error message
        echo $result;
    }
}


<?php
// Database configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'login_system';

// Connect to database
$conn = mysqli_connect($host, $db_username, $db_password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']); // Hash password

    // Check if username exists in database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($row['password'] == $password) {
            // Password matches
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            // Password doesn't match
            $error_message = "Invalid username or password!";
        }
    } else {
        // Username doesn't exist
        $error_message = "Invalid username or password!";
    }
}

// Close database connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>

    <a href="register.php">Create an account</a>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'login_system';

// Connect to database
$conn = mysqli_connect($host, $db_username, $db_password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $username = $conn->real_escape_string($_POST['username']);
    
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify password using PHP's built-in password verification function
        if (password_verify($_POST['password'], $row['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Invalid username or password!";
        }
    } else {
        $error_message = "Invalid username or password!";
    }
}

// Close database connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>

    <a href="register.php">Create an account</a>
</body>
</html>


// When registering a user:
$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
// Store $password_hash in your database


<?php
session_start();
require_once 'db_connection.php'; // Include your database connection file

function user_login($username, $password) {
    global $conn;

    if (!isset($_POST['login'])) {
        return false;
    }

    // Sanitize input
    $username = htmlspecialchars(trim($username));
    $password = htmlspecialchars(trim($password));

    // Check if username and password are not empty
    if (empty($username) || empty($password)) {
        return "Username or password cannot be empty!";
    }

    try {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return "Invalid credentials!";
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            return "Invalid credentials!";
        }

        // Start session and store user data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['logged_in'] = true;

        return "Login successful!";

    } catch (PDOException $e) {
        // Handle any database errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = user_login($username, $password);

    if ($result === "Login successful!") {
        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    } else {
        // Display error message
        echo $result;
    }
}
?>


<?php
session_start();
try {
    // Database connection configuration
    $host = 'localhost';
    $dbname = 'your_database_name';
    $username = 'your_username';
    $password = 'your_password';

    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input data
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        // Prepare SQL statement to select user by username
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        // Check if a user exists
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Password is correct, start session and set session variables
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                // Secure the session
                ini_set('session.cookie_httponly', '1');
                if (isset($_SERVER['HTTPS'])) {
                    ini_set('session.cookie_secure', '1');
                }
                
                // Redirect to dashboard or other page after login
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                $error = "Invalid username or password";
            }
        } else {
            // No user found with that username
            $error = "Invalid username or password";
        }
    }

} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// If no post data, show login form
if (!isset($_POST)) {
    header("Location: login_form.php");
    exit();
}
?>


<?php
// Configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'login_example';

// Connect to MySQL database
$conn = mysqli_connect($host, $db_username, $db_password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

if (isset($_POST['submit'])) {
    // Sanitize input data
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    
    // Validate username and password
    if (empty($username) || empty($password)) {
        $error = "Username and password are required!";
    } else {
        // Query the database for user
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Start session and set session variables
                $_SESSION['username'] = $username;
                
                // Remember me functionality
                if ($_POST['remember_me']) {
                    $cookie_name = 'login_cookie';
                    $cookie_value = $username . '-' . md5($password);
                    setcookie($cookie_name, $cookie_value, time() + (7 * 24 * 60 * 60)); // Expiry in a week
                }
                
                // Redirect to dashboard or home page
                header('Location: dashboard.php');
                exit();
            } else {
                $error = "Invalid username or password!";
            }
        } else {
            $error = "Username not found!";
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <style>
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        form {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h2>Login</h2>
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username"><br>
            
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password"><br><br>
            
            <label><input type="checkbox" name="remember_me"> Remember Me</label><br><br>
            
            <button type="submit" name="submit">Login</button>
        </form><br>
        
        <p>Don't have an account? <a href="register.php">Register here.</a></p>
    </div>
</body>
</html>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username = 'root'; // Database username
$password = '';      // Database password
$dbname = 'login_system';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function for user login
function loginUser() {
    global $conn;

    // Check if form is submitted
    if (isset($_POST['login'])) {
        $email_username = $_POST['email_username'];
        $password = $_POST['password'];

        // Validate input
        if (empty($email_username) || empty($password)) {
            $_SESSION['error'] = "Please fill in all fields";
            header("Location: login.php");
            exit();
        }

        // Prepare and bind statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, "SELECT id, username, email, password FROM users WHERE username = ? OR email = ?");
        mysqli_stmt_bind_param($stmt, "ss", $email_username, $email_username);

        // Execute the query
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Start session and store user data
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];

                // Login successful
                $_SESSION['success'] = "Welcome back, " . $row['username'];
                header("Location: dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "Incorrect password";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "User does not exist";
            header("Location: login.php");
            exit();
        }
    }
}

// Call the login function
loginUser();

// Close database connection
mysqli_close($conn);
?>


<?php
// Start session
session_start();

// Database connection (replace with your database credentials)
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'user_login';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['login'])) {
    // Get user input
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    // Hash password for security
    $hashed_password = hash('sha256', $password);

    // Query to check if username exists in database
    $sql = "SELECT * FROM users WHERE username = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get user details
        $user_data = $result->fetch_assoc();

        // Compare hashed passwords
        if (hash('sha256', $password) === $user_data['password']) {
            // Login successful, start session and redirect
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['id'] = $user_data['id'];

            header("Location: welcome.php");
            exit();
        } else {
            // Password does not match
            echo "<script>alert('Incorrect password!');</script>";
        }
    } else {
        // Username does not exist
        echo "<script>alert('Username does not exist!');</script>";
    }

    $stmt->close();
}

$conn->close();

// Display login form if not logged in
if (!isset($_SESSION['loggedin'])) {
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Page</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <h2 class="text-center mb-4">Login</h2>
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
    </html>
<?php
}
?>



<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Welcome <?php echo $_SESSION['username']; ?>!</h2>
        <a href="logout.php" class="btn btn-primary">Logout</a>
    </div>
</body>
</html>


<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: login.php");
exit();
?>


<?php
session_start();

// Database configuration
$host = "localhost";
$user = "username";
$password = "password";
$db_name = "database_name";

// Connect to database
$conn = new mysqli($host, $user, $password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password, $conn) {
    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        return array(
            'status' => false,
            'message' => "Username and password are required!"
        );
    }

    try {
        // Prepare statement to select user data
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        // Get result
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verify password (password_hash should be used in production)
            $hashed_password = hash('sha256', $password);
            
            if ($user['password'] == $hashed_password) {
                // Password is correct
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                return array(
                    'status' => true,
                    'message' => "Login successful!"
                );
            } else {
                // Incorrect password
                return array(
                    'status' => false,
                    'message' => "Invalid username or password!"
                );
            }
        } else {
            // User not found
            return array(
                'status' => false,
                'message' => "Invalid username or password!"
            );
        }
    } catch (Exception $e) {
        // Error occurred
        return array(
            'status' => false,
            'message' => "An error occurred: " . $e->getMessage()
        );
    }
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Call login function
    $result = login_user($username, $password, $conn);
    
    if ($result['status']) {
        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    } else {
        echo $result['message'];
    }
}

// Close database connection
$conn->close();
?>


<?php
// Include database configuration file
include('config.php');

function loginUser() {
    // Check if form is submitted
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            // Prepare SQL statement to select user based on username
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            // Check if user exists
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Password is correct, start session and redirect to dashboard
                    session_start();
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['logged_in'] = true;
                    
                    // Optional: Set remember me cookie
                    if (isset($_POST['remember'])) {
                        setcookie('username', $username, time() + 3600 * 24 * 7, '/');
                        setcookie('password', $password, time() + 3600 * 24 * 7, '/');
                    }
                    
                    // Redirect to dashboard
                    header("Location: dashboard.php");
                    exit();
                } else {
                    // Password is incorrect
                    echo "Incorrect password!";
                }
            } else {
                // User does not exist
                echo "Username does not exist!";
            }
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}

// Create login form HTML
function loginForm() {
    ?>
    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="checkbox" name="remember"> Remember me
        <button type="submit">Login</button>
    </form>
    <?php
}

// Main login function
function main() {
    // Show login form if not logged in
    session_start();
    
    if (!isset($_SESSION['logged_in'])) {
        loginUser();
        loginForm();
    } else {
        header("Location: dashboard.php");
        exit();
    }
}

main();
?>


$hashed_password = password_hash($password, PASSWORD_DEFAULT);
// Store $hashed_password in database


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database';
$username = 'your_username';
$password = 'your_password';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $username_login = $_POST['username'];
        $password_login = $_POST['password'];

        // Prepare and execute SQL statement
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username_login);
        $stmt->execute();

        // Fetch user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password_login, $user['password'])) {
                // Start session and store user data
                session_start();
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                
                // Optional: Set remember me cookie
                if (!empty($_POST['remember_me'])) {
                    $cookie_name = 'login_cookie';
                    $cookie_value = $username_login . '|' . md5($password_login);
                    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
                }

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Invalid username or password";
            }
        } else {
            echo "Invalid username or password";
        }
    }
} catch(PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <!-- Add security headers -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'">
    <meta http-equiv="X-Content-Type" content="nosniff">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="checkbox" id="remember_me" name="remember_me">
            <label for="remember_me">Remember me</label>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>


<?php
session_start();

// Database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Connect to database using PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input and sanitize them
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Prevent SQL injection by using prepared statements
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user session data
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <!-- Add your CSS styles here -->
    <style>
        .login-form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <?php if (isset($error)) { ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php } ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>


// Set session cookie parameters
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Only use sessions over HTTPS
ini_set('session.use_strict_mode', 1);


<?php
session_start();
unset($_SESSION['loggedin']);
unset($_SESSION['id']);
unset($_SESSION['username']);
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>


<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit();
}

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'mydatabase';

// Connect to database
$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = "";
$password = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Function to sanitize input
    function sanitize_input($data) {
        return htmlspecialchars(mysqli_real_escape_string($conn, trim($data)));
    }

    // Get username and password from form
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error_message = "Username or password cannot be empty!";
    } else {
        // Prepare query to select user with given username
        $stmt = $conn->prepare("SELECT id, username, password, admin FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Get user data
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                $_SESSION['username'] = $user['username'];
                $_SESSION['admin'] = $user['admin'];
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                $error_message = "Invalid username or password!";
            }
        } else {
            // No user found with this username
            $error_message = "Invalid username or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .login-form {
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <?php if (!empty($error_message)) { ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php } ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" value="<?php echo $password; ?>">
            </div>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>


$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
// Insert into database using prepared statement


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create a database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Function to handle user login
function loginUser($username, $password, $conn) {
    try {
        // Prepare and execute SQL statement to select user with given username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        // Fetch the result
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['userid'] = $user['id'];
                
                return true;
            } else {
                // Incorrect password
                return false;
            }
        } else {
            // User does not exist
            return false;
        }
    } catch(PDOException $e) {
        die("An error occurred: " . $e->getMessage());
    }
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Call login function
    if (loginUser($username, $password, $conn)) {
        header("Location: dashboard.php");
        die();
    } else {
        echo "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login Page</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($host, $username_db, $password_db, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;

    // Get user input
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Sanitize the email
    $email = mysqli_real_escape_string($conn, $email);

    // Check if user exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows == 0) {
        return "User not found!";
    }

    // Get the user data
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
        // Create session variables
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['admin'] = $user['is_admin'];

        return "Login successful!";
    } else {
        return "Incorrect password!";
    }
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Call the login function
        $login_result = login_user();
        
        if ($login_result === "Login successful!") {
            header("Location: dashboard.php");
            exit();
        } else {
            echo $login_result;
        }
    } catch (Exception $e) {
        die("An error occurred: " . $e->getMessage());
    }
}
?>


<?php
function register_user() {
    global $conn;

    // Get user input
    $username = htmlspecialchars($_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return "Email already exists!";
    }

    // Insert new user into the database
    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$hashed_password')";
            
    if ($conn->query($sql)) {
        return "Registration successful! You can now login.";
    } else {
        return "Registration failed. Please try again.";
    }
}
?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'username';
$password = 'password';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Sanitize input (optional but recommended)
        $email = htmlspecialchars(trim($email));
        $password = htmlspecialchars(trim($password));

        // Prepare SQL statement to check user credentials
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct; start session and set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                header("Location: login.php?error=incorrect_credentials");
                exit();
            }
        } else {
            // User not found
            header("Location: login.php?error=incorrect_credentials");
            exit();
        }
    } else {
        // If form was not submitted, redirect to login page
        header("Location: login.php");
        exit();
    }

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


// When registering a new user:
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
// Store $hashed_password in the database


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function login_user() {
    global $conn;

    // Get form data
    if (isset($_POST['login'])) {
        $username = trim(htmlspecialchars(mysqli_real_escape_string($conn, $_POST['username'])));
        $password = trim(htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password'])));

        // Check if username exists in the database
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Password is correct
                session_start();
                
                // Set session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $row['id'];
                
                // Generate new token to prevent session hijacking
                $_SESSION['token'] = bin2hex(random_bytes(32));
                
                // Unset cookies
                setcookie('PHPSESSID', '', time() - 3600, '/');
                session_regenerate(true);
                
                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                $error = "Invalid username or password!";
                echo $error;
            }
        } else {
            // Username does not exist
            $error = "Invalid username or password!";
            echo $error;
        }
    }
}

// Call the login function
login_user();

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    
    <?php 
    if (isset($_GET['error'])) {
        echo "<p style='color: red;'>".$_GET['error']."</p>";
    }
    ?>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input
        $username = htmlspecialchars(trim($_POST['username']));
        $password = htmlspecialchars(trim($_POST['password']));

        // Check if both fields are filled
        if (empty($username) || empty($password)) {
            die("Username and password are required!");
        }

        // Prepare statement to select user from database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        // Fetch the result
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and password matches
        if ($user && password_verify($password, $user['password'])) {
            // Start session
            session_start();
            
            // Store user data in session
            $_SESSION['username'] = $user['username'];
            $_SESSION['id'] = $user['id'];

            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Incorrect username or password
            echo "Incorrect username or password!";
        }
    }

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Username:<br>
        <input type="text" name="username">
        <br>
        Password:<br>
        <input type="password" name="password">
        <br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'user_login';

// Sanitize input data
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    // Connect to the database
    $conn = new mysqli($host, $username_db, $password_db, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prevent SQL injection by using prepared statements
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user_data['password'])) {
            // Start session and set session variables
            session_start();
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['user_id'] = $user_data['id'];
            
            // Regenerate session ID to prevent session fixation attacks
            session_regenerate(true);
            
            // Set CSRF token for security
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            
            // Redirect to dashboard or home page after successful login
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            echo "Invalid username or password!";
        }
    } else {
        // Username does not exist
        echo "Invalid username or password!";
    }

    $stmt->close();
    $conn->close();
}
?>


<?php
session_start();

// Database connection
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'user_login';

$conn = new mysqli($host, $username_db, $password_db, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Register function
function registerUser($name, $email, $username, $password, $conn) {
    // Sanitize inputs
    $name = sanitizeInput($name);
    $email = sanitizeInput($email);
    $username = sanitizeInput($username);
    $password = sanitizeInput($password);

    // Validate inputs
    if (empty($name) || empty($email) || empty($username) || empty($password)) {
        return "All fields are required!";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format!";
    }

    // Check if username or email already exists
    $checkQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Username or email already exists!";
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $insertQuery = "INSERT INTO users (name, email, username, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssss", $name, $email, $username, $hashedPassword);

    if ($stmt->execute()) {
        return "Registration successful! You can now login.";
    } else {
        return "Error registering user!";
    }
}

// Login function
function loginUser($emailOrUsername, $password, $conn) {
    // Sanitize inputs
    $emailOrUsername = sanitizeInput($emailOrUsername);
    $password = sanitizeInput($password);

    // Validate inputs
    if (empty($emailOrUsername) || empty($password)) {
        return "Please fill in all fields!";
    }

    // Check if email or username exists
    $selectQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($selectQuery);
    $stmt->bind_param("ss", $emailOrUsername, $emailOrUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Check if user is active
            if ($user['status'] == 'active') {
                // Start session and store user data
                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
                
                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                return "Your account has been disabled!";
            }
        } else {
            return "Incorrect password!";
        }
    } else {
        return "Username or email does not exist!";
    }
}

// Logout function
function logoutUser() {
    // Unset all session variables
    session_unset();
    
    // Destroy the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    header("Location: login.php");
    exit();
}
?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'test';

// Connect to database
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Handle login form submission
if (isset($_POST['login'])) {
    // Get and sanitize input data
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        // Verify password
        if (password_verify($password, $hashedPassword)) {
            // Password is correct
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];

            // Set cookie for remember me functionality
            $cookie_name = "login";
            $cookie_value = serialize(array(
                'email' => $row['email'],
                'id' => $row['id']
            ));
            setcookie($cookie_name, $cookie_value, time() + 3600 * 24 * 7); // Cookie expires after a week

            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            echo "Incorrect password!";
        }
    } else {
        // User does not exist
        echo "User does not exist!";
    }

    $stmt->close();
}

// Close database connection
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <h2>Login Form</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" name="login" value="Login">
    </form>
</body>
</html>


<?php
session_start();
// Unset all session variables
$_SESSION = array();

// If using cookies, delete them as well
if (isset($_COOKIE['login'])) {
    setcookie('login', '', time() - 3600);
}

// Destroy the session
session_destroy();
header("Location: login.php");
exit();
?>


<?php
// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'my_database';

// Function to connect to the database
function loginDB() {
    global $host, $user, $password, $database;
    
    // Create connection
    $conn = new mysqli($host, $user, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to login user
function loginUser($username, $password) {
    global $host, $user, $password, $database;
    
    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        return false;
    }
    
    // Connect to database
    $conn = loginDB();
    
    // SQL injection prevention using mysqli_real_escape_string()
    $username = $conn->real_escape_string($username);
    $password = md5($conn->real_escape_string($password)); // Store password as MD5 hash
    
    // Query the database for user
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        // Start session and set session variables
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        
        return true;
    } else {
        return false;
    }
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
}

// Function to logout user
function logoutUser() {
    session_start();
    unset($_SESSION['logged_in']);
    unset($_SESSION['username']);
    session_destroy();
    
    // Redirect to login page
    header("Location: login.php");
}

// Example usage:
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (loginUser($username, $password)) {
        echo "Login successful!";
        // Redirect to dashboard
        header("Location: dashboard.php");
    } else {
        echo "Invalid username or password!";
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$username_db = 'root'; // Replace with your database username
$password_db = '';     // Replace with your database password
$database = 'testdb';  // Replace with your database name

// Create a connection to the database
$conn = new mysqli($host, $username_db, $password_db, $database);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle user login
function loginUser($username, $password, $conn) {
    // Sanitize input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query the database for the user
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct, start a session
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $row['id'];
            return true;
        } else {
            // Incorrect password
            return "Incorrect password";
        }
    } else {
        // User does not exist
        return "User does not exist";
    }
}

// Check if form is submitted
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = loginUser($username, $password, $conn);

    if ($result === true) {
        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    } else {
        // Display error message
        echo "<div style='color:red;'>$result</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>LOGIN</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br><br>
        Password: <input type="password" name="password"><br><br>
        <input type="submit" name="login" value="Login">
    </form>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>


<?php
session_start();
// Destroy session variables
unset($_SESSION['username']);
unset($_SESSION['id']);
// Destroy the entire session
session_destroy();
// Redirect to login page
header("Location: login.php");
exit();
?>


<?php
// Include necessary files (e.g., database configuration)
include_once("header.php");
include_once("config.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input data to prevent SQL injection
    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);

    // Connect to database
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Please fill in all fields!';
        header('Location: login.php');
        exit();
    }

    // Prepare SQL query to select user data
    $sql = "SELECT * FROM users WHERE username = ?";
    
    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);

    // Execute the query
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Start session and store user data in session variables
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['login_time'] = time();

            // Redirect to dashboard or home page
            header('Location: dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = 'Invalid username or password!';
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'User does not exist!';
        header('Location: login.php');
        exit();
    }

    // Close database connection
    mysqli_close($conn);
}
?>


<?php
function user_login($username, $password) {
    try {
        // Database connection parameters
        $db_host = 'localhost';
        $db_user = 'root';
        $db_pass = '';
        $db_name = 'mydatabase';

        // Create a database connection
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Escape special characters to prevent SQL injection
        $username = $conn->real_escape_string($username);

        // Query the database for the user's information
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($conn->error) {
            throw new Exception("Query error: " . $conn->error);
        }

        // Check if a user exists with that username
        if ($result->num_rows == 0) {
            return false;
        }

        // Fetch the user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (!password_verify($password, $user['password'])) {
            return false;
        }

        // Start a session and store user data in session variables
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['login_time'] = time();

        // Close the database connection
        $conn->close();

        return true;

    } catch (Exception $e) {
        // Handle exceptions and log errors if necessary
        echo "An error occurred: " . $e->getMessage();
        return false;
    }
}
?>


<?php
session_start();

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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Escape special characters to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Hash the password (using md5 for this example - consider using stronger hashing in production)
    $hash_password = md5($password);

    // Query to check user credentials
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        
        if (mysqli_num_rows($result) > 0) {
            // Verify password
            if ($hash_password == $row['password']) {
                // Set session variables
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                
                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "Username not found!";
        }
    } else {
        echo "Error executing query!";
    }

    mysqli_close($conn);
}
?>


<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'mydatabase';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query database for user with matching email
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // User exists, get user data
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            $error = "Incorrect email or password";
        }
    } else {
        // User does not exist
        $error = "Incorrect email or password";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'user_login';

// Connect to database
$conn = mysqli_connect($host, $db_username, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from form
    $username_email = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Sanitize the inputs
    $username_email = mysqli_real_escape_string($conn, $username_email);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if username or email exists in database
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username_email, $username_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Start session
            session_start();
            
            // Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['login_time'] = time();

            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Invalid username or password";
    }

    mysqli_close($conn);
}
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'mydatabase';

// Connect to database
$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];

    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        echo "Please fill in all fields";
        exit();
    }

    // Query the database for the user
    $sql = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }

    $user_data = mysqli_fetch_assoc($result);

    // Check if user exists and password is correct
    if ($user_data && password_verify($password, $user_data['password'])) {
        // Set session variables
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['logged_in'] = true;

        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid username or password";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login Form</h2>
    <form action="login.php" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>


<?php
// Include database configuration file
include('config.php');

// Initialize variables
$username = $password = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];

    // Check if username and password are not empty
    if (empty($username) || empty($password)) {
        $error = "Username and password are required!";
    } else {
        // Query to check if username exists in the database
        $query = "SELECT * FROM users WHERE username = '" . $username . "'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Password is correct
                session_start();
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['id'];
                
                // Optional: Set a cookie for remember me functionality
                setcookie('username', $row['username'], time() + 3600);
                setcookie('session_id', session_id(), time() + 3600);

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                $error = "Incorrect username or password!";
            }
        } else {
            // Username does not exist
            $error = "Incorrect username or password!";
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .login-container {
            width: 300px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .login-container h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if ($error != "") { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>

    <script>
        // Optional: Add client-side validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const username = this.username.value;
            const password = this.password.value;

            if (username === '' || password === '') {
                alert('Please fill in all fields!');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>


<?php
session_start();

// Database configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$database_name = 'mydb';

// Create connection
$conn = mysqli_connect($host, $username_db, $password_db, $database_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if username exists in database (either as username or email)
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param('ss', $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get user data
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        // Verify password
        if (password_verify($password, $stored_password)) {
            // Password is correct
            // Start session and store user data in session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['logged_in'] = true;

            // Redirect to dashboard or home page
            header('Location: dashboard.php');
            exit();
        } else {
            // Password is incorrect
            $error_message = "Incorrect password!";
        }
    } else {
        // Username/email not found in database
        $error_message = "Username or email does not exist!";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        .container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
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
    <div class="container">
        <?php
        if (isset($error_message)) {
            echo "<p style='color:red;'>$error_message</p>";
        }
        ?>
        <h3>Login Form</h3>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="username" placeholder="Username or Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>


// In your registration script
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);


<?php
// Start session
session_start();

// Include database connection file
include_once("db_connection.php");

function loginUser() {
    // Check if form is submitted
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate input
        if (empty($username) || empty($password)) {
            return "Please fill in all fields";
        }

        try {
            // Prepare statement to select user by username
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            
            // Get result
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (!$user) {
                return "Username does not exist";
            }

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                // Start session and store user data
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['email'] = $user['email'];

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                return "Incorrect password";
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}

// Call the function if form is submitted
if (isset($_POST['login'])) {
    $error = loginUser();
    if ($error) {
        echo "<div class='error'>$error</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username:<br>
        <input type="text" name="username" required><br>
        Password:<br>
        <input type="password" name="password" required><br><br>
        <input type="submit" name="login" value="Login">
    </form>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">User Login</h2>
        
        <?php
            // Database connection details
            $host = "localhost";
            $db_name = "your_database_name";
            $username = "your_username";
            $password = "your_password";

            try {
                // Create database connection
                $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if (isset($_POST['login'])) {
                    $input = $_POST['username_email'];
                    $pass = $_POST['password'];

                    // Prepare and execute query
                    $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ? OR email LIKE ?");
                    $stmt->bindValue(1, '%' . $input . '%', PDO::PARAM_STR);
                    $stmt->bindValue(2, '%' . $input . '%', PDO::PARAM_STR);
                    $stmt->execute();

                    // Check if any rows found
                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // Verify password
                        if (password_verify($pass, $row['password'])) {
                            // Start session and store user data
                            session_start();
                            $_SESSION['user_id'] = $row['id'];
                            $_SESSION['username'] = $row['username'];
                            $_SESSION['email'] = $row['email'];

                            // Redirect to dashboard
                            header("Location: dashboard.php");
                            exit();
                        } else {
                            echo "<div class='alert alert-danger'>Invalid username/password!</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Username or Email not found!</div>";
                    }
                }
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        ?>

        <form method="POST" action="<?php $_PHP_SELF; ?>">
            <div class="mb-3">
                <input type="text" name="username_email" class="form-control" placeholder="Enter username or email" required>
            </div>
            <div class="mb-4">
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </form>

        <p class="mt-3">Don't have an account? <a href="register.php">Register here</a></p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php
// Connect to the database
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "login";

$conn = mysqli_connect($host, $username_db, $password_db, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Sanitize input data
    $username = trim(mysqli_real_escape_string($conn, htmlspecialchars(stripslashes($_POST['username']))));
    $password = trim(mysqli_real_escape_string($conn, htmlspecialchars(stripslashes($_POST['password']))));

    // Query the database for the username and password
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if ($password == md5($row['password'])) {
            // Start the session and set session variables
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $row['id'];
            $_SESSION['logged_in'] = true;

            // Set message for success
            $_SESSION['message'] = "You are now logged in";
            header("location: dashboard.php");
            exit();
        } else {
            // Password doesn't match
            $_SESSION['message'] = "Invalid username or password";
            header("location: login.php");
            exit();
        }
    } else {
        // Username not found
        $_SESSION['message'] = "Invalid username or password";
        header("location: login.php");
        exit();
    }
}

// Close the database connection
mysqli_close($conn);
?>


<?php
session_start();
require_once('database_connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Please fill in both username and password";
        header("Location: login.php?error=$error");
        exit();
    }

    try {
        // Prepare SQL statement to select user by username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Set session cookie lifetime to 0 (session ends when browser closes)
                ini_set('session.cookie_lifetime', '0');
                
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                $error = "Invalid username or password";
                header("Location: login.php?error=$error");
                exit();
            }
        } else {
            // No user found
            $error = "Invalid username or password";
            header("Location: login.php?error=$error");
            exit();
        }
    } catch (Exception $e) {
        // Error occurred
        $error = "An error occurred during login. Please try again later.";
        header("Location: login.php?error=$error");
        exit();
    }
}

// Close database connection
$conn->close();
?>


<?php
// Start the session
session_start();

// Include database connection file
include 'db_connection.php';

// Check if form is submitted
if (isset($_POST['login'])) {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Validate input fields
    if (empty($username) || empty($password)) {
        $error = "All fields are required!";
    } else {
        // Query the database for user with this username
        $query = "SELECT * FROM users WHERE username = ?";
        
        // Prepare and bind statement
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_execute($stmt);
        
        // Get the result
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Check password
            if (password_verify($password, $row['password'])) {
                // Password is correct
                
                // Store user session variables
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                
                // Check for remember me
                if ($_POST['remember']) {
                    // Set up a cookie that will last for 30 days
                    setcookie('remember_me', $row['remember_token'], time() + (86400 * 30), "/");
                }
                
                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "Username not found!";
        }
    }
}

// Close database connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error ?></p>
    <?php } ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Username:</label><br>
        <input type="text" name="username"><br>
        
        <label>Password:</label><br>
        <input type="password" name="password"><br>
        
        <label><input type="checkbox" name="remember"> Remember me</label><br>
        
        <input type="submit" name="login" value="Login">
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>


<?php
// login.php

session_start();

// Database connection details
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'mydatabase';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get username and password from form
$username = $_POST['username'];
$password = $_POST['password'];

// Sanitize input to prevent SQL injection
$username = mysqli_real_escape_string($conn, $username);

// Query to check if username exists
$sql = "SELECT id, username, password FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User exists, get stored hash
    $row = $result->fetch_assoc();
    $stored_hash = $row['password'];
    
    // Verify password
    if (md5($password) === $stored_hash) {
        // Password correct
        
        // Start session and set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $row['id'];
        
        // Optional: Set remember me cookie
        if (isset($_POST['remember'])) {
            $cookie_name = "user_cookie";
            $cookie_value = md5($username . $password);
            setcookie($cookie_name, $cookie_value, time() + 3600); // Cookie valid for 1 hour
        }
        
        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    } else {
        // Password incorrect
        $error = "Invalid username or password!";
        header("refresh:2; url=login.html");
        echo "<div class='alert alert-danger mt-4' style='text-align:center;'>$error</div>";
        exit();
    }
} else {
    // Username not found
    $error = "Username does not exist!";
    header("refresh:2; url=login.html");
    echo "<div class='alert alert-danger mt-4' style='text-align:center;'>$error</div>";
    exit();
}

// Close database connection
$conn->close();
?>


<?php
// db_config.php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'login_system';

try {
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
} catch (Exception $e) {
    die("Connection Error: " . $e->getMessage());
}
?>


<?php
session_start();
include('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $username_email = mysqli_real_escape_string($conn, $_POST['username_email']);
    $password = $_POST['password'];

    // Query to check if username or email exists
    $sql = "SELECT * FROM users WHERE username='$username_email' OR email='$username_email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Start session and set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['login_time'] = time();
            
            // Regenerate session ID for security
            session_regenerate(true);
            
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Username or email not found!";
    }
} else {
    echo "Invalid request method!";
}

$conn->close();
?>


<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to your Dashboard, <?=$_SESSION['username']?>!</h1>
    <a href="logout.php">Logout</a>
</body>
</html>


<?php
session_start();
unset($_SESSION);
session_destroy();
header("Location: login.php");
exit();
?>


<?php
// Database configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'login_system';

// Connect to the database
$conn = mysqli_connect($host, $db_username, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session
session_start();

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize inputs
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;

            // Redirect to welcome page
            header("Location: welcome.php");
            exit();
        } else {
            // Incorrect password
            $error = "Incorrect username or password";
        }
    } else {
        // No user found
        $error = "Incorrect username or password";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .login-form {
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" name="submit">Login</button>
        </form>
    </div>
</body>
</html>


// Hash the password before storing it
$hashed_password = password_hash('testpassword', PASSWORD_DEFAULT);

mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('testuser', '$hashed_password')");


<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['submit'])) {
        // Check if form is submitted
        $email_username = $_POST['email_username'];
        $password = $_POST['password'];

        // Validate inputs
        if (empty($email_username) || empty($password)) {
            die("Please fill in all fields.");
        }

        // Query the database for user with provided email or username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $email_username);
        $stmt->bindParam(':email', $email_username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("Invalid credentials.");
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            die("Invalid credentials.");
        }

        // Start session and store user data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];

        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    }

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$database = 'login_system';

// Connect to database
$conn = mysqli_connect($host, $username_db, $password_db, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize and retrieve input data
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);

    // Query the database for user with matching username
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verify password
        if (md5($password) == $row['password']) {
            // Password is correct
            
            // Start session and store user data
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;

            // Redirect to dashboard or home page
            header('Location: dashboard.php');
            exit();
        } else {
            // Incorrect password
            echo "Incorrect username or password";
        }
    } else {
        // User not found
        echo "Incorrect username or password";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
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
        
        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
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
    <div class="login-container">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="text" placeholder="Username" name="username" required>
            <input type="password" placeholder="Password" name="password" required>
            <button type="submit" name="submit">Login</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();

// Database configuration
$host = 'localhost';
$user = 'root'; // Change this to your database username
$password = ''; // Change this to your database password
$database = 'login_system';

// Connect to the database
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;

    // Get input data
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate inputs
        if (empty($username) || empty($password)) {
            echo "Username and password are required!";
            return false;
        }

        // Sanitize input data
        $username = mysqli_real_escape_string($conn, $username);

        // Check if username exists in database
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user_data = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user_data['password'])) {
                // Password is correct
                $_SESSION['user_id'] = $user_data['id'];
                $_SESSION['username'] = $user_data['username'];
                
                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Password does not match
                echo "Incorrect password!";
            }
        } else {
            // Username doesn't exist
            echo "Username does not exist!";
        }

        $stmt->close();
    }
}

// Call the login function if form is submitted
if (isset($_POST['login'])) {
    login_user();
}
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'user_login';

// Connect to the database
$conn = mysqli_connect($host, $username_db, $password_db, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize the input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if username or email exists in the database
    $sql = "SELECT * FROM users WHERE username='$username' OR email='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Get user details
        $row = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Start session and store user data
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['admin'] = $row['is_admin'];

            // Redirect based on user role
            if ($_SESSION['admin'] == 1) {
                header("Location: admin_dashboard.php");
                exit();
            } else {
                header("Location: welcome.php");
                exit();
            }
        } else {
            echo "<div class='error'><i class='fas fa-exclamation-circle'></i> Incorrect password!</div>";
        }
    } else {
        echo "<div class='error'><i class='fas fa-exclamation-circle'></i> Username or email not found!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f3f3f3;
        }

        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 400px;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #666;
        }

        input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 0.8rem;
            background-color: #337ab7;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #286090;
        }

        .error {
            color: #d9534f;
            margin-bottom: 1rem;
            padding: 0.5rem;
            border-radius: 4px;
            background-color: #f2dede;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .success {
            color: #3c763d;
            margin-bottom: 1rem;
            padding: 0.5rem;
            border-radius: 4px;
            background-color: #dff0d8;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-links {
            text-align: center;
            margin-top: 1rem;
        }

        .form-links a {
            color: #337ab7;
            text-decoration: none;
        }

        .form-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        
        <?php
        // Show error/success messages here if needed
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username or Email:</label>
                <input type="text" id="username" name="username" required placeholder="Enter your username or email">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>

            <button type="submit" name="submit">Login</button>
        </form>

        <div class="form-links">
            <a href="#">Forgot Password?</a> |
            <a href="register.php">Create New Account</a>
        </div>
    </div>
</body>
</html>


// Registration function
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username or email already exists
    $check_sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($result) > 0) {
        echo "Username or email already exists!";
    } else {
        // Insert new user
        $insert_sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($conn, $insert_sql)) {
            echo "Registration successful! You can now login.";
        } else {
            echo "Error registering: " . mysqli_error($conn);
        }
    }
}


<?php
// Database configuration
$host = "localhost";
$dbname = "your_database_name";
$username = "your_username";
$password = "your_password";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;

    // Get user input
    $email_username = $_POST['email_username'];
    $password = $_POST['password'];

    if (empty($email_username) || empty($password)) {
        return show_error("Both email/username and password are required");
    }

    // Sanitize inputs
    $email_username = mysqli_real_escape_string($conn, $email_username);
    $password = mysqli_real_escape_string($conn, $password);

    // Prepare SQL statement to check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $email_username, $email_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return show_error("Invalid credentials");
    }

    // Fetch user data
    $user = $result->fetch_assoc();

    // Verify password
    if (!password_verify($password, $user['password'])) {
        return show_error("Incorrect password");
    }

    // Set session variables
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];

    // Optional: Set last login time and update in database
    date_default_timezone_set("UTC");
    $last_login = date("Y-m-d H:i:s");
    $stmt = $conn->prepare("UPDATE users SET last_login = ? WHERE id = ?");
    $stmt->bind_param("si", $last_login, $user['id']);
    $stmt->execute();

    // Redirect to dashboard or home page
    header("Location: dashboard.php");
    exit();
}

function show_error($message) {
    echo "<div class='error'>$message</div>";
    return false;
}
?>


<?php
// Database connection details
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "user_login";

// Create connection
$conn = mysqli_connect($host, $username_db, $password_db, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input
    $username = stripslashes($username);
    $username = mysqli_real_escape_string($conn, $username);

    $password = stripslashes($password);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        $error = "Username or Password is invalid";
        echo "<script>alert('$error');</script>";
    } else {
        // Query the database for the user
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            // User exists, verify password
            $row = mysqli_fetch_assoc($result);
            $hash = $row['password'];
            
            // Verify the password using password_verify()
            if (password_verify($password, $hash)) {
                // Password is correct
                $_SESSION['username'] = $username;
                $_SESSION['login'] = true;
                $_SESSION['last_login'] = time();
                
                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                $error = "Invalid username or password";
                echo "<script>alert('$error');</script>";
            }
        } else {
            // User does not exist
            $error = "User does not exist";
            echo "<script>alert('$error');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <!-- Add CSS styles here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="text" name="username" placeholder="Enter username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</body>
</html>

// Close database connection
mysqli_close($conn);
?>


<?php
// Database connection details and session start
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Insert into database
    $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    mysqli_query($conn, $query);
    header("Location: login.php");
}
?>


<?php
// Database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create a PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Prepare SQL query to select user with given username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        // Fetch the result
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                session_start();
                
                // Store user details in session
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $user['id'];
                $_SESSION["username"] = $user['username'];

                // Redirect to dashboard or home page
                header("location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                echo "Incorrect password!";
            }
        } else {
            // User does not exist
            echo "User does not exist!";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


// logout.php
<?php
session_start();
unset($_SESSION["loggedin"]);
unset($_SESSION["id"]);
unset($_SESSION["username"]);
session_destroy();
header("location: login.php");
exit();
?>


<?php
// Start the session
session_start();

// Include database configuration file
include_once('db_config.php');

function loginUser() {
    // Check if form is submitted
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            // Prepare the query to fetch user data based on username
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->bindParam(1, $username);
            $stmt->execute();

            // Fetch user data
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                // Verify the password
                if (password_verify($password, $row['password'])) {
                    // Password is correct
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['logged_in'] = true;

                    return "Login successful!";
                } else {
                    // Incorrect password
                    return "Incorrect password. Please try again.";
                }
            } else {
                // User doesn't exist
                return "Username does not exist. Please register first.";
            }

        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    } else {
        // Empty fields
        return "Please fill in all required fields!";
    }
}

// Call the login function and store the result
$message = loginUser();

// Redirect to dashboard or show message
if (!empty($_SESSION['logged_in'])) {
    header("Location: dashboard.php");
} else {
    echo $message;
}
?>


<?php

// Database configuration
$servername = "localhost";
$username_db = "username";
$password_db = "password";
$dbname = "myDB";

function user_login($username, $password) {
    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        return array("error" => true, "message" => "Username and password are required");
    }

    // Database connection
    global $servername, $username_db, $password_db, $dbname;
    
    try {
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);
        
        if ($conn->connect_error) {
            return array("error" => true, "message" => "Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind
        $stmt = $conn->prepare("SELECT id, username, password, login_attempts, lockout_time FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);

        $stmt->execute();
        
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            return array("error" => true, "message" => "Username does not exist");
        }

        // Fetch the user data
        $user = $result->fetch_assoc();

        // Check for account lockout (too many failed attempts)
        if ($user['login_attempts'] >= 5 && time() < $user['lockout_time']) {
            return array("error" => true, "message" => "Account locked. Please try again later.");
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            // Update login attempts and lockout time if necessary
            $new_attempts = $user['login_attempts'] + 1;
            
            if ($new_attempts >= 5) {
                $lockout_time = time() + (5 * 60); // Lock for 5 minutes
            } else {
                $lockout_time = $user['lockout_time'];
            }

            $stmt_update = $conn->prepare("UPDATE users SET login_attempts = ?, lockout_time = ? WHERE id = ?");
            $stmt_update->bind_param("iii", $new_attempts, $lockout_time, $user['id']);
            $stmt_update->execute();

            return array("error" => true, "message" => "Incorrect password");
        }

        // Reset login attempts on successful login
        $stmt_reset = $conn->prepare("UPDATE users SET login_attempts = 0 WHERE id = ?");
        $stmt_reset->bind_param("i", $user['id']);
        $stmt_reset->execute();

        // Start session and store user data
        session_start();
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login_time'] = time();

        return array("error" => false, "message" => "Login successful");

    } catch (Exception $e) {
        return array("error" => true, "message" => "An error occurred: " . $e->getMessage());
    }
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = user_login($username, $password);

    if (!$result['error']) {
        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    } else {
        echo $result['message'];
    }
}

$conn->close();

?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

try {
    // Create connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize input data
        $username_login = $_POST['username'];
        $password_login = $_POST['password'];

        try {
            // Prepare and execute query to check user credentials
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username_login]);

            // Check if user exists
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                die('Username not found!');
            }

            // Verify password
            if (!password_verify($password_login, $user['password'])) {
                die('Incorrect password!');
            }

            // Start session and store user data
            session_start();
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            if ($user['role'] == 'admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: user_dashboard.php');
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

    }
} catch(PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>


// During registration:
$raw_password = $_POST['password'];
$hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);
// Store $hashed_password in your database

// When verifying login:
if (password_verify($entered_password, $hashed_password)) {
    // Password matches
} else {
    // Incorrect password
}


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function loginUser($pdo) {
    // Check if form was submitted
    if (!empty($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            // Prepare and execute the query
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            // Get the result as an associative array
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Check if user exists and password is correct
            if ($user && password_verify($password, $user['password'])) {
                // Start session
                session_start();
                
                // Store user data in session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['login_time'] = time();

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Invalid credentials
                $error_message = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            // Handle database errors
            die("An error occurred while accessing the database.");
        }
    }
    
    if (!empty($error_message)) {
        echo "<div class='alert alert-danger'>$error_message</div>";
    }
}

// Call the login function
loginUser($pdo);
?>


<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['logged_in'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Validate input
    if (empty($email) || empty($password)) {
        $error = "Please fill in all required fields!";
    } else {
        // Database connection (replace with your actual database credentials)
        $db_host = "localhost";
        $db_user = "username";
        $db_pass = "password";
        $db_name = "database";

        // Connect to MySQL server
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prevent SQL injection
        $email = $conn->real_escape_string($email);
        
        // Query the database for user with this email
        $sql = "SELECT id, password FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Create session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $row['id'];
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "Invalid email or password!";
        }

        // Close database connection
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 350px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if ($error != "") { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <input type="text" placeholder="Enter your email..." name="email">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter your password..." name="password">
            </div>
            <button type="submit" name="submit">Login</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        die("Username and password are required!");
    }

    // Connect to the database
    require_once 'db_connection.php';

    // Query the database for the user
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $stored_password_hash = $row['password'];

        // Verify the password
        if (password_verify($password, $stored_password_hash)) {
            // Password is correct
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;

            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            die("Incorrect password!");
        }
    } else {
        die("Username does not exist!");
    }

    // Close the database connection
    mysqli_close($conn);
}
?>


<?php
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$database = 'my_database';

$conn = mysqli_connect($host, $username_db, $password_db, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
// Use this hash when storing in your database


<?php
// Start session
session_start();

function user_login($username, $password) {
    // Database connection details
    $host = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "mydatabase";

    // Connect to database
    $conn = mysqli_connect($host, $db_username, $db_password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize input
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Prepare SQL query to select user with the given username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Get user data
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Create session variables
            $_SESSION['username'] = $user['username'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Close database connection
            mysqli_close($conn);

            return true;
        } else {
            // Incorrect password
            return false;
        }
    } else {
        // No user found with that username
        return false;
    }
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (user_login($username, $password)) {
        header("Location: dashboard.php");
    } else {
        echo "Invalid username or password";
    }
}
?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'login_system';

// Connect to database
$conn = mysqli_connect($host, $db_user, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Prepare and execute SQL query
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        // User not found
        echo "<script>alert('Username does not exist. Please try again.');
              window.setTimeout(function(){window.location.href='login.php';}, 1500);
              </script>";
        exit();
    }

    $user = mysqli_fetch_assoc($result);

    if (md5($password) !== $user['password']) {
        // Password is incorrect
        echo "<script>alert('Incorrect password. Please try again.');
              window.setTimeout(function(){window.location.href='login.php';}, 1500);
              </script>";
        exit();
    }

    // Start session and store user data
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_id'] = $user['id'];

    // Redirect to welcome page
    header("Location: welcome.php");
    exit();
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
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
        
        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        input[type='text'],
        input[type='password'] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Database connection parameters
    $host = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'login';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbName", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the query
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Start session and store user data
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: welcome.php");
                exit();
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "Email not found!";
        }

    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'mydatabase';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (isset($_SESSION['username'])) {
    // User is already logged in
    echo "Welcome back, ".$_SESSION['username']."!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Prepare SQL statement to select user with matching username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                session_regenerate(true); // Regenerate session ID to prevent fixation
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;

                // Optional: Set last login time
                date_default_timezone_set('UTC');
                $last_login = date('Y-m-d H:i:s');
                
                $stmt = $conn->prepare("UPDATE users SET last_login = :last_login WHERE id = :id");
                $stmt->execute([':last_login' => $last_login, ':id' => $user['id']]);

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                $_SESSION['error'] = "Invalid username or password";
            }
        } else {
            // Username not found
            $_SESSION['error'] = "Invalid username or password";
        }
    } catch (PDOException $e) {
        die("An error occurred: " . $e->getMessage());
    }
}

// Close database connection
$conn = null;

// Display login form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <?php if (isset($_SESSION['error'])) { ?>
        <div style="color: red;"><?=$_SESSION['error']?></div>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>

    <?php if (!isset($_SESSION['username'])) { ?>
        <a href="register.php">Create an account</a>
    <?php } ?>

    <?php if (isset($_SESSION['username'])) { ?>
        <a href="logout.php">Logout</a>
    <?php } ?>
</body>
</html>

<?php
// Optional: Destroy session data after request
if (!isset($_SESSION['username'])) {
    $_SESSION = array();
    session_unset();
    session_destroy();
}
?>


<?php
// Database connection settings
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'your_database';

// Create database connection
$conn = new mysqli($host, $user, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle user login
function loginUser() {
    global $conn;

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Sanitize input data
        $username = htmlspecialchars(strip_tags($username));
        $password = htmlspecialchars(strip_tags($password));

        // Check if username and password are provided
        if (empty($username) || empty($password)) {
            echo "Username or password cannot be empty.";
            return;
        }

        // Prepare SQL statement to select user
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $user_data = $result->fetch_assoc();

                // Verify password
                if (password_verify($password, $user_data['password'])) {
                    // Set session variables
                    session_start();
                    $_SESSION['user_id'] = $user_data['id'];
                    $_SESSION['logged_in'] = true;
                    
                    // Redirect to dashboard
                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "Incorrect password.";
                }
            } else {
                echo "Username does not exist.";
            }
        } else {
            echo "Error executing query: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Call the login function
loginUser();

// Close database connection
$conn->close();
?>


session_start();


<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit();
?>


<?php
session_start();

// Database configuration
$host = "localhost";
$usernameDb = "your_username";
$passwordDb = "your_password";
$database = "login_system";

// Connect to database
$conn = mysqli_connect($host, $usernameDb, $passwordDb, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5(mysqli_real_escape_string($conn, $_POST['password']));

    // Validate input fields
    if (empty($username) || empty($password)) {
        echo "Please fill in all required fields!";
    } else {
        // Check if user exists in database
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);
        $user_data = mysqli_fetch_assoc($result);

        if (!$user_data) {
            echo "User does not exist!";
        } else {
            // Verify password
            if ($password == $user_data['password']) {
                // Set session variables
                $_SESSION['username'] = $user_data['username'];
                $_SESSION['logged_in'] = true;

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Invalid password!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 500px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        input { width: 100%; padding: 8px; }
        button { background-color: #4CAF50; color: white; padding: 10px 16px; border: none; cursor: pointer; width: 100%; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Form</h2>
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <input type="text" placeholder="Enter your username..." name="username" required>
            </div>

            <div class="form-group">
                <input type="password" placeholder="Enter your password..." name="password" required>
            </div>

            <button type="submit" name="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>


<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>


<?php
// Start session
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "login_system";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Check if username exists in the database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Get user data
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Password is correct
            $_SESSION["username"] = $username;
            echo "Welcome " . $username . "! You have successfully logged in.";
            
            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Incorrect password
            echo "Incorrect username or password";
        }
    } else {
        // Username doesn't exist
        echo "Incorrect username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login Form</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


<?php
// Start session
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "login_system";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username already exists
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Username already exists!";
    } else {
        // Insert new user into the database
        $sql = "INSERT INTO users (username, password)
                VALUES ('$username', '$hashed_password')";
        
        if ($conn->query($sql)) {
            echo "New user created successfully! You can now login.";
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
</head>
<body>
    <h2>Registration Form</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>


<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'test_db');

function user_login($username, $password) {
    $result = array();
    
    // Connect to database
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        return;
    }
    
    try {
        // Prepare SQL query
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result()->fetch_assoc();
            
            if ($result) {
                // Verify password
                if (password_verify($password, $result['password'])) {
                    $result['status'] = 1;
                } else {
                    $result['status'] = 0;
                    $result['message'] = "Incorrect username or password";
                }
            } else {
                $result['status'] = 0;
                $result['message'] = "User not found";
            }
        } else {
            $result['status'] = 0;
            $result['message'] = "Error executing query";
        }
    } catch (Exception $e) {
        $result['status'] = 0;
        $result['message'] = "An error occurred: " . $e->getMessage();
    }
    
    // Close database connection
    mysqli_close($conn);
    
    return $result;
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $login_result = user_login($username, $password);
    
    if ($login_result['status'] == 1) {
        // Successful login
        session_start();
        $_SESSION['user_id'] = $login_result['id'];
        header("Location: dashboard.php");
    } else {
        // Display error message
        echo "Error: " . $login_result['message'];
    }
}
?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$dbname = 'mydatabase';

// Connect to database
$conn = mysqli_connect($host, $username_db, $password_db, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize input data
    $username = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['username']));
    $password = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password']));

    // Query to check if username exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        echo "Username does not exist!";
    } else {
        // Get user data
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (md5($password) === $user['password']) {
            // Start session and store user details
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="submit">Login</button>
    </form>
</body>
</html>


<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['username']);
unset($_SESSION['email']);
session_destroy();

header("Location: login.php");
exit();
?>


<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
    <p>Your email address is: <?php echo $_SESSION['email']; ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>


<?php
session_start();
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "login_system";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // SQL injection prevention using mysqli_real_escape_string
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if the username and password exist in the database
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        
        // Optional: Get user ID from the result
        $row = mysqli_fetch_assoc($result);
        $_SESSION['id'] = $row['id'];
        
        // Redirect to dashboard or home page after login
        header("Location: dashboard.php");
        exit();
    } else {
        // Incorrect password or username
        echo "Invalid username or password!";
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login Form</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


<?php
session_start();

// Database connection details
$host = 'localhost';
$user = 'root'; // MySQL username
$password = ''; // MySQL password
$dbname = 'mydatabase'; // Your database name

// Connect to MySQL database
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Sanitize and validate input
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    // Prevent SQL Injection using prepared statements
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();

    // Store result in a variable
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Start session and store user data
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            
            // Redirect to dashboard or home page
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Username not found!";
    }

    // Close statement and connection
    $stmt->close();
} else {
    // If form is not submitted, redirect to login page
    header('Location: index.html');
}

$conn->close();
?>


// Registration code (register.php)
session_start();

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param('ss', $username, $hashed_password);
    $stmt->execute();
    
    echo "Registration successful!";
    $stmt->close();
}

$conn->close();


<?php
function userLogin($username, $password, $db) {
    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        return false;
    }

    try {
        // Sanitize the input
        $username = htmlspecialchars(trim($username));
        $password = htmlspecialchars(trim($password));

        // Prepare SQL statement to prevent SQL injection
        $stmt = $db->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        // Check if a user exists with the provided username
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() != 1) {
            return false;
        }

        // Verify the password using PHP's built-in password verification function
        if (password_verify($password, $user['password'])) {
            // Password is correct
            return true;
        } else {
            // Password is incorrect
            return false;
        }
    } catch(PDOException $e) {
        // Log any errors to a file or database for debugging purposes
        error_log("Login Error: " . $e->getMessage());
        return false;
    }
}
?>


// Assuming $db is your PDO database connection
if (userLogin($_POST['username'], $_POST['password'], $db)) {
    // Start a session
    session_start();
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['id'] = $userId;  // Get user id from the database

    // Redirect to dashboard or home page
    header("Location: dashboard.php");
} else {
    // Show error message
    echo "Invalid username or password";
}


<?php
// Database configuration
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

try {
    // Create a database connection using PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get user input
        $email = trim($_POST['email']);
        $pass = trim($_POST['password']);

        // Validate input
        if (empty($email) || empty($pass)) {
            die("Email and password are required!");
        }

        // Prepare the SQL statement to select the user
        $stmt = $conn->prepare("SELECT id, email, password, name FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($pass, $user['password'])) {
                // Password is correct
                session_start();
                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                echo "Incorrect password!";
            }
        } else {
            // No user found with the given email
            echo "No account found with this email!";
        }
    }

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Email: <input type="text" name="email"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


<?php
// Database connection file
include('db_connection.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

    // Query to check user credentials
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Start session and store user details
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }

    if (isset($error)) {
        echo "<script>alert('$error');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
        .login-form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 10px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
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
    <div class="login-form">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>


<?php
session_start();
require_once('config.php'); // Include your database connection file

function sanitizeInput($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($GLOBALS['conn'], $data);
    return $data;
}

if (isset($_POST['login'])) {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        die("Username and password are required!");
    }

    // Query the database for the user
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        die("Invalid username or password!");
    }

    $user = mysqli_fetch_assoc($result);
    
    // Verify the password
    if (!password_verify($password, $user['password'])) {
        die("Invalid username or password!");
    }

    // Start session and store user data
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['login_time'] = time();

    // Regenerate the session ID to prevent session fixation attacks
    session_regenerate(true);

    // Redirect to dashboard or home page
    header("Location: dashboard.php");
    exit();
}
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username = 'root'; // Change to your database username
$password = '';      // Change to your database password
$db_name = 'my_login_db';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['login'])) {
    // Sanitize input data
    $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));
    $password = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password']));

    // Check if email exists in the database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Start session and store user data
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];

            // Redirect to dashboard or another page
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Email does not exist!";
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Function to sanitize input data
    function sanitizeInput($data) {
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Check if form is submitted
    if (isset($_POST['submit'])) {
        $email = sanitizeInput($_POST['email']);
        $pass = $_POST['password'];

        // Prepare query to check for existing user
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Verify password
            if (password_verify($pass, $row['password'])) {
                // Password is correct
                session_start();
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Incorrect password";
            }
        } else {
            echo "User does not exist. Please check your email address.";
        }
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


<?php
session_start();

// Database configuration
$host = "localhost";
$username_db = "root";
$password_db = "";
$db_name = "login_system";

// Connect to database
$conn = new mysqli($host, $username_db, $password_db, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    // Get form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($username) || empty($password)) {
        echo "Please fill in all fields";
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // User exists, verify password
            $user_data = $result->fetch_assoc();
            
            if (password_verify($password, $user_data['password'])) {
                // Password is correct
                $_SESSION['username'] = $user_data['username'];
                $_SESSION['logged_in'] = true;
                
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                echo "Incorrect password";
            }
        } else {
            // User does not exist
            echo "Username does not exist";
        }
    }
}

$conn->close();

// Display login form
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login Form</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>


<?php
session_start();
require_once 'config.php'; // Database configuration file

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email and password from form
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    try {
        // Connect to the database
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL query to fetch user data
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Start session and store user data
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['logged_in'] = true;

                // Remember me functionality
                if (!empty($_POST['remember'])) {
                    // Generate a random token
                    $token = bin2hex(random_bytes(16));
                    $encryptedToken = openssl_encrypt($token, 'AES-256-CBC', HASH_KEY, 0, IV_KEY);

                    setcookie('remember_token', $encryptedToken, time() + (86400 * 30), '/');

                    // Update token in database
                    $stmt = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                    $stmt->execute([$token, $user['id']]);
                }

                // Redirect to dashboard
                header('Location: dashboard.php');
                exit();
            } else {
                // Password is incorrect
                $_SESSION['error'] = "Invalid email or password.";
            }
        } else {
            // User doesn't exist
            $_SESSION['error'] = "Invalid email or password.";
        }

    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Check for remember me cookie
if (!empty($_COOKIE['remember_token'])) {
    try {
        // Connect to the database
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Decrypt token and check in database
        $encryptedToken = $_COOKIE['remember_token'];
        $token = openssl_decrypt($encryptedToken, 'AES-256-CBC', HASH_KEY, 0, IV_KEY);

        $stmt = $conn->prepare("SELECT * FROM users WHERE remember_token = ?");
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Start session and store user data
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged_in'] = true;

            // Redirect to dashboard
            header('Location: dashboard.php');
            exit();
        }

    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// If no valid session, redirect to login page
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
?>


<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

// Security keys for encryption
define('HASH_KEY', 'your_encryption_key');
define('IV_KEY', 'your_initialization_vector');
?>


<?php
session_start();
include('db_connection.php'); // Include your database connection file

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, htmlspecialchars($username));
    $password = mysqli_real_escape_string($conn, htmlspecialchars($password));

    // Check if username and password are not empty
    if (empty($username) || empty($password)) {
        echo "Username or password cannot be empty!";
        exit();
    }

    // Query the database to check user credentials
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            
            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Username not found!";
    }

    mysqli_close($conn);
}
?>


<?php
// Database connection constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mydatabase');

// Create database connection
function db_connect() {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
}

// User login function
function user_login($username, $password) {
    // Escape the inputs to prevent SQL injection
    $username = mysqli_real_escape_string(db_connect(), $username);
    $password = mysqli_real_escape_string(db_connect(), $password);

    // Check if username exists in database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = db_connect()->query($query);

    if (!$result->num_rows) {
        return false; // Username does not exist
    }

    // Verify password
    $user_data = $result->fetch_assoc();
    $hash = hash('sha256', $password . $user_data['salt']);

    if ($hash != $user_data['password']) {
        return false; // Password is incorrect
    }

    // Start session and set session variables
    session_start();
    $_SESSION['user_id'] = $user_data['id'];
    $_SESSION['username'] = $user_data['username'];
    $_SESSION['logged_in'] = true;

    if ($user_data['role'] == 'admin') {
        $_SESSION['is_admin'] = true;
    }

    return true; // Login successful
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (user_login($username, $password)) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Invalid username or password!";
    }
}
?>


<?php
session_start();

// Database configuration
require_once('config.php');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the SQL query
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Start session and store user data
            $_SESSION['id'] = $user['id'];
            
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User does not exist!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        form {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
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
    <div class="container">
        <h2>Login Page</h2>
        
        <?php
        if (isset($error)) {
            echo "<p style='color: red;'>$error</p>";
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" name="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>


<?php
session_start();

// Database connection details
$host = 'localhost';
$dbUsername = 'root'; // Change this to your database username
$dbPassword = '';     // Change this to your database password
$dbName = 'login_system';

// Connect to the database
$conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function sanitizeInput($data) {
    $data = htmlspecialchars(strip_tags(trim($data)));
    return $data;
}

if (isset($_POST['login'])) {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    if ($username == "" || $password == "") {
        die("Please fill in all fields.");
    }

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Username not found.");
    }

    $row = $result->fetch_assoc();
    $dbPassword = $row['password'];

    // Verify password
    if (!password_verify($password, $dbPassword)) {
        die("Incorrect password.");
    } else {
        // Create session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();
    }
}

mysqli_close($conn);
?>


<?php
session_start();
unset($_SESSION['user_id']);
unset($_SESSION['username']);
session_destroy();
header("Location: login.php");
exit();
?>


<?php
// Database configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'login_system';

// Connect to database
$conn = new mysqli($host, $username_db, $password_db, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// User login function
function loginUser() {
    global $conn;

    // Check if form is submitted
    if (isset($_POST['login'])) {
        // Sanitize user input
        $username = htmlspecialchars(trim($_POST['username']));
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            echo "Please fill in all fields!";
            return;
        }

        // Query the database for the user
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['logged_in'] = true;
                
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Incorrect password!";
            }
        } else {
            echo "Username not found!";
        }
    }
}

// Close database connection
$conn->close();
?>


<?php
session_start();
// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to register a new user
function registerUser($conn, $username, $password, $email) {
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to insert user data
    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashedPassword, $email);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Function to login user
function loginUser($conn, $username, $password) {
    // Prepare SQL statement to select user by username
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get user data
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct
            // Update last login time
            date_default_timezone_set('UTC');
            $current_time = date('Y-m-d H:i:s');
            
            $updateStmt = $conn->prepare("UPDATE users SET last_login = ? WHERE id = ?");
            $updateStmt->bind_param("si", $current_time, $user['id']);
            $updateStmt->execute();

            // Start session and store user data
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['last_login'] = $current_time;

            return true;
        } else {
            // Password is incorrect
            return false;
        }
    } else {
        // Username not found
        return false;
    }
}

// Check if the registration form was submitted
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if (!empty($username) && !empty($password)) {
        if (registerUser($conn, $username, $password, $email)) {
            echo "Registration successful!";
        } else {
            echo "Registration failed. Please try again.";
        }
    } else {
        echo "Please fill in all required fields.";
    }
}

// Check if the login form was submitted
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        if (loginUser($conn, $username, $password)) {
            echo "Login successful!";
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Please fill in all required fields.";
    }
}

// Close database connection
$conn->close();
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to login user
function loginUser() {
    global $conn;

    // Get input values
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        echo "Username or password cannot be empty!";
        return;
    }

    // Query the database for the user
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get user data
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {

            // Password is correct
            session_start();
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Username not found!";
    }

    $stmt->close();
}

// Call the login function if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    loginUser();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input
        $email = htmlspecialchars(trim($_POST['email']));
        $password = trim($_POST['password']);

        // Check if email and password are provided
        if (empty($email) || empty($password)) {
            die("Email and password are required!");
        }

        // Prepare the statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Start session and store user data
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['logged_in'] = true;

                // Optional: Set a success message
                setcookie('login_message', 'Login successful!', time() + 2, '/', '', false, true);
                
                header("Location: dashboard.php");
                exit();
            } else {
                // Password doesn't match
                $error = "Invalid email or password!";
            }
        } else {
            // User not found
            $error = "Invalid email or password!";
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
    <title>Login Page</title>
</head>
<body>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Email:</label><br>
        <input type="email" name="email" required><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</body>
</html>


// Add this at the beginning of the script
session_start();
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;

// Add this to the form
<input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

// Add this in the login processing section
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Invalid CSRF token!");
}


<?php
session_start();

// Database connection parameters
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create database connection
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check if connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to sanitize input data
    function sanitizeInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    // Function to handle login
    function loginUser($conn, $username, $password) {
        global $loginError;

        // Check if username and password are not empty
        if (empty($username) || empty($password)) {
            $loginError = "Please fill in all fields.";
            return false;
        }

        // Sanitize input data
        $username = sanitizeInput($username);
        $password = sanitizeInput($password);

        // Prepare query to check user credentials
        $query = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param('s', $username);

        // Execute the query
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception("Error executing query: " . $stmt->error);
        }

        // Get result
        $stmt->store_result();

        // Check if user exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $dbUsername, $dbPassword);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $dbPassword)) {
                // Start session and set session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $dbUsername;

                // Set CSRF token for security
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                return true;
            } else {
                $loginError = "Invalid username or password.";
                return false;
            }
        } else {
            $loginError = "Invalid username or password.";
            return false;
        }
    }

    // Check if login form was submitted
    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (loginUser($conn, $username, $password)) {
            // Redirect to dashboard or home page after successful login
            header("Location: dashboard.php");
            exit();
        } else {
            echo $loginError;
        }
    }

} catch (Exception $e) {
    die("An error occurred: " . $e->getMessage());
}

// Close database connection
$conn->close();

?>


function registerUser($conn, $username, $password) {
    // Sanitize input data
    $username = sanitizeInput($username);
    $password = sanitizeInput($password);

    // Check if username and password are not empty
    if (empty($username) || empty($password)) {
        return false;
    }

    // Check if username already exists
    $query = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        return false; // Username already exists
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $insertQuery = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param('ss', $username, $hashedPassword);
    $result = $stmt->execute();

    if ($result) {
        return true;
    } else {
        return false;
    }
}


// Security headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");


<?php
session_start();

// Database connection details
$host = "localhost";
$username_db = "root";
$password_db = "";
$database_name = "login_system";

// Connect to database
$conn = mysqli_connect($host, $username_db, $password_db, $database_name);

// Check if connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Sanitize and validate input data
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    if ($username == '' || $password == '') {
        echo "Please fill in all fields!";
        exit();
    }

    // Check username and password against database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) != 1) {
        echo "Username not found!";
        exit();
    }

    // Get user details
    $row = mysqli_fetch_assoc($result);
    
    // Verify password
    if (password_verify($password, $row['password'])) {
        // Start session and store user data
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];
        
        // Redirect to dashboard or another page
        header("Location: dashboard.php");
    } else {
        echo "Login failed! Incorrect password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <h3 class="text-center mb-4">Login</h3>
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
                </form>
                <p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <!-- Display error messages -->
    <?php
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        if ($error == "empty") {
            echo '<div class="alert alert-danger">Please fill in all fields!</div>';
        } elseif ($error == "username") {
            echo '<div class="alert alert-danger">Username not found!</div>';
        } elseif ($error == "password") {
            echo '<div class="alert alert-danger">Incorrect password!</div>';
        }
    }
    ?>

</body>
</html>

// Logout functionality
<?php
session_start();
if (isset($_SESSION['id'])) {
    session_unset();
    session_destroy();
}
header("Location: login.php");
?>


$password = password_hash("user_password", PASSWORD_DEFAULT);


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    // Check if email and password are provided
    if (empty($email) || empty($password)) {
        die("Please fill in all required fields.");
    }

    try {
        // Prepare SQL statement to select user with the given email
        $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        // Check if user exists
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            die("Invalid email or password.");
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            die("Invalid email or password.");
        }

        // Start session
        session_start();
        
        // Set session variables
        $_SESSION['id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['logged_in'] = true;

        // Redirect to dashboard after successful login
        header("Location: dashboard.php");
        exit();

    } catch (PDOException $e) {
        die("Error processing your request: " . $e->getMessage());
    }
}

// Display login form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .login-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .login-container h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
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
    <div class="login-container">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter your email" name="email" required>
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter your password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();

// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'your_database';

// Connect to database
$conn = mysqli_connect($host, $user, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "Please fill in all fields.";
        exit();
    }

    // Prepare and execute query
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];

            // Optional: set remember me cookie
            if (!empty($_POST['remember'])) {
                $remember_token = bin2hex(random_bytes(32));
                setcookie('remember_token', $remember_token, time() + 3600 * 24 * 7, '/', null, false, true);
            }

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Username not found.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
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

        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
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
    <div class="login-container">
        <h2>Login</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <input type="text" placeholder="Enter username" name="username">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter password" name="password">
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="remember"> Remember me</label>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();

// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$db_name = "user_login";

// Connect to database
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Prepare and bind statement
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User exists
        $row = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct
            
            // Store session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $row['id'];
            $_SESSION["email"] = $row['email'];
            
            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            echo "<div class='error'>Invalid email or password</div>";
        }
    } else {
        // User doesn't exist
        echo "<div class='error'>Invalid email or password</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
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

        .login-form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type='text'], input[type='password'] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="text" placeholder="Enter your email..." name="email" required>
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter your password..." name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>


<?php
// Include database configuration file
require_once("config/db.php");

function user_login($username, $password) {
    try {
        // Prepare SQL statement to select user with matching username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bindParam(1, $username);
        $stmt->execute();

        // Check if exactly one row was found
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password using PHP's built-in password_verify function
            if (password_verify($password, $row['password'])) {
                // Password matches
                return true;
            } else {
                // Incorrect password
                return false;
            }
        } else {
            // No user found with this username
            return false;
        }
    } catch(PDOException $e) {
        // Error occurred
        echo "Error: " . $e->getMessage();
        return false;
    }
}
?>


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (user_login($username, $password)) {
        // Start session or set cookies here
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}


<?php
// Database connection details
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$database_name = 'login_system';

// Create database connection
$conn = mysqli_connect($host, $username_db, $password_db, $database_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function loginUser($username, $password) {
    global $conn;

    // Sanitize input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if username exists in database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        return array('status' => 'error', 'message' => 'Account does not exist!');
    }

    // Get user data
    $user_data = mysqli_fetch_assoc($result);
    
    // Verify password
    if (hash_equals($user_data['password'], hash('sha256', $password))) {
        // Password is correct, create session
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['session_id'] = mysqli_real_escape_string($conn, session_id());
        
        // Generate a random token for security
        $token = bin_rand(32);
        setcookie('auth_token', $token, time() + 3600 * 24 * 30, '/', '', false, true); // Store cookie for 30 days
        
        // Update database with new token and session ID
        $update_query = "UPDATE users SET session_id = '".$_SESSION['session_id']."', auth_token = '$token' WHERE username = '$username'";
        mysqli_query($conn, $update_query);
        
        return array('status' => 'success', 'message' => 'Login successful!');
    } else {
        // Password is incorrect
        return array('status' => 'error', 'message' => 'Incorrect password!');
    }
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $result = loginUser($username, $password);
    
    if ($result['status'] == 'success') {
        echo "Welcome, " . $_SESSION['username'] . "!";
    } else {
        echo $result['message'];
    }
}
?>


<?php
// This code assumes you have a database connection established
session_start();

function login($username, $password, $conn) {
    // Check if username or password is empty
    if (empty($username) || empty($password)) {
        return "Username or Password cannot be empty.";
    }
    
    // Query the database for the user
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        return "Username not found.";
    }
    
    // Get user data
    $user = $result->fetch_assoc();
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        return "Incorrect Password.";
    }
    
    // Start session and store user data
    $_SESSION['id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    
    return "Login successful!";
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $result = login($username, $password, $conn);
    
    if ($result == "Login successful!") {
        header("Location: dashboard.php");
        exit();
    } else {
        echo $result;
    }
}
?>


$password = password_hash($_POST['password'], PASSWORD_DEFAULT);


if (!isset($_COOKIE['username'])) {
    $remember = $_POST['remember'];
    
    if ($remember) {
        setcookie('username', $username, time() + 3600); // Cookie valid for 1 hour
    }
}


<?php
// sanitize_input.php

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <?php
    // Configuration
    include_once('config.php');

    if (isset($_POST['submit'])) {
        $username = sanitizeInput($_POST['username']);
        $password = $_POST['password'];
        
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Prevent SQL Injection
        $username = mysqli_real_escape_string($conn, $username);
        
        $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $db_password = $row['password'];
            
            // Verify password
            if (md5($password) == $db_password) {
                session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                
                // Remember me cookie
                setcookie("stayLoggedIn", "yes", time() + 3600*24*7, "/");
                
                header('Location: dashboard.php');
                exit();
            } else {
                echo "<div style='color:red'>Incorrect username or password!</div>";
            }
        } else {
            echo "<div style='color:red'>User doesn't exist!</div>";
        }
        
        mysqli_close($conn);
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        Remember Me: <input type="checkbox" name="remember_me"><br>
        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'login_system';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle login form submission
if (isset($_POST['submit'])) {
    // Sanitize input data
    $email = htmlspecialchars(trim($_POST['email']));
    $pass = htmlspecialchars(trim($_POST['password']));

    // Check if inputs are not empty
    if ($email == "" || $pass == "") {
        $_SESSION['message'] = "Email and password are required";
        header("Location: login.php");
        exit();
    }

    // SQL injection prevention
    $email = mysqli_real_escape_string($conn, $email);
    $pass = mysqli_real_escape_string($conn, $pass);

    // Query the database for user with matching email
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $_SESSION['message'] = "Error querying database";
        header("Location: login.php");
        exit();
    }

    // Check if user exists
    if (mysqli_num_rows($result) == 0) {
        $_SESSION['message'] = "User not found. Please check your email.";
        header("Location: login.php");
        exit();
    }

    $user_data = mysqli_fetch_assoc($result);

    // Verify password
    if (!password_verify($pass, $user_data['password'])) {
        $_SESSION['message'] = "Incorrect password";
        header("Location: login.php");
        exit();
    }

    // Set session variables
    $_SESSION['id'] = $user_data['id'];
    $_SESSION['email'] = $user_data['email'];
    $_SESSION['username'] = $user_data['username'];
    $_SESSION['logged_in'] = true;

    // Optional: Check if user has exceeded login attempts
    if ($user_data['login_attempts'] >= 3) {
        $_SESSION['message'] = "Account locked. Please contact support.";
        header("Location: login.php");
        exit();
    }

    // Reset login attempts and update last login time
    $reset_login_attempts = "UPDATE users SET login_attempts = 0 WHERE id = {$user_data['id']}";
    mysqli_query($conn, $reset_login_attempts);

    // Redirect to dashboard after successful login
    $_SESSION['message'] = "Welcome back, " . $user_data['username'];
    header("Location: dashboard.php");
    exit();
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 10px;
            padding: 10px;
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            color: #6c6c6c;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Display message if exists
        if (isset($_SESSION['message'])) {
            echo "<div class='message'>";
            echo $_SESSION['message'];
            echo "</div>";
            unset($_SESSION['message']);
        }
        ?>

        <h2>Login</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password">
            </div>

            <button type="submit" name="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$db_name = 'your_database';

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Session start
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Query to check username and password
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        
        // Execute the query
        $stmt->execute();
        
        // Store result
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Start session and store user data
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Incorrect password!";
            }
        } else {
            echo "Username not found!";
        }
        
        // Close statement
        $stmt->close();
    }

    // Close connection
    $conn->close();
}
?>


$hashed_password = password_hash($password, PASSWORD_DEFAULT);


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $loginEmailOrUsername = $_POST['email_or_username'];
        $loginPassword = $_POST['password'];

        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $loginEmailOrUsername);
        $stmt->bindParam(':email', $loginEmailOrUsername);
        $stmt->execute();

        // Check if user exists
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($loginPassword, $user['password'])) {
                // Password is correct, start session
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                // Redirect to dashboard or other page
                header("Location: dashboard.php");
                die();
            } else {
                // Password is incorrect
                echo "Incorrect password!";
            }
        } else {
            // User not found
            echo "User not found!";
        }
    }

} catch(PDOException $e) {
    // Handle any database errors
    echo "Error: " . $e->getMessage();
}

// Close the connection
$conn = null;
?>


<?php
session_start();
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "Please fill in all fields";
        exit();
    }

    // Sanitize the input
    $email = htmlspecialchars(strip_tags(trim($email)));

    try {
        // Prepare a statement
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        // Check if user exists
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            echo "User does not exist";
            exit();
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            echo "Incorrect password";
            exit();
        }

        // If login is successful, create a session
        $_SESSION['id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];

        // Optional: Set a cookie for the user to stay logged in
        setcookie('remember_me', $user['id'], time() + 3600); 

        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        die("Could not connect to the database " . $e->getMessage());
    }
}
?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$username_db = 'root'; // Change to your database username
$password_db = '';     // Change to your database password
$db_name = 'testdb';

// Connect to the database
$conn = mysqli_connect($host, $username_db, $password_db, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";

if (isset($_POST['submit'])) {
    // Sanitize inputs
    $username_email = mysqli_real_escape_string($conn, $_POST['username_email']);
    $password = md5($_POST['password']); // Hash the password

    // Check if either username or email is provided
    $sql = "SELECT * FROM users WHERE username='$username_email' OR email='$username_email'";
    
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    $row = mysqli_fetch_assoc($result);
    
    // Check if account exists
    if (mysqli_num_rows($result) == 1) {
        // Verify password
        if ($password == $row['password']) {
            // Start session and store user data
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            
            // Handle Remember Me functionality
            if (isset($_POST['remember_me'])) {
                $token = bin2hex(random_bytes(32)); // Generate random token
                $cookie_expire = time() + 60 * 60 * 24 * 30; // Cookie expires in 30 days
                
                // Update the user's token in database
                $update_sql = "UPDATE users SET remember_token='$token' WHERE id=" . $_SESSION['id'];
                mysqli_query($conn, $update_sql);
                
                // Set cookies
                setcookie('username', $row['username'], $cookie_expire, '/');
                setcookie('remember_token', $token, $cookie_expire, '/');
            }
            
            // Redirect to dashboard or welcome page
            header("Location: welcome.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Username/Email not found! Please register.";
    }
}

mysqli_close($conn);
?>


<?php
session_start();
include('db_connection.php'); // Include your database connection file

function loginUser($usernameOrEmail, $password) {
    global $conn;

    // Sanitize input to prevent SQL injection
    $usernameOrEmail = filter_var($usernameOrEmail, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    // Check if username or email exists in the database
    $query = "SELECT * FROM users WHERE username = '$usernameOrEmail' OR email = '$usernameOrEmail'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct

            // Set session variables
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role']; // Assuming you have a role column for access control

            return true;
        } else {
            // Password is incorrect
            return "Invalid password";
        }
    } else {
        // Username or email not found
        return "Username or email not found";
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usernameOrEmail = $_POST['username_or_email'];
    $password = $_POST['password'];

    $loginResult = loginUser($usernameOrEmail, $password);

    if ($loginResult === true) {
        header("Location: dashboard.php"); // Redirect to dashboard
        exit();
    } else {
        echo $loginResult; // Display error message
    }
}
?>


// Add this after successful login
if (isset($_POST['remember'])) {
    // Set expiration time for 1 month
    $expire = time() + 30 * 24 * 60 * 60;

    // Generate a random token
    $token = bin2hex(random_bytes(32));

    // Store the token in cookies
    setcookie('remember_token', $token, $expire);

    // Store the token in database
    $userId = $_SESSION['id'];
    $query = "UPDATE users SET remember_token='$token' WHERE id='$userId'";
    mysqli_query($conn, $query);
}


<?php
// Database configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$database = 'test';

// Connect to the database
$conn = new mysqli($host, $username_db, $password_db, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session
session_start();

// Login function
function loginUser() {
    global $conn;

    // Check if form is submitted
    if (isset($_POST['login'])) {
        // Sanitize input data
        $username_email = mysqli_real_escape_string($conn, $_POST['username_email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Validate input data
        if (empty($username_email) || empty($password)) {
            $_SESSION['message'] = "Please fill in all fields!";
            header("Location: login.php");
            exit();
        }

        // Query the database for user with provided username or email
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
        $stmt->bind_param("ss", $username_email, $username_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Create session variables
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['logged_in'] = true;
                $_SESSION['login_time'] = time();

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                $_SESSION['message'] = "Incorrect password!";
                header("Location: login.php");
                exit();
            }
        } else {
            // User not found
            $_SESSION['message'] = "Username or email does not exist!";
            header("Location: login.php");
            exit();
        }

        $stmt->close();
    }
}

// Close database connection
$conn->close();
?>


<?php
// Database configuration
$host = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "mydatabase";

// Connect to the database
$conn = mysqli_connect($host, $db_username, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session
session_start();

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and trim input values
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Prepare the SQL statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM users WHERE username = ?");
    
    if ($stmt === false) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "s", $username);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Store result
    $result = mysqli_stmt_get_result($stmt);

    // Check if a user was found
    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            $error_message = "Incorrect username or password";
        }
    } else {
        // User not found
        $error_message = "Incorrect username or password";
    }

    mysqli_stmt_close($stmt);
}

// Close database connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        
        <?php
        if (isset($error_message)) {
            echo "<div class='error-message'>$error_message</div>";
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="text" placeholder="Enter your username" name="username" required>
            </div>

            <div class="form-group">
                <input type="password" placeholder="Enter your password" name="password" required>
            </div>

            <button type="submit">Login</button>
        </form>

        <p style="text-align: center; margin-top: 20px;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</body>
</html>


<?php
// Database configuration and connection code here...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and trim input values
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)");

    if ($stmt === false) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        echo "Registration successful! Please login.";
        header("Location: login.php");
        exit();
    } else {
        die("Registration failed: " . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);
}
?>


<?php
// Start the session
session_start();

// Database configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'my_database';

// Connect to the database
$conn = mysqli_connect($host, $db_username, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Query to check if username exists
    $sql = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        // User exists, fetch the user data
        $row = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct, start session
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];
            $_SESSION['start'] = time();
            
            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            $error_message = "Incorrect username or password";
        }
    } else {
        // Username does not exist
        $error_message = "Incorrect username or password";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>


if (isset($_POST['remember'])) {
    $cookie_username = $_POST['username'];
    setcookie("username", $cookie_username, time() + 3600); // Cookie expires after 1 hour
}


<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['role']);
session_unset();
session_destroy();
header("Location: login.php");
exit();
?>


<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    // Database connection parameters
    $host = 'localhost';
    $db_username = 'root';  // Change to your DB username
    $db_password = '';      // Change to your DB password
    $database = 'login_system';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$database", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare statement to select user
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Start session and set user data
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['id'] = $user['id'];

                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Incorrect password";
            }
        } else {
            $error = "Username not found";
        }

    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// If no error, redirect or show form again
if (!isset($error)) {
    header("Location: login_form.html");
} else {
    // Include the form with error message
    include('login_form.html');
}
?>


<?php
// Database configuration
$host = "localhost";
$username_db = "root"; // Replace with your database username
$password_db = "";     // Replace with your database password
$db_name = "user_login";

// Connect to the database
$conn = mysqli_connect($host, $username_db, $password_db, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function login_user() {
    global $conn;

    // Check if form is submitted
    if (isset($_POST['login_submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate input
        if (empty($email) || empty($password)) {
            return "Please fill in all fields!";
        }

        // Sanitize inputs to prevent SQL injection
        $email = trim(htmlspecialchars(stripslashes($email)));
        $password = trim(htmlspecialchars(stripslashes($password)));

        // Check if email exists in the database
        $query = "SELECT * FROM users WHERE email = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);

        mysqli_execute($stmt);
        $result = mysqli_get_result($stmt);

        // Check if user exists
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                session_start();
                
                // Store user data in session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['login_time'] = time();

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                return "Incorrect password!";
            }
        } else {
            return "User with this email doesn't exist!";
        }
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
session_start();
require_once('db_connect.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    if (empty($username) && empty($email)) {
        die("Please enter username or email");
    }
    if (empty($password)) {
        die("Please enter password");
    }

    // Connect to database
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the query
    $input = !empty($username) ? $username : $email;
    $column = !empty($username) ? 'username' : 'email';
    
    $sql = "SELECT * FROM users WHERE $column = '" . mysqli_real_escape_string($conn, $input) . "'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        die("Username or email is incorrect");
    }

    $user = mysqli_fetch_assoc($result);
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        die("Password is incorrect");
    }

    // Start session and store user data
    session_regenerate(true); // Prevent session fixation
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];

    // Close the database connection
    mysqli_close($conn);

    // Redirect to dashboard or home page
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p>{$_SESSION['message']}</p>";
        unset($_SESSION['message']);
    }
    ?>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Username or Email: <input type="text" name="username" placeholder="Enter username or email"><br>
        Password: <input type="password" name="password" placeholder="Enter password"><br>
        <input type="submit" value="Login">
    </form>

    <a href="register.php">Create Account</a> |
    <a href="reset_password.php">Forgot Password?</a>
</body>
</html>


<?php
session_start();
include('db_connection.php'); // Include your database connection file

function user_login() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = mysqli_real_escape_string($GLOBALS['conn'], $_POST['username']);
        $password = $_POST['password'];

        // Check if username exists
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        
        if (mysqli_num_rows($result) == 0) {
            echo "<script>alert('Account does not exist!');</script>";
            return;
        }

        // Get user details
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            echo "<script>alert('Incorrect password!');</script>";
            return;
        }

        // On successful login
        $_SESSION['username'] = $username;
        $_SESSION['last_activity'] = time(); // Session timeout implementation

        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    }
}

// Call the function when form is submitted
if (isset($_POST['login'])) {
    user_login();
}
?>


<?php
// Database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$dbuser = 'your_database_user';
$dbpass = 'your_database_password';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$ dbname", $dbuser, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function loginUser($username, $password) {
        global $conn;

        // Check if username and password are provided
        if (empty($username) || empty($password)) {
            return "Username and password are required!";
        }

        try {
            // Prepare the query to prevent SQL injection
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Password is correct
                    session_start();
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $username;
                    
                    // Redirect to dashboard or home page
                    header("Location: dashboard.php");
                    exit();
                } else {
                    return "Incorrect password!";
                }
            } else {
                return "Username not found!";
            }

        } catch (PDOException $e) {
            return "Database error occurred: " . $e->getMessage();
        }
    }

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


// In your login form processing file (e.g., login.php)
session_start();
include 'database_connection.php'; // Include the above code

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = loginUser($username, $password);

    if ($result === true) {
        // Login successful
        header("Location: dashboard.php");
        exit();
    } else {
        // Display error message
        echo $result;
    }
}


<?php
// Database configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'login_system';

// Connect to database
$conn = mysqli_connect($host, $db_username, $db_password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']); // Hash password

    // Check if username exists in database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($row['password'] == $password) {
            // Password matches
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            // Password doesn't match
            $error_message = "Invalid username or password!";
        }
    } else {
        // Username doesn't exist
        $error_message = "Invalid username or password!";
    }
}

// Close database connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>

    <a href="register.php">Create an account</a>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'login_system';

// Connect to database
$conn = mysqli_connect($host, $db_username, $db_password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $username = $conn->real_escape_string($_POST['username']);
    
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify password using PHP's built-in password verification function
        if (password_verify($_POST['password'], $row['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Invalid username or password!";
        }
    } else {
        $error_message = "Invalid username or password!";
    }
}

// Close database connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>

    <a href="register.php">Create an account</a>
</body>
</html>


// When registering a user:
$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
// Store $password_hash in your database


<?php
session_start();
require_once 'db_connection.php'; // Include your database connection file

function user_login($username, $password) {
    global $conn;

    if (!isset($_POST['login'])) {
        return false;
    }

    // Sanitize input
    $username = htmlspecialchars(trim($username));
    $password = htmlspecialchars(trim($password));

    // Check if username and password are not empty
    if (empty($username) || empty($password)) {
        return "Username or password cannot be empty!";
    }

    try {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return "Invalid credentials!";
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            return "Invalid credentials!";
        }

        // Start session and store user data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['logged_in'] = true;

        return "Login successful!";

    } catch (PDOException $e) {
        // Handle any database errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = user_login($username, $password);

    if ($result === "Login successful!") {
        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    } else {
        // Display error message
        echo $result;
    }
}
?>


<?php
session_start();
try {
    // Database connection configuration
    $host = 'localhost';
    $dbname = 'your_database_name';
    $username = 'your_username';
    $password = 'your_password';

    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input data
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        // Prepare SQL statement to select user by username
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        // Check if a user exists
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Password is correct, start session and set session variables
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                // Secure the session
                ini_set('session.cookie_httponly', '1');
                if (isset($_SERVER['HTTPS'])) {
                    ini_set('session.cookie_secure', '1');
                }
                
                // Redirect to dashboard or other page after login
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                $error = "Invalid username or password";
            }
        } else {
            // No user found with that username
            $error = "Invalid username or password";
        }
    }

} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// If no post data, show login form
if (!isset($_POST)) {
    header("Location: login_form.php");
    exit();
}
?>


<?php
// Configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'login_example';

// Connect to MySQL database
$conn = mysqli_connect($host, $db_username, $db_password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

if (isset($_POST['submit'])) {
    // Sanitize input data
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    
    // Validate username and password
    if (empty($username) || empty($password)) {
        $error = "Username and password are required!";
    } else {
        // Query the database for user
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Start session and set session variables
                $_SESSION['username'] = $username;
                
                // Remember me functionality
                if ($_POST['remember_me']) {
                    $cookie_name = 'login_cookie';
                    $cookie_value = $username . '-' . md5($password);
                    setcookie($cookie_name, $cookie_value, time() + (7 * 24 * 60 * 60)); // Expiry in a week
                }
                
                // Redirect to dashboard or home page
                header('Location: dashboard.php');
                exit();
            } else {
                $error = "Invalid username or password!";
            }
        } else {
            $error = "Username not found!";
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <style>
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        form {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h2>Login</h2>
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username"><br>
            
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password"><br><br>
            
            <label><input type="checkbox" name="remember_me"> Remember Me</label><br><br>
            
            <button type="submit" name="submit">Login</button>
        </form><br>
        
        <p>Don't have an account? <a href="register.php">Register here.</a></p>
    </div>
</body>
</html>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username = 'root'; // Database username
$password = '';      // Database password
$dbname = 'login_system';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function for user login
function loginUser() {
    global $conn;

    // Check if form is submitted
    if (isset($_POST['login'])) {
        $email_username = $_POST['email_username'];
        $password = $_POST['password'];

        // Validate input
        if (empty($email_username) || empty($password)) {
            $_SESSION['error'] = "Please fill in all fields";
            header("Location: login.php");
            exit();
        }

        // Prepare and bind statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, "SELECT id, username, email, password FROM users WHERE username = ? OR email = ?");
        mysqli_stmt_bind_param($stmt, "ss", $email_username, $email_username);

        // Execute the query
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Start session and store user data
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];

                // Login successful
                $_SESSION['success'] = "Welcome back, " . $row['username'];
                header("Location: dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "Incorrect password";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "User does not exist";
            header("Location: login.php");
            exit();
        }
    }
}

// Call the login function
loginUser();

// Close database connection
mysqli_close($conn);
?>


<?php
// Start session
session_start();

// Database connection (replace with your database credentials)
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'user_login';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['login'])) {
    // Get user input
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    // Hash password for security
    $hashed_password = hash('sha256', $password);

    // Query to check if username exists in database
    $sql = "SELECT * FROM users WHERE username = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get user details
        $user_data = $result->fetch_assoc();

        // Compare hashed passwords
        if (hash('sha256', $password) === $user_data['password']) {
            // Login successful, start session and redirect
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['id'] = $user_data['id'];

            header("Location: welcome.php");
            exit();
        } else {
            // Password does not match
            echo "<script>alert('Incorrect password!');</script>";
        }
    } else {
        // Username does not exist
        echo "<script>alert('Username does not exist!');</script>";
    }

    $stmt->close();
}

$conn->close();

// Display login form if not logged in
if (!isset($_SESSION['loggedin'])) {
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Page</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <h2 class="text-center mb-4">Login</h2>
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
    </html>
<?php
}
?>



<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Welcome <?php echo $_SESSION['username']; ?>!</h2>
        <a href="logout.php" class="btn btn-primary">Logout</a>
    </div>
</body>
</html>


<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: login.php");
exit();
?>


<?php
session_start();

// Database configuration
$host = "localhost";
$user = "username";
$password = "password";
$db_name = "database_name";

// Connect to database
$conn = new mysqli($host, $user, $password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user($username, $password, $conn) {
    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        return array(
            'status' => false,
            'message' => "Username and password are required!"
        );
    }

    try {
        // Prepare statement to select user data
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        // Get result
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verify password (password_hash should be used in production)
            $hashed_password = hash('sha256', $password);
            
            if ($user['password'] == $hashed_password) {
                // Password is correct
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                return array(
                    'status' => true,
                    'message' => "Login successful!"
                );
            } else {
                // Incorrect password
                return array(
                    'status' => false,
                    'message' => "Invalid username or password!"
                );
            }
        } else {
            // User not found
            return array(
                'status' => false,
                'message' => "Invalid username or password!"
            );
        }
    } catch (Exception $e) {
        // Error occurred
        return array(
            'status' => false,
            'message' => "An error occurred: " . $e->getMessage()
        );
    }
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Call login function
    $result = login_user($username, $password, $conn);
    
    if ($result['status']) {
        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    } else {
        echo $result['message'];
    }
}

// Close database connection
$conn->close();
?>


<?php
// Include database configuration file
include('config.php');

function loginUser() {
    // Check if form is submitted
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            // Prepare SQL statement to select user based on username
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            // Check if user exists
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Password is correct, start session and redirect to dashboard
                    session_start();
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['logged_in'] = true;
                    
                    // Optional: Set remember me cookie
                    if (isset($_POST['remember'])) {
                        setcookie('username', $username, time() + 3600 * 24 * 7, '/');
                        setcookie('password', $password, time() + 3600 * 24 * 7, '/');
                    }
                    
                    // Redirect to dashboard
                    header("Location: dashboard.php");
                    exit();
                } else {
                    // Password is incorrect
                    echo "Incorrect password!";
                }
            } else {
                // User does not exist
                echo "Username does not exist!";
            }
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}

// Create login form HTML
function loginForm() {
    ?>
    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="checkbox" name="remember"> Remember me
        <button type="submit">Login</button>
    </form>
    <?php
}

// Main login function
function main() {
    // Show login form if not logged in
    session_start();
    
    if (!isset($_SESSION['logged_in'])) {
        loginUser();
        loginForm();
    } else {
        header("Location: dashboard.php");
        exit();
    }
}

main();
?>


$hashed_password = password_hash($password, PASSWORD_DEFAULT);
// Store $hashed_password in database


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database';
$username = 'your_username';
$password = 'your_password';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $username_login = $_POST['username'];
        $password_login = $_POST['password'];

        // Prepare and execute SQL statement
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username_login);
        $stmt->execute();

        // Fetch user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password_login, $user['password'])) {
                // Start session and store user data
                session_start();
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                
                // Optional: Set remember me cookie
                if (!empty($_POST['remember_me'])) {
                    $cookie_name = 'login_cookie';
                    $cookie_value = $username_login . '|' . md5($password_login);
                    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
                }

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Invalid username or password";
            }
        } else {
            echo "Invalid username or password";
        }
    }
} catch(PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <!-- Add security headers -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'">
    <meta http-equiv="X-Content-Type" content="nosniff">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="checkbox" id="remember_me" name="remember_me">
            <label for="remember_me">Remember me</label>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>


<?php
session_start();

// Database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Connect to database using PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input and sanitize them
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Prevent SQL injection by using prepared statements
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user session data
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <!-- Add your CSS styles here -->
    <style>
        .login-form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <?php if (isset($error)) { ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php } ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>


// Set session cookie parameters
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Only use sessions over HTTPS
ini_set('session.use_strict_mode', 1);


<?php
session_start();
unset($_SESSION['loggedin']);
unset($_SESSION['id']);
unset($_SESSION['username']);
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>


<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit();
}

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'mydatabase';

// Connect to database
$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = "";
$password = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Function to sanitize input
    function sanitize_input($data) {
        return htmlspecialchars(mysqli_real_escape_string($conn, trim($data)));
    }

    // Get username and password from form
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error_message = "Username or password cannot be empty!";
    } else {
        // Prepare query to select user with given username
        $stmt = $conn->prepare("SELECT id, username, password, admin FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Get user data
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                $_SESSION['username'] = $user['username'];
                $_SESSION['admin'] = $user['admin'];
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                $error_message = "Invalid username or password!";
            }
        } else {
            // No user found with this username
            $error_message = "Invalid username or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .login-form {
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <?php if (!empty($error_message)) { ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php } ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" value="<?php echo $password; ?>">
            </div>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>


$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
// Insert into database using prepared statement


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create a database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Function to handle user login
function loginUser($username, $password, $conn) {
    try {
        // Prepare and execute SQL statement to select user with given username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        // Fetch the result
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['userid'] = $user['id'];
                
                return true;
            } else {
                // Incorrect password
                return false;
            }
        } else {
            // User does not exist
            return false;
        }
    } catch(PDOException $e) {
        die("An error occurred: " . $e->getMessage());
    }
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Call login function
    if (loginUser($username, $password, $conn)) {
        header("Location: dashboard.php");
        die();
    } else {
        echo "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login Page</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($host, $username_db, $password_db, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;

    // Get user input
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Sanitize the email
    $email = mysqli_real_escape_string($conn, $email);

    // Check if user exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows == 0) {
        return "User not found!";
    }

    // Get the user data
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
        // Create session variables
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['admin'] = $user['is_admin'];

        return "Login successful!";
    } else {
        return "Incorrect password!";
    }
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Call the login function
        $login_result = login_user();
        
        if ($login_result === "Login successful!") {
            header("Location: dashboard.php");
            exit();
        } else {
            echo $login_result;
        }
    } catch (Exception $e) {
        die("An error occurred: " . $e->getMessage());
    }
}
?>


<?php
function register_user() {
    global $conn;

    // Get user input
    $username = htmlspecialchars($_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return "Email already exists!";
    }

    // Insert new user into the database
    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$hashed_password')";
            
    if ($conn->query($sql)) {
        return "Registration successful! You can now login.";
    } else {
        return "Registration failed. Please try again.";
    }
}
?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'username';
$password = 'password';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Sanitize input (optional but recommended)
        $email = htmlspecialchars(trim($email));
        $password = htmlspecialchars(trim($password));

        // Prepare SQL statement to check user credentials
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct; start session and set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                header("Location: login.php?error=incorrect_credentials");
                exit();
            }
        } else {
            // User not found
            header("Location: login.php?error=incorrect_credentials");
            exit();
        }
    } else {
        // If form was not submitted, redirect to login page
        header("Location: login.php");
        exit();
    }

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


// When registering a new user:
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
// Store $hashed_password in the database


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function login_user() {
    global $conn;

    // Get form data
    if (isset($_POST['login'])) {
        $username = trim(htmlspecialchars(mysqli_real_escape_string($conn, $_POST['username'])));
        $password = trim(htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password'])));

        // Check if username exists in the database
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Password is correct
                session_start();
                
                // Set session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $row['id'];
                
                // Generate new token to prevent session hijacking
                $_SESSION['token'] = bin2hex(random_bytes(32));
                
                // Unset cookies
                setcookie('PHPSESSID', '', time() - 3600, '/');
                session_regenerate(true);
                
                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                $error = "Invalid username or password!";
                echo $error;
            }
        } else {
            // Username does not exist
            $error = "Invalid username or password!";
            echo $error;
        }
    }
}

// Call the login function
login_user();

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    
    <?php 
    if (isset($_GET['error'])) {
        echo "<p style='color: red;'>".$_GET['error']."</p>";
    }
    ?>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input
        $username = htmlspecialchars(trim($_POST['username']));
        $password = htmlspecialchars(trim($_POST['password']));

        // Check if both fields are filled
        if (empty($username) || empty($password)) {
            die("Username and password are required!");
        }

        // Prepare statement to select user from database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        // Fetch the result
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and password matches
        if ($user && password_verify($password, $user['password'])) {
            // Start session
            session_start();
            
            // Store user data in session
            $_SESSION['username'] = $user['username'];
            $_SESSION['id'] = $user['id'];

            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Incorrect username or password
            echo "Incorrect username or password!";
        }
    }

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Username:<br>
        <input type="text" name="username">
        <br>
        Password:<br>
        <input type="password" name="password">
        <br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'user_login';

// Sanitize input data
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    // Connect to the database
    $conn = new mysqli($host, $username_db, $password_db, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prevent SQL injection by using prepared statements
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user_data['password'])) {
            // Start session and set session variables
            session_start();
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['user_id'] = $user_data['id'];
            
            // Regenerate session ID to prevent session fixation attacks
            session_regenerate(true);
            
            // Set CSRF token for security
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            
            // Redirect to dashboard or home page after successful login
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            echo "Invalid username or password!";
        }
    } else {
        // Username does not exist
        echo "Invalid username or password!";
    }

    $stmt->close();
    $conn->close();
}
?>


<?php
session_start();

// Database connection
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'user_login';

$conn = new mysqli($host, $username_db, $password_db, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Register function
function registerUser($name, $email, $username, $password, $conn) {
    // Sanitize inputs
    $name = sanitizeInput($name);
    $email = sanitizeInput($email);
    $username = sanitizeInput($username);
    $password = sanitizeInput($password);

    // Validate inputs
    if (empty($name) || empty($email) || empty($username) || empty($password)) {
        return "All fields are required!";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format!";
    }

    // Check if username or email already exists
    $checkQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Username or email already exists!";
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $insertQuery = "INSERT INTO users (name, email, username, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssss", $name, $email, $username, $hashedPassword);

    if ($stmt->execute()) {
        return "Registration successful! You can now login.";
    } else {
        return "Error registering user!";
    }
}

// Login function
function loginUser($emailOrUsername, $password, $conn) {
    // Sanitize inputs
    $emailOrUsername = sanitizeInput($emailOrUsername);
    $password = sanitizeInput($password);

    // Validate inputs
    if (empty($emailOrUsername) || empty($password)) {
        return "Please fill in all fields!";
    }

    // Check if email or username exists
    $selectQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($selectQuery);
    $stmt->bind_param("ss", $emailOrUsername, $emailOrUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Check if user is active
            if ($user['status'] == 'active') {
                // Start session and store user data
                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
                
                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                return "Your account has been disabled!";
            }
        } else {
            return "Incorrect password!";
        }
    } else {
        return "Username or email does not exist!";
    }
}

// Logout function
function logoutUser() {
    // Unset all session variables
    session_unset();
    
    // Destroy the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    header("Location: login.php");
    exit();
}
?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'test';

// Connect to database
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Handle login form submission
if (isset($_POST['login'])) {
    // Get and sanitize input data
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        // Verify password
        if (password_verify($password, $hashedPassword)) {
            // Password is correct
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];

            // Set cookie for remember me functionality
            $cookie_name = "login";
            $cookie_value = serialize(array(
                'email' => $row['email'],
                'id' => $row['id']
            ));
            setcookie($cookie_name, $cookie_value, time() + 3600 * 24 * 7); // Cookie expires after a week

            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            echo "Incorrect password!";
        }
    } else {
        // User does not exist
        echo "User does not exist!";
    }

    $stmt->close();
}

// Close database connection
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <h2>Login Form</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" name="login" value="Login">
    </form>
</body>
</html>


<?php
session_start();
// Unset all session variables
$_SESSION = array();

// If using cookies, delete them as well
if (isset($_COOKIE['login'])) {
    setcookie('login', '', time() - 3600);
}

// Destroy the session
session_destroy();
header("Location: login.php");
exit();
?>


<?php
// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'my_database';

// Function to connect to the database
function loginDB() {
    global $host, $user, $password, $database;
    
    // Create connection
    $conn = new mysqli($host, $user, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to login user
function loginUser($username, $password) {
    global $host, $user, $password, $database;
    
    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        return false;
    }
    
    // Connect to database
    $conn = loginDB();
    
    // SQL injection prevention using mysqli_real_escape_string()
    $username = $conn->real_escape_string($username);
    $password = md5($conn->real_escape_string($password)); // Store password as MD5 hash
    
    // Query the database for user
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        // Start session and set session variables
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        
        return true;
    } else {
        return false;
    }
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
}

// Function to logout user
function logoutUser() {
    session_start();
    unset($_SESSION['logged_in']);
    unset($_SESSION['username']);
    session_destroy();
    
    // Redirect to login page
    header("Location: login.php");
}

// Example usage:
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (loginUser($username, $password)) {
        echo "Login successful!";
        // Redirect to dashboard
        header("Location: dashboard.php");
    } else {
        echo "Invalid username or password!";
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$username_db = 'root'; // Replace with your database username
$password_db = '';     // Replace with your database password
$database = 'testdb';  // Replace with your database name

// Create a connection to the database
$conn = new mysqli($host, $username_db, $password_db, $database);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle user login
function loginUser($username, $password, $conn) {
    // Sanitize input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query the database for the user
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct, start a session
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $row['id'];
            return true;
        } else {
            // Incorrect password
            return "Incorrect password";
        }
    } else {
        // User does not exist
        return "User does not exist";
    }
}

// Check if form is submitted
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = loginUser($username, $password, $conn);

    if ($result === true) {
        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    } else {
        // Display error message
        echo "<div style='color:red;'>$result</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>LOGIN</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br><br>
        Password: <input type="password" name="password"><br><br>
        <input type="submit" name="login" value="Login">
    </form>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>


<?php
session_start();
// Destroy session variables
unset($_SESSION['username']);
unset($_SESSION['id']);
// Destroy the entire session
session_destroy();
// Redirect to login page
header("Location: login.php");
exit();
?>


<?php
// Include necessary files (e.g., database configuration)
include_once("header.php");
include_once("config.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input data to prevent SQL injection
    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);

    // Connect to database
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Please fill in all fields!';
        header('Location: login.php');
        exit();
    }

    // Prepare SQL query to select user data
    $sql = "SELECT * FROM users WHERE username = ?";
    
    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);

    // Execute the query
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Start session and store user data in session variables
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['login_time'] = time();

            // Redirect to dashboard or home page
            header('Location: dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = 'Invalid username or password!';
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'User does not exist!';
        header('Location: login.php');
        exit();
    }

    // Close database connection
    mysqli_close($conn);
}
?>


<?php
function user_login($username, $password) {
    try {
        // Database connection parameters
        $db_host = 'localhost';
        $db_user = 'root';
        $db_pass = '';
        $db_name = 'mydatabase';

        // Create a database connection
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Escape special characters to prevent SQL injection
        $username = $conn->real_escape_string($username);

        // Query the database for the user's information
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($conn->error) {
            throw new Exception("Query error: " . $conn->error);
        }

        // Check if a user exists with that username
        if ($result->num_rows == 0) {
            return false;
        }

        // Fetch the user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (!password_verify($password, $user['password'])) {
            return false;
        }

        // Start a session and store user data in session variables
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['login_time'] = time();

        // Close the database connection
        $conn->close();

        return true;

    } catch (Exception $e) {
        // Handle exceptions and log errors if necessary
        echo "An error occurred: " . $e->getMessage();
        return false;
    }
}
?>


<?php
session_start();

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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Escape special characters to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Hash the password (using md5 for this example - consider using stronger hashing in production)
    $hash_password = md5($password);

    // Query to check user credentials
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        
        if (mysqli_num_rows($result) > 0) {
            // Verify password
            if ($hash_password == $row['password']) {
                // Set session variables
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                
                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "Username not found!";
        }
    } else {
        echo "Error executing query!";
    }

    mysqli_close($conn);
}
?>


<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'mydatabase';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query database for user with matching email
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // User exists, get user data
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            $error = "Incorrect email or password";
        }
    } else {
        // User does not exist
        $error = "Incorrect email or password";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'user_login';

// Connect to database
$conn = mysqli_connect($host, $db_username, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from form
    $username_email = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Sanitize the inputs
    $username_email = mysqli_real_escape_string($conn, $username_email);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if username or email exists in database
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username_email, $username_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Start session
            session_start();
            
            // Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['login_time'] = time();

            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Invalid username or password";
    }

    mysqli_close($conn);
}
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'mydatabase';

// Connect to database
$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];

    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        echo "Please fill in all fields";
        exit();
    }

    // Query the database for the user
    $sql = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }

    $user_data = mysqli_fetch_assoc($result);

    // Check if user exists and password is correct
    if ($user_data && password_verify($password, $user_data['password'])) {
        // Set session variables
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['logged_in'] = true;

        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid username or password";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login Form</h2>
    <form action="login.php" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>


<?php
// Include database configuration file
include('config.php');

// Initialize variables
$username = $password = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];

    // Check if username and password are not empty
    if (empty($username) || empty($password)) {
        $error = "Username and password are required!";
    } else {
        // Query to check if username exists in the database
        $query = "SELECT * FROM users WHERE username = '" . $username . "'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Password is correct
                session_start();
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['id'];
                
                // Optional: Set a cookie for remember me functionality
                setcookie('username', $row['username'], time() + 3600);
                setcookie('session_id', session_id(), time() + 3600);

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                $error = "Incorrect username or password!";
            }
        } else {
            // Username does not exist
            $error = "Incorrect username or password!";
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .login-container {
            width: 300px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .login-container h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if ($error != "") { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>

    <script>
        // Optional: Add client-side validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const username = this.username.value;
            const password = this.password.value;

            if (username === '' || password === '') {
                alert('Please fill in all fields!');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>


<?php
session_start();

// Database configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$database_name = 'mydb';

// Create connection
$conn = mysqli_connect($host, $username_db, $password_db, $database_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if username exists in database (either as username or email)
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param('ss', $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get user data
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        // Verify password
        if (password_verify($password, $stored_password)) {
            // Password is correct
            // Start session and store user data in session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['logged_in'] = true;

            // Redirect to dashboard or home page
            header('Location: dashboard.php');
            exit();
        } else {
            // Password is incorrect
            $error_message = "Incorrect password!";
        }
    } else {
        // Username/email not found in database
        $error_message = "Username or email does not exist!";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        .container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
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
    <div class="container">
        <?php
        if (isset($error_message)) {
            echo "<p style='color:red;'>$error_message</p>";
        }
        ?>
        <h3>Login Form</h3>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="username" placeholder="Username or Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>


// In your registration script
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);


<?php
// Start session
session_start();

// Include database connection file
include_once("db_connection.php");

function loginUser() {
    // Check if form is submitted
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate input
        if (empty($username) || empty($password)) {
            return "Please fill in all fields";
        }

        try {
            // Prepare statement to select user by username
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            
            // Get result
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (!$user) {
                return "Username does not exist";
            }

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                // Start session and store user data
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['email'] = $user['email'];

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                return "Incorrect password";
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}

// Call the function if form is submitted
if (isset($_POST['login'])) {
    $error = loginUser();
    if ($error) {
        echo "<div class='error'>$error</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username:<br>
        <input type="text" name="username" required><br>
        Password:<br>
        <input type="password" name="password" required><br><br>
        <input type="submit" name="login" value="Login">
    </form>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">User Login</h2>
        
        <?php
            // Database connection details
            $host = "localhost";
            $db_name = "your_database_name";
            $username = "your_username";
            $password = "your_password";

            try {
                // Create database connection
                $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if (isset($_POST['login'])) {
                    $input = $_POST['username_email'];
                    $pass = $_POST['password'];

                    // Prepare and execute query
                    $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ? OR email LIKE ?");
                    $stmt->bindValue(1, '%' . $input . '%', PDO::PARAM_STR);
                    $stmt->bindValue(2, '%' . $input . '%', PDO::PARAM_STR);
                    $stmt->execute();

                    // Check if any rows found
                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // Verify password
                        if (password_verify($pass, $row['password'])) {
                            // Start session and store user data
                            session_start();
                            $_SESSION['user_id'] = $row['id'];
                            $_SESSION['username'] = $row['username'];
                            $_SESSION['email'] = $row['email'];

                            // Redirect to dashboard
                            header("Location: dashboard.php");
                            exit();
                        } else {
                            echo "<div class='alert alert-danger'>Invalid username/password!</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Username or Email not found!</div>";
                    }
                }
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        ?>

        <form method="POST" action="<?php $_PHP_SELF; ?>">
            <div class="mb-3">
                <input type="text" name="username_email" class="form-control" placeholder="Enter username or email" required>
            </div>
            <div class="mb-4">
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </form>

        <p class="mt-3">Don't have an account? <a href="register.php">Register here</a></p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php
// Connect to the database
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "login";

$conn = mysqli_connect($host, $username_db, $password_db, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Sanitize input data
    $username = trim(mysqli_real_escape_string($conn, htmlspecialchars(stripslashes($_POST['username']))));
    $password = trim(mysqli_real_escape_string($conn, htmlspecialchars(stripslashes($_POST['password']))));

    // Query the database for the username and password
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if ($password == md5($row['password'])) {
            // Start the session and set session variables
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $row['id'];
            $_SESSION['logged_in'] = true;

            // Set message for success
            $_SESSION['message'] = "You are now logged in";
            header("location: dashboard.php");
            exit();
        } else {
            // Password doesn't match
            $_SESSION['message'] = "Invalid username or password";
            header("location: login.php");
            exit();
        }
    } else {
        // Username not found
        $_SESSION['message'] = "Invalid username or password";
        header("location: login.php");
        exit();
    }
}

// Close the database connection
mysqli_close($conn);
?>


<?php
session_start();
require_once('database_connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Please fill in both username and password";
        header("Location: login.php?error=$error");
        exit();
    }

    try {
        // Prepare SQL statement to select user by username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Set session cookie lifetime to 0 (session ends when browser closes)
                ini_set('session.cookie_lifetime', '0');
                
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                $error = "Invalid username or password";
                header("Location: login.php?error=$error");
                exit();
            }
        } else {
            // No user found
            $error = "Invalid username or password";
            header("Location: login.php?error=$error");
            exit();
        }
    } catch (Exception $e) {
        // Error occurred
        $error = "An error occurred during login. Please try again later.";
        header("Location: login.php?error=$error");
        exit();
    }
}

// Close database connection
$conn->close();
?>


<?php
// Start the session
session_start();

// Include database connection file
include 'db_connection.php';

// Check if form is submitted
if (isset($_POST['login'])) {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Validate input fields
    if (empty($username) || empty($password)) {
        $error = "All fields are required!";
    } else {
        // Query the database for user with this username
        $query = "SELECT * FROM users WHERE username = ?";
        
        // Prepare and bind statement
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_execute($stmt);
        
        // Get the result
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Check password
            if (password_verify($password, $row['password'])) {
                // Password is correct
                
                // Store user session variables
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                
                // Check for remember me
                if ($_POST['remember']) {
                    // Set up a cookie that will last for 30 days
                    setcookie('remember_me', $row['remember_token'], time() + (86400 * 30), "/");
                }
                
                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "Username not found!";
        }
    }
}

// Close database connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error ?></p>
    <?php } ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Username:</label><br>
        <input type="text" name="username"><br>
        
        <label>Password:</label><br>
        <input type="password" name="password"><br>
        
        <label><input type="checkbox" name="remember"> Remember me</label><br>
        
        <input type="submit" name="login" value="Login">
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>


<?php
// login.php

session_start();

// Database connection details
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'mydatabase';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get username and password from form
$username = $_POST['username'];
$password = $_POST['password'];

// Sanitize input to prevent SQL injection
$username = mysqli_real_escape_string($conn, $username);

// Query to check if username exists
$sql = "SELECT id, username, password FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User exists, get stored hash
    $row = $result->fetch_assoc();
    $stored_hash = $row['password'];
    
    // Verify password
    if (md5($password) === $stored_hash) {
        // Password correct
        
        // Start session and set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $row['id'];
        
        // Optional: Set remember me cookie
        if (isset($_POST['remember'])) {
            $cookie_name = "user_cookie";
            $cookie_value = md5($username . $password);
            setcookie($cookie_name, $cookie_value, time() + 3600); // Cookie valid for 1 hour
        }
        
        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    } else {
        // Password incorrect
        $error = "Invalid username or password!";
        header("refresh:2; url=login.html");
        echo "<div class='alert alert-danger mt-4' style='text-align:center;'>$error</div>";
        exit();
    }
} else {
    // Username not found
    $error = "Username does not exist!";
    header("refresh:2; url=login.html");
    echo "<div class='alert alert-danger mt-4' style='text-align:center;'>$error</div>";
    exit();
}

// Close database connection
$conn->close();
?>


<?php
// db_config.php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'login_system';

try {
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
} catch (Exception $e) {
    die("Connection Error: " . $e->getMessage());
}
?>


<?php
session_start();
include('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $username_email = mysqli_real_escape_string($conn, $_POST['username_email']);
    $password = $_POST['password'];

    // Query to check if username or email exists
    $sql = "SELECT * FROM users WHERE username='$username_email' OR email='$username_email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Start session and set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['login_time'] = time();
            
            // Regenerate session ID for security
            session_regenerate(true);
            
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Username or email not found!";
    }
} else {
    echo "Invalid request method!";
}

$conn->close();
?>


<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to your Dashboard, <?=$_SESSION['username']?>!</h1>
    <a href="logout.php">Logout</a>
</body>
</html>


<?php
session_start();
unset($_SESSION);
session_destroy();
header("Location: login.php");
exit();
?>


<?php
// Database configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'login_system';

// Connect to the database
$conn = mysqli_connect($host, $db_username, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session
session_start();

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize inputs
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;

            // Redirect to welcome page
            header("Location: welcome.php");
            exit();
        } else {
            // Incorrect password
            $error = "Incorrect username or password";
        }
    } else {
        // No user found
        $error = "Incorrect username or password";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .login-form {
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" name="submit">Login</button>
        </form>
    </div>
</body>
</html>


// Hash the password before storing it
$hashed_password = password_hash('testpassword', PASSWORD_DEFAULT);

mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('testuser', '$hashed_password')");


<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['submit'])) {
        // Check if form is submitted
        $email_username = $_POST['email_username'];
        $password = $_POST['password'];

        // Validate inputs
        if (empty($email_username) || empty($password)) {
            die("Please fill in all fields.");
        }

        // Query the database for user with provided email or username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $email_username);
        $stmt->bindParam(':email', $email_username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("Invalid credentials.");
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            die("Invalid credentials.");
        }

        // Start session and store user data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];

        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    }

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$database = 'login_system';

// Connect to database
$conn = mysqli_connect($host, $username_db, $password_db, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize and retrieve input data
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);

    // Query the database for user with matching username
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verify password
        if (md5($password) == $row['password']) {
            // Password is correct
            
            // Start session and store user data
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;

            // Redirect to dashboard or home page
            header('Location: dashboard.php');
            exit();
        } else {
            // Incorrect password
            echo "Incorrect username or password";
        }
    } else {
        // User not found
        echo "Incorrect username or password";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
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
        
        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
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
    <div class="login-container">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="text" placeholder="Username" name="username" required>
            <input type="password" placeholder="Password" name="password" required>
            <button type="submit" name="submit">Login</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();

// Database configuration
$host = 'localhost';
$user = 'root'; // Change this to your database username
$password = ''; // Change this to your database password
$database = 'login_system';

// Connect to the database
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;

    // Get input data
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate inputs
        if (empty($username) || empty($password)) {
            echo "Username and password are required!";
            return false;
        }

        // Sanitize input data
        $username = mysqli_real_escape_string($conn, $username);

        // Check if username exists in database
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user_data = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user_data['password'])) {
                // Password is correct
                $_SESSION['user_id'] = $user_data['id'];
                $_SESSION['username'] = $user_data['username'];
                
                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Password does not match
                echo "Incorrect password!";
            }
        } else {
            // Username doesn't exist
            echo "Username does not exist!";
        }

        $stmt->close();
    }
}

// Call the login function if form is submitted
if (isset($_POST['login'])) {
    login_user();
}
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'user_login';

// Connect to the database
$conn = mysqli_connect($host, $username_db, $password_db, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize the input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if username or email exists in the database
    $sql = "SELECT * FROM users WHERE username='$username' OR email='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Get user details
        $row = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Start session and store user data
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['admin'] = $row['is_admin'];

            // Redirect based on user role
            if ($_SESSION['admin'] == 1) {
                header("Location: admin_dashboard.php");
                exit();
            } else {
                header("Location: welcome.php");
                exit();
            }
        } else {
            echo "<div class='error'><i class='fas fa-exclamation-circle'></i> Incorrect password!</div>";
        }
    } else {
        echo "<div class='error'><i class='fas fa-exclamation-circle'></i> Username or email not found!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f3f3f3;
        }

        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 400px;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #666;
        }

        input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 0.8rem;
            background-color: #337ab7;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #286090;
        }

        .error {
            color: #d9534f;
            margin-bottom: 1rem;
            padding: 0.5rem;
            border-radius: 4px;
            background-color: #f2dede;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .success {
            color: #3c763d;
            margin-bottom: 1rem;
            padding: 0.5rem;
            border-radius: 4px;
            background-color: #dff0d8;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-links {
            text-align: center;
            margin-top: 1rem;
        }

        .form-links a {
            color: #337ab7;
            text-decoration: none;
        }

        .form-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        
        <?php
        // Show error/success messages here if needed
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username or Email:</label>
                <input type="text" id="username" name="username" required placeholder="Enter your username or email">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>

            <button type="submit" name="submit">Login</button>
        </form>

        <div class="form-links">
            <a href="#">Forgot Password?</a> |
            <a href="register.php">Create New Account</a>
        </div>
    </div>
</body>
</html>


// Registration function
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username or email already exists
    $check_sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($result) > 0) {
        echo "Username or email already exists!";
    } else {
        // Insert new user
        $insert_sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($conn, $insert_sql)) {
            echo "Registration successful! You can now login.";
        } else {
            echo "Error registering: " . mysqli_error($conn);
        }
    }
}


<?php
// Database configuration
$host = "localhost";
$dbname = "your_database_name";
$username = "your_username";
$password = "your_password";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login_user() {
    global $conn;

    // Get user input
    $email_username = $_POST['email_username'];
    $password = $_POST['password'];

    if (empty($email_username) || empty($password)) {
        return show_error("Both email/username and password are required");
    }

    // Sanitize inputs
    $email_username = mysqli_real_escape_string($conn, $email_username);
    $password = mysqli_real_escape_string($conn, $password);

    // Prepare SQL statement to check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $email_username, $email_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return show_error("Invalid credentials");
    }

    // Fetch user data
    $user = $result->fetch_assoc();

    // Verify password
    if (!password_verify($password, $user['password'])) {
        return show_error("Incorrect password");
    }

    // Set session variables
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];

    // Optional: Set last login time and update in database
    date_default_timezone_set("UTC");
    $last_login = date("Y-m-d H:i:s");
    $stmt = $conn->prepare("UPDATE users SET last_login = ? WHERE id = ?");
    $stmt->bind_param("si", $last_login, $user['id']);
    $stmt->execute();

    // Redirect to dashboard or home page
    header("Location: dashboard.php");
    exit();
}

function show_error($message) {
    echo "<div class='error'>$message</div>";
    return false;
}
?>


<?php
// Database connection details
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "user_login";

// Create connection
$conn = mysqli_connect($host, $username_db, $password_db, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input
    $username = stripslashes($username);
    $username = mysqli_real_escape_string($conn, $username);

    $password = stripslashes($password);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        $error = "Username or Password is invalid";
        echo "<script>alert('$error');</script>";
    } else {
        // Query the database for the user
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            // User exists, verify password
            $row = mysqli_fetch_assoc($result);
            $hash = $row['password'];
            
            // Verify the password using password_verify()
            if (password_verify($password, $hash)) {
                // Password is correct
                $_SESSION['username'] = $username;
                $_SESSION['login'] = true;
                $_SESSION['last_login'] = time();
                
                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                $error = "Invalid username or password";
                echo "<script>alert('$error');</script>";
            }
        } else {
            // User does not exist
            $error = "User does not exist";
            echo "<script>alert('$error');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <!-- Add CSS styles here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="text" name="username" placeholder="Enter username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</body>
</html>

// Close database connection
mysqli_close($conn);
?>


<?php
// Database connection details and session start
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Insert into database
    $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    mysqli_query($conn, $query);
    header("Location: login.php");
}
?>


<?php
// Database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create a PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Prepare SQL query to select user with given username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        // Fetch the result
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                session_start();
                
                // Store user details in session
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $user['id'];
                $_SESSION["username"] = $user['username'];

                // Redirect to dashboard or home page
                header("location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                echo "Incorrect password!";
            }
        } else {
            // User does not exist
            echo "User does not exist!";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


// logout.php
<?php
session_start();
unset($_SESSION["loggedin"]);
unset($_SESSION["id"]);
unset($_SESSION["username"]);
session_destroy();
header("location: login.php");
exit();
?>


<?php
// Start the session
session_start();

// Include database configuration file
include_once('db_config.php');

function loginUser() {
    // Check if form is submitted
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            // Prepare the query to fetch user data based on username
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->bindParam(1, $username);
            $stmt->execute();

            // Fetch user data
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                // Verify the password
                if (password_verify($password, $row['password'])) {
                    // Password is correct
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['logged_in'] = true;

                    return "Login successful!";
                } else {
                    // Incorrect password
                    return "Incorrect password. Please try again.";
                }
            } else {
                // User doesn't exist
                return "Username does not exist. Please register first.";
            }

        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    } else {
        // Empty fields
        return "Please fill in all required fields!";
    }
}

// Call the login function and store the result
$message = loginUser();

// Redirect to dashboard or show message
if (!empty($_SESSION['logged_in'])) {
    header("Location: dashboard.php");
} else {
    echo $message;
}
?>


<?php

// Database configuration
$servername = "localhost";
$username_db = "username";
$password_db = "password";
$dbname = "myDB";

function user_login($username, $password) {
    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        return array("error" => true, "message" => "Username and password are required");
    }

    // Database connection
    global $servername, $username_db, $password_db, $dbname;
    
    try {
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);
        
        if ($conn->connect_error) {
            return array("error" => true, "message" => "Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind
        $stmt = $conn->prepare("SELECT id, username, password, login_attempts, lockout_time FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);

        $stmt->execute();
        
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            return array("error" => true, "message" => "Username does not exist");
        }

        // Fetch the user data
        $user = $result->fetch_assoc();

        // Check for account lockout (too many failed attempts)
        if ($user['login_attempts'] >= 5 && time() < $user['lockout_time']) {
            return array("error" => true, "message" => "Account locked. Please try again later.");
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            // Update login attempts and lockout time if necessary
            $new_attempts = $user['login_attempts'] + 1;
            
            if ($new_attempts >= 5) {
                $lockout_time = time() + (5 * 60); // Lock for 5 minutes
            } else {
                $lockout_time = $user['lockout_time'];
            }

            $stmt_update = $conn->prepare("UPDATE users SET login_attempts = ?, lockout_time = ? WHERE id = ?");
            $stmt_update->bind_param("iii", $new_attempts, $lockout_time, $user['id']);
            $stmt_update->execute();

            return array("error" => true, "message" => "Incorrect password");
        }

        // Reset login attempts on successful login
        $stmt_reset = $conn->prepare("UPDATE users SET login_attempts = 0 WHERE id = ?");
        $stmt_reset->bind_param("i", $user['id']);
        $stmt_reset->execute();

        // Start session and store user data
        session_start();
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login_time'] = time();

        return array("error" => false, "message" => "Login successful");

    } catch (Exception $e) {
        return array("error" => true, "message" => "An error occurred: " . $e->getMessage());
    }
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = user_login($username, $password);

    if (!$result['error']) {
        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    } else {
        echo $result['message'];
    }
}

$conn->close();

?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

try {
    // Create connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize input data
        $username_login = $_POST['username'];
        $password_login = $_POST['password'];

        try {
            // Prepare and execute query to check user credentials
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username_login]);

            // Check if user exists
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                die('Username not found!');
            }

            // Verify password
            if (!password_verify($password_login, $user['password'])) {
                die('Incorrect password!');
            }

            // Start session and store user data
            session_start();
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            if ($user['role'] == 'admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: user_dashboard.php');
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

    }
} catch(PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>


// During registration:
$raw_password = $_POST['password'];
$hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);
// Store $hashed_password in your database

// When verifying login:
if (password_verify($entered_password, $hashed_password)) {
    // Password matches
} else {
    // Incorrect password
}


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function loginUser($pdo) {
    // Check if form was submitted
    if (!empty($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            // Prepare and execute the query
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            // Get the result as an associative array
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Check if user exists and password is correct
            if ($user && password_verify($password, $user['password'])) {
                // Start session
                session_start();
                
                // Store user data in session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['login_time'] = time();

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Invalid credentials
                $error_message = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            // Handle database errors
            die("An error occurred while accessing the database.");
        }
    }
    
    if (!empty($error_message)) {
        echo "<div class='alert alert-danger'>$error_message</div>";
    }
}

// Call the login function
loginUser($pdo);
?>


<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['logged_in'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Validate input
    if (empty($email) || empty($password)) {
        $error = "Please fill in all required fields!";
    } else {
        // Database connection (replace with your actual database credentials)
        $db_host = "localhost";
        $db_user = "username";
        $db_pass = "password";
        $db_name = "database";

        // Connect to MySQL server
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prevent SQL injection
        $email = $conn->real_escape_string($email);
        
        // Query the database for user with this email
        $sql = "SELECT id, password FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Create session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $row['id'];
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "Invalid email or password!";
        }

        // Close database connection
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 350px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if ($error != "") { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <input type="text" placeholder="Enter your email..." name="email">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter your password..." name="password">
            </div>
            <button type="submit" name="submit">Login</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        die("Username and password are required!");
    }

    // Connect to the database
    require_once 'db_connection.php';

    // Query the database for the user
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $stored_password_hash = $row['password'];

        // Verify the password
        if (password_verify($password, $stored_password_hash)) {
            // Password is correct
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;

            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            die("Incorrect password!");
        }
    } else {
        die("Username does not exist!");
    }

    // Close the database connection
    mysqli_close($conn);
}
?>


<?php
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$database = 'my_database';

$conn = mysqli_connect($host, $username_db, $password_db, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
// Use this hash when storing in your database


<?php
// Start session
session_start();

function user_login($username, $password) {
    // Database connection details
    $host = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "mydatabase";

    // Connect to database
    $conn = mysqli_connect($host, $db_username, $db_password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize input
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Prepare SQL query to select user with the given username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Get user data
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Create session variables
            $_SESSION['username'] = $user['username'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Close database connection
            mysqli_close($conn);

            return true;
        } else {
            // Incorrect password
            return false;
        }
    } else {
        // No user found with that username
        return false;
    }
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (user_login($username, $password)) {
        header("Location: dashboard.php");
    } else {
        echo "Invalid username or password";
    }
}
?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'login_system';

// Connect to database
$conn = mysqli_connect($host, $db_user, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Prepare and execute SQL query
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        // User not found
        echo "<script>alert('Username does not exist. Please try again.');
              window.setTimeout(function(){window.location.href='login.php';}, 1500);
              </script>";
        exit();
    }

    $user = mysqli_fetch_assoc($result);

    if (md5($password) !== $user['password']) {
        // Password is incorrect
        echo "<script>alert('Incorrect password. Please try again.');
              window.setTimeout(function(){window.location.href='login.php';}, 1500);
              </script>";
        exit();
    }

    // Start session and store user data
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_id'] = $user['id'];

    // Redirect to welcome page
    header("Location: welcome.php");
    exit();
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
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
        
        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        input[type='text'],
        input[type='password'] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Database connection parameters
    $host = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'login';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbName", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the query
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Start session and store user data
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: welcome.php");
                exit();
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "Email not found!";
        }

    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'mydatabase';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (isset($_SESSION['username'])) {
    // User is already logged in
    echo "Welcome back, ".$_SESSION['username']."!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Prepare SQL statement to select user with matching username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                session_regenerate(true); // Regenerate session ID to prevent fixation
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;

                // Optional: Set last login time
                date_default_timezone_set('UTC');
                $last_login = date('Y-m-d H:i:s');
                
                $stmt = $conn->prepare("UPDATE users SET last_login = :last_login WHERE id = :id");
                $stmt->execute([':last_login' => $last_login, ':id' => $user['id']]);

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                $_SESSION['error'] = "Invalid username or password";
            }
        } else {
            // Username not found
            $_SESSION['error'] = "Invalid username or password";
        }
    } catch (PDOException $e) {
        die("An error occurred: " . $e->getMessage());
    }
}

// Close database connection
$conn = null;

// Display login form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <?php if (isset($_SESSION['error'])) { ?>
        <div style="color: red;"><?=$_SESSION['error']?></div>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>

    <?php if (!isset($_SESSION['username'])) { ?>
        <a href="register.php">Create an account</a>
    <?php } ?>

    <?php if (isset($_SESSION['username'])) { ?>
        <a href="logout.php">Logout</a>
    <?php } ?>
</body>
</html>

<?php
// Optional: Destroy session data after request
if (!isset($_SESSION['username'])) {
    $_SESSION = array();
    session_unset();
    session_destroy();
}
?>


<?php
// Database connection settings
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'your_database';

// Create database connection
$conn = new mysqli($host, $user, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle user login
function loginUser() {
    global $conn;

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Sanitize input data
        $username = htmlspecialchars(strip_tags($username));
        $password = htmlspecialchars(strip_tags($password));

        // Check if username and password are provided
        if (empty($username) || empty($password)) {
            echo "Username or password cannot be empty.";
            return;
        }

        // Prepare SQL statement to select user
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $user_data = $result->fetch_assoc();

                // Verify password
                if (password_verify($password, $user_data['password'])) {
                    // Set session variables
                    session_start();
                    $_SESSION['user_id'] = $user_data['id'];
                    $_SESSION['logged_in'] = true;
                    
                    // Redirect to dashboard
                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "Incorrect password.";
                }
            } else {
                echo "Username does not exist.";
            }
        } else {
            echo "Error executing query: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Call the login function
loginUser();

// Close database connection
$conn->close();
?>


session_start();


<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit();
?>


<?php
session_start();

// Database configuration
$host = "localhost";
$usernameDb = "your_username";
$passwordDb = "your_password";
$database = "login_system";

// Connect to database
$conn = mysqli_connect($host, $usernameDb, $passwordDb, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5(mysqli_real_escape_string($conn, $_POST['password']));

    // Validate input fields
    if (empty($username) || empty($password)) {
        echo "Please fill in all required fields!";
    } else {
        // Check if user exists in database
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);
        $user_data = mysqli_fetch_assoc($result);

        if (!$user_data) {
            echo "User does not exist!";
        } else {
            // Verify password
            if ($password == $user_data['password']) {
                // Set session variables
                $_SESSION['username'] = $user_data['username'];
                $_SESSION['logged_in'] = true;

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Invalid password!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 500px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        input { width: 100%; padding: 8px; }
        button { background-color: #4CAF50; color: white; padding: 10px 16px; border: none; cursor: pointer; width: 100%; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Form</h2>
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <input type="text" placeholder="Enter your username..." name="username" required>
            </div>

            <div class="form-group">
                <input type="password" placeholder="Enter your password..." name="password" required>
            </div>

            <button type="submit" name="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>


<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>


<?php
// Start session
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "login_system";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Check if username exists in the database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Get user data
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Password is correct
            $_SESSION["username"] = $username;
            echo "Welcome " . $username . "! You have successfully logged in.";
            
            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Incorrect password
            echo "Incorrect username or password";
        }
    } else {
        // Username doesn't exist
        echo "Incorrect username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login Form</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


<?php
// Start session
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "login_system";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username already exists
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Username already exists!";
    } else {
        // Insert new user into the database
        $sql = "INSERT INTO users (username, password)
                VALUES ('$username', '$hashed_password')";
        
        if ($conn->query($sql)) {
            echo "New user created successfully! You can now login.";
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
</head>
<body>
    <h2>Registration Form</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>


<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'test_db');

function user_login($username, $password) {
    $result = array();
    
    // Connect to database
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        return;
    }
    
    try {
        // Prepare SQL query
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result()->fetch_assoc();
            
            if ($result) {
                // Verify password
                if (password_verify($password, $result['password'])) {
                    $result['status'] = 1;
                } else {
                    $result['status'] = 0;
                    $result['message'] = "Incorrect username or password";
                }
            } else {
                $result['status'] = 0;
                $result['message'] = "User not found";
            }
        } else {
            $result['status'] = 0;
            $result['message'] = "Error executing query";
        }
    } catch (Exception $e) {
        $result['status'] = 0;
        $result['message'] = "An error occurred: " . $e->getMessage();
    }
    
    // Close database connection
    mysqli_close($conn);
    
    return $result;
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $login_result = user_login($username, $password);
    
    if ($login_result['status'] == 1) {
        // Successful login
        session_start();
        $_SESSION['user_id'] = $login_result['id'];
        header("Location: dashboard.php");
    } else {
        // Display error message
        echo "Error: " . $login_result['message'];
    }
}
?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$dbname = 'mydatabase';

// Connect to database
$conn = mysqli_connect($host, $username_db, $password_db, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize input data
    $username = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['username']));
    $password = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password']));

    // Query to check if username exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        echo "Username does not exist!";
    } else {
        // Get user data
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (md5($password) === $user['password']) {
            // Start session and store user details
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="submit">Login</button>
    </form>
</body>
</html>


<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['username']);
unset($_SESSION['email']);
session_destroy();

header("Location: login.php");
exit();
?>


<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
    <p>Your email address is: <?php echo $_SESSION['email']; ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>


<?php
session_start();
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "login_system";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // SQL injection prevention using mysqli_real_escape_string
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if the username and password exist in the database
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        
        // Optional: Get user ID from the result
        $row = mysqli_fetch_assoc($result);
        $_SESSION['id'] = $row['id'];
        
        // Redirect to dashboard or home page after login
        header("Location: dashboard.php");
        exit();
    } else {
        // Incorrect password or username
        echo "Invalid username or password!";
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login Form</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


<?php
session_start();

// Database connection details
$host = 'localhost';
$user = 'root'; // MySQL username
$password = ''; // MySQL password
$dbname = 'mydatabase'; // Your database name

// Connect to MySQL database
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Sanitize and validate input
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    // Prevent SQL Injection using prepared statements
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();

    // Store result in a variable
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Start session and store user data
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            
            // Redirect to dashboard or home page
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Username not found!";
    }

    // Close statement and connection
    $stmt->close();
} else {
    // If form is not submitted, redirect to login page
    header('Location: index.html');
}

$conn->close();
?>


// Registration code (register.php)
session_start();

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param('ss', $username, $hashed_password);
    $stmt->execute();
    
    echo "Registration successful!";
    $stmt->close();
}

$conn->close();


<?php
function userLogin($username, $password, $db) {
    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        return false;
    }

    try {
        // Sanitize the input
        $username = htmlspecialchars(trim($username));
        $password = htmlspecialchars(trim($password));

        // Prepare SQL statement to prevent SQL injection
        $stmt = $db->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        // Check if a user exists with the provided username
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() != 1) {
            return false;
        }

        // Verify the password using PHP's built-in password verification function
        if (password_verify($password, $user['password'])) {
            // Password is correct
            return true;
        } else {
            // Password is incorrect
            return false;
        }
    } catch(PDOException $e) {
        // Log any errors to a file or database for debugging purposes
        error_log("Login Error: " . $e->getMessage());
        return false;
    }
}
?>


// Assuming $db is your PDO database connection
if (userLogin($_POST['username'], $_POST['password'], $db)) {
    // Start a session
    session_start();
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['id'] = $userId;  // Get user id from the database

    // Redirect to dashboard or home page
    header("Location: dashboard.php");
} else {
    // Show error message
    echo "Invalid username or password";
}


<?php
// Database configuration
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

try {
    // Create a database connection using PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get user input
        $email = trim($_POST['email']);
        $pass = trim($_POST['password']);

        // Validate input
        if (empty($email) || empty($pass)) {
            die("Email and password are required!");
        }

        // Prepare the SQL statement to select the user
        $stmt = $conn->prepare("SELECT id, email, password, name FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($pass, $user['password'])) {
                // Password is correct
                session_start();
                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                echo "Incorrect password!";
            }
        } else {
            // No user found with the given email
            echo "No account found with this email!";
        }
    }

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Email: <input type="text" name="email"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


<?php
// Database connection file
include('db_connection.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

    // Query to check user credentials
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Start session and store user details
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }

    if (isset($error)) {
        echo "<script>alert('$error');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
        .login-form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 10px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
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
    <div class="login-form">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>


<?php
session_start();
require_once('config.php'); // Include your database connection file

function sanitizeInput($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($GLOBALS['conn'], $data);
    return $data;
}

if (isset($_POST['login'])) {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        die("Username and password are required!");
    }

    // Query the database for the user
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        die("Invalid username or password!");
    }

    $user = mysqli_fetch_assoc($result);
    
    // Verify the password
    if (!password_verify($password, $user['password'])) {
        die("Invalid username or password!");
    }

    // Start session and store user data
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['login_time'] = time();

    // Regenerate the session ID to prevent session fixation attacks
    session_regenerate(true);

    // Redirect to dashboard or home page
    header("Location: dashboard.php");
    exit();
}
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username = 'root'; // Change to your database username
$password = '';      // Change to your database password
$db_name = 'my_login_db';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['login'])) {
    // Sanitize input data
    $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));
    $password = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password']));

    // Check if email exists in the database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Start session and store user data
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];

            // Redirect to dashboard or another page
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Email does not exist!";
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Function to sanitize input data
    function sanitizeInput($data) {
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Check if form is submitted
    if (isset($_POST['submit'])) {
        $email = sanitizeInput($_POST['email']);
        $pass = $_POST['password'];

        // Prepare query to check for existing user
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Verify password
            if (password_verify($pass, $row['password'])) {
                // Password is correct
                session_start();
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Incorrect password";
            }
        } else {
            echo "User does not exist. Please check your email address.";
        }
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


<?php
session_start();

// Database configuration
$host = "localhost";
$username_db = "root";
$password_db = "";
$db_name = "login_system";

// Connect to database
$conn = new mysqli($host, $username_db, $password_db, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    // Get form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($username) || empty($password)) {
        echo "Please fill in all fields";
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // User exists, verify password
            $user_data = $result->fetch_assoc();
            
            if (password_verify($password, $user_data['password'])) {
                // Password is correct
                $_SESSION['username'] = $user_data['username'];
                $_SESSION['logged_in'] = true;
                
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                echo "Incorrect password";
            }
        } else {
            // User does not exist
            echo "Username does not exist";
        }
    }
}

$conn->close();

// Display login form
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login Form</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>


<?php
session_start();
require_once 'config.php'; // Database configuration file

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email and password from form
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    try {
        // Connect to the database
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL query to fetch user data
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Start session and store user data
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['logged_in'] = true;

                // Remember me functionality
                if (!empty($_POST['remember'])) {
                    // Generate a random token
                    $token = bin2hex(random_bytes(16));
                    $encryptedToken = openssl_encrypt($token, 'AES-256-CBC', HASH_KEY, 0, IV_KEY);

                    setcookie('remember_token', $encryptedToken, time() + (86400 * 30), '/');

                    // Update token in database
                    $stmt = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                    $stmt->execute([$token, $user['id']]);
                }

                // Redirect to dashboard
                header('Location: dashboard.php');
                exit();
            } else {
                // Password is incorrect
                $_SESSION['error'] = "Invalid email or password.";
            }
        } else {
            // User doesn't exist
            $_SESSION['error'] = "Invalid email or password.";
        }

    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Check for remember me cookie
if (!empty($_COOKIE['remember_token'])) {
    try {
        // Connect to the database
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Decrypt token and check in database
        $encryptedToken = $_COOKIE['remember_token'];
        $token = openssl_decrypt($encryptedToken, 'AES-256-CBC', HASH_KEY, 0, IV_KEY);

        $stmt = $conn->prepare("SELECT * FROM users WHERE remember_token = ?");
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Start session and store user data
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged_in'] = true;

            // Redirect to dashboard
            header('Location: dashboard.php');
            exit();
        }

    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// If no valid session, redirect to login page
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
?>


<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

// Security keys for encryption
define('HASH_KEY', 'your_encryption_key');
define('IV_KEY', 'your_initialization_vector');
?>


<?php
session_start();
include('db_connection.php'); // Include your database connection file

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, htmlspecialchars($username));
    $password = mysqli_real_escape_string($conn, htmlspecialchars($password));

    // Check if username and password are not empty
    if (empty($username) || empty($password)) {
        echo "Username or password cannot be empty!";
        exit();
    }

    // Query the database to check user credentials
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            
            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Username not found!";
    }

    mysqli_close($conn);
}
?>


<?php
// Database connection constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mydatabase');

// Create database connection
function db_connect() {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
}

// User login function
function user_login($username, $password) {
    // Escape the inputs to prevent SQL injection
    $username = mysqli_real_escape_string(db_connect(), $username);
    $password = mysqli_real_escape_string(db_connect(), $password);

    // Check if username exists in database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = db_connect()->query($query);

    if (!$result->num_rows) {
        return false; // Username does not exist
    }

    // Verify password
    $user_data = $result->fetch_assoc();
    $hash = hash('sha256', $password . $user_data['salt']);

    if ($hash != $user_data['password']) {
        return false; // Password is incorrect
    }

    // Start session and set session variables
    session_start();
    $_SESSION['user_id'] = $user_data['id'];
    $_SESSION['username'] = $user_data['username'];
    $_SESSION['logged_in'] = true;

    if ($user_data['role'] == 'admin') {
        $_SESSION['is_admin'] = true;
    }

    return true; // Login successful
}

// Example usage:
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (user_login($username, $password)) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Invalid username or password!";
    }
}
?>


<?php
session_start();

// Database configuration
require_once('config.php');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the SQL query
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Start session and store user data
            $_SESSION['id'] = $user['id'];
            
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User does not exist!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        form {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
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
    <div class="container">
        <h2>Login Page</h2>
        
        <?php
        if (isset($error)) {
            echo "<p style='color: red;'>$error</p>";
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" name="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>


<?php
session_start();

// Database connection details
$host = 'localhost';
$dbUsername = 'root'; // Change this to your database username
$dbPassword = '';     // Change this to your database password
$dbName = 'login_system';

// Connect to the database
$conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function sanitizeInput($data) {
    $data = htmlspecialchars(strip_tags(trim($data)));
    return $data;
}

if (isset($_POST['login'])) {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    if ($username == "" || $password == "") {
        die("Please fill in all fields.");
    }

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Username not found.");
    }

    $row = $result->fetch_assoc();
    $dbPassword = $row['password'];

    // Verify password
    if (!password_verify($password, $dbPassword)) {
        die("Incorrect password.");
    } else {
        // Create session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();
    }
}

mysqli_close($conn);
?>


<?php
session_start();
unset($_SESSION['user_id']);
unset($_SESSION['username']);
session_destroy();
header("Location: login.php");
exit();
?>


<?php
// Database configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'login_system';

// Connect to database
$conn = new mysqli($host, $username_db, $password_db, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// User login function
function loginUser() {
    global $conn;

    // Check if form is submitted
    if (isset($_POST['login'])) {
        // Sanitize user input
        $username = htmlspecialchars(trim($_POST['username']));
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            echo "Please fill in all fields!";
            return;
        }

        // Query the database for the user
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['logged_in'] = true;
                
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Incorrect password!";
            }
        } else {
            echo "Username not found!";
        }
    }
}

// Close database connection
$conn->close();
?>


<?php
session_start();
// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to register a new user
function registerUser($conn, $username, $password, $email) {
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to insert user data
    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashedPassword, $email);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Function to login user
function loginUser($conn, $username, $password) {
    // Prepare SQL statement to select user by username
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get user data
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct
            // Update last login time
            date_default_timezone_set('UTC');
            $current_time = date('Y-m-d H:i:s');
            
            $updateStmt = $conn->prepare("UPDATE users SET last_login = ? WHERE id = ?");
            $updateStmt->bind_param("si", $current_time, $user['id']);
            $updateStmt->execute();

            // Start session and store user data
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['last_login'] = $current_time;

            return true;
        } else {
            // Password is incorrect
            return false;
        }
    } else {
        // Username not found
        return false;
    }
}

// Check if the registration form was submitted
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if (!empty($username) && !empty($password)) {
        if (registerUser($conn, $username, $password, $email)) {
            echo "Registration successful!";
        } else {
            echo "Registration failed. Please try again.";
        }
    } else {
        echo "Please fill in all required fields.";
    }
}

// Check if the login form was submitted
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        if (loginUser($conn, $username, $password)) {
            echo "Login successful!";
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Please fill in all required fields.";
    }
}

// Close database connection
$conn->close();
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to login user
function loginUser() {
    global $conn;

    // Get input values
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username and password are provided
    if (empty($username) || empty($password)) {
        echo "Username or password cannot be empty!";
        return;
    }

    // Query the database for the user
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get user data
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {

            // Password is correct
            session_start();
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Username not found!";
    }

    $stmt->close();
}

// Call the login function if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    loginUser();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input
        $email = htmlspecialchars(trim($_POST['email']));
        $password = trim($_POST['password']);

        // Check if email and password are provided
        if (empty($email) || empty($password)) {
            die("Email and password are required!");
        }

        // Prepare the statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Start session and store user data
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['logged_in'] = true;

                // Optional: Set a success message
                setcookie('login_message', 'Login successful!', time() + 2, '/', '', false, true);
                
                header("Location: dashboard.php");
                exit();
            } else {
                // Password doesn't match
                $error = "Invalid email or password!";
            }
        } else {
            // User not found
            $error = "Invalid email or password!";
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
    <title>Login Page</title>
</head>
<body>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Email:</label><br>
        <input type="email" name="email" required><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</body>
</html>


// Add this at the beginning of the script
session_start();
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;

// Add this to the form
<input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

// Add this in the login processing section
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Invalid CSRF token!");
}


<?php
session_start();

// Database connection parameters
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create database connection
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check if connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to sanitize input data
    function sanitizeInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    // Function to handle login
    function loginUser($conn, $username, $password) {
        global $loginError;

        // Check if username and password are not empty
        if (empty($username) || empty($password)) {
            $loginError = "Please fill in all fields.";
            return false;
        }

        // Sanitize input data
        $username = sanitizeInput($username);
        $password = sanitizeInput($password);

        // Prepare query to check user credentials
        $query = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param('s', $username);

        // Execute the query
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception("Error executing query: " . $stmt->error);
        }

        // Get result
        $stmt->store_result();

        // Check if user exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $dbUsername, $dbPassword);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $dbPassword)) {
                // Start session and set session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $dbUsername;

                // Set CSRF token for security
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                return true;
            } else {
                $loginError = "Invalid username or password.";
                return false;
            }
        } else {
            $loginError = "Invalid username or password.";
            return false;
        }
    }

    // Check if login form was submitted
    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (loginUser($conn, $username, $password)) {
            // Redirect to dashboard or home page after successful login
            header("Location: dashboard.php");
            exit();
        } else {
            echo $loginError;
        }
    }

} catch (Exception $e) {
    die("An error occurred: " . $e->getMessage());
}

// Close database connection
$conn->close();

?>


function registerUser($conn, $username, $password) {
    // Sanitize input data
    $username = sanitizeInput($username);
    $password = sanitizeInput($password);

    // Check if username and password are not empty
    if (empty($username) || empty($password)) {
        return false;
    }

    // Check if username already exists
    $query = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        return false; // Username already exists
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $insertQuery = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param('ss', $username, $hashedPassword);
    $result = $stmt->execute();

    if ($result) {
        return true;
    } else {
        return false;
    }
}


// Security headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");


<?php
session_start();

// Database connection details
$host = "localhost";
$username_db = "root";
$password_db = "";
$database_name = "login_system";

// Connect to database
$conn = mysqli_connect($host, $username_db, $password_db, $database_name);

// Check if connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Sanitize and validate input data
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    if ($username == '' || $password == '') {
        echo "Please fill in all fields!";
        exit();
    }

    // Check username and password against database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) != 1) {
        echo "Username not found!";
        exit();
    }

    // Get user details
    $row = mysqli_fetch_assoc($result);
    
    // Verify password
    if (password_verify($password, $row['password'])) {
        // Start session and store user data
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];
        
        // Redirect to dashboard or another page
        header("Location: dashboard.php");
    } else {
        echo "Login failed! Incorrect password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <h3 class="text-center mb-4">Login</h3>
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
                </form>
                <p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <!-- Display error messages -->
    <?php
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        if ($error == "empty") {
            echo '<div class="alert alert-danger">Please fill in all fields!</div>';
        } elseif ($error == "username") {
            echo '<div class="alert alert-danger">Username not found!</div>';
        } elseif ($error == "password") {
            echo '<div class="alert alert-danger">Incorrect password!</div>';
        }
    }
    ?>

</body>
</html>

// Logout functionality
<?php
session_start();
if (isset($_SESSION['id'])) {
    session_unset();
    session_destroy();
}
header("Location: login.php");
?>


$password = password_hash("user_password", PASSWORD_DEFAULT);

