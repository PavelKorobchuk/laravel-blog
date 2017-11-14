@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
        </div>
    </div>
    @foreach($posts as $post)
    <article class="row row-post_item">
        <div class="col-md-3 row-post_item__image">
            <div class="image-wrapper" style="background-image: url('{{asset('images')}}/{{$post->image}}')"></div>
        </div>
        <div class="col-md-9 row-post_item__content">
                <h1 class="post-title">{{ $post->title }}</h1>
                <p style="font-weight: bold">
                    @foreach($post->tags as $tag)
                        - {{ $tag->name }} -
                    @endforeach
                </p>
                <div class="article-text">
                    <p>{{substr(strip_tags($post->content), 0, 300)}}{{strlen(strip_tags($post->content)) > 300 ? "..." : ""}}</p>
                </div>
                <p><a class="read-more" href="{{ route('blog.post', ['id' => $post->id]) }}">Read more...</a></p>
        </div>
    </article>
    @endforeach
    <div class="row">
        <div class="col-md-12 text-center">
            {{ $posts->links() }}
        </div>
    </div>
@endsection