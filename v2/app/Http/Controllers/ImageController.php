<?php

namespace App\Http\Controllers;

use App\Listing\Listing;
use App\Listing\Image;
use App\Listing\Newconst;
use App\Tag;
use App\Listing\Floorplan;
use App\Http\Services\Upload\ImageUpload;
use App\Http\Services\Listing\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use DB;
use GuzzleHttp\Client as GuzzClient;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('images.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        $listing = Listing::with(['images', 'floorplan'])->where('listing_id', $id)->first();
        
        $images_high = $listing->images()->with('tags')->whereNotNull('image_myListings')->where([
            ['mark_logo', '<>', '1'],
            ['image_myListings', '<>', ''],
        ])->orderBy('image_date', 'desc')->get();

        $images_low = $listing->images()->where([
            ['mark_logo', '=', '0'],
            ['image_myListings', '=', '']
        ])->get();
        $logos = $listing->images()->where([
            ['mark_logo', '=', '1'],
        ])->get();
        $wallpaper = $listing->images()->where([
            ['mark_logo', '=', '2'],
        ])->first();

        if(empty($wallpaper)) $wallpaper = new Image();

        // Tags
        $tags = Tag::getTagTitleArray();

        // Image order
        $tag_order = [0,0,0,0];

        if($listing->image_tag_order !== 'NULL' && !empty($listing->image_tag_order)) $tag_order = explode(",", $listing->image_tag_order);

        // Orulo
        $new_const = Newconst::find($id);
        $orulo_url = !empty($new_const) ? $new_const->listing_orulo_url : '';

        // Data
        $data = compact('listing','images_high', 'images_low', 'logos', 'wallpaper', 'tags', 'tag_order', 'orulo_url');

        return view('images.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(empty($id)) return response("Empty listing id", 400);
        if(empty($request['model'])) return response("Empty model", 400);

        $model = $request['model'];

        DB::beginTransaction();

        // Delete all button
        if ($request['type'] == 'delete-all'){
            try {
                $images = $model == "image" ? Listing::find($id)->images() : Listing::find($id)->floorplan();

                // Delete images from AWS disk
                if(env('APP_ENV') == 'staging' || env('APP_ENV') == 'prod') {
                    foreach($images->get() as $image){
                        $image_path_high = env('IMAGE_DIR')."/$image->image_myListings";
                        $image_path_low = env('IMAGE_DIR')."/$image->image_path";

                        // mark_logo: 0 => Listing viewer / 1 => Logo / 2 => SERP
                        if(($model == "image" && $image->mark_logo == "0") || $model == "floorplans"){
                            if(file_exists($image_path_high)) @unlink($image_path_high);
                            if(file_exists($image_path_low)) @unlink($image_path_low);
                        }
                    }
                }

                $images->delete();

                // Delete related tags for all images
                if($model == "image"){
                    DB::delete("DELETE FROM db_res_utf8.res_listing_images_tags WHERE listing_id = $id");
                }

                DB::commit();

                return 'true';

            } catch (\Exception $e){
                DB::rollback();
                return $e->getMessage();
            }
        }

        // Delete a single image
        try {
            $image = $model == "image" ? Image::find($request['image_id']) : Floorplan::find($request['image_id']);

            // Delete images from AWS disk
            if(env('APP_ENV') == 'staging' || env('APP_ENV') == 'prod') {
                $image_path_high = env('IMAGE_DIR')."/$image->image_myListings";
                $image_path_low = env('IMAGE_DIR')."/$image->image_path";

                // mark_logo: 0 => Listing viewer / 1 => Logo / 2 => SERP
                if(($model == "image" && $image->mark_logo == "0") || $model == "floorplans"){
                    if(file_exists($image_path_high)) @unlink($image_path_high);
                    if(file_exists($image_path_low)) @unlink($image_path_low);
                }
            }

            // Delete related tag on pivot table
            if($model == "image"){
                $tag = $image->tags()->first();
                if(!empty($tag->tag_id)) $image->tags()->detach($tag->tag_id);
            }

            $image->delete();

            DB::commit();

            return 'true';

        } catch (\Exception $e){
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function order(Request $request)
    {
        $order = DB::table('ai_core.core_settings')
            ->where('setting_key', 'listings_tag_order')
            ->first();

        $data = [
            'tags' => Tag::getTagTitleArray(),
            'tag_order' => !empty($order->setting_value) ? explode(',', $order->setting_value) : [0,0,0,0]
        ];

        return view('images.order', $data);
    }

    public function upload(Request $request){
        $validator = \Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $error_response = response()->json(['errors'=>$validator->errors()->all()], 400);

        if ($validator->fails()) {
            return $error_response;
        }

        if(empty($request->listing_id)) {
            return response("Empty listing id", 400)->header('Content-Type', 'text/plain');
        }

        return ImageUpload::store($request->image, $request->listing_id, $request->model);
    }

    public function getFile(Request $request){
        $storage_path = env('APP_ENV') == 'local' ? storage_path('app/public/tmp') : base_path('../../storage/app/public/tmp');

        $url = $request->url;
        $extension = explode('.', $url);
        $extension = end($extension);
        $file_id = mt_rand(1000,9999);
        $file_name = $file_id.'_img.'.$extension;

        $client = new GuzzClient();
        $response = $client->get($url);
        $file = $response->getBody()->getContents();

        Storage::disk('public')->put('tmp/'.$file_name, $file);

        // Mime type
        $mime_type = mime_content_type("$storage_path/$file_name");

        $file_upload = new UploadedFile(
            "$storage_path/$file_name",
            $file_name,
            $mime_type,
            $response->getHeaders()['Content-Length'][0],
            null,
            true
        );

        // Create file
        ImageUpload::store($file_upload, $request->listing_id, $request->model);

        // Testing method to generate Base64
        $response = new Response();
        $d = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, basename("$storage_path/$file_name"));

        $response->headers->set('Content-Disposition', $d);
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $type = $finfo->file("$storage_path/$file_name");
        $response->headers->set('Content-Type', $type);
        $response->headers->set('Content-Length', filesize("$storage_path/$file_name"));
        $response->headers->set('X-Content-Transfer-Id', $file_id);
        $response->sendHeaders();
        $response->setContent(base64_encode($file));

        Storage::deleteDirectory('tmp');
        return $response;

    }

    public function setWallpaper(Request $request){

        $listing = Listing::with(['images'])->where('listing_id', $request->listing_id)->first();
        // Only high res images
        $images = $listing->images()->whereNotNull('image_myListings')->where([
            ['mark_logo', '<>', '1'],
            ['image_myListings', '<>', ''],
        ])->get();

        $image = Image::find($request->image_id);

        // Check minimum dimensions
        list($width, $height) = getimagesize(env('CDN_URL')."/images/".$image->image_myListings);

        try{
            // Set all high res images to mark_logo = 0
            foreach($images as $img){
                $img->mark_logo = "0";
                $img->save();
            }
        }
        catch (\Exception $e){
            return $e->getMessage();
        }

        try{
            // Set selected image with mark_logo = 2
            $image->mark_logo = "2";
            $image->save();
        }
        catch (\Exception $e){
            return $e->getMessage();
        }

        try{
            // Set SERP image in res_listings
            $listing->listing_display_photo = $image->image_myListings;
            $listing->listing_photo_status = '1';
            $listing->save();

            //Save to slots table
            Slot::update($listing);
        }
        catch (\Exception $e){
            return $e->getMessage();
        }

        if($width < "1280") return response("Wallpaper selecionado, porém a resolução da imagem é baixa. Considere selecionar uma imagem com resolução alta, de pelo menos 1280px", 204);

        return response("Imagem de destaque selecionada", 200);


    }

    public function setTag(Request $request){
        
        if(empty($request->image_id)) return response("Empty image id", 400);
        else if(empty($request->listing_id)) return response("Empty listing id", 400);

        try{
            // Set tag for selected image
            $image = Image::find($request->image_id);
            $image->tags()->detach();
            $image->tags()->attach($request->tag_id, [
                'tag_order' => '',
                'listing_id' => $request->listing_id
            ]);
            return response("Tag salva com sucesso", 200);
        }
        catch (\Exception $e){
            return $e->getMessage();
        }

    }

    public function setTagOrder(Request $request){

        if(empty($request->tag_order)) return response("Empty tag order", 400);
        else if(empty($request->listing_id)) return response("Empty listing id", 400);

        try{
            // Set tag for selected image
            $listing = Listing::find($request->listing_id);
            $listing->image_tag_order = implode(",", $request->tag_order);
            $listing->save();

            //Save to slots table
            Slot::update($listing);

            return response("Ordem de tags salva com sucesso", 200);
        }
        catch (\Exception $e){
            return $e->getMessage();
        }

    }

    public function setTagOrderAll(Request $request){

        if(empty($request->tag_order)) return response("Empty tag order", 400);

        DB::beginTransaction();

        try{
            $image_tag_order = implode(",", $request->tag_order);
            DB::statement("UPDATE ai_core.core_settings SET setting_value = '{$image_tag_order}' WHERE setting_key = 'listings_tag_order'");
            DB::commit();
            return response("Ordem de tags salva com sucesso", 200);
        }
        catch (\Exception $e){
            DB::rollBack();
            return $e->getMessage();
        }

    }

}
