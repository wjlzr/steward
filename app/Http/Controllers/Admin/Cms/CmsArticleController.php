<?php

namespace App\Http\Controllers\Admin\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ControlCenterService;

class CmsArticleController extends Controller
{

    /**
     * 文章列表
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request) {

        $category_id = $request->input('category_id');

        $data = [
            'category_id' => $category_id
        ];

        return view('admin/cms/article/index',$data);

    }

    /**
     * 查询数据
     * @param Request $request
     * @return mixed
     */
    public function search (Request $request) {

        $category_id = $request->input('category_id');
        $page = $request->input('page','0');
        $page_size = $request->input('page_size',10);

        $control_center_service = new ControlCenterService();

        $request_data = [
            'category_id' => $category_id,
            'page' => $page,
            'page_size' => $page_size
        ];

        $return_data = $control_center_service->get('steward/article/search', $request_data );

        if ( $return_data['code'] != 200 ){
            return response()->json(['code' => 400 ,'message' => $return_data['message']]);
        }elseif (empty($return_data['data'])) {
            return response()->json(['code' => 404 ,'message' => '暂无数据']);
        }

        $return_data['createTime'] = $return_data['data'][0]['createTime'];
        $return_data['article_category_name'] = $return_data['data'][0]['article_category_name'];

        return response()->json(['code' => 200, 'message' => 'ok', 'data' => $return_data]);
    }
}
