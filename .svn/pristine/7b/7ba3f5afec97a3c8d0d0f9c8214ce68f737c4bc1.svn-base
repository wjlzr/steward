<?php

namespace App\Http\Controllers\Develop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis as Redis;
use Carbon\Carbon;

use App\Models\StDevRelease;
use App\Models\StDevReleaseFiles;
use App\Models\User\StDevUser;


class ReleaseController extends Controller
{


    /**
     * svn地址
     */
    const SVN_URL = 'http://svn.ebsig.com:8888/svn/ebsig/trunk/steward';


    /**
     * SVN目录
     */
    const SVN_ROOT = '/trunk/steward';


    /**
     * @var array 限制发布文件的目录，如果有发布的文件在下列目录中，则不允许发布
     */
    const RESTRICT_DIR = [];


    /**
     * svn用户名
     */
    const SVN_USER_NAME = 'deployer';


    /**
     * svn用户密码
     */
    const SVN_USER_PWD = 'kkkkkk';


    /**
     * 打开发布页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        return view('develop/release');

    }

    /**
     * 查询已发布的记录列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {

        $user_id = Redis::get('ST_DEV_USER_ID_' . session()->getId());

        $user_data = StDevUser::where('svn_id', $user_id)->first();
        if (!$user_data) {
            return response()->json([
                'total' => 0,
                'rows' => []
            ]);
        }

        $where = [];
        $where['user_id'] = $user_data->svn_id;

        //SVN版本号
        if ($request->input('version_number')) {
            $where['version_number'] = $request->input('version_number');
        }

        //分页发布记录信息
        $release = StDevRelease::where($where)
                   ->orderBy($request->input('sort'), $request->input('order'))
                   ->paginate($request->input('limit'))
                   ->toArray();

        $res_data = [
            'total' => $release['total'],
            'rows' => []
        ];

        if ($release['data']) {
            foreach ( $release['data'] as &$row ) {

                if ($row['release_status'] == 1) {
                    $release_status = '待发布';
                } else if ($row['release_status'] == 2) {
                    $release_status = '已发布到测试环境';
                } else {
                    $release_status = '已发布到生产环境';
                }

                $operation = '<a href="javascript: void(0);" onclick="Release.releasedFiles(' . $row['version_number'] . ');">查看文件</a>&nbsp;&nbsp;';
                if ($row['release_status'] == 1) {
                    $operation .= '<a href="javascript: void(0);" onclick="Release.del(' . $row['release_id'] . ');">删除</a>&nbsp;&nbsp;';
                    $operation .= '<a href="javascript: void(0);" onclick="Release.releasedToProduction(' . $row['release_id'] . ');">发布</a>';
                }

                $res_data['rows'][] = [
                    'operation' => $operation,
                    'release_id' => $row['release_id'],
                    'version_number' => $row['version_number'],
                    'release_time' => $row['release_time'],
                    'user_id' => $row['user_id'],
                    'release_status' => $release_status,
                ];

            }
        }

        return response()->json($res_data);

    }

    /**
     * 查询当前登录用户未发布的svn号列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRevisions()
    {

        $user_id = Redis::get('ST_DEV_USER_ID_' . session()->getId());

        $user_data = StDevUser::where('svn_id', $user_id)->first();
        if (!$user_data) {
            return response()->json(['code' => 100001, 'message' => '当前用户没有找到']);
        } else if (empty($user_data->svn_id)) {
            return response()->json(['code' => 100001, 'message' => '当前会员没有svn号']);
        }

        $svn_id = $user_data['svn_id'];

        //svn地址
        $svn_url = self::SVN_URL;

        //查询修改svn提交日志
        $svn_log_info_xml = stream_get_contents(popen('svn log -l 100 ' . $svn_url . ' --xml --username ' . self::SVN_USER_NAME . ' --password ' . self::SVN_USER_PWD . ' --no-auth-cache --config-dir /tmp' . ' 2>&1', 'r'));

        //解析日志xml
        $log_obj = simplexml_load_string($svn_log_info_xml);
        if (!isset($log_obj->logentry)) {
            return response()->json(['code' => 100002, 'message' => 'SVN提交记录没有找到']);
        }

        //版本号数组
        $revision_data = [];

        //循环最近的100条svn提交记录，获取属于当前用户的记录
        foreach($log_obj->logentry as $logentry) {

            if ($logentry->author == $svn_id) { //属于当前用户的记录

                //版本号
                $revision_number = (int)$logentry->attributes()->revision;

                //检查是否已发布（查到数据表示已发布，则跳过）
                $release = StDevRelease::select('release_id')
                                        ->where('version_number', $revision_number)
                                        ->first();

                if (!$release) {
                    $revision_data[] = $revision_number;
                }

            }

        }

        if (isset($revision_data[0])) {
            return response()->json(['code' => 200, 'message' => 'ok', 'revision' => $revision_data]);
        } else {
            return response()->json(['code' => 100002, 'message' => 'SVN提交记录没有找到']);
        }

    }

    /**
     * 获取已发布的文件信息
     * @param int $revision_number svn版本号
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReleasedFiles($revision_number)
    {

        $release_file = StDevReleaseFiles::select('svn_file')
                                        ->where('version_number', $revision_number)
                                        ->get();
        if ($release_file) {
            $files = [];
            foreach ($release_file as &$file) {
                $files[] = $file['svn_file'];
            }
            return response()->json(['code' => 200, 'message' => 'ok', 'data' => $files]);
        } else {
            return response()->json(['code' => 100001, 'message' => '版本号已经发布，禁止重复发布']);
        }

    }

    /**
     * 根据版本号，查询该版本的文件
     * @param Request $request
     * @param int $revision_number svn版本号
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getFiles(Request $request, $revision_number)
    {

        $res = $this->getSvnFiles($revision_number);
        return response()->json($res);

    }

    /**
     * 代码发布
     * @param int $revision_number svn版本号
     * @return \Illuminate\Http\JsonResponse
     */
    public function release($revision_number)
    {

        //获取发布文件列表
        $res = $this->getSvnFiles($revision_number);
        if ($res['code'] != 200) {
            return response()->json($res);
        }
        $files_data = $res['data'];

        //根目录
        $root = base_path();

        //svn地址
        $svn_url = self::SVN_URL;


        //导出文件待发布的中间目录
        $export_root = '/webcache/html_cache/release/steward/' . $revision_number . '/';

        //服务器上备份文件的目录
        $backup_root = '/webcache/html_cache/backup/steward/' . $revision_number . '/';

        //循环文件列表，并把文件备份在测试或正式服务器上的中间目录
        foreach ($files_data as $file_path) {

            $file_name = basename($file_path);
            $file_dir = dirname($file_path);

            //导出文件待发布的中间完整目录
            $export_dir = $export_root . $file_dir . '/';
            if (!file_exists($export_dir)) {
                if (!mkdir($export_dir, 0777, true)) {
                    system('mkdir -p 777 ' . $export_dir);
                }
            }

            //把SVN上文件导出到测试或正式服务器上的中间目录
            exec('svn export -r ' . $revision_number . ' ' . $svn_url . $file_path . ' ' . $export_dir . $file_name . ' --username ' . self::SVN_USER_NAME . ' --password ' . self::SVN_USER_PWD . ' --no-auth-cache --force --config-dir /tmp');
            if (!file_exists($export_dir . $file_name)) {
                return response()->json(['code' => 100011, 'message' => '文件导出失败：' . $export_dir . $file_name]);
            }

            //备份文件目录
            $backup_dir = $backup_root . $file_dir . '/';
            if (!file_exists($backup_dir)) {
                if (!mkdir($backup_dir, 0777, true)) {
                    system('mkdir -p 777 ' . $backup_dir);
                }
            }
            //如果服务器上存在旧的文件，则复制到备份目录里
            if (file_exists($root . $file_path)) {
                copy($root . $file_path, $backup_dir . $file_name);
            }

        }

        //把已导出的中间目录的文件，覆盖到工作目录里
        $sync = 'rsync -av --progress --partial  -C -e ssh --exclude H ' . $this->getExcludeString() . ' ' . $export_root . '/ ' . $root . '|grep -v sending|grep -v sent|grep -v total|grep -v "^\.\/"|grep -v 100\%|grep -v "\/$"|grep -v "^$"';
        exec($sync, $released_result_data);
        if (!$released_result_data) {
            return response()->json(['code' => 100012, 'message' => '发布失败，无法获取发布失败信息']);
        }

        $release_status = 3; //发布状态

        $user_id = Redis::get('ST_DEV_USER_ID_' . session()->getId());

        $user_data = StDevUser::where('user_id', $user_id)->first();
        if (!$user_data) {
            return response()->json(['code' => 100001, 'message' => '当前会员没有找到']);
        } else if (empty($user_data->svn_id)) {
            return response()->json(['code' => 100001, 'message' => '当前会员没有svn号']);
        }

        //保存发布记录
        $release = new StDevRelease();
        $release->creator = $user_id;
        $release->user_id = $user_data->svn_id;
        $release->release_time = Carbon::now();
        $release->version_number = $revision_number;
        $release->release_status = $release_status;
        $release->release_result = 'ok';
        $release->save();

        //保存发布的文件
        foreach ($files_data as $file_path) {
            $release_file = new StDevReleaseFiles();
            $release_file->creator = $user_id;
            $release_file->version_number = $revision_number;
            $release_file->user_id = $user_id;
            $release_file->svn_file = $file_path;
            $release_file->release_status = $release_status;
            $release_file->save();
        }

        return response()->json(['code' => 200, 'message' => 'ok']);

    }

