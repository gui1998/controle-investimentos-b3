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
                            {{ __('Tipos de Ativos') }}
                            <button style="float: right; font-weight: 900;" class="btn btn-info btn-sm" type="button"
                                    id="getCreateStockTypeModal">
                                Criar Tipo de Ativo
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
    <!-- Criar Tipo de Ativo Modal -->
    <div class="modal" id="CreateStockTypeModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Criar Tipo de Ativo</h4>
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
                        <strong>Success!</strong>Tipo de Ativo Foi adicionado(a) com sucesso.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" class="form-control" name="name" id="name">
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="SubmitCreateStockTypeForm">Criar</button>
                    <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Tipo de Ativo Modal -->
    <div class="modal" id="EditStockTypeModal">
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
                        <strong>Success!</strong>Tipo de Ativo Foi adicionado(a) com sucesso.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="EditStockTypeModalBody">

                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="SubmitEditStockTypeForm">Update</button>
                    <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Setor Modal -->
    <div class="modal" id="DeleteStockTypeModal">
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
                        <strong>Success!</strong>Tipo de Ativo Foi adicionado(a) com sucesso.
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
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
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
                ajax: '{{ route('get-stockTypes') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'Actions', name: 'Actions', orderable: false, serachable: false, sClass: 'text-center'},
                ]
            });

            $('body').on('click', '#getCreateStockTypeModal', function (e) {
                $('#CreateStockTypeModal').show();
                $('#CreateStockTypeModal').modal({
                    keyboard: false,
                    show: true
                });
                // Jquery draggable
                $('#CreateStockTypeModal').draggable({
                    handle: ".modal-header"
                });
            });

            $('body').on('click', '.modelClose', function (e) {
                $('div.modal').hide();
            });

            // Criar article Ajax request.
            $('#SubmitCreateStockTypeForm').click(function (e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('stockTypes.store') }}",
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
                            $('#CreateStockTypeModal').modal('hide');
                            location.reload();
                        }
                    }
                });
            });

            //Call edit page
            var id;
            $('body').on('click', '#getEditStockTypeData', function (e) {
                id = $(this).data('id');
                window.location.href  = 'stockTypes/' + id + '/edit';
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
                    url: "stockTypes/" + id,
                    method: 'DELETE',
                    success: function (result) {
                        if (result.errors) {
                            $('#DeleteStockTypeModal').show();
                            $('.alert-danger').html('');
                            $('.alert-danger').show();
                            $('.alert-danger').append('<strong><li>' + result.errors + '</li></strong>');
                        } else {
                            $('.datatable').DataTable().ajax.reload();
                            $('#DeleteStockTypeModal').hide();
                        }
                    }
                });
            });
        });
    </script>
@endsection
