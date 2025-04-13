<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý người dùng', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <?php if ($model->avatar): ?>
                            <img class="profile-user-img img-fluid img-circle" src="<?= $model->avatar ?>" alt="User profile picture">
                        <?php else: ?>
                            <img class="profile-user-img img-fluid img-circle" src="/img/default-avatar.png" alt="User profile picture">
                        <?php endif; ?>
                    </div>
                    <h3 class="profile-username text-center"><?= Html::encode($model->full_name) ?></h3>
                    <p class="text-muted text-center"><?= Html::encode($model->position) ?></p>

                    <?php if (Yii::$app->user->can('updateUser')): ?>
                        <?php $form = \yii\widgets\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'action' => ['upload-avatar']]); ?>
                        <?= Html::hiddenInput('id', $model->id) ?>
                        <div class="form-group">
                            <?= Html::fileInput('User[avatar]', null, ['class' => 'form-control', 'accept' => 'image/*']) ?>
                        </div>
                        <div class="form-group">
                            <?= Html::submitButton('Cập nhật ảnh đại diện', ['class' => 'btn btn-primary btn-block']) ?>
                        </div>
                        <?php \yii\widgets\ActiveForm::end(); ?>
                    <?php endif; ?>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Tên đăng nhập</b> <a class="float-right"><?= Html::encode($model->username) ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right"><?= Html::encode($model->email) ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Số điện thoại</b> <a class="float-right"><?= Html::encode($model->phone) ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Vai trò</b> <a class="float-right"><?= $model->getRoleNames() ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Trạng thái</b> <a class="float-right">
                                <?php
                                $statuses = [
                                    User::STATUS_ACTIVE => '<span class="badge badge-success">Đang hoạt động</span>',
                                    User::STATUS_INACTIVE => '<span class="badge badge-warning">Chưa kích hoạt</span>',
                                    User::STATUS_DELETED => '<span class="badge badge-danger">Đã vô hiệu</span>',
                                ];
                                echo $statuses[$model->status] ?? $model->status;
                                ?>
                            </a>
                        </li>
                    </ul>

                    <?php if (Yii::$app->user->can('updateUser')): ?>
                        <?= Html::a('<i class="fas fa-pencil-alt"></i> Cập nhật thông tin', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-block']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">Hồ sơ</a></li>
                        <li class="nav-item"><a class="nav-link" href="#history" data-toggle="tab">Lịch sử đăng nhập</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="profile">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Địa chỉ</strong>
                                    <p class="text-muted"><?= Html::encode($profile->address ?? 'Chưa cập nhật') ?></p>
                                    <hr>

                                    <strong><i class="fas fa-calendar mr-1"></i> Ngày sinh</strong>
                                    <p class="text-muted">
                                        <?= $profile->birthday ? Yii::$app->formatter->asDate($profile->birthday) : 'Chưa cập nhật' ?>
                                    </p>
                                    <hr>

                                    <strong><i class="fas fa-venus-mars mr-1"></i> Giới tính</strong>
                                    <p class="text-muted"><?= Html::encode($profile->gender ?? 'Chưa cập nhật') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-id-card mr-1"></i> CMND/CCCD</strong>
                                    <p class="text-muted"><?= Html::encode($profile->id_card ?? 'Chưa cập nhật') ?></p>
                                    <hr>

                                    <strong><i class="fas fa-building mr-1"></i> Phòng ban</strong>
                                    <p class="text-muted"><?= Html::encode($profile->department ?? 'Chưa cập nhật') ?></p>
                                    <hr>

                                    <strong><i class="fas fa-calendar-check mr-1"></i> Ngày vào làm</strong>
                                    <p class="text-muted">
                                        <?= $profile->hire_date ? Yii::$app->formatter->asDate($profile->hire_date) : 'Chưa cập nhật' ?>
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <strong><i class="fas fa-pencil-alt mr-1"></i> Ghi chú</strong>
                            <p class="text-muted"><?= Html::encode($profile->notes ?? 'Chưa có ghi chú') ?></p>
                        </div>

                        <div class="tab-pane" id="history">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>IP</th>
                                        <th>Trình duyệt</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($loginHistory as $history): ?>
                                    <tr>
                                        <td><?= Yii::$app->formatter->asDatetime($history->login_time) ?></td>
                                        <td><?= Html::encode($history->ip_address) ?></td>
                                        <td><?= Html::encode(substr($history->user_agent, 0, 100)) ?></td>
                                        <td>
                                            <?php if ($history->status): ?>
                                                <span class="badge badge-success">Thành công</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Thất bại</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($loginHistory)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Chưa có lịch sử đăng nhập</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>