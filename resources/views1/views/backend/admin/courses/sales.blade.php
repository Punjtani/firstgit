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
                                <h2><b>Sales</b></h2>
                            </div>
{{--                            <div class="col-sm-6">--}}
{{--                                <a href="{{url('trainer/add-form')}}" class="btn btn-success" data-toggle=""><i--}}
{{--                                        class="material-icons">&#xE147;</i> <span>Add New Course</span></a>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
{{--                {{dd(Auth::id())}}--}}
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">

                            <table class="table table-striped table-hover" id="table_id">
                                <thead>
                                <tr>
                                    {{--						<th>--}}
                                    {{--							<span class="custom-checkbox">--}}
                                    {{--								<input type="checkbox" id="selectAll">--}}
                                    {{--								<label for="selectAll"></label>--}}
                                    {{--							</span>--}}
                                    {{--						</th>--}}
                                    <th>Buyer</th>
                                    <th>Seller</th>
                                    <th>Category</th>
                                    <th>Course Name</th>
                                    <th>Thumbnail</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Purchased at</th>
{{--                                    <th>Action</th>--}}
                                </tr>
                                </thead>

                                <tbody>
                                @if($data)
                                    @foreach($data as $row)
                                        <tr>
{{--                                            {{dd($row->category)}}--}}
                                            <td>{{$row->buyer->name}}</td>
                                            <td>{{$row->seller->name}}</td>
                                            <td>{{$row->course->category->name}}</td>
                                            <td>{{$row->course->name}}</td>
                                            <td>
                                                @if($row->course->thumbnail!="")
                                                    <img src="{{asset('course/' .$row->course->thumbnail)}}"
                                                         style="width: 75px;">
                                                @else
                                                    <img src="{{url('image/dummy.jpg')}} " style="width: 75px;">
                                                @endif
                                            </td>
                                            <td>{{$row->course->description}}</td>
                                            <td>{{$row->course->price}}</td>
                                            <td>{{$row->created_at}}</td>
{{--                                            <td><a href="{{url('trainer/view-lessons/'.$row->id)}}"><i class="fa fa-eye"></i></a>--}}
{{--                                            <a href="{{url('trainer/edit-course/'.$row->id)}}"><i class="fa fa-edit"></i></a></td>--}}

                                            {{--						<td>89 Chiaroscuro Rd, Portland, USA</td>--}}
                                            {{--                        <td>(171) 555-2222</td>--}}
                                            {{--                                            <td>--}}
                                            {{--                                                <a href="" data-id="{{$row->id}}" id="edit_cat"  class="edit category_edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>--}}
                                            {{--                                                <a href="#" data-id="{{$row->id}}"  class="delete removePartner" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>--}}
                                            {{--                                            </td>--}}

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

    $(document).ready(function () {
        $('#table_id').DataTable({
            "order": [[2, "desc"]]
        });
    });


</script>
