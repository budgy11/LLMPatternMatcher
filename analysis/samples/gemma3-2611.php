

<?php

/**
 * User Review Class
 *
 * This class provides functionality to store and retrieve user reviews.
 * It's a simplified example and can be extended with features like
 * rating systems, moderation, and more.
 */
class UserReview {

    private $reviews = [];

    /**
     * Adds a new review.
     *
     * @param string $username The username of the reviewer.
     * @param string $reviewText The review text.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview($username, $reviewText) {
        if (empty($username) || empty($reviewText)) {
            return false;
        }

        $this->reviews[] = [
            'username' => $username,
            'review' => $reviewText,
            'date' => date('Y-m-d H:i:s') // Add a timestamp for when the review was created
        ];
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
     * Retrieves reviews for a specific item (e.g., product, service).
     *  This is a placeholder - you'd likely store item IDs linked to reviews.
     *
     * @param int $itemId The ID of the item.
     * @return array An array of review objects for the item.
     */
    public function getReviewsForItem($itemId) {
        // In a real application, you'd likely query a database here
        // to get reviews associated with this item ID.
        $reviews = [];
        foreach ($this->getAllReviews() as $review) {
            // Example: Assume reviews are associated with item ID 1
            if ($review['itemId'] == 1) {
                $reviews[] = $review;
            }
        }
        return $reviews;
    }

    /**
     *  Adds a simple "helpful" count (can be extended for more sophisticated rating systems).
     *
     * @param int $itemId The ID of the item.
     * @return void
     */
    public function markAsHelpful($itemId) {
        //In a real implementation, you'd likely update a database record
        //to increment the 'helpful' count.
        //This is just a placeholder.
    }
}


/**
 * User Review Review Function (Example usage & testing)
 */
function reviewUserFunction($review_text, $user_name) {

    // 1. Create a Review object
    $review = new UserReview();

    // 2. Add the review
    $success = $review->addReview($user_name, $review_text);

    // 3. Handle the result
    if ($success) {
        echo "Review added successfully for user: " . $user_name . "
";
    } else {
        echo "Error adding review. Please ensure both username and review text are provided.
";
    }

    // 4. Example - Retrieving all reviews
    $all_reviews = $review->getAllReviews();
    echo "All reviews:
";
    foreach ($all_reviews as $review) {
        echo "- " . $review['username'] . ": " . $review['review'] . "
";
    }
}


// Example Usage:
$review_text = "This product is amazing!  I highly recommend it.";
$user_name = "JohnDoe";

reviewUserFunction($review_text, $user_name);

$review_text2 = "Not so great. Needs improvement.";
$user_name2 = "JaneSmith";

reviewUserFunction($review_text2, $user_name2);
?>
