<?php
<style>
    /* Feedback Section */
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
<section class="feedback-section" id="feedback">
  <h2>💬 What Our Buyers Say</h2>

  <div class="feedback-card-container">
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
  </div>
</section>

?>