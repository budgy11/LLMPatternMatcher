

<?php

/**
 * User Review Function
 *
 * This function handles adding, retrieving, and displaying user reviews.
 * It includes basic validation and error handling.
 */
class UserReview
{
    private $db; // Database connection object (replace with your actual DB connection)

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Adds a new user review to the database.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $username The username of the reviewer.
     * @param string $rating The rating (e.g., 1-5).
     * @param string $comment The user's review comment.
     *
     * @return bool True on success, false on failure.
     */
    public function addReview($productId, $username, $rating, $comment)
    {
        // Validate inputs - Add more robust validation as needed
        if (!$productId || !$username || !$rating || !$comment) {
            return false;
        }
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        // Sanitize inputs (important to prevent SQL injection)
        $productId = (int)$productId; // Ensure productId is an integer
        $username = trim($username);
        $rating = (int)$rating;
        $comment = trim($comment);


        // Prepare SQL query (using prepared statements - VERY IMPORTANT)
        $sql = "INSERT INTO reviews (product_id, username, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql); //Prepare the statement

        if (!$stmt) {
            return false; //Handle error
        }

        // Bind parameters (prevent SQL injection)
        $stmt->bind_param("sii", $username, $rating, $comment);

        // Execute the query
        if (!$stmt->execute()) {
            error_log("Error adding review: " . $stmt->error); //Log the error for debugging
            return false;
        }

        // Close statement
        $stmt->close();

        return true;
    }

    /**
     * Retrieves all reviews for a specific product.
     *
     * @param int $productId The ID of the product.
     *
     * @return array An array of review objects, or an empty array if no reviews are found.
     */
    public function getReviewsByProduct($productId)
    {
        if (!is_numeric($productId)) {
            return [];
        }

        $productId = (int)$productId;

        $sql = "SELECT * FROM reviews WHERE product_id = ?";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $productId);

        if (!$stmt->execute()) {
            error_log("Error fetching reviews: " . $stmt->error);
            return [];
        }

        $result = $stmt->get_result();
        $reviews = [];

        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }

        $stmt->close();
        return $reviews;
    }

    /**
     * Displays reviews for a product.
     *
     * @param array $reviews An array of review objects.
     *
     * @return string HTML representation of the reviews.
     */
    public function displayReviews(array $reviews)
    {
        if (empty($reviews)) {
            return "<p>No reviews yet.</p>";
        }

        $html = "<ul>";
        foreach ($reviews as $review) {
            $html .= "<li>";
            $html .= "<strong>" . $review['username'] . "</strong> - Rating: " . $review['rating'] . " - " . $review['comment'];
            $html .= "</li>";
        }
        $html .= "</ul>";
        return $html;
    }
}


// Example Usage (replace with your database connection)
// Assuming you have a database connection object called $db

// Create an instance of the UserReview class
$reviewManager = new UserReview($db);

// Add a review
if ($reviewManager->addReview(123, "JohnDoe", 4, "Great product!")) {
    echo "Review added successfully!";
} else {
    echo "Error adding review.";
}

// Get reviews for product ID 123
$reviews = $reviewManager->getReviewsByProduct(123);

// Display the reviews
echo $reviewManager->displayReviews($reviews);

?>
