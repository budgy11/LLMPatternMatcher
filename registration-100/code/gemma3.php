
<?php

// Database connection details (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate username (basic example - you should implement more robust validation)
  if (empty($username)) {
    $username_error = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $username_error = "Username must be at least 3 characters long.";
  }

  // Validate email
  if (empty($email)) {
    $email_error = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format.";
  }

  // Validate password (basic example - you should use stronger hashing)
  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // Check if there are any validation errors
  if ($username_error || $email_error || $password_error) {
    $error = $error . "<br>" . $username_error . "<br>" . $email_error . "<br>" . $password_error;
  } else {
    // Hash the password (VERY IMPORTANT - never store passwords in plain text!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
      $success = "Registration successful! You have been redirected.";
    } else {
      $error = "Error registering user. " . mysqli_error($conn);
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <?php if (isset($success)) { ?>
    <p style="color: green;"><?php echo $success; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">
    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Configuration (Replace with your database credentials)
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_username';
$dbPass = 'your_password';

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Validate Inputs (Crucial for security)
  $username = trim($username);  // Remove whitespace
  $password = trim($password);
  $email = trim($email);

  if (empty($username) || empty($password) || empty($email)) {
    return "Error: All fields are required.";
  }

  // Basic password validation (you should use a stronger hashing method in production)
  if (strlen($password) < 8) {
    return "Error: Password must be at least 8 characters long.";
  }


  // 2. Database Connection
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  if ($conn->connect_error) {
    return "Error: Connection failed: " . $conn->connect_error;
  }

  // 3. Prepare and Execute SQL Query
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashedPassword', '$email')";

  if ($conn->query($sql) === TRUE) {
    return "Registration successful! You have been logged in.";
  } else {
    return "Error: " . $sql . "<br>Error: " . $conn->error;
  }
}


// Example Usage (This is just for demonstration - you would use this from a form submission)
//  $username = $_POST['username'];
//  $password = $_POST['password'];
//  $email = $_POST['email'];
//
//  $registrationResult = registerUser($username, $password, $email);
//
//  echo $registrationResult;


?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Register">
  </form>


</body>
</html>


<?php

// Database configuration (Replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize and validate the input
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email address.";
  }

  // Password validation (Basic - Consider stronger validation in a real application)
  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  }

  // Check if username already exists
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($db_host, $sql);

  if (mysqli_num_rows($result) > 0) {
    $username_error = "Username already exists.";
  }


  // If validation passes, insert the user into the database
  if (empty($username_error) && empty($email_error) && empty($password_error)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($db_host, $sql)) {
      // Registration successful
      $registration_message = "Registration successful! Please log in.";
    } else {
      // Error inserting into database
      $registration_message = "Error: " . mysqli_error($db_host);
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <?php if (isset($registration_message)) {
    echo "<p>" . $registration_message . "</p>";
  } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (Important for security!)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9._-]+$/", $username)  // Username validation
  ) {
    $error_message = "Username must contain only letters, numbers, periods, underscores, and hyphens.";
  } elseif (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $error_message = "Invalid email address.";
  } elseif (
    strlen($password) < 8  // Minimum password length
  ) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (VERY IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
      // Registration successful
      $success_message = "Registration successful.  Please check your email to verify your account.";
    } else {
      // Registration failed
      echo "Error: " . $stmt->error;
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <?php if (isset($error_message)) {
    echo "<p style='color: red;'>Error: " . $error_message . "</p>";
  }  if (isset($success_message)) {
    echo "<p style='color: green;'>" . $success_message . "</p>";
  }
  ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the input
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Sanitize inputs (important for security)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  // Validate required fields
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } elseif (!preg_match("/^[a-zA-Z0-9]{3,20}$/", $username)) {
    $error_message = "Username must be between 3 and 20 characters and contain only letters and numbers.";
  } elseif (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  }
  else {
    // Hash the password (VERY IMPORTANT - NEVER store passwords in plain text)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      $success_message = "Registration successful! You have been logged in.";
    } else {
      $error_message = "Registration failed. " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <?php if (isset($success_message)) { ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" value="<?php echo htmlspecialchars($username); ?>" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" value="<?php echo htmlspecialchars($email); ?>" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual values
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate the email format (basic check)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }
    // Validate password (minimum length) - consider more robust password validation
    if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    }

    // Hash the password using password_hash() - VERY IMPORTANT for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO users (firstname, lastname, email, password)
            VALUES ('$firstname', '$lastname', '$email', '$hashed_password')";

    if ($conn->query($sql)) {
        $success = "Registration successful! You have been redirected to your account.";
        // Redirect to a success page or the user's account page.
        header("Location: success.php"); // Example - replace with your success page
        exit(); // Important to stop further script execution
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

    <h2>User Registration</h2>

    <?php
    if (isset($error)) {
        echo "<p style='color: red;'>Error: " . $error . "</p>";
    }
    ?>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required><br><br>

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data validation (Basic example - expand for robust validation)
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate required fields
  if (empty($name) || empty($email) || empty($password)) {
    $errors["message"] = "All fields are required.";
  }

  // Email Validation (more thorough validation is recommended)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["message"] = "Invalid email address.";
  }

  // Password Strength (Simple example - improve for security)
  if (strlen($password) < 8) {
    $errors["message"] = "Password must be at least 8 characters long.";
  }

  // Basic email check (to avoid duplicates, ideally with a database check)
  //  This is a simplified example and should be replaced with a proper database query.
  //  This example just checks if the email already exists in a file (not recommended for production)
  $emailExists = file_exists("emails.txt") ? file_get_contents("emails.txt") : false;
  if ($emailExists && $emailExists != $email) {
    $errors["message"] = "This email address is already registered.";
  }

}

//  Database insertion (using prepared statements for security)
if (!empty($errors)) {
  // Handle errors - display them to the user
  echo "<div style='color:red;'>";
  echo "<ul>";
  foreach ($errors as $error) {
    echo "<li>" . $error . "</li>";
  }
  echo "</ul></div>";
} else {
  try {
    // Create database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare statement
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");

    // Bind parameters (for security - prevents SQL injection)
    $stmt->bind_param("ss", $name, $password);

    // Execute statement
    if ($stmt->execute()) {
      // Success!  Redirect to a success page or display a message.
      echo "<h2>Registration successful!</h2>";
      echo "<p>Please log in using your email and password.</p>";
      //You could also redirect here:
      //header("Location: login.php");
      //exit();
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
  } catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Registration</title>
</head>
<body>

  <h1>Register</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Form handling (validate and sanitize inputs)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate inputs
    $username = trim($username); // Remove leading/trailing whitespace
    $email = trim($email);
    $password = trim($password);

    // Check if username and email are empty
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else if (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
        $error = "Username must be between 3 and 20 characters and can only contain letters, numbers, and underscores.";
    } else if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    }
    // If no errors, proceed with registration
    else {
        // Hash the password using password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the user into the database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            echo "<p style='color:green;'>Registration successful! You have been redirected.</p>";
        } else {
            // Registration failed
            echo "<p style='color:red;'>Registration failed. " . $conn->error . "</p>";
        }

        // Close the statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h2>User Registration</h2>

<?php if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
}
?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database configuration (replace with your actual database details)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to register a new user
function registerUser($username, $password, $email) {
  try {
    // Connect to the database
    $pdo = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_name, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // For error handling

    // Hash the password (important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");

    // Bind the parameters (important to prevent SQL injection)
    $stmt->bindParam(1, $username);
    $stmt->bindParam(2, $hashed_password);
    $stmt->bindParam(3, $email);

    // Execute the query
    $stmt->execute();

    echo "Registration successful!  You've been redirected."; // Replace with a proper redirect

  } catch (PDOException $e) {
    // Handle database errors
    echo "Error: " . $e->getMessage();
  }
}


// Example usage (handle form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (VERY IMPORTANT - add more thorough validation here)
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

  // Basic validation (customize based on your requirements)
  if (empty($username) || empty($password) || empty($email)) {
    echo "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
  } else {
    // Call the registerUser function
    registerUser($username, $password, $email);
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Include the database connection file (assuming you have one)
require_once 'db_config.php'; // Replace with your database configuration file name

/**
 * Registers a new user in the database.
 *
 * @param string $username  The desired username.
 * @param string $password The desired password.
 * @param string $email    The user's email address.
 * @return bool True on successful registration, false otherwise.
 */
function registerUser($username, $password, $email) {
  // Input Validation (Crucial for security!)
  if (empty($username) || empty($password) || empty($email)) {
    return false; // Required fields must be filled
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // Hash the password (VERY IMPORTANT for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Prepare the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Use prepared statements to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $hashed_password);

  // Execute the query
  if ($stmt->execute()) {
    return true;
  } else {
    // Handle the error - Log the error for debugging
    error_log("Error registering user: " . $stmt->error);
    return false;
  }

  // Close the statement
  $stmt->close();
}


// Example Usage (for testing - remove in a real application)
//
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//   $username = $_POST["username"];
//   $password = $_POST["password"];
//   $email = $_POST["email"];
//
//   if (registerUser($username, $password, $email)) {
//     echo "User registered successfully!";
//   } else {
//     echo "Failed to register user. Please try again.";
//   }
// }
//
?>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_username = "your_db_username";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate input data (important to prevent security vulnerabilities)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);
  $confirm_password = trim($_POST["confirm_password"]);

  // Validation rules (adjust as needed)
  $errors = [];

  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }
  if (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters long.";
  }

  // If no errors, proceed with registration
  if (empty($errors)) {
    // Hash the password (VERY IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement (use prepared statements to prevent SQL injection)
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful!  Please check your email to verify your account.";
      // Redirect to a success page or login page
      header("Location: login.php");
      exit();
    } else {
      // Registration failed
      echo "Registration failed: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Hash the password - Important for security!
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Prepare the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // 4. Prepare the statement
  $stmt = $conn->prepare($sql);

  // 5. Bind the parameters
  $stmt->bind_param("ssi", $username, $hashed_password, $email);

  // 6. Execute the query
  if ($stmt->execute()) {
    return true; // Registration successful
  } else {
    // Handle errors
    echo "Error: " . $stmt->error;
    return false;
  }

  // 7. Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (Handle form submission - replace with your actual form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get data from the form
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate the data (IMPORTANT - Add more robust validation here!)
  if (empty($username) || empty($password) || empty($email)) {
    echo "All fields are required.";
  } else {
    // Register the user
    if (registerUser($username, $password, $email)) {
      echo "Registration successful! Please check your email for verification.";
    } else {
      echo "Registration failed.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate inputs
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Password validation (Basic example - Improve as needed)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (VERY IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success = "Registration successful! Please check your email to activate your account.";
    } else {
      $errors = "Registration failed: " . mysqli_error($GLOBALS["conn"]);
    }
  }
}

// Database connection
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($errors)) { ?>
    <p style="color: red;"><?php echo $errors; ?></p>
  <?php } ?>

  <?php if (isset($success)) { ?>
    <p style="color: green;"><?php echo $success; ?></p>
  <?php } ?>

  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Form handling (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validation (Crucial for security!)
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $error_message = "Invalid email address.";
  } elseif (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Hash the password - IMPORTANT for security!
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success_message = "Registration successful! Please check your email for verification.";
    } else {
      $error_message = "Error registering: " . mysqli_error($GLOBALS["conn"]);
    }
  }
}

