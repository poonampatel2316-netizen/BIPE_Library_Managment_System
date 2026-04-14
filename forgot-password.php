<?php

declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap.php';

if (current_user()) {
    redirect('user_dashboard.php');
}

$user = current_user();
$admin = current_admin();

if (is_post()) {
    remember_input([
        'email' => (string) ($_POST['email'] ?? ''),
        'name' => (string) ($_POST['name'] ?? ''),
    ]);

    $result = reset_student_password(
        (string) ($_POST['email'] ?? ''),
        (string) ($_POST['name'] ?? ''),
        (string) ($_POST['new_password'] ?? ''),
        (string) ($_POST['confirm_password'] ?? '')
    );

    if ($result['success']) {
        forget_input();
        flash('success', $result['message']);
        flash('password_reset_popup', 'Password changed successfully.');
        redirect('login.php');
    }

    flash('error', $result['message']);
}

$errorMessage = flash('error');
$successMessage = flash('success');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | LMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(url('assets/css/login.css')) ?>">
</head>
<body>
<?php include __DIR__ . '/includes/header.php'; ?>
<div class="overlay"></div>
<main class="auth-page">
    <div class="login-container">
        <div class="logo-area">
            <img src="<?= e(url('assets/images/image.png')) ?>" alt="BIPE Library Management System" class="auth-brand-mark">
            <h2>Forgot Password</h2>
            <p style="max-width: 320px; margin: 10px auto 0; line-height: 1.6;">Enter your registered name and email, then choose a new password.</p>
        </div>

        <?php if ($errorMessage || $successMessage): ?>
            <p style="color: <?= $errorMessage ? '#f87171' : '#d4af37' ?>; text-align: center; margin-bottom: 18px; font-size: 14px; line-height: 1.6;">
                <?= e($errorMessage ?: $successMessage) ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="<?= e(url('forgot-password.php')) ?>">
            <div class="input-group">
                <input type="text" name="name" placeholder="Full Name" value="<?= e(old('name')) ?>" required>
            </div>
            <div class="input-group">
                <input type="email" name="email" placeholder="Registered Email" value="<?= e(old('email')) ?>" required>
            </div>
            <div class="input-group">
                <input type="password" name="new_password" placeholder="New Password" required>
            </div>
            <div class="input-group">
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            </div>
            <button type="submit">Change Password</button>
        </form>

        <div class="signup-link" style="line-height: 1.8;">
            <a href="<?= e(url('login.php')) ?>">Back to Student Login</a><br>
            <a href="<?= e(url('signup.php')) ?>">Create account</a>
        </div>
    </div>
</main>
</body>
</html>
