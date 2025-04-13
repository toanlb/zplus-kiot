<?php
use yii\helpers\Html;

$this->title = 'Vai trò: ' . $role->name;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý phân quyền', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="rbac-view-role">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-edit"></i> Cập nhật quyền', ['update-role-permissions', 'name' => $role->name], ['class' => 'btn btn-info btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>Tên vai trò:</th>
                    <td><?= $role->name ?></td>
                </tr>
                <tr>
                    <th>Mô tả:</th>
                    <td><?= $role->description ?></td>
                </tr>
            </table>
            
            <h4 class="mt-4">Quyền của vai trò</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tên quyền</th>
                            <th>Mô tả</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($permissions as $permission): ?>
                        <tr>
                            <td><?= $permission->name ?></td>
                            <td><?= $permission->description ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <h4 class="mt-4">Người dùng có vai trò này</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên đăng nhập</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="3">Không có người dùng nào được gán vai trò này.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user->id ?></td>
                                <td><?= $user->username ?></td>
                                <td><?= $user->email ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>