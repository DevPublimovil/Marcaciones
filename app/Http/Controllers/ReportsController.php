<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use Auth;
use App\Employee;
use App\User;
use App\Marking;
use App\CompanyResource;
use App\Company;
use App\Action;
use App\ActionType;
use App\PersonalAction;
use App\Helper\Assistence;
use \Carbon\Carbon as Fecha;
use Illuminate\Support\Collection;
use App\Notifications\ReportLastDay;
use App\Helper\DynamicRecipient;
use App\Webster_checkinout;

class ReportsController extends Controller
{

    public function index(Request $request)
    {
        if(!Auth::user()->hasPermission('browse_reports')){
            return back()->with([
                'message'   => 'No tienes permiso al recurso',
                'type'      => 'warning'
            ]);
        }

        return view('reports.details');   
    }

    public function createAll(Request $request)
    {
        if(Auth::user()->hasPermission('create_reports')){
            ini_set("max_execution_time", 3600);
            $name = Fecha::now()->toDateString();
            $user = User::find(Auth::id());
            if($request->employees){
                $employees = explode(',',$request->employees);
            }else{
                if($user->role->name == 'gerente' || $user->role->name == 'subjefe'){
                    $employees = $user->workersGte()->active()->get();
                }else{
                    $employees = $user->appCompany->company->employees()->active()->get();
                }
            }
            
            foreach ($employees as $key => $employee) {
                $markings = Assistence::showAssists($request, $employee->id ?? $employee);

                $data[] = [
                    'employee' => Employee::find($employee->id ?? $employee),
                    'markings' => $markings,
                ];
            }

            $pdf = PDF::loadView('reports.report-all-employees', [
                'data' => $data,
                'start' => $request->start_date,
                'end' => $request->end_date
            ])->setPaper('letter','landscape');

            return $pdf->stream($name . '.pdf');
        }else{
            abort(403);
        }
    }

    public function sendNotification(Request $request)
    {
        $user = User::find(Auth::id());
        $resources = $user->appcompany->company;

        $resources->listemail;
        $recipient = new DynamicRecipient($resources->listemail);
        $recipient->notify(new ReportLastDay($request->limitday));
        return response()->json([
            'message' => 'La notificación ha sido enviada',
            'type'  => 'success'
        ]);
    }
}
