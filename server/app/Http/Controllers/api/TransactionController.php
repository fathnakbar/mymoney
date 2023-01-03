<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Transactions;

use DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                "data" => false
            ], 401);
        }


        $wallet = DB::table("wallets")->where("id", $request->wallet_id)->where("user_id", $user->id)->first();
        
        if ($wallet->user_id == $user->id) {
            $transaction = Transactions::create([
                "wallet_id" => $request->wallet_id,
                "currency" => $request->currency,
                "amount" => $request->amount,
                "author" => $user->id,
                "category" => $request->category,
                "description" => $request->description,
                "date" => $request->date
        
            ]);

            return response()->json([
                "data" => $transaction
            ], 200);
        } else {
            return response()->json([
                "error" => "User is not the owner of the wallet"
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                "data" => false
            ], 401);
        }


        return response()->json([
            "data" => DB::table('transactions')
                    ->join('wallets', 'transactions.wallet_id', '=', 'wallets.id')
                    ->where('transactions.id', $id)
                    ->where('wallets.user_id', $user->id)
                    ->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                "data" => false,
            ], 401);
        }


        $wallet = DB::table("transactions")->join('wallets', 'wallets.id', '=', 'transactions.wallet_id')
        ->where("transactions.id", $id)->where("wallets.user_id", $user->id)->first();
        
        if ($wallet->user_id == $user->id) {
            return response()->json([
                "data" => Transactions::find($id)->update([
                            "currency" => $request->currency,
                            "amount" => $request->amount,
                            "author" => $user->id,
                            "category" => $request->category,
                            "description" => $request->description,
                            "date" => $request->date
                        ])
            ], 200);
        } else {
            return response()->json([
                "error" => "User is not the owner of the wallet"
            ], 401);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                "data" => false
            ], 401);
        }


        $wallet = DB::table("transactions")->join('wallets', 'wallets.id', '=', 'transactions.wallet_id')
        ->where("transactions.id", $id)->where("wallets.user_id", $user->id)->first();

        if ($wallet->user_id == $user->id) {
            return response()->json([
                "data" => Transactions::find($id)->delete()
            ], 200);
        } else {
            return response()->json([
                "error" => "User is not the owner of the wallet"
            ], 401);
        }
    }
}
