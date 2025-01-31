@extends('layouts.app')

@section('title', 'Show Post')

@section('content')
    <div class="mt-2 border border-2 rounded p-4 shadow-sm">
        <h2 class="h4">{{ $post->title }}</h2>
        @if(Auth::user()->id == $post->user_id)
        <a href="{{route('profile.show')}}" class="h6 text-secondary">{{$post->user->name}}</a>
        @else
        <a href="{{route('profile.other',$post->user->id)}}" class="h6 text-secondary">{{$post->user->name}}</a>
        @endif

        <p class="fw-light">{{ $post->body }}</p>

        <img src="{{ asset('storage/image/'.$post->image)}}" alt="{{ $post->image }}" class="w-100 shadow rounded">

        <form action="{{ route('comment.store',$post->id)}}" method="post">
            @csrf
            <div class="input-group mt-5">
                <input type="text" name="comment" id="comment" class="form-control" placeholder="Add a comment..." value="{{ old('comment')}}">
                <button type="submit" class="bnt btn-outline-secondary btn-sm">Post</button>
            </div>
            {{-- Error --}}
            @error('comment')
                <div class="text-danger small">{{$message}}</div>

            @enderror
        </form>

        {{-- If the post has comments, show those comments --}}
        @if ($post->comments)
            <div class="mt-2 mb-5">
                @foreach ($post->comments as $comment)
                    <div class="row p-2">
                        <div class="col-10">

                            @if(Auth::user()->id == $comment->user_id)
                             <a href="{{route('profile.show')}}" class="h6 text-secondary">{{$comment->user->name}}</a>
                            @else
                            <a href="{{route('profile.other',$comment->user->id)}}" class="h6 text-secondary">{{$comment->user->name}}</a>
                            @endif

                            &nbsp;
                            <span class="small text-muted">{{$comment->created_at}}</span>
                            <p class="mb-0">{{ $comment->body }}</p>
                        </div>
                        <div class="col-2 text-end">
                            {{-- Show a Delete button if the Auth user is the owner of the comment --}}
                            @if(Auth::user()->id === $comment->user_id || Auth::user()->id == $post->user_id)
                            <form action="{{ route('comment.destroy',$comment->id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete comment">
                                <i class="fa-solid fa-trash-can"></i> Delete
                                 </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    
                @endforeach
            </div>
        @endif
    </div>
@endsection

