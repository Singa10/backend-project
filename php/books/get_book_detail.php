<?php
// =============================================
// CHAPTER 4 COMPLIANT: FETCH SINGLE BOOK
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

// 2. Handling Input (Chapter 3: $_GET and Type Casting)
// We cast to (int) to ensure basic security/validation as taught in Ch 2
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("Invalid Book ID provided.");
}

// 3. Building & Executing the Query (Chapter 4)
// Using a JOIN to get the category name along with book details
$sql = "SELECT books.*, categories.name as cat_name 
        FROM books 
        LEFT JOIN categories ON books.category_id = categories.id 
        WHERE books.id = $id";

$result = mysqli_query($conn, $sql);

// 4. Checking Results & Fetching (Chapter 2 Logic)
if (mysqli_num_rows($result) > 0) {
    $book = mysqli_fetch_assoc($result);
} else {
    die("Book not found in our database.");
}

// 5. Outputting to the Browser (Chapter 1 & 2)
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($book['title']); ?> - Details</title>
    <style>
        .book-detail { max-width: 600px; margin: 20px auto; font-family: sans-serif; }
        .price { color: green; font-size: 1.5em; font-weight: bold; }
        .back-link { display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>

    <div class="book-detail">
        <h1><?php echo htmlspecialchars($book['title']); ?></h1>
        <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($book['cat_name']); ?></p>
        <hr>
        <p><?php echo htmlspecialchars($book['description']); ?></p>
        <p class="price">$<?php echo number_format($book['price'], 2); ?></p>
        
        <p>
            Status: <?php echo ($book['in_stock'] == 1) ? "In Stock" : "Out of Stock"; ?>
        </p>

        <a href="index.php" class="back-link">← Back to Catalog</a>
    </div>

</body>
</html>

<?php
// Close connection (Chapter 4)
mysqli_close($conn);
?>