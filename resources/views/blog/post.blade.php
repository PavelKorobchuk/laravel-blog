@extends('layouts.master')

@section('content')
    <div class="post-body">
        <div class="row-post_item">
            <div class="no-pad">
                <div class="row row-post_item-image">
                    <img src="{{asset('images')}}/{{$post->image}}" alt="">
                </div>
            </div>
            <div class="row-pad">
                <div class="row">
                    <div class="col-md-12">
                        <p class="quote">{{ $post->title }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p>{{ count($post->likes) }} Likes |
                            <a href="{{ route('blog.post.like', ['id' => $post->id]) }}">Like</a></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p>{!! $post->content !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection