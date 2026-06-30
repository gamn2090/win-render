<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Vendor;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorTypes;
use Redirect;
use Carbon\Carbon;

class AdminController extends Controller
{
    //

    public function dashboard(){
        $data = [
            "vendor_types" => VendorTypes::all(),
            "vendor_types_count" => []
        ];
        foreach($data["vendor_types"] as $type){
            array_push($data["vendor_types_count"], $type->countVendorsWithType());
        }
        return view('admin.dashboard', ["data" => $data]);
    }

    public function addMonths(Request $request){
        $data = [
            "status" => false,
            "msg" => "Error"
        ];
        if($request->months == null){
            $data["msg"] = "Invalid months value!";
            return Redirect::back()->with('res', $data);
        }
        $vendor = Vendor::where('email', $request->email)->first();
        if($vendor == null){
            $data["msg"] = "No vendor with that email was found!";
            return Redirect::back()->with('res', $data);
        }
        $payment = Payment::where('vendor_id', $vendor->id)->first();
        if($payment == null){
            $payment = Payment::create([
                'vendor_id' => $vendor->id,
                'price' => "49.99",
                'purchase_date' => Carbon::now(),
                'expiry_date' => Carbon::now()->addMonths($request->months),
                'confirmed' => true
            ]);
            $data["status"] = true;
            return Redirect::back()->with('res', $data);
        }
        $payment->expiry_date = Carbon::now()->addMonths($request->months);
        $payment->confirmed = true;
        $payment->save();
        $data["status"] = true;
        return Redirect::back()->with('res', $data);

    }

    public function generateVendorCSV(){
        $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=vendors.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        $list = Vendor::all('id', 'first_name', 'last_name', 'type', 'email', 'discount', 'location', 'created_at', 'updated_at');
        foreach($list as $ven){
            $ven->type = VendorTypes::where('id', $ven->type)->first()->type;
            $venPayment = Payment::where('vendor_id', $ven->id)->first();
            if($venPayment == null){
                $ven->subscription_cost = 0;
                $ven->billing_status = 0;
            } else{
                $ven->subscription_cost = $venPayment->price;
                if(Carbon::now()->lte($venPayment->expiry_date)){
                    $ven->billing_status = 1;
                } else{
                    $ven->billing_status = 0;
                }
            }
            $ven->website = $ven->profile->business_link;
            $ven->clients_booked = $ven->numberOfClients();
        }
        $list = $list->toArray();
        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

        $callback = function() use ($list) 
        {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) { 
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function login(){
        return view('auth.login');
    }

    public function loginRequest(Request $request){
        $username = $request->username;
        $password = $request->password;
        $admin = Admin::where('username', $username)->first();
        if(!$admin){
            return back()->withErrors(['username' => ['Incorrect login credentials...']]);
        }
        if(!Hash::check($password, $admin->password)) {
            return back()->withErrors(['username' => ['Incorrect login credentials...']]);
        }
        Auth::guard('admin')->login($admin);
        return redirect('/admin/dashboard');
    }
}
