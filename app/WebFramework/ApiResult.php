<?php

namespace App\WebFramework;

use App\WebFramework\Common\StandardApiResultStatusCode;

class ApiResult
{
    public $isSucces;
    public $status;
    public $message;
    public function __construct($isSucces_ = true , $apiResultStatusCode_ = 0 , $message_ = null)
    {
        $this->isSucces = $isSucces_;
        $this->status = $apiResultStatusCode_;
        if($message_ == null){
            $this->message = StandardApiResultStatusCode::defaultMessages[$apiResultStatusCode_];
        }else{
            $this->message = $message_;
        }
    }

    public static function constructorWithCustomMessage($message_){
        return new ApiResult(true,0,$message_);
    }

}
