

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $productName The name of the product.
 * @param string $reviewText The review text entered by the user.
 * @param string $username  The username of the reviewer (optional, for display).
 * @param int    $rating   The rating given by the user (1-5).
 * @param int    $userId  The unique ID of the user (optional, for database storage).
 *
 * @return array An array containing:
 *   - 'success':  True if the review was successfully saved, false otherwise.
 *   - 'message':  A message describing the status of the review.
 *   - 'review':   The review data as an associative array (for display or storage).
 */
function create_user_review(string $productName, string $reviewText, string $username = 'Anonymous', int $rating = 0, int $userId = null) {
  // Validate input - important for security and data integrity
  if (empty($reviewText)) {
    return ['success' => false, 'message' => 'Review text cannot be empty.', 'review' => []];
  }
  if ($rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Rating must be between 1 and 5.', 'review' => []];
  }

  // Create a review object
  $review = [
    'product' => $productName,
    'text' => $reviewText,
    'username' => $username,
    'rating' => $rating,
    'timestamp' => time(), // Add a timestamp for record keeping
    'userId' => $userId  // Optional user ID (useful for database integration)
  ];

  //  **Here's where you'd normally store the review in a database.**
  //  This is a placeholder.  Replace with your database logic.
  //  Example using a simple array to represent a database row:
  //  $review_data = save_review_to_database($review);

  // Simulate saving to a database (for demonstration):
  $review_data = [
    'product' => $productName,
    'text' => $reviewText,
    'username' => $username,
    'rating' => $rating,
    'timestamp' => time(),
    'userId' => $userId
  ];


  // Check if the save was successful (simulated).
  if ($review_data) {
    return ['success' => true, 'message' => 'Review saved successfully.', 'review' => $review_data];
  } else {
    return ['success' => false, 'message' => 'Failed to save review.', 'review' => []];
  }
}

/**
 *  Simulated database saving function (for demonstration purposes)
 *  Replace this with your actual database interaction code.
 *
 * @param array $reviewData The review data to save.
 * @return array|null  The saved review data or null if saving failed.
 */
function save_review_to_database(array $reviewData) {
    // This is a placeholder.  In a real application, you would connect to your database
    // and perform the necessary operations (e.g., INSERT INTO reviews table).
    // For this example, we just return the data.

    // Simulate potential error:
    if (rand(0, 10) > 7) { // 30% chance of "failure"
       return null;
    }

    return $reviewData;
}


// **Example Usage:**

// Create a review
$review_result = create_user_review('Awesome Widget', 'This widget is amazing!', 'John Doe', 5);

if ($review_result['success']) {
  echo "Review saved successfully!
";
  print_r($review_result['review']);
} else {
  echo "Error saving review: " . $review_result['message'] . "
";
}

// Create another review with a different user and rating
$review_result2 = create_user_review('Basic Gadget', 'Could be better.', 'Jane Smith', 3, 123); //Include UserId

if ($review_result2['success']) {
  echo "Review saved successfully!
";
  print_r($review_result2['review']);
} else {
  echo "Error saving review: " . $review_result2['message'] . "
";
}

?>
