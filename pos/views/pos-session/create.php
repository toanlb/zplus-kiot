<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model pos\models\PosSession */

$this->title = 'Tạo ca làm việc mới';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý ca làm việc', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pos-session-create">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>