<?php
  include 'db.php';
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];
  $sql = "INSERT INTO `feedback` (`name`, `email`, `subject`, `message`) VALUES ('$name', '$email', '$subject', '$message')";
  if (mysqli_query($conn, $sql)) {
      echo "<script>alert('Thank you for your feedback!'); window.location.href='contact.php';</script>";
  } else {
      echo "Error: " . mysqli_error($conn);
  }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us | Farm2Home</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Arial", sans-serif;
    }

    body {
      background-color: #f5fff5;
      color: #333;
      line-height: 1.6;
    }
    .logo img{
      height: 60px;
      width: auto;
      margin-left: 25px;
    }
    .container {
      max-width: 1000px;
      margin: 40px auto;
      display: flex;
      gap: 30px;
      flex-wrap: wrap;
      padding: 0 20px;
    }

    .contact-info, .contact-form {
      flex: 1 1 450px;
      background-color: white;
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .contact-info h2, .contact-form h2 {
      color: #2d6a4f;
      margin-bottom: 20px;
      font-size: 22px;
      border-bottom: 2px solid #95d5b2;
      display: inline-block;
      padding-bottom: 4px;
    }

    .info-item {
      margin: 15px 0;
      display: flex;
      align-items: center;
    }

    .info-item span {
      font-size: 20px;
      margin-right: 10px;
      color: #2d6a4f;
    }

    .info-item p {
      font-size: 16px;
      color: #555;
    }

    .contact-form form {
      display: flex;
      flex-direction: column;
    }

    .contact-form label {
      font-weight: 500;
      margin: 10px 0 5px;
    }

    .contact-form input,
    .contact-form textarea {
      padding: 10px 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      outline: none;
      transition: border 0.3s ease;
    }

    .contact-form input:focus,
    .contact-form textarea:focus {
      border-color: #2d6a4f;
    }

    .contact-form textarea {
      resize: none;
      height: 120px;
    }

    .contact-form button {
      margin-top: 15px;
      padding: 12px;
      background-color: #2d6a4f;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .contact-form button:hover {
      background-color: #1b4332;
    }

    footer {
      text-align: center;
      padding: 15px;
      margin-top: 40px;
      background-color: #2d6a4f;
      color: white;
      font-size: 14px;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>
<body>
    <?php require 'content/_translate.html' ?>

    <header>
        <div class="logo"><img src="img\logo.png" alt="logo here"></div>
        <nav>
        <ul>
            <li><a href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
            <li><a href="#translate"><i class="fa-solid fa-language"></i> Translate/भाषा बदला</a></li>
            <li><a href="#"><i class="fa-solid fa-phone"></i> Contact</a></li>
        </ul>
        </nav>
    </header>
    <div class="container">
        <div class="contact-info">
        <h2>Get in Touch</h2>
        <div class="info-item">
            <span><i class="fa-solid fa-phone"></i></span>
            <p>Phone: +91 80809 09010</p>
        </div>
        <div class="info-item">
            <span><i class="fa-brands fa-whatsapp"></i></span>
            <p>Phone: +91 80809 09010</p>
        </div>
        <div class="info-item">
            <span><i class="fa-solid fa-at"></i></span>
            <p>Email: support@farm2home.com</p>
        </div>
        <div class="info-item">
            <span><i class="fa-solid fa-message"></i></span>
            <p>WhatsApp: +91 88889 99910</p>
        </div>
        <div class="info-item">
            <span><i class="fa-solid fa-location-dot"></i></span>
            <p>Address: Farm2Home Pvt. Ltd., Pune, Maharashtra, India</p>
        </div>
        </div>

        <div class="contact-form">
        <h2>Write Us</h2>
        <form action="contact.php" method="POST">
            <label for="name">Your Name</label>
            <input type="text" name="name" id="name" placeholder="Enter your full name" required>

            <label for="email">Your Email</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <label for="subject">Subject</label>
            <input type="text" name="subject" id="subject" placeholder="Subject of your message" required>

            <label for="message">Message / Feedback</label>
            <textarea name="message" id="message" placeholder="Write your feedback here..." required></textarea>
            <button type="submit">Send Feedback</button>
        </form>
        </div>
    </div>
  
    <footer>
        <p>© 2025 Farmer Crop Selling Platform D2C | Designed By Vishal </p>
    </footer>
</body>
</html>

<!-- CREATE TABLE `feedback` ( `feedback_id` INT AUTO_INCREMENT PRIMARY KEY, `name` VARCHAR(100) NOT NULL, `email` VARCHAR(100) NOT NULL, `subject` VARCHAR(150) NOT NULL, `message` TEXT NOT NULL, `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ) -->