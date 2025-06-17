

<?php

/**
 * This class provides a simple user review system.
 */
class UserReview {

    /**
     * Stores the reviews.
     * @var array
     */
    private $reviews = [];

    /**
     * Adds a new review.
     *
     * @param int    $userId     The ID of the user submitting the review.
     * @param string $reviewText The content of the review.
     * @return bool  Returns true on success, false on failure (e.g., empty review).
     */
    public function addReview(int $userId, string $reviewText): bool
    {
        if (empty($reviewText)) {
            return false;
        }

        $this->reviews[] = [
            'user_id' => $userId,
            'review_text' => $reviewText,
            'timestamp' => time() // Add timestamp for ordering/filtering
        ];

        return true;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews(): array
    {
        return $this->reviews;
    }

    /**
     * Retrieves a review by its ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|null The review object if found, null if not found.
     */
    public function getReviewById(int $reviewId): ?array
    {
        foreach ($this->reviews as $review) {
            if ($review['id'] == $reviewId) {
                return $review;
            }
        }

        return null;
    }

    /**
     * Deletes a review by its ID.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True on success, false if the review does not exist.
     */
    public function deleteReview(int $reviewId): bool
    {
        $originalCount = count($this->reviews);
        $this->reviews = array_filter($this->reviews, function ($review) use ($reviewId) {
            return $review['id'] !== $reviewId;
        });
        return count($this->reviews) !== $originalCount;
    }

    /**
     * Generates unique IDs for new reviews.
     *
     * @return int Unique ID
     */
    private function generateUniqueId(): int
    {
        return rand(100000, 999999); // Example - generate 6-digit random number
    }
}

// Example Usage:
$reviewSystem = new UserReview();

// Add some reviews
$reviewSystem->addReview(123, "This product is amazing!");
$reviewSystem->addReview(456, "I would definitely recommend this.");
$reviewSystem->addReview(123, "Great value for money.");

// Get all reviews
$allReviews = $reviewSystem->getAllReviews();
print_r($allReviews);

// Get a specific review
$review = $reviewSystem->getReviewById(2);
if ($review) {
    echo "Review: " . $review['review_text'] . "
";
} else {
    echo "Review not found.
";
}

// Delete a review
$reviewSystem->deleteReview(1);

// Get all reviews after deletion
$allReviews = $reviewSystem->getAllReviews();
print_r($allReviews);

//Add a new review after deletion
$reviewSystem->addReview(789, "This is the best product ever!");
print_r($reviewSystem->getAllReviews());


?>
