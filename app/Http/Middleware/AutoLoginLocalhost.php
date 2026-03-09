<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLoginLocalhost
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() && $this->isLocalhost($request)) {
            $user = User::first() ?? User::create([
                'name' => 'Local User',
                'email' => 'local@desktop.app',
                'password' => bcrypt(str()->random(32)),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]);

            Auth::login($user);
        }

        return $next($request);
    }

    protected function isLocalhost(Request $request): bool
    {
        $host = $request->getHost();
        $ip = $request->ip();

        return in_array($host, ['localhost', '127.0.0.1', '::1'])
            || in_array($ip, ['127.0.0.1', '::1']);
    }
}
