
<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to handle login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, set session variables
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_id'] = $row['id']; // Assuming you have an 'id' column in your users table
        return true; // Login successful
    } else {
        return false; // Login failed
    }

    $conn->close();
}

// Example Usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Redirect to a secure page or display a welcome message
        header("Location: secure_page.php"); // Replace with your secure page
        exit();
    } else {
        // Display an error message
        echo "<p style='color: red;'>Invalid username or password.</p>";
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h1>Login</h1>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$login_username = $_POST['username'];
$login_password = $_POST['password'];

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate
  $sql = "SELECT id, username, password FROM users WHERE username = '$login_username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, verify the password
    $user_data = $result->fetch_assoc();
    if (password_verify($login_password, $user_data['password'])) {
      // Password is correct, user is authenticated
      // Set a session variable to indicate the user is logged in
      session_start();
      $_SESSION['user_id'] = $user_data['id'];
      $_SESSION['username'] = $user_data['username'];
      echo "Login successful! You are now logged in.";
      // Redirect to a protected page or home page
      header("Location: /protected_page.php"); // Replace with your protected page URL
      exit();
    } else {
      echo "Incorrect password.";
    }
  } else {
    echo "User not found.";
  }

  $conn->close();
} else {
  // If the form wasn't submitted via POST, redirect to the login page
  header("Location: login.php");
  exit();
}

?>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data (from the login form)
$username = $_POST['username'];
$password = $_POST['password'];

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  // Check if the username exists
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password']; // Retrieve the hashed password

    // Compare the entered password with the stored hashed password
    if (password_verify($password, $hashed_password)) {
      // Password matches! Login successful.
      // Set session variables to track the user's login status and user ID
      session_start();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;

      // Redirect to a secure page or display a success message
      header("Location: secured_page.php"); // Replace with your secure page URL
      exit();
    } else {
      // Incorrect password
      echo "Incorrect password.";
    }
  } else {
    // Username does not exist
    echo "Username does not exist.";
  }

  $conn->close(); // Close the database connection

} else {
  // If the form wasn't submitted via POST, redirect to the login form
  header("Location: login_form.php");
  exit();
}
?>


    // During user registration:
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // Then store $hashed_password in the database
    
* **`password_verify()`:** This function is designed to compare a plaintext password with a hashed password, returning `true` if they match.
* **Error Handling:** Includes a basic check for database connection errors.  More robust error handling would involve logging errors and providing more user-friendly messages.
* **Session Management:**
    * `session_start();`  Starts a PHP session. Sessions allow you to maintain user state across multiple requests.
    * `$_SESSION['user_id'] = $row['id'];` and `$_SESSION['username'] = $username;` Store the user's ID and username in the session. This lets you identify the user on subsequent pages.
* **Clearer Logic and Comments:** The code is well-commented and has a more logical flow.
* **Input Validation (Important - not implemented here for brevity):**  **Crucially, you *must* add input validation to your login form to prevent security vulnerabilities (e.g., SQL injection, XSS).**  This involves validating:
    * The username and password fields against expected formats and lengths.
    * Preventing special characters from being submitted (which could be used in SQL injection).
    * Consider using prepared statements (using PDO or MySQLi) for even stronger protection.
* **`exit()` after redirects:**  Ensures that the script stops executing after redirecting to prevent any further code from running, which could lead to unexpected behavior.
* **Secure Page:** The `header("Location: secured_page.php");` directs the user to a secure page once they have successfully logged in.
* **`login_form.php`:**  This script should contain the HTML form to collect the username and password.  You need to create this file.
* **Database Structure (important):**  This assumes a basic `users` table with columns `id`, `username`, and `password`.  Your database structure may be different.

**How to use this code:**

1. **Create the `users` table:**  Make sure your database has a `users` table with the specified columns.
2. **Register users:**  Implement user registration functionality.  During registration, use `password_hash()` to store a *hashed* password in the `password` column.
3. **Create `login_form.php`:** Create an HTML form to collect the username and password.  This form should have the following input fields:
   tml
   <form action="login.php" method="post">
     <label for="username">Username:</label>
     <input type="text" id="username" name="username" required><br><br>

     <label for="password">Password:</label>
     <input type="password" id="password" name="password" required><br><br>

     <button type="submit">Login</button>
   </form>
   
4. **Place the PHP code:** Save the PHP code as `login.php` in your web server's document root.
5. **Adjust Database Credentials:**  Replace `"your_username"`, `"your_password"`, and `"your_database"` with your actual database credentials.
6. **Secure `secured_page.php`:** Create a `secured_page.php` (or whatever you named it) to redirect to after successful login.  This page should be protected, so only logged-in users can access it.

**Example `secured_page.php`**



<?php

/**
 * User Login Function
 *
 * This function handles user login using a simple file-based authentication.
 *  **WARNING:** This is a VERY basic example for demonstration purposes ONLY. 
 *  Do NOT use this in a production environment due to security vulnerabilities.
 *  Use a proper database and robust authentication methods.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function login(string $username, string $password)
{
    // Check if the username and password are provided
    if (empty($username) || empty($password)) {
        return false; // Invalid input
    }

    // Load user data from a file (replace with database access in a real application)
    $user_data = file_get_contents('users.txt');

    if ($user_data === false) {
        return false; // Could not read the file
    }


    // Parse user data (assuming a simple format: username:password)
    $users = explode(',', $user_data);  // Split data if multiple users stored in file

    foreach ($users as $user) {
        $parts = explode(':', $user);
        if (count($parts) == 2) {
            $username_in_file = trim($parts[0]);
            $password_in_file = trim($parts[1]);

            // Compare passwords (case-sensitive)
            if ($username === $username_in_file && $password === $password_in_file) {
                return true; // Login successful
            }
        }
    }

    return false; // Login failed
}


// Example Usage (for demonstration only - NEVER do this in a production environment)
// Create a users.txt file for testing (e.g., username:password)
//   user1:password123
//   user2:secret

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (login($username, $password)) {
        echo "Login successful! You are now logged in.";
    } else {
        echo "Login failed.  Incorrect username or password.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Simple Login Example</title>
</head>
<body>

    <h1>Simple Login</h1>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username">
        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php
//  This is a very simplified example and assumes you have the
//  bcrypt extension installed and configured correctly in your PHP environment.

//  In a real application, you would use a proper database.  This is
//  for demonstration only.

function hashPassword(string $password): string {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword(string $password, string $hashedPassword): bool {
    return password_verify($password, $hashedPassword);
}

// Example Usage (for demonstration)
$password = "mySecretPassword";
$hashed = hashPassword($password);
echo "Hashed Password: " . $hashed . "
";

if (verifyPassword("mySecretPassword", $hashed)) {
    echo "Password matches!
";
} else {
    echo "Password does not match.
";
}
?>


<?php

// Database Credentials (Replace with your actual values!)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input - IMPORTANT for security
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password must be filled.";
  } elseif (filter_var($username, FILTER_VALIDATE_EMAIL) === false) { // Basic email validation
    $error_message = "Invalid Username (Please enter a valid email address).";
  } else {
    // Attempt to authenticate the user
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$username'"); // Use email for authentication
    if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row["password"])) { // Use password_verify for secure comparison
          // Successful login - Set session variables
          session_start();
          $_SESSION["username"] = $username;
          $_SESSION["user_id"] = $row["id"]; // Store user ID (good practice)
          header("Location: welcome.php"); // Redirect to welcome page
          exit(); // Important: Stop further execution
        } else {
          $error_message = "Incorrect password.";
        }
      }
    } else {
      $error_message = "Query error.";
    }
  }
} else {
  // If form was not submitted, display the login form
  $error_message = ""; // Clear any previous errors
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (!empty($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    Username:
    <input type="email" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" />
    <br />
    Password:
    <input type="password" name="password" />
    <br />
    <button type="submit">Login</button>
  </form>

  <br />
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user information
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";  //Sanitize input here
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, set session variables
    $user_data = $result->fetch_assoc();
    $_SESSION['user_id'] = $user_data['id'];
    $_SESSION['username'] = $user_data['username'];
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}


// Example Usage:
// 1. Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // 2. Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // 3. Validate input (VERY IMPORTANT - Protect against SQL injection)
  if (empty($username) || empty($password)) {
    $error[] = "Username and password cannot be empty.";
  }

  // 4. Call the login function
  $login_result = loginUser($username, $password);

  // 5. Handle the result
  if ($login_result) {
    // Redirect to a protected page
    header("Location: /protected_page.php"); // Replace with the URL of your protected page
    exit();
  } else {
    $error[] = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <?php
    if (!empty($error)) {
      echo "<div style='color: red;'>Error: " . implode(", ", $error) . "</div>";
    }
  ?>

</body>
</html>


<?php
session_start();

/**
 * Handles user login functionality.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  //  In a real application, you would check against a database.
  //  This is a simplified example for demonstration.
  $validUsers = [
    'john.doe' => 'password123',
    'jane.smith' => 'secretpass',
  ];

  if (isset($validUsers[$username])) {
    if ($validUsers[$username] === $password) {
      //  Login successful
      $_SESSION['username'] = $username; // Store username in session
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }
}

// Example Usage (demonstration)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful! You are now logged in.";
    // Redirect to a secure page or display a welcome message.
    header("Location: /welcome.php"); // Replace /welcome.php with your welcome page
    exit();
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

  <h2>User Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" placeholder="john.doe" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

/**
 * This function handles user login.
 *
 * @param string $username The username to attempt login with.
 * @param string $password The password to attempt login with.
 * @return int|string Returns the user's ID if login is successful,
 *                    or an error message string if login fails.
 */
function loginUser(string $username, string $password) {
  // Replace this with your actual database connection and authentication logic.
  // This is a placeholder for demonstration purposes.

  // Simulate a user database (in a real application, you'd use a database query)
  $users = [
    'john.doe' => 'secretPassword123',
    'jane.smith' => 'anotherPassword',
    'admin' => 'adminPassword'
  ];

  // Convert username and password to lowercase for case-insensitive comparison
  $username = strtolower($username);
  $password = strtolower($password);

  if (isset($users[$username])) {
    // Verify the password
    if ($users[$username] === $password) {
      // Login successful - Get user ID (assuming user ID is the username)
      return $username; // Return the username (or a user ID if you have one)
    } else {
      return "Incorrect password for " . $username;
    }
  } else {
    return "User " . $username . " not found.";
  }
}


// Example Usage:
$username = $_POST['username']; // Get username from form submission
$password = $_POST['password']; // Get password from form submission


if (isset($username) && isset($password)) {
  $loginResult = loginUser($username, $password);

  if (is_int($loginResult)) {
    // Login successful - Redirect or display the user's information
    echo "Login successful! User ID: " . $loginResult;
    // You might redirect here: header("Location: user_dashboard.php?id=" . $loginResult);
  } else {
    // Login failed - Display the error message
    echo "<p style='color:red'>" . $loginResult . "</p>";
  }
} else {
  //  Handle the case where username or password are not submitted.  This is good practice.
  echo "<p>Please enter your username and password.</p>";
}

?>


function loginUser(string $username, string $password) {
  // ... (previous code) ...

  if (isset($users[$username])) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    if ($hashedPassword === $users[$username]) {
      return $username;
    } else {
      return "Incorrect password for " . $username;
    }
  } else {
    return "User " . $username . " not found.";
  }
}


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle the login process
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user data
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found
    $user_data = $result->fetch_assoc();
    $hashed_password = $user_data['password']; // Get the stored hash

    // Verify password (using password_verify) - This is the secure way
    if (password_verify($password, $hashed_password)) {
      // Password is correct!
      // Set session variables to indicate successful login
      $_SESSION['user_id'] = $user_data['id'];
      $_SESSION['username'] = $username;

      return true; // Login successful
    } else {
      // Password mismatch
      return false; // Login failed
    }
  } else {
    // User not found
    return false; // Login failed
  }

  // Close the connection
  $conn->close();
}


// Example usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (basic validation - improve for production)
  if (empty($username) || empty($password)) {
    echo "Username and Password cannot be empty.";
  } else {
    // Call the loginUser function
    if (loginUser($username, $password)) {
      // Login successful - Redirect to the desired page
      header("Location: welcome.php"); // Replace 'welcome.php' with your welcome page
      exit();
    } else {
      // Login failed
      echo "Invalid username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");

  // Bind the username parameter
  $stmt->bind_param("s", $username);

  // Execute the query
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password']; // Fetch the hashed password
    // Compare the provided password with the hashed password
    if (password_verify($password, $hashed_password)) {
      // Password matches!  Set session variables.
      session_start();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;
      return true; // Login successful
    } else {
      // Password does not match
      $stmt->close();
      return false;
    }
  } else {
    // No user found
    $stmt->close();
    return false;
  }
}


// Example usage (simulated form submission - replace with your actual form handling)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a secure page or display a welcome message
    header("Location: secure_page.php"); // Redirect to your secure page
    exit(); // Important to stop further execution after redirection
  } else {
    // Login failed - Display error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

?>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

/**
 * Logs in a user based on username and password.
 *
 * @param string $username The username to authenticate.
 * @param string $password The password.
 * @return int|false User ID if successful, false otherwise.
 */
function login(string $username, string $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id FROM users WHERE username = ? AND password = ?;";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $user_id = $row["id"];
      return $user_id;
    } else {
      return false; // User not found
    }
  } else {
    // Handle query error
    echo "Query failed: " . $conn->error;
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (for testing - don't use directly in your application)
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    echo "Username and password cannot be empty.";
  } else {
    // Call the login function
    $user_id = login($username, $password);

    if ($user_id) {
      // User logged in successfully
      echo "User ID: " . $user_id . "<br>";
      // You can redirect the user to a secure area, etc.
    } else {
      // User login failed
      echo "Invalid username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" placeholder="Enter username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (very basic example, improve this for production)
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
      // User found, set session variables
      session_start(); // Start the session
      $user_row = $result->fetch_assoc();
      $_SESSION["username"] = $user_row["username"];
      $_SESSION["user_id"] = $user_row["id"]; // Assuming you have an id column in your users table
      // You might want to add more user data to the session if needed
      echo "Login successful!  Redirecting...";
      header("Location: user_dashboard.php"); // Redirect to a secure page
      exit(); // Important: Stop further script execution
    } else {
      $error = "Invalid username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" placeholder="Enter your username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter your password"><br><br>
    <input type="submit" value="Login">
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

    // Get username and password from the form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate input (IMPORTANT - Always validate user input!)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Hash the password (using password_hash - preferred method)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query the database to check credentials
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);

        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Check if the hashed password matches
                if (password_verify($password, $user["password"])) {
                    // Login successful
                    session_start();
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["username"] = $user["username"];
                    header("Location: welcome.php"); // Redirect to a welcome page
                    exit(); // Important: Stop further execution
                } else {
                    $error = "Incorrect password.";
                }
            } else {
                $error = "Username not found.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h1>Login</h1>

<?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php } ?>

<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" <?php if (isset($username)) echo 'value="'. $username . '"'; ?> />
    <br />
    Password: <input type="password" name="password" />
    <br />
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials (replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get login details from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    // Check if the username exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row["password"])) {
            // If passwords match, set session variables
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = $row["id"]; // Store the user ID as well for more robust security
            echo "Login successful! <a href=''>Continue to Dashboard</a>";
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<form action="" method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>

<br>
<a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } else {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // Query to authenticate the user
      $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
      $result = $conn->query($sql);

      if ($result->num_rows == 1) {
        // User found, set session variables
        session_start();
        $_SESSION["username"] = $username;
        $result->fetch_assoc(); // Fetch the row data
        $error = "";  // Clear any previous errors
        // Redirect to a secure page or display a welcome message
        header("Location: welcome.php"); // Redirect to a welcome page
        exit(); // Stop further execution
      } else {
        $error = "Incorrect username or password.";
      }
    }
  }
}

// Display the login form if no form was submitted or if there's an error
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["SCRIPT_NAME"]; ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Your Username" value="<?php if (isset($username)) echo $username; ?>">
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Your Password">
    <br><br>

    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here</a>

</body>
</html>


<?php

// Database credentials
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Basic validation - enhance for production)
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } else {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // Use a prepared statement to prevent SQL injection
      $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
      $stmt = $conn->prepare($sql);

      if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("ss", $username, $password);

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
          // User found, set session variables
          session_start();
          $_SESSION["username"] = $username;
          $result->fetch_assoc(); // Fetch the row data
          $error = "";  // Clear any previous errors
          header("Location: welcome.php");
          exit();
        } else {
          $error = "Incorrect username or password.";
        }

        // Close the statement
        $stmt->close();
      } else {
        $error = "Error preparing statement.";
      }
    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["SCRIPT_NAME"]; ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Your Username">
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Your Password">
    <br><br>

    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here</a>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to authenticate the user
function loginUser($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // 3. Bind parameters
  $stmt->bind_param("ss", $username, $password);

  // 4. Execute the query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  // 5. Get the result
  $result = $stmt->get_result();

  // 6. Check if a user was found
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // 7.  Create a session
    session_start();  // Start the session
    $_SESSION['user_id'] = $row['id']; // Store user ID in session
    $_SESSION['username'] = $username; // Store username in session (optional)

    //  You might also want to store other user data in the session.

    // 8.  Success!  Return true
    return true;
  } else {
    // 9. User not found
    return false;
  }

  // 10. Close the statement
  $stmt->close();

  // 11. Close the connection (not always necessary, but good practice)
  $conn->close();
}


// Example usage (handling the login form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // User successfully logged in
    header("Location: welcome.php"); // Redirect to a welcome page
    exit();
  } else {
    // User login failed
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

  <?php if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
  } ?>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Form data
$username = $_POST["username"];
$password = $_POST["password"];

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to authenticate the user
$sql = "SELECT id, username, password FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User exists, fetch the password hash
    $row = $result->fetch_assoc();
    $hashed_password = $row["password"]; // Assuming password is stored as a hash

    // Verify the password
    if (password_verify($password, $hashed_password)) {
        // Password matches, user is authenticated
        // Set a session variable to store the user's ID
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;

        echo "Login successful!";  // Or redirect to your dashboard
        // You can also set a flag to show a success message on the login form
        // e.g., echo "<p style='color:green;'>Login successful!</p>";

    } else {
        // Password does not match
        echo "Incorrect password!";
    }
} else {
    // User does not exist
    echo "User not found!";
}

$conn->close();

?>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Form data
$username = $_POST["username"];
$password = $_POST["password"];

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query
$sql = "SELECT id, username, password FROM users WHERE username = ?";  // Use a placeholder
$stmt = $conn->prepare($sql);

// Bind the parameters
$stmt->bind_param("s", $username); // "s" indicates a string

// Execute the query
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User exists, fetch the password hash
    $row = $result->fetch_assoc();
    $hashed_password = $row["password"];

    // Verify the password
    if (password_verify($password, $hashed_password)) {
        // Password matches, user is authenticated
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;

        echo "Login successful!";
    } else {
        echo "Incorrect password!";
    }
} else {
    echo "User not found!";
}

$stmt->close();
$conn->close();

?>


<?php

// Database Credentials (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check for username and password
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, verify password
    $user_data = $result->fetch_assoc();
    if (password_verify($password, $user_data['password'])) {
      // Password matches, login successful
      $_SESSION['user_id'] = $user_data['id'];
      $_SESSION['username'] = $user_data['username'];
      return true;
    } else {
      // Password does not match
      return false;
    }
  } else {
    // User not found
    return false;
  }
}

// --- Example Usage (Illustrative - Not complete login form) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a welcome page or desired location
    header("Location: welcome.php"); // Replace welcome.php with your desired page
    exit();
  } else {
    // Login failed, display an error message
    $error = "Invalid username or password.";
  }
}

// --- Login Form (Simplified for Demonstration) ---
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php endif; ?>

  <form method="post" action="">
    Username: <input type="text" name="username" value="<?php if(isset($username)) echo $username; ?>">
    <br><br>
    Password: <input type="password" name="password" value="<?php if(isset($password)) echo $password; ?>">
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Not registered? Register here.</a> <!-- Replace with your register page -->

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user information
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verify password
    if (password_verify($password, $user['password'])) {
      // Login successful
      $_SESSION['loggedin'] = true;
      $_SESSION['id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      return true;
    } else {
      // Password mismatch
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}


// Example usage (HTML form for login)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a protected page
    header("Location: protected.php"); // Replace 'protected.php' with your protected page URL
    exit();
  } else {
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p> <!-- Link to registration page (you'll need to create this) -->

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to authenticate the user
function loginUser($username, $password) {
  // 1. Connect to the Database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. SQL Query to Retrieve User
  $query = "SELECT id, username, password FROM users WHERE username = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username); // "s" indicates a string parameter

  if ($stmt->execute() === false) {
    // Handle execution error
    echo "Query execution failed: " . $conn->error;
    $stmt->close();
    $conn->close();
    return false;
  }

  // 3. Get Result
  $result = $stmt->get_result();

  // 4. Check if User Found
  if ($result->num_rows == 0) {
    // User not found
    $stmt->close();
    $conn->close();
    return false;
  }

  // 5. Get User Data
  $user = $result->fetch_assoc(); // Fetch as an associative array
  $stmt->close();
  $conn->close();

  // 6. Verify Password (Using Password Hashing - VERY IMPORTANT!)
  if (password_verify($password, $user['password'])) {
    // Password correct!
    return $user['id']; // Return the user ID if authentication is successful
  } else {
    // Incorrect password
    return false;
  }
}

// Example Usage (Handling Login Form Submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (empty($username) || empty($password)) {
    echo "Username and password fields are required.";
  } else {
    $userId = loginUser($username, $password);

    if ($userId) {
      // Login Successful
      session_start(); // Start the session
      $_SESSION['userId'] = $userId;  // Store the user ID in the session
      echo "Login successful! You are now logged in.";
      // Redirect to a protected page or the homepage
      header("Location: /protected_page.php"); // Replace with your desired URL
      exit();
    } else {
      // Login Failed
      echo "Invalid username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (IMPORTANT - prevent SQL injection)
  $username = trim($username);  // Remove leading/trailing whitespace
  $password = trim($password);

  // Sanitize input (important for security)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $password = filter_var($password, FILTER_SANITIZE_STRING);


  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows > 0) {
    // User found, set session variables
    session_start(); // Start the session (if not already started)
    $_SESSION["username"] = $username;
    echo "Login successful!";
    // Redirect to a secure page or display a welcome message
    header("Location: /welcome.php");  // Replace with your welcome page URL
    exit();
  } else {
    echo "Invalid username or password.";
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
else {
  // If the form wasn't submitted, display the login form
  // You can either include this file or display a simple HTML form
  // For example:
  echo "<form method='post' action='login.php'>";
  echo "Username: <input type='text' name='username'>";
  echo "<br>";
  echo "Password: <input type='password' name='password'>";
  echo "<br>";
  echo "<input type='submit' value='Login'>";
  echo "</form>";
}

?>


<?php

// Database configuration (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the login credentials
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (basic example, improve for production)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Query the database to check for the credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($db_host, $sql);

    if (mysqli_num_rows($result) > 0) {
      // User found, set session variables for authentication
      session_start();
      $_SESSION["username"] = $username;  // Store the username
      // You might want to store other user information here, like user ID, etc.
      // $_SESSION["user_id"] = $user_id;

      // Redirect the user to a secure page (e.g., dashboard)
      header("Location: dashboard.php"); // Replace with your secure page
      exit(); // Important: Stop further script execution
    } else {
      $error = "Invalid username or password.";
    }
  }
}

// If the form hasn't been submitted, display the login form
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" placeholder="Your Username"><br><br>
    Password: <input type="password" name="password" placeholder="Your Password"><br><br>
    <input type="submit" value="Login">
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>  <!-- Link to registration page -->

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$login_username = $_POST["username"];
$login_password = $_POST["password"];

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the query
$sql = "SELECT username, password FROM users WHERE username = '$login_username'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // User not found
    echo "Invalid username or password.";
} else {
    // User found, check password
    $user_data = $result->fetch_assoc();
    if (password_verify($login_password, $user_data["password"])) {
        // Password matches, successful login
        // Set a session variable to store the user's username
        session_start();
        $_SESSION["username"] = $login_username;
        echo "Login successful!  Redirecting...";

        // Redirect to a protected page or your main application page
        header("Location: /protected_page.php"); //  Replace with your protected page
        exit();
    } else {
        // Password does not match
        echo "Invalid username or password.";
    }
}

