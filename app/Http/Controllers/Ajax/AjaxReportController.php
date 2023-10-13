<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Services\GoldTransformService;
use App\Models\Department;
use App\Models\DepartmentDailyReport;
use App\Models\GoldLoss;
use App\Models\ItemDailyJournal;
use App\Models\Report;
use App\Models\Transfer;
use App\Models\TransferReport;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AjaxReportController extends Controller
{
    public function departmentStatement(Request $request)
    {
        $data = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'from' => 'nullable|date_format:Y-m-d',
            'to' => 'nullable|date_format:Y-m-d',
        ]);

        $departmentStatements = ItemDailyJournal::query()
            ->with(['item', 'doc'])
            ->enteryDateBetween($request->from, $request->to)
            ->department($request->department_id)
            ->orderBy('date')
            ->get();

        $department = Department::find($request->department_id);

        return response()->json([
            view('modals.department-report-show', [
                'department' => $department,
                'departmentStatements' => $departmentStatements,
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


    public function goldLosses(Request $request)
    {
        $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'worker_id' => 'nullable|exists:workers,id',
            'from' => 'nullable|date_format:Y-m-d',
            'to' => 'nullable|date_format:Y-m-d',
        ]);

        $goldLosses  = (new GoldTransformService())->getGoldLosses(
            $request->department_id,
            $request->worker_id,
            $request->from,
            $request->to,
        );

        $worker = Worker::find($request->worker_id);
        $department = Department::find($request->department_id);

        return response()->json([
            view('modals.gold-loss-report-show', [
                'goldLosses' => $goldLosses,
                'worker' => $worker,
                'department' => $department,
            ])->render()
        ]);
    }
}
