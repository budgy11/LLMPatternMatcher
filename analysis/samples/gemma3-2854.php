

<?php

/**
 * Function to handle user reviews.
 *
 * This function allows users to submit reviews, display existing reviews,
 * and handles simple moderation (e.g., flagging inappropriate content).
 *
 * @param string $review_text The review text submitted by the user.
 * @param int   $product_id  The ID of the product being reviewed.
 * @param array $review_data (Optional) Array of existing reviews for the product.
 *
 * @return array An array containing:
 *   - 'reviews': An array of existing reviews (if any).
 *   - 'new_review': The submitted review text.
 *   - 'error':  Error message if the review submission failed, otherwise null.
 */
function handleUserReview(string $review_text, int $product_id, array $review_data = []): array
{
    // Validation - Basic checks (can be expanded)
    if (empty($review_text)) {
        return ['reviews' => $review_data, 'new_review' => $review_text, 'error' => 'Review text cannot be empty.'];
    }

    //  Consider adding more robust validation here like:
    // - Length limits for review text
    // - Profanity filtering
    // -  Checking for malicious code

    // Add the new review to the existing data.
    $new_review = ['text' => $review_text, 'timestamp' => time()];
    $updated_reviews = array_merge($review_data, [$new_review]);

    return ['reviews' => $updated_reviews, 'new_review' => $review_text, 'error' => null];
}

// --- Example Usage ---

// Initialize some review data (simulating a database)
$productReviews = [
    ['text' => 'Great product!', 'timestamp' => 1678886400],
    ['text' => 'Could be better.', 'timestamp' => 1678886460]
];

// 1. Submit a new review:
$reviewText = 'Excellent value for the price.';
$result = handleUserReview($reviewText, 123); // Assuming product ID 123

if ($result['error'] === null) {
    echo "New Review Submitted:
";
    print_r($result['reviews']);
} else {
    echo "Error submitting review: " . $result['error'] . "
";
}

echo "
";


// 2. Submit another review:
$reviewText2 = 'This is fantastic!  I highly recommend it.';
$result2 = handleUserReview($reviewText2, 123);

if ($result2['error'] === null) {
    echo "New Review Submitted:
";
    print_r($result2['reviews']);
} else {
    echo "Error submitting review: " . $result2['error'] . "
";
}

echo "
";

// 3. Example of error handling:
$emptyReview = handleUserReview("", 456);
if ($emptyReview['error'] !== null) {
    echo "Error submitting empty review: " . $emptyReview['error'] . "
";
}

?>
