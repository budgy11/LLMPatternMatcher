

<?php

/**
 * User Review Function
 *
 * This function allows you to collect and display user reviews for a product or item.
 *
 * @param string $productName The name of the product or item being reviewed.
 * @param array $reviews An array of review objects.  Each review object should have at least:
 *                     - `user`:  The user's name or ID.
 *                     - `rating`:  An integer representing the rating (e.g., 1-5).
 *                     - `comment`: The user's comment.
 * @return string HTML markup containing the reviews.
 */
function displayUserReviews(string $productName, array $reviews) {
    if (empty($reviews)) {
        return "<p>No reviews yet.</p>";
    }

    $html = "<div class=\"product-reviews\">";
    $html .= "<h3>Reviews for {$productName}</h3>";
    $html .= "<ul class=\"review-list\">";

    foreach ($reviews as $review) {
        $html .= "<li class=\"review\">";
        $html .= "<p><strong>" . htmlspecialchars($review['user']) . ":</strong> " . htmlspecialchars($review['comment']) . "</p>";
        $html .= "<p>Rating: " . $review['rating'] . "</p>";
        $html .= "</li>";
    }

    $html .= "</ul>";
    $html .= "</div>";

    return $html;
}


/**
 * Example Usage:
 */

// Sample Reviews Data (in a real application, this would come from a database)
$productName = "Awesome Gadget X";
$reviews = [
    [
        'user' => 'John Doe',
        'rating' => 5,
        'comment' => 'This gadget is amazing!  I highly recommend it.'
    ],
    [
        'user' => 'Jane Smith',
        'rating' => 4,
        'comment' => 'Great product, works as expected.  Could be a little cheaper.'
    ],
    [
        'user' => 'Peter Jones',
        'rating' => 3,
        'comment' => 'It's okay.  Not quite what I was hoping for.'
    ]
];

// Display the reviews
$reviewHtml = displayUserReviews($productName, $reviews);

// Output the HTML
echo $reviewHtml;

?>
