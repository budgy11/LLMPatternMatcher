

<?php

/**
 * User Review Class
 *
 * Represents a user review and provides methods for adding, retrieving, and displaying reviews.
 */
class UserReview {

    private $reviews = [];

    /**
     * Adds a new user review.
     *
     * @param string $username The username of the reviewer.
     * @param string $rating The rating given (e.g., 1-5).
     * @param string $comment The user's comment.
     * @return bool True on success, false on failure (e.g., invalid rating).
     */
    public function addReview(string $username, string $rating, string $comment) {
        // Validate rating
        if (!preg_match('/^[1-5]+$/', $rating)) {
            return false;
        }

        // Create review object
        $review = [
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add a timestamp for when the review was created
        ];

        $this->reviews[] = $review;
        return true;
    }

    /**
     * Retrieves all user reviews.
     *
     * @return array An array of review objects.  Returns an empty array if no reviews exist.
     */
    public function getAllReviews() {
        return $this->reviews;
    }

    /**
     * Retrieves reviews for a specific item (placeholder - you'll need to adapt this)
     *
     * @param string $itemId The ID of the item the reviews are for.
     * @return array An array of review objects for the given item.  Returns an empty array if no reviews exist for the item.
     */
    public function getReviewsForItem(string $itemId) {
        // In a real application, you would likely query a database here.
        // This is just a placeholder.
        $reviews = [];
        foreach ($this->getAllReviews() as $review) {
            //Add a logic to filter reviews based on item.
            if ($review['item'] === $itemId) {
               $reviews[] = $review;
            }
        }
        return $reviews;
    }


    /**
     * Displays all reviews in a formatted string.
     *
     * @return string A formatted string of reviews.
     */
    public function displayReviews() {
        $output = "<h2>User Reviews</h2>";
        if (empty($this->getAllReviews())) {
            $output .= "<p>No reviews yet.</p>";
        } else {
            $output .= "<ul>";
            foreach ($this->getAllReviews() as $review) {
                $output .= "<li>";
                $output .= "<strong>Username:</strong> " . $review['username'] . "<br>";
                $output .= "<strong>Rating:</strong> " . $review['rating'] . "<br>";
                $output .= "<strong>Comment:</strong> " . $review['comment'] . "<br>";
                $output .= "<strong>Date:</strong> " . $review['date'] . "<br>";
                $output .= "</li>";
            }
            $output .= "</ul>";
        }
        return $output;
    }
}


// Example Usage:
$reviewSystem = new UserReview();

// Add some reviews
$reviewSystem->addReview("JohnDoe", 5, "Great product!  Highly recommended.");
$reviewSystem->addReview("JaneSmith", 4, "Good value for the price.");
$reviewSystem->addReview("PeterJones", 3, "It's okay, could be better.");

// Display all reviews
echo $reviewSystem->displayReviews();

// Get reviews for a specific item (example)
$itemReviews = $reviewSystem->getReviewsForItem("Product123");
echo "<hr><h2>Reviews for Product123</h2>";
echo $reviewSystem->displayReviewsForItem("Product123");
?>
