<?php
// admin/project_form.php — Create or edit a project
session_start();
if (!($_SESSION['admin_authed'] ?? false)) { header('Location: index.php'); exit; }

require_once __DIR__ . '/db.php';
$db = get_db();

$id      = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$editing = $id > 0;
$project = null;
$errors  = [];

//  Load existing project if editing 
if ($editing) {
    $stmt = $db->prepare('SELECT * FROM projects WHERE id = ?');
    $stmt->execute([$id]);
    $project = $stmt->fetch();
    if (!$project) { header('Location: dashboard.php?tab=projects'); exit; }
}

//  Ensure upload directory exists 
$uploadDir = __DIR__ . '/../uploads/projects/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

//  Handle form submit 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'slug'       => trim($_POST['slug']       ?? ''),
        'title_en'   => trim($_POST['title_en']   ?? ''),
        'title_pl'   => trim($_POST['title_pl']   ?? ''),
        'desc_en'    => trim($_POST['desc_en']     ?? ''),
        'desc_pl'    => trim($_POST['desc_pl']     ?? ''),
        'detail_en'  => trim($_POST['detail_en']  ?? ''),
        'detail_pl'  => trim($_POST['detail_pl']  ?? ''),
        'icon'       => trim($_POST['icon']        ?? 'fas fa-code'),
        'github_url' => trim($_POST['github_url']  ?? ''),
        'live_url'   => trim($_POST['live_url']    ?? ''),
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
        'published'  => isset($_POST['published']) ? 1 : 0,
    ];

    $tagsRaw = trim($_POST['tags_raw'] ?? '');
    $tags    = array_values(array_filter(array_map('trim', explode(',', $tagsRaw))));

    if ($data['slug'] === '')     $errors[] = 'Slug is required.';
    if ($data['title_en'] === '') $errors[] = 'English title is required.';
    if ($data['desc_en'] === '')  $errors[] = 'English short description is required.';

    if (empty($errors)) {
        $slugCheck = $db->prepare('SELECT id FROM projects WHERE slug = ? AND id != ?');
        $slugCheck->execute([$data['slug'], $id]);
        if ($slugCheck->fetch()) $errors[] = 'Slug already in use — choose a different one.';
    }

    $existingImages = $editing ? ($_POST['keep_images'] ?? []) : [];

    $newImages = [];
    if (!empty($_FILES['new_images']['name'][0])) {
        $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
        foreach ($_FILES['new_images']['tmp_name'] as $i => $tmp) {
            if ($_FILES['new_images']['error'][$i] !== UPLOAD_ERR_OK) continue;
            $mime = mime_content_type($tmp);
            if (!in_array($mime, $allowed)) { $errors[] = 'Only JPG, PNG, WebP and GIF images allowed.'; continue; }
            $ext  = pathinfo($_FILES['new_images']['name'][$i], PATHINFO_EXTENSION);
            $name = 'uploads/projects/' . uniqid($data['slug'] . '_') . '.' . strtolower($ext);
            if (move_uploaded_file($tmp, __DIR__ . '/../' . $name)) $newImages[] = $name;
        }
    }

    $allImages = array_merge($existingImages, $newImages);

    if (empty($errors)) {
        $data['tags']   = json_encode($tags);
        $data['images'] = json_encode($allImages);

        if ($editing) {
            $sql = 'UPDATE projects SET slug=:slug, title_en=:title_en, title_pl=:title_pl,
                    desc_en=:desc_en, desc_pl=:desc_pl, detail_en=:detail_en, detail_pl=:detail_pl,
                    icon=:icon, tags=:tags, images=:images, github_url=:github_url, live_url=:live_url,
                    sort_order=:sort_order, published=:published WHERE id=:id';
            $data['id'] = $id;
        } else {
            $sql = 'INSERT INTO projects (slug, title_en, title_pl, desc_en, desc_pl, detail_en, detail_pl,
                    icon, tags, images, github_url, live_url, sort_order, published)
                    VALUES (:slug,:title_en,:title_pl,:desc_en,:desc_pl,:detail_en,:detail_pl,
                    :icon,:tags,:images,:github_url,:live_url,:sort_order,:published)';
        }
        $db->prepare($sql)->execute($data);
        header('Location: dashboard.php?tab=projects&msg=' . ($editing ? 'updated' : 'created'));
        exit;
    }

    $project = array_merge($project ?? [], $data, ['tags' => json_encode($tags), 'images' => json_encode($allImages)]);
}

//  Defaults for new project 
if (!$project) {
    $project = ['slug'=>'','title_en'=>'','title_pl'=>'','desc_en'=>'','desc_pl'=>'',
                'detail_en'=>'','detail_pl'=>'','icon'=>'fas fa-code','tags'=>'[]','images'=>'[]',
                'github_url'=>'','live_url'=>'','sort_order'=>0,'published'=>1];
}

