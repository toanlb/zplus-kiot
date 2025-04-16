<?php

/* @var $this \yii\web\View */
/* @var $content string */

use pos\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<?php $this->beginBody() ?>

<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark navbar-primary">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item">
                <a href="<?= Url::to(['/site/index']) ?>" class="nav-link">
                    <i class="fas fa-home"></i> Trang chủ
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= Url::to(['/pos/index']) ?>" class="nav-link">
                    <i class="fas fa-cash-register"></i> POS
                </a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="<?= Url::to(['/images/user-avatar.png']) ?>" class="user-image img-circle elevation-2" alt="User Image">
                    <span class="d-none d-md-inline"><?= Yii::$app->user->identity->username ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- User image -->
                    <li class="user-header bg-primary">
                        <img src="<?= Url::to(['/images/user-avatar.png']) ?>" class="img-circle elevation-2" alt="User Image">
                        <p>
                            <?= Yii::$app->user->identity->username ?>
                            <small>Nhân viên bán hàng</small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <a href="#" class="btn btn-default btn-flat">Thông tin cá nhân</a>
                        <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline']) 
                            . Html::submitButton(
                                'Đăng xuất',
                                ['class' => 'btn btn-default btn-flat float-right']
                            )
                            . Html::endForm() ?>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="<?= Url::home() ?>" class="brand-link navbar-primary">
            <img src="<?= Url::to(['/images/logo.png']) ?>" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">POS Bán Hàng</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="<?= Url::to(['/images/user-avatar.png']) ?>" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?= Yii::$app->user->identity->username ?></a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="<?= Url::to(['/site/index']) ?>" class="nav-link <?= $this->context->id == 'site' && $this->context->action->id == 'index' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Trang chủ</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Url::to(['/pos/index']) ?>" class="nav-link <?= $this->context->id == 'pos' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Bán hàng (POS)</p>
                        </a>
                    </li>
                    <li class="nav-header">QUẢN LÝ</li>
                    <li class="nav-item">
                        <a href="<?=  Url::to(['/transactionhistory'])?>" class="nav-link">
                            <i class="nav-icon fas fa-history"></i>
                            <p>
                                Lịch sử giao dịch
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Url::to(['/pos-session/index']) ?>" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Quản lý ca làm việc
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">HỆ THỐNG</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Cài đặt
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <?= Html::beginForm(['/site/logout'], 'post') 
                            . Html::submitButton(
                                '<i class="nav-icon fas fa-sign-out-alt"></i><p>Đăng xuất</p>',
                                ['class' => 'nav-link btn btn-link logout', 'style' => 'text-align: left;']
                            )
                            . Html::endForm() ?>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"><?= Html::encode($this->title) ?></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <?= Breadcrumbs::widget([
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            'options' => ['class' => 'breadcrumb float-sm-right'],
                            'itemTemplate' => '<li class="breadcrumb-item">{link}</li>',
                            'activeItemTemplate' => '<li class="breadcrumb-item active">{link}</li>'
                        ]) ?>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?= $content ?>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <strong>Copyright &copy; <?= date('Y') ?> <a href="#">POS Bán Hàng</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>
<!-- ./wrapper -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>