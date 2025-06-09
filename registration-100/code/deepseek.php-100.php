
<?php
// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'mydatabase';

// Create connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function registerUser() {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Simple validation
    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill in all fields!";
        return;
    }

    // Check password match
    if ($password != $confirm_password) {
        echo "Passwords do not match!";
        return;
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        return;
    }

    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username already exists
    $check_query = "SELECT id FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($result) > 0) {
        echo "Username already exists!";
        return;
    }

    // Insert user into database
    $insert_query = "INSERT INTO users (username, email, password)
                    VALUES ('$username', '$email', '$hashed_password')";
    
    if (mysqli_query($conn, $insert_query)) {
        echo "Registration successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
}

// Call the function when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    registerUser();
}
?>


<?php
// Register.php
session_start();
require_once 'Database.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input
    if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password)) {
        die("All fields are required!");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format!");
    }

    // Validate username (only letters and numbers)
    if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        die("Username must contain only letters and numbers!");
    }

    // Sanitize input
    $firstName = htmlspecialchars(trim($firstName));
    $lastName = htmlspecialchars(trim($lastName));
    $email = htmlspecialchars(trim($email));
    $username = htmlspecialchars(trim($username));

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        $db = new Database();
        $conn = $db->getConnection();

        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            die("Username already taken!");
        }

        // Insert new user into the database
        $sql = "INSERT INTO users (first_name, last_name, email, username, password_hash) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$firstName, $lastName, $email, $username, $hashedPassword]);

        // Redirect to login page after successful registration
        header("Location: welcome.php?message=Welcome%20" . $username . "!");
        exit();

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>


<?php
// Include database connection file
require_once('db_connection.php');

function registerUser() {
    // Check if form was submitted with POST method
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);

        // Validate input fields
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($first_name) || empty($last_name)) {
            die("Please fill in all required fields.");
        }

        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            die("Username already taken. Please choose another.");
        }

        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Please enter a valid email address.");
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            die("Email already registered. Please use another.");
        }

        // Validate password requirements
        // At least 8 characters, at least one uppercase letter and a number, one special character
        $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

        if (!preg_match($passwordPattern, $password)) {
            die("Password must be at least 8 characters long and contain at least one uppercase letter, a number, and a special character.");
        }

        // Check if password matches confirm password
        if ($password !== $confirm_password) {
            die("Passwords do not match. Please try again.");
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $hashedPassword, $first_name, $last_name);

        if ($stmt->execute()) {
            // Send confirmation email
            require_once('PHPMailer/PHPMailer.php');
            require_once('PHPMailer/Exception.php');
            require_once('PHPMailer/OAuth.php');
            require_once('PHPMailer/SMTP.php');

            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.example.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'your_email@example.com';
                $mail->Password = 'your_password';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('your_email@example.com', 'Registration Confirmation');
                $mail->addAddress($email);

                $mail->Subject = 'Welcome to Our Site!';
                $mail->Body = "Hello $first_name,

Thank you for registering with us.

Your account has been successfully created.

Best regards,
The Team";

                $mail->send();
            } catch (Exception $e) {
                error_log("Email could not be sent. Error: " . $e->getMessage());
            }

            // Redirect to login page
            header('Location: login.php?registered=success');
            exit;
        } else {
            die("An error occurred while registering your account.");
        }
    }
}

// Call the function
registerUser();
?>


<?php
// Database connection
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function registerUser($conn, $username, $email, $password) {
    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        return "Username or email already exists!";
    }

    // Sanitize inputs
    $username = htmlspecialchars(strip_tags($username));
    $email = htmlspecialchars(strip_tags($email));

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        $stmt->execute();
        return "Registration successful!";
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        die("All fields are required!");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format!");
    }

    // Call registration function
    $result = registerUser($conn, $username, $email, $password);
    echo $result;
}
?>


<?php
// Database connection configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$database = 'my_database';

// Create database connection
$conn = new mysqli($host, $username_db, $password_db, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser($username, $email, $password, $conn) {
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        return "All fields are required!";
    }

    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long!";
    }

    // Check if username already exists
    $checkUsernameQuery = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkUsernameQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Username already exists!";
    }

    // Check if email already exists
    $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Email already exists!";
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $registerQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($registerQuery);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        return "Registration successful!";
    } else {
        return "Error: " . $stmt->error;
    }
}

// Example usage:
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Call the registration function
    $result = registerUser($username, $email, $password, $conn);
    echo $result;
}

// Close database connection
$conn->close();
?>


<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username or email already exists
    $checkQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['message'] = "Username or email already exists!";
        header("Location: messages.php");
        exit();
    }

    // Insert user into database
    $insertQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $password);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Registration successful! Please login.";
        header("Location: messages.php");
        exit();
    } else {
        $_SESSION['message'] = "Error registering user!";
        header("Location: messages.php");
        exit();
    }

    mysqli_close($conn);
}
?>


<?php
$host = 'localhost';
$username = 'root'; // Your database username
$password = ''; // Your database password
$dbname = 'mydatabase'; // Your database name

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages</title>
    <style>
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <?php
    if (!empty($_SESSION['message'])) {
        $class = isset($_SESSION['status']) ? $_SESSION['status'] : 'info';
        echo "<div class='$class'>$_SESSION['message']."</div>";
        unset($_SESSION['message']);
        unset($_SESSION['status']);
    }
    ?>
    <a href="register.php">Back to Registration</a>
    <br>
    <a href="login.php">Go to Login</a>
</body>
</html>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'registration';

try {
    // Connect to database
    $conn = mysqli_connect($host, $username_db, $password_db, $db_name);
    
    // Set error reporting for database queries
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to sanitize input data
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Registration function
function registerUser($conn, $username, $email, $password) {
    // Check if username already exists
    $checkUsername = mysqli_prepare($conn, "SELECT username FROM users WHERE username = ?");
    mysqli_stmt_bind_param($checkUsername, 's', $username);
    mysqli_stmt_execute($checkUsername);
    
    if (mysqli_stmt_fetch($checkUsername)) {
        return ['error' => 'Username already exists!'];
    }
    
    // Check if email already exists
    $checkEmail = mysqli_prepare($conn, "SELECT email FROM users WHERE email = ?");
    mysqli_stmt_bind_param($checkEmail, 's', $email);
    mysqli_stmt_execute($checkEmail);
    
    if (mysqli_stmt_fetch($checkEmail)) {
        return ['error' => 'Email already exists!'];
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT, [
        'cost' => 12,
    ]);
    
    // Insert new user into database
    $insertUser = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($insertUser, 'sss', $username, $email, $hashedPassword);
    
    if (!mysqli_stmt_execute($insertUser)) {
        return ['error' => 'Registration failed!'];
    }
    
    // Registration successful
    return ['success' => 'You have been registered successfully!'];
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    // Validate username
    if (!preg_match('/^[a-zA-Z0-9]{3,20}$/', $username)) {
        $errors[] = 'Username must be between 3 and 20 characters long and contain only letters and numbers!';
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address!';
    }
    
    // Validate password
    if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*()_+}{":?><;\/\-=`~]{8,}$/', $password)) {
        $errors[] = 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and a special character!';
    }
    
    // If there are errors
    if (isset($errors)) {
        print_r($errors);
    } else {
        // Call registration function
        $result = registerUser($conn, $username, $email, $password);
        
        // Display result
        if (isset($result['error'])) {
            echo $result['error'];
        } elseif (isset($result['success'])) {
            echo $result['success'];
        }
    }
}

// Close database connection
mysqli_close($conn);

?>


<?php
// Include necessary files
require_once 'database_connection.php';
require_once 'functions.php';

// Function to handle user registration
function registerUser() {
    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = htmlspecialchars(trim($_POST['username']));
        $email = htmlspecialchars(trim($_POST['email']));
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $full_name = htmlspecialchars(trim($_POST['full_name']));
        $date_of_birth = $_POST['date_of_birth'];
        $phone_number = htmlspecialchars(trim($_POST['phone_number']));
        $address = htmlspecialchars(trim($_POST['address']));

        // Validate input fields
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            throw new Exception("All fields are required");
        }

        if ($password !== $confirm_password) {
            throw new Exception("Passwords do not match");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Check if username already exists
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            throw new Exception("Username already exists");
        }

        // Check if email already exists
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            throw new Exception("Email already exists");
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $query = "INSERT INTO users (username, email, password, full_name, date_of_birth, phone_number, address) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssssss', $username, $email, $hashed_password, $full_name, $date_of_birth, $phone_number, $address);

        if (!$stmt->execute()) {
            throw new Exception("Registration failed. Please try again.");
        }

        // Registration successful
        $stmt->close();
        $conn->close();

        // Send welcome email
        sendWelcomeEmail($email, $full_name);

        // Redirect to login page with success message
        header("Location: login.php?success=1");
        exit();
    }
}

// Function to display registration form
function showRegistrationForm() {
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="date" name="date_of_birth" required>
        <input type="tel" name="phone_number" placeholder="Phone Number">
        <textarea name="address" placeholder="Address"></textarea>
        <button type="submit">Register</button>
    </form>
    <?php
}

// Function to send welcome email
function sendWelcomeEmail($email, $full_name) {
    // Configure PHPMailer here
    // You'll need to implement your own email sending logic
}

try {
    registerUser();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Display registration form if not submitted or registration failed
showRegistrationForm();
?>


<?php
function registerUser($username, $email, $password) {
    // Database configuration
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "your_database";

    try {
        // Connect to database
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Check for existing email
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            throw new Exception("Email already exists.");
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        
        if (!$stmt->execute()) {
            throw new Exception("Registration failed: " . $stmt->error);
        }

        // Close connections
        $stmt->close();
        $conn->close();

        return true; // Registration successful

    } catch (Exception $e) {
        // Handle exceptions and errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate inputs here...

    if (registerUser($username, $email, $password)) {
        header("Location: login.php?success=1");
        exit();
    } else {
        echo "Registration failed. Please try again.";
    }
}
?>


<?php
// Database connection
function db_connect() {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "users_db";

    // Create connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Sanitize input data
function sanitize($data) {
    $data = trim($data);
    $data = htmlspecialchars($data, ENT_QUOTES);
    $data = strip_tags($data);
    return $data;
}

// Validate and register user
function register_user($username, $email, $password, $confirm_password, $full_name, $dob) {
    // Sanitize inputs
    $username = sanitize($username);
    $email = sanitize($email);
    $full_name = sanitize($full_name);
    $dob = sanitize($dob);

    // Check if all required fields are filled
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($full_name) || empty($dob)) {
        return "Please fill in all required fields.";
    }

    // Validate password strength
    $min_length = 8;
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);

    if ($uppercase + $lowercase + $number < 2 || strlen($password) < $min_length) {
        return "Password must be at least 8 characters and contain at least two different types of characters (letters, numbers, or special characters).";
    }

    // Check password match
    if ($password !== $confirm_password) {
        return "Passwords do not match.";
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Connect to database
    $conn = db_connect();

    // Check if username already exists
    $sql_check_username = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql_check_username);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Username already exists.";
    }

    // Check if email already exists
    $sql_check_email = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql_check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Email already exists.";
    }

    // Insert new user into database
    $sql_insert_user = "INSERT INTO users (username, email, password, full_name, dob) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert_user);
    $stmt->bind_param("sssss", $username, $email, $hashed_password, $full_name, $dob);

    if ($stmt->execute()) {
        return "Registration successful!";
    } else {
        return "Error: " . $stmt->error;
    }

    // Close database connection
    $conn->close();
}

// Example usage:
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = $_POST['full_name'];
    $dob = $_POST['dob'];

    $result = register_user($username, $email, $password, $confirm_password, $full_name, $dob);
    echo $result;
}
?>


<?php
// Database connection details
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'registration';

// Create database connection
$conn = mysqli_connect($host, $username_db, $password_db, $db_name);

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function registerUser() {
    global $conn;

    // Check if form is submitted
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Data validation
        if (empty($username) || empty($email) || empty($password)) {
            echo "Please fill in all fields!";
            return;
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format!";
            return;
        }

        // Sanitize inputs
        $username = mysqli_real_escape_string($conn, htmlspecialchars($username));
        $email = mysqli_real_escape_string($conn, htmlspecialchars($email));

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

        // Check if username already exists
        $check_username_query = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $check_username_query);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "Username already exists!";
            return;
        }

        // Check if email already exists
        $check_email_query = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_email_query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "Email already exists!";
            return;
        }

        // Insert user into database
        $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hashed_password);

        if (mysqli_stmt_execute($stmt)) {
            echo "Registration successful! You can now login.";
        } else {
            echo "Error registering user: " . mysqli_error($conn);
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Call the function
registerUser();

// Close database connection
mysqli_close($conn);
?>


<?php
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


<?php
session_start();
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $email = htmlspecialchars(trim(strtolower($_POST['email'])));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate inputs
    if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password) || empty($confirmPassword)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: register.html");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format!";
        header("Location: register.html");
        exit();
    }

    // Check password requirements
    if (strlen($password) < 8 || !preg_match('/^[a-zA-Z0-9]*$/', $password)) {
        $_SESSION['error'] = "Password must be at least 8 characters and contain only letters and numbers!";
        header("Location: register.html");
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: register.html");
        exit();
    }

    // Check for existing username or email
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username=:username OR email=:email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Username or email already exists!";
            header("Location: register.html");
            exit();
        }
    } catch (PDOException $e) {
        die("Error checking user: " . $e->getMessage());
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    try {
        $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, username, password) VALUES (:firstName, :lastName, :email, :username, :password)");
        $result = $stmt->execute([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'username' => $username,
            'password' => $hashedPassword
        ]);

        if ($result) {
            $_SESSION['success'] = "Registration successful! Please login.";
            header("Location: login.html");
            exit();
        } else {
            $_SESSION['error'] = "Error registering user!";
            header("Location: register.html");
            exit();
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }

    // Close connection
    $conn = null;
}
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'user_registration';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Register user function
function registerUser() {
    global $conn;

    // Check if form is submitted
    if (isset($_POST['submit'])) {
        // Get form values
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Form validation
        $errors = array();

        if (empty($firstName)) {
            $errors[] = "First name is required";
        }

        if (empty($lastName)) {
            $errors[] = "Last name is required";
        }

        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!preg_match("/^[^\s@]+@[^\s@]+\.[^\s@]+$/", $email)) {
            $errors[] = "Invalid email format";
        }

        if (empty($password)) {
            $errors[] = "Password is required";
        } elseif (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }

        // Check for password strength
        if (!preg_match('/^(?=.*\d)(?=.*[a-zA-Z])[0-9a-zA-Z]+$/', $password)) {
            $errors[] = "Password must contain both letters and numbers";
        }

        // If no errors, proceed to registration
        if (empty($errors)) {
            // Sanitize input data
            $firstName = mysqli_real_escape_string($conn, $firstName);
            $lastName = mysqli_real_escape_string($conn, $lastName);
            $email = mysqli_real_escape_string($conn, $email);

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Check if email already exists
            $checkEmailQuery = "SELECT id FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $checkEmailQuery);

            if (mysqli_num_rows($result) > 0) {
                echo "Email already exists. Please use a different email address.";
            } else {
                // Insert user into database
                $registerQuery = "INSERT INTO users (first_name, last_name, email, password)
                                 VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";

                if (mysqli_query($conn, $registerQuery)) {
                    echo "Registration successful! You can now <a href='login.php'>log in</a>";
                } else {
                    echo "Error registering user: " . mysqli_error($conn);
                }
            }
        } else {
            // Display errors
            foreach ($errors as $error) {
                echo "<div class='error'>$error</div>";
            }
        }
    }
}

// Call the registration function
registerUser();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
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
    <div class="container">
        <h2>Registration Form</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" name="submit">Register</button>
        </form>
    </div>
</body>
</html>


<?php
/**
 * User registration script with security measures and validation
 */

// Include database connection file
include 'db_connection.php';

function sanitizeInput($data) {
    /**
     * Sanitizes input data to prevent SQL injection and XSS attacks
     */
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function validateUsername($username) {
    /**
     * Validates the username format (only letters and numbers)
     * @param string $username The username to validate
     * @return bool Returns true if valid, false otherwise
     */
    return preg_match('/^[a-zA-Z0-9]+$/', $username);
}

function validateEmail($email) {
    /**
     * Validates the email format
     * @param string $email The email to validate
     * @return bool Returns true if valid, false otherwise
     */
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password) {
    /**
     * Validates password strength (at least 6 characters, letters and numbers)
     * @param string $password The password to validate
     * @return bool Returns true if valid, false otherwise
     */
    return preg_match('/^(?=.*\d)(?=.*[a-zA-Z]).{6,}$/', $password);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    $errors = [];

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required!";
    } elseif (!validateUsername($username)) {
        $errors[] = "Username can only contain letters and numbers!";
    }

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required!";
    } elseif (!validateEmail($email)) {
        $errors[] = "Invalid email format!";
    }

    // Validate password
    if (empty($password) || empty($confirmPassword)) {
        $errors[] = "Password is required!";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match!";
    } elseif (!validatePassword($password)) {
        $errors[] = "Password must be at least 6 characters and contain letters and numbers!";
    }

    if (empty($errors)) {
        try {
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bindParam(1, $username);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                die("Username already taken!");
            }

            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bindParam(1, $email);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                die("Email already registered!");
            }

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into database
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $hashedPassword);

            if ($stmt->execute()) {
                // Set session variables
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;

                // Redirect to dashboard or login page
                header("Location: login.php");
                exit();
            } else {
                throw new Exception("Registration failed!");
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    } else {
        foreach ($errors as $error) {
            echo "<div class='error'>$error</div><br>";
        }
    }
}
?>


