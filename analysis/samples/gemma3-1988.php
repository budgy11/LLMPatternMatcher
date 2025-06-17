

<?php

/**
 * User Review Function
 *
 * This function processes user reviews, sanitizes input, and stores them in a database.
 *
 * @param string $username The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $product_id The ID of the product being reviewed.
 * @param PDO $pdo A PDO database connection object.
 *
 * @return bool True if the review was successfully saved, false otherwise.
 */
function saveUserReview(string $username, string $reviewText, int $product_id, PDO $pdo): bool
{
    // Sanitize and validate inputs
    $username = trim($username);
    $reviewText = trim($reviewText);

    // Check if username and review text are empty
    if (empty($username) || empty($reviewText)) {
        error_log("Invalid review: Username and/or review text are empty.");
        return false;
    }

    // Escape strings for SQL query to prevent SQL injection
    $username = $pdo->quote($username);
    $reviewText = $pdo->quote($reviewText);

    // Prepare and execute the SQL query
    $sql = "INSERT INTO reviews (product_id, username, review_text, created_at) VALUES (:product_id, :username, :review_text, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':review_text', $reviewText);
    $stmt->execute();

    return true;
}


/**
 * Retrieves Reviews for a Specific Product
 *
 * Fetches all reviews for a given product ID from the database.
 *
 * @param int $product_id The ID of the product to retrieve reviews for.
 * @param PDO $pdo A PDO database connection object.
 * @return array An array of review objects, each with an 'id', 'product_id', 'username', 'review_text', and 'created_at' properties.
 */
function getReviewsForProduct(int $product_id, PDO $pdo): array
{
    $sql = "SELECT id, product_id, username, review_text, created_at FROM reviews WHERE product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();

    $reviews = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $reviews[] = $row;
    }

    return $reviews;
}


// Example usage (Illustrative - Adapt to your database setup)

//  Assume you have a database connection object $pdo

// Create a sample database connection (replace with your actual connection)
$host = 'localhost';
$dbname = 'your_database_name';
$user = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "
";
    exit; // Or handle the error appropriately
}

// Example review submission
$product_id = 1;
$username = 'JohnDoe';
$reviewText = 'This product is amazing! I highly recommend it.';

if (saveUserReview($username, $reviewText, $product_id, $pdo)) {
    echo "Review successfully saved!
";
} else {
    echo "Error saving review.
";
}

// Retrieve reviews for the product
$reviews = getReviewsForProduct($product_id, $pdo);

echo "Reviews for product $product_id:
";
foreach ($reviews as $review) {
    echo "  ID: " . $review['id'] . ", Username: " . $review['username'] . ", Review: " . $review['review_text'] . "
";
}
?>
