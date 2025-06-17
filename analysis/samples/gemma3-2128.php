

<?php

/**
 * User Review Function
 *
 * This function allows you to create, display, and manage user reviews.
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $reviewText The review text entered by the user.
 * @param int $userId The ID of the user writing the review.  Defaults to 0 (anonymous user).
 * @param int $productId The ID of the product being reviewed.  Defaults to 0 (for general reviews).
 * @return array An array containing the review data (ID, product ID, user ID, review text, date),
 *               or an array with an error message if the review creation fails.
 */
function createReview(string $productName, string $reviewText, int $userId = 0, int $productId = 0) {
  // Sanitize input (basic - improve for production environments)
  $productName = trim($productName);
  $reviewText = trim($reviewText);

  if (empty($productName) || empty($reviewText)) {
    return ['error' => 'Product name and review text cannot be empty.'];
  }

  // Generate a unique ID (for demonstration purposes - use a database sequence for production)
  $reviewId = time();

  // Get the current timestamp
  $date = date('Y-m-d H:i:s');

  // Store the review data (in a real application, this would go to a database)
  // This is just a placeholder for demonstration.
  $reviewData = [
    'id' => $reviewId,
    'product_id' => $productId,
    'user_id' => $userId,
    'review_text' => $reviewText,
    'date' => $date
  ];

  // Validation - Add more robust validation as needed (e.g., review length)
  // For example, you might want to limit the review text length.
  //  if (strlen($reviewText) > 500) {
  //     return ['error' => 'Review text is too long.']
  //  }

  return $reviewData;
}


/**
 * Display Reviews (Example - Adapt to your database and display format)
 *
 * This function displays reviews for a given product.
 *
 * @param int $productId The ID of the product.
 */
function displayReviews(int $productId) {
  // **This is a placeholder - Replace with your database query**
  // In a real application, you would fetch reviews from a database
  // based on the $productId.

  $reviews = [
    // Example reviews (replace with database data)
    [
      'id' => 1,
      'product_id' => $productId,
      'user_id' => 123,
      'review_text' => 'Great product!  I highly recommend it.',
      'date' => '2023-10-26 10:00:00'
    ],
    [
      'id' => 2,
      'product_id' => $productId,
      'user_id' => 456,
      'review_text' => 'Good value for the price.',
      'date' => '2023-10-27 14:30:00'
    ],
  ];


  echo "<h2>Reviews for Product ID: " . $productId . "</h2>";
  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>";
  } else {
    echo "<ul>";
    foreach ($reviews as $review) {
      echo "<li><strong>User:</strong> " . ($review['user_id'] > 0 ? "User ID: " . $review['user_id'] : "Anonymous") . "<br>" .
           "<strong>Date:</strong> " . $review['date'] . "<br>" .
           $review['review_text'];
      echo "</li>";
    }
    echo "</ul>";
  }
}


// **Example Usage**
$product = "Awesome Widget";

// Create a review
$reviewData = createReview($product, "This widget is amazing!");

if (isset($reviewData['error'])) {
  echo "<p style='color:red;'>Error creating review: " . $reviewData['error'] . "</p>";
} else {
  echo "<p>Review created successfully! Review ID: " . $reviewData['id'] . "</p>";
}


// Display reviews for a specific product (e.g., product ID = 1)
displayReviews(1);

?>
