<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DatePeriod;
use DateInterval;

use App\Entities\{ Project };

class CostEstimationsController extends Controller
{
    public function index($projectId)
    {
        $project = Project::findOrFail($projectId);

        $datePeriod = $this->datePeriodToCollection(
            new DatePeriod(
                (new Carbon('today'))->subMonths('5'),
                new DateInterval('P1M'),
                new Carbon('today')
            )
        );

        $datePeriod = $datePeriod->reverse();

        $today = new Carbon('today');

        return view('cost-estimations.index', compact('project', 'datePeriod', 'today'));
    }

    public function show($projectId, $date)
    {
        $project = Project::findOrFail($projectId);

        $date = new Carbon($date);

        return view('cost-estimations.show', compact('project', 'date'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    private function datePeriodToCollection(DatePeriod $datePeriod)
    {
        $collection = collect();

        foreach ($datePeriod as $date) {
            $collection->push($date->format('Y-m-d'));
        }

        return $collection;
    }
}
