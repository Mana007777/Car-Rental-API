<?php

namespace App\DTOs\AuditLog;

use Illuminate\Http\Request;

class AuditLogFilterData
{
    public function __construct(
        public readonly ?string $table_name,
        public readonly ?string $action,
        public readonly ?int $user_id,
        public readonly ?int $record_id,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            table_name: $request->filled('table_name') ? $request->table_name : null,
            action: $request->filled('action') ? $request->action : null,
            user_id: $request->filled('user_id') ? (int) $request->user_id : null,
            record_id: $request->filled('record_id') ? (int) $request->record_id : null,
        );
    }
}