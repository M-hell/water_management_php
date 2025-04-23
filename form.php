<?php
// Database connection
// $conn = new mysqli('sql106.infinityfree.com', 'if0_38530000', 'CghLSOtRVY', 'if0_38530000_testphp');
$conn = new mysqli('localhost', 'root', '', 'testphp');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle request tracking
$requests = [];
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['track_email'])) {
    $email = $conn->real_escape_string($_GET['track_email']);
    $sql = "SELECT * FROM registration WHERE email = '$email' ORDER BY id DESC";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $requests = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us - Progressive Enviro Care</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
      :root {
        --primary: #0077b6;
        --secondary: #00b4d8;
        --accent: #ff6b35;
        --light: #f8f9fa;
        --dark: #212529;
        --gray: #6c757d;
        --section-padding: 80px 0;
      }

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
      }

      body {
        background-color: #f5f7fa;
        color: var(--dark);
        line-height: 1.6;
      }

      .container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
      }

      /* Navbar */
      .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 40px;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
      }

      .logo img {
        height: 50px;
      }

      .nav-links {
        display: flex;
        gap: 30px;
      }

      .nav-links a {
        text-decoration: none;
        color: var(--dark);
        font-weight: 500;
        transition: color 0.3s;
        position: relative;
      }

      .nav-links a:hover {
        color: var(--primary);
      }

      .nav-links a::after {
        content: "";
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 0;
        height: 2px;
        background-color: var(--primary);
        transition: width 0.3s;
      }

      .nav-links a:hover::after {
        width: 100%;
      }

      .contact-button a {
        background-color: var(--primary);
        color: white;
        padding: 10px 20px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
      }

      .contact-button a:hover {
        background-color: var(--secondary);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      .hamburger {
        display: none;
        font-size: 24px;
        cursor: pointer;
      }

      /* Hero Section */
      .contact-hero {
        background: linear-gradient(
            135deg,
            rgba(0, 119, 182, 0.9),
            rgba(0, 180, 216, 0.9)
          ),
          url("images/water-contact.jpg");
        background-size: cover;
        background-position: center;
        color: white;
        padding: 100px 0;
        text-align: center;
      }

      .contact-hero h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
      }

      .contact-hero p {
        font-size: 18px;
        max-width: 700px;
        margin: 0 auto;
        opacity: 0.9;
      }

      /* Contact Form */
      .contact-section {
        padding: var(--section-padding);
      }

      .contact-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
        align-items: start;
      }

      .contact-form {
        background-color: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      }

      .form-group {
        margin-bottom: 25px;
      }

      .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--dark);
      }

      .form-control {
        width: 100%;
        padding: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        font-size: 16px;
        transition: all 0.3s;
      }

      .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.1);
        outline: none;
      }

      textarea.form-control {
        min-height: 150px;
        resize: vertical;
      }

      .submit-btn {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
        box-shadow: 0 4px 15px rgba(0, 119, 182, 0.2);
      }

      .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 119, 182, 0.3);
      }

      /* Contact Info */
      .contact-info {
        background-color: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      }

      .contact-info h2 {
        font-size: 28px;
        margin-bottom: 30px;
        color: var(--primary);
        position: relative;
        padding-bottom: 15px;
      }

      .contact-info h2::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background-color: var(--accent);
      }

      .info-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 25px;
      }

      .info-icon {
        width: 50px;
        height: 50px;
        background-color: rgba(0, 180, 216, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        flex-shrink: 0;
      }

      .info-icon svg {
        width: 24px;
        height: 24px;
        fill: var(--primary);
      }

      .info-content h3 {
        font-size: 18px;
        margin-bottom: 5px;
        color: var(--dark);
      }

      .info-content p,
      .info-content a {
        color: var(--gray);
        text-decoration: none;
        transition: color 0.3s;
      }

      .info-content a:hover {
        color: var(--primary);
      }

      /* Map */
      .map-container {
        height: 300px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-top: 40px;
      }

      .map-container iframe {
        width: 100%;
        height: 100%;
        border: none;
      }

      /* Footer */
      footer {
        background-color: var(--dark);
        color: white;
        padding: 60px 0 0;
      }

      .footer-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 40px;
        padding-bottom: 60px;
      }

      .footer-logo {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
      }

      .footer-logo svg {
        width: 40px;
        height: 40px;
        fill: var(--accent);
        margin-right: 10px;
      }

      .footer-logo-text {
        font-size: 20px;
        font-weight: 700;
      }

      .footer-about p {
        color: #cbd5e0;
        margin-bottom: 20px;
        line-height: 1.6;
      }

      .social-links {
        display: flex;
        gap: 15px;
      }

      .social-links a {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
      }

      .social-links a:hover {
        background-color: var(--accent);
        transform: translateY(-3px);
      }

      .footer-column h3 {
        font-size: 18px;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 10px;
      }

      .footer-column h3::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 2px;
        background-color: var(--accent);
      }

      .footer-links {
        list-style: none;
      }

      .footer-links li {
        margin-bottom: 10px;
      }

      .footer-links a {
        color: #cbd5e0;
        text-decoration: none;
        transition: color 0.3s;
      }

      .footer-links a:hover {
        color: white;
        padding-left: 5px;
      }

      .footer-contact p {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
        color: #cbd5e0;
      }

      .footer-contact svg {
        width: 18px;
        height: 18px;
        fill: var(--accent);
        margin-right: 10px;
        margin-top: 3px;
      }

      .footer-bottom {
        text-align: center;
        padding: 20px 0;
        background-color: rgba(0, 0, 0, 0.2);
        font-size: 14px;
        color: #cbd5e0;
      }

      /* Responsive */
      @media (max-width: 992px) {
        .nav-links {
          display: none;
        }

        .hamburger {
          display: block;
        }

        .contact-hero h1 {
          font-size: 36px;
        }
      }

      @media (max-width: 768px) {
        .contact-hero {
          padding: 80px 0;
        }

        .contact-container {
          grid-template-columns: 1fr;
        }
      }

      @media (max-width: 576px) {
        .navbar {
          padding: 15px 20px;
        }

        .contact-hero h1 {
          font-size: 28px;
        }

        .contact-form,
        .contact-info {
          padding: 30px 20px;
        }
        .nav-link {
      position: relative;
    }
    
    .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -2px;
      left: 0;
      background-color: #0ea5e9;
      transition: width 0.3s ease;
    }
    
    .nav-link:hover::after {
      width: 100%;
    }
      }
    </style>
  </head>