<?php
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

function register_user() {
    global $conn;

    // Check if form is submitted
    if (isset($_POST['submit'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate inputs
        if ($username == "" || $email == "" || $password == "") {
            die("Please fill in all required fields");
        }

        if (strlen($username) < 3) {
            die("Username must be at least 3 characters long");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Please enter a valid email address");
        }

        if ($password != $confirm_password) {
            die("Passwords do not match");
        }

        // Check if email already exists
        $check_email_query = "SELECT COUNT(*) as count FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_email_query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $count);

        if ($count > 0) {
            die("Email already exists");
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

        if (mysqli_stmt_execute($stmt)) {
            // Registration successful
            header("Location: login.php");
            exit();
        } else {
            die("Registration failed. Please try again.");
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
</head>
<body>
    <h2>Register Here</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Username: <input type="text" name="username"><br><br>
        Email: <input type="email" name="email"><br><br>
        Password: <input type="password" name="password"><br><br>
        Confirm Password: <input type="password" name="confirm_password"><br><br>
        <input type="submit" name="submit" value="Register">
    </form>
</body>
</html>

<?php
// Call the register function
register_user();
?>


<?php
// Database configuration
$host = "localhost";
$username_db = "root";
$password_db = "";
$db_name = "user_registration";

// Connect to the database
$conn = mysqli_connect($host, $username_db, $password_db, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Initialize variables and error array
    $errors = [];
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (!preg_match('/^[a-zA-Z0-9_]*$/', $username)) {
        $errors[] = "Username can only contain letters, numbers, and underscores";
    }

    // Check if username already exists
    $check_username_query = "SELECT * FROM users WHERE username = '" . mysqli_real_escape_string($conn, $username) . "'";
    $result_username = mysqli_query($conn, $check_username_query);
    if (mysqli_num_rows($result_username) > 0) {
        $errors[] = "Username already exists";
    }

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Check if email already exists
    $check_email_query = "SELECT * FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email) . "'";
    $result_email = mysqli_query($conn, $check_email_query);
    if (mysqli_num_rows($result_email) > 0) {
        $errors[] = "Email already exists";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^]{8,}$/', $password)) {
        $errors[] = "Password must be at least 8 characters and contain at least one uppercase letter, one lowercase letter, and one number";
    }

    // Confirm password matches
    if ($password != $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    // If no errors, proceed to registration
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $sql = "INSERT INTO users (username, email, password) VALUES ('" . mysqli_real_escape_string($conn, $username) . "', '" . mysqli_real_escape_string($conn, $email) . "', '" . $hashed_password . "')";

        if (mysqli_query($conn, $sql)) {
            echo "<h2>Registration successful!</h2>";
        } else {
            $errors[] = "Error registering user: " . mysqli_error($conn);
        }
    }

    // Close database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php
    // Display errors or success message
    if (isset($_POST['submit'])) {
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div class='error'>$error</div>";
            }
        } else {
            echo "<div class='success'>Registration successful! You can now <a href='login.php'>log in</a>.</div>";
        }
    }
    ?>

    <!-- Registration form -->
    <h2>Register New Account</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password">
        </div>

        <button type="submit" name="submit">Register</button>
    </form>
</body>
</html>


<?php
session_start();
include 'db.php'; // Include your database connection file

function registerUser() {
    $errors = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            // Validate input
            if (empty($_POST['username'])) {
                $errors[] = "Username is required";
            }
            if (empty($_POST['email'])) {
                $errors[] = "Email is required";
            }
            if (empty($_POST['password'])) {
                $errors[] = "Password is required";
            }
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $errors[] = "Passwords do not match";
            }

            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$_POST['username']]);
            if ($stmt->rowCount() > 0) {
                $errors[] = "Username already exists";
            }

            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$_POST['email']]);
            if ($stmt->rowCount() > 0) {
                $errors[] = "Email is already registered";
            }

            // If no errors, proceed to register user
            if (empty($errors)) {
                // Hash password
                $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

                // Insert into database
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, registered) VALUES (?, ?, ?, NOW())");
                $stmt->execute([$_POST['username'], $_POST['email'], $password_hash]);

                // Return success messages
                return array(
                    'success' => true,
                    'messages' => array(
                        "User registration successful!",
                        "You can now log in with your credentials."
                    )
                );
            }

        } catch (Exception $e) {
            $errors[] = "An error occurred while registering. Please try again later.";
            return array('success' => false, 'errors' => $errors);
        }
    }

    // If no POST request or errors present
    if (!empty($errors)) {
        return array('success' => false, 'errors' => $errors);
    } else {
        return array('success' => false, 'errors' => array("Invalid request"));
    }
}

// Example usage:
$result = registerUser();

if ($result['success']) {
    // Redirect to login page or display success message
    header("Location: login.php?msg=registration_success");
} else {
    // Display errors in the registration form
    print_r($result['errors']);
}
?>


<?php
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


<?php
function registerUser($username, $email, $password) {
    // Database connection parameters
    $host = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'my_database';

    // Create connection
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validate username
    if (empty($username)) {
        return "Username is required";
    } elseif (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
        return "Invalid username. Only letters, numbers, and underscores are allowed.";
    } elseif (strlen($username) > 255) {
        return "Username too long.";
    }

    // Validate email
    if (empty($email)) {
        return "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format";
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Username already taken";
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Email already registered";
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return "Error registering user: " . $conn->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = registerUser($username, $email, $password);

    if ($result === true) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $result;
    }
}
?>


<?php
// Include database configuration file
include('db_config.php');

function registerUser() {
    // Check if form submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $birthdate = $_POST['birthdate'];

        // Initialize error array
        $errors = array();

        // Validate username
        if (empty($username)) {
            $errors[] = "Username is required";
        } elseif (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
            $errors[] = "Username must be 3-20 characters and contain only letters, numbers, and underscores";
        } else {
            // Check if username already exists
            $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $errors[] = "Username already exists";
            }
        }

        // Validate email
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        } else {
            // Check if email already exists
            $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $errors[] = "Email already exists";
            }
        }

        // Validate password
        if (empty($password)) {
            $errors[] = "Password is required";
        } elseif (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters";
        }

        // Validate confirm password
        if ($password != $confirm_password) {
            $errors[] = "Passwords do not match";
        }

        // Validate first name and last name
        if (empty($first_name) || empty($last_name)) {
            $errors[] = "First name and Last name are required";
        }

        // Check birthdate format
        if (!empty($birthdate)) {
            if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $birthdate)) {
                $errors[] = "Invalid date format. Please use YYYY-MM-DD";
            }
        }

        // If no errors, proceed to register the user
        if (empty($errors)) {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert into database
            $sql = "INSERT INTO users (username, email, password, first_name, last_name, birthdate) VALUES (?, ?, ?, ?, ?, ?)";
            
            if ($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssssss", $username, $email, $hashed_password, $first_name, $last_name, $birthdate);

                // Execute the statement
                if (mysqli_stmt_execute($stmt)) {
                    echo "Registration successful!";
                    // Redirect to login page or dashboard
                    header("Location: login.php");
                    exit();
                } else {
                    echo "Error registering user. Please try again.";
                }
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }

        // Close database connection
        mysqli_close($conn);
    }
}

// Call the registration function
registerUser();
?>


<?php
$host = 'localhost';
$username = 'your_db_username';
$password = 'your_db_password';
$database = 'your_db_name';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<?php
// Include database configuration file
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = array();
    
    // Validate username
    if (empty($_POST['username'])) {
        $errors[] = "Username is required";
    } else {
        $username = $_POST['username'];
        // Check if username already exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Username already exists";
        }
    }

    // Validate email
    if (empty($_POST['email'])) {
        $errors[] = "Email is required";
    } else {
        $email = $_POST['email'];
        // Check if email already exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Email already exists";
        }
    }

    // Validate password
    if (empty($_POST['password'])) {
        $errors[] = "Password is required";
    } else {
        $password = $_POST['password'];
        // Password strength validation
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }
        if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
            $errors[] = "Password can only contain letters and numbers";
        }
    }

    // Validate confirm password
    if (empty($_POST['confirm_password'])) {
        $errors[] = "Confirm password is required";
    } else {
        $confirm_password = $_POST['confirm_password'];
        if ($password != $confirm_password) {
            $errors[] = "Passwords do not match";
        }
    }

    // Validate first name
    if (empty($_POST['first_name'])) {
        $errors[] = "First name is required";
    } else {
        $first_name = $_POST['first_name'];
        // Simple name validation
        if (!preg_match("/^[a-zA-Z ]*$/", $first_name)) {
            $errors[] = "Invalid first name";
        }
    }

    // Validate last name
    if (empty($_POST['last_name'])) {
        $errors[] = "Last name is required";
    } else {
        $last_name = $_POST['last_name'];
        // Simple name validation
        if (!preg_match("/^[a-zA-Z ]*$/", $last_name)) {
            $errors[] = "Invalid last name";
        }
    }

    // Validate date of birth
    if (empty($_POST['dob'])) {
        $errors[] = "Date of birth is required";
    } else {
        $dob = $_POST['dob'];
        // Convert to SQL date format and validate
        $date = DateTime::createFromFormat('Y-m-d', $dob);
        if ($date === false) {
            $errors[] = "Invalid date format for date of birth";
        }
    }

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Prepare and execute the SQL statement to insert user data
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, first_name, last_name, dob) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password, $first_name, $last_name, $dob]);

            // Registration successful
            echo "Registration successful! You can now log in.";
        } catch (PDOException $e) {
            // Error occurred during database insertion
            $errors[] = "An error occurred while registering. Please try again later.";
            echo $e->getMessage();
        }
    } else {
        // Display errors to the user
        foreach ($errors as $error) {
            echo "<div class='error'>$error</div>";
        }
    }

    // Close database connection
    $conn = null;
}
?>


<?php
// Database connection details
$host = "localhost";
$user = "root";
$password = "";
$dbname = "test";

// Create database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser($username, $email, $password) {
    global $conn;

    try {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return array('status' => false, 'message' => 'Username or email already exists');
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT, array('cost' => 12));

        // Insert new user into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            return array('status' => true, 'message' => 'Registration successful!', 'user_id' => $user_id);
        } else {
            return array('status' => false, 'message' => 'Registration failed. Please try again.');
        }
    } catch (Exception $e) {
        return array('status' => false, 'message' => 'Error: ' . $e->getMessage());
    }
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = registerUser($username, $email, $password);
    
    if ($result['status']) {
        // Registration successful
        echo "Welcome, " . $username . "! Your user ID is: " . $result['user_id'];
        // You can redirect to a login page or dashboard here
    } else {
        // Display error message
        echo $result['message'];
    }
}

$conn->close();
?>


<?php
// Include database connection file
include('db_connection.php');

function registerUser($username, $email, $password) {
    // Sanitize inputs to prevent SQL injection
    $username = mysqli_real_escape_string($GLOBALS['conn'], trim($username));
    $email = mysqli_real_escape_string($GLOBALS['conn'], trim($email));
    $password = trim($password);

    // Validate input fields
    if (empty($username) || empty($email) || empty($password)) {
        return "Please fill in all required fields";
    }

    // Check if username already exists
    $checkUsernameQuery = "SELECT id FROM users WHERE username = ?";
    $stmt = mysqli_prepare($GLOBALS['conn'], $checkUsernameQuery);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        return "Username already exists";
    }

    // Check if email already exists
    $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($GLOBALS['conn'], $checkEmailQuery);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        return "Email already exists";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Please enter a valid email address";
    }

    // Validate password requirements
    $minPasswordLength = 8;
    if (strlen($password) < $minPasswordLength) {
        return "Password must be at least " . $minPasswordLength . " characters long";
    }

    if (!preg_match("/[A-Z]/", $password)) {
        return "Password must contain at least one uppercase letter";
    }

    if (!preg_match("/[0-9]/", $password)) {
        return "Password must contain at least one number";
    }

    if (!preg_match("/[^A-Za-z0-9]/", $password)) {
        return "Password must contain at least one special character";
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $insertQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($GLOBALS['conn'], $insertQuery);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);

    if (mysqli_stmt_execute($stmt)) {
        // Registration successful
        return true;
    } else {
        // Error occurred during registration
        return "Error registering user. Please try again later";
    }
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = registerUser($username, $email, $password);

    if ($result === true) {
        // Start session for the new user
        session_start();
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<p style='color:red'>" . $result . "</p>";
    }
}
?>


<?php
// Database connection
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "mydatabase";

$conn = mysqli_connect($host, $username_db, $password_db, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Get user input
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = array();

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required";
    } else {
        // Check for valid characters in username
        if (!preg_match("/^[a-zA-Z0-9_\-]*$/", $username)) {
            $errors[] = "Username can only contain letters, numbers, underscores, and hyphens";
        }
    }

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address";
        }
    }

    // Validate password
    if (empty($password) || empty($confirm_password)) {
        $errors[] = "Password is required";
    } else {
        if ($password != $confirm_password) {
            $errors[] = "Passwords do not match";
        }

        // Password strength check
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter";
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter";
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number";
        }

        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = "Password must contain at least one special character";
        }
    }

    // If there are errors, display them
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='error'>" . $error . "</div>";
        }
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $sql = "INSERT INTO users (username, email, password) VALUES 
                ('" . mysqli_real_escape_string($conn, $username) . "',
                 '" . mysqli_real_escape_string($conn, $email) . "',
                 '" . $hashed_password . "')";

        if (mysqli_query($conn, $sql)) {
            // Redirect to login page or dashboard
            header('Location: login.php?success=Registration successful! Please login.');
            exit();
        } else {
            echo "<div class='error'>Error registering user. Please try again later.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <style>
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input {
            display: block;
            margin-bottom: 10px;
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
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
        <h2>Registration Form</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="username" placeholder="Enter your username" required><br>
            <input type="email" name="email" placeholder="Enter your email" required><br>
            <input type="password" name="password" placeholder="Enter password" required><br>
            <input type="password" name="confirm_password" placeholder="Confirm password" required><br>
            <button type="submit" name="submit">Register</button>
        </form>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>


<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_registration";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    global $conn;
    
    // Initialize variables and set to empty values
    $username = "";
    $email = "";
    $password = "";
    $confirm_password = "";
    
    // Array to hold error messages
    $errors = array();
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form values
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Validate form inputs
        if (empty($username)) {
            array_push($errors, "Username is required");
        }
        if (empty($email)) {
            array_push($errors, "Email is required");
        }
        if (empty($password)) {
            array_push($errors, "Password is required");
        }
        if ($password != $confirm_password) {
            array_push($errors, "Passwords do not match");
        }
        
        // Check if username already exists
        $check_username = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
        if (mysqli_num_rows($check_username) > 0) {
            array_push($errors, "Username already exists");
        }
        
        // Check if email already exists
        $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($check_email) > 0) {
            array_push($errors, "Email already exists");
        }
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Invalid email format");
        }
        
        // Password validation
        if (strlen($password) < 8) {
            array_push($errors, "Password must be at least 8 characters");
        }
        
        // If no errors
        if (count($errors) == 0) {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user into database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            
            if ($stmt->execute()) {
                echo "Registration successful!";
            } else {
                array_push($errors, "Error registering user");
            }
        }
    }
    
    // Display errors
    foreach ($errors as $error) {
        echo "<p class='error'>" . $error . "</p>";
    }
}

// Call the registration function
if (isset($_POST['register'])) {
    registerUser();
}
?>


<?php
// Database connection details
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'my_database';

// Connect to database
$conn = mysqli_connect($host, $username_db, $password_db, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Initialize variables and error array
    $errors = [];
    $username = $email = $password = '';

    // Sanitize inputs
    function sanitize($data) {
        return htmlspecialchars(mysqli_real_escape_string($conn, trim($data)));
    }

    if (empty($_POST['username'])) {
        $errors[] = "Username is required";
    } else {
        $username = sanitize($_POST['username']);
        if (strlen($username) > 20) {
            $errors[] = "Username must be less than or equal to 20 characters";
        }
    }

    if (empty($_POST['email'])) {
        $errors[] = "Email is required";
    } else {
        $email = sanitize($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
    }

    if (empty($_POST['password'])) {
        $errors[] = "Password is required";
    } else {
        $password = $_POST['password'];
        // Password strength check
        if (strlen($password) < 6 || !preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password)) {
            $errors[] = "Password must be at least 6 characters long and contain an uppercase letter and a number";
        }
    }

    // Check for existing username or email
    if (empty($errors)) {
        $stmt_check = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? OR email = ?");
        mysqli_stmt_bind_param($stmt_check, "ss", $username, $email);
        mysqli_stmt_execute($stmt_check);
        $result = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Username or email already exists";
        }
    }

    // Process registration if no errors
    if (empty($errors)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt_insert = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt_insert, "sss", $username, $email, $password_hashed);

        if (mysqli_stmt_execute($stmt_insert)) {
            echo "Registration successful!";
            header("Refresh: 2; url=login.php");
            exit();
        } else {
            die("Error: " . mysqli_error($conn));
        }
    } else {
        foreach ($errors as $error) {
            echo "<div style='color: red;'>$error</div><br>";
        }
    }

    // Close statements
    if (isset($stmt_check)) mysqli_stmt_close($stmt_check);
    if (isset($stmt_insert)) mysqli_stmt_close($stmt_insert);
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Include database configuration file
include('db-config.php');

