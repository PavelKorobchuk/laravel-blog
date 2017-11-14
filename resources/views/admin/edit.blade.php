@extends('layouts.master')

@section('content')
    <div class="row-post_item">
    @include('partials.errors')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admin.update') }}" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="image">Header Picture</label>
                    <input type="file" id="image" name="input_img" />
                </div>
                <img src="{{asset('images')}}/{{ $post->image }}" alt="" width="400">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input
                            type="text"
                            class="form-control"
                            id="title"
                            name="title"
                            value="{{ $post->title }}">
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control" id="content" name="content" cols="30" rows="10">
                        {{ $post->content }}
                    </textarea>
                </div>
                @foreach($tags as $tag)
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ $post->tags->contains($tag->id) ? 'checked' : '' }}> {{ $tag->name }}
                        </label>
                    </div>
                @endforeach
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $postId }}">
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    </div>
    <script src="//cdn.ckeditor.com/4.7.3/standard/ckeditor.js"></script>
    <script src="{{asset('ckfinder/ckfinder.js')}}"></script>
    <script>
        var editor = CKEDITOR.replace( 'content', {
            extraAllowedContent:  'img[alt,border,width,height,align,vspace,hspace,!src];'
        });
        CKFinder.setupCKEditor( editor, null, { type: 'Files', currentFolder: '../blog_files/' } );
    </script>
@endsection