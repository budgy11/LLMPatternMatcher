

<?php
// Example using a placeholder approach (less secure, but easier to understand)

// Assuming you're using a database extension like mysqli or PDO

// ... (connection code and session start) ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Build the query with placeholders
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($db_host, $query);

    if ($stmt) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "ss", $username, $password); // "ss" means two strings

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // User found - set session variables
            $_SESSION["username"] = $username;

            // Redirect to a protected page
            header("Location: protected_page.php");
            exit();
        } else {
            $error_message = "Incorrect username or password.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle the error preparing the statement
        echo "Error preparing statement: " . mysqli_error($db_host);
    }
}
?>
