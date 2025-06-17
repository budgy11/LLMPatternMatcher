

<?php

/**
 * User Review Function
 *
 * This function allows users to submit reviews for a product or item.
 * It validates the input, sanitizes it, and saves it to a database.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param string $dbHost    The database hostname (e.g., localhost).
 * @param string $dbUser    The database username.
 * @param string $dbPassword The database password.
 * @param string $dbName    The database name.
 *
 * @return bool True if the review was successfully submitted, false otherwise.
 */
function submitReview(
    string $productId,
    string $rating,
    string $comment,
    string $dbHost,
    string $dbUser,
    string $dbPassword,
    string $dbName
) {
    // Input validation and sanitization
    if (empty($productId) || empty($rating) || empty($comment)) {
        error_log("Missing required fields in review submission.");
        return false;
    }

    if (!preg_match('/^[1-5][0-9]*$/', $rating)) {
        error_log("Invalid rating format.  Please enter a number between 1 and 5.");
        return false;
    }

    if (strlen($comment) > 500) {  // Example: Limit comment length
        error_log("Comment exceeds maximum length (500 characters).");
        return false;
    }

    // Database connection (using a simple example - consider using PDO for better security)
    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    if ($conn->connect_error) {
        error_log("Failed to connect to database: " . $conn->connect_error);
        return false;
    }

    // Prepare the SQL statement (using prepared statements for security!)
    $sql = "INSERT INTO reviews (product_id, rating, comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        error_log("Error preparing SQL statement: " . $conn->error);
        $stmt->close();
        $conn->close();
        return false;
    }

    // Bind parameters to prevent SQL injection
    $stmt->bind_param("siii", $productId, $rating, $comment);

    // Execute the statement
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true;
    } else {
        error_log("Error executing SQL statement: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return false;
    }
}

// Example Usage:
// Assuming you have a database set up with a table named 'reviews'
// with columns: product_id (INT), rating (INT), comment (TEXT)

// $success = submitReview("123", "4", "This product is amazing!", "localhost", "user", "password", "mydatabase");

// if ($success) {
//     echo "Review submitted successfully!";
// } else {
//     echo "Error submitting review.";
// }
?>
