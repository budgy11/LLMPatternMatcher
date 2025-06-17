

<?php

/**
 * Class UserReview
 *
 * Handles the creation, retrieval, and display of user reviews.
 */
class UserReview
{
    private $reviews = []; // Array to store reviews
    private $db_connection = null; // Database connection (optional)

    /**
     * Constructor
     *
     * Initializes the UserReview object.  Optionally connects to a database.
     *
     * @param PDO $dbConnection  (Optional)  A PDO object for database connections.
     *                           If not provided, the class will not interact with a database.
     */
    public function __construct(PDO $dbConnection = null)
    {
        $this->reviews = [];
        $this->db_connection = $dbConnection;
    }

    /**
     * Add a new review.
     *
     * @param int $productId The ID of the product being reviewed.
     * @param string $userName The name of the user writing the review.
     * @param string $reviewText The text of the review.
     * @param int $rating The rating (e.g., 1-5).
     * @return bool True on success, false on failure (e.g., invalid rating).
     */
    public function addReview(int $productId, string $userName, string $reviewText, int $rating)
    {
        // Validation (basic - expand as needed)
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        $reviewId = $this->generateReviewId();

        $review = [
            'reviewId' => $reviewId,
            'productId' => $productId,
            'userName' => $userName,
            'reviewText' => $reviewText,
            'rating' => $rating,
            'timestamp' => time()
        ];

        $this->reviews[] = $review;
        return true;
    }

    /**
     * Generate a unique review ID.  A simple counter for demonstration.  
     * In a real application, you'd use a database-generated ID.
     *
     * @return int A unique review ID.
     */
    private function generateReviewId(): int
    {
        static $reviewIdCounter = 1;
        return $reviewIdCounter++;
    }


    /**
     * Get all reviews for a specific product.
     *
     * @param int $productId The ID of the product.
     * @return array An array of review objects, or an empty array if no reviews exist.
     */
    public function getReviewsForProduct(int $productId): array
    {
        $productReviews = [];
        foreach ($this->reviews as $review) {
            if ($review['productId'] == $productId) {
                $productReviews[] = $review;
            }
        }
        return $productReviews;
    }

    /**
     * Get a single review by ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|null The review object if found, null if not found.
     */
    public function getReviewById(int $reviewId): ?array
    {
        foreach ($this->reviews as $review) {
            if ($review['reviewId'] == $reviewId) {
                return $review;
            }
        }
        return null;
    }


    /**
     * Update a review.
     *
     * @param int $reviewId The ID of the review to update.
     * @param string $newReviewText The new text of the review.
     * @return bool True on success, false on failure (e.g., review not found).
     */
    public function updateReview(int $reviewId, string $newReviewText): bool
    {
        $review = $this->getReviewById($reviewId);
        if ($review === null) {
            return false;
        }

        $review['reviewText'] = $newReviewText;
        return true;
    }


    /**
     * Delete a review.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True on success, false if review not found.
     */
    public function deleteReview(int $reviewId): bool
    {
        foreach ($this->reviews as $key => $review) {
            if ($review['reviewId'] == $reviewId) {
                unset($this->reviews[$key]);
                return true;
            }
        }
        return false;
    }

    /**
     * Clear all reviews.  Use with caution!
     */
    public function clearAllReviews(): void
    {
        $this->reviews = [];
    }

    // Example method to output reviews (for demonstration purposes)
    public function displayReviews()
    {
        echo "<pre>";
        foreach ($this->reviews as $review) {
            echo "Review ID: " . $review['reviewId'] . "
";
            echo "Product ID: " . $review['productId'] . "
";
            echo "User: " . $review['userName'] . "
";
            echo "Review: " . $review['reviewText'] . "
";
            echo "Rating: " . $review['rating'] . "
";
            echo "Timestamp: " . $review['timestamp'] . "
";
            echo "---
";
        }
        echo "</pre>";
    }
}

// Example Usage
// Create an instance of the UserReview class
$reviewSystem = new UserReview();

// Add some reviews
$reviewSystem->addReview(1, "John Doe", "Great product!", 5);
$reviewSystem->addReview(1, "Jane Smith", "Could be better.", 3);
$reviewSystem->addReview(2, "Peter Jones", "Amazing!", 5);

// Display all reviews
$reviewSystem->displayReviews();

// Get reviews for product 1
$product1Reviews = $reviewSystem->getReviewsForProduct(1);
echo "<br>Reviews for product 1:<br>";
echo "<pre>";
print_r($product1Reviews);
echo "</pre>";

// Update a review
if ($reviewSystem->updateReview(1, "Fantastic product - highly recommended!") ) {
    echo "<br>Review for product 1 updated.<br>";
} else {
    echo "<br>Failed to update review.<br>";
}

// Delete a review
if ($reviewSystem->deleteReview(2)) {
    echo "<br>Review deleted.<br>";
} else {
    echo "<br>Failed to delete review.<br>";
}

// Display all reviews again
$reviewSystem->displayReviews();

?>
