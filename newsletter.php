<?php
//Escape for HTML output
function esc_html(string $stringToChange): string
{
    return htmlspecialchars($stringToChange, ENT_QUOTES, 'UTF-8');
}
$user = '';
$email = '';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = filter_input(INPUT_POST, 'user', FILTER_UNSAFE_RAW); //string|null
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL); //string|false|null

    if ($user === null || trim($user) === '') {
        $errors['user'] = "Username is required";
    }
    if ($email === null || trim($email) === '') {
        $errors['email'] = "Email is required";
    }
    if (empty($errors)) {
        // example of PRG - POST -> REDIRECT -> GET
        $qs  = 'ok=1&user=' . urlencode($user) . '&email=' . urlencode($email); //qs = query string; urlencode = makes spaces in typed up info into a special character as URLs do not have spaces
        header('Location: newsletter.php' . $qs);
        exit;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Newsletter</title>
    <?php require __DIR__ . '/includes/bootstrapcdnlinks.php'; ?>
</head>

<body class="p-3">
    <?php require __DIR__ . '/includes/navigation.php'; ?>
    <div class="container">
        <h1>Newsletter</h1>

        <!-- FLASH MESSAGE -->
        <?php if (isset($_GET['ok']) && $_GET['ok'] === '1'): ?>
            <div class="alert alert-success">
                Thanks <?= esc_html($_GET['user'] ?? 'friend') ?>. Subscribed as <?= esc_html($_GET['email'] ?? '') ?>
            </div>
        <?php endif; ?>
        <!-- END FLASH MESSAGE -->

        <!-- Error message -->
        <?php if ($errors): ?>
            <div class="alert alert-danger">
                Please fix:
                <ul class="mb-0">
                    <?php foreach ($errors as $msg): ?>
                        <li> <?= $msg; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $errors): // This and below meant to take info put into newsletter and outputs it into an array. Putting user info here isn't good, but below shows it to us so we know what it is about
        ?>
            <form action="newsletter.php" method="post" class="mb-3">
                <label class="form-label">Username
                    <!-- sticky form -->
                    <input class="form-control" type="text" name="user" value="<?= esc_html($user) ?>">
                </label>
                <label class="form-label mt-2">Email
                    <input class="form-control" type="text" name="email" value="<?= esc_html($email) ?>">
                </label>
                <button class="btn btn-primary mt-3" type="submit">Subscribe</button>
            </form>
        <?php else: ?>
            <h2>Raw POST</h2>
            <pre><?php var_dump($_POST); ?></pre>
        <?php endif; ?>
    </div>
</body>

</html>