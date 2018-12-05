<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\News;
use App\Http\Resources\News as NewsResource;
use View;
use Image;
Use Redirect;
use Yajra\DataTables\Facades\DataTables;
use DB;

class NewsController extends Controller
{
    public function showNewsForm()
    {
        // this function is to add new form in admin 
        return view('addNews');
    }

    public function index( Request $request)
    {
        // this function is for api to get news from database
    	
        $count = $request->count; // this variable is to show 'number of entries' in one page
    	$page = $request->page;    // this variable is for page number

    	if( $count == null || $count < 0)
    	{	
    		$count = 0;
    	}
    	if($page == null || $page < 2)
    	{
    		$page = 0;
    	}
    	$news = News::skip($count*$page)->take($count)->get();
        
        if($count == null && $page == null)
        {
        	$news = News::get();
        }
        $collection = NewsResource::collection($news);
        $result = $collection->sortBy('updated_at', SORT_REGULAR, true);
    
        // Return a collection of $news with pagination
        return $result;
    }
    public function create(Request $request)
    {

        $news = new News;
        $news->title = $request->title;
        $news->description = $request->description;

        $image = $request->file('image')->getRealPath(); // get real address for image file
        $filename = time() . ".jpg"; // converting image with unique name
        
        Image::make($image)->resize(300,300)->save( public_path('/admin/uploads/images/'. $filename)); // uploading image in '/admin/uploads/images/' of our public folder.

        $news->image = $filename;
         
        $news->save();
        toastr()->success('News Added Successfully!',' ',['showDuration'=>500,'closeButton'=>true,'progressBar'=>false,'positionClass'=> 'toast-top-right']);
        return redirect('/newsTable');
    }
    public function newsTableAjax()
    {
        //this function is to render dataTables with an ajax call from 'master.blade.php'

        $news = News::get();
        return Datatables::of($news)->addColumn('action', function($row) {
            $html = '<div class="row"><a href='.url('/editNews/'.$row->id).'><i class="fa fa-lg fa-pencil" aria-hidden="true"></i></a>';

            $html .= '<a href='.url('/deleteNews/'.$row->id).' style="padding-left:10px;cursor:pointer;" onclick="return confirm('."'Are you sure you want to delete this item?'".');"><i class="fa fa-lg fa-archive" style="color:#007bff;"></i> </a></div>';
                        return $html;
                    })->editColumn('image',function($row){
                        $html='<img height="40px" width="40px" style="border-radius: 50%;"  src="/admin/uploads/images/'.$row->image.'" >';
                        return $html;
                    })->rawColumns(['action','image'])->make(true);
    }
    public function newsTable(){
        return view('newsTable');
    }
    public function showEditNews($id){
        $news = News::findOrFail($id);
        return view('editNews',['news'=>$news]);
    }
    public function editNews($id,Request $request)
    {
        $news = News::find($id);
        $news->title  = $request->title;
        if($request->image != null)
        {
            $image = $request->file('image')->getRealPath();
            $filename = time() . ".jpg";
            Image::make($image)->resize(300,300)->save( public_path('/admin/uploads/images/'. $filename));
            $news->image = $filename;
        }
        
        $news->description = $request->description;
        $news->save();

        toastr()->success('News Edited Successfully!',' ',['showDuration'=>500,'closeButton'=>true,'progressBar'=>false,'positionClass'=> 'toast-top-right']); 

        return redirect('/newsTable');
    } 

    public function deleteNews($id){

        $news = News::find($id);
        $news->delete();
        toastr()->success('News Deleted Successfully!',' ',['showDuration'=>500,'closeButton'=>true,'progressBar'=>false,'positionClass'=> 'toast-top-right']);
        return redirect('/newsTable');

    }
}
