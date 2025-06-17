

<?php

/**
 * Reviews class for managing user reviews.
 */
class Review {

    private $reviews = [];
    private $db_connection = null; // For database interaction (optional)

    /**
     * Constructor: Initializes the review system.
     *
     * @param mysqli $db_connection  (Optional) Database connection object.
     */
    public function __construct($db_connection = null) {
        $this->db_connection = $db_connection; // Assign the db connection
    }

    /**
     * Adds a new review.
     *
     * @param string $username       The username of the reviewer.
     * @param string $review_text    The text of the review.
     * @param int   $rating         The rating (e.g., 1-5).
     * @return int|false  The ID of the newly created review, or false on failure.
     */
    public function addReview(string $username, string $review_text, int $rating) {
        if (!$this->db_connection) {
            // If no database connection, store review in-memory
            $review_id = count($this->reviews) + 1;
            $this->reviews[$review_id] = [
                'username' => $username,
                'review_text' => $review_text,
                'rating' => $rating,
                'date' => date('Y-m-d H:i:s') // Timestamp for review creation
            ];
            return $review_id;
        } else {
            // Database interaction
            $sql = "INSERT INTO reviews (username, review_text, rating, created_at) VALUES (?, ?, ?, ?)";
            $stmt = $this->db_connection->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sss", $username, $review_text, $rating);
                if ($stmt->execute()) {
                    $review_id = $this->db_connection->insert_id; // Get the auto-incremented ID
                    $stmt->close();
                    return $review_id;
                } else {
                    $error_message = $this->db_connection->error;
                    $stmt->close();
                    error_log("Error adding review: " . $error_message);  // Log the error
                    return false;
                }
            } else {
                error_log("Error preparing database statement for adding review");
                return false;
            }
        }
    }



    /**
     * Retrieves all reviews.
     *
     * @return array  An array of review objects.
     */
    public function getAllReviews() {
        if($this->db_connection) {
            $results = $this->db_connection->query("SELECT * FROM reviews");
        } else {
            $results = $this->reviews;
        }
        $review_objects = [];
        if($results) {
            while ($row = $results->fetch_assoc()) {
                $review_objects[] = $this->convertRowToReviewObject($row);
            }
        }
        return $review_objects;
    }


    /**
     * Converts a database row to a Review object.
     *
     * @param array $row  A database row.
     * @return Review  A Review object.
     */
    private function convertRowToReviewObject(array $row) {
        return new Review([
            'id' => $row['id'],
            'username' => $row['username'],
            'review_text' => $row['review_text'],
            'rating' => $row['rating'],
            'date' => $row['created_at']
        ]);
    }



    /**
     * Retrieves a specific review by its ID.
     *
     * @param int $id The ID of the review.
     * @return Review|false A Review object, or false if not found.
     */
    public function getReviewById(int $id) {
        if($this->db_connection) {
            $result = $this->db_connection->query("SELECT * FROM reviews WHERE id = ?");
            if ($result && $result->fetch_assoc()) {
                return $this->convertRowToReviewObject($result->fetch_assoc());
            } else {
                return false;
            }
        } else {
            // In-memory retrieval
            if (isset($this->reviews[$id])) {
                return $this->reviews[$id];
            } else {
                return false;
            }
        }
    }


    /**
     * Updates an existing review.
     * @param int $id The ID of the review to update.
     * @param string $new_review_text The new text of the review.
     * @param int   $new_rating     The new rating.
     * @return bool True if the update was successful, false otherwise.
     */
    public function updateReview(int $id, string $new_review_text, int $new_rating) {
        if($this->db_connection) {
            $sql = "UPDATE reviews SET review_text = ?, rating = ?, created_at = NOW() WHERE id = ?";
            $stmt = $this->db_connection->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sss", $new_review_text, $new_rating, $id);
                if ($stmt->execute()) {
                    $stmt->close();
                    return true;
                } else {
                    $error_message = $this->db_connection->error;
                    $stmt->close();
                    error_log("Error updating review: " . $error_message);
                    return false;
                }
            } else {
                error_log("Error preparing database statement for updating review");
                return false;
            }
        } else {
            // In-memory update (if no database connection)
            if (isset($this->reviews[$id])) {
                $this->reviews[$id] = [
                    'username' => $this->reviews[$id]['username'], // Keep username
                    'review_text' => $new_review_text,
                    'rating' => $new_rating,
                    'date' => date('Y-m-d H:i:s')
                ];
                return true;
            } else {
                return false;
            }
        }
    }


    /**
     * Deletes a review.
     *
     * @param int $id The ID of the review to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function deleteReview(int $id) {
        if($this->db_connection) {
            $sql = "DELETE FROM reviews WHERE id = ?";
            $stmt = $this->db_connection->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $stmt->close();
                    return true;
                } else {
                    $error_message = $this->db_connection->error;
                    $stmt->close();
                    error_log("Error deleting review: " . $error_message);
                    return false;
                }
            } else {
                error_log("Error preparing database statement for deleting review");
                return false;
            }
        } else {
            // In-memory deletion
            if (isset($this->reviews[$id])) {
                unset($this->reviews[$id]);
                return true;
            } else {
                return false;
            }
        }
    }
}


// Example Usage (Illustrative - Requires database setup)
// $db = new mysqli("localhost", "username", "password", "database_name");

// if ($db->connect_error) {
//   die("Connection failed: " . $db->connect_error);
// }

// $review = new Review($db);

// // Add a review
// $review_id = $review->addReview("John Doe", "This is a great product!", 5);
// if ($review_id) {
//     echo "Review added with ID: " . $review_id . "
";
// }

// // Get all reviews
// $all_reviews = $review->getAllReviews();
// echo "All Reviews:
";
// foreach ($all_reviews as $review) {
//     echo "- Username: " . $review['username'] . ", Rating: " . $review['rating'] . ", Review: " . $review['review_text'] . "
";
// }

// // Get a specific review
// $specific_review = $review->getReviewById($review_id);
// if ($specific_review) {
//     echo "
Specific Review (ID " . $review_id . "): " . json_encode($specific_review) . "
";
// }

// //Update a review
// $update_success = $review->updateReview($review_id, "Updated Review Text", 4);
// if ($update_success) {
//     echo "
Review updated successfully
";
// }

// //Delete a review
// $delete_success = $review->deleteReview($review_id);
// if ($delete_success) {
//     echo "
Review deleted successfully
";
// }

?>
