

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 * It includes basic data validation and sanitization.
 *
 * @param string $product_id The unique identifier of the product/item.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The review text submitted by the user.
 * @param int   $rating      (Optional) The rating given by the user (1-5).  Defaults to 0.
 *
 * @return bool|string Returns true on successful saving, or an error message string if saving fails.
 */
function save_user_review(string $product_id, string $user_name, string $review_text, int $rating = 0) {
    // **Data Validation & Sanitization** - Crucial for security and data integrity

    // Check if the product ID is valid
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $product_id)) {
        return "Invalid product ID.  Must contain only letters, numbers, underscores, and hyphens.";
    }

    // Sanitize the user name and review text to prevent XSS attacks
    $user_name = htmlspecialchars($user_name);
    $review_text = htmlspecialchars($review_text);

    // Sanitize the rating
    $rating = filter_var($rating, FILTER_VALIDATE_INT, array("min" => 1, "max" => 5));
    if ($rating === false) {
        return "Invalid rating. Please enter a number between 1 and 5.";
    }


    // **Database Interaction (Example - Adapt to your DB setup)**
    // This is a placeholder.  Replace with your actual database connection and query logic.
    try {
        // Connect to your database (using PDO is recommended)
        // Example:
        // $db = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");

        // Prepare the SQL query
        $sql = "INSERT INTO reviews (product_id, user_name, review_text, rating) VALUES (:product_id, :user_name, :review_text, :rating)";

        // Prepare the statement
        $stmt = $db->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':review_text', $review_text);
        $stmt->bindParam(':rating', $rating);

        // Execute the query
        $stmt->execute();

        return true;  // Success!

    } catch (PDOException $e) {
        // Handle database errors
        return "Error saving review: " . $e->getMessage();
    }
}


/**
 * Function to display user reviews for a given product.
 *
 * @param string $product_id The unique identifier of the product.
 * @return array|string Returns an array of review data if successful, or an error message.
 */
function display_user_reviews(string $product_id) {
    // **Database Interaction (Example - Adapt to your DB setup)**
    // This is a placeholder.  Replace with your actual database connection and query logic.

    try {
        // Connect to your database
        // Example:
        // $db = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");

        // Prepare the SQL query
        $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $reviews;

    } catch (PDOException $e) {
        return "Error retrieving reviews: " . $e->getMessage();
    }
}


// **Example Usage (Illustrative - Replace with your actual code)**
//  For demonstration purposes only.  Don't run this directly without a database setup.

// Create a dummy database connection (replace with your actual connection)
// $db = new PDO("mysql:host=localhost;dbname=test_db", "user", "password");  // Example connection

//  Save a review
$review_result = save_user_review("product123", "John Doe", "This is a fantastic product!");
if ($review_result == true) {
    echo "Review saved successfully!<br>";
} else {
    echo "Error saving review: " . $review_result . "<br>";
}

// Retrieve and display reviews
$reviews = display_user_reviews("product123");

if ($reviews) {
    echo "<h3>Reviews for Product 123:</h3>";
    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li><b>User:</b> " . $review['user_name'] . "<br>";
        echo "<b>Rating:</b> " . $review['rating'] . "<br>";
        echo "<b>Review:</b> " . $review['review_text'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "No reviews found for product 123.";
}


?>
