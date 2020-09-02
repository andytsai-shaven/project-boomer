<?php

namespace App\Repos\Contracts;

use Illuminate\Support\Collection;
use App\Entities\{
    ConstructionDaily as ConstructionDailyEntity,
    Labor as LaborEntity
};

interface DailyLabor
{
    public function firstOrFail(
        ConstructionDailyEntity $constructionDaily,
        LaborEntity $labor
    );
}
