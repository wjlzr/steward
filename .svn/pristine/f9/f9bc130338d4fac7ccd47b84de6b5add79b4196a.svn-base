<?php

namespace App\Http\Controllers\Admin\Plugin;

use Illuminate\Http\Request;
use App\Models\Mall\StMall;
use App\Http\Controllers\Controller;

class MallPluginController extends Controller
{

    /**
     * 门店插件
     * @return mixed
     */
    public function index()
    {
        return view('admin/plugin/mall');
    }

    /**
     * 列表页数据
     * @param Request $request
     * @return array
     */
    public function search(Request $request)
    {

        //查询数组
        $where = [];

        if ( $request->input('code') ) {
            $where['code'] = $request->input('code');
        }

        if ( $request->input('name') ) {
            $where[] = ['name','like','%'.$request->input('name').'%'];
        }

        $mall_data = StMall::where($where)
            ->paginate($request->input('limit'))
            ->toArray();

        //返回数组
        $return_result = [
            'code' => 0,
            'count' => $mall_data['total'],
            'data' => []
        ];

        if ( $mall_data['total'] ) {

            foreach ( $mall_data['data'] as $item ) {

                $return_result['data'][] = array(
                    'id' => $item['id'],
                    'code' => $item['code'],
                    'name' => $item['name']
                );

            }

        }

        return $return_result;
    }

    /**
     * 全选数据
     * @param Request $request
     */
    public function query(Request $request)
    {
        $id_data = $request->input('id_arr');

        $result_data = [];

        foreach ($id_data as $id) {

            $result_data[] = StMall::find($id)->toArray();
        }

        return response()->json(['code' => 200, 'message' => 'ok', 'data' => $result_data]);
    }
}
