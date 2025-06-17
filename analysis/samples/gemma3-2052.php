

<?php

/**
 * Class ReviewManager
 *
 * Manages user reviews for a product or item.
 *
 * Attributes:
 *   - $reviews:  An array to store the reviews.
 *   - $productId: The ID of the product being reviewed.
 */
class ReviewManager
{
    private $reviews = [];
    private $productId;

    /**
     * Constructor
     *
     * Initializes the ReviewManager with a product ID.
     *
     * @param int $productId The ID of the product being reviewed.
     */
    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }

    /**
     * Add a new review.
     *
     * @param string $reviewerName The name of the reviewer.
     * @param string $comment The review comment.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $reviewerName, string $comment): bool
    {
        $reviewId = count($this->reviews) + 1; // Simple review ID generation
        $this->reviews[$reviewId] = [
            'reviewer' => $reviewerName,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add a timestamp
        ];
        return true;
    }

    /**
     * Get all reviews for the product.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews(): array
    {
        return $this->reviews;
    }

    /**
     * Get a single review by ID.
     *
     * @param int $reviewId The ID of the review.
     * @return array|null The review object if found, null otherwise.
     */
    public function getReviewById(int $reviewId): ?array
    {
        if (isset($this->reviews[$reviewId])) {
            return $this->reviews[$reviewId];
        }
        return null;
    }

    /**
     * Calculate the average rating for the product.
     *
     * @return float|null The average rating, or null if there are no reviews.
     */
    public function calculateAverageRating(): ?float
    {
        $totalRating = 0;
        $reviewCount = count($this->reviews);

        if ($reviewCount > 0) {
            foreach ($this->reviews as $review) {
                // Assume a rating scale from 1 to 5.  Adapt as needed.
                $totalRating += 3; // Example rating: 1-5
            }
            return (float) ($totalRating / $reviewCount);
        }
        return null;
    }
}

// --- Example Usage ---

// Create a ReviewManager for a product with ID 123
$reviewManager = new ReviewManager(123);

// Add some reviews
$reviewManager->addReview('Alice', 'Great product!  I loved it.');
$reviewManager->addReview('Bob', 'Could be better, but overall good.');
$reviewManager->addReview('Charlie', 'Excellent value for the price.');

// Get all reviews
$allReviews = $reviewManager->getAllReviews();
echo "All Reviews:
";
print_r($allReviews);

// Get a specific review
$review1 = $reviewManager->getReviewById(2);
echo "
Review 2:
";
print_r($review1);

// Calculate the average rating
$averageRating = $reviewManager->calculateAverageRating();
echo "
Average Rating: " . ($averageRating === null ? 'N/A' : $averageRating) . "
";


// Example with no reviews
$noReviewsManager = new ReviewManager(456);
$averageRatingNoReviews = $noReviewsManager->calculateAverageRating();
echo "
Average Rating (no reviews): " . ($averageRatingNoReviews === null ? 'N/A' : $averageRatingNoReviews) . "
";

?>
