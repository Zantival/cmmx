<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Trabajo — WO-{{ str_pad($maintenance->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style nonce="{{ $cspNonce }}">
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #1e293b;
            font-size: 10pt;
            line-height: 1.5;
        }

        /* ─── Header ─── */
        .header {
            background-color: #0A192F;
            color: #fff;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .header .brand { font-size: 18pt; font-weight: 700; letter-spacing: -0.5px; }
        .header .brand span { color: #38BDF8; }
        .header .subtitle { font-size: 8pt; color: #94A3B8; margin-top: 3px; }
        .header .wo-number { text-align: right; }
        .header .wo-number .wo-label { font-size: 8pt; color: #94A3B8; text-transform: uppercase; letter-spacing: 1px; }
        .header .wo-number .wo-code { font-size: 20pt; font-weight: 700; color: #38BDF8; font-family: monospace; }

        /* ─── Status Banner ─── */
        .status-banner {
            padding: 8px 30px;
            font-size: 8.5pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .status-preventive { background: #DBEAFE; color: #1D4ED8; }
        .status-corrective  { background: #FEE2E2; color: #B91C1C; }

        /* ─── Body ─── */
        .body { padding: 24px 30px; }

        /* ─── Info Grid ─── */
        .info-grid { display: flex; gap: 20px; margin-bottom: 20px; }
        .info-block { flex: 1; border: 1px solid #E2E8F0; border-radius: 8px; padding: 14px; }
        .info-block .block-title {
            font-size: 7.5pt; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1.2px; color: #64748B; margin-bottom: 10px;
            border-bottom: 1px solid #F1F5F9; padding-bottom: 6px;
        }
        .info-row { margin-bottom: 6px; }
        .info-label { font-size: 7.5pt; color: #94A3B8; }
        .info-value { font-size: 9.5pt; font-weight: 600; color: #1E293B; }

        /* ─── Description ─── */
        .description-block {
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .description-block .block-title {
            font-size: 7.5pt; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1.2px; color: #64748B; margin-bottom: 10px;
        }
        .description-text {
            font-size: 9.5pt;
            color: #334155;
            line-height: 1.7;
            background: #F8FAFC;
            padding: 12px;
            border-radius: 6px;
            border-left: 3px solid #0A192F;
        }

        /* ─── Signature Area ─── */
        .signatures { display: flex; gap: 20px; margin-top: 10px; }
        .sig-block { flex: 1; border-top: 1px solid #CBD5E1; padding-top: 8px; }
        .sig-label { font-size: 8pt; color: #64748B; }
        .sig-name { font-size: 9pt; font-weight: 600; color: #1E293B; margin-top: 4px; }

        /* ─── Footer ─── */
        .footer {
            border-top: 1px solid #E2E8F0;
            padding: 12px 30px;
            display: flex;
            justify-content: space-between;
            font-size: 7.5pt;
            color: #94A3B8;
        }

        /* ─── Status Badge inline ─── */
        .badge-inline {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 8pt;
            font-weight: 600;
        }
        .badge-operational { background:#D1FAE5; color:#065F46; }
        .badge-repair      { background:#FEF3C7; color:#92400E; }
        .badge-oos         { background:#FEE2E2; color:#991B1B; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="brand">Mec<span>App</span></div>
            <div class="subtitle">Sistema de Gestión de Mantenimiento Industrial</div>
        </div>
        <div class="wo-number">
            <div class="wo-label">Orden de Trabajo</div>
            <div class="wo-code">WO-{{ str_pad($maintenance->id, 5, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>

    {{-- Status Banner --}}
    <div class="status-banner {{ $maintenance->type === 'Preventive' ? 'status-preventive' : 'status-corrective' }}">
        {{ $maintenance->type === 'Preventive' ? '📅 Mantenimiento Preventivo' : '🚨 Mantenimiento Correctivo' }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        Fecha programada: {{ \Carbon\Carbon::parse($maintenance->date)->format('d \d\e F \d\e Y') }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        Generado: {{ now()->format('d/m/Y H:i') }}
    </div>

    <div class="body">

        {{-- Info Grid --}}
        <div class="info-grid">

            {{-- Equipment Block --}}
            <div class="info-block">
                <div class="block-title">🔩 Equipo Intervenido</div>
                <div class="info-row">
                    <div class="info-label">Nombre</div>
                    <div class="info-value">{{ $maintenance->equipment->name ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Código</div>
                    <div class="info-value" style="font-family:monospace;">{{ $maintenance->equipment->code ?? '—' }}</div>
                </div>
                @if($maintenance->equipment->brand)
                <div class="info-row">
                    <div class="info-label">Marca / Modelo</div>
                    <div class="info-value">{{ $maintenance->equipment->brand }} {{ $maintenance->equipment->model }}</div>
                </div>
                @endif
                @if($maintenance->equipment->location)
                <div class="info-row">
                    <div class="info-label">Ubicación</div>
                    <div class="info-value">{{ $maintenance->equipment->location }}</div>
                </div>
                @endif
                @if($maintenance->equipment->installation_date)
                <div class="info-row">
                    <div class="info-label">Fecha Instalación</div>
                    <div class="info-value">{{ $maintenance->equipment->installation_date->format('d/m/Y') }}</div>
                </div>
                @endif
                <div class="info-row" style="margin-top:8px;">
                    <div class="info-label">Estado Actual</div>
                    <div class="info-value">
                        @php
                            $s = $maintenance->equipment->status ?? '';
                            $cls = $s === 'Operational' ? 'badge-operational' : ($s === 'In Repair' ? 'badge-repair' : 'badge-oos');
                            $label = $s === 'Operational' ? 'Operativo' : ($s === 'In Repair' ? 'En Reparación' : 'Fuera de Servicio');
                        @endphp
                        <span class="badge-inline {{ $cls }}">{{ $label }}</span>
                    </div>
                </div>
            </div>

            {{-- Technician Block --}}
            <div class="info-block">
                <div class="block-title">👷 Técnico Asignado</div>
                <div class="info-row">
                    <div class="info-label">Nombre</div>
                    <div class="info-value">{{ $maintenance->technician->name ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Correo</div>
                    <div class="info-value" style="font-size:8.5pt;">{{ $maintenance->technician->email ?? '—' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Rol</div>
                    <div class="info-value">{{ $maintenance->technician->role ?? 'Technician' }}</div>
                </div>
                <div class="info-row" style="margin-top:10px;">
                    <div class="info-label">Tipo OT</div>
                    <div class="info-value">{{ $maintenance->type }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">ID de Registro</div>
                    <div class="info-value" style="font-family:monospace;">#{{ $maintenance->id }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Creado el</div>
                    <div class="info-value">{{ $maintenance->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>

        </div>

        {{-- Description --}}
        <div class="description-block">
            <div class="block-title">📋 Descripción del Trabajo</div>
            <div class="description-text">{{ $maintenance->description }}</div>
        </div>

        {{-- Signatures --}}
        <div class="signatures">
            <div class="sig-block">
                <div class="sig-label">Técnico Responsable</div>
                <div class="sig-name">{{ $maintenance->technician->name ?? '________________________' }}</div>
            </div>
            <div class="sig-block">
                <div class="sig-label">Supervisor / Aprobación</div>
                <div class="sig-name">________________________</div>
            </div>
            <div class="sig-block">
                <div class="sig-label">Fecha de Cierre</div>
                <div class="sig-name">________________________</div>
            </div>
        </div>

    </div>

    {{-- Footer --}}
    <div class="footer">
        <div>MecApp — Sistema de Gestión de Mantenimiento Industrial</div>
        <div>WO-{{ str_pad($maintenance->id, 5, '0', STR_PAD_LEFT) }} | Generado: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

</body>
</html>
