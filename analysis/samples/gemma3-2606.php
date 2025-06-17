

<?php

/**
 * Class Review
 *
 * Handles user reviews, including storing them, retrieving them, and displaying them.
 */
class Review {

    private $db; // Database connection (implementation depends on your database)

    /**
     * Constructor
     *
     * Initializes the Review class with a database connection.
     *
     * @param PDO $db  A PDO database connection object.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Add a new review to the database.
     *
     * @param string $userId    The ID of the user submitting the review.
     * @param string $productID The ID of the product being reviewed.
     * @param string $rating   The rating (e.g., 1-5).
     * @param string $comment  The user's review comment.
     * @return bool          True on success, false on failure.
     */
    public function addReview(string $userId, string $productID, string $rating, string $comment) {
        try {
            $sql = "INSERT INTO reviews (user_id, product_id, rating, comment)
                    VALUES (:user_id, :product_id, :rating, :comment)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':product_id', $productID);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $comment);

            $result = $stmt->execute();
            return $result;

        } catch (PDOException $e) {
            error_log("Error adding review: " . $e->getMessage()); // Log the error
            return false;
        }
    }


    /**
     * Get all reviews for a given product.
     *
     * @param string $productID The ID of the product to retrieve reviews for.
     * @return array|false An array of review objects, or false if no reviews are found.
     */
    public function getReviewsByProduct(string $productID) {
        try {
            $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':product_id', $productID);
            $stmt->execute();

            $reviews = [];
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                $reviews[] = $row;
            }
            return $reviews;

        } catch (PDOException $e) {
            error_log("Error getting reviews: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Get a single review by ID.
     *
     * @param int $reviewID The ID of the review to retrieve.
     * @return object|false A review object, or false if the review is not found.
     */
    public function getReviewById(int $reviewID) {
        try {
            $sql = "SELECT * FROM reviews WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $reviewID);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_OBJ);
            return $row;

        } catch (PDOException $e) {
            error_log("Error getting review by ID: " . $e->getMessage());
            return false;
        }
    }



    /**
     *  (Optional)  Example function to update a review.  Not essential for basic review functionality.
     *
     * @param int $reviewID The ID of the review to update.
     * @param string $newRating The new rating.
     * @param string $newComment The new comment.
     * @return bool True on success, false on failure.
     */
    public function updateReview(int $reviewID, string $newRating, string $newComment) {
        try {
            $sql = "UPDATE reviews SET rating = :new_rating, comment = :new_comment WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $reviewID);
            $stmt->bindParam(':new_rating', $newRating);
            $stmt->bindParam(':new_comment', $newComment);

            $result = $stmt->execute();
            return $result;

        } catch (PDOException $e) {
            error_log("Error updating review: " . $e->getMessage());
            return false;
        }
    }

    /**
     * (Optional) Example function to delete a review.
     *
     * @param int $reviewID The ID of the review to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteReview(int $reviewID) {
        try {
            $sql = "DELETE FROM reviews WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $reviewID);
            $stmt->execute();
            return true;

        } catch (PDOException $e) {
            error_log("Error deleting review: " . $e->getMessage());
            return false;
        }
    }


}


// Example usage (assuming you have a database connection named $db)
// $review = new Review($db);

// // Add a review
// $review->addReview(123, 456, 5, "Great product!");

// // Get reviews for a product
// $reviews = $review->getReviewsByProduct(456);
// if ($reviews) {
//     foreach ($reviews as $review) {
//         echo "User ID: " . $review->user_id . "<br>";
//         echo "Rating: " . $review->rating . "<br>";
//         echo "Comment: " . $review->comment . "<br><br>";
//     }
// } else {
//     echo "No reviews found for this product.";
// }
?>
