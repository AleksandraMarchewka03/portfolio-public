<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog - Aleksandra's Portfolio</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="blog.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <?php include 'nav.php'; ?>

  <section class="blog-header">
    <h2 data-translate="blogTitle">Blog</h2>
    <p data-translate="blogSubtitle">Thoughts on technology, projects, and development</p>
    
    <!-- Tag Filter -->
    <div class="tag-filter">
      <button class="tag-btn active" data-tag="all" data-translate="allPosts">All Posts</button>
      <button class="tag-btn" data-tag="tech">Tech</button>
      <button class="tag-btn" data-tag="projects" data-translate="projectsTag">Projects</button>
      <button class="tag-btn" data-tag="tutorial" data-translate="tutorialTag">Tutorial</button>
      <button class="tag-btn" data-tag="machine-learning">Machine Learning</button>
      <button class="tag-btn" data-tag="web-dev">Web Dev</button>
    </div>
  </section>

  <!-- Two Column Blog Layout -->
  <div class="blog-container">
    <div class="blog-column" id="column-left">
      <!-- Posts will be loaded here via JavaScript -->
    </div>
    <div class="blog-column" id="column-right">
      <!-- Posts will be loaded here via JavaScript -->
    </div>
  </div>

  <!-- Modal for Full Post -->
  <div class="modal-overlay" id="post-modal">
    <div class="modal-content">
      <button class="modal-close" id="modal-close">×</button>
      <div class="modal-post-header">
        <div class="modal-post-date" id="modal-post-date"></div>
        <h1 class="modal-post-title" id="modal-post-title"></h1>
        <div class="modal-post-tags" id="modal-post-tags"></div>
      </div>
      <div class="modal-post-content" id="modal-post-content">
        <!-- Full post content will be loaded here -->
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script src="translations.js"></script>
  <script src="script.js"></script>
  <script src="blog.js"></script>
</body>
</html>