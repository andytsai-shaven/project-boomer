<?php

namespace App\Http\Controllers;

use App\Entities\DetailingflowType;
use App\Entities\MainflowType;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StandardWorkflow\{
    StoreRequest as StoreWorkflowRequest,
    EditRequest as EditWorkflowRequest,
    UpdateRequest as UpdateWorkflowRequest,
    DestroyRequest as DestroyWorkflowRequest
};
use App\Http\Requests\StandardWorkflowitem\{
    StoreRequest as StoreWorkflowitemRequest,
    UpdateRequest as UpdateWorkflowitemRequest,
    DestroyRequest as DestroyWorkflowitemRequest
};
use App\Http\Controllers\Controller;

use App\Entities\Work;
use App\Entities\Checklist;
use App\Entities\Workflow;
use App\Entities\WorkflowNode;

class WorkflowsController extends Controller
{
    public function index(Request $request)
    {
        $workflows = Workflow::all();

        if ($request->ajax()) {
            return response()->json(compact('workflows'));
        }

        return view('workflows.index')->withWorkflows($workflows);
    }

    public function indexOfNodes($workflowId)
    {
        $workflow = Workflow::with('nodes')->findOrFail($workflowId);

        $nodes = $workflow->nodes;

        return response()->json(compact('workflow', 'nodes'));
    }

    public function create()
    {
        return view('workflows.create');
    }

    public function store(StoreWorkflowRequest $request)
    {
        $user = $request->user();

        $workflow = $user->workflows()->create([
            'name' => $request->name
        ]);

        if ($request->has('work_ids')) {
            $workIds = explode(',', $request->input('work_ids'));
            $works = Work::findOrFail($workIds);

            foreach ($works as $work) {
                $work->update(['workflow_id' => $workflow->id]);
            }
        }

        if ($request->ajax()) {
            return response()->json(compact('workflow'));
        }

        return redirect()->route('workflows.show', $workflow->id);
    }

    public function storeOfNodes($workflowId, StoreWorkflowitemRequest $request)
    {
        $node = Workflow::findOrFail($workflowId)
            ->nodes()
            ->create([
                'order' => $request->order,
                'title' => $request->title
            ]);

        return response()->json(compact('node'));
    }

    public function show($id, Request $request)
    {
        $workflow = Workflow::findOrFail($id);

        if ($request->ajax()) {
            return response()->json(compact('workflow'));
        }

        return view('workflows.show')->withWorkflow($workflow);
    }

    public function showOfNodes($workflowId, $id)
    {
        $node = WorkflowNode::whereWorkflowId($workflowId)
            ->whereId($id)
            ->firstOrFail();

        return response()->json(compact('node'));
    }

    public function edit($id, EditWorkflowRequest $request)
    {
        $workflow = Workflow::findOrFail($id);

        return view('workflows.edit')->withWorkflow($workflow);
    }

    public function update($id, UpdateWorkflowRequest $request)
    {
        $workflow = Workflow::findOrFail($id);

        $workflow->update(array_only($request->all(), 'name'));

        return redirect()->route('workflows.index');
    }

    public function updateOfNodes($workflowId, $id, UpdateWorkflowitemRequest $request)
    {
        $node = WorkflowNode::whereWorkflowId($workflowId)
            ->whereId($id)
            ->firstOrFail();

        $node->update([
            'order' => $request->order,
            'title' => $request->title
        ]);

        return response()->json(compact('node'));
    }

    public function destroy($id, DestroyWorkflowRequest $request)
    {
        $workflow = Workflow::findOrFail($id);

        WorkflowNode::destroy($workflow->nodes()->lists('id')->all());

        $workflow->delete();

        if ($request->ajax()) {
            return response()->json();
        }

        return redirect()->back();
    }

    public function destroyOfNodes($workflowId, $id, DestroyWorkflowitemRequest $request)
    {
        $node = WorkflowNode::whereWorkflowId($workflowId)
            ->whereId($id)
            ->firstOrFail();

        $node->delete();

        return response()->json();
    }

    public function checklist($workflowId, Request $request)
    {
        $workflow = app(\App\Entities\Workflow::class)->findOrFail($workflowId);

        // $checklist = app(\App\Entities\Checklist::class)->whereWorkflowId($workflow->id)->get();

        //return view('workflows.checklists')->withWorkflow($workflow)->withChecklist($checklist);

        if (!$workflow->checklist) {
            $checklist = Checklist::create([
                'workflow_id' => $workflow->id,
                'name' => $workflow->name
            ] );
        } else {
            $checklist = $workflow->checklist;
        }

        return view('workflows.checklists', compact('workflow', 'checklist'));

        //if (!$workflow->checklist) {
        //    return redirect()->route('checklists.create', ['workflow_id' => $workflow->id]);
        //}

        //return redirect()->route('checklists.show', $workflow->checklist->id);
    }

    public function works($id)
    {
        $workflow = app(\App\Entities\Workflow::class)->findOrFail($id);

        $works = app(\App\Entities\Work::class)->whereWorkflowId($workflow->id)->get();

        return view('workflows.works')->withWorkflow($workflow)->withWorks($works);
    }

    /**
     * Create Work specific to Workflow
     *
     * @param $workflowId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createWork($workflowId)
    {
        $workflow = Workflow::findOrFail($workflowId);

        return view('workflows.createWork', compact('workflow'));
    }

    public function storeWork($workflowId, Request $request)
    {
        $workflow = Workflow::findOrFail($workflowId);

        // since detailingflow_type_id is going to be removed
        // temporarily use the first detailingflow_type_id of
        // specified mainflow_type
        $detailingflowType = DetailingflowType::where('mainflow_type_id', $request->input('mainflow_type_id'))->firstOrFail();

        $work = $request->user()->works()->create(
            array_merge($request->all(), ['amount' => 1, 'unit_price' => 0, 'detailingflow_type_id' => $detailingflowType->id, 'workflow_id' => $workflow->id])
        );

        return redirect()->route('workflows.showWorkItems', [$workflowId, $work->id]);
    }

    public function showWorkItems($workflowId, $workId)
    {
        $workflow = Workflow::findOrFail($workflowId);
        $work = Work::findOrFail($workId);

        return view('workflows.work-items', compact('workflow', 'work'));
    }
}
