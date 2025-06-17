

<?php

/**
 * User Review Class
 * 
 * This class allows users to submit and view reviews for a product or service.
 */
class UserReview {

    private $reviewId;
    private $productId; // The ID of the product being reviewed
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
     * @param int $userId The ID of the user submitting the review.
     * @param int $rating The rating given by the user (e.g., 1-5).
     * @param string $comment The user's review comment.
     */
    public function __construct($productId, $userId, $rating, $comment) {
        $this->productId = $productId;
        $this->userId = $userId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s');  // Set the review date and time
    }

    /**
     * Getters for the review attributes.
     *
     * @return mixed
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
     * Display the review in a formatted string.
     *
     * @return string The review displayed.
     */
    public function displayReview() {
        return "Review ID: " . $this->getReviewId() .
               "
Product ID: " . $this->getProductId() .
               "
User ID: " . $this->getUserId() .
               "
Rating: " . $this->getRating() .
               "
Comment: " . $this->getComment() .
               "
Date: " . $this->getDate();
    }
}

/**
 * User Review Function
 *
 * This function demonstrates how to create and display user reviews.
 */
function displayUserReviews() {

    // Example:  Let's create some reviews
    $review1 = new UserReview(123, 45, 5, "Great product!  Highly recommend.");
    $reviewId1 = $review1->getReviewId();
    $review1->displayReview();
    echo "
";

    $review2 = new UserReview(456, 78, 4, "Good value for the price.");
    $reviewId2 = $review2->getReviewId();
    $review2->displayReview();
    echo "
";

    $review3 = new UserReview(123, 90, 3, "Could be better, but decent.");
    $reviewId3 = $review3->getReviewId();
    $review3->displayReview();
    echo "
";

}

// Call the function to display the reviews
displayUserReviews();

?>
