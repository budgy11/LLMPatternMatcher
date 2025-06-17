

<?php
// ... (database connection setup - $db) ...

function registerUser($username, $password, $email) {
    global $db;
    // ... (validation) ...

    // Prepare the SQL statement
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";  // Using placeholders ( ? )
    $stmt = mysqli_prepare($db, $sql);

    if ($stmt === false) {
        // Handle the error
        return "Error preparing statement: " . mysqli_error($db);
    }

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, $username, $hashed_password, $email);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return "User registered successfully!";
    } else {
        mysqli_stmt_close($stmt);
        return "Error: " . mysqli_error($db);
    }
}
?>
