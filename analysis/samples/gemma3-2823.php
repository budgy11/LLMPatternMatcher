

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 * It handles input validation, database interaction, and output formatting.
 *
 * @param string $productId The ID of the product/service being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $reviewText The review text submitted by the user.
 * @param string $dbHost The database host.
 * @param string $dbName The database name.
 * @param string $dbUser The database user.
 * @param string $dbPassword The database password.
 *
 * @return array An array containing:
 *   - 'success':  True if the review was successfully submitted, false otherwise.
 *   - 'message':  A message indicating the outcome (success/failure and error details).
 *   - 'reviewId': The ID of the newly created review, if successful.
 */
function submitReview(
    $productId,
    $username,
    $reviewText,
    $dbHost,
    $dbName,
    $dbUser,
    $dbPassword
) {
    // Input Validation (Basic - expand as needed)
    if (empty($productId)) {
        return ['success' => false, 'message' => 'Product ID cannot be empty.', 'reviewId' => null];
    }
    if (empty($username)) {
        return ['success' => false, 'message' => 'Username cannot be empty.', 'reviewId' => null];
    }
    if (empty($reviewText)) {
        return ['success' => false, 'message' => 'Review text cannot be empty.', 'reviewId' => null];
    }

    // Database Connection (Use prepared statements for security!)
    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    if ($conn->connect_error) {
        $conn->close();
        return ['success' => false, 'message' => "Database connection failed: " . $conn->connect_error, 'reviewId' => null];
    }

    // Sanitize Input (Important for security - use proper escaping or prepared statements)
    $reviewText = $conn->real_escape_string($reviewText);

    // SQL Query (Use prepared statements to prevent SQL injection)
    $sql = "INSERT INTO reviews (productId, username, reviewText) VALUES ('$productId', '$username', '$reviewText')";

    if ($conn->query($sql) === TRUE) {
        $reviewId = $conn->insert_id;
        return ['success' => true, 'message' => 'Review submitted successfully.', 'reviewId' => $reviewId];
    } else {
        $errorInfo = $conn->error;
        return ['success' => false, 'message' => "Review submission failed: " . $errorInfo['message'], 'reviewId' => null];
    }

    $conn->close();
}


// Example Usage:
// $product_id = "123";
// $user_name = "JohnDoe";
// $review = "This product is fantastic!";

// $result = submitReview($product_id, $user_name, $review, "localhost", "my_database", "my_user", "my_password");

// if ($result['success']) {
//     echo "Review submitted successfully! Review ID: " . $result['reviewId'];
// } else {
//     echo "Error submitting review: " . $result['message'];
// }
?>
