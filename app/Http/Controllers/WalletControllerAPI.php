<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

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

    
    // public function store(Request $request)
    // {
    //     //dd($request->photoFile);
    //     $request->validate([
    //             'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
    //             'email' => 'required|email|unique:users,email',
    //             'password' => 'min:3'
    //         ]);
    //     $user = new User();
    //     $user->fill($request->all());
    //     $user->password = bcrypt($user->password);
    //     if($request->hasFile('photoFile')){
    //         $file = $request->photoFile;
    //         $fileNew = \Storage::putFile('public/fotos', $file);
    //         $filename = basename($fileNew);
    //         $user->photo=$filename;
    //     }
    //     $user->save();
    //     return response()->json(new UserResource($user), 201);
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //             'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
    //             'email' => 'required|email|unique:users,email,'.$id,
    //         ]);
    //     $user = User::findOrFail($id);
        
    //     if($request->hasFile('photoFile')){
    //         $file = $request->photoFile;
    //         $fileNew = \Storage::putFile('public/fotos', $file);
    //         $filename = basename($fileNew);
    //         $user->photo=$filename;
    //     }
        
    //     $user->update($request->all());
    //     return new UserResource($user);
    // }

    // public function save(Request $request)
    // {
    //     $user = User::where('email', $request->input('email'))->first();
    //     if ($request->has('name') && !empty($request->input('name')))
    //         $user->name = $request->input('name');
    //     if ($request->has('nif') && !empty($request->input('nif')))
    //         $user->nif = $request->input('nif');
    //     if ($request->has('photo') && !empty($request->input('photo')))
    //         $user->photo = $request->input('photo');
    //     $user->save();
    //     return new UserResource($user);
    // }


    // public function destroy($id)
    // {
    //     $user = User::findOrFail($id);
    //     $user->delete();
    //     return response()->json(null, 204);
    // }
}


