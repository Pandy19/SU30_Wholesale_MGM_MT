<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();
            $url = $request->fullUrl();
            $path = $request->path();
            $method = $request->method();

            // Skip some boring requests (like static assets or very frequent AJAX)
            $skipPaths = ['admin_login', 'logout', 'debugbar', '_debugbar'];
            foreach ($skipPaths as $skip) {
                if (str_contains($path, $skip)) {
                    return $response;
                }
            }

            // Determine action name
            $action = "Viewed page: " . $path;
            
            // Customize actions for POST/PUT/DELETE
            if ($method !== 'GET') {
                if (str_contains($path, 'supplier/store')) $action = "Created new Supplier";
                elseif (str_contains($path, 'brand/store')) $action = "Added new Brand";
                elseif (str_contains($path, 'category/store')) $action = "Added new Category";
                elseif (str_contains($path, 'setting/update')) $action = "Updated Account Settings";
                elseif (str_contains($path, 'approve')) $action = "Approved a request";
                elseif (str_contains($path, 'deny')) $action = "Denied a request";
                elseif (str_contains($path, 'delete')) $action = "Deleted a record";
                else $action = "Performed action: " . $method . " on " . $path;
            }

            // Special labels for reports
            if (str_contains($path, 'report')) $action = "Viewed Report: " . ucfirst(str_replace('_', ' ', basename($path)));

            AuditLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'method' => $method,
                'url' => $url,
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
        }

        return $response;
    }
}