$tags   = implode(', ', json_decode($project['tags'],   true) ?: []);
$images = json_decode($project['images'], true) ?: [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $editing ? 'Edit' : 'New' ?> Project — Admin</title>
  <link rel="icon" type="image/png" href="../icon.png">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="form-wrap">
  <div class="form-header">
    <h1><?= $editing ? 'Edit Project' : 'New Project' ?></h1>
    <a class="back-btn" href="dashboard.php?tab=projects"><i class="fas fa-arrow-left"></i> Back</a>
  </div>

  <?php if ($errors): ?>
    <div class="error-box">
      <ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
    </div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">

    <p class="section-title">Identifiers</p>
    <div class="form-grid">
      <div class="field">
        <label>Slug (URL key) *</label>
        <input type="text" name="slug" value="<?= htmlspecialchars($project['slug']) ?>" placeholder="my-cool-project" required>
        <small>Lowercase, hyphens only. Used in ?open= URL param.</small>
      </div>
      <div class="field">
        <label>Font Awesome Icon class</label>
        <input type="text" name="icon" value="<?= htmlspecialchars($project['icon']) ?>" placeholder="fas fa-code">
        <small>e.g. fas fa-gamepad · fas fa-mobile-alt · fab fa-github</small>
      </div>
    </div>

    <p class="section-title">English Content</p>
    <div class="field">
      <label>Title (EN) *</label>
      <input type="text" name="title_en" value="<?= htmlspecialchars($project['title_en']) ?>" required>
    </div>
    <div class="field">
      <label>Short description (EN) * — shown on card</label>
      <textarea name="desc_en"><?= htmlspecialchars($project['desc_en']) ?></textarea>
    </div>
    <div class="field">
      <label>Full detail (EN) — shown in modal (HTML allowed)</label>
      <textarea class="tall" name="detail_en"><?= htmlspecialchars($project['detail_en']) ?></textarea>
    </div>

    <p class="section-title">Polish Content</p>
    <div class="field">
      <label>Title (PL)</label>
      <input type="text" name="title_pl" value="<?= htmlspecialchars($project['title_pl']) ?>">
    </div>
    <div class="field">
      <label>Short description (PL) — shown on card</label>
      <textarea name="desc_pl"><?= htmlspecialchars($project['desc_pl']) ?></textarea>
    </div>
    <div class="field">
      <label>Full detail (PL) — shown in modal (HTML allowed)</label>
      <textarea class="tall" name="detail_pl"><?= htmlspecialchars($project['detail_pl']) ?></textarea>
    </div>

    <p class="section-title">Tags</p>
    <div class="field">
      <label>Tags (comma-separated)</label>
      <input type="text" name="tags_raw" value="<?= htmlspecialchars($tags) ?>" placeholder="Python, C#, Unity">
    </div>

    <p class="section-title">Images</p>
    <?php if ($images): ?>
      <p style="font-size:0.85rem;margin:0 0 0.5rem;opacity:0.7;">Uncheck to remove an image on save:</p>
      <div class="image-thumbs">
        <?php foreach ($images as $img): ?>
          <div class="image-thumb">
            <img src="../<?= htmlspecialchars($img) ?>" alt="">
            <label title="Keep this image">
              <input type="checkbox" name="keep_images[]" value="<?= htmlspecialchars($img) ?>" checked>
            </label>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <div class="field" style="margin-top:0.75rem;">
      <label>Upload new images</label>
      <input type="file" name="new_images[]" multiple accept="image/*" style="padding:0.4rem;">
      <small>JPG, PNG, WebP or GIF. First image used as card thumbnail.</small>
    </div>

    <p class="section-title">Links & Settings</p>
    <div class="form-grid">
      <div class="field">
        <label>GitHub URL</label>
        <input type="url" name="github_url" value="<?= htmlspecialchars($project['github_url']) ?>" placeholder="https://github.com/…">
      </div>
      <div class="field">
        <label>Live Demo URL</label>
        <input type="url" name="live_url" value="<?= htmlspecialchars($project['live_url']) ?>" placeholder="https://…">
      </div>
      <div class="field">
        <label>Sort order (lower = first)</label>
        <input type="number" name="sort_order" value="<?= (int)$project['sort_order'] ?>" min="0">
      </div>
      <div class="field" style="justify-content:flex-end;padding-bottom:0.5rem;">
        <label class="toggle-label">
          <input type="checkbox" name="published" <?= $project['published'] ? 'checked' : '' ?>>
          Published (visible on site)
        </label>
      </div>
    </div>

    <div style="margin-top:1.5rem;">
      <button type="submit" class="submit-btn">
        <i class="fas fa-save"></i> <?= $editing ? 'Save Changes' : 'Create Project' ?>
      </button>
    </div>
  </form>
</div>

<script src="../js/translations.js"></script>
<script src="../js/script.js"></script>
</body>
</html>
