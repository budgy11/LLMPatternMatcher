

<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (important for security!)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password must be filled.";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format."; // More specific validation
    } else {
        // Attempt to connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query
        $sql = "SELECT * FROM users WHERE email = '$username'";  // Use email for comparison
        $result = $conn->query($sql);

        // Check if the query executed successfully
        if ($result->num_rows > 0) {
            // Fetch the data from the result set
            $row = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $row["password"])) {
                // Login successful!  Set session variables
                session_start();
                $_SESSION["username"] = $row["email"]; // Store email for flexibility
                $_SESSION["user_id"] = $row["id"]; // Assuming you have a user ID
                echo "Login successful!  Redirecting...";
                // Redirect to a welcome page or desired location
                header("Location: welcome.php");
                exit(); // Important to stop further script execution
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "No user found with that email.";
        }
    }
}
?>
