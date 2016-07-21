<?php

namespace App\Repos;

use App\Entities\{
    ConstructionDaily as ConstructionDailyEntity, DailyLabor, Project, ProjectWork, ProjectChecklist, Subcontractor
};
use App\Repos\Contracts\ConstructionDaily as Contract;
use Carbon\Carbon;

class ConstructionDaily implements Contract
{
    public function create(\ArrayAccess $data, Project $project): ConstructionDailyEntity
    {
        $constructionDaily = new ConstructionDailyEntity;
        $constructionDaily->setAttribute('inspection_record', $data['inspection_record'] ?? '');
        $constructionDaily->setAttribute('important_record', $data['important_record'] ?? '');
        $constructionDaily->setAttribute('work_date', $data['work_date']);

        return $project->constructionDailies()->save($constructionDaily);
    }

    public function addDailyWork(string $seat, Subcontractor $subcontractor, ProjectWork $work, ConstructionDailyEntity $constructionDaily)
    {
        $constructionDaily->works()->save($work, compact('seat'));

        // Create checklist
        if ($work->workflow && $work->workflow->checklist) {
            $checklist = $work->workflow->checklist;
            $checkitems = $checklist->checkitems;

            $checklist = ProjectChecklist::create(
                array_merge(
                    $checklist->toArray(),
                    [
                        'project_id' => $work->project_id,
                        'project_work_id' => $work->id,
                        'subcontractor_id' => $subcontractor->id,
                        'name' => $work->name,
                        'seat' => $seat,
                        'passes_amount' => 0
                    ]
                )
            );

            foreach ($checkitems as $checkitem) {
                $checklist->checkitems()->create($checkitem->toArray());
            }
        }
    }

    public function addLabor(
        ConstructionDailyEntity $constructionDaily,
        DailyLabor $labor,
        int $amount
    ) {
        return $constructionDaily->labors()->attach(
            $labor,
            compact('amount')
        );
    }

    public function getConstructionDaily(Project $project, Carbon $date)
    {
        return (
            $project
                ->constructionDailies()
                ->whereDate('work_date', '=', $date)
                ->firstOrFail()
        );
    }
}
