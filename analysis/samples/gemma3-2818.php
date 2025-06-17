

// Example using PDO (requires PDO and database connection)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling

    $username = "JohnDoe";
    $rating = 5;
    $comment = "Great product!";

    $stmt = $pdo->prepare("INSERT INTO reviews (username, rating, comment, date) VALUES (:username, :rating, :comment, :date)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);
    $stmt->bindParam(':date', date('Y-m-d H:i:s'));

    $stmt->execute();

    echo "Review submitted successfully!";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


<?php

/**
 * Class Review
 *
 * This class provides a basic framework for handling user reviews.
 * It includes methods for adding, retrieving, and displaying reviews.
 */
class Review {

    private $reviews = [];

    /**
     * Adds a new review to the database (simulated).
     *
     * @param string $username  The username of the reviewer.
     * @param string $rating   The rating (e.g., 1-5).
     * @param string $comment The reviewer's comment.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $username, string $rating, string $comment) {
        // Basic validation - add more robust validation as needed
        if (empty($username) || empty($rating) || empty($comment)) {
            return false;
        }

        $review = [
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'timestamp' => time()
        ];

        $this->reviews[] = $review;
        return true;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews() {
        return $this->reviews;
    }

    /**
     * Retrieves reviews by username.
     *
     * @param string $username The username to filter by.
     * @return array An array of review objects for the given username.
     */
    public function getReviewsByUsername(string $username) {
        $reviews = [];
        foreach ($this->getAllReviews() as $review) {
            if ($review['username'] === $username) {
                $reviews[] = $review;
            }
        }
        return $reviews;
    }

    /**
     * Displays all reviews in a formatted way.
     *
     * @return string  A string containing the formatted review display.
     */
    public function displayReviews() {
        $output = "<h2>User Reviews</h2>";
        if (empty($this->getAllReviews())) {
            $output .= "<p>No reviews yet.</p>";
        } else {
            $output .= "<ul>";
            foreach ($this->getAllReviews() as $review) {
                $output .= "<li><strong>Username:</strong> " . htmlspecialchars($review['username']) . "<br>";
                $output .= "<em>Rating:</em> " . htmlspecialchars($review['rating']) . "<br>";
                $output .= "<em>Comment:</em> " . htmlspecialchars($review['comment']) . "<br>";
                $output .= "<em>Timestamp:</em> " . date('Y-m-d H:i:s', $review['timestamp']) . "</li>";
            }
            $output .= "</ul>";
        }
        return $output;
    }
}

// Example Usage:
$reviewSystem = new Review();

// Add some reviews
$reviewSystem->addReview("JohnDoe", 5, "Great product!  Highly recommended.");
$reviewSystem->addReview("JaneSmith", 4, "Good value for the price.");
$reviewSystem->addReview("PeterJones", 3, "It's okay, but could be better.");

// Display all reviews
echo $reviewSystem->displayReviews();

// Get reviews by username
$johnReviews = $reviewSystem->getReviewsByUsername("JohnDoe");
echo "<h2>Reviews by JohnDoe</h2>";
echo "<pre>";
print_r($johnReviews);
echo "</pre>";
?>
