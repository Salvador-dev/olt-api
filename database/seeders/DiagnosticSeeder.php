<?php

namespace Database\Seeders;

use App\Models\Diagnostic;
use App\Models\Onu;
use App\Models\Signal;
use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiagnosticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $onus = Onu::all();

        foreach ($onus as $onu) {
            
            $signal_value = number_format(rand(-5, -60), 2);

            $signal = Signal::where('max_frequency', '<=', $signal_value)->first();

            Diagnostic::create([
                'signal_value' => $signal_value, 
                'distance' => (string) rand(100, 6000), 
                'onu_id' => $onu->id,
                'status_id' => Status::inRandomOrder()->first()->id,
                'signal_id' => $signal->signal_id,
            ]);
            
        }

    }
}