    /**
     * 删除发布
     * @param int $id 发布号
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {

        if (!ebsig_is_int($id)) {
            return response()->json(['code' => 100000, 'message' => '缺少参数']);
        }

        $release = Release::find($id);
        if ($release) {
            $delete_rows = ReleaseFiles::where('version_number', $release->version_number)->delete();
            if ($delete_rows > 0) {
                $release->delete();
            }
        }

        return response()->json(['code' => 200, 'message' => 'ok']);

    }

    /**
     * 获取发布的忽略参数
     * @return string
     */
    private function getExcludeString() {

        $exclude = '';

        $restrict_dir = self::RESTRICT_DIR;

        //忽略的目录
        if (!empty($restrict_dir)) {
            foreach ($restrict_dir as &$dir) {
                $exclude .= '--exclude "' . $dir . '" ';
            }
        }
        return $exclude;

    }

    /**
     * 根据版本号，查询该版本的文件
     * @param int $revision_number 版本号
     * @return array|\Illuminate\Http\JsonResponse
     */
    private function getSvnFiles($revision_number)
    {

        //检查版本号是否已发布
        $release = StDevRelease::select('release_id')
                                ->where('version_number', $revision_number)
                                ->first();

        if ($release) {
            return ['code' => 100001, 'message' => '版本号已经发布，禁止重复发布'];
        }

        //svn地址
        $svn_url = self::SVN_URL;

        //svn目录
        $svn_root = self::SVN_ROOT;

        //根据版本号查询对应的文件
        $svn_log_info_xml = stream_get_contents(popen('svn log -r ' . $revision_number . ' -v ' . $svn_url . ' --xml --username ' . self::SVN_USER_NAME . ' --password ' . self::SVN_USER_PWD . ' --no-auth-cache --config-dir /tmp' . ' 2>&1', 'r'));

        //解析日志xml
        $file_obj = simplexml_load_string($svn_log_info_xml);
        if (!isset($file_obj->logentry)) {
            return ['code' => 100002, 'message' => '版本号对应的文件没有找到'];
        }

        //文件列表数组
        $files_data = [];

        $restrict_dir = self::RESTRICT_DIR;

        foreach ($file_obj->logentry as $logentry) {

            //循环文件，检查文件是否发布
            foreach ($logentry->paths->path as $path) {

                //不是文件，或是删除文件操作
                if ($path->attributes()->kind != 'file' || $path->attributes()->action == 'D') {
                    continue;
                }

                //替换掉svn目录的路径（如：/trunk/ebsig_Glib），得到文件路径
                $file_path = str_replace($svn_root, '', $path);

                if (count($restrict_dir) > 0) {
                    foreach ($restrict_dir as &$dir) {
                        if (strstr($file_path, $dir)) {
                            return ['code' => 100003, 'message' => '有限制发布的文件：' . $file_path];
                        }
                    }
                }

                $files_data[] = $file_path;

            }

        }

        return ['code' => 200, 'message' => 'ok', 'data' => $files_data];

    }

}