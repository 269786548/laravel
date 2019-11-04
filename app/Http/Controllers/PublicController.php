<?php
namespace App\Http\Controllers;

use App\Traits\Msg;
use Illuminate\Http\Request;
use zgldh\QiniuStorage\QiniuStorage;

class PublicController extends Controller
{
    use Msg;
    //图片上传处理
    public function uploadImg(Request $request)
    {
        // $data = ['code'=>200, 'msg'=>'上传OK', 'data'=>'123'];
        // return response()->json($data);

        //上传文件最大大小,单位M
        $maxSize = 10;
        //支持的上传图片类型  xls|csv|xlsx
        $allowed_extensions = ["xls", "csv", "xlsx"];
        //返回信息json
        $data = ['code'=>200, 'msg'=>'上传失败', 'data'=>''];
        $file = $request->file('file');
        //检查文件是否上传完成
        if ($file->isValid()){
            //检测图片类型
            $ext = $file->getClientOriginalExtension();//上传文件的后缀
            if (!in_array(strtolower($ext),$allowed_extensions)){
                $data['msg'] = "请上传".implode(",",$allowed_extensions)."格式的图片";
                return response()->json($data);
            }
            //检测图片大小
            if ($file->getClientSize() > $maxSize*1024*1024){
                $data['msg'] = "图片大小限制".$maxSize."M";
                return response()->json($data);
            }
        }else{
            $data['msg'] = $file->getErrorMessage();
            return response()->json($data);
        }


        $clientName = $file -> getClientOriginalName(); //获取文件名称
        $realPath = $file -> getRealPath();  //这个表示的缓存在tmp文件夹下的文件的绝对路径，例如我的是：D:\wamp\tmp\php9372.tmp
         $saveName = time().'_'.rand().".".$ext;
        // 存储文件 已经不使用 move 这种方式
        // $img->move('./uploads/'.date('Ymd'),$saveName);
        // 使用 store 存储文件
        // $path = $file->store(date('Ymd')); 
        $path = $file->storeAs(date('Ymd'), $saveName);

        // echo back()->withInput(['url'=>'uploads/'.$path]);  
        // $path = $file -> store(app_path().'/storage/uploads',$clientName);


        // $newFile = date('Y-m-d')."_".time()."_".uniqid().".".$file->getClientOriginalExtension();
        // echo $newFile;
        // echo $file->getRealPath();
        // $disk = QiniuStorage::disk('qiniu');
        // $res = $disk->put($newFile,file_get_contents($file->getRealPath()));
        if($path){
            $data = [
                'code'  => 0,
                'msg'   => '上传成功',
                'data'  => $saveName,
                'clientName'  => $clientName,
                'url'   => 'uploads/'.$path
            ];
        }else{
            $data['data'] = $file->getErrorMessage();
        }
        return response()->json($data);
    }



}