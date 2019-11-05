{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">昵称</label>
    <div class="layui-input-inline">
                <select name="name">
                    <option value="xuexue">小赵</option>
                    <option value="shenshen">小申</option>
                </select>
            </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">花费</label>
    <div class="layui-input-inline">
        <input type="text" name="avatar" value="{{$member->avatar??old('avatar')}}"  lay-verify="required" placeholder="请输入金额" class="layui-input">
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">备注</label>
    <div class="layui-input-inline">
        <input type="text" name="remark" value="{{$member->remark??old('remark')}}"  lay-verify="required"  class="layui-input">
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">消费日期</label>
    <div class="layui-input-inline">
        <input type="text" name = 'pay_time' value="<?php echo date('Y-m-d');?>"class="layui-input" id="test6"  lay-key="1" lay-verify="required">
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.member')}}" >返 回</a>
    </div>
</div>