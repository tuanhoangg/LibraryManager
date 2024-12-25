<?php

namespace App\Http\Middleware;

use App\Models\Roles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route(Auth::user()->role_id == Roles::ROLE_USER ? 'book.search' : (Auth::user()->role_id == Roles::ROLE_LIBRARIAN ? 'book.list' :
                'dashboard'))->with(['message' => 'Đăng nhập thành công', 'alert-type' => 'success']);
        }

        $user = Auth::user();

        if (!in_array($user->role_id, explode('|', $roles[0]))) {
            return redirect()->back();
        }
        return $next($request);
    }
}