function registerUser($username, $email, $password) {
    global $conn;

    // Sanitize inputs to prevent SQL injection
    $username = mysqli_real_escape_string($conn, trim($username));
    $email = mysqli_real_escape_string($conn, trim($email));
    $password = mysqli_real_escape_string($conn, trim($password));

    // Check if username or email already exists
    $checkQuery = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($result) > 0) {
        return "Username or email already exists!";
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into database
    $query = "INSERT INTO users (username, email, password) 
              VALUES ('$username', '$email', '$hashedPassword')";

    if (mysqli_query($conn, $query)) {
        return "Registration successful!";
    } else {
        return "Error: " . mysqli_error($conn);
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        echo "All fields are required!";
    } else {
        $result = registerUser($username, $email, $password);
        echo $result;
    }
}
?>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'user_registration';

// Create database connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check for connection errors
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to validate email format
function validateEmail($email) {
    return preg_match("/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/", $email);
}

// Function to validate password requirements (minimum 8 characters, at least one letter and number)
function validatePassword($password) {
    return preg_match('/^(?=.*\d)(?=.*[a-zA-Z])[0-9a-zA-Z!@#$%^&*()_+}{":;?><~`\-|]{8,}$/', $password);
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize input data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($password)) {
        echo "All fields are required!";
    } else if (!validateEmail($email)) {
        echo "Please enter a valid email address!";
    } else if (!validatePassword($password)) {
        echo "Password must be at least 8 characters and contain at least one letter and number!";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Check if user already exists
        $checkQuery = "SELECT id FROM users WHERE email=?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            echo "Email already exists!";
        } else {
            // Insert new user into database
            $insertQuery = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $hashed_password);
            
            if (mysqli_stmt_execute($stmt)) {
                echo "Registration successful! Please login.";
                // Redirect to login page after 2 seconds
                header("Refresh: 2; url=login.php");
            } else {
                echo "Error registering user: " . mysqli_error($conn);
            }
        }
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
session_start();

// Database connection details
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'user_registration';

// Connect to the database
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "All fields are required!";
    } else {
        // Check if username already exists
        $checkUsernameQuery = "SELECT username FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $checkUsernameQuery);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "Username already exists!";
        } else {
            // Check if email already exists
            $checkEmailQuery = "SELECT email FROM users WHERE email = ?";
            $stmt = mysqli_prepare($conn, $checkEmailQuery);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                echo "Email already exists!";
            } else {
                // Check password length and strength
                if (strlen($password) < 8) {
                    echo "Password must be at least 8 characters!";
                } elseif (!preg_match("#[A-Z]#", $password)) {
                    echo "Password must contain at least one uppercase letter!";
                } elseif (!preg_match("#[a-z]#", $password)) {
                    echo "Password must contain at least one lowercase letter!";
                } elseif (!preg_match("#[0-9]#", $password)) {
                    echo "Password must contain at least one number!";
                } else {
                    // Check if passwords match
                    if ($password != $confirm_password) {
                        echo "Passwords do not match!";
                    } else {
                        // Hash the password
                        $hashedPassword = md5($password);

                        // Insert user data into database
                        $insertQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                        $stmt = mysqli_prepare($conn, $insertQuery);
                        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);
                        
                        if (mysqli_stmt_execute($stmt)) {
                            echo "Registration successful!";
                            header("Location: login.php");
                            exit();
                        } else {
                            echo "Error registering user: " . mysqli_error($conn);
                        }
                    }
                }
            }
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>


<?php
// Database connection configuration
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'user_registration';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbName", $dbUsername, $dbPassword);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if all fields are filled in
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        die("All fields must be filled in.");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Check password length
    if (strlen($password) < 8) {
        die("Password must be at least 8 characters long.");
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if user already exists in database
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch();

        if ($result) {
            die("User with this email already exists.");
        }

        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword]);

        // Generate verification token and send confirmation email
        $verificationToken = bin2hex(random_bytes(16));
        
        try {
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com';
            $mail->Password = 'your_password';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('your_email@example.com', 'Registration Confirmation');
            $mail->addAddress($email);
            $mail->Subject = 'Verify your email address';

            $verificationLink = "http://$_SERVER[HTTP_HOST]/verify.php?token=$verificationToken";
            $body = "Please click the following link to verify your account: $verificationLink";

            $mail->Body = $body;
            $mail->send();

            // Store verification token in database
            $stmt = $conn->prepare("UPDATE users SET verification_token = ? WHERE email = ?");
            $stmt->execute([$verificationToken, $email]);

            echo "Registration successful! Please check your email to verify your account.";
        } catch (Exception $e) {
            die("Error sending confirmation email: " . $e->getMessage());
        }
    } catch(PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
</head>
<body>
    <h2>Register Here</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Name: <input type="text" name="name" required><br>
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        Confirm Password: <input type="password" name="confirm_password" required><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function registerUser($username, $email, $password) {
    global $conn;

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        return "All fields are required!";
    }

    // Check username length
    if (strlen($username) < 3 || strlen($username) > 20) {
        return "Username must be between 3 and 20 characters!";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format!";
    }

    // Check password strength
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long!";
    }

    // Check for existing username or email
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->rowCount() > 0) {
        return "Username or email already exists!";
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert user into database
    try {
        $stmt = $conn->prepare("INSERT INTO users (id, username, email, password) VALUES (?, ?, ?, ?)");
        $userId = uniqid();
        $stmt->execute([$userId, $username, $email, $hashed_password]);

        // Log registration attempt
        $logStmt = $conn->prepare("INSERT INTO logs (user_id, action, timestamp) VALUES (?, 'registered', NOW())");
        $logStmt->execute([$userId]);

        return "Registration successful!";
    } catch(PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        return "An error occurred during registration. Please try again later.";
    }
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    
    $result = registerUser($username, $email, $password);
    echo $result;
}
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'user_registration';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required!";
        header("Location: registration.php?error=$error");
        exit();
    }

    // Check password length
    if (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
        header("Location: registration.php?error=$error");
        exit();
    }

    // Check if passwords match
    if ($password != $confirm_password) {
        $error = "Passwords do not match!";
        header("Location: registration.php?error=$error");
        exit();
    }

    // Sanitize input data to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);

    // Check if username or email already exists
    $check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        $error = "Username or email already exists!";
        header("Location: registration.php?error=$error");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into database
    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['username'] = $username;
        header("Location: welcome.php");
        exit();
    } else {
        $error = "Error registering user!";
        header("Location: registration.php?error=$error");
        exit();
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'root'; // Change to your database username
$password = '';     // Change to your database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function registerUser($username, $email, $password, $conn) {
    // Validate inputs
    if (!validateInput($username, $email, $password)) {
        return false;
    }

    // Check if email already exists in database
    if (emailExists($email, $conn)) {
        return false;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the query
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function validateInput($username, $email, $password) {
    // Check if any of the fields are empty
    if (empty($username) || empty($email) || empty($password)) {
        return false;
    }

    // Validate username
    if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
        return false;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Password must be at least 8 characters long and contain at least one number and special character
    if (!preg_match('/^(?=.*\d)(?=.*[!@#$%^&*()_+}{":?><;~-]).{8,}$/', $password)) {
        return false;
    }

    return true;
}

function emailExists($email, $conn) {
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
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

// Example usage:
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (registerUser($username, $email, $password, $conn)) {
        echo "Registration successful!";
        header("Location: login.php"); // Redirect to login page
    } else {
        echo "Registration failed. Please try again.";
    }
}

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

    // Function to handle user registration
    function registerUser($first_name, $last_name, $email, $password, $conn) {
        // Sanitize inputs
        $first_name = htmlspecialchars(strip_tags(trim($first_name)));
        $last_name = htmlspecialchars(strip_tags(trim($last_name)));
        $email = htmlspecialchars(strip_tags(trim($email)));
        $password = trim($password);

        // Validate inputs
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
            throw new Exception("All fields are required");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is invalid");
        }

        if (strlen($password) < 6) {
            throw new Exception("Password must be at least 6 characters");
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("Email already exists");
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $email, $hashed_password]);

        // Get the new user ID
        $user_id = $conn->lastInsertId();

        // Set session variables
        $_SESSION['id'] = $user_id;
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;

        return true;
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            registerUser($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password'], $conn);
            header("Location: dashboard.php");
            exit();
        } catch (Exception $e) {
            // Set error message in session
            $_SESSION['messages'] = array('error' => $e->getMessage());
            header("Location: register.php");
            exit();
        }
    }

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>


<?php
// Database configuration file
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];

    // Initialize error array
    $errors = [];

    // Validate input fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required!";
    }

    // Check username length
    if (strlen($username) < 4) {
        $errors[] = "Username must be at least 4 characters long!";
    }

    // Check password length and complexity
    if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must be at least 8 characters long and contain both letters and numbers!";
    }

    // Check if password matches confirm password
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match!";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address!";
    }

    // Validate date of birth format
    if (!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $date_of_birth)) {
        $errors[] = "Please enter a valid date of birth!";
    }

    // If there are no errors
    if (empty($errors)) {
        // Sanitize input data
        $username = mysqli_real_escape_string($conn, $username);
        $email = mysqli_real_escape_string($conn, $email);
        $first_name = mysqli_real_escape_string($conn, $first_name);
        $last_name = mysqli_real_escape_string($conn, $last_name);

        // Check if username already exists
        $check_username_query = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $check_username_query);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Username already exists!";
        }

        // Check if email already exists
        $check_email_query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_email_query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Email already registered!";
        }

        // If no errors after checks
        if (empty($errors)) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into database
            $insert_query = "
                INSERT INTO users (
                    username,
                    email,
                    password,
                    first_name,
                    last_name,
                    date_of_birth
                ) VALUES (?, ?, ?, ?, ?, ?)
            ";

            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, 'ssssss', $username, $email, $hashed_password, $first_name, $last_name, $date_of_birth);

            if (mysqli_stmt_execute($stmt)) {
                // Registration successful
                echo "Registration successful!";
                header("Location: login.php");
                exit();
            } else {
                // Error inserting into database
                $errors[] = "An error occurred while registering. Please try again later.";
            }
        }
    }

    // Close database connection
    mysqli_close($conn);

    // Display errors if any
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <!-- Add Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Registration Form</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="mb-3">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="date_of_birth">Date of Birth (YYYY-MM-DD):</label>
                <input type="text" name="date_of_birth" id="date_of_birth" class="form-control" required pattern="[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])">
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

    <!-- Add Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>


<?php
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "your_database_name";

// Create connection
$conn = mysqli_connect($servername, $username_db, $password_db, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<?php
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

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Registration function
function registerUser($conn) {
    // Get form data
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $phone_number = sanitizeInput($_POST['phone_number']);
    $date_of_birth = $_POST['date_of_birth'];

    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        die("All fields must be filled out");
    }

    if ($password !== $confirm_password) {
        die("Passwords do not match");
    }

    // Check if username or email already exists
    $check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        die("Username or email already exists");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users 
            (username, email, password, first_name, last_name, phone_number, date_of_birth) 
            VALUES ('$username', '$email', '$hashed_password', '$first_name', '$last_name', '$phone_number', '$date_of_birth')";

    if (mysqli_query($conn, $sql)) {
        // Registration successful
        header("Location: login.php?success=Registration successful! Please login.");
        exit();
    } else {
        die("Error: " . mysqli_error($conn));
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    registerUser($conn);
} else {
    // Display registration form
    ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label>Username:</label><br>
        <input type="text" name="username"><br>

        <label>Email:</label><br>
        <input type="email" name="email"><br>

        <label>Password:</label><br>
        <input type="password" name="password"><br>

        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password"><br>

        <label>First Name:</label><br>
        <input type="text" name="first_name"><br>

        <label>Last Name:</label><br>
        <input type="text" name="last_name"><br>

        <label>Phone Number:</label><br>
        <input type="tel" name="phone_number"><br>

        <label>Date of Birth:</label><br>
        <input type="date" name="date_of_birth"><br>

        <input type="submit" value="Register">
    </form>
    <?php
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Include necessary files and set session
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Function to validate and sanitize input data
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    
    // Validation errors array
    $errors = array();
    
    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required";
    } else if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
        $errors[] = "Username contains invalid characters";
    }
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } else if (!preg_match("/^(?=.*\d)(?=.*[a-zA-Z])[0-9a-zA-Z]{6,}$/", $password)) {
        $errors[] = "Password must be at least 6 characters long and contain both letters and numbers";
    }
    
    // Check for existing username or email
    if (empty($errors)) {
        $sql = "SELECT id FROM users WHERE username='$username' OR email='$email'";
        $result = mysqli_query($db, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Username or email already exists";
        }
    }
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert into database
        $sql = "INSERT INTO users (username, email, password) 
                VALUES ('$username', '$email', '$hashed_password')";
                
        if (mysqli_query($db, $sql)) {
            // Set session variables and redirect to dashboard
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            header("Location: welcome.php");
            exit();
        } else {
            $errors[] = "Error registering user. Please try again later.";
        }
    }
    
    // Display errors if any
    foreach ($errors as $error) {
        echo "<div class='error'>$error</div>";
    }
}
?>


<?php
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$db_name = 'your_database';

$db = mysqli_connect($host, $username, $password, $db_name);

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<?php
// Configuration file for database connection
include('db_config.php');

function registerUser() {
    $errors = array();
    
    if (isset($_POST['submit'])) {
        // Retrieve form data
        $firstName = mysqli_real_escape_string($GLOBALS['conn'], $_POST['first_name']);
        $lastName = mysqli_real_escape_string($GLOBALS['conn'], $_POST['last_name']);
        $email = mysqli_real_escape_string($GLOBALS['conn'], $_POST['email']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validate input fields
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            array_push($errors, "All fields are required");
        }

        // Check for valid email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Invalid email format");
        }

        // Check password requirements
        $passwordLength = strlen($password);
        if ($passwordLength < 8) {
            array_push($errors, "Password must be at least 8 characters long");
        }
        
        // Check for matching passwords
        if ($password !== $confirmPassword) {
            array_push($errors, "Passwords do not match");
        }

        // If no errors, proceed to register user
        if (count($errors) == 0) {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Check for existing email in database
            $checkEmailQuery = "SELECT id FROM users WHERE email=?";
            $stmt = $GLOBALS['conn']->prepare($checkEmailQuery);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                array_push($errors, "Email already exists");
            } else {
                // Insert new user into database
                try {
                    $insertQuery = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
                    $stmt = $GLOBALS['conn']->prepare($insertQuery);
                    $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);
                    $stmt->execute();

                    // Check if registration was successful
                    if ($stmt->affected_rows > 0) {
                        header("Location: registration_success.php");
                        exit();
                    } else {
                        array_push($errors, "Registration failed. Please try again.");
                    }
                } catch (Exception $e) {
                    array_push($errors, "An error occurred: " . $e->getMessage());
                }
            }

            // Close statement
            $stmt->close();
        }
    }

    return $errors;
}

// Include the form HTML
include('registration_form.php');
?>


<?php
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root'; // Change this to your database username
$password = '';      // Change this to your database password
$db_name = 'user_registration';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Session initialization
session_start();

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get user inputs
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = sanitizeInput($_POST['full_name']);
    $date_of_birth = $_POST['date_of_birth'];

    // Validate inputs
    $errors = array();

    if (empty($username)) {
        $errors[] = "Username is required";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    // Check password strength
    if (!preg_match("/^[a-zA-Z0-9\!\@\#\$\%\&\*\(\)\_\+]+$/', $password)) {
        $errors[] = "Password can only contain letters, numbers, and special characters (!@#$%^&*()+)";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // If there are no errors
    if (empty($errors)) {

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement to check if username or email already exists
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $errors[] = "Username or email already exists";
        } else {

            // Prepare SQL statement to insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, date_of_birth) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $email, $hashed_password, $full_name, $date_of_birth);

            if ($stmt->execute()) {
                // Registration successful
                $_SESSION['username'] = $username;
                header("Location: welcome.php");
                exit();
            } else {
                $errors[] = "Error registering user. Please try again.";
            }
        }

    }

}

// Close the database connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Page</title>
    <style>
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<div class='error'>$error</div>";
    }
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label>Username:</label><br>
    <input type="text" name="username"><br>

    <label>Email:</label><br>
    <input type="email" name="email"><br>

    <label>Password:</label><br>
    <input type="password" name="password"><br>

    <label>Confirm Password:</label><br>
    <input type="password" name="confirm_password"><br>

    <label>Full Name:</label><br>
    <input type="text" name="full_name"><br>

    <label>Date of Birth:</label><br>
    <input type="date" name="date_of_birth"><br>

    <input type="submit" value="Register">
</form>

<p>Already have an account? <a href="login.php">Login here</a></p>

</body>
</html>


<?php
// Database connection details
$host = "localhost";
$dbUsername = "root"; // Replace with your database username
$dbPassword = "";     // Replace with your database password
$dbName = "user_registration"; // Replace with your database name

// Connect to the database
$conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to register a new user
function registerUser() {
    global $conn;

    // Initialize variables
    $username = "";
    $email = "";
    $password = "";
    $confirmPassword = "";
    
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize and validate input data
        $username = htmlspecialchars(trim($_POST['username']));
        $email = htmlspecialchars(trim($_POST['email']));
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        
        // Check if all fields are filled
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            echo "All fields must be filled out!";
            return;
        }
        
        // Validate email format
        if (!preg_match("/^[^\s@]+@[^\s@]+\.[^\s@]+$/.", $email)) {
            echo "Invalid email format!";
            return;
        }
        
        // Check if password and confirm password match
        if ($password != $confirmPassword) {
            echo "Passwords do not match!";
            return;
        }
        
        // Check if username already exists
        $checkUsernameQuery = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $checkUsernameQuery);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            echo "Username already exists!";
            return;
        }
        
        // Check if email already exists
        $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $checkEmailQuery);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            echo "Email already exists!";
            return;
        }
        
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user into database
        $insertQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);
        
        if (mysqli_stmt_execute($stmt)) {
            // Registration successful
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            
            header("Location: welcome.php");
            exit();
        } else {
            echo "Error registering user: " . mysqli_error($conn);
        }
    }
}

