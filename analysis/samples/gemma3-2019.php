

<?php

/**
 * User Review Function
 *
 * This function allows you to store user reviews for a product or item.
 *
 * @param string $product_id The unique identifier of the product.
 * @param string $user_id The unique identifier of the user.
 * @param string $rating  The rating given by the user (e.g., 1-5).  Ensure validation.
 * @param string $comment The user's comment/review.
 * @param string $db_connection  A connection object to your database.
 *
 * @return bool True on successful save, false on failure.
 */
function saveUserReview(string $product_id, string $user_id, string $rating, string $comment, $db_connection) {
    // Validate input - VERY IMPORTANT!
    if (empty($product_id) || empty($user_id) || empty($rating) || empty($comment)) {
        error_log("Error: Missing required review fields.");
        return false;
    }

    if (!preg_match('/^[1-5][0-9]*$/', $rating)) {
        error_log("Error: Invalid rating format.  Must be a number between 1 and 5.");
        return false;
    }


    // Prepare the SQL statement
    $sql = "INSERT INTO reviews (product_id, user_id, rating, comment)
            VALUES (:product_id, :user_id, :rating, :comment)";

    // Prepare the statement
    $stmt = $db_connection->prepare($sql);

    if ($stmt === false) {
        error_log("Error preparing statement: " . $db_connection->error);
        return false;
    }


    // Bind parameters
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    // Execute the statement
    if (!$stmt->execute()) {
        error_log("Error executing statement: " . $stmt->error);
        return false;
    }

    return true;
}


// Example Usage (Illustrative - Replace with your actual database setup)
// -----------------------------------------------------------------

// Assuming you have a database connection established as $db_connection

// Example review data
$product_id = "P123";
$user_id = "U456";
$rating = 4;
$comment = "Great product!  Works as advertised.";

// Save the review
$success = saveUserReview($product_id, $user_id, $rating, $comment, $db_connection);

if ($success) {
    echo "Review saved successfully!";
} else {
    echo "Error saving review.";
}

?>
