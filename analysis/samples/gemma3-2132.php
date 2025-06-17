

<?php

/**
 * User Review Function
 *
 * This function allows you to collect and display user reviews for a product or service.
 *
 * @param string $productId The unique identifier for the product/service.
 * @param int $maxReviews The maximum number of reviews to allow.
 * @param string $reviewForm  The HTML form for collecting reviews.  Consider using a template engine for this.
 * @param string $reviewDisplayTemplate The template to use for displaying reviews.  Consider using a template engine for this.
 * @param array $reviews  An optional array of existing reviews to display.
 *
 * @return array An array of review data, including the form and displayed reviews.
 */
function collectAndDisplayUserReviews(
    string $productId,
    int $maxReviews = 5,
    string $reviewForm = '
        <form action="process_review.php" method="post">
            <label for="review_text">Your Review:</label><br>
            <textarea id="review_text" name="review_text" rows="4" cols="50"></textarea><br>
            <button type="submit">Submit Review</button>
        </form>
    ',
    string $reviewDisplayTemplate = '
        <h2>Reviews for Product ID: {$productId}</h2>
        <ul>
            {$reviews}
        </ul>
    ',
    array $reviews = []
) {
    // Validate inputs (optional, but recommended)
    if (!is_numeric($productId)) {
        trigger_error('Invalid product ID. Must be a number.', E_USER_WARNING);
        return ['form' => $reviewForm, 'reviews' => $reviews];
    }
    if ($maxReviews <= 0) {
        trigger_error('Maximum reviews must be greater than 0.', E_USER_WARNING);
        $maxReviews = 5; // Default to 5
    }


    // Add the current review to the reviews array (simulating a form submission)
    $reviews = array_merge($reviews, [
        [
            'user' => 'John Doe',
            'rating' => 4,
            'comment' => 'Great product!  Easy to use.'
        ],
        [
            'user' => 'Jane Smith',
            'rating' => 5,
            'comment' => 'Excellent value for money.'
        ]
    ]);

    // Pass data to the display template
    $data = [
        'productId' => $productId,
        'reviews' => $reviews,
    ];

    // Pass the data to the display template
    $output = str_replace(['{$productId}', '{$reviews}'], $data, $reviewDisplayTemplate);

    return ['form' => $reviewForm, 'output' => $output];
}


// Example Usage:
$reviewData = collectAndDisplayUserReviews('12345');

// Display the results
echo $reviewData['output'];
?>
