
<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
    <style>
        .search-container {
            width: 300px;
            margin: 20px auto;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .results {
            list-style: none;
            padding: 0;
        }
        .result-item {
            padding: 10px;
            border: 1px solid #eee;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

    <div class="search-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
