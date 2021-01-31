<?php

namespace app\helpers;

use Yii;
use yii\filters\auth\CompositeAuth;

use app\models\Status;


/**
 * Class ResponseHelpers provide useful method to prepare default responses
 */
class ResponseHelpers
{
    public static function beforeResponseSend(\yii\base\Event $event)
    {
        $response = $event->sender;
        if ($response->data['status'] == 401) {
            $response->data = self::errorResponse(
                Status::STATUS_FORBIDDEN,
                'Invalid token'
            );
        }
    }
    
    public static function successResponse(int $code = Status::STATUS_OK, array $data)
    {
        return [
            'status' => Status::STATUS_SUCCESS,
            'code'   => $code,
            'data'   => $data
        ];
    }

    public static function errorResponse(int $code, string $message)
    {
        return [
            'status'  => Status::STATUS_ERROR,
            'code'    => $code,
            'message' => $message
        ];
    }
}