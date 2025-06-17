

<?php

/**
 * User Review Class
 *
 * This class allows users to submit and manage reviews.
 */
class UserReview {

    /**
     * @var array  An array to store the review data.
     */
    private $review = [];

    /**
     * Sets the review data.
     *
     * @param int   $userId     The ID of the user submitting the review.
     * @param int   $productId The ID of the product being reviewed.
     * @param string $rating    The rating given (e.g., 1-5).
     * @param string $comment   The user's comments.
     * @return bool True on success, false on failure.
     */
    public function setReview(int $userId, int $productId, string $rating, string $comment) {
        // Validate input - crucial for security!
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        if (empty($comment)) {
            return false; // Require a comment
        }

        $this->review = [
            'userId'    => $userId,
            'productId' => $productId,
            'rating'    => $rating,
            'comment'   => $comment,
            'date'      => date('Y-m-d H:i:s') // Add timestamp for tracking
        ];

        return true;
    }

    /**
     * Retrieves a review based on the product ID.
     *
     * @param int $productId The ID of the product.
     * @return array|null The review data if found, null otherwise.
     */
    public function getReviewByProductId(int $productId) {
        // Placeholder - In a real application, you'd query a database
        //  or use other persistent storage. This simulates a lookup.
        $reviews = [
            [
                'userId'    => 123,
                'productId' => 456,
                'rating'    => 4,
                'comment'   => 'Great product!',
                'date'      => date('Y-m-d H:i:s')
            ],
            [
                'userId'    => 456,
                'productId' => 456,
                'rating'    => 5,
                'comment'   => 'Excellent value for money.',
                'date'      => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($reviews as $review) {
            if ($review['productId'] === $productId) {
                return $review;
            }
        }

        return null;
    }


    /**
     * Displays the review data.  Useful for debugging or showing reviews to the user.
     *
     * @return void
     */
    public function displayReview() {
        echo "<pre>";
        print_r($this->review);
        echo "</pre>";
    }
}

// Example Usage:

// Create a new review object
$review = new UserReview();

// Set a review
if ($review->setReview(123, 456, 4, 'This is a fantastic product!')) {
    echo "Review submitted successfully!
";
    $review->displayReview();

    // Get the review for product ID 456
    $review_data = $review->getReviewByProductId(456);

    if ($review_data) {
        echo "Review for product 456:
";
        $review->displayReview();
    } else {
        echo "No review found for product 456.
";
    }
} else {
    echo "Failed to submit review.
";
}


?>
