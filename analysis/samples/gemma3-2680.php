

<?php

/**
 * Class Review
 *
 * Handles creating, retrieving, updating, and deleting reviews.
 */
class Review
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Creates a new review.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $user  The user who wrote the review.
     * @param string $comment The review text.
     * @param int $rating The rating (1-5).
     * @return int|false The ID of the new review on success, or false on failure.
     */
    public function createReview($productId, $user, $comment, $rating)
    {
        // Validate inputs -  Crucial for security!
        if (!$productId || !$user || !$comment || $rating < 1 || $rating > 5) {
            return false;
        }

        // Prepare the SQL statement to prevent SQL injection
        $sql = "INSERT INTO reviews (product_id, user, comment, rating) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("siii", $productId, $user, $comment, $rating);

        if ($stmt->execute()) {
            return $this->db->insert_id;
        } else {
            // Log the error - very important!
            error_log("Error creating review: " . $stmt->error);
            return false;
        }

        $stmt->close();
    }


    /**
     * Retrieves a review by ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|null An associative array containing the review data, or null if not found.
     */
    public function getReviewById($reviewId)
    {
        $sql = "SELECT * FROM reviews WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $reviewId);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                return null;
            }
        } else {
            error_log("Error retrieving review: " . $stmt->error);
            return null;
        }

        $stmt->close();
    }



    /**
     * Updates a review by ID.
     *
     * @param int $reviewId The ID of the review to update.
     * @param string $user  The new user (optional, if unchanged).
     * @param string $comment The new review text (optional, if unchanged).
     * @param int $rating The new rating (1-5).
     * @return bool True on success, false on failure.
     */
    public function updateReviewById($reviewId, $user = null, $comment = null, $rating = null)
    {
        $set_clause = "";
        $params = [];

        if ($user !== null) {
            $set_clause .= "user = ? , ";
            $params[] = $user;
        }
        if ($comment !== null) {
            $set_clause .= "comment = ? , ";
            $params[] = $comment;
        }
        if ($rating !== null) {
            $set_clause .= "rating = ? ";
            $params[] = $rating;
        }

        if (empty($set_clause)) {
            return false; // Nothing to update
        }

        $set_clause = substr($set_clause, 0, -2); // Remove trailing comma and space

        $sql = "UPDATE reviews SET $set_clause WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $reviewId);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error updating review: " . $stmt->error);
            return false;
        }

        $stmt->close();
    }



    /**
     * Deletes a review by ID.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteReviewById($reviewId)
    {
        $sql = "DELETE FROM reviews WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $reviewId);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error deleting review: " . $stmt->error);
            return false;
        }

        $stmt->close();
    }
}



// Example Usage (Assuming you have a database connection $db)
//  Replace this with your actual database connection details

// Mock database connection (for testing without a real database)
class MockDB
{
    public function prepare($sql) {
        // In a real implementation, this would return a prepared statement object
        return new MockStatement($sql);
    }

    public function execute($stmt) {
        // Simulate execution and return true/false based on the simulated outcome
        return true; // Or false if the 'execute' method failed
    }

    public function insert_id() {
        return 123; // Dummy ID for demonstration
    }
}


class MockStatement{
    private $sql;
    public function __construct($sql) {
        $this->sql = $sql;
    }

    public function bind_param($param_types, $values){
        //Simulate binding parameters - do nothing in this mock
    }

    public function execute() {
        // Simulate execution.  In reality, this would execute the query.
        return true;
    }

    public function get_result() {
        //Simulate a result set
        return new MockResultSet();
    }

    public function close(){
    }
}

class MockResultSet{
    public function num_rows() {
        return 0;
    }

    public function fetch_assoc(){
        return null;
    }
}


$db = new MockDB();

$review = new Review($db);

//Create a review
$newReviewId = $review->createReview(1, "John Doe", "Great product!", 5);
if ($newReviewId) {
    echo "New review created with ID: " . $newReviewId . "
";
} else {
    echo "Failed to create review.
";
}

// Get the review
$reviewData = $review->getReviewById($newReviewId);
if ($reviewData) {
    echo "Review details: " . print_r($reviewData, true) . "
";
} else {
    echo "Review not found.
";
}

//Update the review
$updateResult = $review->updateReviewById($newReviewId, "Jane Smith", "Excellent!", 4);
if ($updateResult) {
    echo "Review updated successfully.
";
} else {
    echo "Failed to update review.
";
}

//Delete the review
$deleteResult = $review->deleteReviewById($newReviewId);
if ($deleteResult) {
    echo "Review deleted successfully.
";
} else {
    echo "Failed to delete review.
";
}
?>
