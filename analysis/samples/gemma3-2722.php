

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
    private $reviewDate;

    /**
     * Constructor
     *
     * @param int $reviewId (Optional, auto-generated)
     * @param int $userId
     * @param int $productId
     * @param int $rating (1-5)
     * @param string $comment
     */
    public function __construct(
        int $reviewId = null,
        int $userId,
        int $productId,
        int $rating,
        string $comment
    ) {
        $this->reviewId = $reviewId;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->reviewDate = date('Y-m-d H:i:s'); // Set current timestamp
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

    public function getReviewDate() {
        return $this->reviewDate;
    }

    /**
     * Setters (If needed - use with caution)
     */
    public function setRating(int $rating) {
        $this->rating = $rating;
    }


    /**
     *  Basic display function - useful for testing or simple output
     */
    public function displayReview() {
        echo "Review ID: " . $this->getReviewId() . "<br>";
        echo "User ID: " . $this->getUserId() . "<br>";
        echo "Product ID: " . $this->getProductId() . "<br>";
        echo "Rating: " . $this->getRating() . "<br>";
        echo "Comment: " . $this->getComment() . "<br>";
        echo "Review Date: " . $this->getReviewDate() . "<br>";
    }

}



/**
 * User Review Review Function (Simplified Example)
 */
function reviewProduct(UserReview $review) {
    // Basic validation (add more robust validation in a real application)
    if ($review->getRating() < 1 || $review->getRating() > 5) {
        echo "Invalid rating. Rating must be between 1 and 5.";
        return;
    }

    //  Add the review to a database (replace with your database logic)
    echo "Review submitted successfully for Product ID: " . $review->getProductId() . "<br>";
    echo "User: " . $review->getUserId() . "<br>";
    echo "Rating: " . $review->getRating() . "<br>";
    echo "Comment: " . $review->getComment() . "<br>";
}



// Example Usage:
$review1 = new UserReview(null, 123, 456, 5, "Great product! I love it.");
reviewProduct($review1);

$review2 = new UserReview(null, 456, 789, 3, "It was okay.");
reviewProduct($review2);

$review3 = new UserReview(1, 789, 101, 1, "Terrible!");  //Testing with an ID
reviewProduct($review3);

?>
