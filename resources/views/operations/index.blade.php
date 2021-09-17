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
                            {{ __('Operação Listagem') }}
                            <button style="float: right; font-weight: 900;" class="btn btn-info btn-sm" type="button"
                                    id="getCreateOperationModal">
                                Criar Operação
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered datatable">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Compra ou Venda</th>
                                    <th>Ativo</th>
                                    <th>Data da Operação</th>
                                    <th>Quantidade de Ativos</th>
                                    <th>Custo</th>
                                    <th>Irrf</th>
                                    <th>Valor Bruto</th>
                                    <th>Valor Líquido Total</th>
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
    <!-- Criar Operação Modal -->
    <div class="modal" id="CreateOperationModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Operação Criar</h4>
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
                        <strong>Success!</strong>Operação Foi adicionado(a) com sucesso.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
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
                                <label for="stock_amount">Quantidade de Ativos:</label>
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
                                <select name="buy_r_sell" id="buy_r_sell" class="form-control select2-static" required>
                                    <option value="B">Compra</option>
                                    <option value="S">Venda</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="stock">Ativo:</label>
                                <select name="stock" id="stock" class="form-control select2" required>
                                </select>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="SubmitCreateOperationForm">Criar</button>
                            <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
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
                    {data: 'buy_r_sell'},
                    {data: 'stock'},
                    {data: 'operation_date'},
                    {data: 'stock_amount'},
                    {data: 'cost'},
                    {data: 'irrf'},
                    {data: 'price'},
                    {data: 'net_value'}
                ]
            });

            $('body').on('click', '#getCreateOperationModal', function (e) {

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
                $.ajax({
                    url: '{{route('list-brokers')}}', success: function (result) {
                        if (!result.length) {
                            alert("Necessário cadastrar uma corretora!");
                            window.location.href = '../brokers';
                        }
                        result.forEach(function (e, i) {
                            $('#broker').append($('<option></option>').val(e.id).text(e.name));
                        });
                    }
                });
                $('#CreateOperationModal').show();
            });

            // Criar article Ajax request.
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
        });
    </script>
@endsection
