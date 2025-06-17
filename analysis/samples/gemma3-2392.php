

<?php

/**
 * User Review Function
 *
 * This function allows you to store, retrieve, and display user reviews for a given item.
 *
 * @param string $item_id The unique identifier for the item being reviewed.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int $rating  The rating provided by the user (e.g., 1-5).
 * @param string $db_connection  The established database connection object.  Crucial for database interaction.
 *
 * @return array  An array containing success/failure status and an optional list of reviews.
 *                 Returns an empty array on error.
 */
function store_user_review(string $item_id, string $user_name, string $review_text, int $rating, $db_connection) {
  // Validate input (important for security and data integrity)
  if (empty($item_id) || empty($user_name) || empty($review_text) || $rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Invalid input data.  Item ID, user name, and review text must be filled. Rating must be between 1 and 5.'];
  }

  // Sanitize input (essential for security - prevent SQL injection)
  $item_id = mysqli_real_escape_string($db_connection, $item_id);
  $user_name = mysqli_real_escape_string($db_connection, $user_name);
  $review_text = mysqli_real_escape_string($db_connection, $review_text);
  $rating = (int)$rating; // Cast rating to integer

  // SQL query (using prepared statements - the best practice)
  $sql = "INSERT INTO reviews (item_id, user_name, review_text, rating) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_prepare($db_connection, $sql);

  if ($stmt === false) {
    // Handle the error
    return ['success' => false, 'message' => 'Error preparing statement: ' . mysqli_error($db_connection)];
  }

  mysqli_stmt_bind_param($stmt, "ssis", $item_id, $user_name, $rating); // 'ssis' - string, string, integer, string

  if (mysqli_stmt_execute($stmt) === false) {
    // Handle the error
    mysqli_stmt_close($stmt);
    return ['success' => false, 'message' => 'Error executing statement: ' . mysqli_error($db_connection)];
  }

  mysqli_stmt_close($stmt);

  // Optionally, fetch the newly created review (for confirmation)
  $result = mysqli_query($db_connection, "SELECT * FROM reviews WHERE item_id = '$item_id' ORDER BY review_id DESC LIMIT 1");
  if ($result) {
    $review = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
  } else {
     // Handle error in fetching.  This is unlikely but good to check.
     return ['success' => false, 'message' => 'Error fetching review after insertion: ' . mysqli_error($db_connection)];
  }

  return ['success' => true, 'review' => $review];
}


// --- Example Usage (requires a database connection, assume 'db_connection' is already established) ---
// (Important:  Replace this with your actual database connection details.)

// Assuming you have a database connection named $db_connection

// $review_data = store_user_review("product_123", "John Doe", "Great product!", 5, $db_connection);

// if ($review_data['success']) {
//   echo "Review submitted successfully! Review ID: " . $review_data['review']['review_id'];
// } else {
//   echo "Error submitting review: " . $review_data['message'];
// }


// --- Example Database Table Creation (MySQL) ---
/*
CREATE TABLE reviews (
  review_id INT AUTO_INCREMENT PRIMARY KEY,
  item_id VARCHAR(255) NOT NULL,
  user_name VARCHAR(255) NOT NULL,
  review_text TEXT NOT NULL,
  rating INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
*/


?>
