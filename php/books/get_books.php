<?php
// =============================================
// CHAPTER 4 COMPLIANT: FETCH BOOKS
// =============================================

// 1. Database Connection (Chapter 4: MySQLi Procedural Style)
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "bookstore_db"; // Ensure this matches your DB name

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 2. Handling User Input (Chapter 3: $_GET and HTML Sanitization)
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// 3. Building the SQL Query (Chapter 4: Basic SELECT & JOIN)
// We use a simpler string concatenation method as taught in early integration
$sql = "SELECT books.*, categories.name as cat_name 
        FROM books 
        LEFT JOIN categories ON books.category_id = categories.id 
        WHERE books.in_stock = 1";

// Adding simple filters
if (!empty($category)) {
    // Sanitize input to prevent basic issues (Chapter 3/4)
    $safe_category = mysqli_real_escape_string($conn, $category);
    $sql .= " AND categories.slug = '$safe_category'";
}

if (!empty($search)) {
    $safe_search = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (books.title LIKE '%$safe_search%' OR books.author LIKE '%$safe_search%')";
}

// 4. Executing Query (Chapter 4)
$result = mysqli_query($conn, $sql);

// 5. Displaying Results (Chapter 2: Loops & Chapter 1: HTML/PHP Mix)
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Catalog</title>
    <style>
        .book-card { border: 1px solid #ccc; padding: 10px; margin: 10px; display: inline-block; width: 200px; }
    </style>
</head>
<body>

    <h1>Our Books</h1>

    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search books..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <div class="book-container">
        <?php 
        // Chapter 2: Using a while loop to fetch data
        if (mysqli_num_rows($result) > 0) {
            while($book = mysqli_fetch_assoc($result)) { 
        ?>
            <div class="book-card">
                <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
                <p>Category: <?php echo htmlspecialchars($book['cat_name']); ?></p>
                <p><strong>Price: $<?php echo number_format($book['price'], 2); ?></strong></p>
            </div>
        <?php 
            } 
        } else {
            echo "<p>No books found.</p>";
        }
        ?>
    </div>

</body>
</html>

<?php
// Close connection (Chapter 4)
mysqli_close($conn);
?>