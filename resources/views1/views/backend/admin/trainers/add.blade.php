@extends('backend.admin.layouts.app')
@section('customSection')
    <div class="container mt-5">
        <form  id="course_form" method="POST" action="{{route('admin_new_profile')}}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title">Add User</h4>
            </div>


            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="role_id">
                            <option value="1">Admin</option>
{{--                            <option value="2">Trainer</option>--}}
                            <option value="3">User</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" value="{{old('name')}}" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="{{old('email')}}" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" value="{{old('')}}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" name="address" value="{{old('address')}}" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Experience</label>
                        <input type="text" class="form-control" name="experience" value="{{old('experience')}}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Country</label>
                        <input type="text" class="form-control" name="country" value="{{old('country')}}" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" name="phone" value="{{old('phone')}}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Cnic</label>
                        <input type="number" class="form-control" name="cnic" value="{{old('cnic')}}" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Date Of Birth</label>
                        <input type="date" class="form-control" name="date_of_birth" value="{{old('date_of_birth')}}" required>
                    </div>
                </div>

            </div>
            <div class="col-6">
                <div class="btn btn-primary btn-sm float-left">
                    <span>Choose Profile Photo</span>
                    <input type="file" name="image" accept="image/*" value="{{old('image')}}"  required>
                </div>
            </div>




            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                <input type="submit" class="btn btn-success cat" id="cat" value="Submit">
            </div>
        </form>
    </div>
@endsection