// Connect to the database
$GLOBALS["conn"] = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($GLOBALS["conn"]->connect_error) {
  die("Connection failed: " . $GLOBALS["conn"]->connect_error);
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <?php if (isset($success_message)) { ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate the input data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format.";
  }

  // Password validation (basic example - improve for security)
  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  } else if (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }


  // Check if username already exists
  $username_exists = false;
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT username FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $username_exists = true;
  }
  $stmt->close();

  // Insert the user into the database
  if (empty($username_error) && empty($password_error) && !$username_exists) {
    // Hash the password (IMPORTANT: NEVER STORE PLAINTEXT PASSWORDS)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email, $hashed_password);
    if ($stmt->execute()) {
      $success_message = "Registration successful! Please check your email to verify your account.";
    } else {
      $error_message = "Registration failed. Please try again.";
    }
    $stmt->close();
  }


  // Close the database connection
  $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($success_message)) {
    echo "<p style='color: green;'>$success_message</p>";
  } ?>

  <?php if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
  } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual credentials
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to register a user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Hash the password - VERY IMPORTANT for security
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Prepare and execute the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssi", $username, $hashed_password, $email);

  if ($stmt->execute()) {
    // User registered successfully
    $stmt->close();
    $conn->close();
    return true;
  } else {
    // Error during registration
    $error = $stmt->error;
    $stmt->close();
    $conn->close();
    return false;
  }
}


// Example usage (This will be handled by the form submission)
//  You will need to create a form (HTML) to collect the user data.
//  The following part is just for demonstration - typically this would be done via POST.
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "User registered successfully! You have been redirected.";
  } else {
    echo "Registration failed. Please try again.";
  }
}
*/


//  To use this, you'll need:
//  1. Create a database table named "users" with columns:
//      - id (INT, PRIMARY KEY, AUTO_INCREMENT)
//      - username (VARCHAR)
//      - password (VARCHAR)
//      - email (VARCHAR)
//  2. Create an HTML form to collect the username, password, and email.
//  3. Submit the form data to this PHP file using POST.
//  4. The PHP code will then register the user.


?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form action="register.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the form data
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
  }
  if (preg_match('/^\s*$/', $username)) {
    $errors[] = "Username cannot be blank";
  }

  // Email validation (basic check)
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Password validation
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // If there are no errors, proceed with registration
  if (empty($errors)) {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Hash the password (important for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! You have been redirected.";
      // Redirect the user (optional)
      header("Location: login.php"); // Or your desired location
      exit();
    } else {
      // Query failed
      echo "Query failed: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <?php if (!empty($errors)) { ?>
    <div style="color: red;">
      <?php foreach ($errors as $error) {
        echo $error . "<br>";
      } ?>
    </div>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data (important for security!)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate username
    if (empty($username)) {
        $username_error = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $username_error = "Username must be at least 3 characters long.";
    }

    // Validate email
    if (empty($email)) {
        $email_error = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
    }

    // Validate password
    if (empty($password)) {
        $password_error = "Password cannot be empty.";
    } elseif (strlen($password) < 6) {
        $password_error = "Password must be at least 6 characters long.";
    }

    // If no errors, process the registration
    if (empty($username_error) && empty($email_error) && empty($password_error)) {
        // Hash the password (very important for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful! Please check your email to verify your account.";
        } else {
            // Registration failed
            echo "Registration failed. Error: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Display the registration form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>

<h2>Registration Form</h2>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
    <?php if (isset($username_error)) echo "<span style='color:red;'>$username_error</span>"; ?>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
    <?php if (isset($email_error)) echo "<span style='color:red;'>$email_error</span>"; ?>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="">
    <?php if (isset($password_error)) echo "<span style='color:red;'>$password_error</span>"; ?>

    <br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual values)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to register a user
function registerUser($username, $password, $email) {
  // 1. Connect to the Database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL Query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // 3. Bind Parameters (Important for security - prevents SQL injection)
  $password_hash = password_hash($password, PASSWORD_DEFAULT); // Hash the password
  $stmt->bind_param("ss", $username, $password_hash);

  // 4. Execute the Query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  // 5. Close the Statement and Connection
  $stmt->close();
  $conn->close();

  return true; // Indicate successful registration
}

// --- Example Usage (for demonstration - not a complete web form)
// Assume this is part of a form submission process

// Get user input (from a form, for example)
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

// Validate input (VERY IMPORTANT - you need to add proper validation here!)
if (empty($username) || empty($password) || empty($email)) {
    echo "All fields are required!";
    exit; // Stop further processing
}

// Call the registration function
if (registerUser($username, $password, $email)) {
  echo "Registration successful!  You have been redirected.";
  // Redirect to a login page or success page
} else {
  echo "Registration failed. Please try again.";
}

?>


<?php

// Configuration (adjust these as needed)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to register a user
function registerUser($username, $password, $email) {
  // 1. Database Connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Input Validation and Sanitization (VERY IMPORTANT!)
  $username = trim($username); // Remove whitespace
  $password = trim($password);
  $email = trim($email);

  // Basic validation (you should add more robust validation)
  if (empty($username) || empty($password) || empty($email)) {
    return false; // Indicate failure
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // Sanitize input (more robust sanitization is highly recommended)
  $username = $conn->real_escape_string($username);
  $email = $conn->real_escape_string($email);


  // 3. Check if the username already exists
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    return false; // Username already exists
  }

  // 4. Hash the password (VERY IMPORTANT for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 5. Insert the user into the database
  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

  if ($conn->query($sql) === TRUE) {
    return true; // Registration successful
  } else {
    return false; // Registration failed
  }

  // 6. Close the connection
  $conn->close();
}

// Example Usage (for demonstration)
// This is just an example; you would typically handle this through a form submission.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "User registered successfully!";
  } else {
    echo "Failed to register user. Please try again.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to register a new user
function registerUser($username, $password, $email) {
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Sanitize and Validate Input (IMPORTANT!)
    //   -  Escape the username and email to prevent SQL injection
    $username = $conn->real_escape_string($username);
    $email = $conn->real_escape_string($email);

    // Add more validation rules here:
    // - Password strength (minimum length, special characters, etc.)
    // - Email format validation
    // - Check if username or email already exists
    

    // 3. Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 4. Prepare and execute the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

    if ($conn->query($sql) === TRUE) {
        // Success!
        return true;
    } else {
        // Error
        echo "Error: " . $sql . "<br>" . $conn->error;
        return false;
    }

    // 5. Close the connection
    $conn->close();
}

// Example Usage (Illustrative - This would be part of a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    if (registerUser($username, $password, $email)) {
        echo "Registration successful!  You have been redirected.";
        // Redirect to a success page or login form
    } else {
        echo "Registration failed. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
</head>
<body>

    <h1>Registration Form</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input (Important for security - prevent SQL injection and other issues)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation (customize as needed)
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9_]+$/", $username)  // Allow only alphanumeric and underscores for username
  ) {
    $error_message = "Username must contain only letters, numbers, and underscores.";
  } elseif (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $error_message = "Invalid email format.";
  } elseif (
    strlen($password) < 8  //  Minimum password length
  ) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Hash the password before storing it in the database (VERY IMPORTANT FOR SECURITY)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query (Using prepared statements to prevent SQL injection)
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    $stmt->execute();

    // Success message
    $success_message = "Registration successful! Please log in.";

    // Close the statement
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <?php if (isset($success_message)) { ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter your username">

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter your email">

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter your password">

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate the input data
    $errors = [];

    // Username validation
    if (empty($username)) {
        $errors["username"] = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $errors["username"] = "Username must be at least 3 characters long.";
    }

    // Email validation
    if (empty($email)) {
        $errors["email"] = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format.";
    }

    // Password validation
    if (empty($password)) {
        $errors["password"] = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $errors["password"] = "Password must be at least 8 characters long.";
    } elseif ($password != $confirm_password) {
        $errors["password"] = "Passwords must match.";
    }

    // If there are errors, display them
    if (!empty($errors)) {
        echo "<div class='error'>Error: " . $errors["username"] . " - " . $errors["email"] . " - " . $errors["password"] . "</div>";
    } else {
        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Bind the parameters
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            echo "<div class='success'>Registration successful!</div>";
            // You might redirect here or display a welcome message.
        } else {
            // Registration failed
            echo "<div class='error'>Registration failed: " . $stmt->error . "</div>";
        }

        // Close the statement
        $stmt->close();
    }
}

// Start the session
session_start();

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Instantiate the database connection
$conn = connectToDatabase();

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <style>
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>

    <h2>User Registration</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Use prepared statements to prevent SQL injection
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind the parameters
    $stmt->bind_param("sss", $username, $password, $email);

    // Execute the query
    if ($stmt->execute()) {
      // Success!
      $user_id = $conn->insert_id; // Get the last inserted ID
      $stmt->close();
      $conn->close();

      // Optionally, send a confirmation email here
      // Example: sendEmail($email, "Welcome!", "...");

      return $user_id; // Return the user ID for future use
    } else {
      // Error executing query
      $error = $stmt->error;
      $stmt->close();
      $conn->close();
      return false;
    }
  } else {
    // Error preparing statement
    $stmt->close();
    $conn->close();
    return false;
  }
}


// Example Usage (Simplified -  This would typically be from a form submission)
// $username = $_POST['username'];
// $password = $_POST['password'];
// $email = $_POST['email'];

// if (isset($username) && isset($password) && isset($email)) {
//   $user_id = registerUser($username, $password, $email);

