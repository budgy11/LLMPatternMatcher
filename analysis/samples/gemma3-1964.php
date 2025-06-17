

<?php

/**
 * User Review Function
 *
 * This function allows you to collect and display user reviews.
 * It includes basic validation, sanitization, and storage.
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $reviewText The user's review text.
 * @param int $rating The user's rating (1-5).
 * @param string $userEmail The user's email address.
 * @param string $storageMethod 'database' or 'file' -  determines how reviews are stored.
 *
 * @return array|string Returns an array of reviews or a success/error message.
 */
function createAndStoreUserReview(
    string $productName,
    string $reviewText,
    int $rating,
    string $userEmail,
    string $storageMethod = 'database' // Default to database
) {
    // Input Validation & Sanitization - IMPORTANT!
    if (empty($productName)) {
        return ['status' => 'error', 'message' => 'Product name cannot be empty.'];
    }
    if (empty($reviewText)) {
        return ['status' => 'error', 'message' => 'Review text cannot be empty.'];
    }
    if ($rating < 1 || $rating > 5) {
        return ['status' => 'error', 'message' => 'Rating must be between 1 and 5.'];
    }
    if (empty($userEmail)) {
        return ['status' => 'error', 'message' => 'User email cannot be empty.'];
    }

    // Prepare review data -  Important to prevent SQL injection if using database
    $reviewData = [
        'product_name' => $productName,
        'review_text' => $reviewText,
        'rating' => $rating,
        'user_email' => $userEmail,
        'created_at' => date('Y-m-d H:i:s') // Add timestamp for tracking
    ];

    // --- Database Storage (Example) ---
    if ($storageMethod === 'database') {
        // Replace with your database connection details
        $dbHost = 'localhost';
        $dbName = 'your_database_name';
        $dbUser = 'your_database_user';
        $dbPassword = 'your_database_password';

        try {
            // Establish database connection
            $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

            // Check connection
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }

            // Prepare SQL statement to prevent SQL injection
            $sql = "INSERT INTO reviews (product_name, review_text, rating, user_email, created_at) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ssiii", $productName, $reviewText, $rating, $userEmail);
                if ($stmt->execute()) {
                    $stmt->close();
                    $conn->close();
                    return ['status' => 'success', 'message' => 'Review submitted successfully!'];
                } else {
                    $stmt->close();
                    $conn->close();
                    return ['status' => 'error', 'message' => 'Error submitting review: ' . $stmt->error];
                }
            } else {
                $conn->close();
                return ['status' => 'error', 'message' => 'Failed to prepare statement.'];
            }

        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }

    }
    // --- File Storage (Example - Simpler) ---
    // In a real application, you'd use a more robust file handling system.
    else if ($storageMethod === 'file') {
        $file = 'reviews.txt'; // or a better name and location
        $data = $reviewData['review_text'] . ', Rating: ' . $reviewData['rating'] . ' - ' . $reviewData['user_email'] . ' - ' . $reviewData['created_at'] . "
";
        file_put_contents($file, $data, FILE_APPEND);
        return ['status' => 'success', 'message' => 'Review submitted to file.'];
    }
    else {
      return ['status' => 'error', 'message' => 'Invalid storage method specified.  Choose "database" or "file"'];
    }
}


// Example Usage:
$reviewResult = createAndStoreUserReview(
    'Awesome Product',
    'This product is amazing! I highly recommend it.',
    4,
    'user@example.com',
    'database' // or 'file'
);

print_r($reviewResult);

// Example File Storage Usage:
$reviewResultFile = createAndStoreUserReview(
    'Another Product',
    'Good value for the money.',
    5,
    'anotheruser@example.com',
    'file'
);

print_r($reviewResultFile);
?>
