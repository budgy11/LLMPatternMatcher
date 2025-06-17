

<?php

/**
 * User Review Function - Allows users to submit and view reviews.
 *
 * This function handles:
 *  - Receiving review input (text, rating, etc.)
 *  - Validating the input
 *  - Saving the review to a database (example implementation)
 *  - Displaying the reviews
 *
 * @param string $reviewText The user's review text.
 * @param int   $rating     The user's rating (1-5).
 * @param string $productName The name of the product the review is for.
 * @return array An array containing:
 *   - 'success':  True if the review was submitted successfully, false otherwise.
 *   - 'message': A message indicating the success or failure of the operation.
 *   - 'reviews': An array of review objects.
 */
function handleUserReview(string $reviewText, int $rating, string $productName) {
  // Input validation - Very important for security and data integrity.
  if (empty($reviewText)) {
    return ['success' => false, 'message' => 'Review text cannot be empty.', 'reviews' => []];
  }

  if ($rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Rating must be between 1 and 5.', 'reviews' => []];
  }

  // **In a real application, you would connect to your database here.**
  // This is a simplified example using a dummy array.
  $reviews = getReviewsFromDatabase(); // Replace with your actual database retrieval

  // Create a review object (can be customized to include more data)
  $review = [
    'id' => count($reviews) + 1, // Simple unique ID (in a real app, use auto-increment)
    'text' => $reviewText,
    'rating' => $rating,
    'timestamp' => date('Y-m-d H:i:s'),
    'product' => $productName
  ];

  // Add the new review to the reviews array
  $reviews[] = $review;

  // Save the review to the database (example - adapt to your DB)
  saveReviewToDatabase($review);  // Replace with your actual database saving function

  return ['success' => true, 'message' => 'Review submitted successfully.', 'reviews' => $reviews];
}


/**
 * Dummy function to simulate retrieving reviews from a database.
 * Replace with your actual database query.
 *
 * @return array An array of review objects.
 */
function getReviewsFromDatabase() {
  // Dummy data for demonstration
  return [
    [
      'id' => 1,
      'text' => 'Great product!  Highly recommended.',
      'rating' => 5,
      'timestamp' => date('Y-m-d H:i:s'),
      'product' => 'Laptop X100'
    ],
    [
      'id' => 2,
      'text' => 'Could be better, but decent for the price.',
      'rating' => 3,
      'timestamp' => date('Y-m-d H:i:s'),
      'product' => 'Laptop X100'
    ]
  ];
}

/**
 * Dummy function to simulate saving a review to the database.
 * Replace with your actual database saving logic.
 *
 * @param array $review The review data to save.
 */
function saveReviewToDatabase(array $review) {
  // In a real implementation, you would use a database connection
  // and execute a query to insert the review data into the database.

  // This is just a placeholder.  In a real app, do the database insertion.
  echo "Saving review to database: " . print_r($review, true) . "
";
}



// Example Usage:
$product = 'Laptop X100';
$reviewText = "This is a fantastic laptop! Very fast and reliable.";
$rating = 5;

$result = handleUserReview($reviewText, $rating, $product);

if ($result['success']) {
  echo "Review submitted successfully!
";
  echo "Reviews:
";
  print_r($result['reviews']);
} else {
  echo "Error submitting review: " . $result['message'] . "
";
}

// Example of an invalid review:
$invalidResult = handleUserReview("", 6, "Tablet Z200");
if (!$invalidResult['success']) {
    echo "Invalid review detected!
" . $invalidResult['message'] . "
";
}

?>
