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
        $limit = $request->get('limit',20);
        $query = Log::query();
        $total = $query->count();
        $list = $query->forPage($page,$limit)->get();

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data'=>[
                'list' => $list,
                'mate'=>[
                    'total' => $total,
                    'pageSize'=>$limit
                ]
            ]
        ],200);
    }
    public function delete($id)
    {
        Log::query()->where('id',$id)->delete();

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data'=>[]
        ],200);
    }
}
