@extends('layouts.master')
@section('style')
    <style>
        #project_table .status {
            direction: rtl;
            min-width: 240px;
            padding: 5px
        }
        .form-control {
            padding: 5px 3px;
        }
    </style>
@endsection
@section('content')
@php
    $owners = \App\Models\Owner::all();
@endphp
<div class="container-fluid">    
    <div class="row mt-5">
        <div class="col-12 clearfix">
            <h3 class="float-left">Banned Projects</h3>
        </div>
        <div class="col-12 table-responsive">
            <table class="table table-bordered" id="project_table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Client Name</th>
                        <th>Project Name</th>
                        <th style="width:110px;">Start Date</th>
                        <th>Project Budget</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td width="40" class="text-center">{{$loop->index + 1}}</td>
                            <td>{{$item->client_name}}</td>
                            <td>{{$item->name}}</td>
                            <td>
                                @if ($item->start_date != '')
                                    {{date('d/m/Y', strtotime($item->start_date))}}
                                @endif 
                            </td>
                            <td>@if($item->budget){{$item->budget}} @endif</td>
                            <td class="py-2">
                                <button class="btn btn-success btn-restore btn-sm" data-id="{{$item->id}}">Restore</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('.btn-restore').click(function(){
                if(!window.confirm('Are you sure?')){
                    return false;
                }
                let id = $(this).data('id');
                let _token = "{{csrf_token()}}";
                let project_row = $(this).parents('tr');
                $.ajax({
                    url: '/project/update',
                    method: 'POST',
                    data: {_token: _token, id: id, field: 'is_hidden', value: 0},
                    success(response) {
                        project_row.remove();
                    }
                });
            });
        })
    </script>
@endsection
