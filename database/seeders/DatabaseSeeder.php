<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ([
            ['nombre' => 'MacBook Air 13" M3', 'sku' => 'APP-MBA-M3', 'categoria' => 'Computadoras', 'descripcion' => 'Chip M3, 16 GB de memoria y SSD de 512 GB.', 'precio' => 1299, 'stock' => 8],
            ['nombre' => 'ThinkPad X1 Carbon Gen 12', 'sku' => 'LEN-X1C-G12', 'categoria' => 'Computadoras', 'descripcion' => 'Intel Core Ultra 7, 32 GB y pantalla OLED de 14 pulgadas.', 'precio' => 1849, 'stock' => 5],
            ['nombre' => 'iPhone 16 Pro', 'sku' => 'APP-IP16-PRO', 'categoria' => 'Celulares', 'descripcion' => '256 GB, cámara Pro y pantalla Super Retina XDR.', 'precio' => 999, 'stock' => 12],
            ['nombre' => 'Galaxy S25 Ultra', 'sku' => 'SAM-S25-ULT', 'categoria' => 'Celulares', 'descripcion' => '256 GB, S Pen integrado y cámara de 200 MP.', 'precio' => 1199, 'stock' => 7],
            ['nombre' => 'Dell UltraSharp 27" 4K', 'sku' => 'DEL-U2723QE', 'categoria' => 'Monitores', 'descripcion' => 'Monitor IPS 4K con USB-C y concentrador de 90 W.', 'precio' => 579, 'stock' => 10],
            ['nombre' => 'Logitech MX Master 3S', 'sku' => 'LOG-MXM3S', 'categoria' => 'Accesorios', 'descripcion' => 'Mouse inalámbrico ergonómico con seguimiento de 8000 DPI.', 'precio' => 99, 'stock' => 24],
        ] as $producto) {
            $categoria = Categoria::firstOrCreate(
                ['nombre_categoria' => $producto['categoria']],
                ['activo' => true],
            );

            unset($producto['categoria']);
            Producto::firstOrCreate(
                ['sku' => $producto['sku']],
                [...$producto, 'categoria_id' => $categoria->id],
            );
        }
    }
}
