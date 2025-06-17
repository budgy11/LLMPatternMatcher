

<?php

/**
 * Function to add, display, and manage user reviews.
 *
 * This function provides a basic framework for handling user reviews.
 * It includes functions for:
 *   - Adding a new review.
 *   - Displaying all reviews.
 *   - Displaying a single review.
 *   - Potentially adding more sophisticated features like rating, etc.
 *
 * NOTE: This is a simplified example and does not include database integration.
 *       You'll need to adapt it to your specific database and application needs.
 */

class ReviewManager {

    private $reviews = [];
    private $reviewIdCounter = 1; // For generating unique IDs

    /**
     * Adds a new review to the system.
     *
     * @param string $reviewerName The name of the reviewer.
     * @param string $reviewText The content of the review.
     *
     * @return int|false The ID of the newly created review, or false on failure.
     */
    public function addReview($reviewerName, $reviewText) {
        $reviewId = $this->reviewIdCounter++;
        $review = [
            'id' => $reviewId,
            'reviewerName' => $reviewerName,
            'reviewText' => $reviewText,
            'dateCreated' => date('Y-m-d H:i:s') // Add timestamp for tracking
        ];

        $this->reviews[] = $review;
        return $reviewId;
    }

    /**
     * Displays all reviews.
     */
    public function displayAllReviews() {
        echo "<h2>All Reviews</h2>";
        if (empty($this->reviews)) {
            echo "<p>No reviews yet.</p>";
        } else {
            foreach ($this->reviews as $review) {
                echo "<h3>Review ID: " . $review['id'] . "</h3>";
                echo "<p><strong>Reviewer:</strong> " . $review['reviewerName'] . "</p>";
                echo "<p>" . $review['reviewText'] . "</p>";
                echo "<p><strong>Date Created:</strong> " . $review['dateCreated'] . "</p>";
                echo "<hr>";
            }
        }
    }

    /**
     * Displays a single review by ID.
     *
     * @param int $reviewId The ID of the review to display.
     *
     * @return bool True if the review was found and displayed, false otherwise.
     */
    public function displayReviewById($reviewId) {
        foreach ($this->reviews as $review) {
            if ($review['id'] == $reviewId) {
                echo "<h2>Review ID: " . $review['id'] . "</h2>";
                echo "<p><strong>Reviewer:</strong> " . $review['reviewerName'] . "</p>";
                echo "<p>" . $review['reviewText'] . "</p>";
                echo "<p><strong>Date Created:</strong> " . $review['dateCreated'] . "</p>";
                return true;
            }
        }
        return false;
    }
}

// Example Usage:

// Create a ReviewManager instance
$reviewManager = new ReviewManager();

// Add some reviews
$reviewId1 = $reviewManager->addReview("John Doe", "This product is amazing!");
$reviewId2 = $reviewManager->addReview("Jane Smith", "I'm a bit disappointed with the shipping time.");

// Display all reviews
echo "<h2>Reviews</h2>";
$reviewManager->displayAllReviews();

// Display a specific review
echo "<hr>";
$reviewManager->displayReviewById($reviewId1);
$reviewManager->displayReviewById($reviewId2);
?>
