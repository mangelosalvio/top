<?php

namespace App\Http\Controllers;

use App\Collection;
use App\Customer;
use App\GeneralLedger;
use App\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;

class CustomersController extends Controller
{


    public function __construct(){

        $search_data = [ 'search_url' => 'customers' ];

        $this->arr_rules  = [
            'last_name' => 'required',
            'first_name' => 'required',
            'no_of_dependents' => 'required',
            'years_of_stay' => 'required',
            'res_cert_date' => 'required',
            'age' => 'required'
        ];

        return view()->share(compact(['search_data']));
    }

    public function index(Request $request){
        $keyword = $request->input('keyword');

        if ( $keyword ) {
            $customers = Customer::where('first_name','like',"%$keyword%")
                ->orWhere('last_name','like',"%$keyword%")
                ->orWhere('id','like',"%$keyword%")
                ->paginate();
        } else {
            $customers = Customer::paginate();
        }


        return view('customers.search_customers',compact([
            'customers'
        ]));
    }

    public function create(){

        return view('customers.customers',compact([
        ]));
    }

    public function store(Request $request){
        $this->validate($request,$this->arr_rules);

        $Customer = Customer::create($request->all());

        return Redirect::to("/customers/{$Customer->id}/edit")->with('flash_message','Information Saved');;
    }

    public function edit(Customer $customer){
        return view('customers.customers', compact([
            'customer'
        ]));

    }

    public function update(Request $request, Customer $customer){
        $this->validate($request,$this->arr_rules);
        $customer->update($request->all());
        return Redirect::to("/customers/{$customer->id}/edit")->with('flash_message','Information Saved')->with('flash_message','Information Saved');;
    }

    public function collaterals (Customer $customer = null) {
        if ( $customer ) {
            return $customer->collaterals->map(function($Collateral){
                return [
                    'text' => $Collateral->label,
                    'id' => $Collateral->id
                ];
            })->toArray();
        } else {
            return [];
        }

        //return $customer->collaterals;
    }

    public function destroy(Customer $customer){

        $loans = Loan::whereCustomerId($customer->id)->get();

        $loans->each(function($Loan){
            $collections = Collection::whereLoanId($Loan->id)->get();

            /**
             * delete ledgers of collections
             */
            $collections->each(function($Collection){
                GeneralLedger::whereColumnHeader('cash_receipts')
                    ->whereColumnHeaderId($Collection->id)->delete();
                $Collection->delete();
            });

            GeneralLedger::whereColumnHeader('loans')
                ->whereColumnHeaderId($Loan->id)->delete();

            $Loan->delete();
        });
        $customer->delete();

        return redirect()->route('customers.index')->with('flash_message','Customer Deleted');
    }
}
