

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
     * @param int $reviewId
     * @param int $userId
     * @param int $productId
     * @param int $rating
     * @param string $comment
     */
    public function __construct($reviewId = null, $userId = null, $productId = null, $rating = null, $comment = null) {

        $this->reviewId = $reviewId;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Default to current timestamp
    }

    /**
     * Getters
     *
     * @return mixed
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
     * Setters
     *
     * @param mixed $value
     * @return void
     */
    public function setReviewId($reviewId) {
        $this->reviewId = $reviewId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setProductId($productId) {
        $this->productId = $productId;
    }

    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function setDate($date) {
        $this->date = $date;
    }
}


/**
 * User Review Review Function (with validation)
 */
function reviewUser($reviewId = null, $userId = null, $productId = null, $rating = null, $comment = null) {

    // Input Validation - Crucial for security and data integrity
    if ($reviewId !== null) {
        if (!is_numeric($reviewId)) {
            return "Error: Review ID must be a number.";
        }
    }
    if ($userId !== null) {
        if (!is_numeric($userId)) {
            return "Error: User ID must be a number.";
        }
    }
    if ($productId !== null) {
        if (!is_numeric($productId)) {
            return "Error: Product ID must be a number.";
        }
    }
    if ($rating !== null) {
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) { // Validate rating range
            return "Error: Rating must be a number between 1 and 5.";
        }
    }
    if ($comment !== null) {
        if (empty($comment)) {
            return "Error: Comment cannot be empty.";
        }
    }

    // Create a new Review object
    $review = new UserReview($reviewId, $userId, $productId, $rating, $comment);

    // Simulate saving to a database (Replace this with your database logic)
    // For demonstration purposes, we'll just return a success message.
    return "Review submitted successfully. Review ID: " . $review->getReviewId();
}

// Example Usage
echo reviewUser(123, 45, 67, 4, "This product is great!");
echo "
";
echo reviewUser(null, 78, 90, 5, "Excellent!");
echo "
";
echo reviewUser(null, null, null, 3, "Okay, but could be better."); // No parameters, no validation
echo "
";
echo reviewUser(null, 10, 20, 6, ""); // Invalid rating (empty comment)
echo "
";

?>
