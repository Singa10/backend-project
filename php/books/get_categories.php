<?php
// =============================================
// CHAPTER 4 COMPLIANT: FETCH CATEGORIES
// =============================================

// 1. Database Connection (Chapter 4: MySQLi Procedural)
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "bookstore_db";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 2. The SQL Query (Chapter 4: Joining and Grouping)
// We use a LEFT JOIN to count books in each category
$sql = "SELECT categories.*, COUNT(books.id) as book_count 
        FROM categories 
        LEFT JOIN books ON categories.id = books.category_id 
        GROUP BY categories.id 
        ORDER BY categories.name ASC";

$result = mysqli_query($conn, $sql);

// 3. Displaying Content (Chapter 1 Architecture & Chapter 2 Loops)
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Categories</title>
    <style>
        .category-list { list-style: none; padding: 0; }
        .category-item { padding: 10px; border-bottom: 1px solid #eee; }
        .count-badge { background: #007bff; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8em; }
    </style>
</head>
<body>

    <h2>Browse by Category</h2>

    <ul class="category-list">
        <?php 
        // Chapter 2: Using a while loop to iterate through the result set
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) { 
        ?>
            <li class="category-item">
                <a href="catalog.php?category=<?php echo htmlspecialchars($row['slug']); ?>">
                    <?php echo htmlspecialchars($row['name']); ?>
                </a>
                <span class="count-badge"><?php echo $row['book_count']; ?> books</span>
            </li>
        <?php 
            } 
        } else {
            echo "<li>No categories found.</li>";
        }
        ?>
    </ul>

</body>
</html>

<?php
// Close connection (Chapter 4)
mysqli_close($conn);
?>