$conn->close();

?>


<?php
// ... (database configuration and connection)

// Prepare the statement
$stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");

// Bind the parameters
$stmt->bind_param("s", $login_username); // "s" indicates a string

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // User not found
    echo "Invalid username or password.";
} else {
    // User found, check password
    $user_data = $result->fetch_assoc();
    if (password_verify($login_password, $user_data["password"])) {
        // Password matches, successful login
        session_start();
        $_SESSION["username"] = $login_username;
        echo "Login successful!  Redirecting...";

        // Redirect to a protected page
        header("Location: /protected_page.php");
        exit();
    } else {
        // Password does not match
        echo "Invalid username or password.";
    }
}

$stmt->close();
$conn->close();
?>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle user login
function loginUser($username, $password) {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

    // Bind parameters to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result(); // Get result set

        if ($result->num_rows > 0) {
            // User exists, return user data (you can customize this)
            $user_data = $result->fetch_assoc();
            return $user_data;
        } else {
            return false; // User not found
        }
    } else {
        return false; // Query error
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}


// Example usage (Handle login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (basic example - improve for production)
    if (empty($username) || empty($password)) {
        echo "Username and password fields cannot be empty.";
    } else {
        $loginResult = loginUser($username, $password);

        if ($loginResult) {
            // Successful login
            session_start();  // Start a session to store user data
            $_SESSION["username"] = $loginResult["username"]; // Store username in session
            $_SESSION["user_id"] = $loginResult["user_id"]; // Store user_id for more efficient database lookups
            echo "Login successful!  Redirecting...";
            // Redirect to a secure page or dashboard
            header("Location: /dashboard.php"); // Replace with your dashboard URL
            exit();

        } else {
            echo "Invalid username or password.";
        }
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

    <h2>Login</h2>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to fetch user data
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, return user data (you can customize this to return other user details)
    $user = $result->fetch_assoc();
    return $user;
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}

// Example usage (handling form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - see notes below)
  if (empty($username) || empty($password)) {
    echo "Username and password must be filled.";
  } else {
    // Call the loginUser function
    $user = loginUser($username, $password);

    if ($user) {
      // User logged in successfully
      // You can redirect to a welcome page or do something else here
      // echo "Welcome, " . $user['username'];  // Display username (example)
      // For production, you'd typically set a session variable here.
      session_start();
      $_SESSION['username'] = $username;  // Store username in session
      header("Location: welcome.php"); // Redirect to a welcome page
      exit();

    } else {
      // User login failed
      echo "Invalid username or password.";
    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if ($stmt->execute()) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // User found, set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true; // Login successful
      } else {
        return false; // User not found
      }
    } else {
      return false; // Query execution error
    }
  } else {
    return false; // Prepare statement error
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (Demonstration - Replace with actual form handling)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security -  see below)
  if (empty($username) || empty($password)) {
    $error = "Username and Password cannot be empty.";
  } else {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // SQL query to authenticate the user
      $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
      $result = $conn->query($sql);

      if ($result->num_rows == 1) {
        // User found - Set session variables
        session_start();
        $_SESSION["username"] = $username;
        $_SESSION["user_id"] = $result->fetch_assoc()["id"]; // Assuming you have an 'id' column
        $error = "Login successful!";
        // Redirect to a secure page (e.g., dashboard)
        header("Location: dashboard.php"); // Or your desired secure page
        exit();
      } else {
        $error = "Invalid username or password.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <?php if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
  }
  ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" placeholder="Enter username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Crucial for security)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } else {
        // Hash the password (Important for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query the database to check credentials
        $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
        $result = mysqli_query($db_host, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // Verify the password
            if (password_verify($password, $row["password"])) {
                // Successful login
                // Set a session variable to store the user's ID
                session_start();
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $username; // Optionally store username
                
                // Redirect the user to a protected page
                header("Location: protected_page.php"); // Replace with your protected page
                exit(); // Important to stop further script execution
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "Username not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h2>Login</h2>

    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username" <?php if (isset($error_message) && $error_message == "Username not found.") { echo "required"; } ?>><br><br>
        Password: <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="registration.php">Register</a></p>
</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Crucial for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL Query to authenticate the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
      // User found, set session variables
      session_start(); // Start a session (important for maintaining user info)
      $user_row = $result->fetch_assoc();
      $_SESSION["username"] = $user_row["username"];
      $_SESSION["id"] = $user_row["id"]; // Assuming you have an ID column in your users table

      // Redirect to a protected page or display a success message
      header("Location: protected_page.php"); // Replace with your protected page
      exit();

    } else {
      $error_message = "Invalid username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>New user? <a href="register.php">Register here</a></p>

</body>
</html>


   <?php
   // ... (Database connection code)

   if ($result->num_rows == 1) {
     $user_row = $result->fetch_assoc();
     $hashed_password = $user_row["password"];  // Get the hashed password from the database
     if (password_verify($password, $hashed_password)) {
       // Password matches, proceed with login
       // ... (Set session variables)
     } else {
       $error_message = "Invalid password.";
     }
   }
   ?>
   

   * **`password_hash($password, PASSWORD_BCRYPT)`**:  This function generates a secure, salted hash of the user's password before storing it in the database.

4. **Register Form (register.php):** Create a `register.php` file to allow new users to create accounts.  Use `password_hash()` to hash their passwords before storing them in the database.

5. **Protected Page (protected_page.php):**  Create a `protected_page.php` (or whatever you choose) to display content only for logged-in users.  Check if `$_SESSION["username"]` is set before allowing access.

Example `register.php` (Very basic - expand for real-world use):



<?php

/**
 * User Login Function
 *
 * This function handles user login using a simple username/password scheme.
 * **Important:**  This is a basic example for demonstration purposes.  
 *  For production environments, you should use a secure authentication method 
 *  like hashing passwords and using sessions or tokens.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // **IMPORTANT:  In a real application, you should NEVER store passwords in plain text.**
  // This example uses a hardcoded database for simplicity.
  // You should hash passwords using a strong hashing algorithm like bcrypt.

  $validUsers = [
    'john.doe' => 'password123',
    'jane.smith' => 'securePass456',
  ];

  // Check if the username exists
  if (isset($validUsers[$username])) {
    // Verify the password
    if ($validUsers[$username] === $password) {
      // Login successful - set a session variable to indicate logged-in status
      session_start();
      $_SESSION['user_id'] = $username; // Use username as identifier
      $_SESSION['logged_in'] = true;
      return true;
    } else {
      return false; // Incorrect password
    }
  } else {
    return false; // Username not found
  }
}

// Example usage:
// Simulate a login attempt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    echo '<p>Login successful! You are now logged in.</p>';
    // Redirect to a protected page or display a welcome message
    header("Location: welcome.php"); // Redirect to welcome page
    exit();
  } else {
    echo '<p>Invalid username or password.</p>';
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle login
function loginUser($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. SQL Query to verify credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  // 3. Check if any rows were returned
  if ($result->num_rows > 0) {
    // User found - Get the data
    $user = $result->fetch_assoc();
    // 4. Store user session information
    $_SESSION['user_id'] = $user['id']; // Assuming 'id' is the user's ID
    $_SESSION['username'] = $user['username'];

    return true; // Login successful
  } else {
    return false; // Login failed
  }

  // Close connection
  $conn->close();
}


// Example Usage (Demonstration - Not for Production)
if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
  $username = $_POST['login_username'];
  $password = $_POST['login_password'];

  if (loginUser($username, $password)) {
    // Redirect to a protected page or display a success message
    header("Location: protected_page.php"); // Replace with your protected page
    exit(); // Important: Stop further script execution
  } else {
    // Display error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="login_username" placeholder="Enter your username"><br><br>
    Password: <input type="password" name="login_password" placeholder="Enter your password"><br><br>
    <input type="submit" name="login_submit" value="Login">
  </form>

</body>
</html>


<?php

// Example using prepared statements for secure SQL queries
function loginUser($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL query using prepared statements
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters

  // Execute the query
  $result = $stmt->execute();

  if ($result) {
    $user = $stmt->fetch_assoc();
    // 4. Store user session information
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    return true;
  } else {
    return false;
  }

  // Close connection
  $conn->close();
}

// Example usage (same as before)
if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
  $username = $_POST['login_username'];
  $password = $_POST['login_password'];

  if (loginUser($username, $password)) {
    header("Location: protected_page.php");
    exit();
  } else {
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}
?>


<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the inputs (Basic example - enhance this for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]+$/", $username)) {
    $error = "Invalid username.  Please enter a valid email or alphanumeric username.";
  } else {
    // SQL query to check the credentials
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($db_host, $query);

    if (mysqli_num_rows($result) > 0) {
      // User found, verify the password
      $row = mysqli_fetch_assoc($result);
      if (password_verify($password, $row["password"])) {
        // Password matches, login successful
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;
        // Redirect to a welcome page or desired location
        header("Location: welcome.php"); // Replace with your welcome page URL
        exit(); // Stop further execution
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "User not found.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" placeholder="your_email@example.com" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>
</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login based on provided credentials.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|false  The user ID if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // **Database Connection (Replace with your actual connection details)**
  // This is a placeholder.  You *must* replace this with your database connection.
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

  // **Authentication Logic**
  //  This is where you'd perform the actual authentication.
  //  This example uses a hardcoded user and password for demonstration only.
  //  You should *never* do this in a real application.  Use a secure hashing method.

  $hashedPassword = "hashed_password_here"; //Replace with the hashed password from your database.  This is just a placeholder.

  if ($username == 'admin' && $password == 'password') {
    // Login successful!
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      // Store the user's session ID
      session_start(); // Make sure session is started!
      $_SESSION['userId'] = $user['id'];
      return $user['id'];
    } else {
      return false; // User not found
    }
  } else {
    return false; // Invalid credentials
  }
}

// **Example Usage (for testing)**
//This code is just to demonstrate how to use the function
// in a test environment or during development.

// Simulate a login attempt
$username = 'testuser';
$password = 'testpassword';

$userId = loginUser($username, $password);

if ($userId) {
  echo "Login successful! User ID: " . $userId . "<br>";
  // Redirect to a protected page or perform other actions here.
  // For example:
  // header("Location: protected_page.php");
  // exit();

} else {
  echo "Login failed.  Invalid username or password.<br>";
}

?>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security!)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } elseif (filter_var($username, FILTER_VALIDATE_EMAIL) === false) {
        $error_message = "Invalid email address.";
    } else {
        // Database connection
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to check the credentials
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // User found!  Set session variables.  This is crucial.
            session_start(); // Start the session
            $_SESSION["username"] = $username; // Store the username in the session
            header("Location: welcome.php"); // Redirect to a welcome page
            exit(); // Stop further script execution
        } else {
            $error_message = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>

<h1>User Login</h1>

<?php
if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
}
?>

<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" value="<?php echo isset($username) ? $username : ''; ?>">
    Password: <input type="password" name="password" value="<?php echo isset($password) ? $password : ''; ?>">
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials - IMPORTANT: Replace with your actual credentials!
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";


// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query - Using prepared statements for security
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // Bind parameters to the SQL query - IMPORTANT for security
  $stmt->bind_param("ss", $username, $password);

  // Execute the query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  // Get the result
  $result = $stmt->get_result();

  // Check if any rows were returned
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Successful login
    $_SESSION['user_id'] = $row['id']; // Store user ID in session
    $_SESSION['username'] = $username;
    return true;
  } else {
    // Login failed
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage:

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
  echo "Welcome, " . $_SESSION['username'] . "!";
} else {
  // Check if the form has been submitted
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input (VERY IMPORTANT - Add more validation here)
    if (empty($username) || empty($password)) {
      echo "Username and password cannot be empty.";
    } else {
      // Attempt to login
      if (loginUser($username, $password)) {
        echo "Login successful!";
        // Redirect to a protected page, or display a welcome message
        header("Location: /protected_page.php"); // Replace with your protected page URL
        exit();
      } else {
        echo "Invalid username or password.";
      }
    }
  } else {
    // Display the login form
    echo "<h2>Login</h2>";
    echo "<form method='post'>";
    echo "<label for='username'>Username:</label><br>";
    echo "<input type='text' id='username' name='username'><br>";
    echo "<label for='password'>Password:</label><br>";
    echo "<input type='password' id='password' name='password'><br>";
    echo "<button type='submit'>Login</button>";
    echo "</form>";
  }
}

?>


<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format for username.";
  } else {
    // Database query
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = '" . $conn->real_escape_string($username) . "' AND password = '" . $conn->real_escape_string($password) . "'";

    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error = "Invalid username or password.";
    } else {
      // If the query is successful, set a session variable
      session_start();
      $_SESSION["username"] = $username;
      $error = "Login successful!";
      // Redirect to a secure page (e.g., dashboard)
      header("Location: dashboard.php");  // Replace dashboard.php with your secure page
      exit();
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
  } ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_username = "your_db_username";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve the user's password hash
  $query = "SELECT password_hash, user_id FROM users WHERE username = '$username'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password_hash'];
    $userId = $row['user_id'];

    // Verify the password
    if (password_verify($password, $hashedPassword)) {
      // Authentication successful
      session_start(); // Start a session to store user information

      // Store user information in the session
      $_SESSION['user_id'] = $userId;
      $_SESSION['username'] = $username;
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}

// Example Usage (in your login form HTML)
//  <form method="post" action="login.php">
//   Username: <input type="text" name="username">
//   Password: <input type="password" name="password">
//   <button type="submit">Login</button>
// </form>

// PHP code to handle the form submission
//  <?php
//  if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    $username = $_POST["username"];
//    $password = $_POST["password"];

//    if (loginUser($username, $password)) {
//      // Login successful, redirect to your homepage or desired page
//      header("Location: homepage.php"); // Replace with your homepage URL
//      exit();
//    } else {
//      // Login failed
//      $error = "Invalid username or password.";
//    }
//  }
//?>
// ... (HTML for displaying error message if needed)


// Example of using password_hash() and password_verify() - IMPORTANT!
// Before using this code, you MUST hash the user's password before storing it in the database.
// This example demonstrates the correct way to handle password storage.
// In a real application, you should use a strong hashing algorithm like bcrypt or argon2.

//  $hashedPassword = password_hash("your_password", PASSWORD_DEFAULT);
//  // Store $hashedPassword in the users table.

?>


<?php

// Database Credentials - Replace with your actual credentials!
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input - Basic validation (Stronger validation is recommended in production)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (strlen($username) < 3) {
    $error_message = "Username must be at least 3 characters long.";
  } 
  // Add more validation rules here, like checking for valid characters, password strength, etc.


  // Database connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?"; // Use prepared statements for security

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("ss", $username, $password);

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // User found, set session variables
      session_start();
      $_SESSION["username"] = $username;  // Store the username
      $user_data = $result->fetch_assoc(); // Get user data
      $_SESSION['user_id'] = $user_data['id']; // Store the user ID (optional but recommended)

      // Redirect to a protected page or display a success message
      header("Location: /protected_page.php"); // Replace with your protected page URL
      exit; 
    } else {
      $error_message = "Invalid username or password.";
    }
  } else {
    $error_message = "Error executing query. " . $conn->error;
  }
} else {
  // If the form hasn't been submitted, display the login form
  // This is usually handled by your HTML template.  The code below is just to
  // show what's happening on the server side when the page is first loaded.
  $error_message = ""; 
}

// Close the connection
$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (!empty($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    Password: <input type="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login with username and password verification.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|string Returns the user ID if login is successful, 
 *                   or an error message string if login fails.
 */
function loginUser(string $username, string $password) {
  // **IMPORTANT:  Replace this with your actual database connection and user data.**
  // This is a simplified example and won't work without a database.
  $validUsers = [
    'john.doe' => 'password123',
    'jane.smith' => 'securePass42',
  ];

  // Sanitize input (important for security)
  $username = strtolower($username); // Convert to lowercase for consistency

  if (isset($validUsers[$username])) {
    // Verify password
    if ($validUsers[$username] === $password) {
      // Login successful
      // Generate a session ID (for security - you'd normally use a more robust method)
      $sessionId = bin2hex(random_bytes(32)); // Generate a secure session ID

      // Store session ID in session variable (for simplicity, but proper session management is crucial)
      session_start();
      $_SESSION['userId'] = $username; // Store the username, not the password!
      $_SESSION['sessionId'] = $sessionId; 

      return $username; // Return the username (or user ID if you have one)
    } else {
      return "Incorrect password.";
    }
  } else {
    return "User not found.";
  }
}


// **Example Usage:**

// Get username and password from form submission (example)
// $username = $_POST['username'];
// $password = $_POST['password'];

// if (isset($username) && isset($password)) {
//   $loginResult = loginUser($username, $password);
//   if ($loginResult === 'Incorrect password.') {
//     echo "<p style='color:red;'>Incorrect password.</p>";
//   } elseif ($loginResult === 'User not found.') {
//     echo "<p style='color:red;'>User not found.</p>";
//   } else {
//     echo "<p>Login successful!  User ID: " . $loginResult . "</p>";
//     // Redirect to a secure page after login
//   }
// } else {
//   echo "<p>Please fill in the form.</p>";
// }


?>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

  // Execute the query
  $result = $conn->query($sql);

  // Check if a user was found
  if ($result->num_rows > 0) {
    // User found, you can now process their session or redirect them to a protected area
    // Example:  Store user data in a session
    session_start();
    $user_data = $result->fetch_assoc(); // Fetch the user data as an associative array
    $_SESSION['user_id'] = $user_data['id']; // Assuming 'id' is the user ID column
    $_SESSION['username'] = $user_data['username'];

    // Redirect to a protected page (e.g., the dashboard)
    header("Location: dashboard.php"); // Replace dashboard.php with your protected page
    exit(); // Important to stop further execution

  } else {
    // User not found
    echo "<p>Invalid username or password.</p>";
  }

  $conn->close();
}

// Example usage (Handle the login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Important for security!)
  if (empty($username) || empty($password)) {
    echo "<p>Username and password fields cannot be empty.</p>";
  } else {
    // Call the loginUser function
    loginUser($username, $password);
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" placeholder="Enter your username" required>
    <br><br>
    Password: <input type="password" name="password" placeholder="Enter your password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual values
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameter
  $stmt->bind_param("s", $username);

  // Execute the statement
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Verify the password
      if (password_verify($password, $row["password"])) {
        // Login successful
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        return true;
      } else {
        // Password mismatch
        return false;
      }
    } else {
      // User not found
      return false;
    }
  } else {
    // Error executing the query
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (Demonstration -  This is for testing and should be replaced with your form input)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a protected page
    header("Location: /protected_page.php"); // Replace with your protected page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">

    <button type="submit">Login</button>
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
  // Get the username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (preg_match("/^[a-zA-Z0-9_]+$/", $username) == 0) {
    $error_message = "Username must contain only letters, numbers, and underscores.";
  } else {
    // Query the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query (use prepared statements for security!)
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind the parameters (for security)
      $stmt->bind_param("s", $username, $password);

      // Execute the query
      if ($stmt->execute()) {
        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
          // User found, set session variables
          session_start();
          $_SESSION["username"] = $username;
          $result->fetch_assoc(); // Get the user data
          $stmt->close();

          // Redirect to a secure page or display a success message
          header("Location: secure_page.php"); // Replace with your secure page
          exit();
        } else {
          $error_message = "Incorrect username or password.";
          $stmt->close();
        }
      } else {
        $error_message = "Query execution failed.";
        $stmt->close();
      }
    } else {
      $error_message = "Error preparing statement.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <?php if (!empty($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    Password: <input type="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual credentials!
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ?";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);  // "s" indicates a string parameter

  // Execute the query
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Verify the password
      if (password_verify($password, $row["password"])) {
        // Successful login
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        return true;
      } else {
        // Incorrect password
        return false;
      }
    } else {
      // User not found
      return false;
    }
  } else {
    // Query error
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage:

// 1. Form submission (in your HTML form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to your welcome page or desired location
    header("Location: welcome.php"); // Replace with your welcome page
    exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Session handling
session_start();

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Bind the parameters for security (important to prevent SQL injection)
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // Set session variables for the logged-in user
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      return true; // Login successful
    } else {
      return false; // No user found or incorrect password
    }
  } else {
    return false; // Error executing query
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (Demonstration -  This would typically be within a form submission)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    echo "Login successful! You are now logged in as " . $_SESSION['username'];
    // Redirect to a protected page or display a welcome message
    header("Location: protected_page.php"); // Replace with your protected page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check for the username and password
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the statement
    if ($stmt->execute()) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        // User found, retrieve user data
        $user = $result->fetch_assoc();
        // Set session variables to store the user information
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true; // Login successful
      } else {
        // User not found
        return false;
      }
    } else {
      // Error executing statement
      error_log("Query error: " . $conn->error); // Log the error for debugging
      return false;
    }
  } else {
    // Error preparing statement
    error_log("Statement preparation error");
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (Form Handling - Not a complete form, just demonstrating the login function)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a secure page or display a welcome message
    header("Location: /welcome.php"); // Replace with your welcome page URL
    exit();
  } else {
    // Handle login failure (display error message)
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $stmt = $conn->prepare($sql);

  // Execute the query
  $stmt->execute();

  // Store the result
  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $user['password'])) {
      // Login successful - set session variables
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];

      return true; // Indicate successful login
    } else {
      return false; // Indicate incorrect password
    }
  } else {
    return false; // Indicate user not found
  }

  // Close the database connection
  $stmt->close();
  $conn->close();
}


