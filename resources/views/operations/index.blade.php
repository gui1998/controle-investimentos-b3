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
                            {{ __('Operation Listing') }}
                            <button style="float: right; font-weight: 900;" class="btn btn-info btn-sm" type="button"
                                    id="getCreateOperationModal">
                                Create Operation
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered datatable">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Tipo</th>
                                    <th>Compra ou Venda</th>
                                    <th>Ação</th>
                                    <th>Data de Pagamento</th>
                                    <th>Quantidade de Ações</th>
                                    <th>Custo</th>
                                    <th>Irrf</th>
                                    <th>Valor Bruto</th>
                                    <th>Valor Líquido Total</th>
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
    <!-- Create Operation Modal -->
    <div class="modal" id="CreateOperationModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Operation Create</h4>
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
                        <strong>Success!</strong>Operation was added successfully.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <label for="operation_date">Data da Operação:</label>
                                <input type="text"
                                       class="form-control date" maxlength="5" name="operation_date"
                                       id="operation_date"
                                       required>
                            </div>
                            <div class="col">
                                <label for="stock_amount">Quantidade de Ações:</label>
                                <input type="text"
                                       class="form-control positiveNumber" placeholder="10" name="stock_amount"
                                       id="stock_amount"
                                       required>

                            </div>
                            <div class="col">
                                <label for="price">Valor Bruto:</label>
                                <input type="text"
                                       class="form-control money" placeholder="5.10" name="price" id="price"
                                       required>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="cost">Custo:</label>
                                <input type="text"
                                       class="form-control money" placeholder="5.10" name="cost" id="cost"
                                       required>

                            </div>
                            <div class="col">
                                <label for="irrf">Irrf:</label>
                                <input type="text"
                                       class="form-control money" placeholder="5.10" name="irrf" id="irrf">

                            </div>
                            <div class="col">
                                <label for="broker">Corretora:</label>
                                <select name="broker" id="broker" class="form-control select2" required>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="buy_r_sell">Compra ou Venda:</label>
                                <select name="buy_r_sell" id="buy_r_sell" class="form-control" required>
                                    <option value="C">Compra</option>
                                    <option value="V">Venda</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="operation_type">Tipo de Operação:</label>
                                <select name="operation_type" id="operation_type" class="form-control select2" required>
                                </select>
                            </div>
                            <div class="col">
                                <label for="stock">Ação:</label>
                                <select name="stock" id="stock" class="form-control select2" required>
                                </select>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="SubmitCreateOperationForm">Create</button>
                            <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Operation Modal -->
    <div class="modal" id="DeleteOperationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Operation Delete</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <h4>Are you sure want to delete this Operation?</h4>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="SubmitDeleteOperationForm">Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script type="text/javascript">
        $(document).ready(function () {
            // init datatable.
            var dataTable = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                "pagingType": "numbers",
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.1/i18n/pt_br.json"
                },
                ajax: '{{ route('get-operations') }}',
                columns: [
                    {data: 'id'},
                    {data: 'operation_type'},
                    {data: 'buy_r_sell'},
                    {data: 'stock'},
                    {data: 'operation_date'},
                    {data: 'stock_amount'},
                    {data: 'cost'},
                    {data: 'irrf'},
                    {data: 'price'},
                    {data: 'net_value'},
                    {
                        data: 'Actions',
                        name: 'Actions',
                        orderable: false,
                        serachable: false,
                        sClass: 'text-center'
                    },
                ]
            });

            $('body').on('click', '#getCreateOperationModal', function (e) {

                $.ajax({
                    url: '{{route('list-operationTypes')}}', success: function (result) {
                        result.forEach(function (e, i) {
                            $('#operation_type').append($('<option></option>').val(e.id).text(e.name));
                        });
                    }
                });
                $.ajax({
                    url: '{{route('list-stocks')}}', success: function (result) {
                        result.forEach(function (e, i) {
                            $('#stock').append($('<option></option>').val(e.id).text(e.code));
                        });
                    }
                });
                $.ajax({
                    url: '{{route('list-brokers')}}', success: function (result) {
                        result.forEach(function (e, i) {
                            $('#broker').append($('<option></option>').val(e.id).text(e.name));
                        });
                    }
                });
                $('#CreateOperationModal').show();
            });

            // Create article Ajax request.
            $('#SubmitCreateOperationForm').click(function (e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('operations.store') }}",
                    method: 'post',
                    data: {
                        operation_date: $('#operation_date').val(),
                        stock_amount: $('#stock_amount').val(),
                        cost: $('#cost').val(),
                        irrf: $('#irrf').val(),
                        price: $('#price').val(),
                        operation_type_id: $('#operation_type').val(),
                        stock_id: $('#stock').val(),
                        broker_id: $('#broker').val(),
                        buy_r_sell: $('#buy_r_sell').val(),
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
                            $('#CreateOperationModal').modal('hide');
                            location.reload();
                        }
                    }
                });
            });

            $('body').on('click', '#getEditOperationData', function (e) {
                id = $(this).data('id');
                window.location.href = 'operations/' + id + '/edit';
            });

            // Update article Ajax request.
            $('#SubmitEditOperationForm').click(function (e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "operations/" + id,
                    method: 'PUT',
                    data: {
                        name: $('#editName').val(),
                    },
                    success: function (result) {
                        if (result.errors) {
                            $('.alert-danger').html('');
                            $.each(result.errors, function (key, value) {
                                $('.alert-danger').show();
                                $('.alert-danger').append('<strong><li>' + value + '</li></strong>');
                            });
                        } else {
                            $('.alert-danger').hide();
                            $('.alert-success').show();
                            $('.datatable').DataTable().ajax.reload();

                            $('.alert-success').hide();
                            $('#EditOperationModal').hide();
                        }
                    }
                });
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
                    url: "operations/" + id,
                    method: 'DELETE',
                    success: function (result) {
                        $('.datatable').DataTable().ajax.reload();
                        $('#DeleteOperationModal').hide();
                    }
                });
            });
        });
    </script>
@endsection
