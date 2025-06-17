

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It demonstrates basic CRUD operations and provides a simple interface
 * for managing reviews.
 *
 * Note: This is a simplified example for illustrative purposes.  In a real-world
 * application, you'd want to handle database connections, error handling,
 * input validation, and potentially more complex features.
 */

class Review {
    private $id;
    private $user_id;
    private $product_id;
    private $rating;
    private $comment;
    private $created_at;

    /**
     * Constructor
     *
     * Initializes a new Review object.
     *
     * @param int $user_id
     * @param int $product_id
     * @param int $rating
     * @param string $comment
     */
    public function __construct($user_id, $product_id, $rating, $comment) {
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->created_at = date('Y-m-d H:i:s');
    }

    /**
     * Getters
     *
     *  Provides access to the properties of the Review object.
     *
     * @return mixed
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

    public function getCreated_at() {
        return $this->created_at;
    }

    /**
     * Setters
     *
     * Allows updating properties of the Review object.
     *
     * @param mixed $id
     * @param mixed $rating
     * @param string $comment
     */
    public function setId($id) {
        $this->id = $id;
    }

    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }
}


/**
 * Review Management Functions
 */
class ReviewManager {
    private $reviews = [];

    public function addReview($user_id, $product_id, $rating, $comment) {
        $review = new Review($user_id, $product_id, $rating, $comment);
        $this->reviews[] = $review;
        return $review; // Return the newly created review
    }

    public function getReviewsByProductId($product_id) {
        $results = [];
        foreach ($this->reviews as $review) {
            if ($review->getProduct_id() == $product_id) {
                $results[] = $review;
            }
        }
        return $results;
    }

    public function getReviewsByUser($user_id) {
        $results = [];
        foreach ($this->reviews as $review) {
            if ($review->getUser_id() == $user_id) {
                $results[] = $review;
            }
        }
        return $results;
    }

    // You could add update and delete functions here too
}


// Example Usage
$reviewManager = new ReviewManager();

// Create a review
$review1 = $reviewManager->addReview(1, 101, 5, "Great product!  Highly recommend.");
$review2 = $reviewManager->addReview(2, 101, 4, "Good value for the money.");
$review3 = $reviewManager->addReview(1, 102, 3, "Okay product, nothing special.");

// Get reviews for product 101
$reviewsForProduct101 = $reviewManager->getReviewsByProductId(101);
echo "Reviews for Product 101:
";
foreach ($reviewsForProduct101 as $review) {
    echo "- " . $review->getCreated_at() . " - User ID: " . $review->getUser_id() . " - Rating: " . $review->getRating() . " - Comment: " . $review->getComment() . "
";
}

// Get reviews for user 1
$reviewsByUser1 = $reviewManager->getReviewsByUser(1);
echo "
Reviews for User 1:
";
foreach ($reviewsByUser1 as $review) {
    echo "- " . $review->getCreated_at() . " - User ID: " . $review->getUser_id() . " - Rating: " . $review->getRating() . " - Comment: " . $review->getComment() . "
";
}
?>