// Example usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - added basic validation)
  if (empty($username) || empty($password)) {
    echo "<p style='color:red;'>Username and password cannot be empty.</p>";
  } else {
    // Call the loginUser function
    if (loginUser($username, $password)) {
      // Login successful - redirect to the user's dashboard or homepage
      header("Location: dashboard.php");
      exit();
    } else {
      echo "<p style='color:red;'>Invalid username or password.</p>";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
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
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and Password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    // Check if the query returned any results
    if ($result->num_rows > 0) {
      // Get the data from the result
      $user_data = $result->fetch_assoc();

      // Verify the password
      if (password_verify($password, $user_data["password"])) {
        // Successful login - set session variables
        session_start();
        $_SESSION["user_id"] = $user_data["id"];
        $_SESSION["username"] = $username;
        echo "Login successful! <a href='welcome.php'>Welcome, " . $username . "</a>"; // Redirect to a welcome page
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "User not found.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
  } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here</a>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to authenticate user credentials
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $query = "SELECT id, username, password FROM users WHERE username = ?";
  $stmt = $conn->prepare($query);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // Bind the parameters
  $stmt->bind_param("s", $username);  // "s" indicates a string parameter

  // Execute the query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  // Get the result
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    return false; // User not found
  }

  $user = $result->fetch_assoc();
  $stmt->close();

  // Verify password
  if (password_verify($password, $user['password'])) {
    // Successful login
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    return true;
  } else {
    // Incorrect password
    return false;
  }
}

// Example usage (in a form submission, for example)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with the URL of your protected page
    exit();
  } else {
    // Login failed - Display an error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


function insertUser($username, $password) {
  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $query = "INSERT INTO users (username, password) VALUES (?, ?)";
  $stmt = $conn->prepare($query);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  $stmt->bind_param("ss", $username, $hashed_password); // "ss" for string parameters

  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  $stmt->close();
  $conn->close();
}


<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email address.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ? LIMIT 1";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind the parameters
      $stmt->bind_param("ss", $username, $password);

      // Execute the query
      $stmt->execute();

      // Get the result
      $result = $stmt->get_result();

      // Check if a user was found
      if ($result->num_rows == 1) {
        // User found, set session variables
        session_start();
        $user_id = $result->fetch_assoc()["id"];
        $_SESSION["user_id"] = $user_id;
        $_SESSION["username"] = $username;

        // Redirect to a protected page
        header("Location: protected_page.php"); // Replace with your protected page
        exit(); // Important to stop further execution
      } else {
        $error_message = "Invalid username or password.";
      }
    } else {
      $error_message = "Error preparing statement.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" placeholder="Your Email Address">
    Password: <input type="password" name="password" placeholder="Your Password">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Link to registration page -->
</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check for the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found - Fetch data from the result set
    $user = $result->fetch_assoc();

    // Create a session
    session_start();

    // Store user data in the session
    $_SESSION['user_id'] = $user['id'];  // Assuming 'id' is the user's ID column
    $_SESSION['username'] = $user['username']; // Store username as well

    // Redirect to a secure page (e.g., the homepage)
    header("Location: welcome.php");
    exit(); // Important: Stop further script execution
  } else {
    // User not found
    return false;
  }

  $conn->close();
}

// Example Usage (handling login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (very important - see explanation below)
  if (empty($username) || empty($password)) {
    $error = "Username and Password must be filled.";
  } else {
    // Call the loginUser function
    $login_result = loginUser($username, $password);

    if ($login_result === false) {
      $error = "Invalid username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
  } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    Password: <input type="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login using a simple (and potentially insecure) 
 * username/password comparison.
 *
 * **WARNING:** This is a basic example for demonstration purposes only.
 *  For production environments, use a secure authentication system 
 *  like hashing passwords with bcrypt or Argon2, and consider using 
 *  a library or framework for managing user sessions and security.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser(string $username, string $password)
{
    // *** IMPORTANT SECURITY WARNING:  Do NOT store passwords in plain text. ***
    // This is just for demonstration purposes.  A real system would hash the password.

    // ** Replace with your database connection code**
    $db_host = 'localhost';
    $db_name = 'your_database_name';
    $db_user = 'your_database_user';
    $db_password = 'your_database_password';

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input -  This is extremely important to prevent SQL injection!
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    // SQL Query -  Be very careful with this.  Validate inputs before constructing queries.
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Compare passwords -  Again, *never* store passwords in plain text.
        if (password_verify($password, $row['password'])) {
            // Login successful!

            // ***  Set Session (Replace with your preferred session management method) ***
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;

            return true;
        } else {
            // Password mismatch
            return false;
        }
    } else {
        // No user found
        return false;
    }

    // Close connection
    $conn->close();
}


// --- Example Usage ---
// Assuming you have a form that captures the username and password:

// If the login is successful:
// echo "Login successful!  Welcome, " . $_SESSION['username'] . "!";

// If the login fails:
// echo "Login failed. Please check your credentials.";


?>


<?php

// Database credentials (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    // User found, set session variables for authentication
    $row = $result->fetch_assoc();
    $_SESSION["user_id"] = $row["id"];
    $_SESSION["username"] = $row["username"];
    // You might want to store other user information here if needed
    
    // Redirect to a secure page
    header("Location: secure_page.php"); // Replace 'secure_page.php' with the appropriate URL
    exit();
  } else {
    // User not found
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form action="login.php" method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle the login process
function login($username, $password) {
  // Connect to the database
  $conn = new mysqli($username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the query
  $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);  // "s" indicates a string parameter
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if a user with the given username exists
  if ($result->num_rows == 0) {
    return false; // User doesn't exist
  }

  $user = $result->fetch_assoc();
  $stored_password = $user['password'];

  // Verify the password
  if (password_verify($password, $stored_password)) {
    // Password is correct, set session variables
    session_start();
    $_SESSION['username'] = $username;
    return true; // Login successful
  } else {
    return false; // Password incorrect
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage (handling login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (login($username, $password)) {
    // Redirect to a secure page or display a success message
    header("Location: secure_page.php"); // Replace with your secure page
    exit();
  } else {
    // Display an error message
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Link to a registration page -->

</body>
</html>


     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
     // Then, insert $hashed_password into the database.
     

3. **Input Validation and Sanitization:**
   - **ALWAYS** validate and sanitize user input *before* using it in any query or operation. This includes:
     - Checking the data type and format (e.g., ensure the username is alphanumeric).
     - Using `filter_var()` to sanitize data (e.g., `filter_var($username, FILTER_SANITIZE_STRING)`).
     - Escaping any output before displaying it to prevent XSS vulnerabilities.

4. **CSRF Protection:** Implement Cross-Site Request Forgery (CSRF) protection. This is a common web security vulnerability that involves attackers tricking a user's browser into submitting malicious requests to your website without their knowledge.

5. **Session Management:**
   -  Set an expiration time for session variables to prevent session hijacking.
   -  Implement session regeneration after login to further enhance security.

6. **Logging:** Implement logging to track login attempts (successful and failed) for security auditing and troubleshooting.

7. **Rate Limiting:**  Implement rate limiting to prevent brute-force attacks (e.g., limiting the number of login attempts from a single IP address).

8. **Testing:** Thoroughly test your login functionality, including:
   - Successful login
   - Failed login (invalid credentials)
   - Brute-force attack attempts
   - CSRF attacks

9. **HTTPS:**  Always use HTTPS to encrypt the communication between the user's browser and your server.

**Example of PDO (for modern development):**



<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement (important for security - prevents SQL injection)
  $stmt = $conn->prepare($query);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // User found, set session variables
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];
      return true; // Login successful
    } else {
      return false; // User not found
    }

  } else {
    return false; // Query error
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}



// Example usage:
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (VERY IMPORTANT - add more robust validation here)
  if (empty($username) || empty($password)) {
    echo "Username and password must be filled.";
  } else {
    // Attempt to login
    if (loginUser($username, $password)) {
      // Redirect to a secure page or display a welcome message
      header("Location: /welcome.php");  // Adjust path as needed
      exit();
    } else {
      echo "Invalid username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

/**
 * Logs in a user based on their username and password.
 *
 * @param string $username The username.
 * @param string $password The password.
 * @return int|false User ID if successful, false if not.
 */
function loginUser(string $username, string $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username FROM users WHERE username = ? AND password = ?;";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // 'ss' indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      $userId = $user['id'];
      return $userId;
    } else {
      return false; // User not found
    }
  } else {
    // Handle query errors
    echo "Query failed: " . $conn->error;
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// --- Example Usage ---
// Get username and password from the form (or wherever you get them)
$username = $_POST['username'];
$password = $_POST['password'];

// Check if the login form was submitted
if (isset($_POST['login'])) {
  // Call the login function
  $userId = loginUser($username, $password);

  // Check if the login was successful
  if ($userId) {
    // Redirect the user to a logged-in page, or set a session variable
    session_start();
    $_SESSION['userId'] = $userId;
    $_SESSION['username'] = $username;
    echo "Login successful! You are logged in as " . $username;
    // Redirect to a logged-in page
    header("Location: /loggedin.php"); // Replace with your logged-in page URL
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>

<!-- HTML Form (for simplicity, placed here) -->
<form method="post" action="">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username">
  <br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password">
  <br>
  <button type="submit" name="login">Login</button>
</form>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - discussed below)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password are required.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      $error_message = "Connection failed: " . $conn->connect_error;
    } else {
      // SQL query to check username and password
      $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
      $result = $conn->query($sql);

      if ($result->num_rows == 1) {
        // User found, check password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
          // Password matches, login successful
          session_start();
          // Store user information in session
          $_SESSION["user_id"] = $row["id"];
          $_SESSION["username"] = $username;

          // Redirect to a protected page (e.g., dashboard)
          header("Location: dashboard.php"); // Replace with your protected page
          exit();
        } else {
          // Password doesn't match
          $error_message = "Incorrect password.";
        }
      } else {
        // User not found
        $error_message = "User not found.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (!empty($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
    Password: <input type="password" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p> <!-- Link to your registration page -->

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Login form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (basic example - improve this for production)
  if (empty($username) || empty($password)) {
    $error = "Username and Password are required.";
  } else {
    // Database query
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'"; // Use prepared statements for security!
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error = "Invalid username or password.";
    }

    if ($result->num_rows > 0) {
      // User found, set session variables
      session_start();
      $_SESSION["username"] = $username;
      // Redirect to a secure page or display a welcome message
      header("Location: welcome.php"); // Assuming you have a welcome.php page
      exit();
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (!empty($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" />
    Password: <input type="password" name="password" />
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php
session_start();

/**
 * Logs in a user based on username and password.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool True if login successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // Simulate a database or user store.  In a real application,
  // this would connect to a database to retrieve user information.
  $users = [
    'john.doe' => 'password123',
    'jane.smith' => 'securePass',
  ];

  // Check if the username exists
  if (isset($users[$username])) {
    // Verify the password
    if (password_verify($password, $users[$username])) {
      // Login successful
      $_SESSION['username'] = $username;  // Store username in session
      return true;
    } else {
      // Password does not match
      return false;
    }
  } else {
    // Username does not exist
    return false;
  }
}

/**
 *  Demonstration/Testing Function
 *  This function will show the login form and process the login.
 */
function showLogin() {
    // Start the session
    session_start();

    //Check if user is already logged in
    if (isset($_SESSION['username'])) {
        header("Location: /success.php"); // Redirect to a success page
        exit();
    }

    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<title>Login</title>";
    echo "</head>";
    echo "<body>";
    echo "<h1>Login</h1>";

    // Check if there's an error message
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>" . htmlspecialchars($_SESSION['error']) . "</p>";
        unset($_SESSION['error']); // Clear error message after displaying
    }

    echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
    echo "<label for='username'>Username:</label><br>";
    echo "<input type='text' id='username' name='username' required><br><br>";
    echo "<label for='password'>Password:</label><br>";
    echo "<input type='password' id='password' name='password' required><br><br>";
    echo "<button type='submit'>Login</button>";
    echo "</form>";
}


//Example Usage (Demonstration)
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        echo "<p>Login successful!</p>";
        // Redirect to a protected page or homepage
        header("Location: /home.php");
        exit();
    } else {
        // Handle login failure
        $_SESSION['error'] = "Invalid username or password.";
        showLogin();
    }
} else {
    // Show the login form initially
    showLogin();
}
?>


<?php

// Database credentials - REPLACE WITH YOUR ACTUAL CREDENTIALS!
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL Query to check login
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User exists, retrieve data
    $row = $result->fetch_assoc();
    // Set session variables to track the user
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];

    // Redirect to a protected page or display a welcome message
    return true; // Indicate successful login
  } else {
    // User does not exist
    return false; // Indicate failed login
  }

  $conn->close();
}

// Example usage (handling the login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a protected page or display a welcome message
    header("Location: protected_page.php"); // Example: Redirect to a protected page
    exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

//  Example login form (for testing)
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Hash the password for security (using password_hash)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database to check credentials
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = mysqli_query($db_host, $sql);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      // Verify the password
      if (password_verify($password, $row["password"])) {
        // If passwords match, set session variables
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;
        echo "Login successful! <a href='welcome.php'>Welcome, " . $username . "</a>"; // Redirect to a welcome page
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "Username not found.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
  }
  ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

/**
 * Login function to authenticate users.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|false  The user ID if login is successful, or false if failed.
 */
function login(string $username, string $password) {
  // **IMPORTANT: NEVER STORE PASSWORDS IN PLAIN TEXT!**
  // This example demonstrates the *concept* of authentication.
  // In a real application, you should hash passwords using a secure algorithm
  // like bcrypt or Argon2.

  // **Simulated User Database (Replace with your actual database connection)**
  $users = [
    'john.doe' => 'password123',
    'jane.smith' => 'securepass',
    'admin' => 'adminpassword'
  ];

  // Check if the username exists in the database
  if (isset($users[$username])) {
    // Compare the provided password with the stored hashed password
    if (password_verify($password, $users[$username])) {  // Use password_verify for secure comparison
      // Login successful
      // Generate a session token (for security)
      $session_token = bin2hex(random_bytes(32));

      // Store the session token in a session variable.
      // **This is a simplified example. In a production environment,
      // use a more robust session management system like PHP's built-in
      // session functions or a dedicated session library.**
      $_SESSION['user_id'] = $username; // Store the username as the user_id.  Better to store a unique ID.
      $_SESSION['session_token'] = $session_token;


      return $username; // Return the username (or user ID)
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // Username does not exist
    return false;
  }
}


// **Example Usage (For testing)**
//  1.  Get username and password from form submission (example)

//  if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    $username = $_POST["username"];
//    $password = $_POST["password"];
//    $loginResult = login($username, $password);

//    if ($loginResult) {
//      echo "Login successful! User ID: " . $loginResult;
//    } else {
//      echo "Login failed.  Invalid username or password.";
//    }
//  }

//  2.  A simple example for demonstration:
//  $user_id = login('john.doe', 'password123');
//  if ($user_id) {
//      echo "User login successful. User ID: " . $user_id;
//  } else {
//      echo "Login failed.";
//  }

?>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_SANITIZE_STRING)) {
    $error_message = "Invalid username. Please use only alphanumeric characters.";
  } elseif (!filter_var($password, FILTER_SANITIZE_STRING)) {
    $error_message = "Invalid password.  Please use only alphanumeric characters.";
  }

  // Database connection
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Query to check username and password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  // Check if the query returned any results
  if ($result->num_rows > 0) {
    // User found, set session variables
    session_start();
    $_SESSION["username"] = $username; // Store username in session
    $conn->close();

    // Redirect to a protected page or display a success message
    header("Location: secure_page.php"); // Redirect to a secure page.
    exit();

  } else {
    // User not found
    $conn->close();
    $error_message = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p> <!-- Link to registration page -->

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to validate user input
function validateInput($username, $password) {
  // Basic validation - adjust as needed for your security requirements
  return true; // Always true for this example - *NEVER* use this in a production environment!
}

// Function to handle user login
function loginUser($username, $password) {
  // 1. Check if the username and password are provided
  if (empty($username) || empty($password)) {
    return "Username and password are required.";
  }

  // 2. Database connection (using PDO for better security)
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Throw exceptions on errors
  } catch (PDOException $e) {
    return "Error connecting to the database: " . $e->getMessage();
  }

  // 3. SQL query (SELECT query - adjust the column names if necessary)
  $sql = "SELECT * FROM users WHERE username = :username AND password = :password";  // Use parameterized query

  // 4. Prepare and execute the query
  try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // 5. Check if a row was returned (user found)
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      // User found - set session variables
      $_SESSION['username'] = $username; // Store username in session
      return "Login successful!";
    } else {
      // User not found
      return "Invalid username or password.";
    }
  } catch (PDOException $e) {
    return "Error executing query: " . $e->getMessage();
  }
}


// --- Example Usage (This is just for demonstration) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Call the loginUser function
  $loginResult = loginUser($username, $password);

  // Display the result
  echo "<p>" . $loginResult . "</p>";

  // Redirect to a different page after login
  if ($loginResult == "Login successful!") {
    header("Location: welcome.php"); // Replace 'welcome.php' with your desired page
    exit();
  }
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php
session_start();

/**
 * Handles user login.
 *
 * @param string $username The username to authenticate.
 * @param string $password The password to authenticate.
 * @return bool True if login was successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // In a real application, you would store the password securely (hashed)
  // and compare the hashed password with the provided password.
  // This example demonstrates a simple (and insecure) comparison for demonstration purposes.

  // Hardcoded credentials (FOR DEMO ONLY - NEVER DO THIS IN PRODUCTION!)
  $validUsers = [
    'john.doe' => 'password123',
    'jane.smith' => 'securePass42'
  ];

  if (isset($validUsers[$username])) {
    if ($validUsers[$username] == $password) {
      // Login successful
      $_SESSION['username'] = $username;
      return true;
    } else {
      return false; // Incorrect password
    }
  } else {
    return false; // User not found
  }
}

// --- Example Usage (Demonstration) ---

// Check if the user is already logged in
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Redirect to a protected page
    header("Location: /protected_page.php");  // Replace with your actual protected page
    exit(); // Important to stop further execution after redirection
  } else {
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" placeholder="john.doe" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" name="login" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate inputs (Important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and Password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]+$/", $username)) {
    $error = "Invalid username. Please use a valid email or alphanumeric characters only.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check the login
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    // Check if the query was successful and if a user was found
    if ($result->num_rows > 0) {
      // User found, set session variables and redirect
      session_start();
      $_SESSION["username"] = $username; // Store the username in the session
      // Optionally store other user data here, like roles/permissions
      // if you have a more complex system.

      // Redirect the user to the home page or protected area
      header("Location: home.php"); // Replace 'home.php' with your desired page
      exit();
    } else {
      $error = "Incorrect username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
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
    // Get username and password from form input
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important to prevent SQL injection and other issues)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Connect to the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query (using prepared statements -  VERY IMPORTANT!)
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

        // Bind the values to the query
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings

        // Execute the query
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // User found, set session variables
                session_start(); // Start a session if not already started
                $_SESSION["username"] = $username;
                $result->fetch_assoc(); // Fetch the user data (for consistency)

                // Redirect to a protected page or display a success message
                header("Location: /protected_page.php"); // Replace with your protected page URL
                exit();

            } else {
                $error = "Invalid username or password.";
            }

        } else {
            $error = "Query execution failed: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

<h1>Login</h1>

<?php
if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    // Execute the statement
    if ($stmt->execute() === TRUE) {
        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            // Successful login
            session_start();
            // Store user data in session
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['username'] = $user_data['username'];
            return true;
        } else {
            // User not found
            return false;
        }
    } else {
        // Error executing the query
        return false;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Example usage (within a form or other input method)
// $username = $_POST['username'];
// $password = $_POST['password'];
// if (loginUser($username, $password)) {
//     // Login successful, redirect to a secure page
//     header("Location: secure_page.php");
//     exit();
// } else {
//     // Login failed, display an error message
//     echo "Invalid username or password.";
// }

?>


<?php

// Database credentials - Replace with your actual values
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // 'ss' indicates two string parameters
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if any rows were returned
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();  // Fetch the first row as an associative array
    return $user; // Return user data if login is successful
  } else {
    return false; // Return false if login fails
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (Demonstration)

// Get username and password from form (or wherever you get them)
$username = $_POST['username'];
$password = $_POST['password'];

// Call the login function
$loggedInUser = loginUser($username, $password);

// Check if the login was successful
if ($loggedInUser) {
  // User is logged in - Now you can redirect to a different page,
  // display a welcome message, or access user data.
  echo "Welcome, " . $loggedInUser['username'] . "!";
  // You can also do things like:
  // session_start();
  // $_SESSION['user_id'] = $loggedInUser['id']; // Example: Store user ID in session
} else {
  // Login failed
  echo "Invalid username or password.";
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form action="" method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the username and password from the form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate the input (important for security!)
    if (empty($username) || empty($password)) {
        $error_message = "Username and Password must be filled.";
    } else {
        // Query the database
        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query - **IMPORTANT:  Use parameterized queries for security!**
        $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind the parameters
            $stmt->bind_param("ss", $username, $password);

            // Execute the statement
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // User found
                $row = $result->fetch_assoc();
                $user_id = $row["id"];
                $username = $row["username"];
                // Set session variables
                session_start();
                $_SESSION["user_id"] = $user_id;
                $_SESSION["username"] = $username;
                echo "Login successful!";
                // Redirect to a protected page (e.g., dashboard)
                header("Location: dashboard.php"); // Replace with your protected page
                exit(); // Important to stop further execution
            } else {
                $error_message = "Invalid username or password.";
            }
        } else {
            $error_message = "Error preparing the statement.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>

    <h1>User Login</h1>

    <?php if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        Username: <input type="text" name="username">
        Password: <input type="password" name="password">
        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual values
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to fetch user data
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, create a session
    // Set a session variable to indicate login
    session_start();
    $_SESSION['username'] = $username; // Store username in session
    $_SESSION['user_id'] = $result->fetch_assoc()['id']; // Example: Store user ID as well
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}


// Example Usage (from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // User logged in successfully
    header("Location: /welcome.php"); // Redirect to a welcome page (replace with your URL)
    exit();
  } else {
    // Login failed
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

// HTML Form for Login (to be embedded in your HTML page)
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Form handling (POST method)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Basic - should be more robust in a real application)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } else {
        // Authenticate against the database
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL Query (Using prepared statements for security!)
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // User found, set session variables
                session_start();
                $_SESSION["username"] = $username;
                $stmt->close();
                $conn->close();
                header("Location: welcome.php"); // Redirect to welcome page
                exit();
            } else {
                $error_message = "Invalid username or password.";
                $stmt->close();
                $conn->close();
            }
        } else {
            $error_message = "Error preparing statement.";
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h1>Login</h1>

    <?php
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username" />
        Password: <input type="password" name="password" />
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to check login credentials
function login($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $query = "SELECT id, username, password FROM users WHERE username = ?";

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username); // "s" indicates a string parameter

    // Execute the query
    if ($stmt->execute() === TRUE) {
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $row['password'])) {
                // Successful Login
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $row["username"];
                return true;
            } else {
                // Incorrect password
                return false;
            }
        } else {
            // No user found
            return false;
        }
    } else {
        // Query error
        return false;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Example Usage (Form handling - this is simplified)

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (VERY IMPORTANT for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Call the login function
        $login_result = login($username, $password);

        if ($login_result) {
            // Redirect to a protected page
            header("Location: protected_page.php"); // Replace with your protected page URL
            exit();
        } else {
            $error = "Incorrect username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h2>Login</h2>

    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username" placeholder="Enter your username"><br><br>
        Password: <input type="password" name="password" placeholder="Enter your password"><br><br>
        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php
session_start();

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

/**
 * Logs in a user.
 *
 * @param string $username The username to log in.
 * @param string $password The password.
 * @return bool True if login successful, false otherwise.
 */
function loginUser($username, $password)
{
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check the credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, set session variables
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id']; // Assuming you have a user ID
        return true;
    } else {
        return false;
    }

    $conn->close();
}

/**
 * Logs out the current user.
 */
function logoutUser() {
    // Destroy the session
    session_unset();
    session_destroy();
}

