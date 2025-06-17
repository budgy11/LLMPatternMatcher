

<?php

/**
 * User Review Function
 *
 * This function takes a user ID, a product ID, and a review text,
 * and saves the review to a database.  It also handles basic input validation.
 *
 * @param int $userId The ID of the user submitting the review.
 * @param int $productId The ID of the product being reviewed.
 * @param string $reviewText The text of the review.
 * @return bool True if the review was saved successfully, false otherwise.
 */
function saveUserReview(int $userId, int $productId, string $reviewText): bool
{
    // Input Validation -  Crucial to prevent SQL injection and bad data
    if (!$userId || !$productId || !$reviewText) {
        error_log("Error: Missing required parameters for review."); // Log error
        return false;
    }

    // Sanitize the review text -  Important!
    $reviewText = htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8');

    //  You'll need a database connection here.  This is just a placeholder.
    //  Replace this with your actual database connection code.
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

    try {
        // Prepare the SQL statement to prevent SQL injection
        $stmt = $db->prepare("INSERT INTO reviews (user_id, product_id, review_text, rating) VALUES (:userId, :productId, :reviewText, :rating)");

        // Bind the parameters - ensures data is properly escaped
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':reviewText', $reviewText);
        $stmt->bindParam(':rating', 0); // default rating - you might want to allow users to set this

        // Execute the statement
        $result = $stmt->execute();

        // Check if the query was successful
        if ($result) {
            return true;
        } else {
            // Log the error
            error_log("Error saving review: " . print_r($stmt->errorInfo(), true));
            return false;
        }
    } catch (PDOException $e) {
        // Log the exception -  Very important for debugging
        error_log("PDO Exception: " . $e->getMessage());
        return false;
    }
}


// Example Usage (Illustrative - Replace with your actual data)
// $userId = 123;
// $productId = 456;
// $reviewText = "This product is amazing!  I really love it!";
//
// if (saveUserReview($userId, $productId, $reviewText)) {
//     echo "Review saved successfully!";
// } else {
//     echo "Error saving review.";
// }



//  Important Considerations and Best Practices:

// 1. Database Connection: This example uses a placeholder database connection.  You *must* replace the example connection with your actual database connection code using PDO or MySQLi.

// 2. Error Handling:  The `try...catch` block handles potential database errors, preventing your script from crashing and providing valuable debugging information. Always log errors.

// 3. Input Validation:  The  `if (!$userId || ...)` block is crucial. Validate that all required parameters are provided and of the correct type.  This prevents unexpected behavior and potential security vulnerabilities.  You can extend this validation to check for things like review length limits, disallowed characters, etc.

// 4. SQL Injection Prevention: The code *must* use prepared statements with parameterized queries (using `$db->prepare()` and `$stmt->bindParam()`).  This is the *only* secure way to handle user input in SQL queries. Never directly concatenate user input into your SQL strings.

// 5. Sanitization: `htmlspecialchars()`  sanitizes the review text to prevent cross-site scripting (XSS) attacks.  It converts special characters (like <, >, &, etc.) into their HTML entities, preventing them from being interpreted as HTML tags.

// 6.  Database Schema: The code assumes a database schema with tables like `reviews` with columns `user_id`, `product_id`, `review_text`, and `rating`.  Adjust this to match your actual database structure.

// 7.  Rating:  The code includes a `rating` column. You can extend this function to allow users to set a rating (e.g., a star rating).

// 8.  Logging:  Using `error_log()` is vital for debugging. It's better than `echo`ing errors because it doesn't expose them to users.

// 9.  Security: Keep your database credentials secure. Don't hardcode them into your scripts; use environment variables or a configuration file.

// 10.  Database Transactions (For Complex Operations):  If you're performing multiple database operations (e.g., updating several tables), consider using database transactions to ensure that either all operations succeed or none of them do, maintaining data integrity.


<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 * It uses a simple database (for demonstration) and includes basic input validation.
 * 
 * **Important:** This is a basic example and should be adapted to your specific database,
 * schema, and security requirements.  Consider using an ORM (Object-Relational Mapper) 
 * for more complex applications.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating The rating given by the reviewer (e.g., 1-5).
 * @param string $comment The comment left by the reviewer.
 * @param string $dbHost The database host.
 * @param string $dbName The database name.
 * @param string $dbUser The database username.
 * @param string $dbPassword The database password.
 *
 * @return array  An array containing:
 *                - 'success': True if the operation was successful, False otherwise.
 *                - 'message':  A message describing the result.
 *                - 'reviewId': The ID of the newly created review (if successful).
 *                              Returns NULL if the review was not created.
 */
function createReview(
    string $productId,
    string $username,
    string $rating,
    string $comment,
    string $dbHost,
    string $dbName,
    string $dbUser,
    string $dbPassword
) {
    // Input Validation - Basic Example
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return [
            'success' => false,
            'message' => 'Invalid rating. Rating must be a number between 1 and 5.',
            'reviewId' => null,
        ];
    }

    // Database connection (Use a real database connection class for production)
    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    if ($conn->connect_error) {
        return [
            'success' => false,
            'message' => "Failed to connect to database: " . $conn->connect_error,
            'reviewId' => null,
        ];
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO reviews (productId, username, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        return [
            'success' => false,
            'message' => "Failed to prepare SQL statement: " . $conn->error,
            'reviewId' => null,
        ];
    }

    // Bind parameters
    $stmt->bind_param("ssis", $productId, $username, $rating, $comment);

    // Execute the statement
    if ($stmt->execute() === false) {
        return [
            'success' => false,
            'message' => "Failed to execute SQL: " . $stmt->error,
            'reviewId' => null,
        ];
    }

    // Get the last inserted ID
    $reviewId = $conn->insert_id;

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    return [
        'success' => true,
        'message' => 'Review created successfully.',
        'reviewId' => $reviewId,
    ];
}

// Example Usage (Replace with your database details)
// $product_id = '123';
// $username = 'john.doe';
// $rating = 4;
// $comment = 'Great product!';

// $result = createReview($product_id, $username, $rating, $comment, 'localhost', 'your_db_name', 'your_db_user', 'your_db_password');

// print_r($result); // Display the result
?>
