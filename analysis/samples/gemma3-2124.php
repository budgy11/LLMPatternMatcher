

<?php

/**
 * Function to display user reviews for a product or service.
 *
 * @param array $reviews An array of review objects.
 *                       Each review object should have properties like:
 *                       - 'user': User's name or ID
 *                       - 'rating': Integer rating (e.g., 1-5)
 *                       - 'comment': User's review text
 * @param string $productName (Optional) The name of the product/service being reviewed.
 * @return string HTML formatted review display.
 */
function displayUserReviews(array $reviews, string $productName = '') {
  if (empty($reviews)) {
    return "<p>No reviews yet.</p>";
  }

  $html = "<div class='reviews'><h2>Reviews for {$productName}</h2>";
  $html .= "<ul>";

  foreach ($reviews as $review) {
    $html .= "<li class='review'>";
    $html .= "<p><strong>" . htmlspecialchars($review['user']) . ":</strong> " . htmlspecialchars($review['comment']) . "</p>";
    $html .= "<p>Rating: " . $review['rating'] . "</p>";
    $html .= "</li>";
  }

  $html .= "</ul></div>";
  return $html;
}

// Example Usage:
// Sample Reviews
$reviews = [
  [
    'user' => 'John Doe',
    'rating' => 5,
    'comment' => 'Great product!  Works exactly as advertised.',
  ],
  [
    'user' => 'Jane Smith',
    'rating' => 4,
    'comment' => 'Good value for money.  Could be a little more durable.',
  ],
  [
    'user' => 'Peter Jones',
    'rating' => 3,
    'comment' => 'It\'s okay.  Not amazing, but not bad either.',
  ],
];

// Display the reviews for "Awesome Gadget"
$reviewHtml = displayUserReviews($reviews, 'Awesome Gadget');
echo $reviewHtml;


//Example using an empty array to show the "No reviews yet" message.
$emptyReviews = [];
$emptyHtml = displayUserReviews($emptyReviews, 'Another Product');
echo $emptyHtml;
?>
