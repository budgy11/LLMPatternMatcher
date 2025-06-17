

<?php

/**
 * User Review Function
 * 
 * This function allows you to collect and display user reviews for a product or item.
 * It includes input validation, data sanitization, and basic formatting.
 * 
 * @param string $productName The name of the product being reviewed.
 * @param array $reviews An array of review objects.  Each object should have:
 *                     - name: The reviewer's name.
 *                     - rating: An integer from 1 to 5 representing the rating.
 *                     - comment: The review text.
 * @return string The HTML output of the reviews.  Returns an empty string if no reviews are found.
 */
function displayUserReviews(string $productName, array $reviews) {
  // Input Validation - Basic check for empty reviews array
  if (empty($reviews)) {
    return "<p>No reviews yet.</p>";
  }

  $html = "<div class=\"product-reviews\">";
  $html .= "<h3>Reviews for {$productName}</h3>";
  $html .= "<ul>";

  foreach ($reviews as $review) {
    // Data Sanitization - Very basic, consider more robust sanitization for production
    $name = htmlspecialchars($review['name'], ENT_QUOTES, 'UTF-8');
    $rating = htmlspecialchars($review['rating'], ENT_QUOTES, 'UTF-8');
    $comment = htmlspecialchars($review['comment'], ENT_QUOTES, 'UTF-8');

    $html .= "<li class=\"review\">";
    $html .= "<p><strong>{$name}:</strong> {$rating} stars</p>";
    $html .= "<p>{$comment}</p>";
    $html .= "</li>";
  }

  $html .= "</ul>";
  $html .= "</div>";

  return $html;
}


/**
 * Example Usage:
 */

// Sample Reviews (replace with actual data from a database or form)
$reviews = [
  [
    'name' => 'John Doe',
    'rating' => 4,
    'comment' => 'Great product!  Works exactly as advertised.',
  ],
  [
    'name' => 'Jane Smith',
    'rating' => 5,
    'comment' => 'Excellent value for the price. Highly recommended.',
  ],
  [
    'name' => 'Peter Jones',
    'rating' => 3,
    'comment' => 'It\'s okay, but could be better.',
  ]
];

// Call the function to display the reviews
$reviewHTML = displayUserReviews('Awesome Widget', $reviews);

// Output the HTML to the browser
echo $reviewHTML;


?>
