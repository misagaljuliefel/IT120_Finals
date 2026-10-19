<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $this->renderSection('title') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/SunSonSolar.css') ?>" />
    <?= $this->renderSection('head') ?>
</head>
<body>
    <?= $this->renderSection('content') ?>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
