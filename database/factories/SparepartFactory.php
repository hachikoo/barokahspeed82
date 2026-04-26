<?php

namespace Database\Factories;

use App\Models\Sparepart;
use App\Models\Stok;
use Illuminate\Database\Eloquent\Factories\Factory;

class SparepartFactory extends Factory
{
    protected $model = Sparepart::class;

    public function definition(): array
    {
        // Daftar data acak agar variatif
        $kategori = ['MESIN', 'BODY', 'KELISTRIKAN', 'OLI', 'BAN', 'REM', 'CVT', 'BAUT'];
        $merk = ['HONDA', 'YAMAHA', 'FEDERAL', 'ASPIRA', 'NPP', 'DENSO', 'BOSCH'];
        $part_names = ['KAMPAS', 'BUSI', 'KABEL', 'GEAR', 'RANTAI', 'SHOCK', 'PIRINGAN', 'ROLLER', 'V-BELT'];

        $hargaBeli = $this->faker->numberBetween(5000, 300000);

        return [
            // Generate Kode PRT-XXXXX yang unik
            'kode_part' => 'PRT-' . $this->faker->unique()->numberBetween(10000, 99999),
            'nama_part' => $this->faker->randomElement($part_names) . ' ' . $this->faker->randomElement($merk) . ' ' . strtoupper($this->faker->word()),
            'kategori'  => $this->faker->randomElement($kategori),
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaBeli + $this->faker->numberBetween(5000, 50000),
            'rak'       => $this->faker->randomElement(['A', 'B', 'C', 'D']) . $this->faker->numberBetween(1, 10),
        ];
    }

    // Fungsi ini otomatis membuat record di tabel STOK setelah data Sparepart dibuat
    public function configure()
    {
        return $this->afterCreating(function (Sparepart $sparepart) {
            Stok::create([
                'sparepart_id' => $sparepart->id,
                'jumlah_stok'  => $this->faker->numberBetween(1, 100),
            ]);
        });
    }
}
