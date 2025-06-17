

<?php

/**
 * User Review Function
 *
 * This function allows you to store and retrieve user reviews for a given product or item.
 * It demonstrates basic database interaction and validation.  For a production environment,
 * consider using a more robust framework and security measures.
 *
 * @param string $productId The ID of the product or item the review is for.
 * @param string $name       The user's name.
 * @param string $rating     The user's rating (e.g., 1-5).
 * @param string $comment    The user's review comment.
 * @param PDO       $db       A PDO database connection object.
 *
 * @return bool True on successful review creation, false otherwise.
 */
function createReview(string $productId, string $name, string $rating, string $comment, PDO $db)
{
    // Validate inputs - Crucial for security and data integrity
    if (empty($productId)) {
        error_log("Error: Product ID cannot be empty.");
        return false;
    }
    if (empty($name)) {
        error_log("Error: User name cannot be empty.");
        return false;
    }
    if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
        error_log("Error: User name must contain only letters and spaces.");
        return false;
    }
    if (!preg_match('/^[0-5][0-9]*$/', $rating)) {
        error_log("Error: Rating must be a number between 0 and 5.");
        return false;
    }
    if (empty($comment)) {
        error_log("Error: Comment cannot be empty.");
        return false;
    }
    if (!preg_match('/^[\x00-\x7F]+$/', $comment)) {
        error_log("Error: Comment must contain only ASCII characters.");
        return false;
    }

    // Prepare the SQL statement - Important for preventing SQL injection
    $sql = "INSERT INTO reviews (productId, userName, rating, comment)
            VALUES (:productId, :userName, :rating, :comment)";

    try {
        // Prepare the statement
        $stmt = $db->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':userName', $name);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        // Execute the statement
        $stmt->execute();

        return true; // Review created successfully

    } catch (PDOException $e) {
        // Handle database errors - Log them for debugging
        error_log("Database Error: " . $e->getMessage());
        return false; // Failed to create review
    }
}

/**
 * Retrieve Reviews for a Product
 *
 *  Retrieves all reviews for a given product.
 *
 * @param string $productId The ID of the product.
 * @param PDO       $db       A PDO database connection object.
 * @return array  An array of review objects, or an empty array if no reviews are found.
 */
function getReviews(string $productId, PDO $db) {
    $sql = "SELECT * FROM reviews WHERE productId = :productId";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();

    $reviews = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $reviews[] = $row;
    }

    return $reviews;
}


// Example Usage (Assuming you have a database connection called $db)
// This is just a demonstration - replace with your actual database details
// You need to set up your database connection and table before running this.
//
// $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

// Create a review
// $reviewCreated = createReview("123", "John Doe", 5, "Great product!", $db);
// if ($reviewCreated) {
//     echo "Review created successfully!";
// } else {
//     echo "Failed to create review.";
// }

// Get reviews for product ID "123"
// $reviews = getReviews("123", $db);
// if (!empty($reviews)) {
//     echo "<br><h2>Reviews for Product 123:</h2>";
//     foreach ($reviews as $review) {
//         echo "<p><strong>Name:</strong> " . $review['userName'] . "</p>";
//         echo "<p><strong>Rating:</strong> " . $review['rating'] . "</p>";
//         echo "<p><strong>Comment:</strong> " . $review['comment'] . "</p>";
//         echo "<br>";
//     }
// } else {
//     echo "<p>No reviews found for product 123.</p>";
// }
?>
