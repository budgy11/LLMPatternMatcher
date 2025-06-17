

<?php

/**
 * Class Review
 *
 * This class provides a simple way to manage user reviews.
 */
class Review {

    private $reviews = [];

    /**
     * Adds a new review to the collection.
     *
     * @param string $username The username of the reviewer.
     * @param string $comment The review comment.
     * @param int $rating The rating given by the reviewer (e.g., 1-5).
     *
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $username, string $comment, int $rating): bool {
        if (empty($username) || empty($comment) || $rating < 1 || $rating > 5) {
            return false; // Invalid input
        }

        $this->reviews[] = [
            'username' => $username,
            'comment' => $comment,
            'rating' => $rating
        ];

        return true;
    }


    /**
     * Retrieves all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews(): array {
        return $this->reviews;
    }


    /**
     * Retrieves reviews by a specific username.
     *
     * @param string $username The username to filter by.
     *
     * @return array An array of review objects for the given username.
     */
    public function getReviewsByUsername(string $username): array {
        $reviews = [];
        foreach ($this->getAllReviews() as $review) {
            if ($review['username'] === $username) {
                $reviews[] = $review;
            }
        }
        return $reviews;
    }

    /**
     * Retrieves the average rating.
     *
     * @return float|null The average rating, or null if no reviews have been added.
     */
    public function getAverageRating(): ?float {
        if (empty($this->reviews)) {
            return null;
        }

        $totalRating = 0;
        $reviewCount = count($this->reviews);

        foreach ($this->reviews as $review) {
            $totalRating += $review['rating'];
        }

        return $totalRating / $reviewCount;
    }

    /**
     * Clears all reviews.
     *
     * @return void
     */
    public function clearReviews(): void {
        $this->reviews = [];
    }
}

// Example Usage:

// Create a Review object
$reviewManager = new Review();

// Add some reviews
$reviewManager->addReview('JohnDoe', 'Great product!', 5);
$reviewManager->addReview('JaneSmith', 'Good value for money.', 4);
$reviewManager->addReview('JohnDoe', 'Could be better.', 3);

// Get all reviews
$allReviews = $reviewManager->getAllReviews();
print_r($allReviews);

// Get reviews by a specific user
$johnReviews = $reviewManager->getReviewsByUsername('JohnDoe');
print_r($johnReviews);

// Get the average rating
$averageRating = $reviewManager->getAverageRating();
echo "Average rating: " . ($averageRating !== null ? $averageRating : "No reviews yet.") . "
";

// Clear all reviews
$reviewManager->clearReviews();
print_r($reviewManager->getAllReviews());  // Should be empty.
?>
