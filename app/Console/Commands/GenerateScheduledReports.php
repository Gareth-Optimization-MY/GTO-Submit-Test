<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reports;

class GenerateScheduledReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:generate {reportId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate scheduled reports';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $args = $this->arguments();

        $report_id = false;
        if( isset( $args['reportId'])){
            $report_id =  $args['reportId'];
        }

        if( $report_id ){
            $this->generate_report( $report_id);
        }else{
            $this->generate_all_scheduled();
        }
       
        return Command::SUCCESS;
    }

    /**
     * 
     */
    function generate_report( $report_id){
        $report = Reports::find( $report_id);
        /**
         * Write code to generate report
         */
    }

    function generate_all_scheduled(){
        
        // Get all the scheduled reports that are not yet generated
        $reports = Reports::where( [ 'report_type'=>'schedule', 'is_queued'=> 1])->get();


        foreach( $reports  as  $report){
            //print_r($report);
            // Once the report is generated, make is_queued = 0
        }

        
        
    }
}
