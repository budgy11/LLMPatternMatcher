

<?php

/**
 * User Review Function
 *
 * This function allows you to save user reviews with various metadata.
 *
 * @param string $user_id      The ID of the user submitting the review.
 * @param string $product_id   The ID of the product being reviewed.
 * @param string $rating       The rating given by the user (e.g., 1-5).
 * @param string $comment      The user's written review.
 * @param string $timestamp   (Optional) Timestamp for the review. Defaults to current timestamp.
 *
 * @return bool|string True on success, error message string on failure.
 */
function saveUserReview(
    string $user_id,
    string $product_id,
    string $rating,
    string $comment,
    string $timestamp = null // Optional timestamp
) {
    // Input Validation (Important!)
    if (empty($user_id) || empty($product_id) || empty($rating) || empty($comment)) {
        return "Error: All fields (User ID, Product ID, Rating, Comment) are required.";
    }

    if (!is_numeric($rating) || intval($rating) < 1 || intval($rating) > 5) {
        return "Error: Rating must be a number between 1 and 5.";
    }

    // Sanitize inputs (Important for security - prevents XSS, SQL injection)
    $user_id = filter_var($user_id, FILTER_SANITIZE_STRING);
    $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);
    $rating = intval($rating);  // Ensure rating is an integer
    $comment = filter_var($comment, FILTER_SANITIZE_STRING);

    // Database Interaction (Example - Replace with your actual database logic)
    try {
        // Assuming you have a database connection established
        // $db = new PDO(/* your database connection details */);
        // Example using a placeholder for demonstration - REPLACE with your connection

        $stmt = $db->prepare("INSERT INTO reviews (user_id, product_id, rating, comment, created_at) VALUES (:user_id, :product_id, :rating, :comment, :created_at)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':created_at', date('Y-m-d H:i:s')); //Using timestamp as string

        $result = $stmt->execute();

        if ($result) {
            return true; // Success
        } else {
            // Log the error for debugging
            error_log("Error saving review: " . print_r($stmt->errorInfo(), true));
            return "Error: Could not save review to database. Check error logs.";
        }

    } catch (PDOException $e) {
        // Handle database connection or query errors
        error_log("Database Error: " . $e->getMessage());
        return "Error: Database error.  Check database connection and query syntax.  " . $e->getMessage();
    }
}

// Example usage:
$user_id = "user123";
$product_id = "product456";
$rating = 4;
$comment = "Great product! Highly recommended.";

$result = saveUserReview($user_id, $product_id, $rating, $comment);

if ($result === true) {
    echo "Review saved successfully!";
} else {
    echo "Error: " . $result;
}
?>
