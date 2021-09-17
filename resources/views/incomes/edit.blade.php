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
                <h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">Editar Rendimento</h1>
                <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <form action="{{route('incomes.update', $income->id)}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            <label for="payment_date" class="font-weight-bold">Data de
                                                Pagamento:</label>
                                            <input type="text"
                                                   class="form-control date @error('payment_date') is-invalid @enderror"
                                                   name="payment_date"
                                                   value="{{ old('payment_date', \Carbon\Carbon::parse($income->payment_date)->format('d/m/Y')) }}">
                                        </div>
                                        <div class="col">
                                            <label for="stock_amount">Quantidade de Ativos:</label>
                                            <input type="text"
                                                   class="form-control positiveNumber @error('stock_amount') is-invalid @enderror"  name="stock_amount"
                                                   id="stock_amount"
                                                   value="{{ old('stock_amount', $income->stock_amount) }}"
                                                   required>

                                        </div>
                                        <div class="col">
                                            <label for="discount" class="font-weight-bold">Desconto:</label>
                                            <input type="text"
                                                   class="form-control money @error('discount') is-invalid @enderror"
                                                   name="discount"
                                                   value="{{ old('discount', $income->discount) }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <label for="total" class="font-weight-bold">Valor Bruto:</label>
                                            <input type="text"
                                                   class="form-control money @error('total') is-invalid @enderror"
                                                   name="total"
                                                   value="{{ old('total', $income->total) }}">
                                        </div>
                                        <div class="col">
                                            <label for="income_type_id">Tipo:</label>
                                            <select name="income_type_id" id="income_type_id" class="form-control select2"
                                                    required>
                                                @foreach($income_types as $types)
                                                    <option
                                                        value="{{ $types->id }}" {{ $types->id == $income->incomeTypes->id ? 'selected' : '' }}>{{ $types->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col">
                                            <label for="stock_id">Ativo:</label>
                                            <select name="stock_id" id="stock_id" class="form-control select2" required>
                                                @foreach($stocks as $stock)
                                                    <option
                                                        value="{{ $stock->id }}" {{ $stock->id == $income->stocks->id ? 'selected' : '' }}>{{ $stock->code }}</option>
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
                window.location.href = '../../incomes';
            });
        });
    </script>
@endsection
