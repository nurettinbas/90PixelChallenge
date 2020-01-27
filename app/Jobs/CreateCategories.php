<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Imports\CategoriesImport;
use Excel;
use Log;

class CreateCategories implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $files = collect(scandir(public_path()."\categories"))
               ->filter(function($file){
                 $pathInfo = pathinfo($file);
                 return $pathInfo['extension'] == 'xlsx';
               })
               ->sortByDesc(function($file){
                 $file = pathinfo($file);
                 $file = explode("-",$file['filename']);
                 $date = strtotime($file[1]);
                 return $date;
               });
      $lastFile = public_path()."\categories\\".$files->first();
      Excel::import(new CategoriesImport,$lastFile);
    }
}
