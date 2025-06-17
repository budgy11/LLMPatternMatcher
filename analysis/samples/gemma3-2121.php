

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews.
 *
 * @param string $reviewText The text of the user's review.
 * @param string $username The username of the user who submitted the review.
 * @param int   $rating    The rating given by the user (e.g., 1-5).  Optional, defaults to 0.
 * @param int   $itemId     The ID of the item being reviewed (e.g., product ID). Required.
 * @param PDO  $db         A PDO database connection object. Required.
 *
 * @return array An array containing the review ID, or an empty array on failure.
 */
function create_user_review(string $reviewText, string $username, int $rating = 0, int $itemId, PDO $db)
{
    // Input validation - very important!
    if (empty($reviewText)) {
        return ['error' => 'Review text cannot be empty.'];
    }
    if (empty($username)) {
        return ['error' => 'Username cannot be empty.'];
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be a number between 1 and 5.'];
    }
    if (!is_numeric($itemId)) {
        return ['error' => 'Item ID must be a number.'];
    }

    try {
        // SQL Query
        $sql = "INSERT INTO reviews (item_id, username, review_text, rating)
                VALUES (:item_id, :username, :review_text, :rating)";

        // Prepare and execute the query
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':item_id', $itemId);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':review_text', $reviewText);
        $stmt->bindParam(':rating', $rating);

        $result = $stmt->execute();

        // Get the last inserted ID
        $reviewId = $db->lastInsertId();

        return ['success' => true, 'review_id' => $reviewId];

    } catch (PDOException $e) {
        // Handle database errors
        error_log("Database error: " . $e->getMessage()); // Log the error for debugging
        return ['error' => 'Database error: ' . $e->getMessage()];
    }
}


/**
 * Function to display a single user review.
 *
 * @param int $reviewId The ID of the review to display.
 * @param PDO $db      A PDO database connection object.
 *
 * @return array An array containing the review data, or an empty array on failure.
 */
function display_user_review(int $reviewId, PDO $db) {
    try {
        $sql = "SELECT * FROM reviews WHERE id = :review_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':review_id', $reviewId);
        $stmt->execute();

        $review = $stmt->fetch(PDO::FETCH_ASSOC);

        return $review;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return ['error' => 'Database error: ' . $e->getMessage()];
    }
}

// Example Usage (Illustrative - requires database setup)
/*
// Assume you have a database connection named $db
// Example using SQLite (replace with your database connection)
$db = new PDO('sqlite:/path/to/your/database.db'); //  Replace with your database details

$reviewData = create_user_review("This is a fantastic product!", "JohnDoe", 5, 123);

if ($reviewData['success']) {
    echo "Review created successfully!  Review ID: " . $reviewData['review_id'];
} else {
    echo "Error creating review: " . $reviewData['error'];
}

$displayReview = display_user_review($reviewData['review_id'], $db);
if ($displayReview['success']) {
  echo "<br>Review Details:<br>";
  echo "Username: " . $displayReview['username'] . "<br>";
  echo "Review: " . $displayReview['review_text'] . "<br>";
  echo "Rating: " . $displayReview['rating'] . "<br>";
} else {
  echo "Error displaying review: " . $displayReview['error'];
}

*/


?>
