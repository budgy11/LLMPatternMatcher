

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or service.
 *
 * @param string $productId The unique identifier of the product or service.
 * @param string $userName The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int    $rating   The rating given by the user (e.g., 1-5).
 * @param string $dbHost  The database hostname.
 * @param string $dbName  The name of the database.
 * @param string $dbUser  The database username.
 * @param string $dbPassword The database password.
 *
 * @return array An array containing:
 *              - 'success': True if the review was added successfully, false otherwise.
 *              - 'message': A message describing the outcome (e.g., 'Review added', 'Error saving review').
 */
function add_user_review(
    string $productId,
    string $userName,
    string $reviewText,
    int    $rating,
    string $dbHost,
    string $dbName,
    string $dbUser,
    string $dbPassword
) {

    // Validate inputs (Basic example - more robust validation is recommended)
    if (empty($productId) || empty($userName) || empty($reviewText) || $rating < 1 || $rating > 5) {
        return [
            'success' => false,
            'message' => 'Invalid input.  Product ID, User Name, Review Text, and Rating must be provided and Rating must be between 1 and 5.'
        ];
    }

    try {
        // Establish database connection
        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

        // Check connection
        if ($conn->connect_error) {
            throw new Exception('Connection failed: ' . $conn->connect_error);
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO reviews (productId, userName, reviewText, rating) VALUES (?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("sss", $productId, $userName, $reviewText);

        // Execute the statement
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            return [
                'success' => true,
                'message' => 'Review added successfully.'
            ];
        } else {
            $stmt->close();
            $conn->close();
            return [
                'success' => false,
                'message' => 'Error saving review: ' . $stmt->error
            ];
        }
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ];
    }
}


// Example Usage (Remember to adapt this to your specific database setup)
// $result = add_user_review(
//     'product123',
//     'John Doe',
//     'Great product!  Highly recommended.',
//     5,
//     'localhost',
//     'my_database',
//     'my_user',
//     'my_password'
// );

// print_r($result);
?>
