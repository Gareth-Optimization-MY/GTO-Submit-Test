<?php

namespace App\Console;

use App\Http\Controllers\OrderReportController;
use App\Http\Controllers\BillingController;
use App\Models\Reports;
use App\Models\Store;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        date_default_timezone_set("Asia/Kuala_Lumpur");
        $current_time = date('H:i');
        if ($current_time >= "00:00" && $current_time < "23:00") {

            $reports = Reports::where('report_type','schedule')->whereDate('last_run','<=',\Carbon\Carbon::now())->get();

            foreach ($reports as $report) {
                $shop = $report->shop;
                $store = Store::where('shop_url', $shop )->first();
                if($store->plan_id == 1 || $report->status == 0){
                    continue;
                }
                $checkBilling = BillingController::check_billing_charge_id($store->shop_url,$store->current_charge_id);
                if(!$checkBilling){
                    $report->status = 0;
                    $report->update();
                    continue;
                }


                $date = \Carbon\Carbon::now(); // Current datetime


                if($report['last_run'] <= $date){
                    $schedule->call(function () use ($report) {

                        $report['report_id'] = $report->id;
                        $reportExists = Reports::where('report_type', 'schedule_cron')->where('report_id', $report->id)->whereDate('last_run', '<=', \Carbon\Carbon::now())->get();

                        $date = \Carbon\Carbon::now(); // Current datetime
                        $now = \Carbon\Carbon::now(); // Current datetime
                        $customdate = $now->subDay();

                        $shouldExecute = false;


                        if($report['last_run'] == NULL){
                            $shouldExecute = true;
                        }else{
                            if($report['last_run'] <= $date){
                                $shouldExecute = true;
                            }
                        }


                        if($shouldExecute){


                            if($report['schedule_cron'] == 'every_hour'){
                                $nextCron = $date->addHour(); // Add one hour to the datetime
                            }elseif($report['schedule_cron'] == 'every_five_minute'){
                                $nextCron = $date->addMinutes(5); // Add one day to the datetime
                            }elseif($report['schedule_cron'] == 'every_day'){
                                $nextCron = $date->addDay(); // Add one day to the datetime
                            }elseif($report['schedule_cron'] == 'every_year'){
                                $nextCron = $date->addYear(); // Add one week to the datetime
                            }elseif($report['schedule_cron'] == 'every_month'){
                                $nextCron = $date->addMonth(); // Add one month to the datetime
                            }else{
                                $nextCron = $date->addMonth(); // Add one month to the datetime
                            }

                            $now = new \DateTime(); // current DateTime
                            // Create date only DateTime objects for comparison
                            $nextCronDate = new \DateTime($nextCron->format('Y-m-d'));
                            $reportDate = new \DateTime($customdate->format('Y-m-d'));

                            $interval = $nextCronDate->diff($reportDate);

                            if ($interval->days >= 1) {
                                // New day has started, so you can increase the report_date by one day
                                $reportFromDate = \DateTime::createFromFormat('Y-m-d', $customdate->format('Y-m-d'));
                                $reportToDate = \DateTime::createFromFormat('Y-m-d', $customdate->format('Y-m-d'));

                                $report->report_date = $reportFromDate->format('Y-m-d');
                                $report->report_to_date = $reportToDate->format('Y-m-d');
                            }
                            $orderReport = app(OrderReportController::class);
                            if($report['template_use'] == 2)
                                $newFile = $orderReport->GenerateHourlyReport($report);
                            elseif ($report['template_use'] == 4 || $report['template_use'] == 5) {
                                $newFile = $orderReport->GenerateHourly18Report($report);
                            }
                            elseif ($report['template_use'] == 3 || $report['template_use'] == 1) {
                                $newFile = $orderReport->GenerateDailyReport($report);
                            }


                            // check if the report date is yesterday or before
                            if ($interval->days >= 1) {
                                // New day has started, so you can increase the report_date by one day
                                // $files = array_merge(json_decode($report->filename), $newFile);
                                // Reports::where('id',$report['id'])->update(['filename' => json_encode($files)]);
                            }
                            Reports::where('id',$report['id'])->update(['last_run' => $nextCron]);

                        }


                    })->cron('* * * * *');
                }

            }
        }

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
