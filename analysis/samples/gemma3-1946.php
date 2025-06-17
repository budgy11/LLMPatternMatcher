

<?php

/**
 * User Review Class
 *
 * This class provides functionality for managing and displaying user reviews.
 */
class UserReview {

    private $reviews = [];

    /**
     * Adds a new review.
     *
     * @param string $user  The name of the user who submitted the review.
     * @param string $comment The text of the review.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $user, string $comment) {
        if (empty($user) || empty($comment)) {
            return false; // Validation: User and comment must be provided.
        }
        $this->reviews[] = ['user' => $user, 'comment' => $comment];
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
     * Retrieves a specific review by ID.  (Simulated with index for simplicity)
     *
     * @param int $reviewId The ID of the review to retrieve.  (This is a simulated index,
     *                     should be managed with a proper database or array key)
     * @return array|null The review object if found, null otherwise.
     */
    public function getReviewById(int $reviewId) {
        if (isset($this->reviews[$reviewId])) {
            return $this->reviews[$reviewId];
        }
        return null;
    }

    /**
     * Deletes a review by ID.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True if the review was deleted successfully, false otherwise.
     */
    public function deleteReview(int $reviewId) {
        if (isset($this->reviews[$reviewId])) {
            unset($this->reviews[$reviewId]);
            return true;
        }
        return false;
    }

    /**
     * Calculates the average rating for a product (simulated).
     *
     * @param string $productName  The name of the product being reviewed.
     * @return float|null The average rating, or null if no reviews exist for the product.
     */
    public function calculateAverageRating(string $productName) {
        $ratings = [];
        foreach ($this->reviews as $review) {
            if ($review['comment'] === strtolower($productName)) {
                $ratings[] = $review['rating']; // Assuming a 'rating' field exists in the review
            }
        }

        if (empty($ratings)) {
            return null;
        }

        return array_sum($ratings) / count($ratings);
    }
}


// Example Usage:
$reviewManager = new UserReview();

// Add some reviews
$reviewManager->addReview('Alice', 'Great product, highly recommended!');
$reviewManager->addReview('Bob', 'It\'s okay, could be better.');
$reviewManager->addReview('Charlie', 'Excellent value for the money.');
$reviewManager->addReview('David', 'Poor quality, would not buy again.');

// Retrieve all reviews
$allReviews = $reviewManager->getAllReviews();
echo "All Reviews:
";
print_r($allReviews);

// Get a specific review
$specificReview = $reviewManager->getReviewById(1);
if ($specificReview) {
    echo "
Review ID 1:
";
    print_r($specificReview);
} else {
    echo "
Review not found.";
}

// Calculate average rating for a product
$averageRating = $reviewManager->calculateAverageRating('product name');
if ($averageRating !== null) {
    echo "
Average rating for 'product name': " . $averageRating . "
";
} else {
    echo "
No reviews found for 'product name'.
";
}

//Delete a review
$reviewManager->deleteReview(2);
echo "
After deleting review 2:
";
print_r($reviewManager->getAllReviews());
?>
