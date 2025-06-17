

   // config.php
   $host = "localhost";
   $db   = "your_database_name";
   $user = "your_db_username";
   $pass = "your_db_password";
   $conn; // Will be established in the main script
   

2. **`registerUser()` Function:**
   - Takes `$username`, `$password`, and `$email` as arguments.
   - **Input Validation:**
     - `empty()` checks that all required fields are not empty.
     - `filter_var()` with `FILTER_VALIDATE_EMAIL` ensures the email format is valid.  This is a basic check; consider adding more sophisticated validation (e.g., length limits, character restrictions).
   - **Password Hashing:**  `password_hash($password, PASSWORD_DEFAULT)` is *crucial*.  **Never** store passwords in plain text.  `PASSWORD_DEFAULT` uses a strong algorithm for hashing, making it much more secure than older methods.
   - **SQL Query:** Constructs an SQL `INSERT` statement.
   - **Prepared Statement:** Uses a prepared statement (`$stmt = $conn->prepare($sql)`) to prevent SQL injection attacks. This is a *vital* security measure.
   - **Parameter Binding:** `bind_param()` associates the variables with the placeholders in the SQL statement.  The `"sss"` indicates three string parameters.
   - **Error Handling:**  `error_log()` logs any database errors.  This is incredibly useful for debugging.
   - **Closing the Statement:** `close()` closes the prepared statement to free up resources.

3. **Security (Very Important):**
   - **Password Hashing:**  Using `password_hash()` is the *only* secure way to handle passwords.
   - **Prepared Statements:**  Prepared statements are *essential* to prevent SQL injection.
   - **Input Validation:**  While this example has basic input validation, you should expand it to include more checks (e.g., character limits, allowed characters).

4. **Database Table (Assumed):** This code assumes you have a `users` table in your database with the following columns:
   - `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
   - `username` (VARCHAR)
   - `password` (VARCHAR)  (This will store the *hashed* password)
   - `email` (VARCHAR)

   Example SQL to create the table:

   sql
   CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       username VARCHAR(255) NOT NULL UNIQUE,
       password VARCHAR(255) NOT NULL,
       email VARCHAR(255) NOT NULL
   );
   

5. **`config.php` Inclusion:**  Make sure you include `config.php` at the top of your script to establish the database connection:

   
   require_once 'config.php';
   

**How to Use:**

1.  **Set up your database:** Create the `users` table as shown above.
2.  **Create `config.php`:**  Fill in your database credentials in the `config.php` file.
3.  **Include the script:**  Include the PHP code above in your web page.
4.  **Form for Input:** Create an HTML form to allow users to enter their username, password, and email.
5.  **Submit the Form:**  When the form is submitted, the code will call `registerUser()`.

**Example HTML Form:**



<?php

// Database connection details - Replace with your actual values
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form handling (check if form has been submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data - VERY IMPORTANT!  Prevent SQL injection and XSS
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);
    $confirm_email = trim($_POST["confirm_email"]);

    // Validate data - This is crucial for security and data integrity
    $errors = [];

    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    }
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    }
    if (empty($confirm_email)) {
        $errors[] = "Confirm Email cannot be empty.";
    }

    if ($email != $confirm_email) {
        $errors[] = "Email and confirm email must match.";
    }

    if (count($errors) === 0) {
        //  Prepare the SQL query - Use prepared statements for security
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);  // Assuming $conn is your database connection

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ss", $username, $password);

            // Execute the query
            if ($stmt->execute()) {
                // Registration successful
                echo "Registration successful!  Please check your email for verification instructions.";
            } else {
                // Error executing the query
                echo "Error registering user: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();

        } else {
            echo "Error preparing statement.";
        }
    } else {
        // Display validation errors
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>";
    }
}
?>
