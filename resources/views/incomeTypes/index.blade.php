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
              {{ __('Tipos de Rendimentos') }}
              <button style="float: right; font-weight: 900;" class="btn btn-info btn-sm" type="button"
                      id="getCreateIncomeTypeModal">
                Criar Tipo de Rendimento
              </button>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered datatable">
                <thead>
                <tr>
                  <th>Id</th>
                  <th>Nome</th>
                  <th width="150" class="text-center">Ação</th>
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
  <!-- Criar Tipo de Rendimento Modal -->
  <div class="modal" id="CreateIncomeTypeModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Criar Tipo de Rendimento</h4>
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
            <strong>Success!</strong>Tipo de Rendimento Foi adicionado(a) com sucesso.
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
          <button type="button" class="btn btn-success" id="SubmitCreateIncomeTypeForm">Criar</button>
          <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Tipo de Rendimento Modal -->
  <div class="modal" id="EditIncomeTypeModal">
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
            <strong>Success!</strong>Tipo de Rendimento Foi adicionado(a) com sucesso.
            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div id="EditIncomeTypeModalBody">

          </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="SubmitEditIncomeTypeForm">Update</button>
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
        ajax: '{{ route('get-incomeTypes') }}',
        columns: [
          {data: 'id', Nome: 'id'},
          {data: 'name', Nome: 'name'},
          {data: 'Actions', Nome: 'Actions', orderable: false, serachable: false, sClass: 'text-center'},
        ],
        "order": [[ 1, "asc" ]]
      });

      $('body').on('click', '#getCreateIncomeTypeModal', function (e) {
        $('#CreateIncomeTypeModal').show();
        $('#CreateIncomeTypeModal').modal({
          keyboard: false,
          show: true
        });
        // Jquery draggable
        $('#CreateIncomeTypeModal').draggable({
          handle: ".modal-header"
        });
      });

      $('body').on('click', '.modelClose', function (e) {
        $('div.modal').hide();
      });

      // Criar income type Ajax request.
      $('#SubmitCreateIncomeTypeForm').click(function (e) {
        e.preventDefault();
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          url: "{{ route('incomeTypes.store') }}",
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
              $('#CreateIncomeTypeModal').modal('hide');
              location.reload();
            }
          }
        });
      });

      var id;
      $('body').on('click', '#getEditIncomeTypeData', function (e) {
        id = $(this).data('id');
        window.location.href = 'incomeTypes/' + id + '/edit';
      });

      // Delete income type Ajax request.
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
          url: "incomeTypes/" + id,
          method: 'DELETE',
          success: function (result) {
            $('.datatable').DataTable().ajax.reload();
            $('#DeleteIncomeTypeModal').hide();
          }
        });
      });
    });
  </script>
@endsection
