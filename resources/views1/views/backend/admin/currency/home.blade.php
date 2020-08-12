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
                                <h2>Currency</b></h2>
                            </div>
                            <div class="col-sm-6">
                                <a href="#addCategory" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Currency</span></a>
                              
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
                                    
                                    <th>Currency Name</th>
                                    <th>Symbol</th>
                                    <th>Created At</th>                                    
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                @if($data)
                                    @foreach($data as $row)
                                        <tr>                                      
                                            <td>{{$row->currency}}</td>
                                            <td>{{$row->symbol}}</td>                                      
                                            <td>
                                                @if($row->flag!="")
                                                    <img src="{{asset('currency/' .$row->flag)}}" style="width: 75px;" >
                                                @else
                                                    <img src="{{url('image/dummy.jpg')}} " style="width: 75px;">
                                                @endif
                                            </td>

                                            <td>
                                                <a href="" data-id="{{$row->id}}" class="edit category_edit" data-toggle="modal"><i
                                                        class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
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
    <div id="addCategory" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form  id="category_form" method="POST" action="{{route('save_currency')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Add Currency</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">

                        <div class="file-field">
                            <div class="form-group">
                                <label>Currency Name</label>
                                <input type="text" class="form-control" placeholder="currency name" name="name" required value="{{old('name')}}">
                            </div>
                            <div class="form-group">
                                <label>Symbol</label>
                                <input type="text" class="form-control" placeholder="$, SR" name="symbol" required value="{{old('symbol')}}">
                            </div>

                            <label>Flag</label>
                            <div class="btn btn-primary btn-sm float-left">
                                <span>Choose file</span>
                                <input type="file" name="logo" accept="image/*" required>
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

    <div id="edit_category" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form  id="category_form" method="POST" action="{{route('update_currency')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Currency</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body" id="replace1">
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
        var task_id = $( this ).attr( "data-id" );
        var form_data = {
            id: task_id
        };
        swal({
            title: "Do you want to delete this Record",
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
                    url: '<?php echo url("admin/delete/currency"); ?>',
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
    $(document).on('click', '.category_edit', function (evt) {
        var task_id = $( this ).attr( "data-id" );
        var form_data = {
            id: task_id
        }
        $.ajax({
            type: 'get',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{url('admin/edit/currency')}}',
            data: form_data,
            success: function (response) {
                if(response.status == "success") {
                    var html ="";
                    html += '<input type="hidden" name="id" value="'+response.data.id+ '" >';
                    html += '<div class="form-group">' ;
                    html +=  '<label>Name</label>' ;
                    html += '<input type="text" class="form-control" value="'+response.data.currency+ '" name="name" required>' ;
                    html +=  '</div>' ;
                    html += '<div class="form-group">' ;
                    html +=  '<label>Name</label>' ;
                    html += '<input type="text" class="form-control" value="'+response.data.symbol+ '" name="symbol" required>' ;
                    html +=  '</div>' ;
                    html += '<div class="btn btn-primary btn-sm float-left">';
                    html +=  '<span>Choose file</span>';
                    html +=  '<input type="file" name="logo" accept="image/*">' ;
                    html +=  '</div>';
                    $('#replace1').html(html);
                    $('#edit_category').modal('toggle');

                }
            }
        });
    });
</script>
