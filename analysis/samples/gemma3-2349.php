

<?php

/**
 * User Review Function
 *
 * This function allows you to generate a simple user review display based on
 * a list of reviews.  It provides basic formatting for display.
 *
 * @param array $reviews An array of review objects.  Each object should have:
 *                       - 'user': The username of the reviewer.
 *                       - 'rating': The rating (e.g., 1-5).
 *                       - 'comment': The review text.
 * @param int $limit The maximum number of reviews to display. Defaults to 5.
 *
 * @return string HTML markup for displaying the reviews.
 */
function displayUserReviews(array $reviews, $limit = 5)
{
  if (empty($reviews)) {
    return "<p>No reviews yet.</p>";
  }

  $reviewsToDisplay = array_slice($reviews, min($limit, count($reviews))); // Limit the number of reviews

  $html = '<div class="user-reviews">';
  foreach ($reviewsToDisplay as $review) {
    $html .= '<div class="review">';
    $html .= '<p class="user"><strong>' . htmlspecialchars($review['user']) . '</strong></p>';
    $html .= '<p class="rating">Rating: ' . $review['rating'] . '</p>';
    $html .= '<p class="comment">' . nl2br(htmlspecialchars($review['comment'])) . '</p>'; // nl2br for line breaks
    $html .= '</div>';
  }
  $html .= '</div>';

  return $html;
}


/**
 * Example Usage (Demonstration)
 */

// Sample Reviews
$reviews = [
  ['user' => 'Alice', 'rating' => 5, 'comment' => "This product is amazing! I highly recommend it."],
  ['user' => 'Bob', 'rating' => 4, 'comment' => "Good product, but could be a little better."],
  ['user' => 'Charlie', 'rating' => 3, 'comment' => "It's okay.  Nothing special."],
  ['user' => 'David', 'rating' => 5, 'comment' => "Excellent value for the price."],
  ['user' => 'Eve', 'rating' => 2, 'comment' => "Not what I expected."],
  ['user' => 'Frank', 'rating' => 4, 'comment' => "A solid choice."],
  ['user' => 'Grace', 'rating' => 1, 'comment' => "Very disappointing."],
];


// Display the first 3 reviews
$reviewHTML = displayUserReviews($reviews, 3);
echo $reviewHTML;
?>
