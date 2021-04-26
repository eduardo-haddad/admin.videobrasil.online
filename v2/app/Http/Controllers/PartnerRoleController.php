<?php

namespace App\Http\Controllers;

use \DB;
use Illuminate\Http\Request;
use App\PartnerRole;

class PartnerRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('partnerrole.index', [
            'roles' => PartnerRole::all()
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
        return view('partnerrole.edit', [
            'role' => PartnerRole::find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\StoreUser $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(request $request, $id)
    {
        $role = PartnerRole::find($id);

        if($role){
            $role->role_pt = $request['role_pt'];
            $role->role_en = $request['role_en'];
            $role->save();

            return redirect()->route('partnerroles.index')
                         ->with('success', 'Chancela editada com sucesso!');
        }
    }

    public function create()
    {
        return view('partnerrole.create');
    }

    public function store(Request $request)
    {
        $role = new PartnerRole;

        $role->role_pt = $request['role_pt'];
        $role->role_en = $request['role_en'];
        $role->save();

        return redirect()->route('partnerroles.index')
                         ->with('success', 'Chancela criada com sucesso!');

    }

    public function destroy($id)
    {

        DB::beginTransaction();

        try {
            PartnerRole::destroy($id);
            DB::commit();
            return redirect()->route('partnerroles.index')
                         ->with('success', 'Chancela removida com sucesso!');

        } catch (\Exception $e){
            DB::rollback();
            return redirect()->route('partnerroles.index')
                         ->with('error', 'Erro ao remover chancela!');
        }
    }


}
