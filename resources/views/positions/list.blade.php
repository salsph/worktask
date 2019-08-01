


@extends('adminlte::page')

@section('title', 'Test Task')

@section('content_header')
    <div>
        <h1>Positions</h1>
    </div>
    <div>
        <a class="btn btn-success" href="/admin/positions/editor">Add position</a>
    </div>
@stop

@section('css')
    <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/user-styles.css')?>" type="text/css">
@stop

@section('js')
<script src="https://kit.fontawesome.com/c87fcf8dde.js"></script>
@stop




{{--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">--}}
{{--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>--}}
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>--}}
{{--<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>--}}




<script src="{{ mix('js/app.js') }}"></script>



@section('content')
    <h3>Positions list</h3>

    <table class="table table-bordered data-table" id="laravel_datatable">
        <thead>
        <tr>
            <th>Name</th>
            <th>Last update</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>


    <!-- Removing alert modal -->
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Remove position</h4>
                </div>

                <form action="/admin/positions/remove" method="POST">
                    {{csrf_field()}}

                    <div class="modal-body">
                        <p>Are you sure you want to remove position <span id="position-name"></span>?</p>
                        <input type="hidden" name="position_id" id="position-id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Delete</button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <script>
        $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('#laravel_datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 50,
                ajax: "{{ url('admin/positions/list') }}",
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });

        $('#delete').on('show.bs.modal', function(e) {
            let button = $(e.relatedTarget);

            let positionId = button.data('position_id');
            let positionName = button.data('position_name');
            let modal = $(this);

            modal.find('.modal-body #position-name').html(positionName);
            modal.find('.modal-body #position-id').val(positionId);
        });
    </script>




@endsection







