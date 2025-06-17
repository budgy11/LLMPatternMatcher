

<?php

/**
 * User Review Class
 *
 * This class provides a simple way to store and manage user reviews.
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
     * @param int $reviewId (optional) - Unique identifier for the review.  If not provided, will be auto-generated.
     * @param int $userId The ID of the user submitting the review.
     * @param int $productId The ID of the product being reviewed.
     * @param int $rating The rating given (e.g., 1-5).
     * @param string $comment The user's review comment.
     */
    public function __construct($reviewId = null, $userId, $productId, $rating, $comment) {
        $this->reviewId = $reviewId;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Set current timestamp
    }

    /**
     * Getters
     *
     * @return int
     */
    public function getReviewId() {
        return $this->reviewId;
    }

    /**
     * Get User ID
     *
     * @return int
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * Get Product ID
     *
     * @return int
     */
    public function getProductId() {
        return $this->productId;
    }

    /**
     * Get Rating
     *
     * @return int
     */
    public function getRating() {
        return $this->rating;
    }

    /**
     * Get Comment
     *
     * @return string
     */
    public function getComment() {
        return $this->comment;
    }

    /**
     * Get Date
     *
     * @return string
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Display Review
     *
     * A simple method to display the review information.
     */
    public function displayReview() {
        echo "<p><strong>Review ID:</strong> " . $this->getReviewId() . "</p>";
        echo "<p><strong>User ID:</strong> " . $this->getUserId() . "</p>";
        echo "<p><strong>Product ID:</strong> " . $this->getProductId() . "</p>";
        echo "<p><strong>Rating:</strong> " . $this->getRating() . "</p>";
        echo "<p><strong>Comment:</strong> " . $this->getComment() . "</p>";
        echo "<p><strong>Date:</strong> " . $this->getDate() . "</p>";
    }
}



/**
 * User Review Review Function (Example Usage)
 */
function createReview($reviewId, $userId, $productId, $rating, $comment) {
    $review = new UserReview($reviewId, $userId, $productId, $rating, $comment);
    return $review;
}

function displayAllReviews($reviews) {
    echo "<h2>All Reviews:</h2>";
    foreach ($reviews as $review) {
        $review->displayReview();
        echo "<hr>";
    }
}



// Example Usage:
$review1 = createReview(1, 123, 456, 5, "Great product! Highly recommended.");
$review2 = createReview(2, 456, 456, 4, "Good value for money.");
$review3 = createReview(3, 789, 456, 3, "Could be better.");


$allReviews = [$review1, $review2, $review3];
displayAllReviews($allReviews);

?>
