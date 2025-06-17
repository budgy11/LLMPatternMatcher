

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews.
 * It includes features for saving reviews, retrieving reviews,
 * and displaying them.
 */

class UserReview {

    private $db_host = "localhost"; // Replace with your database connection details
    private $db_name = "your_database_name";
    private $db_user = "your_username";
    private $db_password = "your_password";

    public function __construct() {
        // Establish database connection
        $this->conn = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    /**
     * Saves a new user review.
     *
     * @param string $username   The username of the reviewer.
     * @param string $reviewText The text of the review.
     * @return bool True on success, false on failure.
     */
    public function saveReview($username, $reviewText) {
        $reviewText = $this->conn->real_escape_string($reviewText); // Sanitize input

        $sql = "INSERT INTO reviews (username, review_text) VALUES ('$username', '$reviewText')";

        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            echo "Error: " . $sql . "<br>" . $this->conn->error;
            return false;
        }
    }

    /**
     * Retrieves all user reviews.
     *
     * @return array An array of review objects.  Returns an empty array if no reviews are found.
     */
    public function getReviews() {
        $sql = "SELECT id, username, review_text, created_at FROM reviews ORDER BY created_at DESC";
        $result = $this->conn->query($sql);

        $reviews = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $reviews[] = new ReviewObject($row);  // Pass the row data to the ReviewObject
            }
        }
        return $reviews;
    }


    /**
     *  Review Object -  A simple class to represent a review.
     */
    private function __construct(){} //Prevent instantiation


    // Nested class -  Allows instantiation of the review object.
    private static $instance = null; // Singleton pattern.

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}


/**
 * Review Object -  A simple class to represent a review.
 */
class ReviewObject {
    public $id;
    public $username;
    public $review_text;
    public $created_at;

    public function __construct($row) {
        $this->id = $row["id"];
        $this->username = $row["username"];
        $this->review_text = $row["review_text"];
        $this->created_at = $row["created_at"];
    }
}



// Example usage (after creating the database table 'reviews')

// Create an instance of the Review class
$review = UserReview::getInstance();

// Save a review
if ($review->saveReview("JohnDoe", "This is a great product!")) {
    echo "Review saved successfully!";
} else {
    echo "Failed to save review.";
}

// Retrieve all reviews
$allReviews = $review->getReviews();

// Display reviews
echo "<br><br><h2>All Reviews:</h2>";
foreach ($allReviews as $review) {
    echo "<strong>Username:</strong> " . $review->username . "<br>";
    echo "<strong>Review:</strong> " . $review->review_text . "<br>";
    echo "<strong>Date:</strong> " . $review->created_at . "<br><br>";
}
?>