// Example usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security - see explanation below)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Attempt to log in
        if (loginUser($username, $password)) {
            // Login successful, redirect to a different page
            header("Location: user_dashboard.php"); // Redirect to a secure page
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>

    <h2>User Login</h2>

    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username" <?php if (isset($username)) echo 'value="' . htmlspecialchars($username) . '"'; ?> />
        <br/>
        Password: <input type="password" name="password" />
        <br/>
        <button type="submit">Login</button>
    </form>

    <br/>
    <a href="logout.php">Logout</a>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Login form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (IMPORTANT:  Always sanitize/validate user input!)
    $username = trim($username); // Remove leading/trailing whitespace
    $password = trim($password);


    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);  // Use prepared statements for security!
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // User found - set session variables
            $row = $result->fetch_assoc();
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];

            // Redirect to a protected page or display a success message
            header("Location: protected_page.php"); // Or any other appropriate page
            exit();

        } else {
            // User not found
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Error executing query.";
    }
}

// Display the login form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php
if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" placeholder="Your Username"><br><br>
    Password: <input type="password" name="password" placeholder="Your Password"><br><br>
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database Credentials (Replace with your actual credentials!)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (mysqli_stmt_prepare($conn, "SELECT username, password FROM users WHERE username = ?")) {
    if (mysqli_stmt_bind_param($conn, "s", $username)) {
      mysqli_stmt_execute($conn);
      $result = mysqli_stmt_get_result($conn);
      if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row["password"])) {
          // Password matches!  Set session variables.
          session_start(); // Start the session if it's not already
          $_SESSION["username"] = $username;
          $error_message = "";  // Clear any previous errors
          header("Location: welcome.php"); // Redirect to a welcome page
          exit(); // Stop further execution
        } else {
          $error_message = "Incorrect password.";
        }
        mysqli_stmt_close($conn); // Close the statement
      } else {
        $error_message = "Incorrect username.";
      }
      mysqli_stmt_close($conn);
    } else {
        $error_message = "Error preparing statement.";
    }
  } else {
    $error_message = "Error preparing statement.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <style>
    .error {
      color: red;
    }
  </style>
</head>
<body>

  <h2>Login</h2>

  <?php if (!empty($error_message)) { ?>
    <p class="error"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" />
    <br />
    Password: <input type="password" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>" />
    <br />
    <button type="submit">Login</button>
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL Query
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if (mysqli_stmt_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)")) {
      if (mysqli_stmt_bind_param($conn, "ss", $username, $hashed_password)) {
        mysqli_stmt_execute($conn);
        mysqli_stmt_close($conn);

        // Login successful - redirect to login.php
        header("Location: login.php");
        exit();
      } else {
        $error_message = "Error preparing statement.";
      }
    } else {
      $error_message = "Error preparing statement.";
    }
  }
}
?>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // If the user exists, you can now access their data
      // You might want to set a session or other authentication mechanism here
      session_start(); // Start the session (important for storing user information)
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      // You can add more data from the row here if needed, like $row['email']
      return true; // Login successful
    } else {
      return false; // Login failed (username or password incorrect)
    }

  } else {
    // Error executing query
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();

  return false; //Should not reach here if everything is done correctly
}

// Example usage (This is just an example - implement appropriate form handling)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    echo "Login successful!  Redirecting...";
    // Redirect to a protected page or home page
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>

<!-- HTML form (Example - adapt to your design) -->
<form method="post" action="">
  Username: <input type="text" name="username">
  Password: <input type="password" name="password">
  <input type="submit" value="Login">
</form>


<?php

// Database connection details - Replace with your actual credentials
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve the password hash from the database
  $sql = "SELECT password_hash, user_id FROM users WHERE username = '$username'";  // Assuming 'username' is the user's login identifier
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password_hash'];
    $userId = $row['user_id'];

    // Verify the password
    if (password_verify($password, $hashedPassword)) {
      // Authentication successful
      session_start(); // Start the session for user tracking
      $_SESSION['user_id'] = $userId; // Store the user's ID in the session
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}

// Example usage (Illustrative - Replace with your form handling logic)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a secure page, show a welcome message, etc.
    header("Location: welcome.php"); // Redirect to a welcome page
    exit();
  } else {
    // Login failed - Display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials - **IMPORTANT:  Never hardcode these in a production environment!**
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

/**
 * Logs in a user based on their username and password.
 *
 * @param string $username The username to log in.
 * @param string $password The password for the username.
 * @return int|false The user ID if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the SQL query
  $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings

  if ($stmt->execute() === false) {
    // Handle execution errors
    echo "Query execution failed: " . $stmt->error;
    $stmt->close();
    $conn->close();
    return false;
  }

  // Get the result
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // User not found
    $stmt->close();
    $conn->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];

  $stmt->close();
  $conn->close();

  return $userId;
}


// Example usage:

// Get username and password from form input (in a real application, validate this input!)
$username = $_POST['username'];
$password = $_POST['password'];

// Perform the login
$userId = loginUser($username, $password);

if ($userId) {
  // Login successful
  echo "Login successful! User ID: " . $userId;

  // You would typically redirect the user to a protected area of the website here.
} else {
  // Login failed
  echo "Login failed.  Please check your username and password.";
}

?>


$password = $_POST['password'];  // Get the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// ... (Insert $username and $hashedPassword into the database)


$password = $_POST['password'];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// ... (Query for the hashed password from the database)

if (password_verify($password, $hashedPasswordFromDatabase)) {
  // Login successful!
} else {
  // Login failed - incorrect password
}


<?php

// Database credentials (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Form handling (for simplicity, we'll directly process the form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (basic example - you should add more robust validation)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    }
    
    else {
        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query to select the user from the database
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User found!
            // Session management (important for security - see explanation below)
            session_start();
            $_SESSION["username"] = $username; // Store the username in the session
            echo "Login successful! You are now logged in.";
            // Redirect to a protected page or display a welcome message
            // header("Location: /protected_page.php"); // Example
            //exit();

        } else {
            // User not found
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

<h1>Login</h1>

<?php if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    Password: <input type="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// ... (Your login function code) ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (Get input values) ...

    if (empty($username) || empty($password)) {
        // ... (Error handling) ...
    } else {
        // Connect to the database (as before)
        $conn = new mysqli($servername, $username, $password, $dbname);
        // ... (Check connection) ...

        // Hash the password
        $password = password_hash($password, PASSWORD_DEFAULT); // Use password_hash()

        // Query to select the user from the database
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User found!
            session_start();
            $_SESSION["username"] = $username;
            // ... (Redirect, welcome message, etc.) ...

        } else {
            $error = "Invalid username or password.";
        }
    }
}

?>


<?php
// ... (Your login function code) ...

if ($result->num_rows > 0) {
    // User found!
    $user = $result->fetch_assoc();
    $hashed_password = $user['password'];

    if (password_verify($password, $hashed_password)) {
        // Password matches!
        session_start();
        $_SESSION["username"] = $username;
        // ... (Redirect, welcome message, etc.) ...
    } else {
        $error = "Invalid username or password.";
    }
}
?>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $query = "SELECT * FROM users WHERE username = '" . $conn->real_escape_string($username) . "' AND password = '" . $conn->real_escape_string($password) . "'";

  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // User found, set session variables
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    return true; // Login successful
  } else {
    return false; // Login failed
  }
}


// Example Usage:

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - add more robust validation here)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Call the login function
    $loginResult = loginUser($username, $password);

    if ($loginResult == true) {
      // Redirect to a protected page or display a welcome message
      header("Location: /protected_page.php"); // Change to your protected page URL
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    Password: <input type="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (VERY IMPORTANT - prevent SQL injection)
  $username = trim($username); // Remove leading/trailing whitespace
  $password = trim($password);

  // Sanitize the input (more robust than just trim)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $password = filter_var($password, FILTER_SANITIZE_STRING);


  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query with prepared statements (MOST IMPORTANT - prevents SQL injection)
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // User found - set session variables
      session_start();
      $row = $result->fetch_assoc();
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];

      // Redirect to a protected page
      header("Location: /protected_page.php"); // Replace with your protected page
      exit();

    } else {
      echo "Invalid username or password.";
    }

  } else {
    echo "Error executing query: " . $conn->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();

} else {
  // If the form hasn't been submitted, display the login form
  // This is crucial for the initial page load
  ?>
  <!DOCTYPE html>
  <html>
  <head>
    <title>Login</title>
  </head>
  <body>
    <h1>Login</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      Username: <input type="text" name="username" required>
      Password: <input type="password" name="password" required>
      <button type="submit">Login</button>
    </form>
  </body>
  </html>
  <?php
}
?>


<?php

// Database credentials (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement (important for security - prevents SQL injection)
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if ($stmt->execute()) {
      // Fetch the result
      $result = $stmt->fetch_assoc();

      // Check if the query returned any results
      if ($result) {
        // User found, set session variables
        session_start();
        $_SESSION['user_id'] = $result['id']; // Assuming you have an 'id' column in your users table
        $_SESSION['username'] = $username;
        return true; // Login successful
      } else {
        return false; // User not found
      }
    } else {
      // Error executing query
      return false;
    }
  } else {
    // Error preparing the statement
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (in your login form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a secure page
    header("Location: /secure_page.php"); // Replace with your desired secure page
    exit();
  } else {
    // Login failed, display an error message
    echo "Invalid username or password.";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
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
  // Get username and password from form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";  //Use prepared statements for security

  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    // User found, set session variables for authentication
    session_start();
    $row = $result->fetch_assoc();
    $_SESSION["user_id"] = $row["user_id"];
    $_SESSION["username"] = $row["username"];

    // Redirect to a secure page or display a welcome message
    header("Location: /welcome.php"); // Replace /welcome.php with your protected page's URL
    exit();

  } else {
    // User not found
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form action="login.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Login">
  </form>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check username and password
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $row['password'])) {
      // Login successful - Set session variables
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;
      return true; // Indicate successful login
    } else {
      return false; // Indicate incorrect password
    }
  } else {
    return false; // Indicate username not found
  }

  $conn->close();
}

// Example usage:
// Assume the user has entered their username and password in form fields
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page
    exit();
  } else {
    // Login failed - Display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL statement
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // Bind parameters
  $stmt->bind_param("ss", $username, $password);

  // Execute the statement
  if (!$stmt->execute()) {
    echo "Error: " . $stmt->error;
    return false;
  }

  // Get the result
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // No user found with those credentials
    return false;
  }

  // Fetch the user data
  $user = $result->fetch_assoc();

  // Close the statement and connection
  $stmt->close();
  $conn->close();

  // Successful login - return user data
  return $user;
}

// --- Example Usage ---

// Get username and password from form (example)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (add more robust validation here - e.g., password strength, input length)
  if (empty($username) || empty($password)) {
    echo "<p>Username and password fields cannot be empty.</p>";
  } else {
    // Call the login function
    $loginResult = loginUser($username, $password);

    if ($loginResult) {
      // User is logged in - redirect or display user info
      echo "<h2>Welcome, " . $loginResult["username"] . "</h2>";
      // You can store the user ID in a session, cookie, or local storage for later use.
      // Example:
      // $_SESSION['user_id'] = $loginResult['id'];  // Requires session_start()
    } else {
      // Login failed
      echo "<p>Invalid username or password.</p>";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Important for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and Password cannot be empty!";
    }
    elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    }
    else {
        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query (Prevent SQL Injection!)
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters (Crucial for security!)
            $stmt->bind_param("ss", $username, $password);

            // Execute the query
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if any rows were returned
            if ($result->num_rows > 0) {
                // User found!  Start a session.
                session_start();
                $_SESSION["username"] = $username; // Store username in session
                $result->fetch_assoc(); // Get user details (optional, for further processing)
                $error = ""; // Clear any previous errors
                echo "Login successful!  You are now logged in.";
                header("Location: /welcome.php"); // Redirect to a welcome page
                exit();

            } else {
                $error = "Invalid username or password.";
            }

        } else {
            $error = "Error preparing statement.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h2>Login</h2>

    <?php if (!empty($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
        Password: <input type="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Database connection
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to check credentials
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            $error = "Invalid username or password.";
        }

        if ($result->num_rows > 0) {
            // Login successful - set session variables
            session_start();
            $_SESSION["username"] = $username; // Store username in session
            $conn->close();
            header("Location: welcome.php"); // Redirect to a welcome page (or your home page)
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h1>Login</h1>

<?php
if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}
?>

<form method="post" action="<?php echo $_SERVER["SCRIPT_NAME"]; ?>">
    Username: <input type="text" name="username" placeholder="Enter username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter password"><br><br>
    <input type="submit" value="Login">
</form>

</body>
</html>


<?php

// Database connection details - **IMPORTANT: Replace with your actual details**
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate input (VERY IMPORTANT - prevent SQL injection)
    if (empty($username) || empty($password)) {
        $error = "Username and Password cannot be empty.";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid Email format.";
    } else {
        // Database query (using prepared statements for security)
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username); // "s" indicates a string parameter
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Verify password (using password_hash for secure comparison)
            if (password_verify($password, $row["password"])) {
                // Login successful - set session variables
                session_start();
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $username;
                echo "Login successful! <a href='dashboard.php'>Go to Dashboard</a>"; // Redirect after successful login
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Incorrect username.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php } ?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$db_name = 'your_db_name';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid username (must be a valid email address).";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to select the user based on username
    $sql = "SELECT id, username, password FROM users WHERE username = '" . $username . "'";  // Escape username for SQL injection. Use prepared statements for better security.

    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error = "Invalid username or password.";
    } else {
      // User found, verify password
      $row = $result->fetch_assoc();
      if (password_verify($password, $row["password"])) {
        // Login successful
        session_start();
        // Store user ID in session for later access
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $username;
        echo "Login successful!  Redirecting...";
        header("Location: welcome.php"); // Redirect to a welcome page or your application's homepage
        exit();
      } else {
        $error = "Invalid username or password.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (!empty($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login validation and returns user data if successful,
 * or an error message if login fails.
 *
 * @param string $username  The username to log in.
 * @param string $password The password to log in.
 * @param array $users     An associative array of usernames and their passwords.
 *                        Example: ['john.doe' => 'password123', 'jane.smith' => 'securePass']
 *
 * @return array|string  An array containing user data (id, username, etc.) on success,
 *                       or an error message string on failure.
 */
function login(string $username, string $password, array $users) {
    // Sanitize input -  Crucial for security!
    $username = trim($username);
    $password = trim($password);

    // Check if the user exists
    if (isset($users[$username])) {
        // Verify the password
        if (password_verify($password, $users[$username])) {  // Uses password_verify
            // User found and password matches - Create user data array
            $userData = [
                'id' => $username, // Using username as ID - can change to a real ID field
                'username' => $username,
            ];

            return $userData;
        } else {
            return "Incorrect password for " . $username;
        }
    } else {
        return "User " . $username . " not found";
    }
}

// Example Usage (for testing - REMOVE OR COMMENT OUT IN A REAL APPLICATION)
//  In a real application, you'd get these values from a form submission.

// Sample user data (in a real application, this would come from a database)
$users = [
    'john.doe' => '$2y$11$K3c2V1o8tG0wS16J8Xy1Z.w17xQ7fJ5p77789Qk9fV33333', // Hashed password
    'jane.smith' => '$2y$11$rGj2H9uL9oT6z953zY2c7.u41xQ7fJ5p77789Qk9fV33333', // Another hashed password
];


// Test login attempts
$loginResult1 = login('john.doe', 'password123', $users);
if ($loginResult1 === 'Incorrect password for john.doe') {
    echo "Login Failed: Incorrect password.
";
} elseif ($loginResult1 !== null) {
    echo "Login Successful!
";
    print_r($loginResult1);
} else {
    echo "Login Failed: User not found.
";
}

$loginResult2 = login('nonexistent.user', 'anypassword', $users);
if ($loginResult2 === 'Incorrect password for nonexistent.user') {
    echo "Login Failed: Incorrect password.
";
} elseif ($loginResult2 !== null) {
    echo "Login Successful!
";
    print_r($loginResult2);
} else {
    echo "Login Failed: User not found.
";
}

$loginResult3 = login('john.doe', 'wrongpassword', $users);
if ($loginResult3 === 'Incorrect password for john.doe') {
    echo "Login Failed: Incorrect password.
";
} elseif ($loginResult3 !== null) {
    echo "Login Successful!
";
    print_r($loginResult3);
} else {
    echo "Login Failed: User not found.
";
}
?>


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form data
$username = $_POST['username'];
$password = $_POST['password'];

// 1. Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// 2. Prepare the SQL query
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameters
$stmt->bind_param("ss", $username, $password); // "ss" indicates two strings

// Execute the query
$stmt->execute();

// 3. Get the result
$result = $stmt->get_result();

// 4. Process the result
$row = $result->fetch_assoc();

if($row) {
  // User exists
  // Set session variables
  session_start(); // Important: Start the session
  $_SESSION['username'] = $username;
  $_SESSION['user_id'] = $row['id']; // Assuming you have an 'id' column in your users table

  // Redirect the user to a protected page
  header("Location: /protected_page.php"); // Replace with your protected page URL
  exit(); // Important: Stop further execution
} else {
  // User doesn't exist
  echo "Invalid username or password.";
}

// 5. Close the connection
$stmt->close();
$conn->close();

?>


<?php

// Database connection details
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$username = $_POST['username'];
$password = $_POST['password'];

// SQL query to fetch the user
$sql = "SELECT id, username, password FROM users WHERE username = '$username'";
$result = mysqli_query($host, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Password is correct, set session variables
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;

            // Redirect to a protected page or display a success message
            header("Location: protected_page.php"); // Replace with your protected page
            exit();
        } else {
            // Incorrect password
            echo "<p style='color:red;'>Incorrect username or password.</p>";
        }
    }
} else {
    // Error querying the database
    echo "<p style='color:red;'>Database error: " . mysqli_error($host) . "</p>";
}

?>


<?php

// Database Credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (Crucial for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'") === false) {
    $error_message = "Invalid username or password.";
  } else {
    // Password verification (Important!  Never store passwords in plain text)
    $hashed_password = "your_hashed_password"; // Replace with the actual hashed password from your database

    if (password_verify($password, $hashed_password)) {
      // Login successful!
      session_start();
      $_SESSION["username"] = $username;
      header("Location: welcome.php"); // Redirect to a welcome page
      exit;
    } else {
      $error_message = "Invalid username or password.";
    }
  }
}

// Database connection
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


// Registering a new user (example)
$hashed_password = password_hash("your_password", PASSWORD_DEFAULT);

// Storing the hashed password in the database (in the INSERT query)
// ...

// Logging in a user:
$password = $_POST["password"];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
// ... your SQL query ...


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to select the user
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";  // Use prepared statements for security

    // Prepare the statement (important for security)
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            // User found, set session variables
            session_start();  // Start session management
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Redirect to a protected page or display a success message
            header("Location: /protected_page.php"); // Replace with your protected page URL
            exit(); // Important to stop further execution
        } else {
            // User not found
            return false;
        }
    } else {
        // Query error
        return false;
    }

    // Close the statement
    $stmt->close();
    $conn->close();

    return true;
}


// Example Usage (Demonstration - DO NOT directly use this in your application)
// Assuming you have a form to collect username and password

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important to prevent SQL injection)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        $login_result = loginUser($username, $password);

        if ($login_result) {
            echo "Login successful!  Redirecting...";
        } else {
            echo "Login failed.  Invalid credentials.";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

    <h1>Login</h1>

    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>

    <form method="post" action="">
        Username: <input type="text" name="username" />
        Password: <input type="password" name="password" />
        <input type="submit" value="Login" />
    </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Session management:  Store user information in session
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      return true; // Login successful
    } else {
      return false; // No user found with those credentials
    }
  } else {
    // Handle the error
    echo "Error executing query: " . $conn->error;
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (Handle Login Form Submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security - more robust validation is recommended)
  if (empty($username) || empty($password)) {
    echo "Username and password cannot be empty.";
  } else {
    // Call the loginUser function
    if (loginUser($username, $password)) {
      // Redirect to a protected page or display a welcome message
      header("Location: /protected_page.php"); // Replace with your protected page URL
      exit();
    } else {
      echo "Invalid username or password.";
    }
  }
}

//  Display login form (only displayed on the initial page)
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username); // 's' indicates a string parameter
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // User not found
    return false;
  } else {
    // User found, verify password
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      // Password matches, login successful
      // You can store user session data here (e.g., set session variables)
      // Example:
      session_start();
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      return true;
    } else {
      // Password mismatch
      return false;
    }
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (for demonstration - NOT for production!)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    // Login failed - Display error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>If you don't have an account, <a href="register.php">register</a>.</p>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found - store the user's data in a session
    $user_data = $result->fetch_assoc();
    $_SESSION['user_id'] = $user_data['id']; // Assuming 'id' is the primary key
    $_SESSION['username'] = $user_data['username'];
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}


// Example usage:  This is for demonstration - don't directly use this in your web app.
//  This shows how to call the function and handle the result.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful!  You are now logged in.";
    // Redirect to a secure page or display a welcome message.
    header("Location: /welcome.php"); // Replace with your secure page URL.  Important to redirect.
    exit();
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

  <h2>User Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (basic validation - improve this for a production environment)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid username (please enter a valid email address)";
  } else {
    // Database connection
    $conn = new mysqli($username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT * FROM users WHERE email = '$username'"; // Use email for security
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error_message = "Invalid username or password.";
    } else {
      // User found, check the password
      $user = $result->fetch_assoc();
      if (password_verify($password, $user["password"])) {
        // Authentication successful - set session variables
        session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["email"]; // Or username, depending on your database
        header("Location: welcome.php"); // Redirect to a welcome page
        exit();
      } else {
        $error_message = "Invalid username or password.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
  }
?>

  <form method="post" action="<?php echo $_SERVER["SCRIPT_NAME"]; ?>">
    Username: <input type="text" name="username" placeholder="your_email@example.com">
    <br><br>
    Password: <input type="password" name="password">
    <br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


// ... (Database connection code) ...

// Get the username and password from the form
$username = $_POST["username"];
$password = $_POST["password"];

// Hash the password using password_hash()
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// SQL query to insert the user
$sql = "INSERT INTO users (email, password) VALUES ('$username', '$hashed_password')";

// Execute the query
if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $conn->error;
}

// ... (Close database connection) ...


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Successful login
      session_start();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      $stmt->close();
      $conn->close();
      return true; // Indicate successful login
    } else {
      // Login failed
      $stmt->close();
      $conn->close();
      return false; // Indicate failed login
    }
  } else {
    // Prepare failed
    $stmt->close();
    $conn->close();
    return false; // Indicate prepare failed.
  }
}


// Example usage:  (This part would be in your HTML form processing)
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a protected page or display a welcome message
    header("Location: protected_page.php"); // Replace with your protected page
    exit();
  } else {
    // Display an error message
    echo "<p>Invalid username or password.</p>";
  }
}
*/


?>


<?php

// Database Credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid Username (Please enter a valid email address).";
  } else {
    // SQL Query (Use prepared statements for security - see explanation below)
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
      $error_message = "Connection failed: " . $conn->connect_error;
    }

    // Execute the query
    $result = $conn->query($sql);

    // Check if the query returned any results
    if ($result->num_rows > 0) {
      // User exists, now verify the password
      $row = $result->fetch_assoc();
      if (password_verify($password, $row["password"])) {
        // Password is correct, set session variables
        $_SESSION["username"] = $username;
        $_SESSION["user_id"] = $row["id"]; // Store user ID for better security
        header("Location: welcome.php"); // Redirect to a welcome page
        exit;
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "User not found.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <?php if (!empty($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" placeholder="Enter your email">
    <br><br>
    Password: <input type="password" name="password">
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here.</a>
  <br><br>
  <a href="forgot_password.php">Forgot Password?</a>  <!-- You'll need to implement forgot password functionality -->

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to handle login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, set session variables
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_id'] = $row['id']; // Assuming you have an 'id' column in your users table
        return true; // Login successful
    } else {
        return false; // Login failed
    }

    $conn->close();
}