//   if ($user_id) {
//     echo "Registration successful! User ID: " . $user_id;
//   } else {
//     echo "Registration failed.";
//   }
// } else {
//   echo "Please fill in all fields.";
// }

?>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input (Important for security -  expand as needed)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validation example:  Basic checks - enhance this significantly!
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  } else {
    // Hash the password - *VERY IMPORTANT* - Never store passwords in plain text!
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success = "Registration successful! You have been redirected.";
    } else {
      $error = "Error registering: " . mysqli_error($GLOBALS["conn"]);
    }
  }
}

// Connect to the database
$GLOBALS["conn"] = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check the connection
if ($GLOBALS["conn"]->connect_error) {
  die("Connection failed: " . $GLOBALS["conn"]->connect_error);
}


?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <?php if (isset($success)) { ?>
    <p style="color: green;"><?php echo $success; ?></p>
    <meta http-equiv="refresh" content="3;url=index.php" >  <!-- Redirect to homepage after success -->
  <?php } ?>

  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., via mysqli)
//  $conn = new mysqli("localhost", "username", "password", "database_name");
//  if ($conn->connect_error) {
//      die("Connection failed: " . $conn->connect_error);
//  }

//  $conn = null; // Close the connection

//  // OR if using PDO
//  // $pdo = new PDO("mysql:host=localhost;dbname=database_name", "username", "password");

// --- Registration Function ---
function registerUser($username, $password, $email) {
  // Input validation (Basic - customize for your needs)
  if (empty($username) || empty($password) || empty($email)) {
    return "Error: All fields are required.";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Error: Invalid email format.";
  }

  // Hash the password (VERY IMPORTANT for security!)
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // SQL Query (sanitize to prevent SQL injection)
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Use prepared statements to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, $hashedPassword, $email);

  if ($stmt->execute()) {
    return "Registration successful! You've been redirected.";
  } else {
    return "Error: Could not register. " . $conn->error;
  }

  // Close the statement
  $stmt->close();
}


// --- Example Usage (Simulated - replace with your form handling) ---
//  In a real application, this would be handled by a form submission.

// Assuming you have the form data in $_POST:
// $username = $_POST['username'];
// $password = $_POST['password'];
// $email = $_POST['email'];

// $result = registerUser($username, $password, $email);
// echo $result;


// ---  Example Database Table Structure (MySQL) ---
/*
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
);
*/

?>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Function to register a new user
function registerUser($username, $password, $email) {
    // 1. Data Validation (Crucial - Prevent Security Issues)
    $username = trim($username); //Remove leading/trailing spaces
    $password = trim($password);
    $email = trim($email);

    //Basic Validation - Enhance as needed
    if (empty($username) || empty($password) || empty($email)) {
        return "Error: All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Error: Invalid email format.";
    }


    // 2. Database Interaction (Example using mysqli - Use PDO for better security)
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Escape data to prevent SQL injection
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);
    $email = $conn->real_escape_string($email);

    // Simple password hashing (VERY BASIC - Use a stronger library like Sodium for production)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    // SQL Query (Use parameterized queries for better security)
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

    if ($conn->query($sql) === TRUE) {
        return "Registration successful!  You have been redirected.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return "Error: " . $conn->error;
    }

    $conn->close();
}

// --- Example Usage (Form Handling) ---

// Check if the registration form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Call the registration function
    $registration_result = registerUser($username, $password, $email);

    // Display the result
    echo $registration_result;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h2>User Registration</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>
    Email: <input type="email" name="email"><br><br>
    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Sanitize and validate input (IMPORTANT!)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email address.";
  }

  // Password validation (Basic - improve this for production)
  if (empty($password)) {
    $error_message = "Password cannot be empty.";
  }

  // Check if username already exists
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($db_host, $sql);
  if (mysqli_num_rows($result) > 0) {
    $error_message = "Username already exists.";
  }

  // If no errors, insert the user into the database
  if (empty($error_message)) {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query for inserting the user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    mysqli_query($db_host, $sql);

    // Redirect to a success page or login page
    header("Location: success.php"); // Replace 'success.php' with your desired page
    exit();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php
try {
  $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
  // Set PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // ... your registration code using prepared statements ...
} catch(PDOException $e) {
  // Handle the exception (e.g., log the error)
  echo "Database connection failed: " . $e->getMessage();
  exit;
}
?>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate input (Basic validation - enhance for production)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } elseif (strlen($password) < 6) {
    $error = "Password must be at least 6 characters long.";
  } else {
    // Hash the password (Important for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      $success = "Registration successful! Please log in.";
    } else {
      $error = "Registration failed. " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
  }
}

// Function to connect to the database (You should create this function)
function connectToDatabase() {
  // Replace these with your database credentials
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Display any errors or success messages
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <?php if (isset($success)) { ?>
    <p style="color: green;"><?php echo $success; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);
  $confirm_password = trim($_POST["confirm_password"]);

  // Validation checks
  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email address.";
  } elseif ($password != $confirm_password) {
    $error_message = "Passwords do not match.";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      $success_message = "Registration successful! Please check your email to activate your account.";
    } else {
      $error_message = "Error registering. Please try again.";
    }

    // Close the statement
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <?php if (isset($success_message)) { ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
  <?php } ?>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual database details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL statement
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ssi", $username, $password, $email); // "ssi" indicates string, string, string integer. Adjust if needed.

  // 3. Execute the statement
  if ($stmt->execute()) {
    return true; // Registration successful
  } else {
    return false; // Registration failed
  }

  // 4. Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage (This is for demonstration and testing - not a complete form)
//  This part is to show how to call the function

//  Simulating form submission
// $username = $_POST["username"];
// $password = $_POST["password"];
// $email = $_POST["email"];

// if (isset($username) && isset($password) && isset($email)) {
//    if (registerUser($username, $password, $email)) {
//      echo "Registration successful! Please check your email for verification.";
//      // Redirect to a success page or login form
//    } else {
//      echo "Registration failed. Please try again.";
//    }
// } else {
//   echo "Please fill out the registration form.";
// }

?>


function registerUser($username, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL statement
  $sql = "INSERT INTO users (username, email) VALUES (?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $email);

  // 3. Execute the statement
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }

  // 4. Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (with password hashing)
// $username = $_POST["username"];
// $email = $_POST["email"];

// if (isset($username) && isset($email)) {
//   $hashed_password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password

//   if (registerUser($username, $hashed_password, $email)) {
//     echo "Registration successful! Please check your email for verification.";
//   } else {
//     echo "Registration failed. Please try again.";
//   }
// }


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the input data
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate the input data
    $username = trim($username);
    $email = trim($email);
    $password = trim($password);

    // Check if the username and email are empty
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } else {
        // Check if the username or email already exists in the database
        $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $result = mysqli_query($db_host, $sql);

        if (mysqli_num_rows($result) > 0) {
            $error_message = "Username or email already exists.";
        } else {
            // Hash the password using password_hash()
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

            if (mysqli_query($db_host, $sql)) {
                $success_message = "Registration successful! Please check your email to activate your account.";
            } else {
                $error_message = "Error inserting data into the database. " . mysqli_error($db_host);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

    <h2>User Registration</h2>

    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

    <?php if (isset($success_message)) { ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php } ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter your username" value="<?php echo htmlspecialchars($username); ?>">
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>">
        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password">
        <br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Check if required fields are empty
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        }
        // Hash the password (for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            $success = "Registration successful! Please login.";
        } else {
            $error = "Registration failed. " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
</head>
<body>

    <h2>Registration Form</h2>

    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>

    <?php if (isset($success)) { ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php } ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database_name';

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Database Connection
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

  // Check for connection errors
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Data Sanitization and Validation (Crucial for security)
  //  - Escape input to prevent SQL injection
  $username = $conn->real_escape_string($username);
  $email = $conn->real_escape_string($email);

  //  - Validation (add more checks as needed)
  if (empty($username) || empty($password) || empty($email)) {
    return false; // Indicate failure - required fields missing
  }

  //  - Check for existing username or email (optional, but recommended)
  $sql = "SELECT id, username, email FROM users WHERE username = '$username' OR email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    return false; // Username or email already exists
  }

  // 3.  Prepare and Execute the SQL Query
  $passwordHash = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security

  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$passwordHash', '$email')";

  if ($conn->query($sql) === TRUE) {
    return true; // Registration successful
  } else {
    // Handle database errors (important for debugging)
    error_log("Registration error: " . $conn->error);
    return false;
  }
}


// Example Usage (Handle form submission)

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Call the registration function
  if (registerUser($username, $password, $email)) {
    echo "Registration successful!  Please check your email to verify.";
  } else {
    echo "Registration failed. Please try again.";
  }
}


// HTML Form for Registration
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>Register</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual values)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize and validate input
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validation (Basic - Add more robust validation as needed)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $errors = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  }

  // Hash the password - IMPORTANT for security!
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if there are errors
  if ($errors) {
    // Display error messages
    echo "<div class='error'>" . $errors . "</div>";
  } else {
    // Connect to the database
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email, $hashed_password);
    $stmt->execute();

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Display success message
    echo "<div class='success'>Registration successful!</div>";

    // Optionally redirect to a login page or homepage
    // header("Location: login.php"); 
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
  <style>
    .error {
      color: red;
    }
    .success {
      color: green;
    }
  </style>
