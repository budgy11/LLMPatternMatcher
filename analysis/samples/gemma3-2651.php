

<?php

/**
 * User Review Class
 * 
 * Represents a user review for a product or item.
 */
class UserReview {
    private $userId;
    private $productId;
    private $rating;
    private $comment;
    private $date;

    /**
     * Constructor
     *
     * @param int $userId The ID of the user who wrote the review.
     * @param int $productId The ID of the product being reviewed.
     * @param int $rating The rating given by the user (e.g., 1-5).
     * @param string $comment The user's written review.
     */
    public function __construct($userId, $productId, $rating, $comment) {
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Set the date and time of the review
    }

    /**
     * Getters
     */
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
     * Display Review (for debugging/presentation)
     *
     * @return string  A formatted string representing the review.
     */
    public function displayReview() {
        return "User ID: " . $this->getUserId() .
               "
Product ID: " . $this->getProductId() .
               "
Rating: " . $this->getRating() .
               "
Comment: " . $this->getComment() .
               "
Date: " . $this->getDate();
    }
}

/**
 * User Review Function (Example Usage)
 */
function createAndDisplayReview($userId, $productId, $rating, $comment) {
    // Create a new review object
    $review = new UserReview($userId, $productId, $rating, $comment);

    // Display the review
    echo $review->displayReview() . "

";

    // You could also save this review to a database here.
}


// Example Usage:
createAndDisplayReview(123, 456, 5, "Great product!  Highly recommend.");
createAndDisplayReview(789, 101, 3, "It was okay, but could be better.");

?>
