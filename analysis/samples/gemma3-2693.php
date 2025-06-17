

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 *
 * @param string $productName  The name of the product or service being reviewed.
 * @param string $reviewerName The name of the reviewer.
 * @param string $reviewText   The text of the review.
 * @param array $reviews       (Optional) An array of existing reviews to display.
 *
 * @return array An array containing:
 *   - 'reviews': The updated array of reviews (including the new one).
 *   - 'newReview': The submitted review (for confirmation).
 *   - 'error':     (Optional) An error message if the review submission failed.
 */
function submit_review(string $productName, string $reviewerName, string $reviewText, array &$reviews = []) {
  // Basic validation (you can expand this with more robust validation)
  if (empty($reviewerName) || empty($reviewText)) {
    return ['reviews' => $reviews, 'newReview' => "Error: Reviewer name and review text are required."];
  }

  // Format the review
  $review = [
    'reviewer' => $reviewerName,
    'date'     => date("Y-m-d H:i:s"), // Add timestamp
    'text'     => $reviewText,
  ];

  // Add the new review to the array
  $reviews[] = $review;

  // Return the updated array and confirmation
  return ['reviews' => $reviews, 'newReview' => "Review submitted successfully for $productName."];
}


// Example Usage:
$product = "Awesome Gadget X";

// Submit a review
$result = submit_review($product, "John Doe", "This gadget is amazing!  I love it!");

if (isset($result['error'])) {
  echo "<p style='color:red;'>Error: " . $result['error'] . "</p>";
} else {
  echo "<h2>Reviews for " . $product . "</h2>";
  echo "<ul>";
  foreach ($result['reviews'] as $review) {
    echo "<li><strong>" . $review['reviewer'] . ":</strong> " . $review['text'] . "</li>";
  }
  echo "</ul>";
}

// Example with an invalid submission:
$result2 = submit_review("Another Product", "", "This is a test review.");

if (isset($result2['error'])) {
    echo "<p style='color:red;'>Error: " . $result2['error'] . "</p>";
}
?>
