<?php

namespace App\Http\Controllers;

use \DB;
use App\SaibaMais;
use App\LinkSaibaMais;
use App\Edition;
use Illuminate\Http\Request;

class LinkSaibaMaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $edition = Edition::find($id);
        $saibamais = SaibaMais::with('links')->where('edition_id', $id)->first();

        return view('saibamais.index', [
            'edition' => $edition,
            'links' => $saibamais->links()->get(),
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
        return view('saibamais.links.edit', [
            'linksaibamais' => LinkSaibaMais::find($id),
            'edition_id' => $edition_id
        ]);
    }
    
    public function create($edition_id, $saibamais_id)
    {
        return view('saibamais.links.create', [
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
        $link = LinkSaibaMais::find($id);

        if(!empty($link)){
            $link->title_pt = $request['title_pt'];
            $link->title_en = $request['title_en'];
            $link->url_pt = $request['url_pt'];
            $link->url_en = $request['url_en'];
            $link->blank = !empty($request['blank']) ? $request['blank'] : 0;
            $link->download = !empty($request['download']) ? $request['download'] : 0;
            $link->text_replacement = $request['text_replacement'];
            $link->saibamais_id = $request['saibamais_id'];
            $link->save();

            return redirect()->route('saibamais.index', ['id' => $request['edition_id']])
                         ->with('success', 'Link alterado com sucesso!');
        }
    }
    
    public function store(Request $request)
    {
        $link = new LinkSaibaMais;

        $link->title_pt = $request['title_pt'];
        $link->title_en = $request['title_en'];
        $link->url_pt = $request['url_pt'];
        $link->url_en = $request['url_en'];
        $link->blank = !empty($request['blank']) ? $request['blank'] : 0;
        $link->download = !empty($request['download']) ? $request['download'] : 0;
        $link->text_replacement = $request['text_replacement'];
        $link->saibamais_id = $request['saibamais_id'];
        $link->save();

        return redirect()->route('saibamais.index', ['id' => $request['edition_id']])
                        ->with('success', 'Link criado com sucesso!');
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            LinkSaibaMais::destroy($id);
            DB::commit();
            return redirect()->route('saibamais.index', ['id' => $request['edition_id']])
                         ->with('success', 'Link removido com sucesso!');

        } catch (\Exception $e){
            DB::rollback();
            return redirect()->route('saibamais.index', ['id' => $request['edition_id']])
                         ->with('error', 'Erro ao remover link!');
        }
    }

}
