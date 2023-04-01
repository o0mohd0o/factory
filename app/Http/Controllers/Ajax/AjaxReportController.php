<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentDailyReport;
use App\Models\Report;
use App\Models\Transfer;
use App\Models\TransferReport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AjaxReportController extends Controller
{
    public function transferReports(Request $request)
    {
        $data = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'from' => 'required|date_format:Y-m-d',
            'to' => 'required|date_format:Y-m-d',
        ]);

        try {
            //code...
            $department = Department::with(['openingBalancesReports' => function ($query) use ($data) {
                return $query->datePeriod($data['from'], $data['to'])
                    ->orderBy('date', 'desc');
            }])->findOrFail($data['department_id']);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('Department Not Found'),
            ], 404);
        }

        $transferReports = TransferReport::where('transfer_to', $data['department_id'])
            ->orWhere('transfer_from', $data['department_id'])
            ->datePeriod($data['from'], $data['to'])
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('date');

        //Load department opening balances reports if it is the main department
        if ($department->main_department) {
            $openingBalancesReports = $department->openingBalancesReports->groupBy('date');
        }

        return response()->json([
            view('modals.department-report-show', [
                'department' => $department,
                'transferReports' => $transferReports,
                'openingBalancesReports' => $openingBalancesReports ?? null,
                'to' => $data['to'],
                'from' => $data['from'],
            ])->render()
        ]);
    }


    public function dailyReports(Request  $request)
    {
        $data = $request->validate([
            'day' => 'required|date_format:Y-m-d',
        ]);

        //get the query date from the database if the request date is not found in the reports
        $lastReportBeforeQueryDate = DepartmentDailyReport::where('date', '<=', $data['day'])->latest()->first();
        $reportDate = $lastReportBeforeQueryDate ? $lastReportBeforeQueryDate->date : $data['day'];

        $departments = Department::with(['dailyReports' => function ($query) use ($reportDate) {
            return $query->day($reportDate);
        }])->get();

        return response()->json([
            view('modals.department-daily-report-show', [
                'departments' => $departments,
                'day' => $data['day'],
            ])->render()
        ]);
    }


    public function dailyReportsInTotal(Request  $request)
    {
        $data = $request->validate([
            'day' => 'required|date_format:Y-m-d',
        ]);

        //get the query date from the database if the request date is not found in the reports
        $lastReportBeforeQueryDate = DepartmentDailyReport::where('date', '<=', $data['day'])->latest()->first();
        $reportDate = $lastReportBeforeQueryDate ? $lastReportBeforeQueryDate->date : $data['day'];

        $isSameDay = $reportDate == $data['day'] ? true : false;

        $departments = Department::with(['dailyReports' => function ($query) use ($reportDate) {
            return $query->day($reportDate);
        }])->get();

        return response()->json([
            view('modals.department-daily-report-in-total-show', [
                'departments' => $departments,
                'isSameDay' => $isSameDay,
                'day' => $data['day'],
            ])->render()
        ]);
    }

    public function karatDifferenceReports(Request $request)
    {
        $data = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'from' => 'required|date_format:Y-m-d',
            'to' => 'required|date_format:Y-m-d',
        ]);

        $department = Department::findOrFail($data['department_id']);

        $transferReports = TransferReport::Where('transfer_from', $data['department_id'])
            ->whereRaw('cast(shares as signed) > cast(shares_to_transfer as signed)')
            ->datePeriod($data['from'], $data['to'])
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('date');


        return response()->json([
            view('modals.department-karat-difference-report-show', [
                'department' => $department,
                'transferReports' => $transferReports,
                'to' => $data['to'],
                'from' => $data['from'],
            ])->render()
        ]);
    }
}
