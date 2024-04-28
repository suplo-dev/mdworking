<?php

namespace App\Imports;

use App\Models\CampaignGoogle;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithUpserts;

class CampaignGoogleImport implements ToModel, WithUpserts
{
    private int $count = 0;

    public function model(array $row): Model|CampaignGoogle|null
    {
        if($this->count && $row[0]){
            new CampaignGoogle([
                'user_id' => User::query()->where('email', '=', $row[1])->first()?->id ?? 1,
                'name' => $row[0],
                'type' => 1,
            ]);
        }
        $this->count++;
        return null;
    }

    public function uniqueBy(): array
    {
        return ['user_id', 'name'];
    }
}
