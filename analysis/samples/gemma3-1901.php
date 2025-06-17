

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
     * @param int $reviewId (optional)
     * @param int $userId
     * @param int $productId
     * @param int $rating
     * @param string $comment
     */
    public function __construct($reviewId = null, $userId, $productId, $rating, $comment) {
        $this->reviewId = $reviewId;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->reviewDate = date("Y-m-d H:i:s"); // Use current timestamp for date
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
     * Setters (optional - for update functionality)
     */
    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    /**
     * Display Review Information (for testing or display)
     */
    public function displayReview() {
        echo "<p>Review ID: " . $this->getReviewId() . "</p>";
        echo "<p>User ID: " . $this->getUserId() . "</p>";
        echo "<p>Product ID: " . $this->getProductId() . "</p>";
        echo "<p>Rating: " . $this->getRating() . "</p>";
        echo "<p>Comment: " . $this->getComment() . "</p>";
        echo "<p>Review Date: " . $this->getReviewDate() . "</p>";
    }
}


/**
 * User Review Function (Class to handle reviews)
 */
class UserReviewManager {

    private $reviews = [];

    /**
     * Add a review
     *
     * @param UserReview $review
     */
    public function addReview(UserReview $review) {
        $this->reviews[] = $review;
    }

    /**
     * Get all reviews
     *
     * @return array
     */
    public function getAllReviews() {
        return $this->reviews;
    }

    /**
     * Get reviews for a specific product ID
     *
     * @param int $productId
     * @return array
     */
    public function getReviewsByProduct($productId) {
        $productReviews = [];
        foreach ($this->getAllReviews() as $review) {
            if ($review->getProductId() == $productId) {
                $productReviews[] = $review;
            }
        }
        return $productReviews;
    }


    /**
     * Calculate Average Rating
     *
     * @return float|null
     */
    public function calculateAverageRating() {
        if (empty($this->getAllReviews())) {
            return null; // No reviews to calculate from
        }

        $totalRating = 0;
        foreach ($this->getAllReviews() as $review) {
            $totalRating += $review->getRating();
        }

        return $totalRating / count($this->getAllReviews());
    }
}


// --- Example Usage ---
// Create a Review Manager
$reviewManager = new UserReviewManager();

// Create some reviews
$review1 = new UserReview(null, 123, 456, 5, "Great product!");
$reviewManager->addReview($review1);

$review2 = new UserReview(null, 456, 456, 4, "Good, but could be better.");
$reviewManager->addReview($review2);

$review3 = new UserReview(null, 789, 456, 3, "Not what I expected.");
$reviewManager->addReview($review3);

// Get all reviews
$allReviews = $reviewManager->getAllReviews();
echo "<h2>All Reviews:</h2>";
foreach ($allReviews as $review) {
    $review->displayReview();
    echo "<br>";
}

// Get reviews for product 456
$product456Reviews = $reviewManager->getReviewsByProduct(456);
echo "<h2>Reviews for Product 456:</h2>";
foreach ($product456Reviews as $review) {
    $review->displayReview();
    echo "<br>";
}

// Calculate average rating
$averageRating = $reviewManager->calculateAverageRating();
echo "<h2>Average Rating: " . ($averageRating === null ? "No reviews to calculate from" : $averageRating) . "</h2>";
?>
