@extends('backend.admin.layouts.app')
@section('customSection')
    <div class="container mt-5">
    <form  id="course_form" method="POST" action="{{route('update_course')}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{$course->id}}">
        <div class="modal-header">
            <h4 class="modal-title">Edit Course</h4>
        </div>

            <div class="row">
            <div class="col-12">
            <div class="form-group select-dropdown">
                <label>Category</label>
                <select class="form-control chosen chosen-height" name="category_id[]" multiple required>
                    @foreach($categories as $c)
                        
                        <option value="{{$c->id}}" @if(isset($cate_id[$check])) @if($cate_id[$check] == $c->id) @php $check++ @endphp selected @endif @endif>{{$c->name}}</option>
                    
                    @endforeach
                </select>
            </div>
            </div>

            </div>
            <div class="row">
            <div class="col-6">
            <div class="form-group">
                <label>Course Name</label>
                <input type="text" class="form-control" name="name" value="{{$course->name}}" required>
            </div>
            </div>
            <div class="col-6">
            <div class="form-group">
                <label>Duration</label>
                <input type="text" class="form-control" name="duration" value="{{$course->duration}}" required>
            </div>
            </div>
            </div>

            <div class="row">
            <div class="col-6">
            <div class="form-group">
                <label>Price</label>
                <input type="number" class="form-control" name="price" value="{{$course->price}}" required>
            </div>
            </div>
            <div class="col-6">
            <div class="btn btn-primary btn-sm float-left">
                <span>Choose file</span>
                <input type="file" name="image" accept="image/*" >
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-12">
            <div class="form-group">
                <label>Description</label>
                <textarea id="description"  class="form-control" name="description" required>{{$course->description}}</textarea>
            </div>
            </div>
            </div>



        <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
            <input type="submit" class="btn btn-success cat" id="cat" value="Update">
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
