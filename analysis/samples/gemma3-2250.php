

<?php

/**
 * User Review Class
 *
 * This class allows users to submit and view reviews for a product or service.
 */
class UserReview {

    private $productId;
    private $reviews = [];

    /**
     * Constructor
     *
     * @param int $productId The ID of the product or service being reviewed.
     */
    public function __construct(int $productId) {
        $this->productId = $productId;
    }

    /**
     * Add a new review.
     *
     * @param string $name The user's name.
     * @param string $comment The review text.
     * @param int $rating  The rating from 1 to 5.
     * @return bool True on success, false on failure (e.g., invalid rating).
     */
    public function addReview(string $name, string $comment, int $rating): bool {
        if (!isset($name, $comment) || empty($name) || empty($comment)) {
            return false; // Require name and comment
        }

        if ($rating < 1 || $rating > 5) {
            return false; // Rating must be between 1 and 5
        }

        $this->reviews[] = [
            'name' => $name,
            'comment' => $comment,
            'rating' => $rating
        ];
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
     * Get the average rating for this product.
     *
     * @return float|null The average rating, or null if no reviews exist.
     */
    public function getAverageRating(): ?float {
        if (empty($this->reviews)) {
            return null;
        }

        $totalRating = 0;
        $numReviews = count($this->reviews);

        foreach ($this->reviews as $review) {
            $totalRating += $review['rating'];
        }

        return $totalRating / $numReviews;
    }

    /**
     * Get a single review by its index.
     *
     * @param int $index The index of the review to retrieve (0-based).
     * @return array|null The review object at the given index, or null if the index is invalid.
     */
    public function getReview(int $index): ?array {
        if ($index >= count($this->reviews)) {
            return null;
        }
        return $this->reviews[$index];
    }

}


/**
 * Example Usage
 */

// Create a review object for a product
$productReview = new UserReview(123);

// Add some reviews
$productReview->addReview("John Doe", "Great product!  I love it.", 5);
$productReview->addReview("Jane Smith", "Could be better.", 3);
$productReview->addReview("Peter Jones", "Excellent value for the money.", 4);

// Get all reviews
$allReviews = $productReview->getAllReviews();
echo "All Reviews:
";
foreach ($allReviews as $review) {
    echo "  Name: " . $review['name'] . "
";
    echo "  Comment: " . $review['comment'] . "
";
    echo "  Rating: " . $review['rating'] . "
";
    echo "  --- 
";
}

// Get the average rating
$averageRating = $productReview->getAverageRating();
echo "Average Rating: " . ($averageRating !== null ? $averageRating : "No reviews yet") . "
";

// Get a specific review
$specificReview = $productReview->getReview(1);
if ($specificReview) {
    echo "Specific Review:
";
    echo "  Name: " . $specificReview['name'] . "
";
    echo "  Comment: " . $specificReview['comment'] . "
";
    echo "  Rating: " . $specificReview['rating'] . "
";
} else {
    echo "Review not found.
";
}
?>
