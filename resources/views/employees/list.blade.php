@extends('adminlte::page')

@section('title', 'Test Task')

@section('content_header')
    <div>
        <h1>Employees</h1>
    </div>
    <div>
        <a class="btn btn-success" href="/admin/employees/editor">Add employee</a>
    </div>
@stop


@section('css')
    <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/user-styles.css')?>" type="text/css">
@stop

@section('js')
    <script src="https://kit.fontawesome.com/c87fcf8dde.js"></script>
@stop

<script src="{{ mix('js/app.js') }}"></script>



@section('content')
    <h3>Employees list</h3>

    <table class="table table-bordered data-table" id="laravel_datatable">
        <thead>
        <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Position</th>
            <th>Date of employment</th>
            <th>Phone number</th>
            <th>Email</th>
            <th>Salary</th>
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
                    <h4 class="modal-title" id="myModalLabel">Remove employee</h4>
                </div>

                <form action="/admin/employees/remove" method="POST">
                    {{csrf_field()}}

                    <div class="modal-body">
                        <p>Are you sure you want to remove employee <span id="employee-name"></span>?</p>
                        <input type="hidden" name="employee_id" id="employee-id" value="">
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
                ajax: "{{ url('admin/employees/list') }}",
                columns: [
                    { data: 'photo', name: 'photo',
                        render: function( data, type, full, meta ) {
                            return "<img src=\"" + data + "\" style='border-radius: 50%; width: 50px;' />";
                        }
                    },
                    { data: 'name', name: 'name' },
                    { data: 'position', name: 'position' },
                    { data: 'employee_date', name: 'employee_date'},
                    { data: 'phone', name: 'phone' },
                    { data: 'email', name: 'email' },
                    { data: 'salary', render: $.fn.dataTable.render.number( '.', ',', 3, '$' ), name: 'salary' },
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                columnDefs: [
                    {
                        targets: 0,
                        className: 'text-center'
                    }
                ]
            });
        });

        $('#delete').on('show.bs.modal', function(e) {
            let button = $(e.relatedTarget);
            let employeeId = button.data('emp_id');
            let employeeName = button.data('emp_name');
            let modal = $(this);

            modal.find('.modal-body #employee-name').html(employeeName);
            modal.find('.modal-body #employee-id').val(employeeId);
        });
    </script>


@endsection







