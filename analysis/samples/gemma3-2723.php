

<?php

/**
 * Class Review
 *
 * Handles the creation, storage, and retrieval of user reviews.
 */
class Review {

    private $db; // Database connection object

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Creates a new review.
     *
     * @param int $productId The ID of the product the review is for.
     * @param int $userId The ID of the user submitting the review.
     * @param string $title The title of the review.
     * @param string $comment The content of the review.
     * @param int $rating The rating (e.g., 1-5).
     * @return int|false The ID of the newly created review on success, or false on failure.
     */
    public function createReview(int $productId, int $userId, string $title, string $comment, int $rating) {
        if ($this->db->isConnected()) { // Check if database connection is active
            $sql = "INSERT INTO reviews (product_id, user_id, title, comment, rating)
                    VALUES (:product_id, :user_id, :title, :comment, :rating)";

            $stmt = $this->db->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':comment', $comment);
            $stmt->bindParam(':rating', $rating);

            // Execute the query
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            } else {
                // Handle error
                error_log("Error creating review: " . $this->db->errorInfo());
                return false;
            }
        } else {
            //Handle no database connection
            error_log("Database connection is not active.");
            return false;
        }
    }

    /**
     * Retrieves all reviews for a given product.
     *
     * @param int $productId The ID of the product to retrieve reviews for.
     * @return array An array of review objects, or an empty array if no reviews are found.
     */
    public function getReviewsByProduct(int $productId) {
        if ($this->db->isConnected()) {
            $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();

            $reviews = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reviews[] = (object) $row; // Create an object from the row
            }

            return $reviews;
        } else {
            //Handle no database connection
            error_log("Database connection is not active.");
            return [];
        }
    }

    /**
     * Retrieves a single review by its ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return \stdClass|null The review object if found, or null if not found.
     */
    public function getReviewById(int $reviewId) {
        if ($this->db->isConnected()) {
            $sql = "SELECT * FROM reviews WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $reviewId);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return (object) $row;
            } else {
                return null;
            }
        } else {
            //Handle no database connection
            error_log("Database connection is not active.");
            return null;
        }
    }

    /**
     * Updates an existing review.
     *
     * @param int $reviewId The ID of the review to update.
     * @param string $title The new title.
     * @param string $comment The new comment.
     * @param int $rating The new rating.
     * @return bool True on success, false on failure.
     */
    public function updateReview(int $reviewId, string $title, string $comment, int $rating) {
        if ($this->db->isConnected()) {
            $sql = "UPDATE reviews SET title = :title, comment = :comment, rating = :rating WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $reviewId);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':comment', $comment);
            $stmt->bindParam(':rating', $rating);
            $stmt->execute();

            return $stmt->rowCount() > 0; // Returns true if at least one row was affected
        } else {
            //Handle no database connection
            error_log("Database connection is not active.");
            return false;
        }
    }

    /**
     * Deletes a review.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteReview(int $reviewId) {
        if ($this->db->isConnected()) {
            $sql = "DELETE FROM reviews WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $reviewId);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } else {
            //Handle no database connection
            error_log("Database connection is not active.");
            return false;
        }
    }
}

// Example usage (Assuming you have a database connection object called $db)

// $review = new Review($db);

// $newReviewId = $review->createReview(1, 123, "Great product!", "Highly recommended.", 5);

// if ($newReviewId) {
//     echo "New review created with ID: " . $newReviewId . "<br>";
// } else {
//     echo "Error creating review.<br>";
// }

// $reviews = $review->getReviewsByProduct(1);

// echo "Reviews for product 1:<br>";
// foreach ($reviews as $review) {
//     echo "Product ID: " . $review->product_id . "<br>";
//     echo "Title: " . $review->title . "<br>";
//     echo "Comment: " . $review->comment . "<br>";
//     echo "Rating: " . $review->rating . "<br>";
//     echo "<br>";
// }

?>
