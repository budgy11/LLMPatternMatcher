

<?php

/**
 * User Review Function
 *
 * This function handles the creation and display of user reviews.
 * It includes input validation, sanitization, and basic formatting.
 *
 * @param string $product_id The ID of the product being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @return array|string Returns an array on success or an error message string.
 */
function create_user_review(string $product_id, string $username, string $rating, string $comment)
{
    // Input Validation & Sanitization
    if (empty($product_id) || empty($username) || empty($rating) || empty($comment)) {
        return "Error: All fields are required.";
    }

    if (!preg_match('/^[a-zA-Z0-9 ]+$/', $username)) {
        return "Error: Username must contain only letters and spaces.";
    }

    if (!preg_match('/^[1-5]$/', $rating)) {
        return "Error: Rating must be a number between 1 and 5.";
    }

    if (strlen($comment) > 1000) {
        return "Error: Comment must be less than 1000 characters.";
    }

    // Sanitize input (optional, but recommended) -  This is a basic example
    $username = trim($username);
    $comment = trim($comment);


    //  Database interaction (Replace with your actual database connection)
    //  This is a simplified example; you'll need to adapt it to your database setup.
    $db_host = 'localhost';
    $db_name = 'your_database_name';
    $db_user = 'your_database_user';
    $db_password = 'your_database_password';

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        return "Error: Database connection failed: " . $e->getMessage();
    }


    // Prepare the SQL statement
    $sql = "INSERT INTO reviews (product_id, username, rating, comment) 
            VALUES (:product_id, :username, :rating, :comment)";
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    // Execute the statement
    if ($stmt->execute()) {
        return "Review successfully created!";
    } else {
        return "Error: Review creation failed. " . print_r($stmt->errorInfo(), true);
    }
}


// Example Usage:
$product_id = '123';
$username = 'JohnDoe';
$rating = '4';
$comment = "This product is amazing!  I highly recommend it.  It's fantastic!";


$result = create_user_review($product_id, $username, $rating, $comment);

if (is_string($result)) {
    echo $result . "<br>";
} else {
    echo "Review created successfully!";
}


?>
