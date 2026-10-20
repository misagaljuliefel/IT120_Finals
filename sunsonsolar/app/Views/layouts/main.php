<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $this->renderSection('title') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/SunSonSolar.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/SunSonSolarPortal.css') ?>" />
</head>
<body>
    <?= $this->renderSection('content') ?>

    <script src="<?= base_url('assets/SunSonSolarData.js') ?>"></script>
    <script src="<?= base_url('assets/SunSonSolarPortal.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
