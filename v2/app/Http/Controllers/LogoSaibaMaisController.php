<?php

namespace App\Http\Controllers;

use \DB;
use App\SaibaMais;
use App\LogoSaibaMais;
use App\Edition;
use App\PartnerRole;
use Illuminate\Http\Request;

class LogoSaibaMaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $edition = Edition::find($id);
        $saibamais = SaibaMais::with('logos')->where('edition_id', $id)->first();

        return view('saibamais.index', [
            'edition' => $edition,
            'logos' => $saibamais->logos()->get(),
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
    public function edit($edition_id, $id)
    {
        $types = PartnerRole::all()->map->only(['id', 'role_pt']);
        $types_arr = [];
        foreach($types as $type){
            $types_arr[$type['id']] = $type['role_pt'];
        }

        return view('saibamais.logos.edit', [
            'partner_roles' => $types_arr,
            'logosaibamais' => LogoSaibaMais::find($id),
            'edition_id' => $edition_id
        ]);
    }
    
    public function create($edition_id, $saibamais_id)
    {
        $types = PartnerRole::all()->map->only(['id', 'role_pt']);
        $types_arr = [];
        foreach($types as $type){
            $types_arr[$type['id']] = $type['role_pt'];
        }

        return view('saibamais.logos.create', [
            'partner_roles' => $types_arr,
            'saibamais_id' => $saibamais_id,
            'edition_id' => $edition_id
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
        $logo = LogoSaibaMais::find($id);

        if(!empty($logo)){
            $logo->img = $request['img'];
            $logo->url = $request['url'];
            $logo->saibamais_id = $request['saibamais_id'];
            $logo->partner_roles_id = $request['partner_roles'];
            $logo->save();

            return redirect()->route('saibamais.index', ['id' => $request['edition_id']])
                         ->with('success', 'Logo alterado com sucesso!');
        }
    }
    
    public function store(Request $request)
    {
        $logo = new LogoSaibaMais;

        $logo->img = $request['img'];
        $logo->url = $request['url'];
        $logo->saibamais_id = $request['saibamais_id'];
        $logo->partner_roles_id = $request['partner_roles'];
        $logo->save();

        return redirect()->route('saibamais.index', ['id' => $request['edition_id']])
                        ->with('success', 'Logo criado com sucesso!');
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            LogoSaibaMais::destroy($id);
            DB::commit();
            return redirect()->route('saibamais.index', ['id' => $request['edition_id']])
                         ->with('success', 'Logo removido com sucesso!');

        } catch (\Exception $e){
            DB::rollback();
            return redirect()->route('saibamais.index', ['id' => $request['edition_id']])
                         ->with('error', 'Erro ao remover logo!');
        }
    }

}
