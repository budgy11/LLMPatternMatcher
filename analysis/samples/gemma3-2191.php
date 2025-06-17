

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It provides a basic framework and can be customized further.
 */

class Review {
    private $id;
    private $user_id;
    private $product_id;
    private $rating;
    private $comment;
    private $date_created;

    /**
     * Constructor
     *
     * Initializes a new Review object.
     *
     * @param int $user_id The ID of the user who wrote the review.
     * @param int $product_id The ID of the product the review is for.
     * @param int $rating The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment about the product.
     */
    public function __construct($user_id, $product_id, $rating, $comment)
    {
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date_created = date("Y-m-d H:i:s"); // Get current timestamp
    }


    /**
     * Getters
     */
    public function getId() {
        return $this->id;
    }

    public function getUser_id() {
        return $this->user_id;
    }

    public function getProduct_id() {
        return $this->product_id;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getDate_created() {
        return $this->date_created;
    }

    /**
     * Setters
     */
    public function setId($id) {
        $this->id = $id;
    }

    public function setDate_created($date_created) {
        $this->date_created = $date_created;
    }

}


/**
 * User Review Function - Example Usage &  Some Basic Functions
 */
class ReviewManager {

    private $reviews = [];  // Store reviews (for simplicity)

    /**
     * Add a review
     *
     * @param Review $review
     */
    public function addReview(Review $review) {
        $this->reviews[] = $review;
    }

    /**
     * Get all reviews for a product
     *
     * @param int $product_id
     * @return array An array of Review objects.
     */
    public function getReviewsByProduct($product_id) {
        $results = [];
        foreach ($this->reviews as $review) {
            if ($review->getProduct_id() == $product_id) {
                $results[] = $review;
            }
        }
        return $results;
    }

    /**
     *  (Simplified) Update a review - in a real system, you'd likely want more robust validation.
     * @param Review $review
     */
    public function updateReview(Review $review) {
        // In a real application, you'd likely check for existing reviews
        // and perform validation before updating.
        foreach($this->reviews as $r) {
            if($r->getId() == $review->getId()) {
                $r->setDate_created(date("Y-m-d H:i:s")); // Update timestamp
                return true;
            }
        }
        return false; // Not found
    }

    /**
     * Delete a review
     *
     * @param int $id
     * @return bool True if successful, false otherwise.
     */
    public function deleteReview($id) {
        foreach ($this->reviews as $key => $review) {
            if ($review->getId() == $id) {
                unset($this->reviews[$key]);
                return true;
            }
        }
        return false;
    }
}

// Example Usage:
$reviewManager = new ReviewManager();

// Create a review
$review1 = new Review(123, 456, 5, "Great product! I highly recommend it.");
$reviewManager->addReview($review1);

$review2 = new Review(456, 456, 4, "Good value for the price.");
$reviewManager->addReview($review2);


// Get reviews for product 456
$reviewsForProduct = $reviewManager->getReviewsByProduct(456);
echo "Reviews for product 456:
";
foreach ($reviewsForProduct as $review) {
    echo "  User ID: " . $review->getUser_id() . ", Rating: " . $review->getRating() . ", Comment: " . $review->getComment() . "
";
}

// Update a review
$reviewManager->updateReview($review1);

// Delete a review
$reviewManager->deleteReview($review1->getId());

// Try to get the deleted review
$reviewsForProduct = $reviewManager->getReviewsByProduct(456);
echo "Reviews for product 456 after deletion:
";
foreach ($reviewsForProduct as $review) {
    echo "  User ID: " . $review->getUser_id() . ", Rating: " . $review->getRating() . ", Comment: " . $review->getComment() . "
";
}
?>