// Call the registration function
registerUser();

// Close database connection
mysqli_close($conn);
?>


<?php
// Database connection details
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$database = 'my_database';

// Connect to the database
$conn = mysqli_connect($host, $username_db, $password_db, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to handle user registration
function registerUser() {
    global $conn;

    // Check if form is submitted
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate inputs
        if (empty($username) || empty($email) || empty($password)) {
            echo "All fields are required!";
            return;
        }

        // Sanitize inputs
        $username = htmlspecialchars(strip_tags($username));
        $email = htmlspecialchars(strip_tags($email));

        // Check if username already exists
        $check_username = mysqli_query($conn, "SELECT username FROM users WHERE username='$username'");
        if (mysqli_num_rows($check_username) > 0) {
            echo "Username already exists!";
            return;
        }

        // Check if email already exists
        $check_email = mysqli_query($conn, "SELECT email FROM users WHERE email='$email'");
        if (mysqli_num_rows($check_email) > 0) {
            echo "Email already registered!";
            return;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $sql = "INSERT INTO users (username, email, password)
                VALUES ('$username', '$email', '$hashed_password')";

        if (mysqli_query($conn, $sql)) {
            echo "Registration successful!";
            // Redirect to login page after 3 seconds
            header("Refresh: 3; url=login.php");
        } else {
            echo "Error registering user: " . mysqli_error($conn);
        }
    }
}

// Close database connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
</head>
<body>
    <h2>Register Here</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Username: <input type="text" name="username"><br>
        Email: <input type="email" name="email"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" name="submit" value="Register">
    </form>

    <?php
    // Call the registration function
    if (isset($_POST['submit'])) {
        registerUser();
    }
    ?>
</body>
</html>


<?php
// Database configuration
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill in all required fields";
        exit();
    }
    
    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit();
    }

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "Username already exists";
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "Email already exists";
        exit();
    }

    // Password validation
    $min_length = 8;
    if (strlen($password) < $min_length) {
        echo "Password must be at least $min_length characters long";
        exit();
    }
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        $stmt->execute();
        
        echo "Registration successful!";
        
        // Redirect to login page or dashboard
        header("Location: welcome.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        echo "Error registering user: " . $e->getMessage();
    }
}

// Close database connection
$conn->close();

?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'user_registration';

// Connect to database
$conn = mysqli_connect($host, $username_db, $password_db, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

$errors = array();

// Basic validation
if (empty($name) || empty($email) || empty($password)) {
    $errors[] = "All fields are required!";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format!";
}

if ($password !== $confirm_password) {
    $errors[] = "Passwords do not match!";
}

// Check if username or email already exists
$sql_check = "SELECT id FROM users WHERE email = ? OR username = ?";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "ss", $email, $username);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);

if (mysqli_num_rows($result_check) > 0) {
    $errors[] = "Email or username already exists!";
}

// If no errors
if (empty($errors)) {
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Insert into database
    $sql_insert = "INSERT INTO users (name, email, username, password) VALUES (?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "ssss", $name, $email, $username, $hashed_password);
    
    if (mysqli_stmt_execute($stmt_insert)) {
        // Registration successful
        $_SESSION['success'] = "Registration successful! You can now login.";
        header("Location: register.php");
        exit();
    } else {
        $errors[] = "Error registering user. Please try again later.";
    }
}

// If errors occurred
if (!empty($errors)) {
    foreach ($errors as $error) {
        $_SESSION['error'] .= $error . "<br>";
    }
    header("Location: register.php");
    exit();
}
?>


<?php
// Include database configuration file
include('dbconfig.php');

// Define variables to store form data
$username = "";
$email = "";
$password = "";

// Define error messages
$error_username = "";
$error_email = "";
$error_password = "";
$registration_success = false;

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process registration data

    // Validate username
    if (empty($_POST["username"])) {
        $error_username = "Username is required";
    } else {
        $username = htmlspecialchars(trim($_POST["username"]));
        
        // Check if username contains only letters and numbers
        if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
            $error_username = "Username can only contain letters, numbers, and underscores";
        }
    }

    // Validate email
    if (empty($_POST["email"])) {
        $error_email = "Email is required";
    } else {
        $email = htmlspecialchars(trim($_POST["email"]));
        
        // Check if email format is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_email = "Invalid email format";
        }
    }

    // Validate password
    if (empty($_POST["password"])) {
        $error_password = "Password is required";
    } else {
        $password = $_POST["password"];
        
        // Check if password meets requirements
        if (strlen($password) < 6) {
            $error_password = "Password must be at least 6 characters long";
        }
        if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
            $error_password = "Password can only contain letters and numbers";
        }
    }

    // If no errors, proceed with registration
    if ($error_username == "" && $error_email == "" && $error_password == "") {
        // Check if username or email already exists in the database
        $check_query = "SELECT * FROM users WHERE username=? OR email=?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // User already exists
            if ($row = mysqli_fetch_assoc($result)) {
                if ($row['username'] == $username) {
                    $error_username = "Username already exists";
                }
                if ($row['email'] == $email) {
                    $error_email = "Email already exists";
                }
            }
        } else {
            // Proceed to register the new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert into database
            $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
            
            if (mysqli_stmt_execute($stmt)) {
                // Registration successful
                $registration_success = true;
            } else {
                // Error occurred during registration
                echo "Error: " . mysqli_error($conn);
            }
        }
    }

    // Close database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; }
        .error { color: red; margin-top: 5px; }
        .success { color: green; margin-top: 5px; }
    </style>
</head>
<body>
    <?php if ($registration_success) { ?>
        <div class="success">
            Registration successful! You can now <a href="login.php">log in</a>.
        </div>
    <?php } else { ?>
        <h2>Registration Form</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>"><br>
            <div class="error"><?php echo $error_username; ?></div>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>"><br>
            <div class="error"><?php echo $error_email; ?></div>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password"><br>
            <div class="error"><?php echo $error_password; ?></div>

            <input type="submit" value="Register">
        </form>
    <?php } ?>

    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</body>
</html>


<?php
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "your_database_name";

// Create connection
$conn = mysqli_connect($servername, $username_db, $password_db, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<?php
// Database configuration
$host = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "user_registration";

// Create connection
$conn = new mysqli($host, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form values
    $firstName = sanitizeInput($_POST['first_name']);
    $lastName = sanitizeInput($_POST['last_name']);
    $email = sanitizeInput($_POST['email']);
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Form validation
    $errors = array();

    if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password)) {
        $errors[] = "All fields are required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }

    // Check username and email uniqueness
    $checkUsernameQuery = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkUsernameQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Username already exists";
    }

    $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Email already registered";
    }

    // If no errors, proceed to registration
    if (empty($errors)) {

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into database
        $sql = "INSERT INTO users (first_name, last_name, email, username, password) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $username, $hashedPassword);

        if ($stmt->execute()) {
            echo "Registration successful! You can now <a href='login.php'>log in</a>";
        } else {
            die("Error: " . $sql . "<br>" . $conn->error);
        }

    } else {

        // Display errors
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }

}

// Close database connection
$conn->close();
?>


<?php
// Database connection details
$host = "localhost";
$username_db = "username";
$password_db = "password";
$db_name = "mydatabase";

// Connect to the database
$conn = new mysqli($host, $username_db, $password_db, $db_name);

// Check for database connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to register a new user
function registerUser() {
    global $conn;

    // Initialize error array
    $errors = array();

    if (isset($_POST['register'])) {
        // Escape and trim the input values
        $username = mysqli_real_escape_string($conn, trim($_POST['username']));
        $email = mysqli_real_escape_string($conn, trim($_POST['email']));
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $first_name = mysqli_real_escape_string($conn, trim($_POST['first_name']));
        $last_name = mysqli_real_escape_string($conn, trim($_POST['last_name']));

        // Validate username
        if (empty($username)) {
            $errors[] = "Username is required";
        } elseif (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
            $errors[] = "Username must be between 3 and 20 characters long and contain only letters, numbers, or underscores";
        }

        // Validate email
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!preg_match("/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/", $email)) {
            $errors[] = "Please enter a valid email address";
        }

        // Validate password
        if (empty($password)) {
            $errors[] = "Password is required";
        } elseif (!preg_match("/^.{6,}$/", $password)) {
            $errors[] = "Password must be at least 6 characters long";
        }

        // Check if passwords match
        if ($password != $confirm_password) {
            $errors[] = "Passwords do not match";
        }

        // Validate first name and last name
        if (empty($first_name)) {
            $errors[] = "First name is required";
        }
        if (empty($last_name)) {
            $errors[] = "Last name is required";
        }

        // Check if username or email already exists
        $check_query = "SELECT * FROM users WHERE username LIKE ? OR email LIKE ?";
        $stmt_check = $conn->prepare($check_query);
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['username'] == $username) {
                    $errors[] = "Username already exists";
                }
                if ($row['email'] == $email) {
                    $errors[] = "Email already exists";
                }
            }
        }

        // If no errors, proceed to register the user
        if (empty($errors)) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert into database
            $query = "INSERT INTO users (username, email, password, first_name, last_name) 
                      VALUES (?, ?, ?, ?, ?)";
            
            $stmt_insert = $conn->prepare($query);
            $stmt_insert->bind_param("sssss", $username, $email, $hashed_password, $first_name, $last_name);

            if ($stmt_insert->execute()) {
                // Registration successful
                header("Location: welcome.php?success=1");
                exit();
            } else {
                $errors[] = "There was a problem registering your account. Please try again later.";
            }

            // Close the statement
            $stmt_insert->close();
        }

        // Close the check statement
        $stmt_check->close();
    }

    // Close database connection
    $conn->close();

    return $errors;
}

// Call the function
$errors = registerUser();
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'mydatabase';

// Connect to the database
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    global $conn;

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form values
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Sanitize inputs
        $username = htmlspecialchars(trim($username));
        $email = htmlspecialchars(trim($email));

        // Validate inputs
        if (empty($username) || empty($email) || empty($password)) {
            echo "All fields are required!";
            return;
        }

        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Username already exists!";
            return;
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Email already exists!";
            return;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Registration successful! You can now login.";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
}

// Call the function when form is submitted
if (isset($_POST['submit'])) {
    registerUser();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <style>
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
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
        <h2>Registration Form</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="submit">Register</button>
        </form>
    </div>
</body>
</html>


<?php
// Database connection
$host = "localhost";
$username = "root"; // your database username
$password = "";     // your database password
$dbname = "testdb";  // your database name

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    $error = array();

    // Clean and validate input data
    function clean($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    $username = clean($_POST['username']);
    $email = clean($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = clean($_POST['first_name']);
    $last_name = clean($_POST['last_name']);
    $dob = clean($_POST['dob']);
    $phone_number = clean($_POST['phone_number']);
    $address = clean($_POST['address']);

    // Check for empty fields
    if ($username == "" || $email == "" || $password == "" || $confirm_password == "") {
        $error[] = "All fields must be filled out!";
    }

    // Validate password
    if (strlen($password) < 8) {
        $error[] = "Password must be at least 8 characters long!";
    }

    if (!preg_match("#[A-Z]#", $password)) {
        $error[] = "Password must contain at least one uppercase letter!";
    }

    if (!preg_match("#[a-z]#", $password)) {
        $error[] = "Password must contain at least one lowercase letter!";
    }

    if (!preg_match("#[0-9]#", $password)) {
        $error[] = "Password must contain at least one number!";
    }

    // Check password match
    if ($password != $confirm_password) {
        $error[] = "Passwords do not match!";
    }

    // Check username already exists
    $sql_username = "SELECT id FROM users WHERE username = ?";
    $stmt_username = $conn->prepare($sql_username);
    $stmt_username->bind_param("s", $username);
    $stmt_username->execute();
    $result_username = $stmt_username->get_result();

    if ($result_username->num_rows > 0) {
        $error[] = "Username already exists!";
    }

    // Check email already exists
    $sql_email = "SELECT id FROM users WHERE email = ?";
    $stmt_email = $conn->prepare($sql_email);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $result_email = $stmt_email->get_result();

    if ($result_email->num_rows > 0) {
        $error[] = "Email already exists!";
    }

    // If no errors
    if (empty($error)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $sql_insert = "INSERT INTO users (username, email, password, first_name, last_name, dob, phone_number, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssssssss", $username, $email, $hashed_password, $first_name, $last_name, $dob, $phone_number, $address);

        if ($stmt_insert->execute()) {
            echo "Registration successful!";
            // Optionally redirect to login page
            header("Refresh: 3; url=login.php");
        } else {
            die("Error registering user: " . $conn->error);
        }
    } else {
        foreach ($error as $err) {
            echo "<div class='alert alert-danger'>$err</div>";
        }
    }

    // Close statements
    $stmt_username->close();
    $stmt_email->close();
    $stmt_insert->close();
}

// Close connection
$conn->close();
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Registration Form</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="row">
                <!-- Username -->
                <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email address:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <!-- Password -->
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6 mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>

                <!-- First Name -->
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name:</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>

                <!-- Last Name -->
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>

                <!-- Date of Birth -->
                <div class="col-md-6 mb-3">
                    <label for="dob" class="form-label">Date of Birth:</label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>

                <!-- Phone Number -->
                <div class="col-md-6 mb-3">
                    <label for="phone_number" class="form-label">Phone Number:</label>
                    <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                </div>

                <!-- Address -->
                <div class="col-md-12 mb-3">
                    <label for="address" class="form-label">Address:</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                </div>

                <!-- Submit Button -->
                <div class="col-md-12 mt-4">
                    <button type="submit" name="submit" class="btn btn-primary">Register</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'mydatabase';

// Create connection
$conn = mysqli_connect($host, $username, $password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function register_user($username, $email, $password, $conn) {
    // Sanitize inputs
    $username = sanitize_input($username);
    $email = sanitize_input($email);
    $password = sanitize_input($password);

    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        return "Please fill in all required fields";
    }

    // Check if email already exists
    $check_email_query = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $check_email_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        return "Email already exists. Please use a different email address.";
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $register_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $register_query);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
        return "Registration successful! You can now login.";
    } else {
        return "Error registering user: " . mysqli_error($conn);
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Call the registration function
    $result = register_user($username, $email, $password, $conn);
    echo $result;
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Database configuration
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "user_registration";

// Connect to database
$conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user inputs and sanitize them
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if user already exists
    $checkQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        die("User already exists. Please login.");
    }
    
    // Insert new user into the database
    $sql = "INSERT INTO users (username, email, password_hash)
            VALUES (?, ?, ?)";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);
    
    if (mysqli_stmt_execute($stmt)) {
        // Registration successful, redirect to login page
        header("Location: login.php?success=1");
        exit();
    } else {
        echo "Error registering user: " . mysqli_error($conn);
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Database connection configuration
$host = "localhost";
$username_db = "root";
$password_db = "";
$db_name = "your_database";

// Create database connection
$conn = new mysqli($host, $username_db, $password_db, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function register_user($username, $email, $password, $confirm_password, $full_name, $dob, $address) {
    global $conn;
    
    // Error array to store validation errors
    $errors = [];
    
    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required";
    } else {
        // Check for invalid characters
        if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
            $errors[] = "Username can only contain letters, numbers, and underscores";
        }
        if (strlen($username) < 3 || strlen($username) > 20) {
            $errors[] = "Username must be between 3 and 20 characters";
        }
    }
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        // Check if email already exists in database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $errors[] = "Email already exists";
        }
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } else {
        // Password must be at least 8 characters long
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters";
        }
        
        // Check for uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter";
        }
        
        // Check for lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter";
        }
        
        // Check for number
        if (!preg_match('/\d/', $password)) {
            $errors[] = "Password must contain at least one number";
        }
        
        // Check for special character
        if (!preg_match('/[^A-Za-z\d]/', $password)) {
            $errors[] = "Password must contain at least one special character";
        }
    }
    
    // Validate confirm password
    if ($confirm_password != $password) {
        $errors[] = "Passwords do not match";
    }
    
    // Validate full name
    if (empty($full_name)) {
        $errors[] = "Full name is required";
    } else {
        // Check for at least two names
        $names = explode(" ", $full_name);
        if (count($names) < 2) {
            $errors[] = "Please enter your full name (first and last name)";
        }
    }
    
    // Validate date of birth
    if (empty($dob)) {
        $errors[] = "Date of birth is required";
    } else {
        // Check if the age is at least 13 years old
        $today = new DateTime();
        $birth_date = new DateTime($dob);
        
        $age = $today->diff($birth_date)->y;
        
        if ($age < 13) {
            $errors[] = "You must be at least 13 years old to register";
        }
    }
    
    // Validate address
    if (!empty($address)) {
        // Address should not be too short
        if (strlen($address) < 5) {
            $errors[] = "Address is invalid or too short";
        }
    }
    
    // If there are errors, return them
    if (!empty($errors)) {
        return [
            'success' => false,
            'errors' => $errors
        ];
    }
    
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);
    $full_name = mysqli_real_escape_string($conn, $full_name);
    $dob = mysqli_real_escape_string($conn, $dob);
    $address = mysqli_real_escape_string($conn, $address);
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Insert user into database
    $sql = "INSERT INTO users (username, email, password, full_name, dob, address) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $username, $email, $hashed_password, $full_name, $dob, $address);
    
    if ($stmt->execute()) {
        return [
            'success' => true,
            'message' => "Registration successful!"
        ];
    } else {
        return [
            'success' => false,
            'errors' => ["An error occurred during registration"]
        ];
    }
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = $_POST['full_name'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    
    $result = register_user($username, $email, $password, $confirm_password, $full_name, $dob, $address);
    
    if ($result['success']) {
        echo "Success: " . $result['message'];
    } else {
        foreach ($result['errors'] as $error) {
            echo "Error: " . $error . "<br>";
        }
    }
}
?>


