

<?php

/**
 * Class UserReview
 *
 * This class allows users to submit and view reviews for a product or service.
 */
class UserReview
{
    private $reviews = [];

    /**
     * Adds a new review to the system.
     *
     * @param string $userId    The ID of the user submitting the review.
     * @param string $rating    The rating given by the user (e.g., 1-5).
     * @param string $comment  The user's comment about the product/service.
     *
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $userId, string $rating, string $comment)
    {
        // Validation -  Add more validation as needed (e.g., numeric rating check, comment length limits)
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        $review = [
            'userId' => $userId,
            'rating' => $rating,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Timestamp of when the review was added
        ];

        $this->reviews[] = $review;
        return true;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of all reviews.
     */
    public function getAllReviews()
    {
        return $this->reviews;
    }

    /**
     * Retrieves reviews by user ID.
     *
     * @param string $userId The user ID to filter by.
     *
     * @return array An array of reviews for the specified user.
     */
    public function getReviewsByUserId(string $userId)
    {
        $userReviews = [];
        foreach ($this->reviews as $review) {
            if ($review['userId'] === $userId) {
                $userReviews[] = $review;
            }
        }
        return $userReviews;
    }

    /**
     * Calculates the average rating for a product/service.
     *
     * @return float|null The average rating, or null if no reviews are available.
     */
    public function calculateAverageRating()
    {
        if (empty($this->reviews)) {
            return null;
        }

        $totalRating = 0;
        foreach ($this->reviews as $review) {
            $totalRating += floatval($review['rating']);
        }
        return round($totalRating / count($this->reviews), 2); // Round to 2 decimal places
    }

    /**
     * Clears all reviews.
     */
    public function clearReviews()
    {
        $this->reviews = [];
    }
}

// --- Example Usage ---
// Create a UserReview object
$reviewSystem = new UserReview();

// Add some reviews
$reviewSystem->addReview('user123', 4, 'Great product!  Easy to use.');
$reviewSystem->addReview('user456', 5, 'Excellent service and fast delivery.');
$reviewSystem->addReview('user123', 3, 'It was okay, nothing special.');

// Get all reviews
$allReviews = $reviewSystem->getAllReviews();
echo "All Reviews:
";
print_r($allReviews);

// Get reviews for user123
$userReviews = $reviewSystem->getReviewsByUserId('user123');
echo "
Reviews for user123:
";
print_r($userReviews);

// Calculate the average rating
$averageRating = $reviewSystem->calculateAverageRating();
echo "
Average Rating: " . ($averageRating !== null ? $averageRating : 'No reviews available') . "
";

// Clear the reviews
$reviewSystem->clearReviews();
echo "
Reviews cleared.
";

// Check if reviews are empty
$allReviews = $reviewSystem->getAllReviews();
echo "Reviews after clearing: " . count($allReviews) . "
";
?>
