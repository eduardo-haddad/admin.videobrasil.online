<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Sobre;
use Illuminate\Http\Request;

class SobreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function edit()
    {
        return view('sobre.edit', [
            'sobre' => Sobre::first()
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
        $sobre = Sobre::find($id);

        if($sobre){
            $sobre->title_pt = $request['title_pt'];
            $sobre->title_en = $request['title_en'];
            $sobre->content_pt = $request['content_pt'];
            $sobre->content_en = $request['content_en'];
            $sobre->save();

            return redirect()->route('home')
                         ->with('success', 'Sobre editado com sucesso!');
        }


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
