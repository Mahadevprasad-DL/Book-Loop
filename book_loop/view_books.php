<?php
include 'db2.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: exchange_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the username of the logged-in user
$user_result = mysqli_query($conn, "SELECT username FROM users WHERE id = '$user_id'");
$user_row = mysqli_fetch_assoc($user_result);
$sender_username = $user_row['username'];

// Handle the "Like to Exchange" button
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['like_exchange'])) {
    $book_id = mysqli_real_escape_string($conn, $_POST['book_id']);
    $receiver_id = mysqli_real_escape_string($conn, $_POST['receiver_id']);

    // Check if notification already exists to avoid duplicates
    $check_sql = "SELECT * FROM notifications WHERE book_id = '$book_id' AND sender_id = '$user_id' AND receiver_id = '$receiver_id'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) == 0) {
        // Insert the notification with a descriptive message
        $message = "$sender_username wants to exchange their book with you.";
        $sql = "INSERT INTO notifications (book_id, sender_id, receiver_id, message) 
                VALUES ('$book_id', '$user_id', '$receiver_id', '$message')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Request sent successfully!');</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('You have already sent a request for this book.');</script>";
    }
}

// Fetch all books along with uploader information
$books_query = "SELECT books.*, users.username AS uploader_username, users.id AS uploader_id
                FROM books 
                INNER JOIN users ON books.user_id = users.id 
                ORDER BY books.id DESC";
$books = mysqli_query($conn, $books_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        .header {
            background-color: #3498db;
            color: white;
            padding: 15px;
            text-align: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .header h2 {
            margin: 0;
        }

        .header .actions {
            display: flex;
            gap: 20px;
        }

        .header a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 15px;
            background-color: #2980b9;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .header a:hover {
            background-color: #1f6693;
        }

        .content {
            padding: 20px;
            margin-top: 20px;
        }

        .right {
            padding: 15px;
            background-color: #fff;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .book-container {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 15px;
            width: 300px;
            display: inline-block;
            text-align: center;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.3);
            background-color: #f9f9f9;
            opacity: 0;
            animation: fadeIn 1s forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        img {
            max-width: 100px;
            max-height: 100px;
            margin-bottom: 10px;
        }

        button {
            background-color: #3498db;
            color: white;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        p {
            margin: 5px 0;
            color: #555;
        }

        a {
            display: block;
            margin: 10px auto;
            text-align: center;
            color: #3498db;
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #0056b3;
        }

    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h2>Welcome, <?php echo htmlspecialchars($sender_username); ?>!</h2>
        <div class="actions">
            <a href="dashboard.php">Upload Book</a>
            <a href="notifications.php">View Notifications</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="right">
            <h3>Available Books</h3>
            <?php if (mysqli_num_rows($books) > 0): ?>
                <?php while ($book = mysqli_fetch_assoc($books)): ?>
                    <div class="book-container">
                        <img src="<?php echo htmlspecialchars($book['book_image']); ?>" alt="Book Image">
                        <p><strong>Book Name:</strong> <?php echo htmlspecialchars($book['book_name']); ?></p>
                        <p><strong>Condition:</strong> <?php echo htmlspecialchars($book['book_condition']); ?></p>
                        <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author_name']); ?></p>
                        <p><strong>Uploaded By:</strong> <?php echo htmlspecialchars($book['uploader_username']); ?></p>

                        <?php if ($book['uploader_id'] != $user_id): ?>
                            <!-- Like to Exchange Button -->
                            <form method="POST" action="">
                                <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                <input type="hidden" name="receiver_id" value="<?php echo $book['uploader_id']; ?>">
                                <button type="submit" name="like_exchange">Like to Exchange This Book</button>
                            </form>
                        <?php else: ?>
                            <p style="color: grey; font-style: italic;">(This is your book)</p>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center;">No books have been uploaded yet.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
