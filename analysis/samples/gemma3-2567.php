

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or item.
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $reviewText The review text submitted by the user.
 * @param int $userId (Optional) The ID of the user submitting the review.  Defaults to 0 for anonymous submissions.
 * @param string $timestamp (Optional)  Timestamp for the review - defaults to current time.
 * @return array An array containing the review details, or an error message if validation fails.
 */
function createReview(string $productName, string $reviewText, int $userId = 0, string $timestamp = null) {

  // Input Validation - Very Important!
  if (empty($productName)) {
    return ['error' => 'Product name cannot be empty.'];
  }
  if (empty($reviewText)) {
    return ['error' => 'Review text cannot be empty.'];
  }
  if (!is_numeric($userId)) {
      $userId = 0;  // If invalid ID, default to anonymous.
  }


  //  You would likely store this in a database here
  //  For demonstration purposes, we'll just return an array
  //  representing the review details.


  $review = [
    'productName' => $productName,
    'reviewText' => $reviewText,
    'userId' => $userId,
    'timestamp' => $timestamp ?? time() // Use current time if timestamp is not provided
  ];

  return $review;
}


/**
 * Displays a single review.
 *
 * @param array $review A review array (as returned by createReview).
 * @return string HTML output of the review, or an error message if the review is not valid.
 */
function displayReview(array $review) {
  if (empty($review) || !isset($review['productName']) || !isset($review['reviewText'])) {
    return "<p style='color: red;'>Invalid Review Data</p>";
  }

  return "<div style='border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;'>";
  return "<p><strong>Product:</strong> " . htmlspecialchars($review['productName']) . "</p>";
  return "<p><strong>Review:</strong> " . htmlspecialchars($review['reviewText']) . "</p>";
  return "<p><strong>User:</strong> " . ($review['userId'] > 0 ? "User #" . $review['userId'] : "Anonymous") . "</p>";
  return "<p><strong>Timestamp:</strong> " . date("Y-m-d H:i:s", $review['timestamp']) . "</p>"; // Format timestamp
  return "</div>";
}



// --- Example Usage ---

// 1. Create a Review
$reviewData = createReview("Awesome Widget", "This widget is fantastic! It does everything I need and more.", 123);

if (isset($reviewData['error'])) {
  echo "<p style='color: red;'>Error: " . $reviewData['error'] . "</p>";
} else {
  // 2. Display the Review
  $reviewOutput = displayReview($reviewData);
  echo $reviewOutput;
}


// Example of creating an invalid review:
$invalidReview = createReview("", "This is a bad review.");
if (isset($invalidReview['error'])) {
    echo "<p style='color: red;'>Error: " . $invalidReview['error'] . "</p>";
}
?>
