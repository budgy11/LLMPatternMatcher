

<?php

/**
 *  User Review Function
 *
 *  This function allows users to submit and display reviews for a given item.
 *
 *  @param string $item_name The name of the item being reviewed.
 *  @param string $item_description  A short description of the item (optional).
 *  @param array $reviews An array of review objects. Each review object should have:
 *                       - 'user' => string - The name of the user who wrote the review.
 *                       - 'rating' => int  -  The rating given by the user (e.g., 1-5).
 *                       - 'comment' => string - The user's comment.
 *
 *  @return string  HTML output displaying the reviews.  Returns a message if no reviews exist.
 */
function display_reviews(string $item_name, string $item_description = '', array $reviews = [])
{
    if (empty($reviews)) {
        return "<p>No reviews yet.  Be the first to review!</p>";
    }

    $html = "<h2>Reviews for " . htmlspecialchars($item_name) . "</h2>";
    if (!empty($item_description)) {
        $html .= "<p>" . htmlspecialchars($item_description) . "</p>";
    }

    $html .= "<ul>";
    foreach ($reviews as $review) {
        $html .= "<li>";
        $html .= "<strong>" . htmlspecialchars($review['user']) . ":</strong> " . htmlspecialchars($review['comment']) . " (Rating: " . htmlspecialchars($review['rating']) . ")";
        $html .= "</li>";
    }
    $html .= "</ul>";

    return $html;
}


// Example Usage (Illustrative)
// Simulate a database retrieval for reviews
$reviews_data = [
    [
        'user' => 'Alice',
        'rating' => 5,
        'comment' => 'Amazing product!  Exactly what I was looking for.',
    ],
    [
        'user' => 'Bob',
        'rating' => 4,
        'comment' => 'Good quality for the price.  Could be a little better.',
    ],
    [
        'user' => 'Charlie',
        'rating' => 3,
        'comment' => 'It was okay.  Not amazing, but not bad either.',
    ],
];

// Display the reviews
echo display_reviews('Awesome Widget', 'A fantastic widget for all your needs!');
echo display_reviews('Basic Gadget'); //Demonstrating no reviews case
?>
