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
    <div class="row mt-3 mt-md-5">
        <div class="col-md-2 col-sm-6">
            <div class="card card-body text-center">
                <h1>{{$client_count}}</h1>
                <h5>Clients</h5>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card card-body text-center">
                <h1>{{$project_count}}</h1>
                <h5>Projects</h5>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card card-body text-center">
                <h1>{{$hours_tracked}}</h1>
                <h5>Hours Tracked</h5>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card card-body text-center">
                <h1>{{$billable}}%</h1>
                <h5>Billable</h5>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card card-body text-center">
                <h1>1</h1>
                <h5>Lorem</h5>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12 table-responsive">
            <table class="table table-bordered" id="project_table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Client Name</th>
                        <th>Project Name</th>
                        <th style="width:110px;">Start Date</th>
                        <th>Project Budget</th>
                        <th>Hourly Tracked</th>
                        <th>Difference</th>
                        <th>Project Life</th>
                        <th>Project Owner</th>
                        <th>Deadline</th>
                        <th style="width:160px;">Project Health</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        @php
                            if($item->start_date) {
                                $created = new \Carbon\Carbon($item->start_date);
                                $now = \Carbon\Carbon::now();
                                $life = $created->diff($now)->days;
                            } else {
                                $life = '';
                            }
                            $difference = $item->budget - $item->tracked;
                                               
                        @endphp
                        <tr>
                            <td width="40" class="text-center">{{$loop->index + 1}}</td>
                            <td>{{$item->client_name}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->start_date}}</td>
                            <td>@if($item->budget){{$item->budget}} Hours @endif</td>
                            <td>{{$item->tracked}} Hours</td>
                            <td class="@if($difference < 0) text-danger @endif">{{$difference}} Hours</td>
                            <td>{{$life}} Days</td>
                            <td class="px-1">
                                <select class="form-control owner" data-id="{{$item->id}}">
                                    <option value=""></option>
                                    @foreach ($owners as $owner_item)
                                        <option value="{{$owner_item->id}}" @if($owner_item->id == $item->owner_id) selected @endif>{{$owner_item->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-1">
                                <input type="date" class="form-control deadline" value="{{$item->deadline}}" data-id="{{$item->id}}">
                            </td>
                            <td class="px-1">
                                <select class="form-control health" data-id="{{$item->id}}">
                                    <option value=""></option>
                                    <option value="1" @if($item->health == 1) selected @endif>1 - Very Bad</option>
                                    <option value="2" @if($item->health == 2) selected @endif>2 - Very Bad</option>
                                    <option value="3" @if($item->health == 3) selected @endif>3 - Pretty Bad</option>
                                    <option value="4" @if($item->health == 4) selected @endif>4 - Bad</option>
                                    <option value="5" @if($item->health == 5) selected @endif>5 - Bad</option>
                                    <option value="6" @if($item->health == 6) selected @endif>6 - Good</option>
                                    <option value="7" @if($item->health == 7) selected @endif>7 - Good</option>
                                    <option value="8" @if($item->health == 8) selected @endif>8 - Pretty Good</option>
                                    <option value="9" @if($item->health == 9) selected @endif>9 - Very Good</option>
                                    <option value="10" @if($item->health == 10) selected @endif>10 - Very Good</option>
                                </select>
                            </td>
                            <td class="px-1">
                                <textarea rows="3" class="form-control status" data-id="{{$item->id}}">{{$item->status}}</textarea>
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
            $('#project_table .owner').change(function(){
                let id = $(this).data('id');
                let value = $(this).val();
                change_project(id, 'owner_id', value);
            });

            $('#project_table .health').change(function(){
                let id = $(this).data('id');
                let value = $(this).val();
                change_project(id, 'health', value);
            });

            $('#project_table .deadline').change(function(){
                let id = $(this).data('id');
                let value = $(this).val();
                change_project(id, 'deadline', value);
            });

            $('#project_table .status').change(function(){
                let id = $(this).data('id');
                let value = $(this).val();
                change_project(id, 'status', value);
            });

            // var status_timeout = 0;
            // $('#project_table .status').keypress(function () { 
            //     let id = $(this).data('id');
            //     let value = $(this).val();
            //     debounce(function(){
            //         console.log('Debounce happen...')
            //     }, 2000);             
            // });

            function change_project(id, field, value) {
                let _token = "{{csrf_token()}}";
                $.ajax({
                    url: '/project/update',
                    method: 'POST',
                    data: {_token: _token, id: id, field: field, value: value},
                    success(response) {
                        console.log(response);
                    }
                })
            }



            // Debounce Function
            function debounce(func, wait) {
                var timeout;
                return function() {
                    var context = this, args = arguments;
                    var later = function() {
                        timeout = null;
                        func.apply(context, args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);                    
                };
            };
        })
    </script>
@endsection