// Example Usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Redirect to a secure page or display a welcome message
        header("Location: secure_page.php"); // Replace with your secure page
        exit();
    } else {
        // Display an error message
        echo "<p style='color: red;'>Invalid username or password.</p>";
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h1>Login</h1>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$login_username = $_POST['username'];
$login_password = $_POST['password'];

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate
  $sql = "SELECT id, username, password FROM users WHERE username = '$login_username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, verify the password
    $user_data = $result->fetch_assoc();
    if (password_verify($login_password, $user_data['password'])) {
      // Password is correct, user is authenticated
      // Set a session variable to indicate the user is logged in
      session_start();
      $_SESSION['user_id'] = $user_data['id'];
      $_SESSION['username'] = $user_data['username'];
      echo "Login successful! You are now logged in.";
      // Redirect to a protected page or home page
      header("Location: /protected_page.php"); // Replace with your protected page URL
      exit();
    } else {
      echo "Incorrect password.";
    }
  } else {
    echo "User not found.";
  }

  $conn->close();
} else {
  // If the form wasn't submitted via POST, redirect to the login page
  header("Location: login.php");
  exit();
}

?>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data (from the login form)
$username = $_POST['username'];
$password = $_POST['password'];

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  // Check if the username exists
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password']; // Retrieve the hashed password

    // Compare the entered password with the stored hashed password
    if (password_verify($password, $hashed_password)) {
      // Password matches! Login successful.
      // Set session variables to track the user's login status and user ID
      session_start();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;

      // Redirect to a secure page or display a success message
      header("Location: secured_page.php"); // Replace with your secure page URL
      exit();
    } else {
      // Incorrect password
      echo "Incorrect password.";
    }
  } else {
    // Username does not exist
    echo "Username does not exist.";
  }

  $conn->close(); // Close the database connection

} else {
  // If the form wasn't submitted via POST, redirect to the login form
  header("Location: login_form.php");
  exit();
}
?>


    // During user registration:
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // Then store $hashed_password in the database
    
* **`password_verify()`:** This function is designed to compare a plaintext password with a hashed password, returning `true` if they match.
* **Error Handling:** Includes a basic check for database connection errors.  More robust error handling would involve logging errors and providing more user-friendly messages.
* **Session Management:**
    * `session_start();`  Starts a PHP session. Sessions allow you to maintain user state across multiple requests.
    * `$_SESSION['user_id'] = $row['id'];` and `$_SESSION['username'] = $username;` Store the user's ID and username in the session. This lets you identify the user on subsequent pages.
* **Clearer Logic and Comments:** The code is well-commented and has a more logical flow.
* **Input Validation (Important - not implemented here for brevity):**  **Crucially, you *must* add input validation to your login form to prevent security vulnerabilities (e.g., SQL injection, XSS).**  This involves validating:
    * The username and password fields against expected formats and lengths.
    * Preventing special characters from being submitted (which could be used in SQL injection).
    * Consider using prepared statements (using PDO or MySQLi) for even stronger protection.
* **`exit()` after redirects:**  Ensures that the script stops executing after redirecting to prevent any further code from running, which could lead to unexpected behavior.
* **Secure Page:** The `header("Location: secured_page.php");` directs the user to a secure page once they have successfully logged in.
* **`login_form.php`:**  This script should contain the HTML form to collect the username and password.  You need to create this file.
* **Database Structure (important):**  This assumes a basic `users` table with columns `id`, `username`, and `password`.  Your database structure may be different.

**How to use this code:**

1. **Create the `users` table:**  Make sure your database has a `users` table with the specified columns.
2. **Register users:**  Implement user registration functionality.  During registration, use `password_hash()` to store a *hashed* password in the `password` column.
3. **Create `login_form.php`:** Create an HTML form to collect the username and password.  This form should have the following input fields:
   tml
   <form action="login.php" method="post">
     <label for="username">Username:</label>
     <input type="text" id="username" name="username" required><br><br>

     <label for="password">Password:</label>
     <input type="password" id="password" name="password" required><br><br>

     <button type="submit">Login</button>
   </form>
   
4. **Place the PHP code:** Save the PHP code as `login.php` in your web server's document root.
5. **Adjust Database Credentials:**  Replace `"your_username"`, `"your_password"`, and `"your_database"` with your actual database credentials.
6. **Secure `secured_page.php`:** Create a `secured_page.php` (or whatever you named it) to redirect to after successful login.  This page should be protected, so only logged-in users can access it.

**Example `secured_page.php`**



<?php

/**
 * User Login Function
 *
 * This function handles user login using a simple file-based authentication.
 *  **WARNING:** This is a VERY basic example for demonstration purposes ONLY. 
 *  Do NOT use this in a production environment due to security vulnerabilities.
 *  Use a proper database and robust authentication methods.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function login(string $username, string $password)
{
    // Check if the username and password are provided
    if (empty($username) || empty($password)) {
        return false; // Invalid input
    }

    // Load user data from a file (replace with database access in a real application)
    $user_data = file_get_contents('users.txt');

    if ($user_data === false) {
        return false; // Could not read the file
    }


    // Parse user data (assuming a simple format: username:password)
    $users = explode(',', $user_data);  // Split data if multiple users stored in file

    foreach ($users as $user) {
        $parts = explode(':', $user);
        if (count($parts) == 2) {
            $username_in_file = trim($parts[0]);
            $password_in_file = trim($parts[1]);

            // Compare passwords (case-sensitive)
            if ($username === $username_in_file && $password === $password_in_file) {
                return true; // Login successful
            }
        }
    }

    return false; // Login failed
}


// Example Usage (for demonstration only - NEVER do this in a production environment)
// Create a users.txt file for testing (e.g., username:password)
//   user1:password123
//   user2:secret

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (login($username, $password)) {
        echo "Login successful! You are now logged in.";
    } else {
        echo "Login failed.  Incorrect username or password.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Simple Login Example</title>
</head>
<body>

    <h1>Simple Login</h1>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username">
        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php
//  This is a very simplified example and assumes you have the
//  bcrypt extension installed and configured correctly in your PHP environment.

//  In a real application, you would use a proper database.  This is
//  for demonstration only.

function hashPassword(string $password): string {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword(string $password, string $hashedPassword): bool {
    return password_verify($password, $hashedPassword);
}

// Example Usage (for demonstration)
$password = "mySecretPassword";
$hashed = hashPassword($password);
echo "Hashed Password: " . $hashed . "
";

if (verifyPassword("mySecretPassword", $hashed)) {
    echo "Password matches!
";
} else {
    echo "Password does not match.
";
}
?>


<?php

// Database Credentials (Replace with your actual values!)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input - IMPORTANT for security
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password must be filled.";
  } elseif (filter_var($username, FILTER_VALIDATE_EMAIL) === false) { // Basic email validation
    $error_message = "Invalid Username (Please enter a valid email address).";
  } else {
    // Attempt to authenticate the user
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$username'"); // Use email for authentication
    if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row["password"])) { // Use password_verify for secure comparison
          // Successful login - Set session variables
          session_start();
          $_SESSION["username"] = $username;
          $_SESSION["user_id"] = $row["id"]; // Store user ID (good practice)
          header("Location: welcome.php"); // Redirect to welcome page
          exit(); // Important: Stop further execution
        } else {
          $error_message = "Incorrect password.";
        }
      }
    } else {
      $error_message = "Query error.";
    }
  }
} else {
  // If form was not submitted, display the login form
  $error_message = ""; // Clear any previous errors
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (!empty($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    Username:
    <input type="email" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" />
    <br />
    Password:
    <input type="password" name="password" />
    <br />
    <button type="submit">Login</button>
  </form>

  <br />
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user information
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";  //Sanitize input here
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, set session variables
    $user_data = $result->fetch_assoc();
    $_SESSION['user_id'] = $user_data['id'];
    $_SESSION['username'] = $user_data['username'];
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}


// Example Usage:
// 1. Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // 2. Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // 3. Validate input (VERY IMPORTANT - Protect against SQL injection)
  if (empty($username) || empty($password)) {
    $error[] = "Username and password cannot be empty.";
  }

  // 4. Call the login function
  $login_result = loginUser($username, $password);

  // 5. Handle the result
  if ($login_result) {
    // Redirect to a protected page
    header("Location: /protected_page.php"); // Replace with the URL of your protected page
    exit();
  } else {
    $error[] = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <?php
    if (!empty($error)) {
      echo "<div style='color: red;'>Error: " . implode(", ", $error) . "</div>";
    }
  ?>

</body>
</html>


<?php
session_start();

/**
 * Handles user login functionality.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  //  In a real application, you would check against a database.
  //  This is a simplified example for demonstration.
  $validUsers = [
    'john.doe' => 'password123',
    'jane.smith' => 'secretpass',
  ];

  if (isset($validUsers[$username])) {
    if ($validUsers[$username] === $password) {
      //  Login successful
      $_SESSION['username'] = $username; // Store username in session
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }
}

// Example Usage (demonstration)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful! You are now logged in.";
    // Redirect to a secure page or display a welcome message.
    header("Location: /welcome.php"); // Replace /welcome.php with your welcome page
    exit();
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

  <h2>User Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" placeholder="john.doe" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

/**
 * This function handles user login.
 *
 * @param string $username The username to attempt login with.
 * @param string $password The password to attempt login with.
 * @return int|string Returns the user's ID if login is successful,
 *                    or an error message string if login fails.
 */
function loginUser(string $username, string $password) {
  // Replace this with your actual database connection and authentication logic.
  // This is a placeholder for demonstration purposes.

  // Simulate a user database (in a real application, you'd use a database query)
  $users = [
    'john.doe' => 'secretPassword123',
    'jane.smith' => 'anotherPassword',
    'admin' => 'adminPassword'
  ];

  // Convert username and password to lowercase for case-insensitive comparison
  $username = strtolower($username);
  $password = strtolower($password);

  if (isset($users[$username])) {
    // Verify the password
    if ($users[$username] === $password) {
      // Login successful - Get user ID (assuming user ID is the username)
      return $username; // Return the username (or a user ID if you have one)
    } else {
      return "Incorrect password for " . $username;
    }
  } else {
    return "User " . $username . " not found.";
  }
}


// Example Usage:
$username = $_POST['username']; // Get username from form submission
$password = $_POST['password']; // Get password from form submission


if (isset($username) && isset($password)) {
  $loginResult = loginUser($username, $password);

  if (is_int($loginResult)) {
    // Login successful - Redirect or display the user's information
    echo "Login successful! User ID: " . $loginResult;
    // You might redirect here: header("Location: user_dashboard.php?id=" . $loginResult);
  } else {
    // Login failed - Display the error message
    echo "<p style='color:red'>" . $loginResult . "</p>";
  }
} else {
  //  Handle the case where username or password are not submitted.  This is good practice.
  echo "<p>Please enter your username and password.</p>";
}

?>


function loginUser(string $username, string $password) {
  // ... (previous code) ...

  if (isset($users[$username])) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    if ($hashedPassword === $users[$username]) {
      return $username;
    } else {
      return "Incorrect password for " . $username;
    }
  } else {
    return "User " . $username . " not found.";
  }
}


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle the login process
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user data
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found
    $user_data = $result->fetch_assoc();
    $hashed_password = $user_data['password']; // Get the stored hash

    // Verify password (using password_verify) - This is the secure way
    if (password_verify($password, $hashed_password)) {
      // Password is correct!
      // Set session variables to indicate successful login
      $_SESSION['user_id'] = $user_data['id'];
      $_SESSION['username'] = $username;

      return true; // Login successful
    } else {
      // Password mismatch
      return false; // Login failed
    }
  } else {
    // User not found
    return false; // Login failed
  }

  // Close the connection
  $conn->close();
}


// Example usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (basic validation - improve for production)
  if (empty($username) || empty($password)) {
    echo "Username and Password cannot be empty.";
  } else {
    // Call the loginUser function
    if (loginUser($username, $password)) {
      // Login successful - Redirect to the desired page
      header("Location: welcome.php"); // Replace 'welcome.php' with your welcome page
      exit();
    } else {
      // Login failed
      echo "Invalid username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");

  // Bind the username parameter
  $stmt->bind_param("s", $username);

  // Execute the query
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password']; // Fetch the hashed password
    // Compare the provided password with the hashed password
    if (password_verify($password, $hashed_password)) {
      // Password matches!  Set session variables.
      session_start();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;
      return true; // Login successful
    } else {
      // Password does not match
      $stmt->close();
      return false;
    }
  } else {
    // No user found
    $stmt->close();
    return false;
  }
}


// Example usage (simulated form submission - replace with your actual form handling)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a secure page or display a welcome message
    header("Location: secure_page.php"); // Redirect to your secure page
    exit(); // Important to stop further execution after redirection
  } else {
    // Login failed - Display error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

?>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

/**
 * Logs in a user based on username and password.
 *
 * @param string $username The username to authenticate.
 * @param string $password The password.
 * @return int|false User ID if successful, false otherwise.
 */
function login(string $username, string $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id FROM users WHERE username = ? AND password = ?;";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $user_id = $row["id"];
      return $user_id;
    } else {
      return false; // User not found
    }
  } else {
    // Handle query error
    echo "Query failed: " . $conn->error;
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (for testing - don't use directly in your application)
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    echo "Username and password cannot be empty.";
  } else {
    // Call the login function
    $user_id = login($username, $password);

    if ($user_id) {
      // User logged in successfully
      echo "User ID: " . $user_id . "<br>";
      // You can redirect the user to a secure area, etc.
    } else {
      // User login failed
      echo "Invalid username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" placeholder="Enter username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (very basic example, improve this for production)
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
      // User found, set session variables
      session_start(); // Start the session
      $user_row = $result->fetch_assoc();
      $_SESSION["username"] = $user_row["username"];
      $_SESSION["user_id"] = $user_row["id"]; // Assuming you have an id column in your users table
      // You might want to add more user data to the session if needed
      echo "Login successful!  Redirecting...";
      header("Location: user_dashboard.php"); // Redirect to a secure page
      exit(); // Important: Stop further script execution
    } else {
      $error = "Invalid username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" placeholder="Enter your username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter your password"><br><br>
    <input type="submit" value="Login">
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

    // Get username and password from the form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate input (IMPORTANT - Always validate user input!)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Hash the password (using password_hash - preferred method)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query the database to check credentials
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);

        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Check if the hashed password matches
                if (password_verify($password, $user["password"])) {
                    // Login successful
                    session_start();
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["username"] = $user["username"];
                    header("Location: welcome.php"); // Redirect to a welcome page
                    exit(); // Important: Stop further execution
                } else {
                    $error = "Incorrect password.";
                }
            } else {
                $error = "Username not found.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h1>Login</h1>

<?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php } ?>

<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" <?php if (isset($username)) echo 'value="'. $username . '"'; ?> />
    <br />
    Password: <input type="password" name="password" />
    <br />
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials (replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get login details from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    // Check if the username exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row["password"])) {
            // If passwords match, set session variables
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = $row["id"]; // Store the user ID as well for more robust security
            echo "Login successful! <a href=''>Continue to Dashboard</a>";
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<form action="" method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>

<br>
<a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } else {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // Query to authenticate the user
      $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
      $result = $conn->query($sql);

      if ($result->num_rows == 1) {
        // User found, set session variables
        session_start();
        $_SESSION["username"] = $username;
        $result->fetch_assoc(); // Fetch the row data
        $error = "";  // Clear any previous errors
        // Redirect to a secure page or display a welcome message
        header("Location: welcome.php"); // Redirect to a welcome page
        exit(); // Stop further execution
      } else {
        $error = "Incorrect username or password.";
      }
    }
  }
}

// Display the login form if no form was submitted or if there's an error
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["SCRIPT_NAME"]; ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Your Username" value="<?php if (isset($username)) echo $username; ?>">
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Your Password">
    <br><br>

    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here</a>

</body>
</html>


<?php

// Database credentials
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Basic validation - enhance for production)
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } else {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // Use a prepared statement to prevent SQL injection
      $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
      $stmt = $conn->prepare($sql);

      if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("ss", $username, $password);

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
          // User found, set session variables
          session_start();
          $_SESSION["username"] = $username;
          $result->fetch_assoc(); // Fetch the row data
          $error = "";  // Clear any previous errors
          header("Location: welcome.php");
          exit();
        } else {
          $error = "Incorrect username or password.";
        }

        // Close the statement
        $stmt->close();
      } else {
        $error = "Error preparing statement.";
      }
    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["SCRIPT_NAME"]; ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Your Username">
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Your Password">
    <br><br>

    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here</a>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to authenticate the user
function loginUser($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // 3. Bind parameters
  $stmt->bind_param("ss", $username, $password);

  // 4. Execute the query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  // 5. Get the result
  $result = $stmt->get_result();

  // 6. Check if a user was found
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // 7.  Create a session
    session_start();  // Start the session
    $_SESSION['user_id'] = $row['id']; // Store user ID in session
    $_SESSION['username'] = $username; // Store username in session (optional)

    //  You might also want to store other user data in the session.

    // 8.  Success!  Return true
    return true;
  } else {
    // 9. User not found
    return false;
  }

  // 10. Close the statement
  $stmt->close();

  // 11. Close the connection (not always necessary, but good practice)
  $conn->close();
}


// Example usage (handling the login form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // User successfully logged in
    header("Location: welcome.php"); // Redirect to a welcome page
    exit();
  } else {
    // User login failed
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

  <?php if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
  } ?>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Form data
$username = $_POST["username"];
$password = $_POST["password"];

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to authenticate the user
$sql = "SELECT id, username, password FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User exists, fetch the password hash
    $row = $result->fetch_assoc();
    $hashed_password = $row["password"]; // Assuming password is stored as a hash

    // Verify the password
    if (password_verify($password, $hashed_password)) {
        // Password matches, user is authenticated
        // Set a session variable to store the user's ID
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;

        echo "Login successful!";  // Or redirect to your dashboard
        // You can also set a flag to show a success message on the login form
        // e.g., echo "<p style='color:green;'>Login successful!</p>";

    } else {
        // Password does not match
        echo "Incorrect password!";
    }
} else {
    // User does not exist
    echo "User not found!";
}

$conn->close();

?>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Form data
$username = $_POST["username"];
$password = $_POST["password"];

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query
$sql = "SELECT id, username, password FROM users WHERE username = ?";  // Use a placeholder
$stmt = $conn->prepare($sql);

// Bind the parameters
$stmt->bind_param("s", $username); // "s" indicates a string

// Execute the query
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User exists, fetch the password hash
    $row = $result->fetch_assoc();
    $hashed_password = $row["password"];

    // Verify the password
    if (password_verify($password, $hashed_password)) {
        // Password matches, user is authenticated
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;

        echo "Login successful!";
    } else {
        echo "Incorrect password!";
    }
} else {
    echo "User not found!";
}

$stmt->close();
$conn->close();

?>


<?php

// Database Credentials (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check for username and password
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, verify password
    $user_data = $result->fetch_assoc();
    if (password_verify($password, $user_data['password'])) {
      // Password matches, login successful
      $_SESSION['user_id'] = $user_data['id'];
      $_SESSION['username'] = $user_data['username'];
      return true;
    } else {
      // Password does not match
      return false;
    }
  } else {
    // User not found
    return false;
  }
}

// --- Example Usage (Illustrative - Not complete login form) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a welcome page or desired location
    header("Location: welcome.php"); // Replace welcome.php with your desired page
    exit();
  } else {
    // Login failed, display an error message
    $error = "Invalid username or password.";
  }
}

// --- Login Form (Simplified for Demonstration) ---
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php endif; ?>

  <form method="post" action="">
    Username: <input type="text" name="username" value="<?php if(isset($username)) echo $username; ?>">
    <br><br>
    Password: <input type="password" name="password" value="<?php if(isset($password)) echo $password; ?>">
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Not registered? Register here.</a> <!-- Replace with your register page -->

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user information
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verify password
    if (password_verify($password, $user['password'])) {
      // Login successful
      $_SESSION['loggedin'] = true;
      $_SESSION['id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      return true;
    } else {
      // Password mismatch
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}


// Example usage (HTML form for login)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a protected page
    header("Location: protected.php"); // Replace 'protected.php' with your protected page URL
    exit();
  } else {
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p> <!-- Link to registration page (you'll need to create this) -->

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to authenticate the user
function loginUser($username, $password) {
  // 1. Connect to the Database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. SQL Query to Retrieve User
  $query = "SELECT id, username, password FROM users WHERE username = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username); // "s" indicates a string parameter

  if ($stmt->execute() === false) {
    // Handle execution error
    echo "Query execution failed: " . $conn->error;
    $stmt->close();
    $conn->close();
    return false;
  }

  // 3. Get Result
  $result = $stmt->get_result();

  // 4. Check if User Found
  if ($result->num_rows == 0) {
    // User not found
    $stmt->close();
    $conn->close();
    return false;
  }

  // 5. Get User Data
  $user = $result->fetch_assoc(); // Fetch as an associative array
  $stmt->close();
  $conn->close();

  // 6. Verify Password (Using Password Hashing - VERY IMPORTANT!)
  if (password_verify($password, $user['password'])) {
    // Password correct!
    return $user['id']; // Return the user ID if authentication is successful
  } else {
    // Incorrect password
    return false;
  }
}

// Example Usage (Handling Login Form Submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (empty($username) || empty($password)) {
    echo "Username and password fields are required.";
  } else {
    $userId = loginUser($username, $password);

    if ($userId) {
      // Login Successful
      session_start(); // Start the session
      $_SESSION['userId'] = $userId;  // Store the user ID in the session
      echo "Login successful! You are now logged in.";
      // Redirect to a protected page or the homepage
      header("Location: /protected_page.php"); // Replace with your desired URL
      exit();
    } else {
      // Login Failed
      echo "Invalid username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (IMPORTANT - prevent SQL injection)
  $username = trim($username);  // Remove leading/trailing whitespace
  $password = trim($password);

  // Sanitize input (important for security)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $password = filter_var($password, FILTER_SANITIZE_STRING);


  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows > 0) {
    // User found, set session variables
    session_start(); // Start the session (if not already started)
    $_SESSION["username"] = $username;
    echo "Login successful!";
    // Redirect to a secure page or display a welcome message
    header("Location: /welcome.php");  // Replace with your welcome page URL
    exit();
  } else {
    echo "Invalid username or password.";
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
else {
  // If the form wasn't submitted, display the login form
  // You can either include this file or display a simple HTML form
  // For example:
  echo "<form method='post' action='login.php'>";
  echo "Username: <input type='text' name='username'>";
  echo "<br>";
  echo "Password: <input type='password' name='password'>";
  echo "<br>";
  echo "<input type='submit' value='Login'>";
  echo "</form>";
}

?>


<?php

// Database configuration (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the login credentials
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (basic example, improve for production)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Query the database to check for the credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($db_host, $sql);

    if (mysqli_num_rows($result) > 0) {
      // User found, set session variables for authentication
      session_start();
      $_SESSION["username"] = $username;  // Store the username
      // You might want to store other user information here, like user ID, etc.
      // $_SESSION["user_id"] = $user_id;

      // Redirect the user to a secure page (e.g., dashboard)
      header("Location: dashboard.php"); // Replace with your secure page
      exit(); // Important: Stop further script execution
    } else {
      $error = "Invalid username or password.";
    }
  }
}

// If the form hasn't been submitted, display the login form
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" placeholder="Your Username"><br><br>
    Password: <input type="password" name="password" placeholder="Your Password"><br><br>
    <input type="submit" value="Login">
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>  <!-- Link to registration page -->

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$login_username = $_POST["username"];
$login_password = $_POST["password"];

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the query
$sql = "SELECT username, password FROM users WHERE username = '$login_username'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // User not found
    echo "Invalid username or password.";
} else {
    // User found, check password
    $user_data = $result->fetch_assoc();
    if (password_verify($login_password, $user_data["password"])) {
        // Password matches, successful login
        // Set a session variable to store the user's username
        session_start();
        $_SESSION["username"] = $login_username;
        echo "Login successful!  Redirecting...";

        // Redirect to a protected page or your main application page
        header("Location: /protected_page.php"); //  Replace with your protected page
        exit();
    } else {
        // Password does not match
        echo "Invalid username or password.";
    }
}

