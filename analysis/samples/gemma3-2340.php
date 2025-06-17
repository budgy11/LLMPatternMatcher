

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or service.
 *
 * @param string $productName - The name of the product or service being reviewed.
 * @param string $userId - The ID of the user writing the review.  Can be null for anonymous reviews.
 * @param string $reviewText - The text of the review.
 * @param int $rating - The rating given by the user (e.g., 1-5 stars).
 * @param PDO $db - A PDO database connection object.
 *
 * @return array - An array containing the review data or an error message.
 */
function create_review(string $productName, string $userId, string $reviewText, int $rating, PDO $db) {
    // Input Validation (Basic - Expand as needed)
    if (empty($productName)) {
        return ['error' => 'Product name cannot be empty.'];
    }
    if (empty($reviewText)) {
        return ['error' => 'Review text cannot be empty.'];
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be a number between 1 and 5.'];
    }

    // SQL query -  Protect against SQL injection!  Use prepared statements.
    $sql = "INSERT INTO reviews (product_name, user_id, review_text, rating)
            VALUES (:product_name, :user_id, :review_text, :rating)";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':product_name', $productName);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':review_text', $reviewText);
        $stmt->bindParam(':rating', $rating);

        $result = $stmt->execute();

        if ($result) {
            return ['success' => 'Review created successfully.'];
        } else {
            return ['error' => 'Failed to create review. Database error.'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Database error: ' . $e->getMessage()];
    }
}


/**
 * Function to display all reviews for a product.
 *
 * @param string $productName - The name of the product to retrieve reviews for.
 * @param PDO $db - A PDO database connection object.
 *
 * @return array - An array containing the review data.
 */
function get_reviews(string $productName, PDO $db) {
    $sql = "SELECT * FROM reviews WHERE product_name = :product_name";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':product_name', $productName);
        $stmt->execute();

        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $reviews;
    } catch (PDOException $e) {
        return ['error' => 'Database error: ' . $e->getMessage()];
    }
}



// --- Example Usage (For demonstration -  replace with your actual database setup) ---

// **IMPORTANT: Replace these with your database connection details!**
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}



// Example of creating a review:
$review_data = create_review('Awesome Product', 'user123', 'This is a fantastic product!', 5, $db);
print_r($review_data); // Output the result of the create_review function.
// Example: Displaying reviews for a product
$product_name = 'Awesome Product';
$reviews = get_reviews($product_name, $db);
print_r($reviews); // Display the reviews.

// ---  End of Example Usage ---



// ---  Database Table Structure (Example)  ---
/*
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    user_id VARCHAR(255) NULL,  // Can be NULL for anonymous reviews
    review_text TEXT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5)
);
*/


<?php

/**
 * Class UserReview
 *
 * Allows users to submit and view reviews for a product or service.
 */
class UserReview {

    private $reviews = [];

    /**
     * Adds a new review.
     *
     * @param string $user  The name of the user who wrote the review.
     * @param string $rating The rating given by the user (e.g., 1-5 stars).
     * @param string $comment The user's review text.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview($user, $rating, $comment) {
        if (!is_string($user) || !is_string($comment)) {
            return false; // Invalid input
        }

        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false; // Invalid rating
        }

        $this->reviews[] = [
            'user' => $user,
            'rating' => $rating,
            'comment' => $comment
        ];
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
     * Retrieves reviews for a specific product or service (placeholder - extend as needed)
     *
     * @param string $productName (Optional)  Filter by product name
     * @return array An array of review objects filtered by product name.  Returns all reviews if no filter is specified.
     */
    public function getReviewsByProduct($productName = '') {
        if (empty($productName)) {
            return $this->getAllReviews();
        }

        $filteredReviews = [];
        foreach ($this->getAllReviews() as $review) {
            // Add your filtering logic here based on $productName.
            // For example, if you have a field like 'product_id' in your reviews:
            // if ($review['product_id'] === $productName) {
            //    $filteredReviews[] = $review;
            // }
        }
        return $filteredReviews;
    }

    /**
     *  Gets the average rating.
     *  
     *  @return float|null The average rating, or null if there are no reviews.
     */
    public function getAverageRating() {
        if (empty($this->reviews)) {
            return null;
        }

        $totalRating = 0;
        foreach ($this->reviews as $review) {
            $totalRating += $review['rating'];
        }

        return $totalRating / count($this->reviews);
    }

}

// Example Usage:

// Create a UserReview object
$reviewSystem = new UserReview();

// Add some reviews
$reviewSystem->addReview('John Doe', 4, 'Great product, highly recommend!');
$reviewSystem->addReview('Jane Smith', 5, 'Excellent service and fast delivery.');
$reviewSystem->addReview('Peter Jones', 3, 'It was okay, could be better.');

// Get all reviews
$allReviews = $reviewSystem->getAllReviews();
echo "<h2>All Reviews:</h2>";
foreach ($allReviews as $review) {
    echo "<strong>User:</strong> " . $review['user'] . "<br>";
    echo "<strong>Rating:</strong> " . $review['rating'] . "<br>";
    echo "<strong>Comment:</strong> " . $review['comment'] . "<br><br>";
}

// Get average rating
$averageRating = $reviewSystem->getAverageRating();
echo "<h2>Average Rating:</h2>";
if ($averageRating !== null) {
    echo "Average Rating: " . $averageRating . "<br>";
} else {
    echo "No reviews yet.";
}

// Get reviews for a specific product (example - replace 'Product A' with the actual product name)
$productReviews = $reviewSystem->getReviewsByProduct('Product A'); // This will return an empty array in this example.
echo "<h2>Reviews for Product A:</h2>";
if (!empty($productReviews)) {
    foreach ($productReviews as $review) {
        echo "<strong>User:</strong> " . $review['user'] . "<br>";
        echo "<strong>Rating:</strong> " . $review['rating'] . "<br>";
        echo "<strong>Comment:</strong> " . $review['comment'] . "<br><br>";
    }
} else {
    echo "No reviews found for Product A.";
}

?>
