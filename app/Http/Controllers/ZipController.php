<?php
   
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use ZipArchive;
use File;

class ZipController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadZip()
    {

            // Define Dir Folder
            $public_dir='../storage/app/submission_file_iprc/2018/January-June';
            // Zip File Name
            $zipFileName = 'AllDocuments.zip';
            // Create ZipArchive Obj
            $zip = new ZipArchive;
            if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
                // Add Multiple file
                $files = File::allFiles($public_dir); 
                foreach($files as $file) {
                    $zip->addFile($file['path'], $file['name']);
                } 
                // Close ZipArchive     
                $zip->close();
            }
            // Set Header
            $headers = array(
                'Content-Type' => 'application/octet-stream',
            );
            $filetopath=$public_dir.'/'.$zipFileName;
            // Create Download Response
            if(file_exists($filetopath)){
                return response()->download($filetopath,$zipFileName,$headers);
            }
        
        return view('createZip');
    }
}