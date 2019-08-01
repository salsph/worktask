@extends('adminlte::page')

@section('title', 'Test Task')

@section('content_header')
    <h1>Position {{$position ? 'editor' : 'adding'}}</h1>
@stop

@section('css')
    <link rel="stylesheet" href="<?php echo asset('css/user-styles.css')?>" type="text/css">
@stop

@section('content')

    <div class="box-body editor-wrap">

        <form action="/admin/positions/edit" method="POST">
            {!! csrf_field() !!}

            <input type="hidden" name="id" value="{{$position ? $position->id : ''}}">

            <div class="form-group">
                <label for="name">Name</label>
                <input name="name" type="text" class="form-control" id="name" value="{{$position ? $position->name : ''}}">
            </div>

            @if($position)
                <div class="statistic-dates">
                    <div>
                        <span class="bold">Created at:</span> <span id="created_at">{{$position->created_at->format('d.m.y')}}</span>
                    </div>
                    <div>
                        <span class="bold">Admin created ID:</span> {{$position->admin_created_id}}
                    </div>
                    <div>
                        <span class="bold">Updated at:</span> {{$position->updated_at->format('d.m.y')}}
                    </div>
                    <div>
                        <span class="bold">Admin updated ID:</span> {{$position->admin_updated_id}}
                    </div>
                </div>
            @endif

            <div class="editor-action">
                <a href="/admin/positions"><button type="button" class="btn btn-secondary">Cancel</button></a>
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

@endsection

