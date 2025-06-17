

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or service.
 * It includes input validation and basic sanitization.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewText The user's review text.
 * @param string $userId (optional) The ID of the user leaving the review.  If not provided, generates a unique ID.
 * @return array|string  An array containing the review data (review_id, product_name, review_text, user_id, date) if successful, or an error message string if there's an issue.
 */
function storeUserReview(string $productName, string $reviewText, string $userId = null) {
  // Input Validation - Basic checks
  if (empty($productName)) {
    return "Error: Product name cannot be empty.";
  }
  if (empty($reviewText)) {
    return "Error: Review text cannot be empty.";
  }

  // Sanitize Input (more robust sanitization is recommended for production)
  $productName = htmlspecialchars($productName);
  $reviewText = htmlspecialchars($reviewText);

  // Generate unique User ID if not provided
  if ($userId === null || empty($userId)) {
    $userId = uniqid(); // Generate a unique ID
  }

  // Get Current Timestamp
  $timestamp = date('Y-m-d H:i:s');

  // Prepare Data for Storage (Replace this with your database interaction)
  $reviewData = [
    'review_id' => $userId, // Using the ID as review_id for simplicity
    'product_name' => $productName,
    'review_text' => $reviewText,
    'user_id' => $userId,
    'date' => $timestamp,
  ];

  // Simulate Database Storage - Replace with your actual database code
  // Example using an associative array:
  //  $reviews = loadReviewsFromDatabase(); // Replace with your DB function
  //  $reviews[] = $reviewData;
  //  saveReviewsToDatabase($reviews);

  // Simulate Successful Storage
  return $reviewData;
}


/**
 * Example Usage:
 */

// Store a review
$review = storeUserReview("Awesome Widget", "This widget is fantastic! I highly recommend it.");
if (is_array($review)) {
  echo "Review saved successfully:<br>";
  echo "Review ID: " . $review['review_id'] . "<br>";
  echo "Product Name: " . $review['product_name'] . "<br>";
  echo "Review Text: " . $review['review_text'] . "<br>";
  echo "User ID: " . $review['user_id'] . "<br>";
  echo "Date: " . $review['date'] . "<br>";
} else {
  echo "Error: " . $review; // Display the error message
}

// Store a review with a specific user ID (for testing)
$review2 = storeUserReview("Another Great Product", "Great product!", "user123");
if (is_array($review2)) {
    echo "<br>Review 2 saved successfully: <br>";
    echo "Review ID: " . $review2['review_id'] . "<br>";
} else {
    echo "<br>Error: " . $review2;
}


//Example with invalid input
$invalidReview = storeUserReview("", "This is a review.");
echo "<br>Invalid Review: " . $invalidReview;
?>
