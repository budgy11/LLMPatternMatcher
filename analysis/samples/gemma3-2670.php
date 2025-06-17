

<?php

/**
 * Class Review
 *
 * Manages user reviews for a product or service.
 */
class Review {

    private $reviews = []; // Array to store reviews
    private $productId; // ID of the product/service being reviewed

    /**
     * Constructor
     *
     * Initializes the Review object.
     *
     * @param int $productId The ID of the product or service being reviewed.
     */
    public function __construct(int $productId) {
        $this->productId = $productId;
    }

    /**
     * Add a new review.
     *
     * @param string $username The username of the reviewer.
     * @param string $comment The review comment.
     * @param int $rating  The rating (1-5).
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $username, string $comment, int $rating): bool {
        if (empty($username) || empty($comment) || $rating < 1 || $rating > 5) {
            return false; // Invalid input
        }

        $review = [
            'username' => $username,
            'comment' => $comment,
            'rating' => $rating,
            'timestamp' => time() // Add a timestamp for ordering
        ];

        $this->reviews[] = $review;
        return true;
    }


    /**
     * Get all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews(): array {
        return $this->reviews;
    }

    /**
     * Get reviews by product ID.
     *
     * @param int $productId The product ID to filter by.
     * @return array An array of review objects for the specified product ID.
     */
    public function getReviewsByProductId(int $productId): array {
        return array_filter($this->reviews, function($review) use ($productId) {
            return $review['productId'] === $productId;
        });
    }

    /**
     * Get average rating for a product.
     *
     * @param int $productId The product ID to get the average rating for.
     * @return float|null The average rating, or null if there are no reviews.
     */
    public function getAverageRating(int $productId): ?float {
        $reviews = $this->getReviewsByProductId($productId);
        if (empty($reviews)) {
            return null;
        }

        $totalRating = 0;
        foreach ($reviews as $review) {
            $totalRating += $review['rating'];
        }

        return round($totalRating / count($reviews), 2); // Round to 2 decimal places
    }

}


// Example Usage:
$reviewManager = new Review(123); // Assuming product ID 123

// Add some reviews
$reviewManager->addReview('JohnDoe', 'Great product!', 5);
$reviewManager->addReview('JaneSmith', 'Good value for money', 4);
$reviewManager->addReview('PeterJones', 'Could be better', 3);

// Get all reviews
$allReviews = $reviewManager->getAllReviews();
print_r($allReviews);

// Get reviews for product 123
$product123Reviews = $reviewManager->getReviewsByProductId(123);
print_r($product123Reviews);

// Get average rating for product 123
$averageRating = $reviewManager->getAverageRating(123);
echo "Average rating for product 123: " . ($averageRating !== null ? $averageRating : "No reviews yet.") . "
";

// Add a review with invalid input
$reviewManager->addReview("", "Invalid review", 2);
echo "Invalid review attempt was handled correctly.
";

?>
