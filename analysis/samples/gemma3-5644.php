

<?php

// ... (Configuration as before) ...

// SQL query with placeholders
$query = "SELECT * FROM your_table_name WHERE title LIKE :search_term";

// Prepare the statement
$stmt = $conn->prepare($query);

if ($stmt) {
    // Bind the parameter
    $stmt->bind_param("s", $search_term); // "s" indicates a string

    // Execute the statement
    $stmt->execute();

    // Fetch the results
    $result = $stmt->get_result();

    // Process the results (as before)
    // ... (Display Results section) ...

    // Close the statement
    $stmt->close();
} else {
    // Handle the error
    echo "Error preparing statement: " . $conn->error;
}

// ... (Close Connection) ...
?>
