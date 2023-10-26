<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Services\GoldTransformService;
use App\Http\Services\ItemDailyJournalService;
use App\Models\Department;
use App\Models\ItemDailyJournal;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AjaxReportController extends Controller
{
    public $goldTransformService;
    public $itemDailyJournalService;

    public function __construct(GoldTransformService $goldTransformService, ItemDailyJournalService $itemDailyJournalService)
    {
        $this->goldTransformService = $goldTransformService;
        $this->itemDailyJournalService = $itemDailyJournalService;
    }

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

       
        return response()->json([
            view('modals.department-daily-report-show', [
                'day' => $data['day'],
            ])->render()
        ]);
    }


    public function dailyReportsInTotal(Request  $request)
    {
        $data = $request->validate([
            'day' => 'required|date_format:Y-m-d',
        ]);

       
        $departmentsOpeningBalances = $this->itemDailyJournalService->getDepartmentsBalanceCalibIn21(
            fromDate:null,
            toDate: Carbon::parse($request->day)->subDay()->format('Y-m-d'),
        );

        $departmentsTotalDayBalance = $this->itemDailyJournalService->getDepartmentsBalanceCalibIn21(
            fromDate:$request->day,
            toDate: $request->day,
        );

        return response()->json([
            view('modals.department-daily-report-in-total-show', [
                'departmentsOpeningBalances' => $departmentsOpeningBalances,
                'departmentsTotalDayBalance' => $departmentsTotalDayBalance,
                'day' => $data['day'],
            ])->render()
        ]);
    }

    public function purityDifference(Request $request)
    {
        $data = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'from' => 'nullable|date_format:Y-m-d',
            'to' => 'nullable|date_format:Y-m-d',
        ]);

        $department = Department::find($data['department_id']);

        $purityDifferences = $this->itemDailyJournalService->getPurityDifference(
            $request->from,
            $request->to,
            $request->department_id,
        );

        return response()->json([
            view('modals.purity-difference-report-show', [
                'department' => $department,
                'purityDifferences' => $purityDifferences,
                'to' => $request->to,
                'from' => $request->from,
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

        $goldLosses  = $this->goldTransformService->getGoldLosses(
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
