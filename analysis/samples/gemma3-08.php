    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>


<?php

// ... (Database connection code as above) ...

// SQL query with placeholders
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Bind the parameters
$stmt->bind_param("ss", $login_username, $login_password); // "ss" indicates two string parameters

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // ... (Handle successful login) ...
} else {
    // ... (Handle invalid login) ...
}

$stmt->close();
$conn->close();

?>
