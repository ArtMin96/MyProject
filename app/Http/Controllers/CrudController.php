<?php
/*
 * DEVELOPED BY DINEURON.COM
 * NeuronCart
 *  */
namespace App\Http\Controllers;

use App\Models\Image;
use App\Role\UserRole;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image as ImageProcessing;

class CrudController extends Controller
{

    public function __construct()
    {
        $this->middleware('check.role:'.UserRole::ROLE_ADMIN)->only('destroy');

    }

    /**
     * @param $parameters
     * @return mixed
     */
    protected function validateRequest($parameters)
    {
        return request()->validate($parameters);
    }

    /**
     * @param $row
     * @param $name
     * @param $path
     */
    protected function storeFile($row,$name,$path)
    {
        if(request()->has($name)) {
        $imagePath = request()->$name->store($path,'public');
        $thumbnail = ImageProcessing::make(storage_path("app/public/" . $imagePath));
        $thumbnail->resize(400, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $thumbnailFilename = $thumbnail->filename.'_sm.'.$thumbnail->extension;
        $filePath =  $path .'/'. $thumbnailFilename;
        $thumbnail->save(storage_path("app/public/" .$filePath ));
        if (request()->has($name) and request()->$name != null) {
            $row->update([
                $name =>'/storage/'.$imagePath
            ]);
        }
    }
    }

    /**
     * @param $img_name
     * @param $name
     */
    protected function deleteFile($img_name,$name)
    {

        if((request()->has($name) and request()->$name !=null) or request()->_method == 'DELETE')
        {
            if(request()->has($name) && isset($img_name)) {
                $path = str_replace('/storage/','',$img_name);
                $thumbnail = explode('.',$path);
                if(isset($thumbnail[0]) && isset($thumbnail[1])) {
                    $thumbnailPath = $thumbnail[0].'_sm.'.$thumbnail[1];
                    Storage::disk('public')->delete($thumbnailPath);
                }
                Storage::disk('public')->delete($path);
            }
        }
    }

    protected function allColumns ($class_name)
    {

      $columns =  Schema::getColumnListing(Str::plural(Str::snake($class_name)));

      $columns = array_flip($columns);

        unset($columns['id']);
        if (array_key_exists('created_at',$columns))
        {
            unset($columns['created_at']);
        }
        if (array_key_exists('updated_at',$columns))
        {
            unset($columns['updated_at']);
        }
      $columns= array_map(function() { return ''; }, $columns);

        return $columns;
    }

    /**
     * @param $key
     * @param $row
     * @param $path
     */
    protected  function  galleryAdd($key,$row,$path){
        if(request()->hasFile($key))
        {
            $row->images()->create(['path'=>'/storage/'.request()->file->store('/'.$path,'public')]);
        }
    }

    /**
     * @param $key
     * @param $row
     */
    protected  function  galleryRemove($key,$row){
        if(request($key)){

            $path = str_replace('/storage/','',$row->first()->path);

            Storage::disk('public')->delete($path);

            $row->delete();

        }
    }

}
