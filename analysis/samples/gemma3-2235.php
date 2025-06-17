

<?php

/**
 * Class UserReview
 *
 * Handles the creation, retrieval, and display of user reviews.
 */
class UserReview
{
    private $reviews = [];

    /**
     * Adds a new review to the database.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $username The username of the reviewer.
     * @param string $rating The rating given by the user (e.g., 1-5).
     * @param string $comment The user's review comment.
     *
     * @return bool True on success, false on failure (e.g., invalid rating).
     */
    public function addReview(int $productId, string $username, string $rating, string $comment): bool
    {
        // Validation: Check rating range
        if (!preg_match('/^[1-5]$/', $rating)) {
            return false;
        }

        // Create review object
        $review = [
            'productId' => $productId,
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Timestamp for date
        ];

        $this->reviews[] = $review;
        return true;
    }

    /**
     * Retrieves all reviews for a specific product.
     *
     * @param int $productId The ID of the product to retrieve reviews for.
     *
     * @return array An array of review objects for the product.  Returns an empty array if no reviews exist.
     */
    public function getReviewsByProduct(int $productId): array
    {
        $productReviews = [];
        foreach ($this->reviews as $review) {
            if ($review['productId'] === $productId) {
                $productReviews[] = $review;
            }
        }
        return $productReviews;
    }

    /**
     * Retrieves a single review by product ID and username.
     *
     * @param int $productId The ID of the product.
     * @param string $username The username of the reviewer.
     *
     * @return array|null An array containing the review object, or null if not found.
     */
    public function getReviewByProductAndUsername(int $productId, string $username): ?array
    {
        foreach ($this->reviews as $review) {
            if ($review['productId'] === $productId && $review['username'] === $username) {
                return [$review];
            }
        }
        return null;
    }

    /**
     * Calculates the average rating for a product.
     *
     * @param int $productId The ID of the product.
     *
     * @return float|null The average rating, or null if no reviews exist for the product.
     */
    public function getAverageRatingForProduct(int $productId): ?float
    {
        $reviews = $this->getReviewsByProduct($productId);
        if (empty($reviews)) {
            return null;
        }

        $totalRating = 0;
        foreach ($reviews as $review) {
            $totalRating += (float)$review['rating'];
        }

        return round($totalRating / count($reviews), 2); // Round to 2 decimal places
    }


    /**
     * Displays a list of reviews for a product (for demonstration purposes).
     *
     * @param array $reviews  The reviews to display.
     */
    public function displayReviews(array $reviews)
    {
        echo "<h2>Reviews for Product ID: " . $reviews[0]['productId'] . "</h2>";
        if (empty($reviews)) {
            echo "<p>No reviews found.</p>";
            return;
        }

        echo "<ul>";
        foreach ($reviews as $review) {
            echo "<li>";
            echo "<strong>Username:</strong> " . htmlspecialchars($review['username']) . "<br>";
            echo "<strong>Rating:</strong> " . $review['rating'] . " / 5<br>";
            echo "<strong>Comment:</strong> " . htmlspecialchars($review['comment']) . "<br>";
            echo "<strong>Date:</strong> " . $review['date'] . "<br>";
            echo "</li>";
        }
        echo "</ul>";
    }
}

// Example Usage:
$reviewSystem = new UserReview();

// Add some reviews
$reviewSystem->addReview(123, "JohnDoe", "5", "Great product! Highly recommended.");
$reviewSystem->addReview(123, "JaneSmith", "4", "Good product, but could be better.");
$reviewSystem->addReview(456, "PeterJones", "3", "It's okay.");

// Get reviews for product ID 123
$reviews = $reviewSystem->getReviewsByProduct(123);
$reviewSystem->displayReviews($reviews);

// Get average rating for product ID 123
$averageRating = $reviewSystem->getAverageRatingForProduct(123);
echo "<br>Average Rating: " . ($averageRating !== null ? $averageRating : "No reviews found.") . "<br>";

// Get a single review
$singleReview = $reviewSystem->getReviewByProductAndUsername(123, "JohnDoe");
if ($singleReview) {
    echo "<br>Single Review: ";
    $reviewSystem->displayReviews($singleReview);
} else {
    echo "<p>Review not found.</p>";
}
?>
