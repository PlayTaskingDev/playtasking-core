<?php

namespace App\Exports;

use App\Models\AwardCode;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Traits\CampaignsTrait;

class AwardCodeExport implements FromCollection
{
    use CampaignsTrait;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $campaign = $this->get_current_campaign();
        $campaign->load('code.award');

        return AwardCode::where('award_id',$campaign->code->award->id)->select('code','product','validity')->get();
    }
}
