

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a given product or item.
 *
 * @param string $productId The unique identifier for the product/item.
 * @param string $username The username of the user submitting the review.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The user's written review.
 * @param string $dbHost The database host.
 * @param string $dbName The database name.
 * @param string $dbUser The database username.
 * @param string $dbPassword The database password.
 * @return array|null  An array containing the review data on success, or null on error.
 */
function saveUserReview(
    string $productId,
    string $username,
    string $rating,
    string $comment,
    string $dbHost,
    string $dbName,
    string $dbUser,
    string $dbPassword
) {
    // Validate input - Important for security!
    if (empty($productId) || empty($username) || empty($rating) || empty($comment)) {
        error_log("Missing required fields in saveUserReview.");
        return null;
    }

    if (!is_numeric($rating) || (int)$rating < 1 || (int)$rating > 5) {
        error_log("Invalid rating provided: " . $rating);
        return null;
    }

    // Connect to the database
    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        return null;
    }

    // Prepare and execute the SQL statement
    try {
        $sql = "INSERT INTO reviews (productId, username, rating, comment) 
                VALUES (:productId, :username, :rating, :comment)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        $stmt->execute();

        return $pdo->lastInsertId(); // Return the ID of the newly inserted row
    } catch (PDOException $e) {
        error_log("Database query error: " . $e->getMessage());
        return null;
    } finally {
        // Close the database connection (important to release resources)
        $pdo = null;
    }
}


/**
 * Display User Reviews Function
 *
 * This function retrieves and displays user reviews for a given product.
 *
 * @param string $productId The unique identifier for the product.
 * @param string $dbHost The database host.
 * @param string $dbName The database name.
 * @param string $dbUser The database username.
 * @param string $dbPassword The database password.
 * @return array|null An array of review data on success, or null on error.
 */
function getReviews(string $productId, string $dbHost, string $dbName, string $dbUser, string $dbPassword) {
    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        return null;
    }

    $sql = "SELECT * FROM reviews WHERE productId = :productId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();

    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $reviews;
}



// Example Usage (replace with your actual database credentials)
$productId = "product123";
$dbHost = "localhost";
$dbName = "your_database_name";
$dbUser = "your_username";
$dbPassword = "your_password";

// Save a review
$reviewId = saveUserReview($productId, "john.doe", 4, "Great product, highly recommend!", $dbHost, $dbName, $dbUser, $dbPassword);

if ($reviewId) {
    echo "Review saved successfully! Review ID: " . $reviewId . "<br>";
} else {
    echo "Error saving review.";
}

// Retrieve and display reviews
$reviews = getReviews($productId, $dbHost, $dbName, $dbUser, $dbPassword);

if ($reviews) {
    echo "<h2>Reviews for " . $productId . ":</h2>";
    echo "<table>";
    echo "<tr><th>Username</th><th>Rating</th><th>Comment</th></tr>";
    foreach ($reviews as $review) {
        echo "<tr>";
        echo "<td>" . $review['username'] . "</td>";
        echo "<td>" . $review['rating'] . "</td>";
        echo "<td>" . $review['comment'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No reviews found or error retrieving reviews.";
}


?>
