<?php

namespace App\Http\Services\Upload;

use DB;
use Illuminate\Support\Facades\Storage;
use App\Listing\Image;
use App\Listing\Floorplan;

class ImageUpload
{
    static public function store($file, $listing_id, $model) {
        $original_filename = $file->getClientOriginalName();
        $file_contents = file_get_contents($file);
        $file_extension = pathinfo($original_filename, PATHINFO_EXTENSION);
        $file_name = pathinfo($original_filename, PATHINFO_FILENAME);
        $file_name = date('YmdHis').'_'.strtolower(strip_special_chars(strip_accents(trim($file_name))));
        $file_name = "$file_name.$file_extension";
        $local_path = storage_path('app/public/').$file_name;

        $folder_one = substr($listing_id, 0, 1);
        $folder_two = substr($listing_id, 0, 2);

        $high_res_path = "$folder_one/$folder_two/$listing_id/high";
        $low_res_path = "$folder_one/$folder_two/$listing_id/low";

        $disk_path_high = env('IMAGE_DIR')."/$high_res_path";
        $disk_path_low = env('IMAGE_DIR')."/$low_res_path";

        $disk_path_file_high = "$disk_path_high/$file_name";
        $disk_path_file_low = "$disk_path_low/$file_name";

        if(Storage::disk('public')->put($file_name, $file_contents)) {
            if(env('APP_ENV') == 'staging' || env('APP_ENV') == 'prod') {
                // Move to AWS disk
                if (!file_exists($disk_path_high)) {
                    mkdir($disk_path_high, 0755, true);
                }
                if(copy($local_path, $disk_path_file_high)) {
                    // High res image
                    $proc_output_high = Self::compress($disk_path_file_high, $disk_path_file_high, "1280");
                    if($proc_output_high === true || $proc_output_high === null) {
                        // Low res image
                        if (!file_exists($disk_path_low)) {
                            mkdir($disk_path_low, 0755, true);
                        }
                        $proc_output_low = Self::compress($disk_path_file_high, $disk_path_file_low, "469");
                        if($proc_output_low === false) {
                            return response("Error compressing low res file", 400)->header('Content-Type', 'text/plain');
                        }
                        // Move file directly if no processing occurred
                        else if ($proc_output_low === null) {
                            if(!copy($local_path, $disk_path_file_low)){
                                return response("Error moving low res file to disk", 400)->header('Content-Type', 'text/plain');
                            }
                        }

                        // Delete temp file
                        if(Storage::disk('public')->delete($file_name)){
                            $image = new \ReflectionClass("App\Listing\\$model");
                            $image = $image->newInstance();
                            $image->image_listing_id = $listing_id;
                            $image->image_path = "$low_res_path/$file_name";
                            $image->image_myListings = "$high_res_path/$file_name";
                            $image->listing_source = '';
                            $image->image_date = date('Y-m-d');
                            $image->save();

                            // File uploaded successfully
                            return response($image->image_id, 200)->header('Content-Type', 'text/plain');

                        } else {
                            return response("Error deleting temporary file", 400)->header('Content-Type', 'text/plain');
                        }

                    } else if($proc_output_high === false) {
                        return response("Error compressing high res file", 400)->header('Content-Type', 'text/plain');

                    }

                } else {
                    return response("Error moving high res file to disk", 400)->header('Content-Type', 'text/plain');
                }

            } else if(env('APP_ENV') == 'local') {
                echo "Local upload disabled";
            } else {
                return response("Invalid environment", 400)->header('Content-Type', 'text/plain');
            }
        }
        return response("Error storing temporary file", 400)->header('Content-Type', 'text/plain');
    }

    public static function compress($source_path, $target_path, $size)
    {
        list($width, $height) = getimagesize($source_path);

        chmod($source_path, 0777);

        $orientation = $width > $height ? "landscape" : "portrait";

        if ( ($orientation == "landscape" && $width > $size) || ($orientation == "portrait" && $height > $size) ) {
            $exit_status = @shell_exec("convert {$source_path} -auto-orient -resize {$size}x{$size} -strip -quality 92 {$target_path} 2>&1; echo $?");
            $exit_status = trim(str_replace("\r\n","",$exit_status));
            return $exit_status == "0" ? true : false;
        }
        return null;
    }

    public static function compressHighLow($source_path_high, $target_path_high, $source_path_low, $target_path_low, $size_high, $size_low)
    {
        chmod($source_path_high, 0777);
        chmod($source_path_low, 0777);
        @shell_exec("convert {$source_path_high} -auto-orient -resize {$size_high}x{$size_high} -strip -quality 92 {$target_path_high}");
        @shell_exec("convert {$source_path_low} -auto-orient -resize {$size_low}x{$size_low} -strip -quality 92 {$target_path_low}");
    }
}
