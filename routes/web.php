<?php

#クラスをインポートするための宣言
use Illuminate\Support\Facades\Route;
use App\HTTP\Controllers\PostController;
use App\HTTP\Controllers\CommentController;
use App\HTTP\Controllers\UserController;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
//Laravelの標準的な認証（ログイン、登録、パスワードリセット）ルートを自動的に生成します。これにより、ユーザーはログイン、登録、パスワードリセットなどのページにアクセスできるようになる。

//All routes inside may be accessed by logged in/authenticated users only
//認証されたユーザーのみがアクセスできるルートをまとめたグループ
Route::group(['middleware'=>'auth'],function(){
    //'auth'ミドルウェアを指定することで、ユーザーがログインしていない場合、アクセスをリダイレクト（ログインページに）するように制御
    Route::get('/',[PostController::class,'index'])->name('index');
    ///（ルートURL）にアクセスがあった場合、PostControllerのindexメソッドを実行。これにより、投稿の一覧表示ページなどが表示される

    #POST
    Route::group(['prefix'=>'post','as'=>'post.'], function(){
        //prefix =>'post': このグループ内のすべてのURLは/postで始まる
        //as => 'post.': ルートに名前を付ける際にpost.createのような名前空間を自動で追加。この名前空間は、route('post.create')のように使う
        Route::get('/create/',[PostController::class,'create'])->name('create');
        Route::post('/store',[PostController::class,'store'])->name('store');
        Route::get('/{id}/show',[PostController::class,'show'])->name('show');
        Route::get('/{id}/edit',[PostController::class,'edit'])->name('edit');
        Route::patch('/{id}/update',[PostController::class,'update'])->name('update');
        //PATCHメソッドは、リソースの一部を更新するためのリクエストに使われます。通常、部分的な更新を行いたい場合に使用される。ここでは投稿の情報（タイトルや内容など）
        Route::delete('/{id}/destroy',[PostController::class,'destroy'])->name('destroy');
    });

    #COMMENTS
    Route::group(['prefix'=>'comment','as'=>'comment.'],function(){
    //prefix =>'comment': このグループ内のすべてのURLは/commentで始まる
    //as =>'comment.': ルートに名前を付ける際にcomment.createのような名前空間を自動で追加
        Route::post('/{post_id}/store',[CommentController::class,'store'])->name('store');
        Route::delete('/{id}/destroy',[CommentController::class,'destroy'])->name('destroy');
        Route::get('/{id}/auther',[CommentController::class,'auther'])->name('auther');
    });

    #USERS
    Route::group(['prefix'=>'profile','as'=>'profile.'],function(){
    //prefix =>'profile': このグループ内のすべてのURLは/profileで始まる
    //as =>'profile.': ルートに名前を付ける際にprofile.createのような名前空間を自動で追加
        Route::get('/',[UserController::class,'show'])->name('show');
        Route::get('/edit',[UserController::class,'edit'])->name('edit');
        Route::patch('/update',[UserController::class,'update'])->name('update');
        Route::get('/{id}/other',[UserController::class,'other'])->name('other');

    });
});


