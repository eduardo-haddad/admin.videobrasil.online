<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipe;

class EquipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('equipe.index', [
            'equipe' => Equipe::all()
        ]);
    }

    /**
     * Show the given resource.
     */
    public function show(Request $request, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('equipe.edit', [
            'equipe' => Equipe::find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\StoreUser $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $membro = Equipe::find($id);

        if($membro){
            $membro->name = $request['name'];
            $membro->role_pt = $request['role_pt'];
            $membro->role_en = $request['role_en'];
            $membro->save();

            return redirect()->route('equipe.index')
                         ->with('success', 'Equipe editada com sucesso!');
        }
    }

    public function destroy($id)
    {
        //
    }


}
