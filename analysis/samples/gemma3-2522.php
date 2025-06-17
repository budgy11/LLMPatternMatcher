

<?php

/**
 * User Review Class
 *
 * This class provides functionality to store and display user reviews.
 */
class UserReview {

    private $reviews = [];

    /**
     * Adds a new review.
     *
     * @param string $username The username of the reviewer.
     * @param string $comment The review comment.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $username, string $comment) {
        if (empty($username) || empty($comment)) {
            return false;
        }

        $this->reviews[] = ['username' => $username, 'comment' => $comment];
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
     * Retrieves reviews by a specific username.
     *
     * @param string $username The username to filter reviews by.
     * @return array An array of review objects for the specified username.
     */
    public function getReviewsByUsername(string $username) {
        $reviews = [];
        foreach ($this->reviews as $review) {
            if ($review['username'] === $username) {
                $reviews[] = $review;
            }
        }
        return $reviews;
    }

    /**
     * Displays all reviews in a formatted way.
     *
     * @return void Displays reviews to the browser.
     */
    public function displayReviews() {
        echo "<h2>User Reviews</h2>";
        if (empty($this->reviews)) {
            echo "<p>No reviews yet.</p>";
            return;
        }

        echo "<ul>";
        foreach ($this->reviews as $review) {
            echo "<li><strong>" . htmlspecialchars($review['username']) . ":</strong> " . htmlspecialchars($review['comment']) . "</li>";
        }
        echo "</ul>";
    }

}

// Example Usage:

// Create a UserReview object
$reviewSystem = new UserReview();

// Add some reviews
$reviewSystem->addReview("John Doe", "Great product, highly recommended!");
$reviewSystem->addReview("Jane Smith", "Could be better, some issues with the interface.");
$reviewSystem->addReview("Peter Jones", "Excellent value for money.");

// Display all reviews
echo "<h2>All Reviews:</h2>";
$reviewSystem->displayReviews();

// Get reviews for a specific user
echo "<hr>";
echo "<h2>Reviews by John Doe:</h2>";
$johnReviews = $reviewSystem->getReviewsByUsername("John Doe");
if (!empty($johnReviews)) {
    foreach ($johnReviews as $review) {
        echo "<strong>" . htmlspecialchars($review['username']) . ":</strong> " . htmlspecialchars($review['comment']) . "<br>";
    }
} else {
    echo "<p>No reviews found for John Doe.</p>";
}

?>
