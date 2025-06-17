

<?php

/**
 * User Review Function
 * 
 * This function allows you to create, read, update, and delete user reviews.
 * It uses a simple database (you'll need to adapt this to your actual database).
 * 
 *  Important:  This is a simplified example.  For a production environment,
 *  you should use a proper database library and implement robust error handling,
 *  input validation, and security measures.
 */

class UserReview {

    private $db_host = "localhost";
    private $db_name = "your_database_name";
    private $db_user = "your_db_user";
    private $db_pass = "your_db_password";

    private $db;

    public function __construct() {
        try {
            $this->db = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_user, $this->db_pass);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Creates a new review.
     *
     * @param string $product_id The ID of the product the review is for.
     * @param string $user_id   The ID of the user writing the review.
     * @param string $rating    The rating (e.g., 1-5).
     * @param string $comment   The review comment.
     *
     * @return int|false The ID of the new review if successful, or false on failure.
     */
    public function createReview(string $product_id, string $user_id, string $rating, string $comment) {
        try {
            $stmt = $this->db->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
            $stmt->execute([$product_id, $user_id, $rating, $comment]);
            return $this->db->lastInsertId();

        } catch (PDOException $e) {
            // Log the error or handle it appropriately.
            error_log("Error creating review: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Reads a review by its ID.
     *
     * @param int $review_id The ID of the review to retrieve.
     *
     * @return array|false An array containing review data, or false if not found.
     */
    public function readReview(int $review_id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM reviews WHERE id = ?");
            $stmt->execute([$review_id]);
            $review = $stmt->fetch(PDO::FETCH_ASSOC);
            return $review ? $review : false;

        } catch (PDOException $e) {
            error_log("Error reading review: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Updates an existing review.
     *
     * @param int $review_id The ID of the review to update.
     * @param string $product_id The new product_id (if changed).
     * @param string $user_id   The new user_id (if changed).
     * @param string $rating    The new rating.
     * @param string $comment   The new comment.
     *
     * @return bool True on success, false on failure.
     */
    public function updateReview(int $review_id, string $product_id, string $user_id, string $rating, string $comment) {
        try {
            $stmt = $this->db->prepare("UPDATE reviews SET product_id = ?, user_id = ?, rating = ?, comment = ? WHERE id = ?");
            $stmt->execute([$product_id, $user_id, $rating, $comment, $review_id]);
            return $stmt->rowCount() > 0; // Return true if at least one row was affected.
        } catch (PDOException $e) {
            error_log("Error updating review: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletes a review.
     *
     * @param int $review_id The ID of the review to delete.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteReview(int $review_id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = ?");
            $stmt->execute([$review_id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error deleting review: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Reads all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews() {
        $reviews = [];
        $stmt = $this->db->prepare("SELECT * FROM reviews");
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = $row;
        }
        return $reviews;
    }
}


// Example Usage:
$reviewManager = new UserReview();

// Create a review
$newReviewId = $reviewManager->createReview(1, 123, 4, "Great product!");

if ($newReviewId) {
    echo "New review created with ID: " . $newReviewId . "
";
} else {
    echo "Failed to create review.
";
}

// Read a review
$review = $reviewManager->readReview($newReviewId);
if ($review) {
    echo "Review: " . json_encode($review) . "
";
} else {
    echo "Review not found.
";
}

// Update a review
$updateSuccessful = $reviewManager->updateReview($newReviewId, 2, 456, 5, "Even better!");
if ($updateSuccessful) {
    echo "Review updated successfully.
";
} else {
    echo "Failed to update review.
";
}

// Delete a review
$deleteSuccessful = $reviewManager->deleteReview($newReviewId);
if ($deleteSuccessful) {
    echo "Review deleted successfully.
";
} else {
    echo "Failed to delete review.
";
}

// Get all reviews
$allReviews = $reviewManager->getAllReviews();
echo "All Reviews: " . json_encode($allReviews) . "
";
?>
