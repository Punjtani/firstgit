@extends('backend.admin.layouts.app')
@section('customSection')
    {{--    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>--}}
    {{--    <script type="text/javascript" src="//cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>--}}
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-primary">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h2>Manage <b>Slider</b></h2>
                            </div>
                            <div class="col-sm-6">
                                <a href="#addCategory" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Image</span></a>
                                {{--						<a href="#deleteEmployeeModal" class="btn btn-danger" data-toggle="modal"><i class="material-icons">&#xE15C;</i> <span>Delete</span></a>--}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="table_id">
                            <thead>
                            <tr>

                                <th>Heading</th>
                                <th>Sub-Heading</th>
                                <th>Image</th>

                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $row)
                            <tr>

                                <td>{{$row->title}}</td>
                                <td>{{$row->heading}}A</td>
                                    <td>
                                        @if($row->image!="")
                                            <img src="{{asset('slider/'.$row->image)}}" style="width: 75px;" >
                                        @else
                                            <img src="{{url('image/dummy.jpg')}} " style="width: 75px;">
                                        @endif
                                    </td>
                                <td>
                                    <a href="" data-id="{{$row->id}}" class="edit slider_edit" data-toggle="modal"><i
                                            class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                                    <a href="" data-id="{{$row->id}}" class="delete removePartner" data-toggle="modal"><i
                                            class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="addCategory" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form  id="category_form" method="POST" action="{{route('slider-data')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Add Slider Image</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">

                        <div class="file-field">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" name="heading" required>
                            </div>
                            <div class="form-group">
                                <label>Heading</label>
                                <input type="text" class="form-control" name="sub_heading" required>
                            </div>
                            <div class="btn btn-primary btn-sm float-left">
                                <span>Choose file</span>
                                <input type="file" name="image" accept="image/*" required>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-success cat" id="cat">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="edit_slider" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form  id="category_form" method="POST" action="{{url('admin/edit-slider-data')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Slider</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body" id="replace">
                    </div>

                    <div class="modal-footer">
                        <input type="button"  class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-info" value="Update">
                    </div>
                </form>
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
            "order": [[ 2, "desc" ]]
        } );
    } );
    $(document).on('click', '.removePartner', function (evt) {
// alert('he');
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
                    url: '<?php echo url("admin/delete-slider"); ?>',
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
    $(document).on('click', '.slider_edit', function (evt) {
        var task_id = $( this ).attr( "data-id" );
        var form_data = {
            id: task_id
        }
        $.ajax({
            type: 'get',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{url('admin/edit_slider')}}',
            data: form_data,
            success: function (response) {
                if(response.status == "success") {
                    var html ="";
                    html += '<input type="hidden" name="id" value="'+response.data.id+ '" >';
                    html += '<div class="form-group">' ;
                    html +=  '<label>Title</label>' ;
                    html += '<input type="text" class="form-control" value="'+response.data.title+ '" name="heading" required>' ;
                    html +=  '</div>' ;
                    html += '<div class="form-group">' ;
                    html += '<label>Heading</label>' ;
                    html += '<input type="text" class="form-control" value="'+response.data.heading+ '" name="sub_heading" required>';
                    html += '</div>';
                    html += '<div class="btn btn-primary btn-sm float-left">';
                    html +=  '<span>Choose file</span>';
                    html +=  '<input type="file" name="image" accept="image/*" required>' ;
                    html +=  '</div>';
                    $('#replace').html(html);
                    $('#edit_slider').modal('toggle');

                }
            }
        });
    });
</script>
