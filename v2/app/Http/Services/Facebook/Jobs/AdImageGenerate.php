<?php

namespace App\Http\Services\Facebook\Jobs;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Storage;


class AdImageGenerate
{
    
    public static function generate($data, $type) {
        if($type == 'multiple'){
            $process = new Process(dirname($_SERVER['DOCUMENT_ROOT'])."/python-scripts/virtualenv/bin/python3 ".dirname($_SERVER['DOCUMENT_ROOT'])."/python-scripts/add_maker_v3.py ".implode(',', $data));
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $img_path = preg_replace('/\r\n|\r|\n/', '', $process->getOutput());
        }elseif($type == 'single'){ 
            $file = $data->store('/');
            $img_path = Storage::disk('public')->path($file);
        }

        return $img_path;
    }

}

?>