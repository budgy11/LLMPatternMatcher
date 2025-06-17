

<?php

/**
 * Class Review
 *
 * This class provides functionality to handle user reviews.
 * It allows adding, retrieving, and deleting reviews.
 */
class Review {

    private $db; // Database connection object (implementation not defined here)

    /**
     * Constructor
     *
     * Initializes the Review object.  Requires a database connection object.
     *
     * @param PDO $db A PDO database connection object.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Add a new review.
     *
     * @param string $userId The ID of the user submitting the review.
     * @param string $productID The ID of the product the review is for.
     * @param string $rating The rating given (e.g., 1-5).
     * @param string $comment The user's written review.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $userId, string $productID, string $rating, string $comment) {
        try {
            // Sanitize inputs (basic - improve for production)
            $userId = filter_var($userId, FILTER_SANITIZE_STRING);
            $productID = filter_var($productID, FILTER_SANITIZE_STRING);
            $rating = filter_var($rating, FILTER_VALIDATE_INT);
            $comment = filter_var($comment, FILTER_SANITIZE_STRING);

            if (!$rating || $rating < 1 || $rating > 5) {
                return false; // Invalid rating
            }

            // SQL query - Use prepared statements to prevent SQL injection
            $stmt = $this->db->prepare(
                "INSERT INTO reviews (user_id, product_id, rating, comment)
                 VALUES (:user_id, :product_id, :rating, :comment)"
            );

            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':product_id', $productID);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $comment);

            $stmt->execute();
            return true;

        } catch (PDOException $e) {
            error_log("Error adding review: " . $e->getMessage()); // Log the error for debugging
            return false;
        }
    }

    /**
     * Get all reviews for a product.
     *
     * @param string $productID The ID of the product.
     * @return array An array of review objects, or an empty array if no reviews exist.
     */
    public function getReviewsByProduct(string $productID) {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM reviews WHERE product_id = :product_id"
            );
            $stmt->bindParam(':product_id', $productID);
            $stmt->execute();

            $reviews = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reviews[] = $row;
            }
            return $reviews;
        } catch (PDOException $e) {
            error_log("Error retrieving reviews: " . $e->getMessage());
            return [];
        }
    }


    /**
     * Delete a review.
     *
     * @param int $reviewID The ID of the review to delete.
     * @return bool True if the review was deleted successfully, false otherwise.
     */
    public function deleteReview(int $reviewID) {
        try {
            $stmt = $this->db->prepare(
                "DELETE FROM reviews WHERE id = :review_id"
            );
            $stmt->bindParam(':review_id', $reviewID);
            $stmt->execute();
            return true;

        } catch (PDOException $e) {
            error_log("Error deleting review: " . $e->getMessage());
            return false;
        }
    }
}


// Example usage (assuming you have a database connection $db)
// Create a database connection (replace with your actual connection details)
$host = 'localhost';
$username = 'your_db_username';
$password = 'your_db_password';
$dbname = 'your_db_name';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $review = new Review($db);

    // Add a review
    $review->addReview('user123', 'product456', 4, 'Great product!');

    // Get reviews for product456
    $reviews = $review->getReviewsByProduct('product456');
    echo "Reviews for product456:<br>";
    foreach ($reviews as $review) {
        echo "- User: " . $review['user_id'] . ", Rating: " . $review['rating'] . ", Comment: " . $review['comment'] . "<br>";
    }

    // Delete a review (example)
    if($review->deleteReview(1)) {
        echo "<br>Review with ID 1 deleted successfully.";
    } else {
        echo "<br>Failed to delete review.";
    }


} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
