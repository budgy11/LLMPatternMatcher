

<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 * It uses a simple in-memory array to store reviews.  For production environments,
 * you should use a database.
 *
 * @param string $productId  The product ID the review is for.
 * @param string $userId     The ID of the user writing the review.
 * @param string $rating     The rating (e.g., 1-5).
 * @param string $comment    The review comment.
 * @param int    $reviewId   (Optional) The ID of the review to update.  If not provided, a new review is created.
 *
 * @return array|bool  Returns an array containing the review data if successful, or false if there was an error.
 */
function createOrUpdateReview(string $productId, string $userId, string $rating, string $comment, ?int $reviewId = null): array|bool
{
    // Input validation -  Crucial for security and data integrity
    if (empty($productId) || empty($userId) || empty($rating) || empty($comment)) {
        return false; // Invalid input
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return false; // Invalid rating
    }

    // Create a review
    $review = [
        'product_id' => $productId,
        'user_id' => $userId,
        'rating' => $rating,
        'comment' => $comment,
        'created_at' => time(), // Timestamp of when the review was created
    ];

    // Check if the review already exists
    if ($reviewId !== null) {
        // Update existing review
        $reviews = Reviews::getAllReviews(); // Assumes a Reviews class exists
        foreach ($reviews as $reviewItem) {
            if ($reviewItem['id'] == $reviewId) {
                $review['id'] = $reviewId;
                $review['updated_at'] = time();
                $reviews[] = $review;  // Add the updated review to the array.
                break;
            }
        }
    } else {
        // Add a new review
        $reviews[] = $review;
    }

    // Save the reviews (for this example, we just return the updated array)
    Reviews::saveReviews($reviews); // Saves to a database or in-memory storage

    return $reviews;
}


/**
 *  Example Reviews class for demonstration purposes - can be adapted for a database
 */
class Reviews {
    private static $reviews = [];

    public static function getAllReviews(): array {
        return self::$reviews;
    }

    public static function saveReviews(array $reviews) {
        self::$reviews = $reviews;
    }
}

// --- Example Usage ---

// Create a new review
$reviewData = createOrUpdateReview('P123', 'U456', 4, 'Great product, highly recommended!');
if ($reviewData) {
    echo "Review created successfully:
";
    print_r($reviewData);
} else {
    echo "Error creating review.
";
}


// Update an existing review
$updatedReview = createOrUpdateReview('P123', 'U456', 5, 'Excellent product, even better than expected!', 1); // Update review with ID 1
if ($updatedReview) {
    echo "
Review updated successfully:
";
    print_r($updatedReview);
} else {
    echo "Error updating review.
";
}


// Retrieve all reviews for product 'P123'
$reviewsForProduct = Reviews::getAllReviews();
echo "
Reviews for product P123:
";
print_r($reviewsForProduct);
?>
