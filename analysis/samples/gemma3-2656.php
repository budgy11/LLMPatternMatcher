

<?php

/**
 * User Review Function
 *
 * This function handles the creation and display of user reviews.
 * It includes basic validation, saving to a database (optional), and
 * displaying the reviews.
 */

class UserReview {

    private $dbConnection; // Database connection object (optional)

    public function __construct($dbConnection = null) {
        $this->dbConnection = $dbConnection;
    }

    /**
     * Saves a new user review.
     *
     * @param string $username  The username of the reviewer.
     * @param string $rating   The rating (e.g., 1-5).
     * @param string $comment  The user's review comment.
     *
     * @return bool True on success, false on failure.
     */
    public function saveReview($username, $rating, $comment) {
        // Basic validation
        if (empty($username) || empty($rating) || empty($comment)) {
            error_log("Invalid review data: username, rating, and comment are required."); // Log error (better than just returning false)
            return false;
        }

        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            error_log("Invalid rating. Rating must be a number between 1 and 5.");
            return false;
        }

        // Database saving (optional - adjust as needed)
        if ($this->dbConnection) {
            $sql = "INSERT INTO reviews (username, rating, comment) VALUES (?, ?, ?)";
            $stmt = $this->dbConnection->prepare($sql);
            $stmt->bind_param("sii", $username, $rating, $comment); // 's' for string, 'i' for integer
            $result = $stmt->execute();

            if ($result) {
                $stmt->close();
                return true;
            } else {
                error_log("Error saving review to database: " . $this->dbConnection->error); // Log database error
                $stmt->close();
                return false;
            }
        } else {
            // No database connection, simply return true (or handle differently)
            // This can be useful for testing or simple scenarios.
            return true; //  Or you could store in a file, etc.
        }
    }

    /**
     * Retrieves all user reviews.
     *
     * @return array An array of review objects.
     */
    public function getReviews() {
        // In a real application, this would query the database.
        // This is a placeholder for demonstration.
        $reviews = [
            ['username' => 'JohnDoe', 'rating' => 4, 'comment' => 'Great product!'],
            ['username' => 'JaneSmith', 'rating' => 5, 'comment' => 'Excellent value for money.'],
            ['username' => 'PeterJones', 'rating' => 3, 'comment' => 'It was okay, nothing special.'],
        ];
        return $reviews;
    }

    /**
     * Displays a list of user reviews.
     */
    public function displayReviews() {
        echo "<h2>User Reviews</h2>";
        foreach ($this->getReviews() as $review) {
            echo "<p><strong>Username:</strong> " . htmlspecialchars($review['username']) . "</p>";
            echo "<p><strong>Rating:</strong> " . $review['rating'] . "</p>";
            echo "<p><strong>Comment:</strong> " . htmlspecialchars($review['comment']) . "</p>";
            echo "<hr>";
        }
    }
}


// Example Usage:
// Assuming you have a database connection object named $dbConnection

// Create a new review object
$review = new UserReview($dbConnection); // Pass your DB connection

// Save a new review
if ($review->saveReview('TestUser', 5, 'This is a test review!')) {
    echo "<p>Review saved successfully!</p>";
} else {
    echo "<p>Error saving review.</p>";
}

// Display all reviews
$review->displayReviews();

?>
