

<?php

/**
 * User Review Function
 *
 * This function handles the submission and display of user reviews.
 */

class UserReview {

    private $db_connection; // Database connection object

    public function __construct($db_config) {
        // Connect to the database
        $this->db_connection = new PDO(
            $db_config['driver'] . ":" . $db_config['host'] . ":" . $db_config['dbname'],
            $db_config['user'],
            $db_config['password']
        );

        // Set the error mode to exception
        $this->db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Submits a new user review.
     *
     * @param string $product_id The ID of the product the review is for.
     * @param string $user_name The name of the user submitting the review.
     * @param string $review_text The text of the review.
     * @return int|false The ID of the newly created review on success, or false on failure.
     */
    public function submitReview($product_id, $user_name, $review_text) {
        try {
            $stmt = $this->db_connection->prepare(
                "INSERT INTO reviews (product_id, user_name, review_text, review_date) VALUES (?, ?, ?, NOW())"
            );
            $stmt->bindParam(1, $product_id);
            $stmt->bindParam(2, $user_name);
            $stmt->bindParam(3, $review_text);
            $stmt->execute();

            return $this->db_connection->lastInsertId(); // Get the ID of the newly created review
        } catch (PDOException $e) {
            error_log("Error submitting review: " . $e->getMessage()); // Log the error for debugging
            return false;
        }
    }

    /**
     * Retrieves all reviews for a given product.
     *
     * @param int $product_id The ID of the product.
     * @return array An array of review objects, or an empty array if no reviews are found.
     */
    public function getReviewsByProduct($product_id) {
        $reviews = [];
        try {
            $stmt = $this->db_connection->prepare(
                "SELECT id, product_id, user_name, review_text, review_date FROM reviews WHERE product_id = ?"
            );
            $stmt->bindParam(1, $product_id);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reviews[] = (object) [
                    'id' => $row['id'],
                    'product_id' => $row['product_id'],
                    'user_name' => $row['user_name'],
                    'review_text' => $row['review_text'],
                    'review_date' => $row['review_date']
                ];
            }
        } catch (PDOException $e) {
            error_log("Error retrieving reviews: " . $e->getMessage());
            return []; // Return an empty array if an error occurred
        }
        return $reviews;
    }


    /**
     *  Example of deleting a review
     *  (This is just an example - you'd likely have a review ID to pass in)
     */
    public function deleteReview($review_id) {
        try {
            $stmt = $this->db_connection->prepare("DELETE FROM reviews WHERE id = ?");
            $stmt->bindParam(1, $review_id);
            $stmt->execute();

            return true; // Success
        } catch (PDOException $e) {
            error_log("Error deleting review: " . $e->getMessage());
            return false;
        }
    }
}

// --- Example Usage ---

// Database configuration (replace with your actual credentials)
$db_config = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'dbname' => 'your_database_name',
    'user' => 'your_database_user',
    'password' => 'your_database_password'
];

// Create a UserReview object
$review = new UserReview($db_config);

// Example: Submit a new review
$product_id = 1;
$user_name = 'John Doe';
$review_text = 'This is a fantastic product!';
$review_id = $review->submitReview($product_id, $user_name, $review_text);

if ($review_id) {
    echo "Review submitted successfully. Review ID: " . $review_id . "
";
} else {
    echo "Failed to submit review.
";
}

// Example: Get reviews for product 1
$reviews = $review->getReviewsByProduct($product_id);

echo "Reviews for product " . $product_id . ":
";
if (!empty($reviews)) {
    foreach ($reviews as $review) {
        echo "  ID: " . $review->id . ", User: " . $review->user_name . ", Text: " . $review->review_text . "
";
    }
} else {
    echo "No reviews found for this product.
";
}

// Example: Delete a review (you'd typically get the review ID from somewhere)
// $review->deleteReview(1); //  Be careful - this will delete review with ID 1
?>
