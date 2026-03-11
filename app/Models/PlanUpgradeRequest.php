<?php

namespace App\Models;

use App\Models\Dashboard\Plan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanUpgradeRequest extends Model
{
    protected $guarded = [];

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * When the request was processed (accepted/rejected). Uses updated_at when status is not pending.
     */
    public function getProcessedAtAttribute(): ?\Carbon\Carbon
    {
        if ($this->isPending()) {
            return null;
        }
        return $this->updated_at;
    }

    /**
     * URL to the transfer receipt image for display.
     * Handles both legacy paths (/public/uploads/...) and normalized paths (uploads/...).
     */
    public function getTransferReceiptUrlAttribute(): string
    {
        $path = $this->transfer_receipt ?? '';
        if (empty($path) || !is_string($path)) {
            return '';
        }
        $path = str_replace('/public/', '', $path);
        $path = ltrim($path, '/');
        return $path ? asset($path) : '';
    }
}
