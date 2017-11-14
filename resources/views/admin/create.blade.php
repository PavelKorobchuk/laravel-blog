@extends('layouts.master')

@section('content')
    @include('partials.errors')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admin.create') }}" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="image">Header Picture</label>
                    <input type="file" id="image" name="input_img" />
                </div>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title">
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control" id="content" name="content" cols="30" rows="10">

                    </textarea>
                </div>
                @foreach($tags as $tag)
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"> {{ $tag->name }}
                        </label>
                    </div>
                @endforeach
                {{ csrf_field() }}
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
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