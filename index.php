<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>farm2home</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    .logo img{
      height: 60px;
      width: auto;
      margin-left: 25px;
    }
    .feedback-section {
      background: #f8f9fa;
      padding: 60px 40px;
      text-align: center;
    }

    .feedback-section h2 {
      font-size: 2rem;
      color: #2d6a4f;
      margin-bottom: 40px;
      font-weight: 700;
    }

    /* Card Container */
    .feedback-card-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      justify-items: center;
    }

    /* Individual Feedback Card */
    .feedback-card {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      padding: 20px;
      width: 100%;
      max-width: 350px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      text-align: left;
    }

    .feedback-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    /* Card Content */
    .feedback-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .feedback-header h3 {
      font-size: 1.2rem;
      color: #2d6a4f;
      font-weight: 600;
    }

    .feedback-date {
      font-size: 0.85rem;
      color: #777;
    }

    .feedback-subject {
      font-size: 1rem;
      color: #1b4332;
      margin-bottom: 8px;
    }

    .feedback-message {
      font-size: 0.95rem;
      color: #333;
      line-height: 1.5;
    }

    .no-feedback {
      color: #555;
      font-size: 1rem;
      margin-top: 20px;
      text-align: center;
      font-style: italic;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .feedback-section {
        padding: 40px 20px;
      }

      .feedback-card {
        max-width: 100%;
      }
    }

  </style>
</head>