</head>
<body>

  <h2>User Registration</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validation rules (Example - Adjust to your requirements)
    $username_regex = "/^[a-zA-Z0-9_]+$/"; // Allow only letters, numbers, and underscore
    $email_regex = "/^([a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$/";

    if (empty($username) || empty($email) || empty($password)) {
        $errors = ["Username", "Email", "Password"] + (array)$errors ?? []; // Combine errors if array is null
    } elseif (!preg_match($username_regex, $username)) {
        $errors[] = "Username must only contain letters, numbers, and underscores.";
    } elseif (!preg_match($email_regex, $email)) {
        $errors[] = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password (Important for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect to the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ss", $username, $hashed_password, $username); // username is bound twice - one for the username and one for the email

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful! You have been redirected.";
            // Redirect to a success page or log the user in
            header("Location: success.php"); // Example: Redirect to a success page
            exit();
        } else {
            // Query failed
            echo "Query failed: " . $conn->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

    <h2>User Registration</h2>

    <?php if (isset($errors)) { ?>
        <div style="color: red;">
            <?php foreach ($errors as $error) {
                echo $error . "<br>";
            } ?>
        </div>
    <?php } ?>


    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter username" required>

        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email" required>

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required>

        <br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate input (Essential for security!)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9_]+$/", $username) // Basic username validation
  ) {
    $error = "Username must contain only letters, numbers, and underscores.";
  } elseif (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $error = "Invalid email format.";
  } elseif (
    strlen($password) < 8 // Minimum password length
  ) {
    $error = "Password must be at least 8 characters long.";
  } else {
    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      $success = "Registration successful!";
    } else {
      $error = "Registration failed.  Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <?php if (isset($success)) { ?>
    <p style="color: green;"><?php echo $success; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data validation
function validateRegistrationData($username, $email, $password) {
    // Check if fields are empty
    if (empty($username) || empty($email) || empty($password)) {
        return false;
    }

    // Basic email validation (can be expanded)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Password strength check (basic example)
    if (strlen($password) < 6) {
        return false;
    }

    return true;
}


// Register function
function registerUser($username, $email, $password) {
    // Validate the data
    if (!validateRegistrationData($username, $email, $password)) {
        return "Invalid registration data. Please check your input.";
    }

    // Sanitize input (important for security)
    $username = mysqli_real_escape_string($GLOBALS['conn'], $username);
    $email = mysqli_real_escape_string($GLOBALS['conn'], $email);
    $password = mysqli_real_escape_string($GLOBALS['conn'], $password);

    // Hash the password (VERY IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the user
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($GLOBALS['conn'], $query)) {
        return "Registration successful!  Please check your email for verification instructions.";
    } else {
        return "Error registering user. " . mysqli_error($GLOBALS['conn']);
    }
}

// --- Example Usage (Handle form submission) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Register the user
    $registrationResult = registerUser($username, $email, $password);

    // Display the result
    echo "<p>" . $registrationResult . "</p>";
}

// --- Example HTML Form (Include this in your HTML file) ---
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>
    <h2>Register a New Account</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Prepare the statement (only needs to be done once)
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the statement
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

// Bind the parameters (important for security!)
$stmt->bind_param("ss", $username, $email, $hashed_password);

// Get form data
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];

// Hash the password (VERY IMPORTANT for security)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Execute the statement
if ($stmt->execute([$username, $email, $hashed_password])) {
    echo "Registration successful!  Please check your email for verification instructions.";
} else {
    echo "Error registering user. " . mysqli_error($conn);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>



<?php

// Database configuration (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);
  $confirm_password = trim($_POST["confirm_password"]);

  // Validate the data
  $errors = [];

  // Check if username is empty
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }

  // Check if username is too short (example)
  if (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
  }

  // Check if email is empty
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }

  // Check if email is valid (basic validation)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Check if passwords match
  if ($password != $confirm_password) {
    $errors[] = "Passwords must match.";
  }

  // Check if password is too short (example)
  if (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters long.";
  }

  // If no errors, proceed with registration
  if (empty($errors)) {
    // Escape the data to prevent SQL injection
    $username = mysqli_real_escape_string($connection, $username);
    $email = mysqli_real_escape_string($connection, $email);
    $password = mysqli_real_escape_string($connection, $password);

    // Hash the password (important for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($connection, $sql)) {
      // Registration successful
      echo "Registration successful! Please check your email for verification instructions.";
    } else {
      // Registration failed
      echo "Error registering: " . mysqli_error($connection);
    }
  }
}

// Display the registration form
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>Register</h1>

  <?php if (isset($errors)) {
    echo "<h2>Errors:</h2><ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
  } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Sanitize and Validate Inputs
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

  // Validate required fields
  if (empty($username) || empty($password) || empty($email)) {
    return false; // Return false if any field is empty
  }

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Return false if email is invalid
  }

  // 2. Hash the Password (Important for Security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Database Query (using prepared statements - VERY IMPORTANT for security)
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hashed_password, $email]);

    return true; // Return true if registration is successful
  } catch (PDOException $e) {
    // Handle database errors (e.g., duplicate username, invalid email)
    error_log("Registration error: " . $e->getMessage()); // Log the error for debugging
    return false;
  }
}


// Example Usage (this part would be in your registration form's submission handler)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "<p>User registered successfully! Please check your email for verification instructions.</p>";
  } else {
    echo "<p>Registration failed. Please try again.</p>";
    // Optionally, provide more specific error messages based on what failed.
    if (strpos($username, 'already exists') !== false) {
      echo "<p>Username already exists. Please choose a different one.</p>";
    } elseif (strpos($username, 'invalid email') !== false) {
      echo "<p>Invalid email address. Please check your input.</p>";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Sanitize and validate the input
  $username = $conn->real_escape_string($username); // Escape SQL special characters
  $password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password for security
  $email = $conn->real_escape_string($email);


  // 3. Insert the user into the database
  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

  if ($conn->query($sql) === TRUE) {
    return true; // Registration successful
  } else {
    return false; // Registration failed
  }

  // 4. Close the database connection
  $conn->close();
}



// Example usage (This is just for demonstration - you'd usually handle this in your form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "Registration successful! Please check your email for verification instructions.";
  } else {
    echo "Registration failed. Please try again.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's call the database connection object $db

// Function to register a new user
function registerUser($username, $password, $email, $db) {
  // 1. Sanitize and Validate Inputs
  $username = trim($username); // Remove leading/trailing whitespace
  $password = trim($password);
  $email = trim($email);

  // Basic validation - add more robust validation as needed
  if (empty($username) || empty($password) || empty($email)) {
    return "Error: All fields are required.";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Error: Invalid email format.";
  }

  // Password hashing - IMPORTANT for security!  Use a strong hashing algorithm.
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


  // 2. Prepare the SQL Query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $db->prepare($sql); // Prepare statement to prevent SQL injection

  if ($stmt === false) {
    return "Error: Could not prepare statement.";
  }

  // 3. Bind Parameters
  $stmt->bind_param("sss", $username, $hashedPassword, $email); // "sss" indicates 3 string parameters

  // 4. Execute the Query
  if ($stmt->execute()) {
    $stmt->close();
    return "Registration successful! You have been sent a confirmation email."; // or redirect to a success page
  } else {
    $error = $stmt->error;
    $stmt->close();
    return "Error: " . $error; // Return the database error message
  }
}


// Example Usage (in your registration form processing)
// Assuming the form data is in the $_POST array

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Call the registration function
  $result = registerUser($username, $password, $email, $db); // Pass in your database connection

  // Display the result
  echo $result;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>Register a New Account</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize input data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate input data
  $username = trim($username);
  $email = trim($email);
  $password = trim($password);

  // Check if required fields are filled
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $error = "Invalid email format.";
  } elseif (
    // Add more password complexity validation here if needed
    strlen($password) < 6
  ) {
    $error = "Password must be at least 6 characters long.";
  } else {
    // Hash the password using password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    $stmt->execute();

    // Check if the query was successful
    if ($stmt->errno) {
      $error = "Error: " . $stmt->error;
    } else {
      // Redirect to a success page or display a success message
      header("Location: success.php");
      exit(); // Important to stop further execution
    }
  }
}

// Display the registration form
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username">

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email">

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate input (basic example - enhance this with more robust validation)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $error = "Password must be at least 6 characters long.";
  }
  else {
    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($GLOBALS['conn'], $sql)) {
      $success = true;
      $message = "Registration successful! Please check your email to verify.";
    } else {
      $error = "Error: " . mysqli_error($GLOBALS['conn']);
    }
  }
}

// Connect to the database
$GLOBALS['conn'] = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($GLOBALS['conn']->connect_error) {
  die("Connection failed: " . $GLOBALS['conn']->connect_error);
}


