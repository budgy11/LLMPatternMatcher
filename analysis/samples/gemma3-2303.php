

// Assuming a database connection is already established ($db)

// Add a review
$username = 'JohnDoe';
$comment = 'Great product, highly recommended!';

$sql = "INSERT INTO reviews (username, comment, product_id, date)
        VALUES ('$username', '$comment', 123, NOW())";

// Execute the query
if ($db->query($sql) === TRUE) {
  // Review added successfully
  echo "Review added successfully!";
} else {
  echo "Error adding review: " . $db->error;
}


<?php

/**
 * User Review Function
 *
 * This function allows you to add, retrieve, and display user reviews
 * for a given item (e.g., product, movie, book).
 *
 * Assumptions:
 *  - You have a database connection established (e.g., using mysqli or PDO).
 *  - You have a table named 'reviews' with columns:
 *      - id (INT, PRIMARY KEY, AUTO_INCREMENT)
 *      - item_id (INT, FOREIGN KEY referencing your item's ID)
 *      - user_id (INT, FOREIGN KEY referencing your users table)
 *      - rating (INT, 1-5)
 *      - comment (TEXT)
 *      - created_at (TIMESTAMP)
 *
 * @param int $itemId The ID of the item the review is for.
 * @param int $userId The ID of the user writing the review. (Optional, if user is known)
 * @param string $rating The rating (1-5).
 * @param string $comment The review comment.
 * @return array An array containing:
 *              - 'success': True if the review was created/updated successfully, False otherwise.
 *              - 'message':  A message indicating the result (e.g., "Review created successfully", "Review updated successfully").
 *              - 'review': The newly created or updated review object (if successful).
 */
function createOrUpdateReview(int $itemId, int $userId = 0, string $rating, string $comment) : array
{
    global $db; // Assuming you have a database connection variable named $db

    // Validate input
    if (!is_numeric($itemId) || $itemId <= 0) {
        return ['success' => false, 'message' => 'Invalid item ID.', 'review' => null];
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Invalid rating (1-5).', 'review' => null];
    }
    if (empty($comment)) {
        return ['success' => false, 'message' => 'Comment cannot be empty.', 'review' => null];
    }

    // Prepare the SQL query
    $sql = "INSERT INTO reviews (item_id, user_id, rating, comment) VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE rating = VALUES(rating), comment = VALUES(comment)";

    // Prepare the statement
    $stmt = $db->prepare($sql);

    // Bind parameters
    $stmt->bind_param("isss", $itemId, $userId, $rating, $comment);

    // Execute the statement
    if ($stmt->execute()) {
        // Get the last inserted row (for retrieving the review ID)
        $review = $db->query("SELECT * FROM reviews WHERE item_id = $itemId AND user_id = $userId LIMIT 1")->fetch_assoc();
        return ['success' => true, 'message' => 'Review created/updated successfully.', 'review' => $review];
    } else {
        return ['success' => false, 'message' => 'Error creating/updating review: ' . $db->error, 'review' => null];
    }

    // Close the statement
    $stmt->close();
}


/**
 * Retrieve all reviews for a given item.
 *
 * @param int $itemId The ID of the item.
 * @return array An array containing:
 *              - 'success': True if reviews were retrieved successfully, False otherwise.
 *              - 'reviews': An array of review objects, or an empty array if no reviews exist.
 *              - 'message': A message indicating the result.
 */
function getReviewsForItem(int $itemId) : array
{
    global $db;

    $sql = "SELECT * FROM reviews WHERE item_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $itemId);

    $results = [];
    $stmt->execute();

    while ($row = $stmt->fetch_assoc()) {
        $results[] = $row;
    }

    $stmt->close();

    return ['success' => true, 'reviews' => $results, 'message' => 'Reviews retrieved successfully.'];
}


// Example Usage (assuming $db is your database connection)
// $itemId = 123;
// $newReview = createOrUpdateReview($itemId, 456, 4, "This is a great product!");
// print_r($newReview);
?>
