<?php

namespace App\Http\Controllers\Admin\Excel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use App\Models\Admin\User\Users;

class ExcelController extends Controller
{
    /**
     * 导出
     *
     * @param Users $users
     * @return mixed
     */
    public function excelExport(Request $request,Users $users)
    {
        //筛选条件 -- 根据需要 -- 修改你的查询语句
        //$where['client_id'] = $request->client_id;

        $data = $users->get()->toArray();

        return Excel::create('用户数据导出', function($excel) use ($data) {
            $excel->sheet('用户数据导出', function($sheet) use ($data)
            {
                $sheet->cell('A1', function($cell) {$cell->setValue('用户名');   });
                $sheet->cell('B1', function($cell) {$cell->setValue('角色名');   });
                $sheet->cell('C1', function($cell) {$cell->setValue('登陆IP');   });
                $sheet->cell('D1', function($cell) {$cell->setValue('登陆时间'); });
                if (!empty($data)) {
                    foreach ($data as $key => $value) {
                        $i= $key+2;
                        $sheet->cell('A'.$i, $value['username']);
                        $sheet->cell('B'.$i, $value['name']);
                        $sheet->cell('C'.$i, $value['login_ip']);
                        $sheet->cell('D'.$i, $value['login_at']);
                    }
                }
            });
        })->download('xls');
    }

}
