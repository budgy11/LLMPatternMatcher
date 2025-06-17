

<?php

/**
 * User Review Function
 *
 * This function creates a basic user review system that allows users to submit reviews
 * and displays them in a simple format.
 *
 *  Note:  This is a simplified example and doesn't include robust security measures, 
 *        database integration, or complex features.  For a production environment, 
 *        you would need to add proper database connections, security checks (like 
 *        input validation and sanitization), and potentially more sophisticated 
 *        review management capabilities.
 */

class ReviewSystem {

    private $reviews = [];

    /**
     * Adds a new review to the system.
     *
     * @param string $user_name  The name of the user submitting the review.
     * @param string $review_text The text of the review.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $user_name, string $review_text) {
        // Basic validation - you'd want more robust validation in a real application.
        if (empty($user_name) || empty($review_text)) {
            return false;
        }

        $this->reviews[] = ['user' => $user_name, 'text' => $review_text];
        return true;
    }

    /**
     * Displays all reviews.
     *
     * @return void
     */
    public function displayReviews() {
        echo "<h2>User Reviews</h2>";
        if (empty($this->reviews)) {
            echo "<p>No reviews yet.</p>";
        } else {
            foreach ($this->reviews as $review) {
                echo "<h3>" . $review['user'] . "</h3>";
                echo "<p>" . $review['text'] . "</p>";
                echo "<hr>";
            }
        }
    }

    /**
     *  (Optional)  Could add methods for editing or deleting reviews here.
     */
}


// Example Usage:

// Create a review system instance
$reviewSystem = new ReviewSystem();

// Add some reviews
$reviewSystem->addReview("John Doe", "This was a great product! I highly recommend it.");
$reviewSystem->addReview("Jane Smith", "The service was excellent and the staff were friendly.");
$reviewSystem->addReview("Peter Jones", "It was okay, but a little overpriced.");

// Display the reviews
$reviewSystem->displayReviews();


// Example of handling invalid input:
$reviewSystem->addReview("", "This is a review."); // Will return false because user_name is empty
echo "<br>Adding review failed (empty username).<br>";

?>
