<?php

namespace App\Http\Controllers;

use App\Collateral;
use App\CollateralClass;
use App\Customer;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CollateralsController extends Controller
{

    public $arr_rules;

    public function __construct(){

        $this->arr_rules  = [
            'collateral_class_id' => 'required',
            'customer_id' => 'required',
            'date' => 'required|date',
            'market_value' => 'required|numeric',
            'loan_value' => 'required|numeric'
        ];

        $search_data = [ 'search_url' => 'collaterals' ];

        $customers = Customer::all()->pluck('name','id');
        $collateral_classes = CollateralClass::select(DB::raw("id, concat(class_code,'-',class_desc) name"))->get()->pluck('name','id');

        return view()->share(compact(['search_data','customers','collateral_classes']));
    }

    public function index(Request $request){
        $keyword = $request->input('keyword');

        if ( $keyword ) {
            $collaterals = Collateral::wherehas('customer' ,function($query) use ($keyword){
                $query->where('last_name','like',"$keyword%")
                    ->orWhere('first_name','like',"$keyword%")
                    ->orWhere('middle_name','like',"$keyword%");
            })->where('collateral_type','CHATTEL')
            ->paginate();

        } else {
            $collaterals = Collateral::whereCollateralType('CHATTEL')->paginate();
        }


        return view('collaterals.search_collaterals',compact([
            'collaterals'
        ]));
    }

    public function create(){
        return view('collaterals.collaterals',compact([
        ]));
    }

    public function store(Request $request){

        $this->validate($request,$this->arr_rules);

        $Collateral = Collateral::create($request->all());
        $Collateral->collateral_type = "CHATTEL";
        $Collateral->save();

        return Redirect::to("/collaterals/{$Collateral->id}/edit")->with('flash_message','Information Saved');
    }

    public function edit(Collateral $collateral){
        return view('collaterals.collaterals', compact([
            'collateral'
        ]));

    }

    public function update(Request $request, Collateral $collateral){

        $this->validate($request,$this->arr_rules);

        $collateral->update($request->all());
        return Redirect::to("/collaterals/{$collateral->id}/edit")->with('flash_message','Information Saved');
    }

    public function destroy(Collateral $collateral){
        $collateral->delete();
        return Redirect::to("/collaterals")->with('flash_message','Collateral Deleted');
    }

    public function printAppraisalReport(Collateral $collateral){
        return view('reports.print_appraisal_report', compact([
            'collateral'
        ]));
    }
    public function printAppraisalREMReport(Collateral $collateral){
        return view('reports.print_appraisal_rem_report', compact([
            'collateral'
        ]));
    }

}
