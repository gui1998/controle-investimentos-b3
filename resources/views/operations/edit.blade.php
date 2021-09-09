@extends('layouts.admin')

@section('style')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Page Heading -->
                <h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">Editar Operação</h1>
                <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <form action="{{route('operations.update', $operation->id)}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            <label for="operation_date" class="font-weight-bold">Data de
                                                Pagamento:</label>
                                            <input type="text"
                                                   class="form-control date @error('operation_date') is-invalid @enderror"
                                                   name="operation_date"
                                                   value="{{ old('operation_date', \Carbon\Carbon::parse($operation->operation_date)->format('d/m/Y')) }}">
                                        </div>
                                        <div class="col">
                                            <label for="stock_amount">Quantidade de Ações:</label>
                                            <input type="text"
                                                   class="form-control positiveNumber @error('stock_amount') is-invalid @enderror"
                                            name="stock_amount"
                                            id="stock_amount"
                                            value="{{ old('stock_amount', $operation->stock_amount) }}"
                                            required>

                                        </div>
                                        <div class="col">
                                            <label for="price" class="font-weight-bold">Desconto:</label>
                                            <input type="text"
                                                   class="form-control money @error('price') is-invalid @enderror"
                                                   name="price"
                                                   value="{{ old('price', $operation->price) }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="cost" class="font-weight-bold">Custo:</label>
                                            <input type="text"
                                                   class="form-control money @error('cost') is-invalid @enderror"
                                                   name="cost"
                                                   value="{{ old('cost', $operation->cost) }}">
                                        </div>
                                        <div class="col">
                                            <label for="irrf" class="font-weight-bold">Irrf:</label>
                                            <input type="text"
                                                   class="form-control money @error('irrf') is-invalid @enderror"
                                                   name="irrf"
                                                   value="{{ old('irrf', $operation->irrf) }}">
                                        </div>
                                        <div class="col">
                                            <label for="buy_r_sell">Compra ou Venda:</label>
                                            <select name="buy_r_sell" id="buy_r_sell" class="form-control" required>
                                                <option value="C" {{ $operation->buy_r_sell == 'C' ? 'selected' : '' }}>
                                                    Compra
                                                </option>
                                                <option value="V" {{ $operation->buy_r_sell == 'V' ? 'selected' : '' }}>
                                                    Venda
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="broker_id">Tipo da Operação:</label>
                                            <select name="broker_id" id="broker_id"
                                                    class="form-control select2"
                                                    required>
                                                @foreach($brokers as $broker)
                                                    <option
                                                        value="{{ $broker->id }}" {{ $broker->id == $operation->brokers->id ? 'selected' : '' }}>{{ $broker->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="operation_type_id">Tipo da Operação:</label>
                                            <select name="operation_type_id" id="operation_type_id"
                                                    class="form-control select2"
                                                    required>
                                                @foreach($operation_types as $types)
                                                    <option
                                                        value="{{ $types->id }}" {{ $types->id == $operation->operationTypes->id ? 'selected' : '' }}>{{ $types->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="stock_id">Ação:</label>
                                            <select name="stock_id" id="stock_id" class="form-control select2" required>
                                                @foreach($stocks as $stock)
                                                    <option
                                                        value="{{ $stock->id }}" {{ $stock->id == $operation->stocks->id ? 'selected' : '' }}>{{ $stock->code }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @if($errors->any())
                                    @foreach($errors->all(':message') as $message)
                                        <div class="alert alert-danger mt-2">
                                            {{$message}}
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div style="text-align: right;">
                                <button type="submit" class="btn btn-success btn-sm">Atualizar</button>
                                <button type="button" class="btn btn-danger btn-sm goBack">Voltar</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('body').on('click', '.goBack', function (e) {
                window.location.href = '../../operations';
            });
        });
    </script>
@endsection
