<?php

namespace App\Http\Controllers;

use Storage;
use App\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    private $disk;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->disk = app()->environment(['prod']) ? 'cdn' : 'public';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $files = File::where('mimetype', 'like', 'image%')
                     ->orderBy('created_at', 'DESC')
                     ->take(15)
                     ->get();

        $files->transform(function($item, $key){
            return [
                'title' => $item->name,
                'value' => Storage::disk($this->disk)->url($item->path)
            ];
        });

        return response()->json($files->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->hasFile('image') && $request->file('image')->isValid()){
            $name = $original_name = $request->image->getClientOriginalName();
            $info = pathinfo($name);
            $name = str_slug($info['filename'], '-');

            // Count how many files exists with same name
            $count = File::where('name', $original_name)->count();

            if($count != 0){
                // Add count to file name to prevent overwrite
                $name .= "_($count)";
            }

            $name .= '.' . $info['extension'];

            if($path = $request->image->storeAs('images', $name, $this->disk)){
                File::create([
                    'name' => $original_name,
                    'path' => $path,
                    'mimetype' => $request->image->getMimeType()
                ]);

                return response()->json(['location' => Storage::disk($this->disk)->url($path)]);
            }
        }
    }
}
