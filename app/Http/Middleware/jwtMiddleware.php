<?php


namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\JWT;
use Exception;
//use Tymon\JWTAuth\JWT;

class jwtMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $USER = new JWT();
            $user = $USER->toUser();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return $next($request);
                return response()->json(['error'=>'Token is Invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return $next($request);
                return response()->json(['error'=>'Token is Expired']);
            }else{
                return $next($request);
                return response()->json(['error'=>'Something is wrong']);
            }
        }
        return $next($request);
    }

}
