<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SeasonType;

class SeasonTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('season.index', [
            'seasons' => SeasonType::all()
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
        return view('season.edit', [
            'season' => SeasonType::find($id)
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
        $season = SeasonType::find($id);

        if($season){
            $season->title_pt = $request['title_pt'];
            $season->title_en = $request['title_en'];
            $season->save();

            return redirect()->route('season.index')
                         ->with('success', 'Temporada editada com sucesso!');
        }
    }

    public function destroy($id)
    {
        //
    }


}
