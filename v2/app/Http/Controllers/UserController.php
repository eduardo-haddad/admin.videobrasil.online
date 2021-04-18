<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\User;
use App\Lead\Qa;
use App\Client\Client;
use App\Client\ClientAccess;
use App\Http\Requests\StoreUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index', [
            'users' => User::paginate(15)
        ]);
    }

    /**
     * Show the given resource.
     */
    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->merge(['by_agent' => [$user->id]]);
        $results = new \App\Http\Services\Results\Leads\Qa($request);

        return view('users.show', array_merge([
            'profile' => $user
        ], $results->get()));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('users.edit', [
            '_user' => User::find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\StoreUser $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUser $request, $id)
    {
        $user = User::find($id);

        if($request->has('roles')){
            $user->roles()->sync($request->get('roles'));
        }

        if($request->filled('new_password')){
            $user->password = Hash::make($request->get('new_password'));
        }

        $user->fill($request->except('password', 'new_password'));
        $user->save();

        if($request->wantsJson()){
            return response()->json($user->status_context);
        }

        return redirect()->route('users.index')
                         ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the client relationship.
     *
     * @param int $user
     * @param int $client
     */
    public function destroyClient($user, $client)
    {
        if(($client = Client::find($client)) && $client->admin_user_id == $user){
            $client->user()->dissociate()->save();
        }
    }

    /**
     * Remove user access to client tracking.
     *
     * @param int $user
     * @param int $client
     */
    public function destroyAccessClient($user, $client)
    {
        ClientAccess::where('user_id', $user)->where('client_id', $client)->delete();
    }
}
