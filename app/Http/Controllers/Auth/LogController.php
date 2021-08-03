<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;


class LogController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page',1);
        $limit = $request->get('limit',10);
        $query = Log::query();
        $total = $query->count();
        $list = $query->forPage($page,$limit)->get([ 'url','ip','method','name','u_id','created_at','address','id']);


        return $this->success(
            [
                'list' => $list,
                'mate'=>[
                    'total' => $total,
                    'pageSize'=>$limit
                ]
            ]
        );

    }
    public function delete(Request $request)
    {
        $request->validate([
            'id'=>'required'
        ]);
        Log::query()->whereIn('id',explode(',',$request->id))->delete();
        return $this->success($request->id);
    }
}
