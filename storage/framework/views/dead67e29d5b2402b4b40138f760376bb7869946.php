<?php $__env->startSection('content'); ?>
    <form class="layui-form" action="<?php echo e(route('home.member.register')); ?>" method="post">
        <?php echo e(csrf_field()); ?>

        <div class="layui-form-item">
            <label for="" class="layui-form-label">昵称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" value="<?php echo e($member->name ?? old('name')); ?>" lay-verify="required" placeholder="请输入昵称" class="layui-input" >
            </div>
        </div>

        <div class="layui-form-item">
            <label for="" class="layui-form-label">手机号</label>
            <div class="layui-input-inline">
                <input type="text" name="phone" value="<?php echo e($member->phone??old('phone')); ?>" required="phone" lay-verify="phone" placeholder="请输入手机号" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="" class="layui-form-label">密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password" placeholder="请输入密码" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="" class="layui-form-label">确认密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password_confirmation" placeholder="请输入密码" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="" class="layui-form-label">验证码</label>
            <div class="layui-input-inline">
                <input type="text" name="captcha"  lay-verify="required" class="layui-input">
            </div>
            <div class="layui-input-inline">
                <img src="<?php echo e(captcha_src()); ?>" alt="">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">注册</button>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('home.base', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>