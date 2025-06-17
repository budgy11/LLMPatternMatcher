

<?php

/**
 * Reviews Class - Manages user reviews for a product or item.
 */
class Reviews {

    private $reviews = []; // Store the reviews in an array
    private $productId; // The ID of the product/item this reviews are for

    /**
     * Constructor - Initializes the Reviews object with a product ID.
     *
     * @param int $productId The ID of the product/item.
     */
    public function __construct(int $productId) {
        $this->productId = $productId;
    }

    /**
     * Add a new review.
     *
     * @param string $name The reviewer's name.
     * @param string $rating The rating given (e.g., 1-5 stars).
     * @param string $comment The reviewer's comments.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $name, string $rating, string $comment): bool {
        if (empty($name) || empty($rating) || empty($comment)) {
            return false; // Validation: Ensure all fields are filled.
        }

        // Validation:  More robust validation could be added here.
        $rating = (int) $rating; // Convert rating to integer.
        if ($rating < 1 || $rating > 5) {
            return false; // Rating must be between 1 and 5.
        }

        $review = [
            'name' => $name,
            'rating' => $rating,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add timestamp for review date.
        ];
        $this->reviews[] = $review;
        return true;
    }

    /**
     * Get all reviews for the product.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews(): array {
        return $this->reviews;
    }

    /**
     * Get average rating for the product.
     *
     * @return float|null The average rating, or null if no reviews exist.
     */
    public function getAverageRating(): ?float {
        if (empty($this->reviews)) {
            return null;
        }

        $totalRating = 0;
        foreach ($this->reviews as $review) {
            $totalRating += $review['rating'];
        }
        return $totalRating / count($this->reviews);
    }
}

// Example Usage:

// Create a Reviews object for a product with ID 123
$reviews = new Reviews(123);

// Add some reviews
$reviews->addReview("Alice", 5, "Great product! Highly recommended.");
$reviews->addReview("Bob", 4, "Good value for money.");
$reviews->addReview("Charlie", 3, "It's okay, could be better.");

// Get all reviews
$allReviews = $reviews->getAllReviews();
echo "All Reviews:
";
print_r($allReviews);

// Get the average rating
$averageRating = $reviews->getAverageRating();
echo "
Average Rating: " . ($averageRating !== null ? $averageRating : "No reviews yet.") ;

?>
