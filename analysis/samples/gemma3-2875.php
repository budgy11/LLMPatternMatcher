

<?php

/**
 * User Review Function
 *
 * This function creates a simple user review system.  It allows users to
 * submit reviews, displays them, and provides basic functionality for
 * displaying average rating.
 */

class ReviewSystem
{
    private $reviews = []; // Store reviews - key is ID, value is review data

    /**
     * Adds a new review.
     *
     * @param int    $userId   The ID of the user submitting the review.
     * @param string $comment  The review comment.
     * @param int    $rating   The rating (1-5).
     * @return int|false The ID of the newly created review, or false on failure.
     */
    public function addReview(int $userId, string $comment, int $rating)
    {
        if ($rating < 1 || $rating > 5) {
            return false; // Invalid rating
        }

        $reviewId = count($this->reviews) + 1;
        $this->reviews[$reviewId] = [
            'user_id' => $userId,
            'comment' => $comment,
            'rating' => $rating,
            'date' => date('Y-m-d H:i:s') // Store timestamp for sorting/filtering
        ];

        return $reviewId;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of review data.
     */
    public function getAllReviews()
    {
        return $this->reviews;
    }

    /**
     * Retrieves a specific review by its ID.
     *
     * @param int $reviewId The ID of the review.
     * @return array|null Review data if found, null otherwise.
     */
    public function getReviewById(int $reviewId)
    {
        if (isset($this->reviews[$reviewId])) {
            return $this->reviews[$reviewId];
        }
        return null;
    }

    /**
     * Calculates the average rating.
     *
     * @return float|null The average rating, or null if no reviews exist.
     */
    public function getAverageRating()
    {
        $totalRating = 0;
        $reviewCount = count($this->reviews);

        if ($reviewCount > 0) {
            foreach ($this->reviews as $review) {
                $totalRating += $review['rating'];
            }
            return round($totalRating / $reviewCount, 2); // Round to 2 decimal places
        }
        return null;
    }

    /**
     * Displays all reviews.
     */
    public function displayReviews()
    {
        echo "<h2>All Reviews</h2>";
        if (count($this->reviews) == 0) {
            echo "<p>No reviews yet.</p>";
            return;
        }

        echo "<ul>";
        foreach ($this->reviews as $review) {
            echo "<li>";
            echo "<strong>User ID:</strong> " . $review['user_id'] . "<br>";
            echo "<strong>Comment:</strong> " . htmlspecialchars($review['comment']) . "<br>";  // Use htmlspecialchars for security
            echo "<strong>Rating:</strong> " . $review['rating'] . " / 5<br>";
            echo "<strong>Date:</strong> " . $review['date'] . "<br>";
            echo "</li>";
        }
        echo "</ul>";
    }
}


// Example Usage:
$reviewSystem = new ReviewSystem();

// Add some reviews
$reviewId1 = $reviewSystem->addReview(123, "Great product!", 5);
$reviewId2 = $reviewSystem->addReview(456, "Could be better", 3);
$reviewId3 = $reviewSystem->addReview(789, "Amazing!", 5);


// Display all reviews
echo "<h2>Reviews Displayed:</h2>";
$reviewSystem->displayReviews();

// Get average rating
$averageRating = $reviewSystem->getAverageRating();
echo "<br><h2>Average Rating:</h2>";
if ($averageRating !== null) {
    echo "<p>Average Rating: " . $averageRating . "</p>";
} else {
    echo "<p>No reviews to calculate average rating.</p>";
}


// Get a specific review
$review = $reviewSystem->getReviewById(2);
if ($review) {
    echo "<br><h2>Specific Review (ID 2):</h2>";
    echo "<p>User ID: " . $review['user_id'] . "</p>";
    echo "<p>Comment: " . htmlspecialchars($review['comment']) . "</p>";
    echo "<p>Rating: " . $review['rating'] . "</p>";
} else {
    echo "<p>Review not found.</p>";
}

?>
