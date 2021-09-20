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
                            {{ __('Rendimento Listagem') }}
                            <button style="float: right; font-weight: 900;" class="btn btn-info btn-sm" type="button"
                                    id="getCreateIncomeModal">
                                Criar Rendimento
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered datatable">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Tipo</th>
                                    <th>Ativo</th>
                                    <th>Data de Pagamento</th>
                                    <th>Quantidade de Ativos</th>
                                    <th>Descontos</th>
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
    <!-- Criar Rendimento Modal -->
    <div class="modal" id="CreateIncomeModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Rendimento Criar</h4>
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
                        <strong>Success!</strong>Rendimento Foi adicionado(a) com sucesso.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <label for="payment_date">Data do Pagamento:</label>
                                <input type="text"
                                       class="form-control date" maxlength="5" name="payment_date"
                                       id="payment_date"
                                       required>
                            </div>
                            <div class="col">
                                <label for="stock_amount">Quantidade de Ativos:</label>
                                <input type="text"
                                       class="form-control positiveNumber"  placeholder="10" name="stock_amount" id="stock_amount"
                                       required>

                            </div>
                            <div class="col">
                                <label for="total">Valor Bruto:</label>
                                <input type="text"
                                       class="form-control money" placeholder="5.10" name="total" id="total"
                                       required>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="income_type">Tipo:</label>
                                <select name="income_type" id="income_type" class="form-control select2" required>
                                </select>
                            </div>
                            <div class="col">
                                <label for="discount">Descontos:</label>
                                <input type="text"
                                       class="form-control money"  placeholder="5.10" name="discount" id="discount"
                                       required>

                            </div>
                            <div class="col">
                                <label for="stock">Ativo:</label>
                                <select name="stock" id="stock" class="form-control select2" required>
                                </select>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="SubmitCreateIncomeForm">Criar</button>
                            <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Rendimento Modal -->
    <div class="modal" id="DeleteIncomeModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Rendimento Delete</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <h4>Are you sure want to delete this Rendimento?</h4>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="SubmitDeleteIncomeForm">Yes</button>
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
                ajax: '{{ route('get-incomes') }}',
                columns: [
                    {data: 'id'},
                    {data: 'income_type'},
                    {data: 'stock'},
                    {data: 'payment_date'},
                    {data: 'stock_amount'},
                    {data: 'discount'},
                    {data: 'total'},
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

            $('body').on('click', '#getCreateIncomeModal', function (e) {

                $.ajax({
                    url: '{{route('list-incomeTypes')}}', success: function (result) {
                        if (!result.length) {
                            alert("Necessário cadastrar um tipo de rendimento!");
                            window.location.href = '../incomeTypes';
                        }
                        result.forEach(function (e, i) {
                            $('#income_type').append($('<option></option>').val(e.id).text(e.name));
                        });
                    }
                });

                $.ajax({
                    url: '{{route('list-stocks')}}', success: function (result) {
                        if (!result.length) {
                            alert("Necessário cadastrar um ativo!");
                            window.location.href = '../stocks';
                        }
                        result.forEach(function (e, i) {
                            $('#stock').append($('<option></option>').val(e.id).text(e.code));
                        });
                    }
                });
                $('#CreateIncomeModal').show();
                $('#CreateIncomeModal').modal({
                    keyboard: false,
                    show: true
                });
                // Jquery draggable
                $('#CreateIncomeModal').draggable({
                    handle: ".modal-header"
                });
            });

            // Criar article Ajax request.
            $('#SubmitCreateIncomeForm').click(function (e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('incomes.store') }}",
                    method: 'post',
                    data: {
                        payment_date: $('#payment_date').val(),
                        stock_amount: $('#stock_amount').val(),
                        discount: $('#discount').val(),
                        total: $('#total').val(),
                        income_type_id: $('#income_type').val(),
                        stock_id: $('#stock').val(),
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
                            $('#CreateIncomeModal').modal('hide');
                            location.reload();
                        }
                    }
                });
            });

            $('body').on('click', '#getEditIncomeData', function (e) {
                id = $(this).data('id');
                window.location.href = 'incomes/' + id + '/edit';
            });

            // Update article Ajax request.
            $('#SubmitEditIncomeForm').click(function (e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "incomes/" + id,
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
                            $('#EditIncomeModal').hide();
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
                    url: "incomes/" + id,
                    method: 'DELETE',
                    success: function (result) {
                        $('.datatable').DataTable().ajax.reload();
                        $('#DeleteIncomeModal').hide();
                    }
                });
            });
        });
    </script>
@endsection
