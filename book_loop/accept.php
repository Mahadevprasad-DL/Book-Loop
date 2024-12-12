<?php
include 'db2.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: exchange_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept'])) {
    // Retrieve POST data
    $notification_id = mysqli_real_escape_string($conn, $_POST['notification_id']);
    $sender_id = mysqli_real_escape_string($conn, $_POST['sender_id']);
    $receiver_id = mysqli_real_escape_string($conn, $_POST['receiver_id']);
    $book_name = mysqli_real_escape_string($conn, $_POST['book_name']);

    // Insert into accepted_notifications table
    $insert_sql = "INSERT INTO accepted_notifications (notification_id, sender_id, receiver_id, book_name) 
                   VALUES ('$notification_id', '$sender_id', '$receiver_id', '$book_name')";
    
    if (mysqli_query($conn, $insert_sql)) {
        // Generate a random delivery time between 1 and 7 days
        $delivery_time = rand(1, 7);

        // Display confirmation message
        echo "
            <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f7fa;
                            margin: 0;
                            padding: 0;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 100vh;
                            text-align: center;
                        }

                        .container {
                            background-color: white;
                            padding: 20px;
                            border-radius: 8px;
                            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                            font-size: 20px;
                            color: #333;
                        }

                        .message {
                            font-size: 24px;
                            font-weight: bold;
                            color: #28a745;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <p class='message'>Congratulations! Your book will be delivered within {$delivery_time} days.</p>
                    </div>
                </body>
            </html>
        ";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    exit();
}
?>
