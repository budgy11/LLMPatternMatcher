

<?php

/**
 * Class Review
 *
 * This class provides functionality to manage user reviews for a product.
 */
class Review
{
    /**
     * @var array Reviews stored for a product.
     */
    private $reviews = [];

    /**
     * Adds a new review to the review list.
     *
     * @param string $user  The username of the reviewer.
     * @param string $rating The rating provided by the user (e.g., 1-5 stars).
     * @param string $comment The user's comment about the product.
     *
     * @return bool True on success, false on failure (e.g., invalid rating).
     */
    public function addReview(string $user, string $rating, string $comment)
    {
        // Validation (you can expand this with more validation)
        if (!preg_match('/^[a-zA-Z0-9 ]+$/', $user)) {
            return false; // Invalid username
        }
        if (!preg_match('/^[1-5]$/', $rating)) {
            return false; // Invalid rating
        }

        $this->reviews[] = [
            'user' => $user,
            'rating' => (int)$rating, // Convert rating to integer
            'comment' => $comment
        ];

        return true;
    }

    /**
     * Retrieves all reviews for a product.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews(): array
    {
        return $this->reviews;
    }

    /**
     * Retrieves a single review by user.
     *
     * @param string $user The username of the reviewer.
     * @return array|null An array representing the review, or null if not found.
     */
    public function getReviewByUser(string $user)
    {
        foreach ($this->reviews as $review) {
            if ($review['user'] === $user) {
                return $review;
            }
        }
        return null;
    }

    /**
     * Calculates the average rating of all reviews.
     *
     * @return float|null The average rating, or null if no reviews exist.
     */
    public function getAverageRating(): ?float
    {
        if (empty($this->reviews)) {
            return null;
        }

        $totalRating = 0;
        foreach ($this->reviews as $review) {
            $totalRating += $review['rating'];
        }

        return (float)($totalRating / count($this->reviews));
    }
}


// Example Usage:
$reviewManager = new Review();

// Add some reviews
$reviewManager->addReview('John Doe', 5, 'Excellent product, highly recommended!');
$reviewManager->addReview('Jane Smith', 4, 'Good product, works as expected.');
$reviewManager->addReview('Peter Jones', 3, 'Average product, could be better.');

// Get all reviews
$allReviews = $reviewManager->getAllReviews();
echo "All Reviews:
";
print_r($allReviews);

// Get a single review
$johnsReview = $reviewManager->getReviewByUser('John Doe');
echo "
John's Review:
";
print_r($johnsReview);

// Get the average rating
$averageRating = $reviewManager->getAverageRating();
echo "
Average Rating: " . ($averageRating !== null ? $averageRating : 'No reviews yet') . "
";
?>