$conn->close();

?>


<?php
// ... (database configuration and connection)

// Prepare the statement
$stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");

// Bind the parameters
$stmt->bind_param("s", $login_username); // "s" indicates a string

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // User not found
    echo "Invalid username or password.";
} else {
    // User found, check password
    $user_data = $result->fetch_assoc();
    if (password_verify($login_password, $user_data["password"])) {
        // Password matches, successful login
        session_start();
        $_SESSION["username"] = $login_username;
        echo "Login successful!  Redirecting...";

        // Redirect to a protected page
        header("Location: /protected_page.php");
        exit();
    } else {
        // Password does not match
        echo "Invalid username or password.";
    }
}

$stmt->close();
$conn->close();
?>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle user login
function loginUser($username, $password) {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

    // Bind parameters to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result(); // Get result set

        if ($result->num_rows > 0) {
            // User exists, return user data (you can customize this)
            $user_data = $result->fetch_assoc();
            return $user_data;
        } else {
            return false; // User not found
        }
    } else {
        return false; // Query error
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}


// Example usage (Handle login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (basic example - improve for production)
    if (empty($username) || empty($password)) {
        echo "Username and password fields cannot be empty.";
    } else {
        $loginResult = loginUser($username, $password);

        if ($loginResult) {
            // Successful login
            session_start();  // Start a session to store user data
            $_SESSION["username"] = $loginResult["username"]; // Store username in session
            $_SESSION["user_id"] = $loginResult["user_id"]; // Store user_id for more efficient database lookups
            echo "Login successful!  Redirecting...";
            // Redirect to a secure page or dashboard
            header("Location: /dashboard.php"); // Replace with your dashboard URL
            exit();

        } else {
            echo "Invalid username or password.";
        }
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

    <h2>Login</h2>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to fetch user data
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, return user data (you can customize this to return other user details)
    $user = $result->fetch_assoc();
    return $user;
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}

// Example usage (handling form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - see notes below)
  if (empty($username) || empty($password)) {
    echo "Username and password must be filled.";
  } else {
    // Call the loginUser function
    $user = loginUser($username, $password);

    if ($user) {
      // User logged in successfully
      // You can redirect to a welcome page or do something else here
      // echo "Welcome, " . $user['username'];  // Display username (example)
      // For production, you'd typically set a session variable here.
      session_start();
      $_SESSION['username'] = $username;  // Store username in session
      header("Location: welcome.php"); // Redirect to a welcome page
      exit();

    } else {
      // User login failed
      echo "Invalid username or password.";
    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if ($stmt->execute()) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // User found, set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true; // Login successful
      } else {
        return false; // User not found
      }
    } else {
      return false; // Query execution error
    }
  } else {
    return false; // Prepare statement error
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (Demonstration - Replace with actual form handling)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security -  see below)
  if (empty($username) || empty($password)) {
    $error = "Username and Password cannot be empty.";
  } else {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // SQL query to authenticate the user
      $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
      $result = $conn->query($sql);

      if ($result->num_rows == 1) {
        // User found - Set session variables
        session_start();
        $_SESSION["username"] = $username;
        $_SESSION["user_id"] = $result->fetch_assoc()["id"]; // Assuming you have an 'id' column
        $error = "Login successful!";
        // Redirect to a secure page (e.g., dashboard)
        header("Location: dashboard.php"); // Or your desired secure page
        exit();
      } else {
        $error = "Invalid username or password.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <?php if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
  }
  ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" placeholder="Enter username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Crucial for security)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } else {
        // Hash the password (Important for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query the database to check credentials
        $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
        $result = mysqli_query($db_host, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // Verify the password
            if (password_verify($password, $row["password"])) {
                // Successful login
                // Set a session variable to store the user's ID
                session_start();
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $username; // Optionally store username
                
                // Redirect the user to a protected page
                header("Location: protected_page.php"); // Replace with your protected page
                exit(); // Important to stop further script execution
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "Username not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h2>Login</h2>

    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username" <?php if (isset($error_message) && $error_message == "Username not found.") { echo "required"; } ?>><br><br>
        Password: <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="registration.php">Register</a></p>
</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Crucial for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL Query to authenticate the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
      // User found, set session variables
      session_start(); // Start a session (important for maintaining user info)
      $user_row = $result->fetch_assoc();
      $_SESSION["username"] = $user_row["username"];
      $_SESSION["id"] = $user_row["id"]; // Assuming you have an ID column in your users table

      // Redirect to a protected page or display a success message
      header("Location: protected_page.php"); // Replace with your protected page
      exit();

    } else {
      $error_message = "Invalid username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>New user? <a href="register.php">Register here</a></p>

</body>
</html>


   <?php
   // ... (Database connection code)

   if ($result->num_rows == 1) {
     $user_row = $result->fetch_assoc();
     $hashed_password = $user_row["password"];  // Get the hashed password from the database
     if (password_verify($password, $hashed_password)) {
       // Password matches, proceed with login
       // ... (Set session variables)
     } else {
       $error_message = "Invalid password.";
     }
   }
   ?>
   

   * **`password_hash($password, PASSWORD_BCRYPT)`**:  This function generates a secure, salted hash of the user's password before storing it in the database.

4. **Register Form (register.php):** Create a `register.php` file to allow new users to create accounts.  Use `password_hash()` to hash their passwords before storing them in the database.

5. **Protected Page (protected_page.php):**  Create a `protected_page.php` (or whatever you choose) to display content only for logged-in users.  Check if `$_SESSION["username"]` is set before allowing access.

Example `register.php` (Very basic - expand for real-world use):



<?php

/**
 * User Login Function
 *
 * This function handles user login using a simple username/password scheme.
 * **Important:**  This is a basic example for demonstration purposes.  
 *  For production environments, you should use a secure authentication method 
 *  like hashing passwords and using sessions or tokens.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // **IMPORTANT:  In a real application, you should NEVER store passwords in plain text.**
  // This example uses a hardcoded database for simplicity.
  // You should hash passwords using a strong hashing algorithm like bcrypt.

  $validUsers = [
    'john.doe' => 'password123',
    'jane.smith' => 'securePass456',
  ];

  // Check if the username exists
  if (isset($validUsers[$username])) {
    // Verify the password
    if ($validUsers[$username] === $password) {
      // Login successful - set a session variable to indicate logged-in status
      session_start();
      $_SESSION['user_id'] = $username; // Use username as identifier
      $_SESSION['logged_in'] = true;
      return true;
    } else {
      return false; // Incorrect password
    }
  } else {
    return false; // Username not found
  }
}

// Example usage:
// Simulate a login attempt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    echo '<p>Login successful! You are now logged in.</p>';
    // Redirect to a protected page or display a welcome message
    header("Location: welcome.php"); // Redirect to welcome page
    exit();
  } else {
    echo '<p>Invalid username or password.</p>';
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle login
function loginUser($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. SQL Query to verify credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  // 3. Check if any rows were returned
  if ($result->num_rows > 0) {
    // User found - Get the data
    $user = $result->fetch_assoc();
    // 4. Store user session information
    $_SESSION['user_id'] = $user['id']; // Assuming 'id' is the user's ID
    $_SESSION['username'] = $user['username'];

    return true; // Login successful
  } else {
    return false; // Login failed
  }

  // Close connection
  $conn->close();
}


// Example Usage (Demonstration - Not for Production)
if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
  $username = $_POST['login_username'];
  $password = $_POST['login_password'];

  if (loginUser($username, $password)) {
    // Redirect to a protected page or display a success message
    header("Location: protected_page.php"); // Replace with your protected page
    exit(); // Important: Stop further script execution
  } else {
    // Display error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="login_username" placeholder="Enter your username"><br><br>
    Password: <input type="password" name="login_password" placeholder="Enter your password"><br><br>
    <input type="submit" name="login_submit" value="Login">
  </form>

</body>
</html>


<?php

// Example using prepared statements for secure SQL queries
function loginUser($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL query using prepared statements
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters

  // Execute the query
  $result = $stmt->execute();

  if ($result) {
    $user = $stmt->fetch_assoc();
    // 4. Store user session information
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    return true;
  } else {
    return false;
  }

  // Close connection
  $conn->close();
}

// Example usage (same as before)
if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
  $username = $_POST['login_username'];
  $password = $_POST['login_password'];

  if (loginUser($username, $password)) {
    header("Location: protected_page.php");
    exit();
  } else {
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}
?>


<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the inputs (Basic example - enhance this for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]+$/", $username)) {
    $error = "Invalid username.  Please enter a valid email or alphanumeric username.";
  } else {
    // SQL query to check the credentials
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($db_host, $query);

    if (mysqli_num_rows($result) > 0) {
      // User found, verify the password
      $row = mysqli_fetch_assoc($result);
      if (password_verify($password, $row["password"])) {
        // Password matches, login successful
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;
        // Redirect to a welcome page or desired location
        header("Location: welcome.php"); // Replace with your welcome page URL
        exit(); // Stop further execution
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "User not found.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" placeholder="your_email@example.com" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>
</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login based on provided credentials.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|false  The user ID if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // **Database Connection (Replace with your actual connection details)**
  // This is a placeholder.  You *must* replace this with your database connection.
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

  // **Authentication Logic**
  //  This is where you'd perform the actual authentication.
  //  This example uses a hardcoded user and password for demonstration only.
  //  You should *never* do this in a real application.  Use a secure hashing method.

  $hashedPassword = "hashed_password_here"; //Replace with the hashed password from your database.  This is just a placeholder.

  if ($username == 'admin' && $password == 'password') {
    // Login successful!
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      // Store the user's session ID
      session_start(); // Make sure session is started!
      $_SESSION['userId'] = $user['id'];
      return $user['id'];
    } else {
      return false; // User not found
    }
  } else {
    return false; // Invalid credentials
  }
}

// **Example Usage (for testing)**
//This code is just to demonstrate how to use the function
// in a test environment or during development.

// Simulate a login attempt
$username = 'testuser';
$password = 'testpassword';

$userId = loginUser($username, $password);

if ($userId) {
  echo "Login successful! User ID: " . $userId . "<br>";
  // Redirect to a protected page or perform other actions here.
  // For example:
  // header("Location: protected_page.php");
  // exit();

} else {
  echo "Login failed.  Invalid username or password.<br>";
}

?>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security!)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } elseif (filter_var($username, FILTER_VALIDATE_EMAIL) === false) {
        $error_message = "Invalid email address.";
    } else {
        // Database connection
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to check the credentials
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // User found!  Set session variables.  This is crucial.
            session_start(); // Start the session
            $_SESSION["username"] = $username; // Store the username in the session
            header("Location: welcome.php"); // Redirect to a welcome page
            exit(); // Stop further script execution
        } else {
            $error_message = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>

<h1>User Login</h1>

<?php
if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
}
?>

<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" value="<?php echo isset($username) ? $username : ''; ?>">
    Password: <input type="password" name="password" value="<?php echo isset($password) ? $password : ''; ?>">
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials - IMPORTANT: Replace with your actual credentials!
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";


// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query - Using prepared statements for security
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // Bind parameters to the SQL query - IMPORTANT for security
  $stmt->bind_param("ss", $username, $password);

  // Execute the query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  // Get the result
  $result = $stmt->get_result();

  // Check if any rows were returned
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Successful login
    $_SESSION['user_id'] = $row['id']; // Store user ID in session
    $_SESSION['username'] = $username;
    return true;
  } else {
    // Login failed
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage:

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
  echo "Welcome, " . $_SESSION['username'] . "!";
} else {
  // Check if the form has been submitted
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input (VERY IMPORTANT - Add more validation here)
    if (empty($username) || empty($password)) {
      echo "Username and password cannot be empty.";
    } else {
      // Attempt to login
      if (loginUser($username, $password)) {
        echo "Login successful!";
        // Redirect to a protected page, or display a welcome message
        header("Location: /protected_page.php"); // Replace with your protected page URL
        exit();
      } else {
        echo "Invalid username or password.";
      }
    }
  } else {
    // Display the login form
    echo "<h2>Login</h2>";
    echo "<form method='post'>";
    echo "<label for='username'>Username:</label><br>";
    echo "<input type='text' id='username' name='username'><br>";
    echo "<label for='password'>Password:</label><br>";
    echo "<input type='password' id='password' name='password'><br>";
    echo "<button type='submit'>Login</button>";
    echo "</form>";
  }
}

?>


<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format for username.";
  } else {
    // Database query
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = '" . $conn->real_escape_string($username) . "' AND password = '" . $conn->real_escape_string($password) . "'";

    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error = "Invalid username or password.";
    } else {
      // If the query is successful, set a session variable
      session_start();
      $_SESSION["username"] = $username;
      $error = "Login successful!";
      // Redirect to a secure page (e.g., dashboard)
      header("Location: dashboard.php");  // Replace dashboard.php with your secure page
      exit();
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
  } ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_username = "your_db_username";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve the user's password hash
  $query = "SELECT password_hash, user_id FROM users WHERE username = '$username'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password_hash'];
    $userId = $row['user_id'];

    // Verify the password
    if (password_verify($password, $hashedPassword)) {
      // Authentication successful
      session_start(); // Start a session to store user information

      // Store user information in the session
      $_SESSION['user_id'] = $userId;
      $_SESSION['username'] = $username;
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}

// Example Usage (in your login form HTML)
//  <form method="post" action="login.php">
//   Username: <input type="text" name="username">
//   Password: <input type="password" name="password">
//   <button type="submit">Login</button>
// </form>

// PHP code to handle the form submission
//  <?php
//  if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    $username = $_POST["username"];
//    $password = $_POST["password"];

//    if (loginUser($username, $password)) {
//      // Login successful, redirect to your homepage or desired page
//      header("Location: homepage.php"); // Replace with your homepage URL
//      exit();
//    } else {
//      // Login failed
//      $error = "Invalid username or password.";
//    }
//  }
//?>
// ... (HTML for displaying error message if needed)


// Example of using password_hash() and password_verify() - IMPORTANT!
// Before using this code, you MUST hash the user's password before storing it in the database.
// This example demonstrates the correct way to handle password storage.
// In a real application, you should use a strong hashing algorithm like bcrypt or argon2.

//  $hashedPassword = password_hash("your_password", PASSWORD_DEFAULT);
//  // Store $hashedPassword in the users table.

?>


<?php

// Database Credentials - Replace with your actual credentials!
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input - Basic validation (Stronger validation is recommended in production)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (strlen($username) < 3) {
    $error_message = "Username must be at least 3 characters long.";
  } 
  // Add more validation rules here, like checking for valid characters, password strength, etc.


  // Database connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?"; // Use prepared statements for security

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("ss", $username, $password);

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // User found, set session variables
      session_start();
      $_SESSION["username"] = $username;  // Store the username
      $user_data = $result->fetch_assoc(); // Get user data
      $_SESSION['user_id'] = $user_data['id']; // Store the user ID (optional but recommended)

      // Redirect to a protected page or display a success message
      header("Location: /protected_page.php"); // Replace with your protected page URL
      exit; 
    } else {
      $error_message = "Invalid username or password.";
    }
  } else {
    $error_message = "Error executing query. " . $conn->error;
  }
} else {
  // If the form hasn't been submitted, display the login form
  // This is usually handled by your HTML template.  The code below is just to
  // show what's happening on the server side when the page is first loaded.
  $error_message = ""; 
}

// Close the connection
$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (!empty($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    Password: <input type="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login with username and password verification.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|string Returns the user ID if login is successful, 
 *                   or an error message string if login fails.
 */
function loginUser(string $username, string $password) {
  // **IMPORTANT:  Replace this with your actual database connection and user data.**
  // This is a simplified example and won't work without a database.
  $validUsers = [
    'john.doe' => 'password123',
    'jane.smith' => 'securePass42',
  ];

  // Sanitize input (important for security)
  $username = strtolower($username); // Convert to lowercase for consistency

  if (isset($validUsers[$username])) {
    // Verify password
    if ($validUsers[$username] === $password) {
      // Login successful
      // Generate a session ID (for security - you'd normally use a more robust method)
      $sessionId = bin2hex(random_bytes(32)); // Generate a secure session ID

      // Store session ID in session variable (for simplicity, but proper session management is crucial)
      session_start();
      $_SESSION['userId'] = $username; // Store the username, not the password!
      $_SESSION['sessionId'] = $sessionId; 

      return $username; // Return the username (or user ID if you have one)
    } else {
      return "Incorrect password.";
    }
  } else {
    return "User not found.";
  }
}


// **Example Usage:**

// Get username and password from form submission (example)
// $username = $_POST['username'];
// $password = $_POST['password'];

// if (isset($username) && isset($password)) {
//   $loginResult = loginUser($username, $password);
//   if ($loginResult === 'Incorrect password.') {
//     echo "<p style='color:red;'>Incorrect password.</p>";
//   } elseif ($loginResult === 'User not found.') {
//     echo "<p style='color:red;'>User not found.</p>";
//   } else {
//     echo "<p>Login successful!  User ID: " . $loginResult . "</p>";
//     // Redirect to a secure page after login
//   }
// } else {
//   echo "<p>Please fill in the form.</p>";
// }


?>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

  // Execute the query
  $result = $conn->query($sql);

  // Check if a user was found
  if ($result->num_rows > 0) {
    // User found, you can now process their session or redirect them to a protected area
    // Example:  Store user data in a session
    session_start();
    $user_data = $result->fetch_assoc(); // Fetch the user data as an associative array
    $_SESSION['user_id'] = $user_data['id']; // Assuming 'id' is the user ID column
    $_SESSION['username'] = $user_data['username'];

    // Redirect to a protected page (e.g., the dashboard)
    header("Location: dashboard.php"); // Replace dashboard.php with your protected page
    exit(); // Important to stop further execution

  } else {
    // User not found
    echo "<p>Invalid username or password.</p>";
  }

  $conn->close();
}

// Example usage (Handle the login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Important for security!)
  if (empty($username) || empty($password)) {
    echo "<p>Username and password fields cannot be empty.</p>";
  } else {
    // Call the loginUser function
    loginUser($username, $password);
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" placeholder="Enter your username" required>
    <br><br>
    Password: <input type="password" name="password" placeholder="Enter your password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual values
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameter
  $stmt->bind_param("s", $username);

  // Execute the statement
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Verify the password
      if (password_verify($password, $row["password"])) {
        // Login successful
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        return true;
      } else {
        // Password mismatch
        return false;
      }
    } else {
      // User not found
      return false;
    }
  } else {
    // Error executing the query
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (Demonstration -  This is for testing and should be replaced with your form input)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a protected page
    header("Location: /protected_page.php"); // Replace with your protected page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">

    <button type="submit">Login</button>
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
  // Get the username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (preg_match("/^[a-zA-Z0-9_]+$/", $username) == 0) {
    $error_message = "Username must contain only letters, numbers, and underscores.";
  } else {
    // Query the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query (use prepared statements for security!)
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind the parameters (for security)
      $stmt->bind_param("s", $username, $password);

      // Execute the query
      if ($stmt->execute()) {
        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
          // User found, set session variables
          session_start();
          $_SESSION["username"] = $username;
          $result->fetch_assoc(); // Get the user data
          $stmt->close();

          // Redirect to a secure page or display a success message
          header("Location: secure_page.php"); // Replace with your secure page
          exit();
        } else {
          $error_message = "Incorrect username or password.";
          $stmt->close();
        }
      } else {
        $error_message = "Query execution failed.";
        $stmt->close();
      }
    } else {
      $error_message = "Error preparing statement.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <?php if (!empty($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    Password: <input type="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual credentials!
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ?";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);  // "s" indicates a string parameter

  // Execute the query
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Verify the password
      if (password_verify($password, $row["password"])) {
        // Successful login
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        return true;
      } else {
        // Incorrect password
        return false;
      }
    } else {
      // User not found
      return false;
    }
  } else {
    // Query error
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage:

// 1. Form submission (in your HTML form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to your welcome page or desired location
    header("Location: welcome.php"); // Replace with your welcome page
    exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Session handling
session_start();

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Bind the parameters for security (important to prevent SQL injection)
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // Set session variables for the logged-in user
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      return true; // Login successful
    } else {
      return false; // No user found or incorrect password
    }
  } else {
    return false; // Error executing query
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (Demonstration -  This would typically be within a form submission)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    echo "Login successful! You are now logged in as " . $_SESSION['username'];
    // Redirect to a protected page or display a welcome message
    header("Location: protected_page.php"); // Replace with your protected page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check for the username and password
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the statement
    if ($stmt->execute()) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        // User found, retrieve user data
        $user = $result->fetch_assoc();
        // Set session variables to store the user information
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true; // Login successful
      } else {
        // User not found
        return false;
      }
    } else {
      // Error executing statement
      error_log("Query error: " . $conn->error); // Log the error for debugging
      return false;
    }
  } else {
    // Error preparing statement
    error_log("Statement preparation error");
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (Form Handling - Not a complete form, just demonstrating the login function)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a secure page or display a welcome message
    header("Location: /welcome.php"); // Replace with your welcome page URL
    exit();
  } else {
    // Handle login failure (display error message)
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $stmt = $conn->prepare($sql);

  // Execute the query
  $stmt->execute();

  // Store the result
  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $user['password'])) {
      // Login successful - set session variables
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];

      return true; // Indicate successful login
    } else {
      return false; // Indicate incorrect password
    }
  } else {
    return false; // Indicate user not found
  }

  // Close the database connection
  $stmt->close();
  $conn->close();
}


