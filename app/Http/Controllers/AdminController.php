<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Payment;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorTypes;
use Redirect;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard(){
        $couples = User::select('id', 'first_name', 'last_name', 'fiance_first_name', 'email', 'created_at')
            ->orderByDesc('created_at')
            ->paginate(25, ['*'], 'couples_page');

        $vendors = Vendor::select('id', 'first_name', 'last_name', 'business_name', 'email', 'type', 'created_at')
            ->orderByDesc('created_at')
            ->paginate(25, ['*'], 'vendors_page');

        return view('admin.dashboard', [
            'couples' => $couples,
            'vendors' => $vendors,
            'admin' => Auth::guard('admin')->user(),
            'page' => 'dashboard',
        ]);
    }

    /**
     * Bulk-deletes couples via the existing `couple:delete` command, which
     * already handles cascading their related data.
     */
    public function deleteCouples(Request $request)
    {
        $ids = collect($request->input('ids', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        if (empty($ids)) {
            return response()->json(['status' => false, 'message' => 'No couples selected.'], 422);
        }

        Artisan::call('couple:delete', ['ids' => $ids]);

        return response()->json([
            'status' => true,
            'message' => count($ids) . ' couple(s) deleted.',
        ]);
    }

    /**
     * Bulk-deletes vendors via the existing `vendor:delete` command — but
     * first filters out any vendor with an active subscription/membership,
     * since those must never be deletable from here.
     */
    public function deleteVendors(Request $request)
    {
        $ids = collect($request->input('ids', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        if (empty($ids)) {
            return response()->json(['status' => false, 'message' => 'No vendors selected.'], 422);
        }

        $vendors = Vendor::whereIn('id', $ids)->get();
        $blocked = $vendors->filter(fn ($v) => $v->isActiveMember());
        $deletable = $vendors->reject(fn ($v) => $v->isActiveMember());

        if ($deletable->isNotEmpty()) {
            Artisan::call('vendor:delete', ['ids' => $deletable->pluck('id')->all()]);
        }

        return response()->json([
            'status' => true,
            'deleted_count' => $deletable->count(),
            'blocked' => $blocked->map(function ($v) {
                return $v->business_name ?: trim($v->first_name . ' ' . $v->last_name);
            })->values(),
        ]);
    }

    /**
     * Admins are never created through a public route — only from inside
     * the admin dashboard by an already-authenticated admin.
     */
    public function createAdmin(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email', 'unique:vendors,email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Admin::create([
            'username' => $validated['username'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['status' => true, 'message' => 'Admin account created.']);
    }

    public function updatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $admin->password)) {
            return response()->json(['status' => false, 'message' => 'Current password is incorrect.'], 422);
        }

        $admin->password = Hash::make($validated['password']);
        $admin->save();

        return response()->json(['status' => true, 'message' => 'Password updated.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
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
