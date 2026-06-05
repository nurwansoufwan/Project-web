<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Equipment;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Admin User
        User::updateOrCreate(
            ['email' => 'nurwansoufwanasalam@gmail.com'],
            [
                'name' => 'Nurwan Soufwan',
                'password' => Hash::make('nurwan123'),
            ]
        );

        // Seed Camping Equipments
        $equipments = [
            [
                'name' => 'Tenda Dome 4 Orang (Consina)',
                'category' => 'Tenda',
                'rental_price_per_day' => 45000,
                'stock' => 8,
                'description' => 'Tenda dome double layer berkapasitas 4 orang, tahan hujan dan angin sedang.',
                'image_path' => null,
            ],
            [
                'name' => 'Tenda Dome 2 Orang (Eiger)',
                'category' => 'Tenda',
                'rental_price_per_day' => 35000,
                'stock' => 5,
                'description' => 'Tenda ringan berkapasitas 2 orang cocok untuk hiking.',
                'image_path' => null,
            ],
            [
                'name' => 'Tas Carrier 60L (Arei)',
                'category' => 'Tas Carrier',
                'rental_price_per_day' => 25000,
                'stock' => 10,
                'description' => 'Tas punggung carrier kapasitas 60 liter dengan teknologi torso backsystem.',
                'image_path' => null,
            ],
            [
                'name' => 'Sleeping Bag Polar Bulu',
                'category' => 'Sleeping Bag',
                'rental_price_per_day' => 10000,
                'stock' => 15,
                'description' => 'Sleeping bag dengan lapisan dalam polar hangat, nyaman hingga suhu 10 derajat.',
                'image_path' => null,
            ],
            [
                'name' => 'Kompor Portabel Windproof',
                'category' => 'Alat Masak',
                'rental_price_per_day' => 12000,
                'stock' => 12,
                'description' => 'Kompor portabel menggunakan gas kaleng mini, tahan angin dengan model kembang.',
                'image_path' => null,
            ],
            [
                'name' => 'Nesting Cooking Set DS-308',
                'category' => 'Alat Masak',
                'rental_price_per_day' => 15000,
                'stock' => 8,
                'description' => 'Paket panci dan penggorengan aluminium ringan isi 2-3 orang.',
                'image_path' => null,
            ],
            [
                'name' => 'Lentera LED Rechargeable',
                'category' => 'Lampu',
                'rental_price_per_day' => 8000,
                'stock' => 20,
                'description' => 'Lampu tenda gantung LED super terang yang bisa dicharge USB.',
                'image_path' => null,
            ],
            [
                'name' => 'Matras Spons Gulung',
                'category' => 'Lain-lain',
                'rental_price_per_day' => 4000,
                'stock' => 25,
                'description' => 'Matras spons hitam tebal 3mm untuk alas tidur di dalam tenda.',
                'image_path' => null,
            ],
        ];

        foreach ($equipments as $item) {
            Equipment::updateOrCreate(['name' => $item['name']], $item);
        }

        // Seed Customers
        $customers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@gmail.com',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 12, Jakarta Pusat',
            ],
            [
                'name' => 'Rina Wijaya',
                'email' => 'rina.wijaya@yahoo.com',
                'phone' => '085678901234',
                'address' => 'Perum Indah Regency Blok B-4, Sleman, Yogyakarta',
            ],
            [
                'name' => 'Joko Susilo',
                'email' => 'joko.susilo@outlook.com',
                'phone' => '089912345678',
                'address' => 'Jl. Veteran Gg. Damai No. 88, Malang, Jawa Timur',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(['email' => $customer['email']], $customer);
        }
    }
}
