<?php

namespace Domains\Backend\Middlewares;

use Closure;

class CheckUserExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  String  $scope
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userModel = new \Domains\Backend\Models\UserModel();
        $user = $userModel->getUserById($request->route('userId'));
        if (empty($user)) {
            throw new \Domains\Supports\Exceptions\NotFoundException('User not found');
        }

        $request->merge([
            'user' => $user
        ]);

        return $next($request);
    }
}
