

<?php

/**
 * User Review Function
 *
 * This function takes user input (review text) and stores it in a database.
 * It includes basic validation and error handling.
 *
 * @param string $reviewText The user-submitted review text.
 * @param string $username  The username of the user submitting the review.
 * @param string $productId The ID of the product being reviewed.
 * @param PDO $pdo  A PDO database connection object.
 *
 * @return bool True if the review was successfully saved, false otherwise.
 */
function saveUserReview(string $reviewText, string $username, string $productId, PDO $pdo)
{
    // Input Validation
    if (empty($reviewText)) {
        error_log("Empty review text submitted.");  // Log for admin/debugging
        return false;
    }

    if (empty($username)) {
        error_log("Empty username submitted.");
        return false;
    }

    if (empty($productId)) {
        error_log("Empty product ID submitted.");
        return false;
    }

    // Sanitize Input (Basic - Extend for Robustness)
    $reviewText = htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8'); // Prevent XSS
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

    //  Potentially add more validation here, like length limits on the review text.

    // Prepare SQL Query
    $sql = "INSERT INTO reviews (product_id, user_name, review_text, review_date)
            VALUES (:product_id, :user_name, :review_text, NOW())";

    try {
        // Execute the query
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':user_name', $username);
        $stmt->bindParam(':review_text', $reviewText);
        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());  //Log the error for debugging
        return false;
    }
}


// Example Usage (assuming you have a database connection)
//  This is just for demonstration.  Replace with your actual database details.
/*
try {
    // Establish database connection (replace with your credentials)
    $host = 'localhost';
    $dbname = 'your_database_name';
    $user = 'your_db_user';
    $password = 'your_db_password';

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);

    // Example Usage
    $reviewText = "This product is fantastic!  I highly recommend it.";
    $username = "JohnDoe";
    $productId = "123";

    if (saveUserReview($reviewText, $username, $productId, $pdo)) {
        echo "Review saved successfully!";
    } else {
        echo "Error saving review.";
    }

} catch (PDOException $e) {
    echo "Database connection error: " . $e->getMessage();
}
*/

?>
