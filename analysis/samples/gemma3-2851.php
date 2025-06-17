

<?php

/**
 * Class UserReview
 *
 * This class provides functionality to create, retrieve, update, and delete user reviews.
 * It includes basic validation and error handling.
 */
class UserReview
{
    private $db; // Database connection object (replace with your actual database setup)

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Creates a new user review.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $username The username of the reviewer.
     * @param string $rating The rating (e.g., 1-5).
     * @param string $comment The review text.
     *
     * @return int|false The ID of the newly created review on success, false on failure.
     */
    public function createReview($productId, $username, $rating, $comment)
    {
        // Validation (add more as needed)
        if (!$productId || !$username || !$rating || !$comment) {
            return false;
        }

        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        // Sanitize input (important for security)
        $productId = (int)$productId; // Cast to integer
        $username = trim($username);
        $rating = (int)$rating;
        $comment = trim($comment);

        // Prepare and execute the SQL query
        $sql = "INSERT INTO reviews (product_id, username, rating, comment) 
                VALUES ($productId, '$username', $rating, '$comment')";

        $result = $this->db->query($sql);

        if ($result) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * Retrieves a review by its ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     *
     * @return array|false An array containing the review details (product_id, username, rating, comment)
     *                   or false if the review does not exist.
     */
    public function getReview($reviewId)
    {
        if (!is_numeric($reviewId)) {
            return false;
        }

        $reviewId = (int)$reviewId;

        $sql = "SELECT product_id, username, rating, comment 
                FROM reviews 
                WHERE id = $reviewId";

        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    /**
     * Updates an existing review.
     *
     * @param int $reviewId The ID of the review to update.
     * @param int $productId The new ID of the product.
     * @param string $username The new username.
     * @param string $rating The new rating.
     * @param string $comment The new comment.
     *
     * @return bool True on success, false on failure.
     */
    public function updateReview($reviewId, $productId, $username, $rating, $comment)
    {
        if (!is_numeric($reviewId) || !$productId || !$username || !$rating || !$comment) {
            return false;
        }

        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        // Sanitize input
        $productId = (int)$productId;
        $username = trim($username);
        $rating = (int)$rating;
        $comment = trim($comment);


        // Prepare SQL
        $sql = "UPDATE reviews 
                SET product_id = $productId, 
                    username = '$username', 
                    rating = $rating, 
                    comment = '$comment' 
                WHERE id = $reviewId";

        $result = $this->db->query($sql);

        return $result;
    }

    /**
     * Deletes a review.
     *
     * @param int $reviewId The ID of the review to delete.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteReview($reviewId)
    {
        if (!is_numeric($reviewId)) {
            return false;
        }

        $reviewId = (int)$reviewId;

        $sql = "DELETE FROM reviews WHERE id = $reviewId";

        $result = $this->db->query($sql);

        return $result;
    }
}

// Example Usage (assuming you have a database connection object $db)
//  -  Replace this with your actual database setup and connection.
//  -  The example assumes you have a table named 'reviews' with columns: id (INT, PRIMARY KEY), product_id (INT), username (VARCHAR), rating (INT), comment (TEXT)
//  -  You'll need to adapt this to your specific database structure.

//  $db = new PDO("mysql:host=localhost;dbname=your_database", "your_user", "your_password");

// $review = new UserReview($db);

// // Create a review
// $reviewId = $review->createReview(1, "JohnDoe", 4, "Great product!");

// if ($reviewId) {
//     echo "Review created with ID: " . $reviewId . "<br>";

//     // Get the review
//     $review = $review->getReview($reviewId);
//     if ($review) {
//         echo "Review details: " . json_encode($review);
//     } else {
//         echo "Review not found.";
//     }

//     // Update the review
//     $updateResult = $review->updateReview($reviewId, 2, "JaneSmith", 5, "Excellent!");
//     if ($updateResult) {
//         echo "<br>Review updated successfully.";
//     } else {
//         echo "<br>Failed to update review.";
//     }

//     // Delete the review
//     $deleteResult = $review->deleteReview($reviewId);
//     if ($deleteResult) {
//         echo "<br>Review deleted successfully.";
//     } else {
//         echo "<br>Failed to delete review.";
//     }

// } else {
//     echo "Failed to create review.";
// }
?>
