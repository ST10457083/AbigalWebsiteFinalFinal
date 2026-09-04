<?php
// Start session to check admin status if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isAdminLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
$isAdminPage = strpos($_SERVER['REQUEST_URI'], '/admin/') !== false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Abigail Beauty Bar - Professional hair, makeup, bridal and nails services">
    <title><?= htmlspecialchars($pageTitle ?? 'Abigail Beauty Bar') ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght,ital@9..144,300..700,0..1&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= $baseUrl ?>style.css">
    
    <!-- Admin specific styles -->
    <?php if ($isAdminPage): ?>
    <style>
        .admin-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9rem;
            opacity: 0.6;
            transition: opacity 0.2s ease;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            color: var(--ink);
        }
        .admin-link:hover {
            opacity: 1;
            background: var(--rose-pale);
            color: var(--plum);
        }
        .admin-badge {
            display: inline-block;
            background: var(--rose-pale);
            color: var(--plum);
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
            margin-left: 4px;
        }
        .admin-link.dashboard-link {
            border-color: var(--rose);
            color: var(--rose);
        }
        .admin-link.dashboard-link .admin-badge {
            background: var(--rose);
            color: var(--ivory);
        }
    </style>
    <?php endif; ?>
</head>
<body>
    <header class="site-header">
        <div class="wrap">
            <a href="<?= $baseUrl ?>index.php" class="wordmark">Abigail <em>Beauty Bar</em></a>
            
            <?php if ($isAdminPage): ?>
                <!-- Simple back button for admin pages -->
                <a href="<?= $baseUrl ?>index.php" style="font-size:0.9rem;opacity:0.6;text-decoration:none;font-family:'Work Sans',sans-serif;">← Back to site</a>
            <?php else: ?>
                <!-- Full navigation for main site -->
                <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-label="Toggle navigation">
                    ☰
                </button>
                <nav class="main-nav" id="mainNav">
                    <a href="<?= $baseUrl ?>index.php" <?= $current === 'home' ? 'aria-current="page"' : '' ?>>Home</a>
                    <a href="<?= $baseUrl ?>services.php" <?= $current === 'services' ? 'aria-current="page"' : '' ?>>Services</a>
                    <a href="<?= $baseUrl ?>gallery.php" <?= $current === 'gallery' ? 'aria-current="page"' : '' ?>>Gallery</a>
                    <a href="<?= $baseUrl ?>booking.php" <?= $current === 'booking' ? 'aria-current="page"' : '' ?> class="nav-cta">Book now</a>
                    <a href="<?= $baseUrl ?>contact.php" <?= $current === 'contact' ? 'aria-current="page"' : '' ?>>Contact</a>
                    
                    <!-- SMART ADMIN BUTTON -->
                    <?php if ($isAdminLoggedIn): ?>
                        <a href="<?= $baseUrl ?>admin/dashboard.php" class="admin-link dashboard-link" style="border-bottom:2px solid var(--rose);">
                            Dashboard
                            <span class="admin-badge">Admin</span>
                        </a>
                    <?php else: ?>
                        <a href="<?= $baseUrl ?>admin/login.php" class="admin-link" style="border-bottom-color:transparent;">
                             Admin
                        </a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        </div>
    </header>
    <main>