<?php
include 'db2.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: exchange_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle "Accept" or "Ignore" button clicks
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ignore'])) {
    $notification_id = mysqli_real_escape_string($conn, $_POST['notification_id']);

    // Delete the notification
    $delete_sql = "DELETE FROM notifications WHERE id = '$notification_id'";
    if (mysqli_query($conn, $delete_sql)) {
        echo json_encode(['success' => true, 'message' => 'Notification deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete notification.']);
    }
    exit();
}

// Fetch notifications for the logged-in user
$sql = "SELECT notifications.id, notifications.book_id, books.book_name, users.username AS sender_name, notifications.sender_id, notifications.receiver_id
        FROM notifications
        INNER JOIN books ON notifications.book_id = books.id
        INNER JOIN users ON notifications.sender_id = users.id
        WHERE notifications.receiver_id = '$user_id'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        td {
            background-color: #fff;
        }

        button {
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .accept-btn {
            background-color: #28a745;
            color: white;
        }

        .ignore-btn {
            background-color: #dc3545;
            color: white;
        }

        .notification-container {
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <script>
        // Handle ignore button via AJAX
        function deleteNotification(notificationId) {
            if (confirm("Are you sure you want to ignore this notification?")) {
                fetch('', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `notification_id=${notificationId}&ignore=1`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('notification-' + notificationId).remove();
                        alert(data.message);
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</head>
<body>
    <h2>Notifications</h2>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Sender</th>
                    <th>Book</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="notification-container" id="notification-<?php echo $row['id']; ?>">
                        <td><?php echo htmlspecialchars($row['sender_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['book_name']); ?></td>
                        <td>
                            <form method="POST" action="accept.php" style="display:inline;">
                                <input type="hidden" name="notification_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="sender_id" value="<?php echo $row['sender_id']; ?>">
                                <input type="hidden" name="receiver_id" value="<?php echo $row['receiver_id']; ?>">
                                <input type="hidden" name="book_name" value="<?php echo htmlspecialchars($row['book_name']); ?>">
                                <button type="submit" name="accept" class="accept-btn">Accept</button>
                            </form>
                            <button type="button" class="ignore-btn" onclick="deleteNotification(<?php echo $row['id']; ?>)">Ignore</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center;">No notifications yet.</p>
    <?php endif; ?>
</body>
</html>
