<?php

namespace App\Http\Controllers\Consumer;

use App\Enums\AwsPath;
use App\Enums\ConsumerStatus;
use App\Exports\Consumers\ConsumerExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerData;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumerKyc;
use App\Models\DocumentCentre\DocumentTypes;
use App\Models\Master\ConsumerNomineeRelation;
use App\Models\Master\MasterConsumerStatus;
use App\Models\Master\Title;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsumerKycController extends Controller
{
    /**
     * Edit Customer Details
     */
    public function edit($id)
    {
        $consumer = Consumer::find($id);
        return view('consumers.consumers.edit-kyc', [
            'consumer' => $consumer, 
            'titles' => Title::all(),
            'documents' => DocumentTypes::all(),
            'nominee_relations' => ConsumerNomineeRelation::all(),
        ]);
    }

    /**
     * Update Customer Details
     */
    public function update(Request $request, $id)
    {
        // Validation Process
        $request->validate([
            'title' => 'required',
            'fname' => 'required|regex:/^[A-Za-z ]+$/',
            'lname' => 'required|regex:/^[A-Za-z ]+$/',
            'cof' => 'required',
            'cof_name' => 'required',
            'email' => 'nullable',
            'aadhar' => 'required|numeric|digits:12',
            'phone' => 'required|numeric|digits:10',
            'phone_alt' => 'nullable|numeric|digits:10',
            'nominee' => 'required',
            'nominee_relation_id' => 'required',
        ]);
        // Document Upload function
        $doc_upload = DocumentUpload::uploadIfPresent($request, AwsPath::REGISTRATION->value);
        $consumer = Consumer::find($id);
        if($consumer) {
            // Insert into Kyc Table
            ConsumerKyc::create([
                'consumer_id' => $id,
                'title' => $consumer->title,
                'fname' => $consumer->fname,
                'lname' => $consumer->lname,
                'cof' => $consumer->cof,
                'cof_name' => $consumer->cof_name,
                'aadhar' => $consumer->aadhar,
                'phone' => $consumer->phone,
                'phone_alt' => $consumer->phone_alt,
                'email' => $consumer->email,
                'nominee' => $consumer->nominee,
                'nominee_relation_id' => $consumer->nominee_relation_id,
                'created_by' => Auth::id(),
            ]);
            // Consumer Update
            $edit_details = $consumer->update([
                'title' => $request->title,
                'fname' => $request->fname,
                'lname' => $request->lname,
                'cof' => $request->cof,
                'cof_name' => $request->cof_name,
                'aadhar' => $request->aadhar,
                'phone' => $request->phone,
                'phone_alt' => $request->phone_alt,
                'email' => $request->email,
                'nominee' => $request->nominee,
                'nominee_relation_id' => $request->nominee_relation_id,
                'updated_by' => Auth::id(),
            ]);
            // Update Consumer Data KYC Status
            ConsumerData::where('consumer_id', $id)->update([
                'kyc_status' => 1,
            ]);
            //File Upload
            if(!empty($request->dc_file_list)) {
                $add_document = DocumentUpload::uploadIfPresent($request, AwsPath::COMPLAINTS->value);
                foreach($request->dc_file_list as $key => $file) {
                    ConsumerDocument::create([
                        'consumer_id' => $id,
                        'doc_type_id' => $request->document_type[$key] ?? NULL,
                        'file_id' => $add_document['file_list'][$key]['file_id'],
                    ]);
                }
            }
            return response()->json(['success' => 'Consumer details updated successfully']);
        }
    }

    /**
     * Show Consumer Kyc Details
     */
    public function show(Request $request, $id)
    {
        $kyc_details = ConsumerKyc::where('consumer_id', $id)->get();
        return view('consumers.consumers.show-kyc-history', ['kyc_details' => $kyc_details]);
    }
}