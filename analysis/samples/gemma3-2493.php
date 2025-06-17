

<?php

/**
 * User Review Class
 *
 * This class allows you to store and manage user reviews for a product or item.
 * It includes methods for adding, retrieving, and displaying reviews.
 */
class UserReview {

    private $reviews = [];

    /**
     * Add a new review.
     *
     * @param string $username The username of the reviewer.
     * @param string $rating The rating given by the user (e.g., 1-5).
     * @param string $comment The review text.
     *
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview($username, $rating, $comment) {
        if (!empty($username) && is_numeric($rating) && !empty($comment)) {
            $this->reviews[] = [
                'username' => $username,
                'rating' => (int)$rating, // Cast to integer for consistent data type
                'comment' => $comment
            ];
            return true;
        }
        return false;
    }

    /**
     * Get all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews() {
        return $this->reviews;
    }

    /**
     * Get reviews by a specific user.
     *
     * @param string $username The username of the user.
     * @return array An array of reviews by the user, or an empty array if no reviews by that user exist.
     */
    public function getReviewsByUser($username) {
        $userReviews = [];
        foreach ($this->getAllReviews() as $review) {
            if ($review['username'] === $username) {
                $userReviews[] = $review;
            }
        }
        return $userReviews;
    }

    /**
     * Get average rating.
     *
     * @return float|null The average rating, or null if no reviews exist.
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

        return round(($totalRating / $reviewCount), 2); // Round to 2 decimal places.
    }

    /**
     * Display reviews formatted for output (e.g., HTML).
     * This is just an example; you can customize the output format.
     *
     * @return string HTML markup for displaying reviews.
     */
    public function displayReviews() {
        $reviews = $this->getAllReviews();
        if (empty($reviews)) {
            return '<p>No reviews yet.</p>';
        }

        $output = '<ul>';
        foreach ($reviews as $review) {
            $output .= '<li><strong>' . $review['username'] . ':</strong> ' .
                       $review['rating'] . ' - ' . $review['comment'] . '</li>';
        }
        $output .= '</ul>';
        return $output;
    }

}

// Example Usage:
$reviewSystem = new UserReview();

// Add some reviews
$reviewSystem->addReview('JohnDoe', 5, 'Great product!  I highly recommend it.');
$reviewSystem->addReview('JaneSmith', 4, 'Good value for money.');
$reviewSystem->addReview('JohnDoe', 3, 'It\'s okay, could be better.');
$reviewSystem->addReview('PeterPan', 5, 'Absolutely fantastic!');

// Get all reviews
$allReviews = $reviewSystem->getAllReviews();
echo "<h2>All Reviews:</h2>
";
echo $reviewSystem->displayReviews();

// Get reviews by JohnDoe
$johnReviews = $reviewSystem->getReviewsByUser('JohnDoe');
echo "<h2>JohnDoe Reviews:</h2>
";
echo $reviewSystem->displayReviews($johnReviews);

// Get average rating
$averageRating = $reviewSystem->getAverageRating();
echo "<h2>Average Rating:</h2> " . ($averageRating === null ? "No reviews" : $averageRating);

?>