// Example usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - added basic validation)
  if (empty($username) || empty($password)) {
    echo "<p style='color:red;'>Username and password cannot be empty.</p>";
  } else {
    // Call the loginUser function
    if (loginUser($username, $password)) {
      // Login successful - redirect to the user's dashboard or homepage
      header("Location: dashboard.php");
      exit();
    } else {
      echo "<p style='color:red;'>Invalid username or password.</p>";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
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
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and Password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    // Check if the query returned any results
    if ($result->num_rows > 0) {
      // Get the data from the result
      $user_data = $result->fetch_assoc();

      // Verify the password
      if (password_verify($password, $user_data["password"])) {
        // Successful login - set session variables
        session_start();
        $_SESSION["user_id"] = $user_data["id"];
        $_SESSION["username"] = $username;
        echo "Login successful! <a href='welcome.php'>Welcome, " . $username . "</a>"; // Redirect to a welcome page
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "User not found.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
  } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here</a>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to authenticate user credentials
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $query = "SELECT id, username, password FROM users WHERE username = ?";
  $stmt = $conn->prepare($query);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // Bind the parameters
  $stmt->bind_param("s", $username);  // "s" indicates a string parameter

  // Execute the query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  // Get the result
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    return false; // User not found
  }

  $user = $result->fetch_assoc();
  $stmt->close();

  // Verify password
  if (password_verify($password, $user['password'])) {
    // Successful login
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    return true;
  } else {
    // Incorrect password
    return false;
  }
}

// Example usage (in a form submission, for example)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with the URL of your protected page
    exit();
  } else {
    // Login failed - Display an error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


function insertUser($username, $password) {
  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $query = "INSERT INTO users (username, password) VALUES (?, ?)";
  $stmt = $conn->prepare($query);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  $stmt->bind_param("ss", $username, $hashed_password); // "ss" for string parameters

  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  $stmt->close();
  $conn->close();
}


<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email address.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ? LIMIT 1";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind the parameters
      $stmt->bind_param("ss", $username, $password);

      // Execute the query
      $stmt->execute();

      // Get the result
      $result = $stmt->get_result();

      // Check if a user was found
      if ($result->num_rows == 1) {
        // User found, set session variables
        session_start();
        $user_id = $result->fetch_assoc()["id"];
        $_SESSION["user_id"] = $user_id;
        $_SESSION["username"] = $username;

        // Redirect to a protected page
        header("Location: protected_page.php"); // Replace with your protected page
        exit(); // Important to stop further execution
      } else {
        $error_message = "Invalid username or password.";
      }
    } else {
      $error_message = "Error preparing statement.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" placeholder="Your Email Address">
    Password: <input type="password" name="password" placeholder="Your Password">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Link to registration page -->
</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check for the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found - Fetch data from the result set
    $user = $result->fetch_assoc();

    // Create a session
    session_start();

    // Store user data in the session
    $_SESSION['user_id'] = $user['id'];  // Assuming 'id' is the user's ID column
    $_SESSION['username'] = $user['username']; // Store username as well

    // Redirect to a secure page (e.g., the homepage)
    header("Location: welcome.php");
    exit(); // Important: Stop further script execution
  } else {
    // User not found
    return false;
  }

  $conn->close();
}

// Example Usage (handling login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (very important - see explanation below)
  if (empty($username) || empty($password)) {
    $error = "Username and Password must be filled.";
  } else {
    // Call the loginUser function
    $login_result = loginUser($username, $password);

    if ($login_result === false) {
      $error = "Invalid username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
  } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    Password: <input type="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login using a simple (and potentially insecure) 
 * username/password comparison.
 *
 * **WARNING:** This is a basic example for demonstration purposes only.
 *  For production environments, use a secure authentication system 
 *  like hashing passwords with bcrypt or Argon2, and consider using 
 *  a library or framework for managing user sessions and security.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser(string $username, string $password)
{
    // *** IMPORTANT SECURITY WARNING:  Do NOT store passwords in plain text. ***
    // This is just for demonstration purposes.  A real system would hash the password.

    // ** Replace with your database connection code**
    $db_host = 'localhost';
    $db_name = 'your_database_name';
    $db_user = 'your_database_user';
    $db_password = 'your_database_password';

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input -  This is extremely important to prevent SQL injection!
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    // SQL Query -  Be very careful with this.  Validate inputs before constructing queries.
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Compare passwords -  Again, *never* store passwords in plain text.
        if (password_verify($password, $row['password'])) {
            // Login successful!

            // ***  Set Session (Replace with your preferred session management method) ***
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;

            return true;
        } else {
            // Password mismatch
            return false;
        }
    } else {
        // No user found
        return false;
    }

    // Close connection
    $conn->close();
}


// --- Example Usage ---
// Assuming you have a form that captures the username and password:

// If the login is successful:
// echo "Login successful!  Welcome, " . $_SESSION['username'] . "!";

// If the login fails:
// echo "Login failed. Please check your credentials.";


?>


<?php

// Database credentials (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    // User found, set session variables for authentication
    $row = $result->fetch_assoc();
    $_SESSION["user_id"] = $row["id"];
    $_SESSION["username"] = $row["username"];
    // You might want to store other user information here if needed
    
    // Redirect to a secure page
    header("Location: secure_page.php"); // Replace 'secure_page.php' with the appropriate URL
    exit();
  } else {
    // User not found
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form action="login.php" method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle the login process
function login($username, $password) {
  // Connect to the database
  $conn = new mysqli($username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the query
  $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);  // "s" indicates a string parameter
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if a user with the given username exists
  if ($result->num_rows == 0) {
    return false; // User doesn't exist
  }

  $user = $result->fetch_assoc();
  $stored_password = $user['password'];

  // Verify the password
  if (password_verify($password, $stored_password)) {
    // Password is correct, set session variables
    session_start();
    $_SESSION['username'] = $username;
    return true; // Login successful
  } else {
    return false; // Password incorrect
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage (handling login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (login($username, $password)) {
    // Redirect to a secure page or display a success message
    header("Location: secure_page.php"); // Replace with your secure page
    exit();
  } else {
    // Display an error message
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Link to a registration page -->

</body>
</html>


     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
     // Then, insert $hashed_password into the database.
     

3. **Input Validation and Sanitization:**
   - **ALWAYS** validate and sanitize user input *before* using it in any query or operation. This includes:
     - Checking the data type and format (e.g., ensure the username is alphanumeric).
     - Using `filter_var()` to sanitize data (e.g., `filter_var($username, FILTER_SANITIZE_STRING)`).
     - Escaping any output before displaying it to prevent XSS vulnerabilities.

4. **CSRF Protection:** Implement Cross-Site Request Forgery (CSRF) protection. This is a common web security vulnerability that involves attackers tricking a user's browser into submitting malicious requests to your website without their knowledge.

5. **Session Management:**
   -  Set an expiration time for session variables to prevent session hijacking.
   -  Implement session regeneration after login to further enhance security.

6. **Logging:** Implement logging to track login attempts (successful and failed) for security auditing and troubleshooting.

7. **Rate Limiting:**  Implement rate limiting to prevent brute-force attacks (e.g., limiting the number of login attempts from a single IP address).

8. **Testing:** Thoroughly test your login functionality, including:
   - Successful login
   - Failed login (invalid credentials)
   - Brute-force attack attempts
   - CSRF attacks

9. **HTTPS:**  Always use HTTPS to encrypt the communication between the user's browser and your server.

**Example of PDO (for modern development):**



<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement (important for security - prevents SQL injection)
  $stmt = $conn->prepare($query);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // User found, set session variables
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];
      return true; // Login successful
    } else {
      return false; // User not found
    }

  } else {
    return false; // Query error
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}



// Example usage:
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (VERY IMPORTANT - add more robust validation here)
  if (empty($username) || empty($password)) {
    echo "Username and password must be filled.";
  } else {
    // Attempt to login
    if (loginUser($username, $password)) {
      // Redirect to a secure page or display a welcome message
      header("Location: /welcome.php");  // Adjust path as needed
      exit();
    } else {
      echo "Invalid username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

/**
 * Logs in a user based on their username and password.
 *
 * @param string $username The username.
 * @param string $password The password.
 * @return int|false User ID if successful, false if not.
 */
function loginUser(string $username, string $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username FROM users WHERE username = ? AND password = ?;";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // 'ss' indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      $userId = $user['id'];
      return $userId;
    } else {
      return false; // User not found
    }
  } else {
    // Handle query errors
    echo "Query failed: " . $conn->error;
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// --- Example Usage ---
// Get username and password from the form (or wherever you get them)
$username = $_POST['username'];
$password = $_POST['password'];

// Check if the login form was submitted
if (isset($_POST['login'])) {
  // Call the login function
  $userId = loginUser($username, $password);

  // Check if the login was successful
  if ($userId) {
    // Redirect the user to a logged-in page, or set a session variable
    session_start();
    $_SESSION['userId'] = $userId;
    $_SESSION['username'] = $username;
    echo "Login successful! You are logged in as " . $username;
    // Redirect to a logged-in page
    header("Location: /loggedin.php"); // Replace with your logged-in page URL
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>

<!-- HTML Form (for simplicity, placed here) -->
<form method="post" action="">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username">
  <br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password">
  <br>
  <button type="submit" name="login">Login</button>
</form>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - discussed below)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password are required.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      $error_message = "Connection failed: " . $conn->connect_error;
    } else {
      // SQL query to check username and password
      $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
      $result = $conn->query($sql);

      if ($result->num_rows == 1) {
        // User found, check password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
          // Password matches, login successful
          session_start();
          // Store user information in session
          $_SESSION["user_id"] = $row["id"];
          $_SESSION["username"] = $username;

          // Redirect to a protected page (e.g., dashboard)
          header("Location: dashboard.php"); // Replace with your protected page
          exit();
        } else {
          // Password doesn't match
          $error_message = "Incorrect password.";
        }
      } else {
        // User not found
        $error_message = "User not found.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (!empty($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
    Password: <input type="password" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p> <!-- Link to your registration page -->

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Login form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (basic example - improve this for production)
  if (empty($username) || empty($password)) {
    $error = "Username and Password are required.";
  } else {
    // Database query
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'"; // Use prepared statements for security!
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error = "Invalid username or password.";
    }

    if ($result->num_rows > 0) {
      // User found, set session variables
      session_start();
      $_SESSION["username"] = $username;
      // Redirect to a secure page or display a welcome message
      header("Location: welcome.php"); // Assuming you have a welcome.php page
      exit();
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (!empty($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" />
    Password: <input type="password" name="password" />
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php
session_start();

/**
 * Logs in a user based on username and password.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool True if login successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // Simulate a database or user store.  In a real application,
  // this would connect to a database to retrieve user information.
  $users = [
    'john.doe' => 'password123',
    'jane.smith' => 'securePass',
  ];

  // Check if the username exists
  if (isset($users[$username])) {
    // Verify the password
    if (password_verify($password, $users[$username])) {
      // Login successful
      $_SESSION['username'] = $username;  // Store username in session
      return true;
    } else {
      // Password does not match
      return false;
    }
  } else {
    // Username does not exist
    return false;
  }
}

/**
 *  Demonstration/Testing Function
 *  This function will show the login form and process the login.
 */
function showLogin() {
    // Start the session
    session_start();

    //Check if user is already logged in
    if (isset($_SESSION['username'])) {
        header("Location: /success.php"); // Redirect to a success page
        exit();
    }

    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<title>Login</title>";
    echo "</head>";
    echo "<body>";
    echo "<h1>Login</h1>";

    // Check if there's an error message
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>" . htmlspecialchars($_SESSION['error']) . "</p>";
        unset($_SESSION['error']); // Clear error message after displaying
    }

    echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
    echo "<label for='username'>Username:</label><br>";
    echo "<input type='text' id='username' name='username' required><br><br>";
    echo "<label for='password'>Password:</label><br>";
    echo "<input type='password' id='password' name='password' required><br><br>";
    echo "<button type='submit'>Login</button>";
    echo "</form>";
}


//Example Usage (Demonstration)
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        echo "<p>Login successful!</p>";
        // Redirect to a protected page or homepage
        header("Location: /home.php");
        exit();
    } else {
        // Handle login failure
        $_SESSION['error'] = "Invalid username or password.";
        showLogin();
    }
} else {
    // Show the login form initially
    showLogin();
}
?>


<?php

// Database credentials - REPLACE WITH YOUR ACTUAL CREDENTIALS!
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL Query to check login
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User exists, retrieve data
    $row = $result->fetch_assoc();
    // Set session variables to track the user
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];

    // Redirect to a protected page or display a welcome message
    return true; // Indicate successful login
  } else {
    // User does not exist
    return false; // Indicate failed login
  }

  $conn->close();
}

// Example usage (handling the login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a protected page or display a welcome message
    header("Location: protected_page.php"); // Example: Redirect to a protected page
    exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

//  Example login form (for testing)
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Hash the password for security (using password_hash)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database to check credentials
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = mysqli_query($db_host, $sql);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      // Verify the password
      if (password_verify($password, $row["password"])) {
        // If passwords match, set session variables
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;
        echo "Login successful! <a href='welcome.php'>Welcome, " . $username . "</a>"; // Redirect to a welcome page
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "Username not found.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
  }
  ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

/**
 * Login function to authenticate users.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|false  The user ID if login is successful, or false if failed.
 */
function login(string $username, string $password) {
  // **IMPORTANT: NEVER STORE PASSWORDS IN PLAIN TEXT!**
  // This example demonstrates the *concept* of authentication.
  // In a real application, you should hash passwords using a secure algorithm
  // like bcrypt or Argon2.

  // **Simulated User Database (Replace with your actual database connection)**
  $users = [
    'john.doe' => 'password123',
    'jane.smith' => 'securepass',
    'admin' => 'adminpassword'
  ];

  // Check if the username exists in the database
  if (isset($users[$username])) {
    // Compare the provided password with the stored hashed password
    if (password_verify($password, $users[$username])) {  // Use password_verify for secure comparison
      // Login successful
      // Generate a session token (for security)
      $session_token = bin2hex(random_bytes(32));

      // Store the session token in a session variable.
      // **This is a simplified example. In a production environment,
      // use a more robust session management system like PHP's built-in
      // session functions or a dedicated session library.**
      $_SESSION['user_id'] = $username; // Store the username as the user_id.  Better to store a unique ID.
      $_SESSION['session_token'] = $session_token;


      return $username; // Return the username (or user ID)
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // Username does not exist
    return false;
  }
}


// **Example Usage (For testing)**
//  1.  Get username and password from form submission (example)

//  if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    $username = $_POST["username"];
//    $password = $_POST["password"];
//    $loginResult = login($username, $password);

//    if ($loginResult) {
//      echo "Login successful! User ID: " . $loginResult;
//    } else {
//      echo "Login failed.  Invalid username or password.";
//    }
//  }

//  2.  A simple example for demonstration:
//  $user_id = login('john.doe', 'password123');
//  if ($user_id) {
//      echo "User login successful. User ID: " . $user_id;
//  } else {
//      echo "Login failed.";
//  }

?>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_SANITIZE_STRING)) {
    $error_message = "Invalid username. Please use only alphanumeric characters.";
  } elseif (!filter_var($password, FILTER_SANITIZE_STRING)) {
    $error_message = "Invalid password.  Please use only alphanumeric characters.";
  }

  // Database connection
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Query to check username and password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  // Check if the query returned any results
  if ($result->num_rows > 0) {
    // User found, set session variables
    session_start();
    $_SESSION["username"] = $username; // Store username in session
    $conn->close();

    // Redirect to a protected page or display a success message
    header("Location: secure_page.php"); // Redirect to a secure page.
    exit();

  } else {
    // User not found
    $conn->close();
    $error_message = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p> <!-- Link to registration page -->

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to validate user input
function validateInput($username, $password) {
  // Basic validation - adjust as needed for your security requirements
  return true; // Always true for this example - *NEVER* use this in a production environment!
}

// Function to handle user login
function loginUser($username, $password) {
  // 1. Check if the username and password are provided
  if (empty($username) || empty($password)) {
    return "Username and password are required.";
  }

  // 2. Database connection (using PDO for better security)
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Throw exceptions on errors
  } catch (PDOException $e) {
    return "Error connecting to the database: " . $e->getMessage();
  }

  // 3. SQL query (SELECT query - adjust the column names if necessary)
  $sql = "SELECT * FROM users WHERE username = :username AND password = :password";  // Use parameterized query

  // 4. Prepare and execute the query
  try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // 5. Check if a row was returned (user found)
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      // User found - set session variables
      $_SESSION['username'] = $username; // Store username in session
      return "Login successful!";
    } else {
      // User not found
      return "Invalid username or password.";
    }
  } catch (PDOException $e) {
    return "Error executing query: " . $e->getMessage();
  }
}


// --- Example Usage (This is just for demonstration) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Call the loginUser function
  $loginResult = loginUser($username, $password);

  // Display the result
  echo "<p>" . $loginResult . "</p>";

  // Redirect to a different page after login
  if ($loginResult == "Login successful!") {
    header("Location: welcome.php"); // Replace 'welcome.php' with your desired page
    exit();
  }
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php
session_start();

/**
 * Handles user login.
 *
 * @param string $username The username to authenticate.
 * @param string $password The password to authenticate.
 * @return bool True if login was successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // In a real application, you would store the password securely (hashed)
  // and compare the hashed password with the provided password.
  // This example demonstrates a simple (and insecure) comparison for demonstration purposes.

  // Hardcoded credentials (FOR DEMO ONLY - NEVER DO THIS IN PRODUCTION!)
  $validUsers = [
    'john.doe' => 'password123',
    'jane.smith' => 'securePass42'
  ];

  if (isset($validUsers[$username])) {
    if ($validUsers[$username] == $password) {
      // Login successful
      $_SESSION['username'] = $username;
      return true;
    } else {
      return false; // Incorrect password
    }
  } else {
    return false; // User not found
  }
}

// --- Example Usage (Demonstration) ---

// Check if the user is already logged in
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Redirect to a protected page
    header("Location: /protected_page.php");  // Replace with your actual protected page
    exit(); // Important to stop further execution after redirection
  } else {
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" placeholder="john.doe" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" name="login" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate inputs (Important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and Password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]+$/", $username)) {
    $error = "Invalid username. Please use a valid email or alphanumeric characters only.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check the login
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    // Check if the query was successful and if a user was found
    if ($result->num_rows > 0) {
      // User found, set session variables and redirect
      session_start();
      $_SESSION["username"] = $username; // Store the username in the session
      // Optionally store other user data here, like roles/permissions
      // if you have a more complex system.

      // Redirect the user to the home page or protected area
      header("Location: home.php"); // Replace 'home.php' with your desired page
      exit();
    } else {
      $error = "Incorrect username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
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
    // Get username and password from form input
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important to prevent SQL injection and other issues)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Connect to the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query (using prepared statements -  VERY IMPORTANT!)
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

        // Bind the values to the query
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings

        // Execute the query
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // User found, set session variables
                session_start(); // Start a session if not already started
                $_SESSION["username"] = $username;
                $result->fetch_assoc(); // Fetch the user data (for consistency)

                // Redirect to a protected page or display a success message
                header("Location: /protected_page.php"); // Replace with your protected page URL
                exit();

            } else {
                $error = "Invalid username or password.";
            }

        } else {
            $error = "Query execution failed: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

<h1>Login</h1>

<?php
if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    // Execute the statement
    if ($stmt->execute() === TRUE) {
        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            // Successful login
            session_start();
            // Store user data in session
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['username'] = $user_data['username'];
            return true;
        } else {
            // User not found
            return false;
        }
    } else {
        // Error executing the query
        return false;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Example usage (within a form or other input method)
// $username = $_POST['username'];
// $password = $_POST['password'];
// if (loginUser($username, $password)) {
//     // Login successful, redirect to a secure page
//     header("Location: secure_page.php");
//     exit();
// } else {
//     // Login failed, display an error message
//     echo "Invalid username or password.";
// }

?>


<?php

// Database credentials - Replace with your actual values
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // 'ss' indicates two string parameters
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if any rows were returned
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();  // Fetch the first row as an associative array
    return $user; // Return user data if login is successful
  } else {
    return false; // Return false if login fails
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (Demonstration)

// Get username and password from form (or wherever you get them)
$username = $_POST['username'];
$password = $_POST['password'];

// Call the login function
$loggedInUser = loginUser($username, $password);

// Check if the login was successful
if ($loggedInUser) {
  // User is logged in - Now you can redirect to a different page,
  // display a welcome message, or access user data.
  echo "Welcome, " . $loggedInUser['username'] . "!";
  // You can also do things like:
  // session_start();
  // $_SESSION['user_id'] = $loggedInUser['id']; // Example: Store user ID in session
} else {
  // Login failed
  echo "Invalid username or password.";
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form action="" method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the username and password from the form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate the input (important for security!)
    if (empty($username) || empty($password)) {
        $error_message = "Username and Password must be filled.";
    } else {
        // Query the database
        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query - **IMPORTANT:  Use parameterized queries for security!**
        $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind the parameters
            $stmt->bind_param("ss", $username, $password);

            // Execute the statement
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // User found
                $row = $result->fetch_assoc();
                $user_id = $row["id"];
                $username = $row["username"];
                // Set session variables
                session_start();
                $_SESSION["user_id"] = $user_id;
                $_SESSION["username"] = $username;
                echo "Login successful!";
                // Redirect to a protected page (e.g., dashboard)
                header("Location: dashboard.php"); // Replace with your protected page
                exit(); // Important to stop further execution
            } else {
                $error_message = "Invalid username or password.";
            }
        } else {
            $error_message = "Error preparing the statement.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>

    <h1>User Login</h1>

    <?php if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        Username: <input type="text" name="username">
        Password: <input type="password" name="password">
        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual values
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to fetch user data
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, create a session
    // Set a session variable to indicate login
    session_start();
    $_SESSION['username'] = $username; // Store username in session
    $_SESSION['user_id'] = $result->fetch_assoc()['id']; // Example: Store user ID as well
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}


// Example Usage (from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // User logged in successfully
    header("Location: /welcome.php"); // Redirect to a welcome page (replace with your URL)
    exit();
  } else {
    // Login failed
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

// HTML Form for Login (to be embedded in your HTML page)
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Form handling (POST method)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Basic - should be more robust in a real application)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } else {
        // Authenticate against the database
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL Query (Using prepared statements for security!)
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // User found, set session variables
                session_start();
                $_SESSION["username"] = $username;
                $stmt->close();
                $conn->close();
                header("Location: welcome.php"); // Redirect to welcome page
                exit();
            } else {
                $error_message = "Invalid username or password.";
                $stmt->close();
                $conn->close();
            }
        } else {
            $error_message = "Error preparing statement.";
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h1>Login</h1>

    <?php
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username" />
        Password: <input type="password" name="password" />
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to check login credentials
function login($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $query = "SELECT id, username, password FROM users WHERE username = ?";

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username); // "s" indicates a string parameter

    // Execute the query
    if ($stmt->execute() === TRUE) {
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $row['password'])) {
                // Successful Login
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $row["username"];
                return true;
            } else {
                // Incorrect password
                return false;
            }
        } else {
            // No user found
            return false;
        }
    } else {
        // Query error
        return false;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Example Usage (Form handling - this is simplified)

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (VERY IMPORTANT for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Call the login function
        $login_result = login($username, $password);

        if ($login_result) {
            // Redirect to a protected page
            header("Location: protected_page.php"); // Replace with your protected page URL
            exit();
        } else {
            $error = "Incorrect username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h2>Login</h2>

    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username" placeholder="Enter your username"><br><br>
        Password: <input type="password" name="password" placeholder="Enter your password"><br><br>
        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php
session_start();

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

/**
 * Logs in a user.
 *
 * @param string $username The username to log in.
 * @param string $password The password.
 * @return bool True if login successful, false otherwise.
 */
function loginUser($username, $password)
{
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check the credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, set session variables
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id']; // Assuming you have a user ID
        return true;
    } else {
        return false;
    }

    $conn->close();
}

/**
 * Logs out the current user.
 */
function logoutUser() {
    // Destroy the session
    session_unset();
    session_destroy();
}

