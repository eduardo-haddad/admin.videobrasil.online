<?php

namespace App\Http\Controllers;

use App\SaibaMais;
use App\Edition;
use Illuminate\Http\Request;

class SaibaMaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $edition = Edition::find($id);
        $saibamais = SaibaMais::with('links', 'logos')->where('edition_id', $id)->first();
        $links = !empty($saibamais) ? $saibamais->links()->get() : null;
        $logos = !empty($saibamais) ? $saibamais->logos()->get() : null;

        return view('saibamais.index', [
            'edition' => $edition,
            'saibamais' => $saibamais,
            'links' => $links,
            'logos' => $logos,
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
    public function edit($edition_id)
    {
        return view('saibamais.edit', [
            'saibamais' => SaibaMais::where('edition_id', $edition_id)->first(),
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
        $saibaMais = SaibaMais::find($id);

        if(!empty($saibaMais)){
            $saibaMais->content_pt = $request['content_pt'];
            $saibaMais->content_en = $request['content_en'];
            $saibaMais->replace_text = $request['replace_text'];
            $saibaMais->save();

            return redirect()->route('saibamais.index', ['id' => $request['edition_id']])
                         ->with('success', 'Saiba Mais alterado com sucesso!');
        }
    }
    
    public function store(Request $request)
    {
        $saibaMais = new SaibaMais;

        $saibaMais->content_pt = $request['content_pt'];
        $saibaMais->content_en = $request['content_en'];
        $saibaMais->replace_text = $request['replace_text'];
        $saibaMais->edition_id = $request['edition_id'];
        $saibaMais->save();

        return back();
    }

    /**
     * Remove the client relationship.
     *
     * @param int $user
     * @param int $client
     */
    public function destroyClient($user, $client)
    {
        //
    }

}
