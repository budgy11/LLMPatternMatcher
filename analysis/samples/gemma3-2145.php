

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It includes basic validation and demonstrates how to interact with a 
 * hypothetical database.  You'll need to adapt the database interaction part
 * to your specific database system (MySQL, PostgreSQL, etc.).
 */

class Review
{
    private $db; // Database connection

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Create a new review.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $user  The name of the user submitting the review.
     * @param string $comment The review text.
     * @return int|false  The ID of the new review if successful, false otherwise.
     */
    public function createReview(int $productId, string $user, string $comment)
    {
        // Input Validation - Basic
        if (!$productId || !$user || !$comment) {
            return false;
        }

        // Sanitize inputs (important for security - prevents SQL injection)
        $productId = $this->db->real_escape_string($productId);
        $user = $this->db->real_escape_string($user);
        $comment = $this->db->real_escape_string($comment);

        // SQL Query (Adapt to your database system)
        $query = "INSERT INTO reviews (product_id, user, comment) VALUES ('$productId', '$user', '$comment')";

        if ($this->db->query($query) === TRUE) {
            return $this->db->insert_id; // Returns the ID of the newly inserted row
        } else {
            echo "Error: " . $this->db->error . "<br>"; // Handle database errors
            return false;
        }
    }

    /**
     * Get a review by ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|false  An array containing review data if found, false otherwise.
     */
    public function getReview(int $reviewId)
    {
        $reviewId = $this->db->real_escape_string($reviewId);

        $query = "SELECT * FROM reviews WHERE id = '$reviewId'";
        $result = $this->db->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Update a review.
     *
     * @param int $reviewId The ID of the review to update.
     * @param string $newComment The new review text.
     * @return bool True on success, false on failure.
     */
    public function updateReview(int $reviewId, string $newComment)
    {
        $reviewId = $this->db->real_escape_string($reviewId);
        $newComment = $this->db->real_escape_string($newComment);

        $query = "UPDATE reviews SET comment = '$newComment' WHERE id = '$reviewId'";

        if ($this->db->query($query) === TRUE) {
            return true;
        } else {
            echo "Error: " . $this->db->error . "<br>";
            return false;
        }
    }

    /**
     * Delete a review.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteReview(int $reviewId)
    {
        $reviewId = $this->db->real_escape_string($reviewId);

        $query = "DELETE FROM reviews WHERE id = '$reviewId'";

        if ($this->db->query($query) === TRUE) {
            return true;
        } else {
            echo "Error: " . $this->db->error . "<br>";
            return false;
        }
    }
}

// Example Usage (This needs a database connection)
// Replace with your database connection details
$db = new mysqli("localhost", "username", "password", "database_name");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$reviewManager = new Review($db);

// Create a new review
$newReviewId = $reviewManager->createReview(1, "John Doe", "This is a great product!");

if ($newReviewId) {
    echo "New review created with ID: " . $newReviewId . "<br>";
}

// Get the review
$review = $reviewManager->getReview($newReviewId);
if ($review) {
    echo "Review: " . $review['comment'] . "<br>";
}

// Update the review
$reviewManager->updateReview($newReviewId, "Updated Review Comment");

// Delete the review
$reviewManager->deleteReview($newReviewId);

$db->close(); // Always close the database connection

?>
