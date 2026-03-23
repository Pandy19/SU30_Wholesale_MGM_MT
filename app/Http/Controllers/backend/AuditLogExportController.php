<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogExportController extends Controller
{
    public function exportCsv()
    {
        $user = Auth::user();
        $logs = AuditLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        $response = new StreamedResponse(function () use ($user, $logs) {
            $handle = fopen('php://output', 'w');

            // Header information
            fputcsv($handle, ['DETAILED ACTIVITY TIMELINE']);
            fputcsv($handle, ['User Name:', $user->name]);
            fputcsv($handle, ['Email:', $user->email]);
            fputcsv($handle, ['Phone:', $user->phone ?? 'N/A']);
            fputcsv($handle, ['Report Generated:', now()->format('Y-m-d H:i:s')]);
            fputcsv($handle, []); // Empty line for spacing

            // Table headers
            fputcsv($handle, [
                'Date',
                'Time',
                'Action',
                'Method',
                'URL',
                'IP Address'
            ]);

            // Data rows
            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at->format('Y-m-d'),
                    $log->created_at->format('H:i:s'),
                    $log->action,
                    $log->method,
                    $log->url,
                    $log->ip_address,
                ]);
            }

            fclose($handle);
        });

        $filename = 'activity_log_' . $user->id . '_' . date('Ymd_His') . '.csv';

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
