

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a specific item.
 *
 * @param string $item_id The unique identifier for the item being reviewed.
 * @param string $user_id The unique identifier for the user submitting the review.
 * @param string $rating The rating given by the user (e.g., 1-5 stars).  Must be a valid integer.
 * @param string $comment The user's written review.
 * @param array $db_connection A database connection object.  (You'll need to establish this separately)
 *
 * @return bool True if the review was successfully saved, false otherwise.
 */
function saveUserReview(string $item_id, string $user_id, string $rating, string $comment, array $db_connection) {

  // Input validation - VERY IMPORTANT!
  if (!is_numeric($rating) || (int)$rating < 1 || (int)$rating > 5) {
    error_log("Invalid rating provided: " . $rating);
    return false;
  }

  if (empty($comment)) {
    error_log("Review comment is empty.");
    return false; // Or you could allow empty comments, but you'd need to handle them differently in your display.
  }
    
  // SQL query -  **IMPORTANT: Use prepared statements to prevent SQL injection!**
  $sql = "INSERT INTO reviews (item_id, user_id, rating, comment) 
          VALUES (?, ?, ?, ?)";

  // Prepare the statement
  $stmt = $db_connection->prepare($sql);

  if ($stmt === false) {
    error_log("Failed to prepare statement: " . $db_connection->error);
    return false;
  }

  // Bind the parameters
  $stmt->bind_param("siss", $item_id, $user_id, $rating, $comment);

  // Execute the statement
  if (!$stmt->execute()) {
    error_log("Failed to execute statement: " . $stmt->error);
    return false;
  }

  // Close the statement
  $stmt->close();

  return true;
}


/**
 * Display User Reviews
 *
 * Retrieves and displays user reviews for a given item.
 *
 * @param string $item_id The ID of the item to retrieve reviews for.
 * @param array $db_connection A database connection object.
 *
 * @return array An array of review objects, or an empty array if no reviews are found.  Each review object
 *              will have 'id', 'item_id', 'user_id', 'rating', 'comment', 'date_added'
 */
function displayUserReviews(string $item_id, array $db_connection) {
  $sql = "SELECT id, item_id, user_id, rating, comment, date_added
          FROM reviews
          WHERE item_id = ?
          ORDER BY date_added DESC"; // Show most recent reviews first

  $stmt = $db_connection->prepare($sql);

  if ($stmt === false) {
    error_log("Failed to prepare statement: " . $db_connection->error);
    return [];
  }

  $stmt->bind_param("s", $item_id);

  $stmt->execute();

  $reviews = [];
  $result = $stmt->get_result();

  if ($result === false) {
    error_log("Failed to get result: " . $db_connection->error);
    return [];
  }


  while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
  }

  $stmt->close();

  return $reviews;
}

// Example Usage (Illustrative - requires a database connection to be established)

// Assume you have a database connection object $conn

// 1. Save a review
$item_id = "product_123";
$user_id = "user_456";
$rating = 4;
$comment = "Great product! Highly recommended.";

if (saveUserReview($item_id, $user_id, $rating, $comment, $conn)) {
  echo "Review saved successfully!";
} else {
  echo "Error saving review.";
}


// 2. Display the reviews for that item
$reviews = displayUserReviews($item_id, $conn);

if (empty($reviews)) {
  echo "<p>No reviews yet.</p>";
} else {
  echo "<h2>Reviews for " . $item_id . "</h2>";
  foreach ($reviews as $review) {
    echo "<p><strong>User:</strong> " . $review['user_id'] . "<br>";
    echo "<strong>Rating:</strong> " . $review['rating'] . "<br>";
    echo "<strong>Comment:</strong> " . $review['comment'] . "</p>";
  }
}

?>
