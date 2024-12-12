<?php
include 'db2.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: exchange_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Add Book
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_book'])) {
    $book_name = $_POST['book_name'];
    $book_condition = $_POST['book_condition'];
    $author_name = $_POST['author_name'];
    $image = $_FILES['book_image']['name'];
    $image_path = "uploads/" . basename($image);

    // Move the uploaded file to 'uploads/' folder
    if (move_uploaded_file($_FILES['book_image']['tmp_name'], $image_path)) {
        $sql = "INSERT INTO books (user_id, book_name, book_image, book_condition, author_name) 
                VALUES ('$user_id', '$book_name', '$image_path', '$book_condition', '$author_name')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Book uploaded successfully!');</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Failed to upload the image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Book</title>
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: #f4f7fc;
        }

        /* Header */
        header {
            background: #3498db;
            padding: 20px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin-left: 15px;
            background: #2980b9;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        nav a:hover {
            background: #1c6b99;
        }

        /* Main container */
        .main-container {
            display: flex;
            justify-content: space-between;
            margin: 20px;
        }

        .left-container {
            width: 30%;
            background: #3498db;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
            animation: slideInLeft 1s ease-out;
        }

        .right-container {
            width: 65%;
            padding: 30px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            animation: fadeIn 0.8s ease;
        }

        h2, h3 {
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="file"]:focus {
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: #fff;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #2980b9;
        }

        .form-group {
            margin-bottom: 15px;
        }

        /* Animations */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
                align-items: center;
            }

            .left-container, .right-container {
                width: 90%;
                margin-bottom: 20px;
            }

            h2, h3 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

    <header>
        <h1>Exchange The Books</h1>
        <nav>
            <a href="view_books.php">View All Books</a>
        </nav>
    </header>

    <div class="main-container">
        <div class="left-container">
            <h2>Add Your Books</h2>
            <p>Upload your books to exchange with others</p>
        </div>

        <div class="right-container">
            <h3>Welcome, <?php echo htmlspecialchars($username); ?>!</h3>
            <h4>Upload a Book</h4>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="book_name">Book Name:</label>
                    <input type="text" name="book_name" id="book_name" required>
                </div>
                <div class="form-group">
                    <label for="book_condition">Book Condition:</label>
                    <input type="text" name="book_condition" id="book_condition" required>
                </div>
                <div class="form-group">
                    <label for="author_name">Author Name:</label>
                    <input type="text" name="author_name" id="author_name" required>
                </div>
                <div class="form-group">
                    <label for="book_image">Book Image:</label>
                    <input type="file" name="book_image" id="book_image" required>
                </div>
                <button type="submit" name="add_book">Upload Book</button>
            </form>
        </div>
    </div>

</body>
</html>
