<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuditLogResource;
use App\Models\AuditLog;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest('action_date');

        if ($request->filled('table_name')) {
            $query->where('table_name', $request->table_name);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('record_id')) {
            $query->where('record_id', $request->record_id);
        }

        $logs = $query->paginate(20);

        return $this->success(
            AuditLogResource::collection($logs),
            'Audit logs retrieved successfully'
        );
    }

    public function show($id)
    {
        $log = AuditLog::with('user')->find($id);

        if (!$log) {
            return $this->error('Audit log not found', 404);
        }

        return $this->success(
            new AuditLogResource($log),
            'Audit log retrieved successfully'
        );
    }
}