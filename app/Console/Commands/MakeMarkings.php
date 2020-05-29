<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Webster_checkinout;
use App\Marking;
use Carbon\Carbon as Fecha;
use Illuminate\Support\Facades\Log;

class MakeMarkings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:markings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para extraer las marcaciones';

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
     * @return mixed
     */
    public function handle()
    {

        /* $date_start = Fecha::now()->subHours(24)->format('Y-m-d H:i:s');
        $date_end   = Fecha::now()->format('Y-m-d H:i:s'); */
        
        $date_start = '2020-05-19 06:00:00';
        $date_end   = '2020-05-20 06:00:00';
        
        $markings = Webster_checkinout::whereBetween('checktime',[$date_start, $date_end])->orderBy('checktime','ASC')->get();
        //Log::info($markings->count());
        
        foreach ($markings as $key => $marking) {
            $marcacion = Marking::where('cod_marking',$marking->userid)->where('serialno',$marking->serialno)->whereNotNull('check_in')->whereNull('check_out')->whereDate('created_at',Fecha::parse($date_end)->format('Y-m-d'))->exists();
            if($marcacion)
            {
                $marc = Marking::where('cod_marking',$marking->userid)->where('serialno',$marking->serialno)->whereNotNull('check_in')->whereNull('check_out')->whereDate('created_at',Fecha::parse($date_end)->format('Y-m-d'))->first();
                $marc->update([
                    'check_out' => $marking->checktime,
                ]);
            }
            else
            {
                Marking::create([
                    'cod_marking'       => $marking->userid,
                    'check_in'          => $marking->checktime,
                    'serialno'          => $marking->serialno,
                    'created_at'        => Fecha::parse($date_end)->format('Y-m-d'),
                ]);
            }
        }
    }
}
