
</body>
</html>


<?php

/**
 * This class provides functionality for managing user reviews.
 */
class ReviewManager {

    private $reviews = [];

    /**
     * Adds a new review to the system.
     *
     * @param string $user_id The ID of the user submitting the review.
     * @param string $title The title of the review.
     * @param string $comment The content of the review.
     * @param int $rating  The rating given (e.g., 1-5).
     *
     * @return bool True on success, false on failure (e.g., invalid rating).
     */
    public function addReview(string $user_id, string $title, string $comment, int $rating) {
        // Validation (basic - you might want more robust validation)
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        $review = [
            'user_id' => $user_id,
            'title'   => $title,
            'comment' => $comment,
            'rating'  => $rating,
            'date'    => date('Y-m-d H:i:s') // Add timestamp for organization
        ];

        $this->reviews[] = $review;
        return true;
    }


    /**
     * Retrieves all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews() {
        return $this->reviews;
    }

    /**
     * Retrieves reviews by user ID.
     *
     * @param string $user_id The ID of the user to filter by.
     * @return array An array of reviews for the specified user.
     */
    public function getReviewsByUser(string $user_id) {
        $userReviews = [];
        foreach ($this->reviews as $review) {
            if ($review['user_id'] === $user_id) {
                $userReviews[] = $review;
            }
        }
        return $userReviews;
    }


    /**
     * Retrieves reviews based on a given rating.
     *
     * @param int $rating The rating to filter by.
     * @return array An array of reviews with the specified rating.
     */
    public function getReviewsByRating(int $rating) {
        $filteredReviews = [];
        foreach ($this->reviews as $review) {
            if ($review['rating'] === $rating) {
                $filteredReviews[] = $review;
            }
        }
        return $filteredReviews;
    }
}



/**
 *  Example User Review Function  (Illustrative - This is not a full review system)
 */
function displayUserReviews($reviewManager, $userId) {
    $reviews = $reviewManager->getReviewsByUser($userId);

    if (empty($reviews)) {
        echo "<p>No reviews found for user: " . htmlspecialchars($userId) . "</p>"; // Use htmlspecialchars for security
        return;
    }

    echo "<h2>Reviews for User " . htmlspecialchars($userId) . "</h2>";
    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li>";
        echo "<strong>Title:</strong> " . htmlspecialchars($review['title']) . "<br>";
        echo "<strong>Rating:</strong> " . $review['rating'] . "<br>";
        echo "<strong>Comment:</strong> " . htmlspecialchars($review['comment']) . "<br>";
        echo "<strong>Date:</strong> " . htmlspecialchars($review['date']) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}


// --- Example Usage ---

// Create a ReviewManager instance
$reviewManager = new ReviewManager();

// Add some reviews
$reviewManager->addReview('user123', 'Great Product!', 'Highly recommended!', 5);
$reviewManager->addReview('user123', 'Good Value', 'Worth the price.', 4);
$reviewManager->addReview('user456', 'Disappointed', 'Didn\'t meet expectations.', 2);

// Display reviews for user123
displayUserReviews($reviewManager, 'user123');

// Get all reviews
$allReviews = $reviewManager->getAllReviews();
echo "<br><br><h2>All Reviews:</h2>";
echo "<pre>";
print_r($allReviews);
echo "</pre>";


// Get reviews with a rating of 5
$fiveStarReviews = $reviewManager->getReviewsByRating(5);
echo "<br><br><h2>Reviews with Rating 5:</h2>";
print_r($fiveStarReviews);
?>
