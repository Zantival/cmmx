<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Maintenance extends Model
{
    protected $fillable = [
        'type', 'date', 'description', 'equipment_id', 'technician_id',
        'status', 'tech_notes', 'priority', 'estimated_hours', 'actual_hours', 'completion_date',
    ];

    protected $casts = [
        'date'            => 'date',
        'completion_date' => 'datetime',
        'estimated_hours' => 'decimal:1',
        'actual_hours'    => 'decimal:1',
    ];

    // Relationships
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    // Accessors
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'Critical' => '#EF4444',
            'High'     => '#F59E0B',
            'Normal'   => '#3B82F6',
            'Low'      => '#94A3B8',
            default    => '#94A3B8',
        };
    }

    public function getPriorityBgAttribute(): string
    {
        return match($this->priority) {
            'Critical' => '#FEE2E2',
            'High'     => '#FEF3C7',
            'Normal'   => '#DBEAFE',
            'Low'      => '#F1F5F9',
            default    => '#F1F5F9',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'Critical' => __('Crítica'),
            'High'     => __('Alta'),
            'Normal'   => __('Normal'),
            'Low'      => __('Baja'),
            default    => $this->priority ?? 'Normal',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'Pending'     => __('Pendiente'),
            'In Progress' => __('En Progreso'),
            'Completed'   => __('Completada'),
            default       => $this->status,
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return in_array($this->status, ['Pending', 'In Progress'])
            && $this->date->isPast();
    }

    public function getResolutionDaysAttribute(): ?int
    {
        if ($this->status !== 'Completed' || !$this->completion_date) return null;
        return (int) $this->date->diffInDays($this->completion_date);
    }
    public function partsUsed()
    {
        return $this->belongsToMany(InventoryItem::class, 'inventory_item_maintenance')
                    ->withPivot('quantity_used')
                    ->withTimestamps();
    }
}
