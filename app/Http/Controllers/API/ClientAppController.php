<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CustPackage;
use App\Models\Invoice;
use App\Models\Porfoma;
use App\Models\User;
use Illuminate\Http\Request;
use ZteF\Exception\LoginException;
use ZteF\ZteF;
use Validator;


class ClientAppController extends Controller
{
    public function getProfile(Request $request)
    {
        $usename =  $request->user()->username;
        $load['title'] = 'Profile ' . $usename;

        $load['prfile'] = User::selectRaw('username,cust_name,cust_birth_date,cust_phone,cust_bill_phone,cust_email,cust_bill_email')
            ->leftJoin('t_customer', 'users.username', '=', 't_customer.cust_number')
            ->where('username', $usename)->first();

        return response()
            ->json($load);
    }
    public function updateProfile(Request $request)
    {
        $usename =  $request->user()->username;
        $load['title'] = 'Update ' . $usename;

        $validateUser = Validator::make(
            $request->all(),
            [
                'cust_name' => 'required|alpha_num',
                'cust_email' => 'required|email',
                'cust_phone' => 'required|integer',
                'cust_birth_date' => 'required|date',

            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $load['request'] = $request->all();

        return response()
            ->json($load);
    }
    public function updatePassword(Request $request)
    {
        $usename =  $request->user()->username;
        $load['title'] = 'Update ' . $usename;


        $validateUser = Validator::make(
            $request->all(),
            [
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password',

            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $update = User::where('username', $usename)->update(['password' => bcrypt($request->password)]);
        $load['update'] = $update;
        $load['message'] = $update ? 'Update Berhasil' : 'Update Gagal';

        return response()
            ->json($load);
    }
    public function getIndex(Request $request)
    {
        $load['title'] = 'Index';
        
        $usename =  $request->user()->username;
        $load['cust_number'] =  $usename;
        //$usename =  'JP002086';
        $wherePorfoma['cust_number'] = $usename;
        $wherePorfoma['inv_status'] = 0;
        $wherePorfoma['inv_recycle'] = 2;

        $porfoma = Porfoma::selectRaw('t_invoice_porfoma.inv_number,inv_status,inv_start,sum(ii_amount) as amount')
            ->leftJoin('t_inv_item_porfoma', function ($join) {
                $join->on('t_invoice_porfoma.inv_number', '=', 't_inv_item_porfoma.inv_number')->where('ii_recycle', '<>', 1);
            })
            ->groupBy('inv_number')
            ->where($wherePorfoma)->orderByDesc('inv_start')->first();

        $load['porfoma'] = $porfoma;

        $custPackage = CustPackage::selectRaw('cupkg_status,cupkg_ip,trel_cust_pkg.sp_code,inv_status,inv_end')->where('trel_cust_pkg.cust_number', $usename)
            ->leftJoin('t_invoice_porfoma', function ($join) {
                $join->on('trel_cust_pkg.cupkg_bill_lastinv', '=', 't_invoice_porfoma.inv_number')->where('inv_recycle', '<>', 1);
            })
            ->first();

        $load['service_pkg']['cupkg_status'] = $custPackage->cupkg_status;
        $load['service_pkg']['sp_code'] = $custPackage->sp_code;
        $load['service_pkg']['svc_due'] = $custPackage->inv_status == 1 ? $custPackage->inv_end : null;
        $load['service_pkg']['message'] = 'Status anda saat ini adalah ';

        $load['modem']['ip'] = $custPackage->cupkg_ip;

        /*try {
            //$zte = new ZteF($custPackage->cupkg_ip, 'admin', 'Telkomdso123', true);
            $zte = new ZteF('172.16.187.250', 'admin', 'Telkomdso123', true);

            var_dump($zte->administration()->loginTimeout());
            $load['modem']['status'] = true;
            $load['modem']['mssssage'] = 'Koneksi model berhasil';
            $load['modem']['device_info'] = $zte->status()->deviceInformation();
            //$load['modem']['metwork_info'] = $zte->status()->networkInterface();
            $load['modem']['conected_device'] = $zte->network()->wlan()->associatedDevices();
        } catch (LoginException $e) {
            $load['modem']['status'] = false;
            $load['modem']['mssssage'] = $e->getMessage();
        } catch (\Exception $e) {
            $load['modem']['status'] = false;
            $load['modem']['mssssage'] = $e->getMessage();
        }*/

        return response()
            ->json($load);
    }

    public function getBillIndex(Request $request)
    {
        $load['title'] = 'Tagihan Index';

        $usename =  $request->user()->username;
        $load['cust_number'] =  $usename;
        //$usename =  'JP002086';
        $wherePorfoma['cust_number'] = $usename;
        $wherePorfoma['inv_status'] = 0;
        $wherePorfoma['inv_recycle'] = 2;

        $porfoma = Porfoma::selectRaw('t_invoice_porfoma.inv_number,inv_status,inv_start,sum(ii_amount) as amount')
            ->leftJoin('t_inv_item_porfoma', function ($join) {
                $join->on('t_invoice_porfoma.inv_number', '=', 't_inv_item_porfoma.inv_number')->where('ii_recycle', '<>', 1);
            })
            ->groupBy('inv_number')
            ->where($wherePorfoma)->orderByDesc('inv_start')->first();

        $load['porfoma'] = $porfoma;

        $custPackage = CustPackage::selectRaw('cupkg_status,cupkg_ip,trel_cust_pkg.sp_code,cupkg_svc_begin')->where('trel_cust_pkg.cust_number', $usename)
            ->leftJoin('t_invoice_porfoma', function ($join) {
                $join->on('trel_cust_pkg.cupkg_bill_lastinv', '=', 't_invoice_porfoma.inv_number')->where('inv_recycle', '<>', 1);
            })
            ->first();

        $load['service_pkg']['sp_code'] = $custPackage->sp_code;
        $load['service_pkg']['cupkg_status'] = $custPackage->cupkg_status;
        $load['service_pkg']['cupkg_svc_begin'] = $custPackage->cupkg_svc_begin;
        $load['service_pkg']['message'] = 'status anda saat ini adalah';

        $whereInvoice['cust_number'] = $usename;
        $whereInvoice['inv_recycle'] = 2;

        $invoices = Invoice::selectRaw('t_invoice.inv_number,inv_status,inv_start,sum(ii_amount) as amount')
            ->leftJoin('t_inv_item', function ($join) {
                $join->on('t_invoice.inv_number', '=', 't_inv_item.inv_number')->where('ii_recycle', '<>', 1);
            })
            ->groupBy('inv_number')
            ->where($whereInvoice)->orderByDesc('inv_start')->get();

        $load['invoices'] = $invoices;

        return response()
            ->json($load);
    }

    public function getBillDetail($invNumber, Request $request)
    {
        $load['title'] = 'Tagihan Detail ' . $invNumber;

        $whereInvoice['t_invoice.inv_number'] = $invNumber;
        $whereInvoice['t_invoice.inv_recycle'] = 2;

        $invoice = Invoice::selectRaw('t_invoice.inv_number,t_invoice.inv_status,t_invoice_porfoma.inv_paid,t_invoice.inv_start,t_invoice.inv_end,sum(ii_amount) as amount')
            ->leftJoin('t_inv_item', function ($join) {
                $join->on('t_invoice.inv_number', '=', 't_inv_item.inv_number')->where('ii_recycle', '<>', 1);
            })
            ->leftJoin('t_invoice_porfoma', function ($join) {
                $join->on('t_invoice.pi_number', '=', 't_invoice_porfoma.inv_number')->where('t_invoice_porfoma.inv_recycle', '<>', 1);
            })
            ->groupBy('inv_number')
            ->where($whereInvoice)->orderByDesc('inv_start')->first();

        $load['invoice'] = $invoice;
        return response()
            ->json($load);
    }

    public function getSvcDetail(Request $request)
    {
        $load['title'] = 'Detail Layanan ';

        $usename =  $request->user()->username;
        $load['cust_number'] =  $usename;


        $custPackage = CustPackage::selectRaw('cupkg_status,cupkg_ip,trel_cust_pkg.sp_code,cupkg_svc_begin')->where('trel_cust_pkg.cust_number', $usename)
            ->leftJoin('t_invoice_porfoma', function ($join) {
                $join->on('trel_cust_pkg.cupkg_bill_lastinv', '=', 't_invoice_porfoma.inv_number')->where('inv_recycle', '<>', 1);
            })
            ->first();

        $load['service_pkg']['sp_code'] = $custPackage->sp_code;
        $load['service_pkg']['cupkg_status'] = $custPackage->cupkg_status;
        $load['service_pkg']['cupkg_svc_begin'] = $custPackage->cupkg_svc_begin;
        $load['service_pkg']['svc_due'] = $custPackage->inv_status == 1 ? $custPackage->inv_end : null;
        $load['service_pkg']['message'] = 'status anda saat ini adalah';

        return response()
            ->json($load);
    }
}
