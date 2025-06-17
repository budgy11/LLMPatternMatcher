

<?php

/**
 * User Review Class - Handles user reviews for a product or item.
 */
class UserReview {

    private $reviewId;
    private $productId;
    private $userId;
    private $rating;
    private $comment;
    private $date;

    /**
     * Constructor for the UserReview class.
     *
     * @param int $reviewId (optional, auto-generated)
     * @param int $productId
     * @param int $userId
     * @param int $rating (1-5)
     * @param string $comment
     */
    public function __construct($reviewId = null, $productId, $userId, $rating, $comment) {
        $this->reviewId = $reviewId;
        $this->productId = $productId;
        $this->userId = $userId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Get current timestamp
    }

    /**
     * Getters and Setters for the properties.
     */

    public function getReviewId() {
        return $this->reviewId;
    }

    public function setReviewId($reviewId) {
        $this->reviewId = $reviewId;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function setProductId($productId) {
        $this->productId = $productId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getRating() {
        return $this->rating;
    }

    public function setRating($rating) {
        if ($rating >= 1 && $rating <= 5) {
            $this->rating = $rating;
        } else {
            // Handle invalid rating (e.g., throw an exception or set to default)
            throw new InvalidArgumentException("Rating must be between 1 and 5.");
        }
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function getDate() {
        return $this->date;
    }

    /**
     *  Method to display the review information (for review display)
     *
     * @return string
     */
    public function displayReview() {
        return "Review ID: " . $this->getReviewId() . "
" .
               "Product ID: " . $this->getProductId() . "
" .
               "User ID: " . $this->getUserId() . "
" .
               "Rating: " . $this->getRating() . "
" .
               "Comment: " . $this->getComment() . "
" .
               "Date: " . $this->getDate();
    }
}


/**
 * User Review Review Function (Simplified Review System)
 */
function displayReviewFunction($reviews) {
    echo "<br><h2>User Reviews</h2>";

    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
        return;
    }

    foreach ($reviews as $review) {
        echo "<br><h3>Review by User ID: " . $review->getUserId() . "</h3>";
        echo $review->displayReview();
        echo "<br>";
    }
}

// Example Usage:

// Create some review objects
$review1 = new UserReview(null, 123, 1, 5, "Great product! Highly recommended.");
$review2 = new UserReview(null, 456, 2, 4, "Good value for money.");
$review3 = new UserReview(null, 123, 3, 3, "It's okay.");


// Store the reviews in an array
$reviews = [$review1, $review2, $review3];


// Call the displayReviewFunction
displayReviewFunction($reviews);

?>
