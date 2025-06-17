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

  <p>Already have an account? <a href="login.php">Login</a></p>

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
