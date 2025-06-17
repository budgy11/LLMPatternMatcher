

<?php

/**
 * User Review Class
 *
 * This class allows you to store and manage user reviews for a product or item.
 */
class UserReview {

    private $reviewId;
    private $productId;
    private $userId;
    private $rating;
    private $comment;
    private $date;

    /**
     * Constructor
     *
     * Initializes a new UserReview object.
     *
     * @param int $productId The ID of the product being reviewed.
     * @param int $userId    The ID of the user leaving the review.
     * @param int $rating    The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment about the product.
     */
    public function __construct($productId, $userId, $rating, $comment) {
        $this->productId = $productId;
        $this->userId = $userId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Current timestamp
    }

    /**
     * Getters
     */
    public function getReviewId() {
        return $this->reviewId;
    }

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

    /**
     *  Example method to display review info
     */
    public function displayReview() {
        echo "Review ID: " . $this->getReviewId() . "<br>";
        echo "Product ID: " . $this->getProductId() . "<br>";
        echo "User ID: " . $this->getUserId() . "<br>";
        echo "Rating: " . $this->getRating() . "<br>";
        echo "Comment: " . $this->getComment() . "<br>";
        echo "Date: " . $this->getDate() . "<br>";
    }
}


/**
 * User Review Review Function (Simple Example)
 */
function displayUserReviews($reviews) {
    echo "<h2>User Reviews</h2>";

    foreach ($reviews as $review) {
        echo "<div class='review'>";
        $review->displayReview(); // Display the review information
        echo "</div><hr>";
    }
}



// Example Usage:

// Create some sample reviews
$review1 = new UserReview(123, 456, 5, "Great product!  Highly recommend.");
$review2 = new UserReview(123, 789, 4, "Good value for the price.");
$review3 = new UserReview(456, 101, 3, "It was okay, but could be better.");

// Create an array of reviews
$reviews = array($review1, $review2, $review3);

// Display the reviews
displayUserReviews($reviews);

?>
