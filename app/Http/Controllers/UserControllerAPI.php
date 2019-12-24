<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\DB;

use App\User;
use App\StoreUserRequest;
use Hash;

class UserControllerAPI extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('page')) {
            return UserResource::collection(User::paginate(5));
        } else {
            return UserResource::collection(User::all());
        }

        /*Caso não se pretenda fazer uso de Eloquent API Resources (https://laravel.com/docs/5.5/eloquent-resources), é possível implementar com esta abordagem:
        if ($request->has('page')) {
            return User::with('department')->paginate(5);;
        } else {
            return User::with('department')->get();;
        }*/
    }

    public function show($id)
    {
        return new UserResource(User::find($id));
    }

    public function store(Request $request)
    {
        //dd($request->photoFile);
        $request->validate([
                'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
                'email' => 'required|email|unique:users,email',
                'password' => 'min:3'
            ]);
        $user = new User();
        $user->fill($request->all());
        $user->password = bcrypt($user->password);
        if($request->hasFile('photoFile')){
            $file = $request->photoFile;
            $fileNew = \Storage::putFile('public/fotos', $file);
            $filename = basename($fileNew);
            $user->photo=$filename;
        }
        $user->save();
        return response()->json(new UserResource($user), 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
                'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
                'email' => 'required|email|unique:users,email,'.$id,
            ]);
        $user = User::findOrFail($id);
        
        if($request->hasFile('photoFile')){
            $file = $request->photoFile;
            $fileNew = \Storage::putFile('public/fotos', $file);
            $filename = basename($fileNew);
            $user->photo=$filename;
        }
        if (!Hash::check($request->passwordAtual, $user->password)) {
            return response()->json('Password Not Match !!!!', 403);
        }
       // $user = User::where('email', $request->input('email'))->first();
        if ($request->has('name') && !empty($request->input('name')))
            $user->name = $request->input('name');
        if ($request->has('nif') && !empty($request->input('nif')))
            $user->nif = $request->input('nif');
        if ($request->has('photo') && !empty($request->input('photo')))
            $user->photo = $request->input('photo');
            if ($request->has('password') && !empty($request->input('password')))
            $user->password = bcrypt($request->input('password'));

        $user->save();
        return new UserResource($user);
    }

    public function save(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        if ($request->has('name') && !empty($request->input('name')))
            $user->name = $request->input('name');
        if ($request->has('nif') && !empty($request->input('nif')))
            $user->nif = $request->input('nif');
        if ($request->has('photo') && !empty($request->input('photo')))
            $user->photo = $request->input('photo');
        $user->save();
        return new UserResource($user);
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
    public function emailAvailable(Request $request)
    {
        $totalEmail = 1;
        if ($request->has('email') && $request->has('id')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->where('id', '<>', $request->id)->count();
        } else if ($request->has('email')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->count();
        }
        return response()->json($totalEmail == 0);
    }

    public function myProfile(Request $request)
    {
        return new UserResource($request->user());
    }
}
