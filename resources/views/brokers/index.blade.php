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
                            {{ __('Broker Listing') }}
                            <button style="float: right; font-weight: 900;" class="btn btn-info btn-sm" type="button"
                                    id="getCreateBrokerModal">
                                Create Broker
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
    <!-- Create Broker Modal -->
    <div class="modal" id="CreateBrokerModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Broker Create</h4>
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
                        <strong>Success!</strong>Broker was added successfully.
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
                    <button type="button" class="btn btn-success" id="SubmitCreateBrokerForm">Create</button>
                    <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Broker Modal -->
    <div class="modal" id="DeleteBrokerModal">
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
                        <strong>Success!</strong>Broker was added successfully.
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
                ajax: '{{ route('get-brokers') }}',
                columns: [
                    {data: 'id', Nome: 'id'},
                    {data: 'name', Nome: 'name'},
                    {data: 'Actions', Nomer: 'Actions', orderable: false, serachable: false, sClass: 'text-center'},
                ]
            });

            $('body').on('click', '#getCreateBrokerModal', function (e) {
                $('#CreateBrokerModal').show();
            });

            $('body').on('click', '.modelClose', function (e) {
                $('.alert').hide();
                $('div.modal').hide();
            });

            // Create article Ajax request.
            $('#SubmitCreateBrokerForm').click(function (e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('brokers.store') }}",
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
                            $('#CreateBrokerModal').modal('hide');
                            location.reload();
                        }
                    }
                });
            });

            var id;
            $('body').on('click', '#getEditBrokerData', function (e) {
                id = $(this).data('id');
                window.location.href  = 'brokers/' + id + '/edit';
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
                    url: "brokers/" + id,
                    method: 'DELETE',
                    success: function (result) {
                        if (result.errors) {
                            $('#DeleteBrokerModal').show();
                            $('.alert-danger').html('');
                            $('.alert-danger').show();
                            $('.alert-danger').append('<strong><li>' + result.errors + '</li></strong>');
                        } else {
                            $('.datatable').DataTable().ajax.reload();
                            $('#DeleteBrokerModal').hide();
                        }
                    }
                });
            });
        });
    </script>
@endsection
