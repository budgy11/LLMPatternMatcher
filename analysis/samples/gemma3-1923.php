

<?php

/**
 * User Review Class
 */
class UserReview {

    private $review_id;
    private $user_id;
    private $product_id;
    private $rating;
    private $comment;
    private $date;

    /**
     * Constructor
     *
     * @param int $review_id (Optional) - Unique ID for the review. If not provided, auto-generated.
     * @param int $user_id   The ID of the user who wrote the review.
     * @param int $product_id The ID of the product being reviewed.
     * @param int $rating     The rating (e.g., 1-5).
     * @param string $comment The review comment.
     */
    public function __construct($review_id = null, $user_id, $product_id, $rating, $comment) {
        $this->review_id = $review_id;
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Set the current date and time
    }

    /**
     * Getters
     */
    public function getReviewId() {
        return $this->review_id;
    }

    public function getUserID() {
        return $this->user_id;
    }

    public function getProductID() {
        return $this->product_id;
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
     * Display Review (for demonstration)
     */
    public function displayReview() {
        echo "<p><strong>Review ID:</strong> " . $this->getReviewId() . "</p>";
        echo "<p><strong>User ID:</strong> " . $this->getUserID() . "</p>";
        echo "<p><strong>Product ID:</strong> " . $this->getProductID() . "</p>";
        echo "<p><strong>Rating:</strong> " . $this->getRating() . "</p>";
        echo "<p><strong>Comment:</strong> " . $this->getComment() . "</p>";
        echo "<p><strong>Date:</strong> " . $this->getDate() . "</p>";
    }
}

/**
 * User Review Function (Demonstration)
 */
function createReview($review_id = null, $user_id, $product_id, $rating, $comment) {
    // Validate inputs (Example - improve as needed)
    if (!$user_id || !$product_id || $rating < 1 || $rating > 5 || !$comment) {
        return false; // Indicate failure
    }

    $review = new UserReview($review_id, $user_id, $product_id, $rating, $comment);
    return $review;
}

// Example Usage
$review1 = createReview(null, 123, 456, 4, "Great product!  I loved it.");
$review1->displayReview();

$review2 = createReview(101, 456, 789, 5, "Excellent value for money.");
$review2->displayReview();

$review3 = createReview(null, 789, 101, 3, "It was okay, nothing special.");
$review3->displayReview();

// Example with invalid input
$review_invalid = createReview(null, 101, 112, 6, "Invalid Review");
if (!$review_invalid) {
    echo "<p>Review creation failed.</p>";
}

?>
