<?php

use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/hail812/yii2-adminlte3/src/web');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> | <?= Yii::$app->name ?></title>
    <!-- Font Awesome 6 -->
     
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- AdminLTE 3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <?php $this->head() ?>
</head>
<body class="hold-transition sidebar-mini">
<?php $this->beginBody() ?>

<div class="wrapper">
    <!-- Header -->
    <?= $this->render('parts/header', ['assetDir' => $assetDir]) ?>

    <!-- Sidebar -->
    <?= $this->render('parts/sidebar', ['assetDir' => $assetDir]) ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Breadcrumb -->
        <?= $this->render('parts/breadcrumb', ['title' => $this->title]) ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?= $content ?>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <?= $this->render('parts/footer') ?>
</div>

<!-- Scripts -->
<?= $this->render('parts/scripts') ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    // Khởi tạo các widget AdminLTE
    if (typeof $.fn.Treeview !== 'undefined') {
        $('[data-widget="treeview"]').Treeview('init');
    }
});
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>