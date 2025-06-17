
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password (at least 8 characters)">

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli)
// Replace these with your actual database connection details
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data (IMPORTANT: Implement robust validation)
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);

    // Basic validation - you should add more thorough validation
    if (empty($username) || empty($password) || empty($email)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters.";
    } elseif (preg_match("/@/", $email)) {
        $error = "Invalid email format.";
    }  else {
        // Prepare the SQL query to prevent SQL injection
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql); // Assuming $conn is your database connection

        if ($stmt) {
            // Hash the password - VERY IMPORTANT for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Bind the values to the prepared statement
            $stmt->bind_param("sss", $username, $hashed_password, $email); // s = string

            // Execute the query
            if ($stmt->execute()) {
                // Registration successful
                echo "Registration successful!  Please check your email to verify.";
                //  You might redirect to a success page here
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Error preparing statement.";
        }
    }
}
?>
