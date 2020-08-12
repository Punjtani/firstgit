@extends('backend.admin.layouts.app')
@section('customSection')
    <div class="container mt-5">
        <form  id="course_form" method="POST" action="{{route('admin_update_profile')}}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title">Edit User</h4>
            </div>


            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="role_id">
                            <option value="1" @if($user->role_id == 1) selected @endif>Admin</option>
{{--                            <option value="2" @if($user->role_id == 2) selected @endif>Trainer</option>--}}
                            <option value="3" @if($user->role_id == 3) selected @endif>User</option>
                        </select>
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <input type="hidden" name="redirect" value="{{$redirect}}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" value="{{$user->name}}" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="{{$user->email}}" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" value="{{$user->password}}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" name="address" value="{{$user->address}}" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Experience</label>
                        <input type="text" class="form-control" name="experience" value="{{$user->experience}}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Country</label>
                        <input type="text" class="form-control" name="country" value="{{$user->country}}" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" name="phone" value="{{$user->phone}}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Cnic</label>
                        <input type="number" class="form-control" name="cnic" value="{{$user->cnic}}" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Date Of Birth</label>
                        <input type="date" class="form-control" name="date_of_birth" value="{{$user->date_of_birth}}" required>
                    </div>
                </div>

            </div>
            <div class="col-6">
                <div class="btn btn-primary btn-sm float-left">
                    <span>Choose Profile Photo</span>
                    <input type="file" name="image" accept="image/*" src="{{asset('users/'.$user->image)}}" required>
                </div>
            </div>




            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                <input type="submit" class="btn btn-success cat" id="cat" value="Submit">
            </div>
        </form>
    </div>
@endsection
