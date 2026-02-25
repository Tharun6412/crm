<?php
namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use App\Models\Master\PipeTypes;
use App\Models\Spot\ProspectPipeline;
use App\Models\Spot\Prospects;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
     * Display the Form in screen
     * Fetch the Pipeline details and display if available
     * Loads the PipeTypes.
     * Dynamic Add functionality implemented in screen.(Multiple Pipelines can be added)
     * @return view
     */
    public function edit(Request $request, $id)
    {
        $pipeline_list = ProspectPipeline::where('prospect_id', $id)->get();
        $pipe_types = PipeTypes::all();
        return view('spot.prospects.pipeline.edit', ['id' => $id, 'pipe_types' => $pipe_types, 'pipeline_list' => $pipeline_list]);
    }

    /**
     * Add/Update the Pipeline Records
     * 
     * This Method:
     * - Validates the required fields
     * - Data Preparation into array (Multiple records can be added)
     * - upsert - (Adds if no record, updates if record exists)
     * 
     * @return response string
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

    

    /**
     * Updates the PipeLine Status
     * 
     * This Method:
     * - Fetch the Pipeline record by ID
     * - Marks the pipeline status (1=complete)
     * 
     * @return response string
     */
    public function updatePipeLine(Request $request)
    {
        $update_pipeline = ProspectPipeline::find($request->id);
        if($update_pipeline) {
            $update_pipeline->update([
                'status' => 1,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);
        }
        Session::flash('success', 'Pipeline updated successfully');
        return response()->json(['success' => 'Pipeline Updated Successfully']);
    }
}