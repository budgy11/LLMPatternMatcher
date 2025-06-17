

<?php

/**
 * User Review Class
 *
 * This class handles the creation, storage, and retrieval of user reviews.
 */
class UserReview {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Adds a new review.
     *
     * @param int $userId The ID of the user submitting the review.
     * @param int $productId The ID of the product being reviewed.
     * @param string $rating The rating given (e.g., 1-5).
     * @param string $comment The user's review comment.
     *
     * @return bool True on success, false on failure.
     */
    public function addReview(int $userId, int $productId, string $rating, string $comment) {
        $rating = (int)$rating; // Ensure rating is an integer
        $comment = trim($comment); // Trim whitespace from the comment

        if ($rating < 1 || $rating > 5) {
            return false; // Invalid rating
        }

        // Prepare the SQL query to prevent SQL injection
        $sql = "INSERT INTO reviews (userId, productId, rating, comment) VALUES (?, ?, ?, ?)";

        // Use prepared statements to prevent SQL injection vulnerabilities
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $userId, $productId, $rating, $comment);

        if (!$stmt->execute()) {
            error_log("Error adding review: " . $stmt->error); // Log the error
            return false;
        }

        return true;
    }


    /**
     * Retrieves all reviews for a specific product.
     *
     * @param int $productId The ID of the product.
     *
     * @return array An array of review objects, or an empty array if none found.
     */
    public function getReviewsByProduct(int $productId) {
        $sql = "SELECT * FROM reviews WHERE productId = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $productId);

        if (!$stmt->execute()) {
            error_log("Error getting reviews: " . $stmt->error);
            return [];
        }

        $reviews = [];
        $stmt->bind_param("i"); // no need to bind parameters here

        while ($row = $stmt->fetch_assoc()) {
            $reviews[] = new UserReview($this->db); // Create a new review object
            $reviews[count($reviews) - 1]->userId = $row['userId'];
            $reviews[count($reviews) - 1]->productId = $row['productId'];
            $reviews[count($reviews) - 1]->rating = $row['rating'];
            $reviews[count($reviews) - 1]->comment = $row['comment'];
        }

        $stmt->close(); // Close the statement
        return $reviews;
    }

    /**
     * Retrieves a single review by ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     *
     * @return UserReview|null A UserReview object if found, or null if not found.
     */
    public function getReviewById(int $reviewId) {
        $sql = "SELECT * FROM reviews WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $reviewId);

        if (!$stmt->execute()) {
            error_log("Error getting review by ID: " . $stmt->error);
            return null;
        }

        $row = $stmt->fetch_assoc();
        if ($row) {
            return new UserReview($this->db);
        }

        $stmt->close();
        return null;
    }


    /**
     * Deletes a review by ID.
     *
     * @param int $reviewId The ID of the review to delete.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteReview(int $reviewId) {
        $sql = "DELETE FROM reviews WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $reviewId);

        if (!$stmt->execute()) {
            error_log("Error deleting review: " . $stmt->error);
            return false;
        }

        $stmt->close();
        return true;
    }

}



// Example Usage (assuming you have a database connection $db)
// Create a database connection (replace with your actual connection)
$db = new mysqli("localhost", "username", "password", "database_name");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Create a UserReview instance
$reviewManager = new UserReview($db);

// Add a review
$reviewManager->addReview(123, 456, 5, "Great product!");

// Get reviews for product 456
$reviews = $reviewManager->getReviewsByProduct(456);
foreach ($reviews as $review) {
    echo "User ID: " . $review->userId . "<br>";
    echo "Rating: " . $review->rating . "<br>";
    echo "Comment: " . $review->comment . "<br><br>";
}

//Get a specific review by id
$review = $reviewManager->getReviewById(1);
if ($review) {
    echo "Review by id 1: " . $review->comment . "<br>";
}

//delete a review
$reviewManager->deleteReview(1);

// Close the database connection
$db->close();

?>
