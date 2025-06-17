

<?php

/**
 * Class Review
 *
 * Handles the creation, storage, and retrieval of user reviews.
 */
class Review {

    private $db;  // Database connection

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Creates a new review.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $user  The username of the reviewer.
     * @param string $rating The rating (e.g., 1-5 stars).
     * @param string $comment The review comment.
     *
     * @return int|false The ID of the newly created review on success, false on failure.
     */
    public function createReview(int $productId, string $user, string $rating, string $comment) {
        // Input validation (important!)
        if (!$productId || !$user || !$rating || !$comment) {
            return false;
        }

        if (!is_numeric($rating) || (int)$rating < 1 || (int)$rating > 5) {
            return false; // Rating must be 1-5
        }

        // Sanitize inputs (prevent SQL injection)
        $productId = $this->db->real_escape_string($productId);
        $user = $this->db->real_escape_string($user);
        $rating = (int)$rating;
        $comment = $this->db->real_escape_string($comment);

        // Construct SQL query
        $query = "INSERT INTO reviews (product_id, user, rating, comment) VALUES ('$productId', '$user', '$rating', '$comment')";

        // Execute the query
        return $this->db->query($query);
    }


    /**
     * Retrieves reviews for a specific product.
     *
     * @param int $productId The ID of the product.
     *
     * @return array An array of review objects, or an empty array if no reviews exist.
     */
    public function getReviewsByProduct(int $productId) {
        $productId = $this->db->real_escape_string($productId);

        $query = "SELECT * FROM reviews WHERE product_id = '$productId'";
        $result = $this->db->query($query);

        $reviews = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reviews[] = new ReviewObject($row); // Create ReviewObject instances
            }
        }

        return $reviews;
    }

    /**
     *  Review Object (Helper Class) - Makes retrieving review data easier
     */
    private function __construct($data) {
        $this->id = $data['id'];
        $this->product_id = $data['product_id'];
        $this->user = $data['user'];
        $this->rating = $data['rating'];
        $this->comment = $data['comment'];
        $this->created_at = $data['created_at'];
    }

}


/**
 * ReviewObject - A class representing a single review.  This keeps things cleaner than returning
 * arrays of associative arrays.
 */
class ReviewObject {
    public $id;
    public $product_id;
    public $user;
    public $rating;
    public $comment;
    public $created_at;

    public function __construct($data) {
        $this->id = $data['id'];
        $this->product_id = $data['product_id'];
        $this->user = $data['user'];
        $this->rating = $data['rating'];
        $this->comment = $data['comment'];
        $this->created_at = $data['created_at'];
    }
}



// Example usage (assuming you have a database connection $db):
//  (This is just for demonstration, you'll need to adapt to your specific setup)

// Assuming $db is your database connection object (e.g., mysqli)

// 1. Create a review
$review = new Review($db);
$reviewId = $review->createReview(123, "JohnDoe", 4, "Great product!");

if ($reviewId) {
    echo "Review created successfully with ID: " . $reviewId . "
";
} else {
    echo "Failed to create review.
";
}


// 2. Get reviews for product 123
$reviews = $review->getReviewsByProduct(123);

if (count($reviews) > 0) {
    echo "Reviews for product 123:
";
    foreach ($reviews as $review) {
        echo "- User: " . $review->user . ", Rating: " . $review->rating . ", Comment: " . $review->comment . "
";
    }
} else {
    echo "No reviews found for product 123.
";
}
?>
