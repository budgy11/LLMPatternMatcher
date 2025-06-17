
<!DOCTYPE html>
<html>
<head>
    <title>Product Search</title>
    <style>
        .search-container {
            margin: 20px;
        }

        input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 300px;
        }

        .results {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .result-item {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #eee;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

    <div class="search-container">
        <h1>Product Search</h1>
        <input type="text" id="searchInput" placeholder="Enter search term...">
    </div>

    <div class="results" id="resultsContainer">
        <?php if (empty($searchResults)): ?>
