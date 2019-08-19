<?php

namespace App\Models\Export;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class ExportManage extends Model
{


    public $table = 'st_export_manage';

    protected $primaryKey = 'export_id';


    /**
     * 大数据导出
     * @param $sql string 需要执行的SQL语句，用base64_encode处理
     * @param $title string 导出任务名称
     * @param $export_index int 导出任务索引
     * @param $creator string 导出任务创建者
     * @return array
     */
    public function multiExport($sql, $title, $export_index, $creator)
    {

        $export_manage = new self;
        $export_manage->creator = $creator;
        $export_manage->title = $title;
        $export_manage->export_index = $export_index;

        $export_url = env('EXPORT_URL') . 'export-data';
        $client = new Client();

        $call_url = 'http://' . $_SERVER['SERVER_NAME'] . '/api/open-receive/export/push';

        $request = $client->request('POST', $export_url, [ 'form_params' => [
            'mh' => env('DB_HOST'),
            'mu' => env('DB_USERNAME'),
            'mpwd' => env('DB_PASSWORD'),
            'md' => env('DB_DATABASE'),
            'mp' => env('DB_PORT'),
            'mq' => base64_encode($sql),
            'mid' => env('PROJECT_ID'),
            'fb' => $call_url
        ]]);

        if ($request->getStatusCode() != 200) {
            return ['code' => $request->getStatusCode(), 'message' => '导出服务请求失败'];
        }

        $export_result = json_decode($request->getBody()->getContents(), true);

        if ($export_result['err_code'] != 0 || !isset($export_result['data']['req_id'])) {
            $export_manage->status = 3;
            $export_manage->end_time = date('Y-m-d H:i:s');
            $export_manage->error_msg = $export_result['err_msg'];
            $export_manage->save();
            return ['code'=>200, 'message'=>'导出失败'];
        } else {
            $export_manage->status = 1;
            $export_manage->req_id = $export_result['data']['req_id'];
            $export_manage->save();
            return ['code'=>200, 'message'=>'导出成功'];
        }

    }

    /**
     * 导出任务状态更新
     * @param $export_id int 导出任务ID
     * @param $status int 导出状态：1、执行中 2、导出完成 3、失败
     * @param null $error_message 失败信息
     * @return bool
     */
    public function updateMultiStatus($export_id, $status, $error_message = null)
    {

        $export_manage = self::find($export_id);
        if (!$export_manage) {
            return false;
        }

        $export_manage->status = $status;
        $export_manage->end_time = date('Y-m-d H:i:s');
        if (!is_null($error_message)) {
            $export_manage->error_msg = $error_message;
        }
        $export_manage->save();

        return true;

    }


}
