<?php

namespace app\helpers;


/**
 * Class Tools provide different useful tools
 */
class Tools
{
    public static function array_keys_exists(array $keys, array $arr) {
        return !array_diff_key(array_flip($keys), $arr);
    }
}
