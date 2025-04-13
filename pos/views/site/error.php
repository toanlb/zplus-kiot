<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
?>
<div class="site-error">
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h3 class="card-title">
                <i class="fas fa-exclamation-triangle mr-2"></i> <?= Html::encode($this->title) ?>
            </h3>
        </div>
        <div class="card-body">
            <div class="alert alert-danger">
                <?= nl2br(Html::encode($message)) ?>
            </div>

            <p>
                Có lỗi xảy ra trong quá trình xử lý yêu cầu của bạn.
            </p>
            <p>
                Vui lòng liên hệ với quản trị viên nếu bạn nghĩ đây là lỗi của hệ thống. Cảm ơn.
            </p>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <a href="<?= Url::to(['/site/index']) ?>" class="btn btn-primary btn-block">
                        <i class="fas fa-home mr-2"></i> Trở về trang chủ
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="<?= Url::to(['/pos/index']) ?>" class="btn btn-success btn-block">
                        <i class="fas fa-cash-register mr-2"></i> Trở về màn hình POS
                    </a>
                </div>
            </div>
            
            <?php if (YII_DEBUG): ?>
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-bug mr-2"></i> Chi tiết lỗi</h5>
                    </div>
                    <div class="card-body">
                        <div class="error-details">
                            <pre><?= Html::encode($exception) ?></pre>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>