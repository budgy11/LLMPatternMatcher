

<?php

/**
 * User Review Function
 *
 * This function allows you to collect and display user reviews for a product or service.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param array $reviews An array of review objects. Each review object should have:
 *                         - $author: The name of the reviewer.
 *                         - $rating: The rating given by the reviewer (e.g., 1-5).
 *                         - $comment: The user's written review.
 * @return string HTML formatted review section.
 */
function displayUserReviews(string $productName, array $reviews)
{
    if (empty($reviews)) {
        return "<p>No reviews yet.</p>";
    }

    $html = "<div class=\"review-container\">";
    $html .= "<h3>Reviews for {$productName}</h3>";
    $html .= "<ul class=\"review-list\">";

    foreach ($reviews as $review) {
        $html .= "<li class=\"review-item\">";
        $html .= "<p><strong>Author:</strong> {$review['author']}</p>";
        $html .= "<p><strong>Rating:</strong> {$review['rating']}/5</p>";
        $html .= "<p><strong>Comment:</strong> {$review['comment']}</p>";
        $html .= "</li>";
    }

    $html .= "</ul>";
    $html .= "</div>";

    return $html;
}


// Example Usage:
$productName = "Awesome Widget";

$reviews = [
    [
        'author' => 'John Doe',
        'rating' => 5,
        'comment' => 'This widget is amazing!  It does exactly what I need and it's very well-built.',
    ],
    [
        'author' => 'Jane Smith',
        'rating' => 4,
        'comment' => 'Great product.  Easy to use and the support team was very helpful.',
    ],
    [
        'author' => 'Peter Jones',
        'rating' => 3,
        'comment' => 'It\'s okay.  Could be better but for the price, it\'s decent.',
    ],
];


// Get the HTML output
$reviewHTML = displayUserReviews($productName, $reviews);

// Output the HTML (you would typically display this in your HTML page)
echo $reviewHTML;

?>
