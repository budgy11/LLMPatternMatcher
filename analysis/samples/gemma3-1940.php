

<?php

/**
 * User Review Function
 *
 * This function allows you to store user reviews with a basic rating system.
 *
 * @param string $user_id The unique ID of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int $rating The rating given by the user (e.g., 1-5).
 * @param string $item_id The ID of the item being reviewed.
 * @param array $database_connection  (Optional)  Database connection object. Defaults to null.
 * 
 * @return bool True on success, false on failure.
 */
function save_user_review(
    string $user_id,
    string $review_text,
    int $rating,
    string $item_id,
    array $database_connection = null
) {
    // Input Validation - IMPORTANT!  Don't skip this.
    if (empty($user_id) || empty($review_text) || $rating < 1 || $rating > 5 || empty($item_id)) {
        error_log("Invalid review data received. user_id: " . $user_id . ", review_text: " . $review_text . ", rating: " . $rating . ", item_id: " . $item_id); // Log the error
        return false;
    }


    //  Use prepared statements to prevent SQL Injection - CRITICAL SECURITY
    $sql = "INSERT INTO reviews (user_id, item_id, review_text, rating) 
            VALUES (?, ?, ?, ?)";

    $stmt = $database_connection->prepare($sql);

    if ($stmt === false) {
        error_log("Error preparing statement.  Error: " . print_r($database_connection->error, true));
        return false;
    }

    // Bind parameters
    $stmt->bind_param("siss", $user_id, $review_text, $rating);

    // Execute the query
    if (!$stmt->execute()) {
        error_log("Error executing query.  Error: " . print_r($stmt->error, true));
        return false;
    }

    // Close the statement
    $stmt->close();

    return true;
}

/**
 * Example Usage (Illustrative - Adapt to your specific setup)
 */

//  Assume you have a database connection object called $db

// Example 1: Successful Save
$user_id = "user123";
$review_text = "This product is fantastic!";
$rating = 5;
$item_id = "product456";

if (save_user_review($user_id, $review_text, $rating, $item_id, $db)) {
    echo "Review saved successfully!";
} else {
    echo "Failed to save review.";
}

// Example 2:  Illustrative example of invalid input
if (save_user_review("", "Review", 6, "item789")) { // Empty user_id
  echo "Should not reach here";
}

?>
