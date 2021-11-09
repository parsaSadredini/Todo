<?php

namespace App\Http\Controllers;

use App\Events\EmailTimeHasCome;
use App\Models\Task;
use App\Models\User;
use App\WebFramework\ApiResult;
use App\WebFramework\ApiResultWithData;
use App\WebFramework\Common\StandardApiResultStatusCode;
use Dotenv\Exception\ValidationException;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function create(Request $request){
        $this->performValidation($request);
        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->when_to_do = $request->when_to_do ;
        $task->id_user = Auth::user()->id;
        $task->save();
        return Response()->json(new ApiResult(true,StandardApiResultStatusCode::Succees));
    }

    public function update(Task $task){

        $task->id_user = Auth::user()->id;
        $task->update();
        return Response()->json(new ApiResult(true,StandardApiResultStatusCode::Succees));
    }

    public function getAll(){
        $tasks = Auth::user()->tasks;
        return Response()->json(new ApiResultWithData($tasks));
    }
    private function performValidation($request)
    {
        $validator = Validator::make($request->toArray(), Task::$validationRules);
        if ($validator->fails())
            throw new ValidationException($validator);
    }
}
