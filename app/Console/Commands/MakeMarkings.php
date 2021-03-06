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
    protected $signature = 'make:markings {date?}';

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

        $myday = $this->argument('date');

        $date_start = ($myday) ? Fecha::parse($myday)->subDay()->format('Y-m-d') . ' 06:00:00' : Fecha::now()->subDay()->format('Y-m-d') . ' 06:00:00';
        $date_end   = ($myday) ? $myday . ' 06:00:00' : Fecha::now()->format('Y-m-d') . ' 06:00:00';
        $listMarkings = Marking::whereDate('created_at',Fecha::parse($date_start)->format('Y-m-d'))->delete();
        $markings = Webster_checkinout::whereBetween('checktime',[$date_start, $date_end])->orderBy('checktime','ASC')->get();
        
        foreach ($markings as $key => $marking) {
            $marcacion = Marking::firstOrNew([
                'cod_marking'   => $marking->userid,
                'serialno'      => $marking->serialno,
                'created_at'    => Fecha::parse($date_start)->format('Y-m-d')
            ]);

            if($marcacion->exists){
                $marcacion->fill([
                    'check_out' => $marking->checktime
                ]);
            }else{
                $marcacion->fill([
                    'check_in' => $marking->checktime
                ]);
            }
            $marcacion->save();
        }
    }
}
