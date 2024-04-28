<?php

namespace App\Imports;

use App\Models\AdsGoogle;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithUpserts;

class AdsGoogleImport implements ToModel, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private int $count = 0;

    public function model(array $row): Model|AdsGoogle|null
    {
        if($this->count && $row[0]){
            $click = (int) preg_replace('/[^0-9]/', '', $row[4]);
            $ctr = (double) str_replace(",", ".", str_replace("%", "", $row[5]));
            $reach = round($click/$ctr*100);
            return new AdsGoogle([
                'campaign_id' => request()->get('campaign_id'),
                'name' => $row[0],
                'click' => $click,
                'reach' => $reach,
                'amount_spent' => (int) preg_replace('/[^0-9]/', '', $row[3]),
                'started_at' => $row[6],
                'ended_at' => $row[7],
            ]);
        }
        $this->count++;
        return null;
    }

    public function parseEndedAt(string $value): ?string
    {
        $result = null;
        try {
            $result = Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e){
        }

        return $result;
    }

    public function uniqueBy(): array
    {
        return ['user_id', 'name', 'started_at'];
    }
}
