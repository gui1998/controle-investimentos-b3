@extends('layouts.admin')

@section('style')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            {{ __('Sector Listing') }}
                            <button style="float: right; font-weight: 900;" class="btn btn-info btn-sm" type="button"
                                    id="getCreateSectorModal">
                                Create Sector
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered datatable">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nome</th>
                                    <th width="150" class="text-center">Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Create Sector Modal -->
    <div class="modal" id="CreateSectorModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Sector Create</h4>
                    <button type="button" class="close modelClose" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
                        <strong>Success!</strong>Sector was added successfully.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="form-group form-inline">
                        <label for="name">Nome:</label>
                        <input type="text" class="form-control" name="name" id="name">
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="SubmitCreateSectorForm">Create</button>
                    <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Sector Modal -->
    <div class="modal" id="DeleteSectorModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
                        <strong>Success!</strong>Sector was added successfully.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            $('.alert').hide();
            // init datatable.
            var dataTable = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                pageLength: 5,
                responsive: true,
                "pagingType": "numbers",
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.1/i18n/pt_br.json"
                },
                // scrollX: true,
                "order": [[0, "desc"]],
                ajax: '{{ route('get-sectors') }}',
                columns: [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'Actions', name: 'Actions', orderable: false, serachable: false, sClass: 'text-center'},
                ]
            });

            $('body').on('click', '#getCreateSectorModal', function (e) {
                $('#CreateSectorModal').show();
            });

            $('body').on('click', '.modelClose', function (e) {
                $('.alert').hide();
                $('div.modal').hide();
            });

            // Create article Ajax request.
            $('#SubmitCreateSectorForm').click(function (e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('sectors.store') }}",
                    method: 'post',
                    data: {
                        name: $('#name').val(),
                    },
                    success: function (result) {
                        if (result.errors) {
                            $('.alert-danger').html('');
                            $.each(result.errors, function (key, value) {
                                $('.alert-danger').show();
                                $('.alert-danger').append('<strong><li>' + value + '</li></strong>');
                            });
                        } else {
                            $('.datatable').DataTable().ajax.reload();
                            $('#CreateSectorModal').modal('hide');
                            location.reload();
                        }
                    }
                });
            });

            var id;
            $('body').on('click', '#getEditSectorData', function (e) {
                id = $(this).data('id');
                window.location.href  = 'sectors/' + id + '/edit';
            });

            // Delete article Ajax request.
            var deleteID;
            $('body').on('click', '#getDeleteId', function () {
                deleteID = $(this).data('id');
                //e.preventDefault();
                var id = deleteID;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "sectors/" + id,
                    method: 'DELETE',
                    success: function (result) {
                        if (result.errors) {
                            $('#DeleteSectorModal').show();
                            $('.alert-danger').html('');
                            $('.alert-danger').show();
                            $('.alert-danger').append('<strong><li>' + result.errors + '</li></strong>');
                        } else {
                            $('.datatable').DataTable().ajax.reload();
                            $('#DeleteSectorModal').hide();
                        }
                    }
                });
            });
        });
    </script>
@endsection
