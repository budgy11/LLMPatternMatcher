

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view reviews for a product or service.
 *
 * @param string $productId  The ID of the product or service being reviewed.
 * @param string $productName The name of the product or service.
 * @param string $reviewText The review text submitted by the user.
 * @param int   $rating     The rating given to the product or service (1-5).
 * @param string $username  The username of the reviewer.
 * @param array $reviews    (Optional) An array of existing reviews to display.
 *
 * @return array An array containing:
 *              - 'reviews': An array of review objects.
 *              - 'newReview': The newly submitted review (if any).
 */
function create_review(string $productId, string $productName, string $reviewText, int $rating, string $username, array &$reviews = []) {
    // Validate input (basic - can be expanded for more robust validation)
    if (empty($reviewText)) {
        return ['reviews' => $reviews, 'newReview' => ['error' => 'Review text cannot be empty.']];
    }
    if ($rating < 1 || $rating > 5) {
        return ['reviews' => $reviews, 'newReview' => ['error' => 'Rating must be between 1 and 5.']];
    }

    // Create a new review object
    $newReview = [
        'id' => count($reviews) + 1, // Simple ID generation - could use a database sequence
        'product_id' => $productId,
        'product_name' => $productName,
        'username' => $username,
        'review_text' => $reviewText,
        'rating' => $rating,
        'date' => date('Y-m-d H:i:s') // Add a timestamp
    ];

    // Add the new review to the array
    $reviews[] = $newReview;

    return ['reviews' => $reviews, 'newReview' => $newReview];
}

// Example Usage:
// $reviews = []; // Initialize an empty array to store reviews

// // Submit a review
// $result = create_review('123', 'Awesome Gadget', 'This gadget is fantastic!', 5, 'JohnDoe');
// print_r($result);

// // Display existing reviews
// if (isset($result['reviews'])) {
//     echo "<h2>Reviews for " . $result['newReview']['product_name'] . "</h2>";
//     foreach ($result['reviews'] as $review) {
//         echo "<h3>" . $review['username'] . "</h3>";
//         echo "<p>" . $review['review_text'] . "</p>";
//         echo "<p>Rating: " . $review['rating'] . "</p>";
//     }
// }

?>
