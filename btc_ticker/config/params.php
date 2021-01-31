<?php

return [
    'useHttpBasicAuth' => false,
    'useHttpBearerAuth' => true,
    'useQueryParamAuth' => false,

    /**
     * use rate limiter for user
     * you must modified your UserIdentity class, follow this guidelines for complete guide
     * https://www.yiiframework.com/doc/guide/2.0/en/rest-rate-limiting
     */
    'useRateLimiter' => false,
];