<?php
// Database connection configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'user_registration');

// Connect to the database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function registerUser() {
    global $conn;
    
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get user inputs
        $username = htmlspecialchars(trim($_POST['username']));
        $email = htmlspecialchars(trim($_POST['email']));
        $password = trim($_POST['password']);
        
        // Validate inputs
        if (empty($username) || empty($email) || empty($password)) {
            echo "Please fill in all required fields!";
            return;
        }
        
        // Sanitize email
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        
        // Check if username or email already exists
        $checkQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            echo "Username or email already exists!";
            return;
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user into database
        $insertQuery = "INSERT INTO users (username, email, password, role)
                       VALUES (?, ?, ?, 'user')";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "Registration successful! Redirecting to login page...";
            header("Location: login.php");
            exit();
        } else {
            echo "Error registering user: " . mysqli_error($conn);
        }
    }
}

try {
    registerUser();
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$db_name = "user_registration";

// Connect to database
$conn = mysqli_connect($host, $username, $password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function registerUser() {
    global $conn;

    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birthdate = $_POST['birthdate'];

    // Validate input fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "Please fill in all required fields";
        return;
    }

    if ($password !== $confirm_password) {
        echo "Passwords do not match";
        return;
    }

    // Check if username already exists
    $checkUsernameQuery = "SELECT id FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $checkUsernameQuery);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "Username already exists";
        return;
    }

    // Check if email already exists
    $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $checkEmailQuery);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "Email already exists";
        return;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $insertQuery = "INSERT INTO users (username, email, password, first_name, last_name, birthdate) 
                    VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmt, "ssssss", $username, $email, $hashed_password, $first_name, $last_name, $birthdate);

    if (mysqli_stmt_execute($stmt)) {
        echo "Registration successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Call the registration function when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    registerUser();
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Database connection details
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'user_registration');

// Connect to the database
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Sanitize and store user inputs
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    
    // Validate input fields
    $errors = array();
    
    if (empty($username) || strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long!";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address!";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required!";
    } else {
        // Check password strength
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long!";
        }
        
        if (!ctype_upper(str_replace(array(' ', '-'), '', $username))) {
            $errors[] = "Username must contain uppercase letters!";
        }
        
        if (!preg_match('#[0-9]#', $password)) {
            $errors[] = "Password must contain at least one number!";
        }
        
        if (!preg_match('#[!@#$%^&*()]#', $password)) {
            $errors[] = "Password must contain at least one special character!";
        }
    }
    
    // If there are errors, display them
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // Sanitize data before storing in database
        $username = mysqli_real_escape_string($connection, $username);
        $email = mysqli_real_escape_string($connection, $email);
        
        // Hash the password using bcrypt
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        // Check if username already exists
        $check_username_query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($connection, $check_username_query);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<div class='alert alert-danger'>Username already exists!</div>";
        } else {
            // Check if email already exists
            $check_email_query = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($connection, $check_email_query);
            
            if (mysqli_num_rows($result) > 0) {
                echo "<div class='alert alert-danger'>Email already exists!</div>";
            } else {
                // Insert new user into the database
                $insert_query = "INSERT INTO users (username, email, password) 
                                VALUES ('$username', '$email', '$hashed_password')";
                
                if (mysqli_query($connection, $insert_query)) {
                    echo "<div class='alert alert-success'>Registration successful! You can now login.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error registering user!</div>";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <style>
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-danger {
            background-color: #f2d6d7;
            color: #a94442;
            border-color: #ebccd1;
        }
        
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border-color: #bce8b5;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registration Form</h2>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Enter your username..." required><br><br>
            <input type="email" name="email" placeholder="Enter your email..." required><br><br>
            <input type="password" name="password" placeholder="Enter your password..." required><br><br>
            <button type="submit" name="submit">Register</button>
        </form>
    </div>
</body>
</html>


<?php
function registerUser($username, $email, $password) {
    // Database connection parameters
    $host = "localhost";
    $db_username = "root";
    $db_password = "";
    $database = "my_database";

    // Connect to the database
    $conn = new mysqli($host, $db_username, $db_password, $database);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validate inputs
    $username = htmlspecialchars(trim($username));
    $email = htmlspecialchars(trim($email));
    $password = trim($password);

    // Basic input validation
    if (empty($username) || empty($email) || empty($password)) {
        return "All fields are required!";
    }

    // Validate username format
    if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
        return "Username contains invalid characters!";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format!";
    }

    // Check if username already exists
    $check_username = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_username);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Username already exists!";
    }

    // Check if email already exists
    $check_email = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Email already exists!";
    }

    // Validate password requirements
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long!";
    }

    if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
        return "Password contains invalid characters!";
    }

    if (!preg_match('/[A-Za-z]/', $password)) {
        return "Password must contain letters!";
    }

    if (!preg_match('/[0-9]/', $password)) {
        return "Password must contain numbers!";
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $insert_user = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_user);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        return "Registration successful!";
    } else {
        return "Error registering user: " . $stmt->error;
    }

    // Close the database connection
    $conn->close();
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = registerUser($username, $email, $password);
    echo $result;
}
?>


<?php
// Database configuration
$host = "localhost";
$db_username = "root"; // Change to your database username
$db_password = "";     // Change to your database password
$db_name = "test_db";  // Change to your database name

// Connect to the database
$conn = new mysqli($host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function for user registration
function registerUser() {
    global $conn;

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Get form data
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];

        // Validate input fields
        if (empty($username) || empty($password) || empty($email)) {
            die("Please fill in all required fields.");
        }

        // Check if username already exists
        $checkUsernameQuery = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($checkUsernameQuery);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            die("Username already exists.");
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $insertQuery = "INSERT INTO users (username, password, email, first_name, last_name)
                        VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sssss", $username, $hashedPassword, $email, $firstName, $lastName);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            die("Error registering user.");
        }

    }
}

// Call the registration function
registerUser();

// Close database connection
$conn->close();
?>


<?php
// Database connection
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'mydatabase';

// Connect to database
$conn = mysqli_connect($host, $username_db, $password_db, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Function to sanitize input data
    function sanitize($data) {
        return htmlspecialchars(trim($data));
    }

    // Get user inputs
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $agree_terms = isset($_POST['agree']);

    // Initialize error array
    $errors = array();

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required!";
    }

    if (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long!";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address!";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match!";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long!";
    }

    if (!$agree_terms) {
        $errors[] = "You must agree to the terms and conditions!";
    }

    // Check if username or email already exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Username or email already exists!";
        }
    }

    // If no errors, register the user
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        
        if ($stmt->execute()) {
            echo "Registration successful! You can now login.";
        } else {
            $errors[] = "Error registering user. Please try again!";
        }
    }

    // Close statement
    $stmt->close();
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <?php if (isset($errors)) { ?>
            <?php foreach ($errors as $error) { ?>
                <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
            <?php } ?>
        <?php } ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="bg-white p-5 rounded shadow">
            <h2 class="text-center mb-4">Registration Form</h2>

            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="agree" name="agree" required>
                <label class="form-check-label" for="agree">I agree to the terms and conditions</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "All fields are required!";
    } else if (!validateUsername($username)) {
        $message = "Invalid username. Only letters, numbers, underscores, and hyphens are allowed.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
    } else if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else if (!validatePassword($password)) {
        $message = "Password must be at least 8 characters long and contain letters and numbers.";
    } else {
        try {
            // Check for existing username or email
            $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username OR email=:email");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                throw new Exception("Username or email already exists.");
            }

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert into database
            $insertStmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $insertStmt->bindParam(':username', $username);
            $insertStmt->bindParam(':email', $email);
            $insertStmt->bindParam(':password', $hashed_password);

            if ($insertStmt->execute()) {
                $message = "Registration successful! You can now login.";
            } else {
                throw new Exception("Error registering user.");
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }

    echo "<script>alert('$message');</script>";
}
$conn = null;

function validateUsername($username) {
    return preg_match("/^[a-zA-Z0-9_\-]*$/", $username);
}

function validatePassword($password) {
    if (strlen($password) < 8) return false;
    if (!preg_match("#[A-Za-z]#", $password)) return false;
    if (!preg_match("#[0-9]#", $password)) return false;
    return true;
}
?>


<?php
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'root'; // default username for local development
$password = '';      // default password for local development

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


<?php
function registerUser() {
    // Database connection (replace with your actual connection)
    $conn = mysqli_connect("localhost", "username", "password", "database");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if form is submitted
    if (isset($_POST['submit'])) {
        $error = false;

        // Sanitize and validate input data
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate username
        if (empty($username)) {
            $error = true;
            $error_message .= "Username is required.<br>";
        } elseif (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
            $error = true;
            $error_message .= "Username contains invalid characters.<br>";
        } elseif (strlen($username) < 3 || strlen($username) > 20) {
            $error = true;
            $error_message .= "Username must be between 3 and 20 characters long.<br>";
        }

        // Validate email
        if (empty($email)) {
            $error = true;
            $error_message .= "Email is required.<br>";
        } elseif (!preg_match("/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z]{2,4}$/", $email)) {
            $error = true;
            $error_message .= "Invalid email format.<br>";
        }

        // Validate password
        if (empty($password) || empty($confirm_password)) {
            $error = true;
            $error_message .= "Password is required.<br>";
        } elseif ($password !== $confirm_password) {
            $error = true;
            $error_message .= "Passwords do not match.<br>";
        } elseif (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])/u", $password)) {
            $error = true;
            $error_message .= "Password must contain at least one uppercase letter, one lowercase letter, one number, and a special character.<br>";
        } elseif (strlen($password) < 8) {
            $error = true;
            $error_message .= "Password must be at least 8 characters long.<br>";
        }

        // If no errors
        if (!$error) {
            // Check if username already exists
            $check_username_query = "SELECT id FROM users WHERE username = ?";
            $stmt = mysqli_prepare($conn, $check_username_query);
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $error = true;
                $error_message .= "Username already exists.<br>";
            }

            // Check if email already exists
            $check_email_query = "SELECT id FROM users WHERE email = ?";
            $stmt = mysqli_prepare($conn, $check_email_query);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $error = true;
                $error_message .= "Email already exists.<br>";
            }

            // Close the prepared statements
            mysqli_stmt_close($stmt);
        }

        // If no errors, insert into database
        if (!$error) {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT, array('cost' => 12));

            // Insert user data into table
            $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

            if (mysqli_stmt_execute($stmt)) {
                // Registration successful
                echo "Registration successful! You can now <a href='login.php'>login</a>.";
                
                // Optional: Log the registration attempt
                log_registration_attempt('success', $username);
                
                // Redirect after success
                header("Location: login.php");
                exit();
            } else {
                // Error in inserting data
                $error = true;
                $error_message .= "Error registering. Please try again later.<br>";
            }
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);

        if ($error) {
            echo "<div class='alert alert-danger'>" . $error_message . "</div>";
            
            // Optional: Log registration attempt with errors
            log_registration_attempt('failed', $username, $error_message);
        }

    } else {
        // Form not submitted yet
        header("Location: register.php");
        exit();
    }

    // Close database connection
    mysqli_close($conn);
}

// Example usage:
registerUser();

// Optional function to log registration attempts
function log_registration_attempt($status, $username, $error_message = '') {
    $log_file = 'registration_log.txt';
    
    $log_entry = date('Y-m-d H:i:s') . " - Status: " . $status;
    if ($username) {
        $log_entry .= " - Username: " . htmlspecialchars($username);
    }
    if ($error_message) {
        $log_entry .= " - Error: " . htmlspecialchars($error_message);
    }
    $log_entry .= "
";

    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}
?>


<?php
// Connect to the database
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize input data
    function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);

    // Validate inputs
    $errors = array();

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required!";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format!";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match!";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long!";
    }

    if (!isset($_POST['agree'])) {
        $errors[] = "You must agree to the terms and conditions!";
    }

    // If there are errors, display them
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='error'>$error</div>";
        }
    } else {
        // Proceed with registration

        // Check if username already exists
        try {
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->rowCount() > 0) {
                die("Username already exists!");
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }

        // Check if email already exists
        try {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                die("Email already registered!");
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }

        // Generate activation token
        $activation_token = bin2hex(random_bytes(16));

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        try {
            $stmt = $conn->prepare("
                INSERT INTO users 
                (username, email, password, first_name, last_name, activation_token, is_active)
                VALUES (?, ?, ?, ?, ?, ?, 0)
            ");
            $stmt->execute([$username, $email, $hashed_password, $first_name, $last_name, $activation_token]);

            // Send activation email
            $to = $email;
            $subject = "Account Activation";
            $message = "
                <html>
                    <head>
                        <title>Activate your account</title>
                    </head>
                    <body>
                        <p>Please click the following link to activate your account:</p>
                        <a href='http://example.com/activate.php?token=$activation_token'>Activate Now</a>
                    </body>
                </html>
            ";
            $headers = "MIME-Version: 1.0" . "\r
";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
            $headers .= "From: admin@example.com";

            mail($to, $subject, $message, $headers);

            // Redirect to success page
            header("Location: registration_success.php");
            exit();
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
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
    <h1>Create Account</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username"><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password"><br>

        <label for="first_name">First Name:</label><br>
        <input type="text" id="first_name" name="first_name"><br>

        <label for="last_name">Last Name:</label><br>
        <input type="text" id="last_name" name="last_name"><br>

        <input type="checkbox" id="agree" name="agree">
        <label for="agree">I agree to the terms and conditions</label><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>


<?php
// Connect to database (same as above)
require_once 'db_connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE activation_token = ? AND is_active = 0");
        $stmt->execute([$token]);
        if ($stmt->rowCount() == 1) {
            list($user_id, $username) = $stmt->fetch(PDO::FETCH_NUM);

            // Activate the user
            $update_stmt = $conn->prepare("UPDATE users SET is_active = 1, activation_token = NULL WHERE id = ?");
            $update_stmt->execute([$user_id]);

            echo "Account activated successfully!";
            exit();
        } else {
            die("Invalid or expired activation link.");
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    die("No activation token provided.");
}
?>


<?php
session_start();
// Database configuration
$host = "localhost";
$username = "root"; // database username
$password = ""; // database password
$database = "mydb"; // database name

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Registration function
function registerUser() {
    global $conn;
    
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize inputs
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars(filter_var($_POST['email'], FILTER_SANITIZE_STRING));
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $first_name = htmlspecialchars($_POST['first_name']);
        $last_name = htmlspecialchars($_POST['last_name']);

        // Validate inputs
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            echo "All fields are required!";
            return;
        }

        if ($password != $confirm_password) {
            echo "Passwords do not match!";
            return;
        }

        // Check if username already exists
        $checkUsernameQuery = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($checkUsernameQuery);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Username already exists!";
            return;
        }

        // Check if email already exists
        $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($checkEmailQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Email already exists!";
            return;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into database
        try {
            $insertQuery = "INSERT INTO users (username, email, password, first_name, last_name) 
                            VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sssss", $username, $email, $hashed_password, $first_name, $last_name);

            if ($stmt->execute()) {
                // Registration successful
                $_SESSION['username'] = $username;
                header("Location: welcome.php");
                exit();
            } else {
                echo "Error registering user!";
            }
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }
}

// Close database connection
$conn->close();
?>

<!-- HTML registration form -->
<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
</head>
<body>
    <h2>Register Here:</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>
        <input type="text" name="first_name" placeholder="First Name" required><br><br>
        <input type="text" name="last_name" placeholder="Last Name" required><br><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>

<?php
// Call the registration function
registerUser();
?>


<?php
// Database connection settings
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'registration_db';

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to register a new user
function registerUser() {
    global $conn;

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get and sanitize input data
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validate input
        if (empty($username) || empty($email) || empty($password)) {
            echo "All fields must be filled out!";
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format!";
            return;
        }

        if ($password !== $confirmPassword) {
            echo "Passwords do not match!";
            return;
        }

        // Check if username already exists
        $checkUsername = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($checkUsername);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Username already exists!";
            return;
        }

        // Check if email already exists
        $checkEmail = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($checkEmail);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Email already exists!";
            return;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "Registration successful!";
            // You can redirect to login page here
            header("Location: login.php");
            exit();
        } else {
            echo "Error registering user: " . $conn->error;
        }

        $stmt->close();
    }
}

// Close database connection
$conn->close();
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
</head>
<body>
    <h2>Register Here</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Username: <input type="text" name="username"><br><br>
        Email: <input type="email" name="email"><br><br>
        Password: <input type="password" name="password"><br><br>
        Confirm Password: <input type="password" name="confirm_password"><br><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>

// Call the registration function
<?php
registerUser();
?>


<?php
// Database connection details
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'user_registration';

// Connect to the database
$conn = mysqli_connect($host, $username_db, $password_db, $db_name);

