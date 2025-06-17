

<?php

/**
 * Class Review
 *
 * Handles creating, retrieving, updating, and deleting reviews.
 */
class Review {

    private $db; // Database connection object

    /**
     * Constructor
     *
     * Initializes the Review class with a database connection.
     *
     * @param PDO $db Database connection object.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Create a new review.
     *
     * @param string $product_id  The ID of the product the review is for.
     * @param string $user_id     The ID of the user who wrote the review.
     * @param string $rating      The rating given (e.g., 1-5).
     * @param string $comment     The review comment.
     * @return int|null The ID of the newly created review, or null on failure.
     */
    public function createReview(string $product_id, string $user_id, string $rating, string $comment) {
        $sql = "INSERT INTO reviews (product_id, user_id, rating, comment)
                VALUES (:product_id, :user_id, :rating, :comment)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        } else {
            return null;
        }
    }


    /**
     * Retrieve a review by its ID.
     *
     * @param int $review_id The ID of the review to retrieve.
     * @return array|null An associative array containing the review data, or null if not found.
     */
    public function getReview(int $review_id) {
        $sql = "SELECT * FROM reviews WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $review_id);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC); // Return as associative array
        } else {
            return null;
        }
    }

    /**
     * Retrieve all reviews for a product.
     *
     * @param int $product_id The ID of the product.
     * @return array An array of associative arrays, each representing a review.
     */
    public function getReviewsByProduct(int $product_id) {
        $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);

        $reviews = [];
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reviews[] = $row;
            }
        }
        return $reviews;
    }


    /**
     * Update an existing review.
     *
     * @param int $review_id The ID of the review to update.
     * @param string $product_id  The ID of the product the review is for.
     * @param string $user_id     The ID of the user who wrote the review.
     * @param string $rating      The new rating.
     * @param string $comment     The new comment.
     * @return bool True on successful update, false on failure.
     */
    public function updateReview(int $review_id, string $product_id, string $user_id, string $rating, string $comment) {
        $sql = "UPDATE reviews
                SET product_id = :product_id,
                    user_id = :user_id,
                    rating = :rating,
                    comment = :comment
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $review_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        return $stmt->execute();
    }


    /**
     * Delete a review by its ID.
     *
     * @param int $review_id The ID of the review to delete.
     * @return bool True on successful deletion, false on failure.
     */
    public function deleteReview(int $review_id) {
        $sql = "DELETE FROM reviews WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $review_id);

        return $stmt->execute();
    }
}

// Example usage (requires a database connection):
// Assuming you have a database connection object $db
// ...
// $reviews = new Review($db);
// $newReviewId = $reviews->createReview(123, 456, 5, "This product is amazing!");
// if ($newReviewId) {
//     echo "New review created with ID: " . $newReviewId;
// } else {
//     echo "Error creating review.";
// }

// To get reviews for product ID 123:
// $reviews = $reviews->getReviewsByProduct(123);
// print_r($reviews);
?>
