@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <form action="{{route('setting.update')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="">Account ID</label>
                        <input type="text" name="account_id" class="form-control" value="{{$setting->account_id}}" />
                    </div>
                    <div class="form-group">
                        <label for="">Access Token</label>
                        <textarea name="access_token" class="form-control" rows="3">{{$setting->access_token}}</textarea>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
