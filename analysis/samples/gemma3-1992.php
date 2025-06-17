

<?php

/**
 * User Review Function
 *
 * This function creates a simple user review system, allowing users to submit reviews
 * and displaying them.
 */

class UserReviewSystem {

    private $reviews = [];

    /**
     * Adds a new review to the system.
     *
     * @param string $username The username of the reviewer.
     * @param string $reviewText The text of the review.
     * @return bool True on success, false on failure (e.g., invalid input).
     */
    public function addReview(string $username, string $reviewText): bool {
        if (empty($username) || empty($reviewText)) {
            return false; // Invalid input
        }

        $this->reviews[] = ['username' => $username, 'review' => $reviewText];
        return true;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews(): array {
        return $this->reviews;
    }

    /**
     * Displays all reviews in a formatted manner.
     */
    public function displayReviews(): void {
        echo "<h2>All Reviews:</h2>";
        if (empty($this->getAllReviews())) {
            echo "<p>No reviews yet.</p>";
        } else {
            foreach ($this->getAllReviews() as $review) {
                echo "<h3>" . htmlspecialchars($review['username']) . ":</h3>";
                echo "<p>" . htmlspecialchars($review['review']) . "</p>";
            }
        }
    }
}


// Example Usage:
$reviewSystem = new UserReviewSystem();

// Add some reviews
$reviewSystem->addReview('Alice', 'This product is amazing!  I highly recommend it.');
$reviewSystem->addReview('Bob', 'It was okay, but could be better.');
$reviewSystem->addReview('Charlie', 'Excellent value for money.');


// Display the reviews
$reviewSystem->displayReviews();


//  Demonstration of input validation
$reviewSystem->addReview("", "This is a test review");  // Will return false
echo "<br>";
echo "Last addition failed due to empty username.";
?>
