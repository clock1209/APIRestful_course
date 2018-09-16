<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * @author Octavio Cornejo <octavio.cornejo@nuvemtecnologia.mx>
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::all();

        return response()->json(['data' => $users], 200);
    }

    /**
     * @author Octavio Cornejo <octavio.cornejo@nuvemtecnologia.mx>
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::USER_NOT_VERIFIED;
        $data['verification_token'] = User::generateVerificationToken();
        $data['admin'] = User::USER_REGULAR;

        $user = User::create($data);

        return response()->json(['data' => $user], 201);
    }

    /**
     * @author Octavio Cornejo <octavio.cornejo@nuvemtecnologia.mx>
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return response()->json(['data' => $user], 200);
    }

    /**
     * @author Octavio Cornejo <octavio.cornejo@nuvemtecnologia.mx>
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USER_ADMINISTRATOR .','. User::USER_REGULAR,
        ];

        $this->validate($request, $rules);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') and $user->email != $request->email) {
            $user->verified = User::USER_NOT_VERIFIED;
            $user->verification_token = User::generateVerificationToken();
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->pasword);
        }

        if ($request->has('admin')) {
            if (!$user->isVerified()) {
                return response()->json(['error' => 'Únicamente los usurios verificados pueden cambiar su valor de administrador', 'code' => 409], 409);
            }
            $user->admin = $request->admin;
        }

        if ($user->isClean()) {
            return response()->json(['error' => 'Se debe especificar al menos un valor diferente para actualizar', 'code' => 422], 422);
        }

        $user->save();

        return response()->json(['data' => $user], 200);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['data' => $user], 200);
    }
}
