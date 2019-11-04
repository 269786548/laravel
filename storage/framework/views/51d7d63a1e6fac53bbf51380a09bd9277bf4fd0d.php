<?php $__env->startSection('content'); ?>
    <h2>无权限访问</h2>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.base', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>