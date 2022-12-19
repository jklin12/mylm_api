<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Customer::select(
            DB::raw('t_customer.cust_number'),
            'cust_name',
            'cust_address',
            'cust_phone',
            'cust_email',
            'sp_code',
            /*'cust_bill_address',
            'cust_bill_phone',
            'cust_bill_email'*/
        )->join('trel_cust_pkg', 't_customer.cust_number', '=', 'trel_cust_pkg.cust_number')
            ->paginate(10);


        $load['message'] = "Customer Data found";
        $load['data'] = $customer;

        return response()
            ->json($load);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $message = "Customer Data found";
        $status = 200;

        $customer = Customer::select(
            DB::raw('t_customer.cust_number'),
            'cust_name',
            'cust_address',
            'cust_phone',
            'cust_email',
            'cust_bill_address',
            'cust_bill_phone',
            'cust_bill_email',
            DB::raw('trel_cust_pkg.sp_code'),
            'sp_type',
            'cupkg_svc_begin',
            'cupkg_acc_type',
            'cupkg_status',

            'cupkg_acct_manager'
        )
            ->leftJoin('trel_cust_pkg', 't_customer.cust_number', '=', 'trel_cust_pkg.cust_number')
            ->LeftJoin('t_service_pkg', 'trel_cust_pkg.sp_code', '=', 't_service_pkg.sp_code')
            ->find($id);
 
        if (!$customer) {
            $message = "Customer Data  not found";
            $status = 200;
        }
        $load['message'] = $message;
        $load['data'] = $customer;

        return response()
            ->json($load, $status);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
