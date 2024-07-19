<?php 

namespace App\Http\Controllers;

use App\Attachment;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
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
    $atch = Attachment::findOrFail($id);
    
    $file = Storage::get($atch->coc->business_area . '/attachment/' . $atch->filename);

    return (new Response($file, 200))
        ->header('Content-Type', 'pdf')
        ->header('Content-Disposition', 'attachment; filename="' . $atch->filename . '"');

  }
  
}

?>
