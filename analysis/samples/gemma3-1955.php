

<?php

/**
 *  User Review Function
 *
 *  This function allows you to store and display user reviews for a given product.
 *
 *  @param string $product_id - The unique identifier of the product.
 *  @param string $user_name - The name of the user submitting the review.
 *  @param string $review_text - The text of the user's review.
 *  @param int $rating - The rating given by the user (e.g., 1-5).
 *  @return bool - True if the review was successfully saved, false otherwise.
 */
function save_user_review(string $product_id, string $user_name, string $review_text, int $rating)
{
    // **Important:** Replace this with your actual database connection code.
    // This is a placeholder for demonstration purposes.

    $db_host = 'localhost';
    $db_name = 'your_database_name';
    $db_user = 'your_database_user';
    $db_password = 'your_database_password';

    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage()); // Log the error
        return false;
    }

    // Sanitize inputs to prevent SQL injection
    $product_id = $pdo->quote($product_id);
    $user_name = $pdo->quote($user_name);
    $review_text = $pdo->quote($review_text);

    // Construct the SQL query
    $sql = "INSERT INTO reviews (product_id, user_name, review_text, rating)
            VALUES (:product_id, :user_name, :review_text, :rating)";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':user_name', $user_name);
    $stmt->bindParam(':review_text', $review_text);
    $stmt->bindParam(':rating', $rating);

    // Execute the query
    try {
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        error_log("Database query error: " . $e->getMessage()); // Log the error
        return false;
    }
}


/**
 *  Get User Reviews for a Product
 *
 *  This function retrieves all reviews for a given product.
 *
 *  @param string $product_id - The unique identifier of the product.
 *  @return array - An array of review objects, or an empty array if no reviews are found.
 */
function get_user_reviews(string $product_id)
{
    // **Important:** Replace this with your actual database query code.
    // This is a placeholder for demonstration purposes.

    $db_host = 'localhost';
    $db_name = 'your_database_name';
    $db_user = 'your_database_user';
    $db_password = 'your_database_password';

    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        return [];
    }

    // Construct the SQL query
    $sql = "SELECT * FROM reviews WHERE product_id = :product_id";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);

    // Execute the query
    $stmt->execute();

    // Fetch all the results as an array of objects
    $reviews = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $reviews;
}

/**
 * Displaying the Reviews (Example)
 */
function display_reviews(array $reviews) {
    echo "<h2>User Reviews for Product ID: " . $product_id . "</h2>";
    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
    } else {
        echo "<ul>";
        foreach ($reviews as $review) {
            echo "<li>";
            echo "<strong>" . $review->user_name . ":</strong> " . $review->review_text . " (Rating: " . $review->rating . ")";
            echo "</li>";
        }
        echo "</ul>";
    }
}


// Example Usage:

// Dummy data for demonstration.  Replace this with real data.
$product_id = "123";

// Save a review
$review_saved = save_user_review($product_id, "John Doe", "This product is amazing!", 5);
if ($review_saved) {
    echo "<p>Review saved successfully!</p>";
} else {
    echo "<p>Failed to save review.</p>";
}

// Get reviews
$reviews = get_user_reviews($product_id);
display_reviews($reviews);
?>
