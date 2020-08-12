@extends('backend.admin.layouts.app')
@section('customSection')
    <div class="container mt-5">
        <form  id="course_form" method="POST" action="{{route('update_settings')}}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
            <h4 class="modal-title">Settings</h4>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Facebook link</label>
                        <input type="url" class="form-control" name="fb_link" value="{{$data->fb_link}}" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Instagram link</label>
                        <input type="url" class="form-control" name="insta_link" value="{{$data->insta_link}}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Twitter Link</label>
                        <input type="url" class="form-control" name="twitter_link" value="{{$data->twitter_link}}" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Linkedin Link</label>
                        <input type="url" class="form-control" name="in_link" value="{{$data->in_link}}" required>
                    </div>
                </div>

            </div>
            <div class="row">

            <div class="col-6">
                <div class="btn btn-primary btn-sm float-left">
                    <span>About Image</span>
                    <input type="file" name="image" accept="image/*"  required>
                </div>
            </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label> About Us Description</label>
                        <textarea id="description"  class="form-control description" name="description" required>{!! $data->about_us_description !!}</textarea>
                    </div>
                </div>
            </div>



            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                <input type="submit" class="btn btn-success cat" id="cat">
            </div>
        </form>
    </div>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description');
    </script>
    <script>
        $(".chosen").chosen();
    </script>

@endsection

