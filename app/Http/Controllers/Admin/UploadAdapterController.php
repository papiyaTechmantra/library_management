<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;

class UploadAdapterController extends Controller
{
    public function upload(Request $request)
    {
        // image upload
        if (isset($request->upload)) {
            $fileUpload2 = fileUpload($request->upload, 'ckcontent-uploads');

            // $category->upload_small = $fileUpload2['file'][0];
            // $category->upload_medium = $fileUpload2['file'][1];
            // $category->upload_large = $fileUpload2['file'][2];
        }

        return response()->json([$fileUpload2['file'][2]]);
    }
}
