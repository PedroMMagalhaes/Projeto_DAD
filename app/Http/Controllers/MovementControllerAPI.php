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

    public function totalMovements(Request $request)
    {
        if ($request->has('days')) {
            $movements = DB::table('movements')
                ->select(DB::raw('count(*) as movements, DATE(date) as date'))
                ->whereRAW('(DATE(date) BETWEEN ADDDATE(CURDATE(), INTERVAL -? DAY) AND CURDATE())',[$request->input('days')])
                ->orderBy('DATE(date)', 'desc')
                ->groupBy('DATE(date)')
                ->limit(10)
                ->get();
            return json_encode($movements);
        } else {
            $movements = DB::table('movements')
                ->select(DB::raw("count(*) as movements, DATE(date) as day"))
                ->orderBy('day', 'desc')
                ->groupBy('day')
                ->limit(10)
                ->get();
            return json_encode($movements);
        }
    }

    public function movementsByExpense(Request $request, $id)
    {
        if ($request->has('days')) {
            $movements = DB::table('movements')
                ->select(DB::raw("sum(value) as movements, IFNULL((SELECT name from categories where id=movements.category_id),'') AS category"))
                ->whereRAW('(DATE(date) BETWEEN ADDDATE(CURDATE(), INTERVAL -? DAY) AND CURDATE())',[$request->input('days')])
                ->whereRAW("TYPE = 'e'")
                ->orderBy('movements', 'desc')
                ->groupBy('category')
                ->get();
            return json_encode($movements);
        } else {
            $movements = DB::table('movements')
                ->select(DB::raw("sum(value) as movements, IFNULL((SELECT name from categories where id=movements.category_id),'') AS category"))
                ->whereRAW("TYPE = 'e'")
                ->orderBy('movements', 'desc')
                ->groupBy('category')
                ->get();
            return json_encode($movements);
        }
    }

    public function movementsByIncome(Request $request, $id)
    {
        if ($request->has('days')) {
            $movements = DB::table('movements')
                ->select(DB::raw("sum(value) as movements, IFNULL((SELECT name from categories where id=movements.category_id),'') AS category"))
                ->whereRAW('(DATE(date) BETWEEN ADDDATE(CURDATE(), INTERVAL -? DAY) AND CURDATE())',[$request->input('days')])
                ->whereRAW("TYPE = 'i'")
                ->orderBy('movements', 'desc')
                ->groupBy('category')
                ->get();
            return json_encode($movements);
        } else {
            $movements = DB::table('movements')
                ->select(DB::raw("sum(value) as movements, IFNULL((SELECT name from categories where id=movements.category_id),'') AS category"))
                ->whereRAW("TYPE = 'i'")
                ->orderBy('movements', 'desc')
                ->groupBy('category')
                ->get();
            return json_encode($movements);
        }
    }

    public function movementsIncomeVSExpense(Request $request, $id)
    {
        $movements = DB::table('movements')
            ->select(DB::raw("sum(case when TYPE = 'i' then VALUE ELSE 0 end) as income, sum(case when TYPE = 'e' then VALUE ELSE 0 end) AS expense, DATE_FORMAT(CAST(date as DATE), '%m/%Y') AS TIME"))
            ->whereRAW("DATE>=date_add(date_add(CURDATE(),interval -DAY(CURDATE())+1 DAY), INTERVAL -11 MONTH)")
            ->orderBy('time', 'desc')
            ->groupBy('time')
            ->get();
        return json_encode($movements);
    }
    
}


