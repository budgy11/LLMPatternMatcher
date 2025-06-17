

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewText The user's review text.
 * @param int $userId (Optional) The ID of the user submitting the review.  If not provided, a default/anonymous user is used.
 * @param string $reviewerName (Optional) The name to display for the reviewer. If not provided, a default name is used.
 *
 * @return array An array containing review details (success/failure, review text, reviewer details).
 */
function submitReview(string $productName, string $reviewText, $userId = null, $reviewerName = null) {
  // Input Validation - crucial for security and data integrity
  if (empty($productName)) {
    return ['success' => false, 'message' => 'Product name cannot be empty.'];
  }
  if (empty($reviewText)) {
    return ['success' => false, 'message' => 'Review text cannot be empty.'];
  }

  // Handle User ID (if provided) -  This is a simplified example.  In a real application,
  // you would typically authenticate and validate the user.
  if ($userId === null) {
    $userId = 1; // Default user ID.  Change this for a real system.
  }

  // Handle Reviewer Name - Default if not provided
  if ($reviewerName === null) {
    $reviewerName = 'Anonymous User';
  }

  //  Simulate saving the review to a database or file.  In a real application,
  //  replace this with your database interaction logic.
  $reviewId = time(); // Generate a unique ID for the review.
  $reviewData = [
    'reviewId' => $reviewId,
    'productId' => $productName,
    'reviewText' => $reviewText,
    'userId' => $userId,
    'reviewerName' => $reviewerName,
    'dateSubmitted' => date('Y-m-d H:i:s')
  ];

  // Simulate saving the review to a file (for demonstration)
  file_put_contents('reviews.txt', $reviewData . PHP_EOL, FILE_APPEND);


  return ['success' => true, 'review' => $reviewData];
}


/**
 * Display a Review
 *
 * This function retrieves and displays a review based on its ID.
 *
 * @param string $reviewId The ID of the review to display.
 *
 * @return array An array containing review details (success/failure, review details).
 */
function displayReview(string $reviewId) {
  // Simulate retrieving the review from a database or file.
  // Replace this with your database query logic.
  $reviews = [];
  if (file_exists('reviews.txt')) {
      $lines = file('reviews.txt', FILE_IGNORE_NEW_LINES);
      foreach ($lines as $line) {
          $reviews[] = explode(',', $line);
      }
  }

  foreach ($reviews as $review) {
    if ($review[0] == $reviewId) {
      return ['success' => true, 'review' => $review];
    }
  }

  return ['success' => false, 'message' => 'Review not found.'];
}



// Example Usage:
$product = 'Awesome Gadget X';

// Submit a review
$reviewResult = submitReview($product, 'This gadget is amazing!  I highly recommend it.', 123, 'John Doe');

if ($reviewResult['success']) {
  echo "Review submitted successfully!
";
  echo "Review ID: " . $reviewResult['review']['reviewId'] . "
";
  echo "Reviewer: " . $reviewResult['review']['reviewerName'] . "
";
  echo "Review Text: " . $reviewResult['review']['reviewText'] . "
";
} else {
  echo "Error submitting review: " . $reviewResult['message'] . "
";
}

// Display the review
$reviewDisplayResult = displayReview($reviewResult['review']['reviewId']);

if ($reviewDisplayResult['success']) {
  echo "
--- Displaying Review ---
";
  echo "Review ID: " . $reviewDisplayResult['review']['reviewId'] . "
";
  echo "Reviewer: " . $reviewDisplayResult['review']['reviewerName'] . "
";
  echo "Review Text: " . $reviewDisplayResult['review']['reviewText'] . "
";
  echo "Date Submitted: " . $reviewDisplayResult['review']['dateSubmitted'] . "
";
} else {
  echo "Error displaying review: " . $reviewDisplayResult['message'] . "
";
}
?>
