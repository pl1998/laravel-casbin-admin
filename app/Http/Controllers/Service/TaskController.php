<?php
/**
 * Created By PhpStorm.
 * User : Latent
 * Date : 2021/8/3
 * Time : 5:04 下午
 **/

namespace App\Http\Controllers\Service;


use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $page     =  $request->input('page',1);
        $pageSize =  $request->input('limit',10);
        $query    =  Task::query();
        $total    =  Task::query()->count();
        $list     =  $query->forPage($page,$pageSize)->get();
        return $this->success([
                'list' => $list,
                'mate'=>[
                    'total' => $total,
                    'pageSize'=>$pageSize
                ]]
        );
    }
    public function store(TaskRequest $request,TaskService $service)
    {
        $resp = Task::query()->create([
            'task_name'=>$request->input('task_name'),
            'status'=>1,
            'op_name'=>auth('api')->user()->name,
            'type'=>$request->input('task_type'),
            'cycle'=>'*/1 * * * *',
            'email'=>$request->input('email'),
            'textarea'=>$request->input('textarea'),
        ]);

        //$service->add($resp->id,'*/1 * * * *',$request->input('textarea'));

        return $this->success([$resp->id]);
    }
    public function update($id,Request $request)
    {
        $request->validate([
            'id'=>'required',
            'status'=>'required',
        ]);
        $task = Task::query()->find($id);
        $task->status= $request->input('status');
        $resp = $task->save();
        return $this->success([$resp]);
    }

    public function destroy($id)
    {

        Task::destroy($id);
        return $this->success();
    }
}
