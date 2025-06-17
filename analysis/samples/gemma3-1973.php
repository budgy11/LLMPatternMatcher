

<?php

/**
 * User Review Function
 *
 * This function allows you to save user reviews for a given item.
 *
 * @param string $item_id The unique identifier of the item being reviewed.
 * @param string $user_id The unique identifier of the user submitting the review.
 * @param string $rating The user's rating (e.g., 1-5 stars).
 * @param string $comment The user's written comment about the item.
 *
 * @return bool True if the review was successfully saved, false otherwise.
 */
function saveReview(string $item_id, string $user_id, string $rating, string $comment)
{
    // Validate inputs (important for security and data integrity)
    if (empty($item_id) || empty($user_id) || empty($rating) || empty($comment)) {
        error_log("Missing required fields for review submission.");
        return false;
    }

    if (!is_numeric($rating) || (int)$rating < 1 || (int)$rating > 5) {
        error_log("Invalid rating provided.");
        return false;
    }

    // In a real application, you would typically interact with a database here.
    // This example simulates database interaction for demonstration purposes.

    // Data for demonstration
    $db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password"); // Replace with your database credentials
    $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        // Prepare the SQL statement
        $stmt = $db_connection->prepare(
            "INSERT INTO reviews (item_id, user_id, rating, comment)
             VALUES (:item_id, :user_id, :rating, :comment)"
        );

        // Bind parameters
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        // Execute the statement
        $stmt->execute();

        // Return true if successful
        return true;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        // Handle database errors appropriately (logging, displaying an error message to the user)
        return false;
    }
}


// Example Usage:

// Simulate user data
$item_id = "product123";
$user_id = "user456";
$rating = 4;
$comment = "Great product, highly recommend!";

// Save the review
$success = saveReview($item_id, $user_id, $rating, $comment);

if ($success) {
    echo "Review successfully saved!";
} else {
    echo "Failed to save review.  Check the logs for details.";
}


?>
