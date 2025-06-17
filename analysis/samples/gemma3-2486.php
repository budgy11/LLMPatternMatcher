

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 * It includes basic validation and formatting for improved readability.
 */

class UserReview {

    private $productId;
    private $userId;
    private $rating;
    private $comment;
    private $reviewDate;

    /**
     * Constructor
     *
     * Initializes a new UserReview object.
     *
     * @param int $productId The ID of the product or item being reviewed.
     * @param int $userId The ID of the user submitting the review.
     * @param int $rating   The rating given by the user (e.g., 1-5 stars).
     * @param string $comment The user's comment/feedback.
     */
    public function __construct(int $productId, int $userId, int $rating, string $comment) {
        $this->productId = $productId;
        $this->userId = $userId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->reviewDate = date('Y-m-d H:i:s'); // Set review date to current timestamp
    }

    /**
     * Getters
     *
     * Provides methods to access the review's attributes.
     *
     * @return array An associative array containing the review's data.
     */
    public function getReviewData(): array {
        return [
            'product_id' => $this->productId,
            'user_id' => $this->userId,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'review_date' => $this->reviewDate,
        ];
    }

    /**
     * Format Review for Display
     *
     * Formats the review data for display in a user-friendly format.
     *
     * @return string  A formatted string representing the review.
     */
    public function formatReview(): string {
        return sprintf(
            "<b>Product ID:</b> %d<br>
             <b>User ID:</b> %d<br>
             <b>Rating:</b> %d stars<br>
             <b>Comment:</b> %s<br>
             <b>Review Date:</b> %s",
            $this->productId,
            $this->userId,
            $this->rating,
            $this->comment,
            $this->reviewDate
        );
    }
}

// --- Example Usage ---

// Create a new review
$review = new UserReview(123, 456, 5, "Excellent product!  Highly recommended.");

// Get the review data
$reviewData = $review->getReviewData();
print_r($reviewData);  // Output:  Associative array

// Format and display the review
echo $review->formatReview() . "<br>"; // Output:  Formatted string

// ---  Example of Saving to a Database (Illustrative - requires database setup) ---
// This section is a placeholder and needs adaptation for your specific database.

/*
// Example using mysqli (adjust for your database library)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL statement
$sql = "INSERT INTO reviews (product_id, user_id, rating, comment, review_date)
        VALUES (?, ?, ?, ?, ?)";

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("isss", $review->productId, $review->userId, $review->rating, $review->comment);

// Execute statement
if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
*/
?>
