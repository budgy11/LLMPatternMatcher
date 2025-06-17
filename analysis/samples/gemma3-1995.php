

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a given item.
 *
 * @param string $item_id The unique ID of the item being reviewed.
 * @param string $reviewer_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param array $reviews (optional) An array of existing reviews to store and display.
 *
 * @return array An updated array of reviews, including the new review if successful.
 */
function create_and_display_review(string $item_id, string $reviewer_name, string $review_text, array &$reviews = []): array
{
  // Validation (basic - you might want more robust validation)
  if (empty($reviewer_name)) {
    return $reviews; // Return existing reviews if no name provided
  }
  if (empty($review_text)) {
    return $reviews; // Return existing reviews if no text provided
  }

  // Create a new review
  $new_review = [
    'item_id' => $item_id,
    'reviewer_name' => $reviewer_name,
    'review_text' => $review_text,
    'timestamp' => date('Y-m-d H:i:s')
  ];

  // Add the new review to the array
  $reviews[] = $new_review;

  // Sort reviews by timestamp (optional -  can be useful for display)
  usort($reviews, function($a, $b) {
    return $a['timestamp'] <=> $b['timestamp'];
  });

  return $reviews;
}


/**
 * Display Reviews Function (for demonstration purposes)
 *
 * This function formats and displays the reviews.
 *
 * @param array $reviews The array of reviews to display.
 */
function display_reviews(array $reviews) {
  echo "<h2>Reviews for Item ID: " . $reviews[0]['item_id'] . "</h2>"; // Access the first item's ID
  echo "<ul>";
  foreach ($reviews as $review) {
    echo "<li>";
    echo "<strong>Reviewer:</strong> " . htmlspecialchars($review['reviewer_name']) . "<br>";
    echo "<strong>Date:</strong> " . $review['timestamp'] . "<br>";
    echo "<strong>Rating:</strong> " . $review['review_text'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}



// Example Usage:

// Initialize an empty array of reviews.  Pass by reference (&) so the function can modify it.
$reviews = [];

// Create a review
$reviews = create_and_display_review('product123', 'John Doe', 'Great product!  I highly recommend it.');

// Create another review
$reviews = create_and_display_review('product456', 'Jane Smith', 'It was okay, nothing special.');

// Display the reviews
display_reviews($reviews);

// Show the final reviews array (for demonstration)
echo "<hr>";
echo "<h3>Final Reviews Array:</h3>";
print_r($reviews); // Output the array to see the results.
?>
