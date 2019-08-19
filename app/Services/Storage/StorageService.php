<?php

namespace App\Services\Storage;

use App\Services\Storage\Src\TxCos;

class StorageService
{

    /**
     * 创建目录
     * @param $folder
     * @return array
     */
    public static function addFolder($folder)
    {


        if (!isset($folder) || empty($folder)) {
            return ['code' => 400, 'message' => '缺少目录路径：folder'];
        }

        $TxCos = new TxCos();
        return $result = $TxCos->createFolder($folder);
    }

    /**
     * 目录列表
     * @param $folder
     * @return array|mixed
     */
    public static function listFolder($folder)
    {

        if (!isset($folder) || empty($folder)) {
            return ['code' => 400, 'message' => '缺少目录路径：folder'];
        }

        $TxCos = new TxCos();
        return $result = $TxCos->listFolder($folder);

    }

    /**
     * 删除目录[不能删除根目录/]
     * @return array
     */
    public static function delFolder($folder)
    {

        if (!isset($folder) || empty($folder)) {
            return ['code' => 400, 'message' => '缺少目录路径：folder'];
        }

        $TxCos = new TxCos();
        return $result = $TxCos->delFolder($folder);

    }

    /**
     * 查询目录信息
     * @return array
     */
    public static function statFolder($folder)
    {

        if (!isset($folder) || empty($folder)) {
            return ['code' => 400, 'message' => '缺少目录路径：folder'];
        }

        $TxCos = new TxCos();
        return $result = $TxCos->statFolder($folder);

    }

    /**
     *文件上传[上传文件小于20M]
     * @return array
     */
    public static function upload($args_data)
    {

        if (!isset($args_data['src_path']) || empty($args_data['src_path'])) {
            return ['code' => 400, 'message' => '缺少文件本地路径：src_path'];
        }

        if (!isset($args_data['dst_path']) || empty($args_data['dst_path'])) {
            return ['code' => 400, 'message' => '缺少上传文件路径：dst_path'];
        }

        $TxCos = new TxCos();
        return $result = $TxCos->upload($args_data['src_path'],$args_data['dst_path']);

    }

    /**
     * 删除文件信息
     * @return array
     */
    public static function delFile($args_data)
    {

        if (!isset($args_data['path']) || empty($args_data['path'])) {
            return ['code' => 400, 'message' => '缺少目录路径：folder'];
        }

        $TxCos = new TxCos();
        return $result = $TxCos->delFile($args_data['path']);

    }

    /**
     * 文件下载
     * @return array
     */
    public static function downloadFile($args_data)
    {

        if (!isset($args_data['file_name']) || empty($args_data['file_name'])) {
            return ['code' => 400, 'message' => '缺少目录路径：file_name'];
        }

        $TxCos = new TxCos();
        return $result = $TxCos->delFile($args_data['file_name']);

    }


}