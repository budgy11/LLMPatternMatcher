

<?php

/**
 * User Review Function
 *
 * This function allows you to create and display user reviews for a product.
 *
 * @param string $productId The ID of the product to review.
 * @param string $productName The name of the product.
 * @param array $reviews An array of review objects.  Each object should have 'user', 'rating' (1-5), and 'comment' properties.
 * @return string HTML formatted review display.
 */
function displayUserReviews(string $productId, string $productName, array $reviews)
{
    if (empty($reviews)) {
        return "<p>No reviews yet.</p>";
    }

    $html = "<div class='reviews'>";
    $html .= "<h2>Reviews for {$productName} (ID: {$productId})</h2>";
    $html .= "<ul class='review-list'>";

    foreach ($reviews as $review) {
        $html .= "<li class='review-item'>";
        $html .= "<div class='review-header'>";
        $html .= "<span class='user'>User: {$review['user']}</span>";
        $html .= "<span class='rating'>Rating: {$review['rating']}</span>";
        $html .= "</div>";
        $html .= "<p class='review-text'>{$review['comment']}</p>";
        $html .= "</li>";
    }

    $html .= "</ul>";
    $html .= "</div>";

    return $html;
}


// Example Usage (Simulated Reviews)
$productID = '123';
$productName = 'Awesome Widget';
$reviews = [
    [
        'user' => 'John Doe',
        'rating' => 5,
        'comment' => 'This widget is fantastic!  It exceeded my expectations.'
    ],
    [
        'user' => 'Jane Smith',
        'rating' => 4,
        'comment' => 'Good quality, but a bit pricey.'
    ],
    [
        'user' => 'Peter Jones',
        'rating' => 3,
        'comment' => 'It does the job, nothing special.'
    ]
];

// Display the reviews
$reviewHTML = displayUserReviews($productID, $productName, $reviews);

// Output the HTML (you would typically output this to your web page)
echo $reviewHTML;

?>