<body>

  <header>
    <div class="logo"><img src="img\logo.png" alt="logo here"></div>
    <nav>
      <ul>
        <li><a href="#"><i class="fa-solid fa-house"></i> Home</a></li>
        <li><a href="#about""><i class=" fa-solid fa-circle-info"></i> About</a></li>
        <li><a href="javascript:void(0)" onclick="openLoginPopup()"><i class="fa-solid fa-user"></i> Login</a></li>
        <li><a href="#translate"><i class="fa-solid fa-language"></i> Translate/भाषा बदला</a></li>
        <li><a href="contact.php"><i class="fa-solid fa-phone"></i> Contact</a></li>
      </ul>
    </nav>
  </header>

  <div class="lightbox" id="translate">
    <a href="#" class="close"><i class="fa-solid fa-xmark"></i></a>
    <div class="popup-content">
      <div class="int-container">
        <div class="inst-eng">
          <h2>How to Translate this Page</h2>
          <div class="instructions">
            <h3>💻 On Laptop/Desktop:</h3>
            <ol>
              <li>Right-click anywhere on the page.</li>
              <li>Select <strong>“Translate to [Your Language]”</strong> from the menu.</li>
              <li>If you don’t see it, click the <strong>Google Translate icon</strong> in your browser’s address bar.
              </li>
            </ol>
          </div>

          <div class="instructions">
            <h3>📱 On Mobile (Chrome / Other Browsers):</h3>
            <ol>
              <li>Tap the <strong>three dots</strong> in the top-right corner of your browser.</li>
              <li>Select <strong>“Translate…”</strong> from the menu.</li>
              <li>Choose your preferred language to view the page.</li>
            </ol>
          </div>
        </div>

        <div class="inst-mar">
          <h2>हे पान कसे भाषांतरित करावे</h2>
          <div class="instructions">
            <h3>💻 लॅपटॉप / डेस्कटॉप वर:</h3>
            <ol>
              <li>पृष्ठावर कुठेही <strong>राइट-क्लिक</strong> करा.</li>
              <li>मेनूमधून <strong>“Translate to [तुमची भाषा]”</strong> हा पर्याय निवडा.</li>
              <li>जर हा पर्याय दिसत नसेल तर ब्राउझरच्या अ‍ॅड्रेस बारमधील <strong>Google Translate</strong> आयकॉनवर क्लिक
                करा.</li>
            </ol>
          </div>

          <div class="instructions">
            <h3>📱 मोबाईलवर (Chrome / इतर ब्राउझर):</h3>
            <ol>
              <li>ब्राउझरच्या वरच्या उजव्या कोपऱ्यातील <strong>तीन डॉट्स</strong> (⋮) वर टॅप करा.</li>
              <li>मेनूमधून <strong>“Translate…”</strong> हा पर्याय निवडा.</li>
              <li>तुमची पसंतीची भाषा निवडा आणि पृष्ठ भाषांतरित होईल.</li>
            </ol>
          </div>
        </div>
      </div>

    </div>
  </div>

  <section class="hero">
    <div class="hero-text">
      <h1>Fresh Crops Direct from Farmers</h1>
      <p>Buy and sell crops easily and securely on our platform.</p>
      <p style="font-family: cursive;">Start your journey as a …</p>
      <a href="farmer_register.php" class="btn" title="Click To Register..">Farmer</a>
      <a href="buyer_register.php" class="btn" title="Click To Register..">Buyer</a>
    </div>
  </section>

  <section class="about" id="about">
    <div class="about-container">

      <div class="about-header">
        <h2>About Our Platform</h2>
        <p>
          Our Farmer Crop Selling Platform bridges the gap between farmers and consumers,
          providing fair prices for farmers and fresh produce for buyers.
          We are committed to supporting local agriculture and promoting sustainability.
        </p>
      </div>

      <div class="about-details">
        <div class="about-card">
          <h3><i class="fa-solid fa-flag"></i> Our Mission</h3>
          <p>
            To empower farmers with technology, enabling them to reach a wider market,
            improve their income, and provide consumers with fresh and affordable produce.
          </p>
        </div>

        <div class="about-card">
          <h3><i class="fa-solid fa-rocket"></i> Our Vision</h3>
          <p>
            To build a trusted and sustainable digital marketplace that connects
            farmers and consumers while fostering transparency and community growth.
          </p>
        </div>

        <div class="about-card">
          <h3><i class="fa-solid fa-bullseye"></i> Our Goals</h3>
          <ul>
            <li>Support farmers with easy online crop listing</li>
            <li>Provide secure payments and fair prices</li>
            <li>Promote organic and sustainable farming</li>
            <li>Build a strong farmer–buyer community</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section class="categories">
    <h2>Our Category</h2>
    <div class="category-container">

      <div class="category-card">
        <img src="img/vege.jpg" alt="Vegetables">
        <h3>Vegetables</h3>
      </div>

      <div class="category-card">
        <img src="img/fruits.jpg" alt="Fruits">
        <h3>Fruits</h3>
      </div>

      <div class="category-card">
        <img src="img/grains.jpg" alt="Grains">
        <h3>Grains</h3>
      </div>

      <div class="category-card">
        <img src="img/pulses.jpg" alt="Pulses">
        <h3>Pulses</h3>
      </div>

      <div class="category-card">
        <img src="img/spices.jpg" alt="Spices">
        <h3>Spices</h3>
      </div>

      <div class="category-card">
        <img src="img/organic.jpg" alt="Organic Products">
        <h3>Organic</h3>
      </div>

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
      <form id="buyerLogin" class="login-form" action="/farm2home/buyer_login.php" method="post">
        <div class="form-group">
          <label for="buyerEmail">Username</label>
          <input type="text" name="input_Busername" id="buyerEmail" placeholder="Enter your Username" required>
        </div>

        <div class="form-group">
          <label for="buyerPassword">Password</label>
          <input type="password" name="input_Bpassword" id="buyerPassword" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="login-btn">Login as Buyer</button>
        
        <p class="register-link">New here? <a href="buyer_register.php">Register as Buyer</a></p>
      </form>
    </div>
  </div>

  <section class="feedback-section" id="feedback">
    <h2><i class="fa-solid fa-message"></i> What Our Buyers Say</h2>
    <div class="feedback-card-container">
      <?php
        include 'db.php';
        $sql = "SELECT name, subject, message, submitted_at FROM feedback ORDER BY submitted_at DESC LIMIT 6";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
                <div class='feedback-card'>
                    <div class='feedback-header'>
                        <h3>" . htmlspecialchars($row['name']) . "</h3>
                        <span class='feedback-date'>" . htmlspecialchars(date('d M Y', strtotime($row['submitted_at']))) . "</span>
                    </div>
                    <h4 class='feedback-subject'>" . htmlspecialchars($row['subject']) . "</h4>
                    <p class='feedback-message'>" . htmlspecialchars($row['message']) . "</p>
                </div>";
            }
        } else {
            echo "<p class='no-feedback'>No feedback available yet. Be the first to share your thoughts!</p>";
        }
      ?>
    </div>
  </section>

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

</html>