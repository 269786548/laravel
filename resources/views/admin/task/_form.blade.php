{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">文件上传</label>
    <div class="layui-upload">
      <button type="button" class="layui-btn layui-btn-normal" id="test2">选择文件</button>
      <span  id ='file_up'></span>
            <div class="layui-upload-list" >
            <input type="hidden" name="file_name" id="thumb" />
<!--                 <ul id="layui-upload-box" class="layui-clear">
                    @if(isset($article->thumb))
                        <li><img src="{{ $article->thumb }}" /><p>上传成功</p></li>
                    @endif
                </ul>
             <input type="hidden" name="thumb" id="thumb" value="{{ $article->thumb??'' }}"> -->   
            </div>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">任务名称</label>
    <div class="layui-input-inline">
        <input type="text" name="name" value="{{ $task->name ?? old('name') }}" lay-verify="required" placeholder="请输入任务名称" class="layui-input" >
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">选择模板</label>
    <div class="layui-input-inline">
        <select name="template_id" lay-verify="required">
        @foreach($menus as $menu)
            <option value="{{  $menu['id'] }}">{{  $menu['template_name'] }} </option>
        @endforeach

          </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">日期选择</label>
    <div class="layui-input-inline">
        <input type="text" name = 'date_time' value="{{$task->date_time??old('date_time')}}"class="layui-input" id="test6" placeholder=" - " lay-key="1" lay-verify="required">
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">时间段选择</label>
    <div class="layui-input-inline">
        <input type="text" name = 'period_range' value="{{$task->period_range??old('period_range')}}"class="layui-input" id="time1" placeholder=" , " lay-key="10" lay-verify="required">
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">屏蔽省份</label>
    <div class="layui-input-block">
        <input type="text" name="city" value="{{$task->city??old('city')}}" lay-verify="required" placeholder="请输入标题" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.task')}}" >返 回</a>
    </div>
</div>
<style>
    #layui-upload-box li{
        width: 120px;
        height: 100px;
        float: left;
        position: relative;
        overflow: hidden;
        margin-right: 10px;
        border:1px solid #ddd;
    }
    #layui-upload-box li img{
        width: 100%;
    }
    #layui-upload-box li p{
        width: 100%;
        height: 22px;
        font-size: 12px;
        position: absolute;
        left: 0;
        bottom: 0;
        line-height: 22px;
        text-align: center;
        color: #fff;
        background-color: #333;
        opacity: 0.6;
    }
    #layui-upload-box li i{
        display: block;
        width: 20px;
        height:20px;
        position: absolute;
        text-align: center;
        top: 2px;
        right:2px;
        z-index:999;
        cursor: pointer;
    }
</style>