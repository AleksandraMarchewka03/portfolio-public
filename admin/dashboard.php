<?php
// admin/dashboard.php
session_start();
if (!($_SESSION['admin_authed'] ?? false)) { header('Location: index.php'); exit; }

require_once __DIR__ . '/db.php';

$db  = get_db();
$tab = $_GET['tab'] ?? 'projects';

// ── Handle project delete ──
if ($tab === 'projects' && isset($_GET['delete'])) {
    $db->prepare('DELETE FROM projects WHERE id = ?')->execute([(int)$_GET['delete']]);
    header('Location: dashboard.php?tab=projects&msg=deleted');
    exit;
}

// ── Handle inquiry status update ──
if ($tab === 'inquiries' && isset($_POST['set_status'])) {
    $db->prepare('UPDATE inquiries SET status = ? WHERE id = ?')
       ->execute([$_POST['status'], (int)$_POST['id']]);
    header('Location: dashboard.php?tab=inquiries');
    exit;
}

// ── Handle inquiry delete ──
if ($tab === 'inquiries' && isset($_GET['delete'])) {
    $db->prepare('DELETE FROM inquiries WHERE id = ?')->execute([(int)$_GET['delete']]);
    header('Location: dashboard.php?tab=inquiries');
    exit;
}

// ── Logout ──
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

$projects  = $db->query('SELECT * FROM projects ORDER BY sort_order, id')->fetchAll();
$inquiries = $db->query('SELECT * FROM inquiries ORDER BY created_at DESC')->fetchAll();
$newCount  = count(array_filter($inquiries, fn($i) => $i['status'] === 'new'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>
  <link rel="icon" type="image/png" href="../icon.png">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="admin-header">
  <h1><i class="fas fa-tools"></i> Portfolio Admin</h1>
  <div class="admin-tabs">
    <a class="admin-tab <?= $tab === 'projects'  ? 'active' : '' ?>" href="?tab=projects">
      <i class="fas fa-code"></i> Projects
    </a>
    <a class="admin-tab <?= $tab === 'inquiries' ? 'active' : '' ?>" href="?tab=inquiries">
      <i class="fas fa-envelope"></i> Inquiries
      <?php if ($newCount > 0): ?><span class="badge"><?= $newCount ?></span><?php endif; ?>
    </a>
  </div>
  <a class="logout-btn" href="?logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="admin-body">

<?php if ($tab === 'projects'): ?>
  <!-- ══ PROJECTS TAB ══ -->
  <a class="new-project-btn" href="project_form.php">
    <i class="fas fa-plus"></i> New Project
  </a>

  <table class="admin-table">
    <thead>
      <tr>
        <th>Order</th>
        <th>Title</th>
        <th>Tags</th>
        <th>Images</th>
        <th>Links</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($projects as $p):
      $tags   = json_decode($p['tags'],   true) ?: [];
      $images = json_decode($p['images'], true) ?: [];
    ?>
      <tr>
        <td><?= (int)$p['sort_order'] ?></td>
        <td>
          <strong><?= htmlspecialchars($p['title_en']) ?></strong><br>
          <span style="font-size:0.78rem;opacity:0.6;"><?= htmlspecialchars($p['title_pl']) ?></span>
        </td>
        <td><?= implode(', ', array_map('htmlspecialchars', $tags)) ?></td>
        <td><?= count($images) ?></td>
        <td>
          <?= $p['github_url'] ? '<i class="fab fa-github" title="GitHub"></i>' : '—' ?>
          <?= $p['live_url']   ? ' <i class="fas fa-external-link-alt" title="Live"></i>' : '' ?>
        </td>
        <td>
          <span class="published-dot <?= $p['published'] ? 'dot-on' : 'dot-off' ?>"></span>
          <?= $p['published'] ? 'Live' : 'Hidden' ?>
        </td>
        <td style="white-space:nowrap;">
          <a class="action-btn" href="project_form.php?id=<?= $p['id'] ?>">
            <i class="fas fa-edit"></i> Edit
          </a>
          <a class="action-btn danger"
             href="?tab=projects&delete=<?= $p['id'] ?>"
             onclick="return confirm('Delete <?= htmlspecialchars(addslashes($p['title_en'])) ?>?')">
            <i class="fas fa-trash"></i> Delete
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (empty($projects)): ?>
      <tr><td colspan="7" style="text-align:center;padding:2rem;opacity:0.5;">No projects yet.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>

<?php elseif ($tab === 'inquiries'): ?>
  <!-- ══ INQUIRIES TAB ══ -->
  <table class="admin-table">
    <thead>
      <tr>
        <th>Date</th>
        <th>Name</th>
        <th>Email</th>
        <th>Contact</th>
        <th>Message</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($inquiries as $inq): ?>
      <tr style="cursor:pointer;" onclick="toggleRow(<?= $inq['id'] ?>)">
        <td><?= date('d M Y', strtotime($inq['created_at'])) ?></td>
        <td><?= htmlspecialchars($inq['sender_name']) ?></td>
        <td>
          <a href="mailto:<?= htmlspecialchars($inq['sender_email']) ?>" onclick="event.stopPropagation()">
            <?= htmlspecialchars($inq['sender_email']) ?>
          </a>
        </td>
        <td><?= htmlspecialchars($inq['best_contact']) ?: '—' ?></td>
        <td><div class="msg-short"><?= htmlspecialchars($inq['message']) ?></div></td>
        <td>
          <span class="status-badge status-<?= $inq['status'] ?>"><?= $inq['status'] ?></span>
        </td>
        <td style="white-space:nowrap;" onclick="event.stopPropagation()">
          <form method="POST" style="display:inline;">
            <input type="hidden" name="id" value="<?= $inq['id'] ?>">
            <select name="status" style="font-size:0.75rem;padding:0.2rem;border:1px solid var(--border);border-radius:0.4rem;background:var(--button-bg);color:var(--button-text);">
              <?php foreach (['new','read','replied','archived'] as $s): ?>
                <option value="<?= $s ?>" <?= $s === $inq['status'] ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit" name="set_status" class="action-btn" style="margin-left:0.25rem;">Save</button>
          </form>
          <a class="action-btn danger"
             href="?tab=inquiries&delete=<?= $inq['id'] ?>"
             onclick="return confirm('Delete this inquiry?')">
            <i class="fas fa-trash"></i>
          </a>
        </td>
      </tr>
      <tr class="expand-row" id="row-<?= $inq['id'] ?>">
        <td colspan="7"><?= htmlspecialchars($inq['message']) ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (empty($inquiries)): ?>
      <tr><td colspan="7" style="text-align:center;padding:2rem;opacity:0.5;">No inquiries yet.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
<?php endif; ?>

</div>

<script src="../js/translations.js"></script>
<script src="../js/script.js"></script>
<script>
  function toggleRow(id) {
    document.getElementById('row-' + id).classList.toggle('open');
  }
</script>
</body>
</html>
