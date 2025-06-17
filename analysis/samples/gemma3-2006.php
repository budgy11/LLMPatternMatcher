

<?php

/**
 * User Review Function
 *
 * This function allows you to generate a formatted HTML list of user reviews.
 *
 * @param array $reviews An array of review objects.  Each object should have:
 *                       - author: The name of the reviewer.
 *                       - rating: An integer representing the rating (e.g., 1-5).
 *                       - comment: The text of the review.
 * @param array $options (optional) An array of options to customize the output.
 *                       - maxRating:  The maximum rating value to display (default: 5).
 *                       - ratingDisplay: How to display the rating (e.g., 'stars', 'text').
 *                       - starIcon:  Path to the star icon image.
 *
 * @return string HTML string of the user reviews.
 */
function generateUserReviews(array $reviews, array $options = []) {
    // Default options
    $maxRating = $options['maxRating'] ?? 5;
    $ratingDisplay = $options['ratingDisplay'] ?? 'stars';
    $starIcon = $options['starIcon'] ?? '';

    if (empty($reviews)) {
        return "<p>No reviews yet.</p>";
    }

    $html = '<ul>';
    foreach ($reviews as $review) {
        $html .= '<li>';
        $html .= '<div class="review">';

        $html .= '<div class="review-author">' . htmlspecialchars($review['author']) . '</div>';

        if ($ratingDisplay === 'stars') {
            $html .= '<div class="review-rating">';
            for ($i = 1; $i <= $maxRating; $i++) {
                if ($i <= $review['rating']) {
                    $html .= '<img src="' . $starIcon . '" alt="Star" width="20" height="20">';
                } else {
                    $html .= '&nbsp;'; // Add space for empty stars
                }
            }
            $html .= '</div>';
        } else {
            $html .= '<div class="review-rating">' . $review['rating'] . '/' . $maxRating . '</div>';
        }

        $html .= '<div class="review-comment">' . htmlspecialchars($review['comment']) . '</div>';
        $html .= '</div>';
        $html .= '</li>';
    }
    $html .= '</ul>';

    return $html;
}

// Example usage:
$reviews = [
    ['author' => 'John Doe', 'rating' => 4, 'comment' => 'Great product!  Highly recommended.'],
    ['author' => 'Jane Smith', 'rating' => 5, 'comment' => 'Excellent value for money.'],
    ['author' => 'Peter Jones', 'rating' => 3, 'comment' => 'It was okay, but could be better.'],
];

// With default options
$reviewsHTML = generateUserReviews($reviews);
echo $reviewsHTML;

// With custom options
$customOptions = [
    'maxRating' => 10,
    'ratingDisplay' => 'text',
    'starIcon' => 'star.png' // Replace with the path to your star icon image
];
$customReviewsHTML = generateUserReviews($reviews, $customOptions);
echo "<hr>";
echo $customReviewsHTML;

?>
