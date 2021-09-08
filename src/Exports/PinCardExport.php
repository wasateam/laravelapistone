<?php

namespace Wasateam\Laravelapistone\Exports;

use Wasateam\Laravelapistone\Models\PinCard;
use Maatwebsite\Excel\Concerns\FromCollection;

class PinCardExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PinCard::all();
    }
}
