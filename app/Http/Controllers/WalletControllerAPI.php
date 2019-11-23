<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;
use App\Wallet;
use App\Http\Resources\Wallet as WalletResource;
use Illuminate\Support\Facades\DB;


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
}
