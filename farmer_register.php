<?php
    include('db.php');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $farmer_name = $_POST['farmer_name'] ?? '';
        $farmer_age = $_POST['farmer_age'] ?? '';
        $farmer_gender = $_POST['farmer_gender'] ?? '';
        $farmer_address = $_POST['farmer_address'] ?? '';
        $city = $_POST['city'] ?? '';
        $state = $_POST['state'] ?? '';
        $pin_code = $_POST['pin_code'] ?? '';
        $aadhar_no = $_POST['farmer_aadhar_no'] ?? '';
        $pan_no = $_POST['farmer_pan_no'] ?? '';
        $contact_no = $_POST['farmer_contact_no'] ?? '';
        $email = $_POST['farmer_mail_id'] ?? '';
        $username = $_POST['farmer_username'] ?? '';
        $password = $_POST['farmer_password'] ?? '';

        $hash = password_hash($password,PASSWORD_DEFAULT);
        $sql = "INSERT INTO farmer 
        (farmer_name, farmer_age, farmer_gender, farmer_address, city, state, pin_code, farmer_aadhar_no, farmer_pan_no, farmer_contact_no, farmer_mail_id, farmer_username, farmer_password) 
        VALUES 
        ('$farmer_name', '$farmer_age', '$farmer_gender', '$farmer_address', '$city', '$state', '$pin_code', '$aadhar_no', '$pan_no', '$contact_no', '$email', '$username', '$hash')";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('Registration successful! You can now log in.'); window.location.href='index.php';</script>";
        } else {
            echo "Record not inserted: " . mysqli_error($conn);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="style.css">
    
    <style>
        .logo img{
        height: 60px;
        width: auto;
        margin-left: 25px;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo"><img src="img\logo.png" alt="logo here"></div>
        <nav>
            <ul>
                <li><a href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
                <li><a href="index.php"><i class=" fa-solid fa-circle-info"></i> About</a></li>
                <li><a href="#translate"><i class="fa-solid fa-language"></i> Translate/भाषा बदला</a></li>
                <li><a href="#"><i class="fa-solid fa-phone"></i> Contact</a></li>
            </ul>
        </nav>
    </header>

    <?php require 'content/_translate.html' ?>

    <section class="register-section">
        <div class="register-container">
            <h2>Farmer Registration</h2>
            <p class="subtitle">Join our platform to sell your crops directly to buyers</p>

            <form class="register-form" action="/farm2home/farmer_register.php" method="POST">

                <!-- Full Name -->
                <div class="form-group">
                    <label for="farmer_name">Full Name</label>
                    <input type="text" id="farmer_name" name="farmer_name" placeholder="Enter your full name" required>
                </div>

                <div class="form-row">
                    <!-- Age -->
                    <div class="form-group">
                        <label for="farmer_age">Age</label>
                        <input type="text" id="farmer_age" name="farmer_age" placeholder="Enter your age" min="18"
                            max="100" required>
                    </div>

                    <!-- Gender -->
                    <div class="form-group">
                        <label for="farmer_gender">Gender</label>
                        <select id="farmer_gender" name="farmer_gender" required>
                            <option value="">--Select Gender--</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                            <option value="O">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="farmer_address">Address</label>
                    <textarea id="farmer_address" name="farmer_address" placeholder="Enter your address" rows="3"
                        required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" placeholder="City" required>
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" placeholder="State" required>
                    </div>
                    <div class="form-group">
                        <label for="pin_code">Pin Code</label>
                        <input type="text" id="pin_code" name="pin_code" placeholder="Pin Code" pattern="[0-9]{6}" maxlength="6"
                            required>
                    </div>
                </div>

                <div class="form-row">
                    <!-- Aadhar No -->
                    <div class="form-group">
                        <label for="farmer_aadhar_no">Aadhar Number</label>
                        <input type="text" id="farmer_aadhar_no" name="farmer_aadhar_no"
                            placeholder="Enter 12-digit Aadhar No" pattern="[0-9]{12}" required>
                    </div>

                    <!-- PAN No -->
                    <div class="form-group">
                        <label for="farmer_pan_no">PAN Number</label>
                        <input type="text" id="farmer_pan_no" name="farmer_pan_no"
                            placeholder="Enter 10-character PAN No" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" required>
                    </div>
                </div>

                <div class="form-row">
                    <!-- Contact No -->
                    <div class="form-group">
                        <label for="farmer_contact_no">Contact Number</label>
                        <input type="text" id="farmer_contact_no" name="farmer_contact_no"
                            placeholder="Enter 10-digit phone number" pattern="[0-9]{10}" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="farmer_mail_id">Email Address</label>
                        <input type="text" id="farmer_mail_id" name="farmer_mail_id" placeholder="Enter your email"
                            required>
                    </div>
                </div>

                <!-- Username -->
                <div class="form-group">
                    <label for="farmer_username">Create Username</label>
                    <input type="text" id="farmer_username" name="farmer_username" placeholder="Create username"
                        required>
                </div>

                <div class="form-row">
                    <div class="form-group password-group">
                        <label for="farmer_password">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="farmer_password" name="farmer_password"
                                placeholder="Enter a strong password" required>
                            <span class="toggle-password" onclick="togglePassword('farmer_password')"><i
                                    class="fa-solid fa-eye-slash"></i></span>
                        </div>
                    </div>

                    <div class="form-group password-group">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password"
                                placeholder="Re-enter password" required>
                            <span class="toggle-password" onclick="togglePassword('confirm_password')"><i
                                    class="fa-solid fa-eye-slash"></i></span>
                        </div>
                    </div>

                </div>

                <button type="submit" class="register-btn">Register</button>

                <p class="login-link">
                    Already registered? <a href="javascript:void(0)" onclick="openLoginPopup()">Login Here</a>
                </p>
            </form>
        </div>
    </section>

  <div class="login-overlay" id="loginOverlay">
    <div class="login-popup">
      <span class="close-btn" onclick="closeLoginPopup()">&times;</span>
      <h2>Login</h2>

      <!-- Tab Buttons -->
      <div class="tab-buttons">
        <button class="tab-btn active" onclick="showLoginForm('farmer')">Farmer Login</button>
        <button class="tab-btn" onclick="showLoginForm('buyer')">Buyer Login</button>
      </div>

      <!-- Farmer Login Form -->
      <form id="farmerLogin" class="login-form active" action="/farm2home/login.php" method="post">
        <div class="form-group">
          <label for="farmerUsername">Username</label>
          <input type="text" name="input_username" id="farmerUsername" placeholder="Enter your username" required>
        </div>

        <div class="form-group">
          <label for="farmerPassword">Password</label>
          <input type="password" name="input_password" id="farmerPassword" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="login-btn">Login as Farmer</button>
        <p class="register-link">New here? <a href="farmer_register.php">Register as Farmer</a></p>
      </form>

      <!-- Buyer Login Form -->
      <form id="buyerLogin" class="login-form" action="/farm2home/login.php" method="post">
        <div class="form-group">
          <label for="buyerEmail">Username</label>
          <input type="text" name="input_username"  id="buyerEmail" placeholder="Enter your Username" required>
        </div>

        <div class="form-group">
          <label for="buyerPassword">Password</label>
          <input type="password" name="input_password" id="buyerPassword" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="login-btn">Login as Buyer</button>
        <p class="register-link">New here? <a href="buyer_register.php">Register as Buyer</a></p>
      </form>
    </div>
  </div>

    <footer>
        <p>© 2025 Farmer Crop Selling Platform D2C | Designed By VS<sup>2</sup> </p>
    </footer>
</body>

<script>
    function openLoginPopup() {
        document.getElementById('loginOverlay').style.display = 'flex';
    }

    function closeLoginPopup() {
        document.getElementById('loginOverlay').style.display = 'none';
    }

    function showLoginForm(type) {
        const farmerForm = document.getElementById('farmerLogin');
        const buyerForm = document.getElementById('buyerLogin');
        const tabBtns = document.querySelectorAll('.tab-btn');

        if (type === 'farmer') {
            farmerForm.classList.add('active');
            buyerForm.classList.remove('active');
            tabBtns[0].classList.add('active');
            tabBtns[1].classList.remove('active');
        } else {
            buyerForm.classList.add('active');
            farmerForm.classList.remove('active');
            tabBtns[1].classList.add('active');
            tabBtns[0].classList.remove('active');
        }
    }

    window.onclick = function (event) {
        const overlay = document.getElementById('loginOverlay');
        if (event.target === overlay) {
            overlay.style.display = 'none';
        }
    }
</script>

<script>
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        if (input.type === "password") {
            input.type = "text"; // show password
        } else {
            input.type = "password"; // hide password
        }
    }
</script>

</html>