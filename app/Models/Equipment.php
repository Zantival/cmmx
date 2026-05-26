<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Equipment extends Model
{
    protected $fillable = [
        'name', 'brand', 'model', 'code', 'location', 'status',
        'installation_date', 'category', 'serial_number', 'criticality',
        'next_maintenance_date', 'warranty_expiry', 'notes',
    ];

    protected $casts = [
        'installation_date'    => 'date',
        'next_maintenance_date'=> 'date',
        'warranty_expiry'      => 'date',
    ];

    // Relationships
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class)->orderByDesc('date');
    }

    // Accessors
    public function getCriticalityColorAttribute(): string
    {
        return match($this->criticality) {
            'Critical' => '#EF4444',
            'High'     => '#F59E0B',
            'Medium'   => '#3B82F6',
            'Low'      => '#10B981',
            default    => '#94A3B8',
        };
    }

    public function getCriticalityBgAttribute(): string
    {
        return match($this->criticality) {
            'Critical' => '#FEE2E2',
            'High'     => '#FEF3C7',
            'Medium'   => '#DBEAFE',
            'Low'      => '#D1FAE5',
            default    => '#F1F5F9',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Operational'    => '#10B981',
            'In Repair'      => '#F59E0B',
            'Out of Service' => '#EF4444',
            default          => '#94A3B8',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'Operational'    => __('Operativo'),
            'In Repair'      => __('En Reparación'),
            'Out of Service' => __('Fuera de Servicio'),
            default          => $this->status,
        };
    }

    public function getDaysToNextMaintenanceAttribute(): ?int
    {
        if (!$this->next_maintenance_date) return null;
        return (int) now()->diffInDays($this->next_maintenance_date, false);
    }

    public function getIsWarrantyActiveAttribute(): bool
    {
        return $this->warranty_expiry && $this->warranty_expiry->isFuture();
    }
}