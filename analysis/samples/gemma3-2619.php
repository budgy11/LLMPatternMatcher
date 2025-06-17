

<?php

/**
 * Reviews class to handle user reviews for a product or service.
 */
class Review {

    private $productId;
    private $userId;
    private $rating;
    private $comment;
    private $date;
    private $reviewId;  // Unique identifier for the review.

    /**
     * Constructor
     * 
     * @param int $productId The ID of the product or service being reviewed.
     * @param int $userId The ID of the user submitting the review.
     * @param int $rating The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment/feedback.
     */
    public function __construct(int $productId, int $userId, int $rating, string $comment) {
        $this->productId = $productId;
        $this->userId = $userId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Timestamp of the review.
        $this->reviewId = uniqid(); // Generate a unique ID for the review.
    }

    /**
     * Getters for each review attribute.
     * 
     * @return mixed
     */
    public function getProductId() {
        return $this->productId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getDate() {
        return $this->date;
    }

    public function getReviewId() {
        return $this->reviewId;
    }

    /**
     *  Display the review in a user-friendly format.
     *
     * @return string
     */
    public function displayReview() {
        return "Review ID: " . $this->reviewId . "
" .
               "Product ID: " . $this->productId . "
" .
               "User ID: " . $this->userId . "
" .
               "Rating: " . $this->rating . "
" .
               "Comment: " . $this->comment . "
" .
               "Date: " . $this->date;
    }
}

/**
 * Function to process user reviews (example usage)
 */
function processReview(Review $review) {
    echo "New Review Received:
";
    echo $review->displayReview() . "
";

    // In a real application, you'd likely save this review to a database.
    //  This is a placeholder for that functionality.
    echo "Review processed (placeholder - saving to database would happen here).
";
}


// --- Example Usage ---

// Create some reviews
$review1 = new Review(123, 45, 5, "Excellent product!  Highly recommended.");
$review2 = new Review(456, 78, 4, "Good value for the money.");
$review3 = new Review(123, 90, 3, "It's okay, but could be better.");

// Process the reviews
processReview($review1);
processReview($review2);
processReview($review3);

?>
