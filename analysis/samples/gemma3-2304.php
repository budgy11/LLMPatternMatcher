

<?php

/**
 * User Review Function
 *
 * This function allows you to create and display user reviews.
 *
 * @param string $productName  The name of the product being reviewed.
 * @param string $userName     The name of the user submitting the review.
 * @param string $reviewText   The text of the review.
 * @param int   $rating       The rating given by the user (1-5).
 * @param array $reviews      (Optional) An array of existing reviews to append to.
 *
 * @return array An array containing the new review object and the updated array of reviews.
 */
function createAndDisplayReview(string $productName, string $userName, string $reviewText, int $rating, array &$reviews = []) {
    // Validate the rating
    if ($rating < 1 || $rating > 5) {
        return ['status' => 'error', 'message' => 'Invalid rating.  Rating must be between 1 and 5.'];
    }

    // Create a new review object (you can customize this further)
    $review = [
        'product'   => $productName,
        'user'      => $userName,
        'text'      => $reviewText,
        'rating'    => $rating,
        'timestamp' => time() // Add a timestamp for tracking
    ];

    // Append the new review to the array
    $reviews[] = $review;

    return ['status' => 'success', 'review' => $review, 'reviews' => $reviews];
}


// Example Usage:

// Simulate retrieving existing reviews (from a database, file, etc.)
$existingReviews = [
    ['product' => 'Laptop X', 'user' => 'Alice', 'text' => 'Great laptop! Fast and reliable.', 'rating' => 5, 'timestamp' => 1678886400],
    ['product' => 'Smartphone Y', 'user' => 'Bob', 'text' => 'Good phone, but battery life is short.', 'rating' => 3, 'timestamp' => 1678886460]
];

// Create a new review
$newReviewResult = createAndDisplayReview(
    'Laptop X',
    'Charlie',
    'Excellent value for the price.',
    4
);

// Display the results
if ($newReviewResult['status'] === 'success') {
    echo "<h2>New Review:</h2>";
    echo "<p><b>Product:</b> " . $newReviewResult['review']['product'] . "</p>";
    echo "<p><b>User:</b> " . $newReviewResult['review']['user'] . "</p>";
    echo "<p><b>Rating:</b> " . $newReviewResult['review']['rating'] . "</p>";
    echo "<p><b>Review:</b> " . $newReviewResult['review']['text'] . "</p>";
    echo "<p><b>Timestamp:</b> " . date('Y-m-d H:i:s', $newReviewResult['review']['timestamp']) . "</p>";

    echo "<hr>";

    echo "<h2>All Reviews:</h2>";
    foreach ($newReviewResult['reviews'] as $review) {
        echo "<p><b>Product:</b> " . $review['product'] . "</p>";
        echo "<p><b>User:</b> " . $review['user'] . "</p>";
        echo "<p><b>Rating:</b> " . $review['rating'] . "</p>";
        echo "<p><b>Review:</b> " . $review['text'] . "</p>";
        echo "<p><b>Timestamp:</b> " . date('Y-m-d H:i:s', $review['timestamp']) . "</p>";
        echo "<hr>";
    }

} else {
    echo "<p style='color: red;'>Error creating review: " . $newReviewResult['message'] . "</p>";
}

// Example of invalid rating
$invalidReviewResult = createAndDisplayReview('Tablet Z', 'David', 'Okay', 6);

if($invalidReviewResult['status'] === 'error'){
    echo "<p style='color: red;'>Invalid Review Attempt: " . $invalidReviewResult['message'] . "</p>";
}
?>
