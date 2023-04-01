<?php

namespace App\Console\Commands;

use App\Models\DepartmentDailyReport;
use App\Models\DepartmentItem;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

use function PHPUnit\Framework\throwException;

class DepartmentDailyReportSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'departments:dailyreport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command excutes daily to generate department existing kinds and their details as a departments daily reports';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $departmentsItems = DepartmentItem::with(['department'])
            ->where('current_weight', '>', 0)
            ->get();


        foreach ($departmentsItems as $item) {
            DepartmentDailyReport::create([
                'previous_balance' => $item->current_weight,
                'current_balance' => $item->current_weight,
                'kind' => $item->kind,
                'date' => Carbon::today()->format('Y-m-d'),
                'kind_name' => $item->kind_name,
                'karat' => $item->karat,
                'shares' => $item->shares,
                'department_id' => $item->department_id,
                'department_name' => $item->department->name,
            ]);
        }
    }
}
