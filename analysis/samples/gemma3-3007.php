

<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate username (basic check - you should have more robust validation)
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
    } elseif (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    }

    // If validation passes, proceed with registration
    if (!empty($username_error) || !empty($email_error) || !empty($password_error)) {
        $errors = [];
        if($username_error){
            $errors[] = $username_error;
        }
        if($email_error){
            $errors[] = $email_error;
        }
        if($password_error){
            $errors[] = $password_error;
        }
        // Display the errors
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>";
    } else {
        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

        // Execute the query
        if (mysqli_query($GLOBALS["conn"], $sql)) {
            // Registration successful
            echo "Registration successful!  Please login.";
            // Redirect to login page (or display a success message)
            // header("Location: login.php"); // Assuming you have a login.php page
        } else {
            // Error occurred during registration
            echo "Error: " . $sql . "<br>" . mysqli_error($GLOBALS["conn"]);
        }
    }
}
?>
