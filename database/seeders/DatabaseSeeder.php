<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Family;
use App\Models\Member;
use App\Models\AllowedEmail;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $familyId = 'fam_utama';

        // 1. Create Default Family
        Family::updateOrCreate(
            ['id' => $familyId],
            [
                'nama' => 'Keluarga Utama',
                'cutoff_day' => 1,
            ]
        );

        // 2. Create Default Members
        $members = [
            ['area_key' => 'keluarga', 'nama' => 'Keluarga', 'tipe' => 'keluarga', 'email' => 'suami@keluarga.com', 'family_id' => $familyId, 'urutan' => 0],
            ['area_key' => 'suami', 'nama' => 'Suami', 'tipe' => 'suami', 'email' => 'suami@keluarga.com', 'family_id' => $familyId, 'urutan' => 1],
            ['area_key' => 'istri', 'nama' => 'Istri', 'tipe' => 'istri', 'email' => 'istri@keluarga.com', 'family_id' => $familyId, 'urutan' => 2],
        ];

        foreach ($members as $m) {
            Member::updateOrCreate(['area_key' => $m['area_key']], $m);
        }

        // 3. Create Default Allowed Emails (Users)
        $accounts = [
            [
                'email' => 'suami@keluarga.com',
                'nama' => 'Suami',
                'role' => 'leader',
                'family_id' => $familyId,
                'is_platform_admin' => true,
                'status' => 'active',
                'password' => Hash::make('password'),
            ],
            [
                'email' => 'istri@keluarga.com',
                'nama' => 'Istri',
                'role' => 'member',
                'family_id' => $familyId,
                'is_platform_admin' => false,
                'status' => 'active',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($accounts as $acc) {
            AllowedEmail::updateOrCreate(['email' => $acc['email']], $acc);
        }

        // 4. Default Categories
        $defaultCats = [
            // Suami
            ['id' => 'cat_suami_1', 'area' => 'suami', 'jenis' => 'keluar', 'nama' => 'Bensin & Motor', 'urutan' => 1],
            ['id' => 'cat_suami_2', 'area' => 'suami', 'jenis' => 'keluar', 'nama' => 'Makan & Kopi', 'urutan' => 2],
            ['id' => 'cat_suami_3', 'area' => 'suami', 'jenis' => 'keluar', 'nama' => 'Pulsa & Internet', 'urutan' => 3],
            ['id' => 'cat_suami_4', 'area' => 'suami', 'jenis' => 'masuk', 'nama' => 'Gaji & Income', 'urutan' => 1],

            // Istri
            ['id' => 'cat_istri_1', 'area' => 'istri', 'jenis' => 'keluar', 'nama' => 'Belanja Dapur', 'urutan' => 1],
            ['id' => 'cat_istri_2', 'area' => 'istri', 'jenis' => 'keluar', 'nama' => 'Kebutuhan Anak', 'urutan' => 2],
            ['id' => 'cat_istri_3', 'area' => 'istri', 'jenis' => 'keluar', 'nama' => 'Jajan & Kuliner', 'urutan' => 3],
            ['id' => 'cat_istri_4', 'area' => 'istri', 'jenis' => 'masuk', 'nama' => 'Jatah Suami', 'urutan' => 1],

            // Keluarga
            ['id' => 'cat_kel_1', 'area' => 'keluarga', 'jenis' => 'keluar', 'nama' => 'Listrik & Token', 'urutan' => 1],
            ['id' => 'cat_kel_2', 'area' => 'keluarga', 'jenis' => 'keluar', 'nama' => 'Air & PDAM', 'urutan' => 2],
            ['id' => 'cat_kel_3', 'area' => 'keluarga', 'jenis' => 'keluar', 'nama' => 'Sedekah & Infak', 'urutan' => 3],
            ['id' => 'cat_kel_4', 'area' => 'keluarga', 'jenis' => 'masuk', 'nama' => 'Pemasukan Keluarga', 'urutan' => 1],
        ];

        foreach ($defaultCats as $c) {
            Category::updateOrCreate(['id' => $c['id']], $c);
        }
    }
}
