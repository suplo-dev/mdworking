<?php

namespace App\Imports;

use App\Models\CampaignFacebook;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithUpserts;

class CampaignFacebookImport implements ToModel, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private int $count = 0;

    public function model(array $row): Model|CampaignFacebook|null
    {
        if($this->count && $row[0]){
            return new CampaignFacebook([
                'user_id' => User::query()->where('email', '=', $row[3])->first()?->id ?? 1,
                'name' => $row[2],
                'status' => $this->parseEndedAt($row[13]) === null,
                'type' => $row[7] !== null ? $row[8] === 'actions:onsite_conversion.lead_grouped' : null,
                'result' => $row[7],
                'reach' => $row[9],
                'impression' => $row[10],
                'amount_spent' => $row[12],
                'started_at' => $row[0],
                'ended_at' => $this->parseEndedAt($row[13]),
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
