<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class AuthOtpController extends Controller
{
    public function loginWithOtp(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'cust_number' => 'required|exists:t_customer,cust_number',
                'otp' => 'required'
            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }
        $now = Carbon::now();
        $verificationCode = VerificationCode::where('user_id', $request->cust_number)->where('otp', $request->otp)->first();

        if (!$verificationCode) {
            return response()->json([
                'status' => false,
                'message' => 'Error',
                'errors' => 'Kode OTP yang anda masukan tidak valid'
            ], 401);
        } elseif ($verificationCode && $now->isAfter($verificationCode->expire_at)) {
            return response()->json([
                'status' => false,
                'message' => 'Error',
                'errors' => 'Kode OTP anda sudah expired'
            ], 401);
        }


        $user = User::where('username', $request->cust_number)->first();

        // dd($user);

        if (!$user) {
            $postVal = [
                'username' => $request->cust_number,
                'name' => $request->cust_name,
                'email' => $request->cust_email,
                'email_verified_at' => $now,
                'level' => 0,
                'password' => bcrypt('123456'),
            ];
            $user = User::create($postVal);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $token = explode('|', $token);
        return response()
            ->json(['status' => true, 'message' => 'Suthentication Success', 'token' => $token[1], 'token_type' => 'Bearer',]);
    }


    public function generate(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'cust_number' => 'required|exists:t_customer,cust_number',
            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $verificationCode = $this->generateOtp($request->cust_number);

        $message = "Kode otp anda berhasil dikirimkan ke - " . $verificationCode;

        return response()
            ->json(['status' => true, 'message' => $message,]);
    }

    public function generateOtp($custNumber)
    {
        $customer = Customer::where('cust_number', $custNumber)->first();

        # User Does not Have Any Existing OTP
        $verificationCode = VerificationCode::where('user_id', $customer->cust_number)->latest()->first();

        $now = Carbon::now();

        if ($verificationCode && $now->isBefore($verificationCode->expire_at)) {
            return $verificationCode;
        }

        // Create a New OTP
        VerificationCode::create([
            'user_id' => $customer->cust_number,
            'otp' => rand(1234, 9999),
            'expire_at' => Carbon::now()->addMinutes(10)
        ]);

        return $customer->cust_phone;
    }
}
