<?php

namespace App\Http\Controllers\Liquid;

use App\Http\Controllers\Controller;
use Spatie\MediaLibrary\Media;

class MediaController extends Controller
{
    public function destroy(Media $media)
    {
        $media->delete();

        return redirect()->back()->withSuccess('Media berhasil dihapus');
    }
}
