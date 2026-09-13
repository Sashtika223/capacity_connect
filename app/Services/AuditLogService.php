<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Record an audit log entry.
     */
    public static function log(string $action, $entity = null, ?array $previousValues = null, ?array $newValues = null)
    {
        $entityType = null;
        $entityId = null;

        if (is_object($entity)) {
            $entityType = get_class($entity);
            $entityId = $entity->id ?? null;
        } elseif (is_string($entity)) {
            $entityType = $entity;
        }

        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'previous_values' => $previousValues,
            'new_values' => $newValues,
        ]);
    }
}
