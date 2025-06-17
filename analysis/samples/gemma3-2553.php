

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $rating A rating from 1 to 5.
 * @param string $comment The user's review comment.
 * @param string $username The user's name.
 *
 * @return array An array containing:
 *   - 'reviews': An array of review objects.
 *   - 'totalReviews': The total number of reviews.
 *   - 'success': True if the review was added successfully, false otherwise.
 *   - 'message':  A message describing the outcome of the operation.
 */
function addReview(string $productId, string $rating, string $comment, string $username): array
{
    // Validate input (important for security and data integrity)
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return [
            'reviews' => [],
            'totalReviews' => 0,
            'success' => false,
            'message' => "Invalid rating. Rating must be a number between 1 and 5."
        ];
    }

    if (empty($comment)) {
        return [
            'reviews' => [],
            'totalReviews' => 0,
            'success' => false,
            'message' => "Comment cannot be empty."
        ];
    }


    // In a real application, you'd save this data to a database.
    // This is a placeholder for demonstration purposes.

    $review = [
        'productId' => $productId,
        'rating' => $rating,
        'comment' => $comment,
        'username' => $username,
        'timestamp' => time() // Add timestamp for tracking
    ];

    // Add the review to a simple array (replace with database insertion)
    $reviews = getReviewsByProduct($productId);  // Placeholder function to get existing reviews
    $reviews[] = $review;

    //  In a real application, you would sanitize and validate $review data before saving it.

    return [
        'reviews' => $reviews,
        'totalReviews' => count($reviews),
        'success' => true,
        'message' => "Review submitted successfully!"
    ];
}

/**
 * Placeholder function to get reviews by product ID
 * Replace this with actual database retrieval
 *
 * @param string $productId The product ID.
 * @return array An array of review objects.
 */
function getReviewsByProduct(string $productId): array
{
    // In a real implementation, this would fetch data from a database.
    // For demonstration, let's return a static array.
    if ($productId === '123') {
        return [
            [
                'productId' => '123',
                'rating' => 4,
                'comment' => 'Great product!',
                'username' => 'JohnDoe',
                'timestamp' => time()
            ],
            [
                'productId' => '123',
                'rating' => 5,
                'comment' => 'Excellent value for money.',
                'username' => 'JaneSmith',
                'timestamp' => time()
            ]
        ];
    }
    return [];
}


// Example Usage:
$product_id = '123';
$rating = 4;
$comment = 'This is a fantastic product!';
$username = 'TestUser';

$result = addReview($product_id, $rating, $comment, $username);

if ($result['success']) {
    echo "Review submitted successfully!<br>";
    echo "Total Reviews: " . $result['totalReviews'] . "<br>";
    echo "Reviews: ";
    foreach ($result['reviews'] as $review) {
        echo "- " . $review['username'] . ": " . $review['comment'] . " (Rating: " . $review['rating'] . ")" . "<br>";
    }
} else {
    echo "Error: " . $result['message'] . "<br>";
}

// Example of invalid input:
$result = addReview($product_id, 6, 'Bad rating', 'Invalid User');

if (!$result['success']) {
    echo "Error: " . $result['message'] . "<br>";
}
?>
