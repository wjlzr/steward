<?php

namespace App\Services\App;

use App\Models\StApp;


class AppSearchService
{


    /**
     * 平台列表查询
     * @param $args
     * @return mixed
     */
    public function search($args)
    {

        $page_size = isset($args['page_size'])
            ? $args['page_size']
            : 10;

        $enable = isset($args['enable'])
            ? app_to_int($args['enable'], -1)
            : -1;

        $where = [];

        if (isset($args['id']) && ebsig_is_int($args['id'])) {
            $where[] = ['id', $args['id']];
        }

        if (isset($args['name']) && !empty($args['name'])) {
            $where[] = ['name', 'like', '%' . $args['name'] . '%'];
        }

        if (isset($args['alias']) && !empty($args['alias'])) {
            $where[] = ['alias', $args['alias']];
        }

        if (in_array($enable, [0, 1])) {
            $where[] = ['enable', $enable];
        }

        $app_array = StApp::where($where)
            ->orderBy('id', 'desc')
            ->paginate($page_size)
            ->toArray();

        $app_result = [
            'total' => $app_array['total'],
            'list' => []
        ];

        foreach($app_array['data'] as $app) {
            $app_result['list'][] = [
                'id' => app_to_int($app['id']),
                'name' => app_to_string($app['name']),
                'alias' => app_to_string($app['alias']),
                'logo' => app_to_string($app['logo']),
                'memo' => app_to_string($app['memo'])
            ];
        }

        return $app_result;

    }

}