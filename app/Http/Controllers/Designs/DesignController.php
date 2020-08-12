<?php

namespace App\Http\Controllers\Designs;

use App\Models\Design;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => ['required', 'unique:designs,title,' . $id],
            'description' => ['required', 'string', 'min:20', 'max:140'],
        ]);

        $design = Design::find($id);

        $design->update([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'is_live' => ! $design->upload_successful ? false : $request->is_live
        ]);

        return response()->json($design, 200);
    }
}
