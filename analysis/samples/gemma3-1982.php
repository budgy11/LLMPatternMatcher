

<?php

/**
 * User Review Function
 *
 * This function allows you to create, display, and manage user reviews for a product or item.
 * It provides basic functionality like adding reviews, displaying them, and potentially
 * handling ratings.
 *
 * @param string $productId The unique identifier for the product or item being reviewed.
 * @param string $productName The name of the product/item being reviewed.
 * @param array $reviews An array of review data.  Each element should be an associative array with:
 *                       - 'userId': The unique identifier for the user.
 *                       - 'rating': The rating given by the user (e.g., 1-5).
 *                       - 'comment': The user's comment on the product.
 *
 * @return array An array containing the generated review HTML and any potential errors.
 */
function generateUserReviews(string $productId, string $productName, array $reviews = [])
{
    // Input Validation (Basic - expand for more robust validation)
    if (empty($productId) || empty($productName)) {
        return ['error' => 'Product ID and Product Name are required.'];
    }
    if (empty($reviews)) {
        return ['reviewHTML' => 'No reviews yet.'];
    }

    // Build the review HTML
    $reviewHTML = '<h2>Reviews for ' . $productName . '</h2>';
    $reviewHTML .= $reviewHTML; // Add the introductory heading

    // Loop through reviews and add them to the HTML
    foreach ($reviews as $review) {
        $reviewHTML .= '<div>';
        $reviewHTML .= '<p><strong>User:</strong> ' . $review['userId'] . '</p>';
        $reviewHTML .= '<p><strong>Rating:</strong> ' . $review['rating'] . ' / 5</p>';
        $reviewHTML .= '<p>' . $review['comment'] . '</p>';
        $reviewHTML .= '</div>';
    }

    return ['reviewHTML' => $reviewHTML, 'error' => null];
}

// Example Usage:
$productId = 'product123';
$productName = 'Awesome Widget';

// Sample Review Data (Simulated database results)
$reviews = [
    ['userId' => 'user1', 'rating' => 4, 'comment' => 'Great product!  Very useful.'],
    ['userId' => 'user2', 'rating' => 5, 'comment' => 'Exactly what I needed. Highly recommended.'],
    ['userId' => 'user3', 'rating' => 3, 'comment' => 'It's okay, but could be better.'],
];

$result = generateUserReviews($productId, $productName, $reviews);

if ($result['error'] !== null) {
    echo "Error: " . $result['error'] . "<br>";
} else {
    echo $result['reviewHTML'];
}

?>