// Example usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security - see explanation below)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Attempt to log in
        if (loginUser($username, $password)) {
            // Login successful, redirect to a different page
            header("Location: user_dashboard.php"); // Redirect to a secure page
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>

    <h2>User Login</h2>

    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username" <?php if (isset($username)) echo 'value="' . htmlspecialchars($username) . '"'; ?> />
        <br/>
        Password: <input type="password" name="password" />
        <br/>
        <button type="submit">Login</button>
    </form>

    <br/>
    <a href="logout.php">Logout</a>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Login form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (IMPORTANT:  Always sanitize/validate user input!)
    $username = trim($username); // Remove leading/trailing whitespace
    $password = trim($password);


    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);  // Use prepared statements for security!
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // User found - set session variables
            $row = $result->fetch_assoc();
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];

            // Redirect to a protected page or display a success message
            header("Location: protected_page.php"); // Or any other appropriate page
            exit();

        } else {
            // User not found
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Error executing query.";
    }
}

// Display the login form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php
if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" placeholder="Your Username"><br><br>
    Password: <input type="password" name="password" placeholder="Your Password"><br><br>
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database Credentials (Replace with your actual credentials!)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (mysqli_stmt_prepare($conn, "SELECT username, password FROM users WHERE username = ?")) {
    if (mysqli_stmt_bind_param($conn, "s", $username)) {
      mysqli_stmt_execute($conn);
      $result = mysqli_stmt_get_result($conn);
      if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row["password"])) {
          // Password matches!  Set session variables.
          session_start(); // Start the session if it's not already
          $_SESSION["username"] = $username;
          $error_message = "";  // Clear any previous errors
          header("Location: welcome.php"); // Redirect to a welcome page
          exit(); // Stop further execution
        } else {
          $error_message = "Incorrect password.";
        }
        mysqli_stmt_close($conn); // Close the statement
      } else {
        $error_message = "Incorrect username.";
      }
      mysqli_stmt_close($conn);
    } else {
        $error_message = "Error preparing statement.";
    }
  } else {
    $error_message = "Error preparing statement.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <style>
    .error {
      color: red;
    }
  </style>
</head>
<body>

  <h2>Login</h2>

  <?php if (!empty($error_message)) { ?>
    <p class="error"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" />
    <br />
    Password: <input type="password" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>" />
    <br />
    <button type="submit">Login</button>
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL Query
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if (mysqli_stmt_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)")) {
      if (mysqli_stmt_bind_param($conn, "ss", $username, $hashed_password)) {
        mysqli_stmt_execute($conn);
        mysqli_stmt_close($conn);

        // Login successful - redirect to login.php
        header("Location: login.php");
        exit();
      } else {
        $error_message = "Error preparing statement.";
      }
    } else {
      $error_message = "Error preparing statement.";
    }
  }
}
?>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // If the user exists, you can now access their data
      // You might want to set a session or other authentication mechanism here
      session_start(); // Start the session (important for storing user information)
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      // You can add more data from the row here if needed, like $row['email']
      return true; // Login successful
    } else {
      return false; // Login failed (username or password incorrect)
    }

  } else {
    // Error executing query
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();

  return false; //Should not reach here if everything is done correctly
}

// Example usage (This is just an example - implement appropriate form handling)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    echo "Login successful!  Redirecting...";
    // Redirect to a protected page or home page
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>

<!-- HTML form (Example - adapt to your design) -->
<form method="post" action="">
  Username: <input type="text" name="username">
  Password: <input type="password" name="password">
  <input type="submit" value="Login">
</form>


<?php

// Database connection details - Replace with your actual credentials
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve the password hash from the database
  $sql = "SELECT password_hash, user_id FROM users WHERE username = '$username'";  // Assuming 'username' is the user's login identifier
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password_hash'];
    $userId = $row['user_id'];

    // Verify the password
    if (password_verify($password, $hashedPassword)) {
      // Authentication successful
      session_start(); // Start the session for user tracking
      $_SESSION['user_id'] = $userId; // Store the user's ID in the session
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}

// Example usage (Illustrative - Replace with your form handling logic)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a secure page, show a welcome message, etc.
    header("Location: welcome.php"); // Redirect to a welcome page
    exit();
  } else {
    // Login failed - Display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials - **IMPORTANT:  Never hardcode these in a production environment!**
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

/**
 * Logs in a user based on their username and password.
 *
 * @param string $username The username to log in.
 * @param string $password The password for the username.
 * @return int|false The user ID if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the SQL query
  $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings

  if ($stmt->execute() === false) {
    // Handle execution errors
    echo "Query execution failed: " . $stmt->error;
    $stmt->close();
    $conn->close();
    return false;
  }

  // Get the result
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // User not found
    $stmt->close();
    $conn->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];

  $stmt->close();
  $conn->close();

  return $userId;
}


// Example usage:

// Get username and password from form input (in a real application, validate this input!)
$username = $_POST['username'];
$password = $_POST['password'];

// Perform the login
$userId = loginUser($username, $password);

if ($userId) {
  // Login successful
  echo "Login successful! User ID: " . $userId;

  // You would typically redirect the user to a protected area of the website here.
} else {
  // Login failed
  echo "Login failed.  Please check your username and password.";
}

?>


$password = $_POST['password'];  // Get the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// ... (Insert $username and $hashedPassword into the database)


$password = $_POST['password'];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// ... (Query for the hashed password from the database)

if (password_verify($password, $hashedPasswordFromDatabase)) {
  // Login successful!
} else {
  // Login failed - incorrect password
}


<?php

// Database credentials (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Form handling (for simplicity, we'll directly process the form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (basic example - you should add more robust validation)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    }
    
    else {
        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query to select the user from the database
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User found!
            // Session management (important for security - see explanation below)
            session_start();
            $_SESSION["username"] = $username; // Store the username in the session
            echo "Login successful! You are now logged in.";
            // Redirect to a protected page or display a welcome message
            // header("Location: /protected_page.php"); // Example
            //exit();

        } else {
            // User not found
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

<h1>Login</h1>

<?php if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    Password: <input type="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// ... (Your login function code) ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (Get input values) ...

    if (empty($username) || empty($password)) {
        // ... (Error handling) ...
    } else {
        // Connect to the database (as before)
        $conn = new mysqli($servername, $username, $password, $dbname);
        // ... (Check connection) ...

        // Hash the password
        $password = password_hash($password, PASSWORD_DEFAULT); // Use password_hash()

        // Query to select the user from the database
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User found!
            session_start();
            $_SESSION["username"] = $username;
            // ... (Redirect, welcome message, etc.) ...

        } else {
            $error = "Invalid username or password.";
        }
    }
}

?>


<?php
// ... (Your login function code) ...

if ($result->num_rows > 0) {
    // User found!
    $user = $result->fetch_assoc();
    $hashed_password = $user['password'];

    if (password_verify($password, $hashed_password)) {
        // Password matches!
        session_start();
        $_SESSION["username"] = $username;
        // ... (Redirect, welcome message, etc.) ...
    } else {
        $error = "Invalid username or password.";
    }
}
?>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $query = "SELECT * FROM users WHERE username = '" . $conn->real_escape_string($username) . "' AND password = '" . $conn->real_escape_string($password) . "'";

  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // User found, set session variables
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    return true; // Login successful
  } else {
    return false; // Login failed
  }
}


// Example Usage:

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - add more robust validation here)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Call the login function
    $loginResult = loginUser($username, $password);

    if ($loginResult == true) {
      // Redirect to a protected page or display a welcome message
      header("Location: /protected_page.php"); // Change to your protected page URL
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    Password: <input type="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (VERY IMPORTANT - prevent SQL injection)
  $username = trim($username); // Remove leading/trailing whitespace
  $password = trim($password);

  // Sanitize the input (more robust than just trim)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $password = filter_var($password, FILTER_SANITIZE_STRING);


  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query with prepared statements (MOST IMPORTANT - prevents SQL injection)
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // User found - set session variables
      session_start();
      $row = $result->fetch_assoc();
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];

      // Redirect to a protected page
      header("Location: /protected_page.php"); // Replace with your protected page
      exit();

    } else {
      echo "Invalid username or password.";
    }

  } else {
    echo "Error executing query: " . $conn->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();

} else {
  // If the form hasn't been submitted, display the login form
  // This is crucial for the initial page load
  ?>
  <!DOCTYPE html>
  <html>
  <head>
    <title>Login</title>
  </head>
  <body>
    <h1>Login</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      Username: <input type="text" name="username" required>
      Password: <input type="password" name="password" required>
      <button type="submit">Login</button>
    </form>
  </body>
  </html>
  <?php
}
?>


<?php

// Database credentials (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement (important for security - prevents SQL injection)
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if ($stmt->execute()) {
      // Fetch the result
      $result = $stmt->fetch_assoc();

      // Check if the query returned any results
      if ($result) {
        // User found, set session variables
        session_start();
        $_SESSION['user_id'] = $result['id']; // Assuming you have an 'id' column in your users table
        $_SESSION['username'] = $username;
        return true; // Login successful
      } else {
        return false; // User not found
      }
    } else {
      // Error executing query
      return false;
    }
  } else {
    // Error preparing the statement
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (in your login form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a secure page
    header("Location: /secure_page.php"); // Replace with your desired secure page
    exit();
  } else {
    // Login failed, display an error message
    echo "Invalid username or password.";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
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
  // Get username and password from form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";  //Use prepared statements for security

  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    // User found, set session variables for authentication
    session_start();
    $row = $result->fetch_assoc();
    $_SESSION["user_id"] = $row["user_id"];
    $_SESSION["username"] = $row["username"];

    // Redirect to a secure page or display a welcome message
    header("Location: /welcome.php"); // Replace /welcome.php with your protected page's URL
    exit();

  } else {
    // User not found
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form action="login.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Login">
  </form>

  <?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check username and password
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $row['password'])) {
      // Login successful - Set session variables
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;
      return true; // Indicate successful login
    } else {
      return false; // Indicate incorrect password
    }
  } else {
    return false; // Indicate username not found
  }

  $conn->close();
}

// Example usage:
// Assume the user has entered their username and password in form fields
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page
    exit();
  } else {
    // Login failed - Display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL statement
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // Bind parameters
  $stmt->bind_param("ss", $username, $password);

  // Execute the statement
  if (!$stmt->execute()) {
    echo "Error: " . $stmt->error;
    return false;
  }

  // Get the result
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // No user found with those credentials
    return false;
  }

  // Fetch the user data
  $user = $result->fetch_assoc();

  // Close the statement and connection
  $stmt->close();
  $conn->close();

  // Successful login - return user data
  return $user;
}

// --- Example Usage ---

// Get username and password from form (example)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (add more robust validation here - e.g., password strength, input length)
  if (empty($username) || empty($password)) {
    echo "<p>Username and password fields cannot be empty.</p>";
  } else {
    // Call the login function
    $loginResult = loginUser($username, $password);

    if ($loginResult) {
      // User is logged in - redirect or display user info
      echo "<h2>Welcome, " . $loginResult["username"] . "</h2>";
      // You can store the user ID in a session, cookie, or local storage for later use.
      // Example:
      // $_SESSION['user_id'] = $loginResult['id'];  // Requires session_start()
    } else {
      // Login failed
      echo "<p>Invalid username or password.</p>";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Important for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and Password cannot be empty!";
    }
    elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    }
    else {
        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query (Prevent SQL Injection!)
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters (Crucial for security!)
            $stmt->bind_param("ss", $username, $password);

            // Execute the query
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if any rows were returned
            if ($result->num_rows > 0) {
                // User found!  Start a session.
                session_start();
                $_SESSION["username"] = $username; // Store username in session
                $result->fetch_assoc(); // Get user details (optional, for further processing)
                $error = ""; // Clear any previous errors
                echo "Login successful!  You are now logged in.";
                header("Location: /welcome.php"); // Redirect to a welcome page
                exit();

            } else {
                $error = "Invalid username or password.";
            }

        } else {
            $error = "Error preparing statement.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h2>Login</h2>

    <?php if (!empty($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
        Password: <input type="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Database connection
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to check credentials
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            $error = "Invalid username or password.";
        }

        if ($result->num_rows > 0) {
            // Login successful - set session variables
            session_start();
            $_SESSION["username"] = $username; // Store username in session
            $conn->close();
            header("Location: welcome.php"); // Redirect to a welcome page (or your home page)
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h1>Login</h1>

<?php
if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}
?>

<form method="post" action="<?php echo $_SERVER["SCRIPT_NAME"]; ?>">
    Username: <input type="text" name="username" placeholder="Enter username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter password"><br><br>
    <input type="submit" value="Login">
</form>

</body>
</html>


<?php

// Database connection details - **IMPORTANT: Replace with your actual details**
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate input (VERY IMPORTANT - prevent SQL injection)
    if (empty($username) || empty($password)) {
        $error = "Username and Password cannot be empty.";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid Email format.";
    } else {
        // Database query (using prepared statements for security)
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username); // "s" indicates a string parameter
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Verify password (using password_hash for secure comparison)
            if (password_verify($password, $row["password"])) {
                // Login successful - set session variables
                session_start();
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $username;
                echo "Login successful! <a href='dashboard.php'>Go to Dashboard</a>"; // Redirect after successful login
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Incorrect username.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php } ?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$db_name = 'your_db_name';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid username (must be a valid email address).";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to select the user based on username
    $sql = "SELECT id, username, password FROM users WHERE username = '" . $username . "'";  // Escape username for SQL injection. Use prepared statements for better security.

    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error = "Invalid username or password.";
    } else {
      // User found, verify password
      $row = $result->fetch_assoc();
      if (password_verify($password, $row["password"])) {
        // Login successful
        session_start();
        // Store user ID in session for later access
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $username;
        echo "Login successful!  Redirecting...";
        header("Location: welcome.php"); // Redirect to a welcome page or your application's homepage
        exit();
      } else {
        $error = "Invalid username or password.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (!empty($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login validation and returns user data if successful,
 * or an error message if login fails.
 *
 * @param string $username  The username to log in.
 * @param string $password The password to log in.
 * @param array $users     An associative array of usernames and their passwords.
 *                        Example: ['john.doe' => 'password123', 'jane.smith' => 'securePass']
 *
 * @return array|string  An array containing user data (id, username, etc.) on success,
 *                       or an error message string on failure.
 */
function login(string $username, string $password, array $users) {
    // Sanitize input -  Crucial for security!
    $username = trim($username);
    $password = trim($password);

    // Check if the user exists
    if (isset($users[$username])) {
        // Verify the password
        if (password_verify($password, $users[$username])) {  // Uses password_verify
            // User found and password matches - Create user data array
            $userData = [
                'id' => $username, // Using username as ID - can change to a real ID field
                'username' => $username,
            ];

            return $userData;
        } else {
            return "Incorrect password for " . $username;
        }
    } else {
        return "User " . $username . " not found";
    }
}

// Example Usage (for testing - REMOVE OR COMMENT OUT IN A REAL APPLICATION)
//  In a real application, you'd get these values from a form submission.

// Sample user data (in a real application, this would come from a database)
$users = [
    'john.doe' => '$2y$11$K3c2V1o8tG0wS16J8Xy1Z.w17xQ7fJ5p77789Qk9fV33333', // Hashed password
    'jane.smith' => '$2y$11$rGj2H9uL9oT6z953zY2c7.u41xQ7fJ5p77789Qk9fV33333', // Another hashed password
];


// Test login attempts
$loginResult1 = login('john.doe', 'password123', $users);
if ($loginResult1 === 'Incorrect password for john.doe') {
    echo "Login Failed: Incorrect password.
";
} elseif ($loginResult1 !== null) {
    echo "Login Successful!
";
    print_r($loginResult1);
} else {
    echo "Login Failed: User not found.
";
}

$loginResult2 = login('nonexistent.user', 'anypassword', $users);
if ($loginResult2 === 'Incorrect password for nonexistent.user') {
    echo "Login Failed: Incorrect password.
";
} elseif ($loginResult2 !== null) {
    echo "Login Successful!
";
    print_r($loginResult2);
} else {
    echo "Login Failed: User not found.
";
}

$loginResult3 = login('john.doe', 'wrongpassword', $users);
if ($loginResult3 === 'Incorrect password for john.doe') {
    echo "Login Failed: Incorrect password.
";
} elseif ($loginResult3 !== null) {
    echo "Login Successful!
";
    print_r($loginResult3);
} else {
    echo "Login Failed: User not found.
";
}
?>


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form data
$username = $_POST['username'];
$password = $_POST['password'];

// 1. Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// 2. Prepare the SQL query
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameters
$stmt->bind_param("ss", $username, $password); // "ss" indicates two strings

// Execute the query
$stmt->execute();

// 3. Get the result
$result = $stmt->get_result();

// 4. Process the result
$row = $result->fetch_assoc();

if($row) {
  // User exists
  // Set session variables
  session_start(); // Important: Start the session
  $_SESSION['username'] = $username;
  $_SESSION['user_id'] = $row['id']; // Assuming you have an 'id' column in your users table

  // Redirect the user to a protected page
  header("Location: /protected_page.php"); // Replace with your protected page URL
  exit(); // Important: Stop further execution
} else {
  // User doesn't exist
  echo "Invalid username or password.";
}

// 5. Close the connection
$stmt->close();
$conn->close();

?>


<?php

// Database connection details
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$username = $_POST['username'];
$password = $_POST['password'];

// SQL query to fetch the user
$sql = "SELECT id, username, password FROM users WHERE username = '$username'";
$result = mysqli_query($host, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Password is correct, set session variables
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;

            // Redirect to a protected page or display a success message
            header("Location: protected_page.php"); // Replace with your protected page
            exit();
        } else {
            // Incorrect password
            echo "<p style='color:red;'>Incorrect username or password.</p>";
        }
    }
} else {
    // Error querying the database
    echo "<p style='color:red;'>Database error: " . mysqli_error($host) . "</p>";
}

?>


<?php

// Database Credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (Crucial for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'") === false) {
    $error_message = "Invalid username or password.";
  } else {
    // Password verification (Important!  Never store passwords in plain text)
    $hashed_password = "your_hashed_password"; // Replace with the actual hashed password from your database

    if (password_verify($password, $hashed_password)) {
      // Login successful!
      session_start();
      $_SESSION["username"] = $username;
      header("Location: welcome.php"); // Redirect to a welcome page
      exit;
    } else {
      $error_message = "Invalid username or password.";
    }
  }
}

// Database connection
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


// Registering a new user (example)
$hashed_password = password_hash("your_password", PASSWORD_DEFAULT);

// Storing the hashed password in the database (in the INSERT query)
// ...

// Logging in a user:
$password = $_POST["password"];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
// ... your SQL query ...


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to select the user
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";  // Use prepared statements for security

    // Prepare the statement (important for security)
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            // User found, set session variables
            session_start();  // Start session management
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Redirect to a protected page or display a success message
            header("Location: /protected_page.php"); // Replace with your protected page URL
            exit(); // Important to stop further execution
        } else {
            // User not found
            return false;
        }
    } else {
        // Query error
        return false;
    }

    // Close the statement
    $stmt->close();
    $conn->close();

    return true;
}


// Example Usage (Demonstration - DO NOT directly use this in your application)
// Assuming you have a form to collect username and password

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important to prevent SQL injection)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        $login_result = loginUser($username, $password);

        if ($login_result) {
            echo "Login successful!  Redirecting...";
        } else {
            echo "Login failed.  Invalid credentials.";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

    <h1>Login</h1>

    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>

    <form method="post" action="">
        Username: <input type="text" name="username" />
        Password: <input type="password" name="password" />
        <input type="submit" value="Login" />
    </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Session management:  Store user information in session
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      return true; // Login successful
    } else {
      return false; // No user found with those credentials
    }
  } else {
    // Handle the error
    echo "Error executing query: " . $conn->error;
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (Handle Login Form Submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security - more robust validation is recommended)
  if (empty($username) || empty($password)) {
    echo "Username and password cannot be empty.";
  } else {
    // Call the loginUser function
    if (loginUser($username, $password)) {
      // Redirect to a protected page or display a welcome message
      header("Location: /protected_page.php"); // Replace with your protected page URL
      exit();
    } else {
      echo "Invalid username or password.";
    }
  }
}

//  Display login form (only displayed on the initial page)
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username); // 's' indicates a string parameter
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // User not found
    return false;
  } else {
    // User found, verify password
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      // Password matches, login successful
      // You can store user session data here (e.g., set session variables)
      // Example:
      session_start();
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      return true;
    } else {
      // Password mismatch
      return false;
    }
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (for demonstration - NOT for production!)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    // Login failed - Display error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>If you don't have an account, <a href="register.php">register</a>.</p>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found - store the user's data in a session
    $user_data = $result->fetch_assoc();
    $_SESSION['user_id'] = $user_data['id']; // Assuming 'id' is the primary key
    $_SESSION['username'] = $user_data['username'];
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}


// Example usage:  This is for demonstration - don't directly use this in your web app.
//  This shows how to call the function and handle the result.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful!  You are now logged in.";
    // Redirect to a secure page or display a welcome message.
    header("Location: /welcome.php"); // Replace with your secure page URL.  Important to redirect.
    exit();
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

  <h2>User Login</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (basic validation - improve this for a production environment)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid username (please enter a valid email address)";
  } else {
    // Database connection
    $conn = new mysqli($username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT * FROM users WHERE email = '$username'"; // Use email for security
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error_message = "Invalid username or password.";
    } else {
      // User found, check the password
      $user = $result->fetch_assoc();
      if (password_verify($password, $user["password"])) {
        // Authentication successful - set session variables
        session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["email"]; // Or username, depending on your database
        header("Location: welcome.php"); // Redirect to a welcome page
        exit();
      } else {
        $error_message = "Invalid username or password.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
  }
?>

  <form method="post" action="<?php echo $_SERVER["SCRIPT_NAME"]; ?>">
    Username: <input type="text" name="username" placeholder="your_email@example.com">
    <br><br>
    Password: <input type="password" name="password">
    <br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


// ... (Database connection code) ...

// Get the username and password from the form
$username = $_POST["username"];
$password = $_POST["password"];

// Hash the password using password_hash()
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// SQL query to insert the user
$sql = "INSERT INTO users (email, password) VALUES ('$username', '$hashed_password')";

// Execute the query
if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $conn->error;
}

// ... (Close database connection) ...


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Successful login
      session_start();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      $stmt->close();
      $conn->close();
      return true; // Indicate successful login
    } else {
      // Login failed
      $stmt->close();
      $conn->close();
      return false; // Indicate failed login
    }
  } else {
    // Prepare failed
    $stmt->close();
    $conn->close();
    return false; // Indicate prepare failed.
  }
}


// Example usage:  (This part would be in your HTML form processing)
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a protected page or display a welcome message
    header("Location: protected_page.php"); // Replace with your protected page
    exit();
  } else {
    // Display an error message
    echo "<p>Invalid username or password.</p>";
  }
}
*/


?>


<?php

// Database Credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid Username (Please enter a valid email address).";
  } else {
    // SQL Query (Use prepared statements for security - see explanation below)
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
      $error_message = "Connection failed: " . $conn->connect_error;
    }

    // Execute the query
    $result = $conn->query($sql);

    // Check if the query returned any results
    if ($result->num_rows > 0) {
      // User exists, now verify the password
      $row = $result->fetch_assoc();
      if (password_verify($password, $row["password"])) {
        // Password is correct, set session variables
        $_SESSION["username"] = $username;
        $_SESSION["user_id"] = $row["id"]; // Store user ID for better security
        header("Location: welcome.php"); // Redirect to a welcome page
        exit;
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "User not found.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <?php if (!empty($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Username: <input type="text" name="username" placeholder="Enter your email">
    <br><br>
    Password: <input type="password" name="password">
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here.</a>
  <br><br>
  <a href="forgot_password.php">Forgot Password?</a>  <!-- You'll need to implement forgot password functionality -->

</body>
</html>

