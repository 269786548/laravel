<?php $__env->startSection('content'); ?>
    <style>
        .cate-box{margin-bottom: 15px;padding-bottom:10px;border-bottom: 1px solid #f0f0f0}
        .cate-box dt{margin-bottom: 10px;}
        .cate-box dt .cate-first{padding:10px 20px}
        .cate-box dd{padding:0 50px}
        .cate-box dd .cate-second{margin-bottom: 10px}
        .cate-box dd .cate-third{padding:0 40px;margin-bottom: 10px}
    </style>
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>用户 【<?php echo e($user->name); ?>】分配直接权限，直接权限与角色拥有的角色权限不冲突</h2>
        </div>
        <div class="layui-card-body">
            <form action="<?php echo e(route('admin.user.assignPermission',['user'=>$user])); ?>" method="post" class="layui-form">
                <?php echo e(csrf_field()); ?>

                <?php echo e(method_field('put')); ?>

                <?php $__empty_1 = true; $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $first): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <dl class="cate-box">
                        <dt>
                        <div class="cate-first"><input id="menu<?php echo e($first['id']); ?>" type="checkbox" name="permissions[]" value="<?php echo e($first['id']); ?>" title="<?php echo e($first['display_name']); ?>" lay-skin="primary" <?php echo e($first['own']??''); ?> ></div>
                        </dt>
                        <?php if(isset($first['_child'])): ?>
                            <?php $__currentLoopData = $first['_child']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $second): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <dd>
                                    <div class="cate-second"><input id="menu<?php echo e($first['id']); ?>-<?php echo e($second['id']); ?>" type="checkbox" name="permissions[]" value="<?php echo e($second['id']); ?>" title="<?php echo e($second['display_name']); ?>" lay-skin="primary" <?php echo e($second['own']??''); ?>></div>
                                    <?php if(isset($second['_child'])): ?>
                                        <div class="cate-third">
                                            <?php $__currentLoopData = $second['_child']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thild): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input type="checkbox" id="menu<?php echo e($first['id']); ?>-<?php echo e($second['id']); ?>-<?php echo e($thild['id']); ?>" name="permissions[]" value="<?php echo e($thild['id']); ?>" title="<?php echo e($thild['display_name']); ?>" lay-skin="primary" <?php echo e($thild['own']??''); ?>>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>
                                </dd>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </dl>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div style="text-align: center;padding:20px 0;">
                        无数据
                    </div>
                <?php endif; ?>
                <div class="layui-form-item">
                    <button type="submit" class="layui-btn" lay-submit="" >确 认</button>
                    <a href="<?php echo e(route('admin.user')); ?>"  class="layui-btn" >返 回</a>
                </div>

            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        form.on('checkbox', function (data) {
            var check = data.elem.checked;//是否选中
            var checkId = data.elem.id;//当前操作的选项框
            if (check) {
                //选中
                var ids = checkId.split("-");
                if (ids.length == 3) {
                    //第三极菜单
                    //第三极菜单选中,则他的上级选中
                    $("#" + (ids[0] + '-' + ids[1])).prop("checked", true);
                    $("#" + (ids[0])).prop("checked", true);
                } else if (ids.length == 2) {
                    //第二季菜单
                    $("#" + (ids[0])).prop("checked", true);
                    $("input[id*=" + ids[0] + '-' + ids[1] + "]").each(function (i, ele) {
                        $(ele).prop("checked", true);
                    });
                } else {
                    //第一季菜单不需要做处理
                    $("input[id*=" + ids[0] + "-]").each(function (i, ele) {
                        $(ele).prop("checked", true);
                    });
                }
            } else {
                //取消选中
                var ids = checkId.split("-");
                if (ids.length == 2) {
                    //第二极菜单
                    $("input[id*=" + ids[0] + '-' + ids[1] + "]").each(function (i, ele) {
                        $(ele).prop("checked", false);
                    });
                } else if (ids.length == 1) {
                    $("input[id*=" + ids[0] + "-]").each(function (i, ele) {
                        $(ele).prop("checked", false);
                    });
                }
            }
            form.render();
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.base', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>