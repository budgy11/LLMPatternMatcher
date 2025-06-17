

<?php

/**
 * User Review Function
 *
 * This function creates a new user review, saves it to a database, and returns
 * the review details.
 *
 * @param string $username  The username of the reviewer.
 * @param string $rating   The rating given by the user (e.g., 1-5).
 * @param string $comment  The user's review comment.
 * @param int $item_id     The ID of the item being reviewed.
 * @return array|false Returns an array containing the review details on success,
 *                     or false on failure.
 */
function createReview(string $username, string $rating, string $comment, int $item_id)
{
    // Input Validation - Important for security and data integrity
    if (empty($username) || empty($rating) || empty($comment) || $item_id <= 0) {
        return false; // Invalid input
    }

    // Validate rating (e.g., between 1 and 5)
    $rating = (int) $rating; // Convert to integer
    if ($rating < 1 || $rating > 5) {
        return false; // Invalid rating
    }

    // Connect to the database (replace with your database credentials)
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

    try {
        // Prepare the SQL query to prevent SQL injection
        $stmt = $db->prepare(
            "INSERT INTO reviews (username, rating, comment, item_id) 
             VALUES (:username, :rating, :comment, :item_id)"
        );

        // Bind parameters to the query
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':item_id', $item_id);

        // Execute the query
        $stmt->execute();

        // Get the last inserted ID
        $review_id = $db->lastInsertId();

        // Return the review details
        return [
            'id' => $review_id,
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'item_id' => $item_id,
            'timestamp' => date('Y-m-d H:i:s') // Add timestamp for tracking
        ];

    } catch (PDOException $e) {
        // Handle database errors (e.g., logging, display an error message)
        error_log("Database error: " . $e->getMessage()); // Log the error
        return false; // Indicate failure
    }
}


// Example Usage (replace with your actual values)
$review = createReview("JohnDoe", 4, "Great product!", 123);

if ($review !== false) {
    echo "Review created successfully with ID: " . $review['id'] . "<br>";
    print_r($review); // Display review details
} else {
    echo "Review creation failed. Please check your input and database connection.";
}

?>
