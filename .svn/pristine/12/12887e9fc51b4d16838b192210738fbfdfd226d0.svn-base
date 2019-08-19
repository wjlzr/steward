<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\StApp;
use App\Models\Log\StAppLog;


class ReceiveService
{

    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $start_time = microtime(true);
        $request_data = $request->input();

        $_url = $request_data['_url'];
        $_url_array = explode('/', ltrim($_url, '/'));

        $log_id = StAppLog::addLog($_url, [
            'creator' => 'api-push',
            'request' => $request_data
        ]);

        $app = StApp::find($_url_array[2]);
        if (!$app) {
            $return_data = response()->json(['code'=>404, 'message'=>'应用没有找到']);
            StAppLog::updateLog($log_id, [
                'return' => $request_data,
                'start_time' => $start_time
            ]);
            return $return_data;
        }

        $request->offsetSet('_app', $app);
        $return_data = $next($request);
        StAppLog::updateLog($log_id, [
            'creator' => 'api-'.strtolower($app->alias).'-push',
            'app_id' => $app->id,
            'return' => $return_data,
            'start_time' => $start_time
        ]);

        return $return_data;

    }


}
