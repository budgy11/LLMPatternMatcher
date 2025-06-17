

<?php

/**
 * User Review Function
 *
 * This function allows you to collect and display user reviews.
 * It handles input validation, stores reviews (in this example, in an array),
 * and provides basic display functionality.
 */

class UserReview {

    private $reviews = [];

    /**
     * Add a new review.
     *
     * @param string $name The name of the user who wrote the review.
     * @param string $comment The review comment.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $name, string $comment) {
        // Input validation -  You can customize this as needed
        if (empty($name) || empty($comment)) {
            return false; // Require both name and comment
        }

        $this->reviews[] = [
            'name' => $name,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add a timestamp for review date
        ];

        return true;
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
     * Display all reviews.
     */
    public function displayReviews() {
        echo "<h2>User Reviews:</h2>";
        if (empty($this->getAllReviews())) {
            echo "<p>No reviews yet.</p>";
            return;
        }

        echo "<ul>";
        foreach ($this->getAllReviews() as $review) {
            echo "<li><strong>" . htmlspecialchars($review['name']) . ":</strong> " . htmlspecialchars($review['comment']) . " - " . htmlspecialchars($review['date']) . "</li>";
        }
        echo "</ul>";
    }
}

// --- Example Usage ---

// Create a new review object
$review = new UserReview();

// Add some reviews
$review->addReview("John Doe", "Great product!  Highly recommended.");
$review->addReview("Jane Smith", "Could be better.  Needs some improvements.");
$review->addReview("Peter Jones", "Excellent value for money.");


// Display the reviews
$review->displayReviews();

//  You can also access and process reviews programmatically
//  $allReviews = $review->getAllReviews();
//  foreach ($allReviews as $review) {
//    // Do something with the review data
//    echo "Review by: " . htmlspecialchars($review['name']) . "<br>";
//  }

?>
