<?php
/*  DB: fetch all published projects + collect unique tags  */
require_once __DIR__ . '/admin/db.php';

try {
    $db       = get_db();
    $projects = $db->query(
        'SELECT * FROM projects WHERE published = 1 ORDER BY sort_order ASC, id ASC'
    )->fetchAll();
} catch (PDOException $e) {
    $projects = [];
    error_log('Projects.php DB error: ' . $e->getMessage());
}

$allTags = [];
foreach ($projects as $p) {
    foreach (json_decode($p['tags'], true) ?: [] as $t) {
        if (!in_array($t, $allTags)) $allTags[] = $t;
    }
}
sort($allTags);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Projects - Aleksandra's Portfolio</title>
  <link rel="icon" type="image/png" href="uploads/icon.png">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <?php include 'php/nav.php'; ?>

  <section class="projects-section">
    <h2 data-translate="allProjects">All Projects</h2>

    <div class="search-bar-wrap">
      <i class="fas fa-search"></i>
      <input class="projects-search" type="text" id="projectSearch"
             data-translate-placeholder="searchPlaceholder" placeholder="Search projects…">
    </div>

    <div class="tag-filters" id="tagFilters">
      <?php foreach ($allTags as $tag): ?>
        <button class="tag-filter" data-tag="<?= htmlspecialchars($tag) ?>"><?= htmlspecialchars($tag) ?></button>
      <?php endforeach; ?>
      <button class="clear-filters" id="clearFilters">✕ Clear</button>
    </div>

    <div class="projects-grid" id="projectsGrid">

      <?php foreach ($projects as $p):
        $tags       = json_decode($p['tags'],   true) ?: [];
        $images     = json_decode($p['images'], true) ?: [];
        $thumb      = $images[0] ?? 'uploads/placeholder.jpg';
        $tagsJson   = htmlspecialchars(json_encode($tags),   ENT_QUOTES);
        $imagesJson = htmlspecialchars(json_encode($images), ENT_QUOTES);
      ?>
      <div class="project-card"
           data-id="<?= htmlspecialchars($p['slug']) ?>"
           data-tags='<?= $tagsJson ?>'
           data-images='<?= $imagesJson ?>'
           data-title-en="<?= htmlspecialchars($p['title_en']) ?>"
           data-title-pl="<?= htmlspecialchars($p['title_pl']) ?>"
           data-desc-en="<?= htmlspecialchars($p['desc_en']) ?>"
           data-desc-pl="<?= htmlspecialchars($p['desc_pl']) ?>"
           data-detail-en="<?= htmlspecialchars($p['detail_en']) ?>"
           data-detail-pl="<?= htmlspecialchars($p['detail_pl']) ?>"
           data-icon="<?= htmlspecialchars($p['icon']) ?>"
           data-github="<?= htmlspecialchars($p['github_url']) ?>"
           data-live="<?= htmlspecialchars($p['live_url']) ?>">

        <img class="project-card__image"
             src="<?= htmlspecialchars($thumb) ?>"
             alt="<?= htmlspecialchars($p['title_en']) ?>">

        <div class="project-card__body">
          <div class="project-card__header">
            <div class="project-card__icon"><i class="<?= htmlspecialchars($p['icon']) ?>"></i></div>
            <h4 class="project-card__title"><?= htmlspecialchars($p['title_en']) ?></h4>
          </div>
          <p class="project-card__desc"><?= htmlspecialchars($p['desc_en']) ?></p>
          <div class="project-card__bottom">
            <div class="project-card__btns">
              <button class="view-details-btn">
                <span data-translate="viewDetails">View Details</span>
                <i class="fas fa-arrow-right"></i>
              </button>
              <a class="card-icon-btn <?= $p['github_url'] ? '' : 'disabled' ?>"
                 href="<?= htmlspecialchars($p['github_url'] ?: '#') ?>"
                 target="<?= $p['github_url'] ? '_blank' : '' ?>"
                 rel="noopener" aria-label="GitHub" title="GitHub"
                 <?= $p['github_url'] ? '' : 'onclick="return false;"' ?>>
                <i class="fab fa-github"></i>
              </a>
              <a class="card-icon-btn <?= $p['live_url'] ? '' : 'disabled' ?>"
                 href="<?= htmlspecialchars($p['live_url'] ?: '#') ?>"
                 target="<?= $p['live_url'] ? '_blank' : '' ?>"
                 rel="noopener" aria-label="Live Demo" title="Live Demo"
                 <?= $p['live_url'] ? '' : 'onclick="return false;"' ?>>
                <i class="fas fa-external-link-alt"></i>
              </a>
            </div>
            <div class="project-card__tags">
              <?php foreach ($tags as $t): ?>
                <span><?= htmlspecialchars($t) ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

      <?php if (empty($projects)): ?>
        <p style="grid-column:1/-1;text-align:center;padding:3rem 0;opacity:0.5;">
          No projects found. Add some via the admin panel.
        </p>
      <?php endif; ?>

      <div class="no-results" id="noResults" style="display:none;">No projects match your filters.</div>
    </div>
  </section>

  <!--  Detail Modal  -->
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal" id="modal">
      <button class="modal__close" id="modalClose" aria-label="Close">✕</button>
      <div class="modal__detail" id="modalDetail">
        <div class="modal__text">
          <div class="modal__header">
            <div class="modal__header-left">
              <div class="modal__icon"><i id="modalIcon"></i></div>
              <h3 class="modal__title" id="modalTitle"></h3>
            </div>
            <div class="modal__header-links" id="modalHeaderLinks"></div>
          </div>
          <div class="modal__desc" id="modalDesc"></div>
          <div class="modal__tags" id="modalTags"></div>
          <div class="modal__links" id="modalLinks"></div>
        </div>
        <div class="modal__images" id="modalImages"></div>
      </div>
    </div>
  </div>

  <?php include 'php/footer.php'; ?>

  <script src="js/translations.js"></script>
  <script src="js/script.js"></script>
  <script src="js/projects.js"></script>
</body>
</html>
