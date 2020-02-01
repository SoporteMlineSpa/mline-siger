<?php

use App\Abastecimiento;
use App\Empresa;
use Illuminate\Database\Seeder;

class ClientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empresa = Empresa::create([
            "razon_social" => 'AUSTRALIS MAR S.A',
            "rut" => '76003885-7',
            "giro" => 'CULTIVO ESPECIES ACUATICAS',
            "direccion" => 'SANTA ROSA N°560, OFICINA 15 A, PUERTO VARAS'
        ]);

        $empresa->users()->create([
            "name" => 'Cristian Carrillo',
            "email" => 'ccarrillo@australis-sa.com',
            "password" => bcrypt('$F?v@Q5)')
        ]);

        $empresa->centros()->createMany([
            [
                "nombre"=> "Costa",
                "direccion"=> "Sector San José s/n, Calbuco",
                "comuna"=> "Calbuco",
                "ciudad"=> "Calbuco"
            ],
            [
                "nombre"=> "Humos 2",
                "direccion"=> "Sector San José s/n, Calbuco",
                "comuna"=> "Calbuco",
                "ciudad"=> "Calbuco"
            ],
            [
                "nombre"=> "Traiguén",
                "direccion"=> "Sector San José s/n, Calbuco",
                "comuna"=> "Calbuco",
                "ciudad"=> "Calbuco"
            ],
            [
                "nombre"=> "Humos 3",
                "direccion"=> "Sector San José s/n, Calbuco",
                "comuna"=> "Calbuco",
                "ciudad"=> "Calbuco"
            ],
            [
                "nombre"=> "Humos 5",
                "direccion"=> "Sector San José s/n, Calbuco",
                "comuna"=> "Calbuco",
                "ciudad"=> "Calbuco"
            ],
            [
                "nombre"=> "Humos 6",
                "direccion"=> "Sector San José s/n, Calbuco",
                "comuna"=> "Calbuco",
                "ciudad"=> "Calbuco"
            ],
            [
                "nombre"=> "Melchor 4",
                "direccion"=> "Sector San José s/n, Calbuco",
                "comuna"=> "Calbuco",
                "ciudad"=> "Calbuco"
            ],
            [
                "nombre"=> "Moraleda",
                "direccion"=> "Sector San José s/n, Calbuco",
                "comuna"=> "Calbuco",
                "ciudad"=> "Calbuco"
            ]
        ]);

        $empresa = Empresa::create([
            "razon_social" => 'Salmones Blumar S.A',
            "rut" => '76653690-5',
            "giro" => 'ACUICULTURA',
            "direccion" => 'Avenida Juan Soler Manfredini 11 Of 1202, Puerto Montt'
        ]);

        $empresa->users()->create([
            "name" => 'Alejandro Guerrero',
            "email" => 'alejandro.guerrero@blumar.com',
            "password" => bcrypt('9y#G<D\r')
        ]);

        $empresa->centros()->createMany([
            [
                "nombre" => "Caicura",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Chivato 1",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Chivato 2",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Concheo 2",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Dring 3",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Dring 1",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Elena NW",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Ester",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Forsyth",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Level",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Midhurst",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Ninualac 1",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Ninualac 2",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Orestes",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Punta Rouse",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Se Forsyth",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Tangbac",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Team Baños 1",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Team Depto Baños 2",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ],
            [
                "nombre" => "Victoria",
                "direccion" => "Camino a Chinquihue, Km 5,1 Puerto Montt",
                "comuna" => "Puerto Montt",
                "ciudad" => "Puerto Montt"
            ]
        ]);

        $empresa = Empresa::create([
            "razon_social" => 'Exportadora Los Fiordos Limitada',
            "rut" => '79872420-7',
            "giro" => 'REPRODUCCION Y CRIANZAS DE PECES MARINOS',
            "direccion" => 'Avenida Diego Portales n° 2000, Puerto Montt'
        ]);

        $empresa->users()->createMany([
            [
                "name" => 'Pablo Villagran',
                "email" => 'pablo.villagran@aquachile.cl',
                "password" => bcrypt('[pmQ3vy?')
            ]
        ]);

        $empresa->centros()->createMany([
            [
                "nombre" => "Acuario",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Angostura",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Bahia Anita",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Catalina",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Esperanza",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Estero Nieto",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Estero Soto",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Gala 1",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Graffer",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Isla Suarez",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Marta",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Martina",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Melimoyu",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Piscicultura Magdalena",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Punta Ganso",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "San Andres",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Villegas",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Bodega Cisnes",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Acuario",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Angostura",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Base Melinka",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Betecoi",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Canal Perez Norte",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Carabelas",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Chaffer",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Concoto",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Cuptana 9",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Elena",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Garrao 2",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Isla May",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Lagreze",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Luna 2",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Navarro",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Serrano",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Sierra",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Valverde 4",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Valverde 5",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Valverde 6",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => "Verdugo 1",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ],
            [
                "nombre" => " Verdugo 2",
                "direccion" => "Camino a Repollal s/n, Melinka",
                "comuna" => "Guaitecas",
                "ciudad" => "Guaitecas"
            ]
        ]);

        $empresa = Empresa::create([
            "razon_social" => 'Empresas Aquachile S.A',
            "rut" => '86247400-7',
            "giro" => 'Cultivo Marinos, Procesadora de Productos',
            "direccion" => 'Cardonal s/n Lote B, Puerto Montt'
        ]);

        $empresa->users()->create([
            "name" => 'Ana Aldea Ainol',
            "email" => 'ana.aldea@aquachile.cl',
            "password" => bcrypt('F>:-kC3R')
        ]);

        $empresa->centros()->createMany([
            [
                "nombre" => "Avellano",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Cascada",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Pangal 1",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Pangal 2",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Pangal 3",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Punta Gonzalez",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Avellano",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Cascada",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Pangal 1",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Pangal 2",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Pangal 3",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Punta Gonzalez",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Avellano",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Cascada",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Pangal 1",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Pangal 2",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Pangal 3",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Punta Gonzalez",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Avellano",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ],
            [
                "nombre" => "Cascada",
                "direccion" => "Arturo Prat 313, Puerto Cisnes",
                "comuna" => "Puerto Cisnes",
                "ciudad" => "Puerto Cisnes"
            ]
        ]);

        $abastecimiento = Abastecimiento::create([
            "nombre" => 'Muelle Frowuar',
            "comuna" => 'Calbuco',
            "ciudad" => 'San Jose'
        ]);

        $abastecimiento = Abastecimiento::create([
            "nombre" => 'Muelle Oxxean',
            "comuna" => 'Puerto Montt',
            "ciudad" => 'Chinquihue'
        ]);
        
        $abastecimiento = Abastecimiento::create([
            "nombre" => 'Puerto Cisnes',
            "comuna" => 'Cisnes',
            "ciudad" => 'Puerto Cisnes'
        ]);

        $abastecimiento = Abastecimiento::create([
            "nombre" => 'Melinka',
            "comuna" => 'Guaitecas',
            "ciudad" => 'Melinka'
        ]);

    }
}