?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <?php if (isset($success)) { ?>
    <p style="color: green;"><?php echo $message; ?></p>
  <?php } ?>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate input (Important for security!)
    $errors = [];

    if (empty($firstname)) {
        $errors[] = "First name cannot be empty.";
    }
    if (empty($lastname)) {
        $errors[] = "Last name cannot be empty.";
    }
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    }
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }
    if (strlen($password) < 8) { // Minimum password length (adjust as needed)
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Check if there are any errors
    if (count($errors) > 0) {
        // Display errors
        echo "<h2>Error:</h2>";
        foreach ($errors as $error) {
            echo "<p>" . $error . "</p>";
        }
    } else {
        // Hash the password (VERY IMPORTANT for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (firstname, lastname, email, password)
                VALUES ('$firstname', '$lastname', '$email', '$hashed_password')";

        // Execute the query
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($conn->query($sql) === TRUE) {
            echo "<p>User registered successfully!</p>";
            // Redirect to a success page or the homepage
            header("Location: success.php"); // Replace success.php with your desired page
            exit();
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h2>User Registration</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" required><br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate the data (basic validation - enhance this for production)
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the user data
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind the parameters
            $stmt->bind_param("ss", $username, $email, $hashed_password);

            // Execute the statement
            if ($stmt->execute()) {
                // Registration successful
                echo "<p>Registration successful! Please <a href='login.php'>login</a>.</p>";
            } else {
                // Error executing the query
                echo "<p>Error: " . $stmt->error . "</p>";
            }
        } else {
            echo "<p>Error preparing statement.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

    <h1>User Registration</h1>

    <?php if (isset($error)) {
        echo "<p style='color: red;'>Error: " . $error . "</p>";
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form handling - Check if the registration form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validation
  $errors = [];

  // Check if required fields are filled
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($confirm_password)) {
    $errors[] = "All fields are required.";
  }

  // Email validation
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Password validation (Minimum length)
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  // Password confirmation validation
  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // Check if there are any errors
  if (count($errors) > 0) {
    // Display errors
    echo "<h2>Error:</h2>";
    foreach ($errors as $error) {
      echo "<p>" . $error . "</p>";
    }
  } else {
    // Sanitize data (Important for security - prevents SQL injection)
    $firstname = mysqli_real_escape_string($connection, $firstname);
    $lastname = mysqli_real_escape_string($connection, $lastname);
    $email = mysqli_real_escape_string($connection, $email);
    $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password securely

    // Database query
    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$password')";

    if (mysqli_query($connection, $sql)) {
      // Registration successful
      echo "<h2>Registration successful!</h2>";
      echo "<p>You have successfully created a new account.</p>";
      // Redirect the user (optional)
      header("Location: login.php"); // Redirect to login page
      exit;
    } else {
      echo "Error: " . $sql . "<br>" . $connection->error;
    }
  }

}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" required><br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual database credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email address.";
    }

    // Hash the password (IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($db_host, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $username_error = "Username already exists.";
    }

    // Insert the user into the database
    if (empty($username_error) && empty($email_error)) {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        if (mysqli_query($db_host, $sql)) {
            $registration_success = true;
            $registration_message = "Registration successful! You have been logged in.";
        } else {
            $registration_error = "Error registering user. Please try again later.";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h1>User Registration</h1>

<?php if (isset($registration_success)): ?>
    <p style="color: green;">
        <?php echo $registration_message; ?>
    </p>
<?php endif; ?>

<?php if (isset($registration_error)): ?>
    <p style="color: red;">
        <?php echo $registration_error; ?>
    </p>
<?php endif; ?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="">
    <br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Error reporting (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to register a new user
function registerUser($username, $password, $email) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Hash the password (important for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // SQL query to insert the user data
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Prepare statement (recommended for security)
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind parameters
    $stmt->bind_param("ss", $username, $hashed_password, $email);

    // Execute the statement
    if ($stmt->execute()) {
      // Success!
      $stmt->close();
      return true;
    } else {
      // Error executing query
      $error = $stmt->error;
      $stmt->close();
      return false;
    }
  } else {
    // Error preparing statement
    $stmt->close();
    return false;
  }
}

// Example Usage (Handle form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate input (Very important!)  Add more validation as needed
  if (empty($username) || empty($password) || empty($email)) {
    $error = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error = "Username must be at least 3 characters.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error = "Invalid email format.";
  } else {
    // Register the user
    if (registerUser($username, $password, $email)) {
      $success = "Registration successful! You have been redirected.";
    } else {
      $error = "Registration failed. Please try again.";
    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <?php if (isset($success)) { ?>
    <p style="color: green;"><?php echo $success; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the input
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Sanitize input (important for security)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  // Check if required fields are filled
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } else if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
    $error = "Username must contain only letters and numbers.";
  } else if (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  }

  // If no errors, proceed with registration
  if (empty($error)) {
    // Hash the password (VERY IMPORTANT - never store plain text passwords)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful!  Please check your email for a verification link.";
    } else {
      // Registration failed
      echo "Registration failed: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error)) {
    echo "<p style='color: red;'>Error: " . $error . "</p>";
  }
  ?>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual credentials
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form input (Crucial for security!)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate username (basic example - customize as needed)
    if (empty($username)) {
        $username_error = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $username_error = "Username must be at least 3 characters long.";
    }

    // Validate email (basic example - use a proper validation library for robust validation)
    if (empty($email)) {
        $email_error = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
    }

    // Validate password (basic example - require minimum length and special characters)
    if (empty($password)) {
        $password_error = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    }

    // Check if there are any errors
    if (!empty($username_error)) {
        $errors = ["username" => $username_error];
    } elseif (!empty($email_error)) {
        $errors = ["email" => $email_error];
    } elseif (!empty($password_error)) {
        $errors = ["password" => $password_error];
    } else {
        // No errors, proceed with registration
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security

        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $username, $hashed_password); //Bind parameters
            $stmt->execute();
            $stmt->close();
        } else {
            die("Error preparing statement: " . print_r($conn->error, true));
        }


        // Success message
        $success_message = "Registration successful! Please log in.";

        // Redirect to login page or homepage
        header("Location: login.php"); // Assuming you have a login.php page
        exit();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

    <h2>User Registration</h2>

    <?php if (!empty($success_message)) {
        echo "<p>" . $success_message . "</p>";
    } ?>

    <?php if (!empty($errors)) { ?>
        <div id="error-container">
            <?php
            foreach ($errors as $key => $value) {
                echo "<p style='color: red;'>Error: " . $value . "</p>";
            }
            ?>
        </div>
    <?php } ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">

        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password">

        <br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate the data (Important for security!)
    if (empty($username) || empty($email) || empty($password)) {
        $errors = "All fields are required.";
    } elseif (
        !preg_match("/^[a-zA-Z0-9_]+$/", $username) || // Username validation
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $errors = "Invalid email or username format.";
    } elseif (strlen($password) < 8) {
        $errors = "Password must be at least 8 characters long.";
    }
    
    // Hash the password (Crucial for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
        // Registration successful
        echo "Registration successful! Please check your email to activate your account.";
    } else {
        // Registration failed
        echo "Registration failed: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h2>User Registration</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the form data (Important for security!)
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error_message = "Invalid email address.";
  } elseif (strlen($password) < 6) {
    $error_message = "Password must be at least 6 characters long.";
  } else {
    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $firstname, $lastname, $hashed_password);
    $stmt->execute();

    // Check if the query was successful
    if ($stmt->affected_rows == 1) {
      // Registration successful
      $success_message = "Registration successful! Please check your email to activate your account.";
    } else {
      // Registration failed
      $error_message = "Registration failed. Please try again.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <?php if (isset($success_message)) { ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
  <?php } ?>

  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" placeholder="John" required>

    <br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" placeholder="Doe" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="example@email.com" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" minlength="6" required>

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Form handling (registration form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Validation (important to prevent security issues)
    if (empty($username) || empty($password) || empty($email)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif (preg_match('/@|\./|"/|[`!#$%&()*+,-_=+/|?^`{|}~]/', $email)) {
        $error = "Invalid email format.";
    }

    // Hash the password (IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password, $email);

    // Execute the query
    if ($stmt->execute()) {
        $success = "Registration successful! You have been logged in.";
    } else {
        $error = "Registration failed: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>

<h1>Registration</h1>

<?php if (isset($error)) {
    echo "<p style='color: red;'>Error: " . $error . "</p>";
} else if (isset($success)) {
    echo "<p style='color: green;'>Success: " . $success . "</p>";
    // Redirect to a login page or display a welcome message.  This is a basic example.
    header("Location: login.php"); // Replace login.php with your login page
    exit();
}
?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);

    // Sanitize and validate input (Crucial for security!)
    $username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_STRIP);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Check if username and email are empty
    if (empty($username) || empty($email)) {
        $error_message = "Username and Email are required.";
    } elseif (strlen($username) < 3) {
        $error_message = "Username must be at least 3 characters long.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error_message = "Invalid Email Address.";
    } else {
        // Hash the password (Important for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        if (mysqli_query($GLOBALS['conn'], $sql)) {
            $success_message = "Registration successful!  You have been logged in.";
        } else {
            $error_message = "Error: " . mysqli_error($GLOBALS['conn']);
        }
    }
} else {
    // Initialize variables for the registration form
    $error_message = "";
    $success_message = "";
}

// Connect to the database
$GLOBALS['conn'] = mysqli_connect($host, $username, $password, $database);

// Check connection
if ($GLOBALS['conn']->connect_error) {
    die("Connection failed: " . $GLOBALS['conn']->connect_error);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
</head>
<body>

    <h1>Registration Form</h1>

    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>


    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter username" value="<?php echo htmlspecialchars($username); ?>">
        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password">
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email">
        <br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate required fields
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $errors = "Password must be at least 6 characters long.";
  }

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if there are any errors
  if ($errors) {
    // Display errors
    echo "<div class='error'>" . $errors . "</div>";
  } else {
    // Insert the user into the database
    try {
      $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Prepare the SQL query
      $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

      // Bind the parameters
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':password', $hashed_password);

      // Execute the query
      $stmt->execute();

      // Display a success message
      echo "<div class='success'>Registration successful!</div>";
      // Optionally, redirect to a login page
      // header("Location: login.php");
      // exit;

    } catch (PDOException $e) {
      echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
  <style>
    .error {
      color: red;
    }
    .success {
      color: green;
    }
  </style>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data (Add more validation as needed)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation (Improve this significantly for production)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  }

  // If no errors, proceed with registration
  if (empty($errors)) {
    // Sanitize inputs (Important for security - see explanation below)
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Hash the password (Use a strong hashing algorithm like password_hash)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email, $hashed_password);
    $stmt->execute();

    // Success message
    $success_message = "Registration successful!  Please check your email to verify your account.";

    // Close the statement and connection
    $stmt->close();
    $conn->close();

  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($success_message)) {
    echo "<p style='color: green;'>$success_message</p>";
  } ?>

  <?php if (isset($errors)) {
    echo "<p style='color: red;'>$errors</p>";
  } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and you're using a secure database connection method (e.g., PDO)

function registerUser($username, $password, $email, $db) {
  // 1. Input Validation & Sanitization - VERY IMPORTANT!
  // Prevent SQL injection and ensure data is clean
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $email    = filter_var($email, FILTER_SANITIZE_EMAIL);

  // Basic username validation (length and character restrictions)
  if (strlen($username) < 3 || strlen($username) > 20) {
    return "Username must be between 3 and 20 characters.";
  }

  // Check if username already exists
  $stmt = $db->prepare("SELECT id, username, email FROM users WHERE username = ? OR email = ?");
  $stmt->bind_param("ss", $username, $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    return "Username or email already exists.";
  }

  // 2. Password Hashing -  Critical for Security
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Use a strong hashing algorithm

  // 3. Insert into Database
  $stmt = $db->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
  $stmt->bind_param("ssi", $username, $hashed_password, $email);
  $stmt->execute();

  if ($stmt->error) {
    return "Error creating user: " . $stmt->error;
  }

  // 4. Success!
  return "User registered successfully!  You have been sent a verification email."; // You should send a real verification email here.
}



// Example Usage (in a form submission handler)
//  (This is a simplified example and needs to be integrated into a real web application)

// Assuming you have a form with fields: username, password, email

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email    = $_POST["email"];

  // Call the registration function
  $result = registerUser($username, $password, $email, $db); // Replace $db with your database connection

  // Display the result
  echo "<p>" . $result . "</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the input (VERY IMPORTANT -  don't skip this!)
  $errors = [];
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }

  // Basic email validation (improve this)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Check if there are any errors
  if (count($errors) > 0) {
    // Display errors (you should do this on a separate page for better design)
    echo "<ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
    //  Don't redirect here - keep the user on the registration page
    exit; // Important to stop further processing if there are errors.
  }

  // Hash the password (VERY IMPORTANT - never store passwords in plain text)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // SQL query (use prepared statements to prevent SQL injection -  SEE IMPORTANT NOTES BELOW)
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $hashed_password);

  // Execute the statement
  if ($stmt->execute()) {
    // Registration successful
    echo "Registration successful!  Please verify your email.";  // Or redirect here
  } else {
    // Registration failed
    echo "Registration failed.  Error: " . $stmt->error;
  }

  // Close the statement
  $stmt->close();
}

