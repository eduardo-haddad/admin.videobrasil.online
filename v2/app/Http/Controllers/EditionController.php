<?php

namespace App\Http\Controllers;

use \DB;
use Illuminate\Http\Request;
use App\Edition;
use App\SeasonType;
use App\Video;

class EditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('edition.index', [
            'editions' => Edition::with('seasonType')->get()
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
        $types = SeasonType::all()->map->only(['id', 'title_pt']);
        $types_arr = [];
        foreach($types as $type){
            $types_arr[$type['id']] = $type['title_pt'];
        }

        return view('edition.edit', [
            'edition' => Edition::find($id),
            'season_types' => $types_arr,
        ]);
    }

    public function clearActive($current, $id = null)
    {
        $current = !empty($current) ? $current : 0;

        if(!empty($current)){

            $active = Edition::where('current', 1);
            
            if($id){
                $active->where('id', '<>', $id);
            }
                
            $active = $active->first();

            if(!empty($active)){
                $active->current = 0;
                $active->save();
            }
        }
        return $current;
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
        $edition = Edition::find($id);

        // Clear any other active edition
        $current = $this->clearActive($request['current'], $id);

        if($edition){
            $edition->title_pt = $request['title_pt'];
            $edition->title_en = $request['title_en'];
            $edition->subtitle_pt = $request['subtitle_pt'];
            $edition->subtitle_en = $request['subtitle_en'];
            $edition->current = $current;
            $edition->group_programs = !empty($request['group_programs']) ? $request['group_programs'] : 0;
            $edition->bg_color = $request['bg_color'];
            $edition->bg_img_desktop = $request['bg_img_desktop'];
            $edition->bg_img_mobile = $request['bg_img_mobile'];
            $edition->season_type_id = $request['season_types'];
            $edition->main_preview_custom_title_pt = $request['main_preview_custom_title_pt'];
            $edition->main_preview_custom_title_en = $request['main_preview_custom_title_en'];
            $edition->videos_to_show = $request['videos_to_show'];

            $edition->save();

            return redirect()->route('edition.index')
                         ->with('success', 'Edição alterada com sucesso!');
        }
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            Edition::destroy($id);
            DB::commit();
            return redirect()->route('edition.index')
                         ->with('success', 'Edição removida com sucesso!');

        } catch (\Exception $e){
            DB::rollback();
            return redirect()->route('edition.index')
                         ->with('error', 'Erro ao remover edição!');
        }
    }

    public function create()
    {
        $types = SeasonType::all()->map->only(['id', 'title_pt']);
        $types_arr = [];
        foreach($types as $type){
            $types_arr[$type['id']] = $type['title_pt'];
        }

        return view('edition.create', [
            'season_types' => $types_arr,
        ]);
    }

    public function store(Request $request)
    {
        $edition = new Edition;

        // Clear any other active edition
        $current = $this->clearActive($request['current']);

        $edition->title_pt = $request['title_pt'];
        $edition->title_en = $request['title_en'];
        $edition->subtitle_pt = $request['subtitle_pt'];
        $edition->subtitle_en = $request['subtitle_en'];
        $edition->current = $current;
        $edition->group_programs = !empty($request['group_programs']) ? $request['group_programs'] : 0;
        $edition->bg_color = $request['bg_color'];
        $edition->bg_img_desktop = $request['bg_img_desktop'];
        $edition->bg_img_mobile = $request['bg_img_mobile'];
        $edition->season_type_id = $request['season_types'];
        $edition->main_preview_custom_title_pt = $request['main_preview_custom_title_pt'];
        $edition->main_preview_custom_title_en = $request['main_preview_custom_title_en'];
        $edition->videos_to_show = $request['videos_to_show'];

        $edition->save();

        return redirect()->route('edition.index')
                        ->with('success', 'Edição criada com sucesso!');

    }
}