// Check if connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// User registration function
function registerUser() {
    global $conn;
    
    // Check if form is submitted
    if (isset($_POST['submit'])) {
        // Get form values
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Sanitize input
        $username = mysqli_real_escape_string($conn, trim($username));
        $email = mysqli_real_escape_string($conn, trim($email));
        $password = mysqli_real_escape_string($conn, trim($password));
        
        // Validate input
        if (empty($username) || empty($email) || empty($password)) {
            echo "Please fill in all fields!";
            return;
        }
        
        // Check if username already exists
        $check_username_query = "SELECT username FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $check_username_query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            echo "Username already exists!";
            return;
        }
        
        // Check if email already exists
        $check_email_query = "SELECT email FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_email_query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            echo "Email already exists!";
            return;
        }
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format!";
            return;
        }
        
        // Password validation
        $min_length = 8;
        if (strlen($password) < $min_length) {
            echo "Password must be at least 8 characters long!";
            return;
        }
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user into database
        $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "Registration successful!";
        } else {
            echo "Error registering user: " . mysqli_error($conn);
        }
    }
}

// Display registration form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        .container {
            width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f2f2f2;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
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
        <h2>Register Here</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required><br>
            </div>
            <div class="form-group">
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required><br>
            </div>
            <div class="form-group">
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br>
            </div>
            <button type="submit" name="submit">Register</button>
        </form>
    </div>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>


<?php
function registerUser($username, $email, $password) {
    // Sanitize inputs to prevent SQL injection
    $username = htmlspecialchars(trim($username));
    $email = htmlspecialchars(trim($email));
    $password = trim($password);

    // Check if all required fields are filled out
    if (empty($username) || empty($email) || empty($password)) {
        return "All fields must be filled out";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Please enter a valid email address";
    }

    // Create a connection to the database
    $conn = new mysqli('localhost', 'username', 'password', 'database_name');

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if username or email already exists in database
    $checkQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Check if the username already exists
        while ($row = $result->fetch_assoc()) {
            if ($row['username'] == $username) {
                return "Username already exists";
            } else if ($row['email'] == $email) {
                return "Email already in use";
            }
        }
    }

    // Insert the new user into the database
    $insertQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Registration successful
        return "Registration successful!";
    } else {
        // Error occurred during registration
        return "Error: " . $stmt->error;
    }

    // Close the database connection
    $conn->close();
}
?>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = registerUser($username, $email, $password);
    echo $result;
}
?>


<?php
// Database configuration
require_once 'db_config.php';

function registerUser() {
    // Check if form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Sanitize input data
        $username = sanitizeInput($username);
        $email = sanitizeInput($email);
        $password = sanitizeInput($password);

        $errors = array();

        // Validate username
        if (empty($username)) {
            $errors[] = "Username is required";
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = "Username can only contain letters, numbers, and underscores";
        }

        // Validate email
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        // Validate password
        if (empty($password)) {
            $errors[] = "Password is required";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long";
        } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
            $errors[] = "Password can only contain letters and numbers";
        }

        // If there are errors, display them
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div class='error'>$error</div>";
            }
            return;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT, array('cost' => 12));

        try {
            // Check if username or email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<div class='error'>Username or email already exists</div>";
                return;
            }

            // Insert new user into database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "<div class='success'>Registration successful! You can now login.</div>";
                // Redirect to login page after some seconds
                header("Refresh: 2; url=login.php");
            } else {
                echo "<div class='error'>Error registering user</div>";
            }
        } catch (Exception $e) {
            echo "<div class='error'>An error occurred: " . $e->getMessage() . "</div>";
        }
    }
}

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(trim($strip_tags($data)));
}

// Close database connection
$conn->close();
?>



<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Registration function
function registerUser($username, $email, $password, $conn) {
    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        return "All fields are required!";
    }

    // Check if username already exists
    $checkUsername = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $checkUsername);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        return "Username already exists!";
    }

    // Check email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format!";
    }

    // Check password strength
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long!";
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

    if (mysqli_execute($stmt)) {
        return "Registration successful!";
    } else {
        return "Error: " . mysqli_error($conn);
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $result = registerUser($username, $email, $password, $conn);

    if (strpos($result, 'successful') !== false) {
        header("Location: welcome.php");
        exit();
    } else {
        echo $result;
    }
}

mysqli_close($conn);
?>

<!-- Registration Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
</head>
<body>
    <h2>Register Here</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$"><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>


<?php
// Database connection details
$host = 'localhost';
$username = 'root'; // Replace with your database username
$password = '';      // Replace with your database password
$dbname = 'mydatabase'; // Replace with your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to validate email format
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate password strength
function isValidPassword($password) {
    // At least 8 characters long and contains at least one letter and one number
    return preg_match('/^(?=.*\d)(?=.*[a-zA-Z])[0-9a-zA-Z!@#$%^&*()_+}{":;?><~\-|=]{8,}$/', $password);
}

// Function to handle registration
function registerUser($username, $email, $password) {
    global $conn;

    // Check if username already exists
    $checkUsernameQuery = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkUsernameQuery);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return 'Username already exists.';
    }

    // Check if email already exists
    $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return 'Email already exists.';
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

    // Insert new user into database
    $registerQuery = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($registerQuery);
    $stmt->bind_param('sss', $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        return 'Registration successful!';
    } else {
        return 'Error registering user: ' . $stmt->error;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input data
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Initialize errors array
    $errors = [];

    // Validate username
    if (empty($username)) {
        $errors[] = 'Username is required.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]*$/', $username)) {
        $errors[] = 'Username can only contain letters, numbers, and underscores.';
    }

    // Validate email
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!isValidEmail($email)) {
        $errors[] = 'Invalid email format.';
    }

    // Validate password
    if (empty($password)) {
        $errors[] = 'Password is required.';
    } else {
        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        } elseif (!isValidPassword($password)) {
            $errors[] = 'Password must be at least 8 characters long and contain at least one letter and one number.';
        }
    }

    // If there are errors, display them
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // Register the user
        $result = registerUser($username, $email, $password);
        echo "<div class='alert alert-success'>$result</div>";
    }

    // Close statement and connection
    $stmt->close();
}

// Close database connection
$conn->close();
?>


<?php
// Database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function registerUser() {
    global $conn;

    // Check if form is submitted
    if (isset($_POST['submit'])) {
        // Sanitize inputs
        $firstname = htmlspecialchars(trim($_POST['firstname']));
        $lastname = htmlspecialchars(trim($_POST['lastname']));
        $email = htmlspecialchars(trim($_POST['email']));
        $username = htmlspecialchars(trim($_POST['username']));
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate inputs
        if (empty($firstname) || empty($lastname) || empty($email) || empty($username) || empty($password)) {
            echo "Please fill in all required fields!";
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Please enter a valid email address!";
            return;
        }

        if (strlen($password) < 8) {
            echo "Password must be at least 8 characters long!";
            return;
        }

        if ($password !== $confirm_password) {
            echo "Passwords do not match!";
            return;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if username already exists
        $check_username_query = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $check_username_query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "Username already exists!";
            return;
        }

        // Check if email already exists
        $check_email_query = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_email_query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "Email already exists!";
            return;
        }

        // Insert user into database
        $insert_query = "
            INSERT INTO users 
            SET firstname = ?, 
                lastname = ?,
                email = ?,
                username = ?,
                password = ?
        ";

        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "sssss", $firstname, $lastname, $email, $username, $hashed_password);

        if (mysqli_stmt_execute($stmt)) {
            echo "Registration successful! You can now login.";
            // Redirect to login page after 2 seconds
            header("Refresh:2; url=login.php");
        } else {
            echo "Error registering user: " . mysqli_error($conn);
        }
    }
}

// Call the registration function
registerUser();

// Close database connection
mysqli_close($conn);
?>


<?php
// Connect to the database
$mysqli = new mysqli('localhost', 'username', 'password', 'database_name');

// Check for database connection errors
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Function to sanitize input data
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

// Register function
function register_user() {
    global $mysqli;

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form values
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Sanitize input data
        $first_name = sanitize($first_name);
        $last_name = sanitize($last_name);
        $email = sanitize($email);

        // Validate input data
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
            die("All fields are required!");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Please enter a valid email address!");
        }

        if ($password !== $confirm_password) {
            die("Passwords do not match!");
        }

        // Check password length
        if (strlen($password) < 8) {
            die("Password must be at least 8 characters long!");
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement to insert user into database
        $stmt = $mysqli->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
        
        if (!$stmt) {
            die("Prepare failed: " . $mysqli->error);
        }

        // Bind parameters
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Registration successful!";
            // Redirect to login page or dashboard after some seconds
            header("Refresh: 2; url=login.php");
        } else {
            die("Error registering user: " . $mysqli->error);
        }

        // Close the statement
        $stmt->close();
    }
}

// Call the register function
register_user();

// Close database connection
$mysqli->close();
?>


<?php
// Connect to the database
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

// Register function
function registerUser() {
    global $conn;
    
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input data
        $username = htmlspecialchars(trim($_POST['username']));
        $email = htmlspecialchars(trim($_POST['email']));
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Validate input
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            echo "All fields are required!";
            return;
        }
        
        if ($password !== $confirm_password) {
            echo "Passwords do not match!";
            return;
        }
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format!";
            return;
        }
        
        // Password requirements: minimum 8 characters with at least one special character and number
        if (strlen($password) < 8 || !preg_match("/[0-9]/", $password) || !preg_match("/[^a-zA-Z0-9]/", $password)) {
            echo "Password must be at least 8 characters long, contain a number, and a special character!";
            return;
        }
        
        // Check if username already exists
        $check_username = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($check_username);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "Username already exists!";
            return;
        }
        
        // Check if email already exists
        $check_email = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_email);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "Email already exists!";
            return;
        }
        
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user into database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        
        if ($stmt->execute()) {
            echo "Registration successful! You can now login.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Call the registration function
try {
    registerUser();
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "
";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <style>
        .container {
            width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f3f3f3;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .input-group {
            margin-bottom: 15px;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"] {
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Registration Form</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="input-group">
                <input type="text" name="username" placeholder="Enter your username" required pattern="[a-zA-Z0-9 ]+">
            </div>
            <div class="input-group">
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Enter your password" required pattern="[a-zA-Z0-9!@#$%^&*()]{8,}">
            </div>
            <div class="input-group">
                <input type="password" name="confirm_password" placeholder="Confirm password" required pattern="[a-zA-Z0-9!@#$%^&*()]{8,}">
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>


<?php
// Function to handle user registration
function registerUser($username, $email, $password, $confirm_password) {
    // Database connection parameters
    $host = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $dbname = 'mydatabase';

    try {
        // Create a new PDO instance
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Validate inputs
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            throw new Exception("All fields are required!");
        }

        // Check if password matches confirm password
        if ($password !== $confirm_password) {
            throw new Exception("Passwords do not match!");
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format!");
        }

        // Sanitize inputs
        $username = htmlspecialchars(strip_tags(trim($username)));
        $email = htmlspecialchars(strip_tags(trim($email)));

        // Password requirements: at least 8 characters with one uppercase and one special character
        if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*()_+}{":?><;\/?~\-|=]{8,}$/', $password)) {
            throw new Exception("Password must be at least 8 characters and contain one uppercase letter and a special character!");
        }

        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("Username already exists!");
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("Email already registered!");
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);

        // Close connection
        $conn = null;

        return true; // Registration successful

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (registerUser($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])) {
            echo "Registration successful!";
            // Redirect to login page
            header("Location: login.php");
            exit();
        }
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'registration_db';

// Connect to database
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    global $conn;

    // Initialize variables
    $errors = [];
    $full_name = '';
    $email = '';
    $password = '';

    // Check if form is submitted
    if (isset($_POST['submit'])) {
        // Get user input
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate inputs
        if (empty($full_name)) {
            $errors[] = "Full name is required";
        } elseif (!preg_match("/^[a-zA-Z ]+$/", $full_name)) {
            $errors[] = "Full name can only contain letters and spaces";
        }

        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        } else {
            // Check if email already exists in database
            $check_email_query = "SELECT id FROM users WHERE email=?";
            $stmt = $conn->prepare($check_email_query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $errors[] = "Email already exists";
            }
        }

        if (empty($password)) {
            $errors[] = "Password is required";
        } elseif (!preg_match("/^(?=.*\d)(?=.*[a-zA-Z]).{8,}$/", $password)) {
            $errors[] = "Password must be at least 8 characters and contain letters and numbers";
        }

        // If no errors, proceed to registration
        if (empty($errors)) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

            // Insert into database
            $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $full_name, $email, $hashed_password);

            if ($stmt->execute()) {
                // Registration successful
                header("Location: registration_success.php?message=Registration+successful");
                exit();
            } else {
                $errors[] = "Error registering user. Please try again.";
            }
        }
    }

    // Close statement and connection
    if (isset($stmt)) {
        $stmt->close();
    }
}

// Call the function when form is submitted
if (isset($_POST['submit'])) {
    registerUser();
}
?>


<?php
$message = isset($_GET['message']) ? $_GET['message'] : '';
if ($message) {
    echo "<h1>$message</h1>";
}
?>


<?php
// Include the configuration file
include("config.php");

// Function to handle user registration
function registerUser() {
    // Check if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Initialize error array
        $errors = array();

        // Validate username
        if (empty($username)) {
            $errors[] = "Username is required";
        } elseif (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
            $errors[] = "Username contains invalid characters. Only letters, numbers and underscores are allowed.";
        }

        // Validate email
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        // Validate password
        if (empty($password)) {
            $errors[] = "Password is required";
        } elseif ($password !== $confirm_password) {
            $errors[] = "Passwords do not match";
        } elseif (!preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&*()_+=]).*$/", $password)) {
            $errors[] = "Password must be at least 8 characters long and contain at least one letter, one number, and one special character";
        }

        // Check if username or email already exists
        $stmt = $GLOBALS['conn']->prepare("SELECT id FROM users WHERE username=? OR email=?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['username'] == $username) {
                    $errors[] = "Username already exists";
                }
                if ($row['email'] == $email) {
                    $errors[] = "Email already exists";
                }
            }
        }

        // If there are no errors, proceed to register the user
        if (empty($errors)) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert into database
            $stmt = $GLOBALS['conn']->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            if ($stmt->execute()) {
                // Registration successful
                header("Location: welcome.php?success=Registration completed successfully");
                exit();
            } else {
                die("An error occurred while registering. Please try again later.");
            }
        } else {
            // Display errors
            foreach ($errors as $error) {
                echo "<p class='error'>$error</p>";
            }
        }

        $stmt->close();
    }
}

// Call the registration function
registerUser();

// Close database connection
$conn->close();
?>


<?php
// Database connection constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mydatabase');

// Connect to the database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function for user registration
function registerUser() {
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Get form values
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate input data
        if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password)) {
            echo "All fields are required!";
            return;
        }

        // Check for valid email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format!";
            return;
        }

        // Check username length and characters
        if (strlen($username) < 3 || preg_match('/[^a-zA-Z0-9_]/', $username)) {
            echo "Username must be at least 3 characters long and contain only letters, numbers, or underscores!";
            return;
        }

        // Password validation
        if (strlen($password) < 8 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[^a-zA-Z0-9]/', $password)) {
            echo "Password must be at least 8 characters long and contain at least one uppercase letter, one number, and one special character!";
            return;
        }

        // Check if user already exists
        $checkQuery = "SELECT * FROM users WHERE email = ? OR username = ?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, "ss", $email, $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "Email or username already exists!";
            return;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $insertQuery = "INSERT INTO users (first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sssss", $firstName, $lastName, $email, $username, $hashedPassword);

        if (mysqli_stmt_execute($stmt)) {
            echo "Registration successful!";
            // Redirect to login page or dashboard
            header('Location: login.php');
            exit();
        } else {
            echo "Error registering user: " . mysqli_error($conn);
        }
    }
}

// Call the registration function
registerUser();

// Close database connection
mysqli_close($conn);
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'my_database';

// Connect to database
$conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function registerUser() {
    global $conn;

    // Check if form is submitted
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $errors = array();
        
        // Validate username
        if (empty($username)) {
            $errors[] = "Username is required";
        } else {
            // Check for invalid characters
            if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
                $errors[] = "Username can only contain letters and numbers";
            }
        }
        
        // Validate email
        if (empty($email)) {
            $errors[] = "Email is required";
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format";
            } else {
                // Check if email already exists in database
                $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
                $stmt = mysqli_prepare($conn, $checkEmailQuery);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $errors[] = "Email already exists";
                }
            }
        }
        
        // Validate password
        if (empty($password)) {
            $errors[] = "Password is required";
        } else {
            if (strlen($password) < 8) {
                $errors[] = "Password must be at least 8 characters long";
            } else {
                // Check for at least one letter and one number
                if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
                    $errors[] = "Password can only contain letters and numbers";
                }
            }
        }
        
        if (empty($errors)) {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user into database
            $insertQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);
            
            if (mysqli_stmt_execute($stmt)) {
                echo "Registration successful!";
                // Redirect to login page
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "Error registering user. Please try again.";
            }
        } else {
            foreach ($errors as $error) {
                echo "<div class='error'>" . $error . "</div>";
            }
        }
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Connect to database
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to register user
function registerUser($conn, $username, $email, $password) {
    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Username already exists!";
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Email already exists!";
    }

    // Validate username, email, and password
    if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
        return "Username contains invalid characters!";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format!";
    }

    if (strlen($password) < 8 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/\d/", $password)) {
        return "Password must be at least 8 characters and contain letters and numbers!";
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    
    if ($stmt->execute()) {
        // Start session and redirect to dashboard
        session_start();
        $_SESSION['registered'] = true;
        header("Location: welcome.php");
        exit();
    } else {
        return "Registration failed! Please try again.";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = registerUser($conn, $username, $email, $password);

    if (is_string($result)) {
        echo "<div class='error'>" . $result . "</div>";
    }
}

// Close database connection
$conn->close();
?>


<?php
// Configuration file (config.php)
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


<?php
session_start();

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = $_POST['full_name'];

    // Initialize error array
    $errors = [];

    // Validate input fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required";
    }

    // Check if username already exists
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Username already exists";
        }
    } catch(PDOException $e) {
        die("Error checking username: " . $e->getMessage());
    }

    // Check if email already exists
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Email already registered";
        }
    } catch(PDOException $e) {
        die("Error checking email: " . $e->getMessage());
    }

    // Validate password
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    if ($password != $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // If no errors
    if (empty($errors)) {
        // Sanitize inputs
        $username = htmlspecialchars(trim($username));
        $email = htmlspecialchars(trim($email));
        $full_name = htmlspecialchars(trim($full_name));

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password, $full_name]);

            // Redirect to confirmation page
            header("Location: registration_success.php");
            exit();
        } catch(PDOException $e) {
            die("Registration error: " . $e->getMessage());
        }
    }

    // If errors exist, display them
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>


