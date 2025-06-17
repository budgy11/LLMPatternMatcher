

<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 * It utilizes a database (simulated here for demonstration) to store the reviews.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating A rating from 1 to 5.
 * @param string $comment The review comment.
 * @param int $reviewId (Optional) The ID of the review to update or delete.  If null, creates a new review.
 *
 * @return array An array containing the review ID if successful, or an error message.
 */
function createOrUpdateReview(string $productId, string $username, int $rating, string $comment, ?int $reviewId = null)
{
    // Simulate a database connection (replace with your actual database connection)
    $db = [
        'reviews' => [
            123 => [
                'username' => 'JohnDoe',
                'rating' => 4,
                'comment' => 'Great product, highly recommended!',
                'created_at' => time()
            ],
            456 => [
                'username' => 'JaneSmith',
                'rating' => 5,
                'comment' => 'Excellent!  Just what I needed.',
                'created_at' => time()
            ]
        ]
    ];

    // Input validation
    if (!$productId || !$username || $rating < 1 || $rating > 5 || !$comment) {
        return ['error' => 'Invalid input.  Please provide a valid product ID, username, rating (1-5), and comment.'];
    }

    // Check if review with the same ID exists
    if ($reviewId !== null) {
        if (!isset($db['reviews'][$reviewId])) {
            return ['error' => 'Review with ID ' . $reviewId . ' not found.'];
        }
    }

    // Create a new review
    if ($reviewId === null) {
        $newReview = [
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'created_at' => time()
        ];
        $reviewId = count($db['reviews']) + 1; // Simple ID generation - use a proper strategy in a real app.
        $db['reviews'][$reviewId] = $newReview;
    } else {
        // Update existing review
        $db['reviews'][$reviewId] = [
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'created_at' => time()
        ];
    }

    return ['success' => $reviewId];
}

/**
 * Get all reviews for a product
 *
 * @param int $productId
 * @return array
 */
function getReviewsForProduct(int $productId): array {
    $reviews = [];
    if(isset($db['reviews'][$productId])) {
        $reviews = $db['reviews'][$productId];
    }
    return $reviews;
}

/**
 * Delete a review by its ID.
 *
 * @param int $reviewId
 * @return array  An array with success or error message.
 */
function deleteReview(int $reviewId): array {
    if (!isset($db['reviews'][$reviewId])) {
        return ['error' => 'Review with ID ' . $reviewId . ' not found.'];
    }

    unset($db['reviews'][$reviewId]);
    return ['success' => true];
}



// Example Usage:

// Create a new review
$result = createOrUpdateReview(123, 'NewUser', 5, 'Amazing product!');
print_r($result);

// Get reviews for product 123
$reviews = getReviewsForProduct(123);
print_r($reviews);

// Update a review
$result = createOrUpdateReview(123, 'JohnDoe', 4, 'Updated comment', 1);
print_r($result);

//Delete a review
$result = deleteReview(1);
print_r($result);

// Get reviews for product 123
$reviews = getReviewsForProduct(123);
print_r($reviews);
?>
