body {
  --background:   var(--light-background);
  --primary:      var(--light-primary);
  --link:         var(--light-link);
  --button-bg:    var(--light-button-bg);
  --button-text:  var(--light-button-text);
  --nav-bg:       var(--light-nav-bg);
  --section-1:    var(--light-section-1);
  --section-2:    var(--light-section-2);
  --border:       var(--light-border);
}

<?php
// admin/index.php -Login gate
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass = $_POST['password'] ?? '';
    $hash = '$2y$10$N0USVl0mu2PpwHNUNS/tguwclYiu.7.loJrnqV3aJ20UZ241salAO';

    if (password_verify($pass, $hash)) {
        $_SESSION['admin_authed'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Incorrect password.';
    }
}

if ($_SESSION['admin_authed'] ?? false) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login</title>
  <link rel="icon" type="image/png" href="../uploads/icon.png">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    /* Login page only  */
    body { display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .login-box {
      background: var(--section-1);
      border: 2px solid var(--border);
      border-radius: 1rem;
      padding: 2.5rem;
      max-width: 380px;
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
    }
    .login-box h1      { margin: 0; font-size: 1.4rem; font-weight: 700; text-align: center; }
    .login-box label   { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.65; }
    .login-box input[type=password] {
      width: 100%; box-sizing: border-box;
      padding: 0.6rem 0.85rem; margin-top: 0.3rem;
      border: 2px solid var(--border); border-radius: 0.6rem;
      background: var(--button-bg); color: var(--button-text);
      font-size: 0.95rem; font-family: inherit; outline: none;
      transition: border-color 0.2s;
    }
    .login-box input[type=password]:focus { border-color: var(--link); }
    .login-btn {
      padding: 0.65rem; background: var(--button-bg); color: var(--button-text);
      border: 2px solid var(--border); border-radius: 0.6rem;
      font-size: 0.95rem; font-weight: 700; font-family: inherit;
      cursor: pointer; transition: background 0.2s, color 0.2s, border-color 0.2s;
    }
    .login-btn:hover { background: var(--link); color: var(--background); border-color: var(--link); }
    .error { color: red; font-size: 0.85rem; font-weight: 600; text-align: center; }
  </style>
</head>
<body>
  <div class="login-box">
    <h1><i class="fas fa-lock"></i> Admin</h1>
    <?php if (!empty($error)): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
      <label>Password</label>
      <input type="password" name="password" autofocus required>
      <br><br>
      <button type="submit" class="login-btn">Sign In</button>
    </form>
    <p style="font-size:0.75rem;opacity:0.5;text-align:center;margin:0;">
      See <code>admin/set_password.php</code> to set your password hash.
    </p>
  </div>
  <script src="../js/translations.js"></script>
  <script src="../js/script.js"></script>
</body>
</html>
