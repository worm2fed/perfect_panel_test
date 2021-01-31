<?php

namespace app\helpers;

use Yii;
use yii\filters\auth\CompositeAuth;


/**
 * Class AuthMethodsFromParamsHelper provide useful method to create your controller behaviors from application parameter
 */
class BehaviorsFromParamsHelper
{
    /**
     * Give available behaviors configuration from params.php
     * the result of BehaviorsFromParamsHelper::behaviors would be like following
     * ```php
     * $behaviors['authenticator'] = [
     *      HttpBearerAuth::class,
     *      HttpBasicAuth::class
     * ];
     * $behaviors['rateLimiter']['enableRateLimitHeader'] = false;
     * ```
     * 
     * example use of this method in your controller
     * ```php
     * public function behaviors(){
     *      $behaviors = parent::behaviors
     *      $behaviors = BehaviorsFromParamsHelper::behaviors($behaviors);
     * 
     *      // you may use this if you want to add more behaviors
     *      # $behaviors['otherMethod'] = $value;
     *      return $behaviors;
     * }
     * ```
     * 
     * @param object $behaviors object you use in your controller. $behaviors = parent::behaviors();
     * @return object $behaviors object you use in your controller. $behaviors = parent::behaviors();
     */
    public static function behaviors($behaviors, bool $auth = false)
    {
        $behaviorsFromParamsHelperObject = new self();
        return $behaviorsFromParamsHelperObject->getBehaviors($behaviors, $auth );
    }

    private function getBehaviors($behaviors, bool $auth = false)
    {
        if ($auth) {
            $behaviors['authenticator'] = [
                'class' => CompositeAuth::class,
                'authMethods' => AuthMethodsFromParamsHelper::authMethods(),
            ];
        }
        $behaviors['contentNegotiator'] = [
	        'class' => 'yii\filters\ContentNegotiator',
	        'formats' => [
	            'application/json' => \yii\web\Response::FORMAT_JSON,
            ]
        ];

        if (Yii::$app->params['useRateLimiter']) {
            $behaviors['rateLimiter']['enableRateLimitHeaders'] = false;
        }
        return $behaviors;
    }
 }