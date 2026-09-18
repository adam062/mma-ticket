<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('admin');

        if ($action = $request->query('action')) {
            $query->where('action', $action);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(25);

        return view('admin.audit-logs.index', compact('logs'));
    }
}
