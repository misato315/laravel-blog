<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private $user;
    const LOCAL_STORAGE_FOLDER = 'avatars/';

    public function __construct(User $user_model){
        $this->user = $user_model;
    }

    public function show(){
        $user = $this->user->findOrFail(Auth::user()->id);
        return view('users.show')->with('user',$user);
    }

    public function other($id){
        $user = $this->user->findOrFail($id);
        return view('users.show')->with('user',$user);
    }

    #This will open the Edit Profile page.
    public function edit(){
        $user = $this->user->findOrFail(Auth::user()->id);
        return view('users.edit')->with('user',$user);
    }

    public function update(Request $request){
        $request -> validate([
            'avatar' =>'mimes:jpeg,jpg,png,gif|max:1048',
            'name' => 'required|max:50',
            'email'=> 'required|email|max:50|unique:users,email,'.Auth::user()->id
        ]);

        $user = $this->user->findOrFail(Auth::user()->id);
        $user->name =$request->name;
        $user->email = $request->email;

        #If the user uploaded an avatar...
        if($request->avatar){
            #If the user currently has an avatar, delete it first from local storage
            if($user->avatar){
                $this->deleteAvatar($user->avatar);
            }

            #Save the new image
            $user->avatar = $this->saveAvatar($request->avatar);
        }

        $user->save();

        return redirect()->route('profile.show');
    }

    private function saveAvatar($avatar){
        $avatar_name = time().".".$avatar->extension();
        $avatar->storeAs(self::LOCAL_STORAGE_FOLDER,$avatar_name);

        return $avatar_name;
    }

    private function deleteAvatar($avatar){
        $avatar_path = self::LOCAL_STORAGE_FOLDER.$avatar;

        if(Storage::disk('public')->exists($avatar_path)){
            Storage::disk('public')->delete($avatar_path);
        }
    }

    

}
