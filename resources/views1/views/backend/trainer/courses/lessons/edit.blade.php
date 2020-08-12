@extends('backend.Trainer.layouts.app')
@section('customSection')

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
    <div class="container mt-5">
        <form  id="course_form" method="POST" action="{{route('update_lesson')}}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title">Edit Lesson</h4>
            </div>

{{--            <div class="row">--}}
{{--                <div class="col-12">--}}
{{--                    <div class="form-group select-dropdown">--}}
{{--                        <label>Category</label>--}}
{{--                        <select class="form-control" name="category_id" required>--}}
{{--                            @foreach($categories as $c)--}}
{{--                                <option value="{{$c->id}}">{{$c->name}}</option>--}}

{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}
            <input type="hidden" name="course_id" value="{{$data->course_id}}">
            <input type="hidden" name="id" value="{{$data->id}}">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Lesson Number*</label>
                        <input type="number" min="0" class="form-control" value="{{$data->lesson_no}}" name="l_num" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Lesson Name*</label>
                        <input type="text" class="form-control" value="{{$data->title}}" name="name" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="btn btn-primary btn-sm float-left">
                        <span>Choose Video*</span>
                        <input type="file" name="video" accept="video/*" required>
                        <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow=""
                             aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            0%
                        </div>
                    </div>
                        <div id="success">

                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="btn btn-primary btn-sm float-left">
                        <span>Choose Document (Optional)</span>
                        <input type="file" name="document" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>Description*</label>
                        <textarea id="description"  class="form-control description"  name="description" required>{{$data->description}}</textarea>
                    </div>
                </div>
            </div>



            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                <input type="submit" class="btn btn-success cat" id="cat" value="Update">
            </div>
        </form>
    </div>
@endsection
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description');
</script>
<script>
    $(document).ready(function(){

        $('form').ajaxForm({
            beforeSend:function(){
                $('#success').empty();
            },
            uploadProgress:function(event, position, total, percentComplete)
            {
                $('.progress-bar').text(percentComplete + '%');
                $('.progress-bar').css('width', percentComplete + '%');
            },
            success:function(data)
            {
                if(data.errors)
                {
                    $('.progress-bar').text('0%');
                    $('.progress-bar').css('width', '0%');
                    $('#success').html('<span class="text-danger"><b>'+data.errors+'</b></span>');
                }
                if(data.success)
                {
                    $('.progress-bar').text('Uploaded');
                    $('.progress-bar').css('width', '100%');
                    $('#success').html('<span class="text-success"><b>'+data.success+'</b></span><br /><br />');
                    $('#success').append(data.image);
                }
            }
        });

    });
</script>
