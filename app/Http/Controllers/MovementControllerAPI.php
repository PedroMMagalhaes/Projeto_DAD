<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use App\Http\Resources\User as UserResource;
use App\Http\Resources\Wallet as WalletResource;
use App\Http\Resources\Movement as MovementResource;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Wallet;
use App\Movement;
use App\StoreUserRequest;
use Hash;


class MovementControllerAPI extends Controller
{
    public function index(Request $request)
    {
       if ($request->has('page')) {
            return MovementResource::collection(Movement::paginate(10));
        } else {
          return MovementResource::collection(Movement::all());
        }
    }

    public function show($id)
    {
        return new MovementResource(Movement::find($id));
    }

    public function getMovements(Request $request, $wallet_id)
    {
        if ($request->has('page')) {
            $movements = Movement::where('wallet_id',$wallet_id)->orderBy('date', 'desc')->paginate(10);
            return MovementResource::collection($movements);
        } else {
            $movements = Movement::where('wallet_id',$wallet_id)->orderBy('date', 'desc')->get();
            return MovementResource::collection($movements);
        }
    }

    public function updateMovement(Request $request, $id)
    {
        $movement = Movement::findOrFail($id);

        if ($movement->category_id != $request->input('category_id')){
            $movement->category_id = $request->input('category_id');
        }
        $movement->description =  $request->input('description');
        
        $movement->save();

        return new MovementResource($movement);
    }

    
    public function create($walletId)
    {
        $wallet = new Wallet();
        $wallet->wallet_id = $walletid;
        $user = new UserResource(User::find($walletid));
        $wallet->email = $user->email;
        return $wallet->save();
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

    public function getCategories($type)
    {
        $category = DB::table('categories')->where('type', $type)->get();
        return json_encode($category);
    }
}


