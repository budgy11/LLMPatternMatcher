

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

// Database configuration (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security)
    if (empty($username) || empty($password)) {
        $error = "Username and password must be filled.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Database connection
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to check credentials
        $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) {
                // Login successful
                session_start();
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $username;
                header("Location: welcome.php"); // Redirect to a welcome page
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Username not found.";
        }
    }
}
?>
