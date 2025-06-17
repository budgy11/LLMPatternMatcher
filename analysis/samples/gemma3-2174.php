

<?php

/**
 * User Review Class
 *
 * This class allows you to store and manage user reviews for a product or item.
 */
class UserReview {

    private $productId;
    private $userId;
    private $rating;
    private $comment;
    private $date;
    private $isApproved;

    /**
     * Constructor
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
        $this->date = date('Y-m-d H:i:s'); // Set current timestamp
        $this->isApproved = false; // Default to unapproved
    }

    /**
     * Getters
     *
     * @return mixed
     */
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

    public function isApproved() {
        return $this->isApproved;
    }

    /**
     * Approve the review
     */
    public function approve() {
        $this->isApproved = true;
    }

    /**
     *  Returns the review as an array for easy output.
     *
     *  @return array  An associative array containing the review data.
     */
    public function toArray() {
        return [
            'product_id' => $this->getProductId(),
            'user_id' => $this->getUserId(),
            'rating' => $this->getRating(),
            'comment' => $this->getComment(),
            'date' => $this->getDate(),
            'is_approved' => $this->isApproved()
        ];
    }
}


/**
 * User Review Function (Example Usage)
 */
function displayUserReviews($reviews) {
    echo "<h2>User Reviews:</h2>";

    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
        return;
    }

    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li>";
        echo "<strong>User ID:</strong> " . $review->getUserId() . "<br>";
        echo "<strong>Rating:</strong> " . $review->getRating() . " / 5<br>";
        echo "<strong>Comment:</strong> " . $review->getComment() . "<br>";
        echo "<strong>Date:</strong> " . $review->getDate() . "<br>";
        if ($review->isApproved()) {
            echo "<strong>Status:</strong> Approved<br>";
        } else {
            echo "<strong>Status:</strong> Pending Approval<br>";
        }
        echo "</li>";
    }
    echo "</ul>";
}



// --- Example Usage ---

// Create some reviews
$review1 = new UserReview(123, 45, 4, "Great product, highly recommended!");
$review2 = new UserReview(456, 78, 5, "Excellent value for money.");
$review3 = new UserReview(123, 90, 3, "It was okay.");

// Store the reviews (e.g., in an array or database)
$reviews = [$review1, $review2, $review3];


// Display the reviews
displayUserReviews($reviews);


// You can also approve a review:
$review2->approve();
echo "<br>After approval: ";
displayUserReviews($reviews);


?>
