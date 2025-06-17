

<?php

/**
 * User Review Class
 */
class UserReview {

    private $reviewId;
    private $userId;
    private $productId;
    private $rating;
    private $comment;
    private $date;

    /**
     * Constructor
     *
     * @param int $reviewId (Optional, auto-generated)
     * @param int $userId
     * @param int $productId
     * @param int $rating (1-5)
     * @param string $comment
     */
    public function __construct($reviewId = null, $userId, $productId, $rating, $comment) {
        $this->reviewId = $reviewId;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Timestamp of when the review was created
    }

    /**
     * Getters
     */
    public function getReviewId() {
        return $this->reviewId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getProductId() {
        return $this->productId;
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

    /**
     *  Setters (optional - for update functionality)
     */
    public function setComment($comment) {
        $this->comment = $comment;
    }
}

/**
 * User Review Function (Demonstration)
 */
function displayUserReviews($reviews) {
    echo "<h2>User Reviews</h2>";
    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li>";
        echo "<strong>User ID:</strong> " . $review->getUserId() . "<br>";
        echo "<strong>Product ID:</strong> " . $review->getProductId() . "<br>";
        echo "<strong>Rating:</strong> " . $review->getRating() . " / 5<br>";
        echo "<strong>Comment:</strong> " . $review->getComment() . "<br>";
        echo "<strong>Date:</strong> " . $review->getDate() . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}


// Example Usage:

// Create some review objects
$review1 = new UserReview(null, 123, 456, 5, "Great product! Highly recommended.");
$review2 = new UserReview(null, 789, 456, 4, "Good product, but a little pricey.");
$review3 = new UserReview(null, 123, 789, 3, "Okay product.");


// Store the reviews in an array
$reviews = array($review1, $review2, $review3);

// Display the reviews
displayUserReviews($reviews);


?>
