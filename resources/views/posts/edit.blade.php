@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
<form action="{{ route('post.update',$post->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="mb-3">
        <label for="title" class="form-label text-secondary">Title</label>
        <input type="text" name="title" id="title" placeholder="Enter title here" value="{{ old('title',$post->title)}}" class="form-control" autofocus>
        {{-- Error --}}
        @error('title')
            <div class="text-danger small">{{ $message }}</div>                
        @enderror
        <div class="mb-3">
            <label for="body" class="form-label text-secondary">Body</label>
            {{-- textarea[name placeholder] --}}
            <textarea name="body" id="body" rows="5" class="form-control" placeholder="Start writing">{{ old('body',$post->body)}}</textarea>
            {{-- Error --}}
            @error('body')
            <div class="text-danger small">{{ $message }}</div>                
            @enderror
        </div>
        <div class="row">
            <div class="col-6">
                <label for="image" class="form-label text-secondary">Image</label>
                <img src="{{ asset('storage/image/'.$post->image)}}" alt="{{ $post->imgae }}" class="w-100 img-thumbnail">
                <input type="file" name="image" id="image" class="form-control" aria-describedby="image-info">
                <div class="format-text" id="image-info">
                    Acceptable formats are jpeg, jpg, png, gif only. <br>
                    Maximum file size is 1048kb.
                </div>
                {{-- Error --}}
                @error('image')
                <div class="text-danger small">{{ $message }}</div>                
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-warning px-5">Save</button>
    </div>
</form>

    
@endsection