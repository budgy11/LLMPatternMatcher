    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="">
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data (essential for security!)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Validate username (basic example - customize as needed)
    if (empty($username)) {
        $username_error = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $username_error = "Username must be at least 3 characters long.";
    }

    // Validate email (basic example - use a more robust validation library for production)
    if (empty($email)) {
        $email_error = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email address.";
    }

    // Validate passwords
    if (empty($password)) {
        $password_error = "Password cannot be empty.";
    } elseif (empty($confirm_password)) {
        $password_error = "Confirm Password cannot be empty.";
    } elseif ($password != $confirm_password) {
        $password_error = "Passwords do not match.";
    }

    // If there are any validation errors, display them
    if (!empty($username_error)) {
        $errors = ["username" => $username_error];
    }
    if (!empty($email_error)) {
        $errors["email"] = $email_error;
    }
    if (!empty($password_error)) {
        $errors["password"] = $password_error;
    }


    // If no errors, proceed with registration
    if (!empty($errors)) {
        // Display the registration form with error messages
        echo "<h1>Registration</h1>";
        echo "<form method='post' action=''>";
        echo "<label for='username'>Username:</label><br>";
        echo "<input type='text' id='username' name='username' value='" . htmlspecialchars($username) . "'><br>";
        echo (isset($errors["username"])) ? "<span style='color:red;'>$username_error</span><br>" : "";


        echo "<label for='email'>Email:</label><br>";
        echo "<input type='email' id='email' name='email' value='" . htmlspecialchars($email) . "'><br>";
        echo (isset($errors["email"])) ? "<span style='color:red;'>$email_error</span><br>" : "";

        echo "<label for='password'>Password:</label><br>";
        echo "<input type='password' id='password' name='password' value='" . htmlspecialchars($password) . "'><br>";

        echo "<label for='confirm_password'>Confirm Password:</label><br>";
        echo "<input type='password' id='confirm_password' name='confirm_password' value='" . htmlspecialchars($confirm_password) . "'><br>";

        echo "<input type='submit' value='Register'>";
        echo "</form>";
    } else {
        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the user data into the database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        $stmt->execute();

        // Check if the query was successful
        if ($stmt->rowCount() > 0) {
            // Redirect the user to a success page or display a success message
            echo "<h1>Registration Successful!</h1>";
            echo "<p>You have successfully registered.  Please <a href='login.php'>log in</a>.</p>";
            // Optionally, delete the form data from the session
            unset($_POST);
        } else {
            echo "Error inserting user data. Please try again.";
        }

        $stmt->close();
    }
} else {
    // Display the registration form if no form has been submitted
    echo "<h1>Registration</h1>";
    echo "<form method='post' action=''>";
    echo "<label for='username'>Username:</label><br>";
    echo "<input type='text' id='username' name='username' value='" . htmlspecialchars($_POST["username"]) . "'><br>";
    echo "<label for='email'>Email:</label><br>";
    echo "<input type='email' id='email' name='email' value='" . htmlspecialchars($_POST["email"]) . "'><br>";
    echo "<label for='password'>Password:</label><br>";
    echo "<input type='password' id='password' name='password' value='" . htmlspecialchars($_POST["password"]) . "'><br>";
    echo "<label for='confirm_password'>Confirm Password:</label><br>";
    echo "<input type='password' id='confirm_password' name='confirm_password' value='" . htmlspecialchars($_POST["confirm_password"]) . "'><br>";
    echo "<input type='submit' value='Register'>";
    echo "</form>";
}

?>