//  The following code is for the registration form itself
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>Register</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate input (Basic example - expand this for a robust implementation)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate username
    if (empty($username)) {
        $username_error = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $username_error = "Username must be at least 3 characters.";
    }

    // Validate email
    if (empty($email)) {
        $email_error = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
    }

    // Validate password (Basic - recommend stronger password policies)
    if (empty($password)) {
        $password_error = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters.";
    }

    // Check if validation passed
    if (empty($username_error) && empty($email_error) && empty($password_error)) {

        // Hash the password (VERY IMPORTANT for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the new user
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        if (mysqli_query($GLOBALS["conn"], $sql)) {
            // Registration successful
            echo "Registration successful! Please login.";
        } else {
            // Handle database error
            echo "Error: " . mysqli_error($GLOBALS["conn"]);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

    <h2>User Registration</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        <?php if (isset($username_error)) echo "<p style='color:red;'>$username_error</p>"; ?>

        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        <?php if (isset($email_error)) echo "<p style='color:red;'>$email_error</p>"; ?>

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password">
        <?php if (isset($password_error)) echo "<p style='color:red;'>$password_error</p>"; ?>

        <br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>


<?php

// Database configuration (replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Hash the password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Prepare the SQL statement
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // 4. Prepare the statement
  $stmt = $conn->prepare($sql);

  // 5. Bind the parameters
  $stmt->bind_param("ss", $username, $hashed_password, $email);

  // 6. Execute the statement
  if ($stmt->execute()) {
    return true; // Registration successful
  } else {
    // Handle errors
    echo "Error: " . $stmt->error;
    return false;
  }

  // 7. Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (This would typically come from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate input (Important for security and data integrity)
  if (empty($username) || empty($password) || empty($email)) {
    echo "All fields are required!";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format!";
  } else {
    // Register the user
    if (registerUser($username, $password, $email)) {
      echo "Registration successful! You can now log in.";
    } else {
      echo "Registration failed. Please try again.";
    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validation (basic - enhance this for production!)
  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $error_message = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error_message = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error_message = "Invalid email format.";
  } elseif ($password != $confirm_password) {
    $error_message = "Passwords must match.";
  }

  // If no errors, proceed with registration
  if (empty($error_message)) {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    $result = mysqli_query($GLOBALS["db_host"], $sql);

    if ($result) {
      // Registration successful
      $success_message = "Registration successful!  Please check your email to activate your account.";
    } else {
      // Registration failed
      $error_message = "Error registering. Please try again later.";
    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <?php if (isset($success_message)) { ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Form data handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the user data
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        if (mysqli_query($GLOBALS['conn'], $sql)) {
            $success = true;
        } else {
            $error = "Error: " . mysqli_error($GLOBALS['conn']);
        }
    }
}

// Database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h1>User Registration</h1>

<?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php } ?>

<?php if (isset($success)) { ?>
    <p style="color: green;">User registered successfully!</p>
<?php } ?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data
    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Check if the email is already registered
    $emailExists = false;
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $check_query = "SELECT email FROM users WHERE email = '$email'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        $emailExists = true;
    }

    $conn->close();

    // If the email doesn't exist, register the user
    if (!$emailExists) {
        // Hash the password (Important for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $insert_query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";

        // Execute the query
        if ($conn = new mysqli($host, $username, $password, $database)) {
            if ($conn->query($insert_query)) {
                echo "Registration successful! Please check your email to activate your account.";
                // Optionally redirect to a success page
                // header("Location: success.php");
                // die();
            } else {
                echo "Error: " . $insert_query . "<br>" . $conn->error;
            }
            $conn->close();
        } else {
            echo "Error: Connection failed!";
        }
    } else {
        echo "This email is already registered. Please log in.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
</head>
<body>

    <h1>Registration</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" placeholder="Your Name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" placeholder="Your Email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="Your Password" required><br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to register a new user
function registerUser($username, $password, $email) {
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Hash the password (Important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 3. Prepare and execute the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $username, $hashed_password, $email); // "ss" means string

        if ($stmt->execute()) {
            // Registration successful
            return true;
        } else {
            // Registration failed
            echo "Registration failed: " . $stmt->error;
            return false;
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
        return false;
    }

    // Close the database connection (Important!)
    $stmt->close();
    $conn->close();
}

// Example Usage (Demonstration - in a real application, this would come from a form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    if (empty($username) || empty($password) || empty($email)) {
        echo "All fields are required.";
    } else {
        if (registerUser($username, $password, $email)) {
            echo "Registration successful! Please check your email for verification instructions.";
        } else {
            echo "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

    <h1>User Registration</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validation (Important: Add more robust validation here)
    if (empty($username) || empty($email) || empty($password)) {
        $errors = ["Username", "Email", "Password"] ;
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors = ["Email"] ;
        $error_message = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $errors = ["Password"] ;
        $error_message = "Password must be at least 8 characters long.";
    }
    else {
        // Hash the password - NEVER store passwords in plain text!
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert user data
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful! Please check your email to activate your account.";
        } else {
            // Registration failed
            echo "Registration failed: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

    <h1>User Registration</h1>

    <?php if (isset($error_message)) {
        echo "<p style='color: red;'>Error: " . $error_message . "</p>";
    }
    ?>


    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., via mysqli)
// This is a basic example, replace with your database connection details
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input (important for security!)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Basic validation (add more robust validation as needed)
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email address.";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } else {
        // Hash the password (VERY IMPORTANT for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            // Registration successful
            $success_message = "Registration successful! Please log in.";
        } else {
            // Registration failed
            $error_message = "Error registering. Please try again.";
            error_log(mysqli_error($conn)); // Log the error for debugging
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h1>User Registration</h1>

<?php if (isset($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>

<?php if (isset($success_message)): ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
<?php endif; ?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">
    <br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize input data to prevent vulnerabilities
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate input data
  $username = trim($username);
  $email = trim($email);
  $password = trim($password);

  // Check if username and email are empty
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } else {
    // Check if username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($db_host, $sql);

    if (mysqli_num_rows($result) > 0) {
      $error_message = "Username already exists.";
    } else {
      // Check if email is valid
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
      }
      // Hash the password before storing it
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // Insert the new user into the database
      $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

      if (mysqli_query($db_host, $sql)) {
        $success_message = "Registration successful!  Please check your email to confirm your account.";
      } else {
        $error_message = "Error inserting user: " . mysqli_error($db_host);
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
  } else if (isset($success_message)) {
    echo "<p style='color: green;'>$success_message</p>";
  } ?>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate Input (Important for security!)
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
  }

  // Email validation
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Password validation
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  // Check if there are any errors
  if (count($errors) > 0) {
    // Display error messages
    echo "<div class='error'><ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul></div>";
  } else {
    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $db_conn->prepare($sql); // Use prepared statements for security!

    // Hash the password (Important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Bind the values to the prepared statement
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "<div class='success'>Registration successful!</div>";
      // Optionally, redirect the user to a login page or homepage
      // header("Location: login.php");
    } else {
      // Registration failed
      echo "<div class='error'>Registration failed.  " . $db_conn->error . "</div>";
    }
    $stmt->close(); // Close the statement
  }
}

// Check if the registration form is being displayed
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
  <style>
    .error {
      background-color: #f44336;
      padding: 10px;
      margin-top: 20px;
    }
    .success {
      background-color: #4caf50;
      padding: 10px;
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <h2>User Registration</h2>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form input
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate username (basic example - adjust as needed)
  if (empty($username)) {
    $username_error = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $username_error = "Username must be at least 3 characters.";
  }

  // Validate email
  if (empty($email)) {
    $email_error = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format.";
  }

  // Validate password
  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters.";
  }


  // Hash the password (important for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if there are any errors
  if (!empty($username_error) || !empty($email_error) || !empty($password_error)) {
    $error = $error . " " . $username_error . " " . $email_error . " " . $password_error;
  } else {
    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    $result = mysqli_query($GLOBALS["db_host"], $sql);

    if ($result) {
      $success = "Registration successful! Please check your email to verify.";
    } else {
      $error = "Error registering.  Please try again.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <?php if (isset($success)) { ?>
    <p style="color: green;"><?php echo $success; ?></p>
  <?php } ?>

  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to register a new user
function registerUser($username, $email, $password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare and execute the SQL query
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password for security

  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("ss", $username, $email, $hashed_password);
    $stmt->execute();
    return true;
  } else {
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage (you'll likely get this from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the data (important - see note below)
  if (empty($username) || empty($email) || empty($password)) {
    echo "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
  } else {
    // Register the user
    if (registerUser($username, $email, $password)) {
      echo "Registration successful!  You've been redirected.";
      // Redirect to a success page or login page
    } else {
      echo "Registration failed.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>Register</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form handling (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);

    // Validation functions (Implement more robust validation)
    if (empty($username) || empty($password) || empty($email)) {
        $errors[] = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    } elseif (preg_match('/^\w+$/', $username) == 0) {
        $errors[] = "Username must contain only alphanumeric characters.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    } elseif (preg_match('/^\w+$/', $password) == 0) {
        $errors[] = "Password must contain only alphanumeric characters.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $errors[] = "Invalid email format.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Escape data for security (IMPORTANT)
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);
        $email = mysqli_real_escape_string($conn, $email);

        // Hash the password (IMPORTANT for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            // Registration successful
            echo "Registration successful! Please log in.";
        } else {
            // Registration failed
            echo "Registration failed: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h2>User Registration</h2>

<?php if (!empty($errors)) { ?>
    <div style="color: red;">
        <?php foreach ($errors as $error) echo $error . "<br>"; ?>
    </div>
<?php } ?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Validation
    $errors = [];

    // Username Validation
    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    }

    // Email Validation
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Password Validation
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    } elseif ($password != $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) 
                VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Bind the parameters
        $stmt->bind_param("s", $username, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful!  Please check your email for verification.";
            // Redirect to a welcome page or login page
            header("Location: welcome.php");
            exit;
        } else {
            // Handle errors
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Display the registration form
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

    <h2>Register</h2>

    <?php if (!empty($errors)) {
        echo "<div style='color: red;'>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>";
        echo "</div>";
    } ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate and sanitize input data
  $firstname = trim($_POST["firstname"]);
  $lastname = trim($_POST["lastname"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  //  Input validation (Important for security)
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (Important for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Attempt to connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $firstname, $lastname, $hashed_password);
    $stmt->execute();

    // Display success message
    echo "Registration successful!  Please check your email to verify your account.";
    // Optionally redirect to a login page or another page
    // header("Location: login.php"); // Replace login.php with your login page URL
    // exit();

  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <?php if (isset($error_message)) {
    echo "<p style='color:red;'>$error_message</p>";
  }
  ?>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" required>

    <br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the input
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    }

    // Validate password (e.g., minimum length)
    if (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    }

    // Check if username already exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Username already exists.";
    }

    // Hash the password (IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the database
    if (empty($error_message)) {
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            $error_message = "Error inserting user.  Check your database credentials.";
        } else {
            $error_message = "Registration successful! Please check your email to verify."; //You'll want a verification email in a real application
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h1>User Registration</h1>

<?php if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect data from the form
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the input (Important!  Add more robust validation here)
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } elseif (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (Important for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (firstname, lastname, email, password) 
            VALUES ('$firstname', '$lastname', '$email', '$hashed_password')";

    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success = "Registration successful! You have been redirected to the login page.";
      // Redirect to the login page
      header("Location: login.php");
      exit;  // Stop further execution
    } else {
      $error = "Error: " . mysqli_error($GLOBALS["conn"]);
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>Registration Form</h2>

  <?php if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
  } ?>

  <form action="registration.php" method="POST">
    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" required>

    <br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <input type="submit" value="Register">
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p>

</body>
</html>


<?php

// Database configuration (Replace with your actual database credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Collect data from the form
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the data (Crucial for security)
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors["username"] = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $errors["username"] = "Username must be at least 3 characters long.";
  }

  // Email validation
  if (empty($email)) {
    $errors["email"] = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Invalid email format.";
  }

  // Password validation
  if (empty($password)) {
    $errors["password"] = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $errors["password"] = "Password must be at least 8 characters long.";
  }

  // Confirm password validation
  if (empty($confirm_password)) {
    $errors["confirm_password"] = "Confirm password cannot be empty.";
  } elseif ($password !== $confirm_password) {
    $errors["confirm_password"] = "Passwords do not match.";
  }

  // If there are no errors, proceed with registration
  if (empty($errors)) {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement (Recommended for security)
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! Please check your email for confirmation instructions.";
    } else {
      // Registration failed
      echo "Registration failed: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

  } else {
    // Display the form with error messages
    echo "<h2>Error:</h2>";
    echo "<ol>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ol>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Validate Inputs (Crucial for security)
  $username = trim($username);
  $password = trim($password);
  $email = trim($email);

  // Check for empty fields
  if (empty($username) || empty($password) || empty($email)) {
    return "Error: All fields are required.";
  }

  // Basic username validation (you can add more complex validation here)
  if (strlen($username) < 3 || strlen($username) > 20) {
    return "Error: Username must be between 3 and 20 characters.";
  }

  // Email Validation (using a basic regex)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Error: Invalid email format.";
  }


  // 2. Connect to the Database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    return "Error: Connection failed: " . $conn->connect_error;
  }

  // 3. Prepare and Execute the SQL Query
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password

  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssi", $username, $hashed_password, $email);

  if ($stmt->execute()) {
    $stmt->close();
    return "Registration successful! You have been logged in.";
  } else {
    $stmt->close();
    return "Error: " . $conn->error;
  }
}

// Example Usage (for testing - you'll typically handle this through a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  $registration_result = registerUser($username, $password, $email);
  echo $registration_result;
} else {
  // Display the registration form (if not submitting a form)
  ?>
  <!DOCTYPE html>
  <html>
  <head>
    <title>User Registration</title>
  </head>
  <body>
    <h1>User Registration</h1>
    <form method="post" action="">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required><br><br>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required><br><br>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required><br><br>

      <button type="submit">Register</button>
    </form>
  </body>
  </html>
<?php
}
?>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the data (IMPORTANT - Add more robust validation here!)
  $errors = [];

  if (empty($firstname)) {
    $errors[] = "First name cannot be empty.";
  }
  if (empty($lastname)) {
    $errors[] = "Last name cannot be empty.";
  }
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors[] = "Invalid email format.";
  }
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  // Add password complexity requirements here (e.g., minimum length, special characters)
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  // Check if there are any errors
  if (count($errors) > 0) {
    // Display the errors
    echo "<h2>Error:</h2>";
    echo "<ol>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ol>";
  } else {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("sss", $firstname, $lastname, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "<h2>Registration Successful!</h2>";
      echo "<p>You have successfully registered.  Please check your email to activate your account (if applicable).</p>";
      // Redirect the user to a login page or homepage
      header("Location: login.php"); // Replace with your login page
      exit();
    } else {
      // Registration failed
      echo "<h2>Registration Failed!</h2>";
      echo "<p>An error occurred while registering.  " . $conn->error . "</p>";
    }

    // Close the statement
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>Register</h2>

  <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" required><br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate the data (Important for security!)
  $errors = [];

  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }

  // Basic email validation (you can use a more robust regex if needed)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Hash the password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if there are any errors
  if (empty($errors)) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful!  Please check your email for confirmation instructions.";
      // Redirect to a confirmation page or login page
      // header("Location: confirmation.php");  // Example
    } else {
      // Query failed
      echo "Registration failed: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
  } else {
    // Display errors
    echo "Errors:<ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database credentials - REPLACE with your actual values!
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (very basic example - improve this for production)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate username (e.g., minimum length, allowed characters)
  if (empty($username) || strlen($username) < 5) {
    $username_error = "Username must be at least 5 characters long.";
  }

  // Validate email (basic format check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format.";
  }

  // Validate password (e.g., minimum length, complexity)
  if (empty($password) || strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }


  // If validation passes, proceed with registration
  if (empty($username_error) && empty($email_error) && empty($password_error)) {

    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    $result = mysqli_query($GLOBALS['db_host'], $sql);  // Use mysqli_query

    if ($result) {
      // Registration successful
      $success_message = "Registration successful! Please check your email to activate your account.";
    } else {
      // Registration failed
      $error_message = "Error registering user. Please try again.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($success_message)) {
    echo "<p style='color: green;'>$success_message</p>";
  } ?>

  <?php if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
  } ?>


  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    <span style="color: red;"><?php if (isset($username_error)) echo $username_error; ?></span>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
    <span style="color: red;"><?php if (isset($email_error)) echo $email_error; ?></span>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
    <span style="color: red;"><?php if (isset($password_error)) echo $password_error; ?></span>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate input (important for security!)
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
  }

  // Email validation
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Password validation
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  } elseif ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // If no errors, proceed with registration
  if (empty($errors)) {
    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare statement for security
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful!  You have been logged in.";
      // Redirect to a success page or home page
      header("Location: success.php");
      exit();
    } else {
      // Handle errors
      echo "Error: " . $stmt->error;
    }
  }
}

