

<?php

/**
 * Class Review
 *
 * Handles user reviews, allowing users to submit and display reviews.
 */
class Review {

    private $reviews = [];

    /**
     * Adds a new review to the database.
     *
     * @param string $user_name The name of the user who submitted the review.
     * @param string $review_text The text of the review.
     */
    public function addReview(string $user_name, string $review_text) {
        $this->reviews[] = ['user' => $user_name, 'text' => $review_text];
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
     * Retrieves reviews based on a given criteria (e.g., maximum rating).
     *
     * @param int $rating The minimum rating to filter by (e.g., 4 or higher).
     * @return array An array of review objects that meet the criteria.
     */
    public function getReviewsByRating(int $rating) {
        $filteredReviews = [];
        foreach ($this->getAllReviews() as $review) {
            // You would typically have a rating field in your review objects.
            // This is just an example.  Replace with your actual rating field.
            if ($review['rating'] >= $rating) { //Example. Modify to use your actual rating field
                $filteredReviews[] = $review;
            }
        }
        return $filteredReviews;
    }

    /**
     * Displays all reviews in a formatted way.
     *
     * @return string The formatted review string.
     */
    public function displayReviews() {
        $output = "<h2>All Reviews:</h2>";
        $output .= "<ul>";
        foreach ($this->getAllReviews() as $review) {
            $output .= "<li><strong>User:</strong> " . htmlspecialchars($review['user']) . "<br>";
            $output .= "<strong>Review:</strong> " . htmlspecialchars($review['text']) . "</li>";
        }
        $output .= "</ul>";
        return $output;
    }
}


// Example Usage (Demonstration)
$reviewSystem = new Review();

// Add some reviews
$reviewSystem->addReview('John Doe', 'Great product! I highly recommend it.');
$reviewSystem->addReview('Jane Smith', 'The delivery was fast and the product was as described.');
$reviewSystem->addReview('Peter Jones', 'It was okay, but could be better.  The instructions were a bit confusing.');


// Display all reviews
echo $reviewSystem->displayReviews();

// Example: Display reviews with a rating of 4 or higher (assuming a rating field is added to review objects)
// To make this work, you'd need to modify your Review object to include a rating field.
// Example modification:
/*
class Review {
   // ... other properties ...
   private $rating;
}
*/

// Example adding rating to review objects
/*
$reviewSystem->addReview('John Doe', 'Great product! I highly recommend it.', 5);
$reviewsWithHighRating = $reviewSystem->getReviewsByRating(4);
echo "<br><h2>Reviews with rating 4 or higher:</h2>";
echo $reviewSystem->displayReviews($reviewsWithHighRating);
*/

?>
