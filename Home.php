<?php
/*  DB: fetch recent projects  */
require_once __DIR__ . '/admin/db.php';

try {
    $db             = get_db();
    $recentProjects = $db->query(
        'SELECT * FROM projects WHERE published = 1 ORDER BY sort_order ASC, id ASC LIMIT 10'
    )->fetchAll();
} catch (PDOException $e) {
    $recentProjects = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aleksandra's Portfolio</title>
  <link rel="icon" type="image/png" href="uploads/icon.png">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <?php include 'php/nav.php'; ?>

  <section class="bio">
    <img class="profileImg" src="uploads/headshot.jpg" alt="A picture of me">
    <div>
      <h2 data-translate="greeting">Hello! I am Aleksandra Marchewka. I am a Software Developer.</h2>
      <p data-translate="bio">I've been coding since I was 13 and recently finished my undergraduate degree in computer science. I have an interest in fullstack development and machine learning.</p>
      <a class="portfolio" href="Projects.php" data-translate="seeMyWork">See my work</a>
      <a class="portfolio" href="Resume.php" data-translate="checkResume">Check Out My Resume</a>
    </div>
  </section>

  <section class="skills-section">
    <h2 data-translate="myStack">My Stack</h2>
    <ul class="skills">
      <li><a href="Projects.php?tag=Python">Python</a></li>
      <li><a href="Projects.php?tag=Java">Java</a></li>
      <li><a href="Projects.php?tag=C%23">C#</a></li>
      <li><a href="Projects.php?tag=C%2B%2B">C++</a></li>
      <li><a href="Projects.php?tag=JavaScript">JavaScript</a></li>
      <li><a href="Projects.php?tag=HTML">HTML</a></li>
      <li><a href="Projects.php?tag=CSS">CSS</a></li>
      <li><a href="Projects.php?tag=Laravel">Laravel</a></li>
      <li><a href="Projects.php?tag=Unity">Unity</a></li>
      <li><a href="Projects.php?tag=Android+Studio">Android Studio</a></li>
      <li><a href="Projects.php?tag=SQL">SQL</a></li>
      <li><a href="Projects.php?tag=Docker">Docker</a></li>
      <li><a href="Projects.php?tag=Git">Git</a></li>
      <li><a href="Projects.php?tag=Machine+Learning">Machine Learning</a></li>
    </ul>
  </section>

  <section class="recentWork">
    <h2 data-translate="recentWork">Recent Work</h2>
    <p data-translate="recentWorkText">Currently I am refactoring and improving my projects from university. Links to completed work will be added shortly.</p>

    <div class="carousel-wrapper">
      <button class="carousel-btn carousel-btn--prev" aria-label="Previous">&#8249;</button>
      <div class="carousel-track-container">
        <ul class="carousel-track">

          <?php foreach ($recentProjects as $rp):
            $rTags       = json_decode($rp['tags'],   true) ?: [];
            $rImages     = json_decode($rp['images'], true) ?: [];
            $rThumb      = $rImages[0] ?? 'uploads/placeholder.jpg';
            $displayTags = array_slice($rTags, 0, 4);
            $extraCount  = count($rTags) - count($displayTags);
          ?>
          <li class="carousel-card"
              data-id="<?= htmlspecialchars($rp['slug']) ?>"
              data-title-en="<?= htmlspecialchars($rp['title_en']) ?>"
              data-title-pl="<?= htmlspecialchars($rp['title_pl']) ?>"
              data-desc-en="<?= htmlspecialchars($rp['desc_en']) ?>"
              data-desc-pl="<?= htmlspecialchars($rp['desc_pl']) ?>">
            <img class="carousel-card__img" src="<?= htmlspecialchars($rThumb) ?>" alt="<?= htmlspecialchars($rp['title_en']) ?> preview">
            <div class="carousel-card__header">
              <div class="carousel-card__icon"><i class="<?= htmlspecialchars($rp['icon']) ?>"></i></div>
              <h4 class="carousel-card__title"><?= htmlspecialchars($rp['title_en']) ?></h4>
            </div>
            <p class="carousel-card__desc"><?= htmlspecialchars($rp['desc_en']) ?></p>
            <div class="carousel-card__bottom">
              <a href="Projects.php?open=<?= htmlspecialchars($rp['slug']) ?>" class="view-project-btn">
                <span data-translate="viewProject">View Project</span>
                <i class="fas fa-arrow-right"></i>
              </a>
              <div class="carousel-card__tags">
                <?php foreach ($displayTags as $rt): ?>
                  <span><?= htmlspecialchars($rt) ?></span>
                <?php endforeach; ?>
                <?php if ($extraCount > 0): ?>
                  <span class="tag-and-more">+<?= $extraCount ?> more</span>
                <?php endif; ?>
              </div>
            </div>
          </li>
          <?php endforeach; ?>

          <?php if (empty($recentProjects)): ?>
          <li class="carousel-card">
            <img class="carousel-card__img" src="uploads/placeholder.jpg" alt="Project preview">
            <div class="carousel-card__header">
              <div class="carousel-card__icon"><i class="fas fa-code"></i></div>
              <h4 class="carousel-card__title" data-translate="proj1Title">Emotion-Driven Unity Game</h4>
            </div>
            <p class="carousel-card__desc" data-translate="proj1Desc">Dissertation project combining facial emotion detection, eye tracking and speech recognition to drive adaptive gameplay in Unity.</p>
            <div class="carousel-card__bottom">
              <a href="Projects.php" class="view-project-btn">
                <span data-translate="viewProject">View Project</span>
                <i class="fas fa-arrow-right"></i>
              </a>
              <div class="carousel-card__tags"><span>Python</span><span>C#</span><span>Unity</span></div>
            </div>
          </li>
          <?php endif; ?>

        </ul>
      </div>
      <button class="carousel-btn carousel-btn--next" aria-label="Next">&#8250;</button>
    </div>
    <div class="carousel-dots" id="carousel-dots"></div>
  </section>

  <?php include 'php/footer.php'; ?>

  <script src="js/translations.js"></script>
  <script src="js/script.js"></script>
  <script src="js/Carousel.js"></script>
  <script src="js/home.js"></script>
</body>
</html>