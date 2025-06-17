

<?php

/**
 * User Review Function
 *
 * This function takes a user's review and stores it in a database (or other storage)
 * and also returns a user-friendly response.
 *
 * @param string $review The user's review text.
 * @param int $productId The ID of the product being reviewed.
 * @param int $userId The ID of the user submitting the review.
 * @param string $dbHost The database host.
 * @param string $dbName The database name.
 * @param string $dbUser The database username.
 * @param string $dbPassword The database password.
 *
 * @return string A user-friendly response indicating success or failure.
 */
function storeUserReview(string $review, int $productId, int $userId, string $dbHost, string $dbName, string $dbUser, string $dbPassword)
{
    // Sanitize input (basic example - improve for production)
    $review = trim($review);
    if (empty($review)) {
        return "Error: Review cannot be empty.";
    }

    //  Database connection (Replace with your actual database connection logic)
    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    if ($conn->connect_error) {
        return "Error: Could not connect to database: " . $conn->connect_error;
    }

    // Prepare the SQL query (Use prepared statements to prevent SQL injection)
    $sql = "INSERT INTO reviews (productId, userId, reviewText) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        return "Error: Could not prepare SQL statement.";
    }

    $stmt->bind_param("sii", $productId, $userId, $review); // 's' for string, 'i' for integer

    if (!$stmt->execute()) {
        return "Error: Could not execute SQL query: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    return "Review submitted successfully!";
}


// Example Usage:
//  You would replace these with your actual database credentials
$dbHost = "localhost";
$dbName = "your_database_name";
$dbUser = "your_db_user";
$dbPassword = "your_db_password";

$reviewText = "This product is amazing! I highly recommend it.";
$productId = 123;
$userId = 456;

$response = storeUserReview($reviewText, $productId, $userId, $dbHost, $dbName, $dbUser, $dbPassword);
echo $response . "
"; // Output the response
?>
