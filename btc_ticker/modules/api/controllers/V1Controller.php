<?php

namespace app\modules\api\controllers;

use Yii;
use yii\rest\Controller;

use app\helpers\BehaviorsFromParamsHelper;
use app\helpers\ResponseHelpers;
use app\helpers\Tools;
use app\models\Status;


class V1Controller extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors = BehaviorsFromParamsHelper::behaviors($behaviors, true);
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
       return [
           'index' => ['GET', 'POST'],
       ];
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        
        if ($request->isGet && $request->get('method') == 'rates') {
            return self::rates(
                $request->get('currency')
            );
        }
        if ($request->isPost) {
            return self::convert(
                $request->get('currency_from'),
                $request->get('currency_to'),
                floatval($request->get('value'))
            );
        }
            
        return ResponseHelpers::errorResponse(
            Status::STATUS_NOT_FOUND, 
            'Not found'
        );
    }

    public function rates(string $currencies = null)
    {
        $result = [];

        if (!is_null($currencies)) {
            $currencies = explode(',', $currencies);
            $result = self::getRates($currencies);
        } else {
            $result = self::getRates();
        }

        return ResponseHelpers::successResponse(
            Status::STATUS_OK, 
            $result
        );
    }

    public function convert(string $from = null, string $to = null, float $amount = null)
    {
        if (is_null($amount) || $amount < 0.01) {
            return ResponseHelpers::errorResponse(
                Status::STATUS_BAD_REQUEST, 
                'The minimum `value` is 0.01'
            );
        }

        if (is_null($from) || is_null($to) ) {
            return ResponseHelpers::errorResponse(
                Status::STATUS_BAD_REQUEST, 
                'You have to specify `currency_from` and `currency_to` currencies'
            );
        }

        if ($from == $to) {
            $rate = 1.0;
            $value = $amount;
        } elseif ($from == 'BTC') {
            $rate = self::getRate($to);
            $value = !is_null($rate) ? round($amount * $rate, 2) : 0;
        } elseif ($to == 'BTC') {
            $rate = self::getRate($from);
            $value = !is_null($rate) ? round($amount / $rate, 10) : 0;
        } else {
            return ResponseHelpers::errorResponse(
                Status::STATUS_NOT_IMPLEMENTED, 
                'Not supported yet'
            );
        }

        return ResponseHelpers::successResponse(
            Status::STATUS_OK, 
            [ 'currency_from'   => $from
            , 'currency_to'     => $to
            , 'value'           => $amount
            , 'converted_value' => $value 
            , 'rate'            => $rate
            ]
        );
    }

    /**
    * Get rate by currencies
    * @param array $currency - set null if want to receive all currencies rates
    * @return array
    */
    protected function getRates(array $currencies = null)
    {
        $result = [];
        $rates = self::_obtainRates();
        foreach ($rates as $c => $v) {
            if (!is_null($currencies) && !in_array($c, $currencies))
                continue;
            $result[$c] = round($v['last'] * 1.02, 2);
        }
        asort($result);
        
        return $result;
    }

    /**
     * Get rate by currency
     * @param string $currency
     * @return float
     */
    protected function getRate(string $currency)
    {
        $rates = self::_obtainRates();
        foreach ($rates as $c => $v) {
            if ($c == $currency) return round($v['last'] * 1.02, 2);
        }
        
        return null;
    }

    /**
     * Obtains rates from blockchain info
     * @return array
     */
    protected function _obtainRates()
    {
        return json_decode(
            file_get_contents('https://blockchain.info/ticker'), 
            true
        );
    }
}