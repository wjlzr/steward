<?php

namespace App\Http\Controllers\Admin\Plugin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Export\ExportManage;
use GuzzleHttp\Client;


class ExportController extends Controller
{

    //加载导出任务列表页面
    public function index($id)
    {

        return view('/admin/plugin/export', ['export_index'=>$id]);

    }

    //查询导出任务列表数据
    public function search(Request $request)
    {

        $where = [];

        if ( $request->input('export_index') ) {
            $where[] = ['export_index', $request->input('export_index')];
        }

        if ( $request->input('title') ) {
            $where[] = ['title','like','%'.$request->input('title').'%'];
        }

        $export_data = ExportManage::where($where)
                                    ->orderBy('created_at','DESC')
                                    ->paginate($request->input('limit'))
                                    ->toArray();

        $export_result = [
            'code' => 0,
            'count' => $export_data['total'],
            'data' => []
        ];

        foreach ( $export_data['data'] as $export ) {

            $operation = '';
            if ($export['status'] == 2) {
                $operation = '<a href="javascript:void(0)" data-id="'.$export['export_id'].'" class="download">下载</a>';
            }

            $status_name = '执行中';
            if ($export['status'] == 2) {
                $status_name = '已完成';
            } else if ($export['status'] == 3) {
                $status_name = '导出失败';
            }

            $export_result['data'][] = [
                'operation' => $operation,
                'export_id' => app_to_int($export['export_id']),
                'export_index' => app_to_int($export['export_index']),
                'title' => app_to_string($export['title']),
                'status' => app_to_int($export['status']),
                'status_name' => $status_name,
                'error_msg' => app_to_string($export['error_msg']),
                'created_at' => app_to_string($export['created_at'])
            ];

        }

        return $export_result;

    }

    /**
     * @param $id
     * @return array
     */
    public function download( $id )
    {

        $st_export_manage = ExportManage::find($id);

        if( !$st_export_manage ){
            return "<script> parent.layer.msg('当前下载任务不存在',{icon: 2, shade: [0.15, 'black'], offset: '120px', time: 2000},function(){parent.layer.closeAll();});</script>";
        }

        $download_url = env('EXPORT_URL') . 'export-file';
        $client = new Client();
        $request = $client->request('POST', $download_url, [ 'form_params' => ['fp' => $st_export_manage->down_url]]);

        if ($request->getStatusCode() != 200) {
            return "<script> parent.layer.msg('下载任务请求失败',{icon: 2, shade: [0.15, 'black'], offset: '120px', time: 2000},function(){parent.layer.closeAll();});</script>";

        }
        $download_result = $request->getBody()->getContents();

        return response($download_result)
            ->header('Content-Type', 'application/octet-stream')
            ->header('Accept-Ranges', 'bytes')
            ->header('Accept-Length', strlen($download_result))
            ->header('Content-Disposition', 'attachment;filename="'.basename($st_export_manage->down_url).'"');
    }
}