<body>
<nav class="bg-white shadow-lg sticky top-0 z-50 bg-glass">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-20 items-center">
            <div class="flex-shrink-0 flex items-center">
              <img src="images/logo.jpg" alt="Logo" class="h-12 w-auto rounded-lg">
            </div>
            <div class="hidden md:block">
              <div class="ml-10 flex items-center space-x-8">
                <a href="index.html" class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-300">Home</a>
                <a href="aboutUs.html" class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-300">About Us</a>
                <a href="services.html" class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-300">Services</a>
                <a href="projects.html" class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-300">Projects</a>
                <a href="admin.php" class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-300">Admin</a>
                <a href="form.html" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-300 transform hover:scale-105 shadow-md">Contact Us</a>
              </div>
            </div>
            <div class="md:hidden">
              <button id="hamburger" class="text-gray-700 hover:text-blue-600 focus:outline-none text-2xl">
                &#9776;
              </button>
            </div>
          </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white shadow-lg">
          <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="index.html" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">Home</a>
            <a href="aboutUs.html" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">About Us</a>
            <a href="services.html" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">Services</a>
            <a href="projects.html" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">Projects</a>
            <a href="form.html" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700">Contact Us</a>
          </div>
        </div>
      </nav>

    <section class="contact-hero">
      <div class="container">
        <h1>Get In Touch With Us</h1>
        <p>
          Have questions about our water management solutions? Reach out to our
          team of experts for personalized consultation and support.
        </p>
      </div>
    </section>
    <section class="request-section">
        <div class="container">
            <div class="request-box">
                <h2>Check Your Requests</h2>
                <form method="GET">
                    <div class="form-group">
                        <input type="email" name="track_email" 
                               placeholder="Enter your email to view requests" required>
                    </div>
                    <button type="submit" class="submit-btn">View Requests</button>
                </form>

                <?php if (!empty($requests)): ?>
                    <div class="requests-list">
                        <?php foreach ($requests as $request): ?>
                            <div class="request-item">
                                <div class="request-header">
                                    
                                    <span class="status-badge <?php echo $request['response'] ? 'responded' : 'pending' ?>">
                                        <?php echo $request['response'] ? 'Responded' : 'Pending' ?>
                                    </span>
                                </div>
                                <div class="request-body">
                                    <p><strong>Your Message:</strong> <?php echo htmlspecialchars($request['message']) ?></p>
                                    <?php if ($request['response']): ?>
                                        <div class="admin-response">
                                            <strong>Admin Response:</strong>
                                            <?php echo nl2br(htmlspecialchars($request['response'])) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php elseif (isset($_GET['track_email'])): ?>
                    <p class="no-requests">No requests found for this email address.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="contact-section">
      <div class="container">
        <div class="contact-container">
          <div class="contact-form">
            <h2>Send Us a Message</h2>
            <form action="connect.php" method="get">
              <div class="form-group">
                <label for="name">Full Name</label>
                <input
                  type="text"
                  id="name"
                  name="name"
                  class="form-control"
                  placeholder="Enter your name"
                  required
                />
              </div>

              <div class="form-group">
                <label for="email">Email Address</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  class="form-control"
                  placeholder="Enter your email"
                  required
                />
              </div>

              <div class="form-group">
                <label for="phone">Phone Number</label>
                <input
                  type="tel"
                  id="phone"
                  name="number"
                  class="form-control"
                  placeholder="Enter your phone number"
                />
              </div>

              <div class="form-group">
                <label for="subject">Company name</label>
                <input
                  type="text"
                  id="subject"
                  name="company"
                  class="form-control"
                  placeholder="What's this about?"
                />
              </div>

              <div class="form-group">
                <label for="message">Your Message</label>
                <textarea
                  id="message"
                  name="message"
                  class="form-control"
                  placeholder="How can we help you?"
                  required
                ></textarea>
              </div>

              <button type="submit" class="submit-btn">Send Message</button>
            </form>
          </div>

          <div class="contact-info">
            <h2>Contact Information</h2>

            <div class="info-item">
              <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path
                    d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"
                  ></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
              </div>
              <div class="info-content">
                <h3>Our Location</h3>
                <p>Indore, Madhya Pradesh, India</p>
              </div>
            </div>

            <div class="info-item">
              <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path
                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                  ></path>
                </svg>
              </div>
              <div class="info-content">
                <h3>Phone Number</h3>
                <a href="tel:08044566420">08044566420</a>
              </div>
            </div>

            <div class="info-item">
              <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path
                    d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                  ></path>
                  <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
              </div>
              <div class="info-content">
                <h3>Email Address</h3>
                <a href="mailto:Progressiveenvirocare@gmail.com"
                  >Progressiveenvirocare@gmail.com</a
                >
              </div>
            </div>

            <div class="info-item">
              <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
              </div>
              <div class="info-content">
                <h3>Working Hours</h3>
                <p>Monday - Friday: 9:00 AM - 6:00 PM</p>
                <p>Saturday: 10:00 AM - 4:00 PM</p>
              </div>
            </div>
          </div>
        </div>

        <div class="map-container">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d235526.9092454033!2d75.7247619!3d22.7239117!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3962fcad1b410ddb%3A0x96ec4da356240f4!2sIndore%2C%20Madhya%20Pradesh!5e0!3m2!1sen!2sin!4v1620000000000!5m2!1sen!2sin"
            allowfullscreen=""
            loading="lazy"
          ></iframe>
        </div>
      </div>
    </section>

    <footer>
      <div class="container">
        <div class="footer-content">
          <div class="footer-about">
            <div class="footer-logo">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                  d="M12 2C12 2 5 12 5 16C5 19.3137 7.68629 22 11 22C14.3137 22 17 19.3137 17 16C17 12 12 2 12 2Z"
                ></path>
              </svg>
              <div class="footer-logo-text">Progressive Enviro Care</div>
            </div>
            <p>
              Leading provider of water management solutions for industrial and
              municipal applications.
            </p>
            <div class="social-links">
              <a href="#"><i class="fab fa-facebook-f"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
              <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
          </div>

          <div class="footer-column">
            <h3>Quick Links</h3>
            <ul class="footer-links">
              <li><a href="index.html">Home</a></li>
              <li><a href="aboutUs.html">About Us</a></li>
              <li><a href="services.html">Services</a></li>
              <li><a href="projects.html">Projects</a></li>
              <li><a href="form.html">Contact</a></li>
            </ul>
          </div>

          <div class="footer-column">
            <h3>Services</h3>
            <ul class="footer-links">
              <li><a href="#">Industrial Treatment</a></li>
              <li><a href="#">Municipal Management</a></li>
              <li><a href="#">Water Analysis</a></li>
              <li><a href="#">Maintenance</a></li>
              <li><a href="#">Consulting</a></li>
            </ul>
          </div>

          <div class="footer-column">
            <h3>Contact</h3>
            <div class="footer-contact">
              <p>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path
                    d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"
                  ></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Indore, Madhya Pradesh, India
              </p>
              <p>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path
                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                  ></path>
                </svg>
                08044566420
              </p>
              <p>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path
                    d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                  ></path>
                  <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                Progressiveenvirocare@gmail.com
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <div class="container">
          © 2017 Progressive Enviro Care Pvt. Ltd. All rights reserved.
        </div>
      </div>
    </footer>

    <script>
      document
        .getElementById("hamburger")
        .addEventListener("click", function () {
          const navLinks = document.querySelector(".nav-links");
          navLinks.classList.toggle("active");
        });
    </script>
    <style>
        .request-section {
            padding: 40px 0;
        }

        .request-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
            margin: 0 auto;
            max-width: 800px;
        }

        .requests-list {
            margin-top: 25px;
        }

        .request-item {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            background: #f9f9f9;
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .request-date {
            color: #666;
            font-size: 0.9em;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 500;
        }

        .status-badge.pending {
            background: #ff6b35;
            color: white;
        }

        .status-badge.responded {
            background: #00b4d8;
            color: white;
        }

        .admin-response {
            margin-top: 15px;
            padding: 15px;
            background: #e3f2fd;
            border-radius: 8px;
            border-left: 4px solid #0077b6;
        }

        .no-requests {
            text-align: center;
            color: #666;
            margin-top: 20px;
        }
    </style>

    <!-- EXISTING SCRIPTS REMAIN UNCHANGED -->
</body>
</html>
<?php $conn->close(); ?>