<?php
// Database connection
$host = "localhost";
$username = "root"; // Change to your database username
$password = "";     // Change to your database password
$dbname = "registration_db"; // Change to your database name

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate empty fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('All fields are required!');</script>";
    } else {
        // Check password match
        if ($password != $confirm_password) {
            echo "<script>alert('Passwords do not match!');</script>";
        } else {
            // Email validation
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<script>alert('Please enter a valid email address!');</script>";
            } else {
                // Check if username already exists
                $checkUsername = "SELECT * FROM users WHERE username='$username'";
                $result = mysqli_query($conn, $checkUsername);
                
                if (mysqli_num_rows($result) > 0) {
                    echo "<script>alert('Username already exists!');</script>";
                } else {
                    // Check if email already exists
                    $checkEmail = "SELECT * FROM users WHERE email='$email'";
                    $result = mysqli_query($conn, $checkEmail);

                    if (mysqli_num_rows($result) > 0) {
                        echo "<script>alert('Email already exists!');</script>";
                    } else {
                        // Hash password before storing
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        
                        // Insert into database
                        $sql = "INSERT INTO users (username, email, password)
                                VALUES ('$username', '$email', '$hashed_password')";
                        
                        if (mysqli_query($conn, $sql)) {
                            echo "<script>alert('Registration successful!');</script>";
                            header("Refresh:0");
                        } else {
                            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                        }
                    }
                }
            }
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-top: -10px;
        }
    </style>
</head>
<body>
    <h2>Registration Page</h2>
    
    <!-- Registration Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username"><br>
        </div>

        <div class="form-group">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email"><br>
        </div>

        <div class="form-group">
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password"><br>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password"><br>
        </div>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="#">Login here</a></p>
</body>
</html>


<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'test';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
function registerUser($username, $email, $password, $conn) {
    // Check if username is already taken
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        throw new Exception("Username already exists.");
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format.");
    }
    
    // Check if email is already registered
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        throw new Exception("Email already exists.");
    }
    
    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password_hash);
    
    if (!$stmt->execute()) {
        throw new Exception("Registration failed: " . $stmt->error);
    }
    
    return true;
}
?>


<?php
require_once 'database_connection.php';
require_once 'functions.php';

try {
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        registerUser($username, $email, $password, $conn);
        echo "Registration successful!";
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>


<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'user_registration');

// Connect to database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to sanitize input data
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to validate and register user
function registerUser() {
    global $conn;

    // Get form data
    $username = isset($_POST['username']) ? sanitize($_POST['username']) : '';
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Initialize errors array
    $errors = [];

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "Username can only contain letters, numbers, and underscores";
    }

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    } else {
        // Check if email already exists
        $check_email_query = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_email_query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Email already exists";
        }
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        $errors[] = "Password must be at least 8 characters and contain at least one letter and one number";
    }

    // If there are errors
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hashed_password);

        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='alert alert-success'>Registration successful! Please login to continue.</div>";
            // Redirect to login page after 3 seconds
            header("Refresh: 3; url=login.php");
        } else {
            echo "Error registering user: " . mysqli_error($conn);
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Call the registration function if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    registerUser();
}
?>


<?php
// Database configuration
$host = "localhost";
$username_db = "root";
$password_db = "";
$db_name = "test";

// Connect to database
$conn = mysqli_connect($host, $username_db, $password_db, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get user inputs
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "All fields are required!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
    } else if ($password != $confirm_password) {
        echo "Passwords do not match!";
    } else {

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if username already exists
        $check_username_query = "SELECT username FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $check_username_query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            echo "Username already exists!";
        } else {

            // Check if email already exists
            $check_email_query = "SELECT email FROM users WHERE email = ?";
            $stmt = mysqli_prepare($conn, $check_email_query);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                echo "Email already exists!";
            } else {

                // Insert new user into database
                $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $insert_query);
                mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

                if (mysqli_stmt_execute($stmt)) {
                    echo "Registration successful! You can now login.";
                } else {
                    echo "Error registering user: " . mysqli_error($conn);
                }
            }
        }

        // Close statements
        mysqli_stmt_close($stmt);
    }

    // Close database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
</head>
<body>
    <h2>Create Account</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br>

        <input type="submit" value="Register">
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>


<?php
// Database connection details
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

try {
    // Connect to database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $pass = $_POST['password'];

        // Validate inputs
        $errors = array();
        
        if (!validateFullName($fullname)) {
            $errors[] = "Please enter a valid full name!";
        }
        
        if (!validateEmail($email)) {
            $errors[] = "Please enter a valid email address!";
        }
        
        if (!validatePassword($pass)) {
            $errors[] = "Please enter a valid password! (Minimum 8 characters, at least one letter and one number)";
        }

        // Check if any errors
        if (count($errors) == 0) {
            // Hash the password
            $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
            
            // Check if email already exists
            $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                echo "Email already exists!";
            } else {
                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (:fullname, :email, :password)");
                $stmt->bindParam(':fullname', $fullname);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hash_pass);
                
                if ($stmt->execute()) {
                    echo "Registration successful!";
                } else {
                    echo "Error registering user!";
                }
            }
        } else {
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        }
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Validation functions

function validateFullName($name) {
    // Name should be at least 2 characters and contain only letters and spaces
    if (strlen($name) < 2 || strlen($name) > 50) {
        return false;
    }
    
    if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        return false;
    }
    
    return true;
}

function validateEmail($email) {
    // Email validation using regex
    if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@((\.[a-zA-Z0-9_\-\.]+)+)$/", $email)) {
        return false;
    }
    
    return true;
}

function validatePassword($pass) {
    // Password validation: at least 8 characters, contains letters and numbers
    if (strlen($pass) < 8) {
        return false;
    }
    
    if (!preg_match("#^[a-zA-Z0-9]*$#", $pass)) {
        return false;
    }
    
    if (!preg_match('#[a-zA-Z]#', $pass) || !preg_match('#[0-9]#', $pass)) {
        return false;
    }
    
    return true;
}
?>


<?php
// Database configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$database = 'my_database';

// Connect to the database
$conn = mysqli_connect($host, $username_db, $password_db, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to register a new user
function registerUser() {
    global $conn;

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate inputs
        $errors = [];
        $success = false;

        if (empty($username) || !preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
            $errors[] = "Username is invalid!";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email is invalid!";
        }

        if (empty($password)) {
            $errors[] = "Password cannot be empty!";
        } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
            $errors[] = "Password is invalid!";
        }

        if ($password != $confirm_password) {
            $errors[] = "Passwords do not match!";
        }

        // If there are no errors
        if (empty($errors)) {
            // Check if username or email already exists
            $check_username = "SELECT * FROM users WHERE username = '" . mysqli_real_escape_string($conn, $username) . "'";
            $result_username = mysqli_query($conn, $check_username);
            
            $check_email = "SELECT * FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email) . "'";
            $result_email = mysqli_query($conn, $check_email);

            if (mysqli_num_rows($result_username) > 0 || mysqli_num_rows($result_email) > 0) {
                $errors[] = "Username or email already exists!";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert into database
                $sql = "INSERT INTO users (username, email, password)
                        VALUES ('" . mysqli_real_escape_string($conn, $username) . "', '" . mysqli_real_escape_string($conn, $email) . "', '" . $hashed_password . "')";
                
                if (mysqli_query($conn, $sql)) {
                    $success = true;
                    // Redirect to login page or dashboard
                    header("Location: login.php?success=Registration successful");
                    exit();
                } else {
                    $errors[] = "Error registering user: " . mysqli_error($conn);
                }
            }
        }

        if (!empty($errors)) {
            // Display error messages
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }
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
    // Create a connection to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function registerUser($formData, $conn) {
    // Extract form data
    $data = [
        'username' => trim($formData['username']),
        'email' => trim($formData['email']),
        'password' => trim($formData['password']),
        'first_name' => trim($formData['first_name']),
        'last_name' => trim($formData['last_name']),
        'birthdate' => $formData['birthdate']
    ];

    // Validate input
    if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
        return ['status' => 400, 'message' => 'Please fill in all required fields'];
    }

    if (strlen($data['username']) < 3) {
        return ['status' => 400, 'message' => 'Username must be at least 3 characters long'];
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return ['status' => 400, 'message' => 'Please enter a valid email address'];
    }

    if (strlen($data['password']) < 6) {
        return ['status' => 400, 'message' => 'Password must be at least 6 characters long'];
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $data['username']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return ['status' => 400, 'message' => 'Username or email already exists'];
    }

    // Prepare the data for insertion
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    
    try {
        $stmt = $conn->prepare("
            INSERT INTO users (
                username,
                email,
                password,
                first_name,
                last_name,
                birthdate
            ) VALUES (
                :username,
                :email,
                :password,
                :first_name,
                :last_name,
                :birthdate
            )
        ");
        
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':birthdate', $data['birthdate']);
        
        if ($stmt->execute()) {
            // Return success response
            return [
                'status' => 200,
                'message' => 'Registration successful!',
                'user_data' => [
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'birthdate' => $data['birthdate']
                ]
            ];
        } else {
            return ['status' => 500, 'message' => 'Registration failed. Please try again.'];
        }
    } catch(PDOException $e) {
        return ['status' => 500, 'message' => 'An error occurred: ' . $e->getMessage()];
    }
}

// Example usage:
$formData = [
    'username' => 'example_user',
    'email' => 'user@example.com',
    'password' => 'secure_password123',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'birthdate' => '1990-01-01'
];

$result = registerUser($formData, $conn);
echo json_encode($result);

// Don't forget to close the database connection
$conn = null;
?>


<?php
// Include database connection file
require_once("db_connection.php");

function registerUser() {
    // Check if form submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $errors = array();
        
        // Sanitize input data
        $firstname = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST[' firstname']));
        $lastname = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST[' lastname']));
        $email = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST[' email']));
        $password = $_POST[' password'];
        $confirmpassword = $_POST[' confirm_password'];
        
        // Validate input data
        if (empty($firstname)) {
            $errors[] = "First name is required";
        }
        
        if (empty($lastname)) {
            $errors[] = "Last name is required";
        }
        
        if (empty($email)) {
            $errors[] = "Email is required";
        } else {
            // Check if email already exists
            $check_email_query = "SELECT id FROM users WHERE email = '$email'";
            $result = mysqli_query($GLOBALS['conn'], $check_email_query);
            if (mysqli_num_rows($result) > 0) {
                $errors[] = "Email already exists";
            }
        }
        
        if (empty($password)) {
            $errors[] = "Password is required";
        } else {
            // Validate password length
            if (strlen($password) < 6) {
                $errors[] = "Password must be at least 6 characters";
            }
            
            // Check if password matches confirm password
            if ($password != $confirmpassword) {
                $errors[] = "Passwords do not match";
            }
        }
        
        if (empty($errors)) {
            try {
                // Hash the password before storing in database
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert user into database
                $insert_query = "INSERT INTO users (first_name, last_name, email, password) 
                               VALUES ('$firstname', '$lastname', '$email', '$hashed_password')";
                               
                if (mysqli_query($GLOBALS['conn'], $insert_query)) {
                    echo "Registration successful! You can now login.";
                } else {
                    die("Error: " . mysqli_error($GLOBALS['conn']));
                }
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
            }
        } else {
            foreach ($errors as $error) {
                echo "<p class='error'>$error</p>";
            }
        }
    }
}

// Call the function
registerUser();

// Close database connection
mysqli_close($GLOBALS['conn']);
?>


<?php
session_start();
require_once 'db.php'; // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = trim($_POST['full_name']);
    $date_of_birth = $_POST['date_of_birth'];
    $phone_number = trim($_POST['phone_number']);

    // Input validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        die("All fields are required!");
    }

    if (strlen($password) < 6) {
        die("Password must be at least 6 characters long!");
    }

    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format!");
    }

    // Phone number validation (simple check)
    if (!preg_match('/^\+?[0-9]{10,}$/', $phone_number)) {
        die("Invalid phone number format!");
    }

    // Check if username already exists
    try {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            die("Username already exists!");
        }
    } catch (PDOException $e) {
        die("Error checking username: " . $e->getMessage());
    }

    // Check if email already exists
    try {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            die("Email already registered!");
        }
    } catch (PDOException $e) {
        die("Error checking email: " . $e->getMessage());
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    try {
        $stmt = $pdo->prepare('INSERT INTO users SET 
            username = ?,
            email = ?,
            password = ?,
            full_name = ?,
            date_of_birth = ?,
            phone_number = ?');
        
        $result = $stmt->execute([
            $username,
            $email,
            $hashed_password,
            $full_name,
            $date_of_birth,
            $phone_number
        ]);

        if ($result) {
            header('Location: welcome.php');
            exit();
        }
    } catch (PDOException $e) {
        die("Registration error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
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
    <h2>Registration Form</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required pattern="[a-zA-Z0-9_]{3,15}">
        </div>

        <div class="form-group">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <div class="form-group">
            <label for="full_name">Full Name:</label><br>
            <input type="text" id="full_name" name="full_name" required>
        </div>

        <div class="form-group">
            <label for="date_of_birth">Date of Birth:</label><br>
            <input type="date" id="date_of_birth" name="date_of_birth" required>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number:</label><br>
            <input type="tel" id="phone_number" name="phone_number" pattern="[0-9]{10}" required>
        </div>

        <button type="submit">Register</button>
    </form>
</body>
</html>


<?php
$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>


<?php
// Database connection
$host = 'localhost';
$username = 'root'; // Change according to your database username
$password = '';      // Change according to your database password
$db_name = 'registration_db'; // Change according to your database name

$conn = mysqli_connect($host, $username, $password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Registration function
function registerUser() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate input fields
        if (empty($username) || empty($email) || empty($password)) {
            echo "All fields are required!";
            return;
        }

        // Sanitize input data
        $username = mysqli_real_escape_string($conn, htmlspecialchars($username));
        $email = mysqli_real_escape_string($conn, htmlspecialchars($email));

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if username or email already exists
        $checkQuery = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $result = mysqli_query($conn, $checkQuery);
        
        if (mysqli_num_rows($result) > 0) {
            echo "Username or email already exists!";
            return;
        }

        // Insert user into database
        $insertQuery = "INSERT INTO users (username, email, password) 
                        VALUES ('$username', '$email', '$hashed_password')";
        
        if (mysqli_query($conn, $insertQuery)) {
            echo "Registration successful! You can now login.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Create users table if it doesn't exist
createUsersTable();

function createUsersTable() {
    global $conn;
    
    $tableQuery = "CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(50) NOT NULL UNIQUE,
                    email VARCHAR(100) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL
                  )";
    
    mysqli_query($conn, $tableQuery);
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <!-- Add your CSS styles here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"] {
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
        
        .message {
            margin-top: 10px;
            padding: 8px;
            font-weight: bold;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registration Form</h1>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email address">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password">
            </div>

            <button type="submit">Register</button>
        </form>
        
        <?php
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            registerUser();
        }
        ?>
    </div>
</body>
</html>


<?php
// registration.php

// Database connection configuration
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'user_registration';

// Create database connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Registration function
function registerUser() {
    global $conn;

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get input values
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate name
        if (empty($name)) {
            echo "Name is required";
            return;
        }
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            echo "Invalid name. Only letters and spaces allowed.";
            return;
        }

        // Validate email
        if (empty($email)) {
            echo "Email is required";
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format";
            return;
        }

        // Check if email already exists
        $check_email = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_email);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Email already exists";
            return;
        }

        // Validate password
        if (empty($password)) {
            echo "Password is required";
            return;
        }
        if ($password !== $confirm_password) {
            echo "Passwords do not match";
            return;
        }

        // Password requirements: at least 8 characters, containing letters, numbers, and special characters
        if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/", $password)) {
            echo "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.";
            return;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Registration successful! You can now log in.";
            // Optionally, redirect to login page here
            header("Location: login.php");
            exit();
        } else {
            echo "Error registering user: " . $conn->error;
        }

        $stmt->close();
    }
}

// Close database connection
$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <style>
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .error {
            color: red;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registration Form</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" required><br>
            </div>

            <div class="form-group">
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required><br>
            </div>

            <div class="form-group">
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label><br>
                <input type="password" id="confirm_password" name="confirm_password" required><br>
            </div>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>


<?php
// Include necessary files
require_once 'config.php'; // Database configuration file

function registerUser() {
    $errors = array();
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $email = trim($_POST['email']);
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $dob = $_POST['dob'];
        
        // Validate form data
        if (empty($username) || empty($password) || empty($email) || empty($first_name) || empty($last_name) || empty($dob)) {
            $errors[] = "All fields are required!";
        }
        
        // Check username length
        if (strlen($username) < 3) {
            $errors[] = "Username must be at least 3 characters long!";
        }
        
        // Check password strength
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long!";
        }
        
        // Email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address!";
        }
        
        // Check if username already exists
        $checkUsernameQuery = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $checkUsernameQuery);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Username already exists!";
        }
        
        // Check if email already exists
        $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $checkEmailQuery);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Email already exists!";
        }
        
        // If no errors
        if (empty($errors)) {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Prepare the SQL statement
            $sql = "INSERT INTO users 
                    SET username = ?, 
                        password = ?, 
                        email = ?,
                        first_name = ?,
                        last_name = ?,
                        dob = ?";
                        
            $stmt = mysqli_prepare($conn, $sql);
            
            // Bind parameters
            $dobFormatted = date('Y-m-d', strtotime($dob)); // Format date for database
            
            mysqli_stmt_bind_param($stmt, "ssssss", 
                                  $username, 
                                  $hashedPassword,
                                  $email,
                                  $first_name,
                                  $last_name,
                                  $dobFormatted);
            
            // Execute the query
            if (mysqli_stmt_execute($stmt)) {
                // Registration successful
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "Registration failed. Please try again!";
            }
        }
    }
    
    return $errors;
}

