
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
