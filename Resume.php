<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CV - Aleksandra's Portfolio</title>
  <link rel="icon" type="image/png" href="uploads/icon.png">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <?php include 'php/nav.php'; ?>

  <section class="resume-section">
    <h2 data-translate="cvTitle">My Resume</h2>

    <div class="pdf-container">
      <iframe id="resume-viewer" src="uploads/AJM_CV_EN.pdf" type="application/pdf"></iframe>
    </div>

    <div class="download-buttons">
      <h3 data-translate="downloadResume">Download Resume</h3>
      <div class="button-group">
        <a href="uploads/AJM_CV_EN.pdf" download="Aleksandra_Marchewka_Resume_EN.pdf" class="download-btn">
          <i class="fas fa-download"></i>
          <span data-translate="downloadEnglish">Download English Version</span>
        </a>
        <a href="uploads/AJM_CV_PL.pdf" download="Aleksandra_Marchewka_CV_PL.pdf" class="download-btn">
          <i class="fas fa-download"></i>
          <span data-translate="downloadPolish">Download Polish Version</span>
        </a>
      </div>
    </div>
  </section>

  <?php include 'php/footer.php'; ?>

  <script src="js/translations.js"></script>
  <script src="js/script.js"></script>
  <script src="js/resume.js"></script>
</body>
</html>