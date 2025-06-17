

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 *
 * @param string $product_id  The ID of the product or service being reviewed.
 * @param string $username  The username of the reviewer.
 * @param string $review_text  The text of the review.
 * @param array $reviews   (Optional) An array of existing reviews to be displayed.
 *
 * @return array An array containing:
 *   - 'reviews':  An array of reviews (including the new one).
 *   - 'new_review_added':  Boolean - True if a new review was added, false otherwise.
 */
function add_user_review(string $product_id, string $username, string $review_text, array &$reviews = []): array
{
  // Sanitize and validate input (important for security)
  $username = trim($username); // Remove whitespace
  $review_text = trim($review_text);

  if (empty($username) || empty($review_text)) {
    return ['reviews' => $reviews, 'new_review_added' => false]; // Return if no review text
  }

  //  Add the new review
  $new_review = [
    'id' => count($reviews) + 1, // Simple ID - consider using database IDs for production
    'username' => $username,
    'review_text' => $review_text,
    'timestamp' => date('Y-m-d H:i:s') // Add timestamp for context
  ];
  $reviews[] = $new_review;

  return ['reviews' => $reviews, 'new_review_added' => true];
}


/**
 *  Example Usage and Demonstration
 */

// Initialize some existing reviews (for demonstration)
$existing_reviews = [
  ['id' => 1, 'username' => 'JohnDoe', 'review_text' => 'Great product!', 'timestamp' => '2023-10-26 10:00:00'],
  ['id' => 2, 'username' => 'JaneSmith', 'review_text' => 'Could be better.', 'timestamp' => '2023-10-26 11:30:00']
];


// Add a new review
$new_review_data = add_user_review('product123', 'PeterJones', 'Excellent value for money!');

// Display the updated reviews
echo "<h2>Reviews for Product: product123</h2>";
echo "<ul>";
foreach ($new_review_data['reviews'] as $review) {
    echo "<li><strong>Username:</strong> " . htmlspecialchars($review['username']) . "<br>"; // Use htmlspecialchars for security
    echo "<strong>Review:</strong> " . htmlspecialchars($review['review_text']) . "<br>";
    echo "<strong>Timestamp:</strong> " . htmlspecialchars($review['timestamp']) . "</li>";
}
echo "</ul>";


// Another example - adding multiple reviews
$new_reviews = [];
for ($i = 0; $i < 3; $i++) {
    $new_reviews = add_user_review('product123', 'User' . $i, 'Review ' . $i);
}

echo "<h2>Reviews for Product: product123 (Multiple Reviews)</h2>";
echo "<ul>";
foreach ($new_reviews['reviews'] as $review) {
    echo "<li><strong>Username:</strong> " . htmlspecialchars($review['username']) . "<br>";
    echo "<strong>Review:</strong> " . htmlspecialchars($review['review_text']) . "<br>";
    echo "<strong>Timestamp:</strong> " . htmlspecialchars($review['timestamp']) . "</li>";
}
echo "</ul>";
?>
