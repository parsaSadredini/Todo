<?php

namespace App\Exceptions;

use App\WebFramework\ApiResult;
use App\WebFramework\ApiResultWithData;
use App\WebFramework\Common\StandardApiResultStatusCode;
use App\WebFramework\Exception\ApplicationException;
use App\WebFramework\Exception\ListEmptyException;
use App\WebFramework\Exception\NotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function(Throwable $exception, $request) {
            if($exception instanceof ValidationException){

                $errors = collect($exception->validator->errors()->all())->map(function($error){
                    return $error;
                });

                return response()->json(new ApiResultWithData($errors,false,StandardApiResultStatusCode::BadRequest));

            }else if($exception instanceof ListEmptyException){

                return response()->json(new ApiResult(false,StandardApiResultStatusCode::ListEmpty));

            }else if($exception instanceof NotFoundException || $exception instanceof ModelNotFoundException){

                return response()->json(new ApiResult(false,StandardApiResultStatusCode::NotFound));

            }else if($exception instanceof ApplicationException){

                return response()->json(new ApiResult(false,StandardApiResultStatusCode::ServerError));

            }else if($exception instanceof BadRequestException){

                return response()->json(new ApiResult(false,StandardApiResultStatusCode::BadRequest,$exception->getMessage()));

            }
            else if($exception instanceof  \Illuminate\Auth\AuthenticationException){
                return response()->json(new ApiResult(false,StandardApiResultStatusCode::LogicError));
            }
            dd($exception);
        });
    }
}
