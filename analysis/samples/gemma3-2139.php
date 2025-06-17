

<?php

/**
 * User Review Function
 *
 * This function takes a user ID, product ID, and a review text,
 * and saves the review to a database.
 *
 * @param int $userId  The ID of the user submitting the review.
 * @param int $productId The ID of the product being reviewed.
 * @param string $reviewText The review text submitted by the user.
 *
 * @return bool True if the review was successfully saved, false otherwise.
 */
function saveUserReview(int $userId, int $productId, string $reviewText): bool
{
    // Sanitize input - Important for security!
    $userId = filter_var($userId, FILTER_VALIDATE_INT);
    $productId = filter_var($productId, FILTER_VALIDATE_INT);
    $reviewText = trim($reviewText);  // Remove leading/trailing whitespace

    // Validation - Check if inputs are valid
    if ($userId <= 0 || $productId <= 0 || empty($reviewText)) {
        error_log("Invalid review data:  userId, productId, and reviewText must be positive and non-empty.");
        return false;
    }


    // --- Database Interaction (Example using MySQLi) ---
    $servername = "localhost";
    $username = "your_db_username";
    $password = "your_db_password";
    $dbname = "your_db_name";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare statement
    $stmt = $conn->prepare("INSERT INTO reviews (userId, productId, reviewText, reviewDate) VALUES (?, ?, ?, NOW())");

    // Bind parameters
    $stmt->bind_param("iii", $userId, $productId, $reviewText);

    // Execute statement
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true;
    } else {
        $stmt->close();
        $conn->close();
        error_log("Error saving review: " . $stmt->error);
        return false;
    }
}

// --- Example Usage ---

// Simulate a review
$user_id = 123;
$product_id = 456;
$review = "This product is fantastic!  I highly recommend it.";

// Save the review
if (saveUserReview($user_id, $product_id, $review)) {
    echo "Review saved successfully!
";
} else {
    echo "Failed to save review.
";
}

// --- Example with invalid data ---
if (saveUserReview(0, 10, "Bad review")) {
    echo "Review saved successfully (unexpectedly).
";
} else {
    echo "Failed to save review (as expected).
";
}

?>
