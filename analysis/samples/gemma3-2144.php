

<?php

/**
 * Class UserReview
 *
 * Handles creating, retrieving, and displaying user reviews.
 */
class UserReview
{
    private $db; // Database connection

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Creates a new user review.
     *
     * @param int $productId The ID of the product being reviewed.
     * @param string $username The username of the reviewer.
     * @param string $rating The rating given (e.g., 1-5).
     * @param string $comment The user's review comment.
     *
     * @return bool True on success, false on failure.
     */
    public function createReview($productId, $username, $rating, $comment)
    {
        // Validate inputs (important for security and data integrity)
        if (!$this->validateInput($productId, $username, $rating, $comment)) {
            return false;
        }

        // Escape data to prevent SQL injection
        $productId = $this->db->real_escape_string($productId);
        $username = $this->db->real_escape_string($username);
        $rating = $this->db->real_escape_string($rating);
        $comment = $this->db->real_escape_string($comment);


        $sql = "INSERT INTO reviews (product_id, user_name, rating, comment)
                VALUES ('$productId', '$username', '$rating', '$comment')";

        if ($this->db->query($sql) === TRUE) {
            return true;
        } else {
            // Handle database error
            error_log("Error creating review: " . $this->db->error);
            return false;
        }
    }

    /**
     * Retrieves all reviews for a given product.
     *
     * @param int $productId The ID of the product.
     *
     * @return array An array of review objects.  Returns an empty array if no reviews.
     */
    public function getReviewsByProduct($productId)
    {
        $productId = $this->db->real_escape_string($productId);

        $sql = "SELECT * FROM reviews WHERE product_id = '$productId'";
        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            $reviews = [];
            while ($row = $result->fetch_assoc()) {
                $reviews[] = new Review($row); // Assuming you have a Review class
                // Alternatively,  $reviews[] = $row;
            }
            return $reviews;
        } else {
            return [];
        }
    }

     /**
     * Retrieves a single review by ID.
     *
     * @param int $reviewId The ID of the review.
     *
     * @return Review|null A Review object if found, null otherwise.
     */
    public function getReviewById($reviewId) {
        $reviewId = $this->db->real_escape_string($reviewId);

        $sql = "SELECT * FROM reviews WHERE id = '$reviewId'";
        $result = $this->db->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return new Review($row);
        } else {
            return null;
        }
    }



    /**
     * Validates input data.  This is crucial for security and data integrity.
     *
     * @param int $productId
     * @param string $username
     * @param string $rating
     * @param string $comment
     *
     * @return bool True if inputs are valid, false otherwise.
     */
    private function validateInput($productId, $username, $rating, $comment)
    {
        if (!is_numeric($productId)) {
            return false;
        }

        if (empty($username)) {
            return false;
        }

        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        if (empty($comment)) {
            return false;
        }

        return true;
    }
}


/**
 * Review Class (simplified example)
 */
class Review
{
    public $id;
    public $productId;
    public $userName;
    public $rating;
    public $comment;
    public $createdAt;

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->productId = $data['product_id'];
        $this->userName = $data['user_name'];
        $this->rating = $data['rating'];
        $this->comment = $data['comment'];
        $this->createdAt = $data['created_at'];
    }
}



// Example Usage (using a mock database connection for demonstration)
//  Replace this with your actual database connection

class MockDB
{
    public function real_escape_string($str) {
        return $str; // Mock implementation -  In real code, this should handle proper escaping.
    }

    public function query($sql) {
      // Simulate database query results.  Replace with your actual database queries.
      if ($sql == "SELECT * FROM reviews WHERE product_id = 1") {
        return new ReviewResult(['id' => 1, 'product_id' => 1, 'user_name' => 'JohnDoe', 'rating' => 5, 'comment' => 'Great product!', 'created_at' => '2023-10-27 10:00:00']);
      } else if ($sql == "INSERT INTO reviews (product_id, user_name, rating, comment) VALUES ('1', 'JaneSmith', '4', 'Good but could be better')") {
        return true; // Simulate successful insert
      }
      else {
        return new ReviewResult();
      }

    }

    public function error() {
      return "Database error";
    }
}

$db = new MockDB();
$reviewSystem = new UserReview($db);

// Create a review
if ($reviewSystem->createReview(1, 'JohnDoe', 5, 'Excellent product!')) {
    echo "Review created successfully!
";
} else {
    echo "Error creating review.
";
}

// Get reviews for product 1
$reviews = $reviewSystem->getReviewsByProduct(1);

echo "Reviews for product 1:
";
if (!empty($reviews)) {
    foreach ($reviews as $review) {
        echo "  ID: " . $review->id . ", User: " . $review->userName . ", Rating: " . $review->rating . ", Comment: " . $review->comment . "
";
    }
} else {
    echo "  No reviews found.
";
}

// Get review by ID
$review = $reviewSystem->getReviewById(1);

if ($review) {
    echo "Review ID 1:
";
    echo "  ID: " . $review->id . ", User: " . $review->userName . ", Rating: " . $review->rating . ", Comment: " . $review->comment . "
";
} else {
    echo "Review not found.
";
}
?>
