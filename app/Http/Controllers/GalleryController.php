<?php

namespace App\Http\Controllers;

use App\CompanyCode;
use App\GalleryCoc;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;
use Mockery\CountValidator\Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class GalleryController extends Controller
{

    public function getFoto($id)
    {
//        $entry = UploadData::where('filename', '=', $filename)->firstOrFail();
        $gallery = GalleryCoc::findOrFail($id);
        $file = Storage::get($gallery->folder. '/' . $gallery->filename);

        return (new Response($file, 200))
            ->header('Content-Type', 'image/jpeg')
            ->header('Content-Disposition', 'attachment; filename="' . $gallery->filename. '"');

    }

    public function getFotoDashboard($id)
    {
//        $entry = UploadData::where('filename', '=', $filename)->firstOrFail();
        $gallery = GalleryCoc::findOrFail($id);
        $file = Storage::get($gallery->folder. '/' . $gallery->filename);

        $img = Image::make($file);

        $img->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        return $img->response('jpg');

//        return (new Response($file, 200))
//            ->header('Content-Type', 'image/jpeg')
//            ->header('Content-Disposition', 'attachment; filename="' . $gallery->filename. '"');

    }

    public function massConvertGallery(){
        $foto_list = GalleryCoc::orderBy('folder', 'asc')->get();
        foreach ($foto_list as $foto) {
            echo $foto->id . ' | ' . $foto->folder . ' | ' . $foto->filename;

            try {
                // cek file
                if (Storage::disk('ftp_plnpusat')->exists('app/' . $foto->folder . '/' . $foto->filename)) {
                    // jika ada

//                echo 'ada<br>';

                    $file_server = Storage::disk('ftp_plnpusat')->get('app/' . $foto->folder . '/' . $foto->filename);

                    $extension = explode('.', $foto->filename);
                    $extension = strtolower($extension[count($extension) - 1]);
                    $filename = $foto->filename;

                    if (!Storage::disk('ftp_plnpusat')->exists('app/'.$foto->folder.'/thumb/' . $filename)) {

                        // open and resize an image file
                        $img = Image::make($file_server);
                        $img->resize(800, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
//                        $img->save(storage_path('app/'.$foto->folder.'/thumb/' . $filename));
                        Storage::disk('ftp_plnpusat')->put('app/'.$foto->folder.'/thumb/' . $filename, $img->response($extension));
//                        $img2 = Image::make($file_server);
//                        $img2->save(storage_path('app/foto-conv/' . $filename));

//                        $user->foto_tmp = $filename;
//                        $user->save();

                        // make thumbnail
//                        Storage::disk('ftp_plnpusat')->put('app/foto-thumb-conv/' . $filename, $img->response($extension));
                        // copy ke foto
//                        Storage::disk('ftp_plnpusat')->put('app/foto-conv/' . $filename, $img2->response($extension));

                        echo ' | ' . 'app/'.$foto->folder.'/thumb/' . $filename . '<br>';

                    } else {
                        echo 'ERROR : FILE EXIST <br>';
                    }
                } else {
                    // jika tidak ada reset foto
//                $user->foto = '';
//                $user->save();
                    echo 'ERROR : FILE NOT FOUND <br>';
                }
            } catch (FileNotFoundException $e) {
                echo 'ERROR : ' . $e->getMessage() . ' <br>';
                continue;
            } catch (NotReadableException $e) {
                echo 'ERROR : ' . $e->getMessage() . ' <br>';
                continue;
            } catch (Exception $e) {
                echo 'ERROR : ' . $e->getMessage() . ' <br>';
                continue;
            }
        }

    }
}
