

<?php

/**
 * User Review Function
 *
 * This function allows you to submit and retrieve user reviews.
 * It includes basic validation and database interaction (assuming a database is set up).
 */

class ReviewManager {

    private $db; // Database connection

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Submits a new user review.
     *
     * @param string $username The username of the reviewer.
     * @param string $rating  The rating (e.g., 1-5).
     * @param string $comment The review text.
     *
     * @return bool True if the review was submitted successfully, false otherwise.
     */
    public function submitReview($username, $rating, $comment) {
        // Input validation - Important for security and data integrity
        if (empty($username) || empty($rating) || empty($comment)) {
            return false;
        }

        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        // Prepare the SQL statement (using prepared statements to prevent SQL injection)
        $sql = "INSERT INTO reviews (username, rating, comment) VALUES (?, ?, ?)";

        // Execute the query with the parameters
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sii", $username, $rating, $comment);
        $stmt->execute();

        return ($stmt->affected_rows > 0); // Check if the insert was successful
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews() {
        $reviews = [];
        $sql = "SELECT id, username, rating, comment, created_at FROM reviews";
        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $reviews[] = $row;
            }
        }

        return $reviews;
    }

    /**
     * Retrieves a single review by ID.
     *
     * @param int $id The ID of the review to retrieve.
     *
     * @return array|null An array representing the review, or null if not found.
     */
    public function getReviewById($id) {
        $sql = "SELECT id, username, rating, comment, created_at FROM reviews WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->num_rows > 0) {
            return $result = $stmt->fetch_assoc();
        }

        return null;
    }
}


// --- Example Usage (assuming you have a database connection called $db) ---
//  (You'll need to replace this with your actual database connection details)

// Sample Database Connection - Replace with your actual connection
// $db = new mysqli("localhost", "username", "password", "database_name");
// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }



// Create a ReviewManager instance
$reviewManager = new ReviewManager($db);

// Submit a review
if ($reviewManager->submitReview("JohnDoe", 4, "Great product!")) {
    echo "Review submitted successfully!";
} else {
    echo "Error submitting review.";
}

// Get all reviews
$allReviews = $reviewManager->getAllReviews();
echo "<br><h2>All Reviews:</h2>";
foreach ($allReviews as $review) {
    echo "<p><strong>Username:</strong> " . $review['username'] . "</p>";
    echo "<p><strong>Rating:</strong> " . $review['rating'] . "</p>";
    echo "<p><strong>Comment:</strong> " . $review['comment'] . "</p>";
    echo "<br>";
}

// Get a single review
$review = $reviewManager->getReviewById(1);

if ($review) {
    echo "<h2>Review ID 1:</h2>";
    echo "<p><strong>Username:</strong> " . $review['username'] . "</p>";
    echo "<p><strong>Rating:</strong> " . $review['rating'] . "</p>";
    echo "<p><strong>Comment:</strong> " . $review['comment'] . "</p>";
} else {
    echo "<p>Review not found.</p>";
}


// Close the database connection (Important!)
$db->close();
?>
