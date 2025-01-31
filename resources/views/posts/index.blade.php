@extends('layouts.app')

@section('title', 'Home')

@section('content')
    @forelse ($all_posts as $post)
        <div class="mat-2 border border-2 rounded p-4">
            <a href="{{ route('post.show',$post->id)}}">
                <h2 class="h4">{{ $post->title}}</h2>
            </a>
            @if(Auth::user()->id != $post->user_id)
            <a href="{{route('profile.other',$post->user->id)}}" class="h6 text-secondary">{{$post->user->name}}</a>
            @else
            <h3 class="h6 text-secondary">{{$post->user->name}}</h3>
            @endif
            
            @if (strlen($post->body)>80)
            <p class="fw-light mb-0">{{Str::limit($post->body,80)}}<br>
                <a href="{{route('post.show',$post->id)}}">Read more</a></p>
            @else
            <p class="fw-light mb-0">{{$post->body}}</p>
            @endif

            <img src="{{ asset('storage/image/'.$post->image)}}" alt="{{ $post->image }}" class="w-50 shadow rounded">

            {{-- Action Buttons --}}
            @if (Auth::user()->id == $post->user_id)
                <div class="mt-2 text-end">
                    <a href="{{ route('post.edit',$post->id)}}" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-pen"></i> Edit
                    </a>
                    <form action="{{ route('post.destroy',$post->id)}}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fa-solid fa-trash-can"></i> Delete
                        </button>
                    </form>
                </div>
            @endif
        </div>
        
    @empty
        
        <div class="text-center" style="margin-top: 100px;">
            <h2 class="text-secondary">No Posts yet</h2>
            <a href="{{ route('post.create')}}" class="text-decoration-none">Create a new post</a>
        </div>
    @endforelse
@endsection