// Start the database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input (important for security!)
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate required fields
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } elseif (strlen($password) < 6) {
    $error = "Password must be at least 6 characters long.";
  }
   // Hash the password - VERY IMPORTANT FOR SECURITY
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("sss", $username, $email, $hashed_password);

  // Execute the query
  if ($stmt->execute()) {
    $success = "Registration successful! You have been redirected.";
    // Optionally redirect to a success page
    // header("Location: success.php");
    // exit;
  } else {
    $error = "Registration failed: " . $stmt->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validation (Crucial for security)
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $errors = "Username must be at least 3 characters.";
    } elseif (preg_match("/@/", $email)) {
        $errors = "Invalid email format.";
    } elseif ($password != $confirm_password) {
        $errors = "Passwords do not match.";
    }


    // Hash the password (Never store passwords in plain text!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful! Please check your email to activate your account.";
        } else {
            // Query failed
            echo "Query failed: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement.";
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h2>User Registration</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and validate the input data
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email address.";
    }

    // Validate password strength (basic example - customize as needed)
    if (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    }

    // Check if username and email are empty
    if (empty($username) || empty($email)) {
        $username_error = "Username and email are required.";
        $email_error = "Email is required.";
    }

    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // If validation passes, insert the user into the database
    if (empty($username_error) && empty($email_error) && empty($password_error)) {
        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // User registration successful
            echo "Registration successful! Please log in.";
        } else {
            // Registration failed
            echo "Registration failed: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h2>User Registration</h2>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database configuration (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to register a user
function registerUser($username, $password, $email) {
    // 1. Validate Inputs (Important for security!)
    if (empty($username) || empty($password) || empty($email)) {
        return "Error: All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Error: Invalid email address.";
    }

    // Sanitize inputs to prevent SQL injection
    $username = mysqli_real_escape_string($GLOBALS['conn'], $username);
    $password = mysqli_real_escape_string($GLOBALS['conn'], $password);
    $email = mysqli_real_escape_string($GLOBALS['conn'], $email);

    // 2. Database Query
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

    if ($GLOBALS['conn']->query($sql) === TRUE) {
        return "Registration successful!  You have been redirected.";
    } else {
        return "Error: " . $sql . "<br>" . $GLOBALS['conn']->error;
    }
}


// Example Usage (This would typically be in a form submission handler)
//  In a real application, this would be part of a form processing handler.
//  This is just for demonstration.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Call the registration function
    $registration_result = registerUser($username, $password, $email);

    // Display the result
    echo "<p>" . $registration_result . "</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h2>User Registration</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to register a new user
function registerUser($username, $password, $email) {

    // Validate inputs (Crucial for security!)
    $username = trim($username);
    $password = trim($password);
    $email = trim($email);

    // Check if inputs are empty
    if (empty($username) || empty($password) || empty($email)) {
        return "Error: All fields are required.";
    }

    // Sanitize inputs - VERY IMPORTANT to prevent SQL injection
    $username = mysqli_real_escape_string($GLOBALS['conn'], $username);
    $password = mysqli_real_escape_string($GLOBALS['conn'], $password);
    $email = mysqli_real_escape_string($GLOBALS['conn'], $email);

    // Check if username or email already exists
    $check_sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($GLOBALS['conn'], $check_sql);

    if (mysqli_num_rows($result) > 0) {
        return "Error: Username or email already exists.";
    }

    // Hash the password (IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    $insert_sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

    if (mysqli_query($GLOBALS['conn'], $insert_sql)) {
        return "Registration successful! You have been sent a verification email.";
    } else {
        return "Error: Failed to register. Please try again.";
    }
}

// --- Example Usage (Demonstration - should be handled by a form submission)
// $registration_result = registerUser("john_doe", "P@$$wOrd", "john.doe@example.com");
// echo $registration_result;

// ---  Form Handling (This would be part of a web form processing script)
// Assuming you have a form with fields: username, password, email
// 1. Get user input from the form.
// 2. Call registerUser() with the input values.
// 3. Display the return value (success message or error message) to the user.


// --- Database Setup (Connection)
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Table Structure (Create the 'users' table if it doesn't exist)
$create_table_sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
)";

if (!$conn->query($create_table_sql)) {
    die("Error creating table: " . $conn->error);
}


?>


<?php

// Database connection details (Replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data (Basic validation - improve this for production)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Check if required fields are filled
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  }
  // Validate email format (basic check)
  elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  }
  // You'd add more complex password validation here (e.g., minimum length, special characters)
  // For simplicity, we'll just check if the password is not empty
  else {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success = "Registration successful. Please log in.";
    } else {
      $errors = "Error registering user. " . mysqli_error($GLOBALS["conn"]);
    }
  }
}

