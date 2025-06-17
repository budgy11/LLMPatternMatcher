

<?php

/**
 * User Review Class
 * 
 * This class provides functionality to create, store, and retrieve user reviews.
 */
class UserReview {

    private $reviewId;
    private $userId;
    private $productId;
    private $rating;
    private $comment;
    private $timestamp;

    /**
     * Constructor
     *
     * @param int $userId      The ID of the user submitting the review.
     * @param int $productId   The ID of the product being reviewed.
     * @param int $rating       The rating given by the user (e.g., 1-5).
     * @param string $comment   The user's comment about the product.
     */
    public function __construct($userId, $productId, $rating, $comment) {
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->timestamp = time(); // Get the current timestamp
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

    public function getTimestamp() {
        return $this->timestamp;
    }


    /**
     * Convert review data into an associative array for easy display/export.
     *
     * @return array An associative array containing the review data.
     */
    public function toArray() {
        return [
            'reviewId' => $this->getReviewId(),
            'userId' => $this->getUserId(),
            'productId' => $this->getProductId(),
            'rating' => $this->getRating(),
            'comment' => $this->getComment(),
            'timestamp' => $this->getTimestamp()
        ];
    }

    /**
     *  This is a basic example.  In a real application, you'd store this data in a database.
     *  This demonstrates how to store the review data.
     *  @param string $dbConnection The database connection string.  Replace with your connection details.
     */
    public function save($dbConnection) {
        // This is a placeholder.  In a real application, you would use a database query to save the review.
        // Example using PDO:
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("INSERT INTO reviews (userId, productId, rating, comment, timestamp) 
                                    VALUES (:userId, :productId, :rating, :comment, :timestamp)");
            $stmt->bindParam(':userId', $this->userId);
            $stmt->bindParam(':productId', $this->productId);
            $stmt->bindParam(':rating', $this->rating);
            $stmt->bindParam(':comment', $this->comment);
            $stmt->bindParam(':timestamp', $this->timestamp);

            $stmt->execute();
            $this->reviewId = $pdo->lastInsertId();
        } catch (PDOException $e) {
            // Handle database errors here.  Log the error, display a user-friendly message, etc.
            echo "Database error: " . $e->getMessage();
        }
    }

}



/**
 * User Review Review Function (Example Usage)
 */
function displayReview($review) {
    if ($review) {
        echo "<h2>Review for Product ID: " . $review->getProductId() . "</h2>";
        echo "<p><strong>User:</strong> " . $review->getUserId() . "</p>";
        echo "<p><strong>Rating:</strong> " . $review->getRating() . "</p>";
        echo "<p><strong>Comment:</strong> " . $review->getComment() . "</p>";
        echo "<p><strong>Date:</strong> " . date("Y-m-d H:i:s", $review->getTimestamp()) . "</p>";
    } else {
        echo "<p>No reviews found.</p>";
    }
}



// Example usage:
$review1 = new UserReview(123, 456, 5, "Excellent product, highly recommended!");
$review1->save("localhost", "root", "password"); // Replace with your DB details.
displayReview($review1);

$review2 = new UserReview(456, 456, 3, "Okay, but could be better.");
$review2->save("localhost", "root", "password"); // Replace with your DB details.
displayReview($review2);

?>
