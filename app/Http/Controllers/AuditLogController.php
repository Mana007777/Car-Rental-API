<?php

namespace App\Http\Controllers;

use App\Actions\AuditLog\ListAuditLogsAction;
use App\Actions\AuditLog\ShowAuditLogAction;
use App\DTOs\AuditLog\AuditLogFilterData;
use App\Http\Resources\AuditLogResource;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    use ApiResponse;

    public function index(Request $request, ListAuditLogsAction $action)
    {
        return $this->success(
            AuditLogResource::collection($action->execute(AuditLogFilterData::fromRequest($request))),
            'Audit logs retrieved successfully'
        );
    }

    public function show(int $id, ShowAuditLogAction $action)
    {
        try {
            return $this->success(
                new AuditLogResource($action->execute($id)),
                'Audit log retrieved successfully'
            );
        } catch (ModelNotFoundException) {
            return $this->error('Audit log not found', 404);
        }
    }
}