<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


use App\Models\Post;

class PostController extends Controller
{
    private $post;
    const LOCAL_STRAGE_FOLDER = 'image/';

    public function __construct(Post $post_model){
        $this -> post = $post_model;
    }

    public function index(){
        $all_posts = $this->post->latest()->get();
        return view('posts.index')->with('all_posts', $all_posts);
    }

    public function show($id){
        $post = $this->post->findOrFail($id);
        return view('posts.show')->with('post',$post);
    }

    public function create(){
        return view('posts.create');
    }

    public function store(Request $request){
        #1.Validate the request
        $request->validate([
            'title'=>'required|max:50',
            'body'=>'required|max:1000',
            'image'=>'required|mimes:jpeg,jpg,png,gif|max:1048'
        ]);
        #2.Save the form data to posts tables
        $this->post->user_id = Auth::user()->id;
        $this->post->title= $request->title;
        $this->post->body= $request->body;
        $this->post->image= $this->saveImage($request->image);
        $this->post->save();

        #3.Redirect to homepage
        return redirect()->route('index');
    }

    private function saveImage($image){
        //Change the name of the image to CURRENT TIME to avoid overwriting.
        $image_name = time().".".$image->extension();//sample of extension: jpeg,jpg,png,and gif

        //Save the image to strage/app/public/images/
        $image->storeAs(self::LOCAL_STRAGE_FOLDER,$image_name);

        return $image_name;
    }


    public function edit($id){
        $post = $this->post->findOrFail($id);

        if($post->user->id != Auth::user()->id){
            return redirect()->back();
        }
        return view('posts.edit')->with('post',$post);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title'=>'required|max:50',
            'body'=>'required|max:1000',
            'image'=>'required|mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        $post = $this->post->findOrFail($id);
        $post->title = $request->title;
        $post->body = $request->body;

        #If there is new image...
        if($request->image){
            #delete the previous image from the local storage
            $this->deleteImage($post->iamge);
            //Sample: $this->deleteImage('1834355234.jpg');

            #Save the new image
            $post->image = $this->saveImage($request->image);
            //Sample: $post->image = '3437567582.jpg'; new image
        }

        $post->save();

        return redirect()->route('post.show',$id);
    }

    private function deleteImage($image){
        $image_path = self::LOCAL_STRAGE_FOLDER.$image;
        //Sample: $image_path = 'iamges/1232432.jpg';

        if(Storage::disk('public')->exists($image_path)){
            Storage::disk('public')->delete($image_path);
        }
    }

    public function destroy($id){

        $post = $this->post->findOrFail($id);
        $this->deleteImage($post->image);
        $post->delete();

        return redirect()->back();
    }
}
