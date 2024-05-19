<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Recommendations by Category</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Movie Recommendations by Category</h1>
    <div id="user-input">
        <label for="username">Enter your username:</label>
        <input type="text" id="username" placeholder="Username">
        <label for="category">Select Category:</label>
        <select id="category">
            <option value="">All Categories</option>
            <option value="drame">Drama</option>
            <option value="Action">Action</option>
        </select>
        <button id="submit-user">Submit</button>
    </div>
    <div id="recommendations" style="display: none;">
        <h2>Recommended Movies</h2>
        <ul id="movie-list"></ul>
    </div>
    <script src="script.js"></script>
</body>
</html>
