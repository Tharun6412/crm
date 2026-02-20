<?php
namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use App\Models\Master\PipeTypes;
use App\Models\Spot\ProspectPipeline;
use App\Models\Spot\Prospects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProspectPipelineController extends Controller
{
    /**
     * Index Method
     */
    public function index(Request $request)
    {
        return "This is a pipeline resource controller";
    }

    /**
     * Add the Pipeline to the prospect
     */
    public function edit(Request $request, $id)
    {
        $pipeline_list = ProspectPipeline::where('prospect_id', $id)->get();
        $pipe_types = PipeTypes::all();
        return view('spot.prospects.pipeline.edit', ['id' => $id, 'pipe_types' => $pipe_types, 'pipeline_list' => $pipeline_list]);
    }

    /**
     * Update the Pipeline
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'pipe_type_id.*' => 'required|distinct',
            'length.*' => 'required|numeric|gt:0',
        ]);
        // Data Preparation
        $pipe_line_data = [];
        foreach($request->pipe_type_id as $key => $type_id) {
            $pipe_line_data[] = array(
                'prospect_id' => $id,
                'pipe_type_id' => $type_id,
                'length' => $request->length[$key],
                'status' => 0,
                'created_by' => Auth::id(),
            );
        }
        // Data Insertion
        ProspectPipeline::upsert($pipe_line_data, ['prospect_id', 'pipe_type_id', 'status'], ['length', 'status', 'created_by']);
        // response
        return response()->json(['success' => 'Pipeline Data added successfully']);
    }
}