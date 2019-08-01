@extends('adminlte::page')

@section('title', 'Test Task')

@section('content_header')
    <h1>Employee {{$employee ? 'editor' : 'adding'}}</h1>
@stop

@section('css')
    <link rel="stylesheet" href="<?php echo asset('css/user-styles.css')?>" type="text/css">
@stop

<script src="{{ mix('js/app.js') }}"></script>

@section('content')

    <div class="box-body editor-wrap">

        <form action="/admin/employees/edit" method="POST" id="employees-form" enctype="multipart/form-data">
            {!! csrf_field() !!}

            <input type="hidden" name="id" value="{{$employee ? $employee->id : ''}}">

            <div class="form-group">
                <label for="photo">Photo</label>
                @if($employee)
                    <p><img src="{{$employee->photo}}" alt=""></p>
                @endif
                <input name="photo" type="file" class="" id="photo">
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input name="name" type="text" class="form-control" id="name" value="{{$employee ? $employee->name : ''}}">
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input name="phone" type="tel" class="form-control" id="phone" value="{{$employee ? $employee->phone : ''}}">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input name="email" type="email" class="form-control" id="email" value="{{$employee ? $employee->email : ''}}">
            </div>

            <div class="form-group">
                <label for="position">Position</label>
                <select name="position" id="position" class="form-control">
                    @foreach($positions as $position)
                        <option value="{{$position->id}}" {{$employee && $employee->position == $position->id ? 'selected' : ''}}>
                            {{$position->name}}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="form-group">
                <label for="salary">Salary, $</label>
                <input name="salary" type="text" class="form-control" id="salary" value="{{$employee ? $employee->salary : ''}}">
            </div>



            <div class="form-group">
                <label for="head">Head</label>
                <input name="head" type="text" class="form-control" id="head" value="{{$employee && $employee->parent ? $employee->parent->name : ''}}">
            </div>



            <div class="form-group">
                <label for="employee_date">Date of employment</label>
                <input name="employee_date" type="text" class="form-control " id="employee_date" value="{{$employee ? $employee->employee_date->format('d.m.y') : ''}}">
            </div>

            <div class="editor-action">
                <a href="/admin/employees"><button type="button" class="btn btn-secondary">Cancel</button></a>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>

        </form>
    </div>


    @if (isset($errors) && count($errors))
        There were {{count($errors->all())}} Error(s)
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }} </li>
            @endforeach
        </ul>
    @endif


    <script type="text/javascript">
        var path = "/admin/employees/autocomplete";
        $('#head').typeahead({
            source:  function (query, process) {
                return $.get(path, { name: query }, function (data) {
                    return process(data);
                });
            }
        });

        $("#phone").mask("+380 (99) 999 99 99",
            {
                'translation':
                    {
                        9: {pattern: /[0-9]/},
                        0: {pattern: /0/}
                    }
            }
        );

        $('#employee_date').datepicker({format: 'dd.mm.yy'});
    </script>
@endsection

