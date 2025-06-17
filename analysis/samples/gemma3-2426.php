

<?php

/**
 * User Review Class
 *
 * This class provides functionality for creating, storing, and displaying user reviews.
 */
class UserReview {

    private $reviewId;
    private $userId;
    private $productId; // Assuming a product ID is associated with the review
    private $rating;
    private $comment;
    private $createdAt;

    /**
     * Constructor
     *
     * Initializes a new UserReview object.
     *
     * @param int $userId  The ID of the user submitting the review.
     * @param int $productId The ID of the product being reviewed.
     * @param int $rating   The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment about the product.
     */
    public function __construct($userId, $productId, $rating, $comment) {
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->createdAt = date('Y-m-d H:i:s'); // Timestamp of review creation
    }

    /**
     * Getters and Setters (for data access)
     *
     * Public methods to access and modify the review's properties.
     */
    public function getReviewId() {
        return $this->reviewId;
    }

    public function setReviewId($reviewId) {
        $this->reviewId = $reviewId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function setProductId($productId) {
        $this->productId = $productId;
    }

    public function getRating() {
        return $this->rating;
    }

    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Display the review in a user-friendly format.
     *
     * @return string The review as a formatted string.
     */
    public function displayReview() {
        return "Review ID: " . $this->getReviewId() .
               "
User ID: " . $this->getUserId() .
               "
Product ID: " . $this->getProductId() .
               "
Rating: " . $this->getRating() .
               "
Comment: " . $this->getComment() .
               "
Created At: " . $this->getCreatedAt();
    }
}


/**
 * User Review Function Example
 */

// Create a new review object
$review = new UserReview(123, 456, 5, "Great product!  I highly recommend it.");

// Display the review
echo $review->displayReview() . "
";

// Demonstrating setting properties after creation
$review->setRating(4);
echo "
Updated Review:
";
echo $review->displayReview() . "
";

// ---  Simulated Database Interaction (Replace with your actual database code) ---
// In a real application, you would save this to a database.
// For example:
// $db = new DatabaseConnection();
// $db->insertReview($review);

// --- End of Simulated Database Interaction ---
?>
