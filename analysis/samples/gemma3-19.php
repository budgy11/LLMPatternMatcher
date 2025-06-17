
</body>
</html>


<?php
// ... (Database connection code - same as before) ...

function loginUser($username, $password) {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use a prepared statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");

    // Bind the parameters
    $stmt->bind_param("ss", $username, $password); // "ss" means two string parameters

    // Execute the query
    $result = $stmt->execute();

    if ($result) {
        $row = $stmt->fetch_assoc();
        if ($row) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            return true;
        }
    }

    $stmt->close();
    $conn->close();
    return false;
}
?>
