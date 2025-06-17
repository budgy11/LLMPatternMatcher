

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or item.
 *
 * @param string $productName The name of the product or item being reviewed.
 * @param string $reviewText The review text submitted by the user.
 * @param string $username (Optional) The username of the user submitting the review.  Defaults to 'Anonymous'.
 * @param int $rating (Optional) The rating provided by the user (1-5). Defaults to 0.
 *
 * @return array An array containing the review data, or an error message if something goes wrong.
 */
function submit_review(string $productName, string $reviewText, string $username = 'Anonymous', int $rating = 0) {

    // Input Validation - Add more robust validation as needed
    if (empty($productName)) {
        return ['error' => 'Product name cannot be empty.'];
    }
    if (empty($reviewText)) {
        return ['error' => 'Review text cannot be empty.'];
    }
    if ($rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be between 1 and 5.'];
    }


    // Store the review data (This would typically be saved to a database)
    // For this example, we'll just store it in an array.
    $review = [
        'product_name' => $productName,
        'review_text' => $reviewText,
        'username' => $username,
        'rating' => $rating,
        'timestamp' => time() // Add a timestamp for tracking
    ];

    // You would typically save this array to a database here.
    // Example: $db->insert('reviews', $review);


    return $review;
}


/**
 * Display User Reviews
 *
 * This function retrieves and displays user reviews for a given product.
 *
 * @param string $productName The name of the product to retrieve reviews for.
 * @param array $reviews (Optional) An array of review data to display. If not provided,
 *                       it will retrieve reviews from a (simulated) database.
 *
 * @return void Displays the reviews on the screen.
 */
function display_reviews(string $productName, array $reviews = []) {
    echo "<h2>Reviews for " . $productName . "</h2>";

    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
        return;
    }

    echo "<ul>";
    foreach ($reviews as $review) {
        if (array_key_exists('error', $review)) { //Handle errors (from submit_review)
            echo "<li>Error: " . $review['error'] . "</li>";
        } else {
            echo "<li>";
            echo "<p><strong>" . $review['username'] . ":</strong> " . $review['review_text'] . "</p>";
            echo "<p>Rating: " . $review['rating'] . "</p>";
            echo "<small>Submitted on: " . date('Y-m-d H:i:s', $review['timestamp']) . "</small>";
            echo "</li>";
        }
    }
    echo "</ul>";
}



// Example Usage:

// Submit a review
$review_data = submit_review('Awesome T-Shirt', 'This is a great shirt!', 'JohnDoe', 5);

if (array_key_exists('error', $review_data)) {
    echo "<p>Error submitting review: " . $review_data['error'] . "</p>";
} else {
    echo "<h3>Review Submitted!</h3>";
    print_r($review_data); // Display the review data (for testing)
}


// Simulate some existing reviews (for demonstration)
$existing_reviews = [
    ['product_name' => 'Awesome T-Shirt', 'review_text' => 'Love this shirt!', 'username' => 'JaneSmith', 'rating' => 4, 'timestamp' => time() - 3600],
    ['product_name' => 'Awesome T-Shirt', 'review_text' => 'Good quality', 'username' => 'MikeJones', 'rating' => 3, 'timestamp' => time() - 7200],
    ['product_name' => 'Basic Mug', 'review_text' => 'Nice mug!', 'username' => 'AliceBrown', 'rating' => 5, 'timestamp' => time() - 1800]
];

// Display the reviews
display_reviews('Awesome T-Shirt', $existing_reviews);


<?php

/**
 * User Review Function
 * 
 * This function allows you to store and display user reviews for a given item.
 *
 * @param string $itemId The unique identifier of the item being reviewed.
 * @param string $userName The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $rating The rating (e.g., 1-5).
 * @param array $dbConnection  A database connection object.
 * @return array  An array containing:
 *               - 'success' => true if the review was saved successfully, false otherwise.
 *               - 'message' => A message describing the result (e.g., "Review saved!", "Error saving review").
 */
function saveUserReview(string $itemId, string $userName, string $reviewText, int $rating, PDO $dbConnection) {
    // Input validation - Important!  Don't allow arbitrary input!
    if (empty($itemId) || empty($userName) || empty($reviewText) || $rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Invalid input.  Please ensure all fields are filled and the rating is between 1 and 5.'];
    }

    try {
        // Prepare the SQL statement - use prepared statements to prevent SQL injection
        $sql = "INSERT INTO reviews (item_id, user_name, review_text, rating)
                VALUES (:item_id, :user_name, :review_text, :rating)";

        $stmt = $dbConnection->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':item_id', $itemId);
        $stmt->bindParam(':user_name', $userName);
        $stmt->bindParam(':review_text', $reviewText);
        $stmt->bindParam(':rating', $rating);

        // Execute the statement
        $stmt->execute();

        return ['success' => true, 'message' => 'Review saved!'];

    } catch (PDOException $e) {
        // Handle database errors - Log the error!  Don't expose the raw error.
        error_log("Database error saving review: " . $e->getMessage()); // Log for debugging
        return ['success' => false, 'message' => 'Error saving review. Please try again later.'];
    }
}

/**
 * Retrieve Reviews for an Item
 *
 * This function retrieves all reviews for a given item from the database.
 *
 * @param string $itemId The unique identifier of the item.
 * @param PDO $dbConnection  A database connection object.
 * @return array An array containing:
 *               - 'reviews' => An array of review objects, or an empty array if no reviews exist.
 *               - 'success' => true if reviews were retrieved successfully, false otherwise.
 */
function getReviewsForItem(string $itemId, PDO $dbConnection) {
    try {
        $sql = "SELECT * FROM reviews WHERE item_id = :item_id";
        $stmt = $dbConnection->prepare($sql);
        $stmt->bindParam(':item_id', $itemId);
        $stmt->execute();

        $reviews = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = $row;
        }

        return ['reviews' => $reviews, 'success' => true];

    } catch (PDOException $e) {
        error_log("Database error retrieving reviews: " . $e->getMessage());
        return ['reviews' => [], 'success' => false, 'message' => 'Error retrieving reviews. Please try again later.'];
    }
}



// Example Usage (Illustrative - Replace with your actual database setup)

// Create a dummy database connection (for demonstration)
$db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');


// Save a review
$reviewResult = saveUserReview('product123', 'John Doe', 'Great product!', 5, $db);
print_r($reviewResult);
// Example: Retrieve Reviews for 'product123'
$reviews = getReviewsForItem('product123', $db);
print_r($reviews);


// Close the database connection (important!)
$db = null; // Disconnect the PDO object

?>
