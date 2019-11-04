<div class="container">
    <div class="panel-heading">上传文件</div>
    <form class="form-horizontal" method="POST" action="/uploadImg" enctype="multipart/form-data">
        <?php echo e(csrf_field()); ?>           
        <label for="file">选择文件</label>
        <input id="file" type="file" class="form-control" name="file" required>    
        <button type="submit" class="btn btn-primary">确定</button>
    </form>
</div>
