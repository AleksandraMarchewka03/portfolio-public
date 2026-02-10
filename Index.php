<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aleksandra's Portfolio</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <?php include 'nav.php'; ?>

  <section class="bio">
    <img class="profileImg" src="headshot.jpg" alt="A picture of me">
    <div>
      <h2>Hello! I am Aleksandra Marchewka. I am a Software Developer.</h2>
      <p>I've been coding since I was 13 and recently finished my undergraduate degree in computer science. I have an interest in fullstack development and machine learning.</p>
      <a class="portfolio" href="Work.php">See my work</a>
    </div>
  </section>

  <section class="skills">
    <h2>My Stack</h2>
    <ul class="skills">
      <li>Python</li><li>Java</li><li>C#</li><li>C++</li>
      <li>JavaScript</li><li>HTML</li><li>CSS</li><li>Laravel</li>
      <li>Unity</li><li>Android Studio</li><li>SQL</li>
      <li>Docker</li><li>Git</li><li>Machine Learning</li>
    </ul>
  </section>

  <section class="recentWork">
    <h2>Recent Work</h2>
    <p>Currently I am refactoring and improving my projects from university. Links to completed work will be added shortly.</p>
    <a class="portfolio" href="Work.php">See my work</a>
  </section>

  <?php include 'footer.php'; ?>

  <script src="script.js"></script>
</body>
</html>