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
use Carbon\Carbon;


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

    public function createCredit(Request $request)
    {
        $email = $request->input('email');
        $wallet = DB::table('wallets')->where('email', $email)->first();
        if ($wallet) {
            $movement = new Movement();
            $movement->wallet_id = $wallet->id;
            $movement->type = 'i';
            $movement->transfer = 0;
            $movement->date = Carbon::now();
            $movement->start_balance = $wallet->balance;
            $movement->value = $request->input('value');
            $movement->end_balance = $wallet->balance + $request->input('value');
            if($request->input('type') == 'bt'){
                $movement->type_payment = 'bt';
                $movement->iban = $request->input('iban');
                $movement->description = $request->input('description');
            }
            $movement->save();
            DB::table('wallets')->where('email', $email)->update(array('balance' => $movement->end_balance,'updated_at'=>Carbon::now()));

        }else{
            return response()->json(['msg'=>"error"], 200);
        }

        return response()->json(['msg'=>"sucess"], 200);
    }

    public function createDebit(Request $request, $id)
    {
        $wallet = Wallet::findOrFail($id);
        if($wallet){
            $movement = new Movement();
            $movement->wallet_id = $wallet->id;
            $movement->type = 'e';
            $movement->date = Carbon::now();
            $movement->start_balance = $wallet->balance;
            $movement->value = $request->input('value');
            $movement->end_balance = $wallet->balance - $request->input('value');
            $movement->description = $request->input('description');
            if($request->input('type') == 'mb'){
                $movement->type_payment = 'mb';
                $movement->transfer = 0;
                $movement->mb_entity_code = $request->input('mbEnt');
                $movement->mb_payment_reference = $request->input('mbRef');
            }
            if($request->input('type') == 'bt'){
                $movement->type_payment = 'bt';
                $movement->transfer = 0;
                $movement->iban = $request->input('iban');
            }
            if($request->input('type') == 'int'){
                $movement->transfer = 1;
                $walletTo = DB::table('wallets')->where('email', $request->input('email'))->first();
                if($walletTo){
                    $movement->transfer_wallet_id = $walletTo->id;

                    $movementTo = new Movement();
                    $movementTo->wallet_id = $walletTo->id;
                    $movementTo->type = 'i';
                    $movementTo->date = Carbon::now();
                    $movementTo->start_balance = $walletTo->balance;
                    $movementTo->value = $request->input('value');
                    $movementTo->end_balance = $walletTo->balance + $request->input('value');
                    $movementTo->source_description = $request->input('sourcedescription');
                    $movementTo->transfer = 1;
                    $movementTo->transfer_wallet_id = $wallet->id;
                }
            }
            $movement->save();
            $movementTo->save();
            $movement->update(array('transfer_movement_id' => $movementTo->id));
            $movementTo->update(array('transfer_movement_id' => $movement->id));
            DB::table('wallets')->where('email', $wallet->email)->update(array('balance' => $movement->end_balance,'updated_at'=>Carbon::now()));
            DB::table('wallets')->where('email', $walletTo->email)->update(array('balance' => $movementTo->end_balance,'updated_at'=>Carbon::now()));
            return response()->json(['msg'=>"sucess"], 200);
        }      
         return response()->json(['msg'=>"error"], 200);
     
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


