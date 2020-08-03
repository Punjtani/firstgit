<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Progress_table;

class progresscontroller extends Controller
{
    public function fileUpload()
    {
        return view('course/progressBar');
    }


    public function fileStore(Request $request)
    {
        // $request->validate([
        // 	'name' => 'required',
        //     'file'  => 'mimetypes:video/quicktime,video/mp4,video/x-flv,video/MP2T,video/3gpp,video/x-msvideo,video/x-ms-wmv'
        // ]);


    
//         if($validator->fails()) {
//     return Redirect::back()->withErrors($validator);
// }
        $i=0;

    	foreach($request->file('file') as $file)
    	{
       $fileName = time().'.'.$file->getClientOriginalExtension();
 
       $file->move(public_path('images'), $fileName);
       $fileupload = new Progress_table;
        $fileupload->name = $request->name[$i];
        $fileupload->media = $fileName;
        $fileupload->description = $request->description[$i];
        $fileupload->save();
        $i++;
    	}
 
        // return response()->json(['success'=>'File Uploaded Successfully']);
    }
}
