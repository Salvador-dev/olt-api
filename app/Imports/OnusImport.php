<?php

namespace App\Imports;

use App\Models\Onu;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class OnusImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */


    public function model(array $row)
    {
        return new Onu([
            'name'  => $row['name'],
            'unique_external_id' => $row['unique_external_id'],
            'status'    => $row['status'],
            'sn'    => $row['sn'],
            'catv'    => $row['catv'],
            'authorization_date'    => $row['authorization_date'],
            'olt_id'    => $row['olt_id'],
            'zone_id'    => $row['zone_id'],
            'onu_type_id'    => 11,
            'signal_1310'    => $row['signal_1310'],
            'signal' => $row['signal'],
        ]);
    }

    public function batchSize(): int
    {
        return 20000;
    }

    public function chunkSize(): int
    {
        return 20000;
    }
}