// Connect to the database
$GLOBALS["conn"] = mysqli_connect($host, $username, $password, $database);

// Check the connection
if ($GLOBALS["conn"]->connect_error) {
  die("Connection failed: " . $GLOBALS["conn"]->connect_error);
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (!empty($errors)) { ?>
    <p style="color: red;"><?php echo $errors; ?></p>
  <?php } ?>

  <?php if (!empty($success)) { ?>
    <p style="color: green;"><?php echo $success; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Form handling (GET or POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validation (Important for security - add more validation as needed)
  if (empty($username) || empty($password) || empty($email)) {
    $error_message = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error_message = "Username must be at least 3 characters long.";
  } elseif (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error_message = "Invalid email format.";
  }

  //  Hashes password before storing in database
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Database connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssi", $username, $hashed_password, $email);

  // Execute the query
  if ($stmt->execute()) {
    // Registration successful
    echo "Registration successful!  You have been logged in.";
    // You might redirect here after successful registration
    // header("Location: welcome.php");
    // exit;

  } else {
    echo "Registration failed: " . $stmt->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$db_name = 'your_db_name';

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate the input data
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email address.";
  }

  // Check if username or email already exists (optional, but recommended)
  if (usernameExists($username, $db_user, $db_password, $db_name)) {
    $username_error = "Username already exists.";
  }

  if (emailExists($email, $db_user, $db_password, $db_name)) {
    $email_error = "Email address already exists.";
  }

  // Password strength validation (basic example)
  if (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // Hash the password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert the new user into the database
  if (empty($username_error) && empty($email_error) && empty($password_error)) {
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql); // Use prepared statement
    $stmt->bind_param("sss", $username, $email, $hashed_password);  // Bind parameters
    $stmt->execute();

    // Redirect to a success page
    header("Location: success.php"); // Replace success.php with your desired redirect
    exit();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <?php
    if (isset($username_error)) echo "<p style='color:red;'>$username_error</p>";
    if (isset($email_error)) echo "<p style='color:red;'>$email_error</p>";
    if (isset($password_error)) echo "<p style='color:red;'>$password_error</p>";
  ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form handling - checking if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitization and validation of input data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate required fields
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  }

  // Database connection
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL statement
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password);

  // Execute the statement
  if ($stmt->execute()) {
    $success = "Registration successful.  Please log in.";
  } else {
    $errors = "Registration failed.  " . $stmt->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($errors)) { ?>
    <p style="color: red;"><?php echo $errors; ?></p>
  <?php } ?>

  <?php if (isset($success)) { ?>
    <p style="color: green;"><?php echo $success; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email, $hashed_password);
    $stmt->execute();

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to a success page
    header("Location: success.php"); // Replace success.php with your desired success page
    exit();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error_message)) {
    echo "<p style='color: red;'>Error: " . $error_message . "</p>";
  } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);
  $confirm_password = trim($_POST["confirm_password"]);

  // Validate the input (IMPORTANT - Sanitize and Validate)
  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $errors = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } elseif ($password != $confirm_password) {
    $errors = "Passwords do not match.";
  } else {
    // Hash the password (VERY IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success = "Registration successful! You have been redirected.";
    } else {
      $errors = "Error registering user. " . mysqli_error($GLOBALS["conn"]);
    }
  }
}

// Connect to the database
$GLOBALS["conn"] = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($GLOBALS["conn"]->connect_error) {
  die("Connection failed: " . $GLOBALS["conn"]->connect_error);
}


?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($errors)) { ?>
    <p style="color: red;"><?php echo $errors; ?></p>
  <?php } ?>

  <?php if (isset($success)) { ?>
    <p style="color: green;"><?php echo $success; ?></p>
  <?php } ?>


  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established 
// (e.g., using mysqli or PDO)
// We'll use a simplified example with mysqli for demonstration.
// Remember to adapt this to your database setup.

// Replace with your database credentials
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Function to register a user
function registerUser($username, $password, $email) {
  try {
    // 1. Establish Database Connection (if not already done)
    $conn = new mysqli($host, $username, $password, $database);

    // Check for connection errors
    if ($conn->connect_error) {
      throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // 2. Prepare the SQL Query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
      throw new Exception("Prepare statement failed.");
    }

    // 3. Bind Parameters
    $stmt->bind_param("ss", $username, $password);

    // 4. Execute the Query
    if (!$stmt->execute()) {
      throw new Exception("Query failed: " . $stmt->error);
    }

    // 5. Close the Statement
    $stmt->close();

    // 6. Close the Connection
    $conn->close();

    return true; // Registration successful
  } catch (Exception $e) {
    // Handle errors appropriately (e.g., display an error message)
    error_log("Registration error: " . $e->getMessage()); // Log the error for debugging
    return false; // Registration failed
  }
}

// --- Example Usage (from a form submission) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize inputs (VERY IMPORTANT - see security notes below)
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

  // Validate inputs (add more validation as needed)
  if (empty($username) || empty($password) || empty($email)) {
    $error = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error = "Username must be at least 3 characters.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error = "Invalid email address.";
  } else {
      // Register the user
      if (registerUser($username, $password, $email)) {
          $success = "Registration successful!  You have been sent an email verification link.";
      } else {
          $error = "Registration failed. Please try again.";
      }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <?php if (isset($success)) {
    echo "<p style='color: green;'>".$success."</p>";
  } ?>


  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (Crucial for security)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation (You should add more robust validation here)
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9]+$/", $username)  // Allow only alphanumeric characters
  ) {
    $error_message = "Username must contain only alphanumeric characters.";
  } elseif (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $error_message = "Invalid email address.";
  } elseif (
    strlen($password) < 8
  ) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (VERY IMPORTANT - never store passwords in plain text)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql); // Assuming $conn is your database connection

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! You have been redirected.";
      // You can redirect to a welcome page or login page here.
      header("Location: login.php"); // Example redirect
      exit();
    } else {
      // Registration failed
      echo "Registration failed: " . $stmt->error;
    }
  }

  // Display the error message if any
  if (!empty($error_message)) {
    echo "<p style='color: red;'>Error: " . $error_message . "</p>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Registration</title>
</head>
<body>

  <h1>Registration</h1>

  <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate the input data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  }

  // Check if password is empty
  if (empty($password)) {
    $error_message = "Password cannot be empty.";
  }

  // Hash the password (IMPORTANT for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $email, $hashed_password);

  // Execute the query
  if ($stmt->execute()) {
    // Registration successful
    $success_message = "Registration successful! Please log in.";
  } else {
    // Registration failed
    $error_message = "Registration failed. " . $stmt->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error_message)) {
    echo "<p style='color: red;'>Error: " . $error_message . "</p>";
  } else if (isset($success_message)) {
    echo "<p style='color: green;'>$success_message</p>";
  }
?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to register a user
function registerUser($username, $password, $email) {
  // 1. Data Validation
  if (empty($username) || empty($password) || empty($email)) {
    return false; // Invalid input
  }

  // You can add more robust validation here, like:
  // - Password complexity checks (minimum length, special characters, etc.)
  // - Email format validation (using filter_var)
  // - Check for existing usernames or emails in the database

  // 2.  Hashing the Password (IMPORTANT for Security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Database Query
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_SILENT); // Suppress error messages

    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    return true; // Registration successful
  } catch (PDOException $e) {
    // Handle database errors
    error_log("Database Error: " . $e->getMessage()); // Log the error
    return false; // Registration failed
  }
}


// --- Example Usage (Handle Form Submission)
//  This part would normally be in a separate PHP file (e.g., register.php)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "Registration successful! Please check your email for verification instructions.";
  } else {
    echo "Registration failed. Please try again.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Registration Form</title>
</head>
<body>

  <h2>Registration Form</h2>

  <form method="post" action="register.php">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation (Add more robust validation as needed)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  } else {
    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind the parameters
      $stmt->bind_param("ss", $username, $hashed_password);

      // Execute the statement
      if ($stmt->execute()) {
        $success = "Registration successful. Please check your email for verification.";
      } else {
        $errors = "Registration failed: " . $stmt->error;
      }
    } else {
      $errors = "Error preparing statement.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($errors)) { ?>
    <p style="color: red;"><?php echo $errors; ?></p>
  <?php } ?>

  <?php if (isset($success)) { ?>
    <p style="color: green;"><?php echo $success; ?></p>
  <?php } ?>

  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form handling (for demonstration, ideally use a proper form with CSRF protection)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validation (Important! Add more robust validation)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $error = "Password must be at least 6 characters long.";
  } else {
    // Password hashing (IMPORTANT: Use strong hashing!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query (Use prepared statements to prevent SQL injection!)
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email, $hashed_password);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
      $error = "Registration failed.  Check your database connection.";
    } else {
      // Successful registration - Redirect to a success page or login form
      header("Location: registration_success.php"); // Replace with your success page
      exit();
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error)) {
    echo "<p style='color: red;'>Error: " . $error . "</p>";
  } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username">

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email">

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validation (important to prevent security vulnerabilities)
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
    $errors[] = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters long.";
  }

  // Hash the password before storing it in the database
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // Database insertion
  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)");
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->execute();

    // Success message
    $success_message = "Registration successful! Please log in.";

  } catch (PDOException $e) {
    // Error message
    $error_message = "Error: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <?php if (!empty($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <?php if (!empty($success_message)) { ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>">

    <br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>">

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>">

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>

