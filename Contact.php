<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact - Aleksandra's Portfolio</title>
  <link rel="icon" type="image/png" href="uploads/icon.png">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
</head>
<body class="page-contact">
  <?php include 'php/nav.php'; ?>

  <section class="contact-section">

    <div class="contact-left">

      <div id="contact-map"></div>

      <div class="contact-info">
        <div>
          <h2 data-translate="name">Aleksandra Marchewka</h2>
          <p class="contact-tagline">
            <span data-translate="contactTaglinePart2">Based in London · Open to relocation to Poland</span>
          </p>
        </div>

        <div class="contact-detail">
          <div class="detail-icon"><i class="fas fa-envelope"></i></div>
          <div class="detail-text">
            <span id="em-label" style="font-weight:600;"></span>
          </div>
        </div>

        <div class="contact-detail">
          <div class="detail-icon"><i class="fas fa-phone"></i></div>
          <div class="detail-text">
            <span>
              <span id="ph-label" style="font-weight:600;"></span>
              <span class="note-badge" data-translate="pleaseText">Please text instead of calling</span>
            </span>
          </div>
        </div>

        <div class="contact-detail">
          <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div class="detail-text">
            <span data-translate="contactLocation">London N4, UK</span>
          </div>
        </div>

        <div class="contact-detail">
          <div class="detail-icon"><i class="fas fa-graduation-cap"></i></div>
          <div class="detail-text">
            <span data-translate="contactUni">Swansea University</span>
            <span style="font-size:0.8rem;opacity:0.65;" data-translate="contactDegree">Computer Science BSc · 2:1 with Honours</span>
          </div>
        </div>

        <div class="social-links">
          <a class="social-btn" href="https://www.linkedin.com/in/aleksandrajowitamarchewka/" target="_blank" rel="noopener">
            <i class="fab fa-linkedin-in"></i> <span data-translate="linkedin">LinkedIn</span>
          </a>
          <a class="social-btn" href="https://github.com/AleksandraMarchewka03" target="_blank" rel="noopener">
            <i class="fab fa-github"></i> <span data-translate="github">GitHub</span>
          </a>
        </div>
      </div>
    </div>

    <div class="contact-right">
      <h3 data-translate="getInTouch">Get in touch</h3>

      <form id="contact-form" novalidate>

        <div class="form-row">
          <div class="form-group">
            <label for="cf-name" data-translate="formName">Your name</label>
            <input type="text" id="cf-name" data-translate-placeholder="formNamePlaceholder" placeholder="Jane Smith" required>
          </div>
          <div class="form-group">
            <label for="cf-email" data-translate="formEmail">Your email</label>
            <input type="email" id="cf-email" data-translate-placeholder="formEmailPlaceholder" placeholder="jane@example.com" required>
          </div>
        </div>

        <div class="form-group" style="margin-top:0.75rem;">
          <label for="cf-contact">
            <span data-translate="formContact">Best way to reach you back</span>
            <span style="font-weight:400;opacity:0.6;" data-translate="formOptional">(optional)</span>
          </label>
          <input type="text" id="cf-contact" data-translate-placeholder="formContactPlaceholder" placeholder="Phone number, LinkedIn URL…">
        </div>

        <div class="form-group" style="margin-top:0.75rem;">
          <label for="cf-message" data-translate="formMessage">Message</label>
          <textarea id="cf-message" data-translate-placeholder="formMessagePlaceholder" placeholder="Hi Aleksandra, I'd love to chat about…" required></textarea>
        </div>

        <div class="form-footer">
          <button type="submit" class="submit-btn">
            <i class="fas fa-paper-plane"></i>
            <span data-translate="sendMessage">Send Message</span>
          </button>
        </div>

      </form>
    </div>

  </section>

  <?php include 'php/footer.php'; ?>

  <script src="js/translations.js"></script>
  <script src="js/script.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="js/contact.js"></script>
</body>
</html>
