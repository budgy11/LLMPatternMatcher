

<?php

/**
 * User Review Function
 *
 * This function allows you to create, display, and manage user reviews for a specific item.
 *
 * @param string $item_id The unique identifier for the item being reviewed.
 * @param string $user_id The unique identifier for the user submitting the review.
 * @param string $rating  The user's rating (e.g., 1-5 stars).
 * @param string $comment The user's review comment.
 *
 * @return bool True on success, false on failure.
 */
function create_user_review(string $item_id, string $user_id, string $rating, string $comment) {
  // ** Validation - VERY IMPORTANT!  Adapt this to your specific requirements.  **
  //  This is a basic example. You should add more robust validation here,
  //  such as checking for valid rating values, input lengths, and potentially
  //  sanitization to prevent XSS attacks.

  if (empty($item_id) || empty($user_id) || empty($rating) || empty($comment)) {
    error_log("Error: Missing required fields for review submission.");
    return false;
  }

  if (!is_numeric($rating) || intval($rating) < 1 || intval($rating) > 5) {
    error_log("Error: Invalid rating value. Rating must be an integer between 1 and 5.");
    return false;
  }

  if (strlen($comment) > 500) {  // Limit comment length to prevent abuse
    error_log("Error: Comment exceeds maximum length.");
    return false;
  }

  // ** Database Interaction - Replace with your database connection and logic. **
  // This is a placeholder example.  Adapt it to your database system (MySQL, PostgreSQL, etc.).

  try {
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("INSERT INTO reviews (item_id, user_id, rating, comment) VALUES (:item_id, :user_id, :rating, :comment)");
    $stmt->bindParam(':item_id', $item_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    $stmt->execute();
    return true; // Success!

  } catch (PDOException $e) {
    error_log("Error creating review: " . $e->getMessage());  // Log the error for debugging
    return false; // Failed to create review
  }
}


// ** Example Usage (Demonstration) **
//
// Assuming you have a "reviews" table in your database with columns:
// - item_id (VARCHAR)
// - user_id (VARCHAR)
// - rating (INT)
// - comment (TEXT)
//
// $item_id = "product123";
// $user_id = "user456";
// $rating = 4;
// $comment = "Great product, would recommend!";

// if (create_user_review($item_id, $user_id, $rating, $comment)) {
//   echo "Review submitted successfully!";
// } else {
//   echo "Error submitting review.";
// }


// **  Displaying Reviews (Example - Adapt to your presentation needs) **

/**
 * Displays reviews for a given item.
 *
 * @param string $item_id The item ID to retrieve reviews for.
 */
function display_reviews(string $item_id) {
    try {
        $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT * FROM reviews WHERE item_id = :item_id");
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();

        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($reviews)) {
            echo "<p>No reviews found for this item.</p>";
        } else {
            echo "<h2>Reviews for " . $item_id . "</h2>";
            echo "<ul>";
            foreach ($reviews as $review) {
                echo "<li>";
                echo "<strong>User:</strong> " . $review['user_id'] . "<br>";
                echo "<strong>Rating:</strong> " . $review['rating'] . "<br>";
                echo "<strong>Comment:</strong> " . htmlspecialchars($review['comment']) . "<br>"; // Sanitize output!
                echo "</li>";
            }
            echo "</ul>";
        }

    } catch (PDOException $e) {
        error_log("Error retrieving reviews: " . $e->getMessage());
        echo "<p>Error retrieving reviews.</p>";
    }
}


// Example of calling the display function
// display_reviews("product123");



?>
