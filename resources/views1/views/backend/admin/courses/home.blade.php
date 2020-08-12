@extends('backend.admin.layouts.app')
@section('customSection')
<style>
#showform{
    display:none;
}
</style>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">



<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>


    {{--    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>--}}
    {{--    <script type="text/javascript" src="//cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>--}}
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-primary">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h2>Manage <b>Courses</b></h2>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{route('add-course')}}" class="btn btn-success" data-toggle=""><i
                                        class="material-icons">&#xE147;</i> <span>Add New Course</span></a>
                            </div>
                            {{--                            <div class="col-sm-6">--}}
                            {{--                                <a href="#addCategory" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Category</span></a>--}}
                            {{--                                --}}{{--						<a href="#deleteEmployeeModal" class="btn btn-danger" data-toggle="modal"><i class="material-icons">&#xE15C;</i> <span>Delete</span></a>--}}
                            {{--                            </div>--}}
{{--                            <div class="col-sm-6">--}}
{{--                                  <a href="#addCategory" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Category</span></a>--}}
{{--                            	<div id="hideform" class="btn"> Delete</div>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <div id="showform">
                                <form>
                                    Hello
                                </form>
                            </div>
                            <table class="table table-striped table-hover" id="table_id">
                                <thead>
                                <tr>
                                
                                    <th>Category</th>
                                    <th>Name</th>
                                    <th>Trainer</th>
                                    <th>Description</th>
                                    <th>Duration</th>
                                    <th>Enable / Disable</th>
                                    <th>Created at</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                @if($data)
                                    @foreach($data as $row)
                                        <tr>
                                            <td>{{$row->categories($row->category_id)}}</td>
                                            <td>{{$row->name}}</td>
                                            <td>{{$row->users['name']}}</td>
                                            <td>{!! $row->description !!}</td>
                                            <td>{{$row->duration}}</td>
                                            <td class="text-center" title="change status"><input type="checkbox" class="course_status" data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-onstyle="success" rel="{{$row->id}}" data-offstyle="danger" @if($row->status=='1') checked @endif></td>
                                            <td>{{$row->created_at}}</td>

                                            {{--						<td>89 Chiaroscuro Rd, Portland, USA</td>--}}
                                            {{--                        <td>(171) 555-2222</td>--}}
                                                                                        <td>
                                            {{--                                                <a href="" data-id="{{$row->id}}" id="edit_cat"  class="edit category_edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>--}}
                                             <a href="{{url('admin/course/edit/'.$row->id)}}"><i class="fa fa-edit"></i></a>
                                            <a href="#" data-id="{{$row->id}}"  class="delete removePartner" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                                                                                        </td>

                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{--@section('script')--}}

@endsection
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>

<script>

    $(document).ready(function() {
        $('#table_id').DataTable( {
            "order": [[ 6, "desc" ]]
        } );
    } );
    $(document).on('click', '.removePartner', function (evt) {
        var task_id = $( this ).attr( "data-id" );
        var form_data = {
            id: task_id
        };
        swal({
            title: "Do you want to delete this Record",
            text: "@lang('packages.delete_package_msg')",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#F79426',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            showLoaderOnConfirm: true
        }).then( ( result ) => {
            if ( result.value == true ) {
                $.ajax( {
                    type: 'get',
                    headers: {
                        'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                    },
                    url: '<?php echo url("admin/delete"); ?>',
                    data: form_data,
                    success: function ( msg ) {
                        swal( "Record Delete Successfully", '', 'success' )
                        setTimeout( function () {
                            location.reload();
                        }, 2000 );
                    }
                } );
            }
        } );

    } );


</script>
<script>
   $( "#hideform" ).click(function() {
  $( "#showform" ).toggle( "slow" );
  });
</script>
<script type="text/javascript">
    // $(document).ready( function () {
    //     $(".course_status").change(function(){ // yahaan pr class select ho gi checkbox me jo di hui he.
            $(document).on('change', '.course_status', function (evt) {
                // alert('he');


                var id=$(this).attr('rel'); // yahaan pr id pick ho gi product ki. rel se
            if($(this).prop("checked")==true){

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'get',
                    url: "{{route('change_course_status')}}",
                    data: {status:'1',id:id},
                    success:function(resp){
                        $(".msgSuccess").show();
                        setTimeout(function() { $(".msgSuccess").fadeOut('slow'); }, 2000);
                    },error:function(){
                        alert('Error');
                    }
                });
            }else
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('customSection')
                    },
                    type: 'get',
                    url: "{{route('change_course_status')}}",
                    data: {status:'0',id:id},
                    success:function(resp){
                        $(".msgError").show();
                        setTimeout(function() { $(".msgError").fadeOut('slow'); }, 2000);
                    },error:function(){
                        alert('Error');
                    }
                });
            }
        });
    // });
</script>
