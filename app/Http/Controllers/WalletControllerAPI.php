<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use App\Http\Resources\Wallet as WalletResource;
use Illuminate\Support\Facades\DB;


class WalletControllerAPI extends Controller
{
    public function index()
    {
       // if ($request->has('page')) {
         //   return WalletResource::collection(Wallet::paginate(5));
        //} else {
            return WalletResource::collection(Wallet::all());
        //}
    }
}
