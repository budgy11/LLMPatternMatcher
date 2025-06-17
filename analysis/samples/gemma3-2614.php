

<?php

/**
 * User Review Function
 * 
 * This function allows users to submit and view reviews for a product.
 *
 * It includes functionalities for:
 * - Creating a new review
 * - Displaying existing reviews
 * - Basic validation (to prevent spam/invalid input)
 *
 * @param string $product_id The ID of the product the review is for.
 * @param string $name The reviewer's name.
 * @param string $comment The review comment.
 * @param array $reviews (Optional) An array of existing reviews to be passed for display.
 *
 * @return array An array containing:
 *   - 'reviews': An array of review objects.
 *   - 'new_review_success': True if the new review was successfully added, false otherwise.
 *   - 'error': An error message if something went wrong, or null if no error.
 */
function create_and_display_reviews(string $product_id, string $name, string $comment, array &$reviews = []) {

    // Input validation (basic - you should enhance this for a real application)
    if (empty($name)) {
        return ['reviews' => $reviews, 'new_review_success' => false, 'error' => 'Name cannot be empty.'];
    }
    if (empty($comment)) {
        return ['reviews' => $reviews, 'new_review_success' => false, 'error' => 'Comment cannot be empty.'];
    }

    // Create the review object (you can expand this with more details like date, rating, etc.)
    $review = [
        'product_id' => $product_id,
        'name' => $name,
        'comment' => $comment,
        'timestamp' => time()
    ];

    // Add the new review to the array
    $reviews[] = $review;

    return ['reviews' => $reviews, 'new_review_success' => true, 'error' => null];
}


// Example Usage:

// Initialize an empty array of reviews
$reviews = [];

// Create a new review
$result = create_and_display_reviews('product_123', 'John Doe', 'Great product!  I highly recommend it.');

if ($result['new_review_success']) {
    echo "Review created successfully!
";
} else {
    echo "Error creating review: " . $result['error'] . "
";
}

// Display all reviews
echo "<h2>All Reviews for Product 123:</h2>
";
foreach ($result['reviews'] as $review) {
    echo "<b>Name:</b> " . $review['name'] . "<br>";
    echo "<b>Comment:</b> " . $review['comment'] . "<br>";
    echo "<b>Date:</b> " . date("Y-m-d H:i:s", $review['timestamp']) . "<br>";
    echo "<hr>
";
}


// Another example creating a review with error handling
$result2 = create_and_display_reviews('product_456', '', 'Another review.');

if ($result2['new_review_success']) {
    echo "Review created successfully!
";
} else {
    echo "Error creating review: " . $result2['error'] . "
";
}


?>
