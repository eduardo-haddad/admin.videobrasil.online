<?php

namespace App\Http\Controllers;

use \DB;
use Illuminate\Http\Request;
use App\Video;
use App\Edition;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($edition_id)
    {
        $edition = Edition::find($edition_id);
        $videos = Video::where('edition_id', $edition_id)->get();

        return view('video.index', [
            'edition' => $edition,
            'videos' => $videos,
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
    public function edit($id, $edition_id)
    {
        return view('video.edit', [
            'video' => Video::find($id),
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
        //dd($request);
        $video = Video::find($id);

        if($video){
            $video->vimeo_id = $request['vimeo_id'];
            $video->vimeo_id_pt = $request['vimeo_id_pt'];
            $video->vimeo_id_en = $request['vimeo_id_en'];
            $video->title_pt = $request['title_pt'];
            $video->title_en = $request['title_en'];
            $video->subtitle_pt = $request['subtitle_pt'];
            $video->subtitle_en = $request['subtitle_en'];
            $video->main_preview_html_pt = $request['main_preview_html_pt'];
            $video->main_preview_html_en = $request['main_preview_html_en'];
            $video->title_box_pt = $request['title_box_pt'];
            $video->title_box_en = $request['title_box_en'];
            $video->poster_pt = $request['poster_pt'];
            $video->poster_en = $request['poster_en'];
            $video->thumb_pt = $request['thumb_pt'];
            $video->thumb_en = $request['thumb_en'];
            $video->category_pt = $request['category_pt'];
            $video->category_en = $request['category_en'];
            $video->specs_pt = $request['specs_pt'];
            $video->specs_en = $request['specs_en'];
            $video->caption_pt = $request['caption_pt'];
            $video->caption_en = $request['caption_en'];
            $video->edition_id = $request['edition_id'];
            $video->video_program_id = $request['video_program_id'];
            $video->order = $request['order'];
            
            $video->save();

            return redirect()->route('video.index', ['id' => $request['edition_id']])
                         ->with('success', 'Vídeo alterado com sucesso!');
        }
    }

    public function destroy(Request $request, $id)
    {

        DB::beginTransaction();

        try {
            Video::destroy($id);
            DB::commit();
            return redirect()->route('video.index', ['id' => $request['edition_id']])
                         ->with('success', 'Vídeo removido com sucesso!');

        } catch (\Exception $e){
            DB::rollback();
            return redirect()->route('video.index')
                         ->with('error', 'Erro ao remover vídeo!');
        }
    }

    public function create($edition_id)
    {
        return view('video.create', [
            'edition_id' => $edition_id,
        ]);
    }

    public function store(Request $request)
    {
        $video = new Video;

        $video->vimeo_id = $request['vimeo_id'];
        $video->vimeo_id_pt = $request['vimeo_id_pt'];
        $video->vimeo_id_en = $request['vimeo_id_en'];
        $video->title_pt = $request['title_pt'];
        $video->title_en = $request['title_en'];
        $video->subtitle_pt = $request['subtitle_pt'];
        $video->subtitle_en = $request['subtitle_en'];
        $video->main_preview_html_pt = $request['main_preview_html_pt'];
        $video->main_preview_html_en = $request['main_preview_html_en'];
        $video->title_box_pt = $request['title_box_pt'];
        $video->title_box_en = $request['title_box_en'];
        $video->poster_pt = $request['poster_pt'];
        $video->poster_en = $request['poster_en'];
        $video->thumb_pt = $request['thumb_pt'];
        $video->thumb_en = $request['thumb_en'];
        $video->category_pt = $request['category_pt'];
        $video->category_en = $request['category_en'];
        $video->specs_pt = $request['specs_pt'];
        $video->specs_en = $request['specs_en'];
        $video->caption_pt = $request['caption_pt'];
        $video->caption_en = $request['caption_en'];
        $video->edition_id = $request['edition_id'];
        $video->video_program_id = $request['video_program_id'];
        $video->order = !empty($request['order']) ? $request['order'] : 0;

        $video->save();

        return redirect()->route('video.index', ['id' => $request['edition_id']])
                         ->with('success', 'Vídeo criado com sucesso!');

    }




}
