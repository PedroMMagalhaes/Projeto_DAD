<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use App\Http\Resources\User as UserResource;
use App\Http\Resources\Wallet as WalletResource;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Wallet;
use App\StoreUserRequest;
use Hash;


class WalletControllerAPI extends Controller
{
    public function index(Request $request)
    {
       if ($request->has('page')) {
            return WalletResource::collection(Wallet::paginate(5));
        } else {
          return WalletResource::collection(Wallet::all());
        }
        //$wallets = \App\Wallet::all();
        //return $wallets; //ALL WALLETS WITHOUT FILTER
    }

    public function countWallets(){
      return (WalletResource::collection(Wallet::all())->count());
    }

    public function show($id)
    {
        return new WalletResource(Wallet::find($id));
    }

    public function myWallet(Request $request)
    {
        return new WalletResource($request->wallet());
    }

    
    public function create($id)
    {
        // $request->validate([
        //         'id' => 'required'
        //     ]);
        $wallet = new Wallet();
        // $wallet->fill($request->all());
        $wallet->id = $id;
        $user = new UserResource(User::find($id));
        $wallet->email = $user->email;
        return $wallet->save();
        // return response()->json(new WalletResource($wallet), 201);
    }

    public function update(Request $request, $id)
    {
      $request->validate([
          'balance' => 'required|',
      ]);
      $wallet = Wallet::findOrFail($id);

      $wallet->update($request->all());
      return new WalletResource($wallet);
    }

    public function destroy($id)
    {
        $wallet = User::findOrFail($wallet);
        $wallet->delete();
        return response()->json(null, 204);
    }
}


