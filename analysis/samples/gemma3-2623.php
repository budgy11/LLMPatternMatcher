

<?php

/**
 * User Review Class
 *
 * This class provides functionality for creating, storing, and displaying user reviews.
 */
class UserReview {

    private $db; // Database connection object

    /**
     * Constructor
     *
     * Initializes the UserReview object with a database connection.
     *
     * @param PDO $db  The PDO database connection object.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Create a new review
     *
     * Creates a new review in the database.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $username The username of the reviewer.
     * @param string $rating The rating given by the reviewer (e.g., 1-5).
     * @param string $comment The comment made by the reviewer.
     *
     * @return int|false The ID of the newly created review, or false on failure.
     */
    public function createReview(int $productId, string $username, string $rating, string $comment) {
        try {
            $sql = "INSERT INTO reviews (product_id, user_name, rating, comment)
                    VALUES (:product_id, :user_name, :rating, :comment)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':user_name', $username);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $comment);

            $stmt->execute();

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating review: " . $e->getMessage()); // Log the error
            return false;
        }
    }

    /**
     * Get a review by ID
     *
     * Retrieves a review from the database based on its ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     *
     * @return array|null An array containing the review data, or null if not found.
     */
    public function getReviewById(int $reviewId) {
        try {
            $sql = "SELECT * FROM reviews WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $reviewId);
            $stmt->execute();

            $review = $stmt->fetch(PDO::FETCH_ASSOC);
            return $review;
        } catch (PDOException $e) {
            error_log("Error getting review: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all reviews for a product
     *
     * Retrieves all reviews for a given product ID.
     *
     * @param int $productId The ID of the product.
     *
     * @return array An array of review objects.
     */
    public function getReviewsByProduct(int $productId) {
        try {
            $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();

            $reviews = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reviews[] = $row;
            }

            return $reviews;
        } catch (PDOException $e) {
            error_log("Error getting reviews for product: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update a review
     *
     * Updates an existing review in the database.
     *
     * @param int $reviewId The ID of the review to update.
     * @param string $rating The new rating.
     * @param string $comment The new comment.
     *
     * @return bool True on success, false on failure.
     */
    public function updateReview(int $reviewId, string $rating, string $comment) {
        try {
            $sql = "UPDATE reviews SET rating = :rating, comment = :comment WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $reviewId);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $comment);

            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating review: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a review
     *
     * Deletes a review from the database.
     *
     * @param int $reviewId The ID of the review to delete.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteReview(int $reviewId) {
        try {
            $sql = "DELETE FROM reviews WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $reviewId);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error deleting review: " . $e->getMessage());
            return false;
        }
    }
}


// Example Usage (assuming you have a database connection $db)
//
// $review = new UserReview($db);
//
// // Create a new review
// $newReviewId = $review->createReview(123, 'john_doe', 5, 'Great product!');
//
// if ($newReviewId) {
//     echo "New review created with ID: " . $newReviewId . "
";
// } else {
//     echo "Error creating review.
";
// }
//
// // Get a review by ID
// $review = $review->getReviewById($newReviewId);
// if ($review) {
//     echo "Review details:
";
//     print_r($review);
// } else {
//     echo "Review not found.
";
// }
//
// // Get reviews for product 123
// $reviews = $review->getReviewsByProduct(123);
// if ($reviews) {
//     echo "Reviews for product 123:
";
//     print_r($reviews);
// } else {
//     echo "No reviews found for product 123.
";
// }
?>
