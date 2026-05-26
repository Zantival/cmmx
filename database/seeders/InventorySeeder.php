<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Aceite Hidráulico ISO 68',
                'sku' => 'OIL-H68-01',
                'description' => 'Aceite para sistemas hidráulicos industriales de alta presión.',
                'stock' => 50,
                'unit_price' => 45.50,
                'category' => 'Consumable',
                'min_stock' => 10,
            ],
            [
                'name' => 'Filtro de Aire de Partículas',
                'sku' => 'FLT-AP-X2',
                'description' => 'Filtro de alta eficiencia para compresores industriales.',
                'stock' => 12,
                'unit_price' => 28.00,
                'category' => 'Spare Part',
                'min_stock' => 5,
            ],
            [
                'name' => 'Banda de Transmisión Sincróntica',
                'sku' => 'BLT-SNC-88',
                'description' => 'Banda reforzada para motores de 10HP.',
                'stock' => 3,
                'unit_price' => 75.00,
                'category' => 'Spare Part',
                'min_stock' => 5, // Trigger "Low Stock" alert
            ],
            [
                'name' => 'Grasa Multiusos Litio',
                'sku' => 'GRS-LIT-500',
                'description' => 'Grasa para rodamientos de alta temperatura.',
                'stock' => 0,
                'unit_price' => 15.20,
                'category' => 'Consumable',
                'min_stock' => 2, // Trigger "Out of Stock" alert
            ],
            [
                'name' => 'Multímetro Digital Pro',
                'sku' => 'TLS-MUR-01',
                'description' => 'Herramienta de medición eléctrica de precisión.',
                'stock' => 5,
                'unit_price' => 120.00,
                'category' => 'Tool',
                'min_stock' => 1,
            ],
            [
                'name' => 'Rodamiento de Bolas SKF 6204',
                'sku' => 'BRG-SKF-6204',
                'description' => 'Rodamiento radial de bolas para motores eléctricos.',
                'stock' => 25,
                'unit_price' => 18.75,
                'category' => 'Spare Part',
                'min_stock' => 10,
            ],
            [
                'name' => 'Sello Mecánico de Viton 1"',
                'sku' => 'SEL-MEC-V10',
                'description' => 'Sello para bombas centrífugas resistentes a químicos.',
                'stock' => 8,
                'unit_price' => 55.00,
                'category' => 'Spare Part',
                'min_stock' => 4,
            ],
            [
                'name' => 'Interruptor Termomagnético 3P 40A',
                'sku' => 'ELE-BRK-40A',
                'description' => 'Protección eléctrica para tableros de control.',
                'stock' => 15,
                'unit_price' => 85.20,
                'category' => 'Consumable',
                'min_stock' => 3,
            ],
            [
                'name' => 'Limpiador de Contactos Eléctricos',
                'sku' => 'CNS-ELC-SPR',
                'description' => 'Aerosol para limpieza de circuitos y contactores.',
                'stock' => 30,
                'unit_price' => 12.50,
                'category' => 'Consumable',
                'min_stock' => 5,
            ],
            [
                'name' => 'Kit de Llaves Allen (Métrico)',
                'sku' => 'TLS-ALN-KIT',
                'description' => 'Set de llaves hexagonales de acero cromo vanadio.',
                'stock' => 10,
                'unit_price' => 35.00,
                'category' => 'Tool',
                'min_stock' => 2,
            ],
            [
                'name' => 'Válvula Solenoide 24V DC',
                'sku' => 'PNU-SOL-24V',
                'description' => 'Válvula de control para sistemas neumáticos de precisión.',
                'stock' => 18,
                'unit_price' => 42.15,
                'category' => 'Spare Part',
                'min_stock' => 4,
            ],
            [
                'name' => 'Sensor de Proximidad Inductivo',
                'sku' => 'SNS-PRX-M18',
                'description' => 'Sensor para detección de metales en líneas de producción.',
                'stock' => 22,
                'unit_price' => 29.90,
                'category' => 'Spare Part',
                'min_stock' => 6,
            ],
            [
                'name' => 'Relé de Estado Sólido 40A',
                'sku' => 'ELE-SSR-40A',
                'description' => 'Relé para control de cargas resistivas e inductivas.',
                'stock' => 14,
                'unit_price' => 19.50,
                'category' => 'Spare Part',
                'min_stock' => 5,
            ],
            [
                'name' => 'Acoplamiento Flexible de Araña',
                'sku' => 'MEC-CPL-FLX',
                'description' => 'Acople para conexión de motores a ejes de transmisión.',
                'stock' => 12,
                'unit_price' => 24.80,
                'category' => 'Spare Part',
                'min_stock' => 4,
            ],
            [
                'name' => 'Lubricante Seco de Teflón',
                'sku' => 'CNS-LUB-TEF',
                'description' => 'Aerosol lubricante para zonas de alta fricción sin residuos grasos.',
                'stock' => 40,
                'unit_price' => 14.20,
                'category' => 'Consumable',
                'min_stock' => 8,
            ],
            [
                'name' => 'Cámara Termográfica Industrial',
                'sku' => 'TLS-CAM-THR',
                'description' => 'Herramienta para detección de puntos calientes en tableros y motores.',
                'stock' => 2,
                'unit_price' => 850.00,
                'category' => 'Tool',
                'min_stock' => 1,
            ],
        ];

        foreach ($items as $item) {
            InventoryItem::updateOrCreate(['sku' => $item['sku']], $item);
        }
    }
}
