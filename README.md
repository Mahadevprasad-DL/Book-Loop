# Book Loop ✨

**Book Loop** is an innovative platform that allows users to exchange old books effortlessly, promoting sustainability and encouraging a love for reading. Connect with fellow book enthusiasts, trade your old books, and discover new stories without any hassle.

---

## 🌟 Key Features
- **Easy Book Exchange**: Exchange old books seamlessly with other users.
- **Eco-Friendly Initiative**: Reduce waste and promote sustainable book sharing.
- **User-Friendly Interface**: Simple, attractive, and easy-to-navigate design.
- **Notifications**: Stay updated with book exchange notifications.
- **Dynamic Delivery Time**: Know when your exchanged book will arrive (1 to 7 days).

---

## 💡 How It Works
1. **Sign Up/Login**: Create an account and log in to start exchanging books.
2. **List Your Books**: Add details of books you want to exchange.
3. **Notifications**: Accept or ignore book exchange requests.
4. **Book Delivery**: Receive dynamic updates about the book's delivery time.

---

## 📚 Technologies Used
- **Front-end**: HTML, CSS, JavaScript
- **Back-end**: PHP
- **Database**: MySQL
- **Web Server**: XAMPP

---

## 🛠️ Setup Instructions
Follow these steps to set up **Book Loop** on your local environment:

### 1. Prerequisites
- Install [XAMPP](https://www.apachefriends.org/) to set up a local server.
- Ensure MySQL is running in XAMPP.

### 2. Clone the Repository
```bash
https://github.com/Mahadevprasad-DL/Book-Loop.git       
cd book-loop
```

### 3. Database Setup
- Import the `book_exchange.sql` file into your MySQL database.
- Update database credentials in `db.php`.

```php
<?php
$host = 'localhost';
$user = 'root';
$password = ''; // Your MySQL password
$database = 'book_exchange';
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
```

### 4. Run the Application
- Start Apache and MySQL servers in XAMPP.
- Open your browser and go to: 
  ```
  http://localhost/book-loop/
  ```

---

## 📊 Folder Structure
```
book-loop/
|-- db.php               # Database connection file
|-- index.php            # Main home page
|-- exchange_login.php   # User login page
|-- notifications.php    # Notifications page
|-- accept.php           # Accept book exchange request
```

## 🎨 Screenshots
### Home Page
![Home Page](screenshots/home.png)

### Book Notifications
![Notifications](screenshots/notifications.png)

---



## 🌈 Contribution Guidelines
We welcome contributions to enhance the **Book Loop** project. Here's how you can help:
1. Fork the repository.
2. Create a new branch for your feature.
3. Make your changes and commit them.
4. Submit a pull request.

---

## ✨ Credits
**Project Developed By**: [Mahadevprasad DL ]  
**GitHub**: [Your GitHub Profile](https://github.com/Mahadevprasad-DL)

---

## 📢 License
This project is open-source and available under the [MIT License](LICENSE).

---

## 📢 Acknowledgements
Special thanks to all the contributors and the open-source community.

