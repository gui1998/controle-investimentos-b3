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
                            {{ __('Ativo Listagem') }}
                            <button style="float: right; font-weight: 900;" class="btn btn-info btn-sm" type="button"
                                    id="getCreateStockModal">
                                Criar Ativo
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered datatable">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Código</th>
                                    <th>Empresa</th>
                                    <th>Tipo</th>
                                    <th>Setor</th>
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
    <!-- Criar Ativo Modal -->
    <div class="modal" id="CreateStockModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Ativo Criar</h4>
                    <button type="button" class="close modelClose" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
                        <strong>Success!</strong>Ativo Foi adicionado(a) com sucesso.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="code">Código:</label>
                            <input type="text"
                                   class="form-control stockCode" placeholder="BBDC4" name="code" id="code"
                                   required>
                        </div>
                        <div class="col">
                            <label for="company_name">Empresa:</label>
                            <input type="text"
                                   class="form-control" placeholder="Bradesco" name="company_name" id="company_name"
                                   required>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label for="stock_type">Tipo:</label>
                            <select name="stock_type" id="stock_type" class="form-control select2" required>
                            </select>
                        </div>
                        <div class="col">
                            <label for="sector">Setor:</label>
                            <select name="sector" id="sector" class="form-control select2" required>
                            </select>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="SubmitCreateStockForm">Criar</button>
                        <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Delete Ativo Modal -->
    <div class="modal" id="DeleteStockModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
                        <strong>Success!</strong>Ativo Foi adicionado(a) com sucesso.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript"
            src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript"
            src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
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
                "order": [[0, "desc"]],
                ajax: '{{ route('get-stocks') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'code', code: 'code'},
                    {data: 'company_name', company_name: 'company_name'},
                    {data: 'stock_type', stock_type: 'stock_type'},
                    {data: 'sector', sector: 'sector'},
                    {
                        data: 'Actions',
                        name: 'Actions',
                        orderable: false,
                        serachable: false,
                        sClass: 'text-center'
                    },
                ]
            });

            $('body').on('click', '#getCreateStockModal', function (e) {
                $.ajax({
                    url: '{{route('list-stockTypes')}}', success: function (result) {
                        result.forEach(function (e, i) {
                            $('#stock_type').append($('<option></option>').val(e.id).text(e.name));
                        });
                    }
                });
                $.ajax({
                    url: '{{route('list-sectors')}}', success: function (result) {
                        result.forEach(function (e, i) {
                            $('#sector').append($('<option></option>').val(e.id).text(e.name));
                        });
                    }
                });
                $('#CreateStockModal').show();
            });

            $('body').on('click', '.modelClose', function (e) {
                $('div.modal').hide();
            });

            // Criar article Ajax request.
            $('#SubmitCreateStockForm').click(function (e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('stocks.store') }}",
                    method: 'post',
                    data: {
                        code: $('#code').val(),
                        stock_type_id: $('#stock_type').val(),
                        company_name: $('#company_name').val(),
                        sector_id: $('#sector').val(),
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
                            $('#CreateStockModal').modal('hide');
                            location.reload();
                        }
                    }
                });
            });


            $('body').on('click', '#getEditStockData', function (e) {
                id = $(this).data('id');
                window.location.href  = 'stocks/' + id + '/edit';
            });

            // Update article Ajax request.
            $('#SubmitEditStockForm').click(function (e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "stocks/" + id,
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
                            $('#EditStockModal').hide();
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
                    url: "stocks/" + id,
                    method: 'DELETE',
                    success: function (result) {
                        if (result.errors) {
                            $('#DeleteStockModal').show();
                            $('.alert-danger').html('');
                            $('.alert-danger').show();
                            $('.alert-danger').append('<strong><li>' + result.errors + '</li></strong>');
                        } else {
                            $('.datatable').DataTable().ajax.reload();
                            $('#DeleteStockModal').hide();
                        }
                    }
                });
            });
        });
    </script>
@endsection
