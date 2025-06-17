

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or item.
 * It includes input validation and basic sanitization.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $reviewerName The name of the reviewer.
 * @param string $reviewText The review text submitted by the user.
 * @param array $existingReviews (Optional) An array of existing reviews.
 * @return array An array containing the updated reviews.  Returns an empty array on error.
 */
function createAndDisplayUserReview(string $productId, string $reviewerName, string $reviewText, array $existingReviews = []) {

  // Input Validation and Sanitization - Crucial for Security!
  if (empty($reviewerName)) {
    error_log("Error: Reviewer name cannot be empty.");
    return []; // Handle invalid input - return an empty array for this scenario
  }

  if (empty($reviewText)) {
    error_log("Error: Review text cannot be empty.");
    return [];
  }

  $reviewText = trim($reviewText);  // Remove leading/trailing whitespace
  if (strlen($reviewText) > 500) { // Limit review text length
      error_log("Error: Review text exceeds the maximum length of 500 characters.");
      return [];
  }


  // Generate a unique ID for the review (using timestamp for uniqueness)
  $reviewId = uniqid();

  // Create the review data
  $review = [
    'id' => $reviewId,
    'productId' => $productId,
    'reviewerName' => $reviewerName,
    'reviewText' => $reviewText,
    'timestamp' => time()  // Store the time of the review
  ];

  // Add the new review to the existing reviews
  $updatedReviews = array_merge($existingReviews, [$review]);  // Merges arrays

  // Log the review submission (for debugging/administration)
  error_log("New review submitted for product ID: " . $productId . " - " . json_encode($review));

  return $updatedReviews;
}

// --- Example Usage ---

// Initial setup - let's assume we have a product with ID '123'
$productReviews = [];

// First review
$newReviews = createAndDisplayUserReview('123', 'John Doe', 'This product is amazing!  I highly recommend it.');
if (!empty($newReviews)) {
    echo "First Review:
";
    print_r($newReviews);
}

// Second review
$newReviews = createAndDisplayUserReview('123', 'Jane Smith', 'It\'s okay, could be better.', $newReviews);
if (!empty($newReviews)) {
    echo "
Second Review:
";
    print_r($newReviews);
}

//Third review - demonstrating adding to existing array
$newReviews = createAndDisplayUserReview('123', 'Peter Jones', 'Fantastic value for money.', $newReviews);
if (!empty($newReviews)) {
    echo "
Third Review:
";
    print_r($newReviews);
}

?>
