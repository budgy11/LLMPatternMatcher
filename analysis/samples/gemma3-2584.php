

<?php

/**
 * User Review Class
 *
 * Allows users to submit and view reviews.
 */
class UserReview
{
    private $reviews = [];

    /**
     * Adds a new review.
     *
     * @param string $name The name of the reviewer.
     * @param string $rating The rating (e.g., 1-5).
     * @param string $comment The review comment.
     *
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview($name, $rating, $comment)
    {
        if (!is_string($name) || !is_string($comment)) {
            return false; // Invalid input
        }

        if (!is_int($rating) || $rating < 1 || $rating > 5) {
            return false; // Invalid rating
        }

        $this->reviews[] = [
            'name' => $name,
            'rating' => $rating,
            'comment' => $comment,
        ];
        return true;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews()
    {
        return $this->reviews;
    }

    /**
     * Retrieves reviews based on a rating range.
     *
     * @param int $minRating The minimum rating (inclusive).
     * @param int $maxRating The maximum rating (inclusive).
     *
     * @return array An array of review objects within the specified rating range.
     */
    public function getReviewsByRange($minRating, $maxRating)
    {
        $filteredReviews = [];
        foreach ($this->getAllReviews() as $review) {
            if ($review['rating'] >= $minRating && $review['rating'] <= $maxRating) {
                $filteredReviews[] = $review;
            }
        }
        return $filteredReviews;
    }

    /**
     * Calculates the average rating.
     *
     * @return float|null The average rating, or null if no reviews have been submitted.
     */
    public function getAverageRating()
    {
        if (empty($this->getAllReviews())) {
            return null;
        }

        $totalRating = 0;
        foreach ($this->getAllReviews() as $review) {
            $totalRating += $review['rating'];
        }

        return $totalRating / count($this->getAllReviews());
    }

    /**
     * Clears all reviews.
     *
     * @return void
     */
    public function clearReviews()
    {
        $this->reviews = [];
    }
}

// Example Usage:
$reviewSystem = new UserReview();

// Add some reviews
$reviewSystem->addReview('Alice', 5, 'Great product!  Highly recommended.');
$reviewSystem->addReview('Bob', 4, 'Good, but could be better.');
$reviewSystem->addReview('Charlie', 3, 'Okay, nothing special.');
$reviewSystem->addReview('David', 5, 'Excellent value for money!');

// Get all reviews
$allReviews = $reviewSystem->getAllReviews();
print_r($allReviews);

// Get reviews with a rating of 4 or 5
$highRatedReviews = $reviewSystem->getReviewsByRange(4, 5);
print_r($highRatedReviews);

// Get the average rating
$averageRating = $reviewSystem->getAverageRating();
echo "Average Rating: " . ($averageRating !== null ? $averageRating : 'No reviews yet') . "
";

// Clear the reviews
$reviewSystem->clearReviews();
print_r($reviewSystem->getAllReviews());  // Should be empty
?>