// Call the function
$errors = registerUser();

// Close database connection
mysqli_close($conn);
?>


<?php
// Database configuration
$host = "localhost";
$username_db = "root";
$password_db = "";
$db_name = "registration";

// Connect to database
$conn = new mysqli($host, $username_db, $password_db, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function for user registration
function registerUser() {
    global $conn;
    
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate form data
    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill in all required fields";
        return false;
    }
    
    if ($password != $confirm_password) {
        echo "Passwords do not match";
        return false;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        return false;
    }
    
    // Check if username already exists
    $check_username = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($check_username);
    
    if ($result->num_rows > 0) {
        echo "Username already exists. Please choose a different one.";
        return false;
    }
    
    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($check_email);
    
    if ($result->num_rows > 0) {
        echo "Email already registered. Please use a different email.";
        return false;
    }
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT, array('cost' => 12));
    
    // Insert user into database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    
    if ($stmt->execute()) {
        echo "Registration successful! You can now login.";
        header("Location: welcome.php");
        exit();
    } else {
        echo "Error registering user: " . $conn->error;
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
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .registration-container {
            max-width: 400px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input[type="text"],
        input[type="email"],
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
        
        .hint {
            color: #666;
            font-size: 0.8em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h2>Register New User</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <div class="hint">Please enter a valid email address</div>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <div class="hint">Minimum 8 characters, include letters and numbers</div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>

<?php
// Call the registration function when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    registerUser();
}
?>


<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Error messages array
    $errors = array();
    
    // Validate username
    if (empty($username)) {
        $errors[] = 'Username is required';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = 'Username can only contain letters, numbers, and underscores';
    }
    
    // Validate email
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address';
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long';
    } elseif (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{6,}$/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter, one lowercase letter, and one number';
    }
    
    // Validate confirm password
    if (empty($confirm_password) || $password != $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    // If there are no errors
    if (empty($errors)) {
        // Check if username or email already exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Username or email already exists
            $errors[] = 'Username or email is already taken';
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user into database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $username, $email, $hashed_password);
            $result = $stmt->execute();
            
            if ($result) {
                // Registration successful
                $_SESSION['username'] = $username;
                header("Location: welcome.php");
                exit();
            } else {
                $errors[] = 'Registration failed. Please try again later.';
            }
        }
        
        $stmt->close();
    }
    
    $conn->close();
}
?>


<?php
$servername = "localhost";
$username_db = "username";
$password_db = "password";
$dbname = "database_name";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
// Include configuration file
include('config.php');

function registerUser($username, $email, $password) {
    // Check if username is valid (only letters and numbers, 3-20 characters)
    if (!preg_match('/^[a-zA-Z0-9]{3,20}$/', $username)) {
        return "Username must be between 3 and 20 characters and contain only letters and numbers!";
    }

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Please enter a valid email address!";
    }

    // Check if password is valid (at least 6 characters)
    if (strlen($password) < 6) {
        return "Password must be at least 6 characters long!";
    }

    // Check if username or email already exists
    $checkQuery = "SELECT id FROM users WHERE username = '$username' OR email = '$email'";
    
    if ($result = mysqli_query($GLOBALS['conn'], $checkQuery)) {
        if (mysqli_num_rows($result) > 0) {
            return "Username or email already exists!";
        }
    }

    // Sanitize input data
    $username = mysqli_real_escape_string($GLOBALS['conn'], $username);
    $email = mysqli_real_escape_string($GLOBALS['conn'], $email);
    $password = password_hash($password, PASSWORD_BCRYPT); // Hash the password

    // Insert user into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    
    if (mysqli_query($GLOBALS['conn'], $query)) {
        return true; // Registration successful
    } else {
        return "Error registering user: " . mysqli_error($GLOBALS['conn']);
    }
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Call registration function
    $result = registerUser($username, $email, $password);

    if ($result === true) {
        // Registration successful - start session and redirect
        session_start();
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
    } else {
        // Display error message
        echo $result;
    }
}
?>


<?php
// Connect to the database
$host = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function registerUser() {
    // Check if form is submitted
    if (isset($_POST['submit'])) {
        // Get form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate input
        if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            echo "All fields are required!";
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format!";
            return;
        }

        if ($password !== $confirm_password) {
            echo "Passwords do not match!";
            return;
        }

        // Sanitize inputs
        $name = htmlspecialchars(strip_tags($name));
        $email = htmlspecialchars(strip_tags($email));

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists in the database
        global $conn;
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "Email already exists!";
            return;
        }

        // Insert user into the database
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
        
        if (mysqli_query($conn, $sql)) {
            // Set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = mysqli_insert_id($conn);
            $_SESSION['email'] = $email;

            // Redirect to login page after successful registration
            header("Location: login.php?success=Your account has been successfully created!");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Database connection configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form values
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Form validation
        $errors = array();

        if (empty($username)) {
            $errors[] = "Username is required";
        }

        if (empty($email)) {
            $errors[] = "Email is required";
        }

        if (empty($password)) {
            $errors[] = "Password is required";
        }

        if ($password != $confirm_password) {
            $errors[] = "Passwords do not match";
        }

        // Check username length
        if (strlen($username) < 4) {
            $errors[] = "Username must be at least 4 characters long";
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        // Check password strength
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }

        // If there are no errors
        if (empty($errors)) {
            // Check if username or email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->rowCount() > 0) {
                die("Username or email already exists");
            }

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Generate verification token
            $verification_token = bin2hex(random_bytes(16));

            // Insert user into database
            $stmt = $conn->prepare("
                INSERT INTO users (
                    username,
                    email,
                    password,
                    verification_token,
                    created_at
                ) VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$username, $email, $hashed_password, $verification_token]);

            // Get user ID
            $user_id = $conn->lastInsertId();

            // Send verification email
            $subject = "Confirm your registration";
            $body = "Please click the following link to verify your account: http://your-site.com/verify.php?token=$verification_token";

            $headers = "From: no-reply@your-site.com\r
";
            $headers .= "Content-Type: text/html; charset=UTF-8\r
";

            if (mail($email, $subject, $body, $headers)) {
                echo "Registration successful! Please check your email to verify your account.";
            } else {
                die("Error sending verification email");
            }

        } else {
            // Output errors
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        }

    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'user_database';
$username = 'root';
$password = '';

try {
    // Create a database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Function to register a user
    function registerUser($conn) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize input data
            $full_name = htmlspecialchars(trim($_POST['full_name']));
            $email = htmlspecialchars(trim($_POST['email']));
            $username = htmlspecialchars(trim($_POST['username']));
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // Validate input data
            $errors = array();

            if (empty($full_name)) {
                $errors[] = "Full name is required";
            }

            if (empty($email)) {
                $errors[] = "Email is required";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format";
            }

            if (empty($username)) {
                $errors[] = "Username is required";
            }

            if (empty($password)) {
                $errors[] = "Password is required";
            } elseif (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/', $password)) {
                $errors[] = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and a special character";
            }

            if ($password != $confirm_password) {
                $errors[] = "Passwords do not match";
            }

            // If there are errors
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<div class='error'>$error</div>";
                }
                return;
            }

            // Check if user already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo "<div class='error'>User with this email or username already exists</div>";
                return;
            }

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into database
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, username, password) VALUES (:full_name, :email, :username, :password)");
            $stmt->bindParam(':full_name', $full_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);

            try {
                $stmt->execute();
                // Registration successful
                echo "<div class='success'>Registration successful! Please login.</div>";
                header("Location: login.php");
                exit();
            } catch (PDOException $e) {
                echo "<div class='error'>Error registering user: " . $e->getMessage() . "</div>";
            }
        }
    }

    // Call the registration function
    registerUser($conn);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
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
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registration Form</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="text" placeholder="Full Name" name="full_name" required><br>
            <input type="email" placeholder="Email" name="email" required><br>
            <input type="text" placeholder="Username" name="username" required><br>
            <input type="password" placeholder="Password" name="password" required><br>
            <input type="password" placeholder="Confirm Password" name="confirm_password" required><br>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'user_registration';

// Connect to database
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to validate user registration
function registerUser($username, $email, $password, $conn) {
    // Check if username already exists
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Username already exists!";
    }

    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Email already exists!";
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        return "Registration successful!";
    } else {
        return "Error registering user!";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get input values
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password confirmation
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Call registration function
    $result = registerUser($username, $email, $password, $conn);
    echo $result;
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        input {
            width: 300px;
            padding: 10px;
            border: 1px solid #ddd;
            margin-top: 5px;
            margin-bottom: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 300px;
        }
        button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <h2>Registration Form</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <button type="submit">Register</button>
    </form>
</body>
</html>


<?php
// Database connection details
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'mydatabase';

// Create database connection using PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName", $dbUsername, $dbPassword);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function registerUser($username, $email, $password, $pdo) {
    // Check if username already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    
    if ($stmt->rowCount() > 0) {
        return "Username already exists!";
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        return "Email already exists!";
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword]);

        return "Registration successful!";
    } catch(PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Validate username
    if (!ctype_alnum($username) || strlen($username) < 3 || strlen($username) > 20) {
        die("Username must be alphanumeric and between 3-20 characters!");
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format!");
    }

    // Validate password
    if (strlen($password) < 8 || !preg_match('/[a-zA-Z]/', $password) || !preg_match('/\d/', $password)) {
        die("Password must be at least 8 characters long and contain letters and numbers!");
    }

    // Register user
    $result = registerUser($username, $email, $password, $pdo);
    echo $result;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Register Here</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">Register</button>
    </form>
</body>
</html>


<?php
// Start the session
session_start();

// Include database configuration
include("config.php");

function registerUser() {
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $error = "";

        // Validate input fields
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check if all required fields are filled
        if (empty($username) || empty($email) || empty($password)) {
            $error = "All fields must be filled out!";
            echo "<script>alert('$error'); window.location.href='register.php';</script>";
            exit();
        }

        // Connect to database
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        // Check if connection was successful
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already exists!";
            echo "<script>alert('$error'); window.location.href='register.php';</script>";
            exit();
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already exists!";
            echo "<script>alert('$error'); window.location.href='register.php';</script>";
            exit();
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            // Registration successful
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            // Error registering user
            $error = "Error registering user!";
            echo "<script>alert('$error'); window.location.href='register.php';</script>";
            exit();
        }

        // Close database connection
        $conn->close();
    }
}

// Call the function
if (isset($_POST['submit'])) {
    registerUser();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
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
        <h2>Registration Form</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" name="submit">Register</button>
        </form>
    </div>
</body>
</html>


<?php
// Database connection configuration
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'user_registration';

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser() {
    global $conn;
    
    // Initialize variables
    $error = "";
    $username = "";
    $email = "";
    $password = "";

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input data
        function sanitizeInput($data) {
            return htmlspecialchars(trim(mysqli_real_escape_string($conn, $data)));
        }

        $username = sanitizeInput($_POST['username']);
        $email = sanitizeInput($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate required fields
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $error = "All fields are required!";
        } else {
            // Check if email is valid
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format!";
            }

            // Check password length
            if (strlen($password) < 8) {
                $error = "Password must be at least 8 characters!";
            }

            // Check if passwords match
            if ($password !== $confirm_password) {
                $error = "Passwords do not match!";
            }

            // Validate username and email are unique
            $check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row['username'] == $username) {
                        $error .= "Username already exists! ";
                    }
                    if ($row['email'] == $email) {
                        $error .= "Email already registered!";
                    }
                }
            }

            // If no errors, proceed with registration
            if (empty($error)) {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Prepare the SQL statement
                $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("sss", $username, $email, $hashed_password);

                if ($stmt->execute()) {
                    // Registration successful
                    echo "Registration successful! You can now login.";
                } else {
                    $error = "Error occurred while registering. Please try again later!";
                }
            }
        }
    }

    return $error;
}

// Call the registration function
$error_message = registerUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <!-- Add Content Security Policy header -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' https: 'unsafe-inline'; style-src 'self' https: 'unsafe-inline'; img-src 'self' https:;">
    <!-- Add X-Content-Type-Options header -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
</head>
<body>
    <?php if (!empty($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

    <!-- Add CAPTCHA for security -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            <label>Username:</label><br>
            <input type="text" name="username">
        </div>

        <div>
            <label>Email:</label><br>
            <input type="email" name="email">
        </div>

        <div>
            <label>Password:</label><br>
            <input type="password" name="password">
        </div>

        <div>
            <label>Confirm Password:</label><br>
            <input type="password" name="confirm_password">
        </div>

        <!-- Add CAPTCHA input -->
        <div>
            <img src="captcha.php" alt="CAPTCHA Image">
            <br>
            <label>Enter the code above:</label><br>
            <input type="text" name="captcha_code">
        </div>

        <button type="submit">Register</button>
    </form>

    <!-- Verify CAPTCHA -->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $entered_captcha = sanitizeInput($_POST['captcha_code']);
        $stored_captcha = $_SESSION['captcha'];
        
        if ($entered_captcha !== $stored_captcha) {
            $error_message = "Invalid CAPTCHA code!";
            // Handle error
        }
    }
    ?>
</body>
</html>


<?php
// Include necessary files
include('db.php');

// Function for user registration
function registerUser() {
    // Check if form is submitted
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate input fields
        if ($username == "" || $email == "" || $password == "" || $confirm_password == "") {
            echo "Please fill in all required fields";
            return;
        }

        // Check username format (only letters and numbers)
        if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
            echo "Username can only contain letters, numbers, and underscores.";
            return;
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Please enter a valid email address";
            return;
        }

        // Check password length and format (at least 8 characters, one uppercase letter, one number)
        $uppercase = preg_match('@[A-Z]@', $password);
        $number = preg_match('@[0-9]@', $password);

        if (!$uppercase || !$number || strlen($password) < 8) {
            echo "Password must be at least 8 characters long and contain at least one uppercase letter and one number.";
            return;
        }

        // Check password match
        if ($password != $confirm_password) {
            echo "Passwords do not match";
            return;
        }

        // Escape special characters
        $username = mysqli_real_escape_string($db, $username);
        $email = mysqli_real_escape_string($db, $email);
        $password = mysqli_real_escape_string($db, $password);

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT, array('cost' => 12));

        // Check if username or email already exists
        $check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
        $result = mysqli_query($db, $check_query);

        if (mysqli_num_rows($result) > 0) {
            echo "Username or email already exists";
            return;
        }

        // Insert new user into the database
        $insert_query = "INSERT INTO users (username, email, password)
                         VALUES ('$username', '$email', '$hashed_password')";

        if (mysqli_query($db, $insert_query)) {
            // Registration successful
            $_SESSION['success'] = "You have successfully registered!";
            header("Location: login.php");
            exit();
        } else {
            echo "Error registering user. Please try again.";
        }
    }
}

// Handle registration process based on request method
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Show registration form
        include('registration_form.php');
        break;

    case 'POST':
        registerUser();
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
</head>
<body>
    <?php if (isset($_SESSION['error'])) { ?>
        <div class="error"><?php echo $_SESSION['error']; ?></div>
    <?php } ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br>

        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password" required><br>

        <input type="submit" name="register" value="Register">
    </form>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$database = 'user_registration';

// Create connection
$conn = new mysqli($host, $username_db, $password_db, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Register function
function registerUser($username, $email, $password, $conn) {
    // Sanitize inputs
    $username = sanitizeInput($username);
    $email = sanitizeInput($email);
    
    // Check if username or email already exists
    $check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Username or email already exists
        return ["status" => "error", "message" => "Username or email already exists."];
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user into database
    $register_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($register_query);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    
    if ($stmt->execute()) {
        return ["status" => "success", "message" => "Registration successful!"];
    } else {
        return ["status" => "error", "message" => "Error registering user."];
    }
}

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Validate inputs
        if (empty($username) || empty($email) || empty($password)) {
            throw new Exception("All fields are required.");
        }
        
        if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            throw new Exception("Username must contain only letters and numbers.");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
        
        if (strlen($password) < 8 || !preg_match("/^[a-zA-Z0-9]*$/", $password)) {
            throw new Exception("Password must be at least 8 characters and contain only letters and numbers.");
        }
        
        // Register user
        $result = registerUser($username, $email, $password, $conn);
        
        if ($result["status"] == "success") {
            header("Location: login.php");
            exit();
        } else {
            throw new Exception($result["message"]);
        }
    } catch (Exception $e) {
        // Handle errors
        http_response_code(400);
        die(json_encode(["error" => $e->getMessage()]));
    }
}

// Close database connection
$conn->close();
?>

