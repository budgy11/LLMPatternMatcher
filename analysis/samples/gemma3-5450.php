
<!DOCTYPE html>
<html>
<head>
    <title>Product Search</title>
    <style>
        .search-container {
            margin: 20px;
        }
        .search-bar {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .search-results {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }

        .result-item {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #eee;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="search-container">
        <form action="" method="GET">
            <input type="text" class="search-bar" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Search products...">
