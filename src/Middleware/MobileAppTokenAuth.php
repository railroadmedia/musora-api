<?php

namespace Railroad\MusoraApi\Middleware;

use Closure;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth as JWTFacade;

class MobileAppTokenAuth extends BaseMiddleware
{
    /**
     * TokenAuth constructor.
     *
     * @param JWTAuth $auth
     */
    public function __construct(JWTAuth $auth)
    {
        parent::__construct($auth);
    }

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws JWTException
     */
    public function handle($request, Closure $next)
    {
        try {

            $user =
                JWTFacade::setRequest($request)->parseToken()
                    ->getPayload()
                    ->get('sub');

            auth()->loginUsingId($user, false);

            return $next($request);

        } catch (\Exception $exception) {

            $exceptionInstance = get_class($exception);

            switch ($exceptionInstance) {
                case \Tymon\JWTAuth\Exceptions\TokenExpiredException::class:
                    return response()->json(['error' => 'TOKEN_EXPIRED']);
                case \Tymon\JWTAuth\Exceptions\TokenInvalidException::class:
                    return response()->json(['error' => 'TOKEN_INVALID']);
                case \Tymon\JWTAuth\Exceptions\TokenBlacklistedException::class:
                    return response()->json(['error' => 'TOKEN_BLACKLISTED']);
                case \Tymon\JWTAuth\Exceptions\JWTException::class:
                    error_log(' ----------------------------------------------------------     Mobile App Token Exception :::::: '.$exception->getMessage());
                    return response()->json(['error' => 'Token not provided']);
                default:
                    return response()->json(['error' => $exception->getMessage()]);
            }
        }

    }
}
