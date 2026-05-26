<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Equipment;
use App\Models\Maintenance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. Users ───────────────────────────────────────────────
        $admin = User::updateOrCreate(['email' => 'admin@cmms.com'], [
            'name'     => 'Carlos Mendoza',
            'password' => Hash::make('password'),
            'role'     => 'Admin',
        ]);

        $tech1 = User::updateOrCreate(['email' => 'tech1@cmms.com'], [
            'name'     => 'Andrés Ramírez',
            'password' => Hash::make('password'),
            'role'     => 'Technician',
        ]);

        $tech2 = User::updateOrCreate(['email' => 'tech2@cmms.com'], [
            'name'     => 'Laura García',
            'password' => Hash::make('password'),
            'role'     => 'Technician',
        ]);

        $tech3 = User::updateOrCreate(['email' => 'tech3@cmms.com'], [
            'name'     => 'Diego Herrera',
            'password' => Hash::make('password'),
            'role'     => 'Technician',
        ]);

        // ─── 2. Equipment (Activos de Planta) ───────────────────────
        $equipments = [
            [
                'code'                  => 'EQ-001',
                'name'                  => 'Generador Eléctrico Principal',
                'brand'                 => 'Caterpillar',
                'model'                 => 'C15 ACERT',
                'serial_number'         => 'CAT-C15-2022-0331',
                'category'              => 'Eléctrico',
                'location'              => 'Zona A — Cuarto de Fuerza',
                'status'                => 'Operational',
                'criticality'           => 'Critical',
                'installation_date'     => '2022-03-15',
                'next_maintenance_date' => Carbon::now()->addDays(12)->toDateString(),
                'warranty_expiry'       => '2027-03-15',
                'notes'                 => 'Generador principal de la planta. Opera 24/7. Prioridad máxima en mantenimiento.',
            ],
            [
                'code'                  => 'EQ-002',
                'name'                  => 'Banda Transportadora Línea C',
                'brand'                 => 'Flexlink',
                'model'                 => 'XL-500',
                'serial_number'         => 'FLX-XL5-2021-0720',
                'category'              => 'Maquinaria Industrial',
                'location'              => 'Línea de Empaque 1',
                'status'                => 'In Repair',
                'criticality'           => 'High',
                'installation_date'     => '2021-07-20',
                'next_maintenance_date' => Carbon::now()->addDays(3)->toDateString(),
                'warranty_expiry'       => '2024-07-20',
                'notes'                 => 'Motor principal con desgaste en rodamientos. Revisar balanceo cada 500 horas.',
            ],
            [
                'code'                  => 'EQ-003',
                'name'                  => 'Prensa Hidráulica 80T',
                'brand'                 => 'Parker',
                'model'                 => 'H-Press 80T',
                'serial_number'         => 'PRK-HP80-2019-1101',
                'category'              => 'Hidráulico',
                'location'              => 'Taller Mantenimiento B',
                'status'                => 'Out of Service',
                'criticality'           => 'High',
                'installation_date'     => '2019-11-01',
                'next_maintenance_date' => Carbon::now()->addDays(1)->toDateString(),
                'warranty_expiry'       => '2022-11-01',
                'notes'                 => 'Fuga activa en cilindro principal. Fuera de servicio hasta reemplazo de sellos.',
            ],
            [
                'code'                  => 'EQ-004',
                'name'                  => 'Compresor de Aire Tornillo',
                'brand'                 => 'Ingersoll Rand',
                'model'                 => 'R4i 15kW',
                'serial_number'         => 'IR-R4I-2023-0110',
                'category'              => 'Neumático',
                'location'              => 'Cuarto Técnico / Azotea',
                'status'                => 'Operational',
                'criticality'           => 'Medium',
                'installation_date'     => '2023-01-10',
                'next_maintenance_date' => Carbon::now()->addDays(20)->toDateString(),
                'warranty_expiry'       => '2026-01-10',
                'notes'                 => 'Purgar condensados cada semana. Cambio de aceite cada 2000 horas de operación.',
            ],
            [
                'code'                  => 'EQ-005',
                'name'                  => 'Chiller de Proceso',
                'brand'                 => 'Carrier',
                'model'                 => '30XA-250',
                'serial_number'         => 'CAR-30XA-2021-0601',
                'category'              => 'HVAC',
                'location'              => 'Exterior / Patio de Máquinas',
                'status'                => 'Operational',
                'criticality'           => 'Critical',
                'installation_date'     => '2021-06-01',
                'next_maintenance_date' => Carbon::now()->addDays(7)->toDateString(),
                'warranty_expiry'       => '2026-06-01',
                'notes'                 => 'Equipo de refrigeración para proceso. Verificar niveles de refrigerante R410A mensualmente.',
            ],
            [
                'code'                  => 'EQ-006',
                'name'                  => 'Torno CNC Horizontal',
                'brand'                 => 'Haas',
                'model'                 => 'ST-20',
                'serial_number'         => 'HAS-ST20-2020-0315',
                'category'              => 'Maquinaria Industrial',
                'location'              => 'Área de Mecanizado',
                'status'                => 'Operational',
                'criticality'           => 'High',
                'installation_date'     => '2020-03-15',
                'next_maintenance_date' => Carbon::now()->addDays(45)->toDateString(),
                'warranty_expiry'       => '2025-03-15',
                'notes'                 => 'Calibración de cabezal y chuck cada 1000 piezas producidas.',
            ],
            [
                'code'                  => 'EQ-007',
                'name'                  => 'Caldera de Vapor Industrial',
                'brand'                 => 'Cleaver Brooks',
                'model'                 => 'CB-50',
                'serial_number'         => 'CLB-CB50-2018-0901',
                'category'              => 'Combustión',
                'location'              => 'Sala de Calderas',
                'status'                => 'Operational',
                'criticality'           => 'Critical',
                'installation_date'     => '2018-09-01',
                'next_maintenance_date' => Carbon::now()->addDays(5)->toDateString(),
                'warranty_expiry'       => '2023-09-01',
                'notes'                 => 'Inspección NOM-020 anual obligatoria. Tratamiento de agua cada 15 días.',
            ],
            [
                'code'                  => 'EQ-008',
                'name'                  => 'Montacargas Eléctrico 3T',
                'brand'                 => 'Toyota',
                'model'                 => '8FBMT30',
                'serial_number'         => 'TOY-8FBM-2022-1115',
                'category'              => 'Vehículos',
                'location'              => 'Almacén Central',
                'status'                => 'Operational',
                'criticality'           => 'Medium',
                'installation_date'     => '2022-11-15',
                'next_maintenance_date' => Carbon::now()->addDays(30)->toDateString(),
                'warranty_expiry'       => '2025-11-15',
                'notes'                 => 'Carga de batería nocturna obligatoria. Revisión de llantas y frenos semanal.',
            ],
        ];

        foreach ($equipments as $eq) {
            Equipment::updateOrCreate(['code' => $eq['code']], $eq);
        }

        $eq1 = Equipment::where('code', 'EQ-001')->first();
        $eq2 = Equipment::where('code', 'EQ-002')->first();
        $eq3 = Equipment::where('code', 'EQ-003')->first();
        $eq4 = Equipment::where('code', 'EQ-004')->first();
        $eq5 = Equipment::where('code', 'EQ-005')->first();
        $eq6 = Equipment::where('code', 'EQ-006')->first();
        $eq7 = Equipment::where('code', 'EQ-007')->first();
        $eq8 = Equipment::where('code', 'EQ-008')->first();

        // ─── 3. Maintenances (OTs) ──────────────────────────────────
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Maintenance::truncate(); // Fresh OT data
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $maintenances = [
            // Critical / Overdue
            [
                'equipment_id' => $eq3->id, 'technician_id' => $tech1->id,
                'type' => 'Corrective', 'priority' => 'Critical',
                'date' => Carbon::now()->subDays(2),
                'status' => 'In Progress', 'estimated_hours' => 6.0,
                'description' => 'Fuga activa de aceite hidráulico en cilindro principal. Reemplazar sellos tipo Parker 2250PSI.',
                'tech_notes' => 'Se identificó fuga en unión roscada del cilindro. Soldadura + sellos necesarios.',
            ],
            // Critical pending today
            [
                'equipment_id' => $eq7->id, 'technician_id' => $tech2->id,
                'type' => 'Preventive', 'priority' => 'Critical',
                'date' => Carbon::now(),
                'status' => 'Pending', 'estimated_hours' => 4.0,
                'description' => 'Revisión semestral de caldera: nivel de agua, quemador, válvulas de seguridad y purga.',
            ],
            // High - upcoming
            [
                'equipment_id' => $eq2->id, 'technician_id' => $tech1->id,
                'type' => 'Corrective', 'priority' => 'High',
                'date' => Carbon::now()->addDays(1),
                'status' => 'Pending', 'estimated_hours' => 3.5,
                'description' => 'Reemplazo de rodamientos del motor de la banda (SKF 6305-2Z). Falla por calentamiento excesivo.',
            ],
            // High - in progress
            [
                'equipment_id' => $eq5->id, 'technician_id' => $tech3->id,
                'type' => 'Preventive', 'priority' => 'High',
                'date' => Carbon::now(),
                'status' => 'In Progress', 'estimated_hours' => 2.0, 'actual_hours' => 1.5,
                'description' => 'Mantenimiento trimestral del chiller: limpieza de condensadores, verificación de refrigerante R410A.',
                'tech_notes' => 'Condensadores limpios. Presión de refrigerante OK. Falta ajuste de expansión.',
            ],
            // Normal - upcoming
            [
                'equipment_id' => $eq1->id, 'technician_id' => $tech2->id,
                'type' => 'Preventive', 'priority' => 'Normal',
                'date' => Carbon::now()->addDays(12),
                'status' => 'Pending', 'estimated_hours' => 3.0,
                'description' => 'Revisión mensual: cambio de aceite motor, filtros de aire y combustible, revisión de baterías.',
            ],
            // Normal - upcoming
            [
                'equipment_id' => $eq4->id, 'technician_id' => $tech1->id,
                'type' => 'Preventive', 'priority' => 'Normal',
                'date' => Carbon::now()->addDays(20),
                'status' => 'Pending', 'estimated_hours' => 1.5,
                'description' => 'Purga de condensados en tanque principal. Inspección de aceite del compresor.',
            ],
            // Completed recent
            [
                'equipment_id' => $eq6->id, 'technician_id' => $tech3->id,
                'type' => 'Preventive', 'priority' => 'Normal',
                'date' => Carbon::now()->subDays(5),
                'status' => 'Completed', 'estimated_hours' => 2.0, 'actual_hours' => 1.8,
                'completion_date' => Carbon::now()->subDays(4),
                'description' => 'Calibración de chuck y verificación de nivel de aceite en cabezal del torno CNC.',
                'tech_notes' => 'Calibración completada. Nivel de aceite correcto. Equipo en óptimas condiciones.',
            ],
            // Completed
            [
                'equipment_id' => $eq1->id, 'technician_id' => $tech2->id,
                'type' => 'Corrective', 'priority' => 'High',
                'date' => Carbon::now()->subDays(10),
                'status' => 'Completed', 'estimated_hours' => 2.5, 'actual_hours' => 3.0,
                'completion_date' => Carbon::now()->subDays(9),
                'description' => 'Ajuste y apretado de bornes en tablero de control por vibración excesiva detectada.',
                'tech_notes' => 'Se apretaron todos los bornes y se aplicó limpia contactos. Funcionamiento normal.',
            ],
            // Completed older
            [
                'equipment_id' => $eq8->id, 'technician_id' => $tech1->id,
                'type' => 'Preventive', 'priority' => 'Low',
                'date' => Carbon::now()->subDays(15),
                'status' => 'Completed', 'estimated_hours' => 1.0, 'actual_hours' => 1.0,
                'completion_date' => Carbon::now()->subDays(14),
                'description' => 'Revisión semanal de llantas, nivel de batería y sistema de frenos del montacargas.',
                'tech_notes' => 'Todo en orden. Presión de llantas ajustada a 100 PSI.',
            ],
            // Low - future
            [
                'equipment_id' => $eq6->id, 'technician_id' => $tech3->id,
                'type' => 'Preventive', 'priority' => 'Low',
                'date' => Carbon::now()->addDays(45),
                'status' => 'Pending', 'estimated_hours' => 2.0,
                'description' => 'Lubricación general y ajuste de contrahusillos del torno CNC.',
            ],
            // New OTs
            [
                'equipment_id' => $eq1->id, 'technician_id' => $tech1->id,
                'type' => 'Corrective', 'priority' => 'High',
                'date' => Carbon::now()->subHours(5),
                'status' => 'In Progress', 'estimated_hours' => 2.0,
                'description' => 'Ruido inusual en el ventilador de enfriamiento del generador. Posible desbalanceo.',
            ],
            [
                'equipment_id' => $eq4->id, 'technician_id' => $tech2->id,
                'type' => 'Corrective', 'priority' => 'Critical',
                'date' => Carbon::now()->subMinutes(30),
                'status' => 'Pending', 'estimated_hours' => 4.0,
                'description' => 'Caída de presión repentina en línea principal de aire comprimido.',
            ],
            [
                'equipment_id' => $eq8->id, 'technician_id' => $tech3->id,
                'type' => 'Preventive', 'priority' => 'Normal',
                'date' => Carbon::now()->addDays(2),
                'status' => 'Pending', 'estimated_hours' => 1.5,
                'description' => 'Revisión periódica de la cadena de tracción y lubricación de mástil.',
            ],
        ];

        foreach ($maintenances as $m) {
            Maintenance::create($m);
        }

        // ─── 4. Seeders Auxiliares ───────────────────────────────────
        $this->call(MlDataSeeder::class);
        $this->call(InventorySeeder::class);
    }
}
