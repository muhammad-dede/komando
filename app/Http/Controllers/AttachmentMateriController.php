<?php 

namespace App\Http\Controllers;

use App\AttachmentMateri;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class AttachmentMateriController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    
  }

  public function getAttachment($id)
  {
//        $entry = UploadData::where('filename', '=', $filename)->firstOrFail();
    $atch = AttachmentMateri::findOrFail($id);



//    dd($atch->materi->business_area);

    $file = Storage::get($atch->materi->business_area . '/attachment_materi/' . $atch->filename);

//    dd($file);

    return (new Response($file, 200))
        ->header('Content-Type', 'pdf')
        ->header('Content-Disposition', 'attachment; filename="' . $atch->filename . '"');

  }
  
}

?>
