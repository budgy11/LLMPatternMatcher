

<?php

/**
 * User Review Class
 *
 * This class allows users to submit and view reviews for a product or service.
 */
class UserReview {

    /**
     * @var array Array of reviews.
     */
    private $reviews = [];

    /**
     * Add a new review.
     *
     * @param string $username The username of the reviewer.
     * @param string $rating  The rating given by the reviewer (e.g., 1-5).
     * @param string $comment The review comment.
     *
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $username, string $rating, string $comment) {
        if (empty($username) || empty($rating) || empty($comment)) {
            return false; // Invalid input
        }

        // Sanitize and validate rating (optional - adjust validation as needed)
        $rating = (int) $rating;
        if ($rating < 1 || $rating > 5) {
            return false; // Invalid rating
        }

        $this->reviews[] = [
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add a timestamp
        ];

        return true;
    }

    /**
     * Get all reviews.
     *
     * @return array Array of reviews.
     */
    public function getAllReviews() {
        return $this->reviews;
    }

    /**
     * Get reviews by username.
     *
     * @param string $username The username to filter by.
     * @return array Array of reviews by the specified username.
     */
    public function getReviewsByUsername(string $username) {
        $reviews = [];
        foreach ($this->getAllReviews() as $review) {
            if ($review['username'] === $username) {
                $reviews[] = $review;
            }
        }
        return $reviews;
    }

    /**
     * Get average rating.
     *
     * @return float|null The average rating if reviews exist, null otherwise.
     */
    public function getAverageRating() {
        if (empty($this->getAllReviews())) {
            return null;
        }

        $totalRating = 0;
        $reviewCount = count($this->getAllReviews());

        foreach ($this->getAllReviews() as $review) {
            $totalRating += $review['rating'];
        }

        return round($totalRating / $reviewCount, 2); // Round to 2 decimal places
    }
}

// Example Usage:
// Create a new review object
$reviewManager = new UserReview();

// Add some reviews
$reviewManager->addReview('JohnDoe', 5, 'Great product!  Highly recommended.');
$reviewManager->addReview('JaneSmith', 4, 'Good value for the money.');
$reviewManager->addReview('JohnDoe', 3, 'It\'s okay, but could be better.');

// Get all reviews
$allReviews = $reviewManager->getAllReviews();
print_r($allReviews);

// Get reviews by JohnDoe
$johnReviews = $reviewManager->getReviewsByUsername('JohnDoe');
print_r($johnReviews);

// Get average rating
$averageRating = $reviewManager->getAverageRating();
echo "Average Rating: " . ($averageRating === null ? "N/A" : $averageRating) . "
";
?>
