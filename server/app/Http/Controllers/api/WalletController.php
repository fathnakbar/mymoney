<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Wallets;

use DB;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                "data" => false
            ], 401);
        }


        return response()->json([
            "data" => DB::table('wallets')
                    ->where('wallets.user_id', $user->id)
                    ->get()
        ]);
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

        return response()->json([
            "data" => Wallets::create([
                        "type" => $request->type,
                        "name" => $request->name,
                        "user_id" => $user->id
                    ])
            ]);
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
        return response()->json([
            "data" => DB::table('transactions')
                    ->join('wallets', 'transactions.wallet_id', '=', 'wallets.id')
                    ->where('wallets.id', $id)
                    ->where('wallets.user_id', $user->id)
                    ->select(DB::raw("transactions.id as id"), "amount", "currency", "wallet_id", "description", "date", "category", "author")
                    ->get()
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
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                "data" => false
            ], 401);
        }

        
        $wallet = DB::table("wallets")->where("id", $id)->where("user_id", $user->id)->first();

        if ($wallet != null && $wallet->user_id == $user->id) {
            
            return response()->json([
                "data" => Wallets::find($id)->update([
                    "type" => $request->type,
                    "name" => $request->name,
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
        
        $wallet = DB::table("wallets")->where("id", $id)->where("user_id", $user->id)->first();

        if ($wallet != null && $wallet->user_id == $user->id) {
            return response()->json([
                "data" => Wallets::find($id)->delete()
            ], 200);
        } else {
            return response()->json([
                "error" => "User is not the owner of the wallet"
            ], 401);
        }
    }
}
