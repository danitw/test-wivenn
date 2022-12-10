<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\Report;

use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    /**
     * Create Report
     * @param Request $request
     * @return Report
     */
    public function create(Request $request): JsonResponse
    {
        try {
            //Validated
            $validateReport = Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'summary' => 'required',
                    'creatorID' => 'required',
                ]
            );

            if ($validateReport->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateReport->errors()
                ], 401);
            }

            $report = Report::create([
                'title' => $request->title,
                'summary' => $request->summary,
                'creatorID' => $request->creatorID,
                'fileInServer' => 'archive.pdf' // TODO: make this later
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Report Created Successfully',
                'report' => $report
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Read Report
     * @param Request $request
     * @return Report
     */
    public function read(Request $request, $id): JsonResponse
    {
        try {
            $report = Report::find($id);

            return response()->json([
                'status' => true,
                'message' => 'Report Found Successfully',
                'report' => $report
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update Report
     * @param Request $request
     * @return Report
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $body = $request->all();

            $report = Report::where('id', $id)->update($body);

            if($report == 0) {
              return response()->json([
                  'status' => false,
                  'message' => 'Report does not exist'
              ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Report Updated Successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Delete Report
     * @param Request $request
     * @return Report
     */
    public function delete(Request $request, $id): JsonResponse
    {
        try {
            Report::where('id', $id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Report Deleted Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


}
