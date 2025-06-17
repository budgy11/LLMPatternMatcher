

<?php

/**
 * This function handles user reviews for a product.
 *
 * @param string $productName The name of the product being reviewed.
 * @param array $reviews An array of review objects. Each review object should have:
 *                       - $rating: An integer representing the rating (1-5).
 *                       - $comment: A string containing the review text.
 * @return array An array of formatted review strings for display.
 */
function formatUserReviews(string $productName, array $reviews): array {
    $formattedReviews = [];

    if (empty($reviews)) {
        $formattedReviews[] = "No reviews yet for $productName.";
        return $formattedReviews;
    }

    foreach ($reviews as $review) {
        $rating = $review['rating'];
        $comment = $review['comment'];

        // Validate rating (optional but recommended)
        if ($rating < 1 || $rating > 5) {
            continue; // Skip invalid reviews
        }

        $formattedReview = "Rating: $rating - Comment: $comment";
        $formattedReviews[] = $formattedReview;
    }

    return $formattedReviews;
}


// Example usage:
$product = "Awesome Widget";
$reviews = [
    ['rating' => 5, 'comment' => "This widget is fantastic! It does everything I need."],
    ['rating' => 4, 'comment' => "Great product, but could be a bit cheaper."],
    ['rating' => 3, 'comment' => "It's okay, nothing special."],
    ['rating' => 1, 'comment' => "Terrible! Don't buy this."],
];

$formatted = formatUserReviews($product, $reviews);

// Print the formatted reviews
echo "Reviews for $product:<br>";
foreach ($formatted as $review) {
    echo $review . "<br>";
}

